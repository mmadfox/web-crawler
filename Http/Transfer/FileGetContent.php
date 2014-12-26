<?php
namespace Madfox\WebCrawler\Http\Transfer;

use Madfox\WebCrawler\Http\Response;
use Madfox\WebCrawler\Url\Url;

class FileGetContent extends AbstractTransfer implements TransferInterface
{
    public function get(Url $url)
    {
        $content = file_get_contents($url->toString());

        return new Response();
    }
}