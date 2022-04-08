<?php
/**
 * Vilva Customizer Partials
 *
 * @package Vilva
 */

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function vilva_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function vilva_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if( ! function_exists( 'vilva_get_read_more' ) ) :
/**
 * Display blog readmore button
*/
function vilva_get_read_more(){
    return esc_html(get_theme_mod( 'read_more_text', __( 'Read More', 'vilva' ) ) );    
}
endif;

if( ! function_exists( 'vilva_get_related_title' ) ) :
/**
 * Display blog readmore button
*/
function vilva_get_related_title(){
    return esc_html( get_theme_mod( 'related_post_title', __( 'Recommended Articles', 'vilva' ) ) );
}
endif;

if( ! function_exists( 'vilva_get_banner_title' ) ) :
/**
 * Display Banner Title
*/
function vilva_get_banner_title(){
    return esc_html( get_theme_mod( 'banner_title', __( 'Find Your Best Holiday', 'vilva' ) ) );
}
endif;

if( ! function_exists( 'vilva_get_banner_sub_title' ) ) :
/**
 * Display Banner SubTitle
*/
function vilva_get_banner_sub_title(){
    return wpautop( wp_kses_post( get_theme_mod( 'banner_subtitle', __( 'Find great adventure holidays and activities around the planet.', 'vilva' ) ) ) );
}
endif;

if( ! function_exists( 'vilva_get_banner_button' ) ) :
/**
 * Display Banner Button Label
*/
function vilva_get_banner_button(){
    return esc_html( get_theme_mod( 'banner_button', __( 'Read More', 'vilva' ) ) );
}
endif;

if( ! function_exists( 'vilva_get_footer_copyright' ) ) :
/**
 * Footer Copyright
*/
function vilva_get_footer_copyright(){
    $copyright = get_theme_mod( 'footer_copyright' );

    echo '<span>'; 
    if( $copyright ){
        echo wp_kses_post( $copyright );
    }else{
        esc_html_e( '&copy; Copyright ', 'vilva' );
        echo date_i18n( esc_html__( 'Y', 'vilva' ) );
        echo ' <a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>. ';
        esc_html_e( 'All Rights Reserved. ', 'vilva' );
    }
    echo '</span>';
}
endif;