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

    public function testAddress()
    {
        $address = $this->site->getAddress();
        $this->assertInstanceOf("Madfox\\WebCrawler\\Site\\AddressInterface", $address);
    }

    public function testPresets()
    {
        $presets = $this->getMockBuilder('Madfox\Presets\PresetsInterface')->setMethods(['install'])->getMock();
        $this->site->presets('preset1', $presets);
    }

    public function testAddRoute()
    {

    }

}
 