<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\Url;
use Madfox\WebCrawler\Url\Utils\UrlUtil;

class MergeOneStringOneInstance extends AbstractMerge
{
    /**
     * @param string|Url $url1
     * @param string|Url $url2
     * @return bool
     */
    public function valid($url1, $url2)
    {
        return (is_string($url1) && $url2 instanceof Url);
    }

    /**
     * @param string $url1
     * @param Url $url2
     * @return string
     */
    public function build($url1, $url2)
    {
        $url1 = UrlUtil::normalizeURL($url1);
        $parts = $this->parseUrl($url1);

        $components = [];
        $meta = $this->getUrlMetaMethods();

        foreach ($meta as $method => $key) {
            $val = call_user_func([$url2, $method]);

            if (!isset($parts[$key])) {
                $components[$key] = $val;
            } else {
                $components[$key] = $parts[$key];
            }
        }

        $url = UrlUtil::buildUrl($components);

        return $url;
    }
}
