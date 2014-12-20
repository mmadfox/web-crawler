<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 20.12.14
 * Time: 7:13
 */

use \Madfox\WebCrawler\Site;

class SiteTest extends PHPUnit_Framework_TestCase {
    public function testEmpty()
    {
        $site = new Site(new Url('http://ulkotours.com/'));
        foreach ($site as $page) {

        }
    }

}
 