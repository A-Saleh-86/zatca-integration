<?php
/*
*plugin name:zatca
*description:B2B Sell Invoice
*author:Ahmed Saleh
*Author url:
*version:8.0
*test domain:zatca
*text domain:zatca
*domain path:/languages
*/


// Security Layer:
if(!defined('ABSPATH')){
    echo 'What Are You Do';
    exit;
}



// Include Option.php:
include 'option.php';

// Get Walker Class For Bootstrap:
include_once 'css/class-wp-bootstrap-navwalker.php';

// Add [CSS, JS, Bootstrap ] to admin panel:
add_action('admin_enqueue_scripts', 'load_assets');

// add assets (css, js, etc):
add_action('wp_enqueue_scripts',  'load_assets');

// Action Of Sending Inserted Data as AJax Data - customers:
add_action('admin_enqueue_scripts', 'inserted_data_ajax');

// Action Of Sending Inserted Data as AJax Data - devices:
add_action('admin_enqueue_scripts', 'inserted_data_ajax_device');


// Action Of Sending Inserted Data as AJax Data - customers:
add_action('admin_enqueue_scripts', 'edit_data_ajax');

// Action Of Sending Inserted Data as AJax Data - devices:
add_action('admin_enqueue_scripts', 'edit_data_ajax_device');

// Action Of Sending Inserted Data as AJax Data - Company:
add_action('admin_enqueue_scripts', 'edit_data_ajax_company');

// Action Of Sending customer Id as AJax Data [ Insert Page]:
add_action('admin_enqueue_scripts', 'customer_data_ajax');


// Action Of Sending customer Id as AJax Data [ Edit page]:
add_action('admin_enqueue_scripts', 'customer_edit_data_ajax');

// Action Of Sending Vat Cat Code Id as AJax Data:
add_action('admin_enqueue_scripts', 'company_data_ajax');

// Action Of Sending woo order data in AJax [ Document ]:
add_action('admin_enqueue_scripts', 'document_data_ajax');

// ajax action - insert - customers:
add_action('wp_ajax_insert', 'insert_form');

// ajax action - insert - devices:
add_action('wp_ajax_insert-device', 'insert_form_devices');

// ajax action - insert - documents:
add_action('wp_ajax_insert-documents', 'insert_form_documents');

// ajax action - edit - customers:
add_action('wp_ajax_edit', 'edit_form_customer');

// ajax action - edit - devices:
add_action('wp_ajax_edit-device', 'edit_form_device');

// ajax action - edit - company:
add_action('wp_ajax_edit-company', 'edit_form_company');

// ajax action - edit - document:
add_action('wp_ajax_edit-document', 'document_edit_form');


// Action to get name from db with ajax data - [ insert page ] customers:
add_action('wp_ajax_customer', 'customer_form');

// Action to get name from db with ajax data - [ Edit page ] customers:
add_action('wp_ajax_customer_edit', 'customer_edit_form');

// Action to get Data from db with ajax data - [ view ] company:
add_action('wp_ajax_company', 'company_form');

// Action to get Data from db with ajax data - [ view ] company:
add_action('wp_ajax_woo-company-data', 'woo_company');

// Action to get Data from db with ajax data - [ insert ] document:
add_action('wp_ajax_woo-document-data', 'woo_document');

// Action to Check customer from db with ajax data - [ insert ] document:
add_action('wp_ajax_doc_check_customer', 'document_check_customer');


// Action to get data as ajax data to check if customer exists in zatcacustomer or not - document:
add_action('wp_ajax_doc_customer', 'doc_customer_Get_data_ajax');

// Action to get data to put in customer insert page when redirected from document page:
add_action('admin_enqueue_scripts', 'document_customer_handle_in_customer_page');

// Action Of Sending Inserted Data as AJax Data - document [ Edit page ]:
add_action('admin_enqueue_scripts', 'document_edit_data_ajax');

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

