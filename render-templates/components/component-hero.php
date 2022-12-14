<?php
/**
 * This Component Renders a.
 *
 * @package xten-sections
 */
function component_hero( $args = null ) {

	// Create id attribute allowing for custom "anchor" value.
	$handle                       = 'hero';
	$component_name               = 'xten-component-' . $handle;
	$component_attrs              = array();

	// xten_register_component_id() returns $handle-$i.
	$component_attrs['data-c-id'] = 'xten-component-' . xten_register_component_id( $handle );
	$c_id                         = $component_attrs['data-c-id'];
	
	$component_attrs['id']        = $args['c_attrs']['id'];
	$component_attrs['class']     = '';
	$component_attrs['class'] .= 'xten-component xten-component-' . $handle;
	$component_attrs['class'] .= ' ' . $args['c_attrs']['class'];

	$component_selector           ='[data-c-id="' . $c_id . '"].' . $component_name;
	$styles                       = '';

	$slides                       = $args['slides'];
	$slide_html                   = '';

	if ( $slides ) :
		foreach( $slides as $key => $slide ) :
			$slide_id       = "$c_id-slide-$key";
			$slide_selector = "[data-s-id=\"$slide_id\"]";
			$slide_attrs    = array();
			$slide_attrs['data-s-id'] = $slide_id;
			$additional_classes       = $slide['slide_classes'] ?
				" $slide[slide_classes]" :
				null;
			$slide_attrs['class']     = "xten-hero-slide $additional_classes";

			// Content
			$content = $slide['content'];
			if ( $content ) :
				$content_color     = esc_attr( $slide['content_color'] ); // ! DV
				if ( $content_color ) :
					$styles .= xten_add_inline_style(
						$slide_selector . ' .xten-content',
						array(
							'color' => $content_color
						),
						$content_color
					);
				endif;
				$component_container = $slide['component_container'] !== null ?
					$slide['component_container']
					: true; // DV = true (Fixed Width).
				$container_class     = $component_container === true ?
					esc_attr( 'container container-ext' ) :
					esc_attr( 'container-fluid' );
				// Content Minimum Width
				$content_minimum_width_group = $slide['content_minimum_width_group'];
				$content_minimum_width       = esc_attr( $content_minimum_width_group['minimum_width'] ); // !DV
				$content_minimum_width_px    = null;
				if ( $content_minimum_width ) :
					$content_minimum_width_px     = ( $content_minimum_width ) . 'px';
					$content_min_width_breakpoint = null;
					$bootstrap_breakpoints = array(
						'vp_xxs' => 380,
						'vp_xs'  => 575,
						'vp_sm'  => 767,
						'vp_m'   => 992,
						'vp_lg'  => 1200,
						'vp_xl'  => 1680,
						'vp_xxl' => 1920,
					);
					foreach ( $bootstrap_breakpoints as $breakpoint ) :
						if ( $content_minimum_width < $breakpoint ) :
							$content_min_width_breakpoint = $breakpoint . 'px';
							break;
						endif;
					endforeach;
					$styles .= xten_add_inline_style(
						$slide_selector . ' .xten-content',
						array(
							'min-width' => $content_minimum_width_px,
							'width' => 'auto',
						),
						true,
						'min-width:' . $content_min_width_breakpoint
					);
					$styles .= xten_add_inline_style(
						$slide_selector . ' .container-' . $component_name,
						array(
							'padding-left' => '0',
							'padding-right' => '0',
						),
						true,
						'min-width:' . $bootstrap_breakpoints['vp_xs'] . 'px'
					);
					$styles .= xten_add_inline_style(
						$slide_selector . ' .container-' . $component_name,
						array(
							'padding-left' => '15px',
							'padding-right' => '15px',
						),
						true,
						'min-width:' . $content_minimum_width_px
					);
				endif; // endif ( $content_minimum_width ) :
				// /Content Minimum Width
				// Content Maximum Width
				$content_maximum_width_group = $slide['content_maximum_width_group'];
				$content_maximum_width       = esc_attr( $content_maximum_width_group['maximum_width'] ); // !DV
				if ( $content_maximum_width ) :
					$content_maximum_width_unit = esc_attr( $content_maximum_width_group['maximum_width_unit'] ); // DV = '%'
					$content_maximum_width_val  = $content_maximum_width . $content_maximum_width_unit;
					$styles .= xten_add_inline_style(
						$slide_selector . ' .xten-content',
						array(
							'max-width' => $content_maximum_width_val
						),
						true,
						'min-width: 992px'
					);
				endif; // endif ( $content_maximum_width ) :
				// /Content Maximum Width
				$content_background_group = $slide['content_background_group'];
				$content_background_color = $content_background_group['content_background_color'];
				if ( $content_background_color ) :
					$content_background_opacity    = esc_attr( $content_background_group['content_background_opacity'] ); // DV = 100
					$content_background_color_rgba = esc_attr(
						convert_hex_to_rgb(
							$content_background_color,
							$content_background_opacity
						)
					);
					$styles .= xten_add_inline_style(
						$slide_selector . ' .xten-content',
						array(
							'background-color' => $content_background_color_rgba
						)
					);
				endif; // endif ( $content_background_color ) :
				$content_location_group                          = $slide['content_location_group'];
				$content_vertical_location                       = $content_location_group['content_vertical_location'] ? : 'middle'; // DV = middle.
				$slide_attrs['data-content-vertical-location']   = $content_vertical_location;
				$content_horizontal_location                     = $content_location_group['content_horizontal_location'] ? : 'middle'; // DV = middle.
				$slide_attrs['data-content-horizontal-location'] = $content_horizontal_location;
			endif; //if ( $content ) :
			// /Content

			// Background
			$slide_background_elm = strpos( $component_attrs['data-slide-method'], 'slide' ) !== false ?
			".xten-component-hero[data-slide-method*=\"slide\"] $slide_selector .xten-hero-slide-background" :
			".xten-component-hero[data-slide-method=\"default\"] $slide_selector";
			$slide_background_style = array();

			// Background Color
			$background_color         = $slide['background_color'];
			if ( $background_color ) :
				$slide_background_style['background-color'] = $background_color;
			endif; // endif ( $background_color ) :
			// /Background Color

			// Background Image
			$background_image_group = $slide['background_image_group'];
			$background_image       = $background_image_group['background_image'];

			if ( $background_image ) :
				$background_image_url        = esc_url( $background_image['url'] );
				$background_image_css_size   = esc_attr( $background_image_group['background_image_size'] ) ? : 'auto'; // DV = 'auto'.
				// Get Banner Background Image Position.
				$background_image_position_x = esc_attr( $background_image_group['background_image_position_x'] );
				$background_image_position_y = esc_attr( $background_image_group['background_image_position_y'] );
				if ( $background_image_position_x || $background_image_position_y ) :
					$slide_background_style['background-position-x'] = $background_image_position_x;
					$slide_background_style['background-position-y'] = $background_image_position_y;
				endif;
				switch ( $background_image_css_size ) :
					case 'auto':
							$background_image_css_size = 'auto';
							break;
					case 'cover':
						$background_image_css_size = 'cover';
						break;
					case 'contain':
						$background_image_css_size = 'contain';
						break;
					case 'width_100':
						$background_image_css_size = '100% auto';
						break;
					case 'height_100':
						$background_image_css_size = 'auto 100%';
						break;
					default:
						$background_image_css_size = 'auto';
				endswitch; //endswitch ( $background_image_css_size ) :
				$slide_background_style['background-image'] = 'url(' . $background_image_url . ')';
				$slide_background_style['background-size'] = $background_image_css_size;
			endif; // endif ( $background_image ) :
			// /Background Image

			// Background Video
			$background_video_fc = $slide['background_video_fc'];
			$video_background_markup = null;

			if (
				! empty( $background_video_fc ) &&
				(// Do not add markup if no video is found in Flexible Content Row.
					(
						$background_video_fc[0]['uploaded_video'] &&
						$background_video_fc[0]['uploaded_video'] !== false
					) ||
					(
						$background_video_fc[0]['external_video'] &&
						! empty( $background_video_fc[0]['external_video'])
					)
				)
			) :
				$loop_background_video = $slide['loop_background_video'] === null ?
					true :
					$slide['loop_background_video'];
				$slide_attrs['data-loop-background-video'] = $loop_background_video;
				$slide_attrs['data-has-video'] = true;
				$video_type = $background_video_fc[0]['acf_fc_layout'];
				ob_start();
				?>
				<div class="xten-hero-slide-background-video-wrapper" data-video-type="<?php echo esc_attr( $video_type ); ?>">
					<?php
					if ( $video_type === 'uploaded_video_type' ) :
						$video_id = $background_video_fc[0]['uploaded_video'];
						if ( $video_id !== false ) :
							$video_src = wp_get_attachment_url( $video_id );
							// Don't autoplay if we're in the editor.
							$autoplay = is_admin() ? null : 'autoplay';
							$loop     = $loop_background_video ? 'loop' : null;
							$slide_attrs['data-video-provider'] = 'internal-video';
							?>
							<div class="xten-hero-slide-background-video-inner">
								<video src="<?php echo esc_url( $video_src ); ?>" class="xten-hero-slide-background-video" <?php echo $autoplay; ?> <?php echo $loop; ?> muted></video>
							</div>
							<?php
						endif; // endif ( $video_id !== false ) :
					endif; // endif ( $video_type === 'uploaded_video_type' ) :
					// /Uploaded Video Type

					// External Video Type
					if ( $video_type === 'external_video_type' ) :
						$external_video_url = $background_video_fc[0]['external_video'];
						if ( $external_video_url ) :
							$oembed   = _wp_oembed_get_object();
							$provider = $oembed->get_provider( $external_video_url );
							if ( $provider !== false ) :
								$slide_attrs['data-video-provider'] = $provider;

								if ( strpos($provider, 'youtube') ) :
									// Enqueue YouTube Iframe API.
									$yt_api_handle = 'xten-vendor-youtube-iframe';
									if ( ! wp_script_is( $yt_api_handle, 'registered' ) ) :
										wp_register_script( $yt_api_handle, 'https://www.youtube.com/iframe_api', null, true );
									endif;
									wp_enqueue_script( $yt_api_handle );
									// Enqueue Hero Video JS
									$video_handle      = "hero-video";
									$video_js_path = "/assets/js/helpers/$video_handle.js";
									$video_js_file = $GLOBALS['xten-sections-dir'] . $video_js_path;
									if (
										! wp_script_is( "$video_handle-js", 'enqueued' ) &&
										file_exists( $video_js_file )
									) :
										wp_register_script(
											"$video_handle-js",
											$GLOBALS['xten-sections-uri'] . $video_js_path,
											array($yt_api_handle),
											filemtime( $video_js_file ),
											'all'
										);
										wp_enqueue_script( "$video_handle-js" );
									endif;

									$video_data       = xten_determine_video_url_type( $external_video_url );
									$youtube_video_id = $video_data['video_id'];
									$slide_attrs['data-background-video-id'] = $youtube_video_id;
									?>
									<div class="xten-hero-slide-background-video-inner">
										<div class="youtube-iframe"></div>
									</div>
									<?php
								endif; // endif ( strpos($provider, 'youtube') ) :
							endif; // endif ( $provider !== false ) :
						endif; // endif ( $external_video_url ) :
					endif; // endif ( $video_type === 'external_video_type' ) :
					// /External Video Type
					?>
				</div>
				<?php
				$video_background_markup = ob_get_clean();
			endif; // endif ( ! empty( $background_video_fc ) ) :
			// /Background Video

			// Set Background Styles.
			if ( ! empty( $slide_background_style ) ) :
				$styles .= xten_add_inline_style(
					"$slide_background_elm:before",
					$slide_background_style
				);
			endif;

			// Background Overlay
			$background_overlay_group = $slide['background_overlay_group'];
			$background_overlay_color = esc_attr( $background_overlay_group['background_overlay_color'] );
			$background_overlay_markup = null;
			if ( $background_overlay_color ) :
				$slide_attrs['data-has-overlay'] = true;
				$background_overlay_opacity      = $background_overlay_group['background_overlay_opacity'] ? : 100; // DV = 100
				$background_overlay_color_rgba   = esc_attr( convert_hex_to_rgb( $background_overlay_color, $background_overlay_opacity ) );
				$styles .= xten_add_inline_style(
					$slide_background_elm . '>.xten-hero-slide-background-overlay',
					array(
						'background-color' => $background_overlay_color_rgba
					)
				);
				$background_overlay_markup = '<div class="xten-hero-slide-background-overlay"></div>';
			endif; // endif ( $background_overlay_color ) :
			// /Background Overlay
			// /Background

			// Slider Background Color
			$slider_background_color = $args['slider_background_color'];
			if ( $slider_background_color ) :
				$styles .= xten_add_inline_style(
					$component_selector,
					array( 'background-color' => $slider_background_color )
				);
			endif;
			// /Slider Background Color

			$slide_attrs_s = xten_stringify_attrs( $slide_attrs );
			ob_start();
			?>
			<div <?php echo $slide_attrs_s; ?>>
				<?php
				echo $video_background_markup;
				echo $background_overlay_markup;
				?>
				<?php if ( strpos( $component_attrs['data-slide-method'], 'slide' ) !== false ) : ?>
					<div class="xten-hero-slide-background"></div>
				<?php endif; ?>
				<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $component_name ); ?>">
					<?php if ( $content ) : ?>
						<div class="xten-content-outer sizeHeroInner">
							<div class="xten-content">
								<?php echo do_shortcode( $content ); ?>
							</div>
						</div>
					<?php endif; // endif ( $content ) : ?>
				</div>
			</div>
			<?php
			$slide_html .= ob_get_clean();

		endforeach; // endforeach( $args['slides'] as $key => $slide ) :
	endif; // endif ( $slides ) :
	// Slider Configuration
	if ( count( $slides ) > 1 ) :
		$slider_config                         = xten_slider_configuration();
		$component_attrs['data-slick']         = $slider_config;
		$component_attrs['class']             .= ' slickSlider';
	endif;
	$component_attrs['data-slide-method']  = xten_snake_to_dash( $args['slide_method'] );
	// /Slider Configuration

	// Minimum Height
	$minimum_height       = esc_attr( $args['minimum_height_group']['minimum_height'] );
	$sizeHero             = null;
	if ( $minimum_height ) :
		$minimum_height_unit     = esc_attr( $args['minimum_height_group']['minimum_height_unit'] ) ? : '%'; // DV = '%'
		$minimum_height_attr_val = $minimum_height . $minimum_height_unit;
		$component_attrs['data-minimum-height'] = $minimum_height_attr_val;

		if ( $args['ignore_site_header_on_min_height_calculation'] ) :
			$component_attrs['data-ignore-site-header'] = $args['ignore_site_header_on_min_height_calculation'];
		endif;

		if ( $minimum_height_unit === '%' ) :
			$component_attrs['class'] .= ' sizeHero';
		else :
			$styles .= xten_add_inline_style(
				$component_selector,
				array(
					'min-height' => $minimum_height_attr_val,
				)
			);
		endif;// endif ( $minimum_height_unit === '%' ) :
	endif; // endif ( $minimum_height ) :
	// /Minimum Height

	// Render Section
	$component_attrs_s = xten_stringify_attrs( $component_attrs );

	ob_start();
	?>
	<div <?php echo $component_attrs_s; ?>>
		<?php echo $slide_html; ?>
	</div><!-- /#<?php echo esc_attr($c_id); ?> -->
	<?php
	$html = ob_get_clean();

	xten_enqueue_assets( $component_name );
	xten_section_boilerplate( $c_id, $component_name, $styles );

	return $html;
}