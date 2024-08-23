<?php
/*
*plugin name:zatca
*description:Zatca Integration
*author:Appy Innovate
*Author url:
*Version: Date - 17 Aug 2024
*test domain:zatca
*text domain:zatca
*domain path:/languages
*/

// Security Layer:
if(!defined('ABSPATH')){
    echo 'What Are You Do';
    exit;
}


// Function to view the release date in admin bar:
function my_custom_admin_bar_text() {
    global $wp_admin_bar;
    
    $wp_admin_bar->add_menu( array(
        'id'    => 'my-custom-release-date',
        'title' => __("Zatca Release Date: ", "zatca") . ': ' . wp_date( 'j F Y', strtotime( '17 Aug 2024' ) ),
        'href'  => false,
    ));
}




// Include Option.php:
include 'option.php';

// Get Walker Class For Bootstrap:
include_once 'css/class-wp-bootstrap-navwalker.php';

// Action for Release date in admin bar:
add_action( 'admin_bar_menu', 'my_custom_admin_bar_text', 999 );

// Include the table query file:
include_once(plugin_dir_path(__FILE__) . '/includes/table_query.php');

// Include the file that contains the create_custom_table function
require_once(plugin_dir_path(__FILE__) . 'create_db_tables.php');

// Create Tables when Plugin run:
register_activation_hook( __FILE__, 'create_custom_tables' );

// Add [CSS, JS, Bootstrap ] to admin panel:
add_action('admin_enqueue_scripts', 'load_assets');

// add assets (css, js, etc):
add_action('wp_enqueue_scripts',  'load_assets');


// Action For Javascript Localization:
add_action('wp_enqueue_scripts', 'localization');
add_action('admin_enqueue_scripts', 'localization');

// Action Of Sending woo order data in AJax [ Document ]:
add_action('admin_enqueue_scripts', 'document_data_ajax');

// ajax action - insert - customers:
add_action('wp_ajax_insert_customer', 'submit_customer_form');

// ajax action - insert - devices:
add_action('wp_ajax_insert_device', 'insert_form_devices');

// ajax action - insert - documents:
add_action('wp_ajax_insert-documents', 'insert_form_documents');

// ajax action - delete - customers:
add_action('wp_ajax_edit_customer', 'edit_customer_form');

// ajax action - edit - customers:
add_action('wp_ajax_delete_customer', 'delete_customer_form');


// ajax action - edit - checkout Page:
add_action('wp_ajax_edit_checkout_page', 'edit_checkout_page_function');

// ajax action - edit - devices:
add_action('wp_ajax_edit_device', 'edit_form_device');

// ajax action - edit - customers:
add_action('wp_ajax_delete_device', 'delete_device_form');

// ajax action - edit - company:
add_action('wp_ajax_submit_company', 'submit_form_company');

// ajax action - edit - document:
add_action('wp_ajax_edit-document', 'document_edit_form');


// Action to get name from db with ajax data - [ insert page ] customers:
add_action('wp_ajax_customer', 'get_customer_data_from_woo');

// Action to get Data from db with ajax data - [ view ] company:
add_action('wp_ajax_company', 'company_vat_cat_code_form');

// Action to get Data from db with ajax data - [ view ] company:
add_action('wp_ajax_woo-company-data', 'woo_company');

// Action to get Data from db with ajax data - [ insert ] document:
add_action('wp_ajax_woo-document-data', 'woo_document');

// Action to Check customer from db with ajax data - [ insert ] document:
add_action('wp_ajax_doc_check_customer', 'document_check_customer');


// Action to get data as ajax data to check if customer exists in zatcaCustomer or not - document:
add_action('wp_ajax_doc_customer', 'doc_customer_Get_data_ajax');

// Action to get data to put in customer insert page when redirected from document page:
add_action('admin_enqueue_scripts', 'document_customer_handle_in_customer_page');

// Action of to get xml from saved response:
add_action('wp_ajax_download_xml', 'get_xml_from_response');

// Action of Send Clearance request to zatca:
add_action('wp_ajax_zatca_clear', 'send_request_to_zatca_clear');


// Use wordpress admin panel color schema:
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_admin_styles');

// Use admin color schema for btns:
add_action('admin_head', 'my_plugin_button_styles');

// Action Of Text Domain:
add_action( 'init', 'my_plugin_load_textdomain' );

// Action Of Login - zatcaLogs:
add_action('wp_login', 'log_user_login', 10, 2);

// Action to get admin data - zatcaUsers:
add_action('wp_ajax_get_user_admin_data', 'admin_user_zatcaUsers');

// Action to insert users data - zatcaUsers:
add_action('wp_ajax_insert_user', 'insert_user_zatcaUsers');

// Action to Edit Users data - zatcaUsers:
add_action('wp_ajax_edit_user', 'edit_user_zatcaUsers');

// ajax action - edit - customers:
add_action('wp_ajax_delete_user', 'delete_user_form');

// Action to check Users Notification - zatcaUsers:
add_action('wp_ajax_check_admin_notification', 'check_admin_for_B2C_notification');

// Short code to checkbox [ checkout page ]:
add_shortcode('checkbox', 'checkbox_function');
    
// Hook of woo to insert checkbox in checkout page [ checkout page ]:
add_action('woocommerce_blocks_checkout_enqueue_data', 'add_custom_field_to_checkout_blocks');
    
add_action('wp_ajax_handle_form_tampering', 'handle_form_tampering');

// Function to run Text Domain:
function my_plugin_load_textdomain() {
    load_plugin_textdomain( 'zatca', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    error_log( 'my_plugin_load_textdomain function executed' );

}

// Admin Panel Schema:
function my_plugin_enqueue_admin_styles() {
    wp_enqueue_style('my-plugin-admin-styles', plugins_url('css/main.css', __FILE__));
}

// Admin Panel Btns:
function my_plugin_button_styles() {
    // Get the current user's admin color scheme
    $color_scheme = get_user_option('admin_color');

    // Define button size (you can set it dynamically based on your requirements)
    $button_size = 'medium';

    // Define button styles based on the color scheme
    switch ($color_scheme) {
        case 'light':
            $button_style = 'background-color: #0073aa; color: #fff;';
            break;
        case 'blue':
            $button_style = 'background-color: #0073aa; color: #fff;';
            break;
        case 'midnight':
            $button_style = 'background-color: #444; color: #fff;';
            break;
        case 'ectoplasm':
            $button_style = 'background-color: #523f6d; color: #fff;';
            break;
        case 'sunrise':
            $button_style = 'background-color: #d64e07; color: #fff;';
            break;
        case 'coffee':
            $button_style = 'background-color: #542f0e; color: #fff;';
            break;
        case 'blue-light':
            $button_style = 'background-color: #0073aa; color: #333;';
            break;
        default:
            $button_style = 'background-color: #0073aa; color: #fff;';
    }

    // Adjust button size based on the chosen size
    switch ($button_size) {
        case 'small':
            $button_style .= ' font-size: 12px; padding: 5px 10px;';
            break;
        case 'medium':
            $button_style .= ' font-size: 14px; padding: 8px 15px;';
            break;
        case 'large':
            $button_style .= ' font-size: 16px; padding: 10px 20px;';
            break;
        // Add more cases for other sizes if needed
    }

    // Echo the button styles inline
    echo '<style>.my-plugin-button { ' . $button_style . ' }</style>';
}

// Function to load all assets [ CSS - JS]:
function load_assets(){

    wp_enqueue_style('datatables-css', plugin_dir_url(__FILE__) . '/css/datatables.min.css');
    wp_enqueue_style('datatables-responsive-css', plugin_dir_url(__FILE__) . '/css/datatable-responsive.css');
    wp_enqueue_style('main', plugin_dir_url(__FILE__) . '/css/main.css') ;
    wp_enqueue_style('checkout-page', plugin_dir_url(__FILE__) . '/css/checkout-page.css') ;
    wp_enqueue_style('bootstap-css', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
    wp_enqueue_style('elect2', plugin_dir_url(__FILE__) . '/css/select.css');
    wp_enqueue_style('fontawsome-css', plugin_dir_url(__FILE__) . '/css/fontawesome.css');
    wp_enqueue_style('fontawsome-solid-css', plugin_dir_url(__FILE__) . '/css/solid.css');
    wp_enqueue_style('fontawsome-brands-css', plugin_dir_url(__FILE__) . '/css/brands.css');
    wp_enqueue_style('fontawsome-min-css', plugin_dir_url(__FILE__) . '/css/fontawesome.min.css');
    wp_enqueue_style('notification-css', plugin_dir_url(__FILE__) . '/css/notification.css');
    wp_enqueue_style("dashicons");
    
    

    
    wp_enqueue_script( 'elect2', plugin_dir_url(__FILE__) . '/js/select.js', array('datatables-js') );
    wp_enqueue_script('datatables-js', plugin_dir_url(__FILE__) . '/js/datatables.min.js', array(), false, true);
    wp_enqueue_script('moment-js', plugin_dir_url(__FILE__) . '/js/moment.js', array(), false, true);
    wp_enqueue_script('datatable-datetime-js', plugin_dir_url(__FILE__) . '/js/datatable-datetime.js', array(), false, true);
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstap-js', plugin_dir_url(__FILE__) . '/js/bootstrap.min.js', array(), false, true);
    wp_enqueue_script('fontawsome-js', plugin_dir_url(__FILE__) . '/js/fontawesome.min.js', array(), false, true);
    wp_enqueue_script('main-js',  plugin_dir_url(__FILE__) . '/js/main.js', array(), false, true);
    wp_enqueue_script('document-js',  plugin_dir_url(__FILE__) . '/js/document.js', array(), false, true);
    wp_enqueue_script('checkout-page',  plugin_dir_url(__FILE__) . '/js/checkout-page.js', array(), false, true);
    wp_localize_script( 'checkout-page', 'checkoutPage', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        ) 
    );

    wp_enqueue_script('tampering-script', plugin_dir_url(__FILE__) . '/js/tampering-detector.js', array(), false, true);  

    // Localize the script with new data  
    wp_localize_script('tampering-script', 'ajax_object', array(  
        'ajax_url' => admin_url('admin-ajax.php')  
    ));  
    
    wp_localize_script( 'main-js', 'main', array( 
        'dtLoc' => plugin_dir_url(__FILE__) . '/js/datatable-localization.json',
        'locale' => get_locale())
    );
    wp_localize_script( 'document-js', 'myDoc', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-documents&action=view'),
        'customer' => admin_url('admin.php?page=zatca-documents&action=doc-add-customer'),
        ) 
    );
    wp_enqueue_script('users-js',  plugin_dir_url(__FILE__) . '/js/users.js', array(), false, true);
    wp_localize_script( 'users-js', 'myUser', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-users&action=view'),
        'isAdmin' => is_admin() ? 'true' : 'false',) 
    );
    wp_enqueue_script('device-js',  plugin_dir_url(__FILE__) . '/js/device.js', array(), false, true);
    wp_localize_script( 'device-js', 'myDevice', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-devices&action=view'),) 
    );
    wp_enqueue_script('company-js',  plugin_dir_url(__FILE__) . '/js/company.js', array(), false, true);
    wp_localize_script( 'company-js', 'myCompany', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-company&action=view'),) 
    );
    wp_enqueue_script('customer-js',  plugin_dir_url(__FILE__) . '/js/customer.js', array(), false, true);
    wp_localize_script( 'customer-js', 'myCustomer', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-customers&action=view'),
        'document' => admin_url('admin.php?page=zatca-documents&action=insert'),
        ) 
    );
    wp_enqueue_script('notification-js', plugin_dir_url(__FILE__) . '/js/notification.js', array(), false, true);

}

// Function to javascript localization:
function localization() {

    // zatcaCustomer Localization:
    wp_enqueue_script('customer-js', plugin_dir_url(__FILE__) . 'js/customer.js', array(), false, true);
    wp_localize_script('customer-js', 'myCustomer', array( 
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'adminUrl' => admin_url('admin.php?page=zatca-customers&action=view'),
        'document' => admin_url('admin.php?page=zatca-documents&action=insert'),
        'postal_null' => __('Postal Code Cant be Null', 'zatca'),
        'postal_digits' => __('Postal Code Must be 5 Digits', 'zatca'),
        'street' => __('Street Arabic Name Cant be Null', 'zatca'),
        'second_id' => __('second business id Cant be Null', 'zatca'),
        'district' => __('District Arabic Name Cant be Null', 'zatca'),
        'city' => __('City Arabic Name Cant be Null', 'zatca'),
        'customer_inserted' => __('Customer Inserted Success', 'zatca'),
        'select_customer' => __('Please select a customer first', 'zatca'),
        'arabic_name' => __('Please Insert Customer Aarabic Name', 'zatca'),
        'client_name_must_arabic' => __("You Must Enter Client Name In Arabic", "zatca"),
        'notification_error_title' => __("Error", "zatca"),
        'notification_success_title' => __("Success", "zatca"),
        'delete_msg' => __("Are You Sure?", "zatca"),
        'delete_title' => __("Delete", "zatca"),
    ));

    // zatcaCompany localization:
    wp_enqueue_script('company-js',  plugin_dir_url(__FILE__) . '/js/company.js', array(), false, true);
    wp_localize_script( 'company-js', 'myCompany', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-company&action=view'),
        'second_bus' => __('Second Business Id Must be 10 Digits', 'zatca'),
        'city_ar' => __('Please Insert City Arabic Name', 'zatca'),
        'dist_ar' => __('Please Insert District Arabic Name', 'zatca'),
        'company_name' => __('Please Insert Company Name', 'zatca'),
        'appartment_no' => __('Please Insert Appartment No.', 'zatca'),
        'po_box_additional' => __('Please Insert Po Box Additional No.', 'zatca'),
        'street_ar' => __('Please Insert Street Arabic Name', 'zatca'),
        'notification_error_title' => __("Error", "zatca"),
        'notification_success_title' => __("Success", "zatca"),
    ));

    // zatcaUser localization:
    wp_enqueue_script('users-js',  plugin_dir_url(__FILE__) . '/js/users.js', array(), false, true);
    wp_localize_script( 'users-js', 'myUser', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-users&action=view'),
        'isAdmin' => is_admin() ? 'true' : 'false',
        'person_no_validation' => __('Person No Cant be Null', 'zatca'),
        'reminder_hours_validation' => __('Please insert reminder hours', 'zatca'),
        'reminder_hours_validation_number' => __('Reminder hours must be between 1-23 Hours', 'zatca'),
        'user_exist' => __('User Already Exist', 'zatca'),
        'user_inserted' => __('User Inserted Successfully', 'zatca'),
        'notification_error_title' => __("Error", "zatca"),
        'notification_success_title' => __("Success", "zatca"),
        'notification_warning_title' => __("Warning", "zatca"),
        'delete_msg' => __("Are You Sure?", "zatca"),
        'delete_title' => __("Delete", "zatca"),
        ) 
    );

    //zatcaDevice loalization:
    wp_enqueue_script('device-js',  plugin_dir_url(__FILE__) . '/js/device.js', array(), false, true);
    wp_localize_script( 'device-js', 'myDevice', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-devices&action=view'),
        'device_inserted' => __("New Device Inserted", "zatca"),
        'device_updated' => __("Device Status Updated", "zatca"),
        'device_active' => __("Not allowed to add more one device active", "zatca"),
        'notification_error_title' => __("Error", "zatca"),
        'notification_success_title' => __("Success", "zatca"),
        'delete_msg' => __("Are You Sure?", "zatca"),
        'delete_title' => __("Delete", "zatca"),
        ) 
    );

    //zatcaDocument localization
    wp_enqueue_script('document-js',  plugin_dir_url(__FILE__) . '/js/document.js', array(), false, true);
    wp_localize_script( 'document-js', 'myDoc', array( 
        'locale' => get_locale(),
        'dtLoc' => plugin_dir_url(__FILE__) . '/js/datatable-localization.json',
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-documents&action=view'),
        'customer' => admin_url('admin.php?page=zatca-documents&action=doc-add-customer'),
        'document_inserted' => __("Document Created successifly", "zatca"),
        'document_updated' => __("Data Updated", "zatca"),
        'document_device_expired' => __("Device CsID_ExpiryDate is Expired", "zatca"),
        'zatca_company_empty' => __("Please Insert Company Details", "zatca"),
        'insert_seller_additional_id' => __("You Muse Insert Seller additional Id Number in zatca Company", "zatca"),
        'insert_buyer_poBox_additionalNo' => __("You Muse Insert Buyer po box additional number in zatca customer", "zatca"),
        'insert_buyer_additional_id' => __("You Muse Insert Buyer additional Number in zatca customer", "zatca"),
        'no_rows_affected' => __("No rows were affected. Possible reasons: No matching rows or the data is already up to date.", "zatca"),
        'error_303' => __("Please submit via reporing", "zatca"),
        'error_401' => __("Unauthorized, Please check authentication certificate and secret and resubmit", "zatca"),
        'error_413' => __("Please resend with smaller payload(invoice), Decrease invoice details and resubmit", "zatca"),
        'error_429' => __("Please wait for 1 minute and resubmit", "zatca"),
        'error_500' => __("Internal Server Error, Please try again later", "zatca"),
        'error_503' => __("Service Unavailable, Please try again later", "zatca"),
        'error_504' => __("Gateway Timeout, Please try again later", "zatca"),
        'buyer_arabic_name' => __("Buyer arabic name is mandatory and the same as his name in his National ID", "zatca"),
        'buyer_second_business_id_type' => __("Second business type must be National ID, Please edit customer profile", "zatca"),
        'buyer_second_business_id' => __("Buyer Second business ID must be filled, Please edit customer profile", "zatca"),
        'seller_second_business_id' => __("Seller Second business ID must be filled, Please edit company profile", "zatca"),
        'company_stage_2' => __("Company zatca stage must be V2, Please edit company profile", "zatca"),
        'isexport0_buyervat' => __("Sorry, VAT ID for the client must be empty because the invoice is exports", "zatca"),
        'isexport1_buyervat' => __("Sorry, VAT ID for the client must be filled", "zatca"),
        'sell_invoice' => __("Sell Invoice", "zatca"),
        'sell_return_invoice' => __("Return Sell Invoice", "zatca"),
        'choose_customer_td' => __("Please select a Order first", "zatca"),
        'notification_error_title' => __("Error", "zatca"),
        'notification_success_title' => __("Success", "zatca"),
        'notification_warning_title' => __("Warning", "zatca"),
        'error_word' => __("Error", "zatca"),
        'document_word' => __("Document", "zatca"),
        'mayBeDeviceError' => __("The device signature or token data may not be correct , please check and try again!", "zatca"),
        'generatedAlready' => __("This invoice is already generated and sent before to zatca!", "zatca"),
        ) 
    );

    // main.js localization:
    wp_enqueue_script('main-js',  plugin_dir_url(__FILE__) . '/js/main.js', array(), false, true);
    wp_localize_script( 'main-js', 'main', array( 
        'dtLoc' => plugin_dir_url(__FILE__) . '/js/datatable-localization.json',
        'delete_msg' => __("Are You Sure?", "zatca"),
        'delete_title' => __("Delete", "zatca"),
        'locale' => get_locale()
        )
    );

    // checkout.js localization:
    wp_enqueue_script('checkout',  plugin_dir_url(__FILE__) . '/js/checkout-page.js', array(), false, true);
    wp_localize_script( 'checkout', 'checkout', array( 
        'client_name_not_empty' => __("Client Name Arabic Cant be Empty", "zatca"),
        'client_name_must_arabic' => __("You Must Enter Client Name In Arabic", "zatca"),
        'notification_error_title' => __("Error", "zatca"),
        'notification_success_title' => __("Success", "zatca"),
        )
    );

}


function handle_form_tampering() {  
    
    // Check if the form is submitted
    if ($_POST['check_type'] == 'check_counter_gap') 
    {
        // Call the counter gap function
        $content = '';
        
        global $wpdb;

        // Get the necessary POST data
        $buildingNo = $_POST['buildingNo']; // branch id
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];

        
        // Query to fetch the invoice numbers within the specified date range for the given branch
        $query = $wpdb->prepare("
            SELECT z1.documentNo
            FROM zatcaDocument z1
            INNER JOIN zatcaDevice zd ON zd.deviceNo = z1.deviceNo
            INNER JOIN zatcaBranch zb ON z1.buildingNo = zb.buildingNo
            WHERE zb.buildingNo = %d
            AND CAST(z1.dateG AS DATE) BETWEEN %s AND %s
            ORDER BY z1.documentNo
        ", $buildingNo, $from_date, $to_date);

        $results = $wpdb->get_results($query);

        
        // Initialize variables
        $missing_numbers = [];
        $prev_number = null;

        // Check for missing invoice numbers
        foreach ($results as $result) {
            $current_number = $result->documentNo;

            if ($prev_number !== null && $current_number - $prev_number > 1) {
                // Gap detected, add missing numbers to the array
                for ($i = $prev_number + 1; $i < $current_number; $i++) {
                    $missing_numbers[] = $i;
                }
            }

            $prev_number = $current_number;
        }

        // Prepare the response data
        $response = array(
            'missing_numbers' => $missing_numbers,
        );

        
        // Display the results in a grid table
        $content .= '<div class="container"><table id="example" class="table table-striped" width="100%">';
        $content .= '<thead><tr><th class="text-center">' . __("Missing Invoice Numbers", "zatca") . '</th></tr></thead>';
        $content .= '<tbody class="text-center">';

        foreach ($response['missing_numbers'] as $missing_number) {
            $content .= '<tr><td>' . $missing_number . '</td></tr>';
        }

        $content .= '</tbody></table></div>';

        echo $content;
    }

    // Check if the form is submitted
    if ($_POST['check_type'] == 'check_hash_gap') 
    {
        // Call the hash gap function
        global $wpdb;

        // Get the necessary POST data
        $buildingNo = $_POST['buildingNo']; // branch id
        $from_date = $_POST['from_date'];
        $to_date = $_POST['to_date'];

        // Define zatcaInfo table name  
        $zacainfo_table = 'zatcaInfo';  

        $missing_records = [];  

        // Step 1: Select zatcaInfo1, zatcaInfo2, and zatcaInfo3 from zacainfo table  
        $zacainfo_query = $wpdb->prepare(  
            "SELECT zatcaInfo1, zatcaInfo2, zatcaInfo3 FROM $zacainfo_table"  
        );  
        $zacainfo_records = $wpdb->get_results( $zacainfo_query, ARRAY_A );  

        // Step 2: Select documentNo, deviceNo, and invoiceHash from zatcaDocument & zatcaDocumentxml tables
        // within the given date range  
        $zatcaDocument_query = $wpdb->prepare("SELECT z.documentNo, z.deviceNo, z1.invoiceHash 
        FROM zatcaDocument z, zatcaDocumentxml z1 
        WHERE z.BuildingNo= %d 
        AND CAST(z.dateG AS DATE) BETWEEN %s AND %s 
        AND z.documentNo=z1.documentNo",
        $buildingNo, $from_date, $to_date);

        $zatcaDocument_records = $wpdb->get_results( $zatcaDocument_query, ARRAY_A );  

        if ( empty($zatcaDocument_records) ) 
        {
            $missing_records = [];
        }
        else
        {
            // Step 3: Create an associative array for zatcaDocument records for quick lookup  
            $zatcaDocument_map = [];  
            foreach ( $zatcaDocument_records as $document ) {  
                $key = implode('|', [$document['invoiceHash'], $document['documentNo'], $document['deviceNo']]);  
                $zatcaDocument_map[ $key ] = $document;  
            }  

            // Step 4: Check for existence of records from zacainfo in zatcaDocument  
            foreach ( $zacainfo_records as $info ) {
                
                $key = implode('|', [decrypt_data($info['zatcaInfo1']), decrypt_data($info['zatcaInfo2']), decrypt_data($info['zatcaInfo3'])]);  
                
                // Check if the composite key exists in zatcaDocument  
                if ( ! isset( $zatcaDocument_map[ $key ] ) ) {  
                    // Push missing record's values to the array  
                    $missing_records[] = [  
                        'invoiceHash' => decrypt_data($info['zatcaInfo1']),  
                        'documentNo' => decrypt_data($info['zatcaInfo2']),  
                        'deviceNo' => decrypt_data($info['zatcaInfo3'])  
                    ];  
                }  
            }

            // Display the results in a grid table
            echo '<div class="container"><table id="example" class="table table-striped" width="100%">';
            echo '<thead><tr><th class="text-center">'. __("Document No", "zatca") .'</th><th class="text-center">'. __("Device No", "zatca") .'</th><th class="text-center">'. __("Invoice Hash", "zatca") .'</th></tr></thead><tbody class="text-center">';

            foreach ($missing_records as $document1) {
                echo '<tr>';
                echo '<td>' . $document1['documentNo'] . '</td>';
                echo '<td>' . $document1['deviceNo'] . '</td>';
                echo '<td>' . $document1['invoiceHash'] . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }

        
    }

    wp_die(); // terminate immediately and return a proper response  
}

// Function to encrypt data
function decrypt_data($data) {
    // Make sure to use the same encryption method for encryption and decryption
    // Decoding the previously encoded text  
    $decodedText  = base64_decode($data);

    return $decodedText ;
}

// AJax Insert_Data to DB - customers [ Insert-page]:
function submit_customer_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['customer_data_ajax'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:

        // Hidden Input For Status:
        $status = $form_array['status'];

        $client_No = $form_array['client-no'];
        $client_Name_Ar = $form_array['client-name-ar'];
        $client_Name_En = $form_array['client-name-en'];
        $vat_Id = $form_array['vat-id'];
        $second_Business_Id_Type = $form_array['second-business-id-type'];
        $second_Business_Id = $form_array['second-business-id'];
        $zatca_Invoice_Type = $form_array['zatca-invoice-type'];
        $apartment_No = $form_array['apartment-no'];
        $postal_code = $form_array['postal-code'];
        $po_Box = $form_array['po-box'];
        $po_Box_Additional_No = $form_array['po-box-additional-no'];
        $street_Name_Ar = $form_array['street-name-ar'];
        $street_Name_En = $form_array['street-name-en'];
        $destrict_Name_Ar = $form_array['district-name-ar'];
        $destrict_Name_En = $form_array['district-name-en'];
        $city_Name_Ar = $form_array['city-name-ar'];
        $city_Name_En = $form_array['city-name-en'];
        $country_Sub_Name_Ar = $form_array['country-sub-name-ar'];
        $country_Sub_Name_En = $form_array['country-sub-name-en'];
        $country = $form_array['country'];

        // Get country_arb & country_eng Depend On Country Choosing:
        $countryArab = $wpdb->get_var($wpdb->prepare("SELECT arabic_name FROM country WHERE country_id = $country"));
        $countryEnglish = $wpdb->get_var($wpdb->prepare("SELECT english_name FROM country WHERE country_id = $country"));
                


        $insert_result = $wpdb->insert(
            'zatcaCustomer',
            [
                'clientVendorNo'       => $client_No,
                'aName'                => $client_Name_Ar,
                'eName'                => $client_Name_En,
                'VATID'                 => $vat_Id,
                'secondBusinessIDType'  => $second_Business_Id_Type,
                'secondBusinessID'      => $second_Business_Id,
                'zatcaInvoiceType'      => $zatca_Invoice_Type,
                'apartmentNum'          => $apartment_No,
                'postalCode'            => $postal_code,
                'POBox'                 => $po_Box,
                'POBoxAdditionalNum'    => $po_Box_Additional_No,
                'street_Arb'            => $street_Name_Ar,
                'street_Eng'            => $street_Name_En,
                'district_Arb'          => $destrict_Name_Ar,
                'district_Eng'          => $destrict_Name_En,
                'city_Arb'              => $city_Name_Ar,
                'city_Eng'              => $city_Name_En,
                'countrySubdivision_Arb'=> $country_Sub_Name_Ar,
                'countrySubdivision_Eng'=> $country_Sub_Name_En,
                'country_No'            => $country,
                'country_Arb'           => $countryArab,
                'country_Eng'           => $countryEnglish
            ]
        );

        if ($insert_result === false) {
            // There was an error inserting data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo $status;
        
        }

    }

    die();
}

