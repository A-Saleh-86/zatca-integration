<?php
// filter.php

// Check if the form is submitted
if (isset($_GET['apply_filter'])) {
    // Sanitize date inputs
    $from_date = isset($_GET['from_date']) ? sanitize_text_field($_GET['from_date']) : '';
    $to_date = isset($_GET['to_date']) ? sanitize_text_field($_GET['to_date']) : '';

    if (!empty($from_date) && !empty($to_date)) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zatca_Logging';

        // Prepare the SQL query with date filters
        $query = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE dateG >= %s AND dateG <= %s",
            $from_date,
            $to_date
        );

        // Execute the query
        $submitted_data = $wpdb->get_results($query, ARRAY_A);
        $i = 1;
		
        // Display the submitted data in a table
        $output = '<h2>Data Display</h2><br>';
		// Back button to return to the date filter form
        $output .= '<br><a href="https://appyfruits.com/?page_id=27"> Back </a><br><br>';

        // Date filter form
        $output .= '<form method="get">';
        $output .= 'From Date: <input type="date" name="from_date" value="' . esc_attr($from_date) . '"> ';
        $output .= 'To Date: <input type="date" name="to_date" value="' . esc_attr($to_date) . '"> ';
        $output .= '<input type="submit" name="apply_filter" value="Apply Filter"> ';
        $output .= '</form>';

        if ($submitted_data) {
            // Display the table
			            $output .= '<div class="table-responsive">';
            $output .= '<table class="table table-striped table-bordered">';
            $output .= '<thead>';
            $output .= '<tr><th>S.No.</th><th>Date and Time</th><th>User ID</th><th>Is Success</th><th>Operation Type</th><th>Device IP</th><th>Mac Address</th></tr>';
            $output .= '</thead>';
            $output .= '<tbody>';


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
    } else {
        echo '<p>Please select both From Date and To Date to apply the filter.</p>';
    }

    exit;
}
