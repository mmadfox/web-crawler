<?php
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Url\Factory\UrlFactory;

class UrlFactoryTest extends PHPUnit_Framework_TestCase {
    private $factory;

    public function setUp()
    {
        $this->factory = new UrlFactory();
    }

    public function testMergeTwoString()
    {
         $url1 = "Baltica";
         $url2 = "http://ulko.com//";

         $res = $this->factory->merge($url1, $url2);
         $this->assertEquals("http://ulko.com/Baltica", (string) $res);

         $url1 = "http://ulko.com/Baltica";
         $url2 = "/Path/To/Baltica";

         $res = $this->factory->merge($url1, $url2);
         $this->assertEquals($url1, (string) $res);

         $url1 = "://Baltica";
         $url2 = "http://ulko.com";

         $res = $this->factory->merge($url1, $url2);
         $this->assertEquals("http://ulko.com/Baltica", (string) $res);

         $url1 = "mailto:serg@ya.ru";
         $url2 = "http://ulko.com";

         $res = $this->factory->merge($url1, $url2);
         $this->assertEquals("http://ulko.com", (string) $res);

         $url1 = "skype:serg@com/call";
         $url2 = "http://ulko.com/path/to/res";

         $res = $this->factory->merge($url1, $url2);
         $this->assertEquals("http://ulko.com/path/to/res", (string) $res);

    }

    private function getUrl($url)
    {
        return $this->factory->create($url);
    }
}
 