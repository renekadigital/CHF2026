<?php
/**
 * Custom Post Types, Taxonomies, and ACF Field Groups
 *
 * Registers the Initiative and Event post types, their taxonomies,
 * default taxonomy terms, and ACF field groups for hero/event fields.
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
 * Register Custom Post Types
 * --------------------------------------------------------------------------
 *
 * @since 5.0.0
 * @return void
 */
function chf_register_post_types() {

	// ---- Initiative CPT ----
	$initiative_labels = array(
		'name'                  => _x( 'Initiatives', 'Post type general name', 'chf' ),
		'singular_name'         => _x( 'Initiative', 'Post type singular name', 'chf' ),
		'menu_name'             => _x( 'Initiatives', 'Admin Menu text', 'chf' ),
		'name_admin_bar'        => _x( 'Initiative', 'Add New on Toolbar', 'chf' ),
		'add_new'               => __( 'Add New', 'chf' ),
		'add_new_item'          => __( 'Add New Initiative', 'chf' ),
		'new_item'              => __( 'New Initiative', 'chf' ),
		'edit_item'             => __( 'Edit Initiative', 'chf' ),
		'view_item'             => __( 'View Initiative', 'chf' ),
		'all_items'             => __( 'All Initiatives', 'chf' ),
		'search_items'          => __( 'Search Initiatives', 'chf' ),
		'not_found'             => __( 'No initiatives found.', 'chf' ),
		'not_found_in_trash'    => __( 'No initiatives found in Trash.', 'chf' ),
		'archives'              => __( 'Initiative Archives', 'chf' ),
		'filter_items_list'     => __( 'Filter initiatives list', 'chf' ),
		'items_list_navigation' => __( 'Initiatives list navigation', 'chf' ),
		'items_list'            => __( 'Initiatives list', 'chf' ),
	);

	$initiative_args = array(
		'labels'             => $initiative_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'initiative', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'initiatives',
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-chart-area',
		'show_in_rest'       => true,
		'supports'           => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'revisions',
		),
	);

	register_post_type( 'chf_initiative', $initiative_args );

	// ---- Event CPT ----
	$event_labels = array(
		'name'                  => _x( 'Events', 'Post type general name', 'chf' ),
		'singular_name'         => _x( 'Event', 'Post type singular name', 'chf' ),
		'menu_name'             => _x( 'Events', 'Admin Menu text', 'chf' ),
		'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'chf' ),
		'add_new'               => __( 'Add New', 'chf' ),
		'add_new_item'          => __( 'Add New Event', 'chf' ),
		'new_item'              => __( 'New Event', 'chf' ),
		'edit_item'             => __( 'Edit Event', 'chf' ),
		'view_item'             => __( 'View Event', 'chf' ),
		'all_items'             => __( 'All Events', 'chf' ),
		'search_items'          => __( 'Search Events', 'chf' ),
		'not_found'             => __( 'No events found.', 'chf' ),
		'not_found_in_trash'    => __( 'No events found in Trash.', 'chf' ),
		'archives'              => __( 'Event Archives', 'chf' ),
		'filter_items_list'     => __( 'Filter events list', 'chf' ),
		'items_list_navigation' => __( 'Events list navigation', 'chf' ),
		'items_list'            => __( 'Events list', 'chf' ),
	);

	$event_args = array(
		'labels'             => $event_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'event', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'events-archive',
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-calendar-alt',
		'show_in_rest'       => true,
		'supports'           => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'revisions',
		),
	);

	register_post_type( 'chf_event', $event_args );
}
add_action( 'init', 'chf_register_post_types' );

/**
 * --------------------------------------------------------------------------
 * Register Taxonomies
 * --------------------------------------------------------------------------
 *
 * @since 5.0.0
 * @return void
 */
function chf_register_taxonomies() {

	// ---- Initiative Category ----
	$init_cat_labels = array(
		'name'                       => _x( 'Initiative Categories', 'taxonomy general name', 'chf' ),
		'singular_name'              => _x( 'Initiative Category', 'taxonomy singular name', 'chf' ),
		'search_items'               => __( 'Search Initiative Categories', 'chf' ),
		'all_items'                  => __( 'All Initiative Categories', 'chf' ),
		'parent_item'                => __( 'Parent Initiative Category', 'chf' ),
		'parent_item_colon'          => __( 'Parent Initiative Category:', 'chf' ),
		'edit_item'                  => __( 'Edit Initiative Category', 'chf' ),
		'update_item'                => __( 'Update Initiative Category', 'chf' ),
		'add_new_item'               => __( 'Add New Initiative Category', 'chf' ),
		'new_item_name'              => __( 'New Initiative Category Name', 'chf' ),
		'menu_name'                  => __( 'Categories', 'chf' ),
		'items_list_navigation'      => __( 'Initiative Categories list navigation', 'chf' ),
		'items_list'                 => __( 'Initiative Categories list', 'chf' ),
	);

	register_taxonomy( 'initiative_category', array( 'chf_initiative' ), array(
		'labels'            => $init_cat_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'initiative-category', 'with_front' => false ),
	) );

	// ---- Event Type ----
	$event_type_labels = array(
		'name'                       => _x( 'Event Types', 'taxonomy general name', 'chf' ),
		'singular_name'              => _x( 'Event Type', 'taxonomy singular name', 'chf' ),
		'search_items'               => __( 'Search Event Types', 'chf' ),
		'all_items'                  => __( 'All Event Types', 'chf' ),
		'parent_item'                => __( 'Parent Event Type', 'chf' ),
		'parent_item_colon'          => __( 'Parent Event Type:', 'chf' ),
		'edit_item'                  => __( 'Edit Event Type', 'chf' ),
		'update_item'                => __( 'Update Event Type', 'chf' ),
		'add_new_item'               => __( 'Add New Event Type', 'chf' ),
		'new_item_name'              => __( 'New Event Type Name', 'chf' ),
		'menu_name'                  => __( 'Event Types', 'chf' ),
		'items_list_navigation'      => __( 'Event Types list navigation', 'chf' ),
		'items_list'                 => __( 'Event Types list', 'chf' ),
	);

	register_taxonomy( 'event_type', array( 'chf_event' ), array(
		'labels'            => $event_type_labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'event-type', 'with_front' => false ),
	) );
}
add_action( 'init', 'chf_register_taxonomies' );

