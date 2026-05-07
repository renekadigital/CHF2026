<?php
/**
 * Initiative Single Render
 *
 * The imported Theme Builder Initiative Single template was authored as a
 * static design with hardcoded placeholder text widgets — it does not have
 * widgets bound to ACF fields. This module fills the data gap by rendering
 * Bento stats + post body + Reports + Related Initiatives below the
 * Theme Builder template via the elementor/theme/after_do_single action.
 *
 * The placeholder icon-list widgets in the template are stripped via
 * patch-templates.php so the duplicate sidebar doesn't confuse readers.
 *
 * @package CHF
 * @since   5.1.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render bento + body + reports + related sections below the Theme Builder
 * Single template for chf_initiative posts.
 *
 * @since 5.1.1
 * @return void
 */
function chf_initiative_render_extras() {
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

	$related = chf_get_related_initiatives( $post_id );
	$has_related = ! empty( $related );

	if ( ! $has_stats && ! $has_reports && ! $has_content && ! $has_related ) {
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
		echo apply_filters( 'the_content', $content );
		echo '</div>';
	}

	// ---- Two-column sidebar: Reports + Related ----
	if ( $has_reports || $has_related ) {
		echo '<div class="chf-initiative-cols">';

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
				if ( ! $href || ! $title ) {
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

		if ( $has_related ) {
			echo '<div class="chf-initiative-related">';
			echo '<h2 class="chf-initiative-related__heading">Related Initiatives</h2>';
			echo '<ul class="chf-initiative-related__list">';
			foreach ( $related as $rel_post ) {
				echo '<li class="chf-initiative-related__item">';
				echo '<a class="chf-initiative-related__link" href="' . esc_url( get_permalink( $rel_post ) ) . '">';
				echo esc_html( get_the_title( $rel_post ) );
				echo '</a></li>';
			}
			echo '</ul>';
			echo '</div>';
		}

		echo '</div>'; // .chf-initiative-cols
	}

	echo '</div>';
	echo '</section>';
}
add_action( 'elementor/theme/after_do_single', 'chf_initiative_render_extras' );

/**
 * Get related chf_initiative posts for the sidebar.
 *
 * Logic:
 *   - On a pillar (energy/health/immigration): list its sub-initiatives
 *   - On a sub-initiative: list siblings (other subs of same parent)
 *   - On a standalone (vision-2050/driving-future): list the 3 pillars
 *
 * @since 5.1.1
 *
 * @param int $post_id Current post ID.
 * @return WP_Post[] Up to 8 related initiatives.
 */
function chf_get_related_initiatives( $post_id ) {
	$slug = get_post_field( 'post_name', $post_id );
	$pillar_slugs = [ 'energy', 'health', 'immigration' ];
	$is_pillar = in_array( $slug, $pillar_slugs, true );

	$parent_id = (int) get_post_meta( $post_id, 'parent_initiative', true );

	$args = [
		'post_type'      => 'chf_initiative',
		'posts_per_page' => 8,
		'post_status'    => 'publish',
		'orderby'        => 'title',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	];

	if ( $is_pillar ) {
		$args['meta_key']   = 'parent_initiative';
		$args['meta_value'] = (string) $post_id;
	} elseif ( $parent_id ) {
		$args['meta_key']     = 'parent_initiative';
		$args['meta_value']   = (string) $parent_id;
		$args['post__not_in'] = [ $post_id ];
	} else {
		$ids = array_filter( array_map( function ( $s ) {
			$p = get_page_by_path( $s, OBJECT, 'chf_initiative' );
			return $p ? $p->ID : null;
		}, $pillar_slugs ) );
		if ( empty( $ids ) ) {
			return [];
		}
		$args['post__in'] = $ids;
	}

	$q = new WP_Query( $args );
	return $q->posts;
}

/**
 * Inline CSS for the initiative extras section.
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

/* Two-column section: Reports + Related */
.chf-initiative-cols {
  display: grid;
  grid-template-columns: 1fr;
  gap: 48px;
}
@media (min-width: 768px) {
  .chf-initiative-cols {
    grid-template-columns: 1fr 1fr;
  }
}

.chf-initiative-reports__heading,
.chf-initiative-related__heading {
  font-family: var(--font-display, 'Merriweather', Georgia, serif);
  font-size: 24px;
  margin: 0 0 24px;
  color: var(--navy, #1B2A4A);
  border-bottom: 1px solid rgba(27, 42, 74, 0.12);
  padding-bottom: 12px;
}
.chf-initiative-reports__list,
.chf-initiative-related__list {
  list-style: none;
  margin: 0;
  padding: 0;
  display: grid;
  gap: 12px;
}
.chf-initiative-reports__link,
.chf-initiative-related__link {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 16px 18px;
  background: #f6f7fa;
  border-radius: 8px;
  text-decoration: none;
  color: var(--navy, #1B2A4A);
  font-weight: 500;
  transition: background 0.2s ease, color 0.2s ease;
}
.chf-initiative-reports__link:hover,
.chf-initiative-related__link:hover {
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
.chf-initiative-related__link::after {
  content: "→";
  color: var(--green, #56B84A);
  font-weight: 700;
}
CSS;

	wp_register_style( 'chf-initiative-extras', false, [ 'chf-design-system' ], CHF_VERSION );
	wp_enqueue_style( 'chf-initiative-extras' );
	wp_add_inline_style( 'chf-initiative-extras', $css );
}
add_action( 'wp_enqueue_scripts', 'chf_initiative_extras_inline_css', 20 );
