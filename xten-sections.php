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

	// Vendor

	// Bootstrap.
	$bootstrap_version = '4.0.0';
	$handle = 'xten-vendor-bootstrap-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/bootstrap/css/bootstrap.min.css', array(), $bootstrap_version );
	}
	$handle = 'xten-vendor-bootstrap-js';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery' ), $bootstrap_version, true );
	}

	// Fontawesome.
	$fontawesome_version = '6.1.2';
	$handle = 'xten-vendor-fontawesome-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/fontawesome/css/all.min.css', array(), $fontawesome_version, 'all' );
	}

	// Slick Slider Vendor
	// Slick JS
	$slick_version = '1.8.0';
	$handle = 'xten-vendor-slick-js';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/slick/slick.min.js', array( 'jquery' ), $slick_version, true );
	}
	// Slick CSS
	$handle = 'xten-vendor-slick-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/slick/slick.min.css', array(), $slick_version, 'all' );
	}
	// Xten Slick CSS - for better looking slick styles.
	$handle = 'xten-vendor-override-slick-css';
	$file_path = 'assets/css/vendor-override/xten-vendor-override-slick.css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style(
			$handle,
			$GLOBALS['xten-sections-uri'] . $file_path,
			array('xten-vendor-fontawesome-css', 'xten-vendor-slick-css'),
			xten_filemtime( $GLOBALS['xten-sections-dir'] . $file_path ),
			'all'
		);
	}

	// Fancybox Vendor
	// Fancybox JS
	$fancybox_version = '3.5.7';
	$handle = 'xten-vendor-fancybox-js';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/fancybox/jquery.fancybox.min.js', array( 'jquery' ), $fancybox_version, true );
	}
	// Fancybox CSS
	$handle = 'xten-vendor-fancybox-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/fancybox/jquery.fancybox.min.css', array(), $fancybox_version, 'all' );
	}

	// AOS Vendor
	// AOS JS
	$aos_version = '2.3.4';
	$handle = 'xten-vendor-aos-js';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/aos/aos.js', array(), $aos_version, true );
	}
	// AOS CSS
	$handle = 'xten-vendor-aos-css';
	if ( ! wp_style_is( $handle, 'registered' ) ) {
		wp_register_style( $handle, $GLOBALS['xten-sections-uri'] . 'vendor/aos/aos.css', array(), $aos_version, 'all' );
	}

	// YouTube Iframe API.
	$handle = 'xten-vendor-youtube-iframe';
	if ( ! wp_script_is( $handle, 'registered' ) ) {
		wp_register_script( $handle, 'https://www.youtube.com/iframe_api', array(), null, true );
	}

	// /Vendor

	// Shared

	$handle = 'xten-fancybox-js';
	$file_path = 'assets/js/shared/xten-fancybox.js';
	wp_register_script(
		$handle,
		$GLOBALS['xten-sections-uri'] . $file_path,
		array( 'jquery', 'xten-vendor-fancybox-js' ),
		xten_filemtime( $GLOBALS['xten-sections-dir'] . $file_path ),
		true
	);

	$handle = 'xten-aos-js';
	$file_path = 'assets/js/shared/xten-aos.js';
	wp_register_script(
		$handle,
		$GLOBALS['xten-sections-uri'] . $file_path,
		array( 'jquery', 'xten-vendor-aos-js' ),
		xten_filemtime( $GLOBALS['xten-sections-dir'] . $file_path ),
		true
	);

	// /Shared

	// Sections

	register_section_assets(
		'sections-common',
		array(
			'css' => null,
			'js'  => array(
								'jquery'
							 ),
		)
	);
	wp_enqueue_style( 'xten-sections-common-css' );
	register_section_assets(
		'component-hero',
		array(
			'css' => array('xten-vendor-override-slick-css'),
			'js'  => array(
								'jquery',
								'xten-vendor-slick-js'
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
	register_section_assets(
		'component-image-gallery',
		array(
			'css' => array(
				'xten-vendor-override-slick-css',
				'xten-vendor-fancybox-css',
			),
			'js'  => array(
				'jquery',
				'xten-vendor-slick-js',
				'xten-vendor-fancybox-js',
			),
		)
	);
	register_section_assets(
		'component-google-review-slider',
		array(
			'css' => array(
				'xten-vendor-override-slick-css',
			),
			'js'  => array(
				'jquery',
				'xten-vendor-slick-js',
			),
		)
	);

	// /Sections

	// Other Components
	register_section_assets(
		'component-modal',
		array(
			'css' => null,
			'js'  => array(
								'jquery'
							 ),
		)
	);
	// /Other Components
}
add_action( 'wp_enqueue_scripts', 'xten_section_assets', 1 );

function deregister_google_review_widget_assets() {
	// Google Review Widget CSS
	$handle = 'grw_css';
	if ( wp_style_is( $handle, 'registered' ) ) {
		wp_deregister_style( $handle );
	}

	// Business Reviews Bundle CSS - Old
	$handle = 'rplg-css';
	if ( wp_style_is( $handle, 'registered' ) ) {
		wp_deregister_style( $handle );
	}
	// Business Reviews Bundle CSS - New
	$handle = 'brb-public-main-css';
	if ( wp_style_is( $handle, 'registered' ) ) {
		wp_deregister_style( $handle );
	}

	// Business Reviews Bundle JS
	// Both Plugins use a js file titled rplg.js but the Free plugin is an old version
	// Only deregister the Free Plugin's js if the Paid Plugin's js file is registered.
	$paid_handle = 'rplg-js';
	if ( wp_script_is( $paid_handle, 'registered' ) ) {
		$free_handle = 'rplg_js';
		if ( wp_script_is( $free_handle, 'registered' ) ) {
			wp_deregister_script( $free_handle );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'deregister_google_review_widget_assets', 99 );

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

// Include Popups / Modals.
require_once $GLOBALS['xten-sections-dir'] . '/render-templates/xten-popups.php';

/**
 * Shortcodes
 */
require_once $GLOBALS['xten-sections-dir'] . '/inc/shortcodes.php';

/**
 * Add class to body if front page.
 */
function xten_sections_add_classes_to_body( $classes ) {
	// Add class to body if device is mobile.
	if ( wp_is_mobile() ) :
		$classes[] = 'is-mobile wp-is-mobile';
	endif;

	return $classes;
}
add_filter( 'body_class', 'xten_sections_add_classes_to_body' );