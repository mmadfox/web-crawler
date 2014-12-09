<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Url\Url;

interface QueueInterface
{
    /**
     * @param Url $url
     * @return mixed
     */
    public function enqueue(Url $url);

    /**
     * @return Url
     */
    public function dequeue();

    /**
     * @return mixed
     */
    public function purge();

    /**
     * @param Url $url
     * @return mixed
     */
    public function ack(Url $url);
}