// Recived the customer data and get name from database - customers [ Insert-page]:
function get_customer_data_from_woo(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['customer_selected'];


		$table_usermeta = $wpdb->prefix . 'usermeta';

        
        $first_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_first_name' and user_id = $vals"));
        $last_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_last_name' and user_id = $vals"));
        $address = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_address_1' and user_id = $vals"));
        $address_2 = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_address_2' and user_id = $vals"));
        $city = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_city' and user_id = $vals"));
        $postalCode = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_postcode' and user_id = $vals"));

        // Return the fetched data
        // echo $first_name . ' ' . $last_name;
        $response = array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'address'    => $address,
            'address_2'    => $address_2,
            'city'       => $city,
            'postalCode' => $postalCode
        );

        // Return the array as JSON
        wp_send_json($response);
       
    }

    die();
}

// AJax Edit in DB - customers [ Edit-page]:
function edit_customer_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['edit_form_data_ajax'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $current_client_no = $form_array['current-client-no'];
        $client_No = $form_array['client-no'];
        $client_Name_Ar = $form_array['client-name-ar'];
        $client_Name_En = $form_array['client-name-en'];
        $vat_Id = $form_array['vat-id'];
        if($vat_Id == ''){ $vat_Id = 0; }
        $second_Business_Id_Type = $form_array['second-business-id-type'];
        $second_Business_Id = $form_array['second-business-id'];
        $zatca_Invoice_Type = $form_array['zatca-invoice-type'];
        $apartment_No = $form_array['apartment-no'];
        $postal_code = $form_array['postal-code'];
        $po_Box = $form_array['po-box'];
        $po_Box_Additional_No = $form_array['po-box-additional-no'];
        $street_Name_Ar = $form_array['street-name-ar'];
        $street_Name_En = $form_array['street-name-en'];
        $destrict_Name_Ar = $form_array['district-name-ar'];
        $destrict_Name_En = $form_array['district-name-en'];
        $city_Name_Ar = $form_array['city-name-ar'];
        $city_Name_En = $form_array['city-name-en'];
        $country_Sub_Name_Ar = $form_array['country-sub-name-ar'];
        $country_Sub_Name_En = $form_array['country-sub-name-en'];
        $country = $form_array['country'];

        // Get country_arb & country_eng Depend On Country Choosing:
        $countryArab = $wpdb->get_var($wpdb->prepare("SELECT arabic_name FROM country WHERE country_id = $country"));
        $countryEnglish = $wpdb->get_var($wpdb->prepare("SELECT english_name FROM country WHERE country_id = $country"));
                

        $table_name = 'zatcaCustomer';
        $data = array(
            'clientVendorNo'       => $client_No,
            'aName'                => $client_Name_Ar,
            'eName'                => $client_Name_En,
            'VATID'                 => $vat_Id,
            'secondBusinessIDType'  => $second_Business_Id_Type,
            'secondBusinessID'      => $second_Business_Id,
            'zatcaInvoiceType'      => $zatca_Invoice_Type,
            'apartmentNum'          => $apartment_No,
            'postalCode'            => $postal_code,
            'POBox'                 => $po_Box,
            'POBoxAdditionalNum'    => $po_Box_Additional_No,
            'street_Arb'            => $street_Name_Ar,
            'street_Eng'            => $street_Name_En,
            'district_Arb'          => $destrict_Name_Ar,
            'district_Eng'          => $destrict_Name_En,
            'city_Arb'              => $city_Name_Ar,
            'city_Eng'              => $city_Name_En,
            'countrySubdivision_Arb'=> $country_Sub_Name_Ar,
            'countrySubdivision_Eng'=> $country_Sub_Name_En,
            'country_No'            => $country,
            'country_Arb'           => $countryArab,
            'country_Eng'           => $countryEnglish
        );
        $where = array('clientVendorNo' => $current_client_no);
        $update_result = $wpdb->update($table_name, $data, $where);
    

        if ($update_result === false) {
            // There was an error inserting data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo __('Data Updated', 'zatca');
        }
       
    }

    die();
}

// AJax Delete From DB - customers:
function delete_customer_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $clientNo = $_REQUEST['client_no'];

        // Assuming your table is named 'wp_custom_table'
        $table_name = 'zatcaCustomer';

        // Define the condition to identify the row(s) to delete
        $where = array(
            'clientVendorNo' => $clientNo 
        );
        
        // Execute the delete query
        $deleted = $wpdb->delete( $table_name, $where );

        if ( $deleted === false ) {

            // Deletion failed
            echo _e('Failed to delete Customer.', 'zatca');

        }else{

            // Deletion successful
            echo _e('Customer deleted successfully.', 'zatca');
 
        }
    }

    die();
}

// AJax Insert_Data to DB - Devices:
function insert_form_devices(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['insert_form_ajax_data'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $device_No = $form_array['device-no'];
        $device_Csid = $form_array['device-csid'];
        $csid_Ex_Date = $form_array['csid-ex-date'];
        $token_Data = $form_array['token-data'];
        $deviceStatus = $form_array['deviceStatus'];

        // check if exist deviceStatus = 0 in zatcaDevice table or not
        $check_deviceStatus = $wpdb->get_results("SELECT * FROM zatcaDevice WHERE deviceStatus = 0");
        // if exist deviceStatus = 0 in zatcaDevice table
        if ($check_deviceStatus && $deviceStatus == 0) {
            
        $send_response = [
            'active' => true,
            'msg' => '',
            'error' => false
        ];
            //echo __("Not allowed to add more one device active", "zatca");
        }
        else
        {
            $insert_result = $wpdb->insert(
                'zatcaDevice',
                [
                    'deviceCSID'            => $device_Csid,
                    'CsID_ExpiryDate'       => $csid_Ex_Date,
                    'tokenData'             => $token_Data,
                    'deviceStatus'             => $deviceStatus
                ]
            );
    
            if ($insert_result === false) {
                // There was an error inserting data
                $error_message = $wpdb->last_error;
                $send_response = [
                    'active' => false,
                    'msg' => 'Error inserting data:' . $error_message,
                    'error' => true
                ];
                //echo "Error inserting data: " . $error_message;
            } else {
    
                $send_response = [
                    'active' => false,
                    'msg' => 'New Device Inserted',
                    'error' => false
                ];
                //echo __("New Device Inserted", "zatca");
            }
        }

        wp_send_json($send_response);

    }

    die();
}

// AJax Edit_data in DB - devices:
function edit_form_device(){
    
    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['edit_form_ajax_data'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $device_No_id = $form_array['device_no_id'];
        $device_No = $form_array['device-no'];
        $device_Csid = $form_array['device-csid'];
        $csid_Ex_Date = $form_array['csid-ex-date'];
        $token_Data = $form_array['token-data'];
        $deviceStatus = $form_array['deviceStatus'];


        // Validation on deviceNo If Used in zatcaDocument:
        $zatcaDocDeviceNo = $wpdb->get_var($wpdb->prepare("SELECT deviceNo FROM zatcaDocument WHERE deviceNo = $device_No_id"));

        if($zatcaDocDeviceNo != NULL){

            
            $deviceData = $wpdb->get_results("SELECT * FROM zatcaDevice WHERE deviceNo = $device_No_id");
            foreach($deviceData as $data){

                // Convert DateTime Format to Validate:
                $dbDate = new DateTime($data->CsID_ExpiryDate);
                $formDate = new DateTime($csid_Ex_Date);
                $dbFinalDate = $dbDate->format('Y-m-d');
                $formFinalDate = $formDate->format('Y-m-d');

               if( //Check If edit in Device No.: || Cryptographic Stamp ID || Expiry Date: || Token Data STOP EDIT:
                    $data->deviceNo != $device_No || 
                    $data->deviceCSID != $device_Csid ||
                    $dbFinalDate != $formFinalDate ||
                    $data->tokenData != $token_Data )
                {
                    $send_response = [
                        'active' => false,
                        'msg' => 'Sorry Cant Edit..Please Contact Your System Admin',
                        'error' => true
                    ];

                    //echo __("Sorry Cant Edit..Please Contact Your System Admin", "zatca");
                    
               }
               else{ // Update Device Status Only:

                    // check if exist deviceStatus = 0 in zatcaDevice table or not
                    $check_deviceStatus = $wpdb->get_results("SELECT * FROM zatcaDevice WHERE deviceStatus = 0");
                    // if exist deviceStatus = 0 in zatcaDevice table
                    if ($check_deviceStatus && $deviceStatus == 0) {
                        $send_response = [
                            'active' => true,
                            'msg' => 'Not allowed to add more one device active',
                            'error' => false
                        ];
                        //echo __("Not allowed to add more one device active", "zatca");
                    }
                    else
                    { // Update Device Status Only:
                        $table_name = 'zatcaDevice';
                        $data = array(
                            'deviceStatus' => $deviceStatus
                        );
                        $where = array('deviceNo' => $device_No_id);
                        $update_result = $wpdb->update($table_name, $data, $where);
                    
                
                        if ($update_result === false) {
                            // There was an error inserting data
                            $error_message = $wpdb->last_error;
                            $send_response = [
                                'active' => false,
                                'msg' => 'Error inserting data: ' . $error_message,
                                'error' => true
                            ];
                            //echo "Error inserting data: $error_message";
                        } else {
                            $send_response = [
                                'active' => false,
                                'msg' => 'Device Status Updated',
                                'error' => false
                            ];
                            //echo __("Device Status Updated", "zatca");
                        }
                    }
                    
                    
               }

            }
        }
        else
        { 
            // Update Device Data if Not Used in zatcaDocument:

            // check if exist deviceStatus = 0 in zatcaDevice table or not
            $check_deviceStatus = $wpdb->get_results("SELECT * FROM zatcaDevice WHERE deviceStatus = 0");
            // if exist deviceStatus = 0 in zatcaDevice table
            if (!empty($check_deviceStatus) && $deviceStatus == 0) {
                $send_response = [
                    'active' => true,
                    'msg' => 'Not allowed to add more one device active',
                    'error' => false
                ];
                //echo __("Not allowed to add more one device active", "zatca");
            }
            else
            {
                $table_name = 'zatcaDevice';
                $data = array(
                    'deviceNo'      => $device_No,
                    'deviceCSID'    => $device_Csid,
                    'CsID_ExpiryDate'=> $csid_Ex_Date,
                    'tokenData'     => $token_Data,
                    'deviceStatus'  => $deviceStatus
                );
                $where = array('deviceNo' => $device_No_id);
                $update_result = $wpdb->update($table_name, $data, $where);
                $error_message='';
        
                if ($update_result === false) {
                    // There was an error inserting data
                    $error_message = $wpdb->last_error;
                    $send_response = [
                        'active' => false,
                        'msg' => 'Error inserting data:' .  $error_message,
                        'error' => true
                    ];
                    //echo "Error inserting data: $error_message";
                } else {
        
                    $send_response = [
                        'active' => false,
                        'msg' => 'Data Updated' .  $error_message,
                        'error' => false
                    ];
                    //echo __("Data Updated", "zatca");
                }
            }

            
        }
        
        wp_send_json($send_response);
    }

    die();
}

// AJax Delete From DB - device:
function delete_device_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $deviceNo = $_REQUEST['device-no'];

        // check if deviceNo exist in zatcaDocument table or not
        $device_No = $wpdb->get_var("SELECT deviceNo FROM zatcaDocument WHERE deviceNo = '$deviceNo'");

        if ($device_No == '') 
        {
            // Assuming your table is named 'wp_custom_table'
            $table_name = 'zatcaDevice';

            // Define the condition to identify the row(s) to delete
            $where = array('deviceNo' => $deviceNo);

            // Execute the delete query
            $deleted = $wpdb->delete( $table_name, $where );

            if ( $deleted === false ) 
            {
                // Deletion failed
                $send_response = [
                    'status' => 400,
                    'msg' => __("Failed to delete device.","zatca")
                ];
                //echo __("Failed to delete device.","zatca");

            } 
            else 
            {
                // Deletion successful
                $send_response = [
                    'status' => 200,
                    'msg' => __("Device deleted successfully.","zatca")
                ];
                //echo __("Device deleted successfully.","zatca");
            }
        }
        else
        {
            // echo error
            // Deletion failed
            $send_response = [
                'status' => 404,
                'msg' => __("Failed to delete device because there are documents related to this device.","zatca")
            ];
            //echo __("Failed to delete device because there are documents related to this device.","zatca");
        }
        
        //AJax Data:
        wp_send_json($send_response);
        
    }

    die();
}

// AJax Delete From DB - user:
function delete_user_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $userNo = $_REQUEST['user-no'];

        $table_name = 'zatcaUser';

        // Define the condition to identify the row(s) to delete
        $where = array(
            'personNo' => $userNo
        );
        
        
        // Execute the delete query
        $deleted = $wpdb->delete( $table_name, $where );
        
        if ( $deleted === false ) {

            // Deletion failed:
            echo __("Failed to delete User","zatca");

        } else {

            // Deletion successful
            echo __("User deleted successfully","zatca");
        }
    }

    die();
}


// Recived the vat cat code data and get name from database - company:
function company_vat_cat_code_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['vat_cat_code_ajax'];

        $subCategories = $wpdb->get_results( "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeNo = $vals" );
        foreach($subCategories as $subCat) {?>
            
            <option 
                value="<?php echo $subCat->VATCategoryCodeSubTypeNo ?>" 
                <?php if($vals == $subCat->VATCategoryCodeSubTypeNo){ echo 'selected';} ?> >
                <?php echo $subCat->aName. ' - ' . $subCat->eName ?>
            </option>
            <?php
        }
       
    }

    die();
}

// AJax Submit in DB - company:
function submit_form_company(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $status = $_REQUEST['Status'];
        $vals = $_REQUEST['form_data_ajax_company'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $id = $form_array['id'];
        $zatca_Stage = $form_array['zatca-stage'];
        $second_Business_Id_Type = $form_array['second-business-id-type'];
        $second_Business_Id = $form_array['second-business-id'];
        $vat_Cat_Code = $form_array['vat-cat-code'];
        $vat_Cat_Code_Sub_No = $form_array['vat-cat-code-sub-no'];
        $vat_Id = $form_array['vat-id'];
        $aName = $form_array['name'];
        $apartment_No = $form_array['apartment-no'];
        $postal_code = $form_array['postal-code'];
        $po_Box = $form_array['po-box'];
        $po_Box_Additional_No = $form_array['po-box-additional-no'];
        $street_Name_Ar = $form_array['street-name-ar'];
        $street_Name_En = $form_array['street-name-en'];
        $district_Name_Ar = $form_array['district-name-ar'];
        $district_Name_En = $form_array['district-name-en'];
        $city_Name_Ar = $form_array['city-name-ar'];
        $city_Name_En = $form_array['city-name-en'];
        $country_Sub_Name_Ar = $form_array['country-sub-name-ar'];
        $country_Sub_Name_En = $form_array['country-sub-name-en'];
        $country = $form_array['country'];
        $branch_no = $form_array['branch-no'];
        $branch_device = $form_array['device'];
        $zatca_interval = $form_array['zatca-interval'];
        $zatca_invoice_type = $form_array['zatca-invoice-type'];
        
        // Get country_arb & country_eng Depend On Country Choosing:
        $countryArab = $wpdb->get_var($wpdb->prepare("SELECT arabic_name FROM country WHERE country_id = $country"));
        $countryEnglish = $wpdb->get_var($wpdb->prepare("SELECT english_name FROM country WHERE country_id = $country"));
        

        // Check If Insert:
        if($status == 'Insert'){

            // $wpdb->show_errors();
            // insert zatcaCompany data:
            $insert_company = $wpdb->insert(
                'zatcaCompany',
                [
                    'zatcaStage'                => $zatca_Stage,
                    'secondBusinessIDType'      => $second_Business_Id_Type,
                    'secondBusinessID'          => $second_Business_Id,
                    'VATCategoryCode'           => $vat_Cat_Code,
                    'VATCategoryCodeSubTypeNo'  => $vat_Cat_Code_Sub_No,
                    'VATID'                     => $vat_Id,
                    'companyName'                     => $aName,
                    'apartmentNum'              => $apartment_No,
                    'postalCode'                => $postal_code,
                    'POBox'                     => $po_Box,
                    'POBoxAdditionalNum'        => $po_Box_Additional_No,
                    'street_Arb'                => $street_Name_Ar,
                    'street_Eng'                => $street_Name_En,
                    'district_Arb'              => $district_Name_Ar,
                    'district_Eng'              => $district_Name_En,
                    'city_Arb'                  => $city_Name_Ar,
                    'city_Eng'                  => $city_Name_En,
                    'countrySubdivision_Arb'    => $country_Sub_Name_Ar,
                    'countrySubdivision_Eng'    => $country_Sub_Name_En,
                    'countryNo'                 => $country, 
                    'country_Arb'               => $countryArab,
                    'country_Eng'               => $countryEnglish
                ]
            );

            // $wpdb->print_error();
            // insert zatcaBranch data:
            $insert_branch = $wpdb->insert(
                'zatcaBranch',
                [
                    'deviceID'                  => $branch_device,
                    'zatcaStage'                => $zatca_Stage,
                    'zatcaInvoiceType'          => $zatca_invoice_type,
                    'secondBusinessIDType'      => $second_Business_Id_Type,
                    'secondBusinessID'          => $second_Business_Id,
                    'VATCategoryCodeNo'           => $vat_Cat_Code,
                    'VATCategoryCodeSubTypeNo'  => $vat_Cat_Code_Sub_No,
                    'apartmentNum'              => $apartment_No,
                    'POBox'                     => $po_Box,
                    'POBoxAdditionalNum'        => $po_Box_Additional_No,
                    'street_Arb'                => $street_Name_Ar,
                    'street_Eng'                => $street_Name_En,
                    'district_Arb'              => $district_Name_Ar,
                    'district_Eng'              => $district_Name_En,
                    'city_Arb'                  => $city_Name_Ar,
                    'city_Eng'                  => $city_Name_En,
                    'countrySubdivision_Arb'    => $country_Sub_Name_Ar,
                    'countrySubdivision_Eng'    => $country_Sub_Name_En,
                    'countryNo'                 => $country, 
                    'country_Arb'               => $countryArab,
                    'country_Eng'               => $countryEnglish,
                    'ZATCA_B2C_SendingIntervalType' => $zatca_interval,
                ]
            );

            if ($insert_company === false || $insert_branch === false) {
                // There was an error inserting data
                $error_message = $wpdb->last_error;
                echo "Error inserting data: $error_message";
            } else {

                echo _e('Data Inserted', 'zatca');
            }


        }else{ // If Update:

            // Update zatcaCompany:
            $table_name = 'zatcaCompany';
            $data = array(
                'zatcaStage'                        => $zatca_Stage,
                'secondBusinessIDType'              => $second_Business_Id_Type,
                'secondBusinessID'                  => $second_Business_Id,
                'VATCategoryCode'                   => $vat_Cat_Code,
                'VATCategoryCodeSubTypeNo'          => $vat_Cat_Code_Sub_No,
                'VATID'                             => $vat_Id,
                'companyName'                             => $aName,
                'apartmentNum'                      => $apartment_No,
                'postalCode'                        => $postal_code,
                'POBox'                             => $po_Box,
                'POBoxAdditionalNum'                => $po_Box_Additional_No,
                'street_Arb'                        => $street_Name_Ar,
                'street_Eng'                        => $street_Name_En,
                'district_Arb'                      => $district_Name_Ar,
                'district_Eng'                      => $district_Name_En,
                'city_Arb'                          => $city_Name_Ar,
                'city_Eng'                          => $city_Name_En,
                'countrySubdivision_Arb'            => $country_Sub_Name_Ar,
                'countrySubdivision_Eng'            => $country_Sub_Name_En,
                'countryNo'                         => $country,
                'country_Arb'                       => $countryArab,
                'country_Eng'                       => $countryEnglish,
            );
            $where = array('companyNo' => $id);
            $update_zatcaCompany = $wpdb->update($table_name, $data, $where);
            
            
            // Update zatcaBranch:
            $table_name = 'zatcaBranch';
            $data = array(
                'deviceID'                  => $branch_device,
                'zatcaStage'                => $zatca_Stage,
                'zatcaInvoiceType'          => $zatca_invoice_type,
                'secondBusinessIDType'      => $second_Business_Id_Type,
                'secondBusinessID'          => $second_Business_Id,
                'VATCategoryCodeNo'           => $vat_Cat_Code,
                'VATCategoryCodeSubTypeNo'  => $vat_Cat_Code_Sub_No,
                'apartmentNum'              => $apartment_No,
                'POBox'                     => $po_Box,
                'POBoxAdditionalNum'        => $po_Box_Additional_No,
                'street_Arb'                => $street_Name_Ar,
                'street_Eng'                => $street_Name_En,
                'district_Arb'              => $district_Name_Ar,
                'district_Eng'              => $district_Name_En,
                'city_Arb'                  => $city_Name_Ar,
                'city_Eng'                  => $city_Name_En,
                'countrySubdivision_Arb'    => $country_Sub_Name_Ar,
                'countrySubdivision_Eng'    => $country_Sub_Name_En,
                'countryNo'                 => $country, 
                'country_Arb'               => $countryArab,
                'country_Eng'               => $countryEnglish,
                'ZATCA_B2C_SendingIntervalType' => $zatca_interval,
            );
            $where = array('buildingNo' => $branch_no);
            $update_zatcaBranch = $wpdb->update($table_name, $data, $where);
        

            if ($update_zatcaCompany === false || $update_zatcaBranch === false) {
                // There was an error inserting data
                $error_message = $wpdb->last_error;
                echo "Error inserting data: $error_message";
            } else {

                echo _e('Data Updated', 'zatca');
            }
        
        }

    }

    die();
}

// AJax Edit in DB - Company:
function woo_company(){

    global $wpdb;


    $table_usermeta = $wpdb->prefix . 'options';

    // Get Data from wp_options For address:
    $address = $wpdb->get_var($wpdb->prepare("select option_value from $table_usermeta WHERE option_name ='woocommerce_store_address'"));
    // Get Data from wp_options For address 2:
    $address_2 = $wpdb->get_var($wpdb->prepare("select option_value from $table_usermeta WHERE option_name ='woocommerce_store_address_2'"));

    // Get Data from wp_options For city:
    $city = $wpdb->get_var($wpdb->prepare("select option_value from $table_usermeta WHERE option_name ='woocommerce_store_city'"));
    // Get Data from wp_options For Postal code:
    $postal_code = $wpdb->get_var($wpdb->prepare("select option_value from $table_usermeta WHERE option_name ='woocommerce_store_postcode'"));
    // Return the fetched data

    $response = array(
        'address'       => $address,
        'address_2'     => $address_2,
        'city'          => $city,
        'postal_code'   => $postal_code
    );

    // Return the array as JSON
    wp_send_json($response);


    die();
}

