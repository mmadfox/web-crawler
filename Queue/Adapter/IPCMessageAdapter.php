<?php
namespace Madfox\WebCrawler\Queue\Adapter;

class IPCMessageAdapter implements AdapterInterface
{
    const TYPE = 999;

    private $key;

    public function __construct()
    {
         if (!function_exists('msg_get_queue')) {
             throw new \RuntimeException("You need to install semaphore extension before accessing the functions msg_get_queue()");
         }

         $this->key = msg_get_queue(5555555555);
    }

    public function enqueue($url)
    {
        $f = msg_send(msg_get_queue(ftok(".", "G")), self::TYPE, (string) $url, true, false, $errno);
        var_dump($errno);
    }

    public function dequeue()
    {
        $stat = msg_stat_queue($this->key);
        $return = "";

        if ($stat['msg_qnum'] > 0) {
            msg_receive($this->key, self::TYPE, $msgtype, 1024, $data);
            $return = $data;
        }

        return $return;
    }
}