<?php
/**
 * Active Callback for Banner Slider
*/
function vilva_banner_ac( $control ){
    $banner        = $control->manager->get_setting( 'ed_banner_section' )->value();
    $slider_type   = $control->manager->get_setting( 'slider_type' )->value();
    $header_video  = $control->manager->get_setting( 'header_video' )->value();
    $external_header_video = $control->manager->get_setting( 'external_header_video' )->value();
    $control_id    = $control->id;
    
    if ( $control_id == 'header_image' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'header_video' && $banner == 'static_banner' ) return true;
    if ( $control_id == 'external_header_video' && $banner == 'static_banner' ) return true;    
    if ( $control_id == 'slider_type' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_auto' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_loop' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'slider_caption' && $banner == 'slider_banner' ) return true; 
    if ( $control_id == 'slider_cat' && $banner == 'slider_banner' && $slider_type == 'cat' ) return true;
    if ( $control_id == 'no_of_slides' && $banner == 'slider_banner' && ( $slider_type == 'latest_posts' || ( vilva_is_delicious_recipe_activated() ) && $slider_type = 'latest_dr_recipes') ) return true;
    if ( $control_id == 'include_repetitive_posts' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'banner_hr' && $banner == 'slider_banner' ) return true;
    if ( $control_id == 'banner_title' && $banner == 'static_banner' ) return true;  
    if ( $control_id == 'banner_subtitle' && $banner == 'static_banner' ) return true;    
    if ( $control_id == 'banner_button' && $banner == 'static_banner' ) return true;    
    if ( $control_id == 'banner_url' && $banner == 'static_banner' ) return true;  
    
    return false;
}

/**
 * Active Callback for post/page
*/
function vilva_post_page_ac( $control ){    
    $ed_related    = $control->manager->get_setting( 'ed_related' )->value();
    $control_id    = $control->id;
    
    if ( $control_id == 'related_post_title' && $ed_related == true ) return true ;    
    return false;
}

/**
 * Active Callback for Newsletter.
*/
function vilva_ed_newsletter(){
    
    $ed_newsletter = get_theme_mod( 'ed_newsletter', true );

    if ( $ed_newsletter ) return true;

    return false;
}

/**
 * Active Callback for Instagram.
*/
function vilva_ed_instagram(){
    
    $ed_instagram = get_theme_mod( 'ed_instagram', false );

    if ( $ed_instagram ) return true;

    return false;
}