<?php

/**************************MBAUOMY**************************** */


// Add tax_invoice_option checkbox to checkout page


function zatca_customer_form() {
    include_once('customers/insert.php'); // Call the function and return its output
}
add_shortcode('zatca_customer_form1', 'zatca_customer_form');



////////////////////////////////////////////////////////////////////////////
// Add zatca_customer_form1 after the Order Data section on the order page
add_action( 'woocommerce_admin_order_data_after_order_details', 'add_custom_section_after_order_data' );

function add_custom_section_after_order_data( $order ) {

    // Add taxInvoiceOption to be used in JavaScript
    $tax_invoice_option = get_post_meta( $order->get_id(), 'tax_invoice_option', true );
    woocommerce_wp_checkbox(array(  
        'id' => 'tax_invoice_option',  
        'label' => __('Tax Invoice Option'),  
        'value' => !empty($tax_invoice_option) ? 'yes' : 'no',  
    ));  
    
    echo '</div>'; 

    wp_localize_script( 'custom-script', 'custom_vars', array(
        'taxInvoiceOption' => $tax_invoice_option
    ) );

    // Add zatca_customer_form1 for the new section here
    echo '<div class="custom-section" style="padding-top:250px;width:800px;">';

    echo do_shortcode('[zatca_customer_form1]');

    echo '</div>';
}

// Save the Tax Invoice Option checkbox value  
add_action('woocommerce_process_shop_order_meta', 'save_tax_invoice_option_custom_field', 10, 1);  
function save_tax_invoice_option_custom_field($order_id){  
    $tax_invoice_option = isset($_POST['tax_invoice_option']) ? 'yes' : 'no';  
    update_post_meta($order_id, 'tax_invoice_option', $tax_invoice_option);  
}

// Enqueue custom JavaScript file to show and hide zactaCustomer
function enqueue_custom_js() {
    wp_enqueue_script( 'custom-script', plugin_dir_url( __FILE__ ) . 'js/custom-script.js', array(), false, true );
}
add_action( 'admin_enqueue_scripts', 'enqueue_custom_js' );

add_action('wp_enqueue_scripts',  'enqueue_custom_js');



///////////////////////////////////////////////Task2/////////////////////////////////////////////////

// Create admin page
function invoice_audit_admin_page() {
    // Add sub-menu pages
    add_submenu_page(
        'zatca', // Parent menu slug
        __( 'Zacta Tampering Detector', 'zatca' ), // Page title
        __( 'Zacta Tampering Detector', 'zatca' ), // Menu title
        'manage_options', // Capability required to access menu
        'invoice-audit-admin-page', // Unique menu slug
        'invoice_audit_admin_page_content', // Callback function to display page content
        'dashicons-admin-generic', // Icon URL or WordPress dashicon class
    );
}
add_action('admin_menu', 'invoice_audit_admin_page');

// Admin page content
function invoice_audit_admin_page_content() {
    echo '<div class="wrap container">';
    echo '<h2 class="text-center">'. __( 'Zacta Tampering Detector', 'zatca' ) .'</h2>';
    // Add your admin page content here
    echo do_shortcode('[invoice_audit_form]');
    echo '</div>';
}


// Shortcode callback function to check gap form
function invoice_audit_form_shortcode()
{
    ob_start();
    require_once(plugin_dir_path(__FILE__) . 'Zacta_Tampering_Detector/invoice_audit_form.php');
    
    // Database Operations Here>> check_gap.php
    require_once(plugin_dir_path(__FILE__) . 'Zacta_Tampering_Detector/check_gap.php');
    return ob_get_clean();
}
add_shortcode('invoice_audit_form', 'invoice_audit_form_shortcode');



//////////////////////////Task 3/////////////////////////////////////////

// Action of Send Reporting request to zatca:
add_action('wp_ajax_zatca_report', 'send_request_to_zatca_report');

// Action of Reissue request to zatca:
add_action('wp_ajax_zatca_reissue', 'send_reissue_request_to_zatca');

// Action of Return request to zatca:
add_action('wp_ajax_zatca_return', 'send_return_request_to_zatca');

// Function to insert new encrypted row as base64 to zatcainfo
function insert_encrypted_row($invoiceHash, $documentNo, $deviceNo)
{
    global $wpdb;
    $table_name = 'zatcainfo';

    // encode invoiceHash and documentNo and deviceNo to Base64
    $invoiceHash = base64_encode($invoiceHash);
    $documentNo = base64_encode($documentNo);
    $deviceNo = base64_encode($deviceNo);

    // insert to zatcainfo the encrypted data
    $wpdb->insert(
        $table_name,
        array(
            'zatcaInfo1' => $invoiceHash,
            'zatcaInfo2' => $documentNo,
            'zatcaInfo3' => $deviceNo
        ), array('%s', '%s', '%s'));
}

// Function to update Seller - Buyer Data Before Send:
    function update_zatca1($doc_no){

        global $wpdb;
    
        $documents = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcadocument WHERE documentNo = $doc_no") );
    
            
    
        $zatca_TaxExemptionReason = '';
        $nominalInvoice = '';
        $exportsInvoice = '';
        $summaryInvoice = '';
        $taxSchemeId = '';
    
        $order_Id = 0;
    
        // Define zatcadocument fields to update:
        $update_seller_sellerName='';
        $update_seller_sellerAdditionalIdType='';
        $update_seller_sellerAdditionalIdNumber='';
        $update_seller_sellerVatNumber='';
        $update_seller_street_Arb='';
        $update_seller_POBoxAdditionalNum='';
        $update_seller_apartmentNum='';
        $update_seller_city_Arb='';
        $update_seller_countrySubdivision_Arb='';
        $update_seller_POBox='';
        $update_seller_district_Arb='';
        $update_buyer_aName='';
        $update_buyer_street_Arb='';
        $update_buyer_POBoxAdditionalNum='';
        $update_buyer_apartmentNum='';
        $update_buyer_city_Arb='';
        $update_buyer_countrySubdivision_Arb='';
        $update_buyer_POBox='';
        $update_buyer_district_Arb='';
        $update_buyer_buyerAdditionalIdNumber='';
        $update_buyer_buyerVatNumber='';
    
    
    
    
        // Get Data From zatcadocument:
        foreach($documents as $doc){
    
            $order_Id = $doc->invoiceNo;

            $billTypeNo = $doc->billTypeNo;
            $reason = $doc->reason;
            $zatcaRejectedInvoiceNo = $doc->zatcaRejectedInvoiceNo;

            $invoiceType = "TAX_INVOICE";
            $invoiceTypeCode = "Simplified";
            $id =  $doc->documentNo;
            $icvIncrementalValue = (int)$doc->documentNo;
            $referenceId = $doc->UUID;
            $issueDate = date("Y-m-d", strtotime($doc->dateG));
            $issueTime = date("H:i:s", strtotime($doc->dateG));
            $previousHash = "NWZlY2ViNjZmZmM4NmYzOGQ5NTI3ODZjNmQ2OTZjNzljMmRiYzIzOWRkNGU5MWI0NjcyOWQ3M2EyN2ZiNTdlOQ==";
            
            // Validation for zatcaInvoiceTransactionCode If 0 = true - if Null = False:
            $nominalInvoice = (isset($doc->zatcaInvoiceTransactionCode_isNominal) && $doc->zatcaInvoiceTransactionCode_isNominal==0) ? true : false;
            $exportsInvoice = (isset($doc->zatcaInvoiceTransactionCode_isExports) && $doc->zatcaInvoiceTransactionCode_isExports==0) ? true : false;
            $summaryInvoice = (isset($doc->zatcaInvoiceTransactionCode_isSummary) && $doc->zatcaInvoiceTransactionCode_isSummary==0) ? true : false;
            
        }
    
        
        // Update Seller Data From zatcacompany:
        $seller_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcacompany"));
    
        
        foreach($seller_update as $seller){
            
            $company_VATCategoryCode =$seller->VATCategoryCode;
    
            $company_VATCategoryCodeSubTypeNo=$seller->VATCategoryCodeSubTypeNo;
    
            // Get code info from zatcabusinessidtype Table:
        
            $seller_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype     WHERE codeNumber = $seller->secondBusinessIDType") );
            
            $taxSchemeId = $wpdb->get_var($wpdb->prepare("SELECT codeName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $seller->VATCategoryCode"));
    
    
            $sellerName = $seller->aName;
            $sellerAdditionalIdType = $seller_codeInfo;
            $sellerAdditionalIdNumber = $seller->secondBusinessID;
            $sellerVatNumber = $seller->VATID;
            $sellerAddress = [
                "streetName" => $seller->street_Arb,
                "additionalNo" => $seller->POBoxAdditionalNum,
                "buildingNumber" => $seller->apartmentNum,
                "city" => $seller->city_Arb,
                "state" => $seller->countrySubdivision_Arb,
                "zipCode" => $seller->POBox,
                "district" => $seller->district_Arb,
                "country" => "SA"
            ];
    
            // Fields Of Update zatca document [ seller ]:
            $update_seller_sellerName=$sellerName;
            $update_seller_sellerAdditionalIdType=$sellerAdditionalIdType;
            $update_seller_sellerAdditionalIdNumber=$sellerAdditionalIdNumber;
            $update_seller_sellerVatNumber=$sellerVatNumber;
            $update_seller_street_Arb=$seller->street_Arb;
            $update_seller_POBoxAdditionalNum=$seller->POBoxAdditionalNum;
            $update_seller_apartmentNum=$seller->apartmentNum;
            $update_seller_city_Arb=$seller->city_Arb;
            $update_seller_countrySubdivision_Arb=$seller->countrySubdivision_Arb;
            $update_seller_POBox=$seller->POBox;
            $update_seller_district_Arb=$seller->district_Arb;
    
        }
    
        
        // Get Customer Id from Order Table:
        $table_orders = $wpdb->prefix . 'wc_orders';
    
        $customer_Id = $wpdb->get_var( $wpdb->prepare( "SELECT customer_id FROM $table_orders WHERE id = $order_Id") );
    
    
        // Update Buyer Data From zatcacustomer:
        $buyer_update = $wpdb->get_results($wpdb->prepare("SELECT * FROM zatcacustomer WHERE clientVendorNo = $customer_Id"));
        
    
        foreach($buyer_update as $buyer){
    
            $buyer_codeInfo = $wpdb->get_var( $wpdb->prepare( "SELECT codeInfo FROM zatcabusinessidtype WHERE codeNumber = $buyer->secondBusinessIDType") );
    
            // "groupVatNumber"=> "NONE"
    
            $buyerName = $buyer->aName;
            $buyerAddress = [
                "streetName" => $buyer->street_Arb,
                "additionalNo" => $buyer->POBoxAdditionalNum,
                "buildingNumber" => $buyer->apartmentNum,
                "city" => $buyer->city_Arb,
                "state" => $buyer->countrySubdivision_Arb,
                "zipCode" => $buyer->POBox,
                "district" => $buyer->district_Arb,
                "country" => "SA"
            ];
            $buyerAdditionalIdType = $buyer_codeInfo;
            $buyerAdditionalIdNumber = $buyer->secondBusinessID;
            $buyerVatNumber = $buyer->VATID;
            
    
            // fields to update zatca document [ buyer ]:
            $update_buyer_aName=$buyerName;
            $update_buyer_street_Arb=$buyer->street_Arb;
            $update_buyer_POBoxAdditionalNum=$buyer->POBoxAdditionalNum;
            $update_buyer_apartmentNum=$buyer->apartmentNum;
            $update_buyer_city_Arb=$buyer->city_Arb;
            $update_buyer_countrySubdivision_Arb=$buyer->countrySubdivision_Arb;
            $update_buyer_POBox=$buyer->POBox;
            $update_buyer_district_Arb=$buyer->district_Arb;
            $update_buyer_buyerAdditionalIdNumber=$buyerAdditionalIdNumber;
            $update_buyer_buyerVatNumber=$buyerVatNumber ;
        }
    
    
        // Loop On zatcadocumentunit to get data:
        $documents_unit = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcadocumentunit WHERE documentNo = $doc_no") );
    
        $totalAmountWithoutVat = 0;
        $totalLineNetAmount = 0;
        $totalVatAmount = 0;
        $totalAmountWithVat = 0;
        $totalDiscountAmount = 0;
        $taxPercent = 0;
        
        $lineItems =[];
        foreach($documents_unit as $unit){
    
            $lineItems[] = [
                
                    "id" => $unit->itemNo,
                    "description" => $unit->eName . ' - ' . $unit->aName, // Must be aName Only [ but didint insert aName],
                    "linePrice" => [
                        "currencyCode" => "SAR",
                        "amount" => (int)number_format((float)$unit->price, 2, '.', '')  
                    ],
                    "lineQuantity" => $unit->quantity,
                    "lineNetAmount" => [
                        "currencyCode" => "SAR",
                        "amount" =>  (int)number_format((float)$unit->netAmount, 2, '.', '')  
                    ],
                    "lineDiscountAmount" => [
                        "currencyCode" => "SAR",
                        "amount" => (int)number_format((float)$unit->discount, 2, '.', '') 
                    ],
                    "lineVatRate" => number_format((float)$unit->vatRate, 2, '.', '') , //$unit->vatRate,
                    "lineVatAmount" => [
                        "currencyCode" => "SAR",
                        "amount" => number_format((float)$unit->vatAmount, 2, '.', '') 
                    ],
                    "lineAmountWithVat" => [
                        "currencyCode" => "SAR",
                        "amount" => number_format((float)$unit->amountWithVAT, 2, '.', '') 
                    ],
                    "taxScheme" => "VAT",
                    "taxSchemeId" => $taxSchemeId
                
            ];
    
            $netAmount = (float)$unit->netAmount;
            $vatAmount = (float)$unit->vatAmount;
            $amountWithVAT = (float)$unit->amountWithVAT;
            $discount = (float)$unit->discount;
        
            $totalAmountWithoutVat += $netAmount;
            $totalLineNetAmount += $netAmount;
            $totalVatAmount += $vatAmount;
            $totalAmountWithVat += $amountWithVAT;
            $totalDiscountAmount += $discount;
    
        }
    
        $totalAmountWithoutVat = number_format($totalAmountWithoutVat, 2, '.', '');
        $totalLineNetAmount = number_format($totalLineNetAmount, 2, '.', '');
        $totalVatAmount = number_format($totalVatAmount, 2, '.', '');
        $totalAmountWithVat = number_format($totalAmountWithVat, 2, '.', '');
        $totalDiscountAmount = number_format($totalDiscountAmount, 2, '.', '');
        $taxPercent = number_format((float)$unit->vatRate, 2, '.', ''); //$unit->vatRate;
    
    
        $totalAmountWithoutVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithoutVat];
        $totalLineNetAmount = ["currencyCode" => "SAR", "amount" => $totalLineNetAmount];
        $totalVatAmount = ["currencyCode" => "SAR", "amount" => $totalVatAmount];
        $totalAmountWithVat = ["currencyCode" => "SAR", "amount" => $totalAmountWithVat];
        $totalDiscountAmount = ["currencyCode" => "SAR", "amount" => $totalDiscountAmount];
        $taxCategory = "S";
        $taxPercent = "15.0";
        $supplyDate = "2024-04-29";
        $lastestSupplyDate = "2024-04-29";
        $invoiceCurrencyCode = "SAR";
        $taxCurrencyCode = "SAR";
        $prePaidAmount = ["currencyCode" => "SAR", "amount" => 0];
        $invoiceTypeTransactionCode = [
            "thirdPartyInvoice" => false,
            "nominalInvoice" => $nominalInvoice,
            "exportsInvoice" => $exportsInvoice,
            "summaryInvoice" => $summaryInvoice,
            "selfBilledInvoice" => false
        ];
    
    
    
    
    
        // update zatca dpcument:
        $update_data_document = array(
    
            'seller_aName' =>                   $update_seller_sellerName,
            'seller_secondBusinessIDType' =>    $update_seller_sellerAdditionalIdType,
            'seller_secondBusinessID' =>        $update_seller_sellerAdditionalIdNumber,
            'VATCategoryCodeNo' =>              $update_seller_sellerVatNumber,
            'seller_street_Arb' =>              $update_seller_street_Arb,
            'seller_POBoxAdditionalNum' =>      $update_seller_POBoxAdditionalNum,
            'seller_apartmentNum' =>            $update_seller_apartmentNum,
            'seller_city_Arb' =>                $update_seller_city_Arb,
            'seller_countrySubdivision_Arb' =>  $update_seller_countrySubdivision_Arb,
            'seller_POBox' =>                   $update_seller_POBox,
            'seller_district_Arb' =>            $update_seller_district_Arb,
            'buyer_aName' =>                    $update_buyer_aName,
            'buyer_street_Arb' =>               $update_buyer_street_Arb,
            'buyer_POBoxAdditionalNum' =>       $update_buyer_POBoxAdditionalNum,
            'buyer_apartmentNum' =>             $update_buyer_apartmentNum,
            'buyer_city_Arb' =>                 $update_buyer_city_Arb,
            'buyer_countrySubdivision_Arb' =>   $update_buyer_countrySubdivision_Arb,
            'buyer_POBox' =>                    $update_buyer_POBox,
            'buyer_district_Arb' =>             $update_buyer_district_Arb,
            'buyer_secondBusinessID' =>         $update_buyer_buyerAdditionalIdNumber,
            'buyer_VAT' =>                      $update_buyer_buyerVatNumber
        );
    
        $where = array('documentNo' => $doc_no);
    
        $update_result = $wpdb->update('zatcadocument', $update_data_document, $where);
    
        if($update_result === false) {
            // There was an error inserting data:
            error_log('Update error: ' . $wpdb->last_error);
            echo "Error Update data: " . $wpdb->last_error;
        }
    
    
        /* Validation On Vat Id - 
        if “zatcaInvoiceTransactionCode_isExport” then VAT ID for the client must be empty:
        */
        if($exportsInvoice == true){
    
            $buyerVatNumber = 0;
    
        }
    
        // original document number if returned bill
        $originalDoc =  "";
        // reason of return
        $returnReason = "";
        // check if billTypeNo is 23
        if($billTypeNo == 23)
        {
            $originalDoc =  $zatcaRejectedInvoiceNo;
            $returnReason = $reason;
        }
        // check if billTypeNo is 33
        else if($billTypeNo == 33)
        {
            $originalDoc =  "";
            $returnReason = "";
        }

        // Build the array Of Request:
        $data = [
            "invoiceType" => $invoiceType,
            "invoiceTypeCode" => $invoiceTypeCode,
            "id" => $id,
            "issueDate" => $issueDate,
            "issueTime" => $issueTime,
            "icvIncrementalValue" => $icvIncrementalValue,
            "previousHash" => $previousHash,
            "seller" => [
                "name" => $sellerName,
                "additionalIdType" => "OTH",//$sellerAdditionalIdType,
                "additionalIdNumber" => $sellerAdditionalIdNumber,
                "vatNumber" => $sellerVatNumber,
                "groupVatNumber" => "",
                "address" => $sellerAddress
            ],
            "buyer" => [
                "name" => $buyerName,
                "address" => $buyerAddress,
                "additionalIdType" =>"OTH", 
                "additionalIdNumber" => $buyerAdditionalIdNumber,
                "vatNumber" => $buyerVatNumber,
                "groupVatNumber" => ""
            ],
            "lineItems" => $lineItems,
            "totalLineNetAmount" => $totalLineNetAmount,
            "totalAmountWithoutVat" => $totalAmountWithoutVat,
            "totalVatAmount" => $totalVatAmount,
            "totalAmountWithVat" => $totalAmountWithVat,
            "totalDiscountAmount" => ["currencyCode" => "SAR", "amount" => 0],
            "taxCategory" => $taxCategory,
            "taxPercent" => $taxPercent,
            "supplyDate" => $supplyDate,
            "lastestSupplyDate" => $lastestSupplyDate,
            "invoiceCurrencyCode" => $invoiceCurrencyCode,
            "taxCurrencyCode" => $taxCurrencyCode,
            "note" => [
                "reason" => $returnReason,
                "invoiceNo" => $originalDoc
            ],
            "taxExemptionReasonCode" => "",
            "taxExemptionReason" => "",
            "invoiceNote" => "",
            "prePaidAmount" => $prePaidAmount,
            "invoiceTypeTransactionCode" => $invoiceTypeTransactionCode
        ];
    
       
        // Encode the array to JSON
        $jsonData = json_encode($data);
    
        return $jsonData;
    }

// Send Request to zatca - Clear Function:
function send_request_to_zatca_report(){

    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    $data = update_zatca1($doc_no);

    $requestArray = json_decode($data, true);

    $msg = '';

    // Validation Fields:
    $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
    $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
    
    $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
    $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

    // validation on seller_additionalIdNumber & buyer_additionalNo:
    if($seller_additionalIdNumber_validation == false){

        $msg = 'You Muse Insert Seller additional Id Number in zatca Company';
        // $msg = var_dump($requestArray);
        
      
    }elseif($buyer_additionalNo_validation == false){ // Validation on additionalNo - customer [ buyer ]:
        
        $msg = 'You Muse Insert Buyer additional Number in zatca customer';

    }else{

   
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Report?deviceID=faf911aa-ad52-498c-8d85-e8ed4ee26f83&skipPortalValidation=false',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDMxMzY2ODYsIlVzZXJJZCI6MzYsIlV1aWQiOiIzMTRmNDRlMC1mNGI4LTRiY2MtYTgwOS03MTgwYTBiMjE1YjAiLCJSZWFkIjp0cnVlLCJXcml0ZSI6dHJ1ZSwiV2hpdGVMaXN0SXAiOm51bGwsIklzc3VlZEF0IjoiMjAyNC0wNS0yNlQxNToxODowNi41NjQ0NTE5KzAwOjAwIn0.najUbWm0ME1aRJQrDrUINr2ztM4zYkcuhzsyfERFOAI'
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            $errorCode = curl_errno($curl);
            echo "cURL Error: $error (Error Code: $errorCode)";
        }
        
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       

        $http_status_msg = "HTTP Status Code: " . $http_status . "\n";

        curl_close($curl);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($curl);
        } else {
            // echo 'HTTP status code: ' . $http_status;
            // echo 'Response: ' . $response;
        }

        $responseArray = json_decode($response, true);

        $statusCode = $responseArray['zatcaStatusCode'];
        $isZatcaValid = $responseArray['isValidationFromZatca'];
        $reportingStatus = $responseArray['reportingStatus'];
        $validationResults = $responseArray['validationResults'];
        $hashed = $responseArray['hash'];
        $qrCode = $responseArray['generatedQR'];
        $response_date = date('Y-m-d H:i:s');
        $warningMessage='';
        $clearedInvoice = $responseArray['clearedInvoice'];
        $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];

        // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
        $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                FROM zatcadocument 
                                                                ORDER BY zatcaResponseDate DESC 
                                                                LIMIT 1"));
        
        if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
            foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                if (isset($Message['message'])) {
                    $warningMessage = $Message['message'];
                }else{

                    echo 'WRONG';
                }
            }
        }

        
        // Check If response Valid:
        if($reportingStatus == 'REPORTED'){

            if($statusCode == '200'){

                
                //  update zatca document xml fields with response Data:
                $zatcadocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 0
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcadocumentxml_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcadocumentxml_update_response_result === 0) {
                    
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                
                }  
    
                // update zatca document fields with response Data:
                $zatcadocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 1,
                    "previousInvoiceHash" => $hashed
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcadocument_update_response_result === false) {
                    // Handle error
                    $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                }elseif ($zatcadocument_update_response_result === 0) {
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // update zatca device fields with last document submitted:
                $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $doc_no") );

                $zatcadevice_update_response_data = [
                    "lastHash" => $hashed,
                    "lastDocumentNo" => $doc_no,
                    "lastDocumentDateTime" => $hashed
                ];
                $where1 = array('deviceNo' => $device_no);

                $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);

                // Check for errors
                if ($zatcadevice_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                
                }elseif ($zatcadevice_update_response_result === 0) {
                   
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                insert_encrypted_row($hashed, $doc_no, $device_no);

                $msg = 'Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
            
            }elseif($statusCode == '202'){

                 // update zatca document xml fields with response Data:
                $zatcadocumentxml_update_response_data = [
    
                    "previousInvoiceHash" => $previousInvoiceHash,
                    "invoiceHash" => $hashed,
                    "qrCode" => $qrCode,
                    "APIRequest" => $data,
                    "APIResponse" => $response,
                    "typeClearanceOrReporting" => 1
                ];
    
                $where = array('documentNo' => $doc_no);
    
                $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
    
                // Check for errors
                if ($zatcadocumentxml_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                
                }elseif ($zatcadocumentxml_update_response_result === 0) {
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }   
    
                // update zatca document fields with response Data:
                $zatcadocument_update_response_data = [
    
                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 2,
                    "previousInvoiceHash" => $hashed,
                    "zatcaErrorResponse" => $warningMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                
                // Check for errors
                if ($zatcadocument_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcadocument_update_response_result === 0) {
                   
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // update zatca device fields with last document submitted:
                $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $doc_no") );

                $zatcadevice_update_response_data = [
                    "lastHash" => $hashed,
                    "lastDocumentNo" => $doc_no,
                    "lastDocumentDateTime" => $hashed
                ];
                $where1 = array('deviceNo' => $device_no);

                $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);

                // Check for errors
                if ($zatcadevice_update_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                
                }elseif ($zatcadevice_update_response_result === 0) {
                   
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                insert_encrypted_row($hashed, $doc_no, $device_no);
                $msg = 'Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
            }
          
        }else{

            if($http_status == '400'){

                // update zatca document fields with response Data:
                $zatcadocument_error_response_data = [

                    "zatcaResponseDate" => $response_date,
                    "zatcaSuccessResponse" => 3,
                    "zatcaErrorResponse" => $errorMessage
                ];
                $where = array('documentNo' => $doc_no);
    
                $zatcadocument_error_response_result = $wpdb->update('zatcadocument', $zatcadocument_error_response_data, $where);
                
                // Check for errors
                if ($zatcadocument_error_response_result === false) {
                    
                    // Handle error
                    $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                
                }elseif ($zatcadocument_error_response_result === 0) {
                    
                    // No rows affected
                    $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                }

                // check if have error message or not:
                if(is_array($validationResults)){

                    $msg = $http_status_msg . ' Error: ' . $errorMessage;

                }else{

                    $msg = $http_status_msg . ' Error: ' . $validationResults;
                }

                
            
            }else{

                $msg = $response;
            }

        }

        // Log the send to zatca:
        $user_login = wp_get_current_user()->user_login;
        $user_id = wp_get_current_user()->ID;
        log_send_to_zatca($user_login, $user_id);

        $send_response = [

            'msg' => $msg,
            'validationResults' => $validationResults,
            'responseArray' => $responseArray,
            'data' => $data

        ];

        wp_send_json($send_response);
      
        
    }
        
    die();
}


//function to insert new copy of woocommerce to database [Reissue]
function insert_woocommerce_copy($docNo){
    global $wpdb;

    // Get the last order ID in the table  
    $last_order_id = $wpdb->get_var($wpdb->prepare("SELECT MAX(id) FROM wp_wc_orders"));

    // get invoiceNo from zatcaDocument table that's mean original_order_id also
    $original_order_id = $wpdb->get_var($wpdb->prepare("SELECT invoiceNo FROM zatcadocument WHERE documentNo = $docNo"));

    $new_order_id = $last_order_id + 1;

    $post_type = 'shop_order_placehold';

    $order_status = 'wc-processing';

    ////////////////////////////////////////////////////////////////////////
    // Insert new order data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO {$wpdb->prefix}wc_orders  
            SELECT $new_order_id, '$order_status' , currency, type, tax_amount, total_amount, customer_id, billing_email, date_created_gmt, date_updated_gmt, parent_order_id, payment_method, payment_method_title, transaction_id, ip_address, user_agent, customer_note  
            FROM {$wpdb->prefix}wc_orders  
            WHERE id = %d;", $original_order_id)
    );

    // Copy order meta  
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO {$wpdb->prefix}wc_order_meta (order_id, meta_key, meta_value)  
            SELECT %d, meta_key, meta_value  
            FROM {$wpdb->prefix}wc_order_meta  
            WHERE order_id = %d;", $new_order_id, $original_order_id)  
    );

    /////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////
    //wp_woocommerce_order_items table
    // Query to get the original order items based on the order ID  
    $order_items_query = $wpdb->prepare("SELECT * FROM wp_woocommerce_order_items WHERE order_id = %d", $original_order_id);  
    $order_items = $wpdb->get_results($order_items_query, ARRAY_A);

    // Insert copies of the order items with new order IDs  
    if ($order_items)
    {
        $new_woocommerce_order_id = $new_order_id; // The new order ID 

        foreach ($order_items as $order_item) 
        {
            $new_order_item_data = [
                'order_id' => $new_woocommerce_order_id,
                'order_item_name' => $order_item['order_item_name'],
                'order_item_type' => $order_item['order_item_type']
                ];
                $wpdb->insert('wp_woocommerce_order_items', $new_order_item_data);
        }
    }

    // wp_woocommerce_order_itemmeta table
    // Query to get the original order item meta based on the order item ID  

    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO wp_woocommerce_order_itemmeta (order_item_id, meta_key, meta_value)  
            SELECT new_item.order_item_id, meta.meta_key, meta.meta_value  
            FROM wp_woocommerce_order_items new_item
            JOIN wp_woocommerce_order_items old_item ON old_item.order_id = '$original_order_id'  
            JOIN wp_woocommerce_order_itemmeta meta ON old_item.order_item_id = meta.order_item_id 
            WHERE new_item.order_id = %d;", $new_woocommerce_order_id));
    ///////////////////////////////////////////////////////////////////////////////

    // function to insert new copy of date to zatcadocument table
    insert_zatcaDocument_copy($docNo, $new_order_id);

}

//function to insert new copy of zatcaDocument to database [Reissue]
function insert_zatcaDocument_copy($docNo, $newInvoiceNo){
    global $wpdb;

    // Table Name:
    $table_name_device = 'zatcadevice';

    // Get the current Active device
    $device__No = $wpdb->get_var($wpdb->prepare(
        "SELECT deviceNo
        FROM $table_name_device
        WHERE deviceStatus = 0")
        );
    $query = $wpdb->prepare("SELECT IFNULL(MAX(documentNo), 0) FROM zatcadocument WHERE deviceNo = $device__No");
    $doc__no = $wpdb->get_var($query);
    $last_document_id = $doc__no + 1;

    $new_document_id = $last_document_id + 1;

    $uuid = wp_generate_uuid4();

    // Get Device No from ZatcaDevice:
        // Prepare the query with a condition on CsID_ExpiryDate not expire and is active:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW() AND deviceStatus = 0") );
        foreach($results as $device)
        {
            if($wpdb->num_rows > 0)
            { // If Date Valid:
                $deviceNo = $device->deviceNo;
            }
            else
            { // If No Date Valid
                // $msg = "You Must Insert Valid CsID_ExpiryDate";
            }
        }

        $device_No = $deviceNo;


    // Insert new order data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO zatcadocument  
            SELECT vendorId, $new_document_id, $device_No , $newInvoiceNo, buildingNo, billTypeNo, dateG, deliveryDate,
            gaztLatestDeliveryDate, zatcaInvoiceType, amountPayed01, amountPayed02, amountPayed03, amountPayed04,
            amountPayed05, amountCalculatedPayed, returnReasonType, subTotal, subTotalDiscount, taxRate1_Percentage,
            taxRate1_Total, subNetTotal, subNetTotalPlusTax, amountLeft, isAllItemsReturned, isZatcaRetuerned, reason,
            previousDocumentNo, previousInvoiceHash, seller_secondBusinessIDType, seller_secondBusinessID, buyer_secondBusinessIDType,
            buyer_secondBusinessID, VATCategoryCodeNo, VATCategoryCodeSubTypeNo, zatca_TaxExemptionReason, zatcaInvoiceTransactionCode_isNominal,
            zatcaInvoiceTransactionCode_isExports, zatcaInvoiceTransactionCode_isSummary, zatcaInvoiceTransactionCode_is3rdParty,
            zatcaInvoiceTransactionCode_isSelfBilled, '$uuid', seller_VAT, seller_aName, seller_eName, seller_apartmentNum,
            seller_countrySubdivision_Arb, seller_countrySubdivision_Eng, seller_street_Arb, seller_street_Eng, seller_district_Arb,
            seller_district_Eng, seller_city_Arb, seller_city_Eng, seller_country_Arb, seller_country_Eng, seller_country_No,
            seller_PostalCode, seller_POBox, seller_POBoxAdditionalNum, buyer_VAT, buyer_aName, buyer_eName, buyer_apartmentNum,
            buyer_countrySubdivision_Arb, buyer_countrySubdivision_Eng, buyer_street_Arb, buyer_street_Eng,
            buyer_district_Arb, buyer_district_Eng, buyer_city_Arb, buyer_city_Eng, buyer_country_Arb, buyer_country_Eng,
            buyer_country_No, buyer_PostalCode, buyer_POBox, buyer_POBoxAdditionalNum, 0, NULL, NULL, 1, zatcaRejectedBuildingNo,
            %d, zatcaAcceptedReissueBuildingNo, zatcaAcceptedReissueInvoiceNo, isZatcaReissued, row_timestamp
            FROM zatcadocument  
            WHERE documentNo = %d", $docNo, $docNo)
    );

    // function of insert new copy to zatcadocumentunit table
    insert_zatcaDocumentUnit_copy($newInvoiceNo, $device_No, $new_document_id);

    //insert new row into zatcadocumentxml
    $wpdb->query($wpdb->prepare("INSERT INTO zatcadocumentxml (documentNo,deviceNo) VALUES (%d, %d)", $new_document_id, $device_No));
}

//function to insert new copy of zatcaDocumentUnits to database [Reissue]
function insert_zatcaDocumentUnit_copy($neworderId, $deviceNo, $newDocNo)
{
    global $wpdb;

    $device_No = $deviceNo;
    $last_doc = $newDocNo;

    // ###########################################
    // Insert Copy of Data to zatcadocumentunit:##
    // ###########################################

    // Prefix Of woo-order-item
    $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';

    // get item id for item [ line_item ]:
    $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $neworderId AND order_item_type = 'line_item'"));

    // get item id for tax [ tax ]:
    $doc_unit_item_id_rate_percentage = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $neworderId AND order_item_type = 'tax'"));

    // get item id for discount [ tax ]:
    $doc_unit_item_id_coupon = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $neworderId AND order_item_type = 'coupon'"));


    // Funtion to handle order discount:
    function get_qty_percentage_for_item($orderId){
    
        global $wpdb;
        // Prefix Of woo-order-item
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
    
        
        // define items number & Number of Items & Item Qty & Total Qty Of Order:
        
        $total_order_qty = 0;
    
        $array_items = [];
        
        $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
        
        foreach($doc_unit_item_id as $itemId){
    
            $item_qty = wc_get_order_item_meta( $itemId->order_item_id , '_qty', true );
    
            $order_discount_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
    
            $order_discount = wc_get_order_item_meta($order_discount_id, 'discount_amount', true);
            
            $array_items[$itemId->order_item_id] = $item_qty;
    
            $total_order_qty +=  + $item_qty;
    
        }
    
        $array_items['order_discount'] = $order_discount;
        $array_items['total_order_qty'] = $total_order_qty;
    
    
        //define percentage of each item:
    
        $updated_total_qty = [];
    
        foreach ($array_items as $key => $value) {
            if (is_numeric($key)) {  // Check for integer keys
                $updated_total_qty[$key] = $value / $array_items["total_order_qty"] * $array_items["order_discount"];
            }
        }
    
        // Now $updated_total_qty will contain the updated quantities for items with numeric keys
        return $updated_total_qty;
        
    }

    //
    foreach($doc_unit_item_id as $item){
    
                        
        // Item Qty:
        $doc_unit_item_qty = wc_get_order_item_meta( $item->order_item_id , '_qty', true );
        
        // Product_id:
        $doc_unit_product_id =wc_get_order_item_meta($item->order_item_id, '_product_id', true);
        
        // Tax Percentage:
        $doc_unit_vatRate =wc_get_order_item_meta($doc_unit_item_id_rate_percentage, 'rate_percent', true);

        // Item name:
        $doc_unit_sku = get_post_meta($doc_unit_product_id, '_sku', true);
        
        // Item Price:
        $doc_unit_price = get_post_meta($doc_unit_product_id, '_price', true);
        $final_price = number_format((float)$doc_unit_price, 6, '.', '');

        // Discount:
        $doc_unit_discount = wc_get_order_item_meta($doc_unit_item_id_coupon, 'discount_amount', true);

        // Subttotal [ price * quantity ]:
        $doc_unit_subtotal = $doc_unit_price * $doc_unit_item_qty;


        // Get the Function of define discount by line:
        $array_of_discounts = get_qty_percentage_for_item($neworderId);

        // Loop to get Each Item Discount:
        foreach($array_of_discounts as $key => $value)
        {

            if($key == $item->order_item_id)
            {

                $final_item_discount= $array_of_discounts[$key];

                // netAmount [ ((price * quantity)-discount) ]:
                $doc_unit_netAmount = $doc_unit_subtotal - $final_item_discount;
                $final_netAmount = number_format((float)$doc_unit_netAmount, 2, '.', '');

                // vatAmount [ netAmount*vatRate ]:
                $doc_unit_vatAmount = ($doc_unit_netAmount * $doc_unit_vatRate) / 100;
                $final_vatAmount = number_format((float)$doc_unit_vatAmount, 2, '.', '');

                // amountWithVat [ netAmount+vatAmount ]:
                $doc_unit_amountWithVat = $doc_unit_netAmount + $doc_unit_vatAmount;
                $final_amountWithVat = number_format((float)$doc_unit_amountWithVat, 2, '.', '');
            

                // Insert Data To zatcadocumentunit:
                $insert_doc_unit = $wpdb->insert(
                    'zatcadocumentunit',
                    [
                        'deviceNo'      => $device_No,
                        'documentNo'    => $last_doc,
                        'itemNo'        => $item->order_item_id,
                        'eName'         => $doc_unit_sku,
                        'price'         => $final_price,
                        'quantity'      => $doc_unit_item_qty,
                        'discount'      => $final_item_discount,
                        'vatRate'       => $doc_unit_vatRate,
                        'vatAmount'     => $final_vatAmount,
                        'netAmount'     => $final_netAmount,
                        'amountWithVAT' => $final_amountWithVat
                    ]
                );
            }
        }

    }

    // function to send the new one to zatca and handle the response from zatca depend on zatcaSuccessResponse code
    send_reissue_zatca($newDocNo);

}

//function to send reissue request to zatca [Reissue]
function send_reissue_zatca($docNo)
{
    global $wpdb;
    // Get the invoice type (B2B or B2C) 
    $zatcaInvoice_type = $wpdb->get_var($wpdb->prepare("SELECT zatcaInvoiceType FROM zatcadocument WHERE documentNo =  $docNo"));

    // check invoice type to detect CLEAR or REPORT
    // if B2B
    if($zatcaInvoice_type == 1)
    {
        $data = update_zatca($docNo);

        $requestArray = json_decode($data, true);

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
        $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
        
        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($seller_additionalIdNumber_validation == false)
        {

            $msg = 'You Muse Insert Seller additional Id Number in zatca Company';
            // $msg = var_dump($requestArray);
        }
        elseif($buyer_additionalNo_validation == false)
        { // Validation on additionalNo - customer [ buyer ]:
            $msg = 'You Muse Insert Buyer additional Number in zatca customer';
        }

        else{
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Clear?deviceID=faf911aa-ad52-498c-8d85-e8ed4ee26f83&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDMxMzY2ODYsIlVzZXJJZCI6MzYsIlV1aWQiOiIzMTRmNDRlMC1mNGI4LTRiY2MtYTgwOS03MTgwYTBiMjE1YjAiLCJSZWFkIjp0cnVlLCJXcml0ZSI6dHJ1ZSwiV2hpdGVMaXN0SXAiOm51bGwsIklzc3VlZEF0IjoiMjAyNC0wNS0yNlQxNToxODowNi41NjQ0NTE5KzAwOjAwIn0.najUbWm0ME1aRJQrDrUINr2ztM4zYkcuhzsyfERFOAI'
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                echo "cURL Error: $error (Error Code: $errorCode)";
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                echo 'Curl error: ' . curl_error($curl);
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $clearanceStatus = $responseArray['clearanceStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcadocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) 
                    {
                        $warningMessage = $Message['message'];
                    }
                    else
                    {
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($clearanceStatus == 'CLEARED'){
    
                if($statusCode == '200'){
                    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////

                    //  update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    }elseif ($zatcadocument_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }

                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
    
                    $msg = 'Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////


                     // update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);

                    $msg = 'Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
                }
              
            }else{
    
                if($http_status == '400'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "isZatcaReissued" => 1
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////

                    
                    // update zatca document fields with response Data:
                    $zatcadocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_error_response_result = $wpdb->update('zatcadocument', $zatcadocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_error_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_error_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $msg = $http_status_msg . ' Error: ' . $errorMessage;
    
                    }else{
    
                        $msg = $http_status_msg . ' Error: ' . $validationResults;
                    }
    
                    
                
                }
                else if($http_status == '303')
                {
                    // update zatca document fields with response Data:
                    $zatcadocument_error_response_data = [
                        "zatcaB2B_isForced_To_B2C" => 1];
                    $where = array('documentNo' => $docNo);
                    $zatcadocument_error_response_result = $wpdb->update('zatcadocument',
                    $zatcadocument_error_response_data, $where);
                    $msg = $response;
                }
            else{
    
                    $msg = $response;
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
    
            $send_response = [
    
                'msg' => $msg,
                'validationResults' => $validationResults,
                'responseArray' => $responseArray,
                'data' => $data
    
            ];
    
            wp_send_json($send_response);
          
            
        }


        //end if
    }
    // if not B2B
    else
    {
        $data = update_zatca1($docNo);

        $requestArray = json_decode($data, true);

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
        $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
        
        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($seller_additionalIdNumber_validation == false)
        {
            $msg = 'You Muse Insert Seller additional Id Number in zatca Company';
            // $msg = var_dump($requestArray);
        }
        elseif($buyer_additionalNo_validation == false)
        { // Validation on additionalNo - customer [ buyer ]:
            $msg = 'You Muse Insert Buyer additional Number in zatca customer';
        }

        else
        {
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Report?deviceID=faf911aa-ad52-498c-8d85-e8ed4ee26f83&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDMxMzY2ODYsIlVzZXJJZCI6MzYsIlV1aWQiOiIzMTRmNDRlMC1mNGI4LTRiY2MtYTgwOS03MTgwYTBiMjE1YjAiLCJSZWFkIjp0cnVlLCJXcml0ZSI6dHJ1ZSwiV2hpdGVMaXN0SXAiOm51bGwsIklzc3VlZEF0IjoiMjAyNC0wNS0yNlQxNToxODowNi41NjQ0NTE5KzAwOjAwIn0.najUbWm0ME1aRJQrDrUINr2ztM4zYkcuhzsyfERFOAI'
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                echo "cURL Error: $error (Error Code: $errorCode)";
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
           
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                echo 'Curl error: ' . curl_error($curl);
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $reportingStatus = $responseArray['reportingStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcadocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) {
                        $warningMessage = $Message['message'];
                    }else{
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($reportingStatus == 'REPORTED'){
    
                if($statusCode == '200'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////


                    //  update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    }elseif ($zatcadocument_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);

                    $msg = 'Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "zatcaAcceptedReissueInvoiceNo" => $docNo
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////


                     // update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 1
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);

                    $msg = 'Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
                }
              
            }
            else
            {
    
                if($http_status == '400'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "isZatcaReissued" => 1
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////


                    // update zatca document fields with response Data:
                    $zatcadocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_error_response_result = $wpdb->update('zatcadocument', $zatcadocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_error_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_error_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $msg = $http_status_msg . ' Error: ' . $errorMessage;
    
                    }else{
    
                        $msg = $http_status_msg . ' Error: ' . $validationResults;
                    }
    
                    
                
                }else{
    
                    $msg = $response;
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
    
            $send_response = [
    
                'msg' => $msg,
                'validationResults' => $validationResults,
                'responseArray' => $responseArray,
                'data' => $data
    
            ];
    
            wp_send_json($send_response);
            
        }
    }
}

//function to insert new copy of data and fire the reissue button action [Reissue]
function send_reissue_request_to_zatca(){
    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];

    // update  isZatcaReissued with true in original document
    $wpdb->update('zatcadocument', array('isZatcaReissued' => '0'),array('documentNo' => $doc_no),array('%s'),array('%d'));

    // function to insert new copy to woocommerce and new copy to zatcadocument and new copy to zatcadocumentunit
    insert_woocommerce_copy($doc_no);

}

////////////////////////////////////////Return Functions//////////////////////////////////////////////

//function to insert new copy of zatcaDocument to database [Return]
function insert_zatcaDocument_returned($docNo, $invoice_no){
    global $wpdb;

    // Table Name:
    $table_name_device = 'zatcadevice';

    // Get the current Active device
    $device__No = $wpdb->get_var($wpdb->prepare(
        "SELECT deviceNo
        FROM $table_name_device
        WHERE deviceStatus = 0")
        );
    $query = $wpdb->prepare("SELECT IFNULL(MAX(documentNo), 0) FROM zatcadocument WHERE deviceNo = $device__No");
    $doc__no = $wpdb->get_var($query);
    $last_document_id = $doc__no;

    $new_document_id = $last_document_id + 1;

    $uuid = wp_generate_uuid4();

    // Get Device No from ZatcaDevice:
        // Prepare the query with a condition on CsID_ExpiryDate not expire and is active:
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name_device WHERE CsID_ExpiryDate > NOW() AND deviceStatus = 0") );
        foreach($results as $device)
        {
            if($wpdb->num_rows > 0)
            { // If Date Valid:
                $deviceNo = $device->deviceNo;
            }
            else
            { // If No Date Valid
                // $msg = "You Must Insert Valid CsID_ExpiryDate";
            }
        }

        $device_No = $deviceNo;


    // Insert new order data 
    $wpdb->query(  
        $wpdb->prepare("  
            INSERT INTO zatcadocument  
            SELECT vendorId, $new_document_id, $device_No , $invoice_no, buildingNo, 23, dateG, deliveryDate,
            gaztLatestDeliveryDate, zatcaInvoiceType, amountPayed01, amountPayed02, amountPayed03, amountPayed04,
            amountPayed05, amountCalculatedPayed, returnReasonType, subTotal, subTotalDiscount, taxRate1_Percentage,
            taxRate1_Total, subNetTotal, subNetTotalPlusTax, amountLeft, isAllItemsReturned, 0, reason,
            previousDocumentNo, previousInvoiceHash, seller_secondBusinessIDType, seller_secondBusinessID, buyer_secondBusinessIDType,
            buyer_secondBusinessID, VATCategoryCodeNo, VATCategoryCodeSubTypeNo, zatca_TaxExemptionReason, zatcaInvoiceTransactionCode_isNominal,
            zatcaInvoiceTransactionCode_isExports, zatcaInvoiceTransactionCode_isSummary, zatcaInvoiceTransactionCode_is3rdParty,
            zatcaInvoiceTransactionCode_isSelfBilled, '$uuid', seller_VAT, seller_aName, seller_eName, seller_apartmentNum,
            seller_countrySubdivision_Arb, seller_countrySubdivision_Eng, seller_street_Arb, seller_street_Eng, seller_district_Arb,
            seller_district_Eng, seller_city_Arb, seller_city_Eng, seller_country_Arb, seller_country_Eng, seller_country_No,
            seller_PostalCode, seller_POBox, seller_POBoxAdditionalNum, buyer_VAT, buyer_aName, buyer_eName, buyer_apartmentNum,
            buyer_countrySubdivision_Arb, buyer_countrySubdivision_Eng, buyer_street_Arb, buyer_street_Eng,
            buyer_district_Arb, buyer_district_Eng, buyer_city_Arb, buyer_city_Eng, buyer_country_Arb, buyer_country_Eng,
            buyer_country_No, buyer_PostalCode, buyer_POBox, buyer_POBoxAdditionalNum, 0, NULL, NULL, zatcaB2B_isForced_To_B2C, zatcaRejectedBuildingNo,
            %d, zatcaAcceptedReissueBuildingNo, zatcaAcceptedReissueInvoiceNo, isZatcaReissued, row_timestamp
            FROM zatcadocument  
            WHERE documentNo = %d", $docNo, $docNo)
    );

    // function of insert new copy to zatcadocumentunit table
    insert_zatcaDocumentUnit_returned($invoice_no, $device_No, $new_document_id);

    //insert new row into zatcadocumentxml
    $wpdb->query($wpdb->prepare("INSERT INTO zatcadocumentxml (documentNo,deviceNo) VALUES (%d, %d)", $new_document_id, $device_No));
}

//function to insert new copy of zatcaDocumentUnits to database [Return]
function insert_zatcaDocumentUnit_returned($orderId, $deviceNo, $newDocNo)
{
    global $wpdb;

    $device_No = $deviceNo;
    $last_doc = $newDocNo;

    // ###########################################
    // Insert Copy of Data to zatcadocumentunit:##
    // ###########################################

    // Prefix Of woo-order-item
    $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';

    // get item id for item [ line_item ]:
    $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));

    // get item id for tax [ tax ]:
    $doc_unit_item_id_rate_percentage = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'tax'"));

    // get item id for discount [ tax ]:
    $doc_unit_item_id_coupon = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));


    // Funtion to handle order discount:
    function get_qty_percentage_for_item($orderId){
    
        global $wpdb;
        // Prefix Of woo-order-item
        $table_orders_items = $wpdb->prefix . 'woocommerce_order_items';
    
        
        // define items number & Number of Items & Item Qty & Total Qty Of Order:
        
        $total_order_qty = 0;
    
        $array_items = [];
        
        $doc_unit_item_id = $wpdb->get_results($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'line_item'"));
        
        foreach($doc_unit_item_id as $itemId){
    
            $item_qty = wc_get_order_item_meta( $itemId->order_item_id , '_qty', true );
    
            $order_discount_id = $wpdb->get_var($wpdb->prepare("select order_item_id from $table_orders_items WHERE order_id = $orderId AND order_item_type = 'coupon'"));
    
            $order_discount = wc_get_order_item_meta($order_discount_id, 'discount_amount', true);
            
            $array_items[$itemId->order_item_id] = $item_qty;
    
            $total_order_qty +=  + $item_qty;
    
        }
    
        $array_items['order_discount'] = $order_discount;
        $array_items['total_order_qty'] = $total_order_qty;
    
    
        //define percentage of each item:
    
        $updated_total_qty = [];
    
        foreach ($array_items as $key => $value) {
            if (is_numeric($key)) {  // Check for integer keys
                $updated_total_qty[$key] = $value / $array_items["total_order_qty"] * $array_items["order_discount"];
            }
        }
    
        // Now $updated_total_qty will contain the updated quantities for items with numeric keys
        return $updated_total_qty;
        
    }

    //
    foreach($doc_unit_item_id as $item){
    
                        
        // Item Qty:
        $doc_unit_item_qty = wc_get_order_item_meta( $item->order_item_id , '_qty', true );
        
        // Product_id:
        $doc_unit_product_id =wc_get_order_item_meta($item->order_item_id, '_product_id', true);
        
        // Tax Percentage:
        $doc_unit_vatRate =wc_get_order_item_meta($doc_unit_item_id_rate_percentage, 'rate_percent', true);

        // Item name:
        $doc_unit_sku = get_post_meta($doc_unit_product_id, '_sku', true);
        
        // Item Price:
        $doc_unit_price = get_post_meta($doc_unit_product_id, '_price', true);
        $final_price = number_format((float)$doc_unit_price, 6, '.', '');

        // Discount:
        $doc_unit_discount = wc_get_order_item_meta($doc_unit_item_id_coupon, 'discount_amount', true);

        // Subttotal [ price * quantity ]:
        $doc_unit_subtotal = $doc_unit_price * $doc_unit_item_qty;


        // Get the Function of define discount by line:
        $array_of_discounts = get_qty_percentage_for_item($orderId);

        // Loop to get Each Item Discount:
        foreach($array_of_discounts as $key => $value)
        {

            if($key == $item->order_item_id)
            {

                $final_item_discount= $array_of_discounts[$key];

                // netAmount [ ((price * quantity)-discount) ]:
                $doc_unit_netAmount = $doc_unit_subtotal - $final_item_discount;
                $final_netAmount = number_format((float)$doc_unit_netAmount, 2, '.', '');

                // vatAmount [ netAmount*vatRate ]:
                $doc_unit_vatAmount = ($doc_unit_netAmount * $doc_unit_vatRate) / 100;
                $final_vatAmount = number_format((float)$doc_unit_vatAmount, 2, '.', '');

                // amountWithVat [ netAmount+vatAmount ]:
                $doc_unit_amountWithVat = $doc_unit_netAmount + $doc_unit_vatAmount;
                $final_amountWithVat = number_format((float)$doc_unit_amountWithVat, 2, '.', '');
            

                // Insert Data To zatcadocumentunit:
                $insert_doc_unit = $wpdb->insert(
                    'zatcadocumentunit',
                    [
                        'deviceNo'      => $device_No,
                        'documentNo'    => $last_doc,
                        'itemNo'        => $item->order_item_id,
                        'eName'         => $doc_unit_sku,
                        'price'         => $final_price,
                        'quantity'      => $doc_unit_item_qty,
                        'discount'      => $final_item_discount,
                        'vatRate'       => $doc_unit_vatRate,
                        'vatAmount'     => $final_vatAmount,
                        'netAmount'     => $final_netAmount,
                        'amountWithVAT' => $final_amountWithVat
                    ]
                );
            }
        }

    }

    // function to send the new one to zatca and handle the response from zatca depend on zatcaSuccessResponse code
    send_return_zatca($newDocNo);

}

//function to send reissue request to zatca [Return]
function send_return_zatca($docNo)
{
    global $wpdb;
    // Get the invoice type (B2B or B2C) 
    $zatcaInvoice_type = $wpdb->get_var($wpdb->prepare("SELECT zatcaInvoiceType FROM zatcadocument WHERE documentNo =  $docNo"));

    // check invoice type to detect CLEAR or REPORT
    // if B2B
    if($zatcaInvoice_type == 1)
    {
        $data = update_zatca($docNo);

        $requestArray = json_decode($data, true);

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
        $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
        
        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($seller_additionalIdNumber_validation == false)
        {

            $msg = 'You Muse Insert Seller additional Id Number in zatca Company';
            // $msg = var_dump($requestArray);
        }
        elseif($buyer_additionalNo_validation == false)
        { // Validation on additionalNo - customer [ buyer ]:
            $msg = 'You Muse Insert Buyer additional Number in zatca customer';
        }

        else{
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Clear?deviceID=faf911aa-ad52-498c-8d85-e8ed4ee26f83&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDMxMzY2ODYsIlVzZXJJZCI6MzYsIlV1aWQiOiIzMTRmNDRlMC1mNGI4LTRiY2MtYTgwOS03MTgwYTBiMjE1YjAiLCJSZWFkIjp0cnVlLCJXcml0ZSI6dHJ1ZSwiV2hpdGVMaXN0SXAiOm51bGwsIklzc3VlZEF0IjoiMjAyNC0wNS0yNlQxNToxODowNi41NjQ0NTE5KzAwOjAwIn0.najUbWm0ME1aRJQrDrUINr2ztM4zYkcuhzsyfERFOAI'
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                echo "cURL Error: $error (Error Code: $errorCode)";
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                echo 'Curl error: ' . curl_error($curl);
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $clearanceStatus = $responseArray['clearanceStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcadocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) 
                    {
                        $warningMessage = $Message['message'];
                    }
                    else
                    {
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($clearanceStatus == 'CLEARED'){
    
                if($statusCode == '200'){

                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));


                    //  update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    }elseif ($zatcadocument_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }

                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
    
                    $msg = 'Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));


                     // update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }

                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
    
                    $msg = 'Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
                }
              
            }
            else{
    
                if($http_status == '400'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    /*
                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "isZatcaReissued" => 1
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////
                    */

                    
                    // update zatca document fields with response Data:
                    $zatcadocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_error_response_result = $wpdb->update('zatcadocument', $zatcadocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_error_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_error_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $msg = $http_status_msg . ' Error: ' . $errorMessage;
    
                    }else{
    
                        $msg = $http_status_msg . ' Error: ' . $validationResults;
                    }
    
                    
                
                }
                else if($http_status == '303')
                {
                    // update zatca document fields with response Data:
                    $zatcadocument_error_response_data = [
                        "zatcaB2B_isForced_To_B2C" => 1];
                    $where = array('documentNo' => $docNo);
                    $zatcadocument_error_response_result = $wpdb->update('zatcadocument',
                    $zatcadocument_error_response_data, $where);
                    $msg = $response;
                }
            else{
    
                    $msg = $response;
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
    
            $send_response = [
    
                'msg' => $msg,
                'validationResults' => $validationResults,
                'responseArray' => $responseArray,
                'data' => $data
    
            ];
    
            wp_send_json($send_response);
          
            
        }


        //end if
    }
    // if not B2B
    else
    {
        $data = update_zatca1($docNo);

        $requestArray = json_decode($data, true);

        $msg = '';

        // Validation Fields:
        $seller_additionalIdNumber = $requestArray['seller']['additionalIdNumber'];
        $seller_additionalIdNumber_validation = (isset($seller_additionalIdNumber ) && $seller_additionalIdNumber !=null) ? true : false;
        
        $buyer_additionalNo = $requestArray['buyer']['address']['additionalNo'];
        $buyer_additionalNo_validation = (isset($buyer_additionalNo ) && $buyer_additionalNo !=null) ? true : false;

        // validation on seller_additionalIdNumber & buyer_additionalNo:
        if($seller_additionalIdNumber_validation == false)
        {
            $msg = 'You Muse Insert Seller additional Id Number in zatca Company';
            // $msg = var_dump($requestArray);
        }
        elseif($buyer_additionalNo_validation == false)
        { // Validation on additionalNo - customer [ buyer ]:
            $msg = 'You Muse Insert Buyer additional Number in zatca customer';
        }

        else
        {
   
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api-sandbox.cpusfatoora.com/v1/Invoice/Report?deviceID=faf911aa-ad52-498c-8d85-e8ed4ee26f83&skipPortalValidation=false',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJleHAiOjE4MDMxMzY2ODYsIlVzZXJJZCI6MzYsIlV1aWQiOiIzMTRmNDRlMC1mNGI4LTRiY2MtYTgwOS03MTgwYTBiMjE1YjAiLCJSZWFkIjp0cnVlLCJXcml0ZSI6dHJ1ZSwiV2hpdGVMaXN0SXAiOm51bGwsIklzc3VlZEF0IjoiMjAyNC0wNS0yNlQxNToxODowNi41NjQ0NTE5KzAwOjAwIn0.najUbWm0ME1aRJQrDrUINr2ztM4zYkcuhzsyfERFOAI'
                ),
            ));
    
            $response = curl_exec($curl);
    
            if ($response === false) {
                $error = curl_error($curl);
                $errorCode = curl_errno($curl);
                echo "cURL Error: $error (Error Code: $errorCode)";
            }
            
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
           
    
            $http_status_msg = "HTTP Status Code: " . $http_status . "\n";
    
            curl_close($curl);
    
            if ($response === false) {
                echo 'Curl error: ' . curl_error($curl);
            } else {
                // echo 'HTTP status code: ' . $http_status;
                // echo 'Response: ' . $response;
            }
    
            $responseArray = json_decode($response, true);
    
            $statusCode = $responseArray['zatcaStatusCode'];
            $isZatcaValid = $responseArray['isValidationFromZatca'];
            $reportingStatus = $responseArray['reportingStatus'];
            $validationResults = $responseArray['validationResults'];
            $hashed = $responseArray['hash'];
            $qrCode = $responseArray['generatedQR'];
            $response_date = date('Y-m-d H:i:s');
            $warningMessage='';
            $clearedInvoice = $responseArray['clearedInvoice'];
            $errorMessage = $responseArray['validationResults']['warningMessages'][0]['message'];
    
            // Get the previous invoice hash for the document depend on newest date in zatcaResponseDate:
            $previousInvoiceHash = $wpdb->get_var($wpdb->prepare("SELECT previousInvoiceHash 
                                                                    FROM zatcadocument 
                                                                    ORDER BY zatcaResponseDate DESC 
                                                                    LIMIT 1"));
            
            if (isset($responseArray['validationResults']['warningMessages']) && is_array($responseArray['validationResults']['warningMessages'])) {
                foreach ($responseArray['validationResults']['warningMessages'] as $Message) {
                    if (isset($Message['message'])) {
                        $warningMessage = $Message['message'];
                    }else{
                        echo 'WRONG';
                    }
                }
            }
    
            
            // Check If response Valid:
            if($reportingStatus == 'REPORTED'){
    
                if($statusCode == '200'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));


                    //  update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 0
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    
                    }  
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 1,
                        "previousInvoiceHash" => $hashed
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    }elseif ($zatcadocument_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    

                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
                    $msg = 'Zatca Status Code Is ' . $statusCode . ' .. Request Is Success' . $http_status_msg;
                
                }elseif($statusCode == '202'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));


                     // update zatca document xml fields with response Data:
                    $zatcadocumentxml_update_response_data = [
        
                        "previousInvoiceHash" => $previousInvoiceHash,
                        "invoiceHash" => $hashed,
                        "qrCode" => $qrCode,
                        "APIRequest" => $data,
                        "APIResponse" => $response,
                        "typeClearanceOrReporting" => 1
                    ];
        
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocumentxml_update_response_result = $wpdb->update('zatcadocumentxml', $zatcadocumentxml_update_response_data, $where);
        
                    // Check for errors
                    if ($zatcadocumentxml_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating on zatcadocumentxml in the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocumentxml_update_response_result === 0) {
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }   
        
                    // update zatca document fields with response Data:
                    $zatcadocument_update_response_data = [
        
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 2,
                        "previousInvoiceHash" => $hashed,
                        "zatcaErrorResponse" => $warningMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_update_response_result = $wpdb->update('zatcadocument', $zatcadocument_update_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // update zatca device fields with last document submitted:
                    $device_no = $wpdb->get_var( $wpdb->prepare( "SELECT deviceNo FROM zatcadocument WHERE documentNo = $docNo") );
    
                    $zatcadevice_update_response_data = [
                        "lastHash" => $hashed,
                        "lastDocumentNo" => $docNo,
                        "lastDocumentDateTime" => $hashed
                    ];
                    $where1 = array('deviceNo' => $device_no);
    
                    $zatcadevice_update_response_result = $wpdb->update('zatcadevice', $zatcadevice_update_response_data, $where1);
    
                    // Check for errors
                    if ($zatcadevice_update_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadevice on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadevice_update_response_result === 0) {
                       
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // insert row to zatcainfo
                    insert_encrypted_row($hashed, $docNo, $device_no);
                    
                    $msg = 'Zatca Status Code Is ' . $statusCode . '.....' . $warningMessage;
                }
              
            }
            else
            {
    
                if($http_status == '400'){
    
                    // update original  doc set zatcaAcceptedReissueInvoiceNo = current docNo
                    $originalDocNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo FROM zatcadocument WHERE documentNo =  $docNo"));

                    /*
                    // update zatca document fields with response Data:
                    $zatcadocument_original_update_data = [
                        "isZatcaReissued" => 1
                    ];
                    $whereOriginal = array('documentNo' => $originalDocNo);
        
                    $zatcadocument_original_update_result = $wpdb->update('zatcadocument', $zatcadocument_original_update_data, $whereOriginal);
                    ///////end////////
                    */


                    // update zatca document fields with response Data:
                    $zatcadocument_error_response_data = [
    
                        "zatcaResponseDate" => $response_date,
                        "zatcaSuccessResponse" => 3,
                        "zatcaErrorResponse" => $errorMessage
                    ];
                    $where = array('documentNo' => $docNo);
        
                    $zatcadocument_error_response_result = $wpdb->update('zatcadocument', $zatcadocument_error_response_data, $where);
                    
                    // Check for errors
                    if ($zatcadocument_error_response_result === false) {
                        
                        // Handle error
                        $msg = "There was an error updating zatcadocument on the field." . $wpdb->last_error;
                    
                    }elseif ($zatcadocument_error_response_result === 0) {
                        
                        // No rows affected
                        $msg = "No rows were affected. Possible reasons: No matching rows or the data is already up to date."; 
                    }
    
                    // check if have error message or not:
                    if(is_array($validationResults)){
    
                        $msg = $http_status_msg . ' Error: ' . $errorMessage;
    
                    }else{
    
                        $msg = $http_status_msg . ' Error: ' . $validationResults;
                    }
    
                    
                
                }else{
    
                    $msg = $response;
                }
    
            }
    
            // Log the send to zatca:
            $user_login = wp_get_current_user()->user_login;
            $user_id = wp_get_current_user()->ID;
            log_send_to_zatca($user_login, $user_id);
    
            $send_response = [
    
                'msg' => $msg,
                'validationResults' => $validationResults,
                'responseArray' => $responseArray,
                'data' => $data
    
            ];
    
            wp_send_json($send_response);
            
        }
    }
}

//function to insert new copy of data and fire the return button action [Return]
function send_return_request_to_zatca(){
    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];


    // get deviceNo from zatcadocument table where documentNo = doc_no
    $invoiceNo = $wpdb->get_var("SELECT invoiceNo FROM zatcadocument WHERE documentNo = '$doc_no'");

    // function to insert new copy to woocommerce and new copy to zatcadocument and new copy to zatcadocumentunit
    insert_zatcaDocument_returned($doc_no, $invoiceNo);

}

////////////////////////////////////////////PDF Code/////////////////////////////////////////////////

require_once(plugin_dir_path(__FILE__) . 'documents/document80mm.php');