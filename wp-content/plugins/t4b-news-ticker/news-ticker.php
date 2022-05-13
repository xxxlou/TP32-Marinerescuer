<?php
/*
Plugin Name: T4B News Ticker
Plugin URI: http://wordpress.org/plugins/t4b-news-ticker/
Description: T4B News Ticker is a flexible and easy to use WordPress plugin that allow you to make horizontal News Ticker.
Version: 1.2.5
Author: Realwebcare
Author URI: http://profiles.wordpress.org/realwebcare/
Text Domain: t4bnt
Domain Path: /languages/
*/

/*  Copyright 2021  Realwebcare  (email : realwebcare@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define('T4BNT_PLUGIN_PATH', plugin_dir_path( __FILE__ ));

require_once ( T4BNT_PLUGIN_PATH . 'inc/ticker-admin.php' );

/* Internationalization */
function t4bnt_textdomain() {
	$domain = 't4bnt';
	$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
	load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
	load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 't4bnt_textdomain' );

/* Add plugin action links */
function t4bnt_plugin_actions( $links ) {
	$links[] = '<a href="'.menu_page_url('t4bnt-settings', false).'">'. __('Settings','t4bnt') .'</a>';
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 't4bnt_plugin_actions' );

/* Enqueue front js and css files */
function t4bnt_enqueue_scripts() {
	$t4bnt_enable = t4bnt_get_option( 'ticker_news', 't4bnt_general', 'yes' );		
	$ticker_effect = t4bnt_get_option( 'ticker_effect', 't4bnt_general', 'scroll' );
	if($t4bnt_enable == 'on') {
		if($ticker_effect == 'scroll') {
			wp_register_script('liscroll', WP_PLUGIN_URL.'/t4b-news-ticker/assets/js/jquery.liscroll.js', array('jquery'), '1.2.5');
			wp_enqueue_script('liscroll');
		} else {
			wp_register_script('ticker', WP_PLUGIN_URL.'/t4b-news-ticker/assets/js/jquery.ticker.js', array('jquery'), '1.2.5');
			wp_enqueue_script('ticker');
		}
		if($ticker_effect == 'scroll') {
			wp_enqueue_style('t4bnewsticker', WP_PLUGIN_URL.'/t4b-news-ticker/assets/css/t4bnewsticker.css?v=1.2.5');
		} else {
			wp_enqueue_style('tickerstyle', WP_PLUGIN_URL.'/t4b-news-ticker/assets/css/ticker-style.css?v=1.2.5');
		}
	}
}
add_action( 'wp_enqueue_scripts', 't4bnt_enqueue_scripts' );
?>