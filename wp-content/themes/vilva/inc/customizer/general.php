<?php
/**
 * General Settings
 *
 * @package Vilva
 */

function vilva_customize_register_general( $wp_customize ){
    
    /** General Settings */
    $wp_customize->add_panel( 
        'general_settings',
         array(
            'priority'    => 60,
            'capability'  => 'edit_theme_options',
            'title'       => __( 'General Settings', 'vilva' ),
            'description' => __( 'Customize Banner, Featured, Social, Sharing, SEO, Post/Page, Newsletter & Instagram, Shop, Performance and Miscellaneous settings.', 'vilva' ),
        ) 
    );
    
    $wp_customize->get_section( 'header_image' )->panel                    = 'general_settings';
    $wp_customize->get_section( 'header_image' )->title                    = __( 'Banner Section', 'vilva' );
    $wp_customize->get_section( 'header_image' )->priority                 = 10;
    $wp_customize->get_control( 'header_image' )->active_callback          = 'vilva_banner_ac';
    $wp_customize->get_control( 'header_video' )->active_callback          = 'vilva_banner_ac';
    $wp_customize->get_control( 'external_header_video' )->active_callback = 'vilva_banner_ac';
    $wp_customize->get_section( 'header_image' )->description              = '';                                               
    $wp_customize->get_setting( 'header_image' )->transport                = 'refresh';
    $wp_customize->get_setting( 'header_video' )->transport                = 'refresh';
    $wp_customize->get_setting( 'external_header_video' )->transport       = 'refresh';
    
    /** Banner Options */
    $wp_customize->add_setting(
		'ed_banner_section',
		array(
			'default'			=> 'slider_banner',
			'sanitize_callback' => 'vilva_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Vilva_Select_Control(
    		$wp_customize,
    		'ed_banner_section',
    		array(
                'label'	      => __( 'Banner Options', 'vilva' ),
                'description' => __( 'Choose banner as static image/video or as a slider.', 'vilva' ),
    			'section'     => 'header_image',
    			'choices'     => array(
                    'no_banner'     => __( 'Disable Banner Section', 'vilva' ),
                    'static_banner' => __( 'Static/Video Banner', 'vilva' ),
                    'slider_banner' => __( 'Banner as Slider', 'vilva' ),
                ),
                'priority' => 5	
     		)            
		)
	);
    
    /** Slider Content Style */
    $wp_customize->add_setting(
		'slider_type',
		array(
			'default'			=> 'latest_posts',
			'sanitize_callback' => 'vilva_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Vilva_Select_Control(
    		$wp_customize,
    		'slider_type',
    		array(
                'label'	  => __( 'Slider Content Style', 'vilva' ),
    			'section' => 'header_image',
    			'choices' => vilva_slider_options(),
                'active_callback' => 'vilva_banner_ac'	
     		)
		)
	);
    
    /** Slider Category */
    $wp_customize->add_setting(
		'slider_cat',
		array(
			'default'			=> '',
			'sanitize_callback' => 'vilva_sanitize_select'
		)
	);

	$wp_customize->add_control(
		new Vilva_Select_Control(
    		$wp_customize,
    		'slider_cat',
    		array(
                'label'	          => __( 'Slider Category', 'vilva' ),
    			'section'         => 'header_image',
    			'choices'         => vilva_get_categories(),
                'active_callback' => 'vilva_banner_ac'	
     		)
		)
	);
    
    /** No. of slides */
    $wp_customize->add_setting(
        'no_of_slides',
        array(
            'default'           => 3,
            'sanitize_callback' => 'vilva_sanitize_number_absint'
        )
    );
    
    $wp_customize->add_control(
		new Vilva_Slider_Control( 
			$wp_customize,
			'no_of_slides',
			array(
				'section'     => 'header_image',
                'label'       => __( 'Number of Slides', 'vilva' ),
                'description' => __( 'Choose the number of slides you want to display', 'vilva' ),
                'choices'	  => array(
					'min' 	=> 1,
					'max' 	=> 20,
					'step'	=> 1,
				),
                'active_callback' => 'vilva_banner_ac'                 
			)
		)
	);
    
    /** HR */
    $wp_customize->add_setting(
        'banner_hr',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
			$wp_customize,
			'banner_hr',
			array(
				'section'	  => 'header_image',
				'description' => '<hr/>',
                'active_callback' => 'vilva_banner_ac'
			)
		)
    );
    
    /** Slider Auto */
    $wp_customize->add_setting(
        'slider_auto',
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
		new Vilva_Toggle_Control( 
			$wp_customize,
			'slider_auto',
			array(
				'section'     => 'header_image',
				'label'       => __( 'Slider Auto', 'vilva' ),
                'description' => __( 'Enable slider auto transition.', 'vilva' ),
                'active_callback' => 'vilva_banner_ac'
			)
		)
	);
    
    /** Slider Loop */
    $wp_customize->add_setting(
        'slider_loop',
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
		new Vilva_Toggle_Control( 
			$wp_customize,
			'slider_loop',
			array(
				'section'     => 'header_image',
				'label'       => __( 'Slider Loop', 'vilva' ),
                'description' => __( 'Enable slider loop.', 'vilva' ),
                'active_callback' => 'vilva_banner_ac'
			)
		)
	);
    
    /** Slider Caption */
    $wp_customize->add_setting(
        'slider_caption',
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
		new Vilva_Toggle_Control( 
			$wp_customize,
			'slider_caption',
			array(
				'section'     => 'header_image',
				'label'       => __( 'Slider Caption', 'vilva' ),
                'description' => __( 'Enable slider caption.', 'vilva' ),
                'active_callback' => 'vilva_banner_ac'
			)
		)
	);

    /** Repetitive Posts */
    $wp_customize->add_setting(
        'include_repetitive_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'include_repetitive_posts',
            array(
                'section'       => 'header_image',
                'label'         => __( 'Include Repetitive Posts', 'vilva' ),
                'description'   => __( 'Enable to add posts included in slider in blog page too.', 'vilva' ),
                'active_callback' => 'vilva_banner_ac'
            )
        )
    );

    /** Static Banner Title */
    $wp_customize->add_setting(
        'banner_title',
        array(
            'default'           => __( 'Find Your Best Holiday', 'vilva' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_title',
        array(
            'label'           => __( 'Title', 'vilva' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'vilva_banner_ac'
        )
    );

    $wp_customize->selective_refresh->add_partial( 'banner_title', array(
        'selector' => '.site-banner .banner-caption .banner-title',
        'render_callback' => 'vilva_get_banner_title',
    ) );

    /** Sub Title */
    $wp_customize->add_setting(
        'banner_subtitle',
        array(
            'default'           => __( 'Find great adventure holidays and activities around the planet.', 'vilva' ),
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_subtitle',
        array(
            'label'           => __( 'Sub Title', 'vilva' ),
            'section'         => 'header_image',
            'type'            => 'textarea',
            'active_callback' => 'vilva_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_subtitle', array(
        'selector' => '.site-banner .banner-caption .banner-desc',
        'render_callback' => 'vilva_get_banner_sub_title',
    ) );

    /** Banner Button Label */
    $wp_customize->add_setting(
        'banner_button',
        array(
            'default'           => __( 'Read More', 'vilva' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage'
        )
    );
    
    $wp_customize->add_control(
        'banner_button',
        array(
            'label'           => __( 'Banner Button Label', 'vilva' ),
            'section'         => 'header_image',
            'type'            => 'text',
            'active_callback' => 'vilva_banner_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'banner_button', array(
        'selector' => '.site-banner .banner-caption .btn',
        'render_callback' => 'vilva_get_banner_button',
    ) );

    /** Banner Link */
    $wp_customize->add_setting(
        'banner_url',
        array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    
    $wp_customize->add_control(
        'banner_url',
        array(
            'label'           => __( 'Banner Button Link', 'vilva' ),
            'section'         => 'header_image',
            'type'            => 'url',
            'active_callback' => 'vilva_banner_ac'
        )
    );
    /** End Of Banner section */

    /** Social Media Settings */
    $wp_customize->add_section(
        'social_media_settings',
        array(
            'title'    => __( 'Social Media Settings', 'vilva' ),
            'priority' => 30,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_social_links', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_social_links',
            array(
                'section'     => 'social_media_settings',
                'label'       => __( 'Enable Social Links', 'vilva' ),
                'description' => __( 'Enable to show social links at header and footer.', 'vilva' ),
            )
        )
    );
    
    $wp_customize->add_setting( 
        new Vilva_Repeater_Setting( 
            $wp_customize, 
            'social_links', 
            array(
                'default' => '',
                'sanitize_callback' => array( 'Vilva_Repeater_Setting', 'sanitize_repeater_setting' ),
            ) 
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Control_Repeater(
            $wp_customize,
            'social_links',
            array(
                'section' => 'social_media_settings',               
                'label'   => __( 'Social Links', 'vilva' ),
                'fields'  => array(
                    'font' => array(
                        'type'        => 'font',
                        'label'       => __( 'Font Awesome Icon', 'vilva' ),
                        'description' => __( 'Example: fab fa-facebook-f', 'vilva' ),
                    ),
                    'link' => array(
                        'type'        => 'url',
                        'label'       => __( 'Link', 'vilva' ),
                        'description' => __( 'Example: https://facebook.com', 'vilva' ),
                    )
                ),
                'row_label' => array(
                    'type' => 'field',
                    'value' => __( 'links', 'vilva' ),
                    'field' => 'link'
                )                        
            )
        )
    );
    /** Social Media Settings Ends */

    /** SEO Settings */
    $wp_customize->add_section(
        'seo_settings',
        array(
            'title'    => __( 'SEO Settings', 'vilva' ),
            'priority' => 40,
            'panel'    => 'general_settings',
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_post_update_date', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_post_update_date',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Last Update Post Date', 'vilva' ),
                'description' => __( 'Enable to show last updated post date on listing as well as in single post.', 'vilva' ),
            )
        )
    );
    
    /** Enable Social Links */
    $wp_customize->add_setting( 
        'ed_breadcrumb', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_breadcrumb',
            array(
                'section'     => 'seo_settings',
                'label'       => __( 'Enable Breadcrumb', 'vilva' ),
                'description' => __( 'Enable to show breadcrumb in inner pages.', 'vilva' ),
            )
        )
    );
    
    /** Breadcrumb Home Text */
    $wp_customize->add_setting(
        'home_text',
        array(
            'default'           => __( 'Home', 'vilva' ),
            'sanitize_callback' => 'sanitize_text_field' 
        )
    );
    
    $wp_customize->add_control(
        'home_text',
        array(
            'type'    => 'text',
            'section' => 'seo_settings',
            'label'   => __( 'Breadcrumb Home Text', 'vilva' ),
        )
    );  
    /** SEO Settings Ends */

    /** Posts(Blog) & Pages Settings */
    $wp_customize->add_section(
        'post_page_settings',
        array(
            'title'    => __( 'Posts(Blog) & Pages Settings', 'vilva' ),
            'priority' => 50,
            'panel'    => 'general_settings',
        )
    );
    
    /** Prefix Archive Page */
    $wp_customize->add_setting( 
        'ed_prefix_archive', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_prefix_archive',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Prefix in Archive Page', 'vilva' ),
                'description' => __( 'Enable to hide prefix in archive page.', 'vilva' ),
            )
        )
    );
        
    /** Blog Excerpt */
    $wp_customize->add_setting( 
        'ed_excerpt', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_excerpt',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Enable Blog Excerpt', 'vilva' ),
                'description' => __( 'Enable to show excerpt or disable to show full post content.', 'vilva' ),
            )
        )
    );
    
    /** Excerpt Length */
    $wp_customize->add_setting( 
        'excerpt_length', 
        array(
            'default'           => 25,
            'sanitize_callback' => 'vilva_sanitize_number_absint'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Slider_Control( 
            $wp_customize,
            'excerpt_length',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Excerpt Length', 'vilva' ),
                'description' => __( 'Automatically generated excerpt length (in words).', 'vilva' ),
                'choices'     => array(
                    'min'   => 10,
                    'max'   => 100,
                    'step'  => 5,
                )                 
            )
        )
    );
    
    /** Read More Text */
    $wp_customize->add_setting(
        'read_more_text',
        array(
            'default'           => __( 'Read More', 'vilva' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'read_more_text',
        array(
            'type'    => 'text',
            'section' => 'post_page_settings',
            'label'   => __( 'Read More Text', 'vilva' ),
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'read_more_text', array(
        'selector' => '.entry-footer .btn-readmore',
        'render_callback' => 'vilva_get_read_more',
    ) );
    
    /** Note */
    $wp_customize->add_setting(
        'post_note_text',
        array(
            'default'           => '',
            'sanitize_callback' => 'wp_kses_post' 
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Note_Control( 
            $wp_customize,
            'post_note_text',
            array(
                'section'     => 'post_page_settings',
                'description' => sprintf( __( '%s These options affect your individual posts.', 'vilva' ), '<hr/>' ),
            )
        )
    );

    /** Enable Image Cropped Size In Single Posts */
    $wp_customize->add_setting( 
        'ed_crop_single', 
        array(
            'default'           => false,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_crop_single',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Single Post Image Crop', 'vilva' ),
                'description' => __( 'Enable to avoid automatic cropping of featured image in single post.', 'vilva' ),
            )
        )
    );
         
    /** Show Related Posts */
    $wp_customize->add_setting( 
        'ed_related', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_related',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Related Posts', 'vilva' ),
                'description' => __( 'Enable to show related posts in single page.', 'vilva' ),
            )
        )
    );
    
    /** Related Posts section title */
    $wp_customize->add_setting(
        'related_post_title',
        array(
            'default'           => __( 'Recommended Articles', 'vilva' ),
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage' 
        )
    );
    
    $wp_customize->add_control(
        'related_post_title',
        array(
            'type'            => 'text',
            'section'         => 'post_page_settings',
            'label'           => __( 'Related Posts Section Title', 'vilva' ),
            'active_callback' => 'vilva_post_page_ac'
        )
    );
    
    $wp_customize->selective_refresh->add_partial( 'related_post_title', array(
        'selector' => '.additional-post .post-title',
        'render_callback' => 'vilva_get_related_title',
    ) );
        
    /** Comments */
    $wp_customize->add_setting(
        'ed_comments',
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_comments',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Comments', 'vilva' ),
                'description' => __( 'Enable to show Comments in Single Post/Page.', 'vilva' ),
            )
        )
    );
    
    /** Hide Category */
    $wp_customize->add_setting( 
        'ed_category', 
        array(
            'default'           => false,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_category',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Category', 'vilva' ),
                'description' => __( 'Enable to hide category.', 'vilva' ),
            )
        )
    );
    
    /** Hide Post Author */
    $wp_customize->add_setting( 
        'ed_post_author', 
        array(
            'default'           => false,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_post_author',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Post Author', 'vilva' ),
                'description' => __( 'Enable to hide post author.', 'vilva' ),
            )
        )
    );
    
    /** Hide Posted Date */
    $wp_customize->add_setting( 
        'ed_post_date', 
        array(
            'default'           => false,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_post_date',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Hide Posted Date', 'vilva' ),
                'description' => __( 'Enable to hide posted date.', 'vilva' ),
            )
        )
    );

     /** Hide Category */
    $wp_customize->add_setting( 
        'ed_featured_image', 
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_featured_image',
            array(
                'section'     => 'post_page_settings',
                'label'       => __( 'Show Featured Image', 'vilva' ),
                'description' => __( 'Enable to show featured image on single post.', 'vilva' ),
            )
        )
    );
    
    /** Posts(Blog) & Pages Settings Ends */

    /** Newsletter Settings */
    $wp_customize->add_section(
        'newsletter_settings',
        array(
            'title'    => __( 'Newsletter Settings', 'vilva' ),
            'priority' => 60,
            'panel'    => 'general_settings',
        )
    );
           
    /** Enable Newsletter Section */
    $wp_customize->add_setting( 
        'ed_newsletter', 
        array(
            'default'           => false,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_newsletter',
            array(
                'section'     => 'newsletter_settings',
                'label'       => __( 'Newsletter Section', 'vilva' ),
                'description' => __( 'Enable to show Newsletter Section', 'vilva' ),
            )
        )
    );
    
    if( vilva_is_btnw_activated() ){
        /** Newsletter Shortcode */
        $wp_customize->add_setting(
            'newsletter_shortcode',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post',
            )
        );
        
        $wp_customize->add_control(
            'newsletter_shortcode',
            array(
                'type'        => 'text',
                'section'     => 'newsletter_settings',
                'label'       => __( 'Newsletter Shortcode', 'vilva' ),
                'description' => __( 'Enter the BlossomThemes Email Newsletters Shortcode. Ex. [BTEN id="356"]', 'vilva' ),
                'active_callback' => 'vilva_ed_newsletter'
            )
        ); 
    } else {
        $wp_customize->add_setting(
            'newsletter_recommend',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Vilva_Note_Control( 
                $wp_customize,
                'newsletter_recommend',
                array(
                    'section'     => 'newsletter_settings',
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Email Newsletter%2$s. After that option related with this section will be visible.', 'vilva' ), '<strong>', '</strong>' ),
                )
            )
        );
    }

    /** Newsletter Settings Ends */

     /** Instagram Settings */
    $wp_customize->add_section(
        'instagram_settings',
        array(
            'title'    => __( 'Instagram Settings', 'vilva' ),
            'priority' => 70,
            'panel'    => 'general_settings',
        )
    );
    
    if( vilva_is_btif_activated() ){
        /** Enable Instagram Section */
        $wp_customize->add_setting( 
            'ed_instagram', 
            array(
                'default'           => false,
                'sanitize_callback' => 'vilva_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Vilva_Toggle_Control( 
                $wp_customize,
                'ed_instagram',
                array(
                    'section'     => 'instagram_settings',
                    'label'       => __( 'Instagram Section', 'vilva' ),
                    'description' => __( 'Enable to show Instagram Section', 'vilva' ),
                )
            )
        );
        
        /** Note */
        $wp_customize->add_setting(
            'instagram_text',
            array(
                'default'           => '',
                'sanitize_callback' => 'wp_kses_post' 
            )
        );
        
        $wp_customize->add_control(
            new Vilva_Note_Control( 
                $wp_customize,
                'instagram_text',
                array(
                    'section'     => 'instagram_settings',
                    'description' => sprintf( __( 'You can change the setting BlossomThemes Social Feed %1$sfrom here%2$s.', 'vilva' ), '<a href="' . esc_url( admin_url( 'admin.php?page=class-blossomthemes-instagram-feed-admin.php' ) ) . '" target="_blank">', '</a>' ),
                  'active_callback'  => 'vilva_ed_instagram'               
                )
            )
        );   

        // Instagram Background Image.
        $wp_customize->add_setting(
            'instagram_bg_image',
            array(
                'sanitize_callback' => 'vilva_sanitize_image',
            )
        );
        
        $wp_customize->add_control(
           new WP_Customize_Image_Control(
               $wp_customize,
               'instagram_bg_image',
               array(
                   'label'           => __( 'Instagram Background Image', 'vilva' ),
                   'description'     => __( 'Upload your instagram background image.', 'vilva' ),
                   'section'         => 'instagram_settings',
                  'active_callback'  => 'vilva_ed_instagram'               
               )
           )
        );      
    }else{
        $wp_customize->add_setting(
            'instagram_text',
            array(
                'sanitize_callback' => 'wp_kses_post',
            )
        );

        $wp_customize->add_control(
            new Vilva_Note_Control(
                $wp_customize,
                'instagram_text',
                array(
                    'section'     => 'instagram_settings',
                    'description' => sprintf( __( 'Please install and activate the recommended plugin %1$sBlossomThemes Social Feed%2$s. After that option related with this section will be visible.', 'vilva' ), '<a href="' . esc_url( admin_url( 'themes.php?page=tgmpa-install-plugins' ) ) . '" target="_blank">', '</a>' )
                )
            )
        );
    }

    /** Instagram Settings Ends */

    /** Shop Settings */
    $wp_customize->add_section(
        'shop_settings',
        array(
            'title'    => __( 'Shop Settings', 'vilva' ),
            'priority' => 75,
            'panel'    => 'general_settings',
        )
    );
    
    if( vilva_is_woocommerce_activated() ){
        /** Shop Section */
        $wp_customize->add_setting( 
            'ed_shopping_cart', 
            array(
                'default'           => true,
                'sanitize_callback' => 'vilva_sanitize_checkbox'
            ) 
        );
        
        $wp_customize->add_control(
            new Vilva_Toggle_Control( 
                $wp_customize,
                'ed_shopping_cart',
                array(
                    'section'     => 'shop_settings',
                    'label'       => __( 'Shopping Cart', 'vilva' ),
                    'description' => __( 'Enable to show Shopping cart in the header.', 'vilva' ),
                )
            )
        );  
    }

    // Shop Background Image.
    $wp_customize->add_setting(
        'shop_bg_image',
        array(
            'sanitize_callback' => 'vilva_sanitize_image',
        )
    );
    
    $wp_customize->add_control(
       new WP_Customize_Image_Control(
           $wp_customize,
           'shop_bg_image',
           array(
               'label'           => __( 'Shop Background Image', 'vilva' ),
               'description'     => __( 'Upload your shop background image.', 'vilva' ),
               'section'         => 'shop_settings',
              'active_callback'  => 'vilva_is_woocommerce_activated'               
           )
       )
    );
    
    /** Shop Page Description */
    $wp_customize->add_setting( 
        'ed_shop_archive_description', 
        array(
            'default'           => false,
            'sanitize_callback' => 'vilva_sanitize_checkbox'
        ) 
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_shop_archive_description',
            array(
                'section'         => 'shop_settings',
                'label'           => __( 'Shop Page Description', 'vilva' ),
                'description'     => __( 'Enable to show Shop Page Description.', 'vilva' ),
                'active_callback' => 'vilva_is_woocommerce_activated'
            )
        )
    );

    /** Shop Settings Ends */

    /** Miscellaneous Settings */
    $wp_customize->add_section(
        'misc_settings',
        array(
            'title'    => __( 'Misc Settings', 'vilva' ),
            'priority' => 85,
            'panel'    => 'general_settings',
        )
    );
        
    /** Header Search */
    $wp_customize->add_setting(
        'ed_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'vilva_sanitize_checkbox',
        )
    );
    
    $wp_customize->add_control(
        new Vilva_Toggle_Control( 
            $wp_customize,
            'ed_header_search',
            array(
                'section'       => 'misc_settings',
                'label'         => __( 'Header Search', 'vilva' ),
                'description'   => __( 'Enable to display search form in header.', 'vilva' ),
            )
        )
    ); 

    /** Miscellaneous Settings Endings */
      
}
add_action( 'customize_register', 'vilva_customize_register_general' );

if ( ! function_exists( 'vilva_slider_options' ) ) :
    /**
     * @return array Content type options
     */
    function vilva_slider_options() {
        $slider_options = array(
            'latest_posts' => __( 'Latest Posts', 'vilva' ),
            'cat'          => __( 'Category', 'vilva' ),
        );
        if ( vilva_is_delicious_recipe_activated() ) {
            $slider_options = array_merge( $slider_options, array( 'latest_dr_recipe' => __( 'Latest Recipes', 'vilva' ) ) );
        }
        $output = apply_filters( 'vilva_slider_options', $slider_options );
        return $output;
    }
endif;