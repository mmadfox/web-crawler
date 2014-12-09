<?php
namespace Madfox\WebCrawler\Url;

final class Url extends \Buzz\Util\Url
{
    public function __construct($url)
    {
         parent::__construct($url);
    }

    public function toString()
    {
        return $this->format("Hs");
    }

    public function getId()
    {
        return md5("todo");
    }
}