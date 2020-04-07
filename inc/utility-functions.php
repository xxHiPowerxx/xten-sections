<?php
/**
 * File used for Registering Section Blocks
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */


/**
 * Safely Get FileMTime
 */
if ( ! function_exists( 'xten_filemtime' ) ) :
	function xten_filemtime($file) {
		if ( file_exists( $file ) ):
			return filemtime( $file );
		endif;
	}
endif;
/**
 * Get Section Asset File
 */
if ( ! function_exists( 'xten_section_asset_file' ) ) :
	function xten_section_asset_file($section_name, $file_type) {
		return 'assets/' . $file_type . '/' . $section_name . '.' . $file_type;
	}
endif; // endif ( ! function_exists( 'xten_section_asset_file' ) ) :
/**
 * Register assets with naming convention.
 * 
 * @param {string} section_name - Section's Name EG: 'section-hero'.
 * @param {array} depenencies - Dependencies for css and js
 * EG: array('css' => null,'js' => array('jquery'))
 */
if ( ! function_exists( 'register_section_assets' ) ) :
	function register_section_assets($section_name, $dependencies) {
		if (! $dependencies['css'] ) :
			$dependencies['css'] = array();
		endif;
		if (! $dependencies['js'] ) :
			$dependencies['js'] = array();
		endif;
		function register_asset($section_name, $dependencies, $file_type) {
			$section_name       = 'xten-' . $section_name;
			$section_asset_file = xten_section_asset_file($section_name, $file_type);
			if ( $file_type === 'css' ) :
				wp_register_style(
					$section_name . '-' . $file_type,
					$GLOBALS['xten-sections-uri'] . $section_asset_file,
					$dependencies[$file_type],
					xten_filemtime( $GLOBALS['xten-sections-dir'] . $section_asset_file )
				);
			endif;
			if ( $file_type === 'js' ) :
				wp_register_script(
					$section_name . '-' . $file_type,
					$GLOBALS['xten-sections-uri'] . $section_asset_file,
					$dependencies[$file_type],
					xten_filemtime( $GLOBALS['xten-sections-dir'] . $section_asset_file ),
					true
				);
			endif;
		}
		register_asset($section_name, $dependencies, 'css');
		register_asset($section_name, $dependencies, 'js');
	}
endif; // endif ( ! function_exists( 'register_section_assets' ) ) :

/**
 * Register assets with naming convention.
 * 
 * @param {string} section_name - Section's Name EG: 'section-hero'.
 */
if ( ! function_exists( 'xten_enqueue_assets' ) ) :
	function xten_enqueue_assets( $section_name ) {
		$section_name_css = $section_name . '-css';
		$section_name_js = $section_name . '-js';
		if ( wp_style_is( $section_name_css, 'registered' ) ) :
			wp_enqueue_style($section_name_css);
		endif;
		if ( wp_script_is( $section_name_js, 'registered' ) ) :
			wp_enqueue_script($section_name_js);
		endif;
	}
endif; // endif ( ! function_exists( 'register_section_assets' ) ) :

/**
 * Function Adds Block Attributes
 * 
 * @param {string} attr_name - Attribute Name (will add data- prefix if not included).
 * @param {string} attr_val - Attribute Value
 */
if ( ! function_exists( 'xten_add_block_attr' ) ) :
	function xten_add_block_attr( $attr_name, $attr_val ) {
		$data_prefix = 'data-';
		$has_data_prefix = substr($attr_name, 0, strlen($data_prefix)) === $data_prefix;
		if ( ! $has_data_prefix ) :
			$attr_name = $data_prefix . $attr_name;
		endif;
		$attr = $attr_name . '=' . $attr_val . '';
		return ' ' . esc_attr( $attr );
	}
endif; // endif ( ! function_exists( 'xten_add_block_attr' ) ) :

/**
 * Convert Hex to RGB
 *
 * @param string $hex_string hex value from customizer.
 * @param string $opacity opacity value from customizer.
 */
if ( ! function_exists( 'convert_hex_to_rgb' ) ) :
	function convert_hex_to_rgb( $hex_string, $opacity = null ) {
		if ( null !== $opacity ) :
				$opacity = $opacity / 100;
		endif;

		list($r, $g, $b) = sscanf( $hex_string, '#%02x%02x%02x' );
		if ( 0 === $opacity || $opacity ) :
			return "rgba({$r}, {$g}, {$b}, {$opacity})";
		else :
				return "rgb({$r}, {$g}, {$b})";
		endif;
	}
endif; // endif ( ! function_exists( 'convert_hex_to_rgb' ) ) :

/**
 * Add Inline Style
 *
 * @param string $selector Selector for Style Rule
 * @param array $rule_array opacity value from customizer.
 * @param string $validator optional value for validation checking.
 * 
 */
if ( ! function_exists( 'xten_add_inline_style' ) ) :
	function xten_add_inline_style($selector, $rule_array, $validator = true) {
		if ( $validator ) :
			$rule = $selector . '{';
			foreach ( $rule_array as $property => $value ) :
				$rule .=	$property . ':' . $value . ';';
			endforeach;
			$rule .= '}';
			return $rule;
		endif;
	}
endif; // endif ( ! function_exists( 'xten_add_inline_style' ) ) :