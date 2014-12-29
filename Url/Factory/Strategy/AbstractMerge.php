<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

use Madfox\WebCrawler\Url\UrlUtil;

abstract class AbstractMerge implements StrategyInterface
{
    public function valid($url1, $url2)
    {
        return true;
    }

    public function build($url1, $url2)
    {
        return $url1;
    }

    /**
     * @param string $rawPath
     * @return string
     */
    protected function trimPath($rawPath)
    {
        $path = $rawPath;

        foreach([':', '//', '://', '/'] as $char) {
            $path = ltrim($path, $char);
        }

        return "/" . $path;
    }

    /**
     * @param $url
     * @return array
     */
    protected function parseUrl($url)
    {
        return parse_url($url);
    }

    /**
     * @param  string $url
     * @return bool
     */
    protected function validScheme($url)
    {
        $scheme = UrlUtil::detectSchema($url);

        if ($scheme == 'http' || $scheme == 'https' || empty($scheme)) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getUrlMetaMethods()
    {
        return  [
            'scheme'      => 'scheme',
            'host'        => 'host',
            'port'        => 'port',
            'user'        => 'user',
            'password'    => 'password',
            'path'        => 'path',
            'query'       => 'query',
            'fragment'    => 'fragment'
        ];
    }
}