<?php
function Hjemmesider_news_widget_text_init() {

	// Sidebar
	register_sidebar(
		array(
			'name'          => __( 'News top', 'simple-news' ),
			'id'            => 'sidebar-newstop',
			'description'   => __( 'Add widgets on top of the /news page.', 'simple-news' ),
			'before_widget' => '<div id="%1$s" class="widget simple-news-widget simple-news-widget-top">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
		)
	);
	register_sidebar(
		array(
			'name'          => __( 'News bottom', 'simple-news' ),
			'id'            => 'sidebar-newsbottom',
			'description'   => __( 'Add widgets at the bottom of the /news page.', 'simple-news' ),
			'before_widget' => '<div id="%1$s" class="widget simple-news-widget simple-news-widget-bottom">',
			'after_widget'  => '</div>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
		)
	);


}

add_action( 'widgets_init', 'Hjemmesider_news_widget_text_init' );

