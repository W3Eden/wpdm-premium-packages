<?php
/**
 * Base: wpdmpro
 * Developer: shahjada
 * Team: W3 Eden
 * Date: 20/7/20 07:23
 */

use WPDM\__\UI;

if(!defined("ABSPATH")) die();
?>
<form method="post" class="wpdm_checkout_cart_form" id="wpdm_checkout_cart_form" action="" name="checkout_cart_form">
    <input name="wpdmpp_update_cart" value="1" type="hidden">

    <table class="wpdm_cart table mb-3">
        <thead>
        <tr class="cart_header">

            <?php do_action( 'wpdmpp_cart_table_header_first' ); ?>

            <th class="cart_item_remove" style="width:40px !important"></th>
            <th class="cart_item_title"><?php _e("Item", "wpdm-premium-packages"); ?></th>
            <th class="cart_item_unit_price"><?php _e("Unit Price", "wpdm-premium-packages"); ?></th>
            <?php if((int)get_wpdmpp_option('no_role_discount', 0) === 0){ ?>
                <th class="cart_item_role_discount"><?php _e("Role Discount", "wpdm-premium-packages"); ?></th>
            <?php } ?>
            <?php if((int)get_wpdmpp_option('no_product_coupon', 0) === 0){ ?>
                <th class="cart_item_coupon_code"><?php _e("Coupon Code", "wpdm-premium-packages"); ?></th>
            <?php } ?>
            <th class="cart_item_quantity"><?php _e("Quantity", "wpdm-premium-packages"); ?></th>
            <th class="cart_item_subtotal amt"><?php _e("Total", "wpdm-premium-packages"); ?></th>

            <?php do_action( 'wpdmpp_cart_table_header_last' ); ?>

        </tr>
        </thead>
        <tbody>
        <!-- Cart Items  -->
        <?php do_action( 'wpdmpp_cart_items_before' ); ?>
        <?php
        foreach ( $cart_data as $ID => $item ) {
            $dynamic = wpdm_valueof($item, 'type') === 'dynamic';
            if ( isset( $item['coupon_amount'] ) ) {
                $discount_amount    = $item['coupon_amount'];
                $discount_style     = "style='color:#008000; text-decoration:underline;'";
                $discount_title     = 'Discounted $' . $discount_amount . " for coupon code '{$item['coupon']}'";

            } else {
                $discount_amount    = "";
                $discount_style     = "";
                $discount_title     = "";
            }

            if (isset($item['error']) && $item['error'] != '') {
                $coupon_style   = "border:1px solid #ff0000;";
                $title          = $item['error'];
            } else {
                $coupon_style   = "";
                $title          = "";
            }
            $item['pid'] = $ID;
            $item['coupon']         = isset($item['coupon']) ? $item['coupon'] : '';
            $item['coupon_amount']  = isset($item['coupon_amount']) ? $item['coupon_amount'] : 0;
            $item_total             = number_format((($item['price'] + $item['prices']) * $item['quantity']) - $item['coupon_amount'] - $item['discount_amount'], 2, ".", "");
            ?>
            <tr id='cart_item_<?php echo $ID; ?>'>

                <?php do_action( 'wpdmpp_cart_item_col_first' , $item ); ?>

                <td class='cart_item_remove pr-0'>
                    <a class='wpdmpp_cart_delete_item p-0' href='#' onclick='return wpdmpp_remove_cart_item("<?php echo $ID; ?>")'>
                        <img title="<?php _e('Remove from cart', 'wpdm-premium-packages'); ?>" src="<?php echo WPDMPP_BASE_URL ?>assets/images/cart-delete.svg" class="p-0 m-0" width="20px" alt="- Remove -" />
                    </a>
                </td>
                <td class='cart_item_title'>
                    <div class="media">
                        <div class='mr-3'><?php WPDMPP()->cart->itemThumb($item); ?></div>
                        <div class="media-body">
                            <?php WPDMPP()->cart->itemLink($item); ?>
                            <?php WPDMPP()->cart->itemInfo($item); ?>
                        </div>
                    </div>
                    <div class='clear'></div>
                </td>
                <td class='cart_item_unit_price' <?php echo $discount_style; ?> >
                    <span class=' d-md-none'><?php echo __("Unit Price", "wpdm-premium-packages"); ?>: </span>
                    <span class='ttip' title='<?php echo $discount_title; ?>'><?php echo wpdmpp_price_format($item['price']); ?></span>
                </td>
                <?php if((int)get_wpdmpp_option('no_role_discount', 0) === 0){ ?>
                    <td class='cart_item_role_discount'>
                        <span class=' d-md-none'><?php echo __("Role Discount", "wpdm-premium-packages"); ?>: </span>
                        <?php echo wpdmpp_price_format($item['discount_amount']); ?>
                    </td>
                <?php } ?>
                <?php if((int)get_wpdmpp_option('no_product_coupon', 0) === 0){ ?>
                    <td class="cart_item_coupon_code">
                        <span class=' d-md-none'><?php echo __("Coupon Code", "wpdm-premium-packages"); ?>: </span>
                        <input style='width:100px;<?php echo $coupon_style; ?>' title='<?php echo $title; ?>'
                               type='text' name='cart_items[<?php echo $ID; ?>][coupon]' value='<?php echo $item['coupon']; ?>' id='<?php echo $ID; ?>' class='ttip input-sm form-control' size=3/>
                    </td>
                <?php } ?>
                <td class='cart_item_quantity'>
                    <span class=' d-md-none'><?php echo __("Quantity", "wpdm-premium-packages"); ?>: </span>
                    <input type='number' style='width:60px' min='1' name='cart_items[<?php echo $ID; ?>][quantity]' value='<?php echo $item['quantity']; ?>' size=3 class=' input-sm form-control'/>
                </td>
                <td class='cart_item_subtotal amt'>
                    <span class=' d-md-none'><?php echo __("Item Total", "wpdm-premium-packages"); ?>: </span>
                    <?php echo wpdmpp_price_format($item_total); ?>
                </td>

                <?php do_action( 'wpdmpp_cart_item_col_last', $item ); ?>

            </tr>

        <?php } ?>
        <!-- Cart Items end -->

        <?php do_action( 'wpdmpp_cart_items_after' ); ?>

        <?php do_action('wpdmpp_cart_extra_row', $cart_data); ?>
        <?php
        $colspan = 6;
        if((int)get_wpdmpp_option('no_role_discount', 0) === 1) $colspan--;
        if((int)get_wpdmpp_option('no_product_coupon', 0) === 1) $colspan--;
        ?>
        <!-- Cart Sub Total  -->
        <tr id="cart-total">
            <td colspan="<?php echo $colspan ?>" class="text-right  " align="right"><?php echo __("Subtotal", "wpdm-premium-packages"); ?>:</td>
            <td class="amt">
                <span class=" d-md-none"><?php echo __("Subtotal", "wpdm-premium-packages"); ?>: </span>
                <strong id="wpdmpp_cart_subtotal"><?php echo wpdmpp_price_format($cart_subtotal); ?></strong>
            </td>
        </tr>
        <!-- Cart Sub Total end  -->

        <!-- Cart Coupon Discount  -->
        <tr id="cart-total">
            <td colspan="<?php echo $colspan-2; ?>" class="text-right ">
                <div class="input-group input-group-sm" style="max-width: 220px">
                    <input type="text" name="coupon_code" class="form-control" value="<?php echo is_array($cart_coupon) && isset($cart_coupon['code'])?$cart_coupon['code']:''; ?>" placeholder="Coupon Code">
                    <span class="input-group-append"><button class="btn btn-secondary" type="submit"><?= esc_attr__( 'Apply', WPDMPP_TEXT_DOMAIN ) ?></button></span>
                </div>
            </td>
            <td colspan="2" class="text-right  " align="right"><?php echo __("Coupon Discount", "wpdm-premium-packages"); ?>:</td>
            <td class="amt">
                <span class=" d-md-none"><?php echo __("Coupon Discount", "wpdm-premium-packages"); ?>: </span>
                <span id="wpdmpp_cart_discount"><?php echo wpdmpp_price_format($cart_coupon_discount); ?></span>
            </td>
        </tr>
        <!-- Cart Coupon Discount end -->

        <?php if (wpdmpp_tax_active()) { ?>
            <!-- Cart Tax  -->
            <tr id="cart-tax">
                <td colspan="<?php echo $colspan ?>" class="text-right  " align="right"><?php echo __("Tax", "wpdm-premium-packages"); ?>:</td>
                <td class="amt" id="wpdmpp_cart_tax">
                    <span class=" d-md-none"><?php echo __("Tax", "wpdm-premium-packages"); ?>: </span>
                    <?php echo wpdmpp_price_format($cart_tax); ?>
                </td>
            </tr>
            <!-- Cart Tax end -->
        <?php } ?>

        <!-- Cart Total Including Tax -->
        <tr id="cart-total-with-tax">
            <td colspan="<?php echo $colspan ?>" class="text-right  " align="right"><?php echo __("Total", "wpdm-premium-packages"); ?>:</td>
            <td class="amt">
                <strong><span class=" d-md-none"><?php echo __("Total", "wpdm-premium-packages"); ?>: </span></strong>
                <strong id="wpdmpp_cart_grand_total"><?php echo wpdmpp_price_format($cart_total_with_tax); ?></strong>
            </td>
        </tr>
        <!-- Cart Total Including Tax end -->

        <!-- Cart buttons -->
        <tr>
            <td colspan="<?php echo $colspan-2 ?>">
                <button type="button" class="btn btn-info " onclick="location.href='<?php echo $settings['continue_shopping_url']; ?>'">
                    <i class="fa fa-white fa-cart-plus"></i>&nbsp;<?php echo apply_filters( "wpdmpp_cart_continue_shopping_button_label", __( "Continue Shopping", "wpdm-premium-packages" ) ); ?>
                </button>
                <button id="save-cart" type="button" class="btn btn-link ml-2">
                    <i class="fas fa-hdd"></i>&nbsp;<?php echo apply_filters( "wpdmpp_save_cart_button_label", __( "Save Cart", "wpdm-premium-packages" ) ); ?>
                </button>
                <button id="empty-cart"  onclick='return wpdmpp_remove_cart_item("all")' type="button" class="btn btn-link">
                    <i class="fas fa-trash"></i>&nbsp;<?php echo apply_filters( "wpdmpp_empty_cart_button_label", __( "Empty Cart", "wpdm-premium-packages" ) ); ?>
                </button>
            </td>
            <td colspan="3" class="text-right" align="right">
                <button class="btn btn-primary btn-icon-left" type="submit" onclick="jQuery(this).find('.fa').addClass('fa-spin');">
                    <i class="fa fa-white fa-sync"></i> <?php echo apply_filters( "wpdmpp_update_cart_button_label", __( "Update Cart", "wpdm-premium-packages" ) ); ?>
                </button>
            </td>
        </tr>
        <!-- Cart buttons end -->

        </tbody>
    </table>


</form>
