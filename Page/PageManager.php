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
            if (!$this->index->has($url)) {
                $this->index->add($url, $page);
            }
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
            $response = $this->getHttpClient()->get($url);
            $page = null;

            if ($response->getStatusCode() == 200
            && $response->getContentType() == "text/html") {
                $links = $this->urlMatcher->match($url, $response->getContent());
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