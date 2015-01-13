<?php
namespace Madfox\WebCrawler\Indexer\Storage;

interface StorageInterface
{
    /**
     * @param string $storageName
     * @return mixed
     */
    public function register($storageName);

    /**
     * @param string|int $id
     * @param string $url
     * @param string|null $content
     * @param string|null $page
     * @return mixed
     * @throws \ExceptionInterface
     */
    public function add($id, $url, $content = null, $page = null);

    /**
     * @param int|string $id
     * @return bool
     */
    public function has($id);

    /**
     * @param int|string $id
     * @return mixed
     */
    public function remove($id);

    /**
     * @param string $id
     * @return null|string
     */
    public function get($id);

    /**
     * @return bool
     */
    public function purge();

} 