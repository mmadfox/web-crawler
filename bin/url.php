<?php
require_once '../vendor/autoload.php';

$matcher = new \Madfox\WebCrawler\Url\UrlMatcher();

$url = new \Madfox\WebCrawler\Url\Url("http://www.edimdoma.ru/retsepty/popular/otvarnie-bluda-iz-testa-oladi");
$html = file_get_contents((string) $url);

$cursor = $matcher->match($url, $html);
$total = 0;

foreach ($cursor as $link) {
    if ($link->host() == "edimdoma.ru" || $link == "www.edimdoma.ru") {
        $total += 1;
        echo $link . "\n";
    }
}

echo "\n";
echo "Founded links = {$total} ";












