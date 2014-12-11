web-crawler
===========

Search the web

<h3>Example API</h3>

<h4>All pages handled</h4>

```php
$crawler = Crawler::create();

$crawler->site("http://ru.lipsum.com/")
        ->ifpath("*")
        ->handler(function ($page) {
              $url = $page->url();
              $links = $page->links();
              $content = $page->content();
         });

$crawler->run();
```

<h4>Pages filter</h4>

```php
$crawler = Crawler::create();

$crawler->site("http://ru.lipsum.com/")
        ->ifpath("/filter/{filter_name}/{filter_id}")
        ->filter(function ($page) {
             $content = $page->content();
             $content = preg_replace("/title/", "---", $content);
             return new Page($page->url(), $page->links(), );
        })
        ->filter(new ReplaceFilter());
        ->handler(function ($page) {
              $url = $page->getUrl();
              $links = $page->getLinks();
              $content = $page->content();
         });

$crawler->run();
```





