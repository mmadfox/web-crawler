<?php
namespace Madfox\WebCrawler\Indexer\Storage;

use Madfox\WebCrawler\Exception\LogicException;
use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Helper\ConnectionURI;

class Mongo implements StorageInterface
{
    /**
     * @var \MongoClient
     */
    private $connection;
    /**
     * @var ConnectionURI
     */
    private $connectionURI;
    /**
     * @var \MongoCollection
     */
    private $collection;
    /**
     * @var \MongoDB
     */
    private $db;

    /**
     * @example mongodb://user:password@localhost:27017/dbname
     * @param string $connectionUri
     */
    public function __construct($connectionUri)
    {
        $this->connectionURI = new ConnectionURI($connectionUri);
        $this->isSupported();
        $this->getConnection();
    }

    /**
     * @return void
     */
    public function __destruct()
    {
        $con = $this->getConnection();
        if ($con) { $con->close(true);}
    }

    /**
     * @param string $storageName
     * @return mixed|void
     */
    public function register($storageName)
    {
        if (null === $this->collection) {
            $this->collection = $this->getDB()->selectCollection($storageName);
            $this->collection->ensureIndex(['uid' => 1]);
        }
    }

    /**
     * @return \MongoDB
     */
    public function getDB()
    {
        if (null === $this->db) {
            $this->db = $this->getConnection()->selectDB($this->connectionURI->getDB());
        }

        return $this->db;
    }

    /**
     * @return \MongoCollection
     */
    public function getCollection()
    {
        if (null === $this->collection) {
            throw new LogicException("You must call method Mongo::register(collectionName) before work");
        }

        return $this->collection;
    }

    /**
     * @param int|string $id
     * @param string $url
     * @param string|null $content
     * @return mixed|void
     */
    public function add($id, $url, $content = null)
    {
        $this->getCollection()->update(
            ['uid' => $id],
            ['uid' => $id, 'url' => $url, 'data' => $content],
            ['upsert' => true]
        );
    }

    /**
     * @param mixed $id
     * @return null|string
     */
    public function get($id)
    {
        $return = null;

        $doc = $this->getCollection()->findOne([
            'uid' => $id
        ]);

        if ($doc) {
            $return = $doc['data'];
        }

        return $return;
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function remove($id)
    {
        $this->getCollection()->remove(['uid' => $id]);
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function has($id)
    {
        $doc = $this->getCollection()->findOne(['uid' => $id], ['_id']);
        return !empty($doc);
    }

    /**
     * @return bool|void
     */
    public function purge()
    {
        $this->getCollection()->remove([]);
    }

    /**
     * @return \MongoClient
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \MongoClient($this->connectionURI->toString());
        }

        return $this->connection;
    }

    /**
     * @return void
     */
    public function isSupported()
    {
        if (!class_exists('\MongoClient')) {
            new RuntimeException("Could not find php-ext mongo");
        }
    }
} 