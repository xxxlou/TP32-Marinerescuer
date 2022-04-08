<?php
/**
 * Vilva Template Functions which enhance the theme by hooking into WordPress
 *
 * @package Vilva
 */

if( ! function_exists( 'vilva_doctype' ) ) :
/**
 * Doctype Declaration
*/
function vilva_doctype(){ ?>
    <!DOCTYPE html>
    <html <?php language_attributes(); ?>>
    <?php
}
endif;
add_action( 'vilva_doctype', 'vilva_doctype' );

if( ! function_exists( 'vilva_head' ) ) :
/**
 * Before wp_head 
*/
function vilva_head(){ ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php
}
endif;
add_action( 'vilva_before_wp_head', 'vilva_head' );

if( ! function_exists( 'vilva_page_start' ) ) :
/**
 * Page Start
*/
function vilva_page_start(){ ?>
    <div id="page" class="site">
        <a class="skip-link" href="#content"><?php esc_html_e( 'Skip to Content', 'vilva' ); ?></a>
    <?php
}
endif;
add_action( 'vilva_before_header', 'vilva_page_start', 20 );

if( ! function_exists( 'vilva_header' ) ) :
/**
 * Header Start
*/
function vilva_header(){ 
    $ed_search = get_theme_mod( 'ed_header_search', true );
    $ed_cart   = get_theme_mod( 'ed_shopping_cart', true ); ?>

    <header id="masthead" class="site-header style-one" itemscope itemtype="http://schema.org/WPHeader">
        <div class="header-t">
            <div class="container">
                <?php vilva_secondary_navigation(); ?>
                <div class="right">
                    <?php if( vilva_social_links( false ) ) : ?>
                        <div class="header-social">
                            <?php  vilva_social_links(); ?>
                        </div><!-- .header-social -->
                    <?php endif; ?>
                    <?php 
                    if ( $ed_search ) { ?>
                        <div class="header-search">                
                            <button class="search-toggle" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false">
                                <i class="fas fa-search"></i>
                            </button>
                            <div class="header-search-wrap search-modal cover-modal" data-modal-target-string=".search-modal">
                                <div class="header-search-inner-wrap">
                                    <?php get_search_form(); ?>
                                    <button class="close" data-toggle-target=".search-modal" data-toggle-body-class="showing-search-modal" data-set-focus=".search-modal .search-field" aria-expanded="false"></button>
                                </div>
                            </div>
                        </div><!-- .header-search -->
                    <?php }
                    if ( vilva_is_woocommerce_activated() && $ed_cart ) vilva_wc_cart_count(); ?>            
                </div><!-- .right -->
            </div>
        </div><!-- .header-t -->

        <div class="header-mid">
            <div class="container">
                <?php vilva_site_branding(); ?>
            </div>
        </div><!-- .header-mid -->

        <div class="header-bottom">
            <div class="container">
                <?php vilva_primary_nagivation(); ?>
            </div>
        </div><!-- .header-bottom -->
    </header>
    <?php
}
endif;
add_action( 'vilva_header', 'vilva_header', 20 );

