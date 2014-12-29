<?php
namespace Madfox\WebCrawler\Url\Matcher;

interface ParserInterface
{
    /**
     * @param string $html
     * @return array
     */
    public function parse($html);

}