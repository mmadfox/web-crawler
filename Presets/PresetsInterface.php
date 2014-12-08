<?php
namespace Madfox\Presets;

use Madfox\WebCrawler\Site;

interface PresetsInterface
{
    /**
     * @param Site $site
     * @return mixed
     */
    public function install(Site $site);

    /**
     * @param Site $site
     * @return mixed
     */
    public function uninstall(Site $site);
}