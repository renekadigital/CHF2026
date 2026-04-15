<?php
/**
 * Security Hardening
 *
 * HTTP security headers, XML-RPC disable, version stripping,
 * login protection, and head cleanup.
 *
 * @package CHF
 * @since   5.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * --------------------------------------------------------------------------
 * HTTP Security Headers
 * --------------------------------------------------------------------------
 *
 * Send security-related HTTP headers on every response. The CSP includes
 * 'unsafe-eval' because Elementor's editor requires it at runtime.
 *
 * @since 5.0.0
 * @return void
 */
function chf_security_headers() {

	if ( headers_sent() ) {
		return;
	}

	// Content Security Policy.
	header( "Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' data: https:; frame-src https://www.google.com;" );

	// Prevent MIME-type sniffing.
	header( 'X-Content-Type-Options: nosniff' );

	// Prevent clickjacking.
	header( 'X-Frame-Options: SAMEORIGIN' );

	// Referrer policy.
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );

	// Permissions policy — disable unused APIs.
	header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );

	// HSTS — force HTTPS for one year including subdomains.
	header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains' );
}
add_action( 'send_headers', 'chf_security_headers' );

/**
 * --------------------------------------------------------------------------
 * Disable XML-RPC
 * --------------------------------------------------------------------------
 *
 * XML-RPC is a common attack vector and is not needed for this site.
 *
 * @since 5.0.0
 *
 * @param bool $enabled Whether XML-RPC is enabled.
 * @return bool Always false.
 */
function chf_disable_xmlrpc( $enabled ) {
	return false;
}
add_filter( 'xmlrpc_enabled', 'chf_disable_xmlrpc' );

/**
 * --------------------------------------------------------------------------
 * Remove WordPress Version Number
 * --------------------------------------------------------------------------
 *
 * Strip the WP version from the <head> generator tag and RSS feeds
 * to reduce information disclosure.
 *
 * @since 5.0.0
 *
 * @return string Empty string.
 */
function chf_remove_wp_version() {
	return '';
}
add_filter( 'the_generator', 'chf_remove_wp_version' );

/**
 * --------------------------------------------------------------------------
 * Disable File Editing in Admin
 * --------------------------------------------------------------------------
 *
 * Prevent theme/plugin file editing from the WordPress admin panel.
 * Only define the constant if it has not already been set in wp-config.php.
 *
 * @since 5.0.0
 * @return void
 */
function chf_disable_file_editing() {
	if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
		define( 'DISALLOW_FILE_EDIT', true );
	}
}
add_action( 'init', 'chf_disable_file_editing' );

/**
 * --------------------------------------------------------------------------
 * Generic Login Error Messages
 * --------------------------------------------------------------------------
 *
 * Replace specific login error messages with a generic one to prevent
 * username enumeration.
 *
 * @since 5.0.0
 *
 * @param string $error The original error message.
 * @return string A generic error message.
 */
function chf_login_error_message( $error ) {
	return __( 'The login credentials you entered are incorrect. Please try again.', 'chf' );
}
add_filter( 'login_errors', 'chf_login_error_message' );

/**
 * --------------------------------------------------------------------------
 * Remove Unnecessary Header Links
 * --------------------------------------------------------------------------
 *
 * Clean up wp_head by removing RSD, WLW manifest, shortlink, and
 * REST API discovery links.
 *
 * @since 5.0.0
 * @return void
 */
function chf_clean_wp_head() {

	// Really Simple Discovery link (for XML-RPC clients).
	remove_action( 'wp_head', 'rsd_link' );

	// Windows Live Writer manifest link.
	remove_action( 'wp_head', 'wlwmanifest_link' );

	// Shortlink.
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );

	// REST API discovery link.
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

	// oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
add_action( 'after_setup_theme', 'chf_clean_wp_head' );
