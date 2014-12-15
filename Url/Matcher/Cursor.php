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

    public function count()
    {
        return count($this->links);
    }

    public function current()
    {
        $url = $this->links[$this->index];

        try {
            return $this->urlFactory->merge($url, $this->url);
        } catch (InvalidArgumentException $e) {

        }
    }

    public function valid()
    {
        return isset($this->links[$this->index]);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->index++;
    }
}