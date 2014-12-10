<?php
namespace Madfox\WebCrawler\Site\Mapper\Factory;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Exception\InvalidArgumentException;
use Madfox\WebCrawler\Site\Page;
use Madfox\WebCrawler\Site\Url;
use Madfox\WebCrawler\Validator\ValidatorFactory;

class PageFactory
{
    private $browser;
    private $validator;

    public function __construct()
    {
        $validatorFactory = new ValidatorFactory();
        $this->browser = new Browser(new Curl());
        $this->validator = $validatorFactory->createValidator();
    }

    public function createPage(Url $url)
    {
        $html = $this->request($url->toString());
        $links = $this->match($html, $url);

        $page = new Page($url, $links, $html);
        return $page;
    }

    private function request($url)
    {
        $response = $this->browser->get($url);
        return $response->getContent();
    }

    private function match($content = "", $parent)
    {
        $pattern = '/<a\s[^>]*href\s*=\s*([\"\']??)([^\" >]*?)\\1[^>]*>.*<\/a>/siU';

        preg_match_all($pattern, $content, $match);
        unset($match[0]);
        unset($match[1]);
        $return = [];

        foreach ((array)$match[2] as $link) {
            try {
                $url = $parent->build($link);
                if ($url->getHostname() == $parent->getHostname()) {
                    array_push($return, $url->toString());
                }
            } catch (InvalidArgumentException $e) {
                echo "Bad \n";
            }
        }

        return $return;
    }
}