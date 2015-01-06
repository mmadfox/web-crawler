<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Queue\Adapter\MemoryAdapter;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Queue\Adapter\AdapterInterface;
use Madfox\WebCrawler\Exception\InvalidArgumentException;

class Queue implements QueueInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;
    /**
     * @var int
     */
    private $channelCount = 0;
    /**
     * @var array
     */
    private $registeredChannels = [];
    /**
     * @var int
     */
    private $counter = 0;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->adapter = is_null($adapter) ? new MemoryAdapter() : $adapter;
    }

    /**
     * @param AdapterInterface $adapter
     * @return Queue
     */
    public function setAdapter(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * @param int|string|array $channelName
     * @return bool|void
     * @throws InvalidArgumentException if has been passed an illegal or inappropriate argument
     */
    public function registerChannel($channelName)
    {
        if (!is_string($channelName) && !is_numeric($channelName) && !is_array($channelName)) {
            throw new InvalidArgumentException();
        }

        if (!is_array($channelName)) $channelName = [$channelName];

        foreach ($channelName as $cn) {
            $cn = $this->channelName($cn);

            $result = $this->adapter->addChannel($cn);

            if ($result) {
                array_push($this->registeredChannels, $cn);
                $this->channelCount++;
            }
        }
    }

    /**
     * @return array
     */
    public function getRegisteredChannels()
    {
        return $this->registeredChannels;
    }

    /**
     * @return int
     */
    public function getChannelCount()
    {
        return $this->channelCount;
    }

    /**
     * @param Url $url
     * @param string|null $channelName
     * @return mixed
     */
    public function enqueue(Url $url, $channelName = null)
    {
        $channelName = $this->channelName($channelName);
        $this->registerChannelIfNotExists($channelName);
        $string = $url->toString();
        $this->counter++;
        return $this->adapter->enqueue($string, $channelName);

    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @param string $channelName
     * @return Url | null
     */
    public function dequeue($channelName = null)
    {
        $channelName = $this->channelName($channelName);
        $this->registerChannelIfNotExists($channelName);

        $string = $this->adapter->dequeue($channelName);
        $url = null;

        if ($string) {
            try {
                $url = new Url($string);
                $this->counter--;
            } catch (InvalidArgumentException $e) {
                var_dump($e);
                //TODO trigger event
            }
        }

        return $url;
    }

    /**
     * @param string $channelName
     * @return mixed
     */
    public function purge($channelName = null)
    {
        $channelName = $this->channelName($channelName);
        $this->registerChannelIfNotExists($channelName);

        $this->adapter->purge($channelName);
    }

    /**
     * @param Url $url
     * @param string|null $channelName
     * @return mixed
     */
    public function ack(Url $url, $channelName = null)
    {
        $channelName = $this->channelName($channelName);
        $this->registerChannelIfNotExists($channelName);

        $this->adapter->ack($url->getId(), $channelName);
    }

    /**
     * @param $channelName
     * @return Urls[]
     */
    public function getUrls($channelName = null)
    {
        $channelName = $this->channelName($channelName);
        return $this->adapter->getUrls($channelName);
    }

    /**
     * @param $channelName
     * @return bool
     */
    public function hasChannel($channelName)
    {
        return in_array($channelName, $this->registeredChannels);
    }

    /**
     * @return int
     */
    public function limit()
    {
        return $this->adapter->getLimit();
    }

    /**
     * @param null|string $channelName
     * @return null|string
     */
    private function channelName($channelName = null)
    {
        $channelName = is_null($channelName) ? 'default' : $channelName;
        $channelName = str_replace([":", "/", "http", ".", "-", "www", "//"], "", $channelName);
        return $channelName;
    }

    private function registerChannelIfNotExists($channelName)
    {
        if (!$this->hasChannel($channelName)) {
            $this->registerChannel($channelName);
        }
    }
}