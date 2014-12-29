<?php
namespace Madfox\WebCrawler\Url\Matcher;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Url\Factory\UrlFactory;

class Cursor implements \Iterator, \Countable
{
    private $links = [];
    private $index = 0;
    private $url;
    private $urlFactory;

    /**
     * @param Url $url
     * @param array $links
     */
    public function __construct(Url $url, array $links = [])
    {
        $this->links = $links;
        $this->url = $url;
        $this->urlFactory = new UrlFactory();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->links);
    }

    /**
     * @return Url
     */
    public function current()
    {
        $url = $this->links[$this->index];

        try {
            return $this->urlFactory->merge($url, $this->url->hostname());
        } catch (InvalidArgumentException $e) {
            return $this->url;
        }
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->links[$this->index]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->index++;
    }
}