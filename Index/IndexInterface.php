<?php
namespace Madfox\WebCrawler\Index;

use Madfox\WebCrawler\Url\Url;

interface IndexInterface
{
    /**
     * @param Url $url
     * @return DocumentInterface|null
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function get(Url $url);

    /**
     * @param Url $url
     * @return mixed
     */
    public function has(Url $url);

    /**
     * @param Url $url
     * @param DocumentInterface $document | null
     * @return Index
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function add(Url $url, DocumentInterface $document = null);

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