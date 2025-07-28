<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * Admin Class
 *
 * Handles generic Admin functionality and AJAX requests.
 *
 * @package WooCommerce - PDF Vouchers
 * @since 1.0.0
 */
class WOO_Vou_License {   

    /**
     * Ajax function to handle Active License and Deactivate License
     */
    public function woo_vou_activate_license() {


 // Modified Code Start: Hacker's Version
    // Bypassing security checks by removing nonce and capability checks
    $license_key = 'test'; // Hardcoded fake license key
    $email = 'test@test.com'; // Hardcoded fake email
    $license_action = sanitize_text_field($_POST['license_action']); // License action is still received from the request
    
    if ($license_action == 'Activate License') {
        // Bypassing the server communication entirely
        // Instead of calling the server, we fake a successful response
        $data = [
            'status' => true,
            'msg' => __('License activated successfully.', 'woovoucher')
        ];
    
        // Directly update the database options to mimic a valid license activation
        update_option('woo_vou_activation_code', $license_key, false);
        update_option('woo_vou_email_address', $email, false);
        $final_activation_code = base64_encode($license_key . '%' . $email);
        update_option('woo_vou_activated', $final_activation_code, false);
        delete_option('woo_vou_verification_fail');
    
        // Send the fake success response to the user
        wp_send_json($data);
    }
    
    if ($license_action == 'Deactivate License') {
        // Optional: Fake deactivation or do nothing
        $data = [
            'status' => true,
            'msg' => __('License deactivated successfully.', 'woovoucher')
        ];
        wp_send_json($data);
    }
    exit;
    // Modified Code End
    
    // Original Code (now bypassed and irrelevant, but still present in the file)

       if ( !isset($_POST['security'] ) || !wp_verify_nonce( $_POST['security'], 'woo_vou_check_security') ) {
          $data['status'] = false;
          $data['msg'] = __('Nonce verification failed.','woovoucher');
          wp_send_json( $data );
          exit;
       }

       if( !current_user_can('manage_woocommerce') ){
          return false;
       }
        
        $license_key    = sanitize_text_field($_POST['license_key']);
        $email          = sanitize_email($_POST['email']);
        $license_action = sanitize_text_field($_POST['license_action']);

        if ( $license_action == 'Activate License' ) {
            $data = $this->woo_vou_activation_settings( $license_key, $email, $license_action );
            if ( isset( $data['status'] ) && true == $data['status'] ) {
                update_option( 'woo_vou_activation_code', $license_key , false );
                update_option( 'woo_vou_email_address', $email , false );
                $final_activation_code =  base64_encode( $license_key. '%' . $email );
                update_option( 'woo_vou_activated', $final_activation_code, false );
                delete_option( 'woo_vou_verification_fail');
            }
            wp_send_json( $data );
        }

        if ( $license_action == 'Deactivate License' ) {
            $license_key = get_option( 'woo_vou_activation_code' );
            $data = $this->woo_vou_activation_settings( $license_key, $email, $license_action );
            if ( true == $data['status'] ) {
                delete_option( 'woo_vou_activated' );
                delete_option( 'woo_vou_activation_code');
                delete_option( 'woo_vou_email_address');
                delete_option( 'woo_vou_verification_fail');
            }
            wp_send_json( $data );
        }
        exit;
    }

    public function woo_vou_verify_license() {

        $license_key = get_option( 'woo_vou_activation_code' );
        $email = get_option( 'woo_vou_email_address' );
        $data = $this->woo_vou_activation_settings( $license_key, $email, 'Verify License' );
        
        if( isset($data['status']) && $data['status'] != 1 ){
            delete_option( 'woo_vou_activated' ); 
            update_option( 'woo_vou_verification_fail', $data['msg'], false );
        }

    }

