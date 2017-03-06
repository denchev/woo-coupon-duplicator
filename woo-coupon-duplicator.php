<?php

/*
	Plugin Name: WooCommerce Coupon Duplicator
	Plugin URI: https://htmlpet.com
	Description: Use pre-existing WooCommerce/Wordpress events to trigger coupon duplication and execute an action
	Version: 1.0.0
	Author: HTML Pet Ltd
	Author URI: https://htmlpet.com
	License: GPLv3
	License URI: https://www.gnu.org/licenses/gpl-3.0.html
	Text Domain: woo-coupon-duplicator
*/

$is_woocommerce_active = in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
$is_woocd_active = in_array( 'woo-coupon-duplicator/woo-coupon-duplicator.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );

// Load plugin logic if WooCommerce is active as well
if ( $is_woocommerce_active ) {

	// ... and loaded
	add_action('woocommerce_loaded', function() {

		require_once __DIR__ . "/inc/generate-coupon.php";
		require_once __DIR__ . "/settings.php";

		require_once __DIR__ . "/actions.php";
	});
} else {

	function woocd_admin_notices_woocommerce_not_active() {
		?>
		<div class="notice notice-error">
			<p><?php echo __('You need to activate WooCommerce for the WooCommerce Coupon Duplicator plugin to work.', 'woo-coupon-duplicator') ?></p>
		</div>
		<?php
	}

	if( $is_woocd_active ) { 
		add_action('admin_notices', 'woocd_admin_notices_woocommerce_not_active');
	}
}