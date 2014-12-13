<?php
namespace Madfox\WebCrawler\Index;

class Document implements DocumentInterface
{
    private $id;
    private $data;

    /**
     * @param string $data
     */
    public function __construct($data = "")
    {
        $this->setData($data);
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return Document
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return Document
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
           'id'     => $this->id,
            'data'  => $this->data
        ]);
    }

    /**
     * @param string $serialized
     * @return mixed|void
     */
    public function unserialize($serialized)
    {
        return unserialize($serialized);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'   => $this->id,
            'data' => $this->data
        ];
    }
} 