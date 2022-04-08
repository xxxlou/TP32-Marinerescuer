<?php
/**
 * Vilva Custom functions and definitions
 *
 * @package Vilva
 */

if ( ! function_exists( 'vilva_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function vilva_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Vilva, use a find and replace
	 * to change 'vilva' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'vilva', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'   => esc_html__( 'Primary', 'vilva' ),
        'secondary' => esc_html__( 'Secondary', 'vilva' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-list',
		'gallery',
		'caption',
	) );
    
    // Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'vilva_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
    
	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support( 
        'custom-logo', 
        apply_filters( 
            'vilva_custom_logo_args', 
            array( 
                'height'      => 70, /** change height as per theme requirement */
                'width'       => 70, /** change width as per theme requirement */
                'flex-height' => true,
                'flex-width'  => true,
                'header-text' => array( 'site-title', 'site-description' ) 
            )
        ) 
    );
    
    /**
     * Add support for custom header.
    */
    add_theme_support( 
        'custom-header', 
        apply_filters( 
            'vilva_custom_header_args', 
            array(
                'default-image' => '',
                'video'         => true,
                'width'         => 1920, /** change width as per theme requirement */
                'height'        => 600, /** change height as per theme requirement */
                'header-text'   => false
            ) 
        ) 
    );

    /**
     * Add support for Delicious Recipes Plugin.
    */
    add_theme_support('delicious-recipes');
 
    /**
     * Add Custom Images sizes.
    */    
    add_image_size( 'vilva-schema', 600, 60 );    
    add_image_size( 'vilva-slider-one', 1220, 600, true );
    add_image_size( 'vilva-featured-four', 800, 530, true );
    add_image_size( 'vilva-blog', 420, 280, true );
    add_image_size( 'vilva-blog-one', 900, 500, true );
    add_image_size( 'vilva-sidebar', 840, 473, true );
    
    // Add theme support for Responsive Videos.
    add_theme_support( 'jetpack-responsive-videos' );

    // Add excerpt support for pages
    add_post_type_support( 'page', 'excerpt' );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );

    // Remove widget block.
    remove_theme_support( 'widgets-block-editor' );
}
endif;
add_action( 'after_setup_theme', 'vilva_setup' );

if( ! function_exists( 'vilva_content_width' ) ) :
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function vilva_content_width() {
	/** 
     * content width.
    */
    $GLOBALS['content_width'] = apply_filters( 'vilva_content_width', 840 );
}
endif;
add_action( 'after_setup_theme', 'vilva_content_width', 0 );

if( ! function_exists( 'vilva_template_redirect_content_width' ) ) :
/**
* Adjust content_width value according to template.
*
* @return void
*/
function vilva_template_redirect_content_width(){
	$sidebar = vilva_sidebar();
    if( $sidebar ){	 
        $GLOBALS['content_width'] = 840;     
	}else{
        if( is_singular() ){
            if( vilva_sidebar( true ) === 'full-width centered' ){
                $GLOBALS['content_width'] = 840; 
            }else{
                $GLOBALS['content_width'] = 1220;               
            }                
        }else{
            $GLOBALS['content_width'] = 1220;
        }
	}
}
endif;
add_action( 'template_redirect', 'vilva_template_redirect_content_width' );

if( ! function_exists( 'vilva_scripts' ) ) :
/**
 * Enqueue scripts and styles.
 */
