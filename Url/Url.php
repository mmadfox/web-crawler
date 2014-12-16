<?php
namespace Madfox\WebCrawler\Url;

use Madfox\WebCrawler\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Url\Utils\UrlUtil;

class Url
{
    const URL_REGEXP = "/\b(?:(?:https?):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    private $url;
    private $components = [];

    /**
     * @param string $url
     */
    public function __construct($url)
    {
         if (!preg_match(self::URL_REGEXP, $url)) {
             throw new InvalidArgumentException(sprintf('The URL "%s" is invalid.', $url));
         }

         $this->url = $url;
         $components = parse_url($url);

         if (false === $components) {
            throw new InvalidArgumentException(sprintf('The URL "%s" is invalid.', $url));
         }

         $this->components = array_merge([
             'scheme'    =>  null,
             'host'      =>  null,
             'port'      =>  null,
             'user'      =>  null,
             'password'  =>  null,
             'path'      =>  null,
             'query'     =>  null,
             'fragment'  =>  null

         ], $components);

    }

    public function equal(Url $url)
    {
        return $this->getId() === $url->getId();
    }

    public function equalHost(Url $url)
    {
        return $this->host() == $url->host();
    }

    public function host()
    {
        return $this->components['host'];
    }

    public function scheme()
    {
        return $this->components['scheme'];
    }

    public function port()
    {
        return $this->components['port'];
    }

    public function user()
    {
        return $this->components['user'];
    }

    public function password()
    {
        return $this->components['password'];
    }

    public function query()
    {
        return $this->components['query'];
    }

    public function path()
    {
        return $this->components['path'];
    }

    public function fragment()
    {
        return $this->components['fragment'];
    }

    public function hostname()
    {
        $components = $this->components;

        foreach (['path', 'query', 'fragment'] as $key) {
            unset($components[$key]);
        }

        return UrlUtil::buildUrl($components);
    }

    public function resource()
    {
        $components = $this->components;

        foreach (['scheme', 'host', 'port', 'user', 'password'] as $key) {
            unset($components[$key]);
        }

        return UrlUtil::buildUrl($components);
    }

    /**
     * @return string
     */
    public function toString()
    {
        return UrlUtil::buildUrl($this->components);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return md5($this->toString());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}