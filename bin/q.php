<?php
require_once '../vendor/autoload.php';

$q = new \Madfox\WebCrawler\Queue\Queue(new \Madfox\WebCrawler\Queue\Adapter\IPCMessageAdapter());
for ($i = 0; $i < 100; $i++) {
    $q->enqueue(new \Madfox\WebCrawler\Url\Url("http://google.com"));
}