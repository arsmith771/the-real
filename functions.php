<?php
/**
 * Real.
 *
 * This file adds functions to the Real Theme.
 *
 * @package Real
 * @author  ChurchPress
 * @license GPL-3.0+
 * @link    https://ChurchPress.co
 */

// Starts the engine.
require_once get_template_directory() . '/lib/init.php';

add_action( 'after_setup_theme', 'genesis_sample_localization_setup' );
/**
 * Sets localization (do not remove).
 *
 * @since 1.0.0
 */
function genesis_sample_localization_setup() {

	load_child_theme_textdomain( 'real', get_stylesheet_directory() . '/languages' );

}

// Defines the child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Real' );
define( 'CHILD_THEME_URL', '' );
define( 'CHILD_THEME_VERSION', '1.5.2' );

add_action( 'init', 'cp_includes' );
/**
 * Load additional functions and helpers (/includes/*)
 */
function cp_includes() {
	$includes_dir = get_stylesheet_directory() . '/lib';

	//* Load required theme files in the includes directory.
	foreach ( glob( $includes_dir . '/*.php' ) as $file ) {
		require_once $file;
	}

	//* Load required files in subdirectories of the includes directory.
	foreach ( glob( $includes_dir . '/*/*.php' ) as $file ) {
		require_once $file;
	}

}

/**
 * Defines responsive menu settings.
 *
 * @since 2.3.0
 */
function genesis_sample_responsive_menu_settings() {

	$settings = array(
		'mainMenu'         => __( 'Menu', 'cp-genesis-starter' ),
		'menuIconClass'    => 'dashicons-before dashicons-menu',
		'subMenu'          => __( 'Submenu', 'cp-genesis-starter' ),
		'subMenuIconClass' => 'dashicons-before dashicons-arrow-down-alt2',
		'menuClasses'      => array(
			'combine' => array(
				'.nav-primary',
			),
			'others'  => array(),
		),
	);

	return $settings;

}

// Sets the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) ) {
	$content_width = 702; // Pixels.
}

// Adds support for HTML5 markup structure.
add_theme_support(
	'html5', array(
		'caption',
		'comment-form',
		'comment-list',
		'gallery',
		'search-form',
	)
);

// Adds support for accessibility.
add_theme_support(
	'genesis-accessibility', array(
		'404-page',
		'drop-down-menu',
		'headings',
		'rems',
		'search-form',
		'skip-links',
	)
);

// Adds viewport meta tag for mobile browsers.
add_theme_support(
	'genesis-responsive-viewport'
);

// Adds custom logo in Customizer > Site Identity.
add_theme_support(
	'custom-logo', array(
		'height'      => 120,
		'width'       => 700,
		'flex-height' => true,
		'flex-width'  => true,
	)
);

// Renames primary and secondary navigation menus.
add_theme_support(
	'genesis-menus', array(
		'primary'   => __( 'Header Menu', 'cp-genesis-starter' ),
		'secondary' => __( 'Footer Menu', 'cp-genesis-starter' ),
	)
);

// Adds support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Adds support for 3-column footer widgets.
add_theme_support( 'genesis-footer-widgets', 3 );

// Removes header right widget area.
unregister_sidebar( 'header-right' );

// Removes secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Removes site layouts.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Removes output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

add_action( 'genesis_theme_settings_metaboxes', 'genesis_sample_remove_metaboxes' );
/**
 * Removes output of unused admin settings metaboxes.
 *
 * @since 2.6.0
 *
 * @param string $_genesis_admin_settings The admin screen to remove meta boxes from.
 */
function genesis_sample_remove_metaboxes( $_genesis_admin_settings ) {

	remove_meta_box( 'genesis-theme-settings-header', $_genesis_admin_settings, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_admin_settings, 'main' );

}

add_filter( 'genesis_customizer_theme_settings_config', 'genesis_sample_remove_customizer_settings' );
/**
 * Removes output of header settings in the Customizer.
 *
 * @since 2.6.0
 *
 * @param array $config Original Customizer items.
 * @return array Filtered Customizer items.
 */
function genesis_sample_remove_customizer_settings( $config ) {

	unset( $config['genesis']['sections']['genesis_header'] );
	return $config;

}

// Displays custom logo.
add_action( 'genesis_site_title', 'the_custom_logo', 0 );

// Repositions primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Repositions the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 10 );

add_filter( 'wp_nav_menu_args', 'genesis_sample_secondary_menu_args' );
/**
 * Reduces secondary navigation menu to one level depth.
 *
 * @since 2.2.3
 *
 * @param array $args Original menu options.
 * @return array Menu options with depth set to 1.
 */
function genesis_sample_secondary_menu_args( $args ) {

	if ( 'secondary' !== $args['theme_location'] ) {
		return $args;
	}

	$args['depth'] = 1;
	return $args;

}

add_filter( 'genesis_author_box_gravatar_size', 'genesis_sample_author_box_gravatar' );
/**
 * Modifies size of the Gravatar in the author box.
 *
 * @since 2.2.3
 *
 * @param int $size Original icon size.
 * @return int Modified icon size.
 */
function genesis_sample_author_box_gravatar( $size ) {

	return 90;

}

add_filter( 'genesis_comment_list_args', 'genesis_sample_comments_gravatar' );
/**
 * Modifies size of the Gravatar in the entry comments.
 *
 * @since 2.2.3
 *
 * @param array $args Gravatar settings.
 * @return array Gravatar settings with modified size.
 */
function genesis_sample_comments_gravatar( $args ) {

	$args['avatar_size'] = 60;
	return $args;

}

