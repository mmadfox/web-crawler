<?php
namespace Madfox\WebCrawler\Queue\Adapter;

use Madfox\WebCrawler\Exception\RuntimeException;

class SQLite3Adapter implements AdapterInterface
{
    private $handle;
    private $limit = 100000;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        if (!class_exists('\SQLite3')) {
            throw new RuntimeException("Could not find driver SQLite3");
        }

        $this->handle = new \SQLite3($filename);
    }

    /**
     * @param null|string $channelName
     * @return bool
     */
    public function addChannel($channelName)
    {
        $this->handle->exec(
            sprintf("CREATE TABLE IF NOT EXISTS %s (id INTEGER PRIMARY KEY AUTOINCREMENT, url VARCHAR(255))",
                $channelName
            )
        );
    }
    /**
     * @param string $id
     * @param string $channelName
     * @return mixed
     */
    public function enqueue($id, $channelName)
    {
        $this->beginTransaction();
        $this->handle->exec(sprintf("INSERT INTO %s (url) VALUES ('%s') ", $channelName, $id));
        $this->endTransaction();
    }

    /**
     * @param string $channelName
     * @return string
     */
    public function dequeue($channelName)
    {
        $this->beginTransaction();
        $result = $this->handle->query(sprintf("SELECT id, url FROM %s LIMIT 1 ", $channelName));
        $row = $result->fetchArray(SQLITE3_ASSOC);
        $url = isset($row['url']) ? $row['url'] : null;
        $this->handle->exec(sprintf("DELETE FROM %s WHERE id = '%s' ", $channelName, $row['id']));
        $this->endTransaction();
        return $url;
    }

    /**
     * @param string $channelName
     * @return mixed
     */
    public function purge($channelName)
    {
        $this->beginTransaction();
        $this->handle->exec(sprintf("DELETE FROM %s ", $channelName));
        $this->endTransaction();
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
        return [];
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    private function beginTransaction()
    {
        $this->handle->exec("BEGIN");
    }

    private function endTransaction()
    {
        $this->handle->exec("COMMIT");
    }
}