if( ! function_exists( 'vilva_banner' ) ) :
/**
 * Banner Section 
*/
function vilva_banner(){
    if( is_front_page() || is_home() ) {
        $ed_banner      = get_theme_mod( 'ed_banner_section', 'slider_banner' );
        $slider_type    = get_theme_mod( 'slider_type', 'latest_posts' ); 
        $slider_cat     = get_theme_mod( 'slider_cat' );
        $posts_per_page = get_theme_mod( 'no_of_slides', 3 );
        $ed_caption     = get_theme_mod( 'slider_caption', true );
        $banner_title   = get_theme_mod( 'banner_title', __( 'Find Your Best Holiday', 'vilva' ) );
        $banner_subtitle = get_theme_mod( 'banner_subtitle' , __( 'Find great adventure holidays and activities around the planet.', 'vilva' ) ) ;
        $banner_button   = get_theme_mod( 'banner_button', __( 'Read More', 'vilva' ) );
        $banner_url      = get_theme_mod( 'banner_url', '#' );    
        
        if( $ed_banner == 'static_banner' && has_custom_header() ){ 

            if( $ed_banner == 'static_banner' ) {
                $banner_class = ' static-cta-banner';
            }

            ?>
            <div class="site-banner<?php if( has_header_video() ) echo esc_attr( ' video-banner' ); echo $banner_class; ?>">
                <?php 
                the_custom_header_markup();

                if( $ed_banner == 'static_banner' && ( $banner_title || $banner_subtitle || ( $banner_button && $banner_url ) )){ ?>
                    <div class="banner-caption">
                        <div class="container">
                            <?php 
                            if( $banner_title ) echo '<h2 class="banner-title">' . esc_html( $banner_title ) . '</h2>';
                            if( $banner_subtitle ) echo '<div class="banner-desc">' . wp_kses_post( $banner_subtitle ) . '</div>';
                            if( $banner_button && $banner_url ) echo '<a href="' . esc_url( $banner_url ) . '" class="btn btn-green"><span>' . esc_html( $banner_button ) . '</span></a>';
                            ?>
                        </div>
                    </div> <?php 
                } ?>
            </div>
            <?php
        }elseif( $ed_banner == 'slider_banner' ){

            if( $slider_type == 'latest_posts' || $slider_type == 'cat' || ( vilva_is_delicious_recipe_activated() && $slider_type == 'latest_dr_recipe' ) ){
            
                $args = array(
                    'post_status'         => 'publish',            
                    'ignore_sticky_posts' => true
                );
                
                if( vilva_is_delicious_recipe_activated() && $slider_type == 'latest_dr_recipe' ){
                    $args['post_type']      = DELICIOUS_RECIPE_POST_TYPE;
                    $args['posts_per_page'] = $posts_per_page;
                }elseif( $slider_type === 'cat' && $slider_cat ){
                    $args['post_type']      = 'post';
                    $args['cat']            = $slider_cat; 
                    $args['posts_per_page'] = -1;  
                }else{
                    $args['post_type']      = 'post';
                    $args['posts_per_page'] = $posts_per_page;
                }
                    
                $qry = new WP_Query( $args );
            
                if( $qry->have_posts() ){ ?>

                    <div id="banner_section" class="site-banner style-one">
                        <div class="item-wrap owl-carousel">
                            <?php while( $qry->have_posts() ){ $qry->the_post(); ?>
                                <div class="item">
                                    <?php 
                                    if( has_post_thumbnail() ){
                                        the_post_thumbnail( 'vilva-slider-one', array( 'itemprop' => 'image' ) );    
                                    }else{ 
                                        vilva_get_fallback_svg( 'vilva-slider-one' );
                                    } 
                                    if( $ed_caption ){ ?>
                                        <div class="banner-caption">
                                            <div class="container">
                                                <div class="cat-links">
                                                    <?php if( vilva_is_delicious_recipe_activated() && DELICIOUS_RECIPE_POST_TYPE == get_post_type() ) {
                                                        vilva_recipe_category(); 
                                                    }else{
                                                        vilva_category(); 
                                                    } ?>
                                                </div>
                                                <h2 class="banner-title">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h2>                                            
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>                             
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata(); 
            }           
        } 
    }  
}
endif;
add_action( 'vilva_after_header', 'vilva_banner', 15 );

if( ! function_exists( 'vilva_featured_area' ) ) :
/**
 * Top Section
 * 
*/
function vilva_featured_area(){
    if( is_home() && is_active_sidebar( 'featured-area' ) ) { ?>
        <section id="featured_area" class="promo-section">
            <div class="container">
                <?php dynamic_sidebar( 'featured-area' ); ?>
            </div>
        </section> <!-- .featured-section -->
    <?php }      
}
endif;
add_action( 'vilva_after_header', 'vilva_featured_area', 20 );

if( ! function_exists( 'vilva_top_bar' ) ) :
/**
 * Top bar for single page and post
 * 
*/
function vilva_top_bar(){
    if( ! is_home() && ! is_front_page() && ! ( vilva_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() ) ) ){ ?>
        <div class="top-bar">
    		<div class="container">
            <?php vilva_breadcrumb(); ?>
    		</div>
    	</div>   
        <?php 
    }    
}
endif;
add_action( 'vilva_after_header', 'vilva_top_bar', 30 );

if( ! function_exists( 'vilva_content_start' ) ) :
/**
 * Content Start
 *  
*/
function vilva_content_start(){ 
    
    echo '<div id="content" class="site-content">'; 

    if ( vilva_is_woocommerce_activated() && ( is_shop() || is_product_category() || is_product_tag() ) )  {

        if ( is_shop() ) {
            $background_image = get_theme_mod( 'shop_bg_image' );
        }elseif( is_product_category() ){
            $cat_id = get_queried_object_id();
            $thumbnail_id = get_term_meta( $cat_id, 'thumbnail_id', true );
            $background_image = wp_get_attachment_url( $thumbnail_id );
        }

        ?>
        <header class="page-header" <?php if( $background_image ){ ?> style="background-image: url( '<?php echo esc_url( $background_image ); ?>' );"<?php } ?> >
            <div class="container">
                <?php 
                the_archive_title(); 
                the_archive_description( '<div class="archive-description">', '</div>' ); 
                vilva_breadcrumb();
                ?>
            </div>
        </header>
        <?php
    } 
    echo '<div class="container">';
}
endif;
add_action( 'vilva_content', 'vilva_content_start' );

if( ! function_exists( 'vilva_search_per_page_count' ) ):
/**
*   Counts the Number of total posts in Archive, Search and Author
*/
function vilva_search_per_page_count(){
    global $wp_query;
    if( is_archive() || is_search() && $wp_query->found_posts > 0 ) {
        $posts_per_page = get_option( 'posts_per_page' );
        $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
        $start_post_number = 0;
        $end_post_number   = 0;

        if( $wp_query->found_posts > 0 && !( vilva_is_woocommerce_activated() && is_shop() ) ):                
            $start_post_number = 1;
            if( $wp_query->found_posts < $posts_per_page  ) {
                $end_post_number = $wp_query->found_posts;
            }else{
                $end_post_number = $posts_per_page;
            }

            if( $paged > 1 ){
                $start_post_number = $posts_per_page * ( $paged - 1 ) + 1;
                if( $wp_query->found_posts < ( $posts_per_page * $paged )  ) {
                    $end_post_number = $wp_query->found_posts;
                }else{
                    $end_post_number = $paged * $posts_per_page;
                }
            }

            printf( esc_html__( '%1$s Showing:  %2$s - %3$s of %4$s RESULTS %5$s', 'vilva' ), '<span class="post-count">', absint( $start_post_number ), absint( $end_post_number ), esc_html( number_format_i18n( $wp_query->found_posts ) ), '</span>' );
        endif;
    }
}
endif; 

if( ! function_exists( 'vilva_entry_header' ) ) :
/**
 * Entry Header
*/
function vilva_entry_header(){ 
    global $wp_query;
    
    if( $wp_query->current_post == 0 ) return false;
    
    ?>
    <header class="entry-header">
        <?php                  
        if( 'post' === get_post_type() || 'blossom-portfolio' === get_post_type() ){

            echo '<div class="entry-meta">';
                vilva_posted_on(); 

                vilva_category();

            echo '</div>';
        }   

        the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); 
        ?>
    </header> 
    <?php  
}
endif;
add_action( 'vilva_post_entry_content', 'vilva_entry_header', 10 );

if ( ! function_exists( 'vilva_entry_header_first' ) ) :
/**
* Entry Header
*/
function vilva_entry_header_first(){
    global $wp_query ;

    if ( $wp_query->current_post == 0 && ! is_single() && ! is_page() ) {
        ?>
        <header class="entry-header">
            <?php      
                if( 'post' === get_post_type() || 'blossom-portfolio' === get_post_type() ){
                    echo '<div class="entry-meta">';
                        vilva_posted_on(); 
                        vilva_category(); 
                    echo '</div>';
                }   

                if ( is_singular() ) :
                    the_title( '<h1 class="entry-title">', '</h1>' );
                else :
                    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                endif; ?>
        </header>    
        <?php
    }

    if ( is_single() ) { ?>
        <header class="entry-header">
            <div class="container">
                <div class="entry-meta">
                    <?php
                    vilva_posted_on();
                    vilva_category();
                    ?>
                </div>

                <h1 class="entry-title"><?php the_title(); ?></h1>     

            </div>
        </header> 
    <?php
    }elseif( is_page() ){
        ?>
        <header class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </header> 
        <?php
    }

}
endif;
add_action( 'vilva_before_post_entry_content', 'vilva_entry_header_first', 10 );
add_action( 'vilva_before_page_entry_content', 'vilva_entry_header_first', 10 ); 


if ( ! function_exists( 'vilva_post_thumbnail' ) ) :
/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function vilva_post_thumbnail() {
    global $wp_query;
    $image_size     = 'thumbnail';
    $sidebar        = vilva_sidebar();
    $ed_crop_single = get_theme_mod( 'ed_crop_single', false );  
    $ed_featured_image = get_theme_mod( 'ed_featured_image', true );  

    if( is_home() ){        
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';

            if( $wp_query->current_post == 0 ) :                
                $image_size = ( $sidebar ) ? 'vilva-blog-one' : 'vilva-slider-one';
            else:
                $image_size = ( $sidebar ) ? 'vilva-blog' : 'vilva-featured-four';
            endif;

            if( has_post_thumbnail() ){                        
                the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
            }else{
                vilva_get_fallback_svg( $image_size );//fallback
            }         
        echo '</a></figure>';

    }elseif( is_archive() || is_search() ){
        echo '<figure class="post-thumbnail"><a href="' . esc_url( get_permalink() ) . '" class="post-thumbnail">';
        
        if( $wp_query->current_post == 0 ) :                
            $image_size = ( $sidebar ) ? 'vilva-blog-one' : 'vilva-slider-one';
        else:
            $image_size = ( $sidebar ) ? 'vilva-blog' : 'vilva-featured-four';
        endif;

        if( has_post_thumbnail() ){
            the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
        }else{
            vilva_get_fallback_svg( $image_size );//fallback
        }
        echo '</a></figure>';
    }elseif( is_page() ){
        
            $image_size = ( $sidebar ) ? 'vilva-sidebar' : 'vilva-slider-one';
            if( has_post_thumbnail() ){
                echo '<figure class="post-thumbnail">';
                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );
                echo '</figure>';    
            }
        
    }elseif( is_single() ){
        if ( has_post_thumbnail() && $ed_featured_image ) {
            echo '<figure class="post-thumbnail">';
                $image_size = ( $sidebar ) ? 'vilva-sidebar' : 'vilva-slider-one';
                if( ! $ed_crop_single ){
                    the_post_thumbnail( $image_size, array( 'itemprop' => 'image' ) );    
                }elseif( $ed_crop_single ){
                    the_post_thumbnail();    
                }
            echo '</figure>';
        }
    }
}
endif;
add_action( 'vilva_before_page_entry_content', 'vilva_post_thumbnail', 20 );
add_action( 'vilva_before_post_entry_content', 'vilva_post_thumbnail', 20 );

