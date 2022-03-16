<?php
/**
 * This Component renders a bootstrap modal.
 * @package xten
 */
function component_modal( $args = null ) {
	// Enqueue Stylesheet.
	$handle           = 'modal';
	$component_name   = 'xten-component-' . $handle;
	$component_handle = 'component-' . $handle;
	$component_attrs  = array();

	// $component_css_path = '/assets/css/' . $component_handle . '.min.css';
	// $component_css_file = get_stylesheet_directory() . $component_css_path;
	// if ( file_exists( $component_css_file ) ) :
	// 	wp_register_style(
	// 		$component_handle . '-css',
	// 		get_theme_file_uri( $component_css_path ),
	// 		array(
	// 			'child-style',
	// 		),
	// 		filemtime( $component_css_file ),
	// 		'all'
	// 	);
	// 	wp_enqueue_style( $component_handle . '-css' );
	// endif;

	$styles = '';

	$component_id = xten_register_component_id( $handle );

	$component_attrs['data-c-id'] = $component_id;
	$c_id_attr                    = "[data-c-id=\"$component_id\"]";
	$component_attrs['class']     = "$component_handle modal fade";
	$component_attrs['id']        = $args['modal_id'];
	$component_attrs['tabindex']  = '-1';
	$component_attrs['role']      = 'dialog';
	// Default Modal size is md so leave null that's chosen.
	$modal_size = ( $args['modal_size'] === null || $args['modal_size'] === 'md' ) ?
		null :
		"modal-$args[modal_size]";

	$component_attrs_s            = xten_stringify_attrs( $component_attrs );

	ob_start();
	?>
	<div <?php echo $component_attrs_s; ?>>
		<div class="modal-dialog <?php echo $modal_size; ?>" role="document">
			<?php echo do_shortcode( $args['modal_content'] ); ?>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	$styles = xten_minify_css( $styles );

	wp_register_style( $component_id, false );
	wp_enqueue_style( $component_id );
	wp_add_inline_style( $component_id, html_entity_decode( $styles ) );
	xten_enqueue_assets( $component_name );

	return $html;
}
