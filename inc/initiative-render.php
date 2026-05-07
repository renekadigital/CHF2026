<?php
/**
 * Initiative Single Render
 *
 * Injects a styled section into chf_initiative single pages that renders
 * the content the imported Theme Builder template doesn't currently bind:
 *   - Bento-grid stats (from `stats` ACF repeater)
 *   - Body content (from post_content)
 *   - Reports & Resources (from `reports` ACF repeater)
 *
 * The imported Theme Builder template was authored as a static design
 * with placeholder text; this module fills the gap until the template
 * widgets are rebound to ACF fields via the Elementor editor.
 *
 * Hooks elementor/theme/after_do_location for the 'single' location, gated
 * on the chf_initiative post type.
 *
 * @package CHF
 * @since   5.1.1
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render the bento + body + reports section after the Theme Builder
 * Single location finishes.
 *
 * @since 5.1.1
 *
 * @param string $location The Theme Builder location being rendered.
 * @return void
 */
function chf_initiative_render_extras( $location ) {
	if ( 'single' !== $location ) {
		return;
	}
	if ( ! is_singular( 'chf_initiative' ) ) {
		return;
	}

	$post_id = get_the_ID();
	if ( ! $post_id ) {
		return;
	}

	$stats   = function_exists( 'get_field' ) ? get_field( 'stats', $post_id ) : null;
	$reports = function_exists( 'get_field' ) ? get_field( 'reports', $post_id ) : null;
	$content = get_post_field( 'post_content', $post_id );

	$has_stats   = is_array( $stats ) && count( $stats ) > 0;
	$has_reports = is_array( $reports ) && count( $reports ) > 0;
	$has_content = trim( wp_strip_all_tags( $content ) ) !== '';

	if ( ! $has_stats && ! $has_reports && ! $has_content ) {
		return;
	}

	echo '<section class="chf-initiative-extras" aria-labelledby="initiative-extras-heading">';
	echo '<div class="chf-initiative-extras__container">';

	// ---- Bento stats grid ----
	if ( $has_stats ) {
		echo '<div class="chf-initiative-stats" role="list" aria-label="Headline data">';
		foreach ( $stats as $stat ) {
			$num   = isset( $stat['stat_number'] ) ? (string) $stat['stat_number'] : '';
			$label = isset( $stat['stat_label'] ) ? (string) $stat['stat_label'] : '';
			if ( $num === '' && $label === '' ) {
				continue;
			}
			echo '<div class="chf-bento-card" role="listitem">';
			echo '<div class="chf-bento-card__number">' . esc_html( $num ) . '</div>';
			echo '<div class="chf-bento-card__label">' . esc_html( $label ) . '</div>';
			echo '</div>';
		}
		echo '</div>';
	}

	// ---- Body content (post_content) ----
	if ( $has_content ) {
		echo '<div class="chf-initiative-body">';
		// the_content filter applies wpautop, embeds, etc.
		echo apply_filters( 'the_content', $content );
		echo '</div>';
	}

	// ---- Reports & Resources ----
	if ( $has_reports ) {
		echo '<div class="chf-initiative-reports">';
		echo '<h2 class="chf-initiative-reports__heading">Reports &amp; Resources</h2>';
		echo '<ul class="chf-initiative-reports__list">';
		foreach ( $reports as $row ) {
			$title = isset( $row['title'] ) ? (string) $row['title'] : '';
			$year  = isset( $row['year'] ) ? (string) $row['year'] : '';
			$pub_id = is_array( $row ) && isset( $row['publication'] )
				? ( is_object( $row['publication'] ) ? $row['publication']->ID : (int) $row['publication'] )
				: 0;
			$file = isset( $row['file'] ) ? $row['file'] : null;
			$file_url = '';
			if ( is_array( $file ) && ! empty( $file['url'] ) ) {
				$file_url = $file['url'];
			} elseif ( is_numeric( $file ) ) {
				$file_url = wp_get_attachment_url( (int) $file );
			}

			$href = $file_url ?: ( $pub_id ? get_permalink( $pub_id ) : '' );
			if ( ! $href ) {
				continue;
			}

			echo '<li class="chf-initiative-reports__item">';
			echo '<a class="chf-initiative-reports__link" href="' . esc_url( $href ) . '"';
			if ( $file_url ) {
				echo ' target="_blank" rel="noopener"';
			}
			echo '>';
			echo '<span class="chf-initiative-reports__title">' . esc_html( $title ) . '</span>';
			if ( $year ) {
				echo '<span class="chf-initiative-reports__year">' . esc_html( $year ) . '</span>';
			}
			echo '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
	}

	echo '</div>';
	echo '</section>';
}
add_action( 'elementor/theme/after_do_location', 'chf_initiative_render_extras' );

/**
 * Enqueue inline CSS for the initiative extras section.
 *
 * Kept inline (vs a separate file) because it's small and only renders on
 * one post type. Uses CSS variables defined in design-system.css.
 *
 * @since 5.1.1
 * @return void
 */
function chf_initiative_extras_inline_css() {
	if ( ! is_singular( 'chf_initiative' ) ) {
		return;
	}

	$css = <<<'CSS'
.chf-initiative-extras {
  background: #fff;
  padding: clamp(48px, 8vh, 96px) 0;
}
.chf-initiative-extras__container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 clamp(20px, 5vw, 48px);
  display: grid;
  gap: clamp(48px, 6vh, 80px);
}

/* Bento stats grid */
.chf-initiative-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
}
.chf-bento-card {
  background: var(--navy-deep, #0f1a2e);
  color: #fff;
  padding: 32px 24px;
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 200px;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.chf-bento-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.18);
}
.chf-bento-card__number {
  font-family: var(--font-display, 'Merriweather', Georgia, serif);
  font-size: clamp(40px, 5vw, 64px);
  font-weight: 700;
  line-height: 1;
  color: var(--green, #56B84A);
  margin-bottom: 16px;
}
.chf-bento-card__label {
  font-size: 15px;
  line-height: 1.45;
  color: rgba(255, 255, 255, 0.92);
}
@media (min-width: 1024px) {
  .chf-initiative-stats {
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  }
  .chf-bento-card {
    min-height: 220px;
  }
}

/* Body content */
.chf-initiative-body {
  font-size: 17px;
  line-height: 1.7;
  color: var(--ink, #1B2A4A);
}
.chf-initiative-body h2 {
  font-family: var(--font-display, 'Merriweather', Georgia, serif);
  font-size: clamp(28px, 3vw, 36px);
  margin: 48px 0 16px;
  color: var(--navy, #1B2A4A);
}
.chf-initiative-body h3 {
  font-size: clamp(22px, 2.4vw, 26px);
  margin: 32px 0 12px;
  color: var(--navy, #1B2A4A);
}
.chf-initiative-body p { margin: 0 0 18px; }
.chf-initiative-body a { color: var(--green-dark, #3d8f35); text-decoration: underline; }
.chf-initiative-body a:hover { color: var(--green, #56B84A); }

/* Reports & resources */
.chf-initiative-reports {
  border-top: 1px solid rgba(27, 42, 74, 0.12);
  padding-top: 32px;
}
.chf-initiative-reports__heading {
  font-family: var(--font-display, 'Merriweather', Georgia, serif);
  font-size: 24px;
  margin: 0 0 24px;
  color: var(--navy, #1B2A4A);
}
.chf-initiative-reports__list {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: 12px;
}
.chf-initiative-reports__link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 18px 20px;
  background: #f6f7fa;
  border-radius: 8px;
  text-decoration: none;
  color: var(--navy, #1B2A4A);
  font-weight: 500;
  transition: background 0.2s ease;
}
.chf-initiative-reports__link:hover {
  background: #eef0f5;
  color: var(--green-dark, #3d8f35);
}
.chf-initiative-reports__year {
  font-variant-numeric: tabular-nums;
  color: rgba(27, 42, 74, 0.6);
  font-weight: 400;
  font-size: 14px;
  flex-shrink: 0;
}
CSS;

	wp_register_style( 'chf-initiative-extras', false, array( 'chf-design-system' ), CHF_VERSION );
	wp_enqueue_style( 'chf-initiative-extras' );
	wp_add_inline_style( 'chf-initiative-extras', $css );
}
add_action( 'wp_enqueue_scripts', 'chf_initiative_extras_inline_css', 20 );
