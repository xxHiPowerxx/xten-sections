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
$args['slides']   = get_field( 'slides_repeater' );
$valid_images     = array();
$valid_min_heights = array();
$valid_contents    = array();
foreach( $args['slides'] as $key => $slide ) :
	if ( $slide['background_image_group']['background_image'] ) :
		$valid_image = $slide['background_image_group']['background_image'];
		$valid_images[] = $valid_image;
	endif;
	if (
		! empty( $slide['minimum_height_group']['minimum_height'] ) &&
		( $slide['background_image_group']['background_image'] ||
			$slide['background_image_group']['background_color'] )
	) :
		$valid_min_height = $slide['minimum_height_group']['minimum_height'] . $slide['minimum_height_group']['minimum_height_unit'];
		$valid_min_heights[] = $valid_min_height;
	endif;
	if ( $slide['content'] ) :
		$valid_content = $slide['content'];
		$valid_contents[] = $valid_content;
	endif;

	// For Efficiency's sake, Check Validation for this Slide,
	$validation = array(
		$valid_image,
		$valid_min_height,
		$valid_content,
	);
	$slide_has_content = xten_has_content( $validation );

	// if doesn't meet requirements, move on.
	if ( ! $slide_has_content ) :
		continue;
	endif;

	$minimum_height_group                     = $slide['minimum_height_group'];
	$args['slides'][$key]['minimum_height']   = $minimum_height_group['minimum_height']; // !DV
	if ( $args['slides'][$key]['minimum_height'] ) :
		$args['slides'][$key]['minimum_height_unit'] = $minimum_height_group['minimum_height_unit']; // DV = '%'
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
	count( $valid_min_heights ) > 0,
	count( $valid_contents ) > 0,
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

	$args['c_attrs']['id']    = $block['anchor'];
	$args['c_attrs']['class'] = $block['className'];

	// Render Section
	$section_attrs_s = xten_stringify_attrs( $section_attrs );
	?>
	<section <?php echo $section_attrs_s; ?>>
		<?php echo xten_sections_render_component( 'hero', $args ); ?>
	</section><!-- /#<?php echo esc_attr($s_id); ?> -->
<?php
endif; // if ( ! $has_content ) :
