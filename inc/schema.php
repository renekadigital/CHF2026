<?php
/**
 * Custom JSON-LD Schema Output
 *
 * Emits structured data that RankMath does not produce out of the box:
 *   - NGO root entity on the homepage
 *   - Event with Place + Offer + Person (speakers) on chf_event singles
 *   - Person on chf_person singles
 *   - EducationalProgram on the Leadership Forum page
 *   - CreativeWork + DigitalDocument on chf_publication singles
 *
 * RankMath continues to handle Article, WebPage, BreadcrumbList,
 * Organization metadata, and Open Graph. Where this file would conflict
 * with RankMath output, prefer the RankMath schema and disable this file's
 * emission for the affected post type.
 *
 * Drop into chf-theme/inc/ and require from functions.php after the CPT
 * and ACF files.
 *
 * @package CHF
 * @since   5.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print a JSON-LD <script> block.
 *
 * @since 5.1.0
 * @param array $schema Associative array — gets json_encoded.
 * @return void
 */
function chf_print_schema( array $schema ) {
	if ( empty( $schema ) ) {
		return;
	}
	echo "\n" . '<script type="application/ld+json">' . "\n";
	echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	echo "\n" . '</script>' . "\n";
}

/**
 * Org/NGO base block — reused by every page-specific schema.
 *
 * @since 5.1.0
 * @return array
 */
function chf_get_organization_schema() {
	return array(
		'@type'       => 'NGO',
		'@id'         => home_url( '/#organization' ),
		'name'        => 'Center for Houston\'s Future',
		'url'         => home_url( '/' ),
		'logo'        => array(
			'@type' => 'ImageObject',
			'url'   => get_stylesheet_directory_uri() . '/assets/images/logo.svg',
		),
		'sameAs'      => array(
			'https://www.linkedin.com/company/centerforhoustonsfuture',
			'https://twitter.com/houstonsfuture',
			'https://www.facebook.com/centerforhoustonsfuture',
		),
		'description' => 'Nonprofit driving the long-term prosperity of the Houston region through research, leadership development, and cross-sector convening.',
	);
}

