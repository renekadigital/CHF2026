<?php
/**
 * Extended ACF Field Groups
 *
 * Adds field groups beyond the base Hero/Event groups in
 * inc/custom-post-types.php. Covers:
 *   - Initiative extended (parent, partners, reports, key dates, stats)
 *   - Event extended (end date, status, agenda, speakers, gallery, recap)
 *   - Person fields
 *   - Supporter fields
 *   - Publication fields
 *   - News post additions (source, external URL, related initiative)
 *
 * Drop into chf-theme/inc/ and require from functions.php after
 * inc/custom-post-types.php and inc/custom-post-types-extended.php.
 *
 * @package CHF
 * @since   5.1.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register extended ACF field groups.
 *
 * @since 5.1.0
 * @return void
 */
function chf_register_extended_acf_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/**
	 * --------------------------------------------------------------------------
	 * Initiative — Extended Fields
	 * --------------------------------------------------------------------------
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_chf_initiative_extended',
		'title'    => 'Initiative — Extended',
		'fields'   => array(
			array(
				'key'           => 'field_chf_init_parent',
				'label'         => 'Parent Initiative',
				'name'          => 'parent_initiative',
				'type'          => 'post_object',
				'post_type'     => array( 'chf_initiative' ),
				'return_format' => 'id',
				'allow_null'    => 1,
				'instructions'  => 'For sub-initiatives, link to the pillar (e.g., Hydrogen Hub → Energy).',
			),
			array(
				'key'           => 'field_chf_init_summary',
				'label'         => 'Summary',
				'name'          => 'summary',
				'type'          => 'textarea',
				'rows'          => 3,
				'instructions'  => 'One-paragraph summary used in cards and meta description fallback.',
			),
			array(
				'key'           => 'field_chf_init_hero_video',
				'label'         => 'Hero Video URL',
				'name'          => 'hero_video_url',
				'type'          => 'url',
				'instructions'  => 'Optional. YouTube or Vimeo URL — overrides hero image when set.',
			),
			array(
				'key'           => 'field_chf_init_partners',
				'label'         => 'Partners',
				'name'          => 'partners',
				'type'          => 'relationship',
				'post_type'     => array( 'chf_supporter' ),
				'return_format' => 'id',
				'instructions'  => 'Select supporter organizations associated with this initiative.',
			),
			array(
				'key'           => 'field_chf_init_reports',
				'label'         => 'Reports & Resources',
				'name'          => 'reports',
				'type'          => 'repeater',
				'button_label'  => 'Add Report',
				'layout'        => 'block',
				'sub_fields'    => array(
					array(
						'key'   => 'field_chf_init_report_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'           => 'field_chf_init_report_pub',
						'label'         => 'Publication',
						'name'          => 'publication',
						'type'          => 'post_object',
						'post_type'     => array( 'chf_publication' ),
						'return_format' => 'id',
						'instructions'  => 'Link to a Publication CPT entry (preferred) or use the file field below.',
					),
					array(
						'key'           => 'field_chf_init_report_file',
						'label'         => 'File (fallback)',
						'name'          => 'file',
						'type'          => 'file',
						'return_format' => 'array',
						'instructions'  => 'Use only if the document has no Publication CPT entry.',
					),
					array(
						'key'   => 'field_chf_init_report_year',
						'label' => 'Year',
						'name'  => 'year',
						'type'  => 'number',
						'min'   => 2000,
						'max'   => 2050,
					),
				),
			),
			array(
				'key'           => 'field_chf_init_key_dates',
				'label'         => 'Key Dates',
				'name'          => 'key_dates',
				'type'          => 'repeater',
				'button_label'  => 'Add Date',
				'layout'        => 'table',
				'sub_fields'    => array(
					array(
						'key'   => 'field_chf_init_date_label',
						'label' => 'Label',
						'name'  => 'label',
						'type'  => 'text',
					),
					array(
						'key'            => 'field_chf_init_date_date',
						'label'          => 'Date',
						'name'           => 'date',
						'type'           => 'date_picker',
						'display_format' => 'F j, Y',
						'return_format'  => 'Ymd',
					),
				),
			),
			array(
				'key'          => 'field_chf_init_stats',
				'label'        => 'Headline Stats (Bento Grid)',
				'name'         => 'stats',
				'type'         => 'repeater',
				'instructions' => 'Headline data points for the initiative. Rendered as a bento-grid section on the Initiative Single template.',
				'button_label' => 'Add Stat',
				'layout'       => 'block',
				'sub_fields'   => array(
					array(
						'key'   => 'field_chf_init_stat_number',
						'label' => 'Stat Number',
						'name'  => 'stat_number',
						'type'  => 'text',
						'instructions' => 'The headline figure (e.g., "1/3", "$8B", "100+", "70").',
					),
					array(
						'key'   => 'field_chf_init_stat_label',
						'label' => 'Stat Label',
						'name'  => 'stat_label',
						'type'  => 'textarea',
						'rows'  => 2,
						'instructions' => 'The supporting description (e.g., "Of the nation\'s hydrogen produced and consumed in the Houston region").',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_initiative',
				),
			),
		),
		'style'    => 'default',
		'position' => 'normal',
	) );

	/**
	 * --------------------------------------------------------------------------
	 * Event — Extended Fields
	 * --------------------------------------------------------------------------
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_chf_event_extended',
		'title'    => 'Event — Extended',
		'fields'   => array(
			array(
				'key'            => 'field_chf_event_end_date',
				'label'          => 'End Date',
				'name'           => 'end_date',
				'type'           => 'date_picker',
				'display_format' => 'F j, Y',
				'return_format'  => 'Ymd',
				'instructions'   => 'For multi-day events. Leave blank for single-day.',
			),
			array(
				'key'      => 'field_chf_event_status',
				'label'    => 'Registration Status',
				'name'     => 'registration_status',
				'type'     => 'select',
				'choices'  => array(
					'open'      => 'Open',
					'closed'    => 'Closed',
					'sold-out'  => 'Sold Out',
					'past'      => 'Past',
				),
				'default_value' => 'open',
				'allow_null'    => 0,
				'required'      => 1,
			),
			array(
				'key'   => 'field_chf_event_loc_addr',
				'label' => 'Location Address',
				'name'  => 'location_address',
				'type'  => 'textarea',
				'rows'  => 2,
				'instructions' => 'Full street address (used for Event schema and map embed).',
			),
			array(
				'key'           => 'field_chf_event_related_init',
				'label'         => 'Related Initiative',
				'name'          => 'related_initiative',
				'type'          => 'post_object',
				'post_type'     => array( 'chf_initiative' ),
				'return_format' => 'id',
				'allow_null'    => 1,
			),
			array(
				'key'           => 'field_chf_event_speakers',
				'label'         => 'Speakers',
				'name'          => 'speakers',
				'type'          => 'relationship',
				'post_type'     => array( 'chf_person' ),
				'return_format' => 'id',
				'instructions'  => 'Select speakers from the Persons CPT.',
			),
			array(
				'key'           => 'field_chf_event_agenda',
				'label'         => 'Agenda',
				'name'          => 'agenda',
				'type'          => 'repeater',
				'button_label'  => 'Add Agenda Item',
				'layout'        => 'block',
				'sub_fields'    => array(
					array(
						'key'   => 'field_chf_event_agenda_time',
						'label' => 'Time',
						'name'  => 'time',
						'type'  => 'text',
						'instructions' => 'e.g. "9:00 AM"',
					),
					array(
						'key'   => 'field_chf_event_agenda_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'   => 'field_chf_event_agenda_desc',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 2,
					),
					array(
						'key'           => 'field_chf_event_agenda_speakers',
						'label'         => 'Speaker(s)',
						'name'          => 'speakers',
						'type'          => 'relationship',
						'post_type'     => array( 'chf_person' ),
						'return_format' => 'id',
					),
				),
			),
			array(
				'key'           => 'field_chf_event_gallery',
				'label'         => 'Gallery',
				'name'          => 'gallery',
				'type'          => 'gallery',
				'return_format' => 'array',
				'instructions'  => 'Photo gallery (typically populated for past events).',
			),
			array(
				'key'   => 'field_chf_event_recap',
				'label' => 'Post-Event Recap',
				'name'  => 'post_event_recap',
				'type'  => 'wysiwyg',
				'tabs'  => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'instructions' => 'Shown on past events. Conditional rendering happens in template logic.',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_chf_event_status',
							'operator' => '==',
							'value'    => 'past',
						),
					),
				),
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

	/**
	 * --------------------------------------------------------------------------
	 * Person — Profile Fields
	 * --------------------------------------------------------------------------
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_chf_person',
		'title'    => 'Person Profile',
		'fields'   => array(
			array(
				'key'   => 'field_chf_person_role',
				'label' => 'Title or Role',
				'name'  => 'title_or_role',
				'type'  => 'text',
				'instructions' => 'e.g. "President & CEO" or "Board Chair".',
			),
			array(
				'key'   => 'field_chf_person_org',
				'label' => 'Organization',
				'name'  => 'organization',
				'type'  => 'text',
				'instructions' => 'Used for board members and external speakers.',
			),
			array(
				'key'   => 'field_chf_person_bio',
				'label' => 'Bio',
				'name'  => 'bio',
				'type'  => 'wysiwyg',
				'tabs'  => 'visual',
				'toolbar' => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'           => 'field_chf_person_headshot',
				'label'         => 'Headshot',
				'name'          => 'headshot',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => 'Square crop preferred (1:1, min 600×600).',
			),
			array(
				'key'   => 'field_chf_person_linkedin',
				'label' => 'LinkedIn URL',
				'name'  => 'linkedin_url',
				'type'  => 'url',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_person',
				),
			),
		),
		'style'    => 'default',
		'position' => 'normal',
	) );

	/**
	 * --------------------------------------------------------------------------
	 * Supporter — Logo & Identity
	 * --------------------------------------------------------------------------
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_chf_supporter',
		'title'    => 'Supporter',
		'fields'   => array(
			array(
				'key'           => 'field_chf_supporter_logo_color',
				'label'         => 'Logo (Color)',
				'name'          => 'logo_color',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'required'      => 1,
				'instructions'  => 'Full-color logo. SVG preferred; PNG with transparency acceptable.',
			),
			array(
				'key'           => 'field_chf_supporter_logo_white',
				'label'         => 'Logo (White / Reverse)',
				'name'          => 'logo_white',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => 'For dark backgrounds. Optional but recommended.',
			),
			array(
				'key'   => 'field_chf_supporter_url',
				'label' => 'Website URL',
				'name'  => 'website_url',
				'type'  => 'url',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_supporter',
				),
			),
		),
		'style'    => 'default',
		'position' => 'normal',
	) );

	/**
	 * --------------------------------------------------------------------------
	 * Publication — Document Metadata
	 * --------------------------------------------------------------------------
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_chf_publication',
		'title'    => 'Publication',
		'fields'   => array(
			array(
				'key'   => 'field_chf_pub_year',
				'label' => 'Year',
				'name'  => 'year',
				'type'  => 'number',
				'min'   => 2000,
				'max'   => 2050,
				'required' => 1,
			),
			array(
				'key'           => 'field_chf_pub_cover',
				'label'         => 'Cover Image',
				'name'          => 'cover_image',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'instructions'  => 'Used in publication card grids. Optional but recommended.',
			),
			array(
				'key'           => 'field_chf_pub_file',
				'label'         => 'PDF File',
				'name'          => 'pdf_file',
				'type'          => 'file',
				'return_format' => 'array',
				'mime_types'    => 'pdf',
				'required'      => 1,
			),
			array(
				'key'   => 'field_chf_pub_summary',
				'label' => 'Summary',
				'name'  => 'summary',
				'type'  => 'textarea',
				'rows'  => 3,
			),
			array(
				'key'           => 'field_chf_pub_related_init',
				'label'         => 'Related Initiative',
				'name'          => 'related_initiative',
				'type'          => 'post_object',
				'post_type'     => array( 'chf_initiative' ),
				'return_format' => 'id',
				'allow_null'    => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'chf_publication',
				),
			),
		),
		'style'    => 'default',
		'position' => 'normal',
	) );

	/**
	 * --------------------------------------------------------------------------
	 * News Post — Additions
	 * --------------------------------------------------------------------------
	 */
	acf_add_local_field_group( array(
		'key'      => 'group_chf_post_additions',
		'title'    => 'News Article Details',
		'fields'   => array(
			array(
				'key'   => 'field_chf_post_source',
				'label' => 'Source Publication',
				'name'  => 'source_publication',
				'type'  => 'text',
				'instructions' => 'For syndicated news (e.g. "Houston Chronicle"). Leave blank for first-party posts.',
			),
			array(
				'key'   => 'field_chf_post_external',
				'label' => 'External URL',
				'name'  => 'external_url',
				'type'  => 'url',
				'instructions' => 'For "in the news" entries that link off-site.',
			),
			array(
				'key'           => 'field_chf_post_related_init',
				'label'         => 'Related Initiative',
				'name'          => 'related_initiative',
				'type'          => 'post_object',
				'post_type'     => array( 'chf_initiative' ),
				'return_format' => 'id',
				'allow_null'    => 1,
			),
			array(
				'key'   => 'field_chf_post_hero_credit',
				'label' => 'Hero Image Credit',
				'name'  => 'hero_image_credit',
				'type'  => 'text',
				'instructions' => 'Photographer / source credit shown under the hero image.',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		),
		'style'    => 'default',
		'position' => 'normal',
	) );
}
add_action( 'acf/init', 'chf_register_extended_acf_fields' );
