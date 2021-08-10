<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $wpdb;
?>
<div class="w3eden">
    <?php
    $menus = [
        ['link' => "edit.php?post_type=wpdmpro&page=pp-license", "name" => __("All Licenses", "wpdm-premium-packages"), "active" => false],
        ['link' => "edit.php?post_type=wpdmpro&page=pp-license&task=NewLicense", "name" => __("New License", "wpdm-premium-packages"), "active" => true],
    ];

    WPDM()->admin->pageHeader(esc_attr__( "Licenses", "wpdm-premium-packages" ), 'id-card color-purple', $menus);
    ?>

    <div class="wpdm-admin-page-content" id="wpdm-wrapper-panel">
        <div class="panel-body">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                <form method="post" action="" id="add-license-form">
                    <input type="hidden" name="do" value="addlicense">
                    <?php wp_nonce_field(NONCE_KEY, '__suc'); ?>
                    <div class="form-group">
                        <label><?php _e('License No:','wpdm-premium-packages'); ?></label>
                        <input id="title" class="form-control input-lg" type="text"  name="license[licenseno]" readonly="readonly" value="<?php echo \WPDMPP\Libs\LicenseManager::generate_licensekey(); ?>">
                    </div>
                    <div class="row">
                        <div class="col-md-4"> <div class="form-group">
                                <label><?php _e('Order ID:','wpdm-premium-packages'); ?></label>
                                <input id="title" class="form-control" type="text"  name="license[oid]">
                            </div></div>
                        <div class="col-md-4"><div class="form-group">
                                <label><?php _e('Product ID:','wpdm-premium-packages'); ?><span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input class="form-control" type="text" id="lpid" required="required"  name="license[pid]">
                                    <div class="input-group-btn"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#product-src-modal"><i class="fas fa-search-plus"></i></button></div>
                                </div>
                            </div></div>
                        <div class="col-md-4"><div class="form-group">
                                <label><?php _e('Domain Limit:','wpdm-premium-packages'); ?></label>
                                <input class="form-control" type="number" size="5" min="0" step="1"  name="license[domain_limit]" value="1"/>
                            </div></div>

                    </div>
                    <div class="form-group">
                        <label><?php _e('Domains:','wpdm-premium-packages'); ?></label>
                        <textarea class="form-control" cols="60" rows="6" name="license[domain]"></textarea>
                        <em><?php _e("One domain per line. Don't use 'http://' or 'www' only 'domain.com'","wpdm-premium-packages"); ?></em>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group">
                                <label><?php _e('Activation Date:','wpdm-premium-packages'); ?></label>
                                <input class="form-control" id="actdate" type="text" name="license[activation_date]" value="" />
                            </div></div>
                        <div class="col-md-6"><div class="form-group">
                                <label><?php _e('Expire Period:','wpdm-premium-packages'); ?></label>

                                    <input id="expdate" class="form-control" type="text"  name="license[expire_date]" value=""/>

                            </div></div>

                    </div>



                    <div class="form-group well text-right">
                        <button class="btn btn-primary btn-lg"><i class="far fa-hdd"></i> Add New License</button>
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

        $('#actdate, #expdate').datetimepicker({dateFormat:"yy-mm-dd", timeFormat: "hh:mm tt"});

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
    });
</script>
