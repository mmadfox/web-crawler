<?php
namespace Madfox\WebCrawler\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class LinkIsNotVisited extends Constraint
{
    private $history;

    public function __construct($history)
    {
        $this->history = $history;
    }

    public function getHistory()
    {
        return $this->history;
    }
}