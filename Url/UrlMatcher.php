<?php
namespace Madfox\WebCrawler\Url;

use Madfox\WebCrawler\Url\Matcher\Cursor;
use Madfox\WebCrawler\Url\Matcher\ParserInterface;
use Madfox\WebCrawler\Url\Matcher\Strategy\RegexpParser;
use Madfox\WebCrawler\Url\Utils\UrlUtil;

class UrlMatcher
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
     * @param string $html
     * @return \Madfox\WebCrawler\Url\Matcher\Cursor
     */
    public function match(Url $url, $html)
    {
        $links = (array)$this->parse($html);
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