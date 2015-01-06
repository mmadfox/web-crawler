<?php
namespace Madfox\WebCrawler\Index\Driver;

use Madfox\WebCrawler\Exception\RuntimeException;

class SQLite3Driver implements DriverInterface
{
    private $handle;
    private $table;

    /**
     * @param string $filename
     * @param string $name default webcrawler_index
     */
    public function __construct($filename, $name = "webcrawler_index")
    {
        if (!class_exists('\SQLite3')) {
            throw new RuntimeException("Could not find driver SQLite3");
        }

        $this->handle = new \SQLite3($filename);
        $this->table  = (string) $name;

        $this->createTable();
    }

    public function __destruct()
    {
        $this->handle->close();
    }

    /**
     * @param string|int $id
     * @return mixed
     * @throws \ExceptionInterface
     */
    public function add($id)
    {
        $this->handle->exec(sprintf("INSERT INTO %s (id) VALUES ('%s') ", $this->table, $id));
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function has($id)
    {
        $result = $this->handle->querySingle(sprintf("SELECT 1 FROM %s WHERE id = '%s' ", $this->table, $id));
        return 1 === $result;
    }

    /**
     * @param int|string $id
     * @return mixed
     */
    public function remove($id)
    {
        $this->handle->exec(sprintf("DELETE FROM %s WHERE id = '%s' ", $this->table, $id));
    }

    /**
     * @return bool
     */
    public function purge()
    {
        $this->handle->exec(sprintf("DELETE FROM %s", $this->table));
    }

    private function createTable()
    {
        $sql  = "CREATE TABLE IF NOT EXISTS {$this->table} (id char(32)) ";
        $this->handle->exec($sql);
    }
}