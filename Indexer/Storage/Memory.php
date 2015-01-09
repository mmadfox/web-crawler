<?php
namespace Madfox\WebCrawler\Indexer\Storage;

class Memory implements StorageInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $storageName
     * @return mixed|void
     */
    public function register($storageName)
    {
        $this->data = [];
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function add($id)
    {
        $this->data[$id] = 1;
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->data[$id]) ? true : false;
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function remove($id)
    {
        unset($this->data[$id]);
    }

    /**
     * @return bool|void
     */
    public function purge()
    {
        $this->data = [];
    }

} 