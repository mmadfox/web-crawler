<?php
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Url\Factory\UrlFactory;

class UrlFactoryTest extends PHPUnit_Framework_TestCase {
    private $factory;

    public function setUp()
    {
        $this->factory = new UrlFactory();
    }

    public function testCreateValidUrl()
    {
        $url = $this->getUrl("http://google.com");
        $this->assertInstanceOf("Madfox\\WebCrawler\\Url\\Url", $url);
    }

    /**
     * @expectedException Madfox\WebCrawler\Exception\InvalidArgumentException
     */
    public function testCreateInvalidUrl()
    {
        $this->getUrl("://google.com");
    }

    public function testMergeTwoInstanceUrl()
    {
        $url1 = $this->getUrl("http://google.com/path/1/merge/2");
        $url2 = $this->getUrl("http://google.com/path/5/orange/2/test/ggg/#fragment");
        $resUrl = $this->factory->merge($url1, $url2);
        $this->assertEquals("http://google.com/path/1/merge/2/test/ggg/#fragment", $resUrl->toString());

        $url1 = $this->getUrl("/path/1/merge/2");
        $url2 = $this->getUrl("http://google.com/path/5/orange/2/test/ggg/#fragment");
        $resUrl = $this->factory->merge($url1, $url2);
        $this->assertEquals("http://google.com/path/1/merge/2/test/ggg/#fragment", $resUrl->toString());
    }

    private function getUrl($url)
    {
        return $this->factory->create($url);
    }
}
 