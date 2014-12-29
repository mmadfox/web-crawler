<?php

use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Page\PageManager;
use Madfox\WebCrawler\Site;

class PageIteratorTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Site
     */
   private $site;

   public function setUp()
   {
       $qf = new \Madfox\WebCrawler\Queue\QueueFactory();
       $indexFactory = new \Madfox\WebCrawler\Index\IndexFactory();
       $index = $indexFactory->create();
       $httpClient = new \Madfox\WebCrawler\Http\Client();
       $urlMatcher = new \Madfox\WebCrawler\UrlMatcher\UrlMatcher();
       $pageManager = new PageManager($index, $httpClient, $urlMatcher);
       $this->site = new \Madfox\WebCrawler\Site(new Url("http://google.com"), $qf->create(), $pageManager);
   }

   public function testEmpty()
   {

   }
}
 