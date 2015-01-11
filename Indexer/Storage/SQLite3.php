<?php
namespace Madfox\WebCrawler\Indexer\Storage;

use Madfox\WebCrawler\Exception\LogicException;
use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Helper\ConnectionURI;

class SQLite3 implements StorageInterface
{
    /**
     * @var ConnectionURI
     */
    private $connectionURI;
    /**
     * @var \SQLite3
     */
    private $connection;
    /**
     * @var string
     */
    private $tableName;

    /**
     * @param string $connectionURI
     */
    public function __construct($connectionURI)
    {
        $this->connectionURI = new ConnectionURI($connectionURI);
        $this->isSupported();
        $this->getConnection();
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        if (null === $this->tableName) {
            throw new LogicException("You must call method SQLite3::register() before work");
        }

        return $this->tableName;
    }

    /**
     * @param string $storageName
     * @return mixed|void
     */
    public function register($storageName)
    {
        $this->connection->exec(
            sprintf("CREATE TABLE IF NOT EXISTS %s (id CHAR (32), url VARCHAR (255), content TEXT, UNIQUE(id) ON CONFLICT REPLACE)",
                $storageName)
        );

        $this->tableName = $storageName;
    }

    /**
     * @return \SQLite3
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \SQLite3($this->connectionURI->getHost());
        }

        return $this->connection;
    }

    /**
     * @param string|int $id
     * @param string $url
     * @param string|null $content
     * @return mixed
     * @throws \ExceptionInterface
     * @throws LogicException if not call SQLite::register()
     */
    public function add($id, $url, $content = null)
    {
        $table = $this->getTableName();

        $this->getConnection()->exec(
            sprintf("INSERT OR REPLACE INTO %s ('id', 'url', 'content') VALUES ('%s', '%s', '%s') ",
                $table,
                $id,
                $url,
                $content
            )
        );
    }

    /**
     * @param int|string $id
     * @return bool
     * @throws LogicException if not call SQLite::register()
     */
    public function has($id)
    {
        $table = $this->getTableName();

        $result = $this->getConnection()->querySingle(sprintf("SELECT id FROM %s WHERE id = '%s' ",
            $table,
            $id));

        return $id == $result;
    }

    /**
     * @param int|string $id
     * @return mixed
     * @throws LogicException if not call SQLite::register()
     */
    public function remove($id)
    {
        $table = $this->getTableName();
        $this->getConnection()->exec(sprintf("DELETE FROM %s WHERE id = '%s' ", $table, $id));
    }

    /**
     * @return bool
     * @throws LogicException if not call SQLite::register()
     */
    public function purge()
    {
        $table = $this->getTableName();
        $this->getConnection()->exec(sprintf("DELETE FROM %s", $table));
    }

    /**
     * @param string $id
     * @return null|string
     */
    public function get($id)
    {
        $table = $this->getTableName();

        $result = $this->getConnection()->query(
            sprintf("SELECT id, url, content FROM %s WHERE id = '%s' LIMIT 1 ",
              $table,
              $id
            )
        );

        $data = $result->fetchArray(SQLITE3_ASSOC);
        return isset($data['content']) ? $data['content'] : "";
    }
    
    public function isSupported()
    {
        if (!class_exists('\SQLite3')) {
            throw new RuntimeException("Could not find driver SQLite3");
        }
    }

}