<?php
/*
Plugin Name: ZATCA Logging Plugin Display
Description: Adds ZATCA functionality to WooCommerce.
Version: 1.0
Author: Your Name
Author URI: https://appyfruits.com/?page_id=
*/

function zatca_logging_display() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'zatca_Logging';

    // Default date filter values
    $from_date = isset($_GET['from_date']) ? sanitize_text_field($_GET['from_date']) : '';
    $to_date = isset($_GET['to_date']) ? sanitize_text_field($_GET['to_date']) : '';

    // Prepare the base SQL query
    $query = "SELECT * FROM $table_name WHERE 1=1";

    // Add date filters if provided
    if (!empty($from_date)) {
        $query .= " AND dateG >= '" . esc_sql($from_date) . "'";
    }
    if (!empty($to_date)) {
        $query .= " AND dateG <= '" . esc_sql($to_date) . "'";
    }

    // Execute the query
    $submitted_data = $wpdb->get_results($query, ARRAY_A);
    $i = 1;

    // Display the submitted data in a table
    $output = '<h2>Data Display</h2><br>';

    // Date filter form
    $output .= '<form method="get">';
    $output .= 'From Date: <input type="date" name="from_date" value="' . esc_attr($from_date) . '"> ';
    $output .= 'To Date: <input type="date" name="to_date" value="' . esc_attr($to_date) . '"> ';
    $output .= '<input type="submit" class="btn btn-primary" name="apply_filter" value="Apply Filter"> ';
    $output .= '</form>';

    if ($submitted_data) {
        // Display the table
        $output .= '<table class="table table-striped table-bordered">';
        $output .= '<tr><th>S.No.</th><th>Date and Time</th><th>User ID</th><th>Is Success</th><th>Operation Type</th><th>Device IP</th><th>Mac Address</th></tr>';
        
        foreach ($submitted_data as $data) {
            $output .= '<tr>';
            $output .= '<td>' . $i . '</td>';
            $output .= '<td>' . esc_html($data['dateG']) . '</td>';
            $output .= '<td>' . esc_html($data['userId']) . '</td>';
            $output .= '<td>' . esc_html($data['isSuccess'] ? 'Yes' : 'No') . '</td>';
            $output .= '<td>' . esc_html($data['operationType']) . '</td>';
            $output .= '<td>' . esc_html($data['deviceIP']) . '</td>';
            $output .= '<td>' . esc_html($data['macAddress']) . '</td>';
            $output .= '</tr>';
            $i++;
        }
        $output .= '</table>';
    } else {
        $output .= '<p>No data found based on the selected date range.</p>';
    }

    // Output the generated content
    echo $output;
}

// Shortcode handler
function zatca_logging_display_shortcode() {
    ob_start();
    zatca_logging_display();
    return ob_get_clean();
}
add_shortcode('zatca_logging_display', 'zatca_logging_display_shortcode');
require_once(plugin_dir_path(__FILE__) . 'filter.php');