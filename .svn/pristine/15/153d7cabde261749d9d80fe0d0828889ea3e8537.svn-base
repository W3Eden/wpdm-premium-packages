<?php
/**
 * Created by PhpStorm.
 * User: shahnuralam
 * Date: 19/11/18
 * Time: 12:41 AM
 */

namespace WPDMPP\Libs;


class User
{
    function __construct()
    {
    }

    static function addCustomer($userID = null){
        global $current_user;
        $userID = $userID ? $userID : $current_user;
        $user = is_object($userID)?$userID:get_user_by('id', $userID);
        if(is_object($user) && get_class($user) == 'WP_User' && !in_array('wpdmpp_customer', $user->roles)){
           $user->add_role('wpdmpp_customer');
        }
    }

    static function removeCustomer($userID = null){
        global $current_user;
        $userID = $userID ? $userID : $current_user;
        $user = is_object($userID)?$userID:get_user_by('id', $userID);
        if(is_object($user) && get_class($user) == 'WP_User' && in_array('wpdmpp_customer', $user->roles)){
           $user->remove_role('wpdmpp_customer');
        }
    }
}