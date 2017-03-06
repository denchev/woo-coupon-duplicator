<?php

class Executors_Mailchimp extends AbstractExecutor
{
	public static $hooks = array('mc4wp_form_success');

	public function process()
	{

        $subject = get_option('woocd_email_subject');
        $content = get_option('woocd_content');

		add_filter('coupon_generator_customer_email', function() {
            return sanitize_email($_POST['EMAIL']);
        });

        add_filter('coupon_generator_expiry_date', function() {
            return CouponGenerator::getExpiryDate('+1 year');
        });

        $couponGenerator = new CouponGenerator();
        $coupon = $couponGenerator->duplicate($this->getCoupon()->code);

        $message = $content;

        $message = sprintf($message, $coupon->code);

        $to = sanitize_email($_POST['EMAIL']);

        wp_mail( $to, $subject, $message );
	}
}