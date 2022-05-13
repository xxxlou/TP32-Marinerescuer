=== Simple News ===

Contributors: MortenPeterAndersen
Donate link: https://www.hjemmesider.dk
Tags: News, News list, Simple News
Requires at least: 4.0
Tested up to: 5.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

A very simple news plugin that output news.
*(img, title, date, excerpt and link)*

* News are order by pub date.
* A post type "NEWS" is added.
* On the options page (WP Settings Menu -> "Simple News") you can change the image size, and deselect the use of the plugin archive.php (news-archive.php).
* Two new Widget areas (Top and Bottom) a printed on the /news page.
* A widget that will show your defined number of latest news. Options to filter results by category id and post id.
* Shortcodes

> **Shortcodes**
> *All shortcodes can combines!*
> * [news]
> Number:
> * [news number=2]
> Offset:
> * [news offset=3]
> Order:
> * [news order=desc] - *default*
> * [news order=asc]
> Categories:
> * [news cat=1]
> * [news cat=1,2,4]
> Columns / grid *(CSS grid styling)*:
> * [news col=2]
> * ...
> * [news col=5]
> * Related *(Print this shortcode on the single news sidebar, and it shows news in the same category)*:
> * [news type=related]

> **Shortcode page design examples**
> *  2 - 4 grid:
> * [news number=2 col=2] [news col=4 offset=2]
> 2 - 4 grid *(only category 3)* :
> * [news number=2 col=2 cat=3] [news col=4 offset=2 cat=3]

== Installation ==

1. Download the plugin, and unzip it.
2. Place the News folder in your wp-content/plugins folder.
3. Activate the plugin from the plugins tab of your Wordpress admin.
4. Save permalink structure again - because of the post type (news)

1. Publish some news with the new post type = NEWS (Admin menu to the left)
2. Place the News-widget where you want it to appear
3. Use the shortcodes on a published page


== Frequently Asked Questions ==

= Can I request a feature to be added on the plugin? =
Certainly and would love to hear about it. BUT - It is meant to be a very simple clean and useful plugin.
Please contact me with your suggestions at: <a href="https://www.web.dk">www.web.dk</a> :-)