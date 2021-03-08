<?php
/**
 * Render Template for Image Gallery Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Store Block Configuration to pass to component.
$args           = array();
$args['images'] = get_field( 'images' );
$validation     = array (
	count( $args['images'] ) >= 2,
);
$has_content    = xten_has_content( $validation );

if ( $has_content ) :
	$handle                     = 'image-gallery';
	$section_name               = 'xten-section-' . $handle;
	$section_attrs              = array();
	$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
	$s_id                       = $section_attrs['data-s-id'];
	$section_attrs['class']     = 'xten-section xten-section-' . $handle;

	$section_selector           ='[data-s-id="' . $s_id . '"].' . $section_name;
	$styles                     = '';

	$args['c_attrs']['id']      = $block['anchor'];
	$args['c_attrs']['class']   = $block['className'];

	// Render Section
	$section_attrs_s = xten_stringify_attrs( $section_attrs );

	?>
	<section <?php echo $section_attrs_s; ?>>
		<?php echo xten_sections_render_component( 'image-gallery', $args ); ?>
	</section><!-- /#<?php echo $s_id; ?> -->

	<?php
	xten_section_boilerplate( $s_id, $section_name, $styles );
else :
	echo xten_section_placeholder();
endif; // endif ( $has_content ) :