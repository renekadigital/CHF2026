# Developer Reference

For developers extending, debugging, or handing off the CHF theme. Assumes
working knowledge of WordPress, PHP 8.2+, and Elementor Pro.

---

## 1. Development setup

### Local environment

**Recommended:** Local by Flywheel, DevKinsta, or Docker (LocalWP is easiest).

### Requirements
- PHP 8.2+
- MySQL 5.7+ or MariaDB 10.3+
- WordPress 6.4+
- Node.js (only needed if building custom widgets or compile steps — CHF ships
  no build process currently)

### Setup steps
1. Install WordPress locally
2. Install **Hello Elementor** theme (required parent)
3. Copy `chf-theme/` into `wp-content/themes/chf-theme/`
4. Install + activate **Elementor Pro**, **ACF Pro**
5. Activate the CHF child theme
6. Import content: Tools → Import → WordPress → upload `chf-content-import.xml`
7. Import Elementor templates: Templates → Saved Templates → Import each of the
   10 JSON files in `elementor-templates/`
8. Set Theme Builder conditions per INSTALL-GUIDE.md

---

## 2. Coding standards

### PHP
- Follow **WordPress PHP Coding Standards** ([link](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/))
- 4-space indentation
- Opening braces on same line for functions, next line for classes
- Prefix all functions with `chf_`
- Prefix all classes with `CHF_`
- Prefix all hooks with `chf_`
- Never output HTML directly — use template functions or return strings
- Escape all output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- Sanitize all input: `sanitize_text_field()`, `wp_kses()`, `absint()`
- Use `nonce` verification on all forms + AJAX
- Document every function with a proper docblock

### Example function

```php
/**
 * Retrieve the formatted event date for a given post.
 *
 * Reads the ACF `event_date` field (stored as Ymd) and returns it as
 * a human-readable string.
 *
 * @since 5.0.0
 *
 * @param int $post_id The post ID. Defaults to current post.
 * @return string Formatted date or empty string on failure.
 */
function chf_get_event_date( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$raw     = function_exists( 'get_field' ) ? get_field( 'event_date', $post_id ) : '';

	if ( empty( $raw ) ) {
		return '';
	}

	$date = DateTime::createFromFormat( 'Ymd', $raw );
	return $date ? $date->format( 'F j, Y' ) : '';
}
```

### CSS
- Use CSS custom properties (tokens) from `style.css` — never hardcode hex values
- Mobile-first media queries
- BEM-ish naming for custom classes (`.component__element--modifier`)
- No `!important` anywhere
- Max file size: keep individual CSS files under 50 KB

### JavaScript
- Vanilla ES2022+ only — no jQuery
- IIFE pattern for scoping
- Use `const` by default, `let` when reassignment required, never `var`
- Document with JSDoc
- Use `IntersectionObserver` over scroll listeners
- Respect `prefers-reduced-motion`

---

## 3. Theme hooks reference

### Custom action hooks

None currently defined. If you add one, use the `chf_` prefix:
```php
do_action( 'chf_before_hero', $post );
```

### Filters

None currently defined. If you add one:
```php
$value = apply_filters( 'chf_hero_subtitle', $value, $post );
```

### WordPress hooks used by the theme

| Hook | File | Function | Purpose |
|---|---|---|---|
| `after_setup_theme` | functions.php | `chf_setup` | Theme support, nav menus, image sizes |
| `wp_enqueue_scripts` | functions.php | `chf_enqueue_assets` | Styles and scripts |
| `init` | custom-post-types.php | `chf_register_post_types` | Register CPTs |
| `init` | custom-post-types.php | `chf_register_taxonomies` | Register taxonomies |
| `acf/init` | custom-post-types.php | `chf_register_acf_fields` | Register ACF field groups |
| `after_switch_theme` | custom-post-types.php | `chf_flush_rewrites` | Flush on activation |
| `send_headers` | security.php | `chf_security_headers` | HTTP security headers |
| `init` | security.php | `chf_disable_xmlrpc` | Disable XML-RPC |
| `wp_head` | security.php | `chf_remove_version` | Strip generator tag |
| `elementor/elements/categories_registered` | elementor-setup.php | `chf_add_widget_category` | Add "CHF Widgets" category |
| `elementor/dynamic_tags/register` | elementor-setup.php | `chf_register_dynamic_tags` | Register the 4 dynamic tags |
| `elementor/theme/register_locations` | elementor-setup.php | `chf_register_elementor_locations` | Enable Theme Builder locations |
| `after_switch_theme` | elementor-setup.php | `chf_set_elementor_defaults` | Global colors/fonts on activation |

---

## 4. ACF field API

### Reading Hero fields

```php
// In a template file or Elementor shortcode
$eyebrow      = get_field( 'hero_eyebrow' );
$highlight    = get_field( 'hero_highlight_word' );
$subtitle     = get_field( 'hero_subtitle' );
$bg_image     = get_field( 'hero_background_image' ); // returns array

if ( $bg_image ) {
    $url = $bg_image['url'];
    $alt = $bg_image['alt'];
}
```