// Send Data as Ajax - document [ Update input in insert new page ]:
function document_data_ajax(){

    if (isset($_GET['page']) && $_GET['page'] ==='zatca-documents' && isset($_GET['action']) && $_GET['action'] === 'insert') {

        // Output your script
        wp_add_inline_script("jquery", "
        (function($) {
            $(document).ready(function() {

                // Update exemption Reason on page load:
                updateInput();


            });
        })(jQuery);

        ");

    }

}

function round_up_five_cents($number) {  
    if ($number < 0) {
        return floor($number * 20) / 20;
    } else {
        return ceil($number * 20) / 20;
    }
    //return ceil($number * 20) / 20;   
}

// AJax Edit in DB - devices:
function woo_document(){

    // AJax Data:
    $orderId = $_REQUEST['woo_order_id'];
    $vatCat = $_REQUEST['vat_cat_code'];

    global $wpdb;


    $table_orders = $wpdb->prefix . 'wc_orders';

    // Get customer id from wc_orders For address:
    $customerId = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $orderId "));
   
    // Get order status from wc_orders:
    $order_status = $wpdb->get_var($wpdb->prepare("select status from $table_orders WHERE id = $orderId "));

    // Get Payed from wc_orders For address:
    $payed = $wpdb->get_var($wpdb->prepare("select total_amount from $table_orders WHERE id = $orderId "));
    
    // Get Payed from wc_orders For address:
    $totalTax = $wpdb->get_var($wpdb->prepare("select tax_amount from $table_orders WHERE id = $orderId "));

    // Get order-item_id from woocommerce_order_items:
    $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';

    // Get Order Id for Specific Item_Id [ coupon ]:
    $item_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));

    // Get Order Id for Specific Item_Id [ line_item ]:
    $item_id_line_item = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));

    // Get Discount from woo_order_itemmeta:
    $discount = wc_get_order_item_meta( $item_id, 'discount_amount', true );

    
    // Get Sub Net Total from woo_order_itemmeta:
    // $sub_net_total = wc_get_order_item_meta( $item_id_line_item, '_line_total', true );
    $sub_net_total = $payed - $totalTax;
    
    // Get Sub Total from woo_order_itemmeta:
    // $sub_total = wc_get_order_item_meta( $item_id_line_item, '_line_subtotal', true );
    $sub_total = $sub_net_total - $discount;
    
    // payed (visa):
    $payedVisa = 0;
    
    // payed (bank):
    $payedBank = 0;
    
    // total payed:
    $totalPayed = $payed + $payedVisa + $payedBank;

    // left amount:
    $leftAmount = $payed - $totalPayed;

    $customerVatId = $wpdb->get_var($wpdb->prepare("SELECT VATID FROM zatcaCustomer WHERE clientVendorNo = $customerId"));
    $invoiceType = $wpdb->get_var($wpdb->prepare("select zatcaInvoiceType from zatcaCustomer where clientVendorNo = $customerId"));
    
    $invoiceTypeCode = 0;

    if($invoiceType === 'B2C'){

        $invoiceTypeCode = 0;

    }elseif($invoiceType === 'B2B'){

        $invoiceTypeCode = 1;
    }else{

        // Check if customer have vatId = 1 [ B2B ] if not = 0 [ B2C ]:
        if($customerVatId === ''){

            $invoiceTypeCode = 0;

        }else{

            $invoiceTypeCode = 1;
        }
    }

    // Check if Discount Exists or not:
    if(! empty($discount)){

        $dicount_value  = $discount;
        $discount_percentage = ($discount / $sub_total) * 100;
        

    }else{

        $dicount_value = 0;
        $discount_percentage = 0;
    }

    // vatCatCodeSubType:
    $subCategories = $wpdb->get_results( "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeSubTypeNo  = $vatCat" );
    foreach($subCategories as $subCat) {
        
        $vatCatName = $subCat->aName;
    }

    //////// get documentUnits lines: //////////////////////////////////////////////////////////////

    // #####################################
    // Add Data to zatcaDocumentUnit_array:#
    // #####################################

    // Initialize an empty array to store zatcaDocumentUnit data  
    $zatcaDocumentUnit_array = [];
    $zatcaSeller_array = [];
    $zatcaBuyer_array = [];

    // Prifix of wp_wc_orders:
    $table_orders = $wpdb->prefix . 'wc_orders';
        
    // get customer id from order table:
    $order_Customer_Id = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $orderId"));

    ///////////////Buyer Info//////////////////////
    // get aName from zatcaCustomers:
    $buyer_aName_Customer = $wpdb->get_var($wpdb->prepare("select aName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get eName from zatcaCustomers:
    $buyer_eName_Customer = $wpdb->get_var($wpdb->prepare("select eName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get apartmentNum from zatcaCustomers:
    $buyer_apartmentNum_Customer = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get street_Arb from zatcaCustomers:
    $buyer_street_Arb_Customer = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get street_Eng from zatcaCustomers:
    $buyer_street_Eng_Customer = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get district_Arb from zatcaCustomers:
    $buyer_district_Arb_Customer = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get district_Eng from zatcaCustomers:
    $buyer_district_Eng_Customer = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get city_Arb from zatcaCustomers:
    $buyer_city_Arb_Customer = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get city_Arb from zatcaCustomers:
    $buyer_city_Eng_Customer = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get CountryNo from zatcaCustomers:
    $buyer_arCountry_Customer = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    $buyer_enCountryNo_Customer = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
    
    // get buyer vat from zatcaCustomer
    $buyer_Postal_Code = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

    // get buyer vat from zatcaCustomer
    $buyer_POBoxAdditionalNum = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

    // get buyer vat from zatcaCustomer
    $buyer_VAT = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

    // get buyer vat from zatcaCustomer
    $buyer_SecondBusinessID = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

    
    $zatcaBuyer_array = [];

    $zatcaBuyer_array = 
    [
        'buyer_aName_Customer' => $buyer_aName_Customer,
        'buyer_eName_Customer' => $buyer_eName_Customer,
        'buyer_apartmentNum_Customer' => $buyer_apartmentNum_Customer,
        'buyer_street_Arb_Customer' => $buyer_street_Arb_Customer,
        'buyer_street_Eng_Customer' => $buyer_street_Eng_Customer,
        'buyer_district_Arb_Customer' => $buyer_district_Arb_Customer,
        'buyer_district_Eng_Customer' => $buyer_district_Eng_Customer,
        'buyer_city_Arb_Customer' => $buyer_city_Arb_Customer,
        'buyer_city_Eng_Customer' => $buyer_city_Eng_Customer,
        'buyer_arCountry_Customer' => $buyer_arCountry_Customer,
        'buyer_enCountry_Customer' => $buyer_enCountryNo_Customer,
        'buyer_Postal_Code' => $buyer_Postal_Code,
        'buyer_POBoxAdditionalNum' => $buyer_POBoxAdditionalNum,
        'buyer_VAT' => $buyer_VAT,
        'buyer_SecondBusinessID' => $buyer_SecondBusinessID
    ];
    ///////////////Seller Info/////////////////////////////

    // Get Seller Vat from zatcaCompany [ seller ]:
    $seller_Name = $wpdb->get_var($wpdb->prepare("select companyName from zatcaCompany"));

    // Get Seller apartment from zatcaCompany [ seller ]:
    $seller_apartmentNum_Company = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCompany"));

    // Get Seller street_Arb from zatcaCompany [ seller ]:
    $seller_street_Arb_Company = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCompany"));
    
    // Get Seller street_Eng from zatcaCompany [ seller ]:
    $seller_street_Eng_Company = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCompany"));

    // Get Seller district_Arb from zatcaCompany [ seller ]:
    $seller_district_Arb_Company = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCompany"));
    
    // Get Seller district_Eng from zatcaCompany [ seller ]:
    $seller_district_Eng_Company = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCompany"));

    // Get Seller city_Arb from zatcaCompany [ seller ]:
    $seller_city_Arb_Company = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCompany"));
    
    // Get Seller city_Eng from zatcaCompany [ seller ]:
    $seller_city_Eng_Company = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCompany"));

    // Get Seller country_Arb from zatcaCompany [ seller ]:
    $seller_country_Arb_Company = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcaCompany"));
    
    // Get Seller country_Eng from zatcaCompany [ seller ]:
    $seller_country_Eng_Company = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcaCompany"));

    // Get Seller postal code from zatcaCompany:
    $seller_postalCode = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCompany"));

    // Get Seller POBoxAdditionalNum from zatcaCompany [ seller ]:
    $seller_POBoxAdditionalNum_Company = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCompany"));

    // Get Seller Vat from zatcaCompany [ seller ]:
    $seller_VAT_Company = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCompany"));
    
    // Get Seller POBox from zatcaCompany [ seller ]:
    $seller_secondBusinessID = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCompany"));

    $zatcaSeller_array = [];
    $zatcaSeller_array = 
    [
        'seller_Name' => $seller_Name,
        'seller_apartmentNum_Company' => $seller_apartmentNum_Company,
        'seller_street_Arb_Company' => $seller_street_Arb_Company,
        'seller_street_Eng_Company' => $seller_street_Eng_Company,
        'seller_district_Arb_Company' => $seller_district_Arb_Company,
        'seller_district_Eng_Company' => $seller_district_Eng_Company,
        'seller_city_Arb_Company' => $seller_city_Arb_Company,
        'seller_city_Eng_Company' => $seller_city_Eng_Company,
        'seller_country_Arb_Company' => $seller_country_Arb_Company,
        'seller_country_Eng_Company' => $seller_country_Eng_Company,
        'seller_postalCode' => $seller_postalCode,
        'seller_POBoxAdditionalNum_Company' => $seller_POBoxAdditionalNum_Company,
        'seller_VAT_Company' => $seller_VAT_Company,
        'seller_secondBusinessID' => $seller_secondBusinessID
    ];

    
    // get item id for item [ line_item ]:
    $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));

    // get item id for tax [ tax ]:
    $doc_unit_item_id_rate_percentage = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'tax'"));

    // get item id for discount [ tax ]:
    $doc_unit_item_id_coupon = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
    

    // Funtion to handle order discount:
    function get_qty_percentage_for_item($orderId)
    {

        global $wpdb;
        // Prefix Of woo-order-item
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
    
        
        // define items number & Number of Items & Item Qty & Total Qty Of Order:
        
        $total_order_qty = 0;
    
        $array_items = [];
        
        $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
        
        foreach($doc_unit_item_id as $itemId)
        {
    
            $item_qty = wc_get_order_item_meta( $itemId->order_item_id , '_qty', true );
    
            $order_discount_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
    
            $order_discount = wc_get_order_item_meta($order_discount_id, 'discount_amount', true);
            //get item price
            $item_price = wc_get_order_item_meta($itemId->order_item_id, '_line_subtotal', true);
            $totalPrice += $item_price;

            // push $item_price to array_items with 'order_price' key
            //array_push($array_items, ['order_price' => $item_price]);
            //array_push($array_items, ['order_qty' => $item_qty]);

            // push itemId-> order_item_id to array_items with key 'item'
            array_push($array_items, ['item' => $itemId->order_item_id, 'order_price' => $item_price, 'order_qty' => $item_qty]);

            
            //$array_items[$itemId->order_item_id] = $item_qty;
    
            //$total_order_qty +=  + $item_qty;
        }
    
        //$array_items['order_discount'] = $order_discount;
        //$array_items['total_order_qty'] = $total_order_qty;

        $discountPercentage = $order_discount / $totalPrice;
        // round to 2 decimal
        $discountPercentage = round($discountPercentage, 6);
        //$discountPercentage = 0.263;
        
        
        //define percentage of each item:
    
        $updated_total_qty = [];
    
        foreach ($array_items as $key) 
        {
            $item_discount = $key["order_price"] * $discountPercentage;
            //push item and discount to updated_total_qty array
            array_push($updated_total_qty, ['item' => $key['item'], 'discount' => $item_discount]);
            // push another key to array
           // array_push($updated_total_qty, ["discount" => $item_discount]);

            //if (is_numeric($key)) 
            // {  // Check for integer keys
            //     //$updated_total_qty[$key] = $value / $array_items["total_order_qty"] * $array_items["order_discount"];
            //     $updated_total_qty[$key] = $array_items["order_qty"] * $array_items["order_price"] * $discountPercentage;
            // }
        }
    
        // Now $updated_total_qty will contain the updated quantities for items with numeric keys
        return $updated_total_qty;
        
    }

    foreach($doc_unit_item_id as $item)
    {

        
        // Item Qty:
        $doc_unit_item_qty = wc_get_order_item_meta( $item->order_item_id , '_qty', true );
        
        // Product_id:
        $doc_unit_product_id =wc_get_order_item_meta($item->order_item_id, '_product_id', true);
        
        // Tax Percentage:
        $doc_unit_vatRate =wc_get_order_item_meta($doc_unit_item_id_rate_percentage, 'rate_percent', true);

        // Item name:
        $doc_unit_sku = $wpdb->get_var($wpdb->prepare("select order_item_name from $table_orders_items WHERE order_item_id='$item->order_item_id' AND order_id = $orderId AND order_item_type = 'line_item'"));
        
        // Item Price:
        $doc_unit_price = get_post_meta($doc_unit_product_id, '_price', true);
        $final_price = number_format((float)$doc_unit_price, 6, '.', '');

        // Discount:
        $doc_unit_discount = wc_get_order_item_meta($doc_unit_item_id_coupon, 'discount_amount', true);

        // Subttotal [ price * quantity ]:
        $doc_unit_subtotal = $doc_unit_price * $doc_unit_item_qty;


        // Get the Function of define discount by line:
        $array_of_discounts = get_qty_percentage_for_item($orderId);

        

        // Loop to get Each Item Discount:
        foreach($array_of_discounts as $key)
        {
    
            //if($key['item'] == $item->order_item_id)
            if($key['item'] == $item->order_item_id)
            {

                //$final_item_discount= number_format((float)$array_of_discounts[$key], 3, '.', '');
                $final_item_discount= round($key['discount'], 2);

                // netAmount [ ((price * quantity)-discount) ]:
                $doc_unit_netAmount = $doc_unit_subtotal - $final_item_discount;
                $final_netAmount = round($doc_unit_netAmount, 2);
                //$final_netAmount = number_format((float)$doc_unit_netAmount, 2, '.', '');

                // vatAmount [ netAmount*vatRate ]:
                $doc_unit_vatAmount = $final_netAmount * ($doc_unit_vatRate / 100);
                // $final_vatAmount = number_format((float)$doc_unit_vatAmount, 2, '.', '');
                $final_vatAmount = round($doc_unit_vatAmount, 1);
                

                // amountWithVat [ netAmount+vatAmount ]:
                $doc_unit_amountWithVat = $final_netAmount + $final_vatAmount;
                //$final_amountWithVat = number_format((float)$doc_unit_amountWithVat, 2, '.', '');
                $final_amountWithVat = round($doc_unit_amountWithVat, 2);
            

                // Prepare zatcaDocumentUnit data  
                $zatcaDocumentUnit_data = 
                [  
                    'itemNo'        => $item->order_item_id,  
                    'eName'         => $doc_unit_sku,  
                    'price'         => $final_price,  
                    'quantity'      => $doc_unit_item_qty,  
                    'discount'      => $final_item_discount,  
                    'vatRate'       => $doc_unit_vatRate,  
                    'vatAmount'     => $final_vatAmount,  
                    'netAmount'     => $final_netAmount,  
                    'amountWithVAT' => $final_amountWithVat
                ];

                // Push the zatcaDocumentUnit data to the array  
                $zatcaDocumentUnit_array[] = $zatcaDocumentUnit_data;
            }
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////



    // Return the fetched data
    $response = array(
        'payed'         => number_format($payed, 2, '.', ''),
        'discount'      => $dicount_value,
        'subNetTotal'   => $sub_net_total,
        'subTotal'      => $sub_total,
        'vatCatName'   => $vatCatName,
        'discountPercentage' => round($discount_percentage, 2),
        'invoiceTypeCode' => $invoiceTypeCode,
        'payedVisa' => $payedVisa,
        'payedBank' => $payedBank,
        'totalPayed' => number_format($totalPayed, 2, '.', ''),
        'totalTax' => $totalTax,
        'leftAmount' =>$leftAmount,
        'zatca_document_unit_lines' => $zatcaDocumentUnit_array,
        'zatcaBuyer_array' => $zatcaBuyer_array,
        'zatcaSeller_array' => $zatcaSeller_array,
        'order_status' => $order_status
    );

    // Return the array as JSON
    wp_send_json($response);


    die();
}

// AJax Insert_Data to DB - Documents:
function insert_form_documents(){

    if(isset($_REQUEST)){

        // AJax Data:
        $docs = $_REQUEST['insert_form_ajax_documents'];
        // Parse Data:
        parse_str($docs, $form_array);

        $orderId = $form_array['invoice-no'];

        global $wpdb;

        // Get Device No from zatcaDevice:

        // Table Name:
        $table_name_device = 'zatcaDevice';

        // Prepare the query with a condition on CsID_ExpiryDate not expire:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW() AND deviceStatus=0") );
        foreach($results as $device){

            if($wpdb->num_rows > 0){ // If Date Valid:

                $deviceNo = $device->deviceNo;

            }else{ // If No Date Valid

                $send_response = [
                    'status' => 'expired'
                ];
                // $msg = "You Must Insert Valid CsID_ExpiryDate";

            }
        }

        // Prefix Of woo-order-item
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
        
        // prefix of postmeta
        $table_post_meta = $wpdb->prefix . 'wp_postmeta';

        // Get Order Id for Specific Item_Id [ line_item ]:
        $item_id_line_item = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));

        // Get Sub  Total from woo_order_itemmeta:
        $sub_total = wc_get_order_item_meta( $item_id_line_item, '_line_subtotal', true );
        
        // Get Sub Net Total from woo_order_itemmeta:
        $sub_net_total = wc_get_order_item_meta( $item_id_line_item, '_line_total', true );
        
        // Check For Document no in zatcaDocument [Previouse Doc No]:
        $documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaDocument") );

        $previuos_docNo = 0;
        $previousInvoiceHash = '';
        
        // Ceheck for previous document No if have doc no will choose it - 1 & previousInvoiceHash will be the value in database:
        if($wpdb->num_rows > 0){
            foreach($documents as $document){
            
                $previuos_docNo = $document->documentNo;
                $previousInvoiceHash = $document->previousInvoiceHash;

            }
        }else{ // if not previuous doc no will be 0 - and previousInvoiceHash will be default:
            
            $previuos_docNo=0;
            $previousInvoiceHash = "NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==";
        }
        
        // Get seconde bussiness id type from zatcaCompany [ seller ]:
        $seller_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcaCompany"));
       
        // Get seconde bussiness id from zatcaCompany [ seller ]:
        $seller_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCompany"));
        
        // Prifix of wp_wc_orders:
        $table_orders = $wpdb->prefix . 'wc_orders';
        
        // get customer id from order table:
        $order_Customer_Id = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $orderId"));
        
        // get buyer_secondBusinessIDType from zatcaCustomers:
        $buyer_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get buyer_secondBusinessID from zatcaCustomers:
        $buyer_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // Get Seller Vat from zatcaCompany [ seller ]:
        $seller_VAT_Company = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCompany"));
        
        // Get Seller apartment from zatcaCompany [ seller ]:
        $seller_apartmentNum_Company = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCompany"));
        
        // Get Seller POBoxAdditionalNum from zatcaCompany [ seller ]:
        $seller_POBoxAdditionalNum_Company = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCompany"));
        
        // Get Seller POBox from zatcaCompany [ seller ]:
        $seller_POBox_Company = $wpdb->get_var($wpdb->prepare("select POBox from zatcaCompany"));
        
        // Get Seller postal code from zatcaCompany:
        $seller_postalCode = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCompany"));
        
        // Get Seller street_Arb from zatcaCompany [ seller ]:
        $seller_street_Arb_Company = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCompany"));
        
        // Get Seller street_Eng from zatcaCompany [ seller ]:
        $seller_street_Eng_Company = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCompany"));
        
        // Get Seller district_Arb from zatcaCompany [ seller ]:
        $seller_district_Arb_Company = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCompany"));
        
        // Get Seller district_Eng from zatcaCompany [ seller ]:
        $seller_district_Eng_Company = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCompany"));
        
        // Get Seller city_Arb from zatcaCompany [ seller ]:
        $seller_city_Arb_Company = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCompany"));
        
        // Get Seller city_Eng from zatcaCompany [ seller ]:
        $seller_city_Eng_Company = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCompany"));
        
        // Get Seller country_Arb from zatcaCompany [ seller ]:
        $seller_country_Arb_Company = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcaCompany"));
        
        // Get Seller country_Eng from zatcaCompany [ seller ]:
        $seller_country_Eng_Company = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcaCompany"));
        
        // Get Seller CountryNo from zatcaCompany [ seller ]:
        $seller_CountryNo_Company = $wpdb->get_var($wpdb->prepare("select countryNo from zatcaCompany "));
        
        // get aName from zatcaCustomers:
        $buyer_aName_Customer = $wpdb->get_var($wpdb->prepare("select aName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get eName from zatcaCustomers:
        $buyer_eName_Customer = $wpdb->get_var($wpdb->prepare("select eName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get apartmentNum from zatcaCustomers:
        $buyer_apartmentNum_Customer = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get POBoxAdditionalNum from zatcaCustomers:
        $buyer_POBoxAdditionalNum_Customer = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get PostalCode from zatcaCustomers:
        $buyer_PoCode = $wpdb->get_var($wpdb->prepare("select POBox from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get PostalCode from zatcaCustomers:
        $buyer_PostalCode = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get street_Arb from zatcaCustomers:
        $buyer_street_Arb_Customer = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get street_Eng from zatcaCustomers:
        $buyer_street_Eng_Customer = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get district_Arb from zatcaCustomers:
        $buyer_district_Arb_Customer = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get district_Eng from zatcaCustomers:
        $buyer_district_Eng_Customer = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get city_Arb from zatcaCustomers:
        $buyer_city_Arb_Customer = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get city_Arb from zatcaCustomers:
        $buyer_city_Eng_Customer = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get CountryNo from zatcaCustomers:
        $buyer_CountryNo_Customer = $wpdb->get_var($wpdb->prepare("select country_No from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        $buyer_VAT = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        



        // Variables of data:
        $device_No = $deviceNo;
        $delivery_Date = $form_array['deliveryDate'];
        $latest_Delivery_Date = $form_array['gaztLatestDeliveryDate'];
        $note = $form_array['note'];
        $uuid = wp_generate_uuid4();

        // check if delivery date is null:
        if(empty($delivery_Date)){
            $delivery_Date = date('Y-m-d');
        }

        // check if latest_Delivery_Date  is null:
        if(empty($latest_Delivery_Date)){
            $latest_Delivery_Date = date('Y-m-d');
        }

        // Validation on CsID_ExpiryDate not expire:
        $device_ExpiryDate = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW() AND deviceStatus=0") );
        
        // Validation on zatcaCompany if have data [ insert doc ] - if not [ stop ]:
        $zatcaCompanyCheck =$wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaCompany") );

        if(empty($device_ExpiryDate)){ // If No Date Valid:

            $send_response = [
                'status' => 'expired'
            ];
            //$msg = __("Device CsID_ExpiryDate is Expired","zatca");

        }else if(empty($zatcaCompanyCheck)){

            $send_response = [
                'status' => 'no_zatcaCompany_data'
            ];

        }else{ // If Date Valid

            $deviceNo = $wpdb->get_var($wpdb->prepare(
                "SELECT deviceNo
                FROM $table_name_device
                WHERE deviceStatus = 0")
                );
            $query = $wpdb->prepare("SELECT IFNULL(MAX(documentNo), 0) FROM zatcaDocument WHERE deviceNo = $deviceNo");
            $docNo = $wpdb->get_var($query);
            $docNo = $docNo + 1;

            $branchNo = $wpdb->get_var($wpdb->prepare("SELECT buildingNo FROM zatcaBranch"));

            $returnReason = $form_array['returnReason'];
            $reason = $form_array['returnReasonType'];
            // #################################
            // Insert Data into zatcaDocument:##
            // #################################
            
            $insert_doc = $wpdb->insert(
                'zatcaDocument',
                [
                    'documentNo'                            => $docNo,
                    'deviceNo'                              => $deviceNo,
                    'invoiceNo'                             => $form_array['invoice-no'],
                    'buildingNo'                             => $branchNo,
                    'billTypeNo'                             => $form_array['billTypeNo'],
                    'deliveryDate'                          => $delivery_Date,
                    'gaztLatestDeliveryDate'                => $latest_Delivery_Date,
                    'zatcaInvoiceType'                      => $form_array['zatcaInvoiceType'],
                    'amountPayed01'                         => $form_array['amountPayed01'],
                    'amountPayed02'                         => $form_array['amountPayed02'],
                    'amountPayed03'                         => $form_array['amountPayed03'],
                    'amountCalculatedPayed'                 => $form_array['amountCalculatedPayed'],
                    'returnReasonType'                      => $returnReason,
                    'reason'                                => $reason,
                    'subTotal'                              => $form_array['subTotal'],
                    'subTotalDiscount'                      => $form_array['subTotalDiscount'],
                    'taxRate1_Total'                        => $form_array['taxRate1_Total'],
                    'subNetTotal'                           => $form_array['subNetTotal'],
                    'subNetTotalPlusTax'                    => $form_array['subNetTotalPlusTax'],
                    'amountLeft'                            => $form_array['amountLeft'],
                    'previousDocumentNo'                    => $previuos_docNo,
                    'previousInvoiceHash'                   => $previousInvoiceHash,
                    'seller_secondBusinessIDType'           => $seller_second_bussiness_Id_Type,
                    'seller_secondBusinessID'               => $seller_second_bussiness_Id,
                    'buyer_secondBusinessIDType'            => $buyer_second_bussiness_Id_Type,
                    'buyer_secondBusinessID'                => $buyer_second_bussiness_Id,
                    'VATCategoryCodeNo'                     => $form_array['insert-doc-vat-cat-code'],
                    'VATCategoryCodeSubTypeNo'              => $form_array['vat-cat-code-sub-no'],
                    'zatca_TaxExemptionReason'              => $form_array['taxExemptionReason'],
                    'zatcaInvoiceTransactionCode_isNominal' => $form_array['isNominal'],
                    'zatcaInvoiceTransactionCode_isExports' => $form_array['isExports'],
                    'zatcaInvoiceTransactionCode_isSummary' => $form_array['isSummary'],
                    'UUID'                                  => $uuid,
                    'seller_VAT'                            => $seller_VAT_Company,
                    'seller_apartmentNum'                   => $seller_apartmentNum_Company,
                    'seller_street_Arb'                     => $seller_street_Arb_Company,
                    'seller_street_Eng'                     => $seller_street_Eng_Company,
                    'seller_district_Arb'                   => $seller_district_Arb_Company,
                    'seller_district_Eng'                   => $seller_district_Eng_Company,
                    'seller_city_Arb'                       => $seller_city_Arb_Company,
                    'seller_city_Eng'                       => $seller_city_Eng_Company,
                    'seller_country_Arb'                    => $seller_country_Arb_Company,
                    'seller_country_Eng'                    => $seller_country_Eng_Company,
                    'seller_country_No'                     => $seller_CountryNo_Company,
                    'seller_POBox'                          => $seller_POBox_Company,
                    'seller_PostalCode'                     => $seller_postalCode,
                    'seller_POBoxAdditionalNum'             => $seller_POBoxAdditionalNum_Company,
                    'buyer_VAT'                             => $buyer_VAT,
                    'buyer_aName'                           => $buyer_aName_Customer,
                    'buyer_eName'                           => $buyer_eName_Customer,
                    'buyer_apartmentNum'                    => $buyer_apartmentNum_Customer,
                    'buyer_street_Arb'                      => $buyer_street_Arb_Customer,
                    'buyer_street_Eng'                      => $buyer_street_Eng_Customer,
                    'buyer_district_Arb'                    => $buyer_district_Arb_Customer,
                    'buyer_district_Eng'                    => $buyer_district_Eng_Customer,
                    'buyer_city_Arb'                        => $buyer_city_Arb_Customer,
                    'buyer_city_Eng'                        => $buyer_city_Eng_Customer,
                    'buyer_country_No'                      => $buyer_CountryNo_Customer,
                    'buyer_POBox'                           => $buyer_PoCode,
                    'buyer_PostalCode'                      => $buyer_PostalCode,
                    'buyer_POBoxAdditionalNum'              => $buyer_POBoxAdditionalNum_Customer,
                    'zatcaSuccessResponse'                  => 0
                ]
            );
    
            if ($insert_doc === false) {
                // There was an error inserting data:
                error_log('Insert error: ' . $wpdb->last_error);
                $send_response = [
                    'status' => 'error',
                    'msg' => 'Error inserting document' . $wpdb->last_error,
                ];
                //echo "Error inserting data: " . $wpdb->last_error;
            } else {
    
                // Get Last DocNo Inserted:
                $last_doc_no = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcaDocument"));
        
                if($last_doc_no === false) {
    
                    // Check For Error:
                    error_log('Get Last DocNo Error: ' . $wpdb->last_error);
                    $send_response = [
                        'status' => 'error',
                        'msg' => 'Error Get Last DocNo Inserted: ' . $wpdb->last_error,
                    ];
                    //echo "Error Get Last DocNo Inserted: " . $wpdb->last_error;
                }else {
    
                    // Get last DocNo in zatcaDocumentno:
                    $last_doc=0;
    
                    foreach($last_doc_no as $doc){
    
                        $last_doc = $doc->documentNo;
                        
                    }
                    
                    
                    /* ####################################
                    ## Insert Data into zatcaDocumentxml:##
                    */ ####################################
    
                        $data_doc_xml = $wpdb->insert(
                            'zatcaDocumentxml',
                            [
                                'documentNo'  => $docNo,
                                'deviceNo'    => $device_No
                            ]
                        );
    
                        // Validation of inserting zatcaDocumentxml:
                        if ($data_doc_xml === false) {
    
                            // Check For Error:
                            error_log('documentxml Error: ' . $wpdb->last_error);
                            $send_response = [
                                'status' => 'error',
                                'msg' => 'Error Inserting DocNo: ' . $wpdb->last_error,
                            ];
                            //echo "Error Inserting DocNo: " . $wpdb->last_error;
                        }
    
    
                    // ################################
                    // Add Data to zatcaDocumentUnit:##
                    // ################################
    
                    // get item id for item [ line_item ]:
                    $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
                    
                    // get item id for tax [ tax ]:
                    $doc_unit_item_id_rate_percentage = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'tax'"));
                
                    // get item id for discount [ tax ]:
                    $doc_unit_item_id_coupon = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
                    
    
                    // Funtion to handle order discount:
                    function get_qty_percentage_for_item($orderId){
    
                        global $wpdb;
                        // Prefix Of woo-order-item
                        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
                    
                        
                        // define items number & Number of Items & Item Qty & Total Qty Of Order:
                        
                        $total_order_qty = 0;
                    
                        $array_items = [];
                        
                        $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
                        
                        foreach($doc_unit_item_id as $itemId){
                    
                            $item_qty = wc_get_order_item_meta( $itemId->order_item_id , '_qty', true );
                    
                            $order_discount_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
                    
                            $order_discount = wc_get_order_item_meta($order_discount_id, 'discount_amount', true);
                            
                            //get item price
                            $item_price = wc_get_order_item_meta($itemId->order_item_id, '_line_subtotal', true);
                            $totalPrice += $item_price;

                            // push $item_price to array_items with 'order_price' key
                            //array_push($array_items, ['order_price' => $item_price]);
                            //array_push($array_items, ['order_qty' => $item_qty]);

                            // push itemId-> order_item_id to array_items with key 'item'
                            array_push($array_items, ['item' => $itemId->order_item_id, 'order_price' => $item_price, 'order_qty' => $item_qty]);

                            
                            //$array_items[$itemId->order_item_id] = $item_qty;
                    
                            //$total_order_qty +=  + $item_qty;
                    
                        }
                    
                        //$array_items['order_discount'] = $order_discount;
                        //$array_items['total_order_qty'] = $total_order_qty;

                        $discountPercentage = $order_discount / $totalPrice;
                        // round to 2 decimal
                        $discountPercentage = round($discountPercentage, 6);
                        //$discountPercentage = 0.263;
                    
                    
                        //define percentage of each item:
                    
                        $updated_total_qty = [];
                    
                        foreach ($array_items as $key) 
                        {
                            $item_discount = $key["order_price"] * $discountPercentage;
                            //push item and discount to updated_total_qty array
                            array_push($updated_total_qty, ['item' => $key['item'], 'discount' => $item_discount]);
                            // push another key to array
                        // array_push($updated_total_qty, ["discount" => $item_discount]);

                            //if (is_numeric($key)) 
                            // {  // Check for integer keys
                            //     //$updated_total_qty[$key] = $value / $array_items["total_order_qty"] * $array_items["order_discount"];
                            //     $updated_total_qty[$key] = $array_items["order_qty"] * $array_items["order_price"] * $discountPercentage;
                            // }
                        }
                    
                        // Now $updated_total_qty will contain the updated quantities for items with numeric keys
                        return $updated_total_qty;
                        
                    }
    
                    foreach($doc_unit_item_id as $item){
    
                        
                        // Item Qty:
                        $doc_unit_item_qty = wc_get_order_item_meta( $item->order_item_id , '_qty', true );
                        
                        // Product_id:
                        $doc_unit_product_id =wc_get_order_item_meta($item->order_item_id, '_product_id', true);
                        
                        // Tax Percentage:
                        $doc_unit_vatRate =wc_get_order_item_meta($doc_unit_item_id_rate_percentage, 'rate_percent', true);
    
                        // Item name:
                        $doc_unit_sku = get_post_meta($doc_unit_product_id, '_sku', true);
                        
                        // Item Price:
                        $doc_unit_price = get_post_meta($doc_unit_product_id, '_price', true);
                        $final_price = number_format((float)$doc_unit_price, 6, '.', '');
    
                        // Discount:
                        $doc_unit_discount = wc_get_order_item_meta($doc_unit_item_id_coupon, 'discount_amount', true);
    
                        // Subttotal [ price * quantity ]:
                        $doc_unit_subtotal = $doc_unit_price * $doc_unit_item_qty;
    

                        // Get the Function of define discount by line:
                        $array_of_discounts = get_qty_percentage_for_item($orderId);
    
                        // Loop to get Each Item Discount:
                        foreach($array_of_discounts as $key){
    
                            if($key['item'] == $item->order_item_id){
    
                                //$final_item_discount= number_format((float)$array_of_discounts[$key], 3, '.', '');
                                $final_item_discount= round($key['discount'], 2);


                                // netAmount [ ((price * quantity)-discount) ]:
                                $doc_unit_netAmount = $doc_unit_subtotal - $final_item_discount;
                                $final_netAmount = round($doc_unit_netAmount, 2);
                                //$final_netAmount = number_format((float)$doc_unit_netAmount, 2, '.', '');

                                // vatAmount [ netAmount*vatRate ]:
                                // vatAmount [ netAmount*vatRate ]:
                                $doc_unit_vatAmount = $final_netAmount * $doc_unit_vatRate / 100;
                                // $final_vatAmount = number_format((float)$doc_unit_vatAmount, 2, '.', '');
                                $final_vatAmount = round($doc_unit_vatAmount, 2);

                                // amountWithVat [ netAmount+vatAmount ]:
                                $doc_unit_amountWithVat = $final_netAmount + $final_vatAmount;
                                $final_amountWithVat = round($doc_unit_amountWithVat, 2);
                            
    
                            // Insert Data To zatcaDocumentUnit:
                            $insert_doc_unit = $wpdb->insert(
                                'zatcaDocumentUnit',
                                [
                                    'deviceNo'      => $device_No,
                                    'documentNo'    => $docNo,
                                    'itemNo'        => $item->order_item_id,
                                    'eName'         => $doc_unit_sku,
                                    'price'         => $final_price,
                                    'quantity'      => $doc_unit_item_qty,
                                    'discount'      => $final_item_discount,
                                    'vatRate'       => $doc_unit_vatRate,
                                    'vatAmount'     => $final_vatAmount,
                                    'netAmount'     => $final_netAmount,
                                    'amountWithVAT' => $final_amountWithVat
                                ]
                            );
                        }
                        }
                        
                        // Validation of zatcaDocumentUnit:
                        if ($insert_doc_unit === false) {
    
                            // Check For Error:
                            error_log('documentunit Error: ' . $wpdb->last_error);
                            $send_response = [
                                'status' => 'error',
                                'msg' => 'Error Inserting doc unit: ' . $wpdb->last_error,
                            ];
                            //echo "Error Inserting docunit: " . $wpdb->last_error;
                        }
    
                    }

                    // Log the invoice insertion
                    $user_login = wp_get_current_user()->user_login;
                    $user_id = wp_get_current_user()->ID;
                    log_invoice_insertion($user_login, $user_id);
    
                }
            }

            $send_response = [
                'status' => 'success',
                'msg' => '',
            ];

            //$msg = __("Document Created successifly", "zatca");

        }
        
        wp_send_json($send_response);
        //echo $form_array['insert-vat-cat-code'];
        
    }

    die();
}


// Function to check if customer exist on zatcaCustomer or not:
function document_check_customer(){

    if(isset($_REQUEST)){

        // AJax Data:
        $customerId = $_REQUEST['woo_customer_id'];

        global $wpdb;

        // Table Name:
        $table_name_customes = 'zatcaCustomer';

        // Prepare the query with a condition on Customer Exist or not:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_customes WHERE clientVendorNo = $customerId") );

        if($wpdb->num_rows > 0){ // If Date Valid:

            
            // echo $c->aName;
            echo 'Exist';
            
        }else{ // If No Date Valid
            
            echo 'Not Exist';

        }
 
        
    }
    die();
}

// Function to add customer to zatcaCustomer from redirect from document page:
function document_customer_handle_in_customer_page(){

    if (isset($_GET['page']) && $_GET['page'] ==='zatca-documents' &&  isset($_GET['action']) && $_GET['action'] === 'doc-add-customer') {
       
        wp_add_inline_script("jquery", "
            window.onload = function() {
                // Data Come With URL:
                const urlParams = new URLSearchParams(window.location.search);
                const customerId = urlParams.get('customerId');
                
                // Get the clientVendorNo input element
                    const clientVendorNoInput = document.getElementById('client-no');

                    // Get the client-name-ar input element to populate with fetched data
                    const clientNameArabicInput = document.getElementById('client_name_ar');

                    // Get the client-name-ar input element to populate with fetched data
                    const clientNameEnglishInput = document.getElementById('customer_client_name_en');

                    // Get the client-name-eng input element to populate with fetched data
                    // const clientNameInput = document.getElementById('client-name');

                    // Get the address input element to populate with fetched data
                    const addressArabicInput = document.getElementById('address-ar');

                    // Get the address input element to populate with fetched data
                    const addressEnglishInput = document.getElementById('address-en');

                    // Get the Arabic city input element to populate with fetched data
                    const cityArabicInput = document.getElementById('city-ar');

                    // Get the English city input element to populate with fetched data
                    const cityEnglishInput = document.getElementById('city-en');

                    // Get Postal Code Input element:
                    var postal_Code = document.getElementById('customer_postal_code');
                    
                    // Get second_bus_id Input element:
                    var second_bus_id_input = document.getElementById('second_bus_id');

                    // Get dist_ar Input element:
                    var dist_ar_input = document.getElementById('dist_ar');

                // Get CustomerId Data From URL to ClientNo Input:
                clientVendorNoInput.value = customerId;

                $.ajax({

                    url: ajaxurl,
                    method: 'POST',
                    data: {
                        'action': 'doc_customer',
                        'doc_customer_id': customerId
                    },
                    
                    success: function(data) {

                        
                    // Variables Come From Ajax:
                    var postalCode = data.postalCode;
                    var first_name = data.first_name;
                    var last_name = data.last_name;
                    var address = data.address;
                    var city = data.city;
                    
                    
                    // change values of inputs:
                    postal_Code.value = postalCode;
                    

                    function containsArabic(text) {
                        var arabicRegex = /[\u0600-\u06FF]/;
                        return arabicRegex.test(text);
                    }
            
                    function containsEnglish(text) {
                        var englishRegex = /^[a-zA-Z0-9\s]*$/;
                        return englishRegex.test(text);
                    }
                    
                    if (!addressArabicInput || !addressEnglishInput || !cityArabicInput || !cityEnglishInput) {
                        console.error('Address input elements not found.');
                        return;
                    }

                    // Empty the inputs if have data:
                    addressArabicInput.value = '';
                    addressEnglishInput.value = '';
                    cityArabicInput.value = '';
                    cityEnglishInput.value = '';
                    clientNameArabicInput.value = '';
                    clientNameEnglishInput.value = '';
            
                    // Check For address arabic or english:
                    if (containsArabic(address)) {
                        
                        // Insert New Value
                        addressArabicInput.value = address;

                    } else if (containsEnglish(address)) {

                        // Insert New Value
                        addressEnglishInput.value = address;

                    } else {

                        console.log('Unable to determine the language of the address.');
                    }

                    // Check For client name arabic or english:
                    if (containsArabic(first_name) && containsArabic(last_name)  ) {
                        
                        // Insert New Value
                        clientNameArabicInput.value = first_name + ' ' + last_name;

                    } else if (containsEnglish(first_name) && containsEnglish(last_name)) {

                        // Insert New Value
                        clientNameEnglishInput.value = first_name + ' ' + last_name;

                    } else {

                        console.log('Unable to determine the language of the client name.');
                    }

                    // Check For city arabic or english:
                    if (containsArabic(city)) {

                        // Insert New Value
                        cityArabicInput.value = city;

                    } else if (containsEnglish(city)) {

                        // Insert New Value
                        cityEnglishInput.value = city;

                    } else {

                        console.log('Unable to determine the language of the city.');
                    }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
                
            };
        
        ");
        
        
    }
}

// Get data from database and send to customer insert page as ajax data - document:
function doc_customer_Get_data_ajax(){


    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $customerId = $_REQUEST['doc_customer_id'];


		$table_usermeta = $wpdb->prefix . 'usermeta';

     

        $first_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_first_name' and user_id = $customerId"));
        $last_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_last_name' and user_id = $customerId"));
        $address = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_address_1' and user_id = $customerId"));
        $city = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_city' and user_id = $customerId"));
        $postalCode = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_postcode' and user_id = $customerId"));

      
        // Return the fetched data
      
        $response = array(
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'address'       => $address,
            'city'          => $city,
            'postalCode'    => $postalCode
        );

        // Return the array as JSON
        wp_send_json($response);
       
    }

    die();
}

// AJax Edit in DB - document [ Edit-page]:
function document_edit_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        
        // AJax Data:
        $documents = $_REQUEST['document_edit_form_ajax'];
        
        // Parse Data:
        parse_str($documents, $form_array);

        // Variable of Document No:
        $documentNo = $form_array['documentNo'];

        // Variable of Invoice no [ order_Id ]:
        $woo_invoice_No = $form_array['invoice-no'];

        // Tables Name:
        $table_name_device = 'zatcaDevice';
        $table_name_document = 'zatcaDocument';
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
        $table_orders = $wpdb->prefix . 'wc_orders';


        // get device no From zatcaDocument:
        $deviceNo_from_db = $wpdb->get_var($wpdb->prepare("select deviceNo from $table_name_document WHERE documentNo = $documentNo "));
        
        // get previousDocumentNo From zatcaDocument:
        $previousDocumentNo_from_db = $wpdb->get_var($wpdb->prepare("select previousDocumentNo from $table_name_document WHERE documentNo = $documentNo "));
        
        // get previousInvoiceHash From zatcaDocument:
        $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("select previousInvoiceHash from $table_name_document WHERE documentNo = $documentNo "));

        // Pget UUID From zatcaDocument::
        $uuid_from_db = $wpdb->get_var($wpdb->prepare("select UUID from $table_name_document WHERE documentNo = $documentNo "));
        
        // Get Order Id for Specific Item_Id [ line_item ]:
        $item_id_line_item = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $woo_invoice_No AND order_item_type = 'line_item'"));

        // get customer id from order table:
        $order_Customer_Id = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $woo_invoice_No"));
        
        // Get Sub  Total from woo_order_itemmeta:
        $sub_total = wc_get_order_item_meta( $item_id_line_item, '_line_subtotal', true );
       
        // Get Sub Net Total from woo_order_itemmeta:
        $sub_net_total = wc_get_order_item_meta( $item_id_line_item, '_line_total', true );
        
        // Get seconde bussiness id type from zatcaCompany [ seller ]:
        $seller_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcaCompany"));
       
        // Get seconde bussiness id from zatcaCompany [ seller ]:
        $seller_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCompany"));
        
        // get buyer_secondBusinessIDType from zatcaCustomers:
        $buyer_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get buyer_secondBusinessID from zatcaCustomers:
        $buyer_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // Get Seller Vat from zatcaCompany [ seller ]:
        $seller_VAT_Company = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCompany"));

        // Get Seller apartment from zatcaCompany [ seller ]:
        $seller_apartmentNum_Company = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCompany"));
        
        // Get Seller POBoxAdditionalNum from zatcaCompany [ seller ]:
        $seller_POBoxAdditionalNum_Company = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCompany"));
        
        // Get Seller POBox from zatcaCompany [ seller ]:
        $seller_POBox_Company = $wpdb->get_var($wpdb->prepare("select POBox from zatcaCompany"));
        
        // Get Seller postal code from zatcaCompany:
        $seller_postalCode = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCompany"));
        

        // Get Seller street_Arb from zatcaCompany [ seller ]:
        $seller_street_Arb_Company = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCompany"));
        
        // Get Seller street_Eng from zatcaCompany [ seller ]:
        $seller_street_Eng_Company = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCompany"));
        
        // Get Seller district_Arb from zatcaCompany [ seller ]:
        $seller_district_Arb_Company = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCompany"));
        
        // Get Seller district_Eng from zatcaCompany [ seller ]:
        $seller_district_Eng_Company = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCompany"));
        
        // Get Seller city_Arb from zatcaCompany [ seller ]:
        $seller_city_Arb_Company = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCompany"));
        
        // Get Seller city_Eng from zatcaCompany [ seller ]:
        $seller_city_Eng_Company = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCompany"));
        
        // Get Seller country_Arb from zatcaCompany [ seller ]:
        $seller_country_Arb_Company = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcaCompany"));
        
        // Get Seller country_Eng from zatcaCompany [ seller ]:
        $seller_country_Eng_Company = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcaCompany"));
        
        // Get Seller CountryNo from zatcaCompany [ seller ]:
        $seller_CountryNo_Company = $wpdb->get_var($wpdb->prepare("select countryNo from zatcaCompany"));
        
        // get aName from zatcaCustomers:
        $buyer_aName_Customer = $wpdb->get_var($wpdb->prepare("select aName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
                
        // get eName from zatcaCustomers:
        $buyer_eName_Customer = $wpdb->get_var($wpdb->prepare("select eName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get apartmentNum from zatcaCustomers:
        $buyer_apartmentNum_Customer = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get POBoxAdditionalNum from zatcaCustomers:
        $buyer_POBoxAdditionalNum_Customer = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get PostalCode from zatcaCustomers:
        $buyer_PoCode = $wpdb->get_var($wpdb->prepare("select POBox from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get PostalCode from zatcaCustomers:
        $buyer_PostalCode = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get street_Arb from zatcaCustomers:
        $buyer_street_Arb_Customer = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get street_Eng from zatcaCustomers:
        $buyer_street_Eng_Customer = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get district_Arb from zatcaCustomers:
        $buyer_district_Arb_Customer = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get district_Eng from zatcaCustomers:
        $buyer_district_Eng_Customer = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get city_Arb from zatcaCustomers:
        $buyer_city_Arb_Customer = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get city_Arb from zatcaCustomers:
        $buyer_city_Eng_Customer = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get CountryNo from zatcaCustomers:
        $buyer_CountryNo_Customer = $wpdb->get_var($wpdb->prepare("select country_No from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));


        // Variables of data:
        $delivery_Date = $form_array['deliveryDate'];
        $latest_Delivery_Date = $form_array['gaztLatestDeliveryDate'];
        $note = $form_array['note'];


        // check if delivery date is null:
            if(empty($delivery_Date)){
                $delivery_Date = date('Y-m-d');
            }
    
            // check if latest_Delivery_Date  is null:
            if(empty($latest_Delivery_Date)){
                $latest_Delivery_Date = date('Y-m-d');
            }


            if(isset($form_array['returnReason']) && $form_array['returnReason'] != '')
            {
                $returnReason = $form_array['returnReasonType'];
                $reason = $form_array['returnReason'];
            }
            else
            {
                $returnReason = 0;
                $reason = NULL;
            }
        // $table_name_document = 'zatcaDocument';
            
        $data = array(
            'deviceNo'                              => $deviceNo_from_db,
            'invoiceNo'                             => $form_array['invoice-no'],
            'deliveryDate'                          => $delivery_Date,
            'gaztLatestDeliveryDate'                => $latest_Delivery_Date,
            'zatcaInvoiceType'                      => $form_array['zatcaInvoiceType'],
            'amountPayed01'                         => $form_array['amountPayed01'],
            'amountPayed02'                         => $form_array['amountPayed02'],
            'amountPayed03'                         => $form_array['amountPayed03'],
            'amountCalculatedPayed'                 => $form_array['amountCalculatedPayed'],
            'returnReasonType'                      => $returnReason,
            'reason'                                => $reason,
            'subTotal'                              => $form_array['subTotal'],
            'subTotalDiscount'                      => $form_array['subTotalDiscount'],
            'taxRate1_Total'                        => $form_array['taxRate1_Total'],
            'subNetTotal'                           => $form_array['subNetTotal'],
            'subNetTotalPlusTax'                    => $form_array['subNetTotalPlusTax'],
            'amountLeft'                            => $form_array['amountLeft'],
            'previousDocumentNo'                    => $previousDocumentNo_from_db,
            'previousInvoiceHash'                   => $previousInvoiceHash,
            'seller_secondBusinessIDType'           => $seller_second_bussiness_Id_Type,
            'seller_secondBusinessID'               => $seller_second_bussiness_Id,
            'buyer_secondBusinessIDType'            => $buyer_second_bussiness_Id_Type,
            'buyer_secondBusinessID'                => $buyer_second_bussiness_Id,
            'VATCategoryCodeNo'                     => $form_array['edit-vat-cat-code'],
            'VATCategoryCodeSubTypeNo'              => $form_array['vat-cat-code-sub-no'],
            'zatca_TaxExemptionReason'              => $form_array['taxExemptionReason'],
            'zatcaInvoiceTransactionCode_isNominal' => $form_array['isNominal'],
            'zatcaInvoiceTransactionCode_isExports' => $form_array['isExports'],
            'zatcaInvoiceTransactionCode_isSummary' => $form_array['isSummary'],
            'UUID'                                  => $uuid_from_db,
            'seller_VAT'                            => $seller_VAT_Company,
            'seller_apartmentNum'                   => $seller_apartmentNum_Company,
            'seller_street_Arb'                     => $seller_street_Arb_Company,
            'seller_street_Eng'                     => $seller_street_Eng_Company,
            'seller_district_Arb'                   => $seller_district_Arb_Company,
            'seller_district_Eng'                   => $seller_district_Eng_Company,
            'seller_city_Arb'                       => $seller_city_Arb_Company,
            'seller_city_Eng'                       => $seller_city_Eng_Company,
            'seller_country_Arb'                    => $seller_country_Arb_Company,
            'seller_country_Eng'                    => $seller_country_Eng_Company,
            'seller_country_No'                     => $seller_CountryNo_Company,
            'seller_POBox'                          => $seller_POBox_Company,
            'seller_PostalCode'                     => $seller_postalCode,
            'seller_POBoxAdditionalNum'             => $seller_POBoxAdditionalNum_Company,
            'buyer_aName'                           => $buyer_aName_Customer,
            'buyer_eName'                           => $buyer_eName_Customer,
            'buyer_apartmentNum'                    => $buyer_apartmentNum_Customer,
            'buyer_street_Arb'                      => $buyer_street_Arb_Customer,
            'buyer_street_Eng'                      => $buyer_street_Eng_Customer,
            'buyer_district_Arb'                    => $buyer_district_Arb_Customer,
            'buyer_district_Eng'                    => $buyer_district_Eng_Customer,
            'buyer_city_Arb'                        => $buyer_city_Arb_Customer,
            'buyer_city_Eng'                        => $buyer_city_Eng_Customer,
            'buyer_country_No'                      => $buyer_CountryNo_Customer,
            'buyer_POBox'                           => $buyer_PoCode,
            'buyer_PostalCode'                      => $buyer_PostalCode,
            'buyer_POBoxAdditionalNum'              => $buyer_POBoxAdditionalNum_Customer
               
        );
            
    
        $where = array('documentNo' => $documentNo);
        $update_result = $wpdb->update($table_name_document, $data, $where);
    

        if ($update_result === false) {
            // There was an error inserting data:
            error_log('Edit error: ' . $wpdb->last_error);
            $send_response = [
    
                'status' => 'error',
                'msg' => 'Error Editing data: '. $wpdb->last_error,
    
            ];
            //echo "Error Editing data: " . $wpdb->last_error;
        } else {
            $send_response = [
                'status' => 'success',
                'msg' => '',
            ];
            //echo __("Data Updated", "zatca");
        }
        wp_send_json($send_response);
        // print_r($data);
       
    }

    die();
}

// Function to update Seller - Buyer Data Before Send:
function update_zatca($doc_no){

    global $wpdb;

    $documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaDocument WHERE documentNo = $doc_no") );

        

    $zatca_TaxExemptionReason = '';
    $nominalInvoice = '';
    $exportsInvoice = '';
    $summaryInvoice = '';
    $taxSchemeId = '';

    $order_Id = 0;

    // Define zatcaDocument fields to update:
    $update_seller_sellerName='';
    $update_seller_sellerAdditionalIdType='';
    $update_seller_sellerAdditionalIdNumber='';
    $update_seller_sellerVatNumber='';
    $update_seller_sellerVatCategoryNo='';
    $update_seller_street_Arb='';
    $update_seller_POBoxAdditionalNum='';
    $update_seller_apartmentNum='';
    $update_seller_city_Arb='';
    $update_seller_countrySubdivision_Arb='';
    $update_seller_POBox='';
    $update_seller_district_Arb='';
    $update_buyer_aName='';
    $update_buyer_street_Arb='';
    $update_buyer_POBoxAdditionalNum='';
    $update_buyer_apartmentNum='';
    $update_buyer_city_Arb='';
    $update_buyer_countrySubdivision_Arb='';
    $update_buyer_POBox='';
    $update_buyer_district_Arb='';
    $update_buyer_buyerAdditionalIdNumber='';
    $update_buyer_buyerVatNumber='';




    // Get Data From zatcaDocument:
    foreach($documents as $doc){

        $order_Id = $doc->invoiceNo;

        $billTypeNo = $doc->billTypeNo;
        $reason = $doc->reason;
        $zatcaRejectedInvoiceNo = $doc->zatcaRejectedInvoiceNo;

        $invoiceType = "TAX_INVOICE";
        $invoiceTypeCode = "Standard";
        $id =  $doc->documentNo;
        $icvIncrementalValue = (int)$doc->documentNo;
        $referenceId = $doc->UUID;
        $issueDate = date("Y-m-d", strtotime($doc->dateG));
        $issueTime = date("H:i:s", strtotime($doc->dateG));
        $previousHash = "NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==";
        
        $zatca_TaxExemptionReason = $doc->zatca_TaxExemptionReason;

        // Validation for zatcaInvoiceTransactionCode If 0 = true - if Null = False:
        $nominalInvoice = (isset($doc->zatcaInvoiceTransactionCode_isNominal) && $doc->zatcaInvoiceTransactionCode_isNominal==0) ? true : false;
        $exportsInvoice = (isset($doc->zatcaInvoiceTransactionCode_isExports) && $doc->zatcaInvoiceTransactionCode_isExports==0) ? true : false;
        $summaryInvoice = (isset($doc->zatcaInvoiceTransactionCode_isSummary) && $doc->zatcaInvoiceTransactionCode_isSummary==0) ? true : false;
        
        //$taxSchemeId = $wpdb->get_var($wpdb->prepare("SELECT codeName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $doc->VATCategoryCodeNo"));
        $documentVatCategoryNo = $doc->VATCategoryCodeNo;
    }

    
    // Update Seller Data From zatcaCompany:
    $seller_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcaCompany"));

    
    foreach($seller_update as $seller){
        
        $company_VATCategoryCode =$seller->VATCategoryCode;

        $company_VATCategoryCodeSubTypeNo=$seller->VATCategoryCodeSubTypeNo;

        // Get code info from zatcabusinessidtype Table:
    
        $seller_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype     WHERE codeNumber = $seller->secondBusinessIDType") );
        
        $taxSchemeId = $wpdb->get_var($wpdb->prepare("SELECT codeName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $seller->VATCategoryCode"));


        $sellerName = $seller->companyName;
        $sellerAdditionalIdType = $seller_codeInfo;
        $sellerAdditionalIdNumber = $seller->secondBusinessID;
        $sellerVatNumber = $seller->VATID;
        $sellerVatCategoryNo = $seller->VATCategoryCode;
        $sellerAddress = [
            "streetName" => $seller->street_Arb,
            "additionalNo" => $seller->POBoxAdditionalNum,
            "buildingNumber" => $seller->apartmentNum,
            "city" => $seller->city_Arb,
            "state" => $seller->countrySubdivision_Arb,
            "zipCode" => $seller->postalCode,
            "district" => $seller->district_Arb,
            "country" => "SA"
        ];

        // Fields Of Update zatca document [ seller ]:
        $update_seller_sellerName=$sellerName;
        $update_seller_sellerAdditionalIdType=$sellerAdditionalIdType;
        $update_seller_sellerAdditionalIdNumber=$sellerAdditionalIdNumber;
        $update_seller_sellerVatNumber=$sellerVatNumber;
        $update_seller_sellerVatCategoryNo=$sellerVatCategoryNo;
        $update_seller_street_Arb=$seller->street_Arb;
        $update_seller_POBoxAdditionalNum=$seller->POBoxAdditionalNum;
        $update_seller_apartmentNum=$seller->apartmentNum;
        $update_seller_city_Arb=$seller->city_Arb;
        $update_seller_countrySubdivision_Arb=$seller->countrySubdivision_Arb;
        $update_seller_POBox=$seller->POBox;
        $update_seller_postalCode=$seller->postalCode;
        $update_seller_district_Arb=$seller->district_Arb;

    }

    
    // Get Customer Id from Order Table:
    $table_orders = $wpdb->prefix . 'wc_orders';

    $customer_Id = $wpdb->get_var( $wpdb->prepare( "SELECT customer_id FROM $table_orders WHERE id = $order_Id") );


    // Update Buyer Data From zatcaCustomer:
    $buyer_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcaCustomer WHERE clientVendorNo = $customer_Id"));
    

    foreach($buyer_update as $buyer){

        $buyer_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype WHERE codeNumber = $buyer->secondBusinessIDType") );

        // "groupVatNumber"=> "NONE"

        $buyerName = $buyer->aName;
        $buyerAddress = [
            "streetName" => $buyer->street_Arb,
            "additionalNo" => $buyer->POBoxAdditionalNum,
            "buildingNumber" => $buyer->apartmentNum,
            "city" => $buyer->city_Arb,
            "state" => $buyer->countrySubdivision_Arb,
            "zipCode" => $buyer->postalCode,
            "district" => $buyer->district_Arb,
            "country" => "SA"
        ];
        $buyerAdditionalIdType = $buyer_codeInfo;
        $buyerAdditionalIdNumber = $buyer->secondBusinessID;
        $buyerVatNumber = $buyer->VATID;
        

        // fields to update zatca document [ buyer ]:
        $update_buyer_aName=$buyerName;
        $update_buyer_street_Arb=$buyer->street_Arb;
        $update_buyer_POBoxAdditionalNum=$buyer->POBoxAdditionalNum;
        $update_buyer_apartmentNum=$buyer->apartmentNum;
        $update_buyer_city_Arb=$buyer->city_Arb;
        $update_buyer_countrySubdivision_Arb=$buyer->countrySubdivision_Arb;
        $update_buyer_POBox=$buyer->POBox;
        $update_buyer_district_Arb=$buyer->district_Arb;
        $update_buyer_buyerAdditionalIdNumber=$buyerAdditionalIdNumber;
        $update_buyer_buyerVatNumber=$buyerVatNumber ;
    }


    // Loop On zatcaDocumentUnit to get data:
    $documents_unit = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaDocumentUnit WHERE documentNo = $doc_no") );

    $totalAmountWithoutVat = 0;
    $totalLineNetAmount = 0;
    $totalVatAmount = 0;
    $totalAmountWithVat = 0;
    $totalDiscountAmount = 0;
    $taxPercent = 0;
    
    $lineItems =[];
    foreach($documents_unit as $unit){

        $lineItems[] = [
            
                "id" => $unit->itemNo,
                "description" => $unit->eName . ' - ' . $unit->aName, // Must be aName Only [ but didint insert aName],
                "linePrice" => [
                    "currencyCode" => "SAR",
                    // "amount" => (int)number_format((float)$unit->price, 2, '.', '')  
                    "amount" => $unit->price
                
                ],
                "lineQuantity" => $unit->quantity,
                "lineNetAmount" => [
                    "currencyCode" => "SAR",
                    // "amount" =>  (int)number_format((float)$unit->netAmount, 2, '.', '')
                    // "amount" =>  (int)number_format((float)round($unit->netAmount,3) , 2, '.', '')
                    "amount" =>  $unit->netAmount
                ],
                "lineDiscountAmount" => [
                    "currencyCode" => "SAR",
                    // "amount" => (int)number_format((float)$unit->discount, 2, '.', '') 
                    "amount" => $unit->discount 
                ],
                // "lineVatRate" => number_format((float)$unit->vatRate, 2, '.', '') , 
                "lineVatRate" => $unit->vatRate,
                "lineVatAmount" => [
                    "currencyCode" => "SAR",
                    "amount" => $unit->vatAmount 
                    // "amount" => round($unit->vatAmount) 
                ],
                "lineAmountWithVat" => [
                    "currencyCode" => "SAR",
                    "amount" => $unit->amountWithVAT
                    // "amount" => round($unit->amountWithVAT) 
                ],
                "taxScheme" => "VAT",
                "taxSchemeId" => $taxSchemeId
            
        ];

        $netAmount = $unit->netAmount;
        $vatAmount = $unit->vatAmount;
        $amountWithVAT = $unit->amountWithVAT;
        $discount = $unit->discount;
    
        $totalAmountWithoutVat += $netAmount;
        $totalLineNetAmount += $netAmount;
        $totalVatAmount += $vatAmount;
        $totalAmountWithVat += $amountWithVAT;
        $totalDiscountAmount += $discount;

    }

    // $totalAmountWithoutVat = number_format($totalAmountWithoutVat, 2, '.', '');
    // $totalLineNetAmount = number_format($totalLineNetAmount, 2, '.', '');
    // $totalVatAmount = number_format($totalVatAmount, 2, '.', '');
    // $totalAmountWithVat = number_format($totalAmountWithVat, 2, '.', '');
    // $totalDiscountAmount = number_format($totalDiscountAmount, 2, '.', '');
    // $taxPercent = number_format((float)$unit->vatRate, 2, '.', ''); 

    $totalAmountWithoutVat = $totalAmountWithoutVat;
    $totalLineNetAmount = $totalLineNetAmount;
    $totalVatAmount = $totalVatAmount;
    $totalAmountWithVat = $totalAmountWithVat;
    $totalDiscountAmount = $totalDiscountAmount;
    $taxPercent = $unit->vatRate; 
    



  


    $totalAmountWithoutVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithoutVat];
    $totalLineNetAmount = ["currencyCode" => "SAR", "amount" => $totalLineNetAmount];
    $totalVatAmount = ["currencyCode" => "SAR", "amount" => $totalVatAmount];
    $totalAmountWithVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithVat];
    $totalDiscountAmount = ["currencyCode" => "SAR", "amount" => $totalDiscountAmount];

    if($taxSchemeId == 'S')
    {
        $taxCategory = "S";
        $taxPercent = "15.0";
    }
    else
    {
        $taxCategory = "E";
        $taxPercent = "00.0";
    }
    

    $supplyDate = "2024-04-29";
    $lastestSupplyDate = "2024-04-29";
    $invoiceCurrencyCode = "SAR";
    $taxCurrencyCode = "SAR";
    $prePaidAmount = ["currencyCode" => "SAR", "amount" => 0];
    $invoiceTypeTransactionCode = [
        "thirdPartyInvoice" => false,
        "nominalInvoice" => $nominalInvoice,
        "exportsInvoice" => $exportsInvoice,
        "summaryInvoice" => $summaryInvoice,
        "selfBilledInvoice" => false
    ];





    // update zatca dpcument:
    $update_data_document = array(

        'seller_aName' =>                   $update_seller_sellerName,
        'seller_secondBusinessIDType' =>    $update_seller_sellerAdditionalIdType,
        'seller_secondBusinessID' =>        $update_seller_sellerAdditionalIdNumber,
        'VATCategoryCodeNo' =>              $update_seller_sellerVatCategoryNo,//documentVatCategoryNo
        'seller_street_Arb' =>              $update_seller_street_Arb,
        'seller_POBoxAdditionalNum' =>      $update_seller_POBoxAdditionalNum,
        'seller_apartmentNum' =>            $update_seller_apartmentNum,
        'seller_city_Arb' =>                $update_seller_city_Arb,
        'seller_countrySubdivision_Arb' =>  $update_seller_countrySubdivision_Arb,
        'seller_POBox' =>                   $update_seller_POBox,
        'seller_PostalCode' =>              $update_seller_postalCode,
        'seller_district_Arb' =>            $update_seller_district_Arb,
        'buyer_aName' =>                    $update_buyer_aName,
        'buyer_street_Arb' =>               $update_buyer_street_Arb,
        'buyer_POBoxAdditionalNum' =>       $update_buyer_POBoxAdditionalNum,
        'buyer_apartmentNum' =>             $update_buyer_apartmentNum,
        'buyer_city_Arb' =>                 $update_buyer_city_Arb,
        'buyer_countrySubdivision_Arb' =>   $update_buyer_countrySubdivision_Arb,
        'buyer_POBox' =>                    $update_buyer_POBox,
        'buyer_district_Arb' =>             $update_buyer_district_Arb,
        'buyer_secondBusinessID' =>         $update_buyer_buyerAdditionalIdNumber,
        'buyer_VAT' =>                      $update_buyer_buyerVatNumber
    );

    $where = array('documentNo' => $doc_no);

    $update_result = $wpdb->update('zatcaDocument', $update_data_document, $where);

    if($update_result === false) {
        // There was an error inserting data:
        error_log('Update error: ' . $wpdb->last_error);
        echo "Error Update data: " . $wpdb->last_error;
    }


    /* Validation On Vat Id - 
    if “zatcaInvoiceTransactionCode_isExport” then VAT ID for the client must be empty:
    */
    /*if($exportsInvoice == true){

        $buyerVatNumber = 0;

    }*/

    // original document number if returned bill
    $originalDoc =  "";
    // reason of return
    $returnReason = "";
    // check if billTypeNo is 23
    if($billTypeNo == 23)
    {
        $originalDoc =  $zatcaRejectedInvoiceNo;
        $returnReason = $reason;
    }
    // check if billTypeNo is 33
    else if($billTypeNo == 33)
    {
        $originalDoc =  "";
        $returnReason = "";
    }

    // Build the array Of Request:
    $data = [
        "invoiceType" => $invoiceType,
        "invoiceTypeCode" => $invoiceTypeCode,
        "id" => $id,
        "icvIncrementalValue" => $icvIncrementalValue,
        "referenceId" => $referenceId,
        "issueDate" => $issueDate,
        "issueTime" => $issueTime,
        "previousHash" => $previousHash,
        "seller" => [
            "name" => $sellerName,
            "additionalIdType" => "OTH",//$sellerAdditionalIdType,
            "additionalIdNumber" => $sellerAdditionalIdNumber,
            "vatNumber" => $sellerVatNumber,
            "groupVatNumber" => "",
            "address" => $sellerAddress
        ],
        "buyer" => [
            "name" => $buyerName,
            "address" => $buyerAddress,
            "additionalIdType" =>"OTH", 
            "additionalIdNumber" => $buyerAdditionalIdNumber,
            "vatNumber" => $buyerVatNumber,
            "groupVatNumber" => ""
        ],
        "lineItems" => $lineItems,
        "totalAmountWithoutVat" => $totalAmountWithoutVat,
        "totalLineNetAmount" => $totalLineNetAmount,
        "totalVatAmount" => $totalVatAmount,
        "totalAmountWithVat" => $totalAmountWithVat,
        "totalDiscountAmount" => ["currencyCode" => "SAR", "amount" => 0],
        "taxCategory" => $taxCategory,
        "taxPercent" => $taxPercent,
        "supplyDate" => $supplyDate,
        "lastestSupplyDate" => $lastestSupplyDate,
        "invoiceCurrencyCode" => $invoiceCurrencyCode,
        "taxCurrencyCode" => $taxCurrencyCode,
        "note" => [
            "reason" => $returnReason,
            "invoiceNo" => $originalDoc
        ],
        "taxExemptionReasonCode" => "",
        "taxExemptionReason" => $zatca_TaxExemptionReason,
        "invoiceNote" => "",
        "prePaidAmount" => $prePaidAmount,
        "invoiceTypeTransactionCode" => $invoiceTypeTransactionCode
    ];

   
    // Encode the array to JSON
    $jsonData = json_encode($data);

    return $jsonData;
}


// Send Request to zatca - Clear Function:
function send_request_to_zatca_clear(){

    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    $data = update_zatca($doc_no);

    $requestArray = json_decode($data, true);

    // get deviceCSID and tokenData from zatcaDevice table
    $deviceCSID = $wpdb->get_var("SELECT zd.deviceCSID 
    FROM zatcaDevice zd, zatcaDocument z 
    WHERE z.documentNo = '$doc_no' and z.deviceNo=zd.deviceNo");

    $tokenData = $wpdb->get_var("SELECT zd.tokenData 
    FROM zatcaDevice zd, zatcaDocument z 
    WHERE z.documentNo = '$doc_no' and z.deviceNo=zd.deviceNo");

    $company_stage = $wpdb->get_var("SELECT zatcaStage 
    FROM zatcaCompany");

    $msg = '';

    // Validation Fields:
    $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
    $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
    $seller_secondBusinessId_companyStage_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber ==null && $company_stage == 2) ? true : false;

    $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
    $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

    $buyer_additional_id = $requestArray['buyer']['additionalIdNumber'];
    $buyer_additional_id_validation = (isset($buyer_additional_id ) && $buyer_additional_id !=null) ? true : false;

    // Validate buyer vat number
    $buyer_vatNo = $requestArray['buyer']['vatNumber'];
    if($buyer_vatNo == null || $buyer_vatNo==0)
    {
        $buyer_vatNo = 0;
    }
    $invoicetransactioncode_isexports = $wpdb->get_var("SELECT zatcaInvoiceTransactionCode_isExports FROM zatcaDocument Where documentNo = '$doc_no'");
    
    $buyer_vatNo_validation1 = ($buyer_vatNo == null && ($invoicetransactioncode_isexports == null)) ? true : false;
    
    $buyer_vatNo_validation0 = ($buyer_vatNo != 0 && ($invoicetransactioncode_isexports != null)) ? true : false;
    
    
    // validation on seller_additionalIdNumber & buyer_additionalNo:
    if($seller_additionalIdNumber_validation == false)
    {
        $send_response = [
            'status' => 'insert_seller_additional_id',
            'msg' => ''
        ];
    }
    elseif($buyer_additionalNo_validation == false)
    { 
      // Validation on additionalNo - customer [ buyer ]:  
        $send_response = [
            'status' => 'insert_buyer_poBox_additionalNo',
            'msg' => $buyer_additionalNo
        ];
    }
    elseif($buyer_additional_id_validation == false)
    { 
      // Validation on additionalNo - customer [ buyer ]:  
        $send_response = [
            'status' => 'insert_buyer_additional_id',
            'msg' => $buyer_additional_id
        ];
    }
    else if($buyer_vatNo_validation1 == true)
    {
        $send_response = [
            'status' => 'isexport1_buyervat',
            'msg' => $buyer_vatNo
        ];
        
    }
    else if($buyer_vatNo_validation0 == true)
    {
        $send_response = [
            'status' => 'isexport0_buyervat',
            'msg' => $requestArray['buyer']['vatNumber']
        ];
    }
    else if($seller_secondBusinessId_companyStage_validation == true)
    {
        $send_response = [
            'status' => 'seller_second_business_id',
            'msg' => ''];
    }
    else{

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Clear?deviceID='. $deviceCSID .'&skipPortalValidation=false',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $tokenData
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            $send_response = [
                'status' => 'error',
                'msg' => 'cURL Error:' . $error . '(Error Code:'. $errorCode . ')'
            ];
            //echo "cURL Error: $error (Error Code: $errorCode)";
        }
        
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       

        $http_status_msg = "HTTP Status Code: " . $http_status . "\n";

        curl_close($curl);

        if ($response === false) {
            $send_response = [
                'status' => 'error',
                'msg' => 'Curl error: ' . curl_error($curl)
            ];
            //echo 'Curl error: ' . curl_error($curl);
        } else {
            // echo 'HTTP status code: ' . $http_status;
            // echo 'Response: ' . $response;
        }

        $responseArray = json_decode($response, true);

        $statusCode = $responseArray['zatcaStatusCode'];
        $isZatcaValid = $responseArray['isValidationFromZatca'];
        $clearanceStatus = $responseArray['clearanceStatus'];
        $validationResults = $responseArray['validationResults'];
        $portalResults = $responseArray['portalResults'];
        $hashed = $responseArray['hash'];
        $qrCode = $responseArray['generatedQR'];
        $response_date = date('Y-m-d H:i:s');
        $warningMessage='';
        $clearedInvoice = $responseArray['clearedInvoice'];

        $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];

        if($responseArray['zatcaStatusCode'] == 400 || $responseArray['zatcaStatusCode'] == null || $responseArray['zatcaStatusCode'] == 0)
        {
            // if($responseArray['portalResults'] = "Object reference not set to an instance of an object.")
            // {
            //     $errorMessage = "The device signature or token data may not be correct , please check and try again!";
            // }
            // else
            // {
            //     $errorMessage = $responseArray['portalResults'];
            // }
            $errorMessage = $responseArray['portalResults'];
        }
        

        // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
        $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                FROM zatcaDocument 
                                                                ORDER BY zatcaResponseDate DESC 
                                                                LIMIT 1"));
        
        if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
            foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                if (isset($Message['message'])) {
                    // $warningMessage = $Message['message'];
                $warningMessage .= $Message['message'] . "\n";
                }else{

                    echo 'WRONG';
                }
            }
        }

        
        // Check If response Valid:
        if($clearanceStatus == 'CLEARED'){

            if($statusCode == '200'){

                // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $doc_no"));

                if($originalDocNo != NULL)
                {
                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                    "zatcaAcceptedReissueInvoiceNo" => $doc_no];
                    $whereOriginal = array('documentNo' => $originalDocNo);
    
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                }

                
                //  update zatca document xml fields with response Data:
                $zatcaDocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 0
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcaDocumentxml_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorxml',
                        'msg' => 'There was an error updating on zatcaDocumentxml in the field ' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating on zatcaDocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocumentxml_update_response_result === 0) {
                    
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                
                }  
    
                // update zatca document fields with response Data:
                $zatcaDocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 1,
                    "previousInvoiceHash" => $hashed
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcaDocument_update_response_result === false) {
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdateresponse',
                        'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                }elseif ($zatcaDocument_update_response_result === 0) {
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // update zatca device fields with last document submitted:
                $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $doc_no") );

                $zatcaDevice_update_response_data = [
                    "lastHash" => $hashed,
                    "lastDocumentNo" => $doc_no,
                    "lastDocumentDateTime" => $hashed
                ];
                $where1 = array('deviceNo' => $device_no);

                $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);

                // Check for errors
                if ($zatcaDevice_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdatedevice',
                        'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDevice on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDevice_update_response_result === 0) {
                   
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                insert_encrypted_row($hashed, $doc_no, $device_no);
                $send_response = [
                    'status' => 'success',
                    'msg' => __("Document Submitted Successfully, Zatca Status Code Is ", "zatca") . $statusCode . __(".. Request Is Success", "zatca") . $http_status_msg
                ];
                //$msg = 'Document Submitted Successfully, Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
            
            }elseif($statusCode == '202'){

                // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $doc_no"));

                if($originalDocNo != NULL)
                {
                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                    "zatcaAcceptedReissueInvoiceNo" => $doc_no];
                    $whereOriginal = array('documentNo' => $originalDocNo);
    
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                }

                 // update zatca document xml fields with response Data:
                $zatcaDocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 0
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcaDocumentxml_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorxml',
                        'msg' => 'There was an error updating on zatcaDocumentxml in the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating on zatcaDocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocumentxml_update_response_result === 0) {
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }   
    
                // update zatca document fields with response Data:
                $zatcaDocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 2,
                    "previousInvoiceHash" => $hashed,
                    "zatcaErrorResponse" => $warningMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcaDocument_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdateresponse',
                        'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocument_update_response_result === 0) {
                   
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // update zatca device fields with last document submitted:
                $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $doc_no") );

                $zatcaDevice_update_response_data = [
                    "lastHash" => $hashed,
                    "lastDocumentNo" => $doc_no,
                    "lastDocumentDateTime" => $hashed
                ];
                $where1 = array('deviceNo' => $device_no);

                $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);

                // Check for errors
                if ($zatcaDevice_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdatedevice',
                        'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDevice on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDevice_update_response_result === 0) {
                   
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                insert_encrypted_row($hashed, $doc_no, $device_no);
                
                $send_response = [
                    'status' => 'warning',
                    'msg' => __("Document Submitted with Warning, Zatca Status Code Is ", "zatca") . $statusCode . '......' . $warningMessage
                ];
                //$msg = 'Document Submitted Successfully, Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
            }
          
        }else{

            if($http_status == '400')
            {

                // update zatca document fields with response Data:
                $zatcaDocument_error_response_data = [
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 3,
                    "zatcaErrorResponse" => $errorMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_error_response_data, $where);
                
                // Check for errors
                if ($zatcaDocument_error_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdateresponse',
                        'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocument_error_response_result === 0) {
                    
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // check if have error message or not:
                if(is_array($validationResults)){

                    $send_response = [
                        'status' => 'http_status_msg',
                        'msg' => $http_status_msg . __(" Error: ", "zatca") . $errorMessage
                    ];
                    //$msg = $http_status_msg . ' Error: ' . $errorMessage;

                }else{
                    $send_response = [
                        'status' => 'http_status_msg',
                        'msg' => $http_status_msg . __(" Error: ", "zatca") . $validationResults
                    ];
                    //$msg = $http_status_msg . ' Error: ' . $validationResults;
                }

                
            
            }
            else if($http_status == '303')
            {
                // update zatca document fields with response Data:
                $zatcaDocument_error_response_data = [
                    "zatcaB2B_isForced_To_B2C" => 1];
                $where = array('documentNo' => $doc_no);
                $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument',
                $zatcaDocument_error_response_data, $where);
                $send_response = [
                    'status' => '303',
                    'msg' => $response
                ];
                //$msg = $response;
            }
            else
            {
                $send_response = [
                    'status' => '',
                    'msg' => $response
                ];
                $msg = $response;
            }

        }

        // Log the send to zatca:
        $user_login = wp_get_current_user()->user_login;
        $user_id = wp_get_current_user()->ID;
        log_send_to_zatca($user_login, $user_id);

    }
    $send_response1 = [
        'msg' => $send_response,
        'validationResults' => $validationResults,
        'responseArray' => $responseArray,
        'requestArray' => $requestArray,
        'data' => $data
    ];

    wp_send_json($send_response1);
        
    die();
}




// function to get response and conver invoice to xml and create xml file: 
function get_xml_from_response() {

    // Get document number from AJAX request
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    global $wpdb;

    // Get response from zatcaDocumentxml
    $xmlResponse = $wpdb->get_var($wpdb->prepare("SELECT APIResponse FROM zatcaDocumentxml WHERE documentNo = %d", $doc_no));

    if ($xmlResponse === null) {
        wp_send_json_error(['message' => 'No response found for document number ' . $doc_no]);
    }

    $responseArray = json_decode($xmlResponse, true);
    //$clearedInvoice = $responseArray['clearedInvoice'];
    $clearedInvoice = $responseArray['clearedInvoice'] ?? $responseArray['xml'];
    //$clearedInvoice = isset($responseArray['clearedInvoice']) ? $responseArray['clearedInvoice'] : $responseArray['reportedInvoice'];
    // Decode cleared hash:
    $decoded_data = base64_decode($clearedInvoice);

    if ($decoded_data === false) {
        wp_send_json_error(['message' => 'Failed to decode the cleared invoice']);
    }

    // Parse XML:
    $xml = simplexml_load_string($decoded_data);
    //$xml = simplexml_load_string($decoded_data, 'SimpleXMLElement', LIBXML_NOCDATA);

    if ($xml === false) {
        wp_send_json_error(['message' => 'Failed to parse XML']);
    }

    // Create directory in server
    $upload_dir = wp_upload_dir();
    $desired_path = $upload_dir['basedir'] . '/Download_XML/';

    // Check if directory not exists, create it
    if (!is_dir($desired_path)) {
        mkdir($desired_path, 0755, true);
    }

    // Create the file
    $fileName = $desired_path . 'invoice_' . $doc_no . '.xml';

    if (file_put_contents($fileName, $xml->asXML()) === false) {
        wp_send_json_error(['message' => 'Failed to write XML to file']);
    }

    // Set file permissions
    chmod($fileName, 0644);

    $downloadUrl = $upload_dir['baseurl'] . '/Download_XML/invoice_' . $doc_no . '.xml';

    if (!file_exists($fileName)) {
        wp_send_json_error(['message' => 'File does not exist after creation']);
    }

    // Log the download xml:
    //$user_login = wp_get_current_user()->user_login;
    //$user_id = wp_get_current_user()->ID;
    //log_download_doc_xml($user_login, $user_id);

    $send_response = [
        'xml' => $xml->asXML(),
        'file_created' => 'File created at: ',
        'download_url' => $downloadUrl
    ];

    wp_send_json_success($send_response);
}


// Function of zatca Log:
function log_user_action($user_login, $user_id, $operationType) {
    
    global $wpdb;

    // get Mac Address:
    $os_type = php_uname('s'); // Get the operating system type

    if (stripos($os_type, 'Windows') !== false) {
        // For Windows
        $mac = exec('getmac');
        $mac = strtok($mac, ' '); // Get the first part of the result
    } else {
        // For Linux
        $mac = exec("ip link | awk '/ether/ {print $2}' | head -n 1");
    }
    
    
    $table_name = 'zatcaLog';
    $timestamp = current_time('mysql');
    $ip_address = $_SERVER['REMOTE_ADDR'];

    
    // If Localhost change to localhost ip:
    if ($ip_address === '::1' || $ip_address === '::ffff:127.0.0.1') {
        $ip_address = '127.0.0.1';
    }

    // Validation on Data If all not empty isSuccess will be true:
    if(!empty($user_login) && !empty($user_id) && !empty($timestamp) && !empty($ip_address) && !empty($mac) && !empty($operationType)){

        $isSuccess = true;

    }else{

        $isSuccess = false;
    }

    $wpdb->insert(
        $table_name,
        array(
            'login_personName' => $user_login,
            'login_personNo' =>$user_id,
            'dateG' => $timestamp,
            'IP' => $ip_address,
            'macAddress' => $mac,
            'isSuccess' => $isSuccess,
            'operationType' => $operationType
        )
    );
}

// Funtions to track the login data:
function log_user_login($user_login, $user) {
    log_user_action($user_login, $user->ID, 1); // 1 = login
}

// Funtions to track the Insert New Document data:
function log_invoice_insertion($user_login, $user_id) {
    log_user_action($user_login, $user_id, 2); // 2 = insert invoice
}

// Funtions to track the send to zatca data:
function log_send_to_zatca($user_login, $user_id) {
    log_user_action($user_login, $user_id, 3); // 3 = send to zatca
}
// Funtions to track the send to zatca data:
function log_download_doc_xml($user_login, $user_id) {
    log_user_action($user_login, $user_id, 4); // 4 = download xml
}

// Function to get admin data from database - zatcaUsers
function admin_user_zatcaUsers(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $userId = $_REQUEST['user_id'];


		$table_usermeta = $wpdb->prefix . 'usermeta';

        
        $first_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'first_name' and user_id = $userId"));
        $last_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'last_name' and user_id = $userId"));

        // Return the fetched data

        $response = array(
            'first_name' => $first_name,
            'last_name'  => $last_name
        );

        // Return the array as JSON
        wp_send_json($response);
       
    }

    die();
}

// function to insert user data to database - zatcaUsers:
function insert_user_zatcaUsers(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $data = $_REQUEST['insert_user_data'];

        // Parse Data:
        parse_str($data, $form_array);


        // Variables of data:
        $personNo = $form_array['person-no'];
        $arabicName = $form_array['arabic-name'];
        $englishName = $form_array['english-name'];
        $isRemind = $form_array['is-remind'];
        $remindInterval = $form_array['remindInterval'];

        // check if is remind checked:
        if($isRemind != null){
          
            $isRemind = 1;

        }else{

            $isRemind = 0;
        }

        // Validation on if user already exist or not:
        $check = $wpdb->get_var($wpdb->prepare("SELECT personNo FROM zatcaUser WHERE personNo = $personNo "));

        if($check != null){

            $msg = 'denied';

        }else{

            // Insert User to database:
            $insert_user = $wpdb->insert(
                'zatcaUser',
                [
                    'personNo'  => $personNo,
                    'aName' =>$arabicName,
                    'eName' =>$englishName,
                    'ZATCA_B2C_NotIssuedDocuments_isRemind'=>$isRemind,
                    'ZATCA_B2C_NotIssuedDocumentsReminderInterval'=>$remindInterval
      
                ]
            );

            $msg = 'passed';
        }
        

        // Validation of zatcaUser:
        if ($insert_user === false) {

            // Check For Error:
            error_log('documentunit Error: ' . $wpdb->last_error);
            echo "Error Inserting docunit: " . $wpdb->last_error;
        }

        echo $msg;
       
    }

    die();

}

// function to insert user data to database - zatcaUsers:
function edit_user_zatcaUsers(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $data = $_REQUEST['edit_user_data'];

        // Parse Data:
        parse_str($data, $form_array);

        // Variables of data:
        $personNo = $form_array['personNo'];
        $arabicName = $form_array['arabic-name'];
        $englishName = $form_array['english-name'];
        $isRemind = $form_array['is-remind'];
        $remindInterval = $form_array['remindInterval'];
        

        // check if is remind checked:
        if($isRemind != null){
          
            $isRemind = 1;

        }else{

            $isRemind = 0;
        }


        $table_name = 'zatcaUser';
        $data = array(

            'aName' =>$arabicName,
            'eName' =>$englishName,
            'ZATCA_B2C_NotIssuedDocuments_isRemind'=>$isRemind,
            'ZATCA_B2C_NotIssuedDocumentsReminderInterval'=>$remindInterval
            
        );
        $where = array('personNo' => $personNo);
        $update_result = $wpdb->update($table_name, $data, $where);
    

        if ($update_result === false) {
            // There was an error Updating data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo __('Data Updated', 'zatca');
        }
       
    }

    die();

}

            
// check for admin B2C notification:
    function check_admin_for_B2C_notification(){

        if(isset($_REQUEST)){
    
            global $wpdb;
    
            // AJax User Id:
            $user_id = get_current_user_id(); 
    
            // check if ZATCA_B2C_NotIssuedDocuments_isRemind is Checked:
            $isChecked = $wpdb->get_var($wpdb->prepare("SELECT ZATCA_B2C_NotIssuedDocuments_isRemind FROM zatcaUser WHERE personNo = $user_id "));
            
            // get ZATCA_B2C_NotIssuedDocumentsReminderInterval if ZATCA_B2C_NotIssuedDocuments_isRemind is Checked:
            $hours = $wpdb->get_var($wpdb->prepare("SELECT ZATCA_B2C_NotIssuedDocumentsReminderInterval FROM zatcaUser WHERE personNo = $user_id AND ZATCA_B2C_NotIssuedDocuments_isRemind != 0 "));
            
            // Get Document No from zatcaDocument Where zatcaresponse=0 & dateG <= current date [ B2C Only]:
            $document = $wpdb->get_results($wpdb->prepare("SELECT documentNo FROM zatcaDocument WHERE zatcaInvoiceType = 'B2C' AND zatcaSuccessResponse = 0 AND dateG <= DATE_SUB(NOW(), INTERVAL 10 MINUTE) "));
            
    
            // If admin conditions is true:
            if($isChecked != 0){
    
                // if documents conditions is true:
                if(!empty($document)){
         
                    $msg = __('You have unsubmitted documents please review them', 'zatca');
                }
    
            }
    
            $response = array('msg' => $msg, 'hours'=> $hours);
            wp_send_json($response);
    
        }
    
        die();
    }


// function to create a checkbox [ checkout page ]:
function checkbox_function() {
    ?>
    <div class="wc-block-components-checkout-step__container" style="display: none;" id="hidden-checkbox-container">
        <div class="wc-block-components-checkout-step__content">
            <div class="wc-block-checkout__add-note">
                <div class="wc-block-components-checkbox">
                    <label for="checkbox-control-0">
                        <input 
                            id="my_checkbox_field" 
                            class="wc-block-components-checkbox__input " 
                            type="checkbox" 
                            aria-invalid="false"
                        >
                        <svg 
                            class="wc-block-components-checkbox__mark" 
                            aria-hidden="true" 
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 24 20">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"></path>
                        </svg>
                        <span class="wc-block-components-checkbox__label">
                            <?php _e('Tax Invoice', 'zatca'); ?>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// function to view the short code in checkout page [ checkout page ]:
function add_custom_field_to_checkout_blocks() {
    ?>
    <div class="woocommerce-form__row my-custom-checkbox-container py-3" >
        <?php echo do_shortcode('[checkbox]'); ?>
                <div id="customForm" style="display: none;">
                <?php include plugin_dir_path(__FILE__) . 'checkout-page.php';?>
            </div>
    </div>
    <?php
}


// // function to insert data from checkout page:
function edit_checkout_page_function(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['edit_form_data_ajax'];

        // Parse Data:
        // parse_str($vals, $form_array);
        $form_array = json_decode(stripslashes($vals), true);

        // variables Come from Form:
        $operationType = $form_array['operationType'];
        $clientId = $form_array['clientId'];
        $clientNameAr = $form_array['clientNameAr'];
        $clientNameEn = $form_array['clientNameEn'];
        $districtNameAr = $form_array['districtNameAr'];
        $vatId = $form_array['vatId'];
        $apartmentNo = $form_array['apartmentNo'];
        $zatcaInvoiceType = $form_array['zatcaInvoiceType'];
        $secondBusinessIdType = $form_array['secondBusinessIdType'];
        $secondBusinessId = $form_array['secondBusinessId'];
        $addressNameArabic = $form_array['addressNameArabic'];
        $addressNameEnglish = $form_array['addressNameEnglish'];
        $cityNameArabic = $form_array['cityNameArabic'];
        $cityNameEnglish = $form_array['cityNameEnglish'];
        $postalCode = $form_array['postalCode'];
        $zatcainvoice = '';

        // Check If vat Id Is Not Empty - zatca invoice type will be B2B - else B2C:
        if($vatId != ''){

            $zatcainvoice = 'B2B';
            
        }else{
            
            $zatcainvoice = 'B2C';
        }

        if($operationType == 'edit'){

            $table_name = 'zatcaCustomer';
            $data = array(
                'clientVendorNo'       => $clientId,
                'aName'                => $clientNameAr,
                'eName'                => $clientNameEn,
                'VATID'                 => $vatId,
                'secondBusinessIDType'  => $secondBusinessIdType,
                'secondBusinessID'      => $secondBusinessId,
                'zatcaInvoiceType'      => $zatcainvoice,
                'apartmentNum'          => $apartmentNo,
                'postalCode'            => $postalCode,
                'street_Arb'            => $addressNameArabic,
                'street_Eng'            => $addressNameEnglish,
                'district_Arb'          => $districtNameAr,
                'city_Arb'              => $cityNameArabic,
                'city_Eng'              => $cityNameEnglish
            );
            $where = array('clientVendorNo' => $clientId);
            $update_result = $wpdb->update($table_name, $data, $where);

            if ($update_result === false) {
                // There was an error inserting data
                $error_message = $wpdb->last_error;
                echo "Error inserting data: $error_message";
            } else {
    
                echo __('Data Updated', 'zatca');
                
               
            }
            
        }else{
            
            $insert_result = $wpdb->insert(
                'zatcaCustomer',
                [
                    'clientVendorNo'       => $clientId,
                    'aName'                => $clientNameAr,
                    'eName'                => $clientNameEn,
                    'VATID'                 => $vatId,
                    'secondBusinessIDType'  => $secondBusinessIdType,
                    'secondBusinessID'      => $secondBusinessId,
                    'zatcaInvoiceType'      => $zatcainvoice,
                    'apartmentNum'          => $apartmentNo,
                    'postalCode'            => $postalCode,
                    'street_Arb'            => $addressNameArabic,
                    'street_Eng'            => $addressNameEnglish,
                    'district_Arb'          => $districtNameAr,
                    'city_Arb'              => $cityNameArabic,
                    'city_Eng'              => $cityNameEnglish
                ]
            );
    
            if ($insert_result === false) {
                // There was an error inserting data
                $error_message = $wpdb->last_error;
                echo "Error inserting data: $error_message";
            } else {
    
                echo __('Data Inserted', 'zatca');
            
            }

        }

    }

    die();

}


// Function to check if string arabic or not:
function is_arabic($string) {
    if (preg_match('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u', $string)) {
        return true;
    } else {
        return false;
    }
}

// Function to get order id after submit checkout page and insert to zatcaDocument - zatcaDocumentUnit - zatcaDocumentxml:
// add_action( 'woocommerce_thankyou', 'insert_document_to_zatca_after_checkout_submit' );

function insert_document_to_zatca_after_checkout_submit( $orderId ) {


    ?>

        <script>

            alert('Order ID: <?php echo $orderId; ?>');


        </script>

        <?php


}

/****************************************************** */


////////////////////////////////////////////////////////////////////////////////////////////////


// Tampering Detector Code
function invoice_audit_admin_page_content() {
    echo '<div class="wrap container">';
    echo '<h2 class="text-center">'. __( 'Zacta Tampering Detector', 'zatca' ) .'</h2>';
    // Add your admin page content here
    echo do_shortcode('[invoice_audit_form]');
    echo '</div>';
}


// Shortcode callback function to check gap form
function invoice_audit_form_shortcode()
{
    ob_start();
    require_once(plugin_dir_path(__FILE__) . 'Zacta_Tampering_Detector/invoice_audit_form.php');
    
    return ob_get_clean();
}
add_shortcode('invoice_audit_form', 'invoice_audit_form_shortcode');



///////////////////////////////////////////////////////////////////

// Action of Send Reporting request to zatca:
add_action('wp_ajax_zatca_report', 'send_request_to_zatca_report');

// Action of Reissue request to zatca:
add_action('wp_ajax_zatca_reissue', 'send_reissue_request_to_zatca');

// Action of Return request to zatca:
add_action('wp_ajax_zatca_return', 'send_return_request_to_zatca');

// Function to insert new encrypted row as base64 to zatcaInfo
function insert_encrypted_row($invoiceHash, $documentNo, $deviceNo)
{
    global $wpdb;
    $table_name = 'zatcaInfo';

    // encode invoiceHash and documentNo and deviceNo to Base64
    $invoiceHash = base64_encode($invoiceHash);
    $documentNo = base64_encode($documentNo);
    $deviceNo = base64_encode($deviceNo);

    // insert to zatcaInfo the encrypted data
    $wpdb->insert(
        $table_name,
        array(
            'zatcaInfo1' => $invoiceHash,
            'zatcaInfo2' => $documentNo,
            'zatcaInfo3' => $deviceNo
        ), array('%s', '%s', '%s'));
}

// Function to update Seller - Buyer Data Before Send:
function update_zatca1($doc_no){

    global $wpdb;

    $documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaDocument WHERE documentNo = $doc_no") );

        

    $zatca_TaxExemptionReason = '';
    $nominalInvoice = '';
    $exportsInvoice = '';
    $summaryInvoice = '';
    $taxSchemeId = '';

    $order_Id = 0;

    // Define zatcaDocument fields to update:
    $update_seller_sellerName='';
    $update_seller_sellerAdditionalIdType='';
    $update_seller_sellerAdditionalIdNumber='';
    $update_seller_sellerVatNumber='';
    $update_seller_sellerVatCategoryNo='';
    $update_seller_street_Arb='';
    $update_seller_POBoxAdditionalNum='';
    $update_seller_apartmentNum='';
    $update_seller_city_Arb='';
    $update_seller_countrySubdivision_Arb='';
    $update_seller_POBox='';
    $update_seller_district_Arb='';
    $update_buyer_aName='';
    $update_buyer_street_Arb='';
    $update_buyer_POBoxAdditionalNum='';
    $update_buyer_apartmentNum='';
    $update_buyer_city_Arb='';
    $update_buyer_countrySubdivision_Arb='';
    $update_buyer_POBox='';
    $update_buyer_district_Arb='';
    $update_buyer_buyerAdditionalIdNumber='';
    $update_buyer_buyerVatNumber='';




    // Get Data From zatcaDocument:
    foreach($documents as $doc){

        $order_Id = $doc->invoiceNo;

        $billTypeNo = $doc->billTypeNo;
        $reason = $doc->reason;
        $zatcaRejectedInvoiceNo = $doc->zatcaRejectedInvoiceNo;

        $invoiceType = "TAX_INVOICE";
        $invoiceTypeCode = "Simplified";
        $id =  $doc->documentNo;
        $icvIncrementalValue = (int)$doc->documentNo;
        $referenceId = $doc->UUID;
        $issueDate = date("Y-m-d", strtotime($doc->dateG));
        $issueTime = date("H:i:s", strtotime($doc->dateG));
        $previousHash = "NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==";
        
        $zatca_TaxExemptionReason = $doc->zatca_TaxExemptionReason;
        // Validation for zatcaInvoiceTransactionCode If 0 = true - if Null = False:
        $nominalInvoice = (isset($doc->zatcaInvoiceTransactionCode_isNominal) && $doc->zatcaInvoiceTransactionCode_isNominal==0) ? true : false;
        $exportsInvoice = (isset($doc->zatcaInvoiceTransactionCode_isExports) && $doc->zatcaInvoiceTransactionCode_isExports==0) ? true : false;
        $summaryInvoice = (isset($doc->zatcaInvoiceTransactionCode_isSummary) && $doc->zatcaInvoiceTransactionCode_isSummary==0) ? true : false;
        //$taxSchemeId = $wpdb->get_var($wpdb->prepare("SELECT codeName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $doc->VATCategoryCodeNo"));
        $documentVatCategoryNo = $doc->VATCategoryCodeNo;
    }

    
    // Update Seller Data From zatcaCompany:
    $seller_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcaCompany"));

    
    foreach($seller_update as $seller){
        
        $company_VATCategoryCode =$seller->VATCategoryCode;

        $company_VATCategoryCodeSubTypeNo=$seller->VATCategoryCodeSubTypeNo;

        // Get code info from zatcabusinessidtype Table:
    
        $seller_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype     WHERE codeNumber = $seller->secondBusinessIDType") );
        
        $taxSchemeId = $wpdb->get_var($wpdb->prepare("SELECT codeName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $seller->VATCategoryCode"));

        $sellerName = $seller->companyName;
        $sellerAdditionalIdType = $seller_codeInfo;
        $sellerAdditionalIdNumber = $seller->secondBusinessID;
        $sellerVatNumber = $seller->VATID;
        $sellerVatCategoryNo = $seller->VATCategoryCode;
        $sellerAddress = [
            "streetName" => $seller->street_Arb,
            "additionalNo" => $seller->POBoxAdditionalNum,
            "buildingNumber" => $seller->apartmentNum,
            "city" => $seller->city_Arb,
            "state" => $seller->countrySubdivision_Arb,
            "zipCode" => $seller->POBox,
            "district" => $seller->district_Arb,
            "country" => "SA"
        ];

        // Fields Of Update zatca document [ seller ]:
        $update_seller_sellerName=$sellerName;
        $update_seller_sellerAdditionalIdType=$sellerAdditionalIdType;
        $update_seller_sellerAdditionalIdNumber=$sellerAdditionalIdNumber;
        $update_seller_sellerVatNumber=$sellerVatNumber;
        $update_seller_sellerVatCategoryNo=$sellerVatCategoryNo;
        $update_seller_street_Arb=$seller->street_Arb;
        $update_seller_POBoxAdditionalNum=$seller->POBoxAdditionalNum;
        $update_seller_apartmentNum=$seller->apartmentNum;
        $update_seller_city_Arb=$seller->city_Arb;
        $update_seller_countrySubdivision_Arb=$seller->countrySubdivision_Arb;
        $update_seller_POBox=$seller->POBox;
        $update_seller_district_Arb=$seller->district_Arb;

    }

    
    // Get Customer Id from Order Table:
    $table_orders = $wpdb->prefix . 'wc_orders';

    $customer_Id = $wpdb->get_var( $wpdb->prepare( "SELECT customer_id FROM $table_orders WHERE id = $order_Id") );


    // Update Buyer Data From zatcaCustomer:
    $buyer_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcaCustomer WHERE clientVendorNo = $customer_Id"));
    

    foreach($buyer_update as $buyer){

        $buyer_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype WHERE codeNumber = $buyer->secondBusinessIDType") );

        // "groupVatNumber"=> "NONE"

        $buyerName = $buyer->aName;
        $buyerAddress = [
            "streetName" => $buyer->street_Arb,
            "additionalNo" => $buyer->POBoxAdditionalNum,
            "buildingNumber" => $buyer->apartmentNum,
            "city" => $buyer->city_Arb,
            "state" => $buyer->countrySubdivision_Arb,
            "zipCode" => $buyer->POBox,
            "district" => $buyer->district_Arb,
            "country" => "SA"
        ];
        $buyerAdditionalIdType = $buyer_codeInfo;
        $buyerAdditionalIdNumber = $buyer->secondBusinessID;
        $buyerVatNumber = $buyer->VATID;
        

        // fields to update zatca document [ buyer ]:
        $update_buyer_aName=$buyerName;
        $update_buyer_street_Arb=$buyer->street_Arb;
        $update_buyer_POBoxAdditionalNum=$buyer->POBoxAdditionalNum;
        $update_buyer_apartmentNum=$buyer->apartmentNum;
        $update_buyer_city_Arb=$buyer->city_Arb;
        $update_buyer_countrySubdivision_Arb=$buyer->countrySubdivision_Arb;
        $update_buyer_POBox=$buyer->postalCode;
        $update_buyer_district_Arb=$buyer->district_Arb;
        $update_buyer_buyerAdditionalIdNumber=$buyerAdditionalIdNumber;
        $update_buyer_buyerVatNumber=$buyerVatNumber ;
    }


    // Loop On zatcaDocumentUnit to get data:
    $documents_unit = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaDocumentUnit WHERE documentNo = $doc_no") );

    $totalAmountWithoutVat = 0;
    $totalLineNetAmount = 0;
    $totalVatAmount = 0;
    $totalAmountWithVat = 0;
    $totalDiscountAmount = 0;
    $taxPercent = 0;
    
    $lineItems =[];
    foreach($documents_unit as $unit){

        $lineItems[] = [
            
                "id" => $unit->itemNo,
                "description" => $unit->eName . ' - ' . $unit->aName, // Must be aName Only [ but didint insert aName],
                "linePrice" => [
                    "currencyCode" => "SAR",
                    "amount" => $unit->price
                ],
                "lineQuantity" => $unit->quantity,
                "lineNetAmount" => [
                    "currencyCode" => "SAR",
                    "amount" =>  $unit->netAmount
                ],
                "lineDiscountAmount" => [
                    "currencyCode" => "SAR",
                    "amount" => $unit->discount
                ],
                "lineVatRate" => $unit->vatRate , //$unit->vatRate,
                "lineVatAmount" => [
                    "currencyCode" => "SAR",
                    "amount" => $unit->vatAmount
                ],
                "lineAmountWithVat" => [
                    "currencyCode" => "SAR",
                    "amount" => $unit->amountWithVAT
                ],
                "taxScheme" => "VAT",
                "taxSchemeId" => $taxSchemeId
            
        ];

        $netAmount = $unit->netAmount;
        $vatAmount = $unit->vatAmount;
        $amountWithVAT = $unit->amountWithVAT;
        $discount = $unit->discount;
    
        $totalAmountWithoutVat += $netAmount;
        $totalLineNetAmount += $netAmount;
        $totalVatAmount += $vatAmount;
        $totalAmountWithVat += $amountWithVAT;
        $totalDiscountAmount += $discount;

    }

    $totalAmountWithoutVat = $totalAmountWithoutVat;
    $totalLineNetAmount = $totalLineNetAmount;
    $totalVatAmount = $totalVatAmount;
    $totalAmountWithVat = $totalAmountWithVat;
    $totalDiscountAmount = $totalDiscountAmount;
    $taxPercent = $unit->vatRate; //$unit->vatRate;


    $totalAmountWithoutVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithoutVat];
    $totalLineNetAmount = ["currencyCode" => "SAR", "amount" => $totalLineNetAmount];
    $totalVatAmount = ["currencyCode" => "SAR", "amount" => $totalVatAmount];
    $totalAmountWithVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithVat];
    $totalDiscountAmount = ["currencyCode" => "SAR", "amount" => $totalDiscountAmount];

    if($taxSchemeId == 'S')
    {
        $taxCategory = "S";
        $taxPercent = "15.0";
    }
    else
    {
        $taxCategory = "E";
        $taxPercent = "00.0";
    }

    $supplyDate = "2024-04-29";
    $lastestSupplyDate = "2024-04-29";
    $invoiceCurrencyCode = "SAR";
    $taxCurrencyCode = "SAR";
    $prePaidAmount = ["currencyCode" => "SAR", "amount" => 0];
    $invoiceTypeTransactionCode = [
        "thirdPartyInvoice" => false,
        "nominalInvoice" => $nominalInvoice,
        "exportsInvoice" => $exportsInvoice,
        "summaryInvoice" => $summaryInvoice,
        "selfBilledInvoice" => false
    ];





    // update zatca dpcument:
    $update_data_document = array(

        'seller_aName' =>                   $update_seller_sellerName,
        'seller_secondBusinessIDType' =>    $update_seller_sellerAdditionalIdType,
        'seller_secondBusinessID' =>        $update_seller_sellerAdditionalIdNumber,
        'VATCategoryCodeNo' =>              $update_seller_sellerVatCategoryNo,//documentVatCategoryNo
        'seller_street_Arb' =>              $update_seller_street_Arb,
        'seller_POBoxAdditionalNum' =>      $update_seller_POBoxAdditionalNum,
        'seller_apartmentNum' =>            $update_seller_apartmentNum,
        'seller_city_Arb' =>                $update_seller_city_Arb,
        'seller_countrySubdivision_Arb' =>  $update_seller_countrySubdivision_Arb,
        'seller_POBox' =>                   $update_seller_POBox,
        'seller_district_Arb' =>            $update_seller_district_Arb,
        'buyer_aName' =>                    $update_buyer_aName,
        'buyer_street_Arb' =>               $update_buyer_street_Arb,
        'buyer_POBoxAdditionalNum' =>       $update_buyer_POBoxAdditionalNum,
        'buyer_apartmentNum' =>             $update_buyer_apartmentNum,
        'buyer_city_Arb' =>                 $update_buyer_city_Arb,
        'buyer_countrySubdivision_Arb' =>   $update_buyer_countrySubdivision_Arb,
        'buyer_POBox' =>                    $update_buyer_POBox,
        'buyer_district_Arb' =>             $update_buyer_district_Arb,
        'buyer_secondBusinessID' =>         $update_buyer_buyerAdditionalIdNumber,
        'buyer_VAT' =>                      $update_buyer_buyerVatNumber
    );

    $where = array('documentNo' => $doc_no);

    $update_result = $wpdb->update('zatcaDocument', $update_data_document, $where);

    if($update_result === false) {
        // There was an error inserting data:
        error_log('Update error: ' . $wpdb->last_error);
        echo "Error Update data: " . $wpdb->last_error;
    }


    /* Validation On Vat Id - 
    if “zatcaInvoiceTransactionCode_isExport” then VAT ID for the client must be empty:
    */
    if($exportsInvoice == true){

        $buyerVatNumber = 0;

    }

    // original document number if returned bill
    $originalDoc =  "";
    // reason of return
    $returnReason = "";
    // check if billTypeNo is 23
    if($billTypeNo == 23)
    {
        $originalDoc =  $zatcaRejectedInvoiceNo;
        $returnReason = $reason;
    }
    // check if billTypeNo is 33
    else if($billTypeNo == 33)
    {
        $originalDoc =  "";
        $returnReason = "";
    }

    // Build the array Of Request:
    $data = [
        "invoiceType" => $invoiceType,
        "invoiceTypeCode" => $invoiceTypeCode,
        "id" => $id,
        "issueDate" => $issueDate,
        "issueTime" => $issueTime,
        "icvIncrementalValue" => $icvIncrementalValue,
        "previousHash" => $previousHash,
        "seller" => [
            "name" => $sellerName,
            "additionalIdType" => "OTH",//$sellerAdditionalIdType,
            "additionalIdNumber" => $sellerAdditionalIdNumber,
            "vatNumber" => $sellerVatNumber,
            "groupVatNumber" => "",
            "address" => $sellerAddress
        ],
        "buyer" => [
            "name" => $buyerName,
            "address" => $buyerAddress,
            "additionalIdType" =>"OTH", 
            "additionalIdNumber" => $buyerAdditionalIdNumber,
            "vatNumber" => $buyerVatNumber,
            "groupVatNumber" => ""
        ],
        "lineItems" => $lineItems,
        "totalLineNetAmount" => $totalLineNetAmount,
        "totalAmountWithoutVat" => $totalAmountWithoutVat,
        "totalVatAmount" => $totalVatAmount,
        "totalAmountWithVat" => $totalAmountWithVat,
        "totalDiscountAmount" => ["currencyCode" => "SAR", "amount" => 0],
        "taxCategory" => $taxCategory,
        "taxPercent" => $taxPercent,
        "supplyDate" => $supplyDate,
        "lastestSupplyDate" => $lastestSupplyDate,
        "invoiceCurrencyCode" => $invoiceCurrencyCode,
        "taxCurrencyCode" => $taxCurrencyCode,
        "note" => [
            "reason" => $returnReason,
            "invoiceNo" => $originalDoc
        ],
        "taxExemptionReasonCode" => "",
        "taxExemptionReason" => $zatca_TaxExemptionReason,
        "invoiceNote" => "",
        "prePaidAmount" => $prePaidAmount,
        "invoiceTypeTransactionCode" => $invoiceTypeTransactionCode
    ];

    
    // Encode the array to JSON
    $jsonData = json_encode($data);

    return $jsonData;
}

// Send Request to zatca - Clear Function:
function send_request_to_zatca_report(){

    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    $data = update_zatca1($doc_no);

    $requestArray = json_decode($data, true);

    // get deviceCSID and tokenData from zatcaDevice table
    $deviceCSID = $wpdb->get_var("SELECT zd.deviceCSID 
    FROM zatcaDevice zd, zatcaDocument z 
    WHERE z.documentNo = '$doc_no' and z.deviceNo=zd.deviceNo");

    $tokenData = $wpdb->get_var("SELECT zd.tokenData 
    FROM zatcaDevice zd, zatcaDocument z 
    WHERE z.documentNo = '$doc_no' and z.deviceNo=zd.deviceNo");

    $company_stage = $wpdb->get_var("SELECT zatcaStage 
    FROM zatcaCompany");

    $VATCategoryCodeSubTypeNo = $wpdb->get_var("SELECT VATCategoryCodeSubTypeNo 
    FROM zatcaCompany");

    $msg = '';

    // Validation Fields:
    $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];

    $seller_secondBusinessId_companyStage_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber == null && $company_stage == 2) ? true : false;
    $seller_secondBusinessId_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber == null) ? true : false;
    
    $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
    $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

    $buyer_additional_id = $requestArray['buyer']['additionalIdNumber'];
    $buyer_additional_id_validation = (isset($buyer_additional_id ) && $buyer_additional_id !=null) ? true : false;

    $buyer_arabic_name = $requestArray['buyer']['name'];

    //$buyerArabicName_validation = ($buyer_arabic_name == '' && ($VATCategoryCodeSubTypeNo == 13 || $VATCategoryCodeSubTypeNo == 14)) ? true : false;
    $buyerArabicName_validation = ($buyer_arabic_name == '') ? true : false;

    /*if($buyer_additionalNo_validation == false)
    { 
      // Validation on additionalNo - customer [ buyer ]:  
        $send_response = [
            'status' => 'insert_buyer_poBox_additionalNo',
            'msg' => $buyer_additionalNo
        ];
    }
    elseif($buyer_additional_id_validation == false)
    { 
      // Validation on additionalNo - customer [ buyer ]:  
        $send_response = [
            'status' => 'insert_buyer_additional_id',
            'msg' => $buyer_additional_id
        ];
    }
    else if($seller_secondBusinessId_companyStage_validation == true)
    {
        $send_response = [
            'status' => 'seller_second_business_id',
            'msg' => ''];
    }
    else*/ if($buyerArabicName_validation == true)
    {
        $send_response = [
            'status' => 'buyer_arabic_name',
            'msg' => $buyer_arabic_name];
    }
    else if($seller_secondBusinessId_validation == true)
    {
        $send_response = [
            'status' => 'seller_second_business_id',
            'msg' => ''];
    }
    else{

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Report?deviceID='. $deviceCSID .'&skipPortalValidation=false',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $tokenData
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            $send_response = [
                'status' => 'error',
                'msg' => 'cURL Error:' . $error . '(Error Code:'. $errorCode . ')'
            ];
            //echo "cURL Error: $error (Error Code: $errorCode)";
        }
        
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       

        $http_status_msg = "HTTP Status Code: " . $http_status . "\n";

        curl_close($curl);

        if ($response === false) {
            $send_response = [
                'status' => 'error',
                'msg' => 'Curl error: ' . curl_error($curl)
            ];
            //echo 'Curl error: ' . curl_error($curl);
        } 
        else {
            // echo 'HTTP status code: ' . $http_status;
            // echo 'Response: ' . $response;
        }

        $responseArray = json_decode($response, true);

        $statusCode = $responseArray['zatcaStatusCode'];
        $isZatcaValid = $responseArray['isValidationFromZatca'];
        $reportingStatus = $responseArray['reportingStatus'];
        $validationResults = $responseArray['validationResults'];
        $hashed = $responseArray['hash'];
        $qrCode = $responseArray['generatedQR'];
        $response_date = date('Y-m-d H:i:s');
        $warningMessage='';
        $clearedInvoice = $responseArray['clearedInvoice'];
        $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
        if($responseArray['zatcaStatusCode'] == 400 || $responseArray['zatcaStatusCode'] == null || $responseArray['zatcaStatusCode'] == 0)
        {
            if($responseArray['portalResults'] == "Object reference not set to an instance of an object.")
            {
                $errorMessage = "The device signature or token data may not be correct , please check and try again!";
            }
            else
            {
                $errorMessage = $responseArray['portalResults'];
            }
        }

        // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
        $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                FROM zatcaDocument 
                                                                ORDER BY zatcaResponseDate DESC 
                                                                LIMIT 1"));
        
        if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
            foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                if (isset($Message['message'])) {
                    $warningMessage .= $Message['message'] . "\n";
                }else{

                    echo 'WRONG';
                }
            }
        }


        
        // Check If response Valid:
        if($reportingStatus == 'REPORTED'){

            if($statusCode == '200'){

                // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $doc_no"));

                if($originalDocNo != NULL)
                {
                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                    "zatcaAcceptedReissueInvoiceNo" => $doc_no];
                    $whereOriginal = array('documentNo' => $originalDocNo);
    
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                }

                //  update zatca document xml fields with response Data:
                $zatcaDocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 0
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcaDocumentxml_update_response_result === false) {
                    $send_response = [
                        'status' => 'errorxml',
                        'msg' => 'There was an error updating on zatcaDocumentxml in the field ' . $wpdb->last_error
                    ];
                    // Handle error
                   // $msg = "There was an error updating on zatcaDocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocumentxml_update_response_result === 0) {
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    // No rows affected
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                
                }  
    
                // update zatca document fields with response Data:
                $zatcaDocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 1,
                    "previousInvoiceHash" => $hashed
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcaDocument_update_response_result === false) {
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdateresponse',
                        'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                }elseif ($zatcaDocument_update_response_result === 0) {
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    // No rows affected
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // update zatca device fields with last document submitted:
                $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $doc_no") );

                $zatcaDevice_update_response_data = [
                    "lastHash" => $hashed,
                    "lastDocumentNo" => $doc_no,
                    "lastDocumentDateTime" => $hashed
                ];
                $where1 = array('deviceNo' => $device_no);

                $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);

                // Check for errors
                if ($zatcaDevice_update_response_result === false) {
                    $send_response = [
                        'status' => 'errorupdatedevice',
                        'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                    ];
                    // Handle error
                    //$msg = "There was an error updating zatcaDevice on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDevice_update_response_result === 0) {
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    // No rows affected
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                insert_encrypted_row($hashed, $doc_no, $device_no);
                $send_response = [
                    'status' => 'success',
                    'msg' => __("Document Submitted Successfully, Zatca Status Code Is ", "zatca") . $statusCode . __(".. Request Is Success", "zatca") . $http_status_msg
                ];

                //$msg = 'Document Submitted Successfully, Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
            
            }elseif($statusCode == '202'){

                // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $doc_no"));

                if($originalDocNo != NULL)
                {
                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                    "zatcaAcceptedReissueInvoiceNo" => $doc_no];
                    $whereOriginal = array('documentNo' => $originalDocNo);
    
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                }

                 // update zatca document xml fields with response Data:
                $zatcaDocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 1
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcaDocumentxml_update_response_result === false) {
                    $send_response = [
                        'status' => 'errorxml',
                        'msg' => 'There was an error updating on zatcaDocumentxml in the field' . $wpdb->last_error
                    ];
                    // Handle error
                    //$msg = "There was an error updating on zatcaDocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocumentxml_update_response_result === 0) {
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }   
    
                // update zatca document fields with response Data:
                $zatcaDocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 2,
                    "previousInvoiceHash" => $hashed,
                    "zatcaErrorResponse" => $warningMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcaDocument_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdateresponse',
                        'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocument_update_response_result === 0) {
                   
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // update zatca device fields with last document submitted:
                $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $doc_no") );

                $zatcaDevice_update_response_data = [
                    "lastHash" => $hashed,
                    "lastDocumentNo" => $doc_no,
                    "lastDocumentDateTime" => $hashed
                ];
                $where1 = array('deviceNo' => $device_no);

                $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);

                // Check for errors
                if ($zatcaDevice_update_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdatedevice',
                        'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDevice on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDevice_update_response_result === 0) {
                   
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                insert_encrypted_row($hashed, $doc_no, $device_no);
                $send_response = [
                    'status' => 'warning',
                    'msg' => __("Document Submitted with Warning, Zatca Status Code Is ", "zatca") . $statusCode . '......' . $warningMessage
                ];

                //$msg = 'Document Submitted Successfully, Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
            }
          
        }
        else{

            if($http_status == '400'){

                // update zatca document fields with response Data:
                $zatcaDocument_error_response_data = [

                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 3,
                    "zatcaErrorResponse" => $errorMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_error_response_data, $where);
                
                // Check for errors
                if ($zatcaDocument_error_response_result === false) {
                    
                    // Handle error
                    $send_response = [
                        'status' => 'errorupdateresponse',
                        'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                    ];
                    //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcaDocument_error_response_result === 0) {
                    
                    // No rows affected
                    $send_response = [
                        'status' => 'no_rows_affected',
                        'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                    ];
                    //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // check if have error message or not:
                if(is_array($validationResults)){

                    $send_response = [
                        'status' => 'error',
                        'msg' => $http_status_msg . __(" Error: ", "zatca") . $errorMessage
                    ];
                    //$msg = $http_status_msg . ' Error: ' . $errorMessage;

                }else{
                    $send_response = [
                        'status' => 'error',
                        'msg' => $http_status_msg . ' Error: ' . $validationResults
                    ];
                    //$msg = $http_status_msg . ' Error: ' . $validationResults;
                }

                
            
            }
            else{

                $send_response = [
                    'status' => '',
                    'msg' => $response
                ];
                //$msg = $response;
            }

        }

        // Log the send to zatca:
        $user_login = wp_get_current_user()->user_login;
        $user_id = wp_get_current_user()->ID;
        log_send_to_zatca($user_login, $user_id);
        
    }

    $send_response1 = [

        'msg' => $send_response,
        'validationResults' => $validationResults,
        'responseArray' => $responseArray,
        'data' => $data

    ];

    wp_send_json($send_response1);
        
    die();
}


//function to insert new copy of woocommerce to database [Reissue]
function insert_woocommerce_copy($docNo){
    global $wpdb;
    $table_orders = $wpdb->prefix . 'wc_orders';
    $table_posts = $wpdb->prefix . 'posts';


    // Get the last order ID in the table  
    $last_order_id = $wpdb->get_var($wpdb->prepare("SELECT MAX(id) FROM $table_posts"));

    // get invoiceNo from zatcaDocument table that's mean original_order_id also
    $original_order_id = $wpdb->get_var($wpdb->prepare("SELECT invoiceNo FROM zatcaDocument WHERE documentNo = $docNo"));

    $new_order_id = $last_order_id + 1;

    $post_type = 'shop_order_placehold';

    $order_status = 'wc-processing';

    /////////////////////////////////////////////////////////////////////
    // Insert new post data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO $table_posts 
            SELECT $new_order_id, post_author , post_date, post_date_gmt, post_content, post_title, post_excerpt, post_status, comment_status, ping_status, post_password, post_name, to_ping, pinged, post_modified, post_modified_gmt, post_content_filtered, post_parent, guid, menu_order, post_type, post_mime_type,comment_count  
            FROM $table_posts 
            WHERE ID = %d;", $original_order_id)
    );

    // Copy post meta  
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO {$wpdb->prefix}postmeta (post_id, meta_key, meta_value)  
            SELECT %d, meta_key, meta_value  
            FROM {$wpdb->prefix}postmeta  
            WHERE post_id = %d;", $new_order_id, $original_order_id)  
    );

    ////////////////////////////////////////////////////////////////////////
    // Insert new order data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO $table_orders 
            SELECT $new_order_id, '$order_status' , currency, type, tax_amount, total_amount, customer_id, billing_email, date_created_gmt, date_updated_gmt, parent_order_id, payment_method, payment_method_title, transaction_id, ip_address, user_agent, customer_note  
            FROM $table_orders 
            WHERE id = %d;", $original_order_id)
    );

    // Copy order meta  
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO {$wpdb->prefix}wc_order_meta (order_id, meta_key, meta_value)  
            SELECT %d, meta_key, meta_value  
            FROM {$wpdb->prefix}wc_order_meta  
            WHERE order_id = %d;", $new_order_id, $original_order_id)  
    );

    /////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////
    //wp_woocommerce_order_items table
    // Query to get the original order items based on the order ID 
    $table_woocommerce_order_items = $wpdb->prefix . 'woocommerce_order_items'; 
    $order_items_query = $wpdb->prepare("SELECT * FROM $table_woocommerce_order_items WHERE order_id = %d", $original_order_id);  
    $order_items = $wpdb->get_results($order_items_query, ARRAY_A);

    // Insert copies of the order items with new order IDs  
    if ($order_items)
    {
        $new_woocommerce_order_id = $new_order_id; // The new order ID 

        foreach ($order_items as $order_item) 
        {
            $new_order_item_data = [
                'order_id' => $new_woocommerce_order_id,
                'order_item_name' => $order_item['order_item_name'],
                'order_item_type' => $order_item['order_item_type']
                ];
                $wpdb->insert($table_woocommerce_order_items, $new_order_item_data);
        }
    }

    // wp_woocommerce_order_itemmeta table
    // Query to get the original order item meta based on the order item ID  
    $table_woocommerce_order_itemmeta = $wpdb->prefix . 'woocommerce_order_itemmeta';
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO $table_woocommerce_order_itemmeta (order_item_id, meta_key, meta_value)  
            SELECT new_item.order_item_id, meta.meta_key, meta.meta_value  
            FROM $table_woocommerce_order_items new_item
            JOIN $table_woocommerce_order_items old_item ON old_item.order_id = '$original_order_id'  
            JOIN $table_woocommerce_order_itemmeta meta ON old_item.order_item_id = meta.order_item_id 
            WHERE new_item.order_id = %d;", $new_woocommerce_order_id));
    ///////////////////////////////////////////////////////////////////////////////

    // function to insert new copy of date to zatcaDocument table
    insert_zatcaDocument_copy($docNo, $new_order_id);

}

