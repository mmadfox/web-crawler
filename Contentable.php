<?php
namespace Madfox\WebCrawler;

interface Contentable extends \Serializable
{
    /**
     * @return string
     */
    public function content();
}