<?php
/**
 * Template for displaying active Payment Methods in cart checkout page.
 *
 * This template can be overridden by copying it to yourtheme/download-manager/checkout-cart/checkout-payment-methods.php.
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $payment_methods;
?>

<div class="card" id="csp">
    <div class="card-header"><?php echo __("Select Payment Method:", "wpdm-premium-packages"); ?></div>
    <div class="list-group list-group-flush payment-gateway-list">
    <?php
    $settings           = maybe_unserialize(get_option('_wpdmpp_settings'));
    $payment_methods    = apply_filters('payment_method', $payment_methods);
    $payment_methods    = isset($settings['pmorders']) && count($settings['pmorders']) == count($payment_methods) ? $settings['pmorders'] : $payment_methods;

    foreach ($payment_methods as $payment_method) {

        $payment_gateway_class = 'WPDMPP\Libs\PaymentMethods\\'.$payment_method;

        if ( class_exists( $payment_gateway_class ) ) {
            if ( isset( $settings[$payment_method]['enabled']) && $settings[$payment_method]['enabled'] == 1) {

                $obj                = new $payment_gateway_class();
                $obj->GatewayName   = isset($obj->GatewayName) ? $obj->GatewayName : $payment_method;
                $name               = get_wpdmpp_option($payment_method . '/title', $obj->GatewayName);
                $name               = $name == '' ? $payment_method : $name;

                echo '<label class="list-group-item"><input class="mr-2" type="radio" name="payment_method" value="' . $payment_method . '" >' . $name . '</label>';
            }
        }
    }
    ?>
    </div>
</div>
