<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Queue\Adapter\AdapterInterface;
use Madfox\WebCrawler\Queue\Adapter\AMQPAdapter;
use Madfox\WebCrawler\Queue\Adapter\SQLite3Adapter;

class QueueFactory
{
    const QUEUE_ADAPTER_MEMORY  = "Memory";
    const QUEUE_ADAPTER_AMQP    = "AMQP";
    const QUEUE_ADAPTER_SQLITE3 = "SQLite3";

    /**
     * @var array
     */
    private $adapterClasses = [
        self::QUEUE_ADAPTER_MEMORY  => '\\Madfox\\WebCrawler\\Queue\\Adapter\\MemoryAdapter',
        self::QUEUE_ADAPTER_AMQP    => '\\Madfox\\WebCrawler\\Queue\\Adapter\\AMQPAdapter',
        self::QUEUE_ADAPTER_SQLITE3 => '\\Madfox\\WebCrawler\\Queue\\Adapter\\SQLite3Adapter'
    ];

    /**
     * @var array
     */
    private $options = [];
    /**
     * @var string
     */
    private $adapterName;

    /**
     * @param string $adapterName
     * @param array $options
     * @return Queue
     * @throws InvalidArgumentException if adapter does not exists
     * @throws RuntimeException
     */
    public function create($adapterName = self::QUEUE_ADAPTER_MEMORY, array $options = [])
    {
        $this->options = $options;
        $this->adapterName = (string)$adapterName;

        if ($this->adapterNotExists()) {
            throw new InvalidArgumentException(sprintf("Adapter %s does not exists", $adapterName));
        }

        $adapterFactoryMethod = "create" . $this->adapterName . "Adapter";

        if (method_exists($this, $adapterFactoryMethod)) {
            $adapter = call_user_func(array($this, $adapterFactoryMethod));
        } else {
            $class = $this->getAdapterClass();
            $adapter = new $class();
        }

        if (!$adapter instanceof AdapterInterface) {
            throw new RuntimeException();
        }

        $queue = new Queue($adapter);
        return $queue;
    }

    /**
     * @return array
     */
    public function supportedAdapters()
    {
        return $this->adapterClasses;
    }

    /**
     * @return AMQPAdapter
     */
    private function createAMQPAdapter()
    {
        $defaults = ['connectionURI' => ""];
        $this->options = array_merge($defaults, $this->options);
        return new AMQPAdapter($this->options['connectionURI']);
    }

    /**
     * @return SQLite3Adapter
     */
    private function createSQLite3Adapter()
    {
        $defaults = ['filepath' => "/tmp/webcrawler.queue.db"];
        $this->options = array_merge($defaults, $this->options);
        return new SQLite3Adapter($this->options['filepath']);
    }

    private function adapterNotExists()
    {
        return !isset($this->adapterClasses[$this->adapterName]);
    }

    private function getAdapterClass()
    {
        return $this->adapterClasses[$this->adapterName];
    }
}
