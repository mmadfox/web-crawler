<?php
namespace Madfox\WebCrawler\Url;

class UrlUtil
{
    /**
     * @var array
     */
    public static $schemas = [
        'ftp',
        'http',
        'rtmp',
        'rtsp',
        'https',
        'gopher',
        'mailto',
        'news',
        'nntp',
        'irc',
        'prospero',
        'telnet',
        'wais',
        'xmpp',
        'file',
        'file',
        'tel',
        'afs',
        'cid',
        'mid',
        'mailserver',
        'nfs',
        'skype',
        'smsto',
        'ed2k',
        'market',
        'steam',
        'bitcoin',
        'javascript'
    ];

    /**
     * @param string $url
     * @return string
     */
    public static function detectSchema($url)
    {
        //TODO!!!
        /*$url = trim((string) $url);
        $schema = substr($url, 0, strpos($url, ":"));

        $detectSchema = end(array_filter(self::$schemas, function($s) use($schema) {
             return $s == $schema;
        }));*/

        return "http";
    }

    /**
     * @param array $components
     * @return string
     */
    public static function buildUrl(array $components = [])
    {
        return ((isset($components['scheme']) && !empty($components['scheme'])) ? $components['scheme'] . '://' : '')
        .((isset($components['user']) && !empty($components['user']) ) ? $components['user'] . ((isset($components['pass'])) ? ':' . $components['pass'] : '') .'@' : '')
        .((isset($components['host']) && !empty($components['host'])) ? $components['host'] : '')
        .((isset($components['port']) && !empty($components['port'])) ? ':' . $components['port'] : '')
        .((isset($components['path']) && !empty($components['path'])) ? '/' .ltrim($components['path'], "/") : '')
        .((isset($components['query']) && !empty($components['query'])) ? '?' . $components['query'] : '')
        .((isset($components['fragment']) && !empty($components['fragment'])) ? '#' . $components['fragment'] : '');
    }

    /**
     * @param string $url
     * @return string
     */
    public static function normalizeURL($url)
    {
        $newUrl = "";
        //TODO !!!
        return $url;

        if (strpos($url, "://") === 0) {
            $url = str_replace("://", "/", $url);
        }

        $url = parse_url($url);
        $defaultSchemes = array("http" => 80, "https" => 443);
        if(isset($url['scheme']))
        {
            $url['scheme'] = strtolower($url['scheme']);
            // Strip scheme default ports
            if(isset($defaultSchemes[$url['scheme']]) && isset($url['port']) && $defaultSchemes[$url['scheme']] == $url['port'])
                unset($url['port']);
            $newUrl .= "{$url['scheme']}://";
        }
        if(isset($url['host']))
        {
            //$url['host'] = strtolower($url['host']);
            // Seems like a valid domain, properly validation should be made in higher layers.
            if(preg_match("/[a-z]+\Z/", $url['host']))
            {
                if(preg_match("/^www\./", $url['host']) && gethostbyname($url['host']) == gethostbyname(str_replace("www.", "", $url['host'])))
                    $newUrl .= str_replace("www.", "", $url['host']);
                else
                    $newUrl .= $url['host'];
            }
            else
                $newUrl .= $url['host'];
        }
        if(isset($url['port']))
            $newUrl .= ":{$url['port']}";
        if(isset($url['path']))
        {
            // Case normalization
            $url['path'] = preg_replace('/(%([0-9abcdef][0-9abcdef]))/ex', "'%'.strtoupper('\\2')", $url['path']);
            //Strip duplicate slashes
            while(preg_match("/\/\//", $url['path']))
                $url['path'] = preg_replace("/\/\//", "/", $url['path']);
            /*
             * Decode unreserved characters, http://www.apps.ietf.org/rfc/rfc3986.html#sec-2.3
             * Heavily rewritten version of urlDecodeUnreservedChars() in Glen Scott's url-normalizer.
             */
            $u = array();
            for ($o = 65; $o <= 90; $o++)
                $u[] = dechex($o);
            for ($o = 97; $o <= 122; $o++)
                $u[] = dechex($o);
            for ($o = 48; $o <= 57; $o++)
                $u[] = dechex($o);
            $chrs = array('-', '.', '_', '~');
            foreach($chrs as $chr)
                $u[] = dechex(ord($chr));
            $url['path'] = preg_replace_callback(array_map(create_function('$str', 'return "/%" . strtoupper($str) . "/x";'), $u),
                create_function('$matches', 'return chr(hexdec($matches[0]));'), $url['path']);
            // Remove directory index
            $defaultIndexes = array("/default\.aspx/" => "default.aspx", "/default\.asp/"  => "default.asp",
                "/index\.html/"   => "index.html",   "/index\.htm/"    => "index.htm",
                "/default\.html/" => "default.html", "/default\.htm/"  => "default.htm",
                "/index\.php/"    => "index.php",    "/index\.jsp/"    => "index.jsp");
            foreach($defaultIndexes as $index => $strip)
            {
                if(preg_match($index, $url['path']))
                    $url['path'] = str_replace($strip, "", $url['path']);
            }

            /**
             * Path segment normalization, http://www.apps.ietf.org/rfc/rfc3986.html#sec-5.2.4
             * Heavily rewritten version of removeDotSegments() in Glen Scott's url-normalizer.
             */

            $new_path = '';
            while(!empty($url['path']))
            {
                if(preg_match('!^(\.\./|\./)!x', $url['path']))
                    $url['path'] = preg_replace('!^(\.\./|\./)!x', '', $url['path']);
                elseif(preg_match('!^(/\./)!x', $url['path'], $matches) || preg_match('!^(/\.)$!x', $url['path'], $matches))
                    $url['path'] = preg_replace("!^" . $matches[1] . "!", '/', $url['path']);
                elseif(preg_match('!^(/\.\./|/\.\.)!x', $url['path'], $matches))
                {
                    $url['path'] = preg_replace( '!^' . preg_quote( $matches[1], '!' ) . '!x', '/', $url['path'] );
                    $new_path = preg_replace( '!/([^/]+)$!x', '', $new_path );
                }
                elseif(preg_match('!^(\.|\.\.)$!x', $url['path']))
                    $url['path'] = preg_replace('!^(\.|\.\.)$!x', '', $url['path']);
                else
                {
                    if(preg_match('!(/*[^/]*)!x', $url['path'], $matches))
                    {
                        $first_path_segment = $matches[1];
                        $url['path'] = preg_replace( '/^' . preg_quote( $first_path_segment, '/' ) . '/', '', $url['path'], 1 );
                        $new_path .= $first_path_segment;
                    }
                }
            }
            $newUrl .= $new_path;
        }

        if(isset($url['fragment']))
            unset($url['fragment']);

        // Sort GET params alphabetically, not because the RFC requires it but because it's cool!
        if(isset($url['query']))
        {
            if(preg_match("/&/", $url['query']))
            {
                $s = explode("&", $url['query']);
                $url['query'] = "";
                sort($s);
                foreach($s as $z)
                    $url['query'] .= "{$z}&";
                $url['query'] = preg_replace("/&\Z/", "", $url['query']);
            }
            $newUrl .= "?{$url['query']}";
        }
        return $newUrl;
    }

}