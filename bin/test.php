<?php
require_once '../vendor/autoload.php';

use Madfox\WebCrawler\Url\Url;

$indexer = new \Madfox\WebCrawler\Indexer\Indexer('default', new \Madfox\WebCrawler\Indexer\Storage\Memory());
$queueFactory = new \Madfox\WebCrawler\Queue\QueueFactory();
$queue = $queueFactory->create();
$urlMatcher = new \Madfox\WebCrawler\UrlMatcher\UrlMatcher();
$httpClient = new \Madfox\WebCrawler\Http\Client();
$url = new Url('http://www.edimdoma.ru/retsepty');
$site = new \Madfox\WebCrawler\Site($url, $queue, $indexer, $urlMatcher, $httpClient);
$iterator = $site->getIterator();

foreach ($iterator as $page) {
    echo $page->url() . "\n";
}
