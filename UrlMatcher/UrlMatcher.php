<?php
namespace Madfox\WebCrawler\UrlMatcher;

use Madfox\WebCrawler\UrlMatcher\Cursor;
use Madfox\WebCrawler\UrlMatcher\ParserInterface;
use Madfox\WebCrawler\Url\UrlUtil;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\UrlMatcher\Strategy\RegexpParser;

class UrlMatcher implements UrlMatcherInterface
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var \Madfox\WebCrawler\Url\Url;
     */
    public function __construct(ParserInterface $parser = null)
    {
        $this->parser = is_null($parser) ? new RegexpParser() : $parser;
    }

    /**
     * @param Url $url
     * @param string $content
     * @return \Madfox\WebCrawler\UrlMatcher\Cursor
     */
    public function match(Url $url, $content)
    {
        $links = (array)$this->parse($content);
        $filtered = [];

        foreach ($links as $link) {
            if ($this->skipEmpty($link)
             || $this->skipFragment($link)
             || $this->skipInvalidScheme($link)
             || $this->skipSuffixes($link)
            ) {
                continue;
            }

            $link = $this->filterPath($link);
            $link = $this->filterFragment($link);

            if (!in_array($link, $filtered)) {
                array_push($filtered, $link);
            }
        }

        return new Cursor($url, $filtered);
    }

    /**
     * @param string $link
     * @return string|null
     */
    protected function filterPath($link)
    {
        $scheme = UrlUtil::detectSchema($link);

        if (empty($scheme)) {
            $link = "/" . ltrim($link, "/");
        }

        return $link;
    }

    protected function filterFragment($link)
    {
        $pos = strrpos($link, "#");

        if ($pos !== false) {
            $offset = strlen($link) - $pos;
            $link = substr($link, 0, -$offset);
        }

        return $link;
    }

    /**
     * @param string $link
     * @return bool
     */
    protected function skipSuffixes($link)
    {
        if (preg_match('/\.(pdf|gif|GIF|jpg|JPG|png|PNG|ico|ICO|css|CSS|sit|SIT|eps|EPS|wmf|WMF|zip|ZIP|ppt|PPT|mpg|MPG|xls|XLS|gz|GZ|rpm|RPM|tgz|TGZ|mov|MOV|exe|EXE|jpeg|JPEG|bmp|BMP|js|JS)(\?|&)*/', $link)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $link
     * @return bool
     */
    protected function skipFragment($link)
    {
        return strpos("#", $link) === 0;
    }

    /**
     * @param string $link
     * @return bool
     */
    protected function skipEmpty($link)
    {
        return empty($link);
    }

    /**
     * @param string $link
     * @return bool
     */
    protected function skipInvalidScheme($link)
    {
        $scheme = UrlUtil::detectSchema($link);

        if  (!empty($scheme) && $scheme != 'http' && $scheme != 'https') {
            return true;
        } else if (strpos($link, "//") === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param $html
     * @return array
     */
    protected function parse($html)
    {
        return $this->parser->parse($html);
    }
}