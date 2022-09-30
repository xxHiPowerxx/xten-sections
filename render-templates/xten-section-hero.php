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
$valid_bgs      = array();
$valid_contents = array();
foreach( $args['slides'] as $key => $slide ) :
	// Only validate bg if there is a min-height set.
	if ( $valid_min_height ) :
		$valid_bg = array();
		// Check for background images.
		if ( $slide['background_image_group']['background_image'] ) :
			$valid_image = $slide['background_image_group']['background_image'];
			$valid_bg[]  = $valid_image;
			$valid_bgs[] = $valid_image;
		endif;

		// Check for background videos.
		if ( ! empty( $slide['background_video_fc'] ) ) :
			$valid_video = $slide['background_video_fc'][0];
			$valid_bg[]  = $valid_video;
			$valid_bgs[] = $valid_video;
		endif;
	endif; // endif ( $valid_min_height ) :

	// Check for content.
	if ( $slide['content'] ) :
		$valid_content    = $slide['content'];
		$valid_contents[] = $valid_content;
	endif;

	// For Efficiency's sake, Check Validation for this Slide,
	$validation = array(
		$valid_bg,
		$valid_content,
	);

	$slide_has_content = xten_has_content( $validation );

	// if doesn't meet requirements, move on.
	if ( ! $slide_has_content ) :
		continue;
	endif;

	$args['slides'][$key]['background_color']         = $slide['background_color'];
	$args['slides'][$key]['background_image_group']   = $slide['background_image_group'];
	$args['slides'][$key]['background_video_fc']      = $slide['background_video_fc'];
	$args['slides'][$key]['background_overlay_group'] = $slide['background_overlay_group'];
	$args['slides'][$key]['content']                  = $slide['content'];
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
	count( $valid_bgs ) > 0,
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

	$args['c_attrs']['id']      = $block['anchor'];
	$args['c_attrs']['class']   = $block['className'];

	$args['slide_method']            = get_field( 'slide_method' );
	$args['slider_background_color'] = get_field( 'slider_background_color' );

	if (
		$args['minimum_height_group']['minimum_height'] == 100 &&
		(
			$args['minimum_height_group']['minimum_height_unit'] === '%' ||
			$args['minimum_height_group']['minimum_height_unit'] === 'vh'
		)
	) :
		$args['ignore_site_header_on_min_height_calculation'] = get_field( 'ignore_site_header_on_min_height_calculation' );
	endif;

	$section_container               = get_field( 'section_container' ); // DV] true (Fixed Width).
	$container_class                 = $section_container ?
		esc_attr( 'container container-ext' ) :
		esc_attr( 'container-fluid' );

	// Render Section
	$section_attrs_s = xten_stringify_attrs( $section_attrs );
	?>
	<section <?php echo $section_attrs_s; ?>>
		<div class="<?php echo $container_class; ?> container-section-hero">
			<div class="row row-section-hero">
				<?php echo xten_sections_render_component( 'hero', $args ); ?>
			</div>
		</div>
	</section><!-- /#<?php echo esc_attr($s_id); ?> -->
<?php
endif; // if ( ! $has_content ) :

xten_section_boilerplate( $s_id, $section_name, $styles );