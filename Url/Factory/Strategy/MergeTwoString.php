<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\Url;

class MergeTwoString implements StrategyInterface
{
    public function valid($url1, $url2)
    {
        return is_string($url1) && is_string($url2);
    }

    public function factory($url1, $url2)
    {

    }
}
