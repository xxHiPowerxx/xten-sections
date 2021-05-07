<?php
/**
 * Render Template for Hero Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Store Block Configuration to pass to component.
$args             = array();

$args['minimum_height_group'] = get_field( 'minimum_height_group' );
$minimum_height               = $args['minimum_height_group']['minimum_height']; // !DV
if ( $minimum_height ) :
	$valid_min_height = $args['minimum_height_group']['minimum_height_unit']; // DV = '%'
endif;

$args['slides'] = get_field( 'slides_repeater' );
$valid_images   = array();
$valid_contents = array();
foreach( $args['slides'] as $key => $slide ) :
	if ( $slide['background_image_group']['background_image'] && $valid_min_height ) :
		$valid_image = $slide['background_image_group']['background_image'];
		$valid_images[] = $valid_image;
	endif;
	if ( $slide['content'] ) :
		$valid_content = $slide['content'];
		$valid_contents[] = $valid_content;
	endif;

	// For Efficiency's sake, Check Validation for this Slide,
	$validation = array(
		$valid_image,
		$valid_content,
	);
	$slide_has_content = xten_has_content( $validation );

	// if doesn't meet requirements, move on.
	if ( ! $slide_has_content ) :
		continue;
	endif;

	$args['slides'][$key]['background_image_group']   = $slide['background_image_group'];
	$args['slides'][$key]['background_color']         = $slide['background_color'];
	$args['slides'][$key]['background_overlay_group'] = $slide['background_overlay_group'];
	$args['slides'][$key]['content']                  = xten_kses_post( $slide['content'] );
	if ( $args['slides'][$key]['content'] ) :
		$args['slides'][$key]['content_color']               = $slide['content_color'];
		$args['slides'][$key]['component_container']         = $slide['component_container']; // DV] true (Fixed Width).
		$args['slides'][$key]['content_minimum_width_group'] = $slide['content_minimum_width_group'];
		$args['slides'][$key]['content_maximum_width_group'] = $slide['content_maximum_width_group'];
		$args['slides'][$key]['content_background_group']    = $slide['content_background_group'];
		$args['slides'][$key]['content_location_group']      = $slide['content_location_group'];
	endif;

endforeach; // endforeach( $args['slides'] as $key => $slide ) :

$validations = array(
	count( $valid_images ) > 0,
	count( $valid_contents ) > 0,
	$valid_min_height,
);

$slides_have_content = xten_has_content( $validations );

if ( ! $slides_have_content ) :
	echo xten_section_placeholder();
else :
	// Create id attribute allowing for custom "anchor" value.
	$section_name               = str_replace( 'acf/', '', $block['name'] );
	$handle                     = 'hero';

	$section_attrs              = array();
	$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
	$s_id                       = $section_attrs['data-s-id'];
	$section_attrs['class']     = 'xten-section xten-section-' . $handle;

	$section_selector           ='[data-s-id="' . $s_id . '"].' . $section_name;
	$styles                     = '';

	$args['c_attrs']['id']      = $block['anchor'];
	$args['c_attrs']['class']   = $block['className'];

	$args['slide_method']            = get_field( 'slide_method' );
	$args['slider_background_color'] = get_field( 'slider_background_color' );

	// Render Section
	$section_attrs_s = xten_stringify_attrs( $section_attrs );
	?>
	<section <?php echo $section_attrs_s; ?>>
		<?php echo xten_sections_render_component( 'hero', $args ); ?>
	</section><!-- /#<?php echo esc_attr($s_id); ?> -->
<?php
endif; // if ( ! $has_content ) :
