<?php
namespace Madfox\WebCrawler\Site;

use Madfox\WebCrawler\Site\Site;
use Madfox\WebCrawler\Site\Address;

class SiteFactory
{
    public function create($address)
    {
         return new Site(
             new Address($address)
         );
    }
}