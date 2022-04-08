<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Vilva
 */
    /**
     * Doctype Hook
     * 
     * @hooked vilva_doctype
    */
    do_action( 'vilva_doctype' );
?>
<head itemscope itemtype="http://schema.org/WebSite">
	<?php 
    /**
     * Before wp_head
     * 
     * @hooked vilva_head
    */
    do_action( 'vilva_before_wp_head' );
    
    wp_head(); ?>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

<?php
    wp_body_open();
    
    /**
     * Before Header
     * 
     * @hooked vilva_page_start - 20 
    */
    do_action( 'vilva_before_header' );
    
    /**
     * Header
     * 
     * @hooked vilva_header           - 20     
    */
    do_action( 'vilva_header' );
    
    /**
     * Before Content
     * 
     * @hooked vilva_banner             - 15
     * @hooked vilva_featured_area      - 20
     * @hooked vilva_top_bar            - 30
    */
    do_action( 'vilva_after_header' );
    
    /**
     * Content
     * 
     * @hooked vilva_content_start
    */
    do_action( 'vilva_content' );