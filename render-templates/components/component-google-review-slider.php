<?php
/**
 * This Component Google Review Slider.
 * @package xten
 */
function component_google_review_slider( $args = null ) {
	// Check to see if Widget Google Reviews Plugin is not Activated.
	$plugin_file = 'widget-google-reviews/grw.php';
	if (
		! is_plugin_active( $plugin_file ) ||
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

		$component_attrs['id']    = $component_id;
		$component_attrs['class'] = $component_handle;
		$component_attrs_s        = xten_stringify_attrs( $component_attrs );

		ob_start();
		?>
		<div <?php echo $component_attrs_s; ?>>
			<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $component_name ); ?>">
				<?php echo do_shortcode( $args['google_reviews_shortcode'] ); ?>
			</div>
		</div>
		<?php
		$html = ob_get_clean();

		xten_enqueue_assets( $component_name );
		xten_section_boilerplate( $c_id, $component_name, $styles );

		return $html;
	endif;
}
