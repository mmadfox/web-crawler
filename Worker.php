<?php
namespace Madfox\WebCrawler;

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
    protected $container;

    /**
     * @param ContainerBuilder $container
     */
    public function __construct(ContainerBuilder $container)
    {
        $this->container = $container;
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

        $worker = $this;

        while(true) {
            $child = new Child(
                new Callback(
                    function (Control $control) use($worker) {
                        echo "Child process is " .$control->info()->getId().PHP_EOL;
                    }
                ),
                $control
            );

            $child->start();
            $child->wait();

            sleep(mt_rand(1,10));
        }
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