function vilva_scripts() {
	// Use minified libraries if SCRIPT_DEBUG is false
    $build  = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '/build' : '';
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    if( vilva_is_woocommerce_activated() )
    wp_enqueue_style( 'vilva-woocommerce', get_template_directory_uri(). '/css' . $build . '/woocommerce' . $suffix . '.css', array(), VILVA_THEME_VERSION );
    
    wp_enqueue_style( 'owl-carousel', get_template_directory_uri(). '/css' . $build . '/owl.carousel' . $suffix . '.css', array(), '2.3.4' );
    wp_enqueue_style( 'animate', get_template_directory_uri(). '/css' . $build . '/animate' . $suffix . '.css', array(), '3.5.2' );
    wp_enqueue_style( 'vilva-google-fonts', vilva_fonts_url(), array(), null );
    wp_enqueue_style( 'vilva', get_stylesheet_uri(), array(), VILVA_THEME_VERSION );

    wp_enqueue_style( 'vilva-gutenberg', get_template_directory_uri(). '/css' . $build . '/gutenberg' . $suffix . '.css', array(), VILVA_THEME_VERSION );
    
    wp_enqueue_script( 'all', get_template_directory_uri() . '/js' . $build . '/all' . $suffix . '.js', array( 'jquery' ), '5.6.3', true );
    wp_enqueue_script( 'v4-shims', get_template_directory_uri() . '/js' . $build . '/v4-shims' . $suffix . '.js', array( 'jquery', 'all' ), '5.6.3', true );
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js' . $build . '/owl.carousel' . $suffix . '.js', array( 'jquery' ), '2.3.4', true );

    wp_enqueue_script( 'owlcarousel2-a11ylayer', get_template_directory_uri() . '/js' . $build . '/owlcarousel2-a11ylayer' . $suffix . '.js', array( 'jquery', 'owl-carousel' ), '0.2.1', true );

	wp_enqueue_script( 'vilva', get_template_directory_uri() . '/js' . $build . '/custom' . $suffix . '.js', array( 'jquery', 'masonry' ), VILVA_THEME_VERSION, true );

    wp_enqueue_script( 'vilva-modal', get_template_directory_uri() . '/js' . $build . '/modal-accessibility' . $suffix . '.js', array( 'jquery' ), VILVA_THEME_VERSION, true );
    
    $array = array( 
        'rtl'           => is_rtl(),
        'auto'          => (bool)get_theme_mod( 'slider_auto', true ),
		'loop'          => (bool)get_theme_mod( 'slider_loop', true ),
    );
    
    wp_localize_script( 'vilva', 'vilva_data', $array );  
    
    if ( vilva_is_jetpack_activated( true ) ) {
        wp_enqueue_style( 'tiled-gallery', plugins_url() . '/jetpack/modules/tiled-gallery/tiled-gallery/tiled-gallery.css' );           
    }
    
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif;
add_action( 'wp_enqueue_scripts', 'vilva_scripts' );

if( ! function_exists( 'vilva_admin_scripts' ) ) :
/**
 * Enqueue admin scripts and styles.
*/
function vilva_admin_scripts( $hook ){
    wp_enqueue_style( 'vilva-admin', get_template_directory_uri() . '/inc/css/admin.css', '', VILVA_THEME_VERSION );
}
endif; 
add_action( 'admin_enqueue_scripts', 'vilva_admin_scripts' );

if( ! function_exists( 'vilva_body_classes' ) ) :
/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function vilva_body_classes( $classes ) {

    $editor_options = get_option( 'classic-editor-replace' );
    $allow_users_options = get_option( 'classic-editor-allow-users' );
    
    // Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

    // Adds a class of custom-background-image to sites with a custom background image.
    if ( get_background_image() ) {
        $classes[] = 'custom-background-image';
    }
    
    // Adds a class of custom-background-color to sites with a custom background color.
    if ( get_background_color() != 'ffffff' ) {
        $classes[] = 'custom-background-color';
    }   

    if ( ( is_archive() && !( vilva_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() ) ) && !( vilva_is_delicious_recipe_activated() && ( is_post_type_archive( 'recipe' ) || is_tax( 'recipe-course' ) || is_tax( 'recipe-cuisine' ) || is_tax( 'recipe-cooking-method' ) || is_tax( 'recipe-key' ) || is_tax( 'recipe-tag' ) ) ) ) || is_search() || is_home() ) {
        $classes[] = 'post-layout-one';
    }

    if ( !vilva_is_classic_editor_activated() || ( vilva_is_classic_editor_activated() && $editor_options == 'block' ) || ( vilva_is_classic_editor_activated() && $allow_users_options == 'allow' && has_blocks() ) ) {
        $classes[] = 'vilva-has-blocks';
    }

    if( is_singular( 'post' ) ){        
        $classes[] = 'single-style-four';
    }

    if ( is_single() || is_page() ) {        
        $classes[] = 'underline';
    }

    $classes[] = vilva_sidebar( true );
    
	return $classes;
}
endif;
add_filter( 'body_class', 'vilva_body_classes' );

