<?php
/**
 * This Component Renders Multiple Accordions.
 * @package xten
 */
function component_accordions_list( $post_id = null ) {
	$handle             = 'accordions-list';
	$styles = '';

	$component_id    = xten_register_component_id( $handle );

	$html            = '';
	if ( have_rows( 'accordions_repeater', $post_id ) ) :
		$parent = get_field( 'open_multiple', $post_id ) === true ? null : $component_id;
		while ( have_rows( 'accordions_repeater', $post_id ) ) :
			the_row();
			$args                     = array();
			$args['parent']           = $parent;
			$args['open']             = get_row_index() === 1 ? true : false;
			$args['content']          = get_sub_field( 'content' );
			$args['title']            = get_sub_field( 'title', false );
			$args['color']            = get_sub_field( 'color' );
			$args['background_color'] = get_sub_field( 'background_color' );

			if ( have_rows( 'icon_fc' ) ) :
				while ( have_rows( 'icon_fc' ) ) :
					the_row();
					$row_layout                              = get_row_layout();
					$component_attrs_array['data-icon-type'] = str_replace( '_', '-', $row_layout );
					$args['icon']                = xten_get_icon_fc( $row_layout );
				endwhile;
			endif;

			$html .= xten_sections_render_component( 'accordion', $args );
		endwhile;

		if ( $html !== '' ) :
			$start_tag    = '<div id="' . $component_id . '"  class="component-' . $handle .'">';
			$end_tag      = '</div>';
			$html         = $start_tag . $html . $end_tag;
			return $html;
		endif;
	endif; // endif ( have_rows( 'accordions_repeater' ) ) :
}
