<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 29.12.14
 * Time: 8:59
 */

use Madfox\WebCrawler\Http\Response;

class ResponseTest extends PHPUnit_Framework_TestCase {

    public function testCreateResponse()
    {
        $response = new Response("<h1>test</h1>");
        $this->assertInstanceOf("Madfox\\WebCrawler\\Http\\Response", $response);
    }

    public function testStatusCode()
    {
        $response = new Response("<h1>test</h1>", 404);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testContentType()
    {
        $response = new Response("<h1>test</h1>", 200, "text/plain");
        $this->assertEquals("text/plain", $response->getContentType());
    }
}
 