if( ! function_exists( 'vilva_entry_content' ) ) :
/**
 * Entry Content
*/
function vilva_entry_content(){ 
    $ed_excerpt = get_theme_mod( 'ed_excerpt', true );  

    if ( is_home() || is_archive() || is_search() ) echo '<div class="content-wrap">';

        if ( is_single() ) vilva_author_desc(); 

        echo '<div class="entry-content" itemprop="text">';
            if( is_singular() || ! $ed_excerpt || ( get_post_format() != false ) ){
                the_content();    
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'vilva' ),
                    'after'  => '</div>',
                ) );
            }else{
                the_excerpt();
            }
        echo '</div>';            

    if ( is_home() ||  is_archive() || is_search() ) echo '</div>';
        
}
endif;
add_action( 'vilva_page_entry_content', 'vilva_entry_content', 30 );
add_action( 'vilva_post_entry_content', 'vilva_entry_content', 30 );

if( ! function_exists( 'vilva_entry_footer' ) ) :
/**
 * Entry Footer
*/
function vilva_entry_footer(){

    $ed_excerpt = get_theme_mod( 'ed_excerpt', true ); 
    $readmore   = get_theme_mod( 'read_more_text', __( 'Read More', 'vilva' ) );

    if ( is_home() || is_archive() || is_search() || is_single() ) { 
 
        echo '<div class="entry-footer">';

            if ( is_single() ) vilva_tag();

            if( $ed_excerpt && $readmore && !empty( get_the_content() ) && ! is_single() ){

                echo '<div class="button-wrap"><a href="' . esc_url( get_the_permalink() ) . '" class="btn-readmore">' . esc_html( $readmore ) . '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path d="M0,12,12,0,5.564,12,12,24Z" transform="translate(12 24) rotate(180)" fill="#121212"/></svg></a></div>';    
            }

            if( get_edit_post_link() ){
                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'vilva' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
            }            

        echo '</div>';
    }
}
endif;
add_action( 'vilva_post_entry_content', 'vilva_entry_footer', 35 );

