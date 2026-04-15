<?php
/**
 * Center for Houston's Future - Theme Functions
 *
 * Elementor Pro-optimized Hello Elementor child theme.
 *
 * @package    CHF
 * @version    5.0.0
 * @since      5.0.0
 * @author     reneka DIGITAL
 * @link       https://www.renekadigital.com
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * --------------------------------------------------------------------------
 * Constants
 * --------------------------------------------------------------------------
 */
define( 'CHF_VERSION', '5.0.0' );
define( 'CHF_DIR', get_stylesheet_directory() );
define( 'CHF_URI', get_stylesheet_directory_uri() );

/**
 * --------------------------------------------------------------------------
 * Theme Setup
 * --------------------------------------------------------------------------
 *
 * Register theme features, nav menus, and image sizes.
 *
 * @since 5.0.0
 * @return void
 */
function chf_setup() {

	// Core theme supports.
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array(
		'height'      => 56,
		'width'       => 98,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'search-form',
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );

	// Navigation menus.
	register_nav_menus( array(
		'primary'            => __( 'Primary Navigation', 'chf' ),
		'footer-about'       => __( 'Footer - About', 'chf' ),
		'footer-initiatives' => __( 'Footer - Initiatives', 'chf' ),
		'footer-leadership'  => __( 'Footer - Leadership', 'chf' ),
		'footer-connect'     => __( 'Footer - Connect', 'chf' ),
	) );

	// Custom image sizes.
	add_image_size( 'news-card', 600, 338, true );
	add_image_size( 'hero-bg', 1920, 1080, true );
	add_image_size( 'supporter-logo', 240, 80, false );

	// Add excerpt support for pages.
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'after_setup_theme', 'chf_setup' );

/**
 * --------------------------------------------------------------------------
 * Enqueue Styles & Scripts
 * --------------------------------------------------------------------------
 *
 * Load Google Fonts, parent theme, child theme, design system,
 * Elementor overrides, and frontend JS.
 *
 * @since 5.0.0
 * @return void
 */
function chf_enqueue_assets() {

	// Google Fonts: Inter + Merriweather.
	wp_enqueue_style(
		'chf-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap',
		array(),
		null
	);

	// Parent theme (Hello Elementor) stylesheet.
	wp_enqueue_style(
		'hello-elementor',
		get_template_directory_uri() . '/style.css',
		array(),
		CHF_VERSION
	);

	// Child theme stylesheet.
	wp_enqueue_style(
		'chf-style',
		get_stylesheet_uri(),
		array( 'hello-elementor' ),
		CHF_VERSION
	);

	// Design system stylesheet.
	wp_enqueue_style(
		'chf-design-system',
		CHF_URI . '/assets/css/design-system.css',
		array( 'chf-style' ),
		CHF_VERSION
	);

	// Elementor overrides stylesheet.
	wp_enqueue_style(
		'chf-elementor-overrides',
		CHF_URI . '/assets/css/elementor-overrides.css',
		array( 'chf-design-system' ),
		CHF_VERSION
	);

	// Frontend JavaScript (deferred, in footer).
	wp_enqueue_script(
		'chf-frontend',
		CHF_URI . '/assets/js/frontend.js',
		array(),
		CHF_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

}
add_action( 'wp_enqueue_scripts', 'chf_enqueue_assets' );

/**
 * --------------------------------------------------------------------------
 * Preconnect Hints for Google Fonts
 * --------------------------------------------------------------------------
 *
 * Add resource hints to speed up Google Fonts loading.
 *
 * @since 5.0.0
 *
 * @param array  $urls          Array of URL strings or descriptor arrays.
 * @param string $relation_type The relation type (e.g., 'preconnect').
 * @return array Modified array of URLs.
 */
function chf_resource_hints( $urls, $relation_type ) {

	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href'        => 'https://fonts.googleapis.com',
			'crossorigin' => '',
		);
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'chf_resource_hints', 10, 2 );

/**
 * --------------------------------------------------------------------------
 * Remove Emoji Scripts
 * --------------------------------------------------------------------------
 *
 * Strip WordPress emoji detection scripts and styles for performance.
 *
 * @since 5.0.0
 * @return void
 */
function chf_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'chf_disable_emojis' );

/**
 * --------------------------------------------------------------------------
 * Meta Description
 * --------------------------------------------------------------------------
 *
 * Output a meta description tag. Prefers Yoast SEO field, falls back
 * to the post excerpt, then a site-level fallback.
 *
 * @since 5.0.0
 * @return void
 */
function chf_meta_description() {

	// Bail if Yoast is handling meta descriptions globally.
	if ( defined( 'WPSEO_VERSION' ) && ! is_front_page() ) {
		return;
	}

	$description = '';

	if ( is_singular() ) {
		// Try Yoast's metadesc field first.
		$yoast_desc = get_post_meta( get_the_ID(), '_yoast_wpseo_metadesc', true );
		if ( ! empty( $yoast_desc ) ) {
			$description = $yoast_desc;
		} elseif ( has_excerpt() ) {
			$description = get_the_excerpt();
		}
	} elseif ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
	}

	if ( ! empty( $description ) ) {
		$description = wp_strip_all_tags( $description );
		$description = esc_attr( mb_substr( $description, 0, 160 ) );
		echo '<meta name="description" content="' . $description . '">' . "\n";
	}
}
add_action( 'wp_head', 'chf_meta_description', 1 );

/**
 * --------------------------------------------------------------------------
 * Elementor Compatibility Notice
 * --------------------------------------------------------------------------
 *
 * Show an admin notice when Elementor is not active. This theme is designed
 * to work with Elementor Pro as the primary page builder.
 *
 * @since 5.0.0
 * @return void
 */
function chf_elementor_required_notice() {

	if ( did_action( 'elementor/loaded' ) ) {
		return;
	}

	$message = sprintf(
		/* translators: 1: Theme name, 2: Elementor plugin name */
		esc_html__( '%1$s requires %2$s to be installed and activated for full functionality. Please install Elementor Pro.', 'chf' ),
		'<strong>Center for Houston\'s Future</strong>',
		'<strong>Elementor</strong>'
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', $message );
}
add_action( 'admin_notices', 'chf_elementor_required_notice' );

/**
 * --------------------------------------------------------------------------
 * Include Modular Files
 * --------------------------------------------------------------------------
 *
 * Load additional theme functionality from the inc/ directory.
 *
 * @since 5.0.0
 */
require_once CHF_DIR . '/inc/custom-post-types.php';
require_once CHF_DIR . '/inc/security.php';
require_once CHF_DIR . '/inc/elementor-setup.php';
