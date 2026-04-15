<?php
/**
 * Elementor Integration
 *
 * Widget categories, Dynamic Tags, default settings, Theme Builder
 * location support, and body class additions.
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
 * Register Elementor Widget Category
 * --------------------------------------------------------------------------
 *
 * Add a "CHF Widgets" category to the Elementor widget panel so custom
 * widgets are grouped together.
 *
 * @since 5.0.0
 *
 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager instance.
 * @return void
 */
function chf_register_elementor_widget_category( $elements_manager ) {

	$elements_manager->add_category( 'chf-widgets', array(
		'title' => __( 'CHF Widgets', 'chf' ),
		'icon'  => 'eicon-globe',
	) );
}
add_action( 'elementor/elements/categories_registered', 'chf_register_elementor_widget_category' );

/**
 * --------------------------------------------------------------------------
 * Register Custom Dynamic Tags
 * --------------------------------------------------------------------------
 *
 * Dynamic Tags allow Elementor Pro users to pull ACF field values directly
 * into any widget setting. Only registers when Elementor Pro is active.
 *
 * @since 5.0.0
 *
 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags_manager Dynamic tags manager.
 * @return void
 */
function chf_register_dynamic_tags( $dynamic_tags_manager ) {

	// Bail if Elementor Pro is not loaded (dynamic tags require Pro).
	if ( ! defined( 'ELEMENTOR_PRO_VERSION' ) ) {
		return;
	}

	// Register the tag group.
	$dynamic_tags_manager->register_group( 'chf-fields', array(
		'title' => __( 'CHF Fields', 'chf' ),
	) );

	/**
	 * Hero Eyebrow Dynamic Tag
	 */
	if ( ! class_exists( 'CHF_Hero_Eyebrow_Tag' ) ) :
	class CHF_Hero_Eyebrow_Tag extends \Elementor\Core\DynamicTags\Tag {

		/**
		 * Get tag name.
		 *
		 * @return string
		 */
		public function get_name() {
			return 'chf-hero-eyebrow';
		}

		/**
		 * Get tag title.
		 *
		 * @return string
		 */
		public function get_title() {
			return __( 'Hero Eyebrow', 'chf' );
		}

		/**
		 * Get tag group.
		 *
		 * @return string
		 */
		public function get_group() {
			return 'chf-fields';
		}

		/**
		 * Get tag categories.
		 *
		 * @return array
		 */
		public function get_categories() {
			return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
		}

		/**
		 * Render the tag output.
		 *
		 * @return void
		 */
		public function render() {
			$value = function_exists( 'get_field' ) ? get_field( 'hero_eyebrow' ) : '';
			echo wp_kses_post( $value );
		}
	}
	endif;

	/**
	 * Hero Subtitle Dynamic Tag
	 */
	if ( ! class_exists( 'CHF_Hero_Subtitle_Tag' ) ) :
	class CHF_Hero_Subtitle_Tag extends \Elementor\Core\DynamicTags\Tag {

		/**
		 * Get tag name.
		 *
		 * @return string
		 */
		public function get_name() {
			return 'chf-hero-subtitle';
		}

		/**
		 * Get tag title.
		 *
		 * @return string
		 */
		public function get_title() {
			return __( 'Hero Subtitle', 'chf' );
		}

		/**
		 * Get tag group.
		 *
		 * @return string
		 */
		public function get_group() {
			return 'chf-fields';
		}

		/**
		 * Get tag categories.
		 *
		 * @return array
		 */
		public function get_categories() {
			return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
		}

		/**
		 * Render the tag output.
		 *
		 * @return void
		 */
		public function render() {
			$value = function_exists( 'get_field' ) ? get_field( 'hero_subtitle' ) : '';
			echo wp_kses_post( $value );
		}
	}
	endif;

	/**
	 * Event Date Dynamic Tag
	 */
	if ( ! class_exists( 'CHF_Event_Date_Tag' ) ) :
	class CHF_Event_Date_Tag extends \Elementor\Core\DynamicTags\Tag {

		/**
		 * Get tag name.
		 *
		 * @return string
		 */
		public function get_name() {
			return 'chf-event-date';
		}

		/**
		 * Get tag title.
		 *
		 * @return string
		 */
		public function get_title() {
			return __( 'Event Date', 'chf' );
		}

		/**
		 * Get tag group.
		 *
		 * @return string
		 */
		public function get_group() {
			return 'chf-fields';
		}

		/**
		 * Get tag categories.
		 *
		 * @return array
		 */
		public function get_categories() {
			return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
		}

		/**
		 * Render the tag output.
		 *
		 * @return void
		 */
		public function render() {
			if ( ! function_exists( 'get_field' ) ) {
				return;
			}

			$raw_date = get_field( 'event_date' );

			if ( empty( $raw_date ) ) {
				return;
			}

			// ACF date_picker returns Ymd format; convert to readable string.
			$date_obj = DateTime::createFromFormat( 'Ymd', $raw_date );

			if ( $date_obj ) {
				echo esc_html( $date_obj->format( 'F j, Y' ) );
			}
		}
	}
	endif;

	/**
	 * Event Location Dynamic Tag
	 */
	if ( ! class_exists( 'CHF_Event_Location_Tag' ) ) :
	class CHF_Event_Location_Tag extends \Elementor\Core\DynamicTags\Tag {

		/**
		 * Get tag name.
		 *
		 * @return string
		 */
		public function get_name() {
			return 'chf-event-location';
		}

		/**
		 * Get tag title.
		 *
		 * @return string
		 */
		public function get_title() {
			return __( 'Event Location', 'chf' );
		}

		/**
		 * Get tag group.
		 *
		 * @return string
		 */
		public function get_group() {
			return 'chf-fields';
		}

		/**
		 * Get tag categories.
		 *
		 * @return array
		 */
		public function get_categories() {
			return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
		}

		/**
		 * Render the tag output.
		 *
		 * @return void
		 */
		public function render() {
			$value = function_exists( 'get_field' ) ? get_field( 'event_location' ) : '';
			echo esc_html( $value );
		}
	}
	endif;

	// Register all dynamic tags.
	$dynamic_tags_manager->register( new CHF_Hero_Eyebrow_Tag() );
	$dynamic_tags_manager->register( new CHF_Hero_Subtitle_Tag() );
	$dynamic_tags_manager->register( new CHF_Event_Date_Tag() );
	$dynamic_tags_manager->register( new CHF_Event_Location_Tag() );
}
add_action( 'elementor/dynamic_tags/register', 'chf_register_dynamic_tags' );

