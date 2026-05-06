<?php
/**
 * WP-CLI Commands for CHF Deployment Automation
 *
 * Adds `wp chf` commands so the deploy script can do in seconds what the
 * INSTALL-GUIDE.md asks a human operator to do across many admin screens.
 *
 *   wp chf assign-templates   Assigns Theme Builder display conditions to
 *                             the 7 imported Elementor JSON templates.
 *   wp chf finalize           Sets static front page, posts page, flushes
 *                             rewrite rules, and clears Elementor CSS cache.
 *   wp chf apply-defaults     Force-applies the design-system kit settings
 *                             (Global Colors and Typography). Safe to re-run.
 *   wp chf assign-menus       Maps the 4 imported nav menus to theme
 *                             locations defined in functions.php.
 *
 * @package CHF
 * @since   5.0.1
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only register commands when WP-CLI is loaded.
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

/**
 * CHF deployment automation commands.
 */
class CHF_CLI_Commands {

	/**
	 * Assign Theme Builder display conditions to imported Elementor templates.
	 *
	 * Maps each template (by exact post_title) to its Elementor template_type
	 * and writes the _elementor_conditions post meta directly. This bypasses
	 * the manual click-through in Templates -> Theme Builder.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Print what would be assigned without writing meta.
	 *
	 * ## EXAMPLES
	 *
	 *     wp chf assign-templates
	 *     wp chf assign-templates --dry-run
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 * @return void
	 */
	public function assign_templates( $args, $assoc_args ) {

		if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			\WP_CLI::error( 'Elementor Pro is required for Theme Builder. Activate Elementor Pro and rerun.' );
		}

		$dry_run = ! empty( $assoc_args['dry-run'] );

		// title => array( template_type, conditions[] )
		//
		// Conditions use Elementor Pro 4.x's "<type>/<name>[/<sub_name>][/<sub_id>]" format.
		// parse_condition() in conditions-manager.php explodes by "/" into [type, name, sub_name, sub_id].
		// type MUST be "include" or "exclude" — earlier Pro 3.x format ("general/include/...") is silently
		// ignored by Pro 4.x and produces no rendered output. See memory: elementor_pro_conditions_cache.md.
		$assignments = array(
			'CHF Global Header' => array(
				'header',
				array( 'include/general' ),
			),
			'CHF Global Footer' => array(
				'footer',
				array( 'include/general' ),
			),
			'CHF Interior Page' => array(
				'single-page',
				array(
					'include/page',
					'exclude/page/in_front_page',
				),
			),
			'CHF Initiative Single' => array(
				'single-post',
				array( 'include/chf_initiative' ),
			),
			'CHF Event Single' => array(
				'single-post',
				array( 'include/chf_event' ),
			),
			'CHF News Archive' => array(
				'archive',
				array( 'include/post_archive' ),
			),
			'CHF Events Archive' => array(
				'archive',
				array( 'include/chf_event_archive' ),
			),
		);

		$assigned = 0;
		$missing  = array();

		foreach ( $assignments as $title => $config ) {

			list( $type, $conditions ) = $config;

			$template = $this->find_template_by_title( $title );

			if ( ! $template ) {
				$missing[] = $title;
				\WP_CLI::warning( sprintf( 'Template not found: %s', $title ) );
				continue;
			}

			if ( $dry_run ) {
				\WP_CLI::log( sprintf( '[dry-run] %s -> type=%s, conditions=%s', $title, $type, implode( ',', $conditions ) ) );
				continue;
			}

			update_post_meta( $template->ID, '_elementor_template_type', $type );
			update_post_meta( $template->ID, '_elementor_conditions', $conditions );

			$assigned++;
			\WP_CLI::log( sprintf( 'Assigned "%s" -> %s (%s)', $title, $type, implode( ', ', $conditions ) ) );
		}

		// Refresh Elementor Pro's location/condition cache so changes take effect immediately.
		if ( ! $dry_run ) {
			delete_transient( 'elementor_pro_theme_builder_locations' );

			if ( class_exists( '\\ElementorPro\\Modules\\ThemeBuilder\\Module' ) ) {
				$module = \ElementorPro\Modules\ThemeBuilder\Module::instance();
				if ( method_exists( $module, 'get_conditions_manager' ) ) {
					$cm = $module->get_conditions_manager();
					if ( method_exists( $cm, 'get_cache' ) && method_exists( $cm->get_cache(), 'regenerate' ) ) {
						$cm->get_cache()->regenerate();
					}
				}
			}
		}

		if ( ! empty( $missing ) ) {
			\WP_CLI::warning( sprintf( '%d template(s) missing — import elementor-templates/*.json before running this command.', count( $missing ) ) );
		}

