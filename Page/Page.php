<?php
namespace Madfox\WebCrawler\Page;

use Madfox\WebCrawler\Index\DocumentInterface;
use Madfox\WebCrawler\Url\Url;

class Page implements DocumentInterface
{
    private $url;
    private $links = [];
    private $content = null;
    private $id;

    /**
     * @param Url $url
     * @param array $links
     * @param null|string $content
     */
    public function __construct(Url $url, array $links = [], $content = null)
    {
        $this->links = $links;
        $this->url   = $url;
        $this->content = $content;
        $this->id = $url->getId();
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->content);
    }

    /**
     * @param string|int $id
     * @return mixed|void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed|string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Url
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function links()
    {
         return $this->links;
    }

    /**
     * @return null|string
     */
    public function content()
    {
         return $this->content;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->toArray());
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $field => $val) {
            $this->$field = $val;
        }
    }

    /**
     * @return array
     */
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