<?php
/**
 * New / Edit Coupon Code form
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $wpdb;
?>
<div class="w3eden">
    <?php
    $menus = [
        ['link' => "edit.php?post_type=wpdmpro&page=pp-coupon-codes", "name" => __("All Coupons", "wpdm-premium-packages"), "active" => false],
        ['link' => "edit.php?post_type=wpdmpro&page=pp-coupon-codes&task=new_coupon", "name" => __("Add New", "wpdm-premium-packages"), "active" => true],
    ];

    WPDM()->admin->pageHeader(esc_attr__( "Coupon Codes", "wpdm-premium-packages" ), 'ticket-alt fas color-purple', $menus);
    ?>

    <div class="wpdm-admin-page-content" id="wpdm-wrapper-panel">
    <!--<div class="panel panel-default" id="wpdm-wrapper-panel">
        <div class="panel-heading">
            <b><i class="fas fa-ticket-alt color-purple"></i> &nbsp;
                <?php /*echo wpdm_query_var('ID') > 0 ? __('Edit Coupon Code', 'wpdm-premium-packages') : __('New Coupon Code', 'wpdm-premium-packages'); */?></b>
            <div class="pull-right">
                <a href="edit.php?post_type=wpdmpro&page=pp-coupon-codes" class="btn btn-sm btn-default">
                    <i class="fas fa-long-arrow-alt-left color-green"></i> <?php /*_e('Back','wpdm-premium-packages'); */?>
                </a>
            </div>
        </div>-->
        <div class="panel-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <form method="post" action="" id="add-license-form">
                            <input type="hidden" name="do" value="<?php echo wpdm_query_var('ID') > 0?'updatecoupon':'addcoupon'; ?>">
                            <?php wp_nonce_field(NONCE_KEY, ((int)wpdm_query_var('ID') > 0?'__ucc':'__anc')); ?>
                            <div class="form-group">
                                <label><?php _e('Coupon Code:','wpdm-premium-packages'); ?> <span class="color-red">*</span></label>
                                <input id="title" class="form-control input-lg" type="text" required="required"  name="coupon[code]"  value="<?php echo isset($coupon)?$coupon->code:''; ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Discount Type:','wpdm-premium-packages'); ?></label>
                                        <select name="coupon[type]" id="dtypes" class="form-control">
                                            <option value="percent"><?php _e('Percent','wpdm-premium-packages'); ?> (%)</option>
                                            <option value="fixed" <?php echo isset($coupon)?selected('fixed',$coupon->type, false):''; ?>><?php _e('Fixed','wpdm-premium-packages'); ?> (<?php echo wpdmpp_currency_sign(); ?>)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Discount Amount:','wpdm-premium-packages'); ?> <span class="color-red">*</span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" required="required" name="coupon[discount]" placeholder="Any Numeric Value" value="<?php echo isset($coupon)?$coupon->discount:''; ?>">
                                            <span class="input-group-addon color-green" style="width: 40px" id="dtp">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Product ID:','wpdm-premium-packages'); ?> <span class="color-purple ttip" title="If you want to allow the coupon on cart total, do not select any product."><i class="fa fa-info-circle"></i></span></label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" id="lpid" placeholder="Cart Coupon" name="coupon[product]" value="<?php echo isset($coupon)?$coupon->product:''; ?>">
                                            <div class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#product-src-modal"><i class="fas fa-search-plus"></i></button></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?php _e('Description:','wpdm-premium-packages'); ?></label>
                                <textarea class="form-control" cols="60" rows="4" name="coupon[description]" placeholder="Coupon Description"><?php echo isset($coupon)?$coupon->description:''; ?></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Expire Date:','wpdm-premium-packages'); ?></label>
                                        <input class="form-control" placeholder="Never" id="expdate" type="text" name="coupon[expire_date]" value="<?php echo isset($coupon->expire_date) && $coupon->expire_date > 0  ? date("Y-m-d H:i a", $coupon->expire_date):''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Minimum Spend:','wpdm-premium-packages'); ?></label>
                                        <input class="form-control" type="number" placeholder="No Limit"  name="coupon[min_order_amount]" value="<?php echo isset($coupon)?$coupon->min_order_amount:''; ?>"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Maximum Spend:','wpdm-premium-packages'); ?></label>
                                        <input class="form-control" type="number" placeholder="No Limit" name="coupon[max_order_amount]" value="<?php echo isset($coupon)?$coupon->max_order_amount:''; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php _e('Limit Usage:','wpdm-premium-packages'); ?></label>
                                        <input class="form-control" placeholder="Unlimited" type="number" name="coupon[usage_limit]" value="<?php echo isset($coupon)?$coupon->usage_limit:''; ?>" />
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><?php _e('Allowed Emails:','wpdm-premium-packages'); ?></label>
                                        <input class="form-control" type="text" placeholder="Multiple emails are sperated by comma(,)"  name="coupon[allowed_emails]" value="<?php echo isset($coupon)?$coupon->allowed_emails:''; ?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group well text-right">
                                <button class="btn btn-primary btn-lg"><i class="far fa-hdd"></i> &nbsp;<?php _e('Save Coupon Code','wpdm-premium-packages'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="product-src-modal" tabindex="-1" role="dialog" aria-labelledby="product-src-modalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="product-src-modalLabel"><?php _e('Select Product','wpdm-premium-packages'); ?></h4>
                </div>
                <div class="modal-body">
                    <input type="text" placeholder="<?php _e('Search Product...','wpdm-premium-packages'); ?>" class="form-control input-lg" id="srcp">
                    <br/>
                    <div class="list-group" id="productlist">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    jQuery(function($){
        $('#expdate').datetimepicker({dateFormat:"yy-mm-dd", timeFormat: "hh:mm tt"});

        function search_product()
        {
            $.get('<?= wpdm_rest_url('search') ?>', { search: $('#srcp').val(), premium: 1 }, function (res) {

                $('#productlist').html("");

                $(res.packages).each(function( i, package ) {
                    var licenses = package.licenses;
                    $("#productlist").append("<div class='list-group-item'><a style='opacity: 1;margin-right: -5px;transform: scale(1.4)' href='#' data-pid='" + package.ID + "' data-index='" + i + "' class='pull-right wpdm-insert-pid'><i class='fa fa-plus-circle color-green'></i></a>" + package.post_title + "</div>");
                });
            });
        }

        $('body').on('keyup', '#srcp', function () {
            search_product();
        });

        $('body').on('click', '.wpdm-insert-pid', function (e) {
            e.preventDefault();
            $('#lpid').val($(this).data('pid'));
            $('#product-src-modal').modal('hide');
        });

        $('#add-license-form').on('submit', function () {
            $('#add-license-form .btn-primary.btn-lg').css('width', $('#add-license-form .btn-primary.btn-lg').css('width')).html("<i class='fas fa-sun fa-spin'></i> Saving...").attr('disabled', 'disabled');
        });

        $('body').on('click', '#dtypes', function () {
            var stype = $(this).val() == 'percent'?'%':'<?php echo wpdmpp_currency_sign(); ?>';
            $('#dtp').html(stype);
        });
        $('.ttip').tooltip();
    });
</script>
