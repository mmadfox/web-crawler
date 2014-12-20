<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Exception\LogicException;
use Madfox\WebCrawler\Queue\Queue;
use Madfox\WebCrawler\Queue\QueueInterface;
use Madfox\WebCrawler\Site\Site;
use Madfox\WebCrawler\Site\SiteCollection;
use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Validator\ValidatorFactory;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;

class Crawler
{
    private $sites;
    private $queue;
    private $history = [];
    private $validator;
    private $running = false;

    public function __construct()
    {
        $this->sites = new SiteCollection();
        $validatorFactory = new ValidatorFactory();
        $this->validator = $validatorFactory->createValidator();
    }

    /**
     * @return QueueInterface
     */
    public function getQueue()
    {
        if (null === $this->queue) {
           $this->queue = new Queue();
        }

        return $this->queue;
    }

    /**
     * @param QueueInterface $queue
     * @return QueueInterface
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;

        return $queue;
    }

    /**
     * @return SiteCollection
     */
    public function getSiteCollection()
    {
        return $this->sites;
    }

    /**
     * @return \Symfony\Component\Validator\Validator\RecursiveValidator
     */
    public function validator()
    {
        return $this->validator;
    }

    /**
     * @param string|Url $url
     * @return Site|null
     * @throws \Buzz\Exception\InvalidArgumentException
     */
    public function site($url)
    {
        $url = $url instanceof Url ? $url : new Url($url);

        if ($this->getSiteCollection()->has($url)) {
            $site = $this->getSiteCollection()->get($url);
        } else {
            $site = new Site($url);
            $this->getSiteCollection()->add($site);
        }

        return $site;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return $this->running;
    }

    public function run()
    {
        if ($this->isRunning()) {
            return;
        }

        $this->prepareRun();
        $this->running = true;

        if ($this->getSiteCollection()->count() == 0) {
            throw new LogicException("Please, add a new website");
        }

        while ($this->isRunning()) {
            $site = $this->getSiteCollection()->random();

            $url = $this->getQueue()->dequeue($site->id());

            if (null === $url) {
                sleep(20);
            } else {
                $page = $site->page($url);


            }
        }
    }

    protected function prepareRun()
    {
        foreach ($this->getSiteCollection() as $site) {
            $siteUrl = $site->getUrl();

            $this->getQueue()->enqueue($siteUrl, "webcrawler." . $siteUrl->host());
        }
    }

}