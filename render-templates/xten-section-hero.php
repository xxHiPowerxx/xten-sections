<?php
/**
 * Render Template for Hero Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Create id attribute allowing for custom "anchor" value.
$section_name = str_replace( 'acf/', '', $block['name'] );
$handle                     = 'hero';
// $section_name               = 'xten-section-' . $handle;
$section_attrs              = array();
$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
$s_id                       = $section_attrs['data-s-id'];
$section_attrs['id']        = $block['anchor'];
$section_attrs['class']     = '';
$section_attrs['class'] .= 'xten-section xten-section-' . $handle;
$section_attrs['class'] .= $block['className'] ?
	' ' . $block['className'] :
	null;

$section_selector           ='[data-s-id="' . $s_id . '"].' . $section_name;
$styles                     = '';

// Minimum Height
$minimum_height_group = get_field( 'minimum_height_group' );
$minimum_height       = esc_attr( $minimum_height_group['minimum_height'] ); // !DV
if ( $minimum_height ) :
	$minimum_height_unit     = esc_attr( $minimum_height_group['minimum_height_unit'] ); // DV = '%'
	$minimum_height_attr_val = $minimum_height . $minimum_height_unit;
	$section_attrs['data-minimum-height'] = $minimum_height_attr_val;
	if ( $minimum_height_unit === '%' ) :
		$sizeHero = 'sizeHero';
	else :
		$sizeHero = null;
		$styles .= xten_add_inline_style(
			$section_selector . ' .container-xten-section-hero',
			array(
				'min-height' => $minimum_height_attr_val,
			)
		 );
	endif;// endif ( $minimum_height_unit === '%' ) :
endif; // endif ( $minimum_height ) :
// /Minimum Height

// Background
// Background Image
$background_image_group = get_field( 'background_image_group' );
$background_image       = $background_image_group['background_image'];
if ( $background_image ) :
	$background_image_url      = esc_url( $background_image['url'] );
	$background_image_css_size = esc_attr( $background_image_group['background_image_size'] ); // DV = 'auto'.
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
	$styles .= xten_add_inline_style(
							$section_selector,
							array(
								'background-image' => 'url(' . $background_image_url . ')',
								'background-size'  => $background_image_css_size,
							)
						 );
endif; // endif ( $background_image ) :
// /Background Image
$background_color         = get_field( 'background_color' );
$background_overlay_group = get_field( 'background_overlay_group' );
$background_overlay_color = esc_attr( $background_overlay_group['background_overlay_color'] );
if ( $background_overlay_color ) :
	$section_attrs['data-has-overlay'] = true;
	$background_overlay_opacity    = $background_overlay_group['background_overlay_opacity']; // DV = 100
	$background_overlay_color_rgba = esc_attr( convert_hex_to_rgb( $background_overlay_color, $background_overlay_opacity ) );
	$styles .= xten_add_inline_style(
		$section_selector . ':before',
		array(
			'background-color' => $background_overlay_color_rgba
		)
	);
endif; // endif ( $background_color ) :
// /Background

// Content
$content = wp_kses_post( get_field( 'content' , false, false ) );
if ( $content ) :
	$content_color     = esc_attr( get_field( 'content_color' ) ); // ! DV
	$styles           .= xten_add_inline_style(
											 	$section_selector . ' .xten-content',
											 	array(
											 		'color' => $content_color
											 	),
											 	$content_color
											 );
	$section_container = get_field( 'section_container' ); // DV = true (Fixed Width).
	$container_class   = $section_container ? esc_attr( 'container container-ext' ) : esc_attr( 'container-fluid' );
	// Content Minimum Width
	$content_minimum_width_group = get_field( 'content_minimum_width_group' );
	$content_minimum_width       = esc_attr( $content_minimum_width_group['minimum_width'] ); // !DV
	$content_minimum_width_px   = null;
	if ( $content_minimum_width ) :
		$content_minimum_width_px = ( $content_minimum_width ) . 'px';
		$styles .= xten_add_inline_style(
			$section_selector . ' .xten-content',
			array(
				'min-width' => $content_minimum_width_px
			),
			true,
			'min-width:' . $content_minimum_width_px
		);
		$styles .= xten_add_inline_style(
			$section_selector . ' .container-' . $section_name,
			array(
				'padding-left' => '0',
				'padding-right' => '0',
			)
		);
		$styles .= xten_add_inline_style(
			$section_selector . ' .container-' . $section_name,
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
	$content_maximum_width_group = get_field( 'content_maximum_width_group' );
	$content_maximum_width       = esc_attr( $content_maximum_width_group['maximum_width'] ); // !DV
	if ( $content_maximum_width ) :
		$content_maximum_width_unit = esc_attr( $content_maximum_width_group['maximum_width_unit'] ); // DV = '%'
		$content_maximum_width_val  = $content_maximum_width . $content_maximum_width_unit;
		$styles .= xten_add_inline_style(
			$section_selector . ' .xten-content',
			array(
				'max-width' => $content_maximum_width_val
			),
			true,
			'min-width: 992px'
		);
	endif; // endif ( $content_maximum_width ) :
	// /Content Maximum Width
	$content_background_group = get_field( 'content_background_group' );
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
								$section_selector . ' .xten-content',
								array(
									'background-color' => $content_background_color_rgba
								)
							);
	endif; // endif ( $content_background_color ) :
	$content_location_group      = get_field( 'content_location_group' );
	$content_vertical_location   = $content_location_group['content_vertical_location'];
	$section_attrs['data-content-vertical-location'] = $content_vertical_location;
	$content_horizontal_location = $content_location_group['content_horizontal_location'];
	$section_attrs['data-content-horizontal-location'] = $content_horizontal_location;
endif; //if ( $content ) :
// /Content

// Render Section
$section_attrs_s = xten_stringify_attrs( $section_attrs );

?>
<section <?php echo $section_attrs_s; ?>>
	<?php if ( $content ) : ?>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $section_name ); ?> <?php echo $sizeHero; ?>">
			<div class="xten-content-outer">
				<div class="xten-content">
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	<?php endif; // endif ( $content ) : ?>
</section><!-- /#<?php echo esc_attr($s_id); ?> -->

<?php

xten_section_boilerplate( $s_id, $section_name, $styles );