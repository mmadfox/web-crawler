<?php

use Madfox\WebCrawler\Site\Url;
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
    /**
     * @expectedException \Exception;
     */
    public function testInvalidConstructor()
    {
        new Url("//google.com");
        new Url("/path/to/hell");
        new Url("path/path/path:q");
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
 