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
	if( function_exists('acf_register_block_type') ) {
		$dir_path = $GLOBALS['xten-sections-dir'];
		// Register a testimonial block.
		$section_name = 'section-hero';
		function get_icon_src($section_name) {
			$file = $dir_path . '/img/icon-' . $section_name . '.svg';
			if ( file_exists($file) ) :
				$section_name = include $file;
			else :
				$file = $dir_path . '/img/icon-placeholder.svg';
			endif;
			return include $file;
		}
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('Hero Section'),
				'description'       => __('Hero Section - Normally the first section on a page.'),
				'icon' => array(
					// Specifying a background color to appear with the icon e.g.: in the inserter.
					'background' => '#7e70af',
					// Specifying a color for the icon (optional: if not set, a readable color will be automatically defined)
					'foreground' => '#fff',
					// Specifying a dashicon for the block
					'src' => get_icon_src($section_name),
				),
				'render_template'   => 'xten-sections/' . $section_name . '/' . $section_name . '.php',
				'keywords'          => array('quote', 'mention', 'cite'),
				'category'          => 'formatting',
				'render_template'   => get_template_directory() . '/template-parts/blocks/testimonial/testimonial.php',
				'enqueue_style'     => get_template_directory_uri() . '/template-parts/blocks/testimonial/testimonial.css',
				'enqueue_style'     => 'xten-' . $section_name . '-css',
				'enqueue_script'    => 'xten-' . $section_name . '-js',
			)
		);
	}
}
add_action('acf/init', 'xten_acf_blocks_init');
