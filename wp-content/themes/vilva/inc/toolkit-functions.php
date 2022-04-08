<?php
/**
 * Toolkit Filters
 *
 * @package Vilva
 */
 
if( ! function_exists( 'vilva_default_image_text_image_size' ) ) :
    function vilva_default_image_text_image_size(){
        $return = 'vilva-blog';

        return $return;
    }
endif;
add_filter( 'bttk_it_img_size', 'vilva_default_image_text_image_size' );

if( ! function_exists( 'vilva_author_image' ) ) :
    function vilva_author_image(){
        return '';
    }
endif;
add_filter( 'author_bio_img_size', 'vilva_author_image' );

if( ! function_exists( 'vilva_advertisement_image' ) ) :
    function vilva_advertisement_image(){
        return 'full';
    }
endif;
add_filter( 'bttk_ad_img_size', 'vilva_advertisement_image' );