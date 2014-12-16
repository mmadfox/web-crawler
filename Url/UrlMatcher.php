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
        $links = (array) $this->parse($html);
        $filtered = [];

        foreach ($links as $link) {
            $scheme = UrlUtil::detectSchema($link);

            if (empty($scheme) || $scheme == 'http' || $scheme == 'https') {
                $filtered[] = $link;
            }
        }

        return new Cursor($url, $filtered);
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