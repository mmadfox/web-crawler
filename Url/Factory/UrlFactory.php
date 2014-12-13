<?php
namespace Madfox\WebCrawler\Url\Factory;

use Madfox\WebCrawler\Exception\RuntimeException;
use Madfox\WebCrawler\Url\Factory\Strategy\StrategyInterface;
use Madfox\WebCrawler\Url\Url;

class UrlFactory
{
    /**
     * @var array
     */
    private $strategyClasses = [
        'MergeTwoInstance'           =>  "\\Madfox\\WebCrawler\\Url\\Factory\\Strategy\\MergeTwoInstance",
        'MergeTwoString'             =>  "\\Madfox\\WebCrawler\\Url\\Factory\\Strategy\\MergeTwoString",
        'MergeOneStringOneInstance'  =>  "\\Madfox\\WebCrawler\\Url\\Factory\\Strategy\\MergeOneStringOneInstance",
        'MergeDafault'               =>  "\\Madfox\\WebCrawler\\Url\\Factory\\Strategy\\MergeDefault",
    ];

    /**
     * @param string $url
     * @return Url
     * @throws \Madfox\WebCrawler\Exception\InvalidArgumentException
     */
    public function create($url)
    {
        return new Url($url);
    }

    /**
     * @param string $strategyName
     * @param string $strategyClass Path to the strategy class
     * @return UrlFactory
     */
    public function addStrategy($strategyName, $strategyClass)
    {
        $this->removeStrategy($strategyName);
        $this->strategyClasses[$strategyName] = $strategyClass;

        return $this;
    }

    /**
     * @param string $strategyName
     * @return null | string
     */
    public function getStrategy($strategyName)
    {
        return isset($this->strategyClasses[$strategyName])
               ? $this->strategyClasses[$strategyName] : null;
    }

    /**
     * @param string $strategyName
     * @return UrlFactory
     */
    public function removeStrategy($strategyName)
    {
        unset($this->strategyClasses[$strategyName]);

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->strategyClasses);
    }

    /**
     * @param Url|string $url1
     * @param Url|string $url2
     * @return null|Url
     * @throws \Madfox\WebCrawler\Exception\InvalidArgumentException
     * @throws \Madfox\WebCrawler\Exception\RuntimeException if the strategy class not found
     */
    public function merge($url1, $url2)
    {
        $instance = null;

        foreach ($this->strategyClasses as $strategyName => $strategyClass) {
             $strategy = new $strategyClass();

             if (!$strategy instanceof StrategyInterface) {
                 continue;
             }

             if ($strategy->valid($url1, $url2)) {
                 $instance = $strategy;
                 break;
             }
        }

        if (null === $instance) {
            throw new RuntimeException("The strategy class not registered");
        }

        $url = $instance->build($url1, $url2);

        return new Url($url);
    }
}