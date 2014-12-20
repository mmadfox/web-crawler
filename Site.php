<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Site\Cursor;
use Madfox\WebCrawler\Url\Url;

class Site implements \IteratorAggregate
{
    /**
     * @var Url
     */
    private $url;

    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getIterator()
    {
         return new Cursor($this);
    }
}