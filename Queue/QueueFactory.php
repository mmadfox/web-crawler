<?php
namespace Madfox\WebCrawler\Queue;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Queue\Adapter\AdapterInterface;
use Madfox\WebCrawler\Queue\Adapter\PhpAMQPAdapter;

class QueueFactory
{
    /**
     * @var array
     */
    private $adapters = [
        'Memory' => 'Madfox\\WebCrawler\\Queue\\Adapter\\MemoryAdapter',
        'PhpAMQP' => 'Madfox\\WebCrawler\\Queue\\Adapter\\PhpAMQPAdapter'
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
    public function create($adapterName = "Memory", array $options = [])
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
        return !array_key_exists($this->adapterName, $this->adapters);
    }

    private function getAdapterClass()
    {
        return $this->adapters[$this->adapterName];
    }
}
