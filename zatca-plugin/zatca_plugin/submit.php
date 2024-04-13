<?php
// Check if form is submitted
if (isset($_POST['submit_button'])) {
    // Get form data
    $VATID = $_POST['VATID'];
    $secondBusinessIDType = $_POST['secondBusinessIDType'];
    $secondBusinessID = $_POST['secondBusinessID'];
    $zatcaInvoiceType = $_POST['zatcaInvoiceType'];
    $apartmentNo = $_POST['apartmentNo'];
    $additionalNo = $_POST['additionalNo'];
    $postalCode = $_POST['postalCode'];
    $streetName = $_POST['streetName'];
    $districtName = $_POST['districtName'];
    $cityName = $_POST['cityName'];
    $countryName = $_POST['countryName'];
    $countryNo = $_POST['countryNo'];

    // Insert data into the custom table
    global $wpdb;
    $table_name = $wpdb->prefix . 'zatca_Customers';
    $result =  $wpdb->insert(
        $table_name,
        array(
            'VATID' => $VATID,
            'secondBusinessIDType' => $secondBusinessIDType,
            'secondBusinessID' => $secondBusinessID,
            'zatcaInvoiceType' => $zatcaInvoiceType,
            'apartmentNo' => $apartmentNo,
            'additionalNo' => $additionalNo,
            'postalCode' => $postalCode,
            'streetName' => $streetName,
            'districtName' => $districtName,
            'cityName' => $cityName,
            'countryName' => $countryName,
            'countryNo' => $countryNo,
        )
    );
    if ($result === false) {
        // Error handling
        echo '<p>Error inserting data: ' . $wpdb->last_error . '</p>';
    } else {
        echo '<p>Data submitted successfully!</p>';
        // Redirect after displaying success message
        echo '<script>window.location.href="https://appyfruits.com/?page_id=12";</script>';
        exit;
    }
}


// Check if the form is submitted
if (isset($_POST['submit'])) {
    global $wpdb; // WordPress database access abstraction layer

    // Retrieve form data
    $dateG = $_POST['dateG'];
	$userId =  $_POST['userId'];
    $isSuccess = $_POST['isSuccess'];
    $operationType = $_POST['operationType'];
    $deviceIP = $_POST['deviceIP'];
    $macAddress = $_POST['macAddress'];

    // Insert data into the database
    $table_name = $wpdb->prefix . 'zatca_Logging'; // Assuming your table is prefixed with WordPress table prefix
   $result =  $wpdb->insert(
        $table_name,
        array(
            'dateG' => $dateG,
			 'userId' => $userId,
			
            'isSuccess' => $isSuccess,
            'operationType' => $operationType,
            'deviceIP' => $deviceIP,
            'macAddress' => $macAddress,
        ),
        array(
            '%s', // dateG
			'%d', // userId
			
            '%d', // isSuccess
            '%s', // operationType
            '%s', // deviceIP
            '%s'  // macAddress
        )
    );

    // Optionally, you can add success/error messages or redirection here
    // For example:
   if ($result === false) {
        // Error handling
        echo '<p>Error inserting data: ' . $wpdb->last_error . '</p>';
    } else {
        echo '<p>Data submitted successfully!</p>';
        // Redirect after displaying success message
        echo '<script>window.location.href="https://appyfruits.com/?page_id=24";</script>';
        exit;
    }
}


?>