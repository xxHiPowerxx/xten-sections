<?php
/**
 * This Component renders the Google Review Slider.
 * @package xten
 */
function component_google_review_slider( $args = null ) {
	// Check to see if Widget Google Reviews Plugin is not Activated.
	if (
		! xten_check_for_reviews_plugins() ||
		empty( $args['google_reviews_shortcode'] )
	) :
		return;
	else :
		// Enqueue Stylesheet.
		$handle             = 'google-review-slider';
		$component_name     = 'xten-component-' . $handle;
		$component_handle   = 'component-' . $handle;
		$component_attrs    = array();

		$component_css_path = '/assets/css/' . $component_handle . '.min.css';
		$component_css_file = get_stylesheet_directory() . $component_css_path;
		if ( file_exists( $component_css_file ) ) :
			wp_register_style(
				$component_handle . '-css',
				get_theme_file_uri( $component_css_path ),
				array(
					'child-style',
				),
				filemtime( $component_css_file ),
				'all'
			);
			wp_enqueue_style( $component_handle . '-css' );
		endif;

		$styles = '';

		$component_id = xten_register_component_id( $handle );

		$component_container = $args['component_container'] ? : true; // DV = true (Fixed Width).
		$container_class     = $component_container ?
			esc_attr( 'container container-ext' ) :
			esc_attr( 'container-fluid' );

		$component_attrs['data-c-id']  = $component_id;
		$c_id_attr                     = "[data-c-id=\"$component_id\"]";
		$component_attrs['class']      = $component_handle;
		$component_attrs_s             = xten_stringify_attrs( $component_attrs );

		ob_start();
		?>
		<div <?php echo $component_attrs_s; ?>>
			<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $component_name ); ?>">
				<?php echo do_shortcode( $args['google_reviews_shortcode'] ); ?>
			</div>
		</div>
		<?php
		$html = ob_get_clean();

		// $styles .= set_max_slick_dots_styles( $c_id_attr, $args['max_dots'] );

		$styles = xten_minify_css( $styles );

		wp_register_style( $component_id, false );
		wp_enqueue_style( $component_id );
		wp_add_inline_style( $component_id, $styles );
		xten_enqueue_assets( $component_name );

		return $html;
	endif;
}
