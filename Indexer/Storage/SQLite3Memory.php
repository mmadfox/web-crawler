<?php
namespace Madfox\WebCrawler\Indexer\Storage;

class SQLite3Memory extends SQLite3
{
    public function __construct($connectionURI)
    {
        parent::__construct($connectionURI);
    }

    /**
     * @return \SQLite3
     */
    public function getConnection()
    {
        if (null === $this->connection) {
            $this->connection = new \SQLite3("file:{$this->connectionURI->getDB()}}?mode=memory&cache=shared");
        }

        return $this->connection;
    }
} 