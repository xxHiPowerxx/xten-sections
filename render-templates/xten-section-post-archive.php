<?php
/**
 * Render Template for Post-Archive Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Create id attribute allowing for custom "anchor" value.
$section_name = str_replace( 'acf/', '', $block['name'] );
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

$posts_list_id = $id . '-posts-list';

// Type Of Archive
$type_of_archive = esc_attr( get_field( 'type_of_archive' ) ); // DV = posts.
switch ( $type_of_archive ) :
	case 'categories':
		$posts_to_get = get_field( 'choose_categories' ); // ! DV, gets Term Object.
		// $posts_to_get = 'categories';
		break;
	default:
		$posts_to_get = esc_attr( get_field( 'choose_post_type' ) );
endswitch; //endswitch ( $type_of_archive ) :
// /Type Of Archive

// Section Layout
$section_container          = get_field( 'section_container' ); // DV = true (Fixed Width).
$container_class            = $section_container ?
															esc_attr( 'container container-ext' ) :
															esc_attr( 'container-fluid' );
$max_number_of_posts        = get_field( 'max_number_of_posts' ); // ! DV.
$max_posts_per_row          = get_field( 'max_posts_per_row' ); // ! DV.
$block_attrs               .= xten_add_block_attr( 'max-posts-per-row', $max_posts_per_row );
$minimum_width_of_posts     = get_field( 'minimum_width_of_posts' ); // DV = 450 && Max = 450.
// Enorce the max Value of 450 since ACF does not in blocks.
$minimum_width_of_posts     = $minimum_width_of_posts <= 450 ? $minimum_width_of_posts : 450;
$minimum_width_of_posts_px  = ( $minimum_width_of_posts ) . 'px';
$listed_post_selector       = '#' . $id . '.' . $section_name . ' .listed-post';
$styles                    .= xten_add_inline_style(
																$listed_post_selector,
																array(
																	'min-width'  => $minimum_width_of_posts_px,
																),
																true,
																'min-width:' . $minimum_width_of_posts_px
															);
$styles                    .= xten_add_inline_style(
																$listed_post_selector,
																array(
																	'-ms-flex-pack'    => 'center',
																	'justify-content'  => 'center',
																),
																true
															);
$max_description_length     = get_field( 'max_description_length' ); // ! DV.
// /Section Layout

// Type of Archive = Posts
if ( $type_of_archive === 'posts' ) :

	$posts_to_get = $posts_to_get === 'posts' ?
									substr($posts_to_get, 0, -1) :
									$posts_to_get;
	$posts = query_posts(
		array(
			'post_type' => $posts_to_get,
			'showposts' => $max_number_of_posts,
			'orderby' => 'date',
			'order' => 'DESC' ,
			)
	);
	$posts_list = array();
	foreach ( $posts as $post ) :
		$listed_post               = array();
		$post_id                   = $post->ID;
		$listed_post['post_uid']   = $id . '-' . $posts_to_get . '-' .  $post_id;
		$listed_post['post_link']  = esc_url( get_permalink( $post_id ) );
		$listed_post['post_title'] = esc_html( $post->post_title );
		$listed_post['post_date']  = xten_posted_on( $post_id );
		$post_excerpt              = $post->post_excerpt;
		// If no excerpt Look for Yoast or theme excerpts.
		if ( ! $post_excerpt ) :
			$yoast_meta = get_post_meta($post_id, '_yoast_wpseo_metadesc', true); 
			setup_postdata( $post );
			$meta_id = 'metadescription_17587';
			$xten_seo_description = get_post_meta( $post_id, $meta_id, true );
			if ( $yoast_meta ) :
				$post_excerpt = $yoast_meta;
			elseif (
				$xten_seo_description !== '' &&
				$xten_seo_description !== null
			) : // else check theme's meta description field.
				$post_excerpt = $xten_seo_description;
			else : // else trim the content.
				$post_excerpt = xten_trim_string(
													$post->post_content,
													30
												);
			endif; // endif ( $yoast_meta ) :
		endif; // endif ( ! $post_excerpt ) :
		$listed_post['post_description'] = $post_excerpt;
		$listed_post['thumbnail_img']    = null;
		if ( has_post_thumbnail( $post_id ) ) :
			$listed_post['thumbnail_img'] = get_the_post_thumbnail(
																				$post_id,
																				array( 450, null ),
																				array(
																					'title' => $listed_post['post_title']
																				)
																			);
		endif;// endif ( $category_thumbnail ) :

		// Assign Listed Post to Posts List with key of post uid.
		$posts_list[$listed_post['post_uid']] = $listed_post;
	endforeach; // endforeach ( $posts as $category ) :
	wp_reset_query();
endif; // endif ( $type_of_archive === 'posts' ) :
// /Type of Archive = Posts

// Type of Archive = Categories
if ( $type_of_archive === 'categories' ) :
	if ( $posts_to_get ) :
		$categories = $posts_to_get;
	else: 
		$categories = get_categories(
										array(
											'orderby' => 'name',
											'parent'  => 0
										)
									);
	endif; // endif ( $posts_to_get ) :
	$posts_list = array();
	foreach ( $categories as $category ) :
		$listed_post                     = array();
		$category_id                     = $category->term_id;
		$listed_post['post_uid']         = $id . '-category-' . $category_id;
		$listed_post['post_link']        = esc_url( get_category_link( $category_id ) );
		$listed_post['post_title']       = esc_html( $category->name );
		$listed_post['post_description'] = $category->description;
	
		$category_thumbnail              = get_field( 'category_thumbnail', $category );
		$listed_post['thumbnail_img']    = null;
		if ( $category_thumbnail ) :
			$thumbnail_id                 = $category_thumbnail['ID'];
			$size = xten_get_optimal_image_size( $thumbnail_id, array(450, null), array( 16, 9 ) );
			if ( is_array( $size ) ) :
				$wide_tall = xten_wide_tall_image( $size );
			else :
				$wide_tall = xten_wide_tall_image( $thumbnail_id );
			endif;
			$listed_post['thumbnail_img'] = wp_get_attachment_image( 
																				$thumbnail_id,
																				$size,
																				false,
																				array(
																					'class' => $wide_tall,
																					'title' => $listed_post['post_title']
																				)
																			);
		endif;// endif ( $category_thumbnail ) :

		// Assign Listed Post to Posts List with key of post uid.
		$posts_list[$listed_post['post_uid']] = $listed_post;
	endforeach; // endforeach ( $categories as $category ) :
endif; // endif ( $type_of_archive === 'categories' ) :
// /Type of Archive = Categories

// Render Section
$id          = esc_attr( $id );
$className   = esc_attr( $className );
$block_attrs = esc_attr( $block_attrs );
?>
<section id="<?php echo $id; ?>" class="xten-section <?php echo $className; ?>" <?php echo $block_attrs; ?>>
	<?php if ( $posts_list ) : ?>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $section_name ); ?>">
			<div class="xten-content">

				<div id="<?php echo $posts_list_id; ?>" class="post-archive posts-list">
					<?php
					foreach ( $posts_list as $listed_post ) :
						$listed_post['post_uid'];
						$listed_post['post_link'];
						$listed_post['post_title'];
						$listed_post['post_description'];
						$listed_post['thumbnail_img'];
						?>
						<div id="<?php echo $listed_post['post_uid']; ?>" class="listed-post">
							<div class="card-style display-flex flex-column listed-post-inner">
								<?php if ( $listed_post['thumbnail_img'] ) : ?>
									<div class="featured-image">
										<a href="<?php echo $listed_post['post_link']; ?>" class="post-link landscape-img" rel="bookmark">
											<?php echo $listed_post['thumbnail_img']; ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="post-body display-flex flex-column">
									<header class="entry-header">
										<a class="post-link" href="<?php echo $listed_post['post_link']; ?>" rel="bookmark">
											<h5 class="entry-title"><?php echo $listed_post['post_title']; ?></h5>
										</a>
										<?php if ( $listed_post['post_date'] ) : ?>
											<div class="post-date">
												<?php echo $listed_post['post_date'] ?>
											</div>
										<?php endif; // endif ( $type_of_archive === 'posts' ) : ?>
									</header><!-- /.entry-header -->
									<?php 
									if ( $listed_post['post_description'] ) : 
										if ( $max_description_length ) :
											$listed_post['post_description'] = xten_trim_string(
																													$listed_post['post_description'],
																													$max_description_length
																												);
										endif; // endif ( $max_description_length ) :
										?>
										<div class="entry-content">
											<?php echo $listed_post['post_description']; ?>
										</div>
									<?php endif; // if ( $listed_post['post_description'] ) : ?>
									<footer class="entry-footer xten-highlight-font">
										<a href="<?php echo $listed_post['post_link']; ?>" class="post-link" title="<?php echo $listed_post['post_title']; ?>">
											<button class="btn btn-theme-style xten-btn theme-style-dark" type="button">Read More</button>
										</a>
									</footer>
								</div><!-- /.post-body -->
							</div><!-- /.listed-post-inner -->
						</div><!-- /#<?php echo $listed_post['post_uid']; ?> -->
						<?php
						// var_dump($category);
					endforeach;
					?>
				</div><!-- /#<?php echo $posts_list_id; ?> -->

			</div><!-- /.xten-content -->
		</div><!-- /.container-<?php echo esc_attr( $section_name ); ?> -->
	<?php endif; // endif ( $content ) : ?>
</section><!-- /#<?php echo esc_attr($id); ?> -->

<?php
xten_section_boilerplate( $id, $section_name, $styles );
