<?php
/**
 * Plugin Name: XTen Sections
 * Plugin URI: https://github.com/xxHiPowerxx/xten-sections
 * Description: Plugin used to render templates using Wordpress Gutenberg Blocks and Advanced Custom Fields to generate a form in the back-end and render a styled template on the front-end.
 * Version: 1.0
 * Author: Ricky Adams
 * Author URI: https://rickyradams.com
 * Text Domain: xten-sections
 * Pugin Used as Functions File for Xten Sections
 *
 */

$GLOBALS['xten-sections-dir'] = plugin_dir_path( __FILE__ );
$GLOBALS['xten-sections-uri'] = plugin_dir_url( __FILE__ );

// Include Utility Functions.
require_once $GLOBALS['xten-sections-dir'] . '/inc/utility-functions.php';

/**
 * Enqueue styles.
 */
function xten_section_assets() {

	// Bootstrap.
	$handle = 'xten-vendor-bootstrap-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/bootstrap/css/bootstrap.min.css', array(), '4.0.0' );
	}
	$handle = 'xten-vendor-bootstrap-js';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery' ), '4.0.0', true );
	}

	// Fontawesome.
	$handle = 'xten-vendor-fontawesome-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/fontawesome/css/all.min.css', array(), ' 5.7.1', 'all' );
	}

	
	register_section_assets(
		'section-hero',
		array(
			'css' => null,
			'js'  => array(
								'jquery'
							 ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'xten_section_assets' );

require $GLOBALS['xten-sections-dir'] . '/inc/xten-section-block-registration.php';

/**
 * Register Custom Block Category.
 */
function xten_block_category( $categories, $post ) {
	return array_merge(
		array(
			array(
				'slug' => 'xten-sections',
				'title' => __( 'XTen Sections', 'xten-sections' ),
			),
		),
		$categories
	);
}
add_filter( 'block_categories', 'xten_block_category', 10, 2);