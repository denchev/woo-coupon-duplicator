<?php

/**
 * Class CouponGenerator
 *
 * @author HTML Pet
 */
class CouponGenerator
{

    public static function getExpiryDate($time = '+1 year') {
        return date('Y-m-d', strtotime($time));
    }

    /**
     * @param $base_code_id
     * @return WC_Coupon
     */
    public function duplicate($base_code_id)
    {
        $coupon = new WC_Coupon($base_code_id);

        if($coupon->exists) {
            $code = $this->copy($coupon);
            
            return new WC_Coupon($code);
        }

        return false;
    }

    /**
     * @return array
     */
    private function getDataFields()
    {
        $data = array(
            'discount_type' => 'percent',
            'coupon_amount' => 10,
            'usage_limit'   => 1,
            'usage_limit_per_user'      => 1,
            'limit_usage_to_x_items'    => '',
            'individual_use'            => 'yes',
            'minimum_amount'            => 0,
            'maximum_amount'            => 10000000,
            'expiry_date'               => self::getExpiryDate(),
            'free_shipping'             => null, // Any other value will set it to true
            'exclude_sale_items'        => 'yes',
            'customer_email'            => '',
            'product_ids'               => '',
            'exclude_product_ids'       => '',
            'product_categories'        => array(),
            'exclude_product_categories' => array()
        );

        return $data;
    }

    /**
     * @return string
     */
    private function copy($source)
    {
        $code = $this->generateCode();

        $dataFields = $this->getDataFields();

        $newData = array();

        // Merge existing coupon data with the default data options
        foreach( $dataFields as $key => $value ) {

            if( property_exists($source, $key) && !empty($source->{$key}) ) {

                $newData[$key] = is_array($source->{$key}) ? implode(',', $source->{$key}) : $source->{$key};
            } else {

                $newData[$key] = $value;
            }
            
            // The coupon create method checks if the data exists not is value
            if($key == 'free_shipping' && $newData[$key] == 'no') {
                $newData[$key] = null;
            }

            // Copy the original product_ids and exclude_product_ids
            if(in_array($key, array('product_ids', 'exclude_product_ids'))) {
                $newData[$key] = get_post_meta($source->id, $key, true );
            }

            if(in_array($key, array('product_categories', 'exclude_product_categories'))) {

                if(is_array($newData[$key]) === false) {
                    $newData[$key] = explode(',', $newData[$key]);
                }
            }

            $newData[$key] = apply_filters('coupon_generator_' . $key, $newData[$key]);
        }

        $post_id = $this->createBaseCoupon($code);
        $post = get_post($post_id);

        foreach ($newData as $key => $value) {
            $_POST[$key] = $value;
        }

        WC_Meta_Box_Coupon_Data::save($post_id, $post);

        return $code;
    }

    /**
     * @return int|WP_Error
     * @param $coupon_code string
     */
    private  function createBaseCoupon($coupon_code)
    {
        // Generate a new Wordpress post to put the content into
        $postarr = array(
            'ID' => 0,
            'post_title'    => $coupon_code,
            'post_excerpt'  => __('Auto generate coupon code.'), // Acts as description
            'post_type'     => 'shop_coupon',
            'post_status'   => 'publish'
        );

        $post_id = wp_insert_post($postarr);

        return $post_id;
    }

    /**
     * @return string
     */
    private function generateCode()
    {
        $prefix = '';
        return $prefix . substr( md5( microtime() ), 0, 6 );
    }

    /**
     * Generates a new coupon with basic data
     *
     * @return string
     */
    public function generate()
    {
        return $this->copy(new stdClass());
    }
}