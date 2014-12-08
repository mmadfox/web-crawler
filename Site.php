<?php
namespace Madfox\WebCrawler;

use Madfox\Presets\PresetsInterface;
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

    public function __construct(AddressInterface $address, PresetsCollection $presetsCollection = null)
    {
        $this->address = $address;
        $this->presetsCollection = $presetsCollection
                                 ? $presetsCollection
                                 : new PresetsCollection();

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

    public function url($route)
    {

    }

    public function ifpath($route)
    {
        return $this->url($route);
    }

    public function match()
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