<?php
namespace Madfox\WebCrawler\Http;

use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Http\Transfer\TransferInterface;

interface ClientInterface
{
    /**
     * @param Url $url
     * @param  TransferInterface $transfer
     * @return Response
     */
    public function get(Url $url, TransferInterface $transfer = null);

    /**
     * @param Url $url
     * @return mixed
     */
    public function setProxyUrl(Url $url);

    /**
     * @return Url
     */
    public function getProxyUrl();

    /**
     * @param string $userAgent
     * @return mixed
     */
    public function setUserAgent($userAgent);

    /**
     * @return string
     */
    public function getUserAgent();
}