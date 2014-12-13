<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

interface StrategyInterface
{
    public function valid($url1, $url2);
    public function build($url1, $url2);
}