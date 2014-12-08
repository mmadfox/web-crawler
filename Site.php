<?php
namespace Madfox\WebCrawler;

use Buzz\Util\Url;
use Madfox\Presets\PresetsInterface;
use Madfox\WebCrawler\Routing\Route;
use Madfox\WebCrawler\Routing\RouteCollection;
use Madfox\WebCrawler\Site\AddressInterface;
use Madfox\WebCrawler\Presets\PresetsCollection;

class Site
{
    /**
     * @var AddressInterface
     */
    private $address;
    /**
     * @var PresetsCollection
     */
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

    public function conf(array $options = array())
    {

    }

    public function presets($name, PresetsInterface $presets)
    {
        $presets->install($this);
        $this->presets[$name] = $presets;
        return $this;
    }

    public function addRoute(Route $route)
    {
        return $route;
    }

    public function ifpath($pathinfo)
    {
        if (!$pathinfo instanceof Route) {
            $pathinfo = (string) $pathinfo;
        }

        $routeName = md5($pathinfo);
        $route = new Route($pathinfo);
        $this->routeCollection->add($routeName, $route);

        return $route;
    }

    public function match(Url $url)
    {

    }

    public function install()
    {
        $this->presetsCollection->install($this);
    }

    public function uninstall()
    {
        $this->presetsCollection->uninstall($this);
    }
}