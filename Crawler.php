<?php
namespace Madfox\WebCrawler;

use Arara\Process\Child;
use Arara\Process\Control;
use Arara\Process\Pool;
use Madfox\WebCrawler\Exception\InvalidArgumentException;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Crawler
{
    /**
     * @var int
     */
    private $workers = 5;
    /**
     * @var Configure[]
     */
    private $sites = [];

    /**
     * @param int $number
     */
    public function setWorkers($number)
    {
        if ($number <= 0 && $number > 100) {
            throw new InvalidArgumentException();
        }

        $this->workers = $number;
    }

    /**
     * @return int
     */
    public function workers()
    {
        return $this->workers;
    }

    /**
     * @param Configure $config
     * @return Crawler
     */
    public function configure(Configure $config)
    {
        $this->sites[] = $config;

        return $this;
    }

    public function run()
    {
        $control = new Control();
        $pool = new Pool($this->workers());
        $pool->start();

        foreach ($this->sites as $site) {
            if (!$pool->isRunning()) {
                continue;
            }

            $container = new ContainerBuilder();
            $config = array();
            $site->load($config, $container);

            $worker = new Worker($container);
            $child = new Child($worker, $control);
            $pool->attach($child);
        }

        $pool->wait();
    }
}