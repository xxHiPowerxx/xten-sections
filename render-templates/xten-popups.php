<?php
/**
 * Get Popup Settings and Render Modal
 *
 * @package xten-sections
 */
function xten_popups() {
	function render_xten_popups( $modal_ids ) {
		the_row();
		$row_layout = get_row_layout();
		$args       = array();
		// common between simplified_popup and customized_popup.
		$row_index             = get_row_index();
		$popup_id              = esc_attr( get_sub_field( 'popup_id' ) );
		$modal_ids[$row_index] = $popup_id;
		$args['modal_id']      = $popup_id;
		$args['modal_size']    = get_sub_field( 'popup_size' );
		// Case: Paragraph layout.
		if ( $row_layout == 'simplified_popup' ) :
			$popup_title      = esc_attr( get_sub_field( 'popup_title' ) );
			$popup_sub_title  = esc_attr( get_sub_field( 'popup_sub_title' ) );
			$popup_content    = xten_kses_post( get_sub_field( 'popup_content' ) );
			ob_start();
			?>
			<div class="modal-content">
				<div class="modal-header">
					<div class="modal-title-wrapper">
						<?php if ( $popup_title ) : ?>
							<h2 class="modal-title"><?php echo $popup_title; ?></h5>
						<?php endif; ?>
						<?php if ( $popup_sub_title ) : ?>
							<h3 class="modal-sub-title"><?php echo $popup_sub_title; ?></h3>
						<?php endif; ?>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<?php if ( $popup_content ) : ?>
					<div class="modal-body">
						<?php echo $popup_content; ?>
					</div>
				<?php endif;

				// Check for Popup Buttons Repeater.
				if ( have_rows( 'popup_buttons_repeater' ) ) :
					$args['buttons'] = array();
					?>
					<div class="modal-footer">
						<?php
						while ( have_rows( 'popup_buttons_repeater' ) ) :
							the_row();
							$button_text                 = esc_attr( get_sub_field( 'button_text' ) );
							$button_link                 = esc_url( get_sub_field( 'button_link' ) );
							$close_popup_on_button_click = get_sub_field( 'close_popup_on_button_click' ) ?
								'modal' :
								null;
							$button_attributes           = array(
								'type'  => 'button',
								'class' => 'btn btn-primary',
								'data-dismiss' => $close_popup_on_button_click,
								'style' => '',
							);

							$button_background_color_group = get_sub_field( 'button_background_color_group' );
							if (
								$button_background_color_group['button_background_color'] &&
								$button_background_color_group['button_background_color_opacity']
							) :
								$button_background_color = convert_hex_to_rgb(
									$button_background_color_group['button_background_color'],
									$button_background_color_group['button_background_color_opacity']
								);
								$button_attributes['style'] = "background-color: $button_background_color;";
							endif;

							$button_text_color_group = get_sub_field( 'button_text_color_group' );
							if (
								$button_text_color_group['button_text_color'] &&
								$button_text_color_group['button_text_color_opacity']
							) :
								$button_text_color = convert_hex_to_rgb(
									$button_text_color_group['button_text_color'],
									$button_text_color_group['button_text_color_opacity']
								);
								$button_attributes['style'] .= "color: $button_text_color;";
							endif;

							if ( have_rows( 'button_attributes_repeater' ) ) :
								while ( have_rows( 'button_attributes_repeater' ) ) :
									the_row();
									$button_attribute_name  = esc_attr( get_sub_field( 'button_attribute_name' ) );
									if ( $button_attribute_name ) :
										// add to instead of overwriting class or style attribute.
										if (
											$button_attribute_name === 'class' ||
											$button_attribute_name === 'style'
										) :
											$button_attributes[$button_attribute_name] .= ' ' . esc_attr ( get_sub_field( 'button_attribute_value' ) );
										else:
											$button_attributes[$button_attribute_name] = esc_attr( get_sub_field( 'button_attribute_value' ) );
										endif;
									endif;
								endwhile;
							endif;

							$button_attributes_s = xten_stringify_attrs( $button_attributes );
							$link_tag_open       = null;
							$link_tag_closed     = null;
							if ( $button_link ) :
								$link_tag_open   = "<a href=\"$button_link\" class=\"anchor-modal-btn\" >";
								$link_tag_closed = "</a>";
							endif;
							echo $link_tag_open;
							?>
							<button <?php echo $button_attributes_s; ?>><?php echo $button_text; ?></button>
							<?php
							echo $link_tag_closed;
						endwhile; // endwhile ( have_rows( 'popup_buttons_repeater' ) ) :
						?>
					</div>
					<?php
				endif; // endif ( have_rows( 'popup_buttons_repeater' ) ) :
				?>

					<!-- <button type="button" class="btn btn-primary">Save changes</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
				</div>

			</div>

			<?php
			$args['modal_content'] = ob_get_clean();

		elseif ( $row_layout == 'customized_popup' ) :
			$args['modal_content'] = xten_kses_post( get_sub_field( 'popup_html', false ) );
		endif; // endif ( $row_layout == 'simplified_popup' ) :

		if ( $args['modal_content'] ) :
			echo xten_sections_render_component( 'modal', $args );
		endif;

		return $modal_ids;
	}
	/*   Sitewide Popups   */
	if ( have_rows( 'popups_fc', 'options' ) ) :
		// Start Modal IDs Array.
		$site_wide_modal_ids = array();

		// Loop through rows.
		while ( have_rows( 'popups_fc', 'options' ) ) :
			$site_wide_modal_ids = render_xten_popups( $site_wide_modal_ids );
		endwhile; // endwhile ( have_rows( 'popups_fc', 'options' ) ) :

		// Send Modal IDs to local script.
		wp_localize_script( 'xten-component-modal-js', 'siteWideModalIds', $site_wide_modal_ids );

	endif; // endif ( have_rows( 'popups_fc', 'options' ) ) :
		
	/*   /Sitewide Popups   */

	/*   Page Specific Popups   */
	if ( have_rows( 'popups_fc' ) ) :
		// Start Modal IDs Array.
		$modal_ids = array();

		// Loop through rows.
		while ( have_rows( 'popups_fc' ) ) :
			$modal_ids = render_xten_popups( $modal_ids );
		endwhile; // endwhile ( have_rows( 'popups_fc' ) ) :

		// Send Modal IDs to local script.
		wp_localize_script( 'xten-component-modal-js', 'modalIds', $modal_ids );

	endif; // endif ( have_rows( 'popups_fc' ) ) :
	/*   /Page Specific Popups   */
}
add_action('wp_footer', 'xten_popups');