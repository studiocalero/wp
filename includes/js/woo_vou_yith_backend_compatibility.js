jQuery(document).ready( function($){
	// Check if the checkbox is not already checked
    if (!$('#_downloadable').is(':checked')) {
        // Trigger the click event on the checkbox
        $('#_downloadable').click();
		$('.bk_is_booking').removeClass('closed');
		$('.bk_is_booking').addClass('open');
    }else{
		$('.yith_booking_options_options.yith_booking_options_tab ').addClass('active');
		$('#yith_booking_settings_tab').show();
	}

	// Add change event handler to the checkbox
    $('#_downloadable').change(function() {
        // Check if the checkbox is unchecked
        if (!$(this).is(':checked')) {
            // Hide the section
            $('.yith_booking_options_options.yith_booking_options_tab ').addClass('active');
			$('#yith_booking_settings_tab').show();
			$('#general_product_data').hide();
        }
    });
});