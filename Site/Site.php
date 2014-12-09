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
    private $routes = [];

    public function __construct(AddressInterface $address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function ifpath($pathinfo)
    {
        $route = new Route($pathinfo);
        $route->setSite($this);
        array_push($this->routes, $route);
        return $route;
    }

    public function routes()
    {
        return $this->routes;
    }
}