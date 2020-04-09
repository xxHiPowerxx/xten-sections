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
		
		// Hero Section - xten-section-hero.
		global $section_name;
		$section_name = 'section-hero';
		$section_name = 'xten-' . $section_name;
		function get_icon_src($section_name) {
			$file = $GLOBALS['xten-sections-dir'] . 'assets/images/icon-' . $section_name . '.svg';
			if ( ! file_exists($file) ) :
				$file = $GLOBALS['xten-sections-dir'] . 'assets/images/icon-xten-placeholder.svg';
			endif;
			return $file;
		}
		$icon = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1312" height="1143.15" viewBox="0 0 1312 1143.15"><defs><radialGradient id="a" cx="656" cy="571.58" r="615.24" gradientTransform="translate(0 73.56) scale(1 0.87)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#404040"/><stop offset="0.2" stop-color="#2e4757"/><stop offset="0.53" stop-color="#155078"/><stop offset="0.81" stop-color="#06568d"/><stop offset="1" stop-color="#005894"/></radialGradient><linearGradient id="b" x1="292.99" y1="710.14" x2="292.99" y2="462.33" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fff"/><stop offset="0.2" stop-color="#e1e1e1"/><stop offset="0.53" stop-color="#a8a8a8"/><stop offset="0.98" stop-color="#fcfcfc"/><stop offset="1" stop-color="#fff"/></linearGradient><linearGradient id="c" x1="738.19" y1="710.14" x2="738.19" y2="462.33" xlink:href="#b"/><linearGradient id="d" x1="1075.36" y1="739.03" x2="1075.36" y2="404.13" xlink:href="#b"/></defs><title>icon-placeholder</title><rect width="1312" height="1143.15" rx="186.73" style="fill:url(#a)"/><polygon points="567.99 462.33 301.27 462.33 351.49 497.11 292.99 537.11 234.5 497.11 284.72 462.33 18 462.33 212.76 586.24 18 710.14 284.72 710.14 234.5 675.36 292.99 635.36 351.49 675.36 301.27 710.14 567.99 710.14 373.23 586.24 567.99 462.33" style="stroke:#404040;stroke-miterlimit:10;fill:url(#b)"/><path d="M807.55,462.33v189.9l100.84,57.91H568l113.4-57.91V516.07L568,462.33Z" style="stroke:#404040;stroke-miterlimit:10;fill:url(#c)"/><path d="M1216.61,465.45l-141.25-61.32L968.77,449.58l-30.6,13.28L857.8,572h-1.08L934.8,678l140.56,61L1215,679.22,1294,572Zm-82.08,152.79-59.17,25.69v-.35l-45.83-19.89-13.72-6-33.08-44.91h.46l25.91-35.19,8.14-11,13-5.62,45.16-19.26,59.84,26,7.71,10.47L1168,572.82Z" style="stroke:#404040;stroke-miterlimit:10;fill:url(#d)"/></svg>';
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('Hero Section'),
				'description'       => __('Hero Section - Normally the first section on a page.'),
				'icon'              => $icon,
				'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
				'keywords'          => array(
																'xten',
																'section',
																'hero',
																'billboard',
																'first',
																'main'
															),
				'category'          => 'xten-sections',
				'enqueue_assets'    => function ($block) {
																	$section_name = str_replace( 'acf/', '', $block['name'] );
																	xten_enqueue_assets( $section_name );
																}
			)
		);

		// Post Archive - xten-section-post-archive.
		$section_name = 'section-post-archive';
		$section_name = 'xten-' . $section_name;
		acf_register_block_type(
			array(
				'name'              => $section_name,
				'title'             => __('Post Archive'),
				'description'       => __('Post Archive - A collection of posts. Choose from type of post, category of post, or cherry-pick.'),
				'icon'              => $icon,
				'render_template'   => $GLOBALS['xten-sections-dir'] . 'render-templates/' . $section_name . '.php',
				'keywords'          => array(
																'xten',
																'section',
																'post',
																'archive',
																'collection',
																'blog'
															),
				'category'          => 'xten-sections',
				'enqueue_assets'    => function ($block) {
																	$section_name = str_replace( 'acf/', '', $block['name'] );
																	xten_enqueue_assets( $section_name );
																}
			)
		);

	}
}
add_action('acf/init', 'xten_acf_blocks_init');
