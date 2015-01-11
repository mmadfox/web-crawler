<?php
namespace Madfox\WebCrawler\Indexer;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Helper\ConnectionURI;

class IndexerFactory
{
    private static $map = [
        'Memory'   => 'memory',
        'Mongo'    => 'mongodb',
        'SQLite3'  => 'sqlite'
    ];

    /**
     * @return array
     */
    public static function registeredStorage()
    {
        return self::$map;
    }

    /**
     * @param string $connectionURI
     * @return Storage\StorageInterface
     * @throws InvalidArgumentException if storage class not found
     */
    public static function createStorage($connectionURI)
    {
        $connectionURI = new ConnectionURI($connectionURI);
        $storageName = null;

        foreach (self::$map as $class => $scheme) {
            if ($connectionURI->getScheme() == $scheme) {
                $storageName = $class;
                break;
            }
        }

        if (null === $storageName) {
            throw new InvalidArgumentException("Storage {$connectionURI->getScheme()} not found");
        }

        $storageClass = "\\Madfox\\WebCrawler\\Indexer\\Storage\\{$storageName}";

        /* @var \Madfox\WebCrawler\Indexer\Storage\StorageInterface $storage */
        $storage = new $storageClass($connectionURI->toString());

        return $storage;
    }

    /**
     * @param string $connectionURI
     *
     *    memory://webcrawler
     *    mongodb://user:password@host:port/databaseName
     *    sqlite://%2Ftmp%2Fdatabase%2Fsqlite%2Fdata.db/databaseName
     *
     * @param string $storageId
     * @return \Madfox\WebCrawler\Indexer\Indexer
     */
    public static function create($connectionURI = "memory://indexer", $storageId = 'webcrawler_indexer')
    {
        $storage = self::createStorage($connectionURI);
        $indexer = new Indexer($storageId, $storage);
        return $indexer;
    }
}