if( ! function_exists( 'vilva_navigation' ) ) :
/**
 * Navigation
*/
function vilva_navigation(){

    if( is_single() ){

        $prev_post = get_previous_post();
        $next_post = get_next_post();

        if( $prev_post || $next_post ){ ?>            
            <nav class="post-navigation pagination" role="navigation">
    			<div class="nav-links">
    				<?php
                       if (!empty( $prev_post )){ ?>
                            <div class="nav-previous">
                                <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" rel="prev">
                                    <span class="meta-nav"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 8"><defs><style>.arla{fill:#999596;}</style></defs><path class="arla" d="M16.01,11H8v2h8.01v3L22,12,16.01,8Z" transform="translate(22 16) rotate(180)"/></svg> <?php esc_html_e( 'Previous Article', 'vilva' ); ?></span>
                                    <span class="post-title"><?php echo esc_html( $prev_post->post_title ); ?></span>
                                </a>
                                <figure class="post-img">
                                    <?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail' ); ?>
                                </figure>
                            </div>
                        <?php }

                        if (!empty( $next_post )){ ?>
                            <div class="nav-next">
                                <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" rel="next">
                                    <span class="meta-nav"><?php esc_html_e( 'Next Article', 'vilva' ); ?><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 8"><defs><style>.arra{fill:#999596;}</style></defs><path class="arra" d="M16.01,11H8v2h8.01v3L22,12,16.01,8Z" transform="translate(-8 -8)"/></svg></span>
                                    <span class="post-title"><?php echo esc_html( $next_post->post_title ); ?></span>
                                </a>
                                <figure class="post-img">
                                    <?php echo get_the_post_thumbnail( $next_post->ID, 'thumbnail' ); ?>
                                </figure>
                            </div>
                        <?php } 
                    ?>
    			</div>
    		</nav> <?php
        }
    }else{                    
        the_posts_pagination( array(
            'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path d="M0,12,12,0,5.564,12,12,24Z" transform="translate(0 0)" fill="#121212"/></svg>' . __( 'Previous', 'vilva' ),
            'next_text'          => __( 'Next', 'vilva' ) . '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="24" viewBox="0 0 12 24"><path d="M0,12,12,0,5.564,12,12,24Z" transform="translate(12 24) rotate(180)" fill="#121212"/></svg>',
            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'vilva' ) . ' </span>',
        ) );     
    }
}
endif;
add_action( 'vilva_after_post_content', 'vilva_navigation', 20 );
add_action( 'vilva_after_posts_content', 'vilva_navigation' );

