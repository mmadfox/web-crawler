<?php

use Madfox\WebCrawler\Queue\Adapter\MemoryAdapter;

class MemoryAdapterTest extends PHPUnit_Framework_TestCase {
   private $adapter;

   public function setUp()
   {
       $this->adapter = new MemoryAdapter();
   }

   public function testEnqueue()
   {
       $res = $this->adapter->enqueue("test");
       $this->assertTrue($res);
   }

    public function testDequeue()
    {
        $this->adapter->enqueue("test1");
        $this->adapter->enqueue("test2");
        $this->adapter->enqueue("test3");
        $this->adapter->enqueue("test4");
        $this->adapter->enqueue("test5");
        $res = $this->adapter->dequeue();
        $this->assertEquals("test5", $res);
        $res = $this->adapter->dequeue();
        $this->assertEquals("test4", $res);
        $res = $this->adapter->dequeue();
        $this->assertEquals("test3", $res);
        $res = $this->adapter->dequeue();
        $this->assertEquals("test2", $res);
        $res = $this->adapter->dequeue();
        $this->assertEquals("test1", $res);
        $res = $this->adapter->dequeue();
        $this->assertNull(null, $res);
    }

    public function testPurge()
    {
        $this->adapter->enqueue("test1");
        $this->adapter->enqueue("test2");
        $this->adapter->enqueue("test3");
        $this->adapter->enqueue("test4");
        $this->adapter->enqueue("test5");
        $this->adapter->purge();
        $res = $this->adapter->dequeue();
        $this->assertNull(null, $res);
    }

}
 