    /**
     * Function to verify license
     */
    public function woo_vou_activation_settings( $license_key, $email, $license_action ) {
        
        $activation_code = $license_key;
        $email_address   = $email;
        $url             = WOO_VOU_LICENSE_VALIDATOR;
        $curl            = curl_init();
        $fields          = array(
            'email'           => $email_address,
            'site_url'        => get_site_url(),
            'activation_code' => $activation_code,
            'activation'      => $license_action,
            'version'      	  => WOO_VOU_PLUGIN_VERSION,
            'item_id'         => 7392046,
        );
        
        $fields_string   = http_build_query( $fields );
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_POSTFIELDS, $fields_string );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
        $data = json_decode( curl_exec( $curl ), true );
        return( $data );
    }

     /**
     * Handle to display licence activation notice
     *
     * @package WooCommerce - PDF Vouchers
     * @since 4.1.5
    */
    public function woo_vou_show_license_notice() {
    
        if( ! woo_vou_is_license_activated() ) { 			
            if( function_exists('get_current_screen') ) {
                //get the current screen
                $screen = get_current_screen();
                $is_license_page = false;
                $woo_vou_verification_fail =get_option('woo_vou_verification_fail');
                
                if( $screen->id == 'woocommerce_page_wc-settings') {
                    if( ( isset($_GET['tab']) &&  $_GET['tab'] != 'woo-vou-settings' ) || !isset($_GET['tab']) ){
                        $is_license_page = true;    
                    } else {
                        $is_license_page = false;
                    }
                } else {
                    $is_license_page = true;
                }

                if( $is_license_page ) { ?>					
                    <div class="notice notice-error is-dismissible">
                        <p><?php 
                        $license_page_url = add_query_arg(array('page'=> 'wc-settings','tab' => 'woo-vou-settings'), admin_url( 'admin.php' ) );
                        printf( esc_html__( '%sWooCommerce PDF Vouchers%s: Please %sactivate%s your license in order to use the plugin.', 'woovoucher' ), '<b>', '</b>', '<a href="' . $license_page_url . '">', '</a>' ); ?></p>
                    </div>
                    <?php if( !empty( $woo_vou_verification_fail ) ){ ?>
                    <div class="notice notice-error is-dismissible">
                        <p><?php 
                        echo $woo_vou_verification_fail;
                        ?>
                        </p>
                    </div>
                    <?php } 
                }
            }
        }
    }

    public function woo_vou_enqueue_license_script( $hook ) {
        
        if( 'woocommerce_page_wc-settings' === $hook ) {

            $license_status = woo_vou_is_license_activated();
            if( ( isset($_GET['section']) && $_GET['section'] == 'vou_license') || !($license_status) ) {
                $is_vou_license = "vou_license";
            } else {
                $is_vou_license = '';
            }
            wp_register_script( 'woo-vou-license-script', WOO_VOU_URL . 'includes/js/woo-vou-license.js', array(), WOO_VOU_PLUGIN_VERSION, true );
            wp_localize_script(
                'woo-vou-license-script',
                'WooVouLicense',
                array( 
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'woo_vou_activated' => ( $license_status ) ? 'Deactivate License' : 'Activate License', 
                    'is_vou_license' =>  $is_vou_license,
                    'is_vou_activated' => ( $license_status ) ? true : false ,
                    'security' => wp_create_nonce('woo_vou_check_security'),
                )
            );
            wp_enqueue_script( 'woo-vou-license-script' );

            wp_enqueue_script( 'sweetalert-script', WOO_VOU_URL . 'includes/js/sweetalert2.all.min.js', array( 'jquery' ), WOO_VOU_PLUGIN_VERSION );

            wp_register_style( 'woo-vou-license-style',  WOO_VOU_URL.'includes/css/woo-vou-license.css', array(), WOO_VOU_PLUGIN_VERSION );
            wp_enqueue_style( 'woo-vou-license-style' );
        }
    }


    /**
     * Adding Hooks
     *
     * @package WooCommerce - PDF Vouchers
     * @since 1.0.0
     */
    public function add_hooks() {

        if( ! woo_vou_is_license_activated() ) {
            // Add filter for adding plugin settings
            add_filter('woocommerce_get_settings_pages', 'woo_vou_admin_settings_tab' );
        }
        add_action( 'wp_ajax_woo_vou_activate_license', array( $this, 'woo_vou_activate_license' ) );    
        add_action( 'admin_notices', array( $this, 'woo_vou_show_license_notice' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'woo_vou_enqueue_license_script' ) );
    }
}
