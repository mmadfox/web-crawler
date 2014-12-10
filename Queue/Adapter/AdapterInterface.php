<?php
namespace Madfox\WebCrawler\Queue\Adapter;

interface AdapterInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function enqueue($id);

    /**
     * @return string
     */
    public function dequeue();

    /**
     * @return mixed
     */
    public function purge();

    /**
     * @param string $id
     * @return mixed
     */
    public function ack($id);
}