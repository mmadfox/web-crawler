<?php

use Madfox\WebCrawler\Site\Address;

class AddressTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
         $address = new Address("http://madfox:golang@github.com/path/path/path.git");
         $this->assertEquals($address->getScheme(), "http", "Address scheme is invalid");
         $this->assertEquals($address->getHostname(), "github.com", "Address hostname is invalid");
         $this->assertEquals($address->getPort(), 80, "Address port is invalid");
         $this->assertEquals($address->getUser(), 'madfox', "Address username is invalid");
         $this->assertEquals($address->getPassword(), 'golang', "Address password is invalid");
         $this->assertEquals($address->toString(), 'http://madfox:golang@github.com:80/path/path/path.git', "");
    }

    /**
     * @expectedException   Madfox\WebCrawler\Exception\InvalidAddressException
     */
    public function testInvalidConstructor()
    {
        $address = new Address("github.com/path/path/path.git");
    }
}