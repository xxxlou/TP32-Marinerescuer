=== Plugin Name ===
Contributors: garyhukkeri
Donate link:
Tags: tableau, tableau public, analytics, reports, shortcode
Requires at least: 4.9.8
Tested up to: 5.2.2
Stable tag:
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html




== Description ==

A simple plugin to insert Tableau Public Vizualizations into a WordPress page. This can be done as a Block, Shortcode or via TinyMCE for the Classic Editor. No frills, no options.

##This plugin will no longer be updated.

###This has been replaced by WP-TAB Tableau Public Viz Block

Download from here: [WP-TAB Tableau Public Viz Block](https://wordpress.org/plugins/wptab-tableau-public-viz-block/).


- This plugin will only work for Tableau Public Visualizations i.e. you need to have a viz hosted on Tableau Public ([What is Tableau Public]( https://community.tableau.com/docs/DOC-9135)).
- This plugin uses the Tableau JS API to a)fetch and b)embed your Tableau Public Viz within your Wordpress site.
- The Tableau Public Data policy can be viewed [here](https://public.tableau.com/en-us/s/data-policy). Data on Tableau Public is expected to be public.
- Tableau's privacy policy can be viewed [here](https://www.tableau.com/privacy)
- This plugin does not store the data anywhere in any format. It simply provides a user friendly way to pull a viz hosted on [http://public.tableau.com](http://public.tableau.com) and embed it into the site hosting the plugin. 

All you need is your Tableau Public url e.g 'https://public.tableau.com/views/WorldIndicators/GDPpercapita'

Usage:

With TinyMCE Editor Button (Classic Editor):
1. Get your Tableau Public url e.g 'https://public.tableau.com/views/WorldIndicators/GDPpercapita'
2. Edit the page/post you want it on
3. Click the Tableau button in the Editor
4. Paste the url and insert and publish

Manual Shortcode( or Shortcode Block for Gutenberg):
If the url is: http://public.tableau.com/views/RegionalSampleWorkbook/Storms
Then, this is the shortcode:
[tableau url="http://public.tableau.com/views/RegionalSampleWorkbook/Flights"]

Embed Block

1. Under the Embed category, select the Simple Tableau Viz block type.
2. Paste your url in the block and publish

That's it. Your Visualization should now render when you view the page.

== Installation ==

1. Upload `simple-tableau-viz` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
Coming soon.



== Screenshots ==

1. Adding shortcode manually.
2. Adding viz through Editor.
3. An embedded tableau report.

== Changelog ==

= 2.0 =
* Added support for WP Gutenberg Blocks

= 1.0 =
* First release with TinyMCE button on editor.
