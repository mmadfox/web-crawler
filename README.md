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
            ->ifpath("/recipes/{slug}/{id}")
            ->exec(function($page) {

            });

$application->run();
```

