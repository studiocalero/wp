"use strict";
jQuery( document ).ready( function( $ ) {
	function loadVoucherData() {
        var month = $('.woo_vou_filter_month').val();
        var year = $('.woo_vou_filter_year').val();

        $('.woo_vou_filter_ajax_response').html('<div class="woo-vou-loading-spinner" style="display: none;"></div>');
        $('.woo-vou-loading-spinner').show();
        $('.woo-vou-dash-voucher-status-table').hide();
        $('.woo_vou_filter_go').attr('disabled',true);

        $.ajax({
            url: ajaxurl, // This is automatically defined in the WordPress admin
            type: 'POST',
            data: {
                action: 'woo_vou_dashboard_widget',
                month: month,
                year: year,
                security: woovoudashboard.woo_vou_nonce,
            },
            success: function(response) {
                if (response.success) {
                    $('.woo_vou_filter_ajax_response').html(response.data.html);
                    $('.woo_vou_filter_go').removeAttr('disabled');
                }
            }
        });
    }

    $('.woo_vou_filter_go').click(function() {
        loadVoucherData();
    });

    // Initial load
    loadVoucherData();
});

