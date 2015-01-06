<?php
namespace Madfox\WebCrawler\Index;

use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Index\Driver\DriverInterface;
use Madfox\WebCrawler\Url\Url;

class Index implements IndexInterface
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param DriverInterface $driver
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param Url $url
     * @return bool
     */
    public function has(Url $url)
    {
        try {
            return $this->driver->has($url->getId());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * @param Url $url
     * @return Index
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function add(Url $url)
    {
        try {
            $this->driver->add($url->getId());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $this;
    }

    /**
     * @param Url $url
     * @return bool
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function remove(Url $url)
    {
        try {
           return $this->driver->remove($url->getId());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * @return bool
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function purge()
    {
        try {
            return $this->driver->purge();
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
} 