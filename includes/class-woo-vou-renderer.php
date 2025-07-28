<?php 

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Renderer Class
 *
 * To handles some small HTML content for front end and backend
 * 
 * @package WooCommerce - PDF Vouchers
 * @since 1.0.0
 */
class WOO_Vou_Renderer {
	
	public $mainmodel, $model;
	
	public function __construct() {
		
		global $woo_vou_model;
		$this->model = $woo_vou_model;
	}

	/**
	 * Add Popup For Purchased Codes 
	 * 
	 * Handels to show purchased voucher codes popup
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 1.0.0
	 */
	public function woo_vou_purchased_codes_popup( $postid ) {
		
		ob_start();
		include_once( WOO_VOU_ADMIN . '/forms/metabox-popups/woo-vou-purchased-codes-popup.php' ); // Including purchased voucher code file
		$html = ob_get_clean();
		
		return $html;
	}

	/**
	 * Add Popup For Used Codes
	 * 
	 * Handels to show used voucher codes popup
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 1.1.0
	 */
	public function woo_vou_used_codes_popup( $postid ) {
		
		ob_start();
		include_once( WOO_VOU_ADMIN . '/forms/metabox-popups/woo-vou-used-codes-popup.php' ); // Including used voucher code file
		$html = ob_get_clean();
		
		return $html;
	}

	/**
	 * Add Popup For UnUsed Codes
	 * 
	 * Handels to show used voucher codes popup
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 1.1.0
	 */
	public function woo_vou_unused_codes_popup( $postid ) {
		
		ob_start();
		include_once( WOO_VOU_ADMIN . '/forms/metabox-popups/woo-vou-unused-codes-popup.php' ); // Including used voucher code file
		$html = ob_get_clean();
		
		return $html;
	}