if( ! function_exists( 'vilva_author' ) ) :
/**
 * Author Section
*/
function vilva_author(){

    $ed_author = get_theme_mod( 'ed_post_author', false );

    if( ( ( is_single() && ! $ed_author ) || is_archive() ) && get_the_author_meta( 'description' ) ){ 
        ?>
        <div class="author-section">
            <figure class="author-img">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 95 ); ?>
            </figure>
            <div class="author-content-wrap">
                <h3 class="author-name">
                    <?php 
                    if ( is_author() ) echo '<span class="sub-title">' . esc_html__( 'ALL POSTS BY: ', 'vilva' ) . '</span>';
                    the_author_meta( 'display_name'); ?>                        
                </h3>
                <div class="author-content">
                    <?php echo wpautop( wp_kses_post( get_the_author_meta( 'description' ) ) ); ?>
                </div>
            </div>
        </div> <!-- .author-section -->
        <?php
    }
}
endif;
add_action( 'vilva_after_post_content', 'vilva_author', 15 );

if( ! function_exists( 'vilva_newsletter' ) ) :
/**
 * Newsletter
*/
function vilva_newsletter(){ 
    $ed_newsletter = get_theme_mod( 'ed_newsletter', false );
    $newsletter    = get_theme_mod( 'newsletter_shortcode' );
    if( vilva_is_btnw_activated() && $ed_newsletter && $newsletter ){ ?>
        <div class="newsletter-block">
            <?php echo do_shortcode( $newsletter ); ?>
        </div>
        <?php
    }
}
endif;
add_action( 'vilva_after_post_content', 'vilva_newsletter', 30 );

