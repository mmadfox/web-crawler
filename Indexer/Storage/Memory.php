<?php
namespace Madfox\WebCrawler\Indexer\Storage;

class Memory implements StorageInterface
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param string $storageName
     * @return mixed|void
     */
    public function register($storageName)
    {
        $this->data = [];
    }

    /**
     * @param int|string $id
     * @param string $url
     * @param string $content
     * @param string|null $page
     * @return mixed|void
     */
    public function add($id, $url, $content = null, $page = null)
    {
        $this->remove($id);

        $this->data[$id] = [
            'id'    => $id,
            'url'   => $url,
            'data'  => $content,
            'page'  => $page
        ];
    }

    /**
     * @param string $id
     * @return null|string
     */
    public function get($id)
    {
        return $this->has($id) ? $this->data[$id]['page'] : null;
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->data[$id]);
    }

    /**
     * @param int|string $id
     * @return mixed|void
     */
    public function remove($id)
    {
        unset($this->data[$id]);
        return true;
    }

    /**
     * @return bool|void
     */
    public function purge()
    {
        $this->data = [];
        return true;
    }

} 