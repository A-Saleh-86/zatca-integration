<?php

// Check if the form is submitted
if (isset($_POST['check_counter_gap'])) 
{
    // Call the counter gap function
    check_counter_gap();
}

// Check if the form is submitted
if (isset($_POST['check_hash_gap'])) 
{
    // Call the hash gap function
    check_hash_gap();
}

// callback to check counter gap
function check_counter_gap() {
    global $wpdb;

    // Get the necessary POST data
    $BuildingNo = $_POST['BuildingNo']; // branch id
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Query to fetch the invoice numbers within the specified date range for the given branch
    $query = $wpdb->prepare("
        SELECT invoiceNo
        FROM zatcaDocument
        INNER JOIN zatcaDevice ON zatcaDevice.deviceNo = zatcaDocument.deviceNo
        INNER JOIN zatcaDocument ON zatcaDocument.BuildingNo = zatcaBranch.BuildingNo
        WHERE zatcaBranch.BuildingNo = %d
        AND zatcaDocument.dateG BETWEEN %s AND %s
        ORDER BY invoiceNo
    ", $BuildingNo, $from_date, $to_date);

    $results = $wpdb->get_results($query);
    
    // Initialize variables
    $missing_numbers = [];
    $prev_number = null;

    // Check for missing invoice numbers
    foreach ($results as $result) {
        $current_number = $result->invoiceNo;

        if ($prev_number !== null && $current_number - $prev_number > 1) {
            // Gap detected, add missing numbers to the array
            for ($i = $prev_number + 1; $i < $current_number; $i++) {
                $missing_numbers[] = $i;
            }
        }

        $prev_number = $current_number;
    }

    // Prepare the response data
    $response = array(
        'missing_numbers' => $missing_numbers,
    );

    // Display the results in a grid table
    echo '<table border="1" cellpadding="5" cellspacing="0" style="margin-left:15%">';
    echo '<tr><th>Missing Invoice Numbers</th></tr>';

    foreach ($response['missing_numbers'] as $missing_number) {
        echo '<tr><td>' . $missing_number . '</td></tr>';
    }

    echo '</table>';

    // Return the response
    //wp_send_json_success($response);
}


// callback to check hash gap
function check_hash_gap() {
    global $wpdb;

    // Get the necessary POST data
    $BuildingNo = $_POST['BuildingNo']; // branch id
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Get the necessary POST data
    $encryption_key = 'your_encryption_key_here'; // Replace with actual encryption key
    $results = []; // Array to store the missing records

    // Query to fetch the relevant fields from zatcaDocument
    
    $query = $wpdb->prepare("SELECT z.documentNo, z.deviceNo, z1.invoiceHash FROM zatcaDocument z, zatcaDocumentXML z1 WHERE z.BuildingNo= %d AND z.dateG BETWEEN %s AND %s AND z.documentNo=z1.documentNo",
    $BuildingNo, $from_date, $to_date);

    $documents = $wpdb->get_results($query);

    // Encrypt and search for each document in zatcaInfo
    foreach ($documents as $document) {
        $encrypted_documentNo = encrypt_data($document->documentNo, $encryption_key);
        $encrypted_deviceNo = encrypt_data($document->deviceNo, $encryption_key);
        $encrypted_invoiceHash = encrypt_data($document->invoiceHash, $encryption_key);

        // Query zatcaInfo for matching encrypted values
        $query = $wpdb->prepare("
            SELECT 1
            FROM zatcaInfo
            WHERE zatcaInfo2 = %s
            AND zatcaInfo3 = %s
            AND zatcaInfo1 = %s
        ", $encrypted_documentNo, $encrypted_deviceNo, $encrypted_invoiceHash);

        $result = $wpdb->get_var($query);

        if (!$result) {
            $results[] = $document; // If a document is not found, add the document to results array
        }
    }

    // Prepare and send the response
    $response = array('missing_documents' => $results);
    
    // Display the results in a grid table
    echo '<table border="1" cellpadding="5" cellspacing="0" style="margin-left:15%">';
    echo '<tr><th>Document No</th><th>Device No</th><th>Invoice Hash</th></tr>';

    foreach ($results as $document) {
        echo '<tr>';
        echo '<td>' . $document->documentNo . '</td>';
        echo '<td>' . $document->deviceNo . '</td>';
        echo '<td>' . $document->invoiceHash . '</td>';
        echo '</tr>';
    }

    echo '</table>';

    //wp_send_json_success($response);
}

// Function to encrypt data
function encrypt_data($data, $key) {
    // Make sure to use the same encryption method and key for encryption and decryption
    // Return the encrypted data
}
?>