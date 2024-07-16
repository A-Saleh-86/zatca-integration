<?php

$docNo = $_GET['doc-no'];

global $wpdb;



// Table Name:
$table_name = 'zatcadocument';

// Prepare the query with a condition on the VendorId column using the %d placeholder
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE documentNo = %d", $docNo ) );

// Check if there are results
if (!empty($results)) {
    foreach ($results as $result) { ?>

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
        <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Edit Document', 'zatca')?></h5>
    </div>
    <!-- / Header -->

    <!-- Form Of Inputes -->
    <form class="form-horizontal main-form mt-1" id="edit_document_form">
        
        <!--  documentNo field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Invoice No:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="documentNo"
                        id="documentNo"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo $result->documentNo ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  documentNo field -->


        <!--  System Invoice No field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label "><?php echo _e('System Invoice No:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="input-group">
                    <input 
                        type="text"
                        name="invoice-no"
                        id="woo-invoice-no"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->invoiceNo, 'zatca') ?>"
                        disabled
                    />

                    <div class="mx-1"></div>

                    <!-- Search Btn -->
                    <!-- <button 
                        type='button' 
                        class='btn my-plugin-button me-1' 
                        data-bs-toggle='modal' 
                        data-bs-target='#exampleModal-search-invoices' 
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="<?php echo _e('Search Invoices', 'zatca') ?>">
                        <span class="dashicons dashicons-search"></span>
                    </button> -->
                    <!-- / Search Btn -->

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
        </div>
        <!-- /  System Invoice No field -->

        <!--  deliveryDate field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Delivery Date:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="date"
                        name="deliveryDate" 
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->deliveryDate, 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  deliveryDate field -->

        <!--  gaztLatestDeliveryDate field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Max Delivery Date:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="date"
                        name="gaztLatestDeliveryDate" 
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->gaztLatestDeliveryDate, 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  gaztLatestDeliveryDate field -->

        <!--  ZATCA Invoices Type  field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Invoices Type :', 'zatca') ?></label>
            <div class="col-sm-10 col-md-9">
                <div class="form-group">
                    <select class="form-select select2"  name="zatcaInvoiceType">
                        <option value="1" <?php if($result->zatcaInvoiceType  == '1'){ echo 'selected';}?>>B2B</option>
                        <option value="0" <?php if($result->zatcaInvoiceType  == '0'){ echo 'selected';}?> >B2C</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- /  ZATCA Invoices Type  field -->

        <!--  Payed (Cash) field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Payed (Cash):', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed01"
                        id="payed-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountPayed01,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  Payed (Cash) field -->

        <!--  Payed (visa) field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Payed (visa):', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed02"
                        id="payed_visa"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountPayed02,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  Payed (visa) field -->

        <!--  Payed (Bank) field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Payed (Bank):', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed03"
                        id="payed_bank"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountPayed03,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  Payed (Bank) field -->

        <!--  Total Payed field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Total Payed:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountCalculatedPayed"
                        id="amountCalculatedPayed"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountCalculatedPayed,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  Total Payed field -->

        <!--  Invoice Total field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Invoice Total:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="subTotal"
                        id="subTotal"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->subTotal,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  Invoice Total field -->

        <!--  SubTotalDiscount field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Discount:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="subTotalDiscount"
                        id="discount-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->subTotalDiscount,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  SubTotalDiscount field -->

        <!--  taxRate1_Percentage  field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Tax Percentage :', 'zatca') ?></label>
            <div class="col-sm-10 col-md-9">
                <div class="form-group">
                    <select class="form-select select2"  name="taxRate1_Percentage" disabled>
                        <option value=""> ...</option>
                        <option value="0">0</option>
                        <option value="5">5</option>
                        <option value="15">15</option>
                    </select>
                </div>
            </div>
        </div>
        <!-- /  taxRate1_Percentage  field -->

        <!--  taxRate1_Total field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Tax Total:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="taxRate1_Total"
                        id="total_tax"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->taxRate1_Total,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  taxRate1_Total field -->

        <!--  subNetTotal field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Invoice Net:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="subNetTotal"
                        id="invoice-net-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->subNetTotal, 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  subNetTotal field -->

        <!--  subNetTotalPlusTax field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Subnet Total plus tax:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="subNetTotalPlusTax"
                        id="subnet-total-plus-tax-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->subNetTotalPlusTax,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  subNetTotalPlusTax field -->

        <!--  amountLeft field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Left Amount:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountLeft"
                        id="left-amount-input"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e(round($result->amountLeft,2), 'zatca') ?>"
                        disabled
                    />
                </div>
            </div>
        </div>
        <!-- /  amountLeft field -->

        <?php
        // Get Data From zatcacompany Table:

        // Table Name:
        $table_name = 'zatcacompany';

        // Prepare the query with a condition on the VendorId column using the %d placeholder
        $companies = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE companyNo = %d", 1 ) );
        
        foreach ($companies as $company) {?>

            <!--  VATCategoryCode field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('VAT Category Code:', 'zatca') ?></label>
                <div class="col-sm-10 col-md-9">
                    <div class="form-group">
                        <select class="form-select select2"  name="edit-vat-cat-code" id="vat-cat-code">
                            <option value="">...</option>
                            <?php
                                global $wpdb;

                                // Fetch Data From Database [ met_vatcategorycode table ]:
                                $categories = $wpdb->get_results( "SELECT * FROM met_vatcategorycode" );
                                foreach($categories as $category) {?>
                                    
                                    <option value="<?php echo $category->VATCategoryCodeNo ?>" <?php if($company->VATCategoryCode == $category->VATCategoryCodeNo){ echo 'selected';} ?> ><?php echo $category->aName. ' - ' . $category->eName ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /  VATCategoryCode field -->

            <!--  VATCategoryCodeSubTypeNo field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('VAT Category SubType Code:', 'zatca') ?></label>
                <div class="col-sm-10 col-md-9">
                    <div class="form-group">
                        <select class="form-select select2"  name="vat-cat-code-sub-no" id="vat-cat-code-sub">
                            <option value="">...</option>
                            <?php
                                global $wpdb;

                                // Fetch Data From Database [ met_vatcategorycodesubtype table depend on VATCategoryCodeNo ]:
                                $subCategories = $wpdb->get_results( "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeNo = $company->VATCategoryCode" );
                                foreach($subCategories as $subCat) {?>
                                    
                                    <option value="<?php echo $subCat->VATCategoryCodeSubTypeNo ?>" <?php if($company->VATCategoryCodeSubTypeNo == $subCat->VATCategoryCodeSubTypeNo){ echo 'selected';} ?> ><?php echo $subCat->aName. ' - ' . $subCat->eName ?></option>
                                    <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /  VATCategoryCodeSubTypeNo field -->
        
            <?php
        }
        ?>

        <!--  taxExemptionReason field -->
        <div class="mb-3 row col-mid-6" id="exemptionReason">
            <label class="col-sm-2 col-form-label"><?php echo _e('Exemption Reason:', 'zatca') ?></label>
            <div class="col-sm-10 col-md-9">
                <div class="form-group">
                    <input 
                        type="text"
                        name="taxExemptionReason"
                        id="exemption-reason"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo _e($result->zatca_TaxExemptionReason, 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  taxExemptionReason field -->
        
        <!-- CheckBox [ isNominal ] -->
        <div class="row mb-3">
            <div class="col-sm-12 d-flex align-items-center">

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
                    >
                <label class="form-check-label ms-1 mb-1" for="isNominal">
                    <?php echo _e('Is Nominal', 'zatca'); ?>
                </label>
                <!-- / zatcaInvoiceTransactionCode_isNominal -->
                
                <span class="checkbox-spacer"></span>

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

                    ?> >
                <label class="form-check-label ms-1 mb-1" for="isExports">
                    <?php echo _e('Is Export', 'zatca') ?>
                </label>
                <!-- / zatcaInvoiceTransactionCode_isExports -->

                <span class="checkbox-spacer"></span>
                
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

                    ?> >
                <label class="form-check-label ms-1 mb-1" for="isSummary">
                    <?php echo _e('Is Summary', 'zatca') ?>
                </label>
                <!-- / zatcaInvoiceTransactionCode_isSummary -->

            </div>
        </div>
        <!-- / CheckBox [ isNominal ] -->

        <!--  note field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Notes:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="note"
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Notes', 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  note field -->

        <!---->
        <?php
        // Prepare the query with a condition to get orders 
        $results1 = $wpdb->get_results( $wpdb->prepare( "SELECT o.order_item_name, z.* FROM zatcadocumentunit z, wp_woocommerce_order_items o WHERE z.documentNo = %d and z.itemNo=o.order_item_id and o.order_item_type='line_item'", $docNo ) );
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

        <!-- Submit Btn -->
        <div class="mb-3 row">
            <div class="d-grid gap-2 col-8 md-flex justify-content-md-end">
                <input type="submit" value="<?php echo _e('Edit Document', 'zatca') ?>" class="my-plugin-button " />
            </div>
        </div>
        <!-- / Submit Btn -->

        

    </form>
    <!--  /Form Of Inputes -->

</div>


    <?php
    }
}