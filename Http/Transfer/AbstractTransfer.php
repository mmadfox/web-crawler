<?php
namespace Madfox\WebCrawler\Http\Transfer;

use Madfox\WebCrawler\Url\Url;

abstract class AbstractTransfer implements TransferInterface
{
    protected  $proxy;
    protected  $userAgent;

    /**
     * @param Url $url
     * @return mixed
     */
    public function proxy(Url $url)
    {
        $this->proxy = $url;
    }

    /**
     * @param string $userAgent
     * @return mixed
     */
    public function userAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }
}