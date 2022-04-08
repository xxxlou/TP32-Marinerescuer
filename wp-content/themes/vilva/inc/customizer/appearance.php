<?php
/**
 * Appearance Settings
 *
 * @package Vilva
 */

function vilva_customize_register_appearance( $wp_customize ) {

    /** Appearance Settings */
    $wp_customize->add_panel( 
        'appearance_settings',
         array(
            'priority'    => 50,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'Appearance Settings', 'vilva' ),
            'description' => __( 'Customize Typography, Header Image & Background Image', 'vilva' ),
        ) 
    );

     /** Primary Color*/
    $wp_customize->add_setting( 
        'primary_color', 
        array(
            'default'           => '#90BAB5',
            'sanitize_callback' => 'sanitize_hex_color',
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'primary_color', 
            array(
                'label'       => __( 'Primary Color', 'vilva' ),
                'description' => __( 'Primary color of the theme.', 'vilva' ),
                'section'     => 'colors',
                'priority'    => 5,
            )
        )
    );

    /** Typography Settings */
    $wp_customize->add_section(
        'typography_settings',
        array(
            'title'    => __( 'Typography Settings', 'vilva' ),
            'priority' => 20,
            'panel'    => 'appearance_settings'
        )
    );
    
    /** Primary Font */
    $wp_customize->add_setting(
        'primary_font',
        array(
            'default'           => 'Nunito Sans',
            'sanitize_callback' => 'vilva_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Vilva_Select_Control(
            $wp_customize,
            'primary_font',
            array(
                'label'       => __( 'Primary Font', 'vilva' ),
                'description' => __( 'Primary font of the site.', 'vilva' ),
                'section'     => 'typography_settings',
                'choices'     => vilva_get_all_fonts(),   
            )
        )
    );
    
    /** Secondary Font */
    $wp_customize->add_setting(
        'secondary_font',
        array(
            'default'           => 'EB Garamond',
            'sanitize_callback' => 'vilva_sanitize_select'
        )
    );

    $wp_customize->add_control(
        new Vilva_Select_Control(
            $wp_customize,
            'secondary_font',
            array(
                'label'       => __( 'Secondary Font', 'vilva' ),
                'description' => __( 'Secondary font of the site.', 'vilva' ),
                'section'     => 'typography_settings',
                'choices'     => vilva_get_all_fonts(),   
            )
        )
    );  

    /** Font Size*/
    $wp_customize->add_setting( 
        'font_size', 
        array(
            'default'           => 18,
            'sanitize_callback' => 'vilva_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Slider_Control( 
            $wp_customize,
            'font_size',
            array(
                'section'     => 'typography_settings',
                'label'       => __( 'Font Size', 'vilva' ),
                'description' => __( 'Change the font size of your site.', 'vilva' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 50,
                    'step'  => 1,
                )                 
            )
        )
    );

    /** Move Background Image section to appearance panel */
    $wp_customize->get_section( 'colors' )->panel              = 'appearance_settings';
    $wp_customize->get_section( 'colors' )->priority           = 10;
    $wp_customize->get_section( 'background_image' )->panel    = 'appearance_settings';
    $wp_customize->get_section( 'background_image' )->priority = 15;
}
add_action( 'customize_register', 'vilva_customize_register_appearance' );
