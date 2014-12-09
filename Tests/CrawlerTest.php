<?php
use \Madfox\WebCrawler\Crawler;

class CrawlerTest extends PHPUnit_Framework_TestCase
{
    private $crawler;

    public function setUp()
    {
        $this->crawler = new Crawler();
    }

    public function testSite()
    {
        $site = $this->crawler->site("http://google.com");
        $this->assertInstanceOf("Madfox\\WebCrawler\\Site\\Site", $site);
        $this->assertEquals(0, count($site->routes()), "Site routes");
        $route = $this->crawler->site("http://google1.com")->ifpath("/");
        $this->assertInstanceOf("Madfox\\WebCrawler\\Routing\\Route", $route);
        $route = $this->crawler->site("http://google2.com")->ifpath("/")->exec("log");
        $this->assertInstanceOf("Madfox\\WebCrawler\\Routing\\Route", $route);
    }

    /**
     * @expectedException Madfox\WebCrawler\Exception\SiteAlreadyExistsException
     */
    public function testAddDuplicateDomain()
    {
        $this->crawler->site("http://google.com/wrwerwer/werwerwer/werwer");
        $this->crawler->site("http://google.com/query1");
        $this->crawler->site("http://google.com/query2");
    }

    /**
     * @expectedException Madfox\WebCrawler\Exception\InvalidAddressException
     */
    public function testCreateWebsiteWithInvalidAddress()
    {
        $this->crawler->site("://gggggg");
    }

    public function testRun()
    {
        $this->crawler->site("http://ulkotours.com/");
        $this->crawler->run();
    }
}
 