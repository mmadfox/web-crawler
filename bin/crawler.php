#!/usr/bin/env php
<?php
require_once '../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Madfox\WebCrawler\Crawler;
use PhpAmqpLib\Connection\AMQPConnection;
$console = new Application();

$console
    ->register('run')
    ->setCode(function (InputInterface $input, OutputInterface $output) {

          $output->writeln("Run....");
          $crawler = new Crawler();
          $crawler->setQueue(new \Madfox\WebCrawler\Queue\Queue(new \Madfox\WebCrawler\Queue\Adapter\PhpAMQPAdapter(
              'foodbook.guru',
              5672,
              'foodbook',
              'rabbitmq',
              '/'
          )));

          $crawler->site("http://ulkotours.com/");
          $crawler->site("http://www.visitsaintpetersburg.com/");
          $crawler->run();
    });

$console->run();