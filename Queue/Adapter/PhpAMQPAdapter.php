<?php
namespace Madfox\WebCrawler\Queue\Adapter;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class PhpAMQPAdapter implements AdapterInterface
{
    private $channel;
    private $exchange;
    private $queue;
    private $connectionOptions = array();

    public function __construct($host, $port, $user, $pass, $vhost = '', $exchange = 'madfox_exchange', $queue = 'madfox')
    {
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

    public function enqueue($id)
    {
        $msg = new AMQPMessage(
            $id,
            array('content_type' => 'text/plain', 'delivery-mode' => 1)
        );

        $this->getChannel()->basic_publish($msg, $this->exchange, '', false, false);
    }

    public function dequeue()
    {
        $msg = $this->getChannel()->basic_get($this->queue);
        if (!$msg) {
            return false;
        }

        return $msg->body;
    }

    public function purge()
    {
        return $this->getChannel()->queue_purge($this->queue);
    }

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

}