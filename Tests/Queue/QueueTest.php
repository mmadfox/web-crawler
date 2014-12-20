<?php

use \Madfox\WebCrawler\Queue\Queue;
use \Madfox\WebCrawler\Url\Url;

class QueueTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Queue
     */
    private $queue;

    public function setUp()
    {
         $this->queue = new Queue();
    }

    public function testRegisterChannelInQueue()
    {
         $this->queue->registerChannel("foo");
         $this->queue->registerChannel("bar");
         $this->queue->registerChannel(2);
         $this->queue->registerChannel(5);
         $this->queue->registerChannel(['mad', 'fox']);
         $this->assertCount(6, $this->queue->getRegisteredChannels());
    }

    /**
     * @expectedException Madfox\WebCrawler\Exception\InvalidArgumentException
     */
    public function testRegisterBadChannelNameInQueue()
    {
        $this->queue->registerChannel(new stdClass());
    }

    public function testCountChannels()
    {
        $this->queue->registerChannel("foo");
        $this->queue->registerChannel("bar");
        $this->queue->registerChannel(2);
        $this->queue->registerChannel(5);
        $this->queue->registerChannel(['mad', 'fox']);
        $this->assertEquals(6, $this->queue->getChannelCount());
    }

    public function testHasChannel()
    {
        $this->queue->registerChannel(['foo', 'bar']);
        $this->assertTrue($this->queue->hasChannel('foo'));
        $this->assertTrue($this->queue->hasChannel('bar'));
    }

    public function testAddTaskInQueue()
    {
        $this->queue->registerChannel(['foo', 'bar']);
        $this->queue->enqueue(new Url('http://google.com'), 'foo');
        $url  = $this->queue->dequeue('foo');
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Url\\Url", $url);

        $url = $this->queue->dequeue('foo');
        $this->assertEquals(null, $url);
    }

    public function testPurgeQueue()
    {
        $this->queue->enqueue(new Url('http://google.com'), 'foo');
        $this->queue->purge('foo');
        $url = $this->queue->dequeue('foo');
        $this->assertEquals(null, $url);
    }
}
 