<?php
namespace Madfox\WebCrawler\Http;

class Response implements ResponseInterface
{
    private $content;
    private $statusCode;
    private $contentType;

    /**
     * @param string $content
     * @param int $statusCode
     * @param string $contentType
     */
    public function __construct($content, $statusCode = 200, $contentType = "text/html")
    {
         $this->content = $content;
         $this->statusCode = intval($statusCode);
         $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}