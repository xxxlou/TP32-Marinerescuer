<?php
function simple_news_loop() {
	$img_options = get_option( 'simple_news_settings' );

	echo '<div id="post-id-' .get_the_ID() . '" class="simple-news-item">';

		if ( has_post_thumbnail() ) :
			echo '<div class="simple-news-img-con">';
				echo '<a class="simple-news-item-link" href="' . get_the_permalink() . '">';

					if ( 0 == $img_options['simple_news_select_field_0'] ) {
					 the_post_thumbnail('news_plugin_small', array('class' => 'simple-news-img-default'));
					}

					if ( 1 == $img_options['simple_news_select_field_0'] ) {
					 the_post_thumbnail('news_plugin_small', array('class' => 'simple-news-img-default'));
					}

					if ( 2 == $img_options['simple_news_select_field_0'] ) {
					 the_post_thumbnail('news_plugin_medium', array('class' => 'simple-news-img-normal'));
					}

				 echo '</a>';
			echo '</div>';
		endif;

		echo '<div class="simple-news-text-con">';
			echo '<h4 class="simple-news-title"><a class="simple-news-item-link" href="' . get_the_permalink() . '">' . get_the_title() . '</a></h4>';
			echo '<div class="simple-news-date">' . get_the_date() . '</div>';
			echo '<div class="simple-news-excerpt">' . get_the_excerpt() . '</div>';
		echo '</div>';

	echo '</div>';
}


function simple_news_loop2() {
	return get_the_title();
}