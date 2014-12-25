<?php
namespace Madfox\WebCrawler\Page;

use Madfox\WebCrawler\Http\Client;
use Madfox\WebCrawler\Index\IndexInterface;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Url\UrlMatcher;

class PageManager
{
    /**
     * @var IndexInterface
     */
    private $index;
    /**
     * @var Client
     */
    private $content;
    /**
     * @var UrlMatcher
     */
    private $urlMatcher;
    /**
     * @param IndexInterface $index
     * @param Client $httpClient
     * @param UrlMatcher $urlMatcher
     */
    public function __construct(IndexInterface $index, Client $httpClient, UrlMatcher $urlMatcher)
    {
        $this->setIndex($index);
        $this->setHttpClient($httpClient);
        $this->setUrlMatcher($urlMatcher);
    }

    /**
     * @param UrlMatcher $urlMatcher
     * @return PageManager
     */
    public function setUrlMatcher(UrlMatcher $urlMatcher)
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
     * @param Client $client
     * @return PageManager
     */
    public function setHttpClient(Client $client)
    {
        $this->content = $client;

        return $this;
    }

    /**
     * @return Client
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
            $page = $this->createPage($url);
        }

        return $page;
    }

    /**
     * @param Url $url
     * @return Page|null
     */
    public function createPage(Url $url)
    {
        $page = $this->getRemotePage($url);

        if ($page) {
            $this->index->add($url, $page);
        }

        return $page;
    }

    /**
     * @param Url $url
     * @return Page|null
     */
    private function getRemotePage(Url $url)
    {
        try {
            $response = $this->content->get($url);
            $page = null;

            if ($response->isValid()) {
                $links = $this->urlMatcher->match($url, $response->getContent());
                $page = new Page($url, $links, $response->getContent());

                return $page;
            }

        } catch (\Exception $e) {
            return null;
        }
    }
}