<?php
/**
 * File used for Registering Section Blocks
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten
 */


function xten_acf_blocks_init() {

	// Check function exists.
	if( function_exists('acf_register_block_type') ) :
		
		
		// Hero Section - xten-section-hero.
		global $section_name;
		$handle       = 'hero';
		$section_name = 'xten-section-' . $handle;
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('Hero Section'),
				'description'       => __('Hero Section - Normally the first section on a page.'),
				'icon'              => xten_get_icon($section_name),
				'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
				'keywords'          => array(
																'xten',
																'section',
																'hero',
																'billboard',
																'first',
																'main'
															),
				'supports'          => array(
					'anchor' => true,
				),
				'category'          => 'xten-sections',
				// 'enqueue_assets'    => function ($block) {
				// 													$section_name = str_replace( 'acf/', '', $block['name'] );
				// 													xten_enqueue_assets( $section_name );
				// 												}
			)
		);

		// Post Archive - xten-section-wysiwyg.
		$handle       = 'post-archive';
		$section_name = 'xten-section-' . $handle;
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('Post Archive'),
				'description'       => __('Post Archive - A collection of posts. Choose from type of post, category of post, or cherry-pick.'),
				'icon'              => xten_get_icon($section_name),
				'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
				'keywords'          => array(
																'xten',
																'section',
																'post',
																'archive',
																'collection',
																'blog'
															),
				'supports'          => array(
					'anchor' => true,
				),
				'category'          => 'xten-sections',
				'enqueue_assets'    => function ($block) {
																	$section_name = str_replace( 'acf/', '', $block['name'] );
																	xten_enqueue_assets( $section_name );
																}
			)
		);

		// WYSIWYG - xten-section-wysiwyg.
		$handle       = 'wysiwyg';
		$section_name = 'xten-section-' . $handle;
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('WYSIWYG'),
				'description'       => __('What You See Is What You Get - Any HTML can be rendered here, with the added bonus of XTen Sections Configuration.'),
				'icon'              => xten_get_icon($section_name),
				'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
				'keywords'          => array(
																'xten',
																'section',
																'wysiwyg',
																'html',
																'custom',
															),
				'supports'          => array(
					'anchor' => true,
				),
				'category'          => 'xten-sections',
				'enqueue_assets'    => function ($block) {
																	$section_name = str_replace( 'acf/', '', $block['name'] );
																	xten_enqueue_assets( $section_name );
																}
			)
		);

		// Image Gallery - xten-section-image-gallery.
		$handle       = 'image-gallery';
		$section_name = 'xten-section-' . $handle;
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('Image Gallery'),
				'description'       => __('Image Gallery Block with Slider and Lightbox (fancybox) Functionality.'),
				'icon'              => xten_get_icon($section_name),
				'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
				'keywords'          => array(
																'xten',
																'section',
																'image',
																'gallery',
																'slider',
																'lightbox',
															),
				'supports'          => array(
					'anchor' => true,
				),
				'category'          => 'xten-sections',
				// 'enqueue_assets'    => function ($block) {
				// 													$section_name = str_replace( 'acf/', '', $block['name'] );
				// 													xten_enqueue_assets( $section_name );
				// 												}
			)
		);

		// Check to see if Widget Google Reviews Plugin is Activated.
		$plugin_file = 'widget-google-reviews/grw.php';
		if ( is_plugin_active( $plugin_file ) ) :
			// Google Review Slider Section - xten-section-google-review-slider.
			$handle       = 'google-review-slider';
			$section_name = 'xten-section-' . $handle;
			$icon         = null;
			if ( function_exists( 'xten_get_icon' ) ) :
				$icon = xten_get_icon( $section_name );
			endif;
			acf_register_block_type(
				array(
					'name'              => $section_name,
					'title'             => __('Google Review Slider Section'),
					'description'       => __('Section with Google Review Slider'),
					'icon'              => $icon,
					'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
					'keywords'          => array(
																	'xten',
																	'section',
																	'google',
																	'review',
																	'slider',
																),
					'supports'          => array(
						'anchor' => true,
					),
					'category'          => 'xten-sections',
					// 'enqueue_assets'    => function ($block) {
					// 													$section_name = str_replace( 'acf/', '', $block['name'] );
					// 													xten_enqueue_assets( $section_name );
					// 												}
				)
			);
		endif; // endif ( is_plugin_active( $plugin_file ) ) :

	// Archive - xten-section-archive.
	$handle       = 'archive';
	$section_name = 'xten-section-' . $handle;
	acf_register_block_type(
		array(
			'name'              => $section_name,
			'title'             => __('Archive Section'),
			'description'       => __('Archive Block which Renders any type of archive.'),
			'icon'              => xten_get_icon($section_name),
			'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
			'keywords'          => array(
															'xten',
															'section',
															'archive',
															'post',
															'taxonomy',
															'category',
														),
			'supports'          => array(
				'anchor' => true,
			),
			'category'          => 'xten-sections',
			// 'enqueue_assets'    => function ($block) {
			// 													$section_name = str_replace( 'acf/', '', $block['name'] );
			// 													xten_enqueue_assets( $section_name );
			// 												}
		)
	);

	endif; // endif( function_exists('acf_register_block_type') ) :
}
add_action('acf/init', 'xten_acf_blocks_init');
