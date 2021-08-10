<?php
/**
 * Orders List Template for [wpdmpp_purchases] shortcode
 *
 * This template can be overridden by copying it to yourtheme/download-manager/partials/user-orders-list.php.
 *
 * @version     1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<div class="panel panel-default panel-purchases">
    <div class="panel-heading"><?php _e("Purchases", "wpdm-premium-packages"); ?></div>
    <table class="table" style="margin:0;">
        <thead>
        <tr>
            <th style="width:50px;"></th>
            <th><?php _e("Order Id", "wpdm-premium-packages"); ?></th>
            <th><?php _e("Date", "wpdm-premium-packages"); ?></th>
            <th style="width: 180px;"><?php _e("Payment Status", "wpdm-premium-packages"); ?></th>
        </tr>
        </thead>
        <?php
        $ordcls = new \WPDMPP\Libs\Order();
        $order_validity_period = $wpdmpp_settings['order_validity_period'] * 86400;

        foreach ($myorders as $order) {
            $date = date("Y-m-d h:i a", $order->date);
            $items = unserialize($order->items);
            $expire_date = $order->expire_date;

            if (intval($expire_date) == 0) {
                $expire_date = $order->date + $order_validity_period;
                $ordcls->Update(array('expire_date' => $expire_date), $order->order_id);
            }

            if (time() > $expire_date && $order->order_status != 'Expired') {
                $ordcls->Update(array('order_status' => 'Expired', 'payment_status' => 'Expired'), $order->order_id);
                $order->order_status = 'Expired';
                $order->payment_status = 'Expired';
            }

            $orderurl = get_permalink(get_the_ID());
            $zurl = $orderurl . $sap;
            $nonce = wp_create_nonce(NONCE_KEY);
            $del = ($order->order_status == 'Processing') ? '<a href="#" data-toggle="tooltip" title="Delete Order" class="delete_order btn btn-xs btn-danger" order_id="' . $order->order_id . '" nonce="' . $nonce . '"><i class="fa fa-times"></i></a>' : '<a href="#" class="btn btn-xs btn-success" disabled="disabled"><i class="fa fa-check"></i></a>';
            ?>
            <tr class="order" id="order_<?php echo $order->order_id; ?>">
                <td><?php echo $del; ?></td>
                <td><a href='<?php echo $zurl; ?>id=<?php echo $order->order_id; ?>'><?php echo $order->order_id; ?></a></td>
                <td><?php echo $date; ?></td>
                <td><?php echo $order->payment_status; ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>

<style>
    td { vertical-align: middle !important; }
    .panel-footer .alert { font-size: 9pt; line-height: 28px; padding: 0 10px; }
    .panel-footer .btn { border: 0 !important; margin-top: -3px; }
    .row-actions { padding: 2px 0 0; visibility: hidden; }
    tr:hover .row-actions { visibility: visible; }
</style>