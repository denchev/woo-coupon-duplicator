<?php

class Executors_Mailchimp extends AbstractExecutor
{
	public static $hooks = array('mc4wp_form_success');

	public function process()
	{
		add_filter('coupon_generator_customer_email', function() {
            return $_POST['EMAIL'];
        });

        add_filter('coupon_generator_expiry_date', function() {
            return CouponGenerator::getExpiryDate('+1 year');
        });

        $couponGenerator = new CouponGenerator();
        $coupon = $couponGenerator->duplicate('nsltr-2d1f19');

        $message = __("Hello lovely person,
            You are getting this email to let you know that you get a coupon for 10 percent off. 
            Your coupon code is: %s");

        $message = sprintf($message, $coupon->code);

        $to = $_POST['EMAIL'];

        wp_mail( $to, __('Your coupon code'), $message );
	}
}