<?php
/**
 * Right Buttons Panel.
 *
 * @package Vilva
 */
?>
<div class="panel-right">
	<div class="panel-aside">
		<h4><?php esc_html_e( 'Upgrade To Pro', 'vilva' ); ?></h4>
		<p><?php esc_html_e( 'With the Pro version, you can change the look and feel of your website in seconds. In just a few clicks, you can change the color and typography of your website. The premium version lets you have better control over the theme as it comes with more customization options. Not just that, the theme also has more sections and layout options as compared to the free version. The Pro version is multi-language compatible as well.', 'vilva' ); ?></p>
		<p><?php esc_html_e( 'You will also get more frequent updates and quicker support with the Pro version.', 'vilva' ); ?></p>
		<a class="button button-primary" href="<?php echo esc_url( 'https://blossomthemes.com/wordpress-themes/vilva-pro/' ); ?>" title="<?php esc_attr_e( 'View Premium Version', 'vilva' ); ?>" target="_blank">
            <?php esc_html_e( 'Read More About the Pro Theme', 'vilva' ); ?>
        </a>
	</div><!-- .panel-aside Theme Support -->
	<!-- Knowledge base -->
	<div class="panel-aside">
		<h4><?php esc_html_e( 'Visit the Knowledge Base', 'vilva' ); ?></h4>
		<p><?php esc_html_e( 'Need help with using the WordPress as quickly as possible? Visit our well-organized Knowledge Base!', 'vilva' ); ?></p>
		<p><?php esc_html_e( 'Our Knowledge Base has step-by-step video and text tutorials, from installing the WordPress to working with themes and more.', 'vilva' ); ?></p>

		<a class="button button-primary" href="<?php echo esc_url( 'https://docs.blossomthemes.com/' . VILVA_THEME_TEXTDOMAIN . '/' ); ?>" title="<?php esc_attr_e( 'Visit the knowledge base', 'vilva' ); ?>" target="_blank"><?php esc_html_e( 'Visit the Knowledge Base', 'vilva' ); ?></a>
	</div><!-- .panel-aside knowledge base -->

	<div class="panel-aside">
		<h4><?php _e( 'Submit your site for social share', 'vilva' ); ?></h4>
		<p><?php _e( 'We regularly share and feature websites made using our themes on our social media accounts( Facebook, Instagram, Twitter and Pinterest ).', 'vilva' ); ?></p>
		<p><?php _e( 'If you would like to get your website shared and featured, please submit your website by clicking the link below.', 'vilva' ); ?></p>

		<a class="button button-primary" href="<?php echo esc_url( 'https://blossomthemes.com/submit-your-site-for-social-share/' ); ?>" title="<?php esc_attr_e( 'Submit your site for social share', 'vilva' ); ?>" target="_blank"><?php _e( 'Submit Here', 'vilva' ); ?></a>
	</div>

</div><!-- .panel-right -->