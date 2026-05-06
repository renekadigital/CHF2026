<?php
/**
 * CHF Site Editor Role + Elementor Restrictions
 *
 * Creates the "CHF Site Editor" role on theme activation. This is the day-to-day
 * client-side role: full content authoring across all CPTs, but no Theme Builder,
 * no Site Settings, no Custom CSS, and no HTML widget. Style controls are
 * disabled on widgets except Image and Spacer.
 *
 * IMPORTANT: Elementor Pro's Role Manager is the canonical way to enforce these
 * restrictions. The constants below mirror the Role Manager UI so the role
 * behaves correctly even before Pro's UI has been touched. Saint-Clair should
 * still walk through Elementor → Role Manager once and confirm the toggles
 * match what's listed in chf-build-playbook-mapping.md section 4.1.
 *
 * Drop into chf-theme/inc/ and require from functions.php.
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
 * Create the CHF Site Editor role
 * --------------------------------------------------------------------------
 *
 * Cloned from the Editor role and granted edit/publish capabilities for all
 * CHF CPTs (initiative, event, person, supporter, publication).
 *
 * @since 5.1.0
 * @return void
 */
function chf_create_site_editor_role() {

	// If the role already exists, remove and recreate so updates apply cleanly
	// on theme reactivation.
	if ( get_role( 'chf_site_editor' ) ) {
		remove_role( 'chf_site_editor' );
	}

	$editor = get_role( 'editor' );
	$caps   = $editor ? $editor->capabilities : array();

	// Strip caps the client should never have.
	unset( $caps['edit_theme_options'] );
	unset( $caps['manage_options'] );
	unset( $caps['edit_files'] );
	unset( $caps['unfiltered_html'] );

	// Add CPT capabilities. CHF CPTs use the standard 'post' capability_type,
	// so the editor caps already cover them — but we include explicit grants
	// in case any CPT is later promoted to its own capability_type.
	$cpt_caps = array(
		'edit_chf_initiatives', 'edit_others_chf_initiatives',
		'publish_chf_initiatives', 'read_private_chf_initiatives',
		'delete_chf_initiatives',
		'edit_chf_events', 'edit_others_chf_events',
		'publish_chf_events', 'read_private_chf_events',
		'delete_chf_events',
		'edit_chf_persons', 'edit_others_chf_persons',
		'publish_chf_persons', 'read_private_chf_persons',
		'delete_chf_persons',
		'edit_chf_supporters', 'edit_others_chf_supporters',
		'publish_chf_supporters', 'read_private_chf_supporters',
		'delete_chf_supporters',
		'edit_chf_publications', 'edit_others_chf_publications',
		'publish_chf_publications', 'read_private_chf_publications',
		'delete_chf_publications',
	);
	foreach ( $cpt_caps as $cap ) {
		$caps[ $cap ] = true;
	}

	add_role( 'chf_site_editor', __( 'CHF Site Editor', 'chf' ), $caps );
}
add_action( 'after_switch_theme', 'chf_create_site_editor_role' );

/**
 * --------------------------------------------------------------------------
 * Hide admin menu items the role shouldn't touch
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @return void
 */
function chf_restrict_admin_menu() {

	if ( ! current_user_can( 'chf_site_editor' ) || current_user_can( 'manage_options' ) ) {
		return;
	}

	// Top-level removals.
	remove_menu_page( 'themes.php' );
	remove_menu_page( 'plugins.php' );
	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' );
	remove_menu_page( 'users.php' );

	// Elementor sub-pages — keep "Templates" so they can edit page templates,
	// but hide Theme Builder, Site Settings, Custom Code, and Custom Fonts.
	remove_submenu_page( 'elementor', 'elementor-app' );          // Site Editor / Theme Builder.
	remove_submenu_page( 'elementor', 'elementor_custom_fonts' ); // Custom Fonts.
	remove_submenu_page( 'elementor', 'elementor_custom_icons' );
	remove_submenu_page( 'elementor', 'elementor-custom-code' );  // Custom Code (Pro).
	remove_submenu_page( 'elementor', 'role-manager' );

	// RankMath — hide from non-admins to prevent SEO mis-config.
	remove_menu_page( 'rank-math' );
}
add_action( 'admin_menu', 'chf_restrict_admin_menu', 999 );

/**
 * --------------------------------------------------------------------------
 * Hide Elementor's HTML widget from the editor for this role
 * --------------------------------------------------------------------------
 *
 * @since 5.1.0
 * @param array $config Elementor editor config.
 * @return array
 */
function chf_unregister_html_widget( $config ) {

	if ( ! current_user_can( 'chf_site_editor' ) || current_user_can( 'manage_options' ) ) {
		return $config;
	}

	if ( isset( $config['widgets']['html'] ) ) {
		unset( $config['widgets']['html'] );
	}

	if ( isset( $config['widgets']['shortcode'] ) ) {
		unset( $config['widgets']['shortcode'] );
	}

	return $config;
}
add_filter( 'elementor/editor/localize_settings', 'chf_unregister_html_widget' );

/**
 * --------------------------------------------------------------------------
 * Reminder banner inside the Role Manager
 * --------------------------------------------------------------------------
 *
 * Saint-Clair must still tick the Role Manager checkboxes for the Pro-side
 * restrictions (Style tab, Custom CSS panel, Pro widgets allowlist). This
 * banner reminds the next admin who opens the page.
 *
 * @since 5.1.0
 * @return void
 */
function chf_role_manager_reminder() {
	$screen = get_current_screen();
	if ( ! $screen || strpos( $screen->id, 'role-manager' ) === false ) {
		return;
	}
	echo '<div class="notice notice-info"><p><strong>CHF Site Editor role:</strong> '
		. esc_html__( 'Confirm the following are restricted for chf_site_editor — Theme Builder access, Site Settings access, Style tab on widgets except Image and Spacer, Custom CSS panel. See chf-build-playbook-mapping.md §4.1.', 'chf' )
		. '</p></div>';
}
add_action( 'admin_notices', 'chf_role_manager_reminder' );
