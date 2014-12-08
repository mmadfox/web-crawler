<?php
namespace Madfox\WebCrawler\Presets;

use Madfox\Presets\PresetsInterface;
use Madfox\WebCrawler\Site;

class PresetsCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $presets = [];

    public function add($name, PresetsInterface $preset)
    {

    }

    public function remove($name)
    {

    }

    public function removeAll()
    {

    }

    public function all()
    {

    }

    public function get($name)
    {

    }

    public function getIterator()
    {
        return new \ArrayIterator($this->presets);
    }

    public function install(Site $site)
    {

    }

    public function uninstall(Site $site)
    {

    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->presets);
    }
}