<?php
namespace Madfox\WebCrawler\UrlMatcher;

use Madfox\WebCrawler\Url\Url;

interface UrlMatcherInterface
{
    /**
     * @param Url $url
     * @param string $content
     * @return mixed
     */
    public function match(Url $url, $content);
}