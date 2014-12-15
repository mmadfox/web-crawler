<?php

use \Madfox\WebCrawler\Url\Url;

class UrlTest extends PHPUnit_Framework_TestCase {
    public function testCreateUrl()
    {
        $url = new Url("http://google.com");
        $this->assertEquals("http://google.com", (string) $url);

        $url = new Url("http://google.com/?index=1");
        $this->assertEquals("http://google.com/?index=1", (string) $url);

        $url = new Url("http://google.com////?index=1");
        $this->assertEquals("http://google.com/?index=1", (string) $url);

        $url = new Url("http://google.com////?index=1#fragment");
        $this->assertEquals("http://google.com/?index=1", (string) $url);

        $url = new Url("https://google.com////????index=1#fragment");
        $this->assertEquals("https://google.com/????index=1", (string) $url);

    }

    /**
     * @expectedException \Madfox\WebCrawler\Exception\InvalidArgumentException
     */
    public function testCreateBadUrl()
    {
        new Url("ftp://ggg.com/dsfsdfsfsdf/sdfsdfsdf/sdfsdfsdf");
        new Url("/dsfsdfsfsdf/sdfsdfsdf/sdfsdfsdf");
    }
}
 