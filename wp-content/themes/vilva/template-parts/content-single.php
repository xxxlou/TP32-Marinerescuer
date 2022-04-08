<?php
/**
 * Template part for displaying single post
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
     * @hooked vilva_post_thumbnail - 20
    */
    do_action( 'vilva_before_post_entry_content' );

    echo '<div class="content-wrap">';
    /**
     * @hooked vilva_entry_content  - 30
     * @hooked vilva_entry_footer   - 35
    */
    do_action( 'vilva_post_entry_content' );
    echo '</div>';
    ?>
</article><!-- #post-<?php the_ID(); ?> -->