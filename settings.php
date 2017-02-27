<?php

if( ! is_admin() ) {
	return;
}

if( ! function_exists( 'woocd_admin_menu' ) ) {

	function woocd_admin_menu() {

		add_submenu_page('woocommerce', __("Coupon Duplicator", "woo-coupon-duplicator"), __("Coupon Duplicator", "woo-coupon-duplicator"), 'manage_woocommerce', 'woo-coupon-duplicator', 'woocd_options_page');
	}
}

if( ! function_exists( 'woocd_options_page' ) ) {

	// Create options page in Settings
	function woocd_options_page() {
		global $wpdb;

		$coupons = $wpdb->get_results( "
			SELECT $wpdb->posts.ID, $wpdb->posts.post_title
			FROM $wpdb->posts
			WHERE $wpdb->posts.post_type = 'shop_coupon'
			AND $wpdb->posts.post_status = 'publish'
		 " );

		$selected_coupon_id = (int)get_option('woocd_coupon_source');

		?>
		<form method="post" action="options.php">
			<?php settings_fields( 'woocd' ); ?>
	    	<?php do_settings_sections( 'woocd' ); ?>

			<h3><?php echo __("Coupon duplicator", "woo-coupon-duplicator") ?></h3>

	    	<table class="form-table">
	    		<tr valign="top">
					<td><?php echo __('Coupon source', 'woocd') ?></td>
					<td><label for="coupon_source">
						<select name="woocd_coupon_source">
						<option><?php echo __('Select a coupon', 'woo-coupon-duplicator') ?></option>
						<?php foreach($coupons as $coupon) : ?>

							<?php
							$selected = "";
							if($coupon->ID == $selected_coupon_id) {
								$selected = ' selected="selected"';
							}
							?>

							<option value="<?php echo $coupon->ID ?>"<?php echo $selected ?>><?php echo esc_html($coupon->post_title) ?></option>
							<?php endforeach; ?>
						</select>
						</label>
					</td>
				</tr>
				<?php
				$hooks = esc_attr(get_option('woocd_hooks'));
				?>
				<tr valign="top">
					<td><?php echo __('Hooks', 'woo-coupon-duplicator') ?></td>
					<td><label for="hooks">
						<input id="hooks" type="text" name="woocd_hooks" value="<?php echo $hooks ?>">
						<br /><?php echo __( 'Comma separated list of hooks. Example: mc4wp_form_success', 'woo-coupon-duplicator') ?>
						</label>
					</td>
				</tr>

				<?php
				$subject = esc_attr(get_option('woocd_email_subject'));
				?>
				<tr valign="top">
					<td><?php echo __('Subject', 'woo-coupon-duplicator') ?></td>
					<td><label for="subject">
						<input id="subject" type="text" name="woocd_subject" value="<?php echo $subject ?>">
						<br /><?php echo __( 'Email subject.', 'woo-coupon-duplicator') ?>
						</label>
					</td>
				</tr>

				<?php
				$content = esc_attr(get_option('woocd_content'));
				?>
				<tr valign="top">
					<td><?php echo __('Content', 'woo-coupon-duplicator') ?></td>
					<td><label for="content">
						<textarea id="content" name="woocd_content" cols="100" rows="10"><?php echo $content ?></textarea>
						<br /><?php echo __( 'Email content.', 'woo-coupon-duplicator') ?>
						</label>
					</td>
				</tr>
	    	</table>
			<?php submit_button() ?>
		</form>
		<?php
	}

}

if( ! function_exists( 'woocd_register_settings' ) ) {

	function woocd_register_settings() {
		register_setting('woocd', 'woocd_coupon_source');
		register_setting('woocd', 'woocd_hooks');
	}

}

if( ! function_exists( 'woocd_admin_init' ) ) {

	function woocd_admin_init() {
		woocd_register_settings();
	}
}


add_action( "admin_menu", "woocd_admin_menu" );
add_action( 'admin_init', 'woocd_admin_init' );