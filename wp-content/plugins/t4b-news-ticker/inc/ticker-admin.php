<?php
/*
 *  T4B News Ticker v1.2.5 - 17-12-2021
 *  By @realwebcare - https://www.realwebcare.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* Enqueue CSS & JS For Admin */
function t4bnt_admin_adding_style() {
	wp_enqueue_script('t4bnt-admin', WP_PLUGIN_URL .'/t4b-news-ticker/assets/js/t4bnt_admin.js', array('jquery'), '1.0');
	wp_enqueue_style('t4bnt-admin-style', WP_PLUGIN_URL .'/t4b-news-ticker/assets/css/t4bnt_admin.css?v=1.0');
}
add_action( 'admin_enqueue_scripts', 't4bnt_admin_adding_style', 11 );

/* Sidebar */
add_action( 't4bnt_settings_content', 't4bnt_sidebar' );
if( !function_exists( 't4bnt_sidebar' ) ){
	function t4bnt_sidebar() { ?>
		<div id="t4bnt-sidebar" class="postbox-container">
			<div id="t4bntusage-shortcode" class="t4bntusage-sidebar">
				<h3><?php _e('Plugin Shortcode', 't4bnt'); ?></h3>
				<input type="text" class="t4bnt-shortcode" value="[t4b-ticker]" />
			</div>
			<div id="t4bntusage-premium" class="t4bntusage-sidebar">
            	<h3><?php _e('Code Usage Instruction', 't4bnt'); ?></h3>
                <div class="t4bnt">
                    <p>Put the below shortcode in your blog posts/pages, where you want to show News Ticker:<br><br>
                    	<code>&#60;&#63;php echo do_shortcode&#40;&#39;&#91;t4b-ticker&#93;&#39;&#41;&#59; &#63;&#62;</code>
                    </p>
                </div>
			</div>
			<div id="t4bntusage-features" class="t4bntusage-sidebar">
				<h3><?php _e('Premium Features', 't4bnt'); ?></h3>
				<div class="ccwrpt"><?php _e('Premium version has been developed to present News Ticker more proficiently. Some of the most notable features are:', 't4bnt'); ?></div>
				<ul class="t4bntusage-list">
					<li><?php _e('7 types of animation effect.', 't4bnt'); ?></li>
					<li><?php _e('Import/Export (Backup) news ticker.', 't4bnt'); ?></li>
					<li><?php _e('Make a copy of a ticker instantly.', 't4bnt'); ?></li>
					<li><?php _e('Multiple categories support', 't4bnt'); ?></li>
					<li><?php _e('RSS and JSON support.', 't4bnt'); ?></li>
					<li><?php _e('RTL Support.', 't4bnt'); ?></li>
					<li><?php _e('Play/Pause/Previous/Next support.', 't4bnt'); ?></li>
					<li><?php _e('Google font support.', 't4bnt'); ?></li>
					<li><?php _e('Font Awesome icon support.', 't4bnt'); ?></li>
				</ul>
				<a href="https://code.realwebcare.com/item/t4b-news-ticker-pro-flexible-horizontal-news-ticker-wordpress-plugin/" target="_blank"><?php _e('View Premium', 't4bnt'); ?></a>
			</div>
			<div id="t4bntusage-info" class="t4bntusage-sidebar">
				<h3><?php _e('Plugin Info', 't4bnt'); ?></h3>
				<ul class="t4bntusage-list">
					<li><?php _e('Version: 1.2.5', 't4bnt'); ?></li>
					<li><?php _e('Scripts: PHP + CSS + JS', 't4bnt'); ?></li>
					<li><?php _e('Requires: Wordpress 3.0+', 't4bnt'); ?></li>
					<li><?php _e('First release: 29 December, 2014', 't4bnt'); ?></li>
					<li><?php _e('Last Update: 17 December, 2021', 't4bnt'); ?></li>
					<li><?php _e('By', 't4bnt'); ?>: <a href="https://www.realwebcare.com/" target="_blank"><?php _e('Realwebcare', 't4bnt'); ?></a><br/>
					<li><?php _e('Need Help', 't4bnt'); ?>? <a href="https://wordpress.org/support/plugin/t4b-news-ticker/" target="_blank">Support</a><br/>
                    <li><?php _e('Like it? Please leave us a', 't4bnt'); ?> <a target="_blank" href="https://wordpress.org/support/plugin/t4b-news-ticker/reviews/?filter=5/#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> <?php _e('rating. We highly appreciate your support!', 't4bnt'); ?><br/>
					<li><?php _e('Published under', 't4bnt'); ?>: <a href="http://www.gnu.org/licenses/gpl.txt"><?php _e('GNU General Public License', 't4bnt'); ?></a>
				</ul>
			</div>
		</div><?php
	}
}
require_once ( T4BNT_PLUGIN_PATH . 'ticker-shortcode.php' );
require_once ( T4BNT_PLUGIN_PATH . 'class/t4bnt-class.settings-api.php' );
require_once ( T4BNT_PLUGIN_PATH . 'inc/ticker-settings.php' );
?>