/**
 * --------------------------------------------------------------------------
 * Set Default Elementor Settings on Theme Activation
 * --------------------------------------------------------------------------
 *
 * Configure Elementor's global settings to match the CHF design system:
 * colors, fonts, container layout, and disable Elementor's default
 * color/font schemes so the theme tokens take precedence.
 *
 * @since 5.0.0
 * @return void
 */
function chf_set_elementor_defaults() {

	// Only run if Elementor is active.
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	// Disable Elementor's default color and font schemes.
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );

	// Use container-based layout (not legacy sections).
	update_option( 'elementor_experiment-container', 'active' );

	// Set default colors matching the CHF design system.
	$chf_colors = array(
		array(
			'_id'   => 'chf_navy',
			'title' => 'CHF Navy',
			'color' => '#1B2A4A',
		),
		array(
			'_id'   => 'chf_navy_deep',
			'title' => 'CHF Navy Deep',
			'color' => '#0f1a2e',
		),
		array(
			'_id'   => 'chf_green',
			'title' => 'CHF Green',
			'color' => '#56B84A',
		),
		array(
			'_id'   => 'chf_green_dark',
			'title' => 'CHF Green Dark',
			'color' => '#3d8f35',
		),
		array(
			'_id'   => 'chf_gold',
			'title' => 'CHF Gold',
			'color' => '#C9A84C',
		),
		array(
			'_id'   => 'chf_white',
			'title' => 'CHF White',
			'color' => '#ffffff',
		),
		array(
			'_id'   => 'chf_off_white',
			'title' => 'CHF Off White',
			'color' => '#f7f6f4',
		),
		array(
			'_id'   => 'chf_rule',
			'title' => 'CHF Rule',
			'color' => '#e5e5e5',
		),
	);

	update_option( 'elementor_custom_colors', $chf_colors );

	// Set default fonts: Inter for body, Merriweather for headings.
	$chf_fonts = array(
		array(
			'_id'             => 'chf_body',
			'title'           => 'CHF Body',
			'font_family'     => 'Inter',
			'font_weight'     => '400',
			'font_size'       => array( 'size' => 16, 'unit' => 'px' ),
			'line_height'     => array( 'size' => 1.6, 'unit' => 'em' ),
			'letter_spacing'  => array( 'size' => 0, 'unit' => 'px' ),
		),
		array(
			'_id'             => 'chf_heading',
			'title'           => 'CHF Heading',
			'font_family'     => 'Merriweather',
			'font_weight'     => '700',
		),
	);

	update_option( 'elementor_custom_fonts', $chf_fonts );

	// Set default generic font families.
	update_option( 'elementor_default_generic_fonts', 'system-ui, -apple-system, sans-serif' );
}
add_action( 'after_switch_theme', 'chf_set_elementor_defaults' );

/**
 * --------------------------------------------------------------------------
 * Elementor Pro Theme Builder Locations
 * --------------------------------------------------------------------------
 *
 * Register all available theme-builder locations so Elementor Pro
 * can assign custom templates to header, footer, single, archive, etc.
 *
 * @since 5.0.0
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $location_manager Location manager.
 * @return void
 */
function chf_register_elementor_locations( $location_manager ) {

	$location_manager->register_all_core_locations();
}
add_action( 'elementor/theme/register_locations', 'chf_register_elementor_locations' );

/**
 * --------------------------------------------------------------------------
 * Add Body Classes for Elementor Pages
 * --------------------------------------------------------------------------
 *
 * Append 'chf-elementor-page' to the body class array when the current
 * page is built with Elementor, allowing CSS targeting.
 *
 * @since 5.0.0
 *
 * @param array $classes Existing body classes.
 * @return array Modified body classes.
 */
function chf_elementor_body_classes( $classes ) {

	if ( ! did_action( 'elementor/loaded' ) ) {
		return $classes;
	}

	// Check if the current page was built with Elementor.
	if ( is_singular() && \Elementor\Plugin::$instance->documents->get( get_the_ID() ) ) {
		$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );

		if ( $document && $document->is_built_with_elementor() ) {
			$classes[] = 'chf-elementor-page';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'chf_elementor_body_classes' );
