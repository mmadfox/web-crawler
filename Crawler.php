<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Site\Address;
use Madfox\WebCrawler\Site\Site;

class Crawler
{
    private $sites = [];

    public function site($site)
    {
        $site = new Site(new Address($site));
        $this->sites[$site->getAddress()->getHostname()] = $site;
        return $site;
    }

    public function isRunning()
    {

    }

    public function run()
    {

    }
}