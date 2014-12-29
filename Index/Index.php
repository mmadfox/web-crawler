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
     * @param Url $url
     * @return DocumentInterface|null
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function get(Url $url)
    {
        try {
            $document = null;
            $data = $this->driver->get($url->getId());

            if (!empty($data)) {
                $document = unserialize($data);
            }

        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $document;
    }

    /**
     * @param Url $url
     * @return bool
     */
    public function has(Url $url)
    {
        $return = false;

        try {
            $return = $this->driver->has($url->getId());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $return;
    }

    /**
     * @param Url $url
     * @param DocumentInterface $document | null
     * @return Index
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function add(Url $url, DocumentInterface $document = null)
    {
        try {
            $document->setId($url->getId());
            $this->driver->add($url->getId(), $document->serialize());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $this;
    }

    /**
     * @param Url $url
     * @return bool
     */
    public function remove(Url $url)
    {
        $return = false;

        try {
           $return = $this->driver->remove($url->getId());

        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $return;
    }

    /**
     * @return bool
     */
    public function purge()
    {
        $return = false;

        try {
            $return = $this->driver->purge();

        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $return;
    }
} 