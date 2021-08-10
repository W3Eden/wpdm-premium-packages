<?php
/**
 * Login and Signup form Template. Applied during cart checkout if user is not logged in and guest checkout disabled.
 *
 * This template can be overridden by copying it to yourtheme/download-manager/checkout-cart/checkout-login-register.php.
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $sap, $current_user;

$tmp_reg_info = \WPDM\Session::get('tmp_reg_info');

?>
<div id="checkout-login" class="card card-default">
    <div class="card-header"><?php _e('Please register or login to checkout','wpdm-prepmium-packages'); ?></div>
    <div class="card-body" style="<?php if ($current_user->ID) echo "display:none"; else echo ""; ?>" id="csl">
        <div class="row">
            <div class="col-md-6 signup-col" style="padding-right: 30px">
                <div id='wpdmreg_checkout'>
                    <h2  class="fetfont"><i class="fas fa-user-plus"></i> <?php _e('Signup', 'wpdm-premium-packages'); ?></h2>
                    <?php
                    $loginurl = get_option('__wpdm_login_url');
                    if($loginurl > 0)
                        $loginurl = get_permalink($loginurl);
                    else
                        $loginurl = wp_login_url();
                    $reg_redirect =  $_SERVER['REQUEST_URI'];
                    if(isset($params['redirect'])) $reg_redirect = esc_url($params['redirect']);
                    if(isset($_GET['redirect_to'])) $reg_redirect = esc_url($_GET['redirect_to']);
                    $force = uniqid();

                    $up = parse_url($reg_redirect);
                    if(isset($up['host']) && $up['host'] != $_SERVER['SERVER_NAME']) $reg_redirect = home_url('/');

                    $reg_redirect = esc_url($reg_redirect);
                    ?>

                    <form method="post" action="" id="registerform" name="registerform" class="login-form">

                        <input type="hidden" name="phash" value="<?php echo \WPDM\libs\Crypt::Encrypt(array('captcha' => 'false', 'autologin' => 'true', )); ?>" />
                        <input type="hidden" name="permalink" value="<?php echo wpdmpp_cart_page(); ?>" />
                        <input type="hidden" id="__reg_nonce" name="__reg_nonce" value="" />

                        <?php global $wp_query; if(\WPDM\Session::get('reg_error')) {  ?>
                                    <div class="error alert alert-danger">
                                        <b><?php _e('Registration Failed!','wpdm-premium-packages'); ?></b><br/>
                                        <?php echo \WPDM\Session::get('reg_error'); \WPDM\Session::clear('reg_error'); ?>
                                    </div>
                                <?php } ?>

                                <div class="form-row">
                                    <div class="form-group col-sm-7">
                                        <div class="input-group input-group-lg">
                                            <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-male"></i></span></div>
                                            <input class="form-control form-control-lg" required="required" placeholder="<?php _e( "First Name" , "download-manager" ); ?>" type="text" size="20" id="first_name" value="<?php echo isset($tmp_reg_info['first_name'])?$tmp_reg_info['first_name']:''; ?>" name="wpdm_reg[first_name]">
                                        </div>
                                    </div>
                                    <div class="form-group col-sm-5">
                                        <input class="form-control form-control-lg" required="required" placeholder="<?php _e( "Last Name" , "download-manager" ); ?>" type="text" size="20" id="last_name" value="<?php echo isset($tmp_reg_info['last_name'])?$tmp_reg_info['last_name']:''; ?>" name="wpdm_reg[last_name]">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-user-circle"></i></span></div>
                                        <input class="form-control" required="required" placeholder="<?php _e('Username','wpdm-premium-packages'); ?>" type="text" size="20" class="required" id="user_login" value="<?php echo isset($tmp_reg_info['user_login'])?$tmp_reg_info['user_login']:''; ?>" name="wpdm_reg[user_login]">
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="input-group input-group-lg">
                                        <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-envelope"></i></span></div>
                                        <input class="form-control form-control-lg" required="required" type="email" size="25" placeholder="<?php _e('E-mail','wpdm-premium-packages'); ?>" id="user_email" value="<?php echo isset($tmp_reg_info['user_email'])?$tmp_reg_info['user_email']:''; ?>" name="wpdm_reg[user_email]">
                                    </div>

                                </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <div class="input-group input-group-lg">
                                                <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-key"></i></span></div>
                                                <input class="form-control" placeholder="<?php _e('Password','wpdm-premium-packages'); ?>" title="<?php _e('Password','wpdm-premium-packages'); ?>" required="required" type="password" size="20" class="required" id="password" value="" name="wpdm_reg[user_pass]">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="input-group input-group-lg">
                                                <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-check-circle"></i></span></div>
                                                <input class="form-control form-control-lg" data-match="#password" data-match-error="<?php _e('Not Matched!','wpdm-premium-packages'); ?>" required="required" placeholder="<?php _e('Confirm Password','wpdm-premium-packages'); ?>" title="<?php _e('Confirm Password','wpdm-premium-packages'); ?>" type="password" size="20" class="required" equalto="#password" id="confirm_user_pass" value="" name="confirm_user_pass">
                                            </div>
                                        </div>
                                    </div>

                                <?php do_action("wpdm_register_form"); ?>
                                <?php do_action("register_form"); ?>


                                <div class="row">
                                    <div class="col-md-12"><button type="submit" class="btn btn-success btn-lg btn-block" id="registerform-submit" name="wp-submit"><i class="fa fa-user-plus"></i> &nbsp; <?php _e('Register','wpdm-premium-packages'); ?></button></div>

                                </div>

                            </form>

                    </div>
                </div>

            <div class="col-md-6"  style="padding-left: 30px">
                <div id="wpdmlogin_checkout">
                    <h2 class="fetfont"><i class="fas fa-lock"></i> <?php _e('Login', 'wpdm-premium-packages'); ?></h2>
                <?php do_action("wpdm_before_login_form"); ?>
                <form name="loginform" id="loginform" action="" method="post" class="login-form" style="margin: 0">
                    <input type="hidden" name="permalink" value="<?php the_permalink(); ?>" />

                    <?php global $wp_query; if(\WPDM\Session::get('login_error')) {  ?>
                        <div class="error alert alert-danger" >
                            <b><?php _e('Login Failed!','wpdm-premium-packages'); ?></b><br/>
                            <?php echo preg_replace("/<a.*?<\/a>\?/i","",\WPDM\Session::get('login_error')); \WPDM\Session::clear('login_error'); ?>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-envelope"></i></span></div>
                            <input placeholder="<?php _e('Email or Username','wpdm-premium-packages'); ?>" type="text" name="wpdm_login[log]" id="user_login" class="form-control form-control-lg required text" value="" size="20" tabindex="38" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group input-group-lg">
                            <div class="input-group-prepend"><span class="input-group-text" ><i class="fa fa-key"></i></span></div>
                            <input type="password" placeholder="<?php _e('Password','wpdm-premium-packages'); ?>" name="wpdm_login[pwd]" id="user_pass" class="form-control form-control-lg required password" value="" size="20" tabindex="39" />
                        </div>
                    </div>


                    <?php do_action("wpdm_login_form"); ?>
                    <?php do_action("login_form"); ?>

                    <div class="form-group" style="line-height: 33px;">
                        <div class="float-right">
                            <?php _e('Forgot Password?','wpdm-premium-packages'); ?> <a class="color-blue" href="<?php echo wpdm_lostpassword_url(); ?>"><?php _e('Request New','wpdm-premium-packages'); ?></a>
                        </div>
                        <label class="eden-checkbox"><input class="wpdm-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /><?php _e('Remember Me','wpdm-premium-packages'); ?></label>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" name="wp-submit" id="loginform-submit" class="btn btn-block btn-primary btn-lg"><i class="fa fa-key"></i> &nbsp; <?php _e('Login','wpdm-premium-packages'); ?></button>
                        </div>

                    </div>


                    <input type="hidden" name="redirect_to" value="<?php echo isset($log_redirect)?$log_redirect:$_SERVER['REQUEST_URI']; ?>" />

                </form>
                <?php do_action("wpdm_after_login_form"); ?>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <!-- div class="card-footer">
        <div class="row">
            <div class="col-md-6 signup-col" style="padding-right: 30px">
                <button type="button" class="btn btn-success btn-lg btn-block">Signup and Continue</button>
            </div>
            <div class="col-md-6"  style="padding-left: 30px">
                <button type="button" name="wp-submit" id="loginform-submit" class="btn btn-block btn-primary btn-lg"><i class="fa fa-key"></i> &nbsp; <?php _e('Login and Continue','wpdm-premium-packages'); ?></button>
            </div>
        </div>
    </div -->

        <?php
        /*
        $__wpdm_social_login = get_option('__wpdm_social_login');
        $__wpdm_social_login = is_array($__wpdm_social_login)?$__wpdm_social_login:array();
        if(count($__wpdm_social_login) > 0) { ?>
            <div class="panel-heading text-center"><?php echo isset($params['social_title'])?$params['social_title']:__("Or Connect Using Your Social Account", "download-manager"); ?></div>
    <div class="panel-body text-center">
            <?php if(isset($__wpdm_social_login['facebook'])){ ?><button type="button" onclick="return _PopupCenter('<?php echo home_url('/?sociallogin=facebook'); ?>', 'Facebook', 400,400);" class="btn btn-social wpdm-facebook wpdm-facebook-connect"><i class="fab fa-facebook-f"></i></button><?php } ?>
            <?php if(isset($__wpdm_social_login['twitter'])){ ?><button type="button" onclick="return _PopupCenter('<?php echo home_url('/?sociallogin=twitter'); ?>', 'Twitter', 400,400);" class="btn btn-social wpdm-twitter wpdm-linkedin-connect"><i class="fab fa-twitter"></i></button><?php } ?>
            <?php if(isset($__wpdm_social_login['linkedin'])){ ?><button type="button" onclick="return _PopupCenter('<?php echo home_url('/?sociallogin=linkedin'); ?>', 'LinkedIn', 400,400);" class="btn btn-social wpdm-linkedin wpdm-twitter-connect"><i class="fab fa-linkedin-in"></i></button><?php } ?>
            <?php if(isset($__wpdm_social_login['google'])){ ?><button type="button" onclick="return _PopupCenter('<?php echo home_url('/?sociallogin=google'); ?>', 'Google', 400,400);" class="btn btn-social wpdm-google-plus wpdm-google-connect"><i class="fab fa-google"></i></button><?php } ?>


    </div>
        <?php } */ ?>

