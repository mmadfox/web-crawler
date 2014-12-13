<?php
namespace Madfox\WebCrawler\Index;

interface DocumentInterface extends \Serializable
{
    /**
     * @return array
     */
    public function toArray();

    /**
     * @return mixed
     */
    public function id();

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id);

}