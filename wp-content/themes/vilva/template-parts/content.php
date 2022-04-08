<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Vilva
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); echo ' itemscope itemtype="https://schema.org/Blog"'; ?>>
    
	<?php 
    /**
     * @hooked vilva_entry_header_first - 10
     * @hooked vilva_post_thumbnail - 20
    */
    do_action( 'vilva_before_post_entry_content' );

    /**
     * @hooked vilva_entry_header   - 10
     * @hooked vilva_entry_content  - 15
     * @hooked vilva_entry_footer   - 20
    */
    do_action( 'vilva_post_entry_content' );

    ?>
</article><!-- #post-<?php the_ID(); ?> -->
