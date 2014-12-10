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
            echo "run ... \n";

            $task = $this->getQueue()->dequeue();

            if (!$task) {
                echo "Task not exists. Sleep 20sec \n";
                sleep(10);
            } else {
                echo "TaskId " . $task->getId() . " -> " . $task->toString() . " \n";
                $site = $this->getSiteCollection()->get($task);

                if ($site) {
                    echo "site found " . $site->getUrl()->toString() . "\n";



                    //$response = $this->getHttpClient()->request($site->getUrl());
                    //if response validate

                    //$links = $urlMatcher->match($response);
                    //foreach($links as $link) {
                    //if validator->valid $link

                    echo "process.... \n";
                    sleep(40);
                }
            }

            sleep(1);
        }
    }

    public function runOld()
    {
        $this->prepareRun();

        $client = new Browser(new Curl());

        while (count($this->queue) > 0) {
            $startTime = microtime(false);

            echo "Queue length = " . count($this->queue) . " Start time " . $startTime . "";
            echo "Start loop... \n";

            $task = $this->getQueue()->dequeue();

            echo "--- get task url from queue {$task} \n";

            if ($this->assertLinkIsNotVisited($task)) {
                continue;
            }

            /*if (isset($this->history[$u])) {

            }*/

            echo " ==== GET URL {$task} ==== \n";

            try {

                //$site = $this->sites[$url->getHostname()];
                //$response = $client->get($url);

                //$this->history[$u] = 1;

                //if ($this->assertResponseHeader($response)) {

                //}
                //if response status code 200 and response content type html
                /*if (!strstr($response->getHeader("Content-Type"), "text/html")) {
                    throw new RequestException("Bad Content Type {$u} ");
                }

                if ($response->getStatusCode() != 200) {
                    throw new RequestException("Bad status code");
                }*/

                //if (!$site) continue;

                echo  "--- Match urls \n";

                //if $site->match($url) Route->exec();
                $response = "";
                $pattern = '/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>.*<\/a>/siU';
                preg_match_all($pattern, $response, $match);
                unset($match[0]);
                unset($match[1]);

                foreach ($match[2] as $rawUrl) {

                    //if Domain valid and url not resource
                    if (preg_match(Address::REGEX_ADDRESS, $rawUrl)) {
                        $testUrl = new Url($rawUrl);
                        //formatted url

                        //if Domain === Domain
                        if ($testUrl->getHostname() == $site->getAddress()->getHostname()) {

                            //if not visited
                            array_push($this->queue, $testUrl->format("Hpq"));
                        }
                        //echo $rawUrl . "\n";
                    }
                }

            } catch (\Exception $e) {
               echo "Log => " . $e->getMessage() . "\n";
               sleep(10);
            }

            $endTime = microtime(false);
            $res = $endTime - $startTime;
            echo "\n";
            echo "End time " . $res;
            echo "\n";
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