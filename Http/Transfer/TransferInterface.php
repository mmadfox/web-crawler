<?php
namespace Madfox\WebCrawler\Http\Transfer;

use Madfox\WebCrawler\Url\Url;

interface TransferInterface
{
    /**
     * @param string Url $url
     * @return mixed
     */
    public function get(Url $url);

    /**
     * @param Url $url
     * @return mixed
     */
    public function proxy(Url $url);

    /**
     * @param string $userAgent
     * @return mixed
     */
    public function userAgent($userAgent);
}