if( ! function_exists( 'vilva_post_classes' ) ) :
/**
 * Add custom classes to the array of post classes.
*/
function vilva_post_classes( $classes ){    

    if( is_single() ){
        $classes[] = 'sticky-meta';
    }

    return $classes;
}
endif;
add_filter( 'post_class', 'vilva_post_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function vilva_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'vilva_pingback_header' );

if( ! function_exists( 'vilva_change_comment_form_default_fields' ) ) :
/**
 * Change Comment form default fields i.e. author, email & url.
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function vilva_change_comment_form_default_fields( $fields ){    
    // get the current commenter if available
    $commenter = wp_get_current_commenter();
 
    // core functionality
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );    
 
    // Change just the author field
    $fields['author'] = '<p class="comment-form-author"><label for="author">' . esc_html__( 'Name', 'vilva' ) . '<span class="required">*</span></label><input id="author" name="author" placeholder="' . esc_attr__( 'Name*', 'vilva' ) . '" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['email'] = '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'vilva' ) . '<span class="required">*</span></label><input id="email" name="email" placeholder="' . esc_attr__( 'Email*', 'vilva' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';
    
    $fields['url'] = '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'vilva' ) . '</label><input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'vilva' ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>'; 
    
    return $fields;    
}
endif;
add_filter( 'comment_form_default_fields', 'vilva_change_comment_form_default_fields' );

if( ! function_exists( 'vilva_change_comment_form_defaults' ) ) :
/**
 * Change Comment Form defaults
 * https://blog.josemcastaneda.com/2016/08/08/copy-paste-hurting-theme/
*/
function vilva_change_comment_form_defaults( $defaults ){    
    $defaults['comment_field'] = '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'vilva' ) . '</label><textarea id="comment" name="comment" placeholder="' . esc_attr__( 'Comment', 'vilva' ) . '" cols="45" rows="8" aria-required="true"></textarea></p>';
    
    return $defaults;    
}
endif;
add_filter( 'comment_form_defaults', 'vilva_change_comment_form_defaults' );

if ( ! function_exists( 'vilva_excerpt_more' ) ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... * 
 */
function vilva_excerpt_more( $more ) {
	return is_admin() ? $more : ' &hellip; ';
}
endif;
add_filter( 'excerpt_more', 'vilva_excerpt_more' );

if ( ! function_exists( 'vilva_excerpt_length' ) ) :
/**
 * Changes the default 55 character in excerpt 
*/
function vilva_excerpt_length( $length ) {
	$excerpt_length = get_theme_mod( 'excerpt_length', 25 );
    return is_admin() ? $length : absint( $excerpt_length );    
}
endif;
add_filter( 'excerpt_length', 'vilva_excerpt_length', 999 );

if( ! function_exists( 'vilva_search_form' ) ) :
/**
 * Search Form
*/
function vilva_search_form(){ 

    if( ! is_search() ){
        $placeholder = is_404() ? _x( 'Try searching for what you were looking for&hellip;', 'placeholder', 'vilva' ) : _x( 'Type & Hit Enter&hellip;', 'placeholder', 'vilva' );
        $form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
                    <label>
                        <span class="screen-reader-text">' . esc_html__( 'Looking for Something?', 'vilva' ) . '
                        </span>
                        <input type="search" class="search-field" placeholder="' . esc_attr( $placeholder ) . '" value="' . get_search_query() . '" name="s" />
                    </label>                
                    <input type="submit" id="submit-field" class="search-submit" value="'. esc_attr_x( 'Search', 'submit button', 'vilva' ) .'" />
                </form>';
     
        return $form;
    }
}
endif;
add_filter( 'get_search_form', 'vilva_search_form' );


if( ! function_exists( 'vilva_get_the_archive_title' ) ) :
/**
 * Filter Archive Title
*/
function vilva_get_the_archive_title( $title ){
    
    $ed_prefix = get_theme_mod( 'ed_prefix_archive', true );

    if( is_post_type_archive( 'product' ) ){
        $title = '<h1 class="page-title">' . esc_html( get_the_title( get_option( 'woocommerce_shop_page_id' ) ) ) . '</h1>';
    }else{
        if( is_category() ){
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' .single_cat_title( '', false ). '</h1>';                                   
            }else{
                $title = sprintf( __( '%1$s Category: %2$s %3$s', 'vilva'), '<span class="sub-title">','</span>', '<h1 class="page-title">' . single_cat_title( '', false ) . '</h1>') ;
            }
        }elseif ( is_tag() ){
            if( $ed_prefix ){
                $title = '<h1 class="page-title">' .single_cat_title( '', false ). '</h1>';                                   
            }else{
                $title = sprintf( __( '%1$s Tag: %2$s %3$s', 'vilva'), '<span class="sub-title">','</span>', '<h1 class="page-title">' . single_tag_title( '', false ) . '</h1>') ;
            }
        }elseif( is_author() ){
            $title = '<span class="vcard">' . get_the_author() . '</span>';
        }elseif ( is_year() ) {
            if( $ed_prefix ){
                 $title = '<h1 class="page-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'vilva' ) ) . '</h1>';                                   
            }else{
                $title = sprintf( __( '%1$s Year: %2$s %3$s', 'vilva'), '<span class="sub-title">','</span>', '<h1 class="pate-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'vilva' ) ) . '</h1>') ;
            }
        }elseif ( is_month() ) {
            if( $ed_prefix ){
                 $title = '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'vilva' ) ) . '</h1>';                                   
            }else{
                $title = sprintf( __( '%1$s Month: %2$s %3$s', 'vilva'), '<span class="sub-title">','</span>', '<h1 class="page-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'vilva' ) ) . '</h1>') ;
            }
        }elseif ( is_day() ) {
            if( $ed_prefix ){
                 $title = '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'vilva' ) ) . '</h1>';                                   
            }else{
                $title = sprintf( __( '%1$s Day: %2$s %3$s', 'vilva'), '<span class="sub-title">','</span>', '<h1 class="page-title">' . get_the_date( _x( 'F j, Y', 'daily archives date format', 'vilva' ) ) .  '</h1>') ;
            }
        }elseif ( is_post_type_archive() ) {
            if( $ed_prefix ){
                 $title = '<h1 class="page-title">'  . post_type_archive_title( '', false ) . '</h1>';                             
            }else{
                $title = sprintf( __( '%1$s Archives: %2$s %3$s', 'vilva'), '<span class="sub-title">','</span>', '<h1 class="page-title">'  . post_type_archive_title( '', false ) . '</h1>') ;
            }
        }elseif ( is_tax() ) {
            $tax = get_taxonomy( get_queried_object()->taxonomy );
                if( $ed_prefix ){
                     $title = '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>';                                 
                }else{
                    $title = sprintf( __( '%1$s: %2$s', 'vilva' ), '<span>' . $tax->labels->singular_name . '</span>', '<h1 class="page-title">' . single_term_title( '', false ) . '</h1>' );
                }
        }else {
            $title = sprintf( __( '%1$sArchives%2$s', 'vilva' ), '<h1 class="page-title">', '</h1>' );
        }
    }
    return $title;    
}
endif;
add_filter( 'get_the_archive_title', 'vilva_get_the_archive_title' );

if( ! function_exists( 'vilva_remove_archive_description' ) ) :
/**
 * filter the_archive_description & get_the_archive_description to show post type archive
 * @param  string $description original description
 * @return string post type description if on post type archive
 */
function vilva_remove_archive_description( $description ){
    $ed_shop_archive_description = get_theme_mod( 'ed_shop_archive_description', false );
    if( is_post_type_archive( 'product' ) ) {
        if( ! $ed_shop_archive_description ){
            $description = '';
        }
    }
    return wpautop( wp_kses_post( $description ) );
}
endif;
add_filter( 'get_the_archive_description', 'vilva_remove_archive_description' );

if( ! function_exists( 'vilva_single_post_schema' ) ) :
/**
 * Single Post Schema
 *
 * @return string
 */
function vilva_single_post_schema() {
    if ( is_singular( 'post' ) ) {
        global $post;
        $custom_logo_id = get_theme_mod( 'custom_logo' );

        $site_logo   = wp_get_attachment_image_src( $custom_logo_id , 'vilva-schema' );
        $images      = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        $excerpt     = vilva_escape_text_tags( $post->post_excerpt );
        $content     = $excerpt === "" ? mb_substr( vilva_escape_text_tags( $post->post_content ), 0, 110 ) : $excerpt;
        $schema_type = ! empty( $custom_logo_id ) && has_post_thumbnail( $post->ID ) ? "BlogPosting" : "Blog";

        $args = array(
            "@context"  => esc_url( "http://schema.org" ),
            "@type"     => $schema_type,
            "mainEntityOfPage" => array(
                "@type" => "WebPage",
                "@id"   => esc_url( get_permalink( $post->ID ) )
            ),
            "headline"  => esc_html( get_the_title( $post->ID ) ),
            "datePublished" => esc_html( get_the_time( DATE_ISO8601, $post->ID ) ),
            "dateModified"  => esc_html( get_post_modified_time(  DATE_ISO8601, __return_false(), $post->ID ) ),
            "author"        => array(
                "@type"     => "Person",
                "name"      => vilva_escape_text_tags( get_the_author_meta( 'display_name', $post->post_author ) )
            ),
            "description" => ( class_exists('WPSEO_Meta') ? WPSEO_Meta::get_value( 'metadesc' ) : $content )
        );

        if ( has_post_thumbnail( $post->ID ) ) :
            $args['image'] = array(
                "@type"  => "ImageObject",
                "url"    => $images[0],
                "width"  => $images[1],
                "height" => $images[2]
            );
        endif;

        if ( ! empty( $custom_logo_id ) ) :
            $args['publisher'] = array(
                "@type"       => "Organization",
                "name"        => esc_html( get_bloginfo( 'name' ) ),
                "description" => wp_kses_post( get_bloginfo( 'description' ) ),
                "logo"        => array(
                    "@type"   => "ImageObject",
                    "url"     => $site_logo[0],
                    "width"   => $site_logo[1],
                    "height"  => $site_logo[2]
                )
            );
        endif;

        echo '<script type="application/ld+json">';
        if ( version_compare( PHP_VERSION, '5.4.0' , '>=' ) ) {
            echo wp_json_encode( $args, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
        } else {
            echo wp_json_encode( $args );
        }
        echo '</script>';
    }
}
endif;
add_action( 'wp_head', 'vilva_single_post_schema' );

if( ! function_exists( 'vilva_get_comment_author_link' ) ) :
/**
 * Filter to modify comment author link
 * @link https://developer.wordpress.org/reference/functions/get_comment_author_link/
 */
function vilva_get_comment_author_link( $return, $author, $comment_ID ){
    $comment = get_comment( $comment_ID );
    $url     = get_comment_author_url( $comment );
    $author  = get_comment_author( $comment );
 
    if ( empty( $url ) || 'http://' == $url )
        $return = '<span itemprop="name">'. esc_html( $author ) .'</span>';
    else
        $return = '<span itemprop="name"><a href=' . esc_url( $url ) . ' rel="external nofollow noopener" class="url" itemprop="url">' . esc_html( $author ) . '</a></span>';

    return $return;
}
endif;
add_filter( 'get_comment_author_link', 'vilva_get_comment_author_link', 10, 3 );

if( ! function_exists( 'vilva_filter_post_gallery' ) ) :
/**
 * Filter the output of the gallery. 
*/ 
function vilva_filter_post_gallery( $output, $attr, $instance ){
    global $post, $wp_locale;

    $html5 = current_theme_supports( 'html5', 'gallery' );
    $atts = shortcode_atts( array(
    	'order'      => 'ASC',
    	'orderby'    => 'menu_order ID',
    	'id'         => $post ? $post->ID : 0,
    	'itemtag'    => $html5 ? 'figure'     : 'dl',
    	'icontag'    => $html5 ? 'div'        : 'dt',
    	'captiontag' => $html5 ? 'figcaption' : 'dd',
    	'columns'    => 3,
    	'size'       => 'thumbnail',
    	'include'    => '',
    	'exclude'    => '',
    	'link'       => ''
    ), $attr, 'gallery' );
    
    $id = intval( $atts['id'] );
    
    if ( ! empty( $atts['include'] ) ) {
    	$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    
    	$attachments = array();
    	foreach ( $_attachments as $key => $val ) {
    		$attachments[$val->ID] = $_attachments[$key];
    	}
    } elseif ( ! empty( $atts['exclude'] ) ) {
    	$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    } else {
    	$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
    }
    
    if ( empty( $attachments ) ) {
    	return '';
    }
    
    if ( is_feed() ) {
    	$output = "\n";
    	foreach ( $attachments as $att_id => $attachment ) {
    		$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
    	}
    	return $output;
    }
    
    $itemtag = tag_escape( $atts['itemtag'] );
    $captiontag = tag_escape( $atts['captiontag'] );
    $icontag = tag_escape( $atts['icontag'] );
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) ) {
    	$itemtag = 'dl';
    }
    if ( ! isset( $valid_tags[ $captiontag ] ) ) {
    	$captiontag = 'dd';
    }
    if ( ! isset( $valid_tags[ $icontag ] ) ) {
    	$icontag = 'dt';
    }
    
    $columns = intval( $atts['columns'] );
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';
    
    $selector = "gallery-{$instance}";
    
    $gallery_style = '';
    
    /**
     * Filter whether to print default gallery styles.
     *
     * @since 3.1.0
     *
     * @param bool $print Whether to print default gallery styles.
     *                    Defaults to false if the theme supports HTML5 galleries.
     *                    Otherwise, defaults to true.
     */
    if ( apply_filters( 'vilva_use_default_gallery_style', ! $html5 ) ) {
    	$gallery_style = "
    	<style type='text/css'>
    		#{$selector} {
    			margin: auto;
    		}
    		#{$selector} .gallery-item {
    			float: {$float};
    			margin-top: 10px;
    			text-align: center;
    			width: {$itemwidth}%;
    		}
    		#{$selector} img {
    			border: 2px solid #cfcfcf;
    		}
    		#{$selector} .gallery-caption {
    			margin-left: 0;
    		}
    		/* see gallery_shortcode() in wp-includes/media.php */
    	</style>\n\t\t";
    }
    
    $size_class = sanitize_html_class( $atts['size'] );
    $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
    
    /**
     * Filter the default gallery shortcode CSS styles.
     *
     * @since 2.5.0
     *
     * @param string $gallery_style Default CSS styles and opening HTML div container
     *                              for the gallery shortcode output.
     */
    $output = apply_filters( 'vilva_gallery_style', $gallery_style . $gallery_div );
    
    $i = 0; 
    foreach ( $attachments as $id => $attachment ) {
            
    	$attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
    	if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
    		//$image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr ); // for attachment url 
            $image_output = "<a href='" . wp_get_attachment_url( $id ) . "' data-fancybox='group{$columns}' data-caption='" . esc_attr( $attachment->post_excerpt ) . "'>";
            $image_output .= wp_get_attachment_image( $id, $atts['size'], false, $attr );
            $image_output .= "</a>";
    	} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
    		$image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
    	} else {
    		$image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr ); //for attachment page
    	}
    	$image_meta  = wp_get_attachment_metadata( $id );
    
    	$orientation = '';
    	if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
    		$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
    	}
    	$output .= "<{$itemtag} class='gallery-item'>";
    	$output .= "
    		<{$icontag} class='gallery-icon {$orientation}'>
    			$image_output
    		</{$icontag}>";
    	if ( $captiontag && trim($attachment->post_excerpt) ) {
    		$output .= "
    			<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
    			" . wptexturize($attachment->post_excerpt) . "
    			</{$captiontag}>";
    	}
    	$output .= "</{$itemtag}>";
    	if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
    		$output .= '<br style="clear: both" />';
    	}
    }
    
    if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
    	$output .= "
    		<br style='clear: both' />";
    }
    
    $output .= "
    	</div>\n";
    
    return $output;
}
endif;
if( class_exists( 'Jetpack' ) ){
    if( !Jetpack::is_module_active( 'carousel' ) ){
        add_filter( 'post_gallery', 'vilva_filter_post_gallery', 10, 3 );
    }
}else{
    add_filter( 'post_gallery', 'vilva_filter_post_gallery', 10, 3 );
}

