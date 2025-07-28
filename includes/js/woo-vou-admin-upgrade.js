"use strict";

jQuery( document ).ready( function() {
	// Trigger upgrades on page load
	var data = { 
		action: 'woo_vou_trigger_upgrades',
		security : WooVouUpgrd.security, 
	};
	jQuery.post( WooVouUpgrd.ajaxurl, data, function (response) {
		if( response == 'complete' ) {
			jQuery('#vou-upgrade-loader').hide();
			document.location.href = 'index.php'; // Redirect to the welcome page
		}
	});
});