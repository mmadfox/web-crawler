<?php

use Madfox\WebCrawler\Url\Url;
use Buzz\Exception\InvalidArgumentException;

class UrlTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider provider
     */
    public function testConstructor($expected)
    {
        $url = new Url($expected);
        $this->assertEquals($url->toString(), $expected);
    }

    public function testBuildUrl()
    {
        $expected = "//google.com/golang/?test=1";
        $url = new Url("http://google.com");
        $newUrl = $url->build($expected);
        $this->assertEquals("http://google.com:80/golang/?test=1", $newUrl->toString());

        $expected = "/golang/?test=1#fragment";
        $url = new Url("http://google.com");
        $newUrl = $url->build($expected);
        $this->assertEquals("http://google.com:80/golang/?test=1#fragment", $newUrl->toString());

    }

    /**
     * @expectedException Buzz\Exception\InvalidArgumentException;
     */
    public function testInvalidArgumentException()
    {
        try {
            new Url("//google.com");
            new Url("/path/to/hell");
            new Url("path/path/path:q");
        }catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail();
    }

    /**
     * @dataProvider providerId
     */
    public function testUrlId($expected, $id)
    {
        $url = new Url($expected);
        $this->assertEquals($url->getId(), $id);
    }

    public function providerId()
    {
        return [
            ['http://google.com:80', md5('http://google.com:80')],
            ['http://google.com:80', md5('http://google.com:80')],
            ['http://user@google.com:80/user/part?d=1#fragment/fragment', md5('http://user@google.com:80/user/part?d=1#fragment/fragment')]
        ];
    }

    public function provider()
    {
        return [
            ['http://google.com:80'],
            ['http://google.com:80'],
            ['http://user:password@google.com:80'],
            ['https://google.com:443/user/1/'],
            ['http://google.com:80/user?part=1&id=1'],
            ['http://user@google.com:80/user/part?d=1#fragment/fragment']
        ];
    }
}
 