</div>

    <script>
        jQuery(function ($) {
            $('#__reg_nonce').val('<?php echo wp_create_nonce(NONCE_KEY); ?>');
            $.getScript('<?php echo WPDM_BASE_URL.'assets/js/validator.min.js'; ?>', function () {
                $('#registerform').validator();
            });
            var llbl = $('#registerform-submit').html();
            $('#registerform').submit(function () {

                $('#registerform-submit').html("<i class='fa fa-spin fa-spinner'></i> <?php _e('Please Wait...','wpdm-premium-packages'); ?>").prop('disabled', true);
                $(this).ajaxSubmit({
                    success: function (res) {
                        if (!res.success) {
                            $('form .alert-danger').hide();
                            $('#registerform').prepend("<div class='alert alert-danger'>"+res.message+"</div>");
                            $('#registerform-submit').html(llbl).prop('disabled', false);
                            setTimeout(function () {
                                $('#registerform .alert').fadeOut();
                            }, 3000);
                        } else {
                            $('#registerform-submit').html("<i class='fa fa-check-circle'></i> <?php _e('Loading Payment Options...','wpdm-premium-packages'); ?>");
                            $('#wpdm-checkout').load(wpdm_ajax_url+"?action=payment_options");

                        }
                    }
                });
                return false;
            });

            $('#loginform-submit').on('click', function () {
                $('#loginform').submit();
            });
            $('#loginform').submit(function () {

                $('#loginform-submit').html("<i class='fa fa-spin fa-spinner'></i> <?php _e('Please Wait...','wpdm-premium-packages'); ?>");
                $(this).ajaxSubmit({
                    success: function (res) {
                        if (!res.success) {
                            $('form .alert-danger').hide();
                            $('#loginform').prepend("<div class='alert alert-danger'><?php _e('Failed! Incorrect login info.','wpdm-premium-packages'); ?></div>");
                            $('#loginform-submit').html(llbl);
                            setTimeout(function () {
                                $('#loginform .alert').fadeOut();
                            }, 3000);
                        } else {
                            $('#loginform-submit').html("<i class='fa fa-check-circle'></i> <?php _e('Loading Payment Options...','wpdm-premium-packages'); ?>");
                            $('#wpdm-checkout').load(wpdm_ajax_url+"?action=payment_options");

                        }
                    }
                });
                return false;
            });

            $('body').on('click', '#checkout-login .alert', function () {
                $(this).fadeOut();
            });
        });
    </script>
<style>
    #checkout-login .col-md-6.signup-col{
        position: relative;
    }
    #checkout-login .col-md-6.signup-col::after {
        content: "";
        position: absolute;
        right: 0;
        height: 100%;
        width: 1px;
        border-right: 1px dashed #cccccc;
        top: 0;
        bottom: 0;
    }
    .w3eden .form-control{
        border-radius: 3px;
    }
    .w3eden .input-group .input-group-addon{
        border-radius: 3px 0 0 3px !important;
    }
    .w3eden .input-group .input-group-addon,
    .w3eden .form-control-lg,
    .w3eden .input-group-lg .form-control{
        font-size: 11pt !important;
    }
    .w3eden .input-group .form-control{
        border-radius: 0 3px 3px 0 !important;
    }
    #checkout-login  .well {

        margin-bottom: 10px;
        box-shadow: none;
        background: rgba(0,0,0,0.03);
        font-weight: 300 !important;

    }
</style>