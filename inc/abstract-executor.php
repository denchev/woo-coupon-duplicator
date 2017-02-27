<?php

require_once ABSPATH .  "wp-content/plugins/woocommerce/includes/class-wc-cache-helper.php";
require_once ABSPATH .  "wp-content/plugins/woocommerce/includes/class-wc-coupon.php";

abstract class AbstractExecutor
{

	private $coupon;

	public function __construct()
	{
		$coupon_id = (int)get_option('woocd_coupon_source');
        $post = get_post($coupon_id);

        $this->coupon = new WC_Coupon($post->post_title);
	}

	protected function getCoupon()
	{
		return $this->coupon;
	}

	abstract public function process();
}