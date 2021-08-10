<?php
/**
 * Template for Checkout Billing Info, Customer Name and Email, and Payment Gateway Options
 *
 * This template can be overridden by copying it to yourtheme/download-manager/checkout-cart/checkout.php.
 *
 * @version     1.0.0
 */

if (!defined('ABSPATH')) die('!');

global $current_user;
$billing = $sbilling = array
(
    'first_name' => '',
    'last_name' => '',
    'company' => '',
    'address_1' => '',
    'address_2' => '',
    'city' => '',
    'postcode' => '',
    'country' => '',
    'state' => '',
    'order_email' => '',
    'phone' => ''
);

if (is_user_logged_in())
    $sbilling = maybe_unserialize(get_user_meta(get_current_user_id(), 'user_billing_shipping', true));

$sbilling = is_array($sbilling) && isset($sbilling['billing']) ? $sbilling['billing'] : array();
$billing = shortcode_atts($billing, $sbilling);

// If email and name is not available from { Edit Profile >> Billing info } get current user email and name
if ($billing['order_email'] == '' && is_user_logged_in()) $billing['order_email'] = $current_user->user_email;
if ($billing['first_name'] == '' && is_user_logged_in()) $billing['first_name'] = $current_user->display_name;

?>
<div id="select-payment-method">
    <form action="" name="payment_form" id="payment_form" method="post">


            <?php
            if (get_wpdmpp_option('billing_address') == 1 || wpdmpp_tax_active()) {
                // Ask Billing Address When Checkout
                include wpdm_tpl_path('checkout-cart/checkout-billing-info.php', WPDMPP_TPL_DIR, WPDMPP_TPL_FALLBACK);
            } else {
                // Ask only Name and Email When Checkout
                include wpdm_tpl_path('checkout-cart/checkout-name-email.php', WPDMPP_TPL_DIR, WPDMPP_TPL_FALLBACK);
            }
            ?>

            <?php
            // Show active payment methods
            include wpdm_tpl_path('checkout-cart/checkout-payment-methods.php', WPDMPP_TPL_DIR, WPDMPP_TPL_FALLBACK);
            ?>




        <div class="card card-default text-right mt-3" id="selected-payment-gateway-action">
            <div class="card-body">
                <?php if(wpdmpp_tax_active()){ ?>
                    <div class="pull-left hide cart-total-final btn color-green">
                        <?php echo apply_filters("wpdmpp_checkout_footer_tax_label", __('Total Including Tax:', 'wpdm-premium-packages') ); ?>
                        <span class="badge"></span>
                    </div>
                <?php } ?>
                <span id="wpdmpp-payment-button">
                    <button id="pay_btn" class="button btn btn-success" type="submit">
                        <i class="fa fa-credit-card"></i>&nbsp;<?php echo apply_filters("wpdmpp_checkout_pay_button_label", __('Pay Now', 'wpdm-premium-packages') ); ?>
                    </button>
                        <span id="wpdmpp-custom-payment-button"></span>

                </span>
            </div>
        </div>

    </form>
    <div id="paymentform"></div>
</div>