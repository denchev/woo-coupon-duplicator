<?php

/*
	Plugin Name: WooCommerce Coupon Duplicator
	Plugin URI: https://htmlpet.com
	Description: Use pre-existing WooCommerce/Wordpress events to trigger coupon duplication and execute an action
	Version: 1.0.0
	Author: HTML Pet Ltd
	Author URI: https://htmlpet.com
	License: GPLv2 or later
	Text Domain: woo-coupon-duplicator
*/

require_once __DIR__ . "/inc/generate-coupon.php";
require_once __DIR__ . "/settings.php";

require_once __DIR__ . "/actions.php";