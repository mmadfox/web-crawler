<?php
require_once '../vendor/autoload.php';

use Madfox\WebCrawler\Crawler;
use Madfox\WebCrawler\Configure;

$crawler = new Crawler();
$crawler->configure(new Configure(__DIR__ . "/res/site1.yml"));

$crawler->run();