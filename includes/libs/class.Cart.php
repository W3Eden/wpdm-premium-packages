<?php
/**
 * User: shahnuralam
 * Date: 25/12/18
 * Time: 3:12 PM
 */

namespace WPDMPP\Libs;


use WPDM\__\Crypt;
use WPDM\__\Template;
use WPDM\__\Session;
use WPDM\__\UI;

class Cart
{

    private $ID;
    private $cartData;

    function __construct()
    {

    }

    /**
     * Get cart id
     * @return null|string
     */
    function getID(){
        $cart_id = null;
        if(is_user_logged_in()){
            $cart_id = get_current_user_id()."_cart";
        } else {
            $cart_id = Session::$deviceID."_cart";
        }

        return $cart_id;
    }

    /**
     * Get cart items
     * @return array|mixed
     */
    function getItems(){

        $cart_id = $this->getID();

        $cart_data = maybe_unserialize(get_option($cart_id));

        //Transfer cart data from guest id to user id
        if( is_user_logged_in() && !$cart_data ){
            $cart_id = Session::$deviceID."_cart";
            $cart_data = maybe_unserialize(get_option($cart_id));
            delete_option($cart_id);
            $cart_id = get_current_user_id()."_cart";
            update_option($cart_id, $cart_data, false);
        }

        return $cart_data ?: [];

    }

    /**
     * Add an item to cart
     * @param $product_id
     * @param string $license
     * @param array $extras
     * @return array|mixed
     */
    function addItem($product_id, $license = '', $extras = array()){

        $cart_id = $this->getID();
        $cart_data = $this->getItems();

        if(isset($cart_data[$product_id])) unset($cart_data[$product_id]);

        $license_prices = get_post_meta($product_id, "__wpdm_license", true);
        $license_prices = maybe_unserialize($license_prices);
        $license_req = (int)get_post_meta($product_id, "__wpdm_enable_license", true);
        $pre_licenses = wpdmpp_get_licenses();

        $files = [];
        $fileinfo = get_post_meta($product_id, '__wpdm_fileinfo', true);
        $fileinfo = maybe_unserialize($fileinfo);
        $files_price = 0;
        $sfiles = isset($extras['files']) ? $extras['files'] : [];
        $sfiles = !is_array($sfiles) ? explode(",", $sfiles) : $sfiles;

        $variations = isset($extras['variations']) ? $extras['variations'] : [];

        if(count($sfiles) > 0 && $sfiles[0] != '' && is_array($fileinfo)) {
            foreach ($sfiles as $findx) {
                $files[$findx] = $fileinfo[$findx]['price'];
                if ($license_req === 1 && $license != '' && $fileinfo[$findx]['license_price'][$license] > 0) {
                    $files[$findx] = wpdm_valueof($fileinfo, "{$findx}/license_price/{$license}"); // $fileinfo[$findx]['license_price'][$license];
                }
            }
        }

        $base_price = wpdmpp_product_price($product_id);
        if($license_req === 1 && isset($license_prices[$license], $license_prices[$license]['price']) && $license_prices[$license]['price'] > 0)
            $base_price = $license_prices[$license]['price'];

        if((int)get_post_meta($product_id, '__wpdm_pay_as_you_want', true) === 0) {
            $files_price = array_sum($files);
            $base_price = $files_price > 0 && $files_price < $base_price ? $files_price : $base_price;
            $cart_data[$product_id] = array('quantity' => 1, 'variation' => $variations, 'price' => $base_price, 'files' => $files);

        } else {
            //Condition for as you want to pay
            $base_price         = isset($extras['iwantopay'])&&(double)$extras['iwantopay'] >= $base_price ?(double)$extras['iwantopay']:$base_price;
            $cart_data[$product_id]    = array('quantity' => 1, 'variation' => $variations, 'price' => $base_price, 'files' => array());
        }

        $cart_data[$product_id]['license'] = array('id' => $license, 'info' => wpdm_valueof($pre_licenses, $license));

        update_option($cart_id, $cart_data, false);
        return $cart_data;

    }

    /**
     * @param $product_id
     * @param $name
     * @param $price
     * @param array $extras
     * @return array|mixed
     */
    function addDynamicItem($product_id, $name, $price, $extras = array()){

        $cart_id = $this->getID();
        $cart_data = $this->getItems();

        if(isset($cart_data[$product_id])) unset($cart_data[$product_id]);

        $cart_data[$product_id]    = array('quantity' => 1, 'variation' => [], 'price' => $price, 'files' => [], 'product_name' => $name, 'product_type' => 'dynamic', 'license' => [], 'info' => $extras);

        update_option($cart_id, $cart_data, false);
        return $cart_data;

    }

