<?php
/*
Plugin Name: ZATCA Plugin
Plugin URI: http://localhost/crebritech/demo-test1/wordpress/zatca-plugin
Description: Adds ZATCA functionality to WooCommerce.
Version: 1.0
Author: Your Name
Author URI: http://localhost/crebritech/demo-test1/wordpress/
*/
add_action('admin_menu', 'zatca_add_menu');
function zatca_add_menu() {
    add_menu_page('ZATCA Plugin', 'ZATCA Plugin', 'manage_options', 'zatca-plugin', 'zatca_plugin_page');
}


function zatca_plugin_page() {
}

function zatca_customer_form_shortcode() {
    ob_start(); // Start output buffering
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> ZATCA Customer Form </title>
    </head>
    <body>
    <h2>Add ZATCA Customer</h2>
    <form action="" method="post">
    <div class="form-group">
    <input type="hidden" class="form-control"  value="<?php echo esc_attr(get_current_user_id()); ?>"><br><br>
  
        <label for="VATID" class="form-label">VAT ID:</label><br>
        <input type="text" class="form-control"  id="VATID" name="VATID" required><br><br>
    </div>
    <div class="form-group">
        <label for="secondBusinessIDType" class="form-label">Second Business ID Type:</label><br>
        <select id="secondBusinessIDType"  class="form-control" name="secondBusinessIDType" required>
            <option value="1">Type 1</option>
            <option value="2">Type 2</option>
            <option value="3">Type 3</option>
        </select><br><br>
    </div>                                                                                                                                                                                                               
    <div class="form-group">
        <label for="secondBusinessID" class="form-label">Additional Business ID:</label><br>
        <input type="text" class="form-control" id="secondBusinessID" name="secondBusinessID"><br><br>
     </div>
    <div class="form-group">
        <label for="zatcaInvoiceType"class="form-label">ZATCA Invoice Type:</label><br>
        <select id="zatcaInvoiceType" class="form-control"  name="zatcaInvoiceType" required>
            <option value="B2B">B2B</option>
            <option value="B2C">B2C</option>
            <option value="Both">Both</option>
        </select><br><br>
    </div>
    <div class="form-group">
        <label for="apartmentNo" class="form-label">Apartment Number:</label><br>
        <input type="text" class="form-control" id="apartmentNo" name="apartmentNo" pattern="[0-9]{4}" title="Please enter a 4-digit number" required><br><br>
    </div>
    <div class="form-group">
        <label for="additionalNo" class="form-label">Additional Number:</label><br>
        <input type="text"  class="form-control" id="additionalNo" name="additionalNo"><br><br>
    </div>
    <div class="form-group">
        <label for="postalCode" class="form-label">Postal Code:</label><br>
        <input type="text" class="form-control" id="postalCode" name="postalCode" required><br><br>
    </div>
    <div class="form-group">
        <label for="streetName" class="form-label">Street Name:</label><br>
        <input type="text" class="form-control" id="streetName" name="streetName" required><br><br>
    </div>
    <div class="form-group">
        <label for="districtName" class="form-label">District Name:</label><br>
        <input type="text" class="form-control" id="districtName" name="districtName" required><br><br>
    </div>
    <div class="form-group">
        <label for="cityName" class="form-label">City Name:</label><br>
        <input type="text" class="form-control" id="cityName" name="cityName" required><br><br>
    </div>
    <div class="form-group">
        <label for="countryName" class="form-label">Country Name:</label><br>
        <input type="text" class="form-control" id="countryName" name="countryName" value="Saudi Arabia" required><br><br>
    </div>
    <div class="form-group">
        <label for="countryNo" class="form-label">Country Number:</label><br>
        <input type="text" class="form-control" id="countryNo" name="countryNo" required><br><br>

        <input type="submit" class="btn btn-primary" name="submit_button" value="Submit">
		 <input type="button" class="btn btn-primary" name="copy_button" value="Copy" onclick="copyFormData()">
    </div>
    <div class="form-group">
        <!-- <label for="postalCode" class="form-label">Postal Code:</label><br> -->
        <input type="hidden" class="form-control" id="postalCode1" name="postalCode1" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_postcode', true)); ?>"><br><br>
    </div>
    <div class="form-group">
        <!-- <label for="streetName" class="form-label">Street Name:</label><br> -->
        <input type="hidden" class="form-control" id="streetName1" name="streetName" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_address_1', true)); ?>"><br><br>
    </div>
    <div class="form-group">
        <!-- <label for="districtName" class="form-label">District Name:</label><br> -->
        <input type="hidden" class="form-control" id="districtName1" name="districtName" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_city', true)); ?>"><br><br>
    </div>
    <div class="form-group">
        <!-- <label for="cityName" class="form-label">City Name:</label><br> -->
        <input type="hidden" class="form-control" id="cityName1" name="cityName" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_city', true)); ?>"><br><br>
    </div>
    <div class="form-group">
        <!-- <label for="countryName" class="form-label">Country Name:</label><br> -->
        <input type="hidden" class="form-control" id="countryName1" name="countryName" value="Saudi Arabia" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_country', true)); ?>"><br><br>
    </div>
    <div class="form-group">
        <!-- <label for="countryNo" class="form-label">Country Number:</label><br> -->
        <input type="hidden" class="form-control" id="countryNo1" name="countryNo" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_country', true)); ?>"><br><br>

       
    </div>
		</div>
	</form>

    </body>
    </html>
    <?php
    return ob_get_clean(); // Return the buffered output
}

