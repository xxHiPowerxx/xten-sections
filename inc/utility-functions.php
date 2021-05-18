<?php
/**
 * File used for Registering Section Blocks
 *
 * @link https://www.advancedcustomfields.com/resources/acf_register_block_type/
 *
 * @package xten-sections
 */


/**
 * Safely Get FileMTime
 */
if ( ! function_exists( 'xten_filemtime' ) ) :
	function xten_filemtime($file) {
		if ( file_exists( $file ) ):
			return filemtime( $file );
		endif;
	}
endif;
/**
 * Get Section Asset File
 */
if ( ! function_exists( 'xten_section_asset_file' ) ) :
	function xten_section_asset_file($section_name, $file_type) {
		return 'assets/' . $file_type . '/' . $section_name . '.' . $file_type;
	}
endif; // endif ( ! function_exists( 'xten_section_asset_file' ) ) :
/**
 * Register assets with naming convention.
 * 
 * @param string $section_name - Section's Name EG: 'section-hero'.
 * @param array $depenencies - Dependencies for css and js
 * EG: array('css' => null,'js' => array('jquery'))
 */
if ( ! function_exists( 'register_section_assets' ) ) :
	function register_section_assets($section_name, $dependencies) {
		if (! $dependencies['css'] ) :
			$dependencies['css'] = array();
		endif;
		if (! $dependencies['js'] ) :
			$dependencies['js'] = array();
		endif;
		if ( ! function_exists( 'register_asset' ) ) :
			function register_asset($section_name, $dependencies, $file_type) {
				$section_name       = 'xten-' . $section_name;
				$section_asset_file = xten_section_asset_file($section_name, $file_type);
				$dir_location       = $GLOBALS['xten-sections-dir'] . $section_asset_file;
				if ( file_exists( $dir_location ) ) :
					if ( $file_type === 'css' ) :
						wp_register_style(
							$section_name . '-' . $file_type,
							$GLOBALS['xten-sections-uri'] . $section_asset_file,
							$dependencies[$file_type],
							xten_filemtime( $dir_location )
						);
					endif;
					if ( $file_type === 'js' ) :
						wp_register_script(
							$section_name . '-' . $file_type,
							$GLOBALS['xten-sections-uri'] . $section_asset_file,
							$dependencies[$file_type],
							xten_filemtime( $dir_location ),
							true
						);
					endif;
				endif; // endif ( file_exists( $dir_location ) ) :
			}
		endif; // endif ( ! function_exists( 'register_asset' ) ) :
		register_asset($section_name, $dependencies, 'css');
		register_asset($section_name, $dependencies, 'js');
	}
endif; // endif ( ! function_exists( 'register_section_assets' ) ) :

/**
 * Register assets with naming convention.
 * 
 * @param string $section_name - Section's Name EG: 'section-hero'.
 */
if ( ! function_exists( 'xten_enqueue_assets' ) ) :
	function xten_enqueue_assets( $section_name ) {
		$section_name_css = $section_name . '-css';
		$section_name_js = $section_name . '-js';
		if ( wp_style_is( $section_name_css, 'registered' ) ) :
			wp_enqueue_style($section_name_css);
		endif;
		if ( wp_script_is( $section_name_js, 'registered' ) ) :
			wp_enqueue_script($section_name_js);
		endif;
	}
endif; // endif ( ! function_exists( 'register_section_assets' ) ) :

/**
 * Function Adds Block Attributes
 * 
 * @param string $attr_name - Attribute Name (will add data- prefix if not included).
 * @param string $attr_val - Attribute Value
 */
if ( ! function_exists( 'xten_add_block_attr' ) ) :
	function xten_add_block_attr( $attr_name, $attr_val ) {
		$data_prefix = 'data-';
		$has_data_prefix = substr($attr_name, 0, strlen($data_prefix)) === $data_prefix;
		if ( ! $has_data_prefix ) :
			$attr_name = $data_prefix . $attr_name;
		endif;
		$attr = $attr_name . '=' . $attr_val . '';
		return ' ' . esc_attr( $attr );
	}
endif; // endif ( ! function_exists( 'xten_add_block_attr' ) ) :

