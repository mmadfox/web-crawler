<?php

use \Madfox\WebCrawler\Site\SiteCollection;
use Madfox\WebCrawler\Site\Url;
use Madfox\WebCrawler\Site\Site;


class SiteCollectionTest extends PHPUnit_Framework_TestCase {
    /**
     * @var SiteCollection
     */
    private $siteCollection;

    public function setUp()
    {
        $this->siteCollection = new SiteCollection();
    }

    public function testAddSite()
    {
        $this->siteCollection->add($this->createSite("http://goole.com"));
        $this->siteCollection->add($this->createSite("http://xooxo.com"));
        $this->siteCollection->add($this->createSite("http://yhooo.com"));
        $this->assertCount(3, $this->siteCollection->all());
    }

    public function testGetSite()
    {
        $this->siteCollection->add($this->createSite("http://google.com"));
        $this->siteCollection->add($this->createSite("http://xooxo.com"));
        $this->siteCollection->add($this->createSite("http://yhooo.com"));

        $google = new Url("http://google.com");
        $site = $this->siteCollection->get($google);
        $this->assertEquals($google->getId(), $site->getUrl()->getId());

        $xooxo = new Url("http://xooxo.com");
        $site = $this->siteCollection->get($xooxo);
        $this->assertEquals($xooxo->getId(), $site->getUrl()->getId());
    }

    protected function createSite($url)
    {
        return new Site(new Url($url));
    }
}
 