		\WP_CLI::success( sprintf( 'Assigned %d Theme Builder condition(s).', $assigned ) );
	}

	/**
	 * Finalize the deployment after content + templates are in place.
	 *
	 *   - Sets static front page to "Future Houston" (slug: home)
	 *   - Sets posts page to "News & Media" (slug: news)
	 *   - Assigns nav menus to theme locations
	 *   - Flushes rewrite rules
	 *   - Clears Elementor CSS cache
	 *
	 * ## EXAMPLES
	 *
	 *     wp chf finalize
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 * @return void
	 */
	public function finalize( $args, $assoc_args ) {

		// Static front page.
		$home = get_page_by_path( 'home' );
		$news = get_page_by_path( 'news' );

		if ( $home ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home->ID );
			\WP_CLI::log( sprintf( 'Front page: "Future Houston" (ID %d)', $home->ID ) );
		} else {
			\WP_CLI::warning( 'Page with slug "home" not found. Skipping static front page.' );
		}

		if ( $news ) {
			update_option( 'page_for_posts', $news->ID );
			\WP_CLI::log( sprintf( 'Posts page: "News & Media" (ID %d)', $news->ID ) );
		} else {
			\WP_CLI::warning( 'Page with slug "news" not found. Skipping posts page.' );
		}

		// Trigger Elementor defaults if still pending.
		if ( '1' === get_option( 'chf_needs_elementor_defaults' ) && function_exists( 'chf_apply_elementor_defaults' ) ) {
			chf_apply_elementor_defaults();
			\WP_CLI::log( 'Applied Elementor design-system defaults.' );
		}

		// Re-register CPTs/taxonomies and flush rewrite rules.
		if ( function_exists( 'chf_register_post_types' ) ) {
			chf_register_post_types();
		}
		if ( function_exists( 'chf_register_taxonomies' ) ) {
			chf_register_taxonomies();
		}
		flush_rewrite_rules( false );
		\WP_CLI::log( 'Flushed rewrite rules.' );

		// Clear Elementor CSS cache.
		if ( class_exists( '\Elementor\Plugin' ) && method_exists( \Elementor\Plugin::$instance->files_manager, 'clear_cache' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
			\WP_CLI::log( 'Cleared Elementor CSS cache.' );
		}

		\WP_CLI::success( 'Deployment finalized.' );
	}

	/**
	 * Force-apply Elementor design-system defaults to the active Kit.
	 *
	 * Useful as a sanity step or when Bug 2's queueing didn't fire.
	 *
	 * ## EXAMPLES
	 *
	 *     wp chf apply-defaults
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 * @return void
	 */
	public function apply_defaults( $args, $assoc_args ) {

		if ( ! function_exists( 'chf_apply_elementor_defaults' ) ) {
			\WP_CLI::error( 'chf_apply_elementor_defaults() not found. Is the CHF theme active?' );
		}

		// Force the flag so the function runs.
		update_option( 'chf_needs_elementor_defaults', '1', false );
		chf_apply_elementor_defaults();

		// If the flag is still set, Elementor wasn't ready.
		if ( '1' === get_option( 'chf_needs_elementor_defaults' ) ) {
			\WP_CLI::warning( 'Elementor not fully loaded; defaults will retry on next admin pageload.' );
		} else {
			\WP_CLI::success( 'Elementor design-system defaults applied to active Kit.' );
		}
	}

	/**
	 * Assign imported nav menus to theme locations.
	 *
	 * Map by menu name (matching the WXR import) to the theme location slug
	 * registered in functions.php.
	 *
	 * ## EXAMPLES
	 *
	 *     wp chf assign-menus
	 *
	 * @when after_wp_load
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Named arguments.
	 * @return void
	 */
	public function assign_menus( $args, $assoc_args ) {

		$mapping = array(
			'Primary Navigation'    => 'primary',
			'Footer - About'        => 'footer-about',
			'Footer - Initiatives'  => 'footer-initiatives',
			'Footer - Leadership'   => 'footer-leadership',
			'Footer - Connect'      => 'footer-connect',
		);

		$locations = get_theme_mod( 'nav_menu_locations', array() );

		foreach ( $mapping as $menu_name => $location ) {

			$menu = wp_get_nav_menu_object( $menu_name );

			if ( ! $menu ) {
				\WP_CLI::warning( sprintf( 'Menu not found: %s', $menu_name ) );
				continue;
			}

			$locations[ $location ] = $menu->term_id;
			\WP_CLI::log( sprintf( '%s -> %s (term %d)', $menu_name, $location, $menu->term_id ) );
		}

		set_theme_mod( 'nav_menu_locations', $locations );
		\WP_CLI::success( sprintf( 'Assigned %d menu(s) to theme locations.', count( $locations ) ) );
	}

	/**
	 * Helper: find an Elementor template (post type elementor_library) by exact title.
	 *
	 * @param string $title Exact post_title to match.
	 * @return WP_Post|null
	 */
	private function find_template_by_title( $title ) {

		$query = new WP_Query(
			array(
				'post_type'              => 'elementor_library',
				'post_status'            => 'any',
				'title'                  => $title,
				'posts_per_page'         => 1,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			)
		);

		return ! empty( $query->posts ) ? $query->posts[0] : null;
	}
}

\WP_CLI::add_command( 'chf', 'CHF_CLI_Commands' );
