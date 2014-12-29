<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\UrlUtil;

class MergeTwoString extends AbstractMerge
{
    /**
     * @param string|\Madfox\WebCrawler\Url\Url $url1
     * @param string|\Madfox\WebCrawler\Url\Url $url2
     * @return bool
     */
    public function valid($url1, $url2)
    {
        return is_string($url1) && is_string($url2);
    }

    /**
     * @param string $url1
     * @param string $url2
     * @return string
     */
    public function build($url1, $url2)
    {
        if (!$this->validScheme($url1)) {
            $url1 = "";
        }

        if (!$this->validScheme($url2)) {
            $url2 = "";
        }

        if (empty($url1) && empty($url2)) {
            return "";
        }

        $url1Components = $this->parseUrl(UrlUtil::normalizeURL($url1));
        $url2Components = $this->parseUrl(UrlUtil::normalizeURL($url2));
        $components = [];

        foreach ($this->getUrlMetaMethods() as $method => $component) {
             $p1 = isset($url1Components[$component]) ? $url1Components[$component] : null;
             $p2 = isset($url2Components[$component]) ? $url2Components[$component] : null;

             if (empty($p1)) $components[$component] = $p2;
             else $components[$component] = $p1;
        }

        $url = UrlUtil::buildUrl($components);

        return $url;
    }
}
