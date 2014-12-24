<?php

class ClientTest extends PHPUnit_Framework_TestCase {
   public function testEmpty()
   {
       $client = new \Madfox\WebCrawler\Http\Client();
       $client->get(new \Madfox\WebCrawler\Url\Url("http://ulkotours.com/"));
   }
}
 