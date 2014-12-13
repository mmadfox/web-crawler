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
        $url1 = $this->getUrl("http://google.com/search?id=1");
        $url2 = $this->getUrl("http://mad.com/path/5");
        $resUrl = $this->factory->merge($url1, $url2);
        $this->assertEquals("http://google.com:80/search?id=1", $resUrl->toString());
    }

    public function testMergeOneStringOneInstanceUrl()
    {
        $url1 = "/path/to/merge?check=1";
        $url2 = $this->getUrl("http://google.com/");
        $resUrl = $this->factory->merge($url1, $url2);
        $this->assertEquals("http://google.com:80/path/to/merge?check=1", $resUrl->toString());

        $url1 = "//";
        $url2 = $this->getUrl("http://google.com/");
        $resUrl = $this->factory->merge($url1, $url2);
        $this->assertEquals("http://google.com:80/path/to/merge?check=1", $resUrl->toString());

        $url1 = "mailto:sergey.liskonog@gmail.com";
        $url2 = $this->getUrl("http://google.com/");
        $resUrl = $this->factory->merge($url1, $url2);
        $this->assertEquals("http://google.com:80/path/to/merge?check=1", $resUrl->toString());
    }


    private function getUrl($url)
    {
        return $this->factory->create($url);
    }
}
 