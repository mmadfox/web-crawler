<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Page\PageIterator;
use Madfox\WebCrawler\Page\PageManager;
use Madfox\WebCrawler\Queue\QueueInterface;
use Madfox\WebCrawler\Url\Url;

class Site implements \IteratorAggregate
{
    /**
     * @var Url
     */
    private $url;
    /**
     * @var QueueInterface
     */
    private $queue;
    /**
     * @var PageManager
     */
    private $pageManager;


    public function __construct(Url $url, QueueInterface $queue, PageManager $pageManager)
    {
        $this->url = $url;
        $this->setQueue($queue);
        $this->setPageManager($pageManager);
    }

    public function hostname()
    {
        return $this->url->hostname();
    }

    public function setPageManager(PageManager $pageManager)
    {
        $this->pageManager = $pageManager;
    }

    public function getPageManager()
    {
        return $this->pageManager;
    }

    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        $this->queue->registerChannel($this->url->hostname());
        $this->queue->enqueue($this->url, $this->url->hostname());
    }

    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return Queue\Urls[]
     */
    public function getUrlsInQueue()
    {
        return $this->getQueue()->getUrls($this->hostname());
    }

    /**
     * @return PageIterator|\Traversable
     */
    public function getIterator()
    {
         $cursor = new PageIterator($this);

         return $cursor;
    }
}