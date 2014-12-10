<?php
namespace Madfox\WebCrawler\Site;

use Buzz\Exception\InvalidArgumentException;

class Url extends \Buzz\Util\Url
{
    const URL_REGEXP = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";

    /**
     * @param string $url
     */
    public function __construct($url)
    {
         if (!preg_match(self::URL_REGEXP, $url)) {
             throw new InvalidArgumentException(sprintf('The URL "%s" is invalid.', $url));
         }

         parent::__construct($url);
    }

    /**
     * @return string
     */
    public function toString()
    {
        $scheme = $this->getScheme() ? $this->getScheme() : "http";
        $hostname = $this->getHostname();
        $user = $this->getUser();
        $password = $this->getPassword();
        $port = $this->format('o');
        $path = $this->format("p");
        $query = $this->getQueryString();
        $fragment = $this->getFragment();
        $url = "";

        $url .= $scheme ."://";

        if (!empty($user) && !empty($password)) {
            $url .= $user . ":" . $password . "@";
        } else if (!empty($user) && empty($password)) {
            $url .= $user . "@";
        }

        $url .= $hostname;

        if (!empty($port)) {
            $url .= ":" . $port;
        }

        if (!empty($path)) {
            $url .= $path;
        }

        if (!empty($query)) {
            $url .= "?" . $query;
        }

        if (!empty($fragment)) {
            $url .= "#" . $fragment;
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return md5($this->toString());
    }

    public function __toString()
    {
        return $this->toString();
    }
}