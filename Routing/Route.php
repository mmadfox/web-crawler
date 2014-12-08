<?php
namespace Madfox\WebCrawler\Routing;

use Symfony\Component\Routing\Route as SymfonyRoute;

class Route extends SymfonyRoute
{
    public function __construct($path)
    {
        parent::__construct($path);
    }

    public function exec(\Closure $callback)
    {
         return $this;
    }
}