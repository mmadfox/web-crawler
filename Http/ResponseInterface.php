<?php
namespace Madfox\WebCrawler\Http;

interface ResponseInterface
{
    /**
     * @return string
     */
    public function getContent();

    /**
     * @return int
     */
    public function getStatusCode();

    /**
     * @return string
     */
    public function getContentType();
}