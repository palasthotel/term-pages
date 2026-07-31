=== Term Pages ===
Contributors: pixelwelt, edwardbock, palasthotel, janame
Donate link: http://palasthotel.de/
Tags: term, tags, category, static page
Requires at least: 6.2
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl

Redirects the first page of a term archive to a page of your choice.

== Description ==

If you want to customize a taxonomy archive, create a page and connect it with the term that should be overwritten. Visitors of that term archive are then redirected to the page with a 301.

Works with categories, tags and every custom taxonomy that has an admin UI. Paginated archive pages such as `/category/news/page/2/` are left untouched, so the rest of the archive keeps working.

This plugin is reasonable to use with [Grid](http://wordpress.org/plugins/grid/ "Grid Landingpage Editor"), so you can create beautiful pages for your topics.

== Installation ==

1. Install *Term Pages* from the plugin directory, or upload `term-pages.zip` to `/wp-content/plugins/` and extract it there
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Edit a category, tag or custom taxonomy term and pick a page in the "Overriding page" field

== Frequently Asked Questions ==

= Which pages can I connect? =

Published pages. The autocomplete only offers pages with the post type `page` and the status `publish`.

= Why is my archive not redirecting? =

The redirect only happens on the first, unpaged view of the archive, and only while the connected page is still published. If the page was trashed, set to draft or private, the archive shows its normal listing again.

= How do I remove a redirect? =

Clear the "Overriding page" field and save the term.

= Does the plugin change my permalinks? =

No. It only redirects. The term archive keeps its URL and remains reachable from page 2 onwards.

== Screenshots ==

1. Admin view for a tag where you can add the relation to a page in the autocomplete field.

== Changelog ==

= 1.0.3 =
* Bugfix: wrong countable $term check

= 1.0.2 =
* Bugfix: Remove warning

= 1.0.1 =
* Bugfix: Missing argument 2 for Term_Pages::save_extra_field()

= 1.0 =
* First release

== Upgrade Notice ==

= 2.0.0 =
Security release. Fixes an unauthenticated SQL injection in the page search of 1.0.3 and earlier, reported by Joao Ramos Maciel (WPScan). Update as soon as possible. Requires WordPress 6.2 and PHP 7.4.
