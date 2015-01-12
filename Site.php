<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Http\ClientInterface;
use Madfox\WebCrawler\Indexer\IndexerInterface;
use Madfox\WebCrawler\Page\PageIterator;
use Madfox\WebCrawler\Queue\QueueInterface;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\UrlMatcher\UrlMatcherInterface;
use Madfox\WebCrawler\Page\Page;

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
     * @var IndexerInterface
     */
    private $indexer;
    /**
     * @var ClientInterface
     */
    private $httpClient;
    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;
    /**
     * @var EventDispatcher
     */
    private $event;

    /**
     * @param Url $url
     * @param QueueInterface $queue
     * @param IndexerInterface $indexer
     * @param UrlMatcherInterface $urlMatcher
     * @param ClientInterface $httpClient
     */
    public function __construct(
        Url $url,
        QueueInterface $queue,
        IndexerInterface $indexer,
        UrlMatcherInterface $urlMatcher,
        ClientInterface $httpClient)
    {
        $this->url = $url;

        $this->setHttpClient($httpClient);
        $this->setQueue($queue);
        $this->setIndexer($indexer);
        $this->setUrlMatcher($urlMatcher);
    }

    /**
     * @param ClientInterface $httpClient
     * @return Site
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param UrlMatcherInterface $urlMatcher
     * @return Site
     */
    public function setUrlMatcher(UrlMatcherInterface $urlMatcher)
    {
        $this->urlMatcher = $urlMatcher;

        return $this;
    }

    /**
     * @return UrlMatcherInterface
     */
    public function getUrlMatcher()
    {
        return $this->urlMatcher;
    }

    /**
     * @return IndexerInterface
     */
    public function getIndexer()
    {
        return $this->indexer;
    }

    /**
     * @param IndexerInterface $indexer
     * @return Site
     */
    public function setIndexer(IndexerInterface $indexer)
    {
        $this->indexer = $indexer;

        return $this;
    }

    /**
     * @param QueueInterface $queue
     */
    public function setQueue(QueueInterface $queue)
    {
        if ($this->queue) {
            $this->queue->purge($this->url->host());
        }

        $this->queue = $queue;
        $this->queue->registerChannel($this->url->host());
        $this->queue->enqueue($this->getUrl(), $this->url->host());
    }

    /**
     * @return QueueInterface
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return string
     */
    public function hostname()
    {
        return $this->url->hostname();
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
     * @param Url|string $url
     * @return Page|null
     */
    public function getPage($url)
    {
        try {
            if (is_string($url)) {
                $url = new Url($url);
            }

            if ($this->getIndexer()->has($url)) {
                return $this->getIndexer()->get($url);
            } else {
                $response = $this->getHttpClient()->get($url);
                $page = null;

                if (200 == $response->getStatusCode()
                    && $response->getContentType() == "text/html") {
                    $cursor = $this->urlMatcher->match($url, $response->getContent());
                    $links = [];

                    foreach ($cursor as $link) {
                        if ($url->equalHost($link)) {
                            array_push($links, $link);
                        }
                    }

                    shuffle($links);

                    $page = new Page($url, $links, $response->getContent());
                    return $page;

                } else {
                    return new Page($url);
                }
            }

        } catch (\Exception $e) {
            return new Page($url);
        }
    }
}