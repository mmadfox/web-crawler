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
              $url = $page->getUrl();
              $links = $page->getLinks();
              $content = $page->content();
         });

$crawler->run();
```

