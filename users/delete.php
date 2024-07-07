<?php

$personNo = $_GET['personNo'];


global $wpdb;

$table_name = 'zatcauser';

// Define the condition to identify the row(s) to delete
$where = array(
    'personNo' => $personNo
);


// Execute the delete query
$deleted = $wpdb->delete( $table_name, $where );

if ( $deleted === false ) {
    // Deletion failed
    echo "Failed to delete row.";
} else {
    // Deletion successful
    echo "Row deleted successfully.";?>
    <script>
        window.location.href = '<?php echo admin_url('admin.php?page=zatca-users&action=view'); ?>';
    </script>
<?php
}
?>
