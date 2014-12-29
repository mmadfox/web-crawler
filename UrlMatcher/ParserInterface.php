<?php
namespace Madfox\WebCrawler\UrlMatcher;

interface ParserInterface
{
    /**
     * @param string $html
     * @return array
     */
    public function parse($html);

}