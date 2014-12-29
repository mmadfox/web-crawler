<?php
namespace Madfox\WebCrawler\Index;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Index\Driver\DriverInterface;
use Madfox\WebCrawler\Index\Driver\MongoDriver;
use Madfox\WebCrawler\Exception\RuntimeException;

class IndexFactory
{
    const INDEX_DRIVER_MONGO  = "Mongo";
    const INDEX_DRIVER_MEMORY = "Memory";
    /**
     * @var array
     */
    private $driverClasses = [
        self::INDEX_DRIVER_MEMORY   => "\\Madfox\\WebCrawler\\Index\\Driver\\MemoryDriver",
        self::INDEX_DRIVER_MONGO    => "\\Madfox\\WebCrawler\\Index\\Driver\\MongoDriver"
    ];

    /**
     * @var array
     */
    private $options = [];
    /**
     * @var string
     */
    private $driverName;

    /**
     * @param string $driverName
     * @param array $options
     * @return Index
     * @throws RuntimeException
     */
    public function create($driverName = self::INDEX_DRIVER_MEMORY, array $options = [])
    {
        $this->options = $options;
        $this->driverName = (string) $driverName;

        if ($this->driverNotExists()) {
            throw new InvalidArgumentException(sprintf("Driver %s does not exists", $driverName));
        }

        $driverFactoryMethod = $this->driverName . "Driver";

        if (method_exists($this, $driverFactoryMethod)) {
            $driver = call_user_func(array($this, $driverFactoryMethod));
        } else {
            $class  = $this->getDriverClass();
            $driver = new $class();
        }

        if (!$driver instanceof DriverInterface) {
            throw new RuntimeException();
        }

        $index = new Index($driver);
        return $index;
    }

    private function MongoDriver()
    {
        $defaults = [
            'host'     => 'localhost',
            'port'     => 27017,
            'user'     => '',
            'password' => '',
        ];

        $this->options = array_merge($defaults, $this->options);

        return new MongoDriver(
            $this->options['host'],
            $this->options['port'],
            $this->options['db'],
            $this->options['collection'],
            $this->options['user'],
            $this->options['password']
        );

    }

    private function driverNotExists()
    {
        return !isset($this->driverClasses[$this->driverName]);
    }

    private function getDriverClass()
    {
        return $this->driverClasses[$this->driverName];
    }
}