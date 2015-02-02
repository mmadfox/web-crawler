<?php
namespace Madfox\WebCrawler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Configure
{
    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(dirname($this->resource)));
        $loader->load(basename($this->resource));
    }
}