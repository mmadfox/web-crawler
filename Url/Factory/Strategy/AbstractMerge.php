<?php
namespace Madfox\WebCrawler\Url\Factory\Strategy;

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
     * @param array $components
     * @return string
     */
    protected function buildQuery(array $components = [])
    {
        return ((isset($components['scheme'])) ? $components['scheme'] . '://' : '')
            .((isset($components['user'])) ? $components['user'] . ((isset($components['pass'])) ? ':' . $components['pass'] : '') .'@' : '')
            .((isset($components['host'])) ? $components['host'] : '')
            .((isset($components['port'])) ? ':' . $components['port'] : '')
            .((isset($components['path'])) ? $components['path'] : '')
            .((isset($components['query'])) ? '?' . $components['query'] : '')
            .((isset($components['fragment'])) ? '#' . $components['fragment'] : '');
    }

    /**
     * @return array
     */
    protected function getUrlMetaMethods()
    {
        return  [
            'getScheme'      => 'scheme',
            'getHostname'    => 'host',
            'getPort'        => 'port',
            'getUser'        => 'user',
            'getPassword'    => 'password',
            'getPath'        => 'path',
            'getQueryString' => 'query',
            'getFragment'    => 'fragment'
        ];
    }
}