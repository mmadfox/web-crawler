<?php
namespace Madfox\WebCrawler\Queue;

use Buzz\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Queue\Adapter\AdapterInterface;

class Queue implements QueueInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    public function registerChannel($channelName)
    {

    }

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
    public function enqueue(Url $url, $channelName = null)
    {
        $string = $url->toString();
        return $this->adapter->enqueue($string);
    }

    /**
     * @return Url | null
     */
    public function dequeue($channelName = null)
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
    public function purge($channelName = null)
    {
        $this->adapter->purge();
    }

    /**
     * @param Url $url
     * @return mixed
     */
    public function ack(Url $url, $channelName = null)
    {
        $this->adapter->ack($url->getId());
    }
}