<?php
/**
 * This Component Renders an Image Gallery.
 *
 * @package xten-sections
 */
function component_image_gallery( $args = null ) {

	// Create id attribute allowing for custom "anchor" value.
	$handle                       = 'image-gallery';
	$component_name               = 'xten-component-' . $handle;
	$component_attrs              = array();

	$component_attrs['data-c-id'] = $component_name . '-' . xten_register_component_id( $handle );
	$c_id                         = $component_attrs['data-c-id'];
	
	$component_attrs['id']        = $args['c_attrs']['id'];
	$component_attrs['class']     = '';
	$component_attrs['class'] .= 'xten-component xten-component-' . $handle;
	$component_attrs['class'] .= ' ' . $args['c_attrs']['class'];

	$component_selector           ='[data-c-id="' . $c_id . '"].' . $component_name;
	$styles                       = '';

	$images                       = $args['images'];

	// Render Section
	$component_attrs_s       = xten_stringify_attrs( $component_attrs );

	$shared_slick_properties = rtrim( '
		"slidesToScroll": 1,
		"dots": false,
		"infinite": true,
		"speed": 350,
		"cssEase": "cubic-bezier(0.22, 0.61, 0.36, 1)"
	' );

	/*   Main Slider Config   */
	$main_slider_slick_attrs_inner = rtrim( '
		"slidesToShow": 1,
		"arrows": true,
		'./*"autoplay": true,
		"autoplaySpeed": 5000,'*/'
		"asNavFor": "' . str_replace( '"', '\"', $component_selector ) . ' .nav-slider"
	' );

	$main_slider_slick_attrs_inner .= ',' . $shared_slick_properties;
	$main_slider_slick_attrs = '{' . $main_slider_slick_attrs_inner . '}';
	$main_slider_attrs       = array(
		'class'      => 'slickSlider main-slider',
		'title'      => 'Click to View Full Image',
		'data-slick' => $main_slider_slick_attrs,
	);

	$main_slider_attrs_s = xten_stringify_attrs( $main_slider_attrs, false );
	/*   /Main Slider Config   */

	/*   Nav Slider Config   */
	$nav_slider_slick_attrs_inner = rtrim( '
		"centerMode": true,
		"centerPadding": "7.5%",
		"focusOnSelect": true,
		"arrows": false,
		"swipeToSlide": true,
		"responsive": [{
			"breakpoint": 576,
			"settings": {"slidesToShow": 3}
		}],
		"asNavFor": "' . str_replace( '"', '\"', $component_selector ) . ' .main-slider"
	' );
	/*   /Nav Slider Config   */


	ob_start();
	?>
	<div <?php echo $component_attrs_s; ?>>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $component_name ); ?> <?php echo $sizeHero; ?>">
			<div class="xten-content">
				<div class="main-slider-wrapper">
					<div <?php echo $main_slider_attrs_s; ?>>
						<?php
						$nav_images = array();
						foreach ( $images as $key=>$image ) :
							// var_dump($image);
							// die;
							$full_image       = wp_get_attachment_image( $image['ID'], array(2560, null) );
							$full_image_url   = $image['url'];
							$nav_images[$key] = $full_image;
							?>
							<a class="anchor-image-gallery-image" data-fancybox="<?php echo $component_attrs['data-c-id']; ?>" data-loop="true" href="<?php echo $full_image_url; ?>" data-width="<?php echo $image['width']; ?>" data-height="<?php echo $image['height']; ?>">
								<?php echo $full_image; ?>
							</a>
							<?php
						endforeach;
						?>
					</div>
				</div>
				<?php
				$max_nav_images   = 6;
				$nav_images_count = count( $nav_images );
				$slides_to_show   = $nav_images_count >= $max_nav_images ?
					$max_nav_images :
					$nav_images_count;
				$nav_slider_slick_attrs_inner .= ',"slidesToShow": ' . --$slides_to_show;
				$nav_slider_slick_attrs_inner .= ',' . $shared_slick_properties;
				$nav_slider_slick_attrs = '{' . $nav_slider_slick_attrs_inner . '}';
				$nav_slider_attrs       = array(
					'class'      => 'slickSlider nav-slider',
					'data-slick' => $nav_slider_slick_attrs,
				);

				$nav_slider_attrs_s = xten_stringify_attrs( $nav_slider_attrs, false );
				?>
				<div class="nav-slider-wrapper">
					<div <?php echo $nav_slider_attrs_s; ?>>
						<?php
						foreach ( $nav_images as $nav_image ) :
							?>
							<div class="image-gallery-nav-image-wrapper">
								<div class="image-gallery-nav-image-wrapper-inner">
									<?php echo $nav_image; ?>
								</div>
							</div>
							<?php
						endforeach;
						?>
					</div>
				</div>
			</div>
		</div>
	</div><!-- /#<?php echo esc_attr($c_id); ?> -->
	<?php
	$html = ob_get_clean();

	xten_enqueue_assets( $component_name );
	xten_section_boilerplate( $c_id, $component_name, $styles );

	return $html;
}