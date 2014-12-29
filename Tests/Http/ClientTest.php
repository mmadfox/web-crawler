<?php

use Madfox\WebCrawler\Http\Client;

class ClientTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Client
     */
   private $client;

   public function setUp()
   {
       $this->client = new Client();
   }

   public function testUserAgent()
   {
        $this->client->setUserAgent("useragent");
        $this->assertEquals("useragent", $this->client->getUserAgent());
   }

   public function testProxyUrl()
   {
       $proxyUrl = new \Madfox\WebCrawler\Url\Url("http://123.123.123:80");
       $this->client->setProxyUrl($proxyUrl);
       $this->assertEquals($proxyUrl, $this->client->getProxyUrl());
   }

   public function testGetRemotePage()
   {
       $url = new \Madfox\WebCrawler\Url\Url("http://google.com");
       $response = $this->client->get($url);
       $this->assertInstanceOf("\\Madfox\\WebCrawler\\Http\\Response", $response);
   }

   private function getTransferMock()
   {

   }
}
 