//function to insert new copy of zatcaDocument to database [Reissue]
function insert_zatcaDocument_copy($docNo, $newInvoiceNo){
    global $wpdb;

    // Table Name:
    $table_name_device = 'zatcaDevice';

    // Get the current Active device
    $device__No = $wpdb->get_var($wpdb->prepare(
        "SELECT deviceNo
        FROM $table_name_device
        WHERE deviceStatus = 0")
        );
    $query = $wpdb->prepare("SELECT IFNULL(MAX(documentNo), 0) FROM zatcaDocument WHERE deviceNo = $device__No");
    $doc__no = $wpdb->get_var($query);
    $last_document_id = $doc__no + 1;

    $new_document_id = $last_document_id;

    $previousDocument = $wpdb->get_var($wpdb->prepare("SELECT MAX(documentNo) from zatcaDocumentxml WHERE previousInvoiceHash IS NOT NULL and invoiceHash IS NOT NULL"));
    $previousDocHash = $wpdb->get_var($wpdb->prepare("SELECT invoiceHash from zatcaDocumentxml 
    WHERE previousInvoiceHash IS NOT NULL and invoiceHash IS NOT NULL and documentNo=$previousDocument"));

    if($previousDocument == NULL)
    {
        $previousDocument = 0;
    }
    if($previousDocHash)
    {
        $previousDocHash = 'NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==';
    }

    $uuid = wp_generate_uuid4();

    // Get Device No from zatcaDevice:
        // Prepare the query with a condition on CsID_ExpiryDate not expire and is active:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW() AND deviceStatus = 0") );
        foreach($results as $device)
        {
            if($wpdb->num_rows > 0)
            { // If Date Valid:
                $deviceNo = $device->deviceNo;
            }
            else
            { // If No Date Valid
                // $msg = "You Must Insert Valid CsID_ExpiryDate";
            }
        }

        $device_No = $deviceNo;


    // Insert new order data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO zatcaDocument  
            SELECT vendorId, $new_document_id, $device_No , $newInvoiceNo, buildingNo, billTypeNo, dateG, deliveryDate,
            gaztLatestDeliveryDate, zatcaInvoiceType, amountPayed01, amountPayed02, amountPayed03, amountPayed04,
            amountPayed05, amountCalculatedPayed, returnReasonType, subTotal, subTotalDiscount, taxRate1_Percentage,
            taxRate1_Total, subNetTotal, subNetTotalPlusTax, amountLeft, isAllItemsReturned, isZatcaRetuerned, reason,
            $previousDocument, '$previousDocHash', seller_secondBusinessIDType, seller_secondBusinessID, buyer_secondBusinessIDType,
            buyer_secondBusinessID, VATCategoryCodeNo, VATCategoryCodeSubTypeNo, zatca_TaxExemptionReason, zatcaInvoiceTransactionCode_isNominal,
            zatcaInvoiceTransactionCode_isExports, zatcaInvoiceTransactionCode_isSummary, zatcaInvoiceTransactionCode_is3rdParty,
            zatcaInvoiceTransactionCode_isSelfBilled, '$uuid', seller_VAT, seller_aName, seller_eName, seller_apartmentNum,
            seller_countrySubdivision_Arb, seller_countrySubdivision_Eng, seller_street_Arb, seller_street_Eng, seller_district_Arb,
            seller_district_Eng, seller_city_Arb, seller_city_Eng, seller_country_Arb, seller_country_Eng, seller_country_No,
            seller_PostalCode, seller_POBox, seller_POBoxAdditionalNum, buyer_VAT, buyer_aName, buyer_eName, buyer_apartmentNum,
            buyer_countrySubdivision_Arb, buyer_countrySubdivision_Eng, buyer_street_Arb, buyer_street_Eng,
            buyer_district_Arb, buyer_district_Eng, buyer_city_Arb, buyer_city_Eng, buyer_country_Arb, buyer_country_Eng,
            buyer_country_No, buyer_PostalCode, buyer_POBox, buyer_POBoxAdditionalNum, 0, NULL, NULL, 1, zatcaRejectedBuildingNo,
            %d, zatcaAcceptedReissueBuildingNo, zatcaAcceptedReissueInvoiceNo, NULL, row_timestamp
            FROM zatcaDocument  
            WHERE documentNo = %d", $docNo, $docNo)
    );

    // function of insert new copy to zatcaDocumentUnit table
    insert_zatcaDocumentUnit_copy($newInvoiceNo, $device_No, $new_document_id);

    //insert new row into zatcaDocumentxml
    $wpdb->query($wpdb->prepare("INSERT INTO zatcaDocumentxml (documentNo,deviceNo) VALUES (%d, %d)", $new_document_id, $device_No));
}

//function to insert new copy of zatcaDocumentUnits to database [Reissue]
function insert_zatcaDocumentUnit_copy($neworderId, $deviceNo, $newDocNo)
{
    global $wpdb;

    $device_No = $deviceNo;
    $last_doc = $newDocNo;

    // ###########################################
    // Insert Copy of Data to zatcaDocumentUnit:##
    // ###########################################

    // Prefix Of woo-order-item
    $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';

    // get item id for item [ line_item ]:
    $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $neworderId AND order_item_type = 'line_item'"));

    // get item id for tax [ tax ]:
    $doc_unit_item_id_rate_percentage = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $neworderId AND order_item_type = 'tax'"));

    // get item id for discount [ tax ]:
    $doc_unit_item_id_coupon = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $neworderId AND order_item_type = 'coupon'"));


    // Funtion to handle order discount:
    function get_qty_percentage_for_item1($orderId){
    
        global $wpdb;
        // Prefix Of woo-order-item
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
    
        
        // define items number & Number of Items & Item Qty & Total Qty Of Order:
        
        $total_order_qty = 0;
    
        $array_items = [];
        
        $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
        
        foreach($doc_unit_item_id as $itemId){
    
            $item_qty = wc_get_order_item_meta( $itemId->order_item_id , '_qty', true );
    
            $order_discount_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
    
            $order_discount = wc_get_order_item_meta($order_discount_id, 'discount_amount', true);
            
            $array_items[$itemId->order_item_id] = $item_qty;
    
            $total_order_qty +=  + $item_qty;
    
        }
    
        $array_items['order_discount'] = $order_discount;
        $array_items['total_order_qty'] = $total_order_qty;
    
    
        //define percentage of each item:
    
        $updated_total_qty = [];
    
        foreach ($array_items as $key => $value) {
            if (is_numeric($key)) {  // Check for integer keys
                $updated_total_qty[$key] = $value / $array_items["total_order_qty"] * $array_items["order_discount"];
            }
        }
    
        // Now $updated_total_qty will contain the updated quantities for items with numeric keys
        return $updated_total_qty;
        
    }

    //
    foreach($doc_unit_item_id as $item){
    
                        
        // Item Qty:
        $doc_unit_item_qty = wc_get_order_item_meta( $item->order_item_id , '_qty', true );
        
        // Product_id:
        $doc_unit_product_id =wc_get_order_item_meta($item->order_item_id, '_product_id', true);
        
        // Tax Percentage:
        $doc_unit_vatRate =wc_get_order_item_meta($doc_unit_item_id_rate_percentage, 'rate_percent', true);

        // Item name:
        $doc_unit_sku = get_post_meta($doc_unit_product_id, '_sku', true);
        
        // Item Price:
        $doc_unit_price = get_post_meta($doc_unit_product_id, '_price', true);
        $final_price = number_format((float)$doc_unit_price, 6, '.', '');

        // Discount:
        $doc_unit_discount = wc_get_order_item_meta($doc_unit_item_id_coupon, 'discount_amount', true);

        // Subttotal [ price * quantity ]:
        $doc_unit_subtotal = $doc_unit_price * $doc_unit_item_qty;


        // Get the Function of define discount by line:
        $array_of_discounts = get_qty_percentage_for_item1($neworderId);

        // Loop to get Each Item Discount:
        foreach($array_of_discounts as $key => $value)
        {

            if($key == $item->order_item_id)
            {

                $final_item_discount= $array_of_discounts[$key];

                // netAmount [ ((price * quantity)-discount) ]:
                $doc_unit_netAmount = $doc_unit_subtotal - $final_item_discount;
                $final_netAmount = number_format((float)$doc_unit_netAmount, 2, '.', '');

                // vatAmount [ netAmount*vatRate ]:
                $doc_unit_vatAmount = ($doc_unit_netAmount * $doc_unit_vatRate) / 100;
                $final_vatAmount = number_format((float)$doc_unit_vatAmount, 2, '.', '');

                // amountWithVat [ netAmount+vatAmount ]:
                $doc_unit_amountWithVat = $doc_unit_netAmount + $doc_unit_vatAmount;
                $final_amountWithVat = number_format((float)$doc_unit_amountWithVat, 2, '.', '');
            

                // Insert Data To zatcaDocumentUnit:
                $insert_doc_unit = $wpdb->insert(
                    'zatcaDocumentUnit',
                    [
                        'deviceNo'      => $device_No,
                        'documentNo'    => $last_doc,
                        'itemNo'        => $item->order_item_id,
                        'eName'         => $doc_unit_sku,
                        'price'         => $final_price,
                        'quantity'      => $doc_unit_item_qty,
                        'discount'      => $final_item_discount,
                        'vatRate'       => $doc_unit_vatRate,
                        'vatAmount'     => $final_vatAmount,
                        'netAmount'     => $final_netAmount,
                        'amountWithVAT' => $final_amountWithVat
                    ]
                );
            }
        }

    }

    // function to send the new one to zatca and handle the response from zatca depend on zatcaSuccessResponse code
    //send_reissue_zatca($newDocNo);

}

//function to send reissue request to zatca [Reissue]
function send_reissue_zatca($docNo)
{
    global $wpdb;
    // Get the invoice type (B2B or B2C) 
    $zatcaInvoice_type = $wpdb->get_var($wpdb->prepare("SELECT zatcaInvoiceType FROM zatcaDocument WHERE documentNo =  $docNo"));

    // check invoice type to detect CLEAR or REPORT
    // if B2B
    if($zatcaInvoice_type == 1)
    {
        $data = update_zatca($docNo);

        $requestArray = json_decode($data, true);

        // get deviceCSID and tokenData from zatcaDevice table
        $deviceCSID = $wpdb->get_var("SELECT zd.deviceCSID 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $tokenData = $wpdb->get_var("SELECT zd.tokenData 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $company_stage = $wpdb->get_var("SELECT zatcaStage 
            FROM zatcaCompany");

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
        $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
        $seller_secondBusinessId_companyStage_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber ==null && $company_stage == 2) ? true : false;

        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        $buyer_additional_id = $requestArray['buyer']['additionalIdNumber'];
        $buyer_additional_id_validation = (isset($buyer_additional_id ) && $buyer_additional_id !=null) ? true : false;

        // Validate buyer vat number
        $buyer_vatNo = $requestArray['buyer']['vatNumber'];
        $invoicetransactioncode_isexports = $wpdb->get_var("SELECT zatcaInvoiceTransactionCode_isExports FROM zatcaDocument Where documentNo = '$docNo'");
        
        $buyer_vatNo_validation1 = (
            $buyer_vatNo == null && 
            ($invoicetransactioncode_isexports == null)) ? true : false;
        
        $buyer_vatNo_validation0 = ($buyer_vatNo == 0) ? true : false;

        // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($seller_additionalIdNumber_validation == false)
        {
            $send_response = [
                'status' => 'insert_seller_additional_id',
                'msg' => ''
            ];
        }
        elseif($buyer_additionalNo_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_poBox_additionalNo',
                'msg' => $buyer_additionalNo
            ];
        }
        elseif($buyer_additional_id_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_additional_id',
                'msg' => $buyer_additional_id
            ];
        }
        else if($buyer_vatNo_validation1 == true)
        {
            $send_response = [
                'status' => 'isexport1_buyervat',
                'msg' => $buyer_vatNo
            ];
            
        }
        else if($buyer_vatNo_validation0 == true)
        {
            $send_response = [
                'status' => 'isexport0_buyervat',
                'msg' => $requestArray['buyer']['vatNumber']
            ];
        }
        else if($seller_secondBusinessId_companyStage_validation == true)
        {
            $send_response = [
                'status' => 'seller_second_business_id',
                'msg' => ''];
        }

        else{
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Clear?deviceID='. $deviceCSID .'&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $tokenData
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                $send_response = [
                    'status' => 'error',
                    'msg' => 'cURL Error:' . $error . '(Error Code:'. $errorCode . ')'
                ];
                //echo "cURL Error: $error (Error Code: $errorCode)";
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                $send_response = [
                    'status' => 'error',
                    'msg' => 'Curl error: ' . curl_error($curl)
                ];
                //echo 'Curl error: ' . curl_error($curl);
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $clearanceStatus = $responseArray['clearanceStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
            if($responseArray['zatcaStatusCode'] == 400 || $responseArray['zatcaStatusCode'] == null || $responseArray['zatcaStatusCode'] == 0)
            {
                $errorMessage = $responseArray['portalResults'];
            }
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcaDocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) 
                    {
                        $warningMessage .= $Message['message'] . "\n";
                    }
                    else
                    {
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($clearanceStatus == 'CLEARED'){
    
                if($statusCode == '200'){
                    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////

                    //  update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field ' . $wpdb->last_error
                        ];
                        // Handle error
                        //$msg = "There was an error updating on zatcaDocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        // No rows affected
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                        //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                    }elseif ($zatcaDocument_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                        //$msg = "There was an error updating zatcaDevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }

                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
                    $send_response = [
                        'status' => 'success',
                        'msg' => __("Document Submitted Successfully, Zatca Status Code Is ", "zatca") . $statusCode . __(".. Request Is Success", "zatca") . $http_status_msg
                    ];
    
                    //$msg = 'Document Submitted Successfully, Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////


                     // update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field' . $wpdb->last_error
                        ];
                        //$msg = "There was an error updating on zatcaDocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                        //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcaDocument_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                        //$msg = "There was an error updating zatcaDevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);

                    $send_response = [
                        'status' => 'warning',
                        'msg' => __("Document Submitted with Warning, Zatca Status Code Is ", "zatca") . $statusCode . '......' . $warningMessage
                    ];

                    //$msg = 'Document Submitted with Warning, Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
                }
              
            }else{
    
                if($http_status == '400'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "isZatcaReissued" => 1
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////

                    
                    // update zatca document fields with response Data:
                    $zatcaDocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_error_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                        //$msg = "There was an error updating zatcaDocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcaDocument_error_response_result === 0) {
                        
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                        //$msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $send_response = [
                            'status' => 'http_status_msg',
                            'msg' => $http_status_msg . __(" Error: ", "zatca") . $errorMessage
                        ];
    
                    }else{
    
                        $send_response = [
                            'status' => 'http_status_msg',
                            'msg' => $http_status_msg . __(" Error: ", "zatca") . $validationResults
                        ];
                    }
    
                    
                
                }
                else if($http_status == '303')
                {
                    // update zatca document fields with response Data:
                    $zatcaDocument_error_response_data = [
                        "zatcaB2B_isForced_To_B2C" => 1];
                    $where = array('documentNo' => $docNo);
                    $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument',
                    $zatcaDocument_error_response_data, $where);
                    $send_response = [
                        'status' => '303',
                        'msg' => $response
                    ];
                }
            else{
                    $send_response = [
                        'status' => '',
                        'msg' => $response
                    ];
                    //$msg = $response;
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
    
        }
        $send_response1 = [
    
            'msg' => $send_response,
            'validationResults' => $validationResults,
            'responseArray' => $responseArray,
            'data' => $data

        ];

        wp_send_json($send_response1);


        //end if
    }
    // if not B2B
    else
    {
        $data = update_zatca1($docNo);

        $requestArray = json_decode($data, true);

        // get deviceCSID and tokenData from zatcaDevice table
        $deviceCSID = $wpdb->get_var("SELECT zd.deviceCSID 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $tokenData = $wpdb->get_var("SELECT zd.tokenData 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $company_stage = $wpdb->get_var("SELECT zatcaStage 
        FROM zatcaCompany");

        $VATCategoryCodeSubTypeNo = $wpdb->get_var("SELECT VATCategoryCodeSubTypeNo 
        FROM zatcaCompany");

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];

        $seller_secondBusinessId_companyStage_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber == null && $company_stage == 2) ? true : false;
    
        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        $buyer_additional_id = $requestArray['buyer']['additionalIdNumber'];
        $buyer_additional_id_validation = (isset($buyer_additional_id ) && $buyer_additional_id !=null) ? true : false;

        $buyer_arabic_name = $requestArray['buyer']['name'];

        $buyerArabicName_validation = ($buyer_arabic_name == '' && ($VATCategoryCodeSubTypeNo == 13 || $VATCategoryCodeSubTypeNo == 14)) ? true : false;

        // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($buyer_additionalNo_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_poBox_additionalNo',
                'msg' => $buyer_additionalNo
            ];
        }
        elseif($buyer_additional_id_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_additional_id',
                'msg' => $buyer_additional_id
            ];
        }
        else if($seller_secondBusinessId_companyStage_validation == true)
        {
            $send_response = [
                'status' => 'seller_second_business_id',
                'msg' => ''];
        }
        else if($buyerArabicName_validation == true)
        {
            $send_response = [
                'status' => 'buyer_arabic_name',
                'msg' => $buyer_arabic_name];
        }
        else
        {
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Report?deviceID='. $deviceCSID .'&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $tokenData
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                $send_response = [
                    'status' => 'error',
                    'msg' => 'cURL Error:' . $error . '(Error Code:'. $errorCode . ')'
                ];
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
           
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                $send_response = [
                    'status' => 'error',
                    'msg' => 'Curl error: ' . curl_error($curl)
                ];
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $reportingStatus = $responseArray['reportingStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
            if($responseArray['zatcaStatusCode'] == 400 || $responseArray['zatcaStatusCode'] == null || $responseArray['zatcaStatusCode'] == 0)
            {
                $errorMessage = $responseArray['portalResults'];
            }
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcaDocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) {
                        $warningMessage .= $Message['message'] . "\n";
                    }else{
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($reportingStatus == 'REPORTED'){
    
                if($statusCode == '200'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////


                    //  update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field ' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    }elseif ($zatcaDocument_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ]; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ]; 
                    }
    
                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);

                    $send_response = [
                        'status' => 'success',
                        'msg' => __("Document Submitted Successfully, Zatca Status Code Is ", "zatca") . $statusCode . __(".. Request Is Success", "zatca") . $http_status_msg
                    ];
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////


                     // update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 1
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocument_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ]; 
                    }
    
                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);

                    $send_response = [
                        'status' => 'warning',
                        'msg' => __("Document Submitted with Warning, Zatca Status Code Is ", "zatca") . $statusCode . '......' . $warningMessage
                    ];
                }
              
            }
            else
            {
    
                if($http_status == '400'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "isZatcaReissued" => 1
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////


                    // update zatca document fields with response Data:
                    $zatcaDocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_error_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocument_error_response_result === 0) {
                        
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $send_response = [
                            'status' => 'error',
                            'msg' => $http_status_msg . __(" Error: ", "zatca") . $errorMessage
                        ];
    
                    }else{
    
                        $send_response = [
                            'status' => 'error',
                            'msg' => $http_status_msg . ' Error: ' . $validationResults
                        ];
                    }
    
                    
                
                }
                else{
    
                    $send_response = [
                        'status' => '',
                        'msg' => $response
                    ];
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
    
        }
        $send_response1 = [
    
            'msg' => $send_response,
            'validationResults' => $validationResults,
            'responseArray' => $responseArray,
            'data' => $data

        ];

        wp_send_json($send_response1);
    }
}

