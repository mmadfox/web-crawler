<?php
use \Madfox\WebCrawler\Crawler;

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    private $crawler;

    public function setUp()
    {
        $this->crawler = new Crawler();
    }

    public function testAddSite()
    {
        $crawler = $this->crawler;

        $foodbook  = $crawler->site("http://site1.com");
        $foodbook->ifpath("")->exec(function () {});
        $foodbook->ifpath("")->exec(function () {});
        $foodbook->ifpath("")->exec(function () {});

        $crawler->run();
    }
}
 