if( ! function_exists( 'vilva_admin_notice' ) ) :
/**
 * Addmin notice for getting started page
*/
function vilva_admin_notice(){
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'vilva_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();
    
    if( 'themes.php' == $pagenow && !$meta ){
        
        if( $current_screen->id !== 'dashboard' && $current_screen->id !== 'themes' ){
            return;
        }

        if( is_network_admin() ){
            return;
        }

        if( ! current_user_can( 'manage_options' ) ){
            return;
        } ?>

        <div class="welcome-message notice notice-info">
            <div class="notice-wrapper">
                <div class="notice-text">
                    <h3><?php esc_html_e( 'Congratulations!', 'vilva' ); ?></h3>
                    <p><?php printf( __( '%1$s is now installed and ready to use. Click below to see theme documentation, plugins to install and other details to get started.', 'vilva' ), esc_html( $name ) ) ; ?></p>
                    <p><a href="<?php echo esc_url( admin_url( 'themes.php?page=vilva-getting-started' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Go to the getting started.', 'vilva' ); ?></a></p>
                    <p class="dismiss-link"><strong><a href="?vilva_admin_notice=1"><?php esc_html_e( 'Dismiss', 'vilva' ); ?></a></strong></p>
                </div>
            </div>
        </div>
    <?php }
}
endif;
add_action( 'admin_notices', 'vilva_admin_notice' );

