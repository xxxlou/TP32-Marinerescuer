<?php
/**
 * Vilva Theme Customizer
 *
 * @package Vilva
 */

/**
 * Requiring customizer panels & sections
*/

$vilva_panels = array( 'info', 'site', 'appearance', 'layout', 'footer', 'general' );

foreach( $vilva_panels as $p ){
    require get_template_directory() . '/inc/customizer/' . $p . '.php';
}

/**
 * Sanitization Functions
*/
require get_template_directory() . '/inc/customizer/sanitization-functions.php';

/**
 * Active Callbacks
*/
require get_template_directory() . '/inc/customizer/active-callback.php';

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function vilva_customize_preview_js() {
	wp_enqueue_script( 'vilva-customizer', get_template_directory_uri() . '/inc/js/customizer.js', array( 'customize-preview' ), VILVA_THEME_VERSION, true );
}
add_action( 'customize_preview_init', 'vilva_customize_preview_js' );

function vilva_customize_script(){
    $array = array(
        'home'    => get_permalink( get_option( 'page_on_front' ) ),
    );
    
    wp_enqueue_style( 'vilva-customize', get_template_directory_uri() . '/inc/css/customize.css', array(), VILVA_THEME_VERSION );
    wp_enqueue_script( 'vilva-customize', get_template_directory_uri() . '/inc/js/customize.js', array( 'jquery', 'customize-controls' ), VILVA_THEME_VERSION, true );
    wp_localize_script( 'vilva-customize', 'vilva_cdata', $array );

    wp_localize_script( 'vilva-repeater', 'vilva_customize',
		array(
			'nonce' => wp_create_nonce( 'vilva_customize_nonce' )
		)
	);
}
add_action( 'customize_controls_enqueue_scripts', 'vilva_customize_script' );