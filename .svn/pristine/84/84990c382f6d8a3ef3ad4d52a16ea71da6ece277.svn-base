<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

if (isset($_POST['psub']))
    update_option("wpdmpp_payout_duration", absint( $_POST['payout_duration']) );

if (isset($_POST['csub']))
    update_option("wpdmpp_user_comission", wpdm_sanitize_array($_POST['comission']));

if (isset($_POST['pschange'])) {
    global $wpdb;
    if (isset($_POST['payout_status']) && $_POST['payout_status'] != "-1" && $_POST['payout_status'] != "2") {
        if (isset($_POST['poutid'])) {
            foreach ($_POST['poutid'] as $payout_id) {
                $wpdb->update(
                    "{$wpdb->prefix}ahm_withdraws",
                    array(
                        'status' => sanitize_text_field( $_POST['payout_status'] )
                    ),
                    array('ID' => $payout_id),
                    array(
                        '%d',
                    ),
                    array('%d')
                );
            }
        }
    }

    if (isset($_POST['payout_status']) && $_POST['payout_status'] == "2") {
        if (isset($_POST['poutid'])) {
            foreach ($_POST['poutid'] as $payout_id) {
                $wpdb->query("delete from {$wpdb->prefix}ahm_withdraws where id={$payout_id}");
            }
        }
    }
}

$payout_duration = get_option("wpdmpp_payout_duration");
$comission = get_option("wpdmpp_user_comission");
?>


<div class="w3eden payout-entries">
    <?php
    $menus = [
        ['link' => "#all_payouts", "name" => __("All Payouts", "wpdm-premium-packages"), "active" => true, 'attrs' => ['data-toggle' => 'tab']],
        ['link' => "#dues", "name" => __("Dues", "wpdm-premium-packages"), "active" => false, 'attrs' => ['data-toggle' => 'tab']],
        ['link' => "#payout_settings", "name" => __("Payout Settings", "wpdm-premium-packages"), "active" => false, 'attrs' => ['data-toggle' => 'tab']],
    ];

    WPDM()->admin->pageHeader(esc_attr__( "Payouts", "wpdm-premium-packages" ), 'credit-card fas color-purple', $menus);
    ?>

    <div class="wpdm-admin-page-content" id="wpdm-wrapper-panel">

        <div class="tab-content panel-body">
            <div class="tab-pane active" id="all_payouts">
                <?php include_once("payout-all.php"); ?>
            </div>
            <div class="tab-pane" id="dues">
                <?php include_once("payout-dues.php"); ?>
            </div>
            <div class="tab-pane" id="payout_settings">
                <?php include_once("payout-settings.php"); ?>
            </div>
        </div>

</div>
</div>
<style>div.notice{ display: none; }</style>
