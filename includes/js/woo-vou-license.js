"use strict";

jQuery( document ).ready( function( $ ) {
    if(WooVouLicense.is_vou_license == "vou_license") {
		$('p.submit button.woocommerce-save-button').hide();
	}

	if(WooVouLicense.is_vou_activated == true ) {
		$('#vou_licence_key').attr('readonly', true);
		$('#vou_email_address').attr('readonly', true);
	}

	$(document).on('click', '.check-license', function(e) {
		e.preventDefault();
		jQuery('.error').remove();
		var license_key = jQuery('#vou_licence_key').val();
		var email = jQuery('#vou_email_address').val();
		var valid = 0;

		if(license_key.length > 1 && email != '') {
			valid = 1;
		} else {
			jQuery('#mainform h2').after('<div class="error notice"><p>All fields are required</p></div>');
			return false;
		}

		if(isEmail(email)) {
			valid = 1;
		} else {
			jQuery('#mainform h2').after('<div class="error notice"><p>Please Enter valid email address</p></div>');
			return false;
		}

		if(valid == 1) {
			jQuery('#check-license').attr('disabled', true);
			$('.woo-vou-license-button #loader').show();
			$.ajax({
				url : WooVouLicense.ajaxurl,
				data: {
					action: 'woo_vou_activate_license',
					license_action: WooVouLicense.woo_vou_activated,
					license_key: license_key,
					email: email,
					security : WooVouLicense.security,
				},
				type : 'POST',
				success : function(response) {	
					$('.woo-vou-license-button #loader').hide();						
					if( response.status==true ) {
						window.onbeforeunload = null;
						Swal.fire(
							'Success',
							response.msg,
							'success',                       
						  ).then(() => {
							  window.location.href = 'admin.php?page=wc-settings&tab=woo-vou-settings';                        
						  });
											
					} else {
						jQuery('#check-license').attr('disabled', false);
						Swal.fire(
							'Error',
							response.msg,
							'error',
						);
						
					}
				}
			});
		} 
	})
});


// function to validate email address
function isEmail(email) {
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
}