<?php
namespace Madfox\WebCrawler\Queue\Adapter;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class PhpAMQPAdapter implements AdapterInterface
{
    private $channel;
    private $exchange;
    private $queue;
    private $connectionOptions = array();

    /**
     * @param string $host
     * @param string $port
     * @param string $user
     * @param string $pass
     * @param string $vhost
     * @param string $exchange
     * @param string $queue
     */
    public function __construct(
        $host,
        $port,
        $user,
        $pass,
        $vhost = '/',
        $exchange = 'webcrawler_exchange',
        $queue = 'webcrawler_queue'
    ) {
        $this->exchange = $exchange;
        $this->queue = $queue;
        $this->connectionOptions = array(
            'host' => $host,
            'port' => $port,
            'user' => $user,
            'pass' => $pass,
            'vhost' => $vhost,
        );
    }

    /**
     * @param string $id
     * @return mixed|void
     */
    public function enqueue($id)
    {
        $msg = new AMQPMessage(
            $id,
            array('content_type' => 'text/plain', 'delivery-mode' => 1)
        );

        $this->getChannel()->basic_publish($msg, $this->exchange, '', false, false);
    }

    /**
     * @return bool|string
     */
    public function dequeue()
    {

        $msg = $this->getChannel()->basic_get($this->queue, true);
        if (!$msg) {
            return false;
        }

        return $msg->body;
    }

    /**
     * @return mixed|null
     */
    public function purge()
    {
        return $this->getChannel()->queue_purge($this->queue);
    }

    /**
     * @param string $identifier
     * @return mixed|void
     */
    public function ack($identifier)
    {
        $this->getChannel()->basic_ack($identifier);
    }

    private function getChannel()
    {
        if (!$this->channel) {
            $conn = new AMQPConnection(
                $this->connectionOptions['host'],
                $this->connectionOptions['port'],
                $this->connectionOptions['user'],
                $this->connectionOptions['pass'],
                $this->connectionOptions['vhost']
            );
            $ch = $conn->channel();
            $ch->queue_declare($this->queue, false, true, false, false);
            $ch->exchange_declare($this->exchange, 'direct', false, true, false);
            $ch->queue_bind($this->queue, $this->exchange, '');
            $this->channel = $ch;
        }
        return $this->channel;
    }

    public function __destruct()
    {
        $this->getChannel()->close();
    }

}