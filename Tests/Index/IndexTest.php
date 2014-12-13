<?php
use \Madfox\WebCrawler\Index\Index;
use Madfox\WebCrawler\Site\Url;

class IndexTest extends PHPUnit_Framework_TestCase {

   private $index;
   private $driver;
   private $driverWithExc;
   private $indexWithExc;

   public function setUp()
   {
       $this->driver = $this->getDriver();
       $this->driverWithExc = $this->getDriverWithExceptions();
       $this->index = new Index($this->driver);
       $this->indexWithExc = new Index($this->driverWithExc);
   }

   public function testAddDocumentInTheIndex()
   {
       $url = $this->getUrl();
       $result = $this->index->add($url, $this->getDocument());
       $this->assertInstanceOf("\\Madfox\\WebCrawler\\Index\\Index", $result);
   }

    /**
     * @expectedException Madfox\WebCrawler\Exception\RuntimeException
     */
   public function testAddDocumentInTheIndexWithException()
   {
        $url = $this->getUrl();
        $result = $this->indexWithExc->add($url, $this->getDocument());
        $this->assertInstanceOf("\\Madfox\\WebCrawler\\Index\\Index", $result);
   }

   public function testGetDocumentFromIndex()
   {
       $url = $this->getUrl();
       $document = $this->index->get($url);
       $this->assertInstanceOf("Madfox\\WebCrawler\\Index\\DocumentInterface", $document);

       $bool = $this->index->has($url);
       $this->assertTrue($bool);
   }
    /**
     * @expectedException Madfox\WebCrawler\Exception\RuntimeException
     */
   public function testGetDocumentFromIndexWithException()
   {
       $url = $this->getUrl();
       $this->indexWithExc->get($url);
   }

    /**
     * @expectedException Madfox\WebCrawler\Exception\RuntimeException
     */
   public function testHasDocumentFromIndexWithException()
   {
       $url = $this->getUrl();
       $this->indexWithExc->has($url);
   }

    /**
     * @expectedException Madfox\WebCrawler\Exception\RuntimeException
     */
    public function testRemoveDocumentFromIndexWithException()
    {
        $url = $this->getUrl();
        $this->indexWithExc->remove($url);
    }

   public function testRemoveDocumentFromIndex()
   {
       $url = $this->getUrl();
       $result = $this->index->remove($url);
       $this->assertTrue($result);
   }

   private function getUrl()
   {
       return new Url("http://google.com");
   }

   private function getDriver()
   {
       $driver = $this->getMock("Madfox\\WebCrawler\\Index\\Driver\\DriverInterface");
       $driver->method('add')->will($this->returnSelf());
       $driver->method('get')->willReturn(serialize($this->getDocument()));
       $driver->method('has')->willReturn(true);
       $driver->method('remove')->willReturn(true);
       return $driver;
   }

   private function getDriverWithExceptions()
   {
       $driver = $this->getMock("Madfox\\WebCrawler\\Index\\Driver\\DriverInterface");
       $driver->method('add')->will($this->throwException(new \Exception));
       $driver->method('get')->will($this->throwException(new \Exception));
       $driver->method('has')->will($this->throwException(new \Exception));
       $driver->method('remove')->will($this->throwException(new \Exception));
       return $driver;
   }

   private function getDocument()
   {
       $document = new \Madfox\WebCrawler\Index\Document(1);
       return $document;
   }
}
 