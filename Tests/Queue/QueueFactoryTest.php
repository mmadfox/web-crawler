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

        $q = $this->factory->create(QueueFactory::QUEUE_ADAPTER_MEMORY, $options);
        $this->assertInstanceOf("Madfox\\WebCrawler\\Queue\\Queue", $q, "Adapter  invalid");
    }

    public function testCreateQueueWithoutParameters()
    {
        $options = [];

        $q = $this->factory->create(QueueFactory::QUEUE_ADAPTER_MEMORY,  $options);
        $this->assertInstanceOf("Madfox\\WebCrawler\\Queue\\Queue", $q, "Adapter invalid");
    }

    /**
     * @expectedException Madfox\WebCrawler\Exception\InvalidArgumentException
     */
    public function testCreateBadAdapterQueue()
    {
        $options = [] ;

        $q = $this->factory->create("BadAdapter",  $options);
        $this->assertInstanceOf("Madfox\\WebCrawler\\Queue\\Queue", $q, "Adapter invalid");
    }
}
 