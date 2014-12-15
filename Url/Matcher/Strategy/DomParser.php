<?php
namespace Madfox\WebCrawler\Url\Matcher\Strategy;

use Madfox\WebCrawler\Url\Matcher\ParserInterface;

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