<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Url\Url;

interface QueueInterface
{
    /**
     * @param string|int $channelName
     * @return bool
     */
    public function registerChannel($channelName);
    /**
     * @param Url $url
     * @param string|int $channelName  optional
     * @return mixed
     */
    public function enqueue(Url $url, $channelName = null);

    /**
     * @param string|int $channelName optional
     * @return Url|null
     */
    public function dequeue($channelName = null);

    /**
     * @param string|int $channelName optional
     * @return mixed
     */
    public function purge($channelName = null);

    /**
     * @param Url $url
     * @param string|int $channelName optional
     * @return mixed
     */
    public function ack(Url $url, $channelName = null);
}