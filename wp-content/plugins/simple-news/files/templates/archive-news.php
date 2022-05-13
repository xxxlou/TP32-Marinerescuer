<?php
  get_header();

  echo '<div class="simple-news-grid-con">';

    if ( is_active_sidebar( 'sidebar-newstop' ) ) :
      echo '<div class="simple-news-widget-con sidebar-newstop-con">';
        dynamic_sidebar( 'sidebar-newstop' );
      echo '</div>';
    endif;

    echo '<div class="simple-news-con news-column simple-news-archive">';
    if ( have_posts() ) : while ( have_posts() ) : the_post();
      simple_news_loop();
    endwhile;
      echo '</div>';

      echo '<div class="simple-news-pager">';
        the_posts_pagination(
          array(
            'mid_size'  => 2,
            'prev_text' => 'Previous',
            'next_text' => 'Next',
            )
          );
      echo '</div>';

    else :
      echo '<p>';
       _e( 'Sorry, no posts matched your criteria.' );
      echo '</p>';
    endif;

    if ( is_active_sidebar( 'sidebar-newsbottom' ) ) {
      echo '<div class="simple-news-widget-con sidebar-newsbottom-con">';
        dynamic_sidebar( 'sidebar-newsbottom' );
      echo '</div>';
    }

  echo '</div>';

  get_footer();