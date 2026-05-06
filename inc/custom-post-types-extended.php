<?php
/**
 * Extended Custom Post Types and Taxonomies
 *
 * Registers Person, Supporter, and Publication post types with their
 * taxonomies and seeded default terms. Companion to inc/custom-post-types.php.
 *
 * Drop this file into chf-theme/inc/ and require it from functions.php
 * alongside custom-post-types.php.
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
 * Register Extended Custom Post Types
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_register_extended_post_types() {

	// ---- Person CPT ----
	$person_labels = array(
		'name'                  => _x( 'Persons', 'Post type general name', 'chf' ),
		'singular_name'         => _x( 'Person', 'Post type singular name', 'chf' ),
		'menu_name'             => _x( 'Persons', 'Admin Menu text', 'chf' ),
		'name_admin_bar'        => _x( 'Person', 'Add New on Toolbar', 'chf' ),
		'add_new'               => __( 'Add New', 'chf' ),
		'add_new_item'          => __( 'Add New Person', 'chf' ),
		'new_item'              => __( 'New Person', 'chf' ),
		'edit_item'             => __( 'Edit Person', 'chf' ),
		'view_item'             => __( 'View Person', 'chf' ),
		'all_items'             => __( 'All Persons', 'chf' ),
		'search_items'          => __( 'Search Persons', 'chf' ),
		'not_found'             => __( 'No persons found.', 'chf' ),
		'not_found_in_trash'    => __( 'No persons found in Trash.', 'chf' ),
		'archives'              => __( 'Person Archives', 'chf' ),
		'filter_items_list'     => __( 'Filter persons list', 'chf' ),
		'items_list_navigation' => __( 'Persons list navigation', 'chf' ),
		'items_list'            => __( 'Persons list', 'chf' ),
	);

	register_post_type( 'chf_person', array(
		'labels'             => $person_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'person', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 7,
		'menu_icon'          => 'dashicons-businessperson',
		'show_in_rest'       => true,
		'supports'           => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'revisions',
		),
	) );

	// ---- Supporter CPT ----
	$supporter_labels = array(
		'name'                  => _x( 'Supporters', 'Post type general name', 'chf' ),
		'singular_name'         => _x( 'Supporter', 'Post type singular name', 'chf' ),
		'menu_name'             => _x( 'Supporters', 'Admin Menu text', 'chf' ),
		'name_admin_bar'        => _x( 'Supporter', 'Add New on Toolbar', 'chf' ),
		'add_new'               => __( 'Add New', 'chf' ),
		'add_new_item'          => __( 'Add New Supporter', 'chf' ),
		'new_item'              => __( 'New Supporter', 'chf' ),
		'edit_item'             => __( 'Edit Supporter', 'chf' ),
		'view_item'             => __( 'View Supporter', 'chf' ),
		'all_items'             => __( 'All Supporters', 'chf' ),
		'search_items'          => __( 'Search Supporters', 'chf' ),
		'not_found'             => __( 'No supporters found.', 'chf' ),
		'not_found_in_trash'    => __( 'No supporters found in Trash.', 'chf' ),
		'archives'              => __( 'Supporter Archives', 'chf' ),
		'filter_items_list'     => __( 'Filter supporters list', 'chf' ),
		'items_list_navigation' => __( 'Supporters list navigation', 'chf' ),
		'items_list'            => __( 'Supporters list', 'chf' ),
	);

	register_post_type( 'chf_supporter', array(
		'labels'             => $supporter_labels,
		'public'             => true,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 8,
		'menu_icon'          => 'dashicons-heart',
		'show_in_rest'       => true,
		'supports'           => array(
			'title',
			'thumbnail',
			'custom-fields',
			'revisions',
		),
	) );

	// ---- Publication CPT ----
	$publication_labels = array(
		'name'                  => _x( 'Publications', 'Post type general name', 'chf' ),
		'singular_name'         => _x( 'Publication', 'Post type singular name', 'chf' ),
		'menu_name'             => _x( 'Publications', 'Admin Menu text', 'chf' ),
		'name_admin_bar'        => _x( 'Publication', 'Add New on Toolbar', 'chf' ),
		'add_new'               => __( 'Add New', 'chf' ),
		'add_new_item'          => __( 'Add New Publication', 'chf' ),
		'new_item'              => __( 'New Publication', 'chf' ),
		'edit_item'             => __( 'Edit Publication', 'chf' ),
		'view_item'             => __( 'View Publication', 'chf' ),
		'all_items'             => __( 'All Publications', 'chf' ),
		'search_items'          => __( 'Search Publications', 'chf' ),
		'not_found'             => __( 'No publications found.', 'chf' ),
		'not_found_in_trash'    => __( 'No publications found in Trash.', 'chf' ),
		'archives'              => __( 'Publication Archives', 'chf' ),
		'filter_items_list'     => __( 'Filter publications list', 'chf' ),
		'items_list_navigation' => __( 'Publications list navigation', 'chf' ),
		'items_list'            => __( 'Publications list', 'chf' ),
	);

	register_post_type( 'chf_publication', array(
		'labels'             => $publication_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'publication', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => 'publications',
		'hierarchical'       => false,
		'menu_position'      => 9,
		'menu_icon'          => 'dashicons-media-document',
		'show_in_rest'       => true,
		'supports'           => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'custom-fields',
			'revisions',
		),
	) );
}
add_action( 'init', 'chf_register_extended_post_types' );

/**
 * --------------------------------------------------------------------------
 * Register Extended Taxonomies
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_register_extended_taxonomies() {

	// ---- Person Type ----
	register_taxonomy( 'person_type', array( 'chf_person' ), array(
		'labels'            => array(
			'name'              => _x( 'Person Types', 'taxonomy general name', 'chf' ),
			'singular_name'     => _x( 'Person Type', 'taxonomy singular name', 'chf' ),
			'search_items'      => __( 'Search Person Types', 'chf' ),
			'all_items'         => __( 'All Person Types', 'chf' ),
			'edit_item'         => __( 'Edit Person Type', 'chf' ),
			'update_item'       => __( 'Update Person Type', 'chf' ),
			'add_new_item'      => __( 'Add New Person Type', 'chf' ),
			'new_item_name'     => __( 'New Person Type Name', 'chf' ),
			'menu_name'         => __( 'Type', 'chf' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'person-type', 'with_front' => false ),
	) );

	// ---- Supporter Tier ----
	register_taxonomy( 'supporter_tier', array( 'chf_supporter' ), array(
		'labels'            => array(
			'name'              => _x( 'Supporter Tiers', 'taxonomy general name', 'chf' ),
			'singular_name'     => _x( 'Supporter Tier', 'taxonomy singular name', 'chf' ),
			'search_items'      => __( 'Search Tiers', 'chf' ),
			'all_items'         => __( 'All Tiers', 'chf' ),
			'edit_item'         => __( 'Edit Tier', 'chf' ),
			'update_item'       => __( 'Update Tier', 'chf' ),
			'add_new_item'      => __( 'Add New Tier', 'chf' ),
			'new_item_name'     => __( 'New Tier Name', 'chf' ),
			'menu_name'         => __( 'Tier', 'chf' ),
		),
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => false,
		'show_in_rest'      => true,
		'rewrite'           => false,
	) );

	// ---- Publication Type ----
	register_taxonomy( 'publication_type', array( 'chf_publication' ), array(
		'labels'            => array(
			'name'              => _x( 'Publication Types', 'taxonomy general name', 'chf' ),
			'singular_name'     => _x( 'Publication Type', 'taxonomy singular name', 'chf' ),
			'search_items'      => __( 'Search Types', 'chf' ),
			'all_items'         => __( 'All Types', 'chf' ),
			'edit_item'         => __( 'Edit Type', 'chf' ),
			'update_item'       => __( 'Update Type', 'chf' ),
			'add_new_item'      => __( 'Add New Type', 'chf' ),
			'new_item_name'     => __( 'New Type Name', 'chf' ),
			'menu_name'         => __( 'Type', 'chf' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'publication-type', 'with_front' => false ),
	) );
}
add_action( 'init', 'chf_register_extended_taxonomies' );

/**
 * --------------------------------------------------------------------------
 * Insert Default Extended Taxonomy Terms
 * --------------------------------------------------------------------------
 *
 * Seeded on theme activation alongside the base term set.
 *
 * @since 5.1.0
 * @return void
 */
