<?php
namespace Madfox\WebCrawler\Http\Transfer;

use Madfox\WebCrawler\Http\Response;
use Madfox\WebCrawler\Url\Url;

class Buzz extends AbstractTransfer implements TransferInterface
{
    public function get(Url $url)
    {
        return new Response("", 404);
    }
}