/**
 * --------------------------------------------------------------------------
 * Insert Default Taxonomy Terms
 * --------------------------------------------------------------------------
 *
 * Seed initiative categories and event types on theme activation.
 *
 * @since 5.0.0
 * @return void
 */
function chf_insert_default_terms() {

	// Initiative Category defaults.
	$initiative_terms = array(
		'energy-climate' => 'Energy & Climate',
		'health-equity'  => 'Health & Health Equity',
		'immigration'    => 'Immigration',
	);

	foreach ( $initiative_terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'initiative_category' ) ) {
			wp_insert_term( $name, 'initiative_category', array( 'slug' => $slug ) );
		}
	}

	// Event Type defaults.
	$event_terms = array(
		'conference' => 'Conference',
		'forum'      => 'Forum',
		'workshop'   => 'Workshop',
		'dinner'     => 'Dinner',
		'summit'     => 'Summit',
		'meeting'    => 'Meeting',
	);

	foreach ( $event_terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'event_type' ) ) {
			wp_insert_term( $name, 'event_type', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'chf_insert_default_terms' );

/**
 * --------------------------------------------------------------------------
 * ACF Field Groups
 * --------------------------------------------------------------------------
 *
 * Register Hero fields and Event Details via ACF's local field group API.
 * Only runs when Advanced Custom Fields is active.
 *
 * @since 5.0.0
 * @return void
 */
function chf_register_acf_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// ---- Hero Fields ----
	acf_add_local_field_group( array(
		'key'      => 'group_chf_hero',
		'title'    => 'Hero Fields',
		'fields'   => array(
			array(
				'key'          => 'field_chf_hero_eyebrow',
				'label'        => 'Hero Eyebrow',
				'name'         => 'hero_eyebrow',
				'type'         => 'text',
				'instructions' => 'Short text displayed above the main heading (e.g., "Our Initiatives").',
				'required'     => 0,
			),
			array(
				'key'          => 'field_chf_hero_highlight_word',
				'label'        => 'Hero Highlight Word',
				'name'         => 'hero_highlight_word',
				'type'         => 'text',
				'instructions' => 'A word in the H1 that should receive the gradient highlight treatment.',
				'required'     => 0,
			),
			array(
				'key'          => 'field_chf_hero_subtitle',
				'label'        => 'Hero Subtitle',
				'name'         => 'hero_subtitle',
				'type'         => 'textarea',
				'instructions' => 'Supporting text shown below the hero heading.',
				'rows'         => 3,
				'required'     => 0,
			),
			array(
				'key'           => 'field_chf_hero_background_image',
				'label'         => 'Hero Background Image',
				'name'          => 'hero_background_image',
				'type'          => 'image',
				'return_format' => 'url',
				'preview_size'  => 'medium',
				'instructions'  => 'Background image for the hero section (recommended 1920x1080).',
				'required'      => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_initiative',
				),
			),
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_event',
				),
			),
		),
		'style'    => 'default',
		'position' => 'acf_after_title',
	) );

	// ---- Event Details ----
	acf_add_local_field_group( array(
		'key'      => 'group_chf_event_details',
		'title'    => 'Event Details',
		'fields'   => array(
			array(
				'key'            => 'field_chf_event_date',
				'label'          => 'Event Date',
				'name'           => 'event_date',
				'type'           => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format'  => 'Ymd',
				'first_day'      => 0,
				'instructions'   => 'The date of the event.',
				'required'       => 1,
			),
			array(
				'key'          => 'field_chf_event_time',
				'label'        => 'Event Time',
				'name'         => 'event_time',
				'type'         => 'text',
				'instructions' => 'Display time (e.g., "6:00 PM - 9:00 PM CST").',
				'required'     => 0,
			),
			array(
				'key'          => 'field_chf_event_location',
				'label'        => 'Event Location',
				'name'         => 'event_location',
				'type'         => 'text',
				'instructions' => 'Venue name and/or address.',
				'required'     => 0,
			),
			array(
				'key'          => 'field_chf_event_registration_url',
				'label'        => 'Registration URL',
				'name'         => 'event_registration_url',
				'type'         => 'url',
				'instructions' => 'Link to the event registration page.',
				'required'     => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_event',
				),
			),
		),
		'style'    => 'default',
		'position' => 'normal',
	) );
}
add_action( 'acf/init', 'chf_register_acf_fields' );

/**
 * --------------------------------------------------------------------------
 * Flush Rewrite Rules on Theme Activation
 * --------------------------------------------------------------------------
 *
 * Ensure custom post type and taxonomy permalinks work immediately
 * after the theme is activated.
 *
 * @since 5.0.0
 * @return void
 */
function chf_flush_rewrite_rules() {
	chf_register_post_types();
	chf_register_taxonomies();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'chf_flush_rewrite_rules' );
