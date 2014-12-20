<?php
require_once '../vendor/autoload.php';

$routeCollection = new \Madfox\WebCrawler\Routing\RouteCollection();


$startTime = microtime(true);

$matcher = new \Madfox\WebCrawler\Url\UrlMatcher();
$url = new \Madfox\WebCrawler\Url\Url("http://ulkotours.com/ASTA");
$html = file_get_contents((string) $url);

$cursor = $matcher->match($url, $html);
$total = 0;

foreach ($cursor as $link) {
    if ($link->equalHost($url)) {
        $total += 1;
        echo $link . "\n";
    }
}

echo "\n";
echo "Founded links = {$total} ";
echo "\n\n";
$endTime = microtime(true);
echo sprintf("Sec %s", substr($endTime - $startTime, 0,4));












