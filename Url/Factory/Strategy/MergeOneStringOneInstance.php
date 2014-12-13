<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\Url;

class MergeOneStringOneInstance implements StrategyInterface
{
    public function valid($url1, $url2)
    {
        return (is_string($url1) && $url2 instanceof Url)
        || ($url2 instanceof Url && is_string($url1));
    }

    public function factory($url1, $url2)
    {

    }
}
