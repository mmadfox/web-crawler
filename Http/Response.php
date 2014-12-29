<?php
namespace Madfox\WebCrawler\Http;

class Response
{
    private $content;
    private $statusCode;
    private $contentType;

    public function __construct($content, $statusCode = 200, $contentType = " text/html")
    {
         $this->content = $content;
         $this->statusCode = intval($statusCode);
         $this->contentType = $contentType;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getContentType()
    {
        return $this->contentType;
    }
}