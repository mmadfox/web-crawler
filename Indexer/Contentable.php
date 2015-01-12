<?php
namespace Madfox\WebCrawler\Indexer;

interface Contentable extends \Serializable
{
    /**
     * @return string
     */
    public function content();
}