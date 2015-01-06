<?php
namespace Madfox\WebCrawler\Queue\Adapter;

use Madfox\WebCrawler\Exception\RuntimeException;

class MongoAdapter implements AdapterInterface
{
    /**
     * @var \MongoClient
     */
    private $connection;
    private $connectionURI;
    private $dbname;
    private $db;
    private $limit = 10;
    private $channels = [];

    /**
     * @param string $connectionURI mongodb://db1.example.net:2500
     * @see http://docs.mongodb.org/manual/reference/connection-string/
     * @param string $dbname
     */
    public function __construct($connectionURI, $dbname = "queue")
    {
        $this->isSupported();
        $this->connectionURI = $connectionURI;
        $this->dbname = "webcrawler_" . $dbname;
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close(true);
        }
    }

    /**
     * @return \MongoDB
     */
    public function getDb()
    {
        if (null === $this->db) {
            $this->db = $this->getConnection()->selectDB($this->dbname);
        }

        return $this->db;
    }

    /**
     * @param string $channelName
     * @return \MongoCollection
     */
    public function channel($channelName)
    {
        echo 444;



        return $channel = $this->getDb()->selectCollection($channelName);

        if (isset($this->channels[$channelName])) {
            return $this->channels[$channelName];
        } else {
            $this->channels[$channelName] = $channel;
            return $channel;
        }
    }

    /**
     * @param null|string $channelName
     * @return bool
     */
    public function addChannel($channelName)
    {
        $this->getDb()->createCollection($channelName)
                      ->ensureIndex(['status' => 1]);
    }
    /**
     * @param string $id
     * @param string $channelName
     * @return mixed
     */
    public function enqueue($id, $channelName)
    {
        $this->channel($channelName)->insert([
            'url'     =>  $id,
            'status'  =>  1
        ]);
    }

    /**
     * @param string $channelName
     * @return string
     */
    public function dequeue($channelName)
    {
        $res = $this->channel($channelName)->findAndModify(
            ['status' => 1],
            [],
            ['url' => 1],
            ['remove' => true, 'upsert' => false, 'new' => false]
        );

        return $res && isset($res['url']) ? $res['url'] : "";
    }

    /**
     * @param string $channelName
     * @return mixed
     */
    public function purge($channelName)
    {
        $this->channel($channelName)->remove([]);
    }

    /**
     * @param string $channelName
     * @param string $id
     * @return mixed
     */
    public function ack($id, $channelName)
    {

    }

    /**
     * @param string $channelName
     * @return Urls[]
     */
    public function getUrls($channelName)
    {
        return $this->channel($channelName)->find();
    }

    /**
     * @return int
     */
    public function getLimit()
    {
         return $this->limit;
    }

    /**
     * @param string $channelName
     * @return int
     */
    public function count($channelName)
    {
        return $this->channel($channelName)->count();
    }

    /**
     * @return \MongoClient
     */
    private function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \MongoClient($this->connectionURI);
        }

        return $this->connection;
    }

    private function isSupported()
    {
        if (!class_exists('\MongoClient')) {
            throw new RuntimeException("Could not find php-ext mongo");
        }
    }
}