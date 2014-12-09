<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Queue\Adapter\AdapterInterface;

class Queue implements QueueInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @param Url $url
     * @return mixed
     */
    public function enqueue(Url $url)
    {
        $string = $url->format("Hpq");
        return $this->adapter->enqueue($string);
    }

    /**
     * @return Url
     */
    public function dequeue()
    {
        $string = $this->adapter->dequeue();

        return new Url($string);
    }

    /**
     * @return mixed
     */
    public function purge()
    {
        $this->adapter->purge();
    }

    /**
     * @param Url $url
     * @return mixed
     */
    public function ack(Url $url)
    {
        $this->adapter->ack($url->getId());
    }
}