if ( ! function_exists( 'xten_stringify_attrs' ) ) :
	/**
	 * Convert Attributes array into String
	 * 
	 * @param array $attr_array - The Attributes 
	 * @param bool (optional) $sanitize - default = true - Optionally Sanitize Output. 
	 * @return string - The Attributes as name-value pairs for HTML.
	 */
	function xten_stringify_attrs( $attr_array, $sanitize = true ) {
		$attr_string = '';
		foreach ($attr_array as $key => $value) :
			if ( $value !== null ) :
				if ( $sanitize ) :
					$value = esc_attr( $value );
				endif;
				$space = $key !== reset( $attr_array ) ?
					' ' :
					null;
				$attr_string.= "$space$key='$value'";
			endif;
		endforeach;
		return $attr_string;
	}
endif; // /endif ( ! function_exists( 'xten_stringify_attrs' ) ) :

/**
 * Convert Hex to RGB
 *
 * @param string $hex_string hex value from customizer.
 * @param string $opacity opacity value from customizer.
 */
if ( ! function_exists( 'convert_hex_to_rgb' ) ) :
	function convert_hex_to_rgb( $hex_string, $opacity = null ) {
		if ( null !== $opacity ) :
				$opacity = $opacity / 100;
		endif;

		list($r, $g, $b) = sscanf( $hex_string, '#%02x%02x%02x' );
		if ( 0 === $opacity || $opacity ) :
			return "rgba({$r}, {$g}, {$b}, {$opacity})";
		else :
				return "rgb({$r}, {$g}, {$b})";
		endif;
	}
endif; // endif ( ! function_exists( 'convert_hex_to_rgb' ) ) :

if ( ! function_exists( 'xten_add_inline_style' ) ) :
	/**
	 * Add Inline Style
	 *
	 * @param string $selector - Selector for Style Rule
	 * @param array $rule_array - opacity value from customizer.
	 * @param string $validator - optional value for validation checking.
	 * @param string|array $media_query - optional value for Media Query Breakpoint.
	 * @return string $rule - The completed Style Rule.
	 * 
	 */
	function xten_add_inline_style(
		$selector,
		$rule_array,
		$validator = true,
		$media_query = null
	) {
		if ( $validator ) :
			$rule = $selector . '{';
			foreach ( $rule_array as $property => $value ) :
				$rule .=	$property . ':' . $value . ';';
			endforeach;
			$rule .= '}';
			if ( $media_query ) :
				// Check if $media_query is string or array.
				if ( is_array( $media_query ) ) :
					$media_string = '@media ';
					foreach ( $media_query as $single_media_query ) :
						// Check if first $single_media_query.
						if ( $single_media_query === reset( $media_query ) ) :
							$media_string .= '(' . $single_media_query . ')';
						else :
							$media_string .= ' and (' . $single_media_query . ')';
						endif;
					endforeach;
					$media_string .= '){' .
						$rule .
					'}';
					$rule = $media_string;
				endif;
				if ( is_string( $media_query ) ) :
				$rule = '@media (' . $media_query . '){' .
									$rule .
								'}';
				endif;
			endif;
			return $rule;
		endif;
	}
endif; // endif ( ! function_exists( 'xten_add_inline_style' ) ) :

/**
 * Prints HTML with meta information for the current post-date/time.
 */
if ( ! function_exists( 'xten_posted_on' ) ) :
	function xten_posted_on( $post = null ) {
		$post = get_post( $post );
		if ( ! $post ) {
				return false;
		}
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U', $post ) !== get_the_modified_time( 'U', $post ) ) :
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		endif;

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c', $post ) ),
			esc_html( get_the_date( '', $post) ),
			esc_attr( get_the_modified_date( 'c', $post ) ),
			esc_html( get_the_modified_date('', $post) )
		);

		$href_attr = '#';
		if ( $post !== get_queried_object() ) :
			$href_attr = 'href="' . esc_url( get_permalink($post) ) . '"';
		endif;

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html( '%1$s', 'post date', 'xten' ),
			'<a ' . $href_attr . ' rel="bookmark">' . $time_string . '</a>'
		);

		return '<span class="posted-on">' . $posted_on . ' </span>'; // WPCS: XSS OK.

	}
