<?php
namespace Madfox\WebCrawler\Site;

class SiteCollection implements \IteratorAggregate, \Countable
{
    /**
     * @var Site[]
     */
    private $sites = [];

    /**
     * @construct
     */
    public function __construct()
    {
        $this->sites = [];
    }

    /**
     * @param Site $site
     */
    public function add(Site $site)
    {
        $id = $site->getUrl()->getId();
        unset($this->sites[$id]);

        $this->sites[$id] = $site;
    }

    /**
     * @param Url $url
     * @return Site|null
     */
    public function get(Url $url)
    {
        return isset($this->sites[$url->getId()]) ? $this->sites[$url->getId()] : null;
    }

    /**
     * @param Url $url
     * @return bool
     */
    public function has(Url $url)
    {
        $site = $this->get($url);
        return $site ? true : false;
    }

    /**
     * @return array|Site[]
     */
    public function all()
    {
        return $this->sites;
    }

    /**
     * @param Url $url
     */
    public function remove(Url $url)
    {
        unset($this->sites[$url->getId()]);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->sites);
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->sites);
    }
}