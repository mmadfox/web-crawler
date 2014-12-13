<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\Url;

class MergeOneStringOneInstance extends AbstractMerge
{
    public function valid($url1, $url2)
    {
        return (is_string($url1) && $url2 instanceof Url);
    }

    public function build($url1, $url2)
    {
        $parts = $this->parseUrl($url1);

        $components = [];
        $meta = $this->getUrlMetaMethods();

        if (strpos($url1, "//") === 0) {
            $parts['path'] = $parts['host'] . $parts['path'];
            unset($parts['host']);
        }

        foreach ($meta as $method => $key) {
            $val = call_user_func([$url2, $method]);
            
            if (in_array($key, ['scheme', 'host', 'port' , 'user', 'password'])) {
                $components[$key] = $val;
            } else {
                if ($key == 'path' && isset($parts['path'])) {
                    $parts[$key] = $this->trimPath($parts[$key]);
                }

                if (!isset($parts[$key])) {
                    $components[$key] = $val;
                } else {
                    $components[$key] = $parts[$key];
                }
            }
        }

        $url = $this->buildQuery($components);
        return $url;
    }
}
