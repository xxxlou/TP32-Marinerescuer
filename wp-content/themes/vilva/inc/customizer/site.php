<?php
/**
 * Site Title Setting
 *
 * @package Vilva
 */

function vilva_customize_register( $wp_customize ) {
	
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'background_color' )->transport = 'refresh';
    $wp_customize->get_setting( 'background_image' )->transport = 'refresh';
	
	if( isset( $wp_customize->selective_refresh ) ){
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'vilva_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'vilva_customize_partial_blogdescription',
		) );
	}
    
    /** Site Title Font */
    $wp_customize->add_setting( 
        'site_title_font', 
        array(
            'default' => array(                                			
                'font-family' => 'EB Garamond',
                'variant'     => 'regular',
            ),
            'sanitize_callback' => array( 'Vilva_Fonts', 'sanitize_typography' )
        ) 
    );

	$wp_customize->add_control( 
        new Vilva_Typography_Control( 
            $wp_customize, 
            'site_title_font', 
            array(
                'label'       => __( 'Site Title Font', 'vilva' ),
                'description' => __( 'Site title and tagline font.', 'vilva' ),
                'section'     => 'title_tagline',
                'priority'    => 60, 
            ) 
        ) 
    );
    
    /** Site Logo Size */
    $wp_customize->add_setting(
        'site_logo_size',
        array(
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'vilva_sanitize_number_absint',
            'default'           => 70, 
        )
    );

    $wp_customize->add_control(
        'site_logo_size',
        array(
            'type'    => 'number',
            'section' => 'title_tagline', 
            'label'   => __( 'Set the width(px) of your Site Logo', 'vilva' ),
        )
    );
    
    /** Site Title Font Size*/
    $wp_customize->add_setting( 
        'site_title_font_size', 
        array(
            'default'           => 30,
            'sanitize_callback' => 'vilva_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
		new Vilva_Slider_Control( 
			$wp_customize,
			'site_title_font_size',
			array(
				'section'	  => 'title_tagline',
				'label'		  => __( 'Site Title Font Size', 'vilva' ),
				'description' => __( 'Change the font size of your site title.', 'vilva' ),
                'priority'    => 65,
                'choices'	  => array(
					'min' 	=> 10,
					'max' 	=> 200,
					'step'	=> 1,
				)                 
			)
		)
	);
    
    /** Site Title Color*/
    $wp_customize->add_setting( 
        'site_title_color', 
        array(
            'default'           => '#121212',
            'sanitize_callback' => 'sanitize_hex_color'
        ) 
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
            $wp_customize, 
            'site_title_color', 
            array(
                'label'       => __( 'Site Title Color', 'vilva' ),
                'description' => __( 'Site Title color of the theme.', 'vilva' ),
                'section'     => 'title_tagline',
                'priority'    => 66,
            )
        )
    );
    
}
add_action( 'customize_register', 'vilva_customize_register' );