<?php

$docNo = $_GET['doc-no'];

global $wpdb;

// Table Name:
$table_name = 'zatcaDocument';

// Prepare the query with a condition on the VendorId column using the %d placeholder
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE documentNo = %d", $docNo ) );

// Check if there are results
if (!empty($results)) {
    foreach ($results as $result) {
        // Prifix of wp_wc_orders:
        $table_orders = $wpdb->prefix . 'wc_orders';
        // get customer id from order table:
        $orderId = $result->invoiceNo;
        $order_Customer_Id = $wpdb->get_var($wpdb->prepare("select customer_id from $table_orders WHERE id = $orderId"));

        ///////////////Buyer Info//////////////////////
        // get aName from zatcaCustomers:
        $buyer_aName_Customer = $wpdb->get_var($wpdb->prepare("select aName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get eName from zatcaCustomers:
        $buyer_eName_Customer = $wpdb->get_var($wpdb->prepare("select eName from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
       
        // get apartmentNum from zatcaCustomers:
        $buyer_apartmentNum_Customer = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get street_Arb from zatcaCustomers:
        $buyer_street_Arb_Customer = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get street_Eng from zatcaCustomers:
        $buyer_street_Eng_Customer = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get district_Arb from zatcaCustomers:
        $buyer_district_Arb_Customer = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get district_Eng from zatcaCustomers:
        $buyer_district_Eng_Customer = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get city_Arb from zatcaCustomers:
        $buyer_city_Arb_Customer = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get city_Arb from zatcaCustomers:
        $buyer_city_Eng_Customer = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get CountryNo from zatcaCustomers:
        $buyer_CountryNo_Customer = $wpdb->get_var($wpdb->prepare("select country_No from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));
        
        // get buyer vat from zatcaCustomer
        $buyer_Postal_Code = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get buyer vat from zatcaCustomer
        $buyer_POBoxAdditionalNum = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get buyer vat from zatcaCustomer
        $buyer_VAT = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        // get buyer vat from zatcaCustomer
        $buyer_SecondBusinessID = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCustomer WHERE clientVendorNo = $order_Customer_Id"));

        ///////////////Seller Info/////////////////////////////

        // Get Seller Vat from zatcaCompany [ seller ]:
        $seller_Name = $wpdb->get_var($wpdb->prepare("select companyName from zatcaCompany"));

        // Get Seller apartment from zatcaCompany [ seller ]:
        $seller_apartmentNum_Company = $wpdb->get_var($wpdb->prepare("select apartmentNum from zatcaCompany"));

        // Get Seller street_Arb from zatcaCompany [ seller ]:
        $seller_street_Arb_Company = $wpdb->get_var($wpdb->prepare("select street_Arb from zatcaCompany"));
        
        // Get Seller street_Eng from zatcaCompany [ seller ]:
        $seller_street_Eng_Company = $wpdb->get_var($wpdb->prepare("select street_Eng from zatcaCompany"));

        // Get Seller district_Arb from zatcaCompany [ seller ]:
        $seller_district_Arb_Company = $wpdb->get_var($wpdb->prepare("select district_Arb from zatcaCompany"));
        
        // Get Seller district_Eng from zatcaCompany [ seller ]:
        $seller_district_Eng_Company = $wpdb->get_var($wpdb->prepare("select district_Eng from zatcaCompany"));

        // Get Seller city_Arb from zatcaCompany [ seller ]:
        $seller_city_Arb_Company = $wpdb->get_var($wpdb->prepare("select city_Arb from zatcaCompany"));
        
        // Get Seller city_Eng from zatcaCompany [ seller ]:
        $seller_city_Eng_Company = $wpdb->get_var($wpdb->prepare("select city_Eng from zatcaCompany"));

        // Get Seller country_Arb from zatcaCompany [ seller ]:
        $seller_country_Arb_Company = $wpdb->get_var($wpdb->prepare("select country_Arb from zatcaCompany"));
        
        // Get Seller country_Eng from zatcaCompany [ seller ]:
        $seller_country_Eng_Company = $wpdb->get_var($wpdb->prepare("select country_Eng from zatcaCompany"));

        // Get Seller postal code from zatcaCompany:
        $seller_postalCode = $wpdb->get_var($wpdb->prepare("select postalCode from zatcaCompany"));

        // Get Seller POBoxAdditionalNum from zatcaCompany [ seller ]:
        $seller_POBoxAdditionalNum_Company = $wpdb->get_var($wpdb->prepare("select POBoxAdditionalNum from zatcaCompany"));

        // Get Seller Vat from zatcaCompany [ seller ]:
        $seller_VAT_Company = $wpdb->get_var($wpdb->prepare("select VATID from zatcaCompany"));
        
        
        // Get Seller POBox from zatcaCompany [ seller ]:
        $seller_secondBusinessID = $wpdb->get_var($wpdb->prepare("select secondBusinessID from zatcaCompany"));
        
        ?>

<div class="container">

    <!-- Back Btn -->
    <div class=" mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-documents&action=view'); ?>" 
            class="btn my-plugin-button" 
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="<?php echo _e('Back', 'zatca') ?>">
            <span class="dashicons dashicons-undo"></span>
        </a>
    </div>
    <!-- / Back Btn -->

    <!-- Header -->
    <div class="col-xl-9 mx-auto mt-3">
        <?php if($result->zatcaSuccessResponse == 0){ ?>
        <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Edit Document', 'zatca')?></h5>
        <?php } else{ ?>
            <h5 class="mb-3 text-uppercase text-center"><?php echo _e('View Document','zatca')?></h5>
        <?php } ?>
    </div>
    <!-- / Header -->

    <!-- Form Of Inputes -->
    <form class="form-horizontal main-form mt-1" id="edit_document_form">
        
        <!--Start Seller and Buyer Tables-->
        <div class="row seller_buyer">
            <!-- Seller Table-->
            <div class="col-md-6">
                <table class="table table-striped table-bordered text-center">
                    <tbody>
                        <tr>
                            <th>Buyer</th>
                            <th>المشترى</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>الاسم</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_aName_Customer; ?></td>
                            <td><?php echo $buyer_aName_Customer; ?></td>
                        </tr>
                        <tr>
                            <th>Building No</th>
                            <th>رقم المبنى</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_apartmentNum_Customer; ?></td>
                            <td><?php echo $buyer_apartmentNum_Customer; ?></td>
                        </tr>
                        <tr>
                            <th>Street Name</th>
                            <th>اسم الشارع</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_street_Eng_Customer; ?></td>
                            <td><?php echo $buyer_street_Arb_Customer; ?></td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <th>الحى</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_district_Eng_Customer; ?></td>
                            <td><?php echo $buyer_district_Arb_Customer; ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <th>المدينة</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_city_Eng_Customer; ?></td>
                            <td><?php echo $buyer_city_Arb_Customer; ?></td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <th>الدولة</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_CountryNo_Customer; ?></td>
                            <td><?php echo $buyer_CountryNo_Customer; ?></td>
                        </tr>
                        <tr>
                            <th>Postal Code</th>
                            <th>الرمز البريدى</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_Postal_Code; ?></td>
                            <td><?php echo $buyer_Postal_Code; ?></td>
                        </tr>
                        <tr>
                            <th>Additional No.</th>
                            <th>الرقم الاضافى للعنوان</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_POBoxAdditionalNum; ?></td>
                            <td><?php echo $buyer_POBoxAdditionalNum; ?></td>
                        </tr>
                        <tr>
                            <th>VAT Number</th>
                            <th>الرقم الضريبى</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_VAT; ?></td>
                            <td><?php echo $buyer_VAT; ?></td>
                        </tr>
                        <tr>
                            <th>Other Seller ID</th>
                            <th>معرف آخر</th>
                        </tr>
                        <tr>
                            <td><?php echo $buyer_SecondBusinessID; ?></td>
                            <td><?php echo $buyer_SecondBusinessID; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Seller Table-->
             <!-- Buyer Table-->
            <div class="col-md-6">
                <table class="table table-striped table-bordered text-center">
                    <tbody>
                        <tr>
                            <th>Seller</th>
                            <th>البائع</th>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <th>الاسم</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_Name; ?></td>
                            <td><?php echo $seller_Name; ?></td>
                        </tr>
                        <tr>
                            <th>Building No</th>
                            <th>رقم المبنى</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_apartmentNum_Company; ?></td>
                            <td><?php echo $seller_apartmentNum_Company; ?></td>
                        </tr>
                        <tr>
                            <th>Street Name</th>
                            <th>اسم الشارع</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_street_Eng_Company; ?></td>
                            <td><?php echo $seller_street_Arb_Company; ?></td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <th>الحى</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_district_Eng_Company; ?></td>
                            <td><?php echo $seller_district_Arb_Company; ?></td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <th>المدينة</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_city_Eng_Company; ?></td>
                            <td><?php echo $seller_city_Arb_Company; ?></td>
                        </tr>
                        <tr>
                            <th>Country</th>
                            <th>الدولة</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_country_Eng_Company; ?></td>
                            <td><?php echo $seller_country_Arb_Company; ?></td>
                        </tr>
                        <tr>
                            <th>Postal Code</th>
                            <th>الرمز البريدى</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_postalCode; ?></td>
                            <td><?php echo $seller_postalCode; ?></td>
                        </tr>
                        <tr>
                            <th>Additional No.</th>
                            <th>الرقم الاضافى للعنوان</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_POBoxAdditionalNum_Company; ?></td>
                            <td><?php echo $seller_POBoxAdditionalNum_Company; ?></td>
                        </tr>
                        <tr>
                            <th>VAT Number</th>
                            <th>الرقم الضريبى</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_VAT_Company; ?></td>
                            <td><?php echo $seller_VAT_Company; ?></td>
                        </tr>
                        <tr>
                            <th>Other Seller ID</th>
                            <th>معرف آخر</th>
                        </tr>
                        <tr>
                            <td><?php echo $seller_secondBusinessID; ?></td>
                            <td><?php echo $seller_secondBusinessID; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Buyer Table-->

        </div>
        <!--End Seller and Buyer Tables-->

        <hr>
        
        <!--Start Invoice Form-->
        <div class="row">

            <!--  documentNo field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Invoice No:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="documentNo"
                        id="documentNo"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo $result->documentNo ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  documentNo field -->

            <!--  System Invoice No field -->
            <div class="col-md-6">
                <?php
                    // Get the site's URL  
                    $site_url = get_site_url(); // or use home_url() if you prefer
                    // Construct the WooCommerce Orders URL  
                    $orders_url = $site_url . '/wp-admin/admin.php?page=wc-orders&action=edit&id=' . $result->invoiceNo;
                ?>
                <a href="<?php echo $orders_url; ?>" target="_blank"><label class="form-label" style="cursor:pointer"><?php echo _e('System Invoice No:', 'zatca') ?></label></a>
                <div class="input-group">
                    <input 
                        type="text"
                        name="invoice-no"
                        id="woo-invoice-no"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->invoiceNo, 'zatca') ?>"
                        readonly
                    />

                    <div class="mx-1"></div>


                    <!-- Invoices Modal -->
                    <div class='modal fade' id='exampleModal-search-invoices' tabindex='-1' aria-labelledby='exampleModalLabel-search-invoices' aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content' >
                            <div class='modal-header'>
                                <h5 class='modal-title'><?php echo _e('Invoices', 'zatca') ?></h5>
                            </div>
                            <div class='modal-body'>
                                
                                <div class="container ">
                                    
                                    <table id="example" class="table table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Order No', 'zatca') ?></th>
                                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Email', 'zatca') ?></th>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <?php
                                            global $wpdb;
                                            
                                            // Get Data from Order table:
                                            $table_order = $wpdb->prefix . 'wc_orders';
                                            $orders = $wpdb->get_results("SELECT * FROM $table_order");
                                            
                                                foreach ($orders as $order) {?>
                                                    <tr data-order-id="<?php echo $order->id;?>" data-customer-id="<?php echo $order->customer_id ?>">
                                                        <td><?php echo $order->id ?></td>
                                                        <td><?php echo $order->billing_email ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                
                                </div> 
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='my-plugin-button' data-bs-dismiss='modal'>
                                    <span class="dashicons dashicons-no"></span>
                                </button>

                                <!-- Copy btn -->
                                <button class="my-plugin-button me-1" type="button" id='search-invoices-data' >
                                    <span class="dashicons dashicons-saved"></span>
                                </button> 
                                <!-- / Copy Btn -->

                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- / Invoices Modal -->

                    <!-- Add Customer Modal -->
                    <div class='modal fade' id='customer-modal' tabindex='-1' aria-labelledby='customer-modal' aria-hidden='true'>
                        <div class='modal-dialog'>
                            <div class='modal-content' >
                                <div class='modal-header'>
                                    <h5 class='modal-title'><?php echo _e('Customer Not Exist', 'zatca') ?></h5>
                                </div>
                                <div class='modal-body'>
                                    
                                    <div class="container ">
                                        
                                    <?php echo _e('This Customer Not Exist In Zatca Customers.Insert Now?', 'zatca') ?>
                                    
                                    </div> 
                                </div>
                                <div class='modal-footer'>
                                    <button type='button' class='my-plugin-button' data-bs-dismiss='modal'>
                                        <span class="dashicons dashicons-no"></span>
                                    </button>
                                    
                                    <!-- insert Customer btn -->
                                    <button 
                                        class="my-plugin-button me-1" 
                                        type="button"  
                                        id="document-add-customer" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="<?php echo _e('Insert New Customer', 'zatca') ?>">
                                        <span class="dashicons dashicons-saved"></span>
                                    </button> 
                                    <!-- / insert customer Btn -->
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Add Customer Modal -->

                </div>
            </div>
            <!-- /  System Invoice No field -->

            <!--  deliveryDate field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Delivery Date:', 'zatca') ?></label>
                    <div class="form-group">
                        <input 
                            type="date"
                            name="deliveryDate" 
                            class="form-control" 
                            autocomplete="off"
                            value="<?php echo _e($result->deliveryDate, 'zatca') ?>"
                            <?php if($result->zatcaSuccessResponse != 0){ ?>readonly<?php } ?>
                        />
                    </div>
            </div>
            <!-- /  deliveryDate field -->

            <!--  gaztLatestDeliveryDate field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Max Delivery Date:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="date"
                        name="gaztLatestDeliveryDate" 
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->gaztLatestDeliveryDate, 'zatca') ?>"
                        <?php if($result->zatcaSuccessResponse != 0){ ?>readonly<?php } ?>
                    />
                </div>
            </div>
            <!-- /  gaztLatestDeliveryDate field -->

            

            <!--  Payed (Cash) field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Payed (Cash):', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed01"
                        id="payed-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountPayed01,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Payed (Cash) field -->

            <!--  Payed (visa) field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Payed (visa):', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed02"
                        id="payed_visa"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountPayed02,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Payed (visa) field -->

            <!--  Payed (Bank) field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Payed (Bank):', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed03"
                        id="payed_bank"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountPayed03,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Payed (Bank) field -->



            <!--  Total Payed field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Total Payed:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountCalculatedPayed"
                        id="amountCalculatedPayed"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountCalculatedPayed,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Total Payed field -->

            <!--  Invoice Total field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Invoice Total:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="subTotal"
                        id="subTotal"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->subTotal,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Invoice Total field -->

            <!--  SubTotalDiscount field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Discount:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="subTotalDiscount"
                        id="discount-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->subTotalDiscount,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  SubTotalDiscount field -->



            <!--  taxRate1_Total field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Tax Total:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="taxRate1_Total"
                        id="total_tax"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->taxRate1_Total,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  taxRate1_Total field -->

            <!--  subNetTotal field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Invoice Net:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="subNetTotal"
                        id="invoice-net-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->subNetTotal, 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  subNetTotal field -->

            <!--  subNetTotalPlusTax field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Subnet Total plus tax:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="subNetTotalPlusTax"
                        id="subnet-total-plus-tax-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->subNetTotalPlusTax,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  subNetTotalPlusTax field -->


            <?php
            // Get Data From zatcaCompany Table:

            // Table Name:
            $table_name = 'zatcaCompany';

            // Prepare the query with a condition on the VendorId column using the %d placeholder
            $companies = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE companyNo = %d", 1 ) );
            
            foreach ($companies as $company) {?>

            <!--  VATCategoryCode field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('VAT Category Code:', 'zatca') ?></label>
                <div class="form-group">
                    <select class="form-select select2"
                     name="edit-vat-cat-code" 
                     id="vat-cat-code"
                    <?php if($result->zatcaSuccessResponse != 0){ ?>disabled<?php } ?>
                    >
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From Database [ met_vatcategorycode table ]:
                            $categories = $wpdb->get_results( "SELECT * FROM met_vatcategorycode" );
                            foreach($categories as $category) {?>
                                
                                <option value="<?php echo $category->VATCategoryCodeNo ?>" <?php if($result->VATCategoryCodeNo == $category->VATCategoryCodeNo){ ?>selected<?php } ?> ><?php echo $category->aName. ' - ' . $category->eName ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- /  VATCategoryCode field -->

            <!--  VATCategoryCodeSubTypeNo field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('VAT Category SubType Code:', 'zatca') ?></label>
                <div class="form-group">
                    <select class="form-select select2"
                    name="vat-cat-code-sub-no" 
                    id="vat-cat-code-sub"
                    <?php if($result->zatcaSuccessResponse != 0){ ?>disabled<?php } ?>
                    >
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From Database [ met_vatcategorycodesubtype table depend on VATCategoryCodeNo ]:
                            $subCategories = $wpdb->get_results( "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeNo = $result->VATCategoryCodeNo" );
                            foreach($subCategories as $subCat) {?>
                                
                                <option value="<?php echo $subCat->VATCategoryCodeSubTypeNo ?>" <?php if($result->VATCategoryCodeSubTypeNo == $subCat->VATCategoryCodeSubTypeNo){ echo 'selected';} ?> ><?php echo $subCat->aName. ' - ' . $subCat->eName ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- /  VATCategoryCodeSubTypeNo field -->
        
            <?php
            }
            ?>

            <!--  taxExemptionReason field -->
            <div class="col-md-4" id="exemptionReason">
                <label class="form-label"><?php echo _e('Exemption Reason:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="taxExemptionReason"
                        id="exemption-reason"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->zatca_TaxExemptionReason, 'zatca') ?>"
                        <?php if($result->zatcaSuccessResponse != 0){ ?>readonly<?php } ?>
                    />
                </div>
            </div>
            <!-- /  taxExemptionReason field -->


            

            <!--  ZATCA Invoices Type  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Invoices Type :', 'zatca') ?></label>
                <div class="form-group">
                    <select class="form-select select2"
                    name="zatcaInvoiceType"
                    <?php if($result->zatcaSuccessResponse != 0){ ?>disabled<?php } ?>
                    >
                        <option value="1" <?php if($result->zatcaInvoiceType  == '1'){ echo 'selected';}?>>B2B</option>
                        <option value="0" <?php if($result->zatcaInvoiceType  == '0'){ echo 'selected';}?> >B2C</option>
                    </select>
                </div>
            </div>
            <!-- /  ZATCA Invoices Type  field -->


            <!--  amountLeft field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Left Amount:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountLeft"
                        id="left-amount-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountLeft,2), 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  amountLeft field -->



            <!-- CheckBox [ isNominal ] -->
            <div class="col-md-12">
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <!-- zatcaInvoiceTransactionCode_isNominal -->
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="isNominal" 
                            name="isNominal"
                            value="<?php echo $result->zatcaInvoiceTransactionCode_isNominal ?>"
                            <?php
                            // check if checked or not:
                            echo (isset($result->zatcaInvoiceTransactionCode_isNominal) && $result->zatcaInvoiceTransactionCode_isNominal==0) ? 'checked' : '';
                            ?>
                            <?php if($result->zatcaSuccessResponse != 0){ ?>readonly<?php } ?>
                            >
                        <label class="form-check-label ms-1 mb-1" for="isNominal">
                            <?php echo _e('Is Nominal', 'zatca'); ?>
                        </label>
                        <!-- / zatcaInvoiceTransactionCode_isNominal -->
                    </div>
                    <div class="col-md-4">
                        <!-- zatcaInvoiceTransactionCode_isExports -->
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="isExports" 
                            name="isExports"
                            value="<?php echo _e($result->zatcaInvoiceTransactionCode_isExports, 'zatca') ?>"
                            <?php
                            // check if checked or not:
                            echo  (isset($result->zatcaInvoiceTransactionCode_isExports) && $result->zatcaInvoiceTransactionCode_isExports==0) ? 'checked' : '';
                            ?>
                            <?php if($result->zatcaSuccessResponse != 0){ ?>readonly<?php } ?>
                            >
                        <label class="form-check-label ms-1 mb-1" for="isExports">
                            <?php echo _e('Is Export', 'zatca') ?>
                        </label>
                        <!-- / zatcaInvoiceTransactionCode_isExports -->
                    </div>
                    <div class="col-md-4">
                        <!-- zatcaInvoiceTransactionCode_isSummary -->
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            id="isSummary" 
                            name="isSummary"
                            value="<?php echo _e($result->zatcaInvoiceTransactionCode_isSummary, 'zatca') ?>"
                            <?php
                            // check if checked or not:
                            echo (isset($result->zatcaInvoiceTransactionCode_isSummary) && $result->zatcaInvoiceTransactionCode_isSummary==0) ? 'checked' : '';

                            ?>
                            <?php if($result->zatcaSuccessResponse != 0){ ?>readonly<?php } ?>
                            >
                        <label class="form-check-label ms-1 mb-1" for="isSummary">
                            <?php echo _e('Is Summary', 'zatca') ?>
                        </label>
                        <!-- / zatcaInvoiceTransactionCode_isSummary -->
                    </div>
                </div>
            </div>
            <!-- CheckBox [ isNominal ] -->


            


            <!--invoice lines-->
            <div class="col-md-12">
                <?php
                // Prepare the query with a condition to get orders 
                $results1 = $wpdb->get_results( $wpdb->prepare( "SELECT o.order_item_name, z.* FROM zatcaDocumentUnit z, wp_woocommerce_order_items o WHERE z.documentNo = %d and z.itemNo=o.order_item_id and o.order_item_type='line_item'", $docNo ) );
                ?>
                <div class="container">
                    <table id="example" class="table table-striped" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Item No', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name Ar', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name En', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Price', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Quantity', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Discount', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Tax Percentage', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Tax Amount', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Net Amount', 'zatca') ?></th>
                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Total Amount with Tax', 'zatca') ?></th>
                            </tr>
                        </thead>
                        <tbody class="order_details">
                        <?php foreach ($results1 as $data) { ?>
                            <tr>
                                <td class="text-center"><?php echo $data->itemNo ?></td>
                                <td class="text-center"><?php echo $data->order_item_name ?></td>
                                <td class="text-center"><?php echo $data->order_item_name ?></td>
                                <td class="text-center"><?php echo $data->price ?></td>
                                <td class="text-center"><?php echo $data->quantity ?></td>
                                <td class="text-center"><?php echo $data->discount ?></td>
                                <td class="text-center"><?php echo $data->vatRate ?>%</td>
                                <td class="text-center"><?php echo $data->vatAmount ?></td>
                                <td class="text-center"><?php echo $data->netAmount ?></td>
                                <td class="text-center"><?php echo $data->amountWithVAT ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--invoice lines-->

            <!-- Submit Btn -->
            <?php if($result->zatcaSuccessResponse == 0){ ?>
            <div class="col-md-7">
                <div class="d-grid gap-2 md-flex justify-content-md-end">
                    <input type="submit" value="<?php echo _e('Edit Document', 'zatca') ?>" class="my-plugin-button " />
                </div>
            </div>
            <!-- / Submit Btn -->
            <?php } ?>

        </div>
        <!--End Invoice Form-->

        <!--Start Invoice Details-->
        <div class="row">
            <?php
            global $wpdb;

            // Fetch Data From Database [ met_vatcategorycode table ]:
            $reissuanceInvoices = $wpdb->get_results( "SELECT * FROM zatcaDocument WHERE zatcaRejectedInvoiceNo='$result->documentNo' and isZatcaReissued=0" );
            $originalInvoices = $wpdb->get_results( "SELECT * FROM zatcaDocument WHERE zatcaAcceptedReissueInvoiceNo='$result->documentNo'" );
            foreach($reissuanceInvoices as $reissuance) {?>
                
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="card-title"><?php echo _e('Reissued Invoice', 'zatca') ?></h5>
                                    <p class="card-text"><?php echo _e('Invoice No:', 'zatca') ?>
                                        <a href="<?php echo admin_url('admin.php?page=zatca-documents&action=edit-document&doc-no='.$reissuance->documentNo.'')  ?>">
                                            <span class="badge bg-primary"><?php echo $reissuance->documentNo ?></span>
                                        </a>
                                    </p>
                                    <p class="card-text"><?php echo _e('Invoice Date:', 'zatca') ?> <span class="badge bg-primary"><?php echo $reissuance->dateG ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
        
            foreach($originalInvoices as $original) {?>
                
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="card-title"><?php echo _e('Original of Reissued Invoice', 'zatca') ?></h5>
                                    <p class="card-text"><?php echo _e('Invoice No:', 'zatca') ?> 
                                        <a href="<?php echo admin_url('admin.php?page=zatca-documents&action=edit-document&doc-no='.$original->documentNo.'') ?>">
                                            <span class="badge bg-primary"><?php echo $original->documentNo ?></span>
                                        </a>
                                    </p>
                                    <p class="card-text"><?php echo _e('Invoice Date:', 'zatca') ?> <span class="badge bg-primary"><?php echo $original->dateG ?></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
        <!--End Invoice Details-->

    </form>
    <!--  /Form Of Inputes -->

</div>


    <?php
    }
}