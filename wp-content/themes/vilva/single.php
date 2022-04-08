<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Vilva
 */

get_header(); ?>
    <div id="primary" class="content-area">	
    	<main id="main" class="site-main">

    	<?php
    	while ( have_posts() ) : the_post();

    		get_template_part( 'template-parts/content', 'single' );

    	endwhile; // End of the loop.
    	?>

    	</main><!-- #main -->
        
        <?php
        /**
         * @hooked vilva_author               - 15
         * @hooked vilva_navigation           - 20 
         * @hooked vilva_newsletter           - 30
         * @hooked vilva_related_posts        - 35
         * @hooked vilva_comment              - 45
        */
        do_action( 'vilva_after_post_content' );
        ?>
    </div><!-- #primary -->

<?php
get_sidebar();
get_footer();