if( ! function_exists( 'vilva_related_posts' ) ) :
/**
 * Related Posts 
*/
function vilva_related_posts(){ 
    $ed_related_post = get_theme_mod( 'ed_related', true );
    
    if( $ed_related_post ){
        vilva_get_posts_list( 'related' );    
    }
}
endif;                                                                               
add_action( 'vilva_after_post_content', 'vilva_related_posts', 35 );


if( ! function_exists( 'vilva_latest_posts' ) ) :
/**
 * Latest Posts
*/
function vilva_latest_posts(){ 
    vilva_get_posts_list( 'latest' );
}
endif;
add_action( 'vilva_latest_posts', 'vilva_latest_posts' );

if( ! function_exists( 'vilva_comment' ) ) :
/**
 * Comments Template 
*/
function vilva_comment(){
    // If comments are open or we have at least one comment, load up the comment template.
	if( get_theme_mod( 'ed_comments', true ) && ( comments_open() || get_comments_number() ) ) :
		comments_template();
	endif;
}
endif;
add_action( 'vilva_after_post_content', 'vilva_comment', 45 );

add_action( 'vilva_after_page_content', 'vilva_comment' );

if( ! function_exists( 'vilva_content_end' ) ) :
/**
 * Content End
*/
function vilva_content_end(){ ?>            
        </div><!-- .container/ -->        
    </div><!-- .error-holder/site-content -->
    <?php
}
endif;
add_action( 'vilva_before_footer', 'vilva_content_end', 20 );

if( ! function_exists( 'vilva_instagram_section' ) ) :
/**
 * Bottom Shop Section
*/
function vilva_instagram_section(){ 
    if( vilva_is_btif_activated() ){
        $ed_instagram = get_theme_mod( 'ed_instagram', false );
        $image        = get_theme_mod( 'instagram_bg_image' );
        if( $ed_instagram ){ ?>
            <div class="instagram-section" <?php if( $image ){ ?> style="background-image: url( '<?php echo esc_url( $image ); ?>' );" <?php } ?> >
               <?php echo do_shortcode( '[blossomthemes_instagram_feed]' );  ?> 
            </div><?php 
        }
    }
}
endif;
add_action( 'vilva_footer', 'vilva_instagram_section', 15 );

