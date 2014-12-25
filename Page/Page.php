<?php
namespace Madfox\WebCrawler\Page;

use Madfox\WebCrawler\Index\DocumentInterface;
use Madfox\WebCrawler\Url\Url;

class Page implements DocumentInterface
{
    private $url;
    private $links = [];
    private $content;
    private $id;

    public function __construct(Url $url, array $links = [], $content = "")
    {
        $this->links = $links;
        $this->url   = $url;
        $this->content = $content;
        $this->id = $url->getId();
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function url()
    {
        return $this->url;
    }

    public function links()
    {
         return $this->links;
    }

    public function content()
    {
         return $this->content;
    }

    public function serialize()
    {
        return serialize($this->toArray());
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $field => $val) {
            $this->$field = $val;
        }
    }

    public function toArray()
    {
        return [
           'url'      =>  $this->url,
           'links'    =>  $this->links,
           'content'  =>  $this->content,
           'id'       =>  $this->id
        ];
    }
}