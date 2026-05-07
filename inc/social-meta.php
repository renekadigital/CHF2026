<?php
/**
 * Open Graph + Twitter Card meta tags
 *
 * Reneka SEO/GEO standard, Phase 2 — On-Page SEO. Emits og:* and twitter:*
 * meta tags so links shared on LinkedIn, Twitter/X, Facebook, and iMessage
 * render with a title, description, and preview image.
 *
 * Defers to active SEO plugins (Yoast, RankMath, SEOPress, AIOSEO) — those
 * plugins emit their own social tags and a duplicate set causes scrapers
 * to pick a random pair. Run only when no plugin is taking responsibility.
 *
 * @package CHF
 * @since   5.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect whether an SEO plugin is handling social meta tags.
 *
 * @since 5.1.0
 * @return bool
 */
function chf_has_seo_plugin() {
	return defined( 'WPSEO_VERSION' )         // Yoast
		|| defined( 'RANK_MATH_VERSION' )      // Rank Math
		|| defined( 'SEOPRESS_VERSION' )       // SEOPress
		|| defined( 'AIOSEO_VERSION' )         // All in One SEO
		|| function_exists( 'aioseo' );
}

/**
 * Build a context-aware social meta payload for the current page.
 *
 * @since 5.1.0
 * @return array{
 *   title:string, description:string, image:string, url:string,
 *   type:string, site_name:string, locale:string
 * }
 */
function chf_build_social_meta() {
	$title       = '';
	$description = '';
	$image       = '';
	$url         = '';
	$type        = 'website';
	$site_name   = get_bloginfo( 'name' );
	$locale      = str_replace( '_', '-', get_locale() );

	if ( is_singular() ) {
		$post_id     = get_the_ID();
		$title       = get_the_title( $post_id );
		$description = has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 30 );
		$url         = get_permalink( $post_id );
		$image       = get_the_post_thumbnail_url( $post_id, 'full' ) ?: '';
		$type        = is_singular( 'post' ) ? 'article' : 'website';
	} elseif ( is_home() || is_front_page() ) {
		$title       = get_bloginfo( 'name' );
		$description = get_bloginfo( 'description' );
		$url         = home_url( '/' );
		$type        = 'website';
	} elseif ( is_archive() ) {
		$title       = wp_strip_all_tags( get_the_archive_title() );
		$description = wp_strip_all_tags( get_the_archive_description() );
		$url         = trailingslashit( home_url( $_SERVER['REQUEST_URI'] ?? '' ) );
		$type        = 'website';
	}

	if ( $description === '' ) {
		$description = get_bloginfo( 'description' );
	}

	if ( $image === '' ) {
		$image = get_stylesheet_directory_uri() . '/assets/images/logo-retina.png';
	}

	$description = wp_strip_all_tags( (string) $description );
	$description = mb_substr( $description, 0, 200 );

	return array(
		'title'       => (string) $title,
		'description' => $description,
		'image'       => (string) $image,
		'url'         => (string) $url,
		'type'        => $type,
		'site_name'   => (string) $site_name,
		'locale'      => $locale,
	);
}

/**
 * Print Open Graph + Twitter Card meta tags.
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_social_meta() {
	if ( chf_has_seo_plugin() ) {
		return;
	}

	$meta = chf_build_social_meta();

	$tag = function( $property, $value, $is_twitter = false ) {
		if ( $value === '' ) {
			return;
		}
		$attr = $is_twitter ? 'name' : 'property';
		printf(
			'<meta %s="%s" content="%s">' . "\n",
			esc_attr( $attr ),
			esc_attr( $property ),
			esc_attr( $value )
		);
	};

	echo "\n<!-- CHF Social Meta (no SEO plugin detected) -->\n";

	// Open Graph.
	$tag( 'og:title',       $meta['title'] );
	$tag( 'og:description', $meta['description'] );
	$tag( 'og:image',       $meta['image'] );
	$tag( 'og:url',         $meta['url'] );
	$tag( 'og:type',        $meta['type'] );
	$tag( 'og:site_name',   $meta['site_name'] );
	$tag( 'og:locale',      str_replace( '-', '_', $meta['locale'] ) );

	// Twitter Card (use 'name' attribute, not 'property').
	$tag( 'twitter:card',        'summary_large_image', true );
	$tag( 'twitter:title',       $meta['title'],        true );
	$tag( 'twitter:description', $meta['description'],  true );
	$tag( 'twitter:image',       $meta['image'],        true );
}
add_action( 'wp_head', 'chf_emit_social_meta', 5 );

/**
 * Output a `<link rel="icon">` set if the WP Site Icon isn't configured.
 *
 * Reneka SEO/GEO standard: Favicon set, including 32x32, 192x192, and
 * 512x512 versions, plus Apple touch icon. WordPress Site Icon (Customizer
 * → Site Identity) handles this when set; this function is a fallback that
 * points at the theme's logo.
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_favicon_fallback() {
	if ( has_site_icon() ) {
		return;
	}

	$logo = get_stylesheet_directory_uri() . '/assets/images/logo-retina.png';

	echo '<link rel="icon" href="' . esc_url( $logo ) . '" sizes="any">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $logo ) . '">' . "\n";
}
add_action( 'wp_head', 'chf_emit_favicon_fallback', 6 );
