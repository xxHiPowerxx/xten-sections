<?php
/**
 * Render Template for WYSIWYG Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */


$kses_defaults = wp_kses_allowed_html( 'post' );

$svg_args = array(
		'svg'   => array(
				'class' => true,
				'aria-hidden' => true,
				'aria-labelledby' => true,
				'role' => true,
				'xmlns' => true,
				'width' => true,
				'height' => true,
				'viewbox' => true, // <= Must be lower case!
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array( 'd' => true, 'fill' => true,  ),
);
$allowed_tags = array_merge( $kses_defaults, $svg_args );

$content = wp_kses( get_field( 'content' ), $allowed_tags );
if ( $content ) :
	// Create id attribute allowing for custom "anchor" value.
	$section_name = 'xten-section-wysiwyg';
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

	$section_container = get_field( 'section_container' ); // DV = true (Fixed Width).
	$container_class   = $section_container ? esc_attr( 'container container-ext' ) : esc_attr( 'container-fluid' );

	// Render Section
	$id          = esc_attr( $id );
	$className   = esc_attr( $className );
	$block_attrs = esc_attr( $block_attrs );
	?>
	<section id="<?php echo $id; ?>" class="xten-section xten-section-wysiwyg <?php echo $className; ?>" <?php echo $block_attrs; ?>>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $section_name ); ?>">
				<div class="xten-content">
					<?php echo $content; ?>
				</div><!-- /.xten-content -->
			</div><!-- /.container-<?php echo esc_attr( $section_name ); ?> -->
	</section><!-- /#<?php echo esc_attr($id); ?> -->

	<?php
	xten_section_boilerplate( $id, $section_name, $styles );
endif; // endif ( $content ) :