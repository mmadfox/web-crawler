<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\Url;

class MergeTwoInstance implements StrategyInterface
{
    public function valid($url1, $url2)
    {
        return $url1 instanceof Url
            && $url2 instanceof Url;
    }

    public function factory($url1, $url2)
    {

    }
} 