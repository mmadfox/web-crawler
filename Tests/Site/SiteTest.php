<?php

use \Madfox\WebCrawler\Site\Mapper\Factory\PageFactory;
use \Madfox\WebCrawler\Site\Url;
use \Madfox\WebCrawler\Site\Site;

class SiteTest extends PHPUnit_Framework_TestCase {
    public function testCreatePage()
    {
        $pageFactory = new PageFactory();
        $page = $pageFactory->createPage(new Url("http://ru.lipsum.com"));

        echo $page->content();

        $site = new Site(new Url("http://google.com"));


    }
}
 