function chf_insert_default_extended_terms() {

	// Person Type defaults.
	$person_terms = array(
		'staff'      => 'Staff',
		'board'      => 'Board',
		'speaker'    => 'Speaker',
		'forum-alum' => 'Forum Alum',
	);

	foreach ( $person_terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'person_type' ) ) {
			wp_insert_term( $name, 'person_type', array( 'slug' => $slug ) );
		}
	}

	// Supporter Tier defaults.
	$supporter_terms = array(
		'founding'  => 'Founding',
		'strategic' => 'Strategic',
		'program'   => 'Program',
		'annual'    => 'Annual',
	);

	foreach ( $supporter_terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'supporter_tier' ) ) {
			wp_insert_term( $name, 'supporter_tier', array( 'slug' => $slug ) );
		}
	}

	// Publication Type defaults.
	$publication_terms = array(
		'annual-report'  => 'Annual Report',
		'press-release'  => 'Press Release',
		'990'            => 'IRS 990',
		'whitepaper'     => 'Whitepaper',
		'plan'           => 'Plan',
		'irs-letter'     => 'IRS Letter',
		'financial'      => 'Financial Statement',
	);

	foreach ( $publication_terms as $slug => $name ) {
		if ( ! term_exists( $slug, 'publication_type' ) ) {
			wp_insert_term( $name, 'publication_type', array( 'slug' => $slug ) );
		}
	}
}
add_action( 'after_switch_theme', 'chf_insert_default_extended_terms' );

/**
 * --------------------------------------------------------------------------
 * Flush Rewrite Rules for Extended CPTs on Theme Activation
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_flush_extended_rewrite_rules() {
	chf_register_extended_post_types();
	chf_register_extended_taxonomies();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'chf_flush_extended_rewrite_rules' );
