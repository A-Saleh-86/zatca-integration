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
}
add_action('admin_menu', 'invoice_audit_admin_page');


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
