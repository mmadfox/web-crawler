<?php
namespace Madfox\WebCrawler;

use Madfox\Presets\PresetsInterface;
use Madfox\WebCrawler\Site\AddressInterface;

class Site
{
    /**
     * @var AddressInterface
     */
    private $address;
    /**
     * @var array
     */
    private $presets = [];

    public function __construct(AddressInterface $address)
    {
        $this->address = $address;
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
        $presets->setup($this);
    }

    public function url($route)
    {

    }

    public function match()
    {

    }
}