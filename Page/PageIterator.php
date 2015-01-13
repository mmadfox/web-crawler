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

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @return void
     */
    public function next()
    {
        $site  = $this->getSite();
        $queue = $site->getQueue();
        $indexer = $site->getIndexer();
        $channelName = $site->getUrl()->host();

        $page = null;
        $url  = null;

        do {
            $url = $queue->dequeue($channelName);
            if ($url) $page = $site->getPage($url);
        } while($url && $page->isEmpty());

        if ($page) {
            foreach ($page->links() as $link) {
                if (!$indexer->has($link)) {
                    $queue->enqueue($link, $channelName);
                }
            }

            $this->currentPage = $page;
            $this->index++;
        } else {
            $this->currentPage = null;
        }
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->currentPage instanceof Page;
    }

    /**
     * @return Page|null
     */
    public function current()
    {
        return $this->currentPage;
    }
}