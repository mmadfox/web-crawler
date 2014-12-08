<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Site\AddressInterface;

class Site
{
    private $address;

    public function __construct(AddressInterface $address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function url()
    {

    }

    public function match()
    {

    }
}