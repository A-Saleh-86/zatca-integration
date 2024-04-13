<?php
/*
Plugin Name: ZATCA Settings
Description: Custom settings for ZATCA integration with WooCommerce.
Version: 1.0
Author: Your Name
*/

// Plugin code will go here

// Add a new tab to WooCommerce settings
add_filter('woocommerce_settings_tabs_array', 'add_zatca_settings_tab', 50);

function add_zatca_settings_tab($tabs) {
    $tabs['zatca_settings'] = __('ZATCA Settings', 'zatca-settings');
    return $tabs;
}

// Include the settings page content
add_action('woocommerce_settings_tabs_zatca_settings', 'zatca_settings_tab_content');

function zatca_settings_tab_content() {
    // Output ZATCA settings form fields
    include_once('zatca-settings-form.php');
}

// Add AJAX action for fetching billing address
add_action('wp_ajax_fetch_billing_address', 'fetch_billing_address');




?>