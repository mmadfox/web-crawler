<?php

use Madfox\WebCrawler\Site;
use Madfox\WebCrawler\Url\Url;

class PageIteratorTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Site
     */
   private $site;

   public function setUp()
   {
       $this->site = \Madfox\WebCrawler\SiteFactory::create("http://google.com");
   }

   public function testCreateIterator()
   {
       $iterator = $this->site->getIterator();
       $this->assertInstanceOf("\\Madfox\\WebCrawler\\Page\\PageIterator", $iterator);
   }

   public function testValidState()
   {
       $iterator = $this->site->getIterator();
       $state = $iterator->valid();
       $this->assertTrue($state);
   }

   public function testCurrentState()
   {
       $iterator = $this->site->getIterator();
       $page = $iterator->current();
       $this->assertInstanceOf("\\Madfox\\WebCrawler\\Page\\Page", $page);
   }

   private function addUrlToQueue($url)
   {
       $this->site->getQueue()->enqueue(new Url($url), $this->site->hostname());
   }
}
 