<?php
namespace Madfox\WebCrawler\Url;

use Buzz\Exception\InvalidArgumentException;

class Url extends \Buzz\Util\Url
{
    const URL_REGEXP = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    /**
     * @param string $url
     */
    public function __construct($url)
    {
         if (!preg_match(self::URL_REGEXP, $url)) {
             throw new InvalidArgumentException(sprintf('The URL "%s" is invalid.', $url));
         }

         parent::__construct($url);
    }

    /**
     * @param string $url
     * @return Url
     */
    public function build($url)
    {
        $components = parse_url($url);
        $url = "";

        foreach ([
            'scheme' => 'getScheme',
            'host'   => 'getHostname',
            'port'   => 'getPort',
            'user'   => 'getUser',
            'password' => 'getPassword',
            'path'     => 'getPath',
            'query'    => 'getQueryString',
            'fragment' => 'getFragment'] as $k => $m) {
                 $p = $this->$m();
                 if (empty($components[$k]) && !empty($p)) {
                     $components[$k] = $p;
                 }
        }

        $url .= $components['scheme'] ? $components['scheme'] : "http";
        $url .= "://";

        if (!empty($components['user']) && !empty($components['password'])) {
            $url .= $components['user'] . ":" . $components['password'] . "@";
        } else if (!empty($user) && empty($password)) {
            $url .= $components['user'] . "@";
        }

        $url .= $components['host'];

        if (!empty($components['port'])) {
            $url .= ":" . $components['port'];
        }

        if (!empty($components['path'])) {
            $url .= $components['path'];
        }

        if (!empty($components['query'])) {
            $url .= "?" . $components['query'];
        }

        if (!empty($components['fragment'])) {
            $url .= "#" . $components['fragment'];
        }

        return new Url($url);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $scheme = $this->getScheme() ? $this->getScheme() : "http";
        $hostname = $this->getHostname();
        $user = $this->getUser();
        $password = $this->getPassword();
        $port = $this->format('o');
        $path = $this->format("p");
        $query = $this->getQueryString();
        $fragment = $this->getFragment();
        $url = "";

        $url .= $scheme ."://";

        if (!empty($user) && !empty($password)) {
            $url .= $user . ":" . $password . "@";
        } else if (!empty($user) && empty($password)) {
            $url .= $user . "@";
        }

        $url .= $hostname;

        if (!empty($port)) {
            $url .= ":" . $port;
        }

        if (!empty($path)) {
            $url .= $path;
        }

        if (!empty($query)) {
            $url .= "?" . $query;
        }

        if (!empty($fragment)) {
            $url .= "#" . $fragment;
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return md5($this->toString());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}