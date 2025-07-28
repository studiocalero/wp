"use strict";

jQuery( document ).ready( function() {

	function woo_vou_paerial_redeem_date_meta_process_start( result ) {
		if( result != 'completed' ) {
			// Trigger upgrades on page load
			var data = { 
				action: 'woo_vou_migrate_voucher_paerial_redeem_date_meta',
				security: WooVouUpgrd.security,
			};
			var response_status = 'process';

			jQuery.post( WooVouUpgrd.ajaxurl, data, function (response) {

			}).done(function( response ) {
				if( response == 'completed' ) {
					jQuery('#vou-upgrade-loader').hide();
					
					if ( typeof $return != 'undefined' && $return != '' ) {
						document.location.href = $return;
					} else {
						document.location.href = 'index.php?woo-vou-migrate-voucher-partial-redeem-date=success'; // Redirect to the welcome page
					}
				} else{
					woo_vou_paerial_redeem_date_meta_process_start( response );
				}
			});
		}
	}
	woo_vou_paerial_redeem_date_meta_process_start('');
});
