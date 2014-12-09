<?php
namespace Madfox\WebCrawler\Url;

class UrlFactory
{
    public function create($url)
    {
         return new Url($url);
    }
}