// Instagram icon - header
/*
add_action('genesis_header', 'instagram_icon_header', 13 );
function instagram_icon_header(){

			echo '<a target="_blank" href="https://www.instagram.com/johnayscough_artist/" class="instagram-icon" title="Visit us on Instagram"></a>';
	
}
*/


// Strapline output
add_action('genesis_header', 'strapline_output', 15 );
function strapline_output(){

			echo '<p class="mission-statement">' . types_render_field( 'fld-strapline', array('id' => '2') ) . '</p>';
	
}


// Homepage output - content
add_action('genesis_entry_content', 'instagram_feed', 8 );
function instagram_feed(){
	if ( is_page_template('homepage.php') ) {

			echo '<div class="instagram-feeder"><p class="instagram-feeder__hashtag">#ThisIsREAL</p>' . do_shortcode('[instagram-feed num=8]') . '</div>';
	
	}
}

// Homepage output - content
add_action('genesis_entry_content', 'mailchimp_output', 9 );
function mailchimp_output(){
	if ( is_page_template('homepage.php') ) {

			echo '<div class="mailchimp-signup"><div class="wrap">[MailChimp sign-up code here.]</div></div>';

			echo '<div class="wrap">';
	
	}
}

// Homepage output - content
add_action('genesis_entry_content', 'xxxx', 11 );
function xxxx(){
	if ( is_page_template('homepage.php') ) {

			echo '</div>';
	
	}
}

//
add_action('genesis_entry_content', 'latest_manifesto', 10 );
function latest_manifesto(){
	if ( is_page('49') ) {

			echo do_shortcode('[wpv-view name="latest-manifesto"]');
	
	}
}

//
add_action('genesis_entry_content', 'manifesto_archive', 11 );
function manifesto_archive(){
	if ( is_page('49') ) {

			echo do_shortcode('[wpv-view name="manifest-pdf-archive"]');
	
	}
}

//
add_action('genesis_before_content', 'articles_header', 10 );
function articles_header(){
	if ( is_singular('post') ) {

			echo '<p class="articles-sub">Articles</p>';
	
	}
}

add_action('genesis_entry_content', 'main_header_image', 10 );
function main_header_image(){

			echo '<style> .entry-header {background-image: url("' . types_render_field( 'header-image', array('url' => 'true') ) . '")} </style>';
	
}


add_action('genesis_before_content_sidebar_wrap', 'main_header_image_blog_landing', 10 );
function main_header_image_blog_landing(){
	if ( is_front_page() && is_home() ) {
  		// Default homepage
	} elseif ( is_front_page() ) {
  		// static homepage
	} elseif ( is_home() ) {

  		echo '<style> .entry-header {background-image: url("' . home_url() . '/wp-content/uploads/2019/06/header-image-3.jpg")} </style><div class="wrap"><header class="entry-header"><h1 class="entry-title" itemprop="headline">Articles</h1></header></div>';
  		
	} 
}


add_action('genesis_before_content_sidebar_wrap', 'yyy', 11 );
function yyy(){
	if ( is_front_page() && is_home() ) {
  		// Default homepage
	} elseif ( is_front_page() ) {
  		// static homepage
	} elseif ( is_home() ) {

  		echo '<div class="articles-intro"><div class="wrap">' . types_render_field( 'article-intro-text', array('id' => '17') ) . '</div></div>';
  	}
}

add_action('genesis_entry_content', 'contact_page', 11 );
function contact_page(){
	if ( is_page_template('contact-page.php') ) {

  		echo do_shortcode('[contact-form-7 id="82" title="Contact form 1"]');
  	}
}

//
add_action('genesis_before_footer', 'before_footer_ctas', 10 );
function before_footer_ctas(){

	echo '<div class="wrap wrap--before-footer"><aside class="before-footer-nav"><a class="before-footer-nav__cta" href="' . home_url() . '/what-is-the-real/"><h3 class="before-footer-nav__header">What is The REAL?</h3><img src="' . home_url() . '/wp-content/themes/real/assets/images/cta-1.png" alt="xxx"></a><a class="before-footer-nav__cta" href="' . home_url() . '/working-manifesto/"><h3 class="before-footer-nav__header">Working Manifesto</h3><img src="' . home_url() . '/wp-content/themes/real/assets/images/cta-1.png" alt="xxx"></a><a class="before-footer-nav__cta" href="' . home_url() . '/articles/"><h3 class="before-footer-nav__header">Articles</h3><img src="' . home_url() . '/wp-content/themes/real/assets/images/cta-1.png" alt="xxx"></a><a class="before-footer-nav__cta" href="' . home_url() . '/contact/"><h3 class="before-footer-nav__header">Contact</h3><img src="' . home_url() . '/wp-content/themes/real/assets/images/cta-1.png" alt="xxx"></a></aside></div>';
}


/** Force full width layout on single posts only*/
add_filter( 'genesis_pre_get_option_site_layout', 'full_width_layout_single_posts' );
/** 
* @author Brad Dalton 
* @link http://wpsites.net/web-design/change-layout-genesis/ 
*/
function full_width_layout_single_posts( $opt ) {
if ( is_singular( 'post' ) ) {
    $opt = 'content-sidebar'; 
    return $opt;

    } 


}

// Change the footer copyright etc text

add_filter( 'genesis_footer_creds_text', 'wpb_footer_creds_text' );
function wpb_footer_creds_text () {
	$copyright = '';
    return $copyright;
}


add_filter('genesis_footer', 'designed_by', 10);
function designed_by() {
	echo '<p>Copyright ' . do_shortcode('[footer_copyright]') . ' John Ayscough<br> Contact: <a href="mailto:johnayscough@hotmail.com">johnayscough@hotmail.com</a></p>';
}