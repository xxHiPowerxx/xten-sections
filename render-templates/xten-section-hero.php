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

$args['slides'] = array();

$valid_bgs      = array();
$valid_contents = array();
while( have_rows('slides_repeater' ) ) :
	$slide = the_row( true );
	// Only validate bg if there is a min-height set.
	if ( $valid_min_height ) :
		$valid_bg = array();
		// Check for background images.
		$background_image_group = get_sub_field( 'background_image_group' );
		$background_image       = $background_image_group['background_image'];
		if ( $background_image ) :
			$valid_bg[]  = $background_image;
			$valid_bgs[] = $background_image;
		endif;

		$background_video_fc = null;
		// Check for background video.
		if ( have_rows( 'background_video_fc' ) ) :
			while ( have_rows( 'background_video_fc' ) ) :
				$background_video_fc = the_row( true );
				$valid_bg[]  = $background_video_fc;
				$valid_bgs[] = $background_video_fc;
			endwhile;
		endif;
	endif; // endif ( $valid_min_height ) :

	// Check for content.
	$content = get_sub_field( 'content', false );
	if ( $content ) :
		$valid_contents[] = $content;
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

	$slide['background_color']         = get_sub_field( 'background_color' );
	$slide['background_image_group']   = $background_image_group;
	$slide['background_video_fc']      = $background_video_fc;
	$slide['background_overlay_group'] = get_sub_field( 'background_overlay_group' );
	$slide['content']                  = $content;

	if ( $content ) :
		$slide['content_color']               = get_sub_field( 'content_color' );
		$slide['component_container']         = get_sub_field( 'component_container' ); // DV true [Fixed Width].
		$slide['content_minimum_width_group'] = get_sub_field( 'content_minimum_width_group' );
		$slide['content_maximum_width_group'] = get_sub_field( 'content_maximum_width_group' );
		$slide['content_background_group']    = get_sub_field( 'content_background_group' );
		$slide['content_location_group']      = get_sub_field( 'content_location_group' );
	endif;

	$key = get_row_index() - 1;
	$args['slides'][$key] = $slide;

endwhile; // endwhile( have_rows('slides_repeater' ) ) :


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