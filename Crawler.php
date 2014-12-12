<?php
namespace Madfox\WebCrawler;

use Buzz\Client\Curl;
use Buzz\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Queue\Adapter\MemoryAdapter;
use Madfox\WebCrawler\Queue\Queue;
use Madfox\WebCrawler\Queue\QueueInterface;
use Madfox\WebCrawler\Site\Site;
use Madfox\WebCrawler\Site\SiteCollection;
use Madfox\WebCrawler\Site\Url;
use Buzz\Browser;
use Madfox\WebCrawler\Validator\Constraints\LinkIsNotVisited;
use Madfox\WebCrawler\Validator\Constraints\ResponseHeader;
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
           $this->queue = new Queue(new MemoryAdapter());
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
     * @param $url
     * @return Site|null
     * @throws \Buzz\Exception\InvalidArgumentException
     */
    public function site($url)
    {
        $url = new Url($url);

        if ($this->getSiteCollection()->has($url)) {
            $site = $this->getSiteCollection()->get($url);
        } else {
            $url = $url instanceof Url ? $url : new Url($url);
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

    protected function assertLinkIsNotVisited($url)
    {
        $errors = $this->validator()
                    ->validate($url, new LinkIsNotVisited($this->history));

        return count($errors) != 0;
    }

    protected function assertResponseHeader($response)
    {
        $errors = $this->validator()
                     ->validate($response, new ResponseHeader());

        return count($errors) == 0;
    }

    public function run()
    {
        if ($this->isRunning()) {
            return;
        }

        $this->prepareRun();
        $this->running = true;

        while ($this->isRunning()) {
            echo "Старт < ========= > \n";

            $url = $this->getQueue()->dequeue();

            if (null === $url) {
                echo "Очередь пустая. Спим 20sec \n";
                sleep(10);
            } else {

                echo "Получили урл из очереди " . $url->getId() . " -> " . $url->toString() . " \n";
                $site = $this->getSiteCollection()->get($url);

                if ($site) {

                    try {
                        //$this->getQueue()->ack($url);
                    } catch(AMQPProtocolChannelException $e) {
                        echo "AMQPProtocolChannelException \n";
                    }


                    if (isset($this->history[$url->getId()])) {
                        echo "Visited \n";
                        continue;
                    }

                    $this->history[$url->getId()] = $url->toString();

                    echo "Сайт найден -> " . $site->getUrl()->toString() . "\n";
                    $page = $site->page($url);
                    echo "Страница " . $page->url() . "\n";

                    if ($site->valid($page)) {
                        echo "Обработчик найден. Продолжаем работу...  \n";
                    } else {
                        echo "Правила для обработки не найдены \n";
                    }

                    foreach ($page->links() as $link) {
                        //echo " Ссылка  " . $link . "\n";
                        $this->getQueue()->enqueue(new Url($link));

                    }

                    echo ".... \n";
                    sleep(2);

                } else {
                    echo "Сайт не найден {$site} \n";
                }
            }

            sleep(1);
        }
    }

    private function prepareRun()
    {
        foreach ($this->getSiteCollection() as $site) {
            $siteUrl = $site->getUrl();
            $this->getQueue()->enqueue($siteUrl);
        }
    }
}