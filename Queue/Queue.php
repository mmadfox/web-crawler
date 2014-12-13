<?php
namespace Madfox\WebCrawler\Queue;

use Buzz\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Site\Url;
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
        $string = $url->toString();
        return $this->adapter->enqueue($string);
    }

    /**
     * @return Url | null
     */
    public function dequeue()
    {
        $string = $this->adapter->dequeue();
        $url = null;

        if ($string) {
            try {
                $url = new Url($string);
            } catch (InvalidArgumentException $e) { }
        }

        return $url;
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