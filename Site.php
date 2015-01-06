<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Http\ClientInterface;
use Madfox\WebCrawler\Index\IndexInterface;
use Madfox\WebCrawler\Page\PageIterator;
use Madfox\WebCrawler\Page\PageManager;
use Madfox\WebCrawler\Queue\QueueInterface;
use Madfox\WebCrawler\Url\Factory\UrlFactory;
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

    /**
     * @param Url $url
     * @param QueueInterface $queue
     * @param PageManager $pageManager
     */
    public function __construct(Url $url, QueueInterface $queue, PageManager $pageManager)
    {
        $this->url = $url;
        $this->setQueue($queue);
        $this->setPageManager($pageManager);
    }

    /**
     * @return string
     */
    public function hostname()
    {
        return $this->url->hostname();
    }

    /**
     * @param PageManager $pageManager
     */
    public function setPageManager(PageManager $pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * @param IndexInterface $index
     * @return Site
     */
    public function setIndex(IndexInterface $index)
    {
        $this->getPageManager()->setIndex($index);

        return $this;
    }

    /**
     * @return IndexInterface
     */
    public function getIndex()
    {
        return $this->getPageManager()->getIndex();
    }

    /**
     * @param ClientInterface $client
     * @return Site
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->getPageManager()->setHttpClient($client);

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->getPageManager()->getHttpClient();
    }

    /**
     * @return PageManager
     */
    public function getPageManager()
    {
        return $this->pageManager;
    }

    /**
     * @param string $url
     * @return Index\DocumentInterface|Page\Page|null
     */
    public function getPage($url)
    {
        $urlFactory = new UrlFactory();
        $formattedUrl = $urlFactory->merge($url, $this->getUrl());

        return $this->pageManager->getOrCreatePage($formattedUrl);
    }

    /**
     * @param array $urls
     * @return Page[]
     */
    public function getPages(array $urls)
    {
        $return = [];

        foreach ($urls as $url) {
            $page = $this->getPage($url);
            array_push($return, $page);
        }

        return $return;
    }

    /**
     * @param QueueInterface $queue
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        $this->queue->registerChannel($this->url->host());
        $this->queue->enqueue($this->url, $this->url->host());
    }

    /**
     * @return QueueInterface
     */
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
     * @return PageIterator|\Traversable
     */
    public function getIterator()
    {
        $cursor = new PageIterator($this);

        return $cursor;
    }

    /**
     * @return void
     */
    public function close()
    {
        $this->getQueue()->purge($this->url->host());
        $this->getPageManager()->getIndex()->purge();
    }
}