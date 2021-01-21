<?php
/**
 * Render Template for Hero Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Create id attribute allowing for custom "anchor" value.
$section_name               = str_replace( 'acf/', '', $block['name'] );
$handle                     = 'hero';

$section_attrs              = array();
$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
$s_id                       = $section_attrs['data-s-id'];
$section_attrs['class']     = 'xten-section xten-section-' . $handle;

$section_selector           ='[data-s-id="' . $s_id . '"].' . $section_name;
$styles                     = '';

// Store Block Configuration to pass to component.
$args = array();
$args['c_attrs']['id']    = $block['anchor'];
$args['c_attrs']['class'] = $block['className'];
$minimum_height_group     = get_field( 'minimum_height_group' );
$args['minimum_height']   = $minimum_height_group['minimum_height']; // !DV
if ( $args['minimum_height']  ) :
	$args['minimum_height_unit'] = $minimum_height_group['minimum_height_unit']; // DV = '%'
endif;
$args['background_image_group']   = get_field( 'background_image_group' );
$args['background_color']         = get_field( 'background_color' );
$args['background_overlay_group'] = get_field( 'background_overlay_group' );
$args['content']                  = xten_kses_post( get_field( 'content' , false, false ) );
if ( $args['content'] ) :
	$args['content_color']               = get_field( 'content_color' );
	$args['component_container']         = get_field( 'component_container' ); // DV = true (Fixed Width).
	$args['content_minimum_width_group'] = get_field( 'content_minimum_width_group' );
	$args['content_maximum_width_group'] = get_field( 'content_maximum_width_group' );
	$args['content_background_group']    = get_field( 'content_background_group' );
	$args['content_location_group']      = get_field( 'content_location_group' );
endif;

// Render Section
$section_attrs_s = xten_stringify_attrs( $section_attrs );
?>
<section <?php echo $section_attrs_s; ?>>
	<?php echo xten_sections_render_component( 'hero', $args ); ?>
</section><!-- /#<?php echo esc_attr($s_id); ?> -->

<?php
