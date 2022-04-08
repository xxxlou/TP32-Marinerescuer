<?php
/**
 * Delicious Recipes Functions.
 *
 * @package vilva
 */

if( ! function_exists( 'vilva_recipe_category' ) ) :
/**
 * Difficulty Level.
 */
function vilva_recipe_category(){
    global $recipe;
    if ( ! empty( $recipe->ID ) ) : ?>
        <span class="post-cat">
            <?php the_terms( $recipe->ID, 'recipe-course', '', '', '' ); ?>
        </span>
    <?php endif;
}
endif;