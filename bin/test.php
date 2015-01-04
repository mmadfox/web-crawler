<?php
require_once '../vendor/autoload.php';

$siteFactory = new \Madfox\WebCrawler\SiteFactory();
$site = $siteFactory->create('http://foodbook.guru/');

foreach ($site as $page) {
    echo "Count => " . count($site->getUrlsInQueue()) . " ";
    echo "IsEmpty? " . ($page->isEmpty() ? "TRUE" : "FALSE");
    echo "Page  => " . $page->url() . "\n";

}

echo "Count => " . count($site->getUrlsInQueue()) . " ";


