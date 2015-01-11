<?php
namespace Madfox\WebCrawler\Helper;

use Madfox\WebCrawler\Exception\InvalidArgumentException;

class ConnectionURI
{
    const REGEXP = "/\b(\w+:\/\/)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    private $user;
    private $password;
    private $port;
    private $host;
    private $db;
    private $scheme;
    private $uri;

    /**
     * @param string $connectionURI
     * @throws InvalidArgumentException
     */
    public function __construct($connectionURI)
    {
        $this->uri = $connectionURI;
        $this->parseURI();
    }

    /**
     * @return string|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return int|null
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string|null
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * @return string|null
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->uri;
    }

    public function __toString()
    {
        return $this->toString();
    }

    private function parseURI()
    {
        if (!preg_match(self::REGEXP, $this->uri)) {
            throw new InvalidArgumentException();
        }

        $components = parse_url($this->uri);
        $this->scheme = isset($components) ? $components['scheme'] : null;
        $this->user = isset($components['user']) ? $components['user'] : null;
        $this->password = isset($components['pass']) ? $components['pass'] : null;
        $this->port = isset($components['port']) ? intval($components['port']) : null;
        $this->host = isset($components['host']) ? urldecode($components['host']) : null;
        $this->db   = isset($components['path']) ? ltrim($components['path'], "/") : "webcrawler";
    }
}