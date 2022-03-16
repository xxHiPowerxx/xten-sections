<?php
/**
 * This Component renders a Dynamic Archive.
 * @package xten
 */
function component_dynamic_archive( $args = null ) {
	$archive_type = $args['archive_type'];
	$component_attrs_s = xten_stringify_attrs( $args['c_attrs'] );
	// Construct Query.
	$query = null;
	if ( $args['archive_type'] === 'post_archive' ) :
		$q_args = array(
			'post_type' => $args['post_type'],
			'posts_per_page' => $args['max_objects_to_show'],
			'tax_query' => array(
				array (
					'taxonomy' => $args['tax_slug'],
					'field'    => 'slug',
					'terms'    => explode (",", $args['term_name']),
				)
			),
		);
		$query = new WP_Query( $q_args );
	endif; // endif ( $args['archive_type'] === 'post_archive ' ) :

	if ( $args['archive_type'] === 'tax_archive' ) :
		$q_args = array(
			'taxonomy' => $args['tax_slug'],
			'number' => $args['max_objects_to_show'],
		);
		$query = new WP_Term_Query( $q_args );
	endif; // endif ( $args['archive_type'] === 'post_archive ' ) :

	$container_class = $args['component_container'] ?
		esc_attr( 'container container-ext' ) :
		esc_attr( 'container-fluid' );
	ob_start();
	?>
	<div <?php echo $component_attrs_s; ?>>
		<div class="<?php echo $container_class; ?>">
			<div class="<?php echo "{$args['post_type']}-list" ?>">
				<?php
				if ( $query && $query->have_posts() ) :
					while ( $query->have_posts() ) :
						$query->the_post();
						get_template_part( 'template-parts/content-archive', get_post_type(), $args );
					endwhile; // End of the loop.
				else:
					get_template_part( 'template-parts/content', 'none' );
				endif; // endif ( $query->have_posts() ) :
				?>
			</div>
		</div>
	</div>
	<?php
	$result = ob_get_clean();
	return $result;
}