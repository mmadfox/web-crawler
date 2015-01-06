<?php
namespace Madfox\WebCrawler\Index\Driver;

class MongoDriver implements DriverInterface
{
    private $conn;
    private $host;
    private $port;
    private $dbName;
    private $colName;
    private $user;
    private $password;

    /**
     * @param string $host
     * @param int $port
     * @param string $databaseName
     * @param string $collectionName
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $port, $databaseName = "webcrawler", $collectionName = "wcindex", $user = null, $password = null)
    {
        $this->host     = (string) $host;
        $this->port     = (int)    $port;
        $this->dbName   = (string) $databaseName;
        $this->colName  = (string) $collectionName;
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function add($id)
    {
        $this->getConnection()->insert(array(
            'uid'   => $id
        ));
    }

    /**
     * @param mixed $id
     * @return null|string
     */
    public function get($id)
    {
        $return = null;

        $doc = $this->getConnection()->findOne([
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
        $this->getConnection()->remove(['uid' => $id]);
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function has($id)
    {
        $doc = $this->getConnection()->findOne(['uid' => $id], ['_id']);
        return !empty($doc);
    }

    /**
     * @return bool|void
     */
    public function purge()
    {
        $this->getConnection()->drop();
    }

    private function getConnection()
    {
        if (null === $this->conn) {
            if ($this->isAuthConnection()) {
                $mongoClient = new \MongoClient(sprintf("mongodb://%s:%s@%s:%s",
                    $this->user,
                    $this->password,
                    $this->host,
                    $this->port ));
            } else {
                $mongoClient = new \MongoClient(sprintf("mongodb://%s:%s", $this->host, $this->port));
            }

            $db = $mongoClient->selectDB($this->dbName);
            $this->conn = $db->selectCollection($this->colName);
            $this->conn->ensureIndex(['uid' => 1]);
        }

        return $this->conn;
    }

    private function isAuthConnection()
    {
        return !is_null($this->user) && !is_null($this->password);
    }

} 