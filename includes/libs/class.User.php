<?php
/**
 * Created by PhpStorm.
 * User: shahnuralam
 * Date: 19/11/18
 * Time: 12:41 AM
 */

namespace WPDMPP\Libs;

use WPDMPP\Product;

class User
{
    function __construct()
    {
    }

    static function addCustomer($userID)
    {
        if(!$userID) return 0;
        $user = is_object($userID) ? $userID : get_user_by('id', $userID);
        if (is_object($user) && get_class($user) == 'WP_User' && !in_array('wpdmpp_customer', $user->roles)) {
            $user->add_role('wpdmpp_customer');
        }
    }

    static function removeCustomer($userID = null)
    {
        $current_user = wp_get_current_user();
        $userID = $userID ? $userID : $current_user;
        $user = is_object($userID) ? $userID : get_user_by('id', $userID);
        if (is_object($user) && get_class($user) == 'WP_User' && in_array('wpdmpp_customer', $user->roles)) {
            $user->remove_role('wpdmpp_customer');
        }
    }

    static function calculateSpent($userID = null)
    {
        global $wpdb;
        $userID = $userID ?: get_current_user_id();
        if(!$userID) return 0;
        $order_total = $wpdb->get_var("select sum(total) from {$wpdb->prefix}ahm_orders where uid = '{$userID}' and order_status='Completed'");
        $renew_total = $wpdb->get_var("SELECT sum(total) FROM {$wpdb->prefix}ahm_orders o, {$wpdb->prefix}ahm_order_renews r WHERE o.order_id = r.order_id and o.uid = '{$userID}'");
        $total = $order_total + $renew_total;
        update_user_meta($userID, '__wpdmpp_total_spent', $total);
        return $total;
    }

    static function totalSpent($userID = null)
    {
        $total = get_user_meta($userID, '__wpdmpp_total_spent', true);
        if(!$total)
            return self::calculateSpent($userID);
        return $total;
    }

    static function processActiveRoles($userID)
    {
        if(!$userID) return 0;

        $items = Order::getPurchasedItems($userID);

        foreach ($items as $item) {
            if($item->order_status !== 'Completed') {
                $product = new Product($item->pid);
                $product->removeRole($item->uid);
            }
        }
        foreach ($items as $item) {
            if($item->order_status === 'Completed') {
                $product = new Product($item->pid);
                $product->assignRole($item->cid);
            }
        }
    }

}
