<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Page\PageManager;
use Madfox\WebCrawler\Url\Url;

class Site implements \IteratorAggregate
{
    /**
     * @var Url
     */
    private $url;
    private $pageManager;

    /**
     * @param Url $url
     */
    public function __construct(Url $url, PageManager $pageManager)
    {
        $this->url = $url;
        $this->pageManager = $pageManager;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return PageIterator|\Traversable
     */
    public function getIterator()
    {
         return null;
    }
}