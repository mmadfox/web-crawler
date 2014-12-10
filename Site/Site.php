<?php
namespace Madfox\WebCrawler\Site;

use Madfox\WebCrawler\Routing\Route;

class Site
{
    private $url;
    private $routes = [];

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
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