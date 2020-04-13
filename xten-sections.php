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

// Include Icons.
require_once $GLOBALS['xten-sections-dir'] . '/inc/xten-sections-svgs.php';

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
	register_section_assets(
		'section-post-archive',
		array(
			'css' => null,
			'js'  => array(
								'jquery'
							 ),
		)
	);
	register_section_assets(
		'section-wysiwyg',
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
				'slug'  => 'xten-sections',
				'title' => __( 'XTen Sections', 'xten-sections' ),
				'icon'  => $GLOBALS['xten-section-icon']['xten-sections'],
			),
		),
		$categories
	);
}
add_filter( 'block_categories', 'xten_block_category', 10, 2);

/**
 * Load ACF Fields
 */
add_filter( 'acf/settings/load_json', 'xten_sections_json_load_point' );
function xten_sections_json_load_point( $paths ) {

	// remove original path (optional).
	unset( $paths[0] );

	// append path.
	$paths[] = $GLOBALS['xten-sections-dir'] . '/acf-json';

	// return.
	return $paths;
}

/**
 * Get Option from Site Settings Page and save ACF to Child if Set.
 * Check to see if xten Save fields file exsists and adds save point if it does.
 * 
 */
function select_where_to_save_acf_field_groups() {
	$save_acf_fields = $GLOBALS['xten-sections-dir'] . 'inc/save-acf-fields.php';
	$select_where_to_save_acf_field_groups = get_field('select_where_to_save_acf_field_groups', 'options');
	$select_where_to_save_acf_field_groups = $select_where_to_save_acf_field_groups !== null ? $select_where_to_save_acf_field_groups : 'plugin';
	if ( $select_where_to_save_acf_field_groups === 'plugin' ) :
		if ( file_exists( $save_acf_fields ) ) :
			require $save_acf_fields;
		endif;
	endif;
}
add_action( 'acf/init', 'select_where_to_save_acf_field_groups' );

/**
 * Add Styles that normally get stripped from WYSIWYG file
 */
function add_to_wysiwyg_whitelist( $styles ) {
	$styles[] = 'display';
	return $styles;
}
add_filter( 'safe_style_css', 'add_to_wysiwyg_whitelist' );

// require $GLOBALS['xten-sections-dir'] . '/inc/add-acf-block-location-rules.php';