<?php
namespace Madfox\WebCrawler\Url;

final class Url extends \Buzz\Util\Url
{
    public function __construct($url)
    {
         parent::__construct($url);
    }
}