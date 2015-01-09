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
     * @return mixed
     * @throws \ExceptionInterface
     */
    public function add($id);

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
     * @return bool
     */
    public function purge();
} 