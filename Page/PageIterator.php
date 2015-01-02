<?php
namespace Madfox\WebCrawler\Page;

use Madfox\WebCrawler\Site;

class PageIterator implements \Iterator
{
    /**
     * @var Page
     */
    private $currentPage;

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
        $this->next();
    }

    public function key()
    {
        return $this->index;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getQueue()
    {
        return $this->site->getQueue();
    }

    public function rewind()
    {
        $this->index = 0;
        $this->getQueue()->purge($this->site->hostname());
    }
    
    public function next()
    {
        $url = $this->getQueue()->dequeue($this->site->hostname());

        if ($url) {
            $page = $this->site->getPageManager()->getOrCreatePage($url);

            foreach ($page->links() as $link) {
                $this->addLinkInQueue($link);
            }

            $this->currentPage = $page;
            $this->index++;

        } else {
            $this->currentPage = null;
        }
    }

    public function valid()
    {
        return $this->currentPage instanceof Page;
    }

    public function current()
    {
        return $this->currentPage;
    }

    protected function addLinkInQueue($link)
    {
        $this->site->getQueue()->enqueue($link, $this->site->getUrl()->hostname());
    }
}