if( ! function_exists( 'vilva_footer_start' ) ) :
/**
 * Footer Start
*/
function vilva_footer_start(){
    ?>
    <footer id="colophon" class="site-footer" itemscope itemtype="http://schema.org/WPFooter">
    <?php
}
endif;
add_action( 'vilva_footer', 'vilva_footer_start', 25 );

if( ! function_exists( 'vilva_footer_top' ) ) :
/**
 * Footer Top
*/
function vilva_footer_top(){    
    $footer_sidebars = array( 'footer-one', 'footer-two', 'footer-three', 'footer-four' );
    $active_sidebars = array();
    $sidebar_count   = 0;
    
    foreach ( $footer_sidebars as $sidebar ) {
        if( is_active_sidebar( $sidebar ) ){
            array_push( $active_sidebars, $sidebar );
            $sidebar_count++ ;
        }
    }
                 
    if( $active_sidebars ){ ?>
        <div class="footer-t">
    		<div class="container">
    			<div class="grid column-<?php echo esc_attr( $sidebar_count ); ?>">
                <?php foreach( $active_sidebars as $active ){ ?>
    				<div class="col">
    				   <?php dynamic_sidebar( $active ); ?>	
    				</div>
                <?php } ?>
                </div>
    		</div>
    	</div>
        <?php 
    }   
}
endif;
add_action( 'vilva_footer', 'vilva_footer_top', 30 );

if( ! function_exists( 'vilva_footer_bottom' ) ) :
/**
 * Footer Bottom
*/
function vilva_footer_bottom(){ ?>
    <div class="footer-b">
        <div class="container">
            <div class="copyright">
                <?php
                    vilva_get_footer_copyright();
                    echo esc_html__( ' Vilva | Developed By ', 'vilva' ); 
                    echo '<a href="' . esc_url( 'https://blossomthemes.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'Blossom Themes', 'vilva' ) . '</a>.';                
                    printf( esc_html__( ' Powered by %s. ', 'vilva' ), '<a href="'. esc_url( __( 'https://wordpress.org/', 'vilva' ) ) .'" target="_blank">WordPress</a>' );
                    if( function_exists( 'the_privacy_policy_link' ) ){
                        the_privacy_policy_link();
                    }
                ?> 
            </div>
            <div class="footer-social">
                <?php vilva_social_links(); ?>
            </div>
            
        </div>
    </div> <!-- .footer-b -->
    <?php
}
endif;
add_action( 'vilva_footer', 'vilva_footer_bottom', 40 );

if( ! function_exists( 'vilva_footer_end' ) ) :
/**
 * Footer End 
*/
function vilva_footer_end(){ ?>
    </footer><!-- #colophon -->
    <?php
}
endif;
add_action( 'vilva_footer', 'vilva_footer_end', 50 );

if( ! function_exists( 'vilva_back_to_top' ) ) :
/**
 * Back to top
*/
function vilva_back_to_top(){ ?>
    <button class="back-to-top">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g transform="translate(-1789 -1176)"><rect width="24" height="2.667" transform="translate(1789 1176)" fill="#fff"/><path d="M-215.453,382.373-221.427,372l-5.973,10.373h4.64v8.293h2.667v-8.293Z" transform="translate(2022.427 809.333)" fill="#fff"/></g></svg>
    </button>
    <?php
}
endif;
add_action( 'vilva_after_footer', 'vilva_back_to_top', 15 );

if( ! function_exists( 'vilva_page_end' ) ) :
/**
 * Page End
*/
function vilva_page_end(){ ?>
    </div><!-- #page -->
    <?php
}
endif;
add_action( 'vilva_after_footer', 'vilva_page_end', 20 );