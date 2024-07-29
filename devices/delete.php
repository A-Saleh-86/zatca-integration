<?php

$deviceNo = $_GET['deviceNo'];


global $wpdb;

// Assuming your table is named 'wp_custom_table'
$table_name = 'zatcaDevice';

// Define the condition to identify the row(s) to delete
$where = array(
    'deviceNo' => $deviceNo 
);


// Execute the delete query
$deleted = $wpdb->delete( $table_name, $where );

if ( $deleted === false ) {
//     // Deletion failed
    echo __("Failed to delete device.","zatca");
} else {
    // Deletion successful
    echo __("Device deleted successfully.","zatca");?>
    <script>
        window.location.href = '<?php echo admin_url('admin.php?page=zatca-devices&action=view'); ?>';
    </script>
<?php
}
?>
