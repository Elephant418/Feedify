Feedify
======

Feedify transforms any data to a feed in PHP

Currently support RSS & SiteMap feed



Code example
-----------------

```php
$writer = new \Feedify\Writer();

/* Add global description */
$writer->title = 'Title of my blog';
$writer->description = 'Description of my blog';
$writer->siteURL = 'http://example.com/url/to/my/blog';
$writer->feedURL = 'http://example.com/url/to/my/blog/feed';

/* Add items & formatters */
// Add data from your database
$writer->addItems($myData);
// The 'date' attribute could be used directly
$writer->addAttribute('date');
// The 'title' attribute corresponds to my 'name' attribute
$writer->addAttributeMap('title', 'name');
// The 'url' attribute needs a specific formatter
$writer->addAttributeFormatter('url', function($article){
  return 'http://example.com/article/'.$article->id;
});

/* Output */
// As a RSS
$writer->output(\Feedify\Writer::RSS_FORMAT);
// Or as a SiteMap
$writer->output(\Feedify\Writer::SITEMAP_FORMAT);
```



Author & Community
--------

Feedify is under [MIT License](http://opensource.org/licenses/MIT).
It is created and maintained by [Thomas ZILLIOX](http://tzi.fr).