endif; // endif ( ! function_exists( 'xten_posted_on' ) ) :

/**
 * Trim String and use Excerpt apply ellipsis on end.
 *
 * @param string $string - String to be trimmed.
 * @param int $max_words - Number of words before string is trimmed.
 */
if ( ! function_exists( 'xten_trim_string' ) ) :
	function xten_trim_string($string, $max_words) {
		$stripped_content                = strip_tags( $string );
		$excerpt_length                  = apply_filters( 'excerpt_length', $max_words );
		$excerpt_more                    = apply_filters( 'excerpt_more', ' [...]' );
		return wp_trim_words( $stripped_content, $excerpt_length, $excerpt_more );
	}
endif; // endif ( ! function_exists( 'xten_trim_string' ) ) :

/**
 * Get Icon from Global Variable
 *
 * @param string $section_name - Section Name .
 * @return string
 */
if ( ! function_exists( 'xten_get_icon' ) ) :
	function xten_get_icon( $section_name ) {
		$placeholder_icon = $GLOBALS['xten-section-icon']['xten-sections'];
		return $GLOBALS['xten-section-icon'][$section_name] ?: $placeholder_icon;
	}
endif; // endif ( ! function_exists( 'xten_get_icon' ) ) :

/**
 * Global Block Configuration
 * 
 * This function uses the global field group XTen Section Block Configuration
 *
 * @param string $section_name - Section Name .
 * @return string
 */
if ( ! function_exists( 'xten_section_block_config' ) ) :
	function xten_section_block_config( $id, $section_name ) {
		$selector = '[data-s-id="' . $id . '"].' . $section_name;
		$groups = array();
		$groups['margins_group']          = get_field( 'margins_group' );
		$groups['borders_group']          = get_field( 'borders_group' );
		$groups['paddings_group']         = get_field( 'paddings_group' );
		$groups['background_color_group'] = get_field( 'background_color_group' );
		$groups['text_color_group']       = get_field( 'text_color_group' );

		$style_array   = array();
		foreach ( $groups as $group ) :
			if ( $group ) :
				foreach ( $group as $rule => $value ) :
					if ( $value ):
						$is_color   = strpos( $rule, 'color' );
						$is_opacity = strpos( $rule, 'opacity' );
						$is_border  = strpos( $rule, 'border' );
						if ( ! $is_color ) :
							$_rule = str_replace('_', '-', $rule );
							$style_array[$_rule] = $value;
						elseif ( false !== $is_color && ! $is_opacity ) :
							$rule_singular = str_replace( 'color', '', $rule );
							$rule_plural   = $rule_singular . 's';
							$rule_color_opacity_value = $groups[ $rule_plural . '_group' ][ $rule_singular . '_color_opacity' ];
							// Remove 'text_' from text_color.
							$__rule = str_replace('text_', '', $rule );
							// Change '_' to '-'.
							$_rule = str_replace('_', '-', $__rule );
							$rule_color_rgba = convert_hex_to_rgb($value, $rule_color_opacity_value);
							$style_array[$_rule] = $rule_color_rgba;
						endif;
					endif;
				endforeach; // /endforeach ( $group as $rule => $value ) :
			endif;
		endforeach; // /endforeach ( $groups as $group ) :

		$global_styles = xten_add_inline_style($selector, $style_array);
		return $global_styles;
	}
endif; // endif ( ! function_exists( 'xten_section_block_config' ) ) :

/**
 * XTen Section Block BoilerPlate.
 * 
 * This function will be called at the end of every Render Template
 *
 * @param string $section_name - Section Name .
 * @return string
 */
if ( ! function_exists( 'xten_section_boilerplate' ) ) :
	function xten_section_boilerplate( $id, $section_name, $styles = null ) {
		$styles .= xten_section_block_config( $id, $section_name );
		wp_register_style( $id, false );
		wp_enqueue_style( $id );
		wp_add_inline_style( $id, $styles );

		if ( is_admin() ) :
			$section_asset_css_file = xten_section_asset_file($section_name, 'css');
			$section_asset_js_file  = xten_section_asset_file($section_name, 'js');
			
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
		return;
	}
