<?php
namespace Madfox\WebCrawler\Http;

use Madfox\WebCrawler\Url\Url;

class Client
{
    private $userAgent;

    public function get(Url $url)
    {
        return new Response('<h1>Test</h1>', 200);
    }
}