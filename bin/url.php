<?php
require_once '../vendor/autoload.php';

$matcher = new \Madfox\WebCrawler\Url\UrlMatcher();

$url = new \Madfox\WebCrawler\Url\Url("http://www.edimdoma.ru/");
$html = file_get_contents((string) $url);

$cursor = $matcher->match($url, $html);

foreach ($cursor as $link) {
    if ($link->equalHost($url)) {
        echo $link->resource() . "\n";
    }
}