endif; // endif ( ! function_exists( 'xten_section_boilerplate' ) ) :
if ( ! function_exists( 'xten_wide_tall_image' ) ) :
	/**
	 * Determine if image is wider than tall or vice-versa.
	 * @param int|array $arg - Can be image ID or Size array(width,height).
	 * @return string 'object-fit-cover ' . 'wide' || 'tall' || 'square'.
	 */
	function xten_wide_tall_image( $arg ) {
		if ( ! $arg ) :
			return;
		endif;
		$return_result = 'object-fit-cover ';
		$image_id      = null;
		$size          = null;
		$image_width   = null;
		$image_height  = null;
		if ( is_int( $arg ) ) :
			$image_id = $arg;
		endif;
		if ( is_array( $arg ) ) :
			$size = $arg;
		endif;
		if ( $image_id ) :
			$image_details = wp_get_attachment_image_src( $image_id, 'full' );
			$image_width   = $image_details[1];
			$image_height  = $image_details[2];
		endif;
		if ( $size ) :
			$image_width  = $size[0];
			$image_height = $size[1];
		endif;
		if ( $image_width > $image_height ) :
			$return_result .= 'wide';
		elseif ( $image_width < $image_height ) :
			$return_result .= 'tall';
		else:
			$return_result .= 'square';
		endif;
		return $return_result;
	}
endif; // if ( ! function_exists( 'xten_wide_tall_image' ) ) :

if ( ! function_exists( 'xten_get_optimal_image_size' ) ) :
	/**
	 * Get Optimal image size when only one dimension is provided
	 * 
	 * @param int $image_id - Post ID for image.
	 * @param array $size - $size[0] = width and $size[1] = height.
	 * @param array $aspect_ratio - $aspect_ratio[0] = Aspect Ratio Width
	 * $aspect_ratio[1] = Aspect Ratio Height
	 * Desired aspect ratio is used to calculate
	 * the null dimension in $size array. Default is 16/9.
	 * @return array $size_array returns provided $size and calculated $size.
	 */
	function xten_get_optimal_image_size(
		$image_id     = null,
		$size         = array( null, null ),
		$aspect_ratio = array( 16, 9 )
	) {
		if ( ! $image_id ) :
			return false;
		endif;
		$size_array = array();
		// Get Image dimensions.
		$image_details       = wp_get_attachment_image_src( $image_id, 'full' );
		$image_width         = $image_details[1];
		$image_height        = $image_details[2];

		// Determine whether min_height is at least 56.25% of min_width
		$min_width           = $size[0];
		$min_height          = $size[1];
		$aspect_ratio_width  = $aspect_ratio[0];
		$aspect_ratio_height = $aspect_ratio[1];

		// If $min_width was provided we need to calculate Height.
		if ( $min_width ) :
			$provided_min_dimension    = $min_width;
			$actual_provided_dimension = $image_width;
			$actual_missing_dimension  = $image_height;
			$aspect_ratio_dividend     = $aspect_ratio_height;
			$aspect_ratio_divisor      = $aspect_ratio_width;
			$provided_dimension_index  = 0;
			$missing_dimension_index   = 1;
		else:
			$provided_min_dimension    = $min_height;
			$actual_provided_dimension = $image_height;
			$actual_missing_dimension  = $image_width;
			$aspect_ratio_dividend     = $aspect_ratio_width;
			$aspect_ratio_divisor      = $aspect_ratio_height;
			$provided_dimension_index  = 1;
			$missing_dimension_index   = 0;
		endif;
		$optimal_provided_dimension  = $actual_provided_dimension;

		$aspect_ratio_multiplicand   = $aspect_ratio_dividend / $aspect_ratio_divisor;

		// Whichever value was not provided (height or width) needs to be calculated.
		$calc_missing_dimension      = $provided_min_dimension *
			$aspect_ratio_multiplicand;

		$calc_min_missing_dimension  = $calc_missing_dimension /
			$provided_min_dimension *
			$actual_missing_dimension;

		$calc_min_provided_dimension = $actual_provided_dimension /
			$actual_missing_dimension *
			$calc_missing_dimension;

		if ( $calc_min_provided_dimension < $provided_min_dimension ) :
			$optimal_missing_dimension = $calc_missing_dimension /
				$calc_min_provided_dimension *
				$provided_min_dimension;
		endif;

		$size_array[$provided_dimension_index] = $optimal_provided_dimension;
		$size_array[$missing_dimension_index]  = $optimal_missing_dimension;

		return $size_array;
	}
