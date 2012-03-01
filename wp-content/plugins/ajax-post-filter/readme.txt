=== Ajax Post Filter ===
Contributors: irvingswiftj, Electric Studio
Tags: ajax, filter, posts, development
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 1.4

Filter posts with ajax

== Description ==

Use shortcode [ajaxFilter] to create a nice filter posts feature.
Attributes for this shortcode include:
* posttypes - comma separated post types that you want displayed
* taxonomies - comma separent taxonomies that you want to be able to filter by.
* showcount - set to 0 or 1 toggle the displaying of post counts next to taxonomies
* pageination - accepts values "top,bottom", "top", "bottom" depend where you want your pagination
* posts_per_page - set the amount of posts per page (default is 15)
* shownav - set to 0 or 1 to toggle on of off the navigation

Example:
[ajaxFilter posttypes="test_album" taxonomies="category,tag,genre,writer" showcount="1"]

== Installation ==

Install from wordpress plugins directory.

Else, to install manually:

1. Upload unzipped plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. An example of the shortcode

== Changelog ==

= 1.0 =
* Version 1.

= 1.1 =
* Bug fixes
* Will use ajax-loop.php file from template if exists

= 1.3 = 
* Adjustable amount of posts per pages
* Taxonomy Titles are now displays as headings on the navigation rather than the slugs
* Pagination now included ( by default, appears like "Prev Page 1 | 2 | 3 | 4 Next Page")
* Added pagination, posts_per_page, shownav to attributes in shortcode.

= 1.4 =
* Removed 'Filter' Title from navigation
* Fixed bug where unpublished posts were being displayed on ajax responses
* Fixed error of wrong classes on next and previous links.
* Fixed last page sometimes getting ignored bug

== Upgrade Notice ==

= 1.0 =

= 1.1 =
* Bug fix
* New Feature

= 1.3 =
* Many new features

= 1.4 =
* Bug fixes