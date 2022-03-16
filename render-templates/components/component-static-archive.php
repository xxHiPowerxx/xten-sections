<?php
/**
 * This Component renders a Static Archive.
 * @package xten
 */
function component_static_archive( $args = null ) {
	if ( ! $args['objects'] ) :
		return null;
	endif;
	$component_attrs_s = xten_stringify_attrs( $args['c_attrs'] );

	$container_class = $args['component_container'] ?
		esc_attr( 'container container-ext' ) :
		esc_attr( 'container-fluid' );
	ob_start();
	?>
	<div <?php echo $component_attrs_s; ?>>
		<div class="<?php echo $container_class; ?>">
			<div class="static-archive-list">
				<?php
				global $post;
				$original_post = $post;
				foreach( $args['objects'] as $object ) :
					$post = $object;
					get_template_part( 'template-parts/content-archive', get_post_type(), $args );
				endforeach; // endforeach( $args['objects'] as $object ) :
				$post = $original_post;
				?>
			</div>
		</div>
	</div>
	<?php
	$result = ob_get_clean();
	return $result;
}