<?php
namespace Madfox\WebCrawler\Indexer;

use Madfox\WebCrawler\Url\Url;

interface IndexerInterface
{
    /**
     * @param Url $url
     * @return mixed
     */
    public function has(Url $url);

    /**
     * @param Url $url
     * @return Index
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function add(Url $url);

    /**
     * @param Url $url
     * @return bool
     */
    public function remove(Url $url);

    /**
     * @return bool
     */
    public function purge();

}