if( ! function_exists( 'vilva_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function vilva_update_admin_notice(){
    if ( isset( $_GET['vilva_admin_notice'] ) && $_GET['vilva_admin_notice'] = '1' ) {
        update_option( 'vilva_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'vilva_update_admin_notice' );

if( ! function_exists( 'vilva_exclude_cat' ) ) :
/**
 * Exclude post with Category from blog and archive page. 
*/
function vilva_exclude_cat( $query ){

    $ed_banner      = get_theme_mod( 'ed_banner_section', 'slider_banner' );
    $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' );
    $slider_cat     = get_theme_mod( 'slider_cat' );
    $posts_per_page = get_theme_mod( 'no_of_slides', 3 );
    $repetitive_posts = get_theme_mod( 'include_repetitive_posts', true );
    
    if( ! is_admin() && $query->is_main_query() && $query->is_home() && $ed_banner == 'slider_banner' && !$repetitive_posts ){
        if( $slider_type === 'cat' && $slider_cat  ){            
            $query->set( 'category__not_in', array( $slider_cat ) );            
        }elseif( $slider_type == 'latest_posts' ){
            $args = array(
                'post_type'           => 'post',
                'post_status'         => 'publish',
                'posts_per_page'      => $posts_per_page,
                'ignore_sticky_posts' => true
            );
            $latest = get_posts( $args );
            $excludes = array();
            foreach( $latest as $l ){
                array_push( $excludes, $l->ID );
            }
            $query->set( 'post__not_in', $excludes );
        }  
    }      
}
endif;
add_filter( 'pre_get_posts', 'vilva_exclude_cat' );

if ( ! function_exists( 'vilva_get_fontawesome_ajax' ) ) :
/**
 * Return an array of all icons.
 */
function vilva_get_fontawesome_ajax() {
    // Bail if the nonce doesn't check out
    if ( ! isset( $_POST['vilva_customize_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['vilva_customize_nonce'] ), 'vilva_customize_nonce' ) ) {
        wp_die();
    }

    // Do another nonce check
    check_ajax_referer( 'vilva_customize_nonce', 'vilva_customize_nonce' );

    // Bail if user can't edit theme options
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        wp_die();
    }

    // Get all of our fonts
    $fonts = vilva_get_fontawesome_list();
    
    ob_start();
    if( $fonts ){ ?>
        <ul class="font-group">
            <?php 
                foreach( $fonts as $font ){
                    echo '<li data-font="' . esc_attr( $font ) . '"><i class="' . esc_attr( $font ) . '"></i></li>';                        
                }
            ?>
        </ul>
        <?php
    }
    echo ob_get_clean();

    // Exit
    wp_die();
}
endif;
add_action( 'wp_ajax_vilva_get_fontawesome_ajax', 'vilva_get_fontawesome_ajax' );