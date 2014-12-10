<?php
namespace Madfox\WebCrawler\Site;

use Madfox\WebCrawler\Routing\RouteCollection;
use Madfox\WebCrawler\Site\Mapper\Factory\PageFactory;
use Madfox\WebCrawler\Site\Url;

class Site
{

    /**
     * @var Url
     */
    private $url;

    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
        $this->routeCollection = new RouteCollection();
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }

    public function valid(Page $page)
    {
        return true;
    }

    public function page(Url $url)
    {
        $pageFactory = new PageFactory();
        return $pageFactory->createPage($url);
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

    /**
     * @return \Symfony\Component\Routing\Route[]
     */
    public function routes()
    {
        return $this->routeCollection->all();
    }
}