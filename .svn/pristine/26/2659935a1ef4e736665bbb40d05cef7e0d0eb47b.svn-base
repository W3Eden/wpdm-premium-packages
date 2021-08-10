<?php
/**
 * Template for User Dashboard >> Downloads >> Coupon Codes submenu page
 *
 * @version     1.0.0
 */

if(!defined('ABSPATH')) die();
global $wpdb;
$limit  = 20;
$page   = wpdm_query_var('paged');
$page   = $page > 0 ? $page : 1;
$start  = ( $page - 1 ) * $limit;
$cond   = array();


$cond[] = "o.order_status = 'Completed' or o.order_status = 'Expired'";

if(wpdm_query_var('product') != '')
    $cond[] = "product = '".wpdm_query_var('product', array('validate' => 'num'))."'";

$usrc = '';
if(wpdm_query_var('search') != '') {
    $src = wpdm_query_var('search');
    $usrc = "(u.user_login like '%{$src}%' or u.user_email like '%{$src}%' or u.user_nicename like '%$src%' or u.display_name like '%$src%' or u.ID = '$src') and o.uid = u.ID ";
    if(count($cond) > 0 ) $usrc = " and $usrc";
}

if(count($cond) > 0)
    $cond = "where (".implode(" or ", $cond).")"; else $cond = '';

$sql                = "select o.uid from {$wpdb->prefix}ahm_orders o, {$wpdb->prefix}users u {$cond} {$usrc} GROUP BY o.uid ORDER BY o.uid DESC limit $start, $limit";
$all_customers      = $wpdb->get_results($sql);
$total_customers    = $wpdb->get_var("select count(DISTINCT uid) from {$wpdb->prefix}ahm_orders o {$cond}");

?>
<div class="w3eden payout-entries">
    <?php
    $menus = [
        ['link' => "edit.php?post_type=wpdmpro&page=customers", "name" => __("All Customers", "wpdm-premium-packages"), "active" => true],
    ];

    WPDM()->admin->pageHeader(esc_attr__( "Customers", "wpdm-premium-packages" ), 'user-graduate fas color-purple', $menus);
    ?>

    <div class="wpdm-admin-page-content" id="wpdm-wrapper-panel">

        <div class="panel-body">
            <form>
                <input type="hidden" name="post_type" value="wpdmpro" />
                <input type="hidden" name="page" value="customers" />
                <div class="input-group input-group-lg">
                    <?php if(wpdm_query_var('search')) {  ?>
                    <div class="input-group-addon">
                        <a href="edit.php?post_type=wpdmpro&page=customers"><?= esc_attr__( 'Clear Search', WPDMPP_TEXT_DOMAIN ) ?></a>
                    </div>
                    <?php } ?>
                    <input type="search" name="search" value="<?= wpdm_query_var('search') ?>" class="form-control" placeholder="<?= esc_attr__( 'ID / Name / Username / Email ...', WPDMPP_TEXT_DOMAIN ) ?>">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-secondary"><?= esc_attr__( 'Search Customer', WPDMPP_TEXT_DOMAIN ) ?></button>
                    </div>
                </div>
            </form>
            <br/>
            <div class="panel panel-default">
                <table class="table table-striped table-wpdmpp">
                    <thead>
                    <tr>
                        <th><?php _e("Name","wpdm-premium-packages");?></th>
                        <th><?php _e("Email","wpdm-premium-packages");?></th>
                        <th><?php _e("Signup Date","wpdm-premium-packages");?></th>
                        <th><?php _e("Total Spent","wpdm-premium-packages");?></th>
                        <th style="width: 200px"><?php _e("Action","wpdm-premium-packages");?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($all_customers as $customer){
                        $_customer = get_user_by('id', $customer->uid);
                        ?>
                        <tr id="cr-<?php echo $customer->uid; ?>">
                            <td><strong><?php echo is_object($_customer) ? $_customer->display_name : esc_attr__( 'User Deleted / Not Found', 'wpdm-premium-packages' ); ?></strong></td>
                            <td><?php echo is_object($_customer) ? $_customer->user_email : '&mdash;'; ?></td>
                            <td><?php echo is_object($_customer) ? wp_date(get_option('date_format')." ".get_option('time_format'), strtotime($_customer->user_registered )): '&mdash;';; ?></td>
                            <td><?php echo wpdmpp_price_format(\WPDMPP\Libs\User::totalSpent($customer->uid)); ?></td>
                            <td>
                                <?php if(is_object($_customer)){ ?>
                                <a href="user-edit.php?user_id=<?php echo $_customer->ID; ?>" class="btn btn-sm btn-info"><i class="fas fa-pencil-alt"></i> <?php _e('Edit','wpdm-premium-packages'); ?></a>
                                <?php } ?>
                                <a href="edit.php?post_type=wpdmpro&page=customers&view=profile&id=<?php echo $customer->uid; ?>" class="btn btn-sm btn-primary"><i class="fas fa-user-circle"></i> <?php _e('Profile','wpdm-premium-packages'); ?></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center">
                <?php
                echo wpdm_paginate_links($total_customers, $limit, $page, 'paged');
                ?>
            </div>
        </div>
    </div>
</div>
