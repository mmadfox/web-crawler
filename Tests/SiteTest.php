<?php

use Madfox\WebCrawler\Site\Address;
use Madfox\WebCrawler\Site\Site;

class SiteTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Site
     */
    private $site;

    public function setUp()
    {
        $this->site = new Site(new Address("http://foodbook.guru"));
    }

    public function testEmpty()
    {

    }
}
 