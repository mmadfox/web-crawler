<?php
namespace Madfox\WebCrawler\Site;

use Buzz\Exception\InvalidArgumentException;
use Buzz\Util\Url;
use Madfox\WebCrawler\Exception\InvalidAddressException;

class Address implements AddressInterface
{
    const REGEX_ADDRESS = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    /**
     * @var Url
     */
    private $url;

    /**
     * @param string $url
     * @throws InvalidAddressException if address site is invalid
     */
    public function __construct($url)
    {
        try {
            $this->url = new Url($url);
            $this->validate();
        } catch (InvalidArgumentException $e) {
            throw new InvalidAddressException(sprintf('The address site "%s" is invalid.', $url));
        }
    }

    public function getScheme()
    {
        return $this->url->getScheme();
    }

    public function getHostname()
    {
        return $this->url->getHostname();
    }

    public function getPort()
    {
        return (int) $this->url->getPort();
    }

    public function getUser()
    {
        return $this->url->getUser();
    }

    public function getPassword()
    {
        return $this->url->getPassword();
    }

    public function getPath()
    {
        return $this->url->getPath();
    }

    public function toString()
    {
        $s = $this->getScheme();
        $h = $this->getHostname();
        $p = $this->getPort();
        $u = $this->getUser();
        $x = $this->getPassword();
        $q = $this->getPath();

        $url = $s . "://";

        if (empty($x) && !empty($u)) {
            $url .= $u . "@" . $h;
        } else if (!empty($x) && !empty($u)) {
            $url .= $u . ":" . $x . "@" . $h;
        } else {
            $url .= $h;
        }

        if (!empty($p)) {
            $url .= ":" . $p;
        }

        if (!empty($q)) {
            $url .= $q;
        }

        return $url;
    }

    public function __toString()
    {
        return $this->toString();
    }

    public function validate()
    {
        if (!preg_match(self::REGEX_ADDRESS, $this->toString())) {
            throw new InvalidAddressException(sprintf('The address site "%s" is invalid.', $this->toString()));
        }
    }
} 