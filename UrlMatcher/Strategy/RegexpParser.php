<?php
namespace Madfox\WebCrawler\UrlMatcher\Strategy;

use Madfox\WebCrawler\UrlMatcher\ParserInterface;

class RegexpParser implements ParserInterface
{
    const REGEXP = '/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>.*<\/a>/siU';
    /**
     * @param string $html
     * @return array
     */
    public function parse($html)
    {
        preg_match_all(self::REGEXP, $html, $match);
        $return = isset($match[2]) ? $match[2] : [];

        return $return;
    }
}