    function applyCoupon($code, $product_id = null){

    }

    /**
     * @param $product_id
     * @return array|mixed
     */
    function removeItem($product_id){
        $cart_id = $this->getID();
        $cart_data = $this->getItems();
        if(isset($cart_data[$product_id])) unset($cart_data[$product_id]);

        update_option($cart_id, $cart_data, false);
        return $cart_data;

    }

    function getCoupon(){
        $cart_id = $this->getID();
        $cart_coupon = get_option($cart_id."_coupon", null);
        if(!isset($cart_coupon['code']) || $cart_coupon['code'] == '') { delete_option($cart_id."_coupon"); $cart_coupon = null; }
        return $cart_coupon;
    }

    function save(){
        $cartdata = $this->getItems();
        $cartinfo = array('cartitems' => $cartdata, 'coupon' => $this->getCoupon());
        $cartinfo = Crypt::encrypt($cartinfo);
        $id = uniqid();
        file_put_contents(WPDM_CACHE_DIR.'saved-cart-'.$id.'.txt', $cartinfo);
        Session::set('savedcartid', $id);
        return $id;
    }

    function loadSaved($saved_cart_id){


        $cartfile = WPDM_CACHE_DIR.'/saved-cart-'.$saved_cart_id.'.txt';
        $saved_cart_data = '';

        if(file_exists($cartfile)) $saved_cart_data = file_get_contents($cartfile);
        $saved_cart_data = Crypt::decrypt($saved_cart_data, true);

        $coupon_data = null;
        if(is_array($saved_cart_data) && count($saved_cart_data) > 0) {
            //wpdmdd($saved_cart_data);
            if(isset($saved_cart_data['cartitems'])){
                $coupon_data = $saved_cart_data['coupon'];
                $saved_cart_data = $saved_cart_data['cartitems'];

            }

            $cart_id = $this->getID();
            update_option($cart_id, $saved_cart_data, false);

            if($coupon_data){
                $cart_id = wpdmpp_cart_id();
                update_option($cart_id."_coupon", $coupon_data);
            }
        }

        return $saved_cart_data;

    }

    function getCartPrice(){
        $cart_items = $this->getItems();
        $total = 0;
        if(is_array($cart_items)){
            foreach($cart_items as $pid => $item){
                if(isset($item['item'])){
                    foreach ($item['item'] as $key => $val){
                        $role_discount = isset($val['discount_amount']) ? $val['discount_amount']: 0;
                        $coupon_discount = isset($val['coupon_amount']) ? $val['coupon_amount']: 0;
                        $val['prices'] = isset($val['prices']) ? $val['prices']: 0;
                        //$total += (($item['price'] + $val['prices'] - $role_discount - $coupon_discount)*$item['quantity']);
                        $total += ( ( $item['price'] + $val['prices'] - $role_discount ) * $val['quantity'] - $coupon_discount );
                    }
                }
                else {
                    $role_discount = isset($item['discount_amount']) ? $item['discount_amount']: 0;
                    $coupon_discount = isset($item['coupon_amount']) ? $item['coupon_amount']: 0;
                    //$total += (($item['price'] + $item['prices'] - $role_discount - $coupon_discount)*$item['quantity']);
                    $total += ( ( $item['price'] + $item['prices'] - $role_discount ) * $item['quantity'] - $coupon_discount );
                }
            }
        }

        $total = apply_filters('wpdmpp_cart_price',$total);

        return number_format($total, 2, ".", "");
    }

    function itemLink($item, $echo = true)
    {
        if(wpdm_valueof($item, 'product_type') === 'dynamic')
            $item_link = "<strong class='ttip wpdm-product-name' title='".esc_attr__( 'Dynamic Product', WPDMPP_TEXT_DOMAIN )."'>{$item['product_name']}</strong>";
        else
            $item_link = '<a target=_blank class="d-block wpdm-product-name" href="'.get_permalink($item['pid']).'">'.get_the_title($item['pid']).'</a>';

        if(!$echo) return $item_link;
        echo $item_link;
    }

