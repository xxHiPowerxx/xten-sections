<?php
/**
 * Render Template for Google Review Slider Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */
// Check to see if Widget Google Reviews Plugin is Activated.
if ( xten_check_for_reviews_plugins() ) :
	// Store Block Configuration to pass to component.
	$args                             = array();
	$args['google_reviews_shortcode'] = get_field( 'google_reviews_shortcode' );
	if ( ! empty( $args['google_reviews_shortcode'] ) ) :
		// Create id attribute allowing for custom "anchor" value.
		$section_name               = str_replace( 'acf/', '', $block['name'] );
		$handle                     = 'google-review-slider';

		$section_attrs              = array();
		$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
		$s_id                       = $section_attrs['data-s-id'];
		$section_attrs['class']     = 'xten-section xten-section-' . $handle;

		$section_selector           ='[data-s-id="' . $s_id . '"].' . $section_name;
		$styles                     = '';

		$section_attrs['id']        = $block['anchor'];
		$section_attrs['class']   .= ' ' . $block['className'];
		$args['component_container'] = get_field( 'component_container' ); // DV = true (Fixed Width).
		$args['max_dots'] = get_field( 'max_dots' ); // ! DV

		// Render Section
		$section_attrs_s = xten_stringify_attrs( $section_attrs );
		?>
		<section <?php echo $section_attrs_s; ?>>
			<?php echo xten_sections_render_component( 'google-review-slider', $args ); ?>
		</section><!-- /#<?php echo esc_attr($s_id); ?> -->
		<?php
		xten_section_boilerplate( $s_id, $section_name, $styles );
	else :
		echo xten_section_placeholder();
	endif;
endif; // endif ( ! is_plugin_active( $plugin_file ) ) :