<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vilva
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php

		/**
         * @hooked vilva_entry_header_first - 10
	     * @hooked vilva_post_thumbnail     - 20
	    */
	    do_action( 'vilva_before_page_entry_content' );

        /**
         * Entry Content
         * 
         * @hooked vilva_entry_content - 15
        */
        do_action( 'vilva_page_entry_content' );    
    ?>
</article><!-- #post-<?php the_ID(); ?> -->
