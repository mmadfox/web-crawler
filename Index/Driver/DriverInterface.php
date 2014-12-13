<?php
namespace Madfox\WebCrawler\Index\Driver;

interface DriverInterface
{
    /**
     * @param string|int $id
     * @param string $data
     * @return mixed
     * @throws \ExceptionInterface
     */
    public function add($id, $data = "");

    /**
     * @return string
     */
    public function get($id);

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