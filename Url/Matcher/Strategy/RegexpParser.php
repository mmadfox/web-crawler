<?php
namespace Madfox\WebCrawler\Url\Matcher\Strategy;

use Madfox\WebCrawler\Url\Matcher\ParserInterface;

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
        $return = [];

        if ($match && isset($match[2])) {
            foreach ($match[2] as $link) {
                if (!in_array($link, $return)) {
                    array_push($return, $link);
                }
            }
        }

        return $return;
    }
}