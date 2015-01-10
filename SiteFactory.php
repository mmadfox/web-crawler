<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Http\Client;
use Madfox\WebCrawler\Index\IndexFactory;
use Madfox\WebCrawler\Page\PageManager;
use Madfox\WebCrawler\Queue\QueueFactory;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\UrlMatcher\UrlMatcher;

class SiteFactory
{
    public function create($url)
    {
        if (is_string($url)) {
            $url = new Url($url);
        }

        $indexFactory = new IndexFactory();
        $client = new Client();
        $urlMatcher = new UrlMatcher();
        $pageManager = new PageManager($indexFactory->create(), $client, $urlMatcher);
        $queueFactory = new QueueFactory();
        $queue = $queueFactory->create();
        $site = new Site($url, $queue, $pageManager);
        return $site;
    }
}