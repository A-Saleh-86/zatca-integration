<?php
include_once dirname(dirname(__FILE__)) . '/zatca.php';
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
    <!-- Back Btn -->

    <!-- Header -->
    <div class="col-xl-9 mx-auto mt-3">
        <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Insert New Document', 'zatca')?></h5>
    </div>
    <!-- / Header -->

    <!-- Form Of Inputes -->
    <form class="form-horizontal main-form mt-1" id="insert_document_form">
        

        <?php
        global $wpdb;
        
        // Prepare the query for documentNo [ If Not Equal 0 get it +1]:
        $table_name_document = 'zatcaDocument';
        $table_name_device = 'zatcaDevice';

        $table_schema = $wpdb->dbname;

        $deviceNo = $wpdb->get_var($wpdb->prepare(
            "SELECT deviceNo
            FROM $table_name_device
            WHERE deviceStatus = 0")
            );

        $query = $wpdb->prepare("SELECT IFNULL(MAX(documentNo), 0) FROM zatcaDocument WHERE deviceNo = $deviceNo");
        $docNo = $wpdb->get_var($query);
        $docNo = $docNo + 1;
        ?>

         <!--Start Seller and Buyer Tables-->
         <div class="row seller_buyer">
            <!-- Seller Table-->
            <div class="col-md-6 seller"></div>
            <div class="col-md-6 buyer"></div>
        </div>
        <!--End Seller and Buyer Tables-->
        <hr>

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
                        value="<?php echo _e($docNo, 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
        
            <!-- /  documentNo field -->

            <!--  System Invoice No field -->
            <div class="col-md-6">
                    <label class="form-label"><?php echo _e('System Invoice No:', 'zatca') ?></label>
                    <div class="input-group">
                        <input 
                            type="text"
                            name="invoice-no"
                            id="woo-invoice-no"
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('System Invoice No', 'zatca') ?>"
                        />

                        <div class="mx-1"></div>

                        <!-- Search Btn -->
                        <button 
                            type='button' 
                            class='btn my-plugin-button me-1' 
                            data-bs-toggle='modal' 
                            data-bs-target='#exampleModal-search-invoices' 
                            data-bs-toggle="tooltip" 
                            data-bs-placement="top" 
                            title="<?php echo _e('Search Invoices', 'zatca') ?>">
                            <span class="dashicons dashicons-search"></span>
                        </button>
                        <span id="statusOrderSpan" style="color:red;font-weight:bolder;margin:2px;padding:2px;"></span>
                        <input type="text" class="m-2" id="woo-order-status" name="billTypeNo" hidden>
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
            <!-- /  System Invoice No field -->

            <!--  Invoice Date field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Invoice Date:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="invoice_date"
                        id="invoice_date"
                        class="form-control" 
                        autocomplete="off"
                        value="<?php echo date('d M Y') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Invoice Date field -->

            <!--  deliveryDate field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Delivery Date:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="date"
                        name="deliveryDate"
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Delivery Date', 'zatca') ?>"
                    />
                </div>
            </div>
            <!-- /  deliveryDate field -->

            <!--  gaztLatestDeliveryDate field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Max Delivery Date:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="date"
                        name="gaztLatestDeliveryDate" 
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Max Delivery Date', 'zatca') ?>"
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
                        placeholder="<?php echo _e('Payed (Cash)', 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Payed (Cash) field -->

            <!--  Payed (Visa) field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Payed (Visa):', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="amountPayed02"
                        id="payed_visa"
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Payed (Visa)', 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Payed (Visa) field -->

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
                        placeholder="<?php echo _e('Payed (Bank)', 'zatca') ?>"
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
                        placeholder="<?php echo _e('Total Payed', 'zatca') ?>"
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
                        placeholder="<?php echo _e('Invoice Total', 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  Invoice Total field -->

            <!-- SubTotalDiscount field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Total Discount:', 'zatca') ?></label>
                <div class="form-group">
                    <input 
                        type="text"
                        name="subTotalDiscount"
                        id="discount-input"
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Total Discount', 'zatca') ?>"
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
                        placeholder="<?php echo _e('Tax Total', 'zatca') ?>"
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
                        placeholder="<?php echo _e('Invoice Net', 'zatca') ?>"
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
                        placeholder="<?php echo _e('Subnet Total plus tax', 'zatca') ?>"
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
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE companyNo = %d", 1 ) );
        
            foreach ($results as $result) {?>
            <!--  VATCategoryCode field -->
            <div class="col-md-4 deviceNo_hidden">
                <label class="form-label"><?php echo _e('VAT Category Code:', 'zatca') ?></label>
                <div class="form-group">
                    <select class="form-select select2"  name="insert-doc-vat-cat-code" id="vat-cat-code">
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From Database [ met_vatcategorycode table ]:
                            $categories = $wpdb->get_results( "SELECT * FROM met_vatcategorycode" );
                            foreach($categories as $category) {?>
                                
                                <option value="<?php echo $category->VATCategoryCodeNo ?>" <?php if($result->VATCategoryCode == $category->VATCategoryCodeNo){ echo 'selected';} ?> ><?php echo $category->aName. ' - ' . $category->eName ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
            </div>
            <!-- /  VATCategoryCode field -->

            <!--  VATCategoryCodeSubTypeNo field -->
            <div class="col-md-4 deviceNo_hidden">
                <label class="form-label"><?php echo _e('VAT Category SubType Code:', 'zatca') ?></label>
                <div class="form-group">
                    <select class="form-select select2"  name="vat-cat-code-sub-no" id="vat-cat-code-sub">
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From Database [ met_vatcategorycodesubtype table depend on VATCategoryCodeNo ]:
                            $subCategories = $wpdb->get_results( "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeNo = $result->VATCategoryCode" );
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
                        placeholder="<?php echo _e('Exemption Reason', 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  taxExemptionReason field -->



            <!--  ZATCA Invoices Type  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Invoices Type :', 'zatca') ?></label>
                <div class="form-group">
                    <select class="form-select select2"  name="zatcaInvoiceType" id= "zatcaInvoiceType">
                        <option value="">...</option>
                        <option value="0">B2C</option>
                        <option value="1">B2B</option>
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
                        placeholder="<?php echo _e('Left Amount', 'zatca') ?>"
                        readonly
                    />
                </div>
            </div>
            <!-- /  amountLeft field -->

            
            <!--Retrive all aName from zatcaReturnReason table from database-->
            <?php
            global $wpdb;
            $reasons = $wpdb->get_results( "SELECT * FROM zatcaReturnReason");
            ?>

            <!--  returnReasonType field -->
            <div class="col-md-6" id="returnReasonType">
                <label class="form-label"><?php echo _e('Return Reason:', 'zatca') ?></label>
                <div class="form-group">
                    <select name="returnReasonType" id="return-reason-type" class="form-control">
                        <option value=""><?php echo _e('Return Reason', 'zatca') ?></option>
                        <?php
                        foreach($reasons as $reason) {
                        ?>
                        <option value="<?= $reason->aName ?>"><?= $reason->aName ?></option>
                        <?php } ?>
                    </select>
                    <input type="hidden" name="returnReason" id="returnReason">
                </div>
            </div>
            <!-- /  returnReasonType field -->

            <!-- CheckBox [ isNominal ] -->
            <div class="col-md-6">
                <br>
                <div class="row">
                    
                    <div class="col-md-4">
                        <!-- zatcaInvoiceTransactionCode_isNominal -->
                        <input class="form-check-input " type="checkbox" id="isNominal" name="isNominal">
                        <label class="form-check-label ms-1 mb-1" for="isNominal">
                            <?php echo _e('Is Nominal', 'zatca') ?>
                        </label>
                        <!-- / zatcaInvoiceTransactionCode_isNominal -->
                    </div>
                    

                    <div class="col-md-4">
                        <!-- zatcaInvoiceTransactionCode_isExports -->
                        <input class="form-check-input" type="checkbox" id="isExports" name="isExports">
                        <label class="form-check-label ms-1 mb-1" for="isExports">
                            <?php echo _e('Is Export', 'zatca') ?>
                        </label>
                        <!-- / zatcaInvoiceTransactionCode_isExports -->
                    </div>
                    

                    <div class="col-md-4">
                        <!-- zatcaInvoiceTransactionCode_isSummary -->
                        <input class="form-check-input" type="checkbox" id="isSummary" name="isSummary">
                        <label class="form-check-label ms-1 mb-1" for="isSummary">
                            <?php echo _e('Is Summary', 'zatca') ?>
                        </label>
                        <!-- / zatcaInvoiceTransactionCode_isSummary -->
                    </div>
                </div>
            </div>
            <!-- / CheckBox [ isNominal ] -->

           

            <div class="col-md-12">
                <!--Invoice Lines-->
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
                            
                        </tbody>
                    </table>
                </div>
                <!--Invoice Lines-->
            </div>

            <!-- Submit Btn -->
            <div class="d-grid gap-2 col-md-7 md-flex justify-content-md-end">
                <input type="submit" value="<?php echo _e('Insert New Document', 'zatca') ?>" class="my-plugin-button " />
            </div>
            <!-- / Submit Btn -->


        </div>
        
    </form>
    <!--  /Form Of Inputes -->

</div>