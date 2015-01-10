<?php
namespace Madfox\WebCrawler\Queue\Adapter;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AMQPAdapter implements AdapterInterface
{
    private $connectionURI;
    private $limit = 20000;
    private $exchange = 'webcrawler_exchange';
    private $ids = [];

    /**
     * @var AMQPConnection
     */
    private $connection;

    /**
     * @example mq://user:password@host:port/vhost | mq://host:port/
     *
     * @param string $connectionURI
     */
    public function __construct($connectionURI)
    {
        $this->connectionURI = parse_url($connectionURI);
        $this->getConnection();
    }

    /**
     * @return AMQPConnection
     */
    public function connect()
    {
        return $this->getConnection();
    }

    /**
     * @param null|string $channelName
     * @return bool
     */
    public function addChannel($channelName)
    {
        $channel = $this->getConnection()->channel();
        $channel->queue_declare($channelName, false, true, false, false);
        $channel->exchange_declare($this->exchange, 'direct', false, true, false);
        $channel->queue_bind($channelName, $this->exchange, $channelName);
        $this->ids[$channel->getChannelId()] = $channelName;
        return true;
    }

    /**
     * @param $channelName
     * @return AMQPChannel|null
     */
    public function getChannel($channelName)
    {
        $channelId = $this->getChannelIdFromName($channelName);
        return $this->getConnection()->channel($channelId);
    }

    /**
     * @param $channelName
     * @return int
     */
    public function getChannelIdFromName($channelName)
    {
        $return = 0;

        foreach ($this->ids as $id => $name) {
            if ($channelName == $name) {
                $return = $id;
                break;
            }
        }

        return $return;
    }

    /**
     * @param string $id
     * @param string $channelName
     * @return mixed
     */
    public function enqueue($id, $channelName)
    {
        $channel = $this->getChannel($channelName);
        $msg = new AMQPMessage($id, array('content_type' => 'text/plain', 'delivery-mode' => 1));
        $channel->basic_publish($msg, $this->exchange, $channelName, false, false);
    }

    /**
     * @param string $channelName
     * @return string
     */
    public function dequeue($channelName)
    {
        $channel = $this->getChannel($channelName);
        $msg = $channel->basic_get($channelName, true);
        return $msg ? $msg->body : "";
    }

    /**
     * @param string $channelName
     * @return mixed
     */
    public function purge($channelName)
    {
        $channel = $this->getChannel($channelName);
        $channel->queue_purge($channelName);
    }

    /**
     * @param string $channelName
     * @param string $id
     * @return mixed
     */
    public function ack($id, $channelName)
    {
        $channel = $this->getChannel($channelName);
        $channel->basic_ack($id);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
         return $this->limit;
    }

    public function close()
    {
        if ($this->connection) {
            /*foreach ($this->ids as $id => $channelName) {
                $this->getChannel($channelName)->close();
            }*/

            $this->connection->close();
        }
    }

    private  function getConnection()
    {
        if (null === $this->connection) {
           $user     = $this->getComponent('user');
           $password = $this->getComponent('pass');
           $host     = $this->getComponent('host');
           $port     = (int) $this->getComponent('port');
           $vhost    = $this->getComponent('path', '/');
           $this->connection = new AMQPConnection($host, $port, $user, $password, $vhost);
        }

        return $this->connection;
    }

    private function getComponent($param, $default = "")
    {
        return isset($this->connectionURI[$param])
            ? $this->connectionURI[$param]
            : $default;
    }
}