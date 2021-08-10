<?php
/**
 * Display / Edit Billing Info in WordPress's user profile page.
 *
 * @version     1.1.0
 */
namespace WPDMPP\Libs;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if( ! class_exists( 'BillingInfo' ) ):

    class BillingInfo{

        function __construct()
        {
            add_action( 'show_user_profile',        array( $this, 'add_billing_info_fields' ) );
            add_action( 'edit_user_profile',        array( $this, 'add_billing_info_fields' ) );
            add_action( 'personal_options_update',  array( $this, 'save_billing_info_fields' ) );
            add_action( 'edit_user_profile_update', array( $this, 'save_billing_info_fields' ) );
            add_action( 'wpdm_edit_profile_form', array( $this, 'billing_info_form_frontend' ) );
            add_action( 'wpdm_update_profile', array( $this, 'save_billing_info_frontend' ) );

        }

        function billing_info_form_frontend(){
            global $current_user;
            $billing = maybe_unserialize(get_user_meta(get_current_user_id(), 'user_billing_shipping', true));
            $store = maybe_unserialize(get_user_meta(get_current_user_id(), '__wpdm_store', true));
            $billing = isset($billing['billing']) ? $billing['billing'] : array();
            //ob_start();
            include wpdm_tpl_path('user-dashboard/billing-info.php', WPDMPP_TPL_DIR);
            //$data = ob_get_clean();
            //echo $data; //apply_filters("wpdmpp_billing_info_form", $data);
        }

        function add_billing_info_fields($user){
            global $wpdmpp_settings;
            $billing_shipping = maybe_unserialize(get_user_meta($user->ID, 'user_billing_shipping',true));
            $billing_defaults =  array
            (
                'first_name'    => '',
                'last_name'     => '',
                'company'       => '',
                'address_1'     => '',
                'address_2'     => '',
                'city'          => '',
                'postcode'      => '',
                'country'       => '',
                'state'         => '',
                'email'         => '',
                'phone'         => '',
                'taxid'         => ''
            );

            //echo '<pre>';print_r($billing_shipping);echo '</pre>';

            if( is_array( $billing_shipping ) && isset( $billing_shipping['billing'] ) )
                $billing = $billing_shipping['billing'];
            else
                $billing = $billing_defaults;

            ob_start();
            echo "<div class='w3eden' style='width: 800px;max-width: 100%'>";
            include wpdm_tpl_path('user-dashboard/billing-info.php', WPDMPP_TPL_FALLBACK);
            echo "</div>";
            $data = ob_get_clean();
            echo apply_filters('wpdmpp_add_billing_info_fields', $data, $user);
        }

        function save_billing_info_fields($user_id){
            if ( !current_user_can( 'edit_user', $user_id ) ) return false;
            $codata = wpdm_sanitize_array($_POST['checkout']);
            update_user_meta($user_id, 'user_billing_shipping', $codata);
            if(current_user_can(WPDMPP_ADMIN_CAP)) {
                if (isset($_REQUEST['wpdmpp_customer']))
                    User::addCustomer($user_id);
                else
                    User::removeCustomer($user_id);
            }
        }

        function save_billing_info_frontend(){
            $codata = wpdm_sanitize_array($_POST['checkout']);
            $user_id = get_current_user_id();
            update_user_meta($user_id, 'user_billing_shipping', $codata);
            //$billing = maybe_unserialize(get_user_meta($user_id, 'user_billing_shipping', true));
            //wpdmdd($billing);
        }

    }

endif;

new BillingInfo();