<?php
namespace Madfox\WebCrawler\Page;

use Madfox\WebCrawler\Http\ClientInterface;
use Madfox\WebCrawler\Index\IndexInterface;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\UrlMatcher\UrlMatcherInterface;

class PageManager
{
    /**
     * @var IndexInterface
     */
    private $index;
    /**
     * @var ClientInterface
     */
    private $content;
    /**
     * @var UrlMatcher
     */
    private $urlMatcher;
    /**
     * @param IndexInterface $index
     * @param ClientInterface $httpClient
     * @param UrlMatcherInterface $urlMatcher
     */
    public function __construct(IndexInterface $index, ClientInterface $httpClient, UrlMatcherInterface $urlMatcher)
    {
        $this->setIndex($index);
        $this->setHttpClient($httpClient);
        $this->setUrlMatcher($urlMatcher);
    }

    /**
     * @param UrlMatcherInterface $urlMatcher
     * @return PageManager
     */
    public function setUrlMatcher(UrlMatcherInterface $urlMatcher)
    {
        $this->urlMatcher = $urlMatcher;

        return $this;
    }

    /**
     * @return UrlMatcher
     */
    public function getUrlMatcher()
    {
        return $this->urlMatcher;
    }

    /**
     * @param ClientInterface $client
     * @return PageManager
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->content = $client;

        return $this;
    }

    /**
     * @return ClientInterface
     */
    public function getHttpClient()
    {
        return $this->content;
    }

    /**
     * @return IndexInterface
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param IndexInterface $index
     * @return PageManager
     */
    public function setIndex(IndexInterface $index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @param Url $url
     * @return \Madfox\WebCrawler\Index\DocumentInterface|Page|null
     */
    public function getOrCreatePage(Url $url)
    {
        $page = null;

        if ($this->index->has($url)) {
            $page = $this->index->get($url);
        } else {
            $page = $this->getRemotePage($url);
            $this->index->add($url, $page);

        }

        return $page;
    }

    /**
     * @param Url $url
     * @return Page|null
     */
    public function createPageIfNotVisited(Url $url)
    {
        if ($this->index->has($url)) {
            return null;
        } else {
            $page = $this->getRemotePage($url);
            $this->index->add($url, $page);
            return $page;
        }
    }

    /**
     * @param Url $url
     * @return Page|null
     */
    private function getRemotePage(Url $url)
    {
        try {
            $response = $this->getHttpClient()->get($url);
            $page = null;

            if (200 == $response->getStatusCode()) {
                $cursor = $this->urlMatcher->match($url, $response->getContent());
                $links  = [];

                foreach ($cursor as $link) {
                    if ($url->equalHost($link)) {
                        array_push($links, $link);
                    }
                }

                $page = new Page($url, $links, $response->getContent());

                return $page;

            } else {
                return new Page($url);
            }

        } catch (\Exception $e) {
            return new Page($url);
        }
    }
}