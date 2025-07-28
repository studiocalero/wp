"use strict";

jQuery( document ).ready( function( $ ) {
// Check Voucher code is valid or not
	$( document ).on( 'click', '#woo_vou_check_voucher_code', function() {
		$( '.woo-vou-voucher-code-submit-wrap' ).hide();

		//Voucher Code
		var voucode = $( '#woo_vou_voucher_code' ).val();
		$( '#woo_vou_valid_voucher_code' ).val(voucode);

		if( voucode == '' || voucode == 'undefine' ) {

			//hide submit row
			$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
			console.log(1);
			$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( WooVouCheck.check_code_error ).show();

		}else {

			//show loader
			$( '.woo-vou-check-voucher-code-loader' ).css( 'display', 'inline' );

			//hide error message
			$( '.woo-vou-voucher-code-msg' ).hide();
			$( '.woo-vou-voucher-reverse-redeem' ).html( "" ).hide();

			// Get currency. Used to add comatible with currency switcher(realmag777)
			var currency = $(this).data('currency');
						
			var data = {
							action				: 'woo_vou_check_voucher_code',
							voucode				: voucode,
							ajax				: true,
							currency			: currency,
						};
			//call ajax to chcek voucher code
			jQuery.post( WooVouCheck.ajaxurl, data, function( response ) {

				var response_data = jQuery.parseJSON(response);

				if( response_data.success ) {

					$('.woo-vou-loader.woo-vou-voucher-code-submit-loader').hide();
					//show submit row
					if( response_data.loggedin_guest_user ) {

						if( response_data.expire ) {

							if(response_data.allow_redeem_expired_voucher == 'yes') {
								$( '.woo-vou-voucher-code-submit-wrap' ).fadeIn();
								$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( response_data.success ).show();
							} else {
								$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.success ).show();
							}
						} else {
							if( WooVouCheck.allow_guest_redeem_voucher == 'yes'){
								$( '.woo-vou-voucher-code-submit-wrap' ).fadeIn();
							}
							
							$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( response_data.success ).show();
						}
					} else if( response_data.expire && response_data.allow_redeem_expired_voucher == 'no' ) {
						
						$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
						console.log(2);
						$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.success ).show();
					} else if( response_data.success && response_data.before_start_date ) {
						
						$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
						console.log(3);
						$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.success ).show();

					}
					else if( response_data.vendors_access === false ) {
						
						$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
						console.log(4);
						$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( response_data.success ).show();
					}
					else if( response_data.vendors_access == true ) {
						
						$( '.woo-vou-voucher-code-submit-wrap' ).fadeIn();
						$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( response_data.success ).show();
					} else {

						$( '.woo-vou-voucher-code-submit-wrap' ).fadeIn();
						$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( response_data.success ).show();
					}

					if( response_data.product_detail ) {
						$( '.woo-vou-voucher-code-msg' ).append(response_data.product_detail);
					}
					if( response_data.reverse_redeem ) {
						$( '.woo-vou-voucher-reverse-redeem' ).html( response_data.reverse_redeem ).show();
					}
				} else if( response_data.error ) {

					//hide submit row
					$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
					console.log(5);
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.error ).show();
				} else if ( response_data.used ) {

					//hide submit row
					$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
					console.log(6);
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.used ).show();

					if( response_data.product_detail ) {
						$( '.woo-vou-voucher-code-msg' ).append(response_data.product_detail);
					}
					if( response_data.reverse_redeem ) {
						$( '.woo-vou-voucher-reverse-redeem' ).html( response_data.reverse_redeem ).show();
					}
				}
				//hide loader
				$( '.woo-vou-check-voucher-code-loader' ).hide();

			});
		}
	});

	// Submit Voucher code ( Redeem vocher code )
	$( document ).on( 'click', '#woo_vou_voucher_code_submit', function() {		
		
		//Voucher Code
		var voucode = $( '#woo_vou_valid_voucher_code' ).val();
		var vou_enable_partial_redeem = $('#vou_enable_partial_redeem').val();

		if( ( voucode == '' || voucode == 'undefine' ) ) {

			//hide submit row
			$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
			$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( WooVouCheck.check_code_error ).show();
		} else {

			var redeem_amount = '';
			var redeem_method = '';
			var total_price = '';
			var redeemed_price = '';
			var remaining_redeem_price = '';

			total_price				= woo_vou_check_number($('#vou_code_total_price').val());
			redeemed_price			= woo_vou_check_number($('#vou_code_total_redeemed_price').val());
			remaining_redeem_price 	= woo_vou_check_number($('#vou_code_remaining_redeem_price').val());

			// check partial redeem is enabled
			if( vou_enable_partial_redeem == "yes" ) {

				// get redeem amount and redeem method
				redeem_amount			= woo_vou_check_number($('#vou_partial_redeem_amount').val());
				redeem_method 			= $('#vou_redeem_method').val();

				// redeem amount validation
				if( redeem_method == 'partial' && ( redeem_amount == '' || isNaN( redeem_amount ) ) ) {

					$('.woo-vou-partial-redeem-amount .woo-vou-voucher-code-error').html( WooVouCheck.redeem_amount_empty_error ).fadeIn();
					return false;
				} else if( redeem_method == 'partial' && redeem_amount > remaining_redeem_price ) {

					$('.woo-vou-partial-redeem-amount .woo-vou-voucher-code-error').html( WooVouCheck.redeem_amount_greaterthen_redeemable_amount ).fadeIn();
					return false;
				}
			}

			//hide error message
			$( '.woo-vou-voucher-code-msg' ).hide();
			$( '.woo-vou-voucher-reverse-redeem' ).html( "" ).hide();

			//show loader
			$( '.woo-vou-voucher-code-submit-loader' ).css( 'display', 'inline' );

			var data = {
							action							: 'woo_vou_save_voucher_code',
							voucode							: voucode,
							vou_code_total_price			: total_price,
							vou_code_total_redeemed_price	: redeemed_price,
							vou_code_remaining_redeem_price	: remaining_redeem_price,
							ajax							: true
						};

			// check partial redeem is enabled
			if( vou_enable_partial_redeem == "yes" ) {

				data['vou_partial_redeem_amount'] 		= redeem_amount;
				data['vou_redeem_method'] 				= redeem_method;
			}

			//Add trigger for redeem data
			$( document ).trigger( "vou_redeem_data", data );

			//call ajax to save voucher code
			jQuery.post( WooVouCheck.ajaxurl, data, function( response ) {

				var response_data = jQuery.parseJSON(response);

				if( response_data.success ) {

					//Voucher Code
					$( '#woo_vou_voucher_code' ).val( '' );
					$( '#woo_vou_valid_voucher_code' ).val( '' );
					//hide submit row
					$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( WooVouCheck.code_used_success ).show();
				} else {

					//Voucher Code
					$( '#woo_vou_voucher_code' ).val( '' );
					$( '#woo_vou_valid_voucher_code' ).val( '' );
					//hide submit row
					$( '.woo-vou-voucher-code-submit-wrap' ).fadeOut();
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.error_message ).show();
				}
				//hide loader
				$( '.woo-vou-voucher-code-submit-loader' ).hide();
			});
		}
	});

	// Confirm Delete Voucher code
	$( document ).on( 'click', '.woo_vou_delete_voucher_code', function() {

		if ( confirm( WooVouCheck.delete_code_confirm ) == true ) {
	        return true;
	    } else {
	        return false;
	    }
	});

	// Date Time picker Field
	$('.woo-vou-meta-datetime').each( function() {

		var jQuerythis  = jQuery(this),
		format = jQuerythis.attr('rel');

		jQuerythis.datetimepicker({
			ampm: true,
			dateFormat : format,
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			showTime: false,
		});
    });

    if( $('.woo_vou_multi_select').length ) {

    	// apply select2 on simple select dropdown
    	$('.woo_vou_multi_select').select2();
    }

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


    function woo_vou_check_number(number){
    	number = Math.round(number * 100) / 100;
    	var pattern = /^\d+(.\d{1,2})?$/;
    	return pattern.test(number)?number:'';
    }

	$( document ).on( 'change', '.woo_vou_check_voucher_code_radio_btn',  function() {
		var $woo_vou_check_voucher_code_radio_btn_vaule = $('.woo_vou_check_voucher_code_radio_btn:checked').val();
		$('.woo-vou-voucher-code-msg').hide();
		$('#woo_vou_voucher_code, #woo_vou_multiple_voucher_codes').val('');
		$( '.woo-vou-voucher-code-submit-wrap, .woo-vou-multiple-voucher-code-submit-wrap' ).hide();

		if( $woo_vou_check_voucher_code_radio_btn_vaule == 'woo_vou_check_voucher_code_single' ){
			$('.woo_vou_check_voucher_code_btn').attr('id', 'woo_vou_check_voucher_code');
			$('#woo_vou_multiple_voucher_codes, .woo_vou_multiple_voucher_note').hide();
			$('#woo_vou_voucher_code').show();
		}else{
			$('.woo_vou_check_voucher_code_btn').attr('id', 'woo_vou_check_multiple_voucher_code');
			$('#woo_vou_voucher_code').hide();
			$('#woo_vou_multiple_voucher_codes, .woo_vou_multiple_voucher_note').show();
		}
    });

	$( document ).on( 'click', '#woo_vou_check_multiple_voucher_code', function() {
		$( '.woo-vou-multiple-voucher-code-submit-wrap' ).hide();

		//Voucher Code
		var voucodes = $( '#woo_vou_multiple_voucher_codes' ).val();
		$( '#woo_vou_valid_voucher_code' ).val(voucodes);

		if( voucodes == '' || voucodes == 'undefine' ) {
			//hide submit row
			$( '.woo-vou-multiple-voucher-code-submit-wrap' ).fadeOut();
			$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( WooVouCheck.check_multi_code_error ).show();
		}else {
			//show loader
			$( '.woo-vou-check-voucher-code-loader' ).css( 'display', 'inline' );

			//hide error message
			$( '.woo-vou-voucher-code-msg' ).hide();

			$( '.woo-vou-voucher-reverse-redeem' ).html( "" ).hide();

			// Get currency. Used to add comatible with currency switcher(realmag777)
			var currency = $(this).data('currency');						
			var data = {
				action				: 'woo_vou_check_multiple_voucher_code',
				voucodes			: voucodes,
				ajax				: true,
				currency			: currency,
			};

			//call ajax to chcek voucher code
			jQuery.post( WooVouCheck.ajaxurl, data, function( response ) {
				var response_data = jQuery.parseJSON(response);
				if( response_data.success ) {
					$( '.woo-vou-multiple-voucher-code-submit-wrap' ).fadeIn();
					$( '.woo-vou-voucher-code-msg.woo-vou-voucher-ajax-info-wrap' ).html( response_data.multiple_code_html ).show();
				}
				//hide loader
				$( '.woo-vou-check-voucher-code-loader' ).hide();
				
				// JS foe showing tooltip on voucher code details page
				var tiptip_args = {
					'attribute': 'data-tip',
					'fadeIn': 50,
					'fadeOut': 50,
					'delay': 200
				};
				$( '.woocommerce-help-tip' ).tipTip( tiptip_args );

				if( $('.woo_vou_multiple_redeem_code_check_box').length == 0 ){
					$('#woo_vou_multiple_voucher_code_submit').hide()					
				}else{
					$('#woo_vou_multiple_voucher_code_submit').show()					
				}
			});
		}
	});

	$( document ).on( 'click', '#woo_vou_multiple_code_select_all', function() {		
		if($(this).prop("checked")) {
			$(".woo_vou_multiple_redeem_code_check_box").prop("checked", true);
			$(".woo_vou_multiple_redeem_code_check_box").addClass("voo_checked");
		} else {
			$(".woo_vou_multiple_redeem_code_check_box").prop("checked", false);
			$(".woo_vou_multiple_redeem_code_check_box").removeClass("voo_checked");
		}                
	});

	$( document ).on( 'click', '.woo_vou_multiple_redeem_code_check_box', function() {
		if($(".woo_vou_multiple_redeem_code_check_box").length == $(".woo_vou_multiple_redeem_code_check_box:checked").length) { 
			 //if the length is same then untick 
			$("#woo_vou_multiple_code_select_all").prop("checked", true);
		}else {
			//vise versa
			$("#woo_vou_multiple_code_select_all").prop("checked", false);            
		}
		$(this).toggleClass("voo_checked");
	});

	$( document ).on( 'click', '#woo_vou_multiple_voucher_code_submit', function( e ) {			
		if( $('.woo_vou_multiple_check_code_table').find('.woo_vou_row_checkbox .woo_vou_multiple_redeem_code_check_box:checked').length != 0 ){
			Swal.fire({
				title: WooVouCheck.check_multi_code_redeem_all_title_error,
				text: WooVouCheck.check_multi_code_redeem_all_text_error,
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Yes",
				allowOutsideClick: false,
			}).then((result) => {
				if (result.isConfirmed) {
					$( '.woo-vou-multiple-voucher-code-submit-wrap' ).hide();
					//show loader
					$( '.woo-vou-check-voucher-code-loader' ).css( 'display', 'inline' );
					
					$( ".woo_vou_multiple_code_row" ).each(function( index, element ) {
						var voucodeid = $(element).find('.woo_vou_row_checkbox .woo_vou_multiple_redeem_code_check_box:checked').val();
						woo_vou_multiple_voucher_code_full_redeem_submit( voucodeid );
					});
					setTimeout(function(){$('#woo_vou_check_multiple_voucher_code').trigger('click');},500);					
				}
			});
		}else{
			Swal.fire({
				icon: "error",
				text: WooVouCheck.check_multi_code_select_atleast_one_error,
				allowOutsideClick: false,
				confirmButtonColor: "#3085d6",
			});
		}
	});

	$( document ).on( 'click', '.column-code_details > .woo-vou-code-redeem, .woo_vou_multiple_check_code_table .woo-vou-code-redeem', function() {		
		$(this).prop('disabled', true);
		$(this).parent('.woo-vou-single-redeem-wrap').find('.woo-vou-loader.woo-vou-check-voucher-code-loader').show();
		var woo_voucher_id = $( this ).data( 'voucherid' );
		var data = {
					action		: 'woo_vou_voucher_redeem_popup',
					voucher_id	: woo_voucher_id,
					ajax		: true,
				};

		//call ajax to chcek voucher code
		jQuery.post( WooVouCheck.ajaxurl, data, function( response ) {
			
			var response_data = jQuery.parseJSON(response);
				
			// Check if response will be success
			if( response_data['success_status'] ){
					
				// Declare variables
				var woo_voucher_id 			= response_data['voucher_id'];
				var woo_voucher_code 		= response_data['voucher_code'];
				var woo_voucher_price       = response_data['price'];
				var woo_vou_redeem_method   = response_data['redeem_method'];
				var woo_vou_redeem_amount   = response_data['redeem_amount'];
				var woo_voucher_redeem_msg  = response_data['success'];
				var woo_voucher_error  		= response_data['error'];				
				
				if( woo_voucher_error ) {
					$('.woo-vou-code-redeem-submit-wrap').hide();
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' );
				} else  {
					$('.woo-vou-code-redeem-submit-wrap').show();
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' );
				}

				$('.woo-vou-partial-redeem-amount').hide();
				$('#woo_vou_voucher_redeem .woo-vou-voucher-code').html( woo_voucher_code );
				$('#woo_vou_voucher_redeem #woo_voucher_code').val( woo_voucher_code );
				$('#woo_vou_voucher_redeem .woo-vou-voucher-code-msg').html( woo_voucher_redeem_msg );
				$('#woo_vou_voucher_redeem #woo_voucher_id').val( woo_voucher_id );
				$('#woo_vou_voucher_redeem .woo-vou-voucher-price').html( woo_voucher_price );
				$('#woo_vou_voucher_redeem .woo-vou-voucher-redeem-method').html( woo_vou_redeem_method );
				$('#woo_vou_voucher_redeem .woo-vou-voucher-redeem-amount').html( woo_vou_redeem_amount );
				if( $('.form-table.woo-vou-check-code').hasClass('woo_vou_is_shortcode') || adminpage == 'woocommerce_page_woo-vou-check-voucher-code' ){
					var $woo_vou_check_voucher_code_radio_btn_vaule = $('.woo_vou_check_voucher_code_radio_btn:checked').val(); 
					if( $woo_vou_check_voucher_code_radio_btn_vaule == 'woo_vou_check_voucher_code_multiple' ){							
						$('.woo-vou-popup-content.woo-vou-voucher-redeem-content').fadeIn();
						$('.woo-vou-popup-overlay.woo-vou-voucher-redeem-overlay').show(); 
						$('#woo_vou_voucher_redeem .woo-vou-voucher-code-msg').show();
						$(this).prop('disabled', false);
					}
				}else{
					$('.woo-vou-popup-content.woo-vou-voucher-redeem-content').fadeIn();
					$('.woo-vou-popup-overlay.woo-vou-voucher-redeem-overlay').show();
				}
				$('.woo-vou-loader.woo-vou-check-voucher-code-loader').hide();
			}
		});
		return false;
	});

	$(document).on('click', '.woo-vou-popup-content.woo-vou-voucher-redeem-content .woo-vou-close-button', function () {
        $('.woo-vou-code-redeem.woo-vou-action-button').prop('disabled', false);
    });

	// Submit Voucher code ( Redeem vocher code )
	$( document ).on( 'click', '#woo_vou_voucher_redeem #woo_vou_voucher_code_redeem', function() {

		//Voucher Code
		var voucode = $( '#woo_voucher_code' ).val();

		var current_button = $( this );
		var vou_enable_partial_redeem = $('#vou_enable_partial_redeem').val();

		if( ( voucode != '' && voucode != 'undefine' ) ) {



			var redeem_amount = '';
			var redeem_method = '';
			var total_price = '';
			var redeemed_price = '';
			var remaining_redeem_price = '';

			total_price				= woo_vou_number_check($('#vou_code_total_price').val());
			redeemed_price			= woo_vou_number_check($('#vou_code_total_redeemed_price').val());
			remaining_redeem_price 	= woo_vou_number_check($('#vou_code_remaining_redeem_price').val());

			// check partial redeem is enabled
			if( vou_enable_partial_redeem == "yes" ) {

				// get redeem amount and redeem method
				redeem_amount			= woo_vou_number_check($('#vou_partial_redeem_amount').val());
				redeem_method 			= $('#vou_redeem_method').val();

				// redeem amount validation
				if( redeem_method == 'partial' && ( redeem_amount == '' || isNaN( redeem_amount ) ) ) {
					$('.woo-vou-voucher-code-error').remove();					
					$( "<p class='woo-vou-voucher-code-error'>"+WooVouCheck.redeem_amount_empty_error+"</p>" ).insertAfter(".woo-vou-partial-redeem-amount .description" );
					return false;
				} else if( redeem_method == 'partial' && redeem_amount > remaining_redeem_price ) {
					$('.woo-vou-voucher-code-error').remove();
					$( "<p class='woo-vou-voucher-code-error'>"+WooVouCheck.redeem_amount_greaterthen_redeemable_amount+"</p>" ).insertAfter(".woo-vou-partial-redeem-amount .description" );
					return false;
				}
			}

			current_button.prop("disabled", true);

			//hide error message
			$( '#woo_vou_voucher_redeem .woo-vou-loader' ).hide();

			//show loader
			$( '#woo_vou_voucher_redeem .woo-vou-loader' ).css( 'display', 'inline' );

			var data = {
							action							: 'woo_vou_save_voucher_code',
							voucode							: voucode,
							vou_code_total_price			: total_price,
							vou_code_total_redeemed_price	: redeemed_price,
							vou_code_remaining_redeem_price	: remaining_redeem_price,
							ajax							: true
						};


			// check partial redeem is enabled
			if( vou_enable_partial_redeem == "yes" ) {

				data['vou_partial_redeem_amount'] 		= redeem_amount;
				data['vou_redeem_method'] 				= redeem_method;
			}

			//Add trigger for redeem data
			$( document ).trigger( "vou_redeem_data", data );

			//call ajax to save voucher code
			jQuery.post( WooVouCheck.ajaxurl, data, function( response ) {

				var response_data = jQuery.parseJSON(response);

				if( response_data.success ) {

					//hide submit row
					$( '.woo-vou-code-redeem-submit-wrap' ).fadeOut();
					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-error' ).addClass( 'woo-vou-voucher-code-success' ).html( WooVouCheck.code_used_success ).show();
				} else {

					$( '.woo-vou-voucher-code-msg' ).removeClass( 'woo-vou-voucher-code-success' ).addClass( 'woo-vou-voucher-code-error' ).html( response_data.error_message ).show();
				}
				//hide loader
				$( '#woo_vou_voucher_redeem .woo-vou-loader' ).hide();
				current_button.prop("disabled", false);
				setTimeout(function() { window.location.reload(); }, 1000);
			});
		}
	});

	function woo_vou_multiple_voucher_code_full_redeem_submit( voucodeid ){
		var $woo_vou_code_row = $( '.woo_vou_multiple_code_id_'+voucodeid );
		var voucode = $woo_vou_code_row.data('vou_code');

		if( ( voucode != '' || voucode != 'undefine' ) ) {			

			var redeem_amount = '';
			var redeem_method = '';
			var total_price = '';
			var redeemed_price = '';
			var remaining_redeem_price = '';

			total_price				= woo_vou_check_number($woo_vou_code_row.data('vou_code_total_price'));
			redeemed_price			= woo_vou_check_number($woo_vou_code_row.data('vou_code_total_redeemed_price'));
			remaining_redeem_price 	= woo_vou_check_number($woo_vou_code_row.data('vou_code_remaining_redeem_price'));

			//hide error message
			$( '.woo-vou-voucher-code-msg' ).hide();
			$( '.woo-vou-voucher-reverse-redeem' ).html( "" ).hide();

			//show loader
			$( '.woo-vou-voucher-code-submit-loader' ).css( 'display', 'inline' );

			var data = {
				action							: 'woo_vou_save_voucher_code',
				voucode							: voucode,
				vou_code_total_price			: total_price,
				vou_code_total_redeemed_price	: redeemed_price,
				vou_code_remaining_redeem_price	: remaining_redeem_price,
				ajax							: true
			};

			//Add trigger for redeem data
			$( document ).trigger( "vou_redeem_data", data );

			//call ajax to save voucher code
			jQuery.post( WooVouCheck.ajaxurl, data, function( response ) {
				var response_data = jQuery.parseJSON(response);
				if( response_data.success ) {} else {}
				//hide loader
				$( '.woo-vou-voucher-code-submit-loader' ).hide();
			});
		}
	}

	function woo_vou_number_check(number){
    	number = Math.round(number * 100) / 100;
    	var pattern = /^\d+(.\d{1,2})?$/;
    	return pattern.test(number)?number:'';
    }
});