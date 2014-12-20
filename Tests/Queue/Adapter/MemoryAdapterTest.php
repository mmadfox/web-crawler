<?php

use Madfox\WebCrawler\Queue\Adapter\MemoryAdapter;

class MemoryAdapterTest extends PHPUnit_Framework_TestCase {
    /**
     * @var MemoryAdapter
     */
   private $adapter;

   public function setUp()
   {
       $this->adapter = new MemoryAdapter();
   }

   public function testEnqueue()
   {
       $this->adapter->addChannel('foo');
       $res = $this->adapter->enqueue("test", 'foo');
       $this->assertTrue($res);
   }

    public function testDequeue()
    {
        $this->adapter->addChannel('foo');
        $this->adapter->enqueue("test1", 'foo');
        $this->adapter->enqueue("test2", 'foo');
        $this->adapter->enqueue("test3", 'foo');
        $this->adapter->enqueue("test4", 'foo');
        $this->adapter->enqueue("test5", 'foo');
        $res = $this->adapter->dequeue('foo');
        $this->assertEquals("test5", $res);
        $res = $this->adapter->dequeue('foo');
        $this->assertEquals("test4", $res);
        $res = $this->adapter->dequeue('foo');
        $this->assertEquals("test3", $res);
        $res = $this->adapter->dequeue('foo');
        $this->assertEquals("test2", $res);
        $res = $this->adapter->dequeue('foo');
        $this->assertEquals("test1", $res);
        $res = $this->adapter->dequeue('foo');
        $this->assertNull(null, $res);
    }

    public function testPurge()
    {
        $this->adapter->addChannel('foo');
        $this->adapter->enqueue("test1", 'foo');
        $this->adapter->enqueue("test2", 'foo');
        $this->adapter->enqueue("test3", 'foo');
        $this->adapter->enqueue("test4", 'foo');
        $this->adapter->enqueue("test5", 'foo');
        $this->adapter->purge('foo');
        $res = $this->adapter->dequeue('foo');

        $this->assertNull(null, $res);
    }

}
 