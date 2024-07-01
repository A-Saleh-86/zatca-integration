<?php
/*
Plugin Name: Zacta Tampering Detector
Description: Plugin to check invoice sequences and hash values
Version: 1.0
Author: MBAUOMY
*/

// Enqueue scripts and styles
function invoice_audit_enqueue_scripts() {
    // Enqueue necessary scripts and stylesheets
}
add_action('admin_enqueue_scripts', 'invoice_audit_enqueue_scripts');

// Create admin page
function invoice_audit_admin_page() {
    // 
    add_menu_page(
        'Zacta Tampering Detector', // Page title
        'Zacta Tampering Detector', // Menu title
        'manage_options', // Capability required to access the page
        'invoice-audit-admin-page', // Unique menu slug
        'invoice_audit_admin_page_content', // Callback function to display page content
        'dashicons-admin-generic', // Icon URL or WordPress dashicon class
        4 // Menu position
    );
}
add_action('admin_menu', 'invoice_audit_admin_page');

// Admin page content
function invoice_audit_admin_page_content() {
    echo '<div class="wrap">';
    echo '<h2>Zacta Tampering Detector</h2>';
    // Add your admin page content here
    echo do_shortcode('[invoice_audit_form]');
    echo '</div>';
}


// Shortcode callback function to check gap form
function invoice_audit_form_shortcode()
{
    ob_start();
    require_once(plugin_dir_path(__FILE__) . 'invoice_audit_form.php');
    return ob_get_clean();
}
add_shortcode('invoice_audit_form', 'invoice_audit_form_shortcode');

// Database Operations Here>> check_gap.php
require_once(plugin_dir_path(__FILE__) . 'check_gap.php');
