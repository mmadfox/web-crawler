<?php

use Madfox\WebCrawler\Url\Url;
use \Madfox\WebCrawler\Page\Page;

class PageTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Url
     */
   private $url;

   public function setUp()
   {
        $this->url = new Url("http://google.com");
   }

   public function testCreatePageWithoutContentAndLinks()
   {
        $page = new Page($this->url);
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Page\\Page", $page);
   }

   public function testCreatePageWithContent()
   {
       $content = "<h1>test content</h1>";
       $page = new Page($this->url, [], $content);
       $this->assertEquals($content, $page->content());
   }

   public function testCreatePageWithLinks()
   {
       $links = array_map(function ($link) {
           return new Url($link);
       }, ['http://domain.com', 'http://domain1.com']);

       $page = new Page($this->url, $links);
       $this->assertEquals($links, $page->links());
   }

   public function testSetId()
   {
       $page = new Page($this->url);
       $page->setId(123);
       $this->assertEquals(123, $page->id());
   }

   public function testSerializedPage()
   {
       $links = array_map(function ($link) {
           return new Url($link);
       }, ['http://domain.com', 'http://domain1.com']);
       $content = "<h1>test content</h1>";
       $page1 = new Page($this->url, $links, $content);
       $ser = serialize($page1);
       $page2 = unserialize($ser);
       $this->assertEquals($page1, $page2);

   }
}
 