    function itemThumb($item, $echo = true, $attrs = [])
    {
        $attrs['class'] = wpdm_valueof($attrs, 'ckass') . " wpdm-cart-thumb";
        if(wpdm_valueof($item, 'product_type') === 'dynamic') {
            $image = wpdm_valueof($item, 'info/image', ['default' => WPDM_BASE_URL.'assets/images/wpdm.svg']);
            $thum = UI::img($image, $item['product_name'], $attrs);
        } else {
            $attrs['alt'] = get_the_title($item['pid']) . ' Thumb';
            $thum = wpdm_thumb((int)$item['pid'], array(96, 96), false, $attrs);
        }

        if(!$echo) return $thum;
        echo $thum;
    }

    function itemInfo($item, $echo = true)
    {
        $variations = isset($item['variations']) && is_array($item['variations']) ? UI::div(implode(", ", $item['variations']), "text-info text-small") : '';
        $license = wpdm_valueof($item, 'license', ['default' => []]);
        $license = maybe_unserialize($license);
        $license = isset($license['info'], $license['info']['name'])? UI::div(sprintf(__("%s License",WPDMPP_TEXT_DOMAIN), $license['info']['name']), "color-purple text-small ttip", ['title' => esc_html($license['info']['description'])]):'';
        $desc = UI::div(wpdm_valueof($item, 'info/desc', esc_attr__( 'Dynamic Product', WPDMPP_TEXT_DOMAIN )), "color-purple text-small ttip");
        $license = $license || wpdm_valueof($item, 'product_type') !== 'dynamic' ? $license : $desc;
        $cart_item_info = $variations.$license;
        $cart_item_info = apply_filters("wpdmpp_cart_item_info", $cart_item_info, $item);

        if(!$echo) return $cart_item_info;
        echo $cart_item_info;
    }

    /**
     * Shortcode function for [wpdmpp_cart], Shows Premium Package Cart
     * @return false|string
     */
    function render(){
        global $wpdb;
        wpdmpp_calculate_discount();
        $cart_data      = $this->getItems();
        $login_html     = "";
        $payment_html   = "";
        $settings       = get_option('_wpdmpp_settings');
        $guest_checkout = (isset($settings['guest_checkout']) && $settings['guest_checkout'] == 1) ? 1 : 0;
        $cart_id        = wpdmpp_cart_id();
        $coupon         = get_option($cart_id."_coupon");

        if(is_array($coupon)) {
            $coupon['discount'] = \WPDMPP\Libs\CouponCodes::validate_coupon($coupon['code']);
            update_option($cart_id . "_coupon", $coupon, false);
        }

        $cart_subtotal          = $this->getCartPrice();
        $cart_total             = wpdmpp_get_cart_total();
        $cart_tax               = wpdmpp_get_cart_tax();
        $cart_total_with_tax    = number_format($cart_total + $cart_tax, 2, '.', '');
        $cart_coupon            = wpdmpp_get_cart_coupon();
        $cart_coupon_discount   = isset($cart_coupon['discount'])?number_format($cart_coupon['discount'],2, '.', ''):0.00;

        $Template = new Template();
        $Template->assign('guest_checkout', $guest_checkout);
        $Template->assign('cart_data', $cart_data);
        $Template->assign('cart_subtotal', $cart_subtotal);
        $Template->assign('cart_total', $cart_total);
        $Template->assign('cart_tax', $cart_tax);
        $Template->assign('cart_total_with_tax', $cart_total_with_tax);
        $Template->assign('cart_coupon', $cart_coupon);
        $Template->assign('settings', $settings);
        $Template->assign('cart_coupon_discount', $cart_coupon_discount);

        return $Template->fetch('checkout-cart/cart.php', WPDMPP_TPL_DIR, WPDMPP_TPL_FALLBACK);
    }

    static function clear(){
        global $current_user;
        $current_user = wp_get_current_user();
        $cart_id = wpdmpp_cart_id();
        delete_option($cart_id);
        delete_option($cart_id."_coupon");
        if(Session::get('orderid')){
            Session::set('last_order', Session::get('orderid'));
            Session::clear('orderid');
            Session::clear('tax');
            Session::clear('subtotal');
        }
    }

    function onUserLogin($user_login, $user){
        $user_cart_id = $user->ID."_cart";
        $guest_cart_id = Session::$deviceID."_cart";
        $cart_data =  maybe_unserialize(get_option($guest_cart_id));
        update_option($user_cart_id, $cart_data, false);
        delete_option($guest_cart_id);
    }

    static function clearAll(){
        global $wpdb;
        $wpdb->query("DELETE FROM `{$wpdb->prefix}_options` WHERE `option_name` LIKE '%_cart'");
        $wpdb->query("DELETE FROM `{$wpdb->prefix}_options` WHERE `option_name` LIKE '%_coupon'");
    }

}
