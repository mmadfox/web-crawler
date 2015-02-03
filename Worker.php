<?php
namespace Madfox\WebCrawler;

use Madfox\WebCrawler\Url\Url;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Arara\Process\Action\Action,
    Arara\Process\Context,
    Arara\Process\Action\Callback,
    Arara\Process\Child,
    Arara\Process\Control;

class Worker implements Action
{
    /**
     * @var ContainerBuilder
     */
    private  $container;
    /**
     * @var Child[]
     */
    private  $processes = [];
    /**
     * @var array
     */
    private  $test = [];
    private $col;

    /**
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
        $conn = new \MongoClient();
        $db = $conn->selectDB("qtest");
        $this->col = $db->selectCollection("q");
    }

    public function addToQ(Url $url)
    {
        $this->col->insert([
            'url'     =>  $url->toString(),
            'st'      =>  1
        ]);
    }

    public function getFromQ()
    {
        $res = $this->col->findAndModify(
            ['st' => 1],
            [],
            ['url' => 1],
            ['remove' => true, 'upsert' => false, 'new' => false]
        );

        return $res && isset($res['url']) ? new Url($res['url']) : "";
    }

    /**
     * @return \Madfox\WebCrawler\Site
     */
    public function getSite()
    {
        return $this->container->get('site');
    }

    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->getSite()->getUrl()->hostname();
    }


    /**
     * This is the action to be runned.
     *
     * @param Control $control Process controller.
     * @param Context $context Process context.
     *
     * @return integer Event status.
     */
    public function execute(Control $control, Context $context)
    {
        $limit = 30;
        $running = 0;
        $worker = $this;

        $this->addToQ(new Url("http://ulkotours.com"));

        do {
           $url = $this->getFromQ();

           if ($url) {
               $childProcess = new Child(new Callback(function (Control $control, Context $context) use($url, $worker) {
                    $page = $worker->getSite()->getPage($url);

                    echo "Page {$page->url()} \n";

                    if (!$page->isEmpty()) {
                        foreach ($page->links() as $link) {
                            $worker->addToQ($link);
                        }
                    }

               }), $control);

               $childProcess->start();
               array_push($this->processes, $childProcess);
               $running++;
           } else {
              sleep(2);
              $running++;
           }

           if ($running == $limit) {
                $test = true;
                while($test && count($this->processes) > 0) {
                    foreach ($this->processes as $v => $p) {
                        if (!$p->isRunning()) {
                            $running--;
                            unset($this->processes[$v]);
                            $test = false;
                        } else {
                            $p->wait();
                        }
                    }
                }
           }

        } while ($running < $limit);
    }

    /**
     * Must be called after action is finished to trigger possible defined events.
     *
     * @param integer $event   Event to be triggered.
     * @param Control $control Process controller.
     * @param Context $context Process context.
     *
     * @return null No return value is expected.
     */
    public function trigger($event, Control $control, Context $context)
    {

    }
}