function zatca_customer_form() {
    return zatca_customer_form_shortcode(); // Call the function and return its output
}
add_shortcode('zatca_customer_form1', 'zatca_customer_form');

require_once(plugin_dir_path(__FILE__) . 'submit.php');

// Function to fetch billing address details
add_action('wp_ajax_fetch_billing_address', 'fetch_billing_address');
function fetch_billing_address() {
    // Verify the nonce
    if ( ! check_ajax_referer( 'fetch_billing_address_nonce', 'security', false ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }

    // Retrieve user's billing address data from wp_usermeta table
    $user_id = get_current_user_id();
    $billing_address = array(
        'postalCode' => get_user_meta($user_id, 'billing_postcode', true),
        'streetName' => get_user_meta($user_id, 'billing_address_1', true),
        'districtName' => get_user_meta($user_id, 'billing_address_2', true),
        'cityName' => get_user_meta($user_id, 'billing_city', true),
        'countryName' => get_user_meta($user_id, 'billing_country', true),
        'countryNo' => get_user_meta($user_id, 'billing_country', true),
    );

    // Send the billing address data as JSON with proper Content-Type header
    wp_send_json($billing_address, 200, array('Content-Type' => 'application/json'));

}


// zatca_plugin.php

// Register the action hook to handle viewing logs
function register_zatca_view_logs_action() {
    if (isset($_POST['action']) && $_POST['action'] === 'view_zatca_logs') {
        handle_view_zatca_logs(); // Call the function to handle viewing logs
    }
}
add_action('init', 'register_zatca_view_logs_action');

// Function to include the view.php file for rendering
function include_zatca_view_file() {
    include(plugin_dir_path(__FILE__) . 'view.php'); // Include the view.php file
}




// Add tax_invoice_option checkbox in order details section
add_action( 'woocommerce_admin_order_data_after_billing_address', 'add_tax_invoice_field_to_order_page');
function add_tax_invoice_field_to_order_page( $order ) {
    echo '<div class="order_data_column">';
    woocommerce_wp_checkbox( array(
        'id' => 'tax_invoice_option',
        'label' => __('Request Tax Invoice'),
        'value' => get_post_meta( $order->get_id(), 'tax_invoice_option', true ),
    ) );
    echo '</div>';
    
}

add_action( 'woocommerce_process_shop_order_meta', 'save_tax_invoice_option_on_order_creation', 10, 2 );
function save_tax_invoice_option_on_order_creation( $order_id, $post ) 
{
    if ( isset( $_POST['tax_invoice_option'] ) ) 
    {
        update_post_meta( $order_id, 'tax_invoice_option', 'yes' );
    } 
    else
    {
        update_post_meta( $order_id, 'tax_invoice_option', 'no' );
    }
}



// Add zatca_customer_form1 after the Order Data section on the order page
add_action( 'woocommerce_admin_order_data_after_order_details', 'add_custom_section_after_order_data' );

function add_custom_section_after_order_data( $order ) {

    // Add taxInvoiceOption to be used in JavaScript
    $tax_invoice_option = get_post_meta( $order->get_id(), 'tax_invoice_option', true );
    wp_localize_script( 'custom-script', 'custom_vars', array(
        'taxInvoiceOption' => $tax_invoice_option
    ) );

    // Add zatca_customer_form1 for the new section here
    echo '<div class="custom-section" style="padding-top:250px;">';

    echo do_shortcode('[zatca_customer_form1]');

    echo '</div>';
}


// Enqueue custom JavaScript file to show and hide zactaCustomer
function enqueue_custom_js() {
    wp_enqueue_script( 'custom-script', plugin_dir_url( __FILE__ ) . 'js/custom-script.js', array( 'jquery' ), null, true );
}
add_action( 'admin_enqueue_scripts', 'enqueue_custom_js' );

?>
<script>
    function copyFormData() {
        // Get values from the second form
        var postalCode1 = document.getElementById('postalCode1').value;
        var streetName1 = document.getElementById('streetName1').value;
        var districtName1 = document.getElementById('districtName1').value;
        var cityName1 = document.getElementById('cityName1').value;
        var countryName1 = document.getElementById('countryName1').value;
        var countryNo1 = document.getElementById('countryNo1').value;
        // Get elements in the first form
        var postalCode = document.getElementById('postalCode');
        var streetName = document.getElementById('streetName');
        var districtName = document.getElementById('districtName');
        var cityName = document.getElementById('cityName');
        var countryName = document.getElementById('countryName');
        var countryNo = document.getElementById('countryNo');

        // Set values to the first form
        postalCode.value = postalCode1;
        streetName.value = streetName1;
        districtName.value = districtName1;
        cityName.value = cityName1;
        countryName.value = countryName1;
        countryNo.value = countryNo1;

        // Change the color of retrieved data to red
        postalCode.style.color = 'red';
        streetName.style.color = 'red';
        districtName.style.color = 'red';
        cityName.style.color = 'red';
        countryName.style.color = 'red';
        countryNo.style.color = 'red';
    }
</script>

