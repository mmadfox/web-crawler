<?php
namespace Madfox\WebCrawler\Http;

use Madfox\WebCrawler\Exception\LogicException;
use Madfox\WebCrawler\Http\Transfer\Buzz;
use Madfox\WebCrawler\Http\Transfer\TransferInterface;
use Madfox\WebCrawler\Url\Url;

class Client implements ClientInterface
{
    private $proxy = null;
    private $userAgent = null;

    /**
     * @param Url $url
     * @param TransferInterface $transfer
     * @return Response
     * @throws BadRequestException
     * @throws LogicException
     */
    public function get(Url $url, TransferInterface $transfer = null)
    {
        if (null === $transfer) {
            $transfer = new Buzz();
        }

        if ($this->proxyIsEnabled()) {
            $transfer->proxy($this->proxy);
        }

        if ($this->userAgentIsEnabled()) {
            $transfer->userAgent($this->userAgent);
        }

        try {
            $response = $transfer->get($url);

            if (!$response instanceof Response) {
                throw new LogicException();
            }

            return $response;

        } catch(LogicException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new BadRequestException($e->getMessage());
        }
    }

    /**
     * @param string $userAgent
     * @return Client
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param Url $url
     * @return $this|mixed
     */
    public function setProxyUrl(Url $url)
    {
        $this->proxy = $url;

        return $this;
    }

    /**
     * @return Url|null
     */
    public function getProxyUrl()
    {
        return $this->proxy;
    }

    /**
     * @return bool
     */
    public function proxyIsEnabled()
    {
        return $this->proxy instanceof Url;
    }

    /**
     * @return bool
     */
    public function userAgentIsEnabled()
    {
        return null !== $this->userAgent;
    }
}