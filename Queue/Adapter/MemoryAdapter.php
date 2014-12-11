<?php
namespace Madfox\WebCrawler\Queue\Adapter;

class MemoryAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $queue = [];

    /**
     * @construct
     */
    public function __construct()
    {
        $this->queue = [];
    }

    /**
     * @param string $url
     * @return bool
     */
    public function enqueue($url)
    {
        array_push($this->queue, $url);
        return true;
    }

    /**
     * @return string
     */
    public function dequeue()
    {
        $url = array_pop($this->queue);
        return $url;
    }

    /**
     * @return mixed
     */
    public function purge()
    {
        $this->queue = [];
    }

    /**
     * @param string $url
     * @return mixed
     */
    public function ack($url)
    {

    }
}