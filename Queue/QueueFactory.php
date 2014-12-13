<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Queue\Adapter\AdapterInterface;
use Madfox\WebCrawler\Queue\Adapter\PhpAMQPAdapter;

class QueueFactory
{
    const QUEUE_ADAPTER_MEMORY = "Memory";
    const QUEUE_ADAPTER_PHPAMQP = "PhpAMQP";
    /**
     * @var array
     */
    private $adapterClasses = [
        self::QUEUE_ADAPTER_MEMORY  => '\\Madfox\\WebCrawler\\Queue\\Adapter\\MemoryAdapter',
        self::QUEUE_ADAPTER_PHPAMQP => '\\Madfox\\WebCrawler\\Queue\\Adapter\\PhpAMQPAdapter',

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

        if ($this->adapterNotExists($this->adapterName)) {
            throw new InvalidArgumentException(sprintf("Adapter %s does not exists", $adapterName));
        }

        $adapterFactoryMethod = $this->adapterName . "Adapter";

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
     * @return PhpAMQPAdapter
     */
    private function PhpAMQPAdapter()
    {
        $defaults = [
            'host'     => 'localhost',
            'port'     => 5672,
            'user'     => 'guest',
            'password' => 'guest',
            'vhost'    => "/",
            'exchange' => 'webcrawler_exchange',
            'queue'    => 'webcrawler_queue'
        ];

        $this->options = array_merge($defaults, $this->options);

        return new PhpAMQPAdapter(
                 $this->options['host'],
                 $this->options['port'],
                 $this->options['user'],
                 $this->options['password'],
                 $this->options['vhost'],
                 $this->options['exchange'],
                 $this->options['queue']
            );
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
