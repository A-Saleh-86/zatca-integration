<?php

$clientNo = $_GET['clientno'];


global $wpdb;

// Assuming your table is named 'wp_custom_table'
$table_name = 'zatcaCustomer';

// Define the condition to identify the row(s) to delete
$where = array(
    'clientVendorNo' => $clientNo // Change column_name and value_to_match accordingly
);


// Execute the delete query
$deleted = $wpdb->delete( $table_name, $where );

if ( $deleted === false ) {
//     // Deletion failed
    echo _e('Failed to delete row.', 'zatca');
} else {
    // Deletion successful
    echo _e('Row deleted successfully.', 'zatca');?>
    <script>
        window.location.href = '<?php echo admin_url('admin.php?page=zatca-customers&action=view'); ?>';
    </script>
<?php
}
?>
