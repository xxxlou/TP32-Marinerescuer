=== Embed CloudTables directly in WordPress ===
Tags: CloudTables, DataTables, Editor, SpryMedia
Requires at least: 5.0
Tested up to: 5.4
Stable tag: 1.0.0
Requires PHP: 5.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

[CloudTables](https://cloudtables.com) lets you build complex and configurable data sets with ease. This plugin provides the ability to embed your data sets through the WordPress editor for use on your WordPress driven site.


== Description ==

[CloudTables](https://cloudtables.com) provides the tools needed to let you create complex database applications in moment and then embed them directly into your own site to provide your users with a seamless design. This plug-in is provided to make the embedding of CloudTables into a WordPress site just the matter of a few clicks!


== Use ==

= Block Editor =

If you use WordPress' block editor, you will find a 'CloudTables' option in the 'Layout' section. Select that for where you wish the CloudTable to appear. You will see the CloudTables logo in the block and in the block inspector a dropdown option list from which you can select the data set you wish to display at that point.

= Short code =

For those that prefer to use a short code, please use: `[cloudtable id="..."]` where the id attribute is the id of the data set you wish to embed. This can be found on the 'Data' tab of your data set in the CloudTables application.


== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'.
2. Search for 'CloudTables' and install.

= From WordPress.org =

1. Download the CloudTables plugin for WordPress.
2. Upload the 'cloudtables' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...).

= Post-install

1. Activate CloudTables from your Plugins page.
2. In 'Settings > CloudTables' enter your CloudTables' sub-domain name and API keys
  * Your CloudTables API keys can be found in your CloudTables application under 'Security > API Keys'.
  * There are two keys that can be entered - one for editors and one for end users. Typically these keys should have read/write access and read-only access, respectively.


== Changelog ==

= 1.0.0 - 22 May 2020 =

* Initial release
