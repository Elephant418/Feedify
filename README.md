Feedify
======

Transform any data to a feed in PHP

Currently support RSS & SiteMap feed



Code example
-----------------

```php
$writer = new \Feedify\Writer();

// Add global description
$writer->title = 'Title of my blog';
$writer->description = 'Description of my blog';
$writer->siteURL = 'http://example.com/url/to/my/blog';
$writer->feedURL = 'http://example.com/url/to/my/blog/feed';

// Add items & formatters
$writer->items = $fetchMyData();
// The date attribute could be used directly
$writer->addAttribute('date');
// The title corresponds to my 'name' attribute
$writer->addAttributeMap('title', 'name');
// The url needs a specific formatter
$writer->addAttributeFormatter('url', function($article){
		return 'http://example.com/article/$article->id;
});

// Output the my datas as a RSS
$writer->output(\Feedify\Writer::RSS_FORMAT);

// Output the my datas as a SiteMap
$writer->output(\Feedify\Writer::SITEMAP_FORMAT);
```



Author & Community
--------

Staq is under [MIT License](http://opensource.org/licenses/MIT).
It is created and maintained by [Thomas ZILLIOX](http://zilliox.me).