<?php
/**
 * Custom Elementor Posts Widget Queries
 *
 * Each function is hooked into elementor/query/{query_id} and modifies the
 * WP_Query before it runs. Use the matching Query ID in any Posts widget's
 * Query → Query ID field to apply the filter.
 *
 * IDs registered:
 *   - homepage_pillars     → initiatives where parent_initiative IS NULL
 *   - upcoming_events      → events with event_date >= today, ASC
 *   - past_events          → events with event_date < today, DESC
 *   - related_publications → publications where related_initiative = current post
 *   - related_initiatives  → initiatives sharing the current initiative's category, exclude current
 *   - featured_post        → latest 1 post
 *   - recent_posts         → posts with offset 1, 9 entries
 *
 * Drop into chf-theme/inc/ and require from functions.php after the other
 * inc/* files. Reference: docs/06-template-implementation-notes.md
 *
 * @package CHF
 * @since   5.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pillars only — initiatives without a parent.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_homepage_pillars( $query ) {
	$query->set( 'meta_query', array(
		'relation' => 'OR',
		array(
			'key'     => 'parent_initiative',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'parent_initiative',
			'value'   => '',
			'compare' => '=',
		),
	) );
	$query->set( 'orderby', 'menu_order title' );
	$query->set( 'order', 'ASC' );
}
add_action( 'elementor/query/homepage_pillars', 'chf_query_homepage_pillars' );

/**
 * Upcoming events — date >= today, ascending.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_upcoming_events( $query ) {
	$query->set( 'meta_key', 'event_date' );
	$query->set( 'orderby', 'meta_value_num' );
	$query->set( 'order', 'ASC' );
	$query->set( 'meta_query', array(
		array(
			'key'     => 'event_date',
			'value'   => date( 'Ymd' ),
			'compare' => '>=',
			'type'    => 'NUMERIC',
		),
	) );
}
add_action( 'elementor/query/upcoming_events', 'chf_query_upcoming_events' );

/**
 * Past events — date < today, descending.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_past_events( $query ) {
	$query->set( 'meta_key', 'event_date' );
	$query->set( 'orderby', 'meta_value_num' );
	$query->set( 'order', 'DESC' );
	$query->set( 'meta_query', array(
		array(
			'key'     => 'event_date',
			'value'   => date( 'Ymd' ),
			'compare' => '<',
			'type'    => 'NUMERIC',
		),
	) );
}
add_action( 'elementor/query/past_events', 'chf_query_past_events' );

/**
 * Publications related to the current initiative.
 *
 * Used inside an Initiative single template — the "current post" is the
 * initiative being viewed.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_related_publications( $query ) {
	$current_id = get_the_ID();
	if ( ! $current_id ) {
		return;
	}
	$query->set( 'meta_query', array(
		array(
			'key'     => 'related_initiative',
			'value'   => $current_id,
			'compare' => '=',
		),
	) );
	$query->set( 'meta_key', 'year' );
	$query->set( 'orderby', 'meta_value_num' );
	$query->set( 'order', 'DESC' );
}
add_action( 'elementor/query/related_publications', 'chf_query_related_publications' );

/**
 * Other initiatives sharing the current initiative's category.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_related_initiatives( $query ) {
	$current_id = get_the_ID();
	if ( ! $current_id ) {
		return;
	}
	$terms = wp_get_post_terms( $current_id, 'initiative_category', array( 'fields' => 'ids' ) );
	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return;
	}
	$query->set( 'tax_query', array(
		array(
			'taxonomy' => 'initiative_category',
			'field'    => 'term_id',
			'terms'    => $terms,
		),
	) );
	$query->set( 'post__not_in', array( $current_id ) );
	$query->set( 'orderby', 'rand' );
	$query->set( 'posts_per_page', 3 );
}
add_action( 'elementor/query/related_initiatives', 'chf_query_related_initiatives' );

/**
 * Featured post — latest 1.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_featured_post( $query ) {
	$query->set( 'posts_per_page', 1 );
	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
}
add_action( 'elementor/query/featured_post', 'chf_query_featured_post' );

/**
 * Recent posts — offset 1 (so the featured post isn't repeated), 9 entries.
 *
 * @since 5.1.0
 * @param WP_Query $query Query object.
 * @return void
 */
function chf_query_recent_posts( $query ) {
	$query->set( 'posts_per_page', 9 );
	$query->set( 'offset', 1 );
	$query->set( 'orderby', 'date' );
	$query->set( 'order', 'DESC' );
}
add_action( 'elementor/query/recent_posts', 'chf_query_recent_posts' );
