<?php

/**************************MBAUOMY**************************** */


// Add tax_invoice_option checkbox to checkout page


function zatca_customer_form() {
    include_once('customers/insert.php'); // Call the function and return its output
}
add_shortcode('zatca_customer_form1', 'zatca_customer_form');


// Add tax_invoice_option checkbox in order details section the_post / woocommerce_before_checkout_form
/*add_action( 'woocommerce_before_checkout_form', 'display_custom_checkout_shortcode');
function display_custom_checkout_shortcode() {
    // Add zatca_customer_form1 for the new section here
    echo '<div class="" style="width:100%; position:relative;top:80%;z-index:1000;">';

    echo do_shortcode('[zatca_customer_form1]');

    echo '</div>';
    
}
*/

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



/////////////////////////Task2/////////////////////////////

// Create admin page
function invoice_audit_admin_page() {
    // 
    /*add_menu_page(
        'Zacta Tampering Detector', // Page title
        'Zacta Tampering Detector', // Menu title
        'manage_options', // Capability required to access the page
        'invoice-audit-admin-page', // Unique menu slug
        'invoice_audit_admin_page_content', // Callback function to display page content
        'dashicons-admin-generic', // Icon URL or WordPress dashicon class
        4 // Menu position
    );*/

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
    echo '<h2>Zacta Tampering Detector</h2>';
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
                "reason" => "",
                "invoiceNo" => ""
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

function send_reissue_request_to_zatca(){
    global $wpdb;
    // document no pass from ajax:
    $doc_no = $_REQUEST['doc_no_from_ajax'];

}


////////////////////////////////////////////PDF Code/////////////////////////////////////////////////

require_once(plugin_dir_path(__FILE__) . 'documents/pdf-document.php');