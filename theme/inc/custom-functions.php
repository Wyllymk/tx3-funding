<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Tx3_Funding
 */
// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

add_filter('woocommerce_checkout_fields', 'tx3_checkout_fields');
function tx3_checkout_fields($fields) {
    // Remove unnecessary fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
    unset($fields['shipping']['shipping_first_name']);
    unset($fields['shipping']['shipping_last_name']);
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_address_1']);
    unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_postcode']);

    // Add a new checkbox field for email updates
    $fields['billing']['signup_for_updates'] = array(
        'type'     => 'checkbox',
        'label'    => __('Sign me up to receive email updates and news', 'woocommerce'),
        'required' => false,
        'class'    => array('form-row-wide'),
        'clear'    => true,
    );

    return $fields;
}

// Add password field to the checkout page
add_action('woocommerce_after_checkout_billing_form', 'tx3_add_password_field');
function tx3_add_password_field($checkout) {
    echo '<div id="tx3_password_field">';
    woocommerce_form_field('account_password', array(
        'type'        => 'password',
        'label'       => __('Create Account Password', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-wide'),
        'placeholder' => __('Enter a password for your account', 'woocommerce'),
    ), $checkout->get_value('account_password'));
    echo '</div>';
}

// Save the password field value and create the user account
add_action('woocommerce_checkout_update_user_meta', 'tx3_save_password_field');
function tx3_save_password_field($user_id) {
    if (!empty($_POST['account_password'])) {
        wp_set_password($_POST['account_password'], $user_id);
    }
}

// Save the checkbox field value
add_action('woocommerce_checkout_update_user_meta', 'tx3_save_checkbox_field');
function tx3_save_checkbox_field($user_id) {
    if (!empty($_POST['signup_for_updates'])) {
        update_user_meta($user_id, 'signup_for_updates', sanitize_text_field($_POST['signup_for_updates']));
    }
}

function restrict_cart_to_single_product($cart_item_data, $product_id) {
    // Get the current cart instance
    $cart = WC()->cart;

    // Clear the cart if it contains any products
    if (!empty($cart->get_cart())) {
        $cart->empty_cart();
    }

    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'restrict_cart_to_single_product', 10, 2);

// add_action('template_redirect', 'check_login_and_redirect');

function check_login_and_redirect() {
    if (!is_user_logged_in()) {
        $current_page = get_current_page_type();
        
        // List of restricted pages/post types
        $restricted_pages = array(
            'shop',
            'cart',
            'home',
            'account',
            'product'
        );
        
        if (in_array($current_page, $restricted_pages)) {
            wp_redirect( site_url('/coming-soon/'));
            exit;
        }
    }
}

function get_current_page_type() {
    global $post;
    
    if (is_shop()) return 'shop';
    if (is_cart()) return 'cart';
    if (is_front_page()) return 'home';
    if (is_account_page()) return 'account';
    if (is_product()) return 'product';
    
    return '';
}

add_filter ('woocommerce_add_to_cart_redirect', function( $url, $adding_to_cart ) {
    return wc_get_checkout_url();
}, 10, 2 ); 