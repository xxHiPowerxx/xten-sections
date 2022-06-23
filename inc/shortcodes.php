<?php
/**
 * File that includes shortcode functions.
 *
 *
 * @package xten-sections
 */

/**
 * Accordions Shortcode
 * Renders Accordions.
 */
function accordions_list_shortcode( $atts = '' ) {
	// When Shortcode is used $atts defaults to ''.
	// Ensure that this gets converted to an array.
	$atts = $atts === '' ? array() : $atts;

	// Get Component Function.
	return xten_render_component( 'accordions-list' );
}
add_shortcode( 'accordions_list', 'accordions_list_shortcode' );