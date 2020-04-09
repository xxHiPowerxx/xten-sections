<?php
/**
 * Render Template for Post-Archive Section Block
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */

// Create id attribute allowing for custom "anchor" value.
$section_name = 'xten-section-post-archive';
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
$section_container      = get_field( 'section_container' ); // DV = true (Fixed Width).
$container_class        = $section_container ?
													esc_attr( 'container container-ext' ) :
													esc_attr( 'container-fluid' );
$max_number_of_posts    = get_field( 'max_number_of_posts' ); // ! DV.
$max_posts_per_row      = get_field( 'max_posts_per_row' ); // ! DV.
$block_attrs           .= xten_add_block_attr( 'max-posts-per-row', $max_posts_per_row );
$minimum_width_of_posts = get_field( 'minimum_width_of_posts' ); // DV = 426.
$minimum_width_of_posts_rem = ( $minimum_width_of_posts * .10 ) . 'rem';
$styles .= xten_add_inline_style(
						'#' . $id . '.' . $section_name . ' .listed-post',
						array(
							'min-width'  => $minimum_width_of_posts_rem,
						)
					);
// /Section Layout

// Start Query.
if ( $type_of_archive === 'posts' ) :
	query_posts(
		array(
			'orderby' => 'date',
			'order' => 'DESC' ,
			'showposts' => $posts_to_get
			)
	);
	ob_start();
	if ( have_posts() ) :
		
		while ( have_posts() ):
			the_post();
			get_template_part( 'template-parts/content-archive', get_post_type() );
		
		endwhile;
	endif;

	wp_reset_query();
	$render =  ob_get_clean();
endif; // endif ( $type_of_archive === 'posts' ) :

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
	$category_archive_id = $id . '-category-archive';
	ob_start();
	?>
	<div id="<?php echo $category_archive_id; ?>" class="post-archive posts-list">
		<?php
		foreach ( $categories as $category ) :
			$category_id        = $category->term_id;
			$category_uid       = $category_archive_id . '-cat-' .  $category_id;
			$category_link = esc_url( get_category_link( $category_id ) );
			$category_title = esc_html( $category->name );
			$category_description = esc_html( $category->description );
			$category_thumbnail = get_field( 'category_thumbnail', $category );
			$thumbnail_img = null;
			if ( $category_thumbnail ) :
				$thumbnail_id  = $category_thumbnail['ID'];
				$thumbnail_img = wp_get_attachment_image( 
													$thumbnail_id,
													'archive-thumbnail',
													false,
													array(
														'title' => $category_title
													)
												);
			endif;// endif ( $category_thumbnail ) :
			
			?>
			<div id="<?php echo $category_uid; ?>" class="listed-post">
				<div class="card-style display-flex flex-column listed-post-inner">
					<?php if ( $thumbnail_img ) : ?>
						<div class="featured-image">
							<a href="<?php echo $category_link; ?>" class="post-link" rel="bookmark">
								<?php echo $thumbnail_img; ?>
							</a>
						</div>
					<?php endif; ?>
					<div class="post-body display-flex flex-column">
						<header class="entry-header">
							<a class="post-link" href="<?php echo $category_link; ?>" rel="bookmark">
								<h5 class="entry-title"><?php echo $category_title; ?></h5>
							</a>
							<?php if ( $type_of_archive === 'posts' ) : ?>
								<div class="post-date">
									<?php
									// TODO: ensure this function exists in xten-sections.
									xten_posted_on();
									?>
								</div>
							<?php endif; // endif ( $type_of_archive === 'posts' ) : ?>
						</header><!-- /.entry-header -->
						<?php if ( $category_description ) : ?>
							<div class="entry-content">
								<?php echo $category_description; ?>
							</div>
						<?php endif; // if ( $category_description ) : ?>
						<footer class="entry-footer xten-highlight-font">
							<a href="<?php echo $category_link; ?>" class="post-link" title="<?php echo $category_title; ?>">
								<button class="btn btn-theme-style theme-style-dark" type="button">Read More</button>
							</a>
						</footer>
					</div><!-- /.post-body -->
				</div><!-- /.listed-post-inner -->
			</div><!-- /#<?php echo $category_uid; ?> -->
			<?php
			// var_dump($category);
		endforeach;
		?>
	</div><!-- /#<?php echo $category_archive_id; ?> -->
	<?php
	$content = ob_get_clean();
endif; // endif ( $type_of_archive === 'categories' ) :

// Render Section
$id          = esc_attr( $id );
$className   = esc_attr( $className );
$block_attrs = esc_attr( $block_attrs );
?>
<section id="<?php echo $id; ?>" class="xten-section <?php echo $className; ?>" <?php echo $block_attrs; ?>>
	<?php if ( $content ) : ?>
		<div class="<?php echo $container_class; ?> container-<?php echo esc_attr( $section_name ); ?>">
			<div class="xten-content">
				<?php echo $content; ?>
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