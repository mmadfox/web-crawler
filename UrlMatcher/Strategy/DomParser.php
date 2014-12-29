<?php
namespace Madfox\WebCrawler\UrlMatcher\Strategy;

use Madfox\WebCrawler\UrlMatcher\ParserInterface;

class DomParser implements ParserInterface
{
    /**
     * @param string $html
     * @return array
     */
    public function parse($html)
    {
        return [];
    }
}