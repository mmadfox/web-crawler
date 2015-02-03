<?php
require_once '../vendor/autoload.php';

$qadapter = new \Madfox\WebCrawler\Queue\Adapter\AMQPAdapter("mq://quest@quest@foodbook.guru:5672/");
$qadapter->addChannel('test');

var_dump($qadapter);












