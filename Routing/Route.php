<?php
namespace Madfox\WebCrawler\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;
use Madfox\WebCrawler\Site\Site;

class Route extends SymfonyRoute
{
    private $command;
    private $site;

    public function __construct($path)
    {
        parent::__construct($path);
    }

    public function setSite(Site $site)
    {
        $this->site = $site;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function exec($command = null)
    {
         $this->command = $command;
         return $this;
    }
}