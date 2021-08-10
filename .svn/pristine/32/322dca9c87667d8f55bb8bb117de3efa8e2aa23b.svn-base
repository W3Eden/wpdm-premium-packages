<?php
/**
 * Template for displaying active Name and Email input fields cart checkout page.
 *
 * This template can be overridden by copying it to yourtheme/download-manager/checkout-cart/checkout-name-email.php.
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
<div class="panel-heading"><?php echo __("Please Enter Your Name & Email", "wpdm-premium-packages"); ?></div>
<div class="panel-body">
    <!-- full-name input-->
    <div class="form-group">
        <div class="controls row">
            <div class="col-md-6">
                <input id="f-name" value="<?php echo $billing['first_name']; ?>" name="billing[first_name]" required="required" type="text" placeholder="<?php echo __("Name", "wpdm-premium-packages"); ?>" class="form-control">
            </div>
            <div class="col-md-6">
                <input type="email" value="<?php echo $billing['order_email']; ?>" required="required" class="form-control" name="billing[order_email]" id="email_m" placeholder="<?php echo __("Enter Order Notification Email", "wpdm-premium-packages"); ?>">
            </div>
        </div>
    </div>
</div>