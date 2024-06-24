<?php

$deviceNo = $_GET['deviceNo'];


global $wpdb;

// Assuming your table is named 'wp_custom_table'
$table_name = 'zatcadevice';

// Define the condition to identify the row(s) to delete
$where = array(
    'deviceNo' => $deviceNo 
);


// Execute the delete query
$deleted = $wpdb->delete( $table_name, $where );

if ( $deleted === false ) {
//     // Deletion failed
    echo "Failed to delete row.";
} else {
    // Deletion successful
    echo "Row deleted successfully.";?>
    <script>
        window.location.href = '<?php echo admin_url('admin.php?page=zatca-devices&action=view'); ?>';
    </script>
<?php
}
?>
