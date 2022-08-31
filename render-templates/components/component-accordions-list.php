<?php
/**
 * This Component Renders Multiple Accordions.
 * @package xten
 */
function component_accordions_list( $args ) {
	$post_id = $args;
	if ( is_array( $args ) ) :
		$post_id = $args['post_id'];
	else:
		$post_id = $args;
	endif;
	$handle             = 'accordions-list';
	$styles = '';

	$component_id    = xten_register_component_id( $handle );

	$html            = '';
	if ( have_rows( 'accordions_repeater', $post_id ) ) :
		$open_multiple      = get_field( 'open_multiple', $post_id );
		$parent             = $open_multiple === true ? null : $component_id;
		$args['swap_icons'] = $args['swap_icons'] ? : get_field( 'swap_icons', $post_id );
		while ( have_rows( 'accordions_repeater', $post_id ) ) :
			the_row();
			$_args                     = array();
			$_args['parent']           = $parent;
			// $_args['open']             = get_row_index() === 1 ? true : false;
			$_args['content']          = get_sub_field( 'content' );
			$_args['title']            = get_sub_field( 'title', false );
			$_args['color']            = get_sub_field( 'color' );
			$_args['background_color'] = get_sub_field( 'background_color' );
			// Do not open if not First Accordion and Open Multiple is Not Enabled.
			$_args['open']             = get_row_index() !== 1 && $open_multiple !== true ?
				false :
				get_sub_field( 'open' );;

			if ( have_rows( 'icon_fc' ) ) :
				while ( have_rows( 'icon_fc' ) ) :
					the_row();
					$row_layout                              = get_row_layout();
					$_args['icon_type'] = str_replace( '_', '-', $row_layout );
					$_args['icon']                            = xten_get_icon_fc( $row_layout );
				endwhile;
			endif;

			$html .= xten_sections_render_component( 'accordion', $_args );
		endwhile;

		if ( $html !== '' ) :
			$component_attrs_a = array(
				'id'              => $component_id,
				'class'           => "component-$handle",
				'data-swap-icons' => $args['swap_icons'],
			);
			$component_attrs_s = xten_stringify_attrs( $component_attrs_a );
			$start_tag    = "<div $component_attrs_s>";
			$end_tag      = '</div>';
			$html         = $start_tag . $html . $end_tag;
			return $html;
		endif;
	endif; // endif ( have_rows( 'accordions_repeater' ) ) :
}
