<?php

use \Madfox\WebCrawler\Page\PageManager;
use Madfox\WebCrawler\Url\Url;

class PageManagerTest extends PHPUnit_Framework_TestCase {
    /**
     * @var PageManager
     */
    private $pageManager;

    public function setUp()
    {
        $indexFactory = new \Madfox\WebCrawler\Index\IndexFactory();
        $index = $indexFactory->create();
        $httpClient = new \Madfox\WebCrawler\Http\Client();
        $urlMatcher = new \Madfox\WebCrawler\UrlMatcher\UrlMatcher();
        $this->pageManager = new PageManager($index, $httpClient, $urlMatcher);

    }

    public function testCreatePage()
    {
        $url = new Url("http://phpcrawl.cuab.de/");
        $page = $this->pageManager->createPage($url);
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Page\\Page", $page);
    }

    public function testGetOrCreatePage()
    {
        //create
        $url = new Url("http://phpcrawl.cuab.de/");
        $page1 = $this->pageManager->getOrCreatePage($url);
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Page\\Page", $page1);
        //get
        $page2 = $this->pageManager->getOrCreatePage($url);
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Page\\Page", $page2);

        $this->assertEquals($page2->id(), $page1->id());
    }
}
 