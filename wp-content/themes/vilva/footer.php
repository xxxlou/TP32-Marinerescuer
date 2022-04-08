<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Vilva
 */
    
    /**
     * After Content
     * 
     * @hooked vilva_content_end - 20
    */
    do_action( 'vilva_before_footer' );

    if( ! is_single() ) vilva_newsletter();
    
    /**
     * Footer
     * @hooked vilva_instagram_section  - 15
     * @hooked vilva_footer_start  - 20
     * @hooked vilva_footer_top    - 30
     * @hooked vilva_footer_bottom - 40
     * @hooked vilva_footer_end    - 50
    */
    do_action( 'vilva_footer' );
    
    /**
     * After Footer
     * 
     * @hooked vilva_back_to_top - 15
     * @hooked vilva_page_end    - 20
    */
    do_action( 'vilva_after_footer' );

    wp_footer(); ?>

</body>
</html>
