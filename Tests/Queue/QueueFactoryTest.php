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

    public function testCreateQueue()
    {

         $this->factory->create("PhpAMQP", array(
             'host'      => 'localhost',
             'queueName' => 'qn',
             'port'      => ''
         ));
    }
}
 