### Reading Event fields

```php
$date         = get_field( 'event_date' );          // Ymd string
$time         = get_field( 'event_time' );          // e.g., "6:00 PM – 9:00 PM CST"
$location     = get_field( 'event_location' );
$reg_url      = get_field( 'event_registration_url' );

// Format the date
$date_obj = DateTime::createFromFormat( 'Ymd', $date );
$formatted = $date_obj ? $date_obj->format( 'F j, Y' ) : '';
```

### Programmatically updating ACF fields

```php
update_field( 'hero_eyebrow', 'NEW VALUE', $post_id );
update_field( 'event_date', '20260615', $post_id ); // Ymd format
```

### Field group locations

Defined in `inc/custom-post-types.php` via `acf_add_local_field_group()`. Do
NOT re-create these in the ACF admin UI — that will create duplicates and
precedence conflicts.

To add a new field group, add another `acf_add_local_field_group()` call in
that file, following the same pattern.

---

## 5. Adding a new Custom Post Type

Use this pattern in `inc/custom-post-types.php`:

```php
/**
 * Register the "CHF Report" custom post type.
 */
function chf_register_report_cpt() {
	$labels = array(
		'name'          => _x( 'Reports', 'post type general name', 'chf' ),
		'singular_name' => _x( 'Report', 'post type singular name', 'chf' ),
		'menu_name'     => __( 'Reports', 'chf' ),
		'add_new_item'  => __( 'Add New Report', 'chf' ),
		'edit_item'     => __( 'Edit Report', 'chf' ),
		'view_item'     => __( 'View Report', 'chf' ),
		'search_items'  => __( 'Search Reports', 'chf' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'has_archive'         => 'reports',
		'rewrite'             => array( 'slug' => 'report', 'with_front' => false ),
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
		'menu_icon'           => 'dashicons-media-document',
		'show_in_rest'        => true,
		'menu_position'       => 25,
		'show_in_nav_menus'   => true,
		'exclude_from_search' => false,
	);

	register_post_type( 'chf_report', $args );
}
add_action( 'init', 'chf_register_report_cpt' );
```

**After adding any new post type:**
1. Visit Settings → Permalinks → Save Changes (to flush rewrite rules)
2. Add an Elementor Theme Builder template for the single post view
3. Set display conditions on that template

---

## 6. Adding a new ACF field group

```php
function chf_register_report_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'    => 'group_chf_report',
		'title'  => 'Report Details',
		'fields' => array(
			array(
				'key'   => 'field_chf_report_year',
				'label' => 'Report Year',
				'name'  => 'report_year',
				'type'  => 'number',
				'min'   => 2020,
				'max'   => 2050,
			),
			array(
				'key'   => 'field_chf_report_pdf',
				'label' => 'Report PDF',
				'name'  => 'report_pdf',
				'type'  => 'file',
				'return_format' => 'array',
				'mime_types'    => 'pdf',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_report',
				),
			),
		),
	) );
}
add_action( 'acf/init', 'chf_register_report_fields' );
```

---

## 7. Adding a new Elementor Dynamic Tag

To expose any ACF field as a dynamic source in the Elementor editor:

```php
function chf_register_report_dynamic_tag( $dynamic_tags_manager ) {

	$dynamic_tags_manager->register_group( 'chf-fields', array(
		'title' => __( 'CHF Fields', 'chf' ),
	) );

	if ( ! class_exists( 'CHF_Report_Year_Tag' ) ) :
	class CHF_Report_Year_Tag extends \Elementor\Core\DynamicTags\Tag {

		public function get_name() {
			return 'chf-report-year';
		}

		public function get_title() {
			return __( 'Report Year', 'chf' );
		}

		public function get_group() {
			return 'chf-fields';
		}

		public function get_categories() {
			return array( \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY );
		}

		public function render() {
			$year = function_exists( 'get_field' ) ? get_field( 'report_year' ) : '';
			echo esc_html( $year );
		}
	}
	endif;

	$dynamic_tags_manager->register( new CHF_Report_Year_Tag() );
}
add_action( 'elementor/dynamic_tags/register', 'chf_register_report_dynamic_tag' );
```

---

## 8. Adding a new stylesheet

If you need a stylesheet that only loads on certain pages:

```php
function chf_conditional_styles() {
	if ( is_singular( 'chf_report' ) ) {
		wp_enqueue_style(
			'chf-report',
			get_stylesheet_directory_uri() . '/assets/css/report.css',
			array( 'chf-design-system' ),
			CHF_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'chf_conditional_styles' );
```

Always declare dependencies (`array('chf-design-system')`) so WordPress loads
them in the right order.

---

## 9. Adding a widget to the "CHF Widgets" category

If you create a custom Elementor widget, add it to the CHF group for easy
finding in the editor panel:

```php
class My_Custom_Widget extends \Elementor\Widget_Base {

	public function get_categories() {
		return array( 'chf-widgets' );
	}

	// ... rest of widget
}
```

---

