<?php
/**
 * Render Template for Archive Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Store Block Configuration to pass to component.
if ( have_rows( 'static_or_dynamic' ) ) :
	while ( have_rows( 'static_or_dynamic' ) ) :
		the_row();
		$handle                     = 'archive';
		$section_name               = 'xten-section-' . $handle;
		$section_attrs              = array();
		$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
		$s_id                       = $section_attrs['data-s-id'];
		$section_attrs['class']     = 'xten-section xten-section-' . $handle;

		$section_selector           ='[data-s-id="' . $s_id . '"].' . $section_name;
		$styles                     = '';

		$args['c_attrs']['id']      = $block['anchor'];
		$args['c_attrs']['class']   = $block['className'];
		$static_or_dynamic          = get_row_layout();
		$args['component_container'] = get_field( 'section_container' ); // DV = true (Fixed Width).

		// Dyanmic
		if ( $static_or_dynamic === 'dynamic' ) :
			$args['archive_type'] = get_sub_field( 'archive_type' );
			$args['c_attrs']['data-object-type'] = $args['archive_type'];
			$args['max_objects_to_show'] = get_sub_field( 'max_objects_to_show' );
			$args['post_type'] = get_sub_field( 'post_type' );
			$args['tax_slug']  = get_sub_field( 'tax_slug' );
			$args['term_name'] = get_sub_field( 'term_name' );
			$args['c_attrs']['data-object-type'] = $args['archive_type'];
		endif; // endif ( $static_or_dynamic === 'dynamic' ) :
		// /Dynamic

		// Static
		if ( $static_or_dynamic === 'static' ) :
			$args['objects'] = get_sub_field( 'objects' );
		endif; // endif ( $static_or_dynamic === 'static' ) :
		// /Static

		// Render Section
		$section_attrs_s = xten_stringify_attrs( $section_attrs );

		?>
		<section <?php echo $section_attrs_s; ?>>
			<?php echo xten_sections_render_component( "$static_or_dynamic-archive", $args ); ?>
		</section><!-- /#<?php echo $s_id; ?> -->

		<?php
		xten_section_boilerplate( $s_id, $section_name, $styles );
	endwhile;
else :
	echo xten_section_placeholder();
endif; // endif ( have_rows( 'static_or_dynamic' ) ) :