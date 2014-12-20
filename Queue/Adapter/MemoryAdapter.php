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
     * @param string $channelName
     * @return mixed|void
     */
    public function addChannel($channelName)
    {
        $this->queue[$channelName] = [];

        return true;
    }

    /**
     * @param string $url
     * @param string $channelName
     * @return bool
     */
    public function enqueue($url, $channelName)
    {
        array_push($this->queue[$channelName], $url);

        return true;
    }

    /**
     * @param string $channelName
     * @return string
     */
    public function dequeue($channelName)
    {
        $return = "";

        if (isset($this->queue[$channelName])) {
            $return = array_pop($this->queue[$channelName]);
        }

        return $return;
    }

    /**
     * @param string $channelName
     * @return mixed
     */
    public function purge($channelName)
    {
        unset($this->queue[$channelName]);
    }

    /**
     * @param string $url
     * @param null $channelName
     * @return mixed
     */
    public function ack($url, $channelName)
    {

    }
}