## 10. Database queries

### Always use `wpdb` with prepared statements

```php
global $wpdb;

// ❌ WRONG — SQL injection risk
$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE ID = $id" );

// ✅ RIGHT
$results = $wpdb->get_results( $wpdb->prepare(
    "SELECT * FROM {$wpdb->posts} WHERE ID = %d",
    $id
) );
```

### Use `WP_Query`, not raw SQL

```php
$query = new WP_Query( array(
	'post_type'      => 'chf_event',
	'posts_per_page' => 6,
	'meta_key'       => 'event_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => array(
		array(
			'key'     => 'event_date',
			'value'   => date( 'Ymd' ),
			'compare' => '>=',
		),
	),
) );
```

### Never forget `wp_reset_postdata()`

```php
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        // render
    }
    wp_reset_postdata(); // ← always
}
```

---

## 11. Debugging tools

### Query Monitor
Install during development. Shows:
- All SQL queries per page
- Hooks that ran
- HTTP requests made
- Memory usage
- Deprecated function calls

Activate only on staging — it's heavy for production.

### Debug Bar + extensions
Lighter than Query Monitor. Shows hooks, queries, cache, and constants.

### Xdebug
If running with Docker/Local, enable Xdebug for step-through debugging in
VS Code or PhpStorm. Worth the 20 minutes of setup.

### Error logs
Always check your `debug.log` before guessing at errors. Enable via:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```
Log lives at `wp-content/debug.log`.

---

## 12. Git workflow

The theme is versioned. Recommended workflow:

### Branches
- `main` — production, always deployable
- `develop` — integration branch
- `feature/xyz` — new work
- `fix/xyz` — bug fixes
- `hotfix/xyz` — emergency production fixes

### Commit message format
Follow Conventional Commits:
```
feat: add report CPT with ACF fields
fix: correct hero subtitle dynamic tag fallback
docs: update install guide slug mapping
refactor: consolidate counter selector
chore: bump theme version to 5.1.0
```

### Before committing
- [ ] Run `php -l` on changed PHP files (syntax check)
- [ ] Validate any JSON you edited with `jq`
- [ ] Test on local staging
- [ ] Update version in `style.css` for meaningful changes
- [ ] Document new hooks in this file

### Before deploying to production
- [ ] Full regression test on staging
- [ ] Database backup
- [ ] Files backup
- [ ] Deploy during low-traffic window
- [ ] Smoke test immediately after

---

## 13. Performance profiling

### Page speed

```bash
# Run Lighthouse CLI from terminal
npm install -g lighthouse
lighthouse https://yoursite.com --view
```

### Database queries

Enable Query Monitor, load a slow page, inspect the queries panel. Look for:
- Duplicate queries (N+1 problem)
- Queries > 100ms
- Missing indexes

### PHP profiling

Use Xdebug or Blackfire.io. Profile the slowest page, look for:
- Function calls taking > 50ms
- Memory spikes
- Unnecessary database calls in loops

---

## 14. Release checklist

When cutting a new theme release:

- [ ] Update `CHF_VERSION` constant in `functions.php`
- [ ] Update `Version:` header in `style.css`
- [ ] Update this docs packet if needed
- [ ] Tag the git commit: `git tag v5.1.0 && git push --tags`
- [ ] Create a zip of the theme: `zip -r chf-theme-v5.1.0.zip chf-theme/`
- [ ] Upload zip to client's private asset storage
- [ ] Notify client of the release + changelog
- [ ] Deploy to staging → test → deploy to production

---

## 15. Useful code snippets

### Fetch upcoming events only
```php
$upcoming = new WP_Query( array(
	'post_type'      => 'chf_event',
	'posts_per_page' => -1,
	'meta_key'       => 'event_date',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
	'meta_query'     => array(
		array(
			'key'     => 'event_date',
			'value'   => date( 'Ymd' ),
			'compare' => '>=',
		),
	),
) );
```

### Get initiatives by category
```php
$energy = new WP_Query( array(
	'post_type'     => 'chf_initiative',
	'tax_query'     => array(
		array(
			'taxonomy' => 'initiative_category',
			'field'    => 'slug',
			'terms'    => 'energy-climate',
		),
	),
) );
```

### Add body class for Elementor pages
```php
function chf_add_body_classes( $classes ) {
	if ( class_exists( 'Elementor\Plugin' ) ) {
		$document = \Elementor\Plugin::$instance->documents->get( get_the_ID() );
		if ( $document && $document->is_built_with_elementor() ) {
			$classes[] = 'chf-elementor-page';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'chf_add_body_classes' );
```

### Conditional enqueue only on homepage
```php
function chf_homepage_styles() {
	if ( is_front_page() ) {
		wp_enqueue_style(
			'chf-homepage',
			get_stylesheet_directory_uri() . '/assets/css/homepage.css',
			array(),
			CHF_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'chf_homepage_styles' );
```

---

## 16. Known issues / technical debt

None currently. The v5.0.0 QA audit cleared all identified issues. If you find
new issues, document them here.
