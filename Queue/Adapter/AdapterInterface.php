<?php
namespace Madfox\WebCrawler\Queue\Adapter;

interface AdapterInterface
{
    /**
     * @param null|string $channelName
     * @return bool
     */
    public function addChannel($channelName);
    /**
     * @param string $id
     * @param string $channelName
     * @return mixed
     */
    public function enqueue($id, $channelName);

    /**
     * @param string $channelName
     * @return string
     */
    public function dequeue($channelName);

    /**
     * @param string $channelName
     * @return mixed
     */
    public function purge($channelName);

    /**
     * @param string $channelName
     * @param string $id
     * @return mixed
     */
    public function ack($id, $channelName);

    /**
     * @param string $channelName
     * @return Urls[]
     */
    public function getUrls($channelName);
}