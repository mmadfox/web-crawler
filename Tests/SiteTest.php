<?php
class SiteTest extends PHPUnit_Framework_TestCase {

    public function testCreateNewSite()
    {
        $site = new \Madfox\WebCrawler\Site("");
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Site", $site);
    }
}
 