/**
 * --------------------------------------------------------------------------
 * Homepage — NGO + WebSite
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_homepage_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			chf_get_organization_schema(),
			array(
				'@type'      => 'WebSite',
				'@id'        => home_url( '/#website' ),
				'url'        => home_url( '/' ),
				'name'       => get_bloginfo( 'name' ),
				'publisher'  => array( '@id' => home_url( '/#organization' ) ),
				'inLanguage' => 'en-US',
			),
		),
	);

	chf_print_schema( $schema );
}
add_action( 'wp_head', 'chf_emit_homepage_schema', 20 );

/**
 * --------------------------------------------------------------------------
 * Event — Event + Place + Offer + Person speakers
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_event_schema() {
	if ( ! is_singular( 'chf_event' ) ) {
		return;
	}

	$post_id = get_the_ID();

	$start_raw = get_field( 'event_date', $post_id );
	$end_raw   = get_field( 'end_date', $post_id );

	// ACF returns Ymd; convert to ISO 8601 date.
	$start_iso = $start_raw ? date( 'Y-m-d', strtotime( $start_raw ) ) : '';
	$end_iso   = $end_raw   ? date( 'Y-m-d', strtotime( $end_raw ) )   : $start_iso;

	$status_map = array(
		'open'     => 'https://schema.org/EventScheduled',
		'closed'   => 'https://schema.org/EventScheduled',
		'sold-out' => 'https://schema.org/EventScheduled',
		'past'     => 'https://schema.org/EventScheduled',
	);
	$status_raw = get_field( 'registration_status', $post_id );

	$location_name = get_field( 'event_location', $post_id );
	$location_addr = get_field( 'location_address', $post_id );

	$location = array();
	if ( $location_name || $location_addr ) {
		$location = array(
			'@type' => 'Place',
			'name'  => $location_name ?: get_the_title(),
		);
		if ( $location_addr ) {
			$location['address'] = array(
				'@type'         => 'PostalAddress',
				'streetAddress' => $location_addr,
			);
		}
	}

	$reg_url = get_field( 'event_registration_url', $post_id );
	$offers  = array();
	if ( $reg_url && $status_raw !== 'past' ) {
		$availability = ( $status_raw === 'sold-out' ) ? 'https://schema.org/SoldOut'
			: ( ( $status_raw === 'closed' ) ? 'https://schema.org/InStock' : 'https://schema.org/InStock' );
		$offers = array(
			'@type'         => 'Offer',
			'url'           => $reg_url,
			'availability'  => $availability,
			'validFrom'     => $start_iso,
		);
	}

	$speakers = get_field( 'speakers', $post_id );
	$performers = array();
	if ( is_array( $speakers ) ) {
		foreach ( $speakers as $speaker_id ) {
			$performers[] = array(
				'@type' => 'Person',
				'name'  => get_the_title( $speaker_id ),
				'url'   => get_permalink( $speaker_id ),
			);
		}
	}

	$schema = array(
		'@context'           => 'https://schema.org',
		'@type'              => 'Event',
		'name'               => get_the_title(),
		'description'        => wp_strip_all_tags( get_the_excerpt() ),
		'startDate'          => $start_iso,
		'endDate'            => $end_iso,
		'eventStatus'        => $status_map[ $status_raw ] ?? 'https://schema.org/EventScheduled',
		'eventAttendanceMode'=> 'https://schema.org/OfflineEventAttendanceMode',
		'image'              => get_the_post_thumbnail_url( $post_id, 'full' ) ?: '',
		'organizer'          => array( '@id' => home_url( '/#organization' ) ),
	);

	if ( $location )   { $schema['location']   = $location; }
	if ( $offers )     { $schema['offers']     = $offers; }
	if ( $performers ) { $schema['performer']  = $performers; }

	chf_print_schema( $schema );
}
add_action( 'wp_head', 'chf_emit_event_schema', 20 );

/**
 * --------------------------------------------------------------------------
 * Person — Person profile schema
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_person_schema() {
	if ( ! is_singular( 'chf_person' ) ) {
		return;
	}

	$post_id = get_the_ID();

	$role     = get_field( 'title_or_role', $post_id );
	$org      = get_field( 'organization', $post_id );
	$linkedin = get_field( 'linkedin_url', $post_id );
	$headshot = get_field( 'headshot', $post_id );

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Person',
		'name'        => get_the_title(),
		'url'         => get_permalink(),
		'jobTitle'    => $role,
	);

	if ( $org ) {
		$schema['worksFor'] = array(
			'@type' => 'Organization',
			'name'  => $org,
		);
	}

	if ( $linkedin ) {
		$schema['sameAs'] = array( $linkedin );
	}

	if ( ! empty( $headshot['url'] ) ) {
		$schema['image'] = $headshot['url'];
	}

	$schema['affiliation'] = array( '@id' => home_url( '/#organization' ) );

	chf_print_schema( $schema );
}
add_action( 'wp_head', 'chf_emit_person_schema', 20 );

/**
 * --------------------------------------------------------------------------
 * Leadership Forum — EducationalProgram
 * --------------------------------------------------------------------------
 *
 * Emits on the /leadership/ page only. Slug-matched so it doesn't fire on
 * the sub-pages (forum-portal, alumni-directory, etc.).
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_leadership_forum_schema() {
	if ( ! is_page( 'leadership' ) ) {
		return;
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'EducationalProgram',
		'name'       => 'CHF Leadership Forum',
		'url'        => get_permalink(),
		'description'=> wp_strip_all_tags( get_the_excerpt() ?: 'A flagship cross-sector leadership development program for the Houston region.' ),
		'provider'   => array( '@id' => home_url( '/#organization' ) ),
		'educationalProgramMode' => 'In-person',
	);

	chf_print_schema( $schema );
}
add_action( 'wp_head', 'chf_emit_leadership_forum_schema', 20 );

/**
 * --------------------------------------------------------------------------
 * Publication — CreativeWork + DigitalDocument
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_emit_publication_schema() {
	if ( ! is_singular( 'chf_publication' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$year    = get_field( 'year', $post_id );
	$file    = get_field( 'pdf_file', $post_id );
	$cover   = get_field( 'cover_image', $post_id );
	$summary = get_field( 'summary', $post_id );

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'CreativeWork',
		'name'        => get_the_title(),
		'url'         => get_permalink(),
		'datePublished' => $year ? (string) $year : '',
		'description' => $summary,
		'publisher'   => array( '@id' => home_url( '/#organization' ) ),
	);

	if ( ! empty( $cover['url'] ) ) {
		$schema['image'] = $cover['url'];
	}

	if ( ! empty( $file['url'] ) ) {
		$schema['associatedMedia'] = array(
			'@type'      => 'DigitalDocument',
			'name'       => get_the_title(),
			'url'        => $file['url'],
			'fileFormat' => 'application/pdf',
		);
	}

	chf_print_schema( $schema );
}
add_action( 'wp_head', 'chf_emit_publication_schema', 20 );
