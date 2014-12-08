<?php
namespace Madfox\WebCrawler\Site;

use Buzz\Util\Url;
use Madfox\Presets\PresetsInterface;
use Madfox\WebCrawler\Routing\Route;
use Madfox\WebCrawler\Routing\RouteCollection;
use Madfox\WebCrawler\Site\AddressInterface;
use Madfox\WebCrawler\Presets\PresetsCollection;

class Site
{
    private $address;
    private $presetsCollection;
    private $routeCollection;

    public function __construct(AddressInterface $address)
    {
        $this->address = $address;
        $this->presetsCollection = new PresetsCollection();
        $this->routeCollection = new RouteCollection();
    }

    /**
     * @return AddressInterface
     */
    public function getAddress()
    {
        return $this->address;
    }

    public function presets($name, PresetsInterface $presets)
    {
    }

    public function ifpath($pathinfo)
    {
        return new Route($pathinfo);
    }

    public function match()
    {

    }

    public function install()
    {
    }

    public function uninstall()
    {

    }
}