	/**
	 * Function For ajax edit of all controls
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 1.0.0
	 */
	public function woo_vou_page_builder() {
		
		global $wp_version;

		$controltype		= $_POST['type'];
		$bgcolor			= isset( $_POST['bgcolor'] ) ? $_POST['bgcolor'] : '';
		$fontcolor			= isset( $_POST['fontcolor'] ) ? $_POST['fontcolor'] : '';
		$fontsize			= isset( $_POST['fontsize'] ) ? $_POST['fontsize'] : '';
		$textalign			= isset( $_POST['textalign'] ) ? $_POST['textalign'] : '';
		$codetextalign		= isset( $_POST['codetextalign'] ) ? $_POST['codetextalign'] : '';
		$codeborder			= isset( $_POST['codeborder'] ) ? $_POST['codeborder'] : '';
		$codecolumn			= isset( $_POST['codecolumn'] ) ? $_POST['codecolumn'] : '';
		$vouchercodes		= isset( $_POST['vouchercodes'] ) ? $_POST['vouchercodes'] : '';
		
		$qrcodewidth		= isset( $_POST['qrcodewidth'] ) ? $_POST['qrcodewidth'] : '';
		$qrcodeheight		= isset( $_POST['qrcodeheight'] ) ? $_POST['qrcodeheight'] : '';
		$qrcodecolor		= isset( $_POST['qrcodecolor'] ) ? $_POST['qrcodecolor'] : '';
		$qrcodesymboltype	= isset( $_POST['qrcodesymboltype'] ) ? $_POST['qrcodesymboltype'] : '';
		$qrcodetype			= isset( $_POST['qrcodetype'] ) ? $_POST['qrcodetype'] : '';
		$qrcodeborder		= isset( $_POST['qrcodeborder'] ) ? $_POST['qrcodeborder'] : '';
		$qrcoderesponse		= isset( $_POST['qrcoderesponse'] ) ? $_POST['qrcoderesponse'] : '';
		
		$barcodewidth		= isset( $_POST['barcodewidth'] ) ? $_POST['barcodewidth'] : '';
		$barcodeheight		= isset( $_POST['barcodeheight'] ) ? $_POST['barcodeheight'] : '';
		$barcodecolor		= isset( $_POST['barcodecolor'] ) ? $_POST['barcodecolor'] : '';
		$barcodetype		= isset( $_POST['barcodetype'] ) ? $_POST['barcodetype'] : '';
		$barcodedisptype	= isset( $_POST['barcodedisptype'] ) ? $_POST['barcodedisptype'] : '';
		$barcodeborder		= isset( $_POST['barcodeborder'] ) ? $_POST['barcodeborder'] : '';
		
		$productimagewidth 	= isset( $_POST['productimagewidth'] ) ? $_POST['productimagewidth'] : '';
		$productimageheight	= isset( $_POST['productimageheight'] ) ? $_POST['productimageheight'] : '';
		
		if( empty($qrcodecolor) ){
			$qrcodecolor = '#000000';
		}
		
		if( empty($barcodecolor) ){
			$barcodecolor = '#000000';
		}

		$align_data = array(
								'left' 		=> esc_html__( 'Left', 'woovoucher' ),
								'center'	=> esc_html__( 'Center', 'woovoucher' ),
								'right' 	=> esc_html__( 'Right', 'woovoucher' ),
							);
							
		$qrcodes_type_data = array(
								'vertical'	 => esc_html__( 'Vertical', 'woovoucher' ),
								'horizontal' => esc_html__( 'Horizontal', 'woovoucher' ),
							);
						
		$qrcode_scan_response_data = array(
										'url'  => esc_html__( 'Redeem URL', 'woovoucher' ),
										'code' => esc_html__( 'Voucher Code', 'woovoucher' ),
										'direct' => esc_html__( 'QR Redeem URL', 'woovoucher' ),
									);

		$barcode_type = apply_filters( 'woo_vou_barcode_type', array(
								'C128' => esc_html__('Code 128', 'woovoucher'),
								'C128B' => esc_html__('Code 128B', 'woovoucher'),
								'C39E' => esc_html__('Code 39 Extended', 'woovoucher'),
								'C39E+' => esc_html__('Code 39 Extended + CheckSum', 'woovoucher'),
							) );
							
		$qrcode_type = apply_filters( 'woo_vou_qrcode_type', array(
								'QRCODE,H' => esc_html__('QR Code', 'woovoucher'),
								'DATAMATRIX' => esc_html__('DATA MATRIX', 'woovoucher'),
								'PDF417' => esc_html__('PDF-417', 'woovoucher'),
							) );

		$barcodes_type_data = array(
								'vertical'	 => esc_html__( 'Vertical', 'woovoucher' ),
								'horizontal' => esc_html__( 'Horizontal', 'woovoucher' ),
							);

		$border_data = array( '1', '2', '3' );

		$column_data = array(
								'1' 	=> esc_html__( '1 Column', 'woovoucher' ),
								'2'		=> esc_html__( '2 Column', 'woovoucher' ),
								'3' 	=> esc_html__( '3 Column', 'woovoucher' ),
							);

		if( $controltype == 'textblock' ) {

			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
			echo '<tr>
								<th scope="row">
									' . esc_html__( 'Title', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';			
									$settings = array( 
															'textarea_name' => $editorid,
															'media_buttons'=> false,
															'quicktags'=> true,
															'teeny' => false,
															'editor_class' => 'content pbrtextareahtml'
														);
									wp_editor( '', $editorid, $settings );	
			echo '					<span class="description">' . sprintf( esc_html__( 'Enter a voucher code title.', 'woovoucher' ), '<code>{codes}</code>' ) . '</span>
								</td>
							</tr>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Title Background Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Title Font Size', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $fontsize . '" id="woo_vou_edit_font_size" name="woo_vou_edit_font_size" class="woo_vou_font_size_box small-text" maxlength="2" />
									' . 'pt' . '<br /><span class="description">' . esc_html__( 'Enter a font size for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Title Alignment', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_text_align" name="woo_vou_edit_text_align" class="woo_vou_text_align_box">';
									foreach ( $align_data as $align_key => $align_value ) {
										echo '<option value="' . $align_key . '" ' . selected( $textalign, $align_key, false ) . '>' . $align_value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . esc_html__( 'Select text-align for the voucher code title.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '<tr>
								<th scope="row">
									' . esc_html__( 'Voucher Code', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';			
									$settings = array( 
															'textarea_name' => $editorid . 'codes',
															'media_buttons'=> false,
															'quicktags'=> true,
															'teeny' => false,
															'editor_class' => 'content pbrtextareahtml'
														);
									wp_editor( '', $editorid . 'codes', $settings );	
			echo '					<span class="description">' . esc_html__( 'Enter your voucher codes content. The available tags are:' , 'woovoucher').' <br /> <code>{codes}</code> - '.esc_html__( 'displays the voucher code(s).', 'woovoucher' ) . '</span>
								</td>
							</tr>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Voucher Code Border', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_code_border" name="woo_vou_edit_code_border" class="woo_vou_code_border_box">
										<option value="">' . esc_html__( 'Select', 'woovoucher' ) . '</option>';
									foreach ( $border_data as $border ) {
										echo '<option value="' . $border . '" ' . selected( $codeborder, $border, false ) . '>' . $border . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . esc_html__( 'Select border for the voucher code.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
								
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Voucher Code Alignment', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_code_text_align" name="woo_vou_edit_code_text_align" class="woo_vou_code_text_align_box">';
									foreach ( $align_data as $align_key => $align_value ) {
										echo '<option value="' . $align_key . '" ' . selected( $codetextalign, $align_key, false ) . '>' . $align_value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . esc_html__( 'Select text-align for the voucher code.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if($controltype == 'message') {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Background Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';			
									$settings = array( 
															'textarea_name' => $editorid,
															'media_buttons'=> false,
															'quicktags'=> true,
															'teeny' => false,
															'editor_class' => 'content pbrtextareahtml'
														);
									wp_editor( '', $editorid, $settings );	
			echo '					<span class="description">' . esc_html__( 'Enter your content. The available tags are:' , 'woovoucher' ). ' <br /><code>{redeem}</code> - '. esc_html__( 'displays the voucher redeem instruction.', 'woovoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
				
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'expireblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Background Color', 'woovoucher' ) . '
								</th>
								<td>';
									
									if( $wp_version >= 3.5 ) {
										
										echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
										
									} else {
										echo '<div class="woo-vou-color-picker-wrapper">
													<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
													<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
													<div class="colorpicker"></div>
												</div>';
									}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your content. The available tags are:' , 'woovoucher').' <br /><code>{expiredate}</code> - '.esc_html__( 'displays the voucher expire date.', 'woovoucher' ) . ' <br /><code>{expiredatetime}</code> - '.esc_html__( 'displays the voucher expire date & time.', 'woovoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'venaddrblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Background Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your content. The available tags are:' , 'woovoucher').' <br /> <code>{vendoraddress}</code> - '. esc_html__( 'displays the vendor\'s address.', 'woovoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'siteurlblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Background Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your content. The available tags are:', 'woovoucher').' <br /><code>{siteurl}</code> - '.esc_html__( 'displays the website URL.', 'woovoucher' ). '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'locblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Background Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> false,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your content. The available tags are:' , 'woovoucher').' <br /><code>{location}</code> - '.esc_html__( 'displays the voucher location.', 'woovoucher' ) . '</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'customblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
							
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Background Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_color_box" data-default-color="" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $bgcolor . '" id="woo_vou_edit_bg_color" name="woo_vou_edit_bg_color" class="woo_vou_edit_bg_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Select a background color for the textbox.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
										
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> true,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . sprintf( esc_html__( 'Enter your custom content. You can find %ssupported shortcodes%s list' , 'woovoucher'), '<strong>', '</strong>' );
			echo '					<a href="javascript:void(0);" data-swal-toast-template="#my-template" target="_blank" class="woovoucher-shortcode-popup">' . esc_html__( 'here', 'woovoucher' ) . '</a>.';
			echo ' 					</span>
								</td>
							</tr>
						</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'qrcodeblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> true,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your custom content. The available tags are:' , 'woovoucher')
										.'<br /><code>{qrcode}</code> - '.esc_html__( 'displays single QR Code for multiple voucher code(s).', 'woovoucher' )
									. '</span>
								</td>
							</tr>';
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'QR Code Width', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $qrcodewidth . '" id="woo_vou_edit_qrcode_width" name="woo_vou_edit_qrcode_width" class="woo_vou_edit_qrcode_width small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter QR Code width. Leave it blank to auto set width as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'QR Code Height', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $qrcodeheight . '" id="woo_vou_edit_qrcode_height" name="woo_vou_edit_qrcode_height" class="woo_vou_edit_qrcode_height small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter QR Code height. Leave it blank to auto set height as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'QR Code Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $qrcodecolor . '" id="woo_vou_edit_qrcode_color" name="woo_vou_edit_qrcode_color" class="woo_vou_color_box" data-default-color="#000000" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $qrcodecolor . '" id="woo_vou_edit_qrcode_color" name="woo_vou_edit_qrcode_color" class="woo_vou_edit_qrcode_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Please select QR Code color.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									'.esc_html__('QR Code Type', 'woovoucher').'
								</th>
								<td>
								<select name="woo_vou_edit_qrcode_symbol_type" id="woo_vou_edit_qrcode_symbol_type">';

								foreach($qrcode_type as $type_key => $type_val){
									echo '<option value="' . $type_key . '" ' . selected( $qrcodesymboltype, $type_key, false ) . '>' . $type_val . '</option>';
								}
			echo '				</select>
								<br /><span class="description">' . esc_html__( 'Please select QRCode type.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Enable Border', 'woovoucher' ) . '
								</th>
								<td>
									<input type="checkbox" value="1" id="woo_vou_edit_qrcode_border" name="woo_vou_edit_qrcode_border" class="woo_vou_edit_qrcode_border" '.checked(!empty($qrcodeborder), true, false).' />
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Return Value', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_qrcode_response" name="woo_vou_edit_qrcode_response" class="woo_vou_edit_qrcode_response">';
									foreach ( $qrcode_scan_response_data as $key => $value ) {
										echo '<option value="' . $key . '" ' . selected( $qrcoderesponse, $key, false ) . '>' . $value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . sprintf( esc_html__( 'Please select return value.%1$s%2$sRedeem URL:%3$s When you scan the QR Code, it will return mobile friendly page URL where you get an option to redeem the voucher code.%1$s%2$sVoucher Code:%3$s When you scan the QR Code, it will return actual voucher code.%1$s%2$sQR Redeem URL:%3$s When you scan the QR Code, it will direct redeem voucher code fully. Please note this setting won\'t work for the partial redemption.', 'woovoucher' ), '<br>','<b>','</b>' ) . '</span>
								</td>
							</tr>';
			
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'qrcodesblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> true,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your custom content. The available tags are:' , 'woovoucher')
										.'<br /><code>{qrcodes}</code> - '.esc_html__( 'displays separate QR Codes for multiple voucher code(s).', 'woovoucher' )
									. '</span>
								</td>
							</tr>';
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'QR Code Width', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $qrcodewidth . '" id="woo_vou_edit_qrcode_width" name="woo_vou_edit_qrcode_width" class="woo_vou_edit_qrcode_width small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter QR Codes width. Leave it blank to auto set width as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'QR Code Height', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $qrcodeheight . '" id="woo_vou_edit_qrcode_height" name="woo_vou_edit_qrcode_height" class="woo_vou_edit_qrcode_height small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter QR Codes height. Leave it blank to auto set height as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'QR Code Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $qrcodecolor . '" id="woo_vou_edit_qrcode_color" name="woo_vou_edit_qrcode_color" class="woo_vou_color_box" data-default-color="#000000" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $qrcodecolor . '" id="woo_vou_edit_qrcode_color" name="woo_vou_edit_qrcode_color" class="woo_vou_edit_qrcode_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Please select QR Codes color.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									'.esc_html__('QR Code Type', 'woovoucher').'
								</th>
								<td>
								<select name="woo_vou_edit_qrcode_symbol_type" id="woo_vou_edit_qrcode_symbol_type">';

								foreach($qrcode_type as $type_key => $type_val){
									echo '<option value="' . $type_key . '" ' . selected( $qrcodesymboltype, $type_key, false ) . '>' . $type_val . '</option>';
								}
			echo '				</select>
								<br /><span class="description">' . esc_html__( 'Please select QR Code type.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Display Type', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_qrcode_type" name="woo_vou_edit_qrcode_type" class="woo_vou_edit_qrcode_type">';
									foreach ( $qrcodes_type_data as $key => $value ) {
										echo '<option value="' . $key . '" ' . selected( $qrcodetype, $key, false ) . '>' . $value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . esc_html__( 'Please select QR Codes display type.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Enable Border', 'woovoucher' ) . '
								</th>
								<td>
									<input type="checkbox" value="1" id="woo_vou_edit_qrcode_border" name="woo_vou_edit_qrcode_border" class="woo_vou_edit_qrcode_border" '.checked(!empty($qrcodeborder), true, false).' />
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Return Value', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_qrcode_response" name="woo_vou_edit_qrcode_response" class="woo_vou_edit_qrcode_response">';
									foreach ( $qrcode_scan_response_data as $key => $value ) {
										echo '<option value="' . $key . '" ' . selected( $qrcoderesponse, $key, false ) . '>' . $value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . sprintf( esc_html__( 'Please select return value.%1$s%2$sRedeem URL:%3$s When you scan the QR Code, it will return mobile friendly page URL where you get an option to redeem the voucher code.%1$s%2$sVoucher Code:%3$s When you scan the QR Code, it will return actual voucher code.', 'woovoucher' ), '<br>','<b>','</b>') . '</span>
								</td>
							</tr>';
			
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
			
		} else if( $controltype == 'barcodeblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> true,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your custom content. The available tags are:' , 'woovoucher')
										.'<br /><code>{barcode}</code> - '.esc_html__( 'displays single Barcode for multiple voucher code(s).', 'woovoucher' )
									. '</span>
								</td>
							</tr>';
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Barcode Width', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $barcodewidth . '" id="woo_vou_edit_barcode_width" name="woo_vou_edit_barcode_width" class="woo_vou_edit_barcode_width small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter Barcode width. Leave it blank to auto set width as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Barcode Height', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $barcodeheight . '" id="woo_vou_edit_barcode_height" name="woo_vou_edit_barcode_height" class="woo_vou_edit_barcode_height small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter Barcode height. Leave it blank to auto set height as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Barcode Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $barcodecolor . '" id="woo_vou_edit_barcode_color" name="woo_vou_edit_barcode_color" class="woo_vou_color_box" data-default-color="#000000" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $barcodecolor . '" id="woo_vou_edit_barcode_color" name="woo_vou_edit_barcode_color" class="woo_vou_edit_barcode_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Please select Barcode color.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									'.esc_html__('Barcode Type', 'woovoucher').'
								</th>
								<td>
								<select name="woo_vou_edit_barcode_type" id="woo_vou_edit_barcode_type">';

								foreach($barcode_type as $type_key => $type_val){
									echo '<option value="' . $type_key . '" ' . selected( $barcodetype, $type_key, false ) . '>' . $type_val . '</option>';
								}
			echo '				</select>
								<br /><span class="description">' . esc_html__( 'Please select Barcode type.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Enable Border', 'woovoucher' ) . '
								</th>
								<td>
									<input type="checkbox" value="1" id="woo_vou_edit_barcode_border" name="woo_vou_edit_barcode_border" class="woo_vou_edit_barcode_border" '.checked(!empty($barcodeborder), true, false).' />
								</td>
							</tr>';
			
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
		} else if( $controltype == 'barcodesblock' ) {
			
			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> true,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your custom content. The available tags are:' , 'woovoucher')
										.'<br /><code>{barcodes}</code> - '.esc_html__( 'displays separate Barcodes for multiple voucher code(s).', 'woovoucher' )
									. '</span>
								</td>
							</tr>';
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Barcode Width', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $barcodewidth . '" id="woo_vou_edit_barcode_width" name="woo_vou_edit_barcode_width" class="woo_vou_edit_barcode_width small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter Barcodes width. Leave it blank to auto set width as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Barcode Height', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $barcodeheight . '" id="woo_vou_edit_barcode_height" name="woo_vou_edit_barcode_height" class="woo_vou_edit_barcode_height small-text" maxlength="3" />&nbsp;<span>mm</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter Barcodes height. Leave it blank to auto set height as per selected PDF size.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Barcode Color', 'woovoucher' ) . '
								</th>
								<td>';
							
								if( $wp_version >= 3.5 ) {
									
									echo '<input type="text" value="' . $barcodecolor . '" id="woo_vou_edit_barcode_color" name="woo_vou_edit_barcode_color" class="woo_vou_color_box" data-default-color="#000000" />';
									
								} else {
									echo '<div class="woo-vou-color-picker-wrapper">
												<input type="text" value="' . $barcodecolor . '" id="woo_vou_edit_barcode_color" name="woo_vou_edit_barcode_color" class="woo_vou_edit_barcode_color" />
												<input type="button" class="woo_vou_color_box button-secondary" value="'.esc_html__('Select Color','woovoucher').'">
												<div class="colorpicker"></div>
											</div>';
								}
			echo '					<br /><span class="description">' . esc_html__( 'Please select Barcodes color.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									'.esc_html__('Barcode Type', 'woovoucher').'
								</th>
								<td>
								<select name="woo_vou_edit_barcode_type" id="woo_vou_edit_barcode_type">';

								foreach($barcode_type as $type_key => $type_val){
									echo '<option value="' . $type_key . '" ' . selected( $barcodetype, $type_key, false ) . '>' . $type_val . '</option>';
								}
			echo '				</select>
								<br /><span class="description">' . esc_html__( 'Please select Barcode type.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Display Type', 'woovoucher' ) . '
								</th>
								<td>
									<select id="woo_vou_edit_barcode_disp_type" name="woo_vou_edit_barcode_disp_type" class="woo_vou_edit_barcode_disp_type">';
									foreach ( $barcodes_type_data as $key => $value ) {
										echo '<option value="' . $key . '" ' . selected( $barcodedisptype, $key, false ) . '>' . $value . '</option>';
									}
			echo '					</select>
									<br /><span class="description">' . esc_html__( 'Please select Barcodes display type.', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Enable Border', 'woovoucher' ) . '
								</th>
								<td>
									<input type="checkbox" value="1" id="woo_vou_edit_barcode_border" name="woo_vou_edit_barcode_border" class="woo_vou_edit_barcode_border" '.checked(!empty($barcodeborder), true, false).' />
								</td>
							</tr>';
			
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
		} else if ( $controltype == 'productimageblock' ) {

			$editorid = $_POST['editorid'];
			ob_start();
			echo '	<table class="form-table">
						<tbody>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Content', 'woovoucher' ) . '
								</th>
								<td class="woo_vou_ajax_editor">';
				
									$settings = array('textarea_name' => $editorid, 'media_buttons'=> true,'quicktags'=> true, 'teeny' => false , 'editor_class' => 'content pbrtextareahtml');
									wp_editor('',$editorid,$settings);
			
			echo '					<span class="description">' . esc_html__( 'Enter your custom content. The available tags are:' , 'woovoucher')
										.'<br /><code>{productimage}</code> - '.esc_html__( 'displays product\'s featured image.', 'woovoucher' )
									. '</span>
								</td>
							</tr>';
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Product Image Width', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $productimagewidth . '" id="woo_vou_edit_product_image_width" name="woo_vou_edit_product_image_width" class="woo_vou_edit_product_image_width small-text" maxlength="3" />&nbsp;<span>px</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter product image width. Leave it blank for default size (100px).', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			
			echo '			<tr>
								<th scope="row">
									' . esc_html__( 'Product Image Height', 'woovoucher' ) . '
								</th>
								<td>
									<input type="text" value="' . $productimageheight . '" id="woo_vou_edit_product_image_height" name="woo_vou_edit_product_image_height" class="woo_vou_edit_product_image_height small-text" maxlength="3" />&nbsp;<span>px</span>';
			echo '					<br /><span class="description">' . esc_html__( 'Please enter product image height. Leave it blank for default size (100px).', 'woovoucher' ) . '</span>
								</td>
							</tr>';
			echo '		</tbody>
					</table>';
			
			$html = ob_get_contents();
			ob_end_clean();
		}
		
		echo $html;
		exit;
	}

	/**
	 * Function is designed to display shortcodes within a template popup.
	 * 
	 * @package WooCommerce - PDF Vouchers
	 * @since 7.4.4
	 */
	public function woo_vou_shortcode_detail() {

		ob_start(); ?>

			<div class="shortcode-wrap">

				<!-- Add VOUCHER SHORTCHODES category shortcode -->
			    <div class="shortcode-title"> <?php esc_html_e( 'VOUCHER SHORTCHODES', 'woovoucher' ); ?></div>
			        <ul>
			            <li><code><span class="woo-vou-copyable-text">{redeem}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher redeem instruction.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{sitelogo}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher site logo.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{vendorlogo}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the vendor logo.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{startdate}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher start date.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{startdatetime}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher start date & time.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{expiredate}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher expire date.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{expiredatetime}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher expire date & time.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{vendoraddress}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the vendor address.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{vendoremail}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the vendor email.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{siteurl}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the site url.', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{location}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the location(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{codes}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{qrcode}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays single QR Code for multiple voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{qrcodes}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays separate QR Codes for multiple voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{barcode}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays single Barcode for multiple voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{barcodes}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays separate Barcodes for multiple voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{recipientname}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the recipient name for voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{recipientemail}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the recipient email for voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{recipientmessage}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the recipient message for voucher code(s).', 'woovoucher' ); ?></span></li>
			            <li><code><span class="woo-vou-copyable-text">{recipientgiftdate}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the recipient gift date for voucher code(s).', 'woovoucher' ); ?></span></li>
			        </ul>

			        <!-- Add PRODUCT RELATED SHORTCODES category shortcode -->
			    <div class="shortcode-title"> <?php esc_html_e( 'PRODUCT RELATED SHORTCODES', 'woovoucher' ); ?></div>
				    <ul>
				    	<li><code><span class="woo-vou-copyable-text">{buyername}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the buyer name.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{buyeremail}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the buyer email.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{billing_city}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the buyer’s billing city.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{billing_address}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the buyer’s billing address.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{orderid}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the order id.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{orderdate}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the order date.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{productname}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the product name.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{producttitle}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the product title.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{productprice}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the product price.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{regularprice}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the regular price.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{payment_method}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the payment method of the order.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{quantity}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the quantity of the product item.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{sku}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the SKU of the product item.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{productshortdesc}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays product short description.', 'woovoucher' ); ?></span></li>

						<li><code><span class="woo-vou-copyable-text">{productshortdesc-digits}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays product short description with define number of digits in shortcode. E.g. If you add a shortcode like {productshortdesc-100} then it will display the first 100 characters from product short description.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{variationname}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the variation name.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{variationdesc}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays variation description.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{variationdesc-digits}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays variation description with define number of digits in shortcode.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{discounted_amount}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the discount amount offered( regularprice – saleprice )', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{productfulldesc}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays product full description.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{productfulldesc-digits}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays product full description with define number of digits in shortcode.', 'woovoucher' ); ?></span></li>
						<li><code><span class="woo-vou-copyable-text">{productimage}</span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'displays the product featured image. If product is variable then it will display variation image. If the variation image is not set then plugin will take product featured image.', 'woovoucher' ); ?></span></li>
				    </ul>

				<?php // If custom fields available then display shortcodes
				$vou_gift_voucher_custom_fields = get_option( 'vou_gift_voucher_custom_fields' );
				if ( ! empty( $vou_gift_voucher_custom_fields ) ) { ?>
					<div class="shortcode-title"> <?php esc_html_e( 'CUSTOM FIELD SHORTCODES', 'woovoucher' ); ?></div>
						<ul>
						<?php
						foreach ( $vou_gift_voucher_custom_fields as $key => $value ) { ?>
							<li><code><span class="woo-vou-copyable-text"><?php echo '{' . $value['id'] . '}'; ?></span></code><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'Copy to use ' ); ?><?php echo $value['name']; ?><?php esc_html_e( ' Short-code in Voucher', 'woovoucher' ); ?></span></li>
						<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>

		<?php $html = ob_get_contents();
		ob_end_clean();
		echo $html;
		exit;
	}
	
	/**
	 * Add Custom File Name settings
	 * 
	 * Handle to add custom file name settings
	 *
	 * @package WooCommerce - PDF Vouchers
	 * @since 1.0.0
	 */
	public function woo_vou_render_filename_callback( $field ) {
		
		global $woocommerce;
		
		if ( isset( $field['title'] ) && isset( $field['id'] ) ) :

			$filetype	= isset( $field['options'] ) ? $field['options'] : '';
			$file_val	= get_option( $field['id']);
			$file_val	= !empty($file_val) ? $file_val : '';
			?>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<fieldset>
							<input class="woo-vou-filename-input" name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" type="text" value="<?php echo esc_attr( $file_val ); ?>" />
							<?php if ( !empty( $filetype ) ) { ?>
								<span class="woo-vou-file-type"><?php echo $filetype;?></span>
							<?php } ?>
						</fieldset>
						<span class="description"><?php echo $field['desc'];?></span>
					</td>
				</tr>
			<?php

		endif;
	}
	
	/**
	 * Display Textarea/Editor HTML
	 * 
	 * Handle to add custom file name settings
	 *
	 * @package WooCommerce - PDF Vouchers
	 * @since 1.0.0
	 */
	public function woo_vou_woocommerce_admin_field_vou_textarea( $field ) {
		
		global $woocommerce;

		if ( isset( $field['title'] ) && isset( $field['id'] ) ) :

			$file_val	= get_option( $field['id']);
			$file_val	= !empty($file_val) ? $file_val : '';
			$editor		= ( isset( $field['editor'] ) && $field['editor'] == true ) ? true : false;
			
			$editor_cofig = array(
									'media_buttons'	=> true,
									'textarea_rows'	=> 5,
									'editor_class'	=> 'woo-vou-wpeditor'
								);
				
			?>
			
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<fieldset><?php 
						if( $editor ) {
							
							wp_editor( $file_val, esc_attr( $field['id'] ), $editor_cofig );
							
						} else { ?>
							
							<textarea class="woo-vou-field-textarea" name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" /><?php echo esc_attr( $file_val ); ?></textarea>
						<?php } ?>
					</fieldset>
					<span class="description"><?php echo $field['desc'];?></span>
				</td>
			</tr><?php
		
		endif;
	}
	
	/**
	 * Upload Callback
	 *
	 * Renders upload fields.
	 *
	 * @since 1.0.0
	 * @package WooCommerce - PDF Vouchers
	 */
	function woo_vou_render_upload_callback( $field ) {
		global $woocommerce;

		if ( isset( $field['title'] ) && isset( $field['id'] ) ) {

			$filetype	= isset( $field['options'] ) ? $field['options'] : '';
			$file_val	= get_option( $field['id'] );
			$file_val	= !empty($file_val) ? $file_val : '';
			
			?>
			<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<fieldset>
							<input class="woo-vou-field-uploader" name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" type="text" value="<?php echo esc_attr( $file_val ); ?>" /><?php echo $filetype;?>
							<input type="button" class="woo-vou-upload-button button-secondary" value="<?php esc_html_e( 'Upload File', 'woovoucher' );?>"/>
						</fieldset>
						<span class="description"><?php echo $field['desc'];?></span>
					</td>
				</tr>
			<?php
		}
	}

	/**
	 * Preview Upload Callback
	 *
	 * Renders upload fields.
	 *
	 * @since 1.0.0
	 * @package WooCommerce - PDF Vouchers
	 */
	function woo_vou_render_preview_upload_callback( $field ) {

		global $woocommerce;

		if ( isset( $field['title'] ) && isset( $field['id'] ) ) {

			$upload_dir = wp_upload_dir();
			$base_url 	= $upload_dir['baseurl'];

			$filetype	= isset( $field['options'] ) ? $field['options'] : '';
			$file_val	= get_option( $field['id'] );
			$file_val	= !empty($file_val) ? $file_val : '';

			?>
			<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<fieldset>
							<input class="woo-vou-field-uploader-preview" name="<?php echo esc_attr( $field['id']  ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" type="text" value="<?php echo esc_attr( $file_val ); ?>" /><?php echo $filetype;?>
							<input type="button" class="woo-vou-upload-preview-button button-secondary" value="<?php esc_html_e( 'Upload File', 'woovoucher' );?>"/>
						</fieldset>
						<span class="description"><?php echo $field['desc'];?></span><br />
						<div class="woo-vou-preview-img-view"><img src="<?php echo !empty( $file_val ) ? esc_url($base_url.$file_val) : esc_url(WOO_VOU_IMG_URL).'/preview.png'; ?>" height="200" width="200" /></div>
					</td>
				</tr>
			<?php
		}
	}

	/**
	 * Display Date Time Picker HTML
	 * 
	 * Handle to add custom date time picker
	 *
	 * @package WooCommerce - PDF Vouchers
	 * @since 3.2.4
	 */
	public function woocommerce_admin_field_vou_datetime_picker( $field ) {
		
		global $woocommerce;

		if ( isset( $field['title'] ) && isset( $field['id'] ) ) :

			$file_val	= get_option($field['id']);
			$file_val	= !empty($file_val) ? $file_val : '';
			?>
			
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<fieldset>
						<input name="<?php echo esc_attr( $field['id'] ); ?>" id="<?php echo esc_attr( $field['id'] ); ?>" class="woo-vou-meta-datetime woo-vou-datetime-input" value="<?php echo $file_val; ?>" type="text" rel="<?php echo esc_attr( $field['rel'] ); ?>">
					</fieldset>
					<span class="description"><?php echo $field['desc'];?></span>
				</td>
			</tr><?php
		
		endif;
	}
	
	public function woo_vou_button_callback( $field ) {

		if ( isset( $field['title'] ) && isset( $field['id'] ) ) {

			$file_class	= !empty( $field['class'] ) ? 'class="'.$field['class'].'"' : '';
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo wp_kses_post( $field['title'] ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<a href="<?php echo esc_url(add_query_arg(array($field['id'] => '1','security'=>wp_create_nonce('woo_vou_check_security')))); ?>" <?php echo $file_class; ?>><?php echo $field['btn_title']; ?></a>
					<span class="description"><?php echo $field['desc'];?></span>
				</td>
			</tr><?php
		}
	}

	/**
	 * Render addons html
	 * 
	 * Callback function for woo_vou_addons field type
	 */
	public function woo_vou_addons_callback( $field ) {

		ob_start();
		require_once WOO_VOU_ADMIN .'/forms/woo-vou-plugin-addon.php';
		$addon_setting = ob_get_clean();
		echo $addon_setting;
	}

	/**
	 * Display Custom field section on setting page
	 * 
	 * Handle to manage custom fields for voucher
	 *
	 * @package WooCommerce - PDF Vouchers
	 * @since 7.4.4
	 */
	public function woo_vou_render_gift_voucher_field_callback( $field ) {

		$vou_gift_voucher_custom_fields = get_option( 'vou_gift_voucher_custom_fields' );
		?>
		<table class="form-table vou-gift-voucher-cf-section">
			<tbody>
				<tr valign="top" class="vou-gift-inner-wrap-voucher">
					<th scope="row" class="titledesc"><?php esc_html_e( 'Manage Custom Fields' ); ?></th>
					<td class="forminp forminp-checkbox">
						<div class="woo-vou-gv-default-div hidden">
							<div class="woo-vou-repeater-block">
								<table class="repeater-table">
									<tbody>
										<tr scope="row">
											<th>
												<label><?php echo esc_html__( 'Field Name:', 'woovoucher' ); ?></label>
											</th>
											<td>
												<input type="text" class="regular-text" value="">
												<input type="hidden" id="woo-vou-gv-field-id" value="">
											</td>
										</tr>
										<tr scope="row">
											<th>
												<label><?php echo esc_html__( 'Field Type:', 'woovoucher' ); ?></label>
											</th>
											<td>
												<select class="woo-vou-meta-select" id="woo-vou-new-recipient-field-type">
													<option value="<?php echo esc_attr( 'text' ); ?>"><?php esc_html_e( 'Text', 'woovoucher' ); ?></option>
													<option value="<?php echo esc_attr( 'textarea' ); ?>"><?php esc_html_e( 'Textarea', 'woovoucher' ); ?></option>
												</select>
											</td>
										</tr>
									</tbody>
								</table>
								<button type="button" class="woo-vou-gv-remove-row-button button"><img src="<?php echo ! empty( $file_val ) ? esc_url( $base_url . $file_val ) : esc_url( WOO_VOU_IMG_URL ) . '/cross-pdfgv.svg'; ?>" /></button>
							</div>
						</div>
						<div class="woo-vou-repeater-main-div">
							<?php
							if ( ! empty( $vou_gift_voucher_custom_fields ) ) {
								$woo_vou_gv_repeater_count = 0;
								foreach ( $vou_gift_voucher_custom_fields as $key => $value ) {
									$woo_vou_gv_repeater_count = $key;
									?>
									<div class="woo-vou-repeater-block">
										<table class="repeater-table">
											<tbody>
												<tr scope="row">
													<th>
														<label for="vou_gift_voucher_custom_fields[<?php echo $key; ?>][name]"><?php echo esc_html__( 'Field Name:', 'woovoucher' ); ?></label>
													</th>
													<td>
														<input type="text" class="regular-text" name="vou_gift_voucher_custom_fields[<?php echo $key; ?>][name]" value="<?php echo esc_attr( $value['name'] ); ?>">
														<input type="hidden" id="woo-vou-gv-field-id" class="regular-text" name="vou_gift_voucher_custom_fields[<?php echo $key; ?>][id]" value="<?php echo esc_attr( $value['id'] ); ?>">
													</td>
												</tr>
												<tr scope="row">
													<th>
														<label for="vou_gift_voucher_custom_fields[<?php echo $key; ?>][type]"><?php echo esc_html__( 'Field Type:', 'woovoucher' ); ?></label>
													</th>
													<td>
														<select name="vou_gift_voucher_custom_fields[<?php echo $key; ?>][type]" class="woo-vou-meta-select" id="woo-vou-new-recipient-field-type">
															<option value="<?php echo esc_attr( 'text' ); ?>" <?php echo $value['type'] == 'text' ? 'selected' : ''; ?>><?php esc_html_e( 'Text', 'woovoucher' ); ?></option>
															<option value="<?php echo esc_attr( 'textarea' ); ?>" <?php echo $value['type'] == 'textarea' ? 'selected' : ''; ?>><?php esc_html_e( 'Textarea', 'woovoucher' ); ?></option>
														</select>
													</td>
												</tr>
												<tr scope="row">
													<th>
														<label><?php echo esc_html__( 'Shortcode:', 'woovoucher' ); ?></label>
													</th>
													<td class="woo-vou-tooltip-copytext"><span class="woo-vou-gv-shortcode woo-vou-copyable-shortcode"><?php echo '{' . $value['id'] . '}'; ?></span><span class="tooltipText tooltipTextTop tooltipArrow tooltipArrowTop"><?php esc_html_e( 'Copy to use Short-code at Voucher Template', 'woovoucher' ); ?></span></span></td>
												</tr>
											</tbody>
										</table>
										<button type="button" class="woo-vou-gv-remove-row-button button"><img src="<?php echo ! empty( $file_val ) ? esc_url( $base_url . $file_val ) : esc_url( WOO_VOU_IMG_URL ) . '/cross-pdfgv.svg'; ?>" /></button>
									</div>
									<?php
								}
							}
							?>
							<input type="hidden" name="woo_vou_gv_repeater_count" class="woo-vou-cus-repeater-count" value="<?php echo isset( $woo_vou_gv_repeater_count ) ? $woo_vou_gv_repeater_count : 0; ?>">
							<div class="woo-vou-gv-repeater-add-button button"><?php echo esc_html__( 'Click To Add' ); ?></div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
}