//function to insert new copy of data and fire the reissue button action [Reissue]
function send_reissue_request_to_zatca(){
    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    // update  isZatcaReissued with true in original document
    $wpdb->update('zatcaDocument', array('isZatcaReissued' => '0'),array('documentNo' => $doc_no),array('%s'),array('%d'));

    // function to insert new copy to woocommerce and new copy to zatcaDocument and new copy to zatcaDocumentUnit
    insert_woocommerce_copy($doc_no);

    $send_response = [
        'status' => 'zatcaIssueDone',
        'msg' => __("Reissued Successfully", "zatca")
    ];


    wp_send_json($send_response);


}

////////////////////////////////////////Return Functions//////////////////////////////////////////////

//function to insert new copy of zatcaDocument to database [Return]
function insert_zatcaDocument_returned($docNo, $invoice_no){
    global $wpdb;

    // Table Name:
    $table_name_device = 'zatcaDevice';

    // Get the current Active device
    $device__No = $wpdb->get_var($wpdb->prepare(
        "SELECT deviceNo
        FROM $table_name_device
        WHERE deviceStatus = 0")
        );
    $query = $wpdb->prepare("SELECT IFNULL(MAX(documentNo), 0) FROM zatcaDocument WHERE deviceNo = $device__No");
    $doc__no = $wpdb->get_var($query);
    $last_document_id = $doc__no;

    $new_document_id = $last_document_id + 1;

    $uuid = wp_generate_uuid4();

    // Get Device No from zatcaDevice:
        // Prepare the query with a condition on CsID_ExpiryDate not expire and is active:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW() AND deviceStatus = 0") );
        foreach($results as $device)
        {
            if($wpdb->num_rows > 0)
            { // If Date Valid:
                $deviceNo = $device->deviceNo;
            }
            else
            { // If No Date Valid
                // $msg = "You Must Insert Valid CsID_ExpiryDate";
            }
        }

        $device_No = $deviceNo;


        $previousDocument = $wpdb->get_var($wpdb->prepare("SELECT MAX(documentNo) from zatcaDocumentxml WHERE previousInvoiceHash IS NOT NULL and invoiceHash IS NOT NULL"));
        $previousDocHash = $wpdb->get_var($wpdb->prepare("SELECT invoiceHash from zatcaDocumentxml 
        WHERE previousInvoiceHash IS NOT NULL and invoiceHash IS NOT NULL and documentNo=$previousDocument"));

        if($previousDocument == NULL)
        {
            $previousDocument = 0;
        }
        if($previousDocHash)
        {
            $previousDocHash = 'NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==';
        }

    // Insert new order data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO zatcaDocument  
            SELECT vendorId, $new_document_id, $device_No , $invoice_no, buildingNo, 23, dateG, deliveryDate,
            gaztLatestDeliveryDate, zatcaInvoiceType, amountPayed01, amountPayed02, amountPayed03, amountPayed04,
            amountPayed05, amountCalculatedPayed, returnReasonType, subTotal, subTotalDiscount, taxRate1_Percentage,
            taxRate1_Total, subNetTotal, subNetTotalPlusTax, amountLeft, isAllItemsReturned, 0, reason,
            $previousDocument, '$previousDocHash', seller_secondBusinessIDType, seller_secondBusinessID, buyer_secondBusinessIDType,
            buyer_secondBusinessID, VATCategoryCodeNo, VATCategoryCodeSubTypeNo, zatca_TaxExemptionReason, zatcaInvoiceTransactionCode_isNominal,
            zatcaInvoiceTransactionCode_isExports, zatcaInvoiceTransactionCode_isSummary, zatcaInvoiceTransactionCode_is3rdParty,
            zatcaInvoiceTransactionCode_isSelfBilled, '$uuid', seller_VAT, seller_aName, seller_eName, seller_apartmentNum,
            seller_countrySubdivision_Arb, seller_countrySubdivision_Eng, seller_street_Arb, seller_street_Eng, seller_district_Arb,
            seller_district_Eng, seller_city_Arb, seller_city_Eng, seller_country_Arb, seller_country_Eng, seller_country_No,
            seller_PostalCode, seller_POBox, seller_POBoxAdditionalNum, buyer_VAT, buyer_aName, buyer_eName, buyer_apartmentNum,
            buyer_countrySubdivision_Arb, buyer_countrySubdivision_Eng, buyer_street_Arb, buyer_street_Eng,
            buyer_district_Arb, buyer_district_Eng, buyer_city_Arb, buyer_city_Eng, buyer_country_Arb, buyer_country_Eng,
            buyer_country_No, buyer_PostalCode, buyer_POBox, buyer_POBoxAdditionalNum, 0, NULL, NULL, zatcaB2B_isForced_To_B2C, zatcaRejectedBuildingNo,
            %d, zatcaAcceptedReissueBuildingNo, zatcaAcceptedReissueInvoiceNo, isZatcaReissued, row_timestamp
            FROM zatcaDocument  
            WHERE documentNo = %d", $docNo, $docNo)
    );

    //insert new row into zatcaDocumentxml
    $wpdb->query($wpdb->prepare("INSERT INTO zatcaDocumentxml (documentNo,deviceNo) VALUES (%d, %d)", $new_document_id, $device_No));

    // function of insert new copy to zatcaDocumentUnit table
    insert_zatcaDocumentUnit_returned($invoice_no, $device_No, $new_document_id);

    
}

//function to insert new copy of zatcaDocumentUnits to database [Return]
function insert_zatcaDocumentUnit_returned($orderId, $deviceNo, $newDocNo)
{
    global $wpdb;

    $device_No = $deviceNo;
    $last_doc = $newDocNo;

    // ###########################################
    // Insert Copy of Data to zatcaDocumentUnit:##
    // ###########################################

    // Prefix Of woo-order-item
    $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';

    // get item id for item [ line_item ]:
    $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));

    // get item id for tax [ tax ]:
    $doc_unit_item_id_rate_percentage = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'tax'"));

    // get item id for discount [ tax ]:
    $doc_unit_item_id_coupon = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));


    // Funtion to handle order discount:
    function get_qty_percentage_for_item2($orderId){
    
        global $wpdb;
        // Prefix Of woo-order-item
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
    
        
        // define items number & Number of Items & Item Qty & Total Qty Of Order:
        
        $total_order_qty = 0;
    
        $array_items = [];
        
        $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
        
        foreach($doc_unit_item_id as $itemId){
    
            $item_qty = wc_get_order_item_meta( $itemId->order_item_id , '_qty', true );
    
            $order_discount_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
    
            $order_discount = wc_get_order_item_meta($order_discount_id, 'discount_amount', true);
            
            $array_items[$itemId->order_item_id] = $item_qty;
    
            $total_order_qty +=  + $item_qty;
    
        }
    
        $array_items['order_discount'] = $order_discount;
        $array_items['total_order_qty'] = $total_order_qty;
    
    
        //define percentage of each item:
    
        $updated_total_qty = [];
    
        foreach ($array_items as $key => $value) {
            if (is_numeric($key)) {  // Check for integer keys
                $updated_total_qty[$key] = $value / $array_items["total_order_qty"] * $array_items["order_discount"];
            }
        }
    
        // Now $updated_total_qty will contain the updated quantities for items with numeric keys
        return $updated_total_qty;
        
    }

    //
    foreach($doc_unit_item_id as $item){
    
                        
        // Item Qty:
        $doc_unit_item_qty = wc_get_order_item_meta( $item->order_item_id , '_qty', true );
        
        // Product_id:
        $doc_unit_product_id =wc_get_order_item_meta($item->order_item_id, '_product_id', true);
        
        // Tax Percentage:
        $doc_unit_vatRate =wc_get_order_item_meta($doc_unit_item_id_rate_percentage, 'rate_percent', true);

        // Item name:
        $doc_unit_sku = get_post_meta($doc_unit_product_id, '_sku', true);
        
        // Item Price:
        $doc_unit_price = get_post_meta($doc_unit_product_id, '_price', true);
        $final_price = number_format((float)$doc_unit_price, 6, '.', '');

        // Discount:
        $doc_unit_discount = wc_get_order_item_meta($doc_unit_item_id_coupon, 'discount_amount', true);

        // Subttotal [ price * quantity ]:
        $doc_unit_subtotal = $doc_unit_price * $doc_unit_item_qty;


        // Get the Function of define discount by line:
        $array_of_discounts = get_qty_percentage_for_item2($orderId);

        // Loop to get Each Item Discount:
        foreach($array_of_discounts as $key => $value)
        {

            if($key == $item->order_item_id)
            {

                $final_item_discount= $array_of_discounts[$key];

                // netAmount [ ((price * quantity)-discount) ]:
                $doc_unit_netAmount = $doc_unit_subtotal - $final_item_discount;
                $final_netAmount = number_format((float)$doc_unit_netAmount, 2, '.', '');

                // vatAmount [ netAmount*vatRate ]:
                $doc_unit_vatAmount = ($doc_unit_netAmount * $doc_unit_vatRate) / 100;
                $final_vatAmount = number_format((float)$doc_unit_vatAmount, 2, '.', '');

                // amountWithVat [ netAmount+vatAmount ]:
                $doc_unit_amountWithVat = $doc_unit_netAmount + $doc_unit_vatAmount;
                $final_amountWithVat = number_format((float)$doc_unit_amountWithVat, 2, '.', '');
            

                // Insert Data To zatcaDocumentUnit:
                $insert_doc_unit = $wpdb->insert(
                    'zatcaDocumentUnit',
                    [
                        'deviceNo'      => $device_No,
                        'documentNo'    => $last_doc,
                        'itemNo'        => $item->order_item_id,
                        'eName'         => $doc_unit_sku,
                        'price'         => $final_price,
                        'quantity'      => $doc_unit_item_qty,
                        'discount'      => $final_item_discount,
                        'vatRate'       => $doc_unit_vatRate,
                        'vatAmount'     => $final_vatAmount,
                        'netAmount'     => $final_netAmount,
                        'amountWithVAT' => $final_amountWithVat
                    ]
                );
            }
        }

    }

    // function to send the new one to zatca and handle the response from zatca depend on zatcaSuccessResponse code
    send_return_zatca($newDocNo);

}

