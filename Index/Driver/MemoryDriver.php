<?php
namespace Madfox\WebCrawler\Index\Driver;

class MemoryDriver implements DriverInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @construct
     */
    public function __construct()
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