<?php
/**
 * Create new order
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $wpdb;

$order_id = uniqid();

$sbilling =  array
(
    'first_name' => '',
    'last_name' => '',
    'company' => '',
    'address_1' => '',
    'address_2' => '',
    'city' => '',
    'postcode' => '',
    'country' => '',
    'state' => '',
    'email' => '',
    'order_email' => '',
    'phone' => ''
);

?>
<?php ob_start(); ?>

<table width="100%" cellspacing="0" class="table">
    <thead>
    <tr>
        <th align="left"><?php _e("Item Name","wpdm-premium-packages");?></th>
        <th align="left"><?php _e("Unit Price","wpdm-premium-packages");?></th>
        <th align="left"><?php _e("Quantity","wpdm-premium-packages");?></th>
        <th align="right" style="width: 150px;text-align: right"><?php _e("Subtotal","wpdm-premium-packages");?></th>
        <th align="right" style="width: 60px;text-align: right"></th>
    </tr>
    </thead>
    <tbody id="admin-cart-body">

    </tbody>

</table>
<?php $content = ob_get_clean(); ?>


<div class="w3eden admin-orders">
    <?php

    $menus = [
        ['link' => "edit.php?post_type=wpdmpro&page=orders", "name" => __("All Orders", "download-manager"), "active" => false],
        ['link' => "edit.php?post_type=wpdmpro&page=orders&task=createorder", "name" => __("Create New", "download-manager"), "active" => true],
    ];

    WPDM()->admin->pageHeader(esc_attr__( "Orders", "wpdm-premium-packages" ), 'cart-arrow-down color-purple', $menus);
    ?>
    <div class="wpdm-admin-page-content" id="wpdm-wrapper-panel">
        <div class="panel-body">

            <div id="msg" style="border-radius: 3px;display: none;" class="alert alert-success"><?php _e("Message", "wpdm-premium-packages"); ?></div>
            <div class="row">
                <div class=" col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php _e("Order ID", "wpdm-premium-packages"); ?></div>
                        <div class="panel-body">
                            <span class="lead">&mdash; &mdash; &mdash; &mdash;</span>
                        </div>
                    </div>
                </div>
                <div class=" col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php _e("Order Date", "wpdm-premium-packages"); ?></div>
                        <div class="panel-body">
                            <span class="lead"><?php echo date("M d, Y h:i a", time()); ?></span>
                        </div>
                    </div>
                </div>
                <div class=" col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php _e("Order Total", "wpdm-premium-packages"); ?></div>
                        <div class="panel-body">
                            <span class="lead" id="ototal"><?php echo $currency_sign ; ?>0.00</span>
                        </div>
                    </div>
                </div>

                <div style="clear: both"></div>
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><?php _e("Order Items", "wpdm-premium-packages"); ?></div>
                        <?php echo $content; ?>
                        <div class="panel-footer">
                            <button class="btn btn-info" type="button"  data-toggle="modal" data-target="#myModal"><i class="fas fa-plus-circle"></i> Add Item</button>
                            <button class="btn btn-danger btn-ec" type="button"><i class="fas fa-trash"></i> Empty Cart</button>
                            <button class="btn btn-success btn-sord" type="button"><i class="fas fa-hdd"></i> Save Order</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?php _e('Select Product','wpdm-premium-packages'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" placeholder="<?php _e('Search Product...','wpdm-premium-packages'); ?>" class="form-control" id="srcp">
                        <div class="input-group-btn"><button type="button" class="btn btn-default" id="srcpnow"><i class="fas fa-search"></i></button></div>
                    </div><br/>
                    <div class="list-group" id="productlist"></div>
                </div>
            </div>
        </div>
    </div>

</div>


<script>

    jQuery(function($){

        $('#srcpnow').on('click', function () {
            $.get('<?= wpdm_rest_url('search') ?>', { search: $('#srcp').val(), premium: 1 }, function (res) {
                //res = JSON.parse(res);
                $('#productlist').html("");

                $(res.packages).each(function( i, package ) {
                    var licenses = package.licenses;
                    if(!licenses) {
                        $("#productlist").append("<div class='list-group-item'><a style='opacity: 1;margin-right: -5px;transform: scale(1.4)' href='#' data-pid='" + package.ID + "' data-license='' data-index='" + i + "' class='pull-right insert-pid'><i class='fa fa-plus-circle color-green'></i></a>" + package.post_title + "</div>");
                    }
                    else {
                        console.log(licenses);
                        $.each(licenses, function(licid, license) {
                            $("#productlist").append("<div class='list-group-item'><a style='opacity: 1;margin-right: -5px;transform: scale(1.4)' href='#' data-pid='" + package.ID + "' data-license='"+licid+"' data-index='" + i + "' class='pull-right insert-pid'><i class='fa fa-plus-circle color-green'></i></a>" + package.post_title + " &mdash; <span class='text-info'>" + license.name + "</span></div>");
                        });
                    }
                });
            });
        });


        $('#admin-cart-body').html('<tr><td colspan="4"><i class="fas fa-sun fa-spin"></i> Fetching Cart...</td></tr>');
        $.get(ajaxurl, {action: 'wpdmpp_admin_cart_html'}, function (res) {
            $('#admin-cart-body').html(res.cart_html);
            $('#ototal').html(res.cart_total);
        });

        $('body').on('click', '.insert-pid', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            $(this).find('.fa').removeClass('fa-plus-circle').addClass('fa-sun fa-spin');

            //wpdmpp_admin_cart.push($(this).data('pid')."|".$(this).data('license'));

            //window.localStorage.setItem("wpdmpp_admin_cart", JSON.stringify(wpdmpp_admin_cart));

            var $this = $(this);
            $.get('<?= home_url('/') ?>', {addtocart: $(this).data('pid'), license: $(this).data('license'), custom_order: 1}, function (res) {
                $('#admin-cart-body').html(res.cart_html);
                $('#ototal').html(res.cart_total);
                $this.find('.fa').removeClass('fa-sun fa-spin').addClass('fa-check-circle');
            });


        });



        $('.btn-ec').on('click', function () {
            wpdm_boot_popup("Clearing Cart", "Are you sure?", [
                {
                    class: 'btn btn-danger',
                    label: 'Yes, Clear!',
                    callback: function () {
                        var modal = this;
                        $.get(ajaxurl, {action: 'wpdmpp_empty_cart'}, function (){
                            $('#admin-cart-body').html('<tr><td colspan="4"><i class="fas fa-shopping-cart"></i> <?php _e('Cart is empty', 'wpdm-premium-packages'); ?></td></tr>');
                            modal.modal('hide');
                        });
                    }
                },
                {
                    class: 'btn btn-default',
                    label: 'No, Later.',
                    callback: function () {
                        this.modal('hide');
                    }

                }
            ]);

        });

        $('body').on('click', '.btn-delete-cart-item', function (e) {
            e.preventDefault();
            if(!confirm('<?= esc_attr__( 'Delete item from cart?', PM_TEXT_DOMAIN ) ?>')) return;
            var pid = $(this).data('pid');
            $.get('<?= home_url('/') ?>', {wpdmpp_remove_cart_item: pid}, function (){
                $('#citem-'+pid).hide();
            });
        });

        $('.btn-sord').on('click', function () {
            wpdm_boot_popup("Saving Order", "You won't be able to edit order items after saving it. Please re-check if all items are added properly", [
                {
                    class: 'btn btn-success',
                    label: 'Save Order',
                    callback: function () {
                        //$('#admin-cart-body').html('<tr><td colspan="4"><i class="fas fa-sun fa-spin"></i> Saving Cart...</td></tr>');
                        var $this = this;
                        $this.find('.modal-body').html('<i class="fas fa-sun fa-spin"></i> Saving Order...');
                        $.get(ajaxurl, {action: 'wpdmpp_admin_save_custom_order', oid: '<?php echo $order_id; ?>', __nonce: '<?php echo wp_create_nonce(NONCE_KEY); ?>'}, function (res) {
                            if(res.status == 1) {
                                window.localStorage.removeItem("wpdmpp_admin_cart");
                                location.href = "edit.php?post_type=wpdmpro&page=orders&task=vieworder&id="+res.oid;
                                $this.modal('hide');
                            }
                            else
                                alert(res);
                        });
                    }
                },
                {
                    class: 'btn btn-default',
                    label: 'Check Again',
                    callback: function () {
                        this.modal('hide');
                    }

                }
            ]);

        });


    });
</script>
<style>
    .chzn-search input{ display: none; }.chzn-results{ padding-top: 5px !important; }
    .btn-group.bootstrap-select .btn{ border-radius: 3px !important; }
    a:focus{ outline: none !important; }
    .panel-heading{ font-weight: bold; }
    .text-renew *{ font-weight: 800; color: #1e9460; }
    .w3eden .dropdown-menu > li{ margin-bottom: 0; }
    .w3eden .dropdown-menu > li > a{ padding: 5px 20px; }
</style>
