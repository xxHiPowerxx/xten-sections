<?php
/**
 * Render Template for Hero Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Create id attribute allowing for custom "anchor" value.
$section_name = 'xten-section-hero';
$id = $section_name . '-' . $block['id'];
if ( !empty($block['anchor']) ) :
	$id = $block['anchor'];
endif;

// Create class attribute allowing for custom "className" and "align" values.
$className = $section_name;
if ( !empty($block['className']) ) :
	$className .= ' ' . $block['className'];
endif;

$block_attrs = '';
$styles       = '';

// Minimum Height
$minimum_height_group = get_field( 'minimum_height_group' );
$minimum_height       = esc_attr( $minimum_height_group['minimum_height'] ); // !DV
if ( $minimum_height ) :
	$minimum_height_unit     = esc_attr( $minimum_height_group['minimum_height_unit'] ); // DV = '%'
	$minimum_height_attr_val = $minimum_height . $minimum_height_unit;
	$block_attrs            .= xten_add_block_attr( 'minimum-height', $minimum_height_attr_val );
	if ( $minimum_height_unit === '%' ) :
		$sizeHero = 'sizeHero';
	else :
		$sizeHero = null;
		$styles .= xten_add_inline_style(
			'#' . $id . '.' . $section_name . ' .container-xten-section-hero',
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
							'#' . $id . '.' . $section_name,
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
	$block_attrs                  .= xten_add_block_attr( 'has-overlay', true );
	$background_overlay_opacity    = $background_overlay_group['background_overlay_opacity']; // DV = 100
	$background_overlay_color_rgba = esc_attr( convert_hex_to_rgb( $background_overlay_color, $background_overlay_opacity ) );
	$styles .= xten_add_inline_style(
		'#' . $id . '.' . $section_name . ':before',
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
	$styles .= xten_add_inline_style(
		'#' . $id . '.' . $section_name . ' .xten-content-inner',
		array(
			'color' => $content_color
		),
		$content_color
	);
	$content_container = get_field( 'content_container' ); // DV = true (Fixed Width).
	$container_class   = $content_container ? esc_attr( 'container container-ext' ) : esc_attr( 'container-fluid' );
	// Content Maximum Width
	$content_maximum_width_group = get_field( 'content_maximum_width_group' );
	$content_maximum_width       = esc_attr( $content_maximum_width_group['maximum_width'] ); // !DV
	if ( $content_maximum_width ) :
		$content_maximum_width_unit = esc_attr( $content_maximum_width_group['maximum_width_unit'] ); // DV = '%'
		$content_maximum_width_val  = $content_maximum_width . $content_maximum_width_unit;
		$styles .= xten_add_inline_style(
			'#' . $id . '.' . $section_name . ' .xten-content-inner',
			array(
				'max-width' => $content_maximum_width_val
			)
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
								'#' . $id . '.' . $section_name . ' .xten-content-inner',
								array(
									'background-color' => $content_background_color_rgba
								)
							);
	endif; // endif ( $content_background_color ) :
	$content_location_group      = get_field( 'content_location_group' );
	$content_vertical_location   = $content_location_group['content_vertical_location'];
	$block_attrs                .= xten_add_block_attr( 'content-vertical-location', $content_vertical_location );
	$content_horizontal_location = $content_location_group['content_horizontal_location'];
	$block_attrs                .= xten_add_block_attr( 'content-horizontal-location', $content_horizontal_location );
endif; //if ( $content ) :
// /Content

// Render Section
$id          = esc_attr( $id );
$className   = esc_attr( $className );
$block_attrs = esc_attr( $block_attrs );
?>
<section id="<?php echo $id; ?>" class="xten-section <?php echo $className; ?>" <?php echo $block_attrs; ?>>
	<?php if ( $content ) : ?>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $section_name ); ?> <?php echo $sizeHero; ?>">
			<div class="xten-content">
				<div class="xten-content-inner">
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	<?php endif; // endif ( $content ) : ?>
</section><!-- /#<?php echo esc_attr($id); ?> -->

<?php

wp_register_style( $id, false );
wp_enqueue_style( $id );
wp_add_inline_style( $id, $styles );

if ( is_admin() ) :
	$section_asset_css_file =  xten_section_asset_file($section_name, 'css');
	$section_asset_js_file  =  xten_section_asset_file($section_name, 'js');
	
	$link_tag_id = $section_name . '-css-css';
	$style_tag_id = $id . '-inline-css';
	$style_tag = '<style id="' . $style_tag_id . '" type="text/css">' . $styles . '</style>';
	$script_tag_id = $section_name . '-js-js';
	echo $style_tag;
	?>
	<script type="text/javascript">
		(function($) {
			var linkID = '<?php echo $link_tag_id; ?>'.replace('-',''),
			linkTag = window.linkID ? window.linkID : false,
			scriptID = '<?php echo $script_tag_id; ?>'.replace('-',''),
			scriptTag = window.scriptID ? window.scriptID : false;
			if ( ! linkTag ) {
				$('<link>').attr({
					rel:  "stylesheet",
					type: "text/css",
					href: '<?php echo $GLOBALS['xten-sections-uri'] . $section_asset_css_file; ?>'
				}).appendTo('head');
				window.linkID = true;
			}
			$('<?php echo $style_tag_id; ?>').remove();
			if ( ! scriptTag ) {
				$.getScript( '<?php echo $GLOBALS['xten-sections-uri'] . $section_asset_js_file; ?>');
				window.scriptID = true;
			}
		})(jQuery);
	</script>
	<?php
endif;