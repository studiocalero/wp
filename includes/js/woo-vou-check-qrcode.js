"use strict";

jQuery( document ).ready( function( $ ) { 
    
	// hide/show redeem amount on change of redeem method
    $( document ).on( 'change', '#vou_redeem_method',  function() {
    	
    	// get selected redeem method value
    	var redeem_method = $( this ).val();
    	if( redeem_method == 'partial' ) {
    		$('.woo-vou-partial-redeem-amount').fadeIn();
    	} else {
    		$('.woo-vou-partial-redeem-amount').fadeOut	();
    	}    	
    });

    
    if( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }

    //vendor QR scan and login code start
    if( WooVouQRCode.enabled_qr_login == 'yes' ){
        $('.woo-vou-qrcode-wplogin-wrap').hide();
    }

    
    //direct redeem url feature
    if( WooVouQRCode.woo_vou_direct_redeem == 1 && $('#woo_vou_voucher_code_submit').length ){
        $('#woo_vou_voucher_code_submit').click();
    }

    jQuery(".woo-vou-show-wp-login-form").click(function(){
        $('.woo-vou-qrcode-wplogin-wrap').show();   
        $('.woo-vou-qrlogin-wrap').hide();   
    })


    jQuery(".woo-vou-show-qr-login-form").click(function(){
        $('.woo-vou-qrcode-wplogin-wrap').hide();   
        $('.woo-vou-qrlogin-wrap').show();   
    })

    jQuery(document).ready(function($) {
        $(".woo_vou_voucher_scan").click(function() {
            var current_btn = jQuery(this);
            var voucode = jQuery(this).attr('data-code');
            current_btn.closest('.woo-vou-qrlogin-wrap').find('.woo-vou-vendor-qr-login-error').hide();
            $('.woo-vou-qrcode-wplogin-wrap').hide();

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                html5QrCode.stop();
                jQuery.ajax({
                    url: WooVouQRCode.ajaxurl,
                    type: 'post',
                    data: {
                        action: 'woo_vou_vendor_login_using_qrcode',
                        woo_vou_scaned_code: decodedText,
                        voucode: voucode,
                        security: WooVouQRCode.security,
                    },
                    success: function(data) {
                        var response_data = jQuery.parseJSON(data);
                        if (response_data.status == 1) {
                            location.reload();
                        } else {
                            current_btn.closest('.woo-vou-qrlogin-wrap').find('.woo-vou-vendor-qr-login-error').show();
                            current_btn.closest('.woo-vou-qrlogin-wrap').find('.woo-vou-vendor-qr-login-error').html(response_data.msg);
                        }
                    },
                    error: function(data) {
                        // Handle error
                    },
                });
            };

            const html5QrCode = new Html5Qrcode("woo_vou_vendor_scan_reader_" + voucode);
            const config = { fps: 10, qrbox: { width: 300, height: 300 } };
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
        });
    });
   //vendor QR scan and login code end 
    
 });