//function to send reissue request to zatca [Return]
function send_return_zatca($docNo)
{
    global $wpdb;
    // Get the invoice type (B2B or B2C) 
    $zatcaInvoice_type = $wpdb->get_var($wpdb->prepare("SELECT zatcaInvoiceType FROM zatcaDocument WHERE documentNo =  $docNo"));

    // check invoice type to detect CLEAR or REPORT
    // if B2B
    if($zatcaInvoice_type == 1)
    {
        $data = update_zatca($docNo);

        $requestArray = json_decode($data, true);

        // get deviceCSID and tokenData from zatcaDevice table
        $deviceCSID = $wpdb->get_var("SELECT zd.deviceCSID 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $tokenData = $wpdb->get_var("SELECT zd.tokenData 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $company_stage = $wpdb->get_var("SELECT zatcaStage 
            FROM zatcaCompany");

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
        $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
        $seller_secondBusinessId_companyStage_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber ==null && $company_stage == 2) ? true : false;

        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        $buyer_additional_id = $requestArray['buyer']['additionalIdNumber'];
        $buyer_additional_id_validation = (isset($buyer_additional_id ) && $buyer_additional_id !=null) ? true : false;

        // Validate buyer vat number
        $buyer_vatNo = $requestArray['buyer']['vatNumber'];
        $invoicetransactioncode_isexports = $wpdb->get_var("SELECT zatcaInvoiceTransactionCode_isExports FROM zatcaDocument Where documentNo = '$docNo'");
        
        $buyer_vatNo_validation1 = (
            $buyer_vatNo == null && 
            ($invoicetransactioncode_isexports == null)) ? true : false;
        
        $buyer_vatNo_validation0 = ($buyer_vatNo == 0) ? true : false;

       // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($seller_additionalIdNumber_validation == false)
        {
            $send_response = [
                'status' => 'insert_seller_additional_id',
                'msg' => ''
            ];
        }
        elseif($buyer_additionalNo_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_poBox_additionalNo',
                'msg' => $buyer_additionalNo
            ];
        }
        elseif($buyer_additional_id_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_additional_id',
                'msg' => $buyer_additional_id
            ];
        }
        else if($buyer_vatNo_validation1 == true)
        {
            $send_response = [
                'status' => 'isexport1_buyervat',
                'msg' => $buyer_vatNo
            ];
            
        }
        else if($buyer_vatNo_validation0 == true)
        {
            $send_response = [
                'status' => 'isexport0_buyervat',
                'msg' => $requestArray['buyer']['vatNumber']
            ];
        }
        else if($seller_secondBusinessId_companyStage_validation == true)
        {
            $send_response = [
                'status' => 'seller_second_business_id',
                'msg' => ''];
        }

        else{
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Clear?deviceID='. $deviceCSID .'&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $tokenData
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                $send_response = [
                    'status' => 'error',
                    'msg' => 'cURL Error:' . $error . '(Error Code:'. $errorCode . ')'
                ];
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                $send_response = [
                    'status' => 'error',
                    'msg' => 'Curl error: ' . curl_error($curl)
                ];
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $clearanceStatus = $responseArray['clearanceStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
            if($responseArray['zatcaStatusCode'] == 400 || $responseArray['zatcaStatusCode'] == null || $responseArray['zatcaStatusCode'] == 0)
            {
                $errorMessage = $responseArray['portalResults'];
            }
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcaDocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) 
                    {
                        $warningMessage .= $Message['message'] . "\n";
                    }
                    else
                    {
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($clearanceStatus == 'CLEARED'){
    
                if($statusCode == '200'){

                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    
                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "isZatcaRetuerned" => 2
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);
                    ///////end////////

                    //  update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field ' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    }elseif ($zatcaDocument_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ]; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ]; 
                    }

                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
    
                    $send_response = [
                        'status' => 'success',
                        'msg' => __("Document Submitted Successfully, Zatca Status Code Is ", "zatca") . $statusCode . __(".. Request Is Success", "zatca") . $http_status_msg
                    ];
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "isZatcaRetuerned" => 2
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);


                     // update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocument_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }

                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
    
                    $send_response = [
                        'status' => 'warning',
                        'msg' => __("Document Submitted with Warning, Zatca Status Code Is ", "zatca") . $statusCode . '......' . $warningMessage
                    ];
                }
              
            }
            else{
    
                if($http_status == '400'){
    
                    //

                    $zatcaDocument_New_update_data = [
                        "isZatcaRetuerned" => 1
                    ];
                    $whereNew = array('documentNo' => $docNo);
                    $zatcaDocument_New_update_result = $wpdb->update('zatcaDocument',
                    $zatcaDocument_New_update_data, $whereNew);
                    

                    
                    // update zatca document fields with response Data:
                    $zatcaDocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_error_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocument_error_response_result === 0) {
                        
                        // No rows affected
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $send_response = [
                            'status' => 'http_status_msg',
                            'msg' => $http_status_msg . __(" Error: ", "zatca") . $errorMessage
                        ];
    
                    }else{
    
                        $send_response = [
                            'status' => 'http_status_msg',
                            'msg' => $http_status_msg . __(" Error: ", "zatca") . $validationResults
                        ];
                    }
    
                    
                
                }
                else if($http_status == '303')
                {
                    // update zatca document fields with response Data:
                    $zatcaDocument_error_response_data = [
                        "zatcaB2B_isForced_To_B2C" => 1];
                    $where = array('documentNo' => $docNo);
                    $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument',
                    $zatcaDocument_error_response_data, $where);
                    $send_response = [
                        'status' => '303',
                        'msg' => $response
                    ];
                }
            else{
    
                    $send_response = [
                        'status' => '',
                        'msg' => $response
                    ];
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
        }
        $send_response1 = [
    
            'msg' => $send_response,
            'validationResults' => $validationResults,
            'responseArray' => $responseArray,
            'data' => $data

        ];

        wp_send_json($send_response1);

        //end if
    }
    // if not B2B // B2C or Both
    else
    {
        $data = update_zatca1($docNo);

        $requestArray = json_decode($data, true);

        // get deviceCSID and tokenData from zatcaDevice table
        $deviceCSID = $wpdb->get_var("SELECT zd.deviceCSID 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $tokenData = $wpdb->get_var("SELECT zd.tokenData 
        FROM zatcaDevice zd, zatcaDocument z 
        WHERE z.documentNo = '$docNo' and z.deviceNo=zd.deviceNo");

        $company_stage = $wpdb->get_var("SELECT zatcaStage 
        FROM zatcaCompany");

        $VATCategoryCodeSubTypeNo = $wpdb->get_var("SELECT VATCategoryCodeSubTypeNo 
        FROM zatcaCompany");

        $msg = '';

       // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];

        $seller_secondBusinessId_companyStage_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber == null && $company_stage == 2) ? true : false;
    
        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        $buyer_additional_id = $requestArray['buyer']['additionalIdNumber'];
        $buyer_additional_id_validation = (isset($buyer_additional_id ) && $buyer_additional_id !=null) ? true : false;

        $buyer_arabic_name = $requestArray['buyer']['name'];

        $buyerArabicName_validation = ($buyer_arabic_name == '' && ($VATCategoryCodeSubTypeNo == 13 || $VATCategoryCodeSubTypeNo == 14)) ? true : false;

        if($buyer_additionalNo_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_poBox_additionalNo',
                'msg' => $buyer_additionalNo
            ];
        }
        elseif($buyer_additional_id_validation == false)
        { 
        // Validation on additionalNo - customer [ buyer ]:  
            $send_response = [
                'status' => 'insert_buyer_additional_id',
                'msg' => $buyer_additional_id
            ];
        }
        else if($seller_secondBusinessId_companyStage_validation == true)
        {
            $send_response = [
                'status' => 'seller_second_business_id',
                'msg' => ''];
        }
        else if($buyerArabicName_validation == true)
        {
            $send_response = [
                'status' => 'buyer_arabic_name',
                'msg' => $buyer_arabic_name];
        }

        else
        {
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Report?deviceID='. $deviceCSID .'&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $tokenData
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                $send_response = [
                    'status' => 'error',
                    'msg' => 'cURL Error:' . $error . '(Error Code:'. $errorCode . ')'
                ];
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
           
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                $send_response = [
                    'status' => 'error',
                    'msg' => 'Curl error: ' . curl_error($curl)
                ];
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $reportingStatus = $responseArray['reportingStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
            if($responseArray['zatcaStatusCode'] == 400 || $responseArray['zatcaStatusCode'] == null || $responseArray['zatcaStatusCode'] == 0)
            {
                $errorMessage = $responseArray['portalResults'];
            }
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcaDocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) {
                        $warningMessage .= $Message['message'] . "\n";
                    }else{
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($reportingStatus == 'REPORTED'){
    
                if($statusCode == '200'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "isZatcaRetuerned" => 2
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);



                    //  update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field ' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    }elseif ($zatcaDocument_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    

                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
                    $send_response = [
                        'status' => 'success',
                        'msg' => __("Document Submitted Successfully, Zatca Status Code Is ", "zatca") . $statusCode . __(".. Request Is Success", "zatca") . $http_status_msg
                    ];
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcaDocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcaDocument_original_update_data = [
                        "isZatcaRetuerned" => 2
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcaDocument_original_update_result = $wpdb->update('zatcaDocument', $zatcaDocument_original_update_data, $whereOriginal);



                     // update zatca document xml fields with response Data:
                    $zatcaDocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 1
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocumentxml_update_response_result = $wpdb->update('zatcaDocumentxml', $zatcaDocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcaDocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorxml',
                            'msg' => 'There was an error updating on zatcaDocumentxml in the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocumentxml_update_response_result === 0) {
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcaDocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_update_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocument_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ]; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcaDocument WHERE documentNo = $docNo") );
    
                    $zatcaDevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcaDevice_update_response_result = $wpdb->update('zatcaDevice', $zatcaDevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcaDevice_update_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdatedevice',
                            'msg' => 'There was an error updating zatcaDevice on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDevice_update_response_result === 0) {
                       
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // insert row to zatcaInfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
                    
                    $send_response = [
                        'status' => 'warning',
                        'msg' => __("Document Submitted with Warning, Zatca Status Code Is ", "zatca") . $statusCode . '......' . $warningMessage
                    ];
                }
              
            }
            else
            {
    
                if($http_status == '400'){
    
                    //
                    $zatcaDocument_New_update_data = [
                        "isZatcaRetuerned" => 1
                    ];
                    $whereNew = array('documentNo' => $docNo);
                    $zatcaDocument_New_update_result = $wpdb->update('zatcaDocument',
                    $zatcaDocument_New_update_data, $whereNew);


                    // update zatca document fields with response Data:
                    $zatcaDocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcaDocument_error_response_result = $wpdb->update('zatcaDocument', $zatcaDocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcaDocument_error_response_result === false) {
                        
                        // Handle error
                        $send_response = [
                            'status' => 'errorupdateresponse',
                            'msg' => 'There was an error updating zatcaDocument on the field' . $wpdb->last_error
                        ];
                    
                    }elseif ($zatcaDocument_error_response_result === 0) {
                        
                        // No rows affected
                        $send_response = [
                            'status' => 'no_rows_affected',
                            'msg' => 'No rows were affected. Possible reasons: No matching rows or the data is already up to date'
                        ];
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $send_response = [
                            'status' => 'error',
                            'msg' => $http_status_msg . __(" Error: ", "zatca") . $errorMessage
                        ];
    
                    }else{
    
                        $send_response = [
                            'status' => 'error',
                            'msg' => $http_status_msg . ' Error: ' . $validationResults
                        ];
                    }
    
                    
                
                }
                else{
    
                    $send_response = [
                        'status' => '',
                        'msg' => $response
                    ];
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
            
        }
        
        $send_response1 = [
    
            'msg' => $send_response,
            'validationResults' => $validationResults,
            'responseArray' => $responseArray,
            'data' => $data

        ];

        wp_send_json($send_response1);
    }
}

//function to insert new copy of data and fire the return button action [Return]
function send_return_request_to_zatca(){
    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];


    // get deviceNo from zatcaDocument table where documentNo = doc_no
    $invoiceNo = $wpdb->get_var("SELECT invoiceNo FROM zatcaDocument WHERE documentNo = '$doc_no'");

    // function to insert new copy to woocommerce and new copy to zatcaDocument and new copy to zatcaDocumentUnit
    insert_zatcaDocument_returned($doc_no, $invoiceNo);

}

////////////////////////////////////////////PDF Code/////////////////////////////////////////////////

//require_once(plugin_dir_path(__FILE__) . 'documents/document80mm.php');
//require_once(plugin_dir_path(__FILE__) . 'documents/documentA4.php');