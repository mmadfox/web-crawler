<?php
namespace Madfox\WebCrawler\Indexer;

use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Indexer\Storage\StorageInterface;
use Madfox\WebCrawler\Indexer\Storage\Memory;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Contentable;

class Indexer implements IndexerInterface
{
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var bool
     */
    private $ignoreContent = false;

    /**
     * @param string $storageName
     * @param StorageInterface $storage
     */
    public function __construct($storageName = 'default', StorageInterface $storage = null)
    {
        $storage = null === $storage ? new Memory() : $storage;
        $this->setStorage($storageName, $storage);
    }

    /**
     * @return Indexer
     */
    public function enableIgnoreContent()
    {
        $this->ignoreContent = true;

        return $this;
    }

    /**
     * @param string $storageName
     * @param StorageInterface $storage
     * @return Indexer
     */
    public function setStorage($storageName, StorageInterface $storage)
    {
        $this->storage = $storage;
        $this->storage->register($storageName);

        return $this;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @param Url $url
     * @return bool
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function has(Url $url)
    {
        try {
            return (bool) $this->storage->has($url->getId());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * @param Url $url
     * @param Contentable $content
     * @return Indexer
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function add(Url $url,  Contentable $content = null)
    {
        try {
            if ($this->ignoreContent) $content = null;

            $data = (null === $content ? "" : $content->content());
            $ser  = (null === $content ? "" : serialize($content));

            $this->storage->add($url->getId(), $url->toString(), $data, $ser);

        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }

        return $this;
    }

    /**
     * @param Url $url
     * @return null|string
     * @throws RuntimeException
     */
    public function get(Url $url)
    {
        try {
            $data = $this->storage->get($url->getId());
            $page = !empty($data) ? $data : null;

            return $page;

        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * @param Url $url
     * @return bool
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function remove(Url $url)
    {
        try {
           return $this->storage->remove($url->getId());
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * @return mixed
     * @throws \Madfox\WebCrawler\Exception\RuntimeException
     */
    public function purge()
    {
        try {
            return $this->storage->purge();
        } catch (\Exception $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
} 