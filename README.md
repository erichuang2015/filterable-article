# Filterable Article
WordPress Filterable Article Plugin

If you have an article that is long and you wish to break it down as seen on
https://backlinko.com/link-building-strategies, then this plugin could be useful. 

![alt text][description_image]

[description_image]: https://raw.githubusercontent.com/sidouglas/filterable-article/master/images/filterable-article.gif "How the plugin works"

Simply mark up your article in the following format and the plugin
will do the rest. Uses VueJS to create the parent row and categories.

```
[fa parent="Top Row of Buttons" cats="Second Row of Buttons"]
  content goes here
[/fa]

[fa parent="multiple,buttons that can be used,together"]
  content goes here
[/fa]
```

### Template overides

Example: Adding a title between the parent and child filters

create article.php in your theme directory:
```
YOUR_TMEME/plugins/filterable-article/article.php
```
This applies for article,item and scripts files.

in YOUR_THEME/plugins/filterable-article/article.php - add:
```
%before%
<filterable-filters
 v-bind:parent="filter.parent"
 v-bind:children="filter.cats"
>
 <?=$title?>
</filterable-filters>
```

YOUR_THEME/functions.php:
```
 add_filter('filterable_article_get_template_args', function($args, $post, $name){
    if( some_condition($post) ){
      $args['title'] = "<h3>{$post->post_title}</h3>";
    }
    return $args;
  }, 10, 3);
```

This will render on your site:
```
<div id="filterable-article-some-unique-id">
  <div>
    <div class="filterable__parent">
      <button class="filter-button">...</button> //repeats
    </div>
    <h3>THE TITLE OF THE POST THAT YOUR ADDED VIA FUNCTIONS.PHP</h3> 
    <div class="filterable__children">
      <button class="filter-button">...</button> //repeats
    </div>
  </div>
</div>
...
```
