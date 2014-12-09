<?php
namespace Madfox\WebCrawler;

use Buzz\Client\Curl;
use Buzz\Exception\ClientException;
use Buzz\Exception\RequestException;
use Buzz\Util\Url;
use Madfox\WebCrawler\Exception\SiteAlreadyExistsException;
use Madfox\WebCrawler\Site\Address;
use Madfox\WebCrawler\Site\Site;
use Madfox\WebCrawler\Site\SiteFactory;
use Buzz\Browser;

class Crawler
{
    private $sites = [];
    private $queue = [];
    private $history = [];

    public function site($site)
    {
        $siteFactory = new SiteFactory();
        $site = $siteFactory->create($site);

        if (isset($this->sites[$site->getAddress()->getHostname()])) {
            throw new SiteAlreadyExistsException("Site already exists!");
        }

        $this->sites[$site->getAddress()->getHostname()] = $site;
        array_push($this->queue, $site->getAddress()->toString());

        return $site;
    }

    public function isRunning()
    {

    }

    public function run()
    {
        $client = new Browser(new Curl());

        while (count($this->queue) > 0) {
            $startTime = microtime(false);

            echo "Queue length = " . count($this->queue) . " Start time " . $startTime . "";
            echo "Start loop... \n";

            $u = array_shift($this->queue);
            echo "--- get item from queue \n";

            if (isset($this->history[$u])) {
                continue;
            }

            echo " ==== GET URL {$u} ==== \n";

            $url = new Url($u);

            try {

                $site = $this->sites[$url->getHostname()];
                $response = $client->get($url);

                $this->history[$u] = 1;

                //if response status code 200 and response content type html
                if (!strstr($response->getHeader("Content-Type"), "text/html")) {
                    throw new RequestException("Bad Content Type {$u} ");
                }

                if ($response->getStatusCode() != 200) {
                    throw new RequestException("Bad status code");
                }

                if (!$site) continue;

                echo  "--- Match urls \n";

                //if $site->match($url) Route->exec();

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
}