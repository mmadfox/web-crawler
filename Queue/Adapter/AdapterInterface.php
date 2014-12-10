<?php
namespace Madfox\WebCrawler\Queue\Adapter;

interface AdapterInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function enqueue($url);

    /**
     * @return string
     */
    public function dequeue();

    /**
     * @return mixed
     */
    public function purge();

    /**
     * @param string $url
     * @return mixed
     */
    public function ack($url);
}