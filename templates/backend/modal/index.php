<?php defined('ABSPATH') or die('You can not access the file directly'); ?>
<script>
    "use strict";

    function closeModal() {
        jQuery(".mxlms-modal").modal('hide');
    }

    function closeAllDropDown() {
        mxlmsHandleDropDown(0);
    }

    function present_right_modal(page, header_title, param1, param2, param3) {
        // CLOSE ALL DROP DOWN
        closeAllDropDown();

        // show the modal first
        jQuery("#mxlms-right-modal").modal('show', {
            backdrop: 'true'
        });
        // SHOW THE PLACEHOLDER
        jQuery(".mxlms-custom-modal-body").hide();
        jQuery("#mxlms-right-modal .mxlms-custom-modal-content").addClass(
            "mxlms-custom-modal-body-placeholder"
        );

        jQuery('#mxlms-right-modal .mxlms-custom-modal-header h2').html(header_title);
        jQuery('#mxlms-right-modal .mxlms-custom-modal-body').block({
            message: null,
            overlayCSS: {
                backgroundColor: '#ffffff'
            }
        });

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

        jQuery.post(
            ajaxurl, {
                'action': 'mxlms',
                'page': page,
                'task': 'load_modal_page',
                'param1': param1,
                'param2': param2,
                'param3': param3
            },
            function(response) {
                jQuery('#mxlms-right-modal .mxlms-custom-modal-content').removeClass('mxlms-custom-modal-body-placeholder');
                jQuery('#mxlms-right-modal .mxlms-custom-modal-body').html(response);
                jQuery('#mxlms-right-modal .mxlms-custom-modal-body').unblock();

                // HIDE THE PLACEHOLDER
                jQuery("#mxlms-right-modal .mxlms-custom-modal-content").removeClass(
                    "mxlms-custom-modal-body-placeholder"
                );
                jQuery(".mxlms-custom-modal-body").show();
            }
        )
    }

    function confirmation_for_deletion(header_title, param1, param2, param3, param4, param5, param6, param7, param8, param9) {

        closeModal();
        jQuery("#confirmation-modal").modal('show', {
            backdrop: 'true'
        });
        jQuery("#confirmation-modal .mxlms-custom-modal-body").html();

        jQuery('#confirmation-modal .mxlms-custom-modal-header h2').html(header_title);

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

        jQuery.post(
            ajaxurl, {
                'action': 'mxlms',
                'task': 'load_confirm_modal_page',
                'param1': param1,
                'param2': param2,
                'param3': param3,
                'param4': param4,
                'param5': param5,
                'param6': param6,
                'param7': param7,
                'param8': param8,
                'param9': param9
            },
            function(response) {
                jQuery('#confirmation-modal .mxlms-custom-modal-body').html(response);
            }
        )
    }


    function confirmation_for_updating(header_title, param1, param2, param3, param4, param5, param6, param7, param8, param9) {

        closeModal();
        jQuery("#confirmation-for-update-modal").modal('show', {
            backdrop: 'true'
        });
        jQuery("#confirmation-for-update-modal .mxlms-custom-modal-body").html();

        jQuery('#confirmation-for-update-modal .mxlms-custom-modal-header h2').html(header_title);

        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

        jQuery.post(
            ajaxurl, {
                'action': 'mxlms',
                'task': 'load_confirm_modal_page_for_updating',
                'param1': param1,
                'param2': param2,
                'param3': param3,
                'param4': param4,
                'param5': param5,
                'param6': param6,
                'param7': param7,
                'param8': param8,
                'param9': param9
            },
            function(response) {
                jQuery('#confirmation-for-update-modal .mxlms-custom-modal-body').html(response);
            }
        )
    }
</script>


<!-- CONFIRMATION MODAL -->
<div class="mxlms-custom-modal mxlms-fade mxlms-modal mxlms-confirmation-modal" id="confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="mxlms-custom-modal-dialog" role="document">
        <div class="mxlms-custom-modal-content container mxlms-text-center">
            <div class="mxlms-custom-modal-header">
                <button type="button" class="mxlms-custom-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="mxlms-custom-modal-title" id="myModalLabel2"></h2>
            </div>

            <div class="mxlms-custom-modal-body">
            </div>
        </div>
    </div>
</div>
<!-- CONFIRMATION Modal -->

<!-- CONFIRMATION MODAL FOR UPDATING -->
<div class="mxlms-custom-modal mxlms-fade mxlms-modal mxlms-confirmation-modal" id="confirmation-for-update-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="mxlms-custom-modal-dialog" role="document">
        <div class="mxlms-custom-modal-content container mxlms-text-center">
            <div class="mxlms-custom-modal-header">
                <button type="button" class="mxlms-custom-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="mxlms-custom-modal-title" id="myModalLabel2"></h2>
            </div>

            <div class="mxlms-custom-modal-body">
            </div>
        </div>
    </div>
</div>
<!-- CONFIRMATION Modal FOR UPDATING-->


<!-- RIGHT MODAL STARTS -->
<div class="mxlms-custom-modal mxlms-custom-modal-right mxlms-fade mxlms-modal" id="mxlms-right-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="mxlms-custom-modal-dialog" role="document">
        <div class="mxlms-custom-modal-content container mxlms-custom-modal-body-placeholder">
            <div class="mxlms-custom-modal-header">
                <button type="button" class="mxlms-custom-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h2 class="mxlms-custom-modal-title" id="myModalLabel2"></h2>
            </div>
            <div class="mxlms-custom-modal-body">

            </div>
        </div><!-- mxlms-custom-modal-content -->
    </div><!-- mxlms-custom-modal-dialog -->
</div><!-- RIGHT MODAL ENDS -->