endif; // endif ( ! function_exists( 'xten_get_optimal_image_size' ) ) :

if ( ! function_exists( 'xten_kses_post' ) ) :
	/**
	 * Sanitizes string but leaves support for SVGs.
	 * @param string $string to be sanitized.
	 * @return string Sanitized string with SVG support.
	 */
	function xten_kses_post( $string ) {
		$kses_defaults = wp_kses_allowed_html( 'post' );
		$svg_args = array(
			'svg' => array(
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
			'path'  => array( 'd' => true, 'fill' => true, ),
		);
		$allowed_tags = array_merge( $kses_defaults, $svg_args );

		foreach ( $allowed_tags as $key=>$allowed_tag ) :
			$tabindex = array(
				'tabindex' => true,
			);
			$allowed_tags[$key] = array_merge( $allowed_tag, $tabindex );
		endforeach;

		return wp_kses( $string, $allowed_tags );
	}
endif; // endif ( ! function_exists( 'xten_kses_post' ) ) :

if ( ! function_exists( 'xten_get_icon_fc' ) ) :
	function xten_get_icon_fc( $row_layout ) {
		$icon = null;

		if ( $row_layout === 'font_awesome_icon' ) :
			$fa_handle = esc_attr( get_sub_field( 'font_awesome_icon_handle' ) );
			if ( $fa_handle ) :
				$icon = '<i class="fa fa-' . $fa_handle . '"></i>';
			endif;
		endif; // endif ( $row_layout === 'font_awesome_icon' ) :

		if ( $row_layout === 'svg' ) :
			$svg_path = get_sub_field( 'svg_path' );
			if ( $svg_path ) :
				$whole_path = get_stylesheet_directory() . $svg_path;
				if ( file_exists( $whole_path ) ) :
					$icon = file_get_contents( $whole_path );
				endif;
			endif;
		endif; // endif ( $row_layout === 'svg' ) :

		if ( $row_layout === 'bitmap' ) :
			$image = get_sub_field( 'image' );
			if ( $image ) :
				$icon = wp_get_attachment_image(
					$image['id'],
					xten_get_optimal_image_size(null, 70), true
				);
			endif;
		endif; // endif ( $row_layout === 'bitmap' ) :

		return $icon;
	}
endif; // endif ( ! function_exists( 'xten_get_icon_fc' ) ) :

if ( ! function_exists( 'xten_sections_render_component' ) ) :
	/**
	 * Render Markup for Component.
	 * Function will attempt to get required file to render Component
	 * 
	 * @param string $handle name of handle will be used to find correct file.
	 * @param mixed array|string $post_id optional post or array of posts of data being passed.
	 * @return string rendered markup as string.
	 */
	function xten_sections_render_component( $handle, $post_id = null ) {
		$file_path = $GLOBALS['xten-sections-dir'] . '/render-templates/components/';
		$file_name = 'component-' . $handle . '.php';
		$file_path = $file_path . $file_name;
		if ( file_exists( $file_path ) ) :
			require_once( $file_path );
			$handle_snake_case = str_replace('-', '_', $handle );
			$component_func = 'component_' . $handle_snake_case;
			if ( function_exists( $component_func ) ) :
				return $component_func( $post_id );
			endif;
		endif;
	}
endif;  //endif ( ! function_exists( 'xten_sections_render_component' ) ) :

if ( ! function_exists( 'xten_register_component_id' ) ) :
	/**
	 * Create Component ID
	 * Function Increments Id based on handle
	 * @param string $handle name of handle.
	 * @return int component id.
	 */
	function xten_register_component_id( $handle ) {
		$GLOBALS['component_ids'][$handle] = $GLOBALS['component_ids'][$handle] !== null ?
			$GLOBALS['component_ids'][$handle] :
			0;
			$GLOBALS['component_ids'][$handle] ++;
			$component_id = $handle . '-' . $GLOBALS['component_ids'][$handle];

		return  $component_id;
	}
endif; // endif ( ! function_exists( 'xten_register_component_id' ) ) :

if ( ! function_exists( 'xten_get_reuseable_block' ) ) :
	/**
	 * Gets Reusable Block by String, Post Object, or ID.
	 * @param string|object|int $block - Block Title, Block Post Object, or Block Post ID.
	 * @return string Reusable Block Content.
	 */
	function xten_get_reuseable_block( $block ) {
		if ( is_string( $block ) ) :
			$reuseable_block = get_page_by_title( $block, OBJECT, 'wp_block' );
		elseif ( is_object( $block ) || is_numeric( $block ) ) :
			$reuseable_block = get_post( $block );
		else:
			return false;
		endif;
		$reuseable_block_content = apply_filters( 'the_content', $reuseable_block->post_content );
		return $reuseable_block_content;
	}
endif; // endif ( ! function_exists( 'xten_get_reuseable_block' ) ) :

if ( ! function_exists( 'xten_section_placeholder' ) ) :
	function xten_section_placeholder( $message = null ) {
		if ( is_admin() ) :
			$message = '<p>No Content Found,</p><p>Please Configure this Block via the Form in the Sidebar.</p>';
			$img_src = $GLOBALS['xten-sections-uri'] . 'assets/img/build.webp';
			ob_start();
			?>
			<div style="background-color:rgb(241,243,245);font-size:18px;padding:15px;text-align:center;width:830px;max-width:100%;">
				<img src="<?php echo $img_src; ?>" width="800" height="600" style="display:inline-block;max-width:100%;" />
				<div><?php echo $message; ?></div>
			</div>
			<?php
			$html = ob_get_clean();
			return $html;
		endif;
	}
endif; // endif ( ! function_exists( 'xten_section_placeholder' ) ) :

if ( ! function_exists( 'xten_has_content' ) ) :
	function xten_has_content( $validation ) {
		if ( ! is_array( $validation ) ) :
			return false;
		else:
			foreach ($validation as $key => $value) :
				if ( empty( $value) ) :
					unset( $validation[$key] );
				endif;
			endforeach;
			return ! empty( $validation );
		endif; // endif ( is_array( $validation ) ) :
	}
endif; // endif ( ! function_exists( 'xten_has_content' ) ) :

if ( ! function_exists( 'xten_snake_to_camel' ) ) :
	function xten_snake_to_camel( $string ) {
		return lcfirst( str_replace( ' ', '', ucwords( str_replace( '_', ' ', $string ) ) ) );
	}
endif; // endif ( ! function_exists( 'xten_snake_to_camel' ) ) :

if ( ! function_exists( 'xten_camel_to_snake' ) ) :
	function xten_camel_to_snake( $string ) {
		return strtolower( preg_replace( '/(?<!^)[A-Z]/', '_$0', $string) );
	}
endif; // endif ( ! function_exists( 'xten_camel_to_snake' ) ) :

if ( ! function_exists( 'xten_snake_to_dash' ) ) :
	function xten_snake_to_dash( $string ) {
		return str_replace( '_', '-', $string );
	}
endif; // endif ( ! function_exists( 'xten_snake_to_dash' ) ) :

if ( ! function_exists( 'xten_dash_to_snake' ) ) :
	function xten_dash_to_snake( $string ) {
		return str_replace( '-', '_', $string );
	}
endif; // endif ( ! function_exists( 'xten_dash_to_snake' ) ) :


if ( ! function_exists( 'filter_slick_defaults' ) ) :
	/**
	 * Filter Slick Defaults
	 * This function compares Slick Defaults to the Defined Slick Settings
	 * and removes them from the settings if they are equal to the Default.
	 * This function is necessary because certain settings cause bugs when defined
	 * EG: adaptiveHeight does not work when SlidesToShow is defined, even if it's 1, which is the default
	 *
	 * @param array - $settings - Post ID or Post Object.
	 * @return string - JSON object with Slider Settings.
	 */
	function filter_slick_defaults( $settings ) {
		// TODO: Add the rest of the Defaults for comparrison.
		// Refer to Field Group - Slider Configuration for more defaults.
		$defaults = array(
			'slides_to_show' => 1,
		);
		if ( ! is_array( $settings ) ) :
			return;
		endif;
		foreach ( $settings as $key=>$setting ) :
			if ( $settings[$key] == $defaults[$key] ) :
				unset( $settings[$key] );
			endif;
		endforeach;
		return $settings;
	}
endif; // endif ( ! function_exists( 'filter_slick_defaults' ) ) :

if ( ! function_exists( 'xten_slider_configuration' ) ) :
	/**
	 * Slider Configuration
	 * This function uses the Field Group - Slider Configuration
	 *
	 * @param int|object - $post - Post ID or Post Object.
	 * @return string - JSON object with Slider Settings.
	 */
	function xten_slider_configuration( $post = null ) {
		// $s is for settings.
		$s = array();

		// General
		$s['adaptive_height']     = get_field( 'adaptive_height', $post );
		$s['variable_width']      = get_field( 'variable_width', $post );
		$autoplay_group           = get_field( 'autoplay_group', $post );
		$s['autoplay']            = $autoplay_group['autoplay'];
		$s['autoplay_speed']      = $autoplay_group['autoplay_speed'];
		$s['pause_on_focus']      = $autoplay_group['pause_on_focus'];
		$s['pause_on_hover']      = $autoplay_group['pause_on_hover'];
		$s['pause_on_dots_hover'] = $autoplay_group['pause_on_dots_hover'];
		$s['css_ease']            = get_field( 'css_ease', $post );
		$s['speed']               = get_field( 'speed', $post );
		$s['as_nav_for']          = get_field( 'as_nav_for', $post );
		$center_mode_group        = get_field( 'center_mode_group', $post );
		$s['center_mode']         = $center_mode_group['center_mode'];
		$center_padding_group     = $center_mode_group['center_padding_group'];
		$s['center_padding']      = $center_padding_group['center_padding'] .
			$center_padding_group[' center_padding_unit'];
		$s['draggable']           = get_field( 'draggable', $post );
		$s['swipe']               = get_field( 'swipe', $post );
		$s['swipe_to_slide']      = get_field( 'swipe_to_slide', $post );
		$s['vertical']            = get_field( 'vertical', $post );
		$s['rtl']                 = get_field( 'rtl', $post );
		$s['wait_for_animate']    = get_field( 'wait_for_animate', $post );
		// /General

		// Nav
		$s['arrows']              = get_field( 'arrows', $post );
		$s['dots']                = get_field( 'dots', $post );
		// /Nav

		// Slides
		$s['fade']                = get_field( 'fade', $post );
		$s['focus_on_select']     = get_field( 'focus_on_select', $post );
		$s['infinite']            = get_field( 'infinite', $post );
		$s['initial_slide']       = get_field( 'initial_slide', $post );
		$s['slides_to_scroll']    = get_field( 'slides_to_scroll', $post );
		$s['slides_to_show']      = get_field( 'slides_to_show', $post ) ? : $d;
		// /Slides

		// Grid
		$s['rows']                = get_field( 'rows', $post );
		$s['slides_per_row']      = get_field( 'slides_per_row', $post );
		// /Grid

		// Responsive
		$s['respond_to']          = get_field( 'respond_to', $post );
		$s['responsive']          = get_field( 'responsive', $post );
		// /Responsive

		$s = filter_slick_defaults( $s );

		$settings = array();
		foreach( $s as $key => $value ) :
			$initial_slide;
			if ( $value !== '' && $value !== null ) :
				$js_key = xten_snake_to_camel( $key );
				if ( $key === 'vertical' && $value === true ) :
					$settings['verticalSwiping'] = $s['swipe'];
				endif;
				// initialSlide setting does not play nice with stringified interger value.
				// So we cannot use json_encode on this value.
				// Add it to the settings string the Hard way.
				if ( $key === 'initial_slide' ) :
					$initial_slide = ',"' . $js_key . '":' . $s['initial_slide'];
					continue;
				endif;
				if ( $key === 'resonsive' ) :
					if ( have_rows( 'responsive' ) ) :
						$value = '[';
						while ( have_rows( 'responsive' ) ) :
							the_row();
							$responsive_object = '{';
							$breakpoint = get_sub_field( 'breakpoint' );
							$responsive_object .= '},';
						endwhile;
					endif;
				endif; // endif ( $key === 'resonsive' ) :
				$settings[$js_key] = $value;
			endif; // endif ( $value !== '' && $value !== null ) :
		endforeach; // endforeach( $s as $setting ) :

		$settings = json_encode( $settings );

		// If $initial_slide is set we need to set attach it to the $settings string without
		// the value being json_encoded.
		if (
			$initial_slide &&
			substr( $settings, -1, 1 ) == '}'
		) :
			// Remove '}' from end of $settings.
			$settings = substr( $settings, 0, -1 );
			// Add $initial_slide string and re-close off object.
			$settings = $settings . $initial_slide . '}';
		endif; // endif $initial_slide.

		return $settings;
	}
endif; // endif ( ! function_exists( 'xten_slider_configuration' ) ) :

// add_action('wp_head', 'update_meta_values');
function update_meta_values() {

	/*
	 * Takes a set of fields and resets them as part of a repeater
	 * To start, create a new repeater field and enter the slug in $new_repeater
	 * Find the fields you would like to move in wp_posts and set the post_parent to the new repeater's ID
	 */

	// Slug of the new repeater
	$new_repeater = 'slides_repeater';
	
	// Set the fields you would like to move's slugs in $rows
	$rows = array(
		'minimum_height_group',
		'background_image_group',
		'background_color',
		'background_overlay_group',
		'content',
		'content_minimum_width_group',
		'content_maximum_width_group',
		'content_color',
		'content_background_group',
		'content_location_group',
	);

	// Query the effected posts
	$args = array(
		'post_type' => array( 'page' ),
		'posts_per_page' => -1
	);

	$posts = get_posts($args);

	foreach ($posts as $post) {

		// Update Meta Fields
		$id = $post->ID;
		if ( has_blocks( $post->post_content ) ) :
			$blocks = parse_blocks( $post->post_content );
			foreach( $blocks as $block ) :
				if ( $block['blockName'] === 'acf/xten-section-hero' ) :
					$meta = $block['attrs']['data'];
					// $meta = get_post_meta( $id );
					unset($updated);

					// Loop each posts Meta values
					foreach ( $meta as $key => $value ) {
			
						// Loop the possible fields
						foreach ( $rows as $row ) {
							var_dump($key);
							if ($post->post_title === 'Home'):
								end($rows);
								if ($row === key($rows)):
									// die;
								endif;
							endif;
							
							//Check if field name is in meta value
							// if ( strpos( $key, $row ) !== false ) {
							if ( $key === $row ) {
							// var_dump($meta);

								// var_dump($key, $row);
								// die;
								// Grab the value's name, prepend it with the repeater name.
								$new_post_meta = str_replace( $row, $new_repeater . '_0_' . $row , $key );

								// Replace old Content with New Content.
								$updated_content   = str_replace($row, $new_post_meta, $block['attrs']['data'] );
								$post_update_array = array(
									'ID'          => $id,
									'post_content'=> $updated_content
								);
								wp_update_post( $post_update_array, true );
								if (is_wp_error($post_id)) {
									$errors = $post_id->get_error_messages();
									foreach ($errors as $error) {
											echo $error;
									}
								}
								// Add new meta
								// 
								// update_post_meta( $id, $new_post_meta, $value[0] );
								
								// Delete old meta
								// delete_post_meta( $id, $key );
								$updated = true;
								break;
							}
						}
					}
			
					// If this post has repeater rows, mark it with 1
					if ($updated) {
						update_post_meta( $id, 'resource_list', 1 );
						update_post_meta( $id, '_resource_list', 'field_574ee7028384d' );
					}
				endif; //endif ( $block['blockName'] === 'acf/xten-section-hero' ) :
			endforeach; // endforeach( $blocks as $block ) :
		endif; // endif ( has_blocks( $post->post_content ) ) :

	}
}