web-crawler
===========

Search the web

<h3>Example API</h3>

```php
$application = Crawler::create();

$application->site("http:://google.com")
            ->conf([])
            ->ifpath("/")
            ->exec(function($page) {

            });

$application->site("http://foodbook.guru")
            ->ifpath("/recipes/{slug}/{id}")->exec(function($page) {});
            ->ifpath("/category/{id}/reviews.html")->exec(function($page) {})
            ->ifpath("/category/{slug}/foo/bar/{id}")->exec(function($page){})

$application->run();
```

