<?php
/**
 * File Used as Functions File for Xten Sections
 * Will be moved to a plugin in future
 *
 * @link https://developer.wordpress.org/plugins/plugin-basics/
 *
 * @package xten
 */


$GLOBALS['xten-sections-dir'] = get_template_directory() . '/xten-sections';
$GLOBALS['xten-sections-uri'] = get_theme_file_uri() . '/xten-sections';
$dir_path = $GLOBALS['xten-sections-dir'];
$uri_path = $GLOBALS['xten-sections-uri'];

/**
 * Enqueue styles.
 */
function xten_section_assets() {
	// Bootstrap.
	$handle = 'xten-vendor-bootstrap-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, get_theme_file_uri( '/assets/vendor/bootstrap/css/bootstrap.min.css' ), array(), '4.0.0' );
	}
	$handle = 'xten-vendor-bootstrap-js';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, get_theme_file_uri( '/assets/vendor/bootstrap/js/bootstrap.bundle.min.js' ), array( 'jquery' ), '4.0.0', true );
	}

	// Fontawesome.
	$handle = 'xten-vendor-fontawesome-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, get_theme_file_uri( '/assets/vendor/fontawesome/css/all.min.css' ), array(), ' 5.7.1', 'all' );
	}

	// Register component styles that are printed as needed.
	$section_name = 'section-hero';
	$section_asset_file = '/assets/css/xten-' . $section_name . '.css';
	function register_section_assets($section_name, $dependancies) {
		if (! $dependancies['css'] ) :
			$dependancies['css'] = array();
		endif;
		if (! $dependancies['js'] ) :
			$dependancies['js'] = array();
		endif;
		function register_asset($file_type) {
			$section_asset_file = '/assets/' . $file_type . '/xten-' . $section_name . '.' . $file_type;
			if ( $file_type === 'css' ) :
				wp_register_style(
					'xten-' . $section_name . '-' . $file_type . '',
					$uri_path . $section_asset_file,
					$dependancies['css'],
					filemtime( $dir_path . $section_asset_file )
				);
			endif;
			if ( $file_type === 'js' ) :
				wp_register_style(
					'xten-' . $section_name . '-css',
					$uri_path . $section_asset_file,
					array(),
					filemtime( $dir_path . $section_asset_file ),
					true
				);
			endif;
		}
		register_asset('css');
		register_asset('js');
		
		// wp_register_style(
		// 	'xten-' . $section_name . '-' . $file_type . '',
		// 	$uri_path . $section_asset_file,
		// 	array(),
		// 	filemtime( $dir_path . $section_asset_file )
		// );
		// $section_asset_file = '/assets/css/xten-' . $section_name . '.css';
		// wp_register_style(
		// 	'xten-' . $section_name . '-css',
		// 	$uri_path . $section_asset_file,
		// 	array(),
		// 	filemtime( $dir_path . $section_asset_file )
		// );
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
	// wp_register_style(
	// 	'xten-' . $section_name . '-css',
	// 	$uri_path . $section_asset_file,
	// 	array(),
	// 	filemtime( $dir_path . $section_asset_file )
	// );
	// $section_asset_file = '/assets/css/xten-' . $section_name . '.css';
	// wp_register_style(
	// 	'xten-' . $section_name . '-css',
	// 	$uri_path . $section_asset_file,
	// 	array(),
	// 	filemtime( $dir_path . $section_asset_file )
	// );
}
add_action( 'wp_enqueue_scripts', 'xten_section_assets' );