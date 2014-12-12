<?php

use Madfox\WebCrawler\Queue\QueueFactory;

class QueueFactoryTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Madfox\WebCrawler\Queue\QueueFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = new QueueFactory();
    }

    public function testCreateQueueWithParameters()
    {
         $options = [
             'host'  => 'localhost',
             'queue' => 'qn',
         ];

         foreach ($this->factory->supportedAdapters() as $adapterName => $class) {
             $q = $this->factory->create($adapterName, $options);
             $this->assertInstanceOf("Madfox\\WebCrawler\\Queue\\Queue", $q, "Adapter {$adapterName} invalid");
         }
    }

    public function testCreateQueueWithoutParameters()
    {
        $options = [];

        foreach ($this->factory->supportedAdapters() as $adapterName => $class) {
            $q = $this->factory->create($adapterName,  $options);
            $this->assertInstanceOf("Madfox\\WebCrawler\\Queue\\Queue", $q, "Adapter {$adapterName} invalid");
        }
    }

    /**
     * @expectedException Madfox\WebCrawler\Exception\InvalidArgumentException
     */
    public function testCreateBadAdapterQueue()
    {
        $options = [] ;

        foreach (['foo' => '///', 'bar' => '///'] as $adapterName => $class) {
            $q = $this->factory->create($adapterName,  $options);
            $this->assertInstanceOf("Madfox\\WebCrawler\\Queue\\Queue", $q, "Adapter {$adapterName} invalid");
        }
    }
}
 