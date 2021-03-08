<?php
/**
 * Render Template for WYSIWYG Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */


$content = xten_kses_post( get_field( 'content', false, false ) );
if ( $content ) :
	$handle                     = 'wysiwyg';
	$section_name               = 'xten-section-' . $handle;
	$section_attrs              = array();
	$section_attrs['data-s-id'] = $section_name . '-' . $block['id'];
	$s_id                       = $section_attrs['data-s-id'];
	$section_attrs['id']        = $block['anchor'];
	$section_attrs['class']     = '';
	$section_attrs['class'] .= 'xten-section xten-section-' . $handle;
	$section_attrs['class'] .= $block['className'] ?
		' ' . $block['className'] :
		null;

	$styles        = '';

	$section_container = get_field( 'section_container' ); // DV = true (Fixed Width).
	$container_class   = $section_container ? esc_attr( 'container container-ext' ) : esc_attr( 'container-fluid' );

	// Render Section
	$section_attrs_s = xten_stringify_attrs( $section_attrs );

	?>
	<section <?php echo $section_attrs_s; ?>>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $section_name ); ?>">
			<div class="xten-content">
				<?php echo $content; ?>
			</div><!-- /.xten-content -->
		</div><!-- /.container-<?php echo esc_attr( $section_name ); ?> -->
	</section><!-- /#<?php echo $s_id; ?> -->

	<?php
	xten_section_boilerplate( $s_id, $section_name, $styles );
endif; // endif ( $content ) :