// Function to run Text Domain:
function my_plugin_load_textdomain() {
    load_plugin_textdomain( 'zatca', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    error_log( 'my_plugin_load_textdomain function executed' );

}


// function my_plugin_load_my_own_textdomain( $mofile, $domain ) {
// 	if ( 'zatca' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
// 		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
// 		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
// 	}
// 	return $mofile;
// }
// add_filter( 'load_textdomain_mofile', 'my_plugin_load_my_own_textdomain', 10, 2 );



// Define the function to create the table
// function create_custom_table() {
//     global $wpdb;
//     $table_name = $wpdb->prefix . 'plugin_table'; // Replace 'your_table_name' with your table name

//     // SQL query to create the table
//     $sql = "CREATE TABLE IF NOT EXISTS $table_name (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         column1 VARCHAR(255),
//         column2 INT
        
//     ) DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";

//     // Execute the query
//     require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//     dbDelta( $sql );
// }

// // Hook the function to plugin activation
// register_activation_hook( __FILE__, 'create_custom_table' );


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


function load_assets(){

    wp_enqueue_style('datatables-css', plugin_dir_url(__FILE__) . '/css/datatables.min.css');
    wp_enqueue_style('datatables-responsive-css', plugin_dir_url(__FILE__) . '/css/datatable-responsive.css');
    wp_enqueue_style('main', plugin_dir_url(__FILE__) . '/css/main.css') ;
    wp_enqueue_style('bootstap-css', plugin_dir_url(__FILE__) . '/css/bootstrap.min.css');
    wp_enqueue_style('elect2', plugin_dir_url(__FILE__) . '/css/select.css');
    wp_enqueue_style('fontawsome-css', plugin_dir_url(__FILE__) . '/css/fontawesome.css');
    wp_enqueue_style('fontawsome-solid-css', plugin_dir_url(__FILE__) . '/css/solid.css');
    wp_enqueue_style('fontawsome-brands-css', plugin_dir_url(__FILE__) . '/css/brands.css');
    wp_enqueue_style('fontawsome-min-css', plugin_dir_url(__FILE__) . '/css/fontawesome.min.css');
    wp_enqueue_style("dashicons");
    
    

    
    wp_enqueue_script( 'elect2', plugin_dir_url(__FILE__) . '/js/select.js', array('datatables-js') );
    wp_enqueue_script('datatables-js', plugin_dir_url(__FILE__) . '/js/datatables.min.js', array(), false, true);
    wp_enqueue_script('moment-js', plugin_dir_url(__FILE__) . '/js/moment.js', array(), false, true);
    wp_enqueue_script('datatable-datetime-js', plugin_dir_url(__FILE__) . '/js/datatable-datetime.js', array(), false, true);
    // wp_deregister_script('jquery');
    wp_enqueue_script('jquery');
    // wp_register_script('jquery', includes_url('/js/jquery/jquery.js'), false, '', true);
    wp_enqueue_script('bootstap-js', plugin_dir_url(__FILE__) . '/js/bootstrap.min.js', array(), false, true);
    wp_enqueue_script('fontawsome-js', plugin_dir_url(__FILE__) . '/js/fontawesome.min.js', array(), false, true);
    wp_enqueue_script('main-js',  plugin_dir_url(__FILE__) . '/js/main.js', array(), false, true);
    wp_enqueue_script('document-js',  plugin_dir_url(__FILE__) . '/js/document.js', array(), false, true);
    wp_localize_script( 'document-js', 'myDoc', array( 
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'adminUrl' => admin_url('admin.php?page=zatca-documents&action=view'),
        'customer' => admin_url('admin.php?page=zatca-documents&action=doc-add-customer'),) 
    );

}



// Send Inserted Data as AJax - customers [ Insert-page]:
function inserted_data_ajax() {

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Output your script
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $("#contact-form__form").submit(function(event){
                event.preventDefault();
                var formData = $(this).serialize();
                var po = document.getElementById("po-insert-customer");
                
                // validation on PO input:
                if (po.value.length != 5 ) {
                    alert("Po Must be 5 Numbers.");
                    return;
                }

                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    method: "POST", // Specify the method
                    data: {
                        "action": "insert",
                        "insert_form_ajax": formData
                    },
                    success: function(data){
                        // console.log(data);
                        // alert(data);

                        // Check for Which Page client come from:
                        if(data === "customers"){ // if from customers page:
                            
                            window.location.href = "' . admin_url('admin.php?page=zatca-customers&action=view') . '";
                            
                        }else{ // if from document insert customer
                            
                            window.location.href = "' . admin_url('admin.php?page=zatca-documents&action=insert') . '";
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    ');
}


// AJax Insert_Data to DB - customers [ Insert-page]:
function insert_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['insert_form_ajax'];

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


        $insert_result = $wpdb->insert(
            'zatcacustomer',
            [
                'Customer'             => $client_No,
                'clientVendorNo'       => $client_No,
                'aName'                => $client_Name_Ar,
                'eName'                => $client_Name_En,
                'VATID'                 => $vat_Id,
                'secondBusinessIDType'  => $second_Business_Id_Type,
                'secondBusinessID'      => $second_Business_Id,
                'zatcaInvoiceType'      => $zatca_Invoice_Type,
                'apartmentNum'          => $apartment_No,
                'POBox'            => $po_Box,
                'POBoxAdditionalNum'    => $po_Box_Additional_No,
                'street_Arb'            => $street_Name_Ar,
                'street_Eng'            => $street_Name_En,
                'district_Arb'          => $destrict_Name_Ar,
                'district_Eng'          => $destrict_Name_En,
                'city_Arb'              => $city_Name_Ar,
                'city_Eng'              => $city_Name_En,
                'countrySubdivision_Arb'=> $country_Sub_Name_Ar,
                'countrySubdivision_Eng'=> $country_Sub_Name_En,
                'country_No'            => $country
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

// Ajax Send btn of Copy Data - customers [ Insert-page]:
function customer_data_ajax(){

    // Check if we are on the post edit screen for your desired post type
    if (isset($_GET['page']) && $_GET['page'] ==='zatca-customers' &&  isset($_GET['action']) && $_GET['action'] === 'insert') {
        // Enqueue jQuery
        // wp_enqueue_script('jquery');

        // Output your script
        wp_add_inline_script("jquery", "
            jQuery(document).ready(function($) {



                $('table').on('click', 'td', function() {

                    // Remove selected from all 
                    $('tr').removeClass('selected');
                
                    // Add selected to clicked row
                    $(this).closest('tr').addClass('selected');
                
                });

                // Btn Of Copy Customer Data:
                const searchBtn = document.getElementById('search-customer-data');
                
                // Cache the table rows for better performance
                const tableRows = $('tr');

                // Get the clientVendorNo input element
                const clientVendorNoInput = document.getElementById('client-no');

                // Get the client-name-ar input element to populate with fetched data
                const clientNameArabicInput = document.getElementById('client-name-ar');

                // Get the client-name-eng input element to populate with fetched data
                const clientNameInput = document.getElementById('client-name');

                // Get the address input element to populate with fetched data
                const addressArabicInput = document.getElementById('address-ar');

                // Get the address input element to populate with fetched data
                const addressEnglishInput = document.getElementById('address-en');

                // Get the Arabic city input element to populate with fetched data
                const cityArabicInput = document.getElementById('city-ar');

                // Get the English city input element to populate with fetched data
                const cityEnglishInput = document.getElementById('city-en');

                // Add event listener to the customer select element
                searchBtn.addEventListener('click', function() {

                    const selectedRow = tableRows.filter('.selected');

                    // If Client Not Choose a Customer:
                    if (!selectedRow.length) {
                        alert('Please select a customer first.');
                        return;
                    }
            
                    // Get the user ID from the data-user-id attribute of the selected row
                    const selectedUserId = selectedRow.data('user-id');

                    // Give VendorNo The Id:
                    clientVendorNoInput.value = selectedUserId;
                    clientVendorNoInput.disabled = false;

                    $.ajax({
                        url: '". admin_url('admin-ajax.php') ."',
                        method: 'POST',
                        data: {
                            'action': 'customer',
                            'customer_form_ajax': selectedUserId
                        },
                        
                        success: function(data) {

                            
                            
                            // Variables Come From Ajax:
                            var first_name = data.first_name;
                            var last_name = data.last_name;
                            
                            
                            // change values of inputs:
                            clientNameInput.value = first_name + ' ' + last_name;
                            
                            // Check For Input Language [ Arabic - English ]
                            var address = data.address;
                            var city = data.city;

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
                    
                            if (containsArabic(address)) {
                                
                                // Insert New Value
                                addressArabicInput.value = address;

                            } else if (containsEnglish(address)) {

                                // Insert New Value
                                addressEnglishInput.value = address;

                            } else {

                                console.log('Unable to determine the language of the address.');
                            }

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


                });
            });

        ");
    }
}

// Recived the customer data and get name from database - customers [ Insert-page]:
function customer_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['customer_form_ajax'];


		$table_usermeta = $wpdb->prefix . 'usermeta';

        
        $first_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_first_name' and user_id = $vals"));
        $last_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_last_name' and user_id = $vals"));
        $address = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_address_1' and user_id = $vals"));
        $city = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_city' and user_id = $vals"));

        // Return the fetched data
        // echo $first_name . ' ' . $last_name;
        $response = array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'address'    => $address,
            'city'       => $city
        );

        // Return the array as JSON
        wp_send_json($response);
       
    }

    die();
}


// Ajax Send btn of Copy Data - customers [ Edit-page]:
function customer_edit_data_ajax(){

    // Check if we are on the post edit screen for your desired post type
    if (isset($_GET['page']) && $_GET['page'] ==='zatca-customers' && isset($_GET['action']) && $_GET['action'] === 'edit-customer') {

        // Output your script
        wp_add_inline_script("jquery", "
            jQuery(document).ready(function($) {



                $('table').on('click', 'td', function() {

                    // Remove selected from all 
                    $('tr').removeClass('selected');
                
                    // Add selected to clicked row
                    $(this).closest('tr').addClass('selected');
                
                  });

                  

                // Btn Of Copy Customer Data:
                const searchBtn = document.getElementById('search-edit-customer-data');

                // Cache the table rows for better performance
                const tableRows = $('tr');

                // Get the clientVendorNo input element
                const clientVendorNoInput = document.getElementById('client-no');

                // Get the client-name-ar input element to populate with fetched data
                const clientNameArabicInput = document.getElementById('client-name-ar');

                // Get the client-name-eng input element to populate with fetched data
                const clientNameInput = document.getElementById('client-name');

                // Get the address input element to populate with fetched data
                const addressArabicInput = document.getElementById('address-ar');

                // Get the address input element to populate with fetched data
                const addressEnglishInput = document.getElementById('address-en');

                // Get the Arabic city input element to populate with fetched data
                const cityArabicInput = document.getElementById('city-ar');

                // Get the English city input element to populate with fetched data
                const cityEnglishInput = document.getElementById('city-en');

                // Po Input:
                var po = document.getElementById('po-insert-customer');

                // Add event listener to the customer select element
                searchBtn.addEventListener('click', function() {

                    const selectedRow = tableRows.filter('.selected');

                    // If Client Not Choose a Customer:
                    if (!selectedRow.length) {
                        alert('Please select a customer first.');
                        return;
                    }
            
                    // Get the user ID from the data-user-id attribute of the selected row
                    const selectedUserId = selectedRow.data('user-id');

                    // Give VendorNo The Id:
                    clientVendorNoInput.value = selectedUserId;
                    clientNameArabicInput.value = '';
                    clientVendorNoInput.disabled = false;

                    
                
                    // validation on PO input:
                    if (po.value.length != 5 ) {
                        alert('Po Must be 5 Numbers.');
                        return;
                    }

                    $.ajax({
                        url: '". admin_url('admin-ajax.php') ."',
                        method: 'POST',
                        data: {
                            'action': 'customer_edit',
                            'customer_edit_form_ajax': selectedUserId
                        },
                        
                        success: function(data) {

                            
                            
                            // Variables Come From Ajax:
                            var first_name = data.first_name;
                            var last_name = data.last_name;
                            
                            
                            // change values of inputs:
                            clientNameInput.value = first_name + ' ' + last_name;
                            
                            // Check For Input Language [ Arabic - English ]
                            var address = data.address;
                            var city = data.city;

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
                    
                            if (containsArabic(address)) {
                                
                                // Insert New Value
                                addressArabicInput.value = address;

                            } else if (containsEnglish(address)) {

                                // Insert New Value
                                addressEnglishInput.value = address;

                            } else {

                                console.log('Unable to determine the language of the address.');
                            }

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


                });
            });

        ");
    }
}

// Recived the customer data and get name from database - customers [ Edit-page]:
function customer_edit_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['customer_edit_form_ajax'];


		$table_usermeta = $wpdb->prefix . 'usermeta';

        
        $first_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_first_name' and user_id = $vals"));
        $last_name = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_last_name' and user_id = $vals"));
        $address = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_address_1' and user_id = $vals"));
        $city = $wpdb->get_var($wpdb->prepare("select meta_value from $table_usermeta where meta_key = 'billing_city' and user_id = $vals"));

        // Return the fetched data
        // echo $first_name . ' ' . $last_name;
        $response = array(
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'address'    => $address,
            'city'       => $city
        );

        // Return the array as JSON
        wp_send_json($response);
       
    }

    die();
}

// Send Edit Data as AJax - customers [ Edit-page]:
function edit_data_ajax(){
    // Check if we are on the post edit screen for your desired post type
    if (isset($_GET['page']) && $_GET['page'] ==='zatca-customers' &&  isset($_GET['action']) && $_GET['action'] === 'edit-customer') {

        // Enqueue jQuery
        // wp_enqueue_script('jquery');

            // Output your script
            wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {

                $("#edit-form__form").submit(function(event){
                    event.preventDefault();
                    var formData = $(this).serialize();
                    
                    $.ajax({
                        url: "' . admin_url('admin-ajax.php') . '", // Echo the admin URL
                        method: "POST", // Specify the method
                        data: {
                            "action": "edit",
                            "edit_form_ajax": formData
                        },
                        success: function(data){
                            //console.log(data);
                             window.location.href = "' . admin_url('admin.php?page=zatca-customers&action=view') . '";
                            
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        ');
    }
}

// AJax Edit in DB - customers [ Edit-page]:
function edit_form_customer(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['edit_form_ajax'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $id = $form_array['id'];
        // $client_No = $form_array['client-no'];
        $client_Name_Ar = $form_array['client-name-ar'];
        $client_Name_En = $form_array['client-name-en'];
        $vat_Id = $form_array['vat-id'];
        $second_Business_Id_Type = $form_array['second-business-id-type'];
        $second_Business_Id = $form_array['second-business-id'];
        $zatca_Invoice_Type = $form_array['zatca-invoice-type'];
        $apartment_No = $form_array['apartment-no'];
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

        // Get Customer-no:
        $client_No = $wpdb->get_var($wpdb->prepare("select Customer from zatcacustomer where ID = $id"));

        $table_name = 'zatcacustomer';
        $data = array(
            'Customer'             => $client_No,
            'clientVendorNo'       => $client_No,
            'aName'                => $client_Name_Ar,
            'eName'                => $client_Name_En,
            'VATID'                 => $vat_Id,
            'secondBusinessIDType'  => $second_Business_Id_Type,
            'secondBusinessID'      => $second_Business_Id,
            'zatcaInvoiceType'      => $zatca_Invoice_Type,
            'apartmentNum'          => $apartment_No,
            'POBox'            => $po_Box,
            'POBoxAdditionalNum'    => $po_Box_Additional_No,
            'street_Arb'            => $street_Name_Ar,
            'street_Eng'            => $street_Name_En,
            'district_Arb'          => $destrict_Name_Ar,
            'district_Eng'          => $destrict_Name_En,
            'city_Arb'              => $city_Name_Ar,
            'city_Eng'              => $city_Name_En,
            'countrySubdivision_Arb'=> $country_Sub_Name_Ar,
            'countrySubdivision_Eng'=> $country_Sub_Name_En,
            'country_No'            => $country
        );
        $where = array('ID' => $id);
        $update_result = $wpdb->update($table_name, $data, $where);
    

        if ($update_result === false) {
            // There was an error inserting data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo 'Data Inserted';
        }
       
    }

    die();
}



// Send Inserted Data as AJax - Devices:
function inserted_data_ajax_device() {

    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Output your script
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $("#device-form__form").submit(function(event){
                event.preventDefault();
                var formData = $(this).serialize();
                
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '", // Echo the admin URL
                    method: "POST", // Specify the method
                    data: {
                        "action": "insert-device",
                        "insert_form_ajax_devices": formData
                    },
                    success: function(data){
                        //console.log(data);
                        window.location.href = "' . admin_url('admin.php?page=zatca-devices&action=view') . '";
                        
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    ');
}


// AJax Insert_Data to DB - Devices:
function insert_form_devices(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['insert_form_ajax_devices'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $device_No = $form_array['device-no'];
        $device_Csid = $form_array['device-csid'];
        $csid_Ex_Date = $form_array['csid-ex-date'];
        $token_Data = $form_array['token-data'];



        $insert_result = $wpdb->insert(
            'zatcadevice',
            [
                'deviceNo'            => $device_No,
                'deviceCSID'            => $device_Csid,
                'CsID_ExpiryDate'       => $csid_Ex_Date,
                'tokenData'             => $token_Data
            ]
        );

        if ($insert_result === false) {
            // There was an error inserting data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo 'Data Inserted';
        }

    }

    die();
}

// Send Edit Data as AJax - devices:
function edit_data_ajax_device(){

    
    
    // Enqueue jQuery
    wp_enqueue_script('jquery');

        // Output your script
        wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $("#edit-form-device__form").submit(function(event){
                event.preventDefault();
                var formData = $(this).serialize();
                
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '", // Echo the admin URL
                    method: "POST", // Specify the method
                    data: {
                        "action": "edit-device",
                        "edit_form_ajax_device": formData
                    },
                    success: function(data){
                        // console.log(data);
                        window.location.href = "' . admin_url('admin.php?page=zatca-devices&action=view') . '";
                        
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    ');
}


// AJax Edit in DB - devices:
function edit_form_device(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['edit_form_ajax_device'];

        // Parse Data:
        parse_str($vals, $form_array);

        // Variables of data:
        $id = $form_array['id'];
        $device_No = $form_array['device-no'];
        $device_Csid = $form_array['device-csid'];
        $csid_Ex_Date = $form_array['csid-ex-date'];
        $token_Data = $form_array['token-data'];

        $table_name = 'zatcadevice';
        $data = array(
            'deviceNo'            => $device_No,
            'deviceCSID'            => $device_Csid,
            'CsID_ExpiryDate'       => $csid_Ex_Date,
            'tokenData'             => $token_Data,
        );
        $where = array('ID' => $id);
        $update_result = $wpdb->update($table_name, $data, $where);
    

        if ($update_result === false) {
            // There was an error inserting data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo 'Data Updated';
        }
       
    }

    die();
}

// Send Data as Ajax - Company:
function company_data_ajax(){

    if (isset($_GET['page']) && $_GET['page'] ==='zatca-company') {

        // wp_enqueue_script('jquery');

        // Output your script
        wp_add_inline_script("jquery", "
            jQuery(document).ready(function($) {

                // Btn Of Copy Company Data:
                const copyBtn = document.getElementById('copy-company-data');

                // Company Address Input
                const comAddressInput = document.getElementById('company-address');

                // Company City Input
                const comCityInput = document.getElementById('company-city');

                // Get the vat cat code element
                const vatCatCodeInput = document.getElementById('vat-cat-code');

                // Get the vat cat code sub element
                const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');

               

                (function($) {
                    $(document).ready(function() {

                        $('select#vat-cat-code').on('change', function(event) {
                            const selectedVatCat = this.value;
                

                            // Make an AJAX request to fetch data based on selectedUserId
                            $.ajax({
                                url: '". admin_url('admin-ajax.php') ."',
                                method: 'POST',
                                data: {
                                    'action': 'company',
                                    'company_form_ajax': selectedVatCat
                                },
                                
                                success: function(data) {
                                    
                                    // console.log(data);
                                    vatCatCodeSubInput.innerHTML = data; 
        
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error fetching data:', error);
                                }
                            });
                        });
                    });
                })(jQuery);
                

                // Add event listener to get Company Data From wp_options:
                copyBtn.addEventListener('click', function() {

                    // Make an AJAX request to fetch data based on selectedUserId
                    $.ajax({
                        url: '". admin_url('admin-ajax.php') ."',
                        method: 'POST',
                        data: {
                            'action': 'woo-company-data'
                        },
                        
                        success: function(data) {
                            
                            // console.log(data);

                            comAddressInput.value = data.address;
                            comCityInput.value = data.city;
                         

                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                        }
                    });
                });


            });

        ");

    }

}


// Recived the vat cat code data and get name from database - company:
function company_form(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['company_form_ajax'];

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

// Send Edit Data as AJax - company:
function edit_data_ajax_company(){
    
    // Enqueue jQuery
    wp_enqueue_script('jquery');

        // Output your script
        wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            $("#edit-form-company__form").submit(function(event){
                event.preventDefault();
                var formData = $(this).serialize();
                
                var po = document.getElementById("po-company");
                var additionalId = document.getElementById("second-id-company");

                // validation on PO input:
                if (po.value.length != 5 ) {
                    alert("Po Must be 5 Numbers.");
                    return;
                }

                // validation on additional Id input:
                if (additionalId.value.trim() === "" ) {
                    alert("Second Business Id Cant be Empty.");
                    return;
                }
                // validation on additional Id input:
                if (additionalId.value.length != 10 ) {
                    alert("Second Business Id Must be 10 Numbers.");
                    return;
                }
                
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '", // Echo the admin URL
                    method: "POST", // Specify the method
                    data: {
                        "action": "edit-company",
                        "edit_form_ajax_company": formData
                    },
                    success: function(data){
                        // console.log(data);
                        window.location.href = "' . admin_url('admin.php?page=zatca-company&action=view') . '";
                        
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    ');
}

// AJax Edit in DB - devices:
function edit_form_company(){

    if(isset($_REQUEST)){

        global $wpdb;

        // AJax Data:
        $vals = $_REQUEST['edit_form_ajax_company'];

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

        $table_name = 'zatcacompany';

        $data = array(
            'zatcaStage'                        => $zatca_Stage,
            'secondBusinessIDType'              => $second_Business_Id_Type,
            'secondBusinessID'                  => $second_Business_Id,
            'VATCategoryCode'                   => $vat_Cat_Code,
            'VATCategoryCodeSubTypeNo'          => $vat_Cat_Code_Sub_No,
            'VATID'                             => $vat_Id,
            'aName'                             => $aName,
            'apartmentNum'                      => $apartment_No,
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
        );

        $where = array('companyNo' => $id);

        $update_result = $wpdb->update($table_name, $data, $where);
    

        if ($update_result === false) {
            // There was an error inserting data
            $error_message = $wpdb->last_error;
            echo "Error inserting data: $error_message";
        } else {

            echo 'Data Updated';
        }
       
    }

    die();
}

// AJax Edit in DB - devices:
function woo_company(){

        global $wpdb;


        $table_usermeta = $wpdb->prefix . 'options';

        // Get Data from wp_options For address:
        $address = $wpdb->get_var($wpdb->prepare("select option_value from $table_usermeta WHERE option_name ='woocommerce_store_address'"));

        // Get Data from wp_options For city:
        $city = $wpdb->get_var($wpdb->prepare("select option_value from $table_usermeta WHERE option_name ='woocommerce_store_city'"));

        // Return the fetched data
        // echo $first_name . ' ' . $last_name;
        $response = array(
            'address'   => $address,
            'city'      => $city
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

// AJax Edit in DB - devices:
function woo_document(){

    // AJax Data:
    $orderId = $_REQUEST['woo_order_id'];
    $vatCat = $_REQUEST['vat_cat_code'];

    global $wpdb;


    $table_orders = $wpdb->prefix . 'wc_orders';

    // Get customer id from wc_orders For address:
    $customerId = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $orderId "));
   
    // Get Payed from wc_orders For address:
    $payed = $wpdb->get_var($wpdb->prepare("select total_amount from $table_orders WHERE id = $orderId "));

    // Get order-item_id from woocommerce_order_items:
    $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';

    // Get Order Id for Specific Item_Id [ coupon ]:
    $item_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));

    // Get Order Id for Specific Item_Id [ line_item ]:
    $item_id_line_item = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));

    // Get Discount from woo_order_itemmeta:
    $discount = wc_get_order_item_meta( $item_id, 'discount_amount', true );

    // Get Sub Net Total from woo_order_itemmeta:
    $sub_net_total = wc_get_order_item_meta( $item_id_line_item, '_line_total', true );

    // Get Sub Total from woo_order_itemmeta:
    $sub_total = wc_get_order_item_meta( $item_id_line_item, '_line_subtotal', true );
    

    $customerVatId = $wpdb->get_var($wpdb->prepare("SELECT VATID FROM zatcacustomer WHERE ID = $customerId"));
    $invoiceType = $wpdb->get_var($wpdb->prepare("select zatcaInvoiceType from zatcacustomer where Customer = $customerId"));
    
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

    // Return the fetched data
    $response = array(
        'payed'         => number_format($payed, 2, '.', ''),
        'discount'      => $dicount_value,
        'subNetTotal'   => $sub_net_total,
        'vatCatName'   => $vatCatName,
        'discountPercentage' => round($discount_percentage, 2),
        'invoiceTypeCode' => $invoiceTypeCode
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

        // Get Device No from ZatcaDevice:

        // Table Name:
        $table_name_device = 'zatcadevice';

        // Prepare the query with a condition on CsID_ExpiryDate not expire:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW()") );
        foreach($results as $device){

            if($wpdb->num_rows > 0){ // If Date Valid:

                $deviceNo = $device->deviceNo;

            }else{ // If No Date Valid

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
        
        // Check For Document no in zatcadocument [Previouse Doc No]:
        $documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcadocument") );

        $previuos_docNo = 0;
        $previousInvoiceHash = '';
        
        // Ceheck for previous document no if have doc no will choose it - 1 & previousInvoiceHash will be the value in database:
        if($wpdb->num_rows > 0){
            foreach($documents as $document){
            
                $previuos_docNo = $document->documentNo - 1;
                $previousInvoiceHash = $document->previousInvoiceHash;

            }
        }else{ // if not previuous doc no will be 0 - and previousInvoiceHash will be default:
            
            $previuos_docNo=0;
            $previousInvoiceHash = "NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==";
        }
        
        // Get seconde bussiness id type from zatcacompany [ seller ]:
        $seller_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcacompany"));
       
        // Get seconde bussiness id from zatcacompany [ seller ]:
        $seller_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcacompany"));
        
        // Prifix of wp_wc_orders:
        $table_orders = $wpdb->prefix . 'wc_orders';
        
        // get customer id from order table:
        $order_Customer_Id = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $orderId"));
        
        // get buyer_secondBusinessIDType from zatcacustomers:
        $buyer_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get buyer_secondBusinessID from zatcacustomers:
        $buyer_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // Get Seller Vat from zatcacompany [ seller ]:
        $seller_VAT_Company = $wpdb->get_var($wpdb->prepare("select VATID from zatcacompany"));
        
        // Get Seller apartment from zatcacompany [ seller ]:
        $seller_apartmentNum_Company = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcacompany"));
        
        // Get Seller POBoxAdditionalNum from zatcacompany [ seller ]:
        $seller_POBoxAdditionalNum_Company = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcacompany"));
        
        // Get Seller POBox from zatcacompany [ seller ]:
        $seller_POBox_Company = $wpdb->get_var($wpdb->prepare("select POBox from zatcacompany"));
        
        // Get Seller street_Arb from zatcacompany [ seller ]:
        $seller_street_Arb_Company = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcacompany"));
        
        // Get Seller street_Eng from zatcacompany [ seller ]:
        $seller_street_Eng_Company = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcacompany"));
        
        // Get Seller district_Arb from zatcacompany [ seller ]:
        $seller_district_Arb_Company = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcacompany"));
        
        // Get Seller district_Eng from zatcacompany [ seller ]:
        $seller_district_Eng_Company = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcacompany"));
        
        // Get Seller city_Arb from zatcacompany [ seller ]:
        $seller_city_Arb_Company = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcacompany"));
        
        // Get Seller city_Eng from zatcacompany [ seller ]:
        $seller_city_Eng_Company = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcacompany"));
        
        // Get Seller country_Arb from zatcacompany [ seller ]:
        $seller_country_Arb_Company = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcacompany"));
        
        // Get Seller country_Eng from zatcacompany [ seller ]:
        $seller_country_Eng_Company = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcacompany"));
        
        // Get Seller CountryNo from zatcacompany [ seller ]:
        $seller_CountryNo_Company = $wpdb->get_var($wpdb->prepare("select countryNo from zatcacompany "));
        
        // get aName from zatcacustomers:
        $buyer_aName_Customer = $wpdb->get_var($wpdb->prepare("select aName from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get eName from zatcacustomers:
        $buyer_eName_Customer = $wpdb->get_var($wpdb->prepare("select eName from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get apartmentNum from zatcacustomers:
        $buyer_apartmentNum_Customer = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get POBoxAdditionalNum from zatcacustomers:
        $buyer_POBoxAdditionalNum_Customer = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get PostalCode from zatcacustomers:
        $buyer_PostalCode_Customer = $wpdb->get_var($wpdb->prepare("select POBox from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get street_Arb from zatcacustomers:
        $buyer_street_Arb_Customer = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get street_Eng from zatcacustomers:
        $buyer_street_Eng_Customer = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get district_Arb from zatcacustomers:
        $buyer_district_Arb_Customer = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get district_Eng from zatcacustomers:
        $buyer_district_Eng_Customer = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get city_Arb from zatcacustomers:
        $buyer_city_Arb_Customer = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get city_Arb from zatcacustomers:
        $buyer_city_Eng_Customer = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get CountryNo from zatcacustomers:
        $buyer_CountryNo_Customer = $wpdb->get_var($wpdb->prepare("select country_No from zatcacustomer WHERE Customer = $order_Customer_Id"));
        



        // Variables of data:
        $woo_invoice_No = $form_array['invoice-no'];
        $device_No = $deviceNo;
        $delivery_Date = $form_array['deliveryDate'];
        $latest_Delivery_Date = $form_array['gaztLatestDeliveryDate'];
        $zatca_Invoice_Type = $form_array['zatcaInvoiceType'];
        $amount_Payed = $form_array['amountPayed01'];
        $sum_Payed =$form_array['amountPayed01'];
        $sub_Total =$sub_net_total;
        $sub_Total_Discount = $form_array['subTotalDiscount'];
        $taxRate1_Percentage = $form_array['taxRate1_Percentage'];
        $taxRate1_Total = $form_array['taxRate1_Total'];
        $sub_Net_Total = $form_array['subNetTotal'];
        $sub_Net_Total_Plus_Tax = $form_array['subNetTotalPlusTax'];
        $discount = $form_array['discount-percentage'];
        $totalDiscount = $form_array['totalDiscount'];
        $previousDocumentNo = $previuos_docNo;
        $seller_secondBusinessIDType = $seller_second_bussiness_Id_Type;
        $seller_secondBusinessID = $seller_second_bussiness_Id;
        $buyer_secondBusinessIDType = $buyer_second_bussiness_Id_Type;
        $buyer_secondBusinesvsID = $buyer_second_bussiness_Id;
        $zatca_VATCategoryCode = $form_array['vat-cat-code'];
        $VATCategoryCodeSubTypeNo = $form_array['vat-cat-code-sub-no'];
        $taxExemptionReason = $form_array['taxExemptionReason'];
        $zatcaInvoiceTransactionCode_isNominal = $form_array['isNominal'];
        $zatcaInvoiceTransactionCode_isExports = $form_array['isExports'];
        $zatcaInvoiceTransactionCode_isSummary = $form_array['isSummary'];
        $note = $form_array['note'];
        $uuid = wp_generate_uuid4();
        $seller_VAT = $seller_VAT_Company;
        $seller_apartmentNum = $seller_apartmentNum_Company;
        $seller_POBoxAdditionalNum = $seller_POBoxAdditionalNum_Company;
        $seller_POBox = $seller_POBoxAdditionalNum_Company;
        $seller_street_Arb = $seller_street_Arb_Company;
        $seller_street_Eng = $seller_street_Eng_Company;
        $seller_district_Arb = $seller_district_Arb_Company;
        $seller_district_Eng = $seller_district_Eng_Company;
        $seller_city_Arb = $seller_city_Arb_Company;
        $seller_city_Eng = $seller_city_Eng_Company;
        $seller_country_Arb = $seller_country_Arb_Company;
        $seller_country_Eng = $seller_country_Eng_Company;
        $seller_CountryNo = $seller_CountryNo_Company;
        $buyer_aName = $buyer_aName_Customer;
        $buyer_eName = $buyer_eName_Customer;
        $buyer_apartmentNum = $buyer_apartmentNum_Customer;
        $buyer_POBoxAdditionalNum = $buyer_POBoxAdditionalNum_Customer;
        $buyer_PostalCode = $buyer_PostalCode_Customer;
        $buyer_street_Arb = $buyer_street_Arb_Customer;
        $buyer_street_Eng = $buyer_street_Eng_Customer;
        $buyer_district_Arb = $buyer_district_Arb_Customer;
        $buyer_district_Eng = $buyer_district_Eng_Customer;
        $buyer_city_Arb = $buyer_city_Arb_Customer;
        $buyer_city_Eng = $buyer_city_Eng_Customer;
        $buyer_CountryNo = $buyer_CountryNo_Customer;

        // check if delivery date is null:
        if(empty($delivery_Date)){
            $delivery_Date = date('Y-m-d');
        }

        // check if latest_Delivery_Date  is null:
        if(empty($latest_Delivery_Date)){
            $latest_Delivery_Date = date('Y-m-d');
        }

        // Validation on CsID_ExpiryDate not expire:
        $device_ExpiryDate = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW()") );
        
        

        if(empty($device_ExpiryDate)){ // If Date Valid:

            $msg = "Device CsID_ExpiryDate is Expired";

        }else{ // If No Date Valid

            
            // #################################
            // Insert Data into zatcadocument:##
            // #################################
    
            $insert_doc = $wpdb->insert(
                'zatcadocument',
                [
                    'deviceNo'                              => $device_No,
                    'invoiceNo'                             => $woo_invoice_No,
                    'deliveryDate'                          => $delivery_Date,
                    'gaztLatestDeliveryDate'                => $latest_Delivery_Date,
                    'zatcaInvoiceType'                      => $zatca_Invoice_Type,
                    'amountPayed01'                         => $amount_Payed,
                    'sumPayed'                              => $sum_Payed,
                    'subTotal'                              => $sub_Total,
                    'subTotalDiscount'                      => $sub_Total_Discount,
                    'subNetTotal'                           => $sub_Net_Total,
                    'subNetTotalPlusTax'                    => $sub_Net_Total_Plus_Tax,
                    'discount'                              => $discount,
                    'totalDiscount'                         => $totalDiscount,
                    'previousDocumentNo'                    => $previousDocumentNo,
                    'previousInvoiceHash'                   => $previousInvoiceHash,
                    'seller_secondBusinessIDType'           => $seller_secondBusinessIDType,
                    'seller_secondBusinessID'               => $seller_secondBusinessID,
                    'buyer_secondBusinessIDType'            => $buyer_secondBusinessIDType,
                    'buyer_secondBusinessID'                => $buyer_secondBusinesvsID,
                    'VATCategoryCodeNo'                     => $zatca_VATCategoryCode,
                    'VATCategoryCodeSubTypeNo'              => $VATCategoryCodeSubTypeNo,
                    'zatca_TaxExemptionReason'              => $taxExemptionReason,
                    'zatcaInvoiceTransactionCode_isNominal' => $zatcaInvoiceTransactionCode_isNominal,
                    'zatcaInvoiceTransactionCode_isExports' => $zatcaInvoiceTransactionCode_isExports,
                    'zatcaInvoiceTransactionCode_isSummary' => $zatcaInvoiceTransactionCode_isSummary,
                    'UUID'                                  => $uuid,
                    'seller_VAT'                            => $seller_VAT,
                    'seller_apartmentNum'                   => $seller_apartmentNum,
                    'seller_street_Arb'                     => $seller_street_Arb,
                    'seller_street_Eng'                     => $seller_street_Eng,
                    'seller_district_Arb'                   => $seller_district_Arb,
                    'seller_district_Eng'                   => $seller_district_Eng,
                    'seller_city_Arb'                       => $seller_city_Arb,
                    'seller_city_Eng'                       => $seller_city_Eng,
                    'seller_country_Arb'                    => $seller_country_Arb,
                    'seller_country_Eng'                    => $seller_country_Eng,
                    'seller_country_No'                     => $seller_CountryNo,
                    'seller_POBox'                          => $seller_POBox,
                    'seller_POBoxAdditionalNum'             => $seller_POBoxAdditionalNum,
                    'buyer_aName'                           => $buyer_aName,
                    'buyer_eName'                           => $buyer_eName,
                    'buyer_apartmentNum'                    => $buyer_apartmentNum,
                    'buyer_street_Arb'                      => $buyer_street_Arb,
                    'buyer_street_Eng'                      => $buyer_street_Eng,
                    'buyer_district_Arb'                    => $buyer_district_Arb,
                    'buyer_district_Eng'                    => $buyer_district_Eng,
                    'buyer_city_Arb'                        => $buyer_city_Arb,
                    'buyer_city_Eng'                        => $buyer_city_Eng,
                    'buyer_country_No'                      => $buyer_CountryNo,
                    'buyer_POBox'                           => $buyer_PostalCode,
                    'buyer_POBoxAdditionalNum'              => $buyer_POBoxAdditionalNum,
                    'zatcaSuccessResponse'                  => 0
                ]
            );
    
            if ($insert_doc === false) {
                // There was an error inserting data:
                error_log('Insert error: ' . $wpdb->last_error);
                echo "Error inserting data: " . $wpdb->last_error;
            } else {
    
                // Get Last DocNo Inserted:
                $last_doc_no = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcadocument"));
        
                if($last_doc_no === false) {
    
                    // Check For Error:
                    error_log('Get Last DocNo Error: ' . $wpdb->last_error);
                    echo "Error Get Last DocNo Inserted: " . $wpdb->last_error;
                }else {
    
                    // Get last DocNo in zatcadocumentno:
                    $last_doc=0;
    
                    foreach($last_doc_no as $doc){
    
                        $last_doc = $doc->documentNo;
                        
                    }
                    
                    
                    /* ####################################
                    ## Insert Data into zatcadocumentxml:##
                    */ ####################################
    
                        $data_doc_xml = $wpdb->insert(
                            'zatcadocumentxml',
                            [
                                'documentNo'  => $last_doc,
                                'deviceNo'    => $device_No
                            ]
                        );
    
                        // Validation of inserting zatcadocumentxml:
                        if ($data_doc_xml === false) {
    
                            // Check For Error:
                            error_log('documentxml Error: ' . $wpdb->last_error);
                            echo "Error Inserting DocNo: " . $wpdb->last_error;
                        }
    
    
                    // ################################
                    // Add Data to zatcadocumentunit:##
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
                        foreach($array_of_discounts as $key => $value){
    
                            if($key == $item->order_item_id){
    
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
                            
    
                            // Insert Data To zatcadocumentunit:
                            $insert_doc_unit = $wpdb->insert(
                                'zatcadocumentunit',
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
                        
                        // Validation of zatcadocumentunit:
                        if ($insert_doc_unit === false) {
    
                            // Check For Error:
                            error_log('documentunit Error: ' . $wpdb->last_error);
                            echo "Error Inserting docunit: " . $wpdb->last_error;
                        }
    
                    }

                    // Log the invoice insertion
                    $user_login = wp_get_current_user()->user_login;
                    $user_id = wp_get_current_user()->ID;
                    log_invoice_insertion($user_login, $user_id);
    
                }
            }

            $msg = 'Document Created successifly';

        }
        
        // wp_send_json($msg);
        echo $msg;
        
    }

    die();
}


// Function to check if customer exist on zatcacustomer or not:
function document_check_customer(){

    if(isset($_REQUEST)){

        // AJax Data:
        $customerId = $_REQUEST['woo_customer_id'];

        global $wpdb;

        // Table Name:
        $table_name_customes = 'zatcacustomer';

        // Prepare the query with a condition on Customer Exist or not:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_customes WHERE Customer = $customerId") );

        if($wpdb->num_rows > 0){ // If Date Valid:

            
            // echo $c->aName;
            echo 'Exist';
            
        }else{ // If No Date Valid
            
            echo 'Not Exist';

        }
 
        
    }
    die();
}

// Function to add customer to zatcacustomer from redirect from document page:
function document_customer_handle_in_customer_page(){

    if (isset($_GET['page']) && $_GET['page'] ==='zatca-documents' &&  isset($_GET['action']) && $_GET['action'] === 'doc-add-customer') {
       
        wp_add_inline_script("jquery", "
            window.onload = function() {
                // Data Come With URL:
                const urlParams = new URLSearchParams(window.location.search);
                const customerId = urlParams.get('customerId');
                
                // Get the clientVendorNo input element
                const clientVendorNoInput = document.getElementById('doc-cust-client-no');

                // Get the client-name-ar input element to populate with fetched data
                const clientNameArabicInput = document.getElementById('client-name-ar');
                
                // Get the client-name-eng input element to populate with fetched data
                const clientNameInput = document.getElementById('client-name-en');

                // Get the address input element to populate with fetched data
                const addressArabicInput = document.getElementById('address-ar');

                // Get the address input element to populate with fetched data
                const addressEnglishInput = document.getElementById('address-en');

                // Get the Arabic city input element to populate with fetched data
                const cityArabicInput = document.getElementById('city-ar');

                // Get the English city input element to populate with fetched data
                const cityEnglishInput = document.getElementById('city-en');

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
                        var first_name = data.first_name;
                        var last_name = data.last_name;
                        
                        
                        // change values of inputs:
                        clientNameInput.value = first_name + ' ' + last_name;
                        
                        // Check For Input Language [ Arabic - English ]
                        var address = data.address;
                        var city = data.city;

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
                
                        if (containsArabic(address)) {
                            
                            // Insert New Value
                            addressArabicInput.value = address;

                        } else if (containsEnglish(address)) {

                            // Insert New Value
                            addressEnglishInput.value = address;

                        } else {

                            console.log('Unable to determine the language of the address.');
                        }

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

      
        // Return the fetched data
      
        $response = array(
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'address'       => $address,
            'city'          => $city
        );

        // Return the array as JSON
        wp_send_json($response);
       
    }

    die();
}

// Send Edit Data as AJax - document [ Edit-page]:
function document_edit_data_ajax(){
    // Check if we are on the post edit screen for your desired post type
    if (isset($_GET['page']) && $_GET['page'] ==='zatca-documents' &&  isset($_GET['action']) && $_GET['action'] === 'edit-document') {

        // Enqueue jQuery
        // wp_enqueue_script('jquery');

            // Output your script
            wp_add_inline_script('jquery', '
            jQuery(document).ready(function($) {

                $("#edit-document__form").submit(function(event){
                    event.preventDefault();

                    // enabled documentNo Input to send data:
                    $("#documentNo").prop("disabled", false);
                    
                    // enabled woo-invoice-no Input to send data:
                    $("#woo-invoice-no").prop("disabled", false);
                    
                    var formData = $(this).serialize();
                    
                    $.ajax({
                        url: "' . admin_url('admin-ajax.php') . '", // Echo the admin URL
                        method: "POST", // Specify the method
                        data: {
                            "action": "edit-document",
                            "document_edit_form_ajax": formData
                        },
                        success: function(data){
                            // console.log(data);
                            window.location.href = "' . admin_url('admin.php?page=zatca-documents&action=view') . '";
                            
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        ');
    }
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
        $table_name_device = 'zatcadevice';
        $table_name_document = 'zatcadocument';
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
        $table_orders = $wpdb->prefix . 'wc_orders';


        // get device no From zatcadocument:
        $deviceNo_from_db = $wpdb->get_var($wpdb->prepare("select deviceNo from $table_name_document WHERE documentNo = $documentNo "));
        
        // get previousDocumentNo From zatcadocument:
        $previousDocumentNo_from_db = $wpdb->get_var($wpdb->prepare("select previousDocumentNo from $table_name_document WHERE documentNo = $documentNo "));

        // Pget UUID From zatcadocument::
        $uuid_from_db = $wpdb->get_var($wpdb->prepare("select UUID from $table_name_document WHERE documentNo = $documentNo "));
        
        // Get Order Id for Specific Item_Id [ line_item ]:
        $item_id_line_item = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $woo_invoice_No AND order_item_type = 'line_item'"));

        // get customer id from order table:
        $order_Customer_Id = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $woo_invoice_No"));
        
        // Get Sub  Total from woo_order_itemmeta:
        $sub_total = wc_get_order_item_meta( $item_id_line_item, '_line_subtotal', true );
       
        // Get Sub Net Total from woo_order_itemmeta:
        $sub_net_total = wc_get_order_item_meta( $item_id_line_item, '_line_total', true );
        
        // Get seconde bussiness id type from zatcacompany [ seller ]:
        $seller_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcacompany"));
       
        // Get seconde bussiness id from zatcacompany [ seller ]:
        $seller_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcacompany"));
        
        // get buyer_secondBusinessIDType from zatcacustomers:
        $buyer_second_bussiness_Id_Type = $wpdb->get_var($wpdb->prepare("select secondBusinessIDType from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // get buyer_secondBusinessID from zatcacustomers:
        $buyer_second_bussiness_Id = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcacustomer WHERE Customer = $order_Customer_Id"));
        
        // Get Seller Vat from zatcacompany [ seller ]:
        $seller_VAT_Company = $wpdb->get_var($wpdb->prepare("select VATID from zatcacompany"));

        // Get Seller apartment from zatcacompany [ seller ]:
        $seller_apartmentNum_Company = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcacompany"));
        
        // Get Seller POBoxAdditionalNum from zatcacompany [ seller ]:
        $seller_POBoxAdditionalNum_Company = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcacompany"));
        
        // Get Seller POBox from zatcacompany [ seller ]:
        $seller_POBox_Company = $wpdb->get_var($wpdb->prepare("select POBox from zatcacompany"));
        
        // Get Seller street_Arb from zatcacompany [ seller ]:
        $seller_street_Arb_Company = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcacompany"));
        
        // Get Seller street_Eng from zatcacompany [ seller ]:
        $seller_street_Eng_Company = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcacompany"));
        
        // Get Seller district_Arb from zatcacompany [ seller ]:
        $seller_district_Arb_Company = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcacompany"));
        
        // Get Seller district_Eng from zatcacompany [ seller ]:
        $seller_district_Eng_Company = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcacompany"));
        
        // Get Seller city_Arb from zatcacompany [ seller ]:
        $seller_city_Arb_Company = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcacompany"));
        
        // Get Seller city_Eng from zatcacompany [ seller ]:
        $seller_city_Eng_Company = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcacompany"));
        
        // Get Seller country_Arb from zatcacompany [ seller ]:
        $seller_country_Arb_Company = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcacompany"));
        
        // Get Seller country_Eng from zatcacompany [ seller ]:
        $seller_country_Eng_Company = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcacompany"));
        
        // Get Seller CountryNo from zatcacompany [ seller ]:
        $seller_CountryNo_Company = $wpdb->get_var($wpdb->prepare("select countryNo from zatcacompany"));
        
        // get aName from zatcacustomers:
        $buyer_aName_Customer = $wpdb->get_var($wpdb->prepare("select aName from zatcacustomer WHERE Customer = $order_Customer_Id"));
                
        // get eName from zatcacustomers:
        $buyer_eName_Customer = $wpdb->get_var($wpdb->prepare("select eName from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get apartmentNum from zatcacustomers:
        $buyer_apartmentNum_Customer = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get POBoxAdditionalNum from zatcacustomers:
        $buyer_POBoxAdditionalNum_Customer = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get PostalCode from zatcacustomers:
        $buyer_PostalCode_Customer = $wpdb->get_var($wpdb->prepare("select POBox from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get street_Arb from zatcacustomers:
        $buyer_street_Arb_Customer = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get street_Eng from zatcacustomers:
        $buyer_street_Eng_Customer = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get district_Arb from zatcacustomers:
        $buyer_district_Arb_Customer = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get district_Eng from zatcacustomers:
        $buyer_district_Eng_Customer = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get city_Arb from zatcacustomers:
        $buyer_city_Arb_Customer = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get city_Arb from zatcacustomers:
        $buyer_city_Eng_Customer = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcacustomer WHERE Customer = $order_Customer_Id"));

        // get CountryNo from zatcacustomers:
        $buyer_CountryNo_Customer = $wpdb->get_var($wpdb->prepare("select country_No from zatcacustomer WHERE Customer = $order_Customer_Id"));


        // Variables of data:
        
        $device_No = $deviceNo_from_db;
        $delivery_Date = $form_array['deliveryDate'];
        $latest_Delivery_Date = $form_array['gaztLatestDeliveryDate'];
        $zatca_Invoice_Type = $form_array['zatcaInvoiceType'];
        $amount_Payed = $form_array['amountPayed01'];
        $sum_Payed = $sub_total;
        $sub_Total =$sub_net_total;
        $sub_Total_Discount = $form_array['subTotalDiscount'];
        // $taxRate1_Percentage = $form_array['taxRate1_Percentage'];
        // $taxRate1_Total = $form_array['taxRate1_Total'];
        $sub_Net_Total = $form_array['subNetTotal'];
        $sub_Net_Total_Plus_Tax = $form_array['subNetTotalPlusTax'];
        $discount = $form_array['discount-percentage'];
        $totalDiscount = $form_array['totalDiscount'];
        $previousDocumentNo = $previousDocumentNo_from_db;
        $seller_secondBusinessIDType = $seller_second_bussiness_Id_Type;
        $seller_secondBusinessID = $seller_second_bussiness_Id;
        $buyer_secondBusinessIDType = $buyer_second_bussiness_Id_Type;
        $buyer_secondBusinesvsID = $buyer_second_bussiness_Id;
        $zatca_VATCategoryCode = $form_array['vat-cat-code'];
        $VATCategoryCodeSubTypeNo = $form_array['vat-cat-code-sub-no'];
        $taxExemptionReason = $form_array['taxExemptionReason'];
        $zatcaInvoiceTransactionCode_isNominal = $form_array['isNominal'];
        $zatcaInvoiceTransactionCode_isExports = $form_array['isExports'];
        $zatcaInvoiceTransactionCode_isSummary = $form_array['isSummary'];
        // $note = $form_array['note'];
        $uuid = $uuid_from_db;
        $seller_VAT = $seller_VAT_Company;
        $seller_apartmentNum = $seller_apartmentNum_Company;
        $seller_POBoxAdditionalNum = $seller_POBoxAdditionalNum_Company;
        $seller_POBox = $seller_POBoxAdditionalNum_Company;
        $seller_street_Arb = $seller_street_Arb_Company;
        $seller_street_Eng = $seller_street_Eng_Company;
        $seller_district_Arb = $seller_district_Arb_Company;
        $seller_district_Eng = $seller_district_Eng_Company;
        $seller_city_Arb = $seller_city_Arb_Company;
        $seller_city_Eng = $seller_city_Eng_Company;
        $seller_country_Arb = $seller_country_Arb_Company;
        $seller_country_Eng = $seller_country_Eng_Company;
        $seller_CountryNo = $seller_CountryNo_Company;
        $buyer_aName = $buyer_aName_Customer;
        $buyer_eName = $buyer_eName_Customer;
        $buyer_apartmentNum = $buyer_apartmentNum_Customer;
        $buyer_POBoxAdditionalNum = $buyer_POBoxAdditionalNum_Customer;
        $buyer_PostalCode = $buyer_PostalCode_Customer;
        $buyer_street_Arb = $buyer_street_Arb_Customer;
        $buyer_street_Eng = $buyer_street_Eng_Customer;
        $buyer_district_Arb = $buyer_district_Arb_Customer;
        $buyer_district_Eng = $buyer_district_Eng_Customer;
        $buyer_city_Arb = $buyer_city_Arb_Customer;
        $buyer_city_Eng = $buyer_city_Eng_Customer;
        $buyer_CountryNo = $buyer_CountryNo_Customer;

        // check if delivery date is null:
            if(empty($delivery_Date)){
                $delivery_Date = date('Y-m-d');
            }
    
            // check if latest_Delivery_Date  is null:
            if(empty($latest_Delivery_Date)){
                $latest_Delivery_Date = date('Y-m-d');
            }


        // $table_name_document = 'zatcadocument';
        $data = array(
            
                'deviceNo' => $device_No,
                'invoiceNo' => $woo_invoice_No,
                'deliveryDate' => $delivery_Date,
                'gaztLatestDeliveryDate' => $latest_Delivery_Date,
                'zatcaInvoiceType' => $zatca_Invoice_Type,
                'amountPayed01' => $amount_Payed,
                'sumPayed' => $sum_Payed,
                'subTotal' => $sub_Total,
                'subTotalDiscount' => $sub_Total_Discount,
                'subNetTotal' => $sub_Net_Total,
                'subNetTotalPlusTax' => $sub_Net_Total_Plus_Tax,
                'discount' => $discount,
                'totalDiscount' => $totalDiscount,
                'previousDocumentNo' => $previousDocumentNo,
                'seller_secondBusinessIDType' => $seller_secondBusinessIDType,
                'seller_secondBusinessID' => $seller_secondBusinessID,
                'buyer_secondBusinessIDType' => $buyer_secondBusinessIDType,
                'buyer_secondBusinessID' => $buyer_secondBusinesvsID,
                'VATCategoryCodeNo' => $zatca_VATCategoryCode,
                'VATCategoryCodeSubTypeNo' => $VATCategoryCodeSubTypeNo,
                'zatca_TaxExemptionReason' => $taxExemptionReason,
                'zatcaInvoiceTransactionCode_isNominal' => $zatcaInvoiceTransactionCode_isNominal,
                'zatcaInvoiceTransactionCode_isExports' => $zatcaInvoiceTransactionCode_isExports,
                'zatcaInvoiceTransactionCode_isSummary' => $zatcaInvoiceTransactionCode_isSummary,
                'UUID' => $uuid,
                'seller_VAT' => $seller_VAT,
                'seller_apartmentNum' => $seller_apartmentNum,
                'seller_street_Arb' => $seller_street_Arb,
                'seller_street_Eng' => $seller_street_Eng,
                'seller_district_Arb' => $seller_district_Arb,
                'seller_district_Eng' => $seller_district_Eng,
                'seller_city_Arb' => $seller_city_Arb,
                'seller_city_Eng' => $seller_city_Eng,
                'seller_country_Arb' => $seller_country_Arb,
                'seller_country_Eng' => $seller_country_Eng,
                'seller_country_No' => $seller_CountryNo,
                'seller_POBox' => $seller_POBox,
                'seller_POBoxAdditionalNum' => $seller_POBoxAdditionalNum,
                'buyer_aName' => $buyer_aName,
                'buyer_eName' => $buyer_eName,
                'buyer_apartmentNum' => $buyer_apartmentNum,
                'buyer_street_Arb' => $buyer_street_Arb,
                'buyer_street_Eng' => $buyer_street_Eng,
                'buyer_district_Arb' => $buyer_district_Arb,
                'buyer_district_Eng' => $buyer_district_Eng,
                'buyer_city_Arb' => $buyer_city_Arb,
                'buyer_city_Eng' => $buyer_city_Eng,
                'buyer_country_No' => $buyer_CountryNo,
                'buyer_POBox' => $buyer_PostalCode,
                'buyer_POBoxAdditionalNum' => $buyer_POBoxAdditionalNum,
            
            
        );
    
        $where = array('documentNo' => $documentNo);
        $update_result = $wpdb->update($table_name_document, $data, $where);
    

        if ($update_result === false) {
            // There was an error inserting data:
            error_log('Edit error: ' . $wpdb->last_error);
            echo "Error Editing data: " . $wpdb->last_error;
        } else {

            echo 'Data Updated';
        }
       
    }

    die();
}

// Function to update Seller - Buyer Data Before Send:
function update_zatca($doc_no){

    global $wpdb;

    $documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcadocument WHERE documentNo = $doc_no") );

        

    $zatca_TaxExemptionReason = '';
    $nominalInvoice = '';
    $exportsInvoice = '';
    $summaryInvoice = '';
    $taxSchemeId = '';

    $order_Id = 0;

    // Define zatcadocument fields to update:
    $update_seller_sellerName='';
    $update_seller_sellerAdditionalIdType='';
    $update_seller_sellerAdditionalIdNumber='';
    $update_seller_sellerVatNumber='';
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




    // Get Data From zatcadocument:
    foreach($documents as $doc){

        $order_Id = $doc->invoiceNo;

        $invoiceType = "TAX_INVOICE";
        $invoiceTypeCode = "Standard";
        $id =  $doc->documentNo;
        $icvIncrementalValue = (int)$doc->documentNo;
        $referenceId = $doc->UUID;
        $issueDate = date("Y-m-d", strtotime($doc->dateG));
        $issueTime = date("H:i:s", strtotime($doc->dateG));
        $previousHash = "NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==";
        
        // Validation for zatcaInvoiceTransactionCode If 0 = true - if Null = False:
        $nominalInvoice = (isset($doc->zatcaInvoiceTransactionCode_isNominal) && $doc->zatcaInvoiceTransactionCode_isNominal==0) ? true : false;
        $exportsInvoice = (isset($doc->zatcaInvoiceTransactionCode_isExports) && $doc->zatcaInvoiceTransactionCode_isExports==0) ? true : false;
        $summaryInvoice = (isset($doc->zatcaInvoiceTransactionCode_isSummary) && $doc->zatcaInvoiceTransactionCode_isSummary==0) ? true : false;
        
    }

    
    // Update Seller Data From zatcacompany:
    $seller_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcacompany"));

    
    foreach($seller_update as $seller){
        
        $company_VATCategoryCode =$seller->VATCategoryCode;

        $company_VATCategoryCodeSubTypeNo=$seller->VATCategoryCodeSubTypeNo;

        // Get code info from zatcabusinessidtype Table:
    
        $seller_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype     WHERE codeNumber = $seller->secondBusinessIDType") );
        
        $taxSchemeId = $wpdb->get_var($wpdb->prepare("SELECT codeName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $seller->VATCategoryCode"));


        $sellerName = $seller->aName;
        $sellerAdditionalIdType = $seller_codeInfo;
        $sellerAdditionalIdNumber = $seller->secondBusinessID;
        $sellerVatNumber = $seller->VATID;
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


    // Update Buyer Data From zatcacustomer:
    $buyer_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcacustomer WHERE Customer = $customer_Id"));
    

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
        $update_buyer_POBox=$buyer->POBox;
        $update_buyer_district_Arb=$buyer->district_Arb;
        $update_buyer_buyerAdditionalIdNumber=$buyerAdditionalIdNumber;
        $update_buyer_buyerVatNumber=$buyerVatNumber ;
    }


    // Loop On zatcadocumentunit to get data:
    $documents_unit = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcadocumentunit WHERE documentNo = $doc_no") );

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
                    "amount" => (int)number_format((float)$unit->price, 2, '.', '')  
                ],
                "lineQuantity" => $unit->quantity,
                "lineNetAmount" => [
                    "currencyCode" => "SAR",
                    "amount" =>  (int)number_format((float)$unit->netAmount, 2, '.', '')  
                ],
                "lineDiscountAmount" => [
                    "currencyCode" => "SAR",
                    "amount" => (int)number_format((float)$unit->discount, 2, '.', '') 
                ],
                "lineVatRate" => number_format((float)$unit->vatRate, 2, '.', '') , //$unit->vatRate,
                "lineVatAmount" => [
                    "currencyCode" => "SAR",
                    "amount" => number_format((float)$unit->vatAmount, 2, '.', '') 
                ],
                "lineAmountWithVat" => [
                    "currencyCode" => "SAR",
                    "amount" => number_format((float)$unit->amountWithVAT, 2, '.', '') 
                ],
                "taxScheme" => "VAT",
                "taxSchemeId" => $taxSchemeId
            
        ];

        $netAmount = (float)$unit->netAmount;
        $vatAmount = (float)$unit->vatAmount;
        $amountWithVAT = (float)$unit->amountWithVAT;
        $discount = (float)$unit->discount;
    
        $totalAmountWithoutVat += $netAmount;
        $totalLineNetAmount += $netAmount;
        $totalVatAmount += $vatAmount;
        $totalAmountWithVat += $amountWithVAT;
        $totalDiscountAmount += $discount;

    }

    $totalAmountWithoutVat = number_format($totalAmountWithoutVat, 2, '.', '');
    $totalLineNetAmount = number_format($totalLineNetAmount, 2, '.', '');
    $totalVatAmount = number_format($totalVatAmount, 2, '.', '');
    $totalAmountWithVat = number_format($totalAmountWithVat, 2, '.', '');
    $totalDiscountAmount = number_format($totalDiscountAmount, 2, '.', '');
    $taxPercent = number_format((float)$unit->vatRate, 2, '.', ''); //$unit->vatRate;


    $totalAmountWithoutVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithoutVat];
    $totalLineNetAmount = ["currencyCode" => "SAR", "amount" => $totalLineNetAmount];
    $totalVatAmount = ["currencyCode" => "SAR", "amount" => $totalVatAmount];
    $totalAmountWithVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithVat];
    $totalDiscountAmount = ["currencyCode" => "SAR", "amount" => $totalDiscountAmount];
    $taxCategory = "S";
    $taxPercent = "15.0";
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
        'VATCategoryCodeNo' =>              $update_seller_sellerVatNumber,
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

    $update_result = $wpdb->update('zatcadocument', $update_data_document, $where);

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
            "reason" => "",
            "invoiceNo" => ""
        ],
        "taxExemptionReasonCode" => "",
        "taxExemptionReason" => "",
        "invoiceNote" => "",
        "prePaidAmount" => $prePaidAmount,
        "invoiceTypeTransactionCode" => $invoiceTypeTransactionCode
    ];

    // Sandbox
    // $data = [
    //     "invoiceType"=> "TAX_INVOICE",
    //     "invoiceTypeCode"=> "Standard",
    //     "id"=> $id,
    //     "icvIncrementalValue"=> $icvIncrementalValue,
    //     "referenceId"=> $referenceId,
    //     "issueDate"=> $issueDate,
    //     "issueTime"=> $issueTime,
    //     "previousHash"=> $previousHash,
    //     "seller"=> [
    //         "name"=> $sellerName,
    //         "additionalIdType"=> "OTH",
    //         "additionalIdNumber"=> $sellerAdditionalIdNumber,
    //         "vatNumber"=> "399999999900003",
    //         "groupVatNumber"=> "",
    //         "address"=> [
    //         "streetName"=> "12, Masjid Street",
    //         "additionalNo"=> "1234",
    //         "buildingNumber"=> "1243",
    //         "city"=> "Dammam",
    //         "state"=> "Riyadh",
    //         "zipCode"=> "12313",
    //         "district"=> "Riyadh",
    //         "country"=> "SA"
    //         ]
    //     ],
    //     "buyer"=> [
    //         "name"=> "Zahid Gani",
    //         "address"=> [
    //         "streetName"=> "12, Masjid Street",
    //         "additionalNo"=> "1234",
    //         "buildingNumber"=> "1243",
    //         "city"=> "Dammam",
    //         "state"=> "Riyadh",
    //         "zipCode"=> "12313",
    //         "district"=> "Riyadh",
    //         "country"=> "SA"
    //         ],
    //         "additionalIdType"=> "OTH",
    //         "additionalIdNumber"=> "12345678",
    //         "vatNumber"=> "399999999900003",
    //         "groupVatNumber"=> ""
    //     ],
    //     "lineItems"=> [
    //         [
    //         "id"=> "1",
    //         "description"=> "Macbook air m2",
    //         "linePrice"=> [
    //             "currencyCode"=> "SAR",
    //             "amount"=> 10
    //         ],
    //         "lineQuantity"=> "50",
    //         "lineNetAmount"=> [
    //             "currencyCode"=> "SAR",
    //             "amount"=> 500
    //         ],
    //         "lineDiscountAmount"=> [
    //             "currencyCode"=> "SAR",
    //             "amount"=> 0
    //         ],
    //         "lineVatRate"=> "15.00",
    //         "lineVatAmount"=> [
    //             "currencyCode"=> "SAR",
    //             "amount"=> 75
    //         ],
    //         "lineAmountWithVat"=> [
    //             "currencyCode"=> "SAR",
    //             "amount"=> 575
    //         ],
    //         "taxScheme"=> "VAT",
    //         "taxSchemeId"=> "S"
    //         ]
    //     ],
    //     "totalAmountWithoutVat"=> [
    //         "currencyCode"=> "SAR",
    //         "amount"=> 500
    //     ],
    //     "totalLineNetAmount"=> [
    //         "currencyCode"=> "SAR",
    //         "amount"=> 500
    //     ],
    //     "totalVatAmount"=> [
    //         "currencyCode"=> "SAR",
    //         "amount"=> 75
    //     ],
    //     "totalAmountWithVat"=> [
    //         "currencyCode"=> "SAR",
    //         "amount"=> 575
    //     ],
    //     "totalDiscountAmount"=> [
    //         "currencyCode"=> "SAR",
    //         "amount"=> 0
    //     ],
    //     "taxCategory"=> "S",
    //     "taxPercent"=> "15.0",
    //     "supplyDate"=> "2024-04-29",
    //     "lastestSupplyDate"=> "2024-04-29",
    //     "invoiceCurrencyCode"=> "SAR",
    //     "taxCurrencyCode"=> "SAR",
    //     "note"=> [
    //         "reason"=> "",
    //         "invoiceNo"=> ""
    //     ],
    //     "taxExemptionReasonCode"=> "",
    //     "taxExemptionReason"=> "",
    //     "invoiceNote"=> "",
    //     "prePaidAmount"=> [
    //         "currencyCode"=> "SAR",
    //         "amount"=> 0
    //     ],
    //     "invoiceTypeTransactionCode"=> [
    //         "thirdPartyInvoice"=> false,
    //         "nominalInvoice"=> false,
    //         "exportsInvoice"=> false,
    //         "summaryInvoice"=> false,
    //         "selfBilledInvoice"=> false
    //     ]
    // ];

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

    $msg = '';

    // Validation Fields:
    $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
    $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
    
    $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
    $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

    // validation on seller_additionalIdNumber & buyer_additionalNo:
    if($seller_additionalIdNumber_validation == false){

        $msg = 'You Muse Insert Seller additional Id Number in zatca Company';
        // $msg = var_dump($requestArray);
        
      
    }elseif($buyer_additionalNo_validation == false){ // Validation on additionalNo - customer [ buyer ]:
        
        $msg = 'You Muse Insert Buyer additional Number in zatca customer';

    }else{

   
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Clear?deviceID=faf911aa-ad52-498c-8d85-e8ed4ee26f83&skipPortalValidation=false',
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
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDMxMzY2ODYsIlVzZXJJZCI6MzYsIlV1aWQiOiIzMTRmNDRlMC1mNGI4LTRiY2MtYTgwOS03MTgwYTBiMjE1YjAiLCJSZWFkIjp0cnVlLCJXcml0ZSI6dHJ1ZSwiV2hpdGVMaXN0SXAiOm51bGwsIklzc3VlZEF0IjoiMjAyNC0wNS0yNlQxNToxODowNi41NjQ0NTE5KzAwOjAwIn0.najUbWm0ME1aRJQrDrUINr2ztM4zYkcuhzsyfERFOAI'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            echo "cURL Error: $error (Error Code: $errorCode)";
        }
        
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       

        $http_status_msg = "HTTP Status Code: " . $http_status . "\n";

        curl_close($curl);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($curl);
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

        // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
        $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                FROM zatcadocument 
                                                                ORDER BY zatcaResponseDate DESC 
                                                                LIMIT 1"));
        
        if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
            foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                if (isset($Message['message'])) {
                    $warningMessage = $Message['message'];
                }else{

                    echo 'WRONG';
                }
            }
        }

        
        // Check If response Valid:
        if($clearanceStatus == 'CLEARED'){

            if($statusCode == '200'){

                //  update zatca document xml fields with response Data:
                $zatcadocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 0
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcadocumentxml_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcadocumentxml_update_response_result === 0) {
                    
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                
                }  
    
                // update zatca document fields with response Data:
                $zatcadocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 1,
                    "previousInvoiceHash" => $hashed
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcadocument_update_response_result === false) {
                    // Handle error
                    $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                }elseif ($zatcadocument_update_response_result === 0) {
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                $msg = 'Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
            
            }elseif($statusCode == '202'){

                 // update zatca document xml fields with response Data:
                $zatcadocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 0
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcadocumentxml_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcadocumentxml_update_response_result === 0) {
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }   
    
                // update zatca document fields with response Data:
                $zatcadocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 2,
                    "previousInvoiceHash" => $hashed,
                    "zatcaErrorResponse" => $warningMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcadocument_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcadocument_update_response_result === 0) {
                   
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }
                $msg = 'Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
            }
          
        }else{

            if($http_status == '400'){

                // update zatca document fields with response Data:
                $zatcadocument_error_response_data = [

                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 3,
                    "zatcaErrorResponse" => $errorMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcadocument_error_response_result = $wpdb->update('zatcadocument', $zatcadocument_error_response_data, $where);
                
                // Check for errors
                if ($zatcadocument_error_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcadocument_error_response_result === 0) {
                    
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // check if have error message or not:
                if(is_array($validationResults)){

                    $msg = $http_status_msg . ' Error: ' . $errorMessage;

                }else{

                    $msg = $http_status_msg . ' Error: ' . $validationResults;
                }

                
            
            }else{

                $msg = $http_status_msg;
            }

        }

        // Log the send to zatca:
        $user_login = wp_get_current_user()->user_login;
        $user_id = wp_get_current_user()->ID;
        log_send_to_zatca($user_login, $user_id);

        $send_response = [

            'msg' => $msg,
            'validationResults' => $validationResults,
            'responseArray' => $responseArray,
            'data' => $data

        ];

        wp_send_json($send_response);
      
        
    }
        
    die();
}


// function to get response and conver invoice to xml and create xml file: 
function get_xml_from_response() {

    // Get document number from AJAX request
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    global $wpdb;

    // Get response from zatcadocumentxml
    $xmlResponse = $wpdb->get_var($wpdb->prepare("SELECT APIResponse FROM zatcadocumentxml WHERE documentNo = %d", $doc_no));

    if ($xmlResponse === null) {
        wp_send_json_error(['message' => 'No response found for document number ' . $doc_no]);
    }

    $responseArray = json_decode($xmlResponse, true);
    $clearedInvoice = $responseArray['clearedInvoice'];

    // Decode cleared hash:
    $decoded_data = base64_decode($clearedInvoice);

    if ($decoded_data === false) {
        wp_send_json_error(['message' => 'Failed to decode the cleared invoice']);
    }

    // Parse XML:
    $xml = simplexml_load_string($decoded_data);

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
    $user_login = wp_get_current_user()->user_login;
    $user_id = wp_get_current_user()->ID;
    log_download_doc_xml($user_login, $user_id);

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
    
    
    $table_name = 'zatcalog';
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

add_action('wp_login', 'log_user_login', 10, 2);