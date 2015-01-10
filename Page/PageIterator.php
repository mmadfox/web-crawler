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
    }
    
    public function next()
    {
        $page = null;
        $url  = null;

        $url = $this->getQueue()->dequeue($this->site->hostname());



        //echo "NEXT \n";

        if ($url) {
            //echo "Get from Q => " . $url->toString() . "\n";
            $page = $this->site->getPageManager()->createPage($url);
            $this->getSite()->getIndex()->add($url);

            if ($this->queueIsNotFull()) {
                foreach ($page->links() as $link) {
                    //echo "Founded link => " . $link->toString() . "\n";
                    if (!$this->getSite()->getIndex()->has($link)) {
                        //echo "Insert to Q => " . $link . "\n";
                        $this->getSite()->getIndex()->add($link);
                        $this->addLinkInQueue($link);
                    }
                }
            }

            $this->currentPage = $page;
            $this->index++;
            sleep(1);
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

    public function clear()
    {
        $this->getQueue()->purge($this->site->hostname());
    }

    protected function queueIsNotFull()
    {
        return $this->getQueue()->getCounter() < $this->getQueue()->limit();
    }

    protected function addLinkInQueue($link)
    {
        $this->site->getQueue()->enqueue($link, $this->site->hostname());
    }
}