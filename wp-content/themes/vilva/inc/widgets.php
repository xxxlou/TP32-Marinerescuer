<?php
/**
 * Vilva Widget Areas
 * 
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 * @package Vilva
 */

function vilva_widgets_init(){    
    $sidebars = array(
        'sidebar'   => array(
            'name'        => __( 'Sidebar', 'vilva' ),
            'id'          => 'sidebar', 
            'description' => __( 'Default Sidebar', 'vilva' ),
        ),
        'featured-area' => array(
            'name'        => __( 'Featured Area Section', 'vilva' ),
            'id'          => 'featured-area', 
            'description' => __( 'Add "Blossom: Image Text" widget for featured area section.', 'vilva' ),
        ),
        'footer-one'=> array(
            'name'        => __( 'Footer One', 'vilva' ),
            'id'          => 'footer-one', 
            'description' => __( 'Add footer one widgets here.', 'vilva' ),
        ),
        'footer-two'=> array(
            'name'        => __( 'Footer Two', 'vilva' ),
            'id'          => 'footer-two', 
            'description' => __( 'Add footer two widgets here.', 'vilva' ),
        ),
        'footer-three'=> array(
            'name'        => __( 'Footer Three', 'vilva' ),
            'id'          => 'footer-three', 
            'description' => __( 'Add footer three widgets here.', 'vilva' ),
        ),
        'footer-four'=> array(
            'name'        => __( 'Footer Four', 'vilva' ),
            'id'          => 'footer-four', 
            'description' => __( 'Add footer four widgets here.', 'vilva' ),
        )
    );
    
    foreach( $sidebars as $sidebar ){
        register_sidebar( array(
    		'name'          => esc_html( $sidebar['name'] ),
    		'id'            => esc_attr( $sidebar['id'] ),
    		'description'   => esc_html( $sidebar['description'] ),
    		'before_widget' => '<section id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</section>',
    		'before_title'  => '<h2 class="widget-title" itemprop="name">',
    		'after_title'   => '</h2>',
    	) );
    }
}
add_action( 'widgets_init', 'vilva_widgets_init' );