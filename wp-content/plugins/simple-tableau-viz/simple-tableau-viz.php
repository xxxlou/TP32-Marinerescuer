<?php
/*
Plugin Name: Simple Tableau Viz
Description: A simple plugin to insert Tableau Public Vizualizations into a WordPress page. This can be done as a Block, Shortcode or via TinyMCE for the Classic Editor. No frills, no options.
Version: 2.0
Author: Gary Hukkeri
Author URI: https://www.linkedin.com/in/rogergary
License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function simple_tableau_viz( $a ){
		//Get Tableau URL from Shortcode Tags
    $tags = shortcode_atts( array(
			'url' => '',
    ), $a );
		//If we hae a Tableau URL, print the container and put it into the data attribute
		if($tags['url']!='') {
			$tableauURL=$tags['url'];
			return '<div id="vizContainer" data-url="'.$tableauURL.'"></div>';
		} else {
				return '';
		}
}

add_shortcode( 'tableau', 'simple_tableau_viz' );

/* Add Tableau Shortcode Button to TinyMCE Editor Toolbar */
add_action('init', 'simple_tableau_viz_tinymce_init');

function simple_tableau_viz_tinymce_init() {
		//Exit if TinyMCE not available
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
			return;

		//Regiser our tinymce plugin
		add_filter("mce_external_plugins", "simple_tableau_viz_register_tinymce");

		// Add Tableau button to the TinyMCE toolbar
		add_filter('mce_buttons', 'simple_tableau_viz_tinymce_button');
}


//Register the admin tinymce script
function simple_tableau_viz_register_tinymce($plugin_array) {
	$plugin_array['simple_tableau_viz'] = plugin_dir_url( __FILE__ ) . 'js/simple-tableau-viz-admin.js';
	return $plugin_array;
}

//Add a Tableau button to the toolbar
function simple_tableau_viz_tinymce_button($buttons) {
		//Add the button ID to the $button array
		$buttons[] = "simple_tableau_viz";
		return $buttons;
}

//Enqueue the public scripts
add_action('wp_enqueue_scripts','simple_tableau_init');

function simple_tableau_init() {
	//Include Tableau JS to allow the Viz to be rendered. min script uses document write which is blocked by browsers, hence use the normal js
    wp_enqueue_script( 'tableau-js', 'https://public.tableau.com/javascripts/api/tableau-2.1.1.js', false);

	//Enqueue the public js
	wp_enqueue_script( 'tableau-init-viz-js', plugin_dir_url( __FILE__ ) .'js/simple-tableau-viz-public.js',array('jquery'), true);
}
// load the js to support blocks
function loadSimpleTableauVizFiles() {
  wp_enqueue_script(
    'simple-tableau-viz',
    plugin_dir_url(__FILE__) . 'js/simple-tableau-viz-block.js',
    array('wp-blocks', 'wp-editor'),
    true
  );
}

add_action('enqueue_block_editor_assets', 'loadSimpleTableauVizFiles');