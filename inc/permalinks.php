<?php
/**
 * Permalink Hierarchy + Archive Routing
 *
 * Two changes the playbook calls for that the base CPT registration in
 * inc/custom-post-types.php doesn't already produce:
 *
 *   1. Initiative URLs follow /initiatives/{pillar}/{sub}/ instead of the
 *      default /initiative/{slug}/. Implemented via a register_post_type_args
 *      filter (changes slug to "initiatives") plus a pre_post_link filter
 *      and matching rewrite rule that splices the pillar taxonomy term in.
 *
 *   2. Event archive lives at /events/ instead of /events-archive/.
 *      Implemented via the same register_post_type_args filter on chf_event.
 *
 * Drop into chf-theme/inc/ and require from functions.php AFTER
 * inc/custom-post-types.php so the filter runs against the registration.
 *
 * After deploying, visit Settings → Permalinks and click Save (or run
 * `wp rewrite flush`) so the new rules take effect.
 *
 * @package CHF
 * @since   5.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * --------------------------------------------------------------------------
 * Filter CPT args at registration time
 * --------------------------------------------------------------------------
 *
 * Runs before WordPress finalizes the CPT, so we can change rewrite slug
 * and has_archive without forking inc/custom-post-types.php.
 *
 * @since 5.1.0
 * @param array  $args      Post type registration args.
 * @param string $post_type Post type key.
 * @return array
 */
function chf_filter_cpt_args( $args, $post_type ) {

	if ( 'chf_initiative' === $post_type ) {
		$args['rewrite']     = array( 'slug' => 'initiatives', 'with_front' => false );
		$args['has_archive'] = 'initiatives';
	}

	if ( 'chf_event' === $post_type ) {
		$args['rewrite']     = array( 'slug' => 'events', 'with_front' => false );
		$args['has_archive'] = 'events';
	}

	return $args;
}
add_filter( 'register_post_type_args', 'chf_filter_cpt_args', 10, 2 );

/**
 * --------------------------------------------------------------------------
 * Insert pillar slug into initiative permalinks
 * --------------------------------------------------------------------------
 *
 * Transforms /initiatives/{slug}/ → /initiatives/{pillar}/{slug}/ for
 * sub-initiatives. Pillars (those without a parent_initiative ACF value)
 * keep the flat /initiatives/{slug}/ URL.
 *
 * Pillar lookup priority: ACF parent_initiative → initiative_category
 * taxonomy term. The ACF relationship wins because it's the authoritative
 * link; the taxonomy is a secondary classification.
 *
 * @since 5.1.0
 * @param string  $post_link Default permalink.
 * @param WP_Post $post      Post object.
 * @return string
 */
function chf_initiative_permalink( $post_link, $post ) {

	if ( 'chf_initiative' !== $post->post_type ) {
		return $post_link;
	}

	$pillar_slug = chf_get_pillar_slug_for_initiative( $post->ID );

	if ( ! $pillar_slug ) {
		// This IS a pillar (or unclassified) — keep the flat URL.
		return $post_link;
	}

	// Splice the pillar in: /initiatives/{slug}/ → /initiatives/{pillar}/{slug}/
	$post_link = str_replace(
		'/initiatives/' . $post->post_name,
		'/initiatives/' . $pillar_slug . '/' . $post->post_name,
		$post_link
	);

	return $post_link;
}
add_filter( 'post_type_link', 'chf_initiative_permalink', 10, 2 );

/**
 * Resolve the pillar slug for an initiative post.
 *
 * @since 5.1.0
 * @param int $post_id Initiative post ID.
 * @return string Pillar slug, or empty if none (i.e. the post IS a pillar).
 */
function chf_get_pillar_slug_for_initiative( $post_id ) {

	// 1. Prefer the ACF parent_initiative relationship.
	if ( function_exists( 'get_field' ) ) {
		$parent_id = get_field( 'parent_initiative', $post_id );
		if ( $parent_id ) {
			$parent = get_post( (int) $parent_id );
			if ( $parent && 'chf_initiative' === $parent->post_type ) {
				return $parent->post_name;
			}
		}
	}

	// 2. Fall back to the initiative_category taxonomy.
	$terms = wp_get_post_terms( $post_id, 'initiative_category', array( 'fields' => 'all' ) );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		// Use the first term's slug.
		return $terms[0]->slug;
	}

	return '';
}

/**
 * --------------------------------------------------------------------------
 * Add rewrite rule that matches /initiatives/{pillar}/{slug}/
 * --------------------------------------------------------------------------
 *
 * Without this, WordPress doesn't know how to resolve the pretty URL back
 * to an initiative post. The rule maps the second segment to chf_initiative
 * by name and ignores the pillar segment for routing (the link is generated
 * with it, but routing only needs the slug).
 *
 * @since 5.1.0
 * @return void
 */
function chf_register_initiative_rewrite() {

	add_rewrite_rule(
		'^initiatives/([^/]+)/([^/]+)/?$',
		'index.php?post_type=chf_initiative&name=$matches[2]',
		'top'
	);
}
add_action( 'init', 'chf_register_initiative_rewrite', 20 );

/**
 * --------------------------------------------------------------------------
 * Flush rewrite rules on theme activation
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_flush_permalink_rules() {
	chf_register_initiative_rewrite();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'chf_flush_permalink_rules', 20 );
