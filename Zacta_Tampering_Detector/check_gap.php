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
    check_hash_gap1();
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
        SELECT z1.documentNo
        FROM zatcaDocument z1
        INNER JOIN zatcadevice zd ON zd.deviceNo = z1.deviceNo
        INNER JOIN zatcabranch zb ON z1.buildingNo = zb.buildingNo
        WHERE zb.buildingNo = %d
        AND z1.dateG BETWEEN %s AND %s
        ORDER BY z1.documentNo
    ", $BuildingNo, $from_date, $to_date);

    $results = $wpdb->get_results($query);

    
    // Initialize variables
    $missing_numbers = [];
    $prev_number = null;

    // Check for missing invoice numbers
    foreach ($results as $result) {
        $current_number = $result->documentNo;

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
    echo '<div class="container"><table id="example" class="table table-striped" width="100%">';
    echo '<thead><tr><th class="text-center">' . __("Missing Invoice Numbers", "zatca") . '</th></tr></thead>';
    echo '<tbody class="text-center">';

    foreach ($response['missing_numbers'] as $missing_number) {
        echo '<tr><td>' . $missing_number . '</td></tr>';
    }

    echo '</tbody></table></div>';

    // Return the response
    //wp_send_json_success($response);
}

/*
// callback to check hash gap
function check_hash_gap() {
    global $wpdb;

    // Get the necessary POST data
    $BuildingNo = $_POST['BuildingNo']; // branch id
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Get the necessary POST data
    $results = []; // Array to store the missing records

    // Query to fetch the relevant fields from zatcaDocument
    
    $query = $wpdb->prepare("SELECT z.documentNo, z.deviceNo, z1.invoiceHash 
    FROM zatcaDocument z, zatcaDocumentXML z1 
    WHERE z.BuildingNo= %d 
    AND z.dateG BETWEEN %s AND %s 
    AND z.documentNo=z1.documentNo",
    $BuildingNo, $from_date, $to_date);

    $documents = $wpdb->get_results($query);

    // Encrypt and search for each document in zatcaInfo
    foreach ($documents as $document) {
        $encrypted_documentNo = encrypt_data($document->documentNo);
        $encrypted_deviceNo = encrypt_data($document->deviceNo);
        $encrypted_invoiceHash = encrypt_data($document->invoiceHash);

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
    echo '<div class="container"><table id="example" class="table table-striped" width="100%">';
    echo '<thead><tr><th class="text-center">'. __("Document No", "zatca") .'</th><th class="text-center">'. __("Device No", "zatca") .'</th><th class="text-center">'. __("Invoice Hash", "zatca") .'</th></tr></thead><tbody class="text-center">';

    foreach ($results as $document) {
        echo '<tr>';
        echo '<td>' . $document->documentNo . '</td>';
        echo '<td>' . $document->deviceNo . '</td>';
        echo '<td>' . $document->invoiceHash . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';

    //wp_send_json_success($response);
}
*/
 

//////////////////////////

function check_hash_gap1() {  
    global $wpdb;

    // Get the necessary POST data
    $BuildingNo = $_POST['BuildingNo']; // branch id
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    // Define zatcainfo table name  
    $zacainfo_table = 'zatcainfo';  

    $missing_records = [];  

    // Step 1: Select zatcainfo1, zatcainfo2, and zatcainfo3 from zacainfo table  
    $zacainfo_query = $wpdb->prepare(  
        "SELECT zatcainfo1, zatcainfo2, zatcainfo3 FROM $zacainfo_table"  
    );  
    $zacainfo_records = $wpdb->get_results( $zacainfo_query, ARRAY_A );  

    // Step 2: Select documentNo, deviceNo, and invoiceHash from zatcaDocument & zatcaDocumentXML tables
    // within the given date range  
    $zatcaDocument_query = $wpdb->prepare("SELECT z.documentNo, z.deviceNo, z1.invoiceHash 
    FROM zatcaDocument z, zatcaDocumentXML z1 
    WHERE z.BuildingNo= %d 
    AND z.dateG BETWEEN %s AND %s 
    AND z.documentNo=z1.documentNo",
    $BuildingNo, $from_date, $to_date);

    $zatcaDocument_records = $wpdb->get_results( $zatcaDocument_query, ARRAY_A );  

    // Step 3: Create an associative array for zatcaDocument records for quick lookup  
    $zatcaDocument_map = [];  
    foreach ( $zatcaDocument_records as $document ) {  
        $key = implode('|', [$document['invoiceHash'], $document['documentNo'], $document['deviceNo']]);  
        $zatcaDocument_map[ $key ] = $document;  
    }  

    // Step 4: Check for existence of records from zacainfo in zatcaDocument  
    foreach ( $zacainfo_records as $info ) {
        
        $key = implode('|', [decrypt_data($info['zatcainfo1']), decrypt_data($info['zatcainfo2']), decrypt_data($info['zatcainfo3'])]);  
        
        // Check if the composite key exists in zatcaDocument  
        if ( ! isset( $zatcaDocument_map[ $key ] ) ) {  
            // Push missing record's values to the array  
            $missing_records[] = [  
                'invoiceHash' => decrypt_data($info['zatcainfo1']),  
                'documentNo' => decrypt_data($info['zatcainfo2']),  
                'deviceNo' => decrypt_data($info['zatcainfo3'])  
            ];  
        }  
    }

    // Display the results in a grid table
    echo '<div class="container"><table id="example" class="table table-striped" width="100%">';
    echo '<thead><tr><th class="text-center">'. __("Document No", "zatca") .'</th><th class="text-center">'. __("Device No", "zatca") .'</th><th class="text-center">'. __("Invoice Hash", "zatca") .'</th></tr></thead><tbody class="text-center">';

    foreach ($missing_records as $document1) {
        echo '<tr>';
        echo '<td>' . $document1['documentNo'] . '</td>';
        echo '<td>' . $document1['deviceNo'] . '</td>';
        echo '<td>' . $document1['invoiceHash'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';

}  

/////////////////////////

// Function to encrypt data
function decrypt_data($data) {
    // Make sure to use the same encryption method for encryption and decryption
    // Decoding the previously encoded text  
    $decodedText  = base64_decode($data);

    return $decodedText ;
}
?>