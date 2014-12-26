<?php
namespace Madfox\WebCrawler\Page;

use Madfox\WebCrawler\Site;
use Madfox\WebCrawler\Url\Url;

class PageIterator implements \Iterator
{
    /**
     * @var Url
     */
    private $currentUrl;

    /**
     * @var Site
     */
    private $site;
    /**
     * @var int
     */
    private $index = 0;

    /**
     * @param Site $site
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    public function key()
    {
        return $this->index;
    }

    public function rewind()
    {

    }
    
    public function next()
    {
        $this->index++;
    }

    public function valid()
    {
        $url = $this->site->getQueue()->dequeue($this->getChannelName());
        $status = false;

        if (null !== $url) {
            $this->currentUrl = $url;
            $status = true;
        }

        return  $status;
    }

    public function current()
    {
        $page = $this->site->getPageManager()->getOrCreatePage($this->currentUrl);
        return $page;
    }

    protected function getChannelName()
    {
        return $this->site->getUrl()->hostname();
    }
}