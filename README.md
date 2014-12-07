web-crawler
===========

Search the web

<h3>Example API</h3>

```php
$application = Crawler::create();

$application->site("http:://google.com")
            ->conf([])
            ->exec(function($page) {});

$application->site("http://foodbook.guru")
            ->match("/recipes/{slug}/{id}")
            ->exec(function($page) {});

$application->run();
```

