<div class="container">
    

    <div class="row">
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Documents', 'zatca') ?></h4>
    </div>
        <!-- Add Btn -->
    <div class="col-md-9 col-sm-12 mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-documents&action=insert')  ?>" 
            class="my-plugin-button btn add-btn" 
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="<?php echo _e('Add New Document', 'zatca') ?>">
            <span class="dashicons dashicons-insert"></span>
        </a>
    </div>
    <!-- / Add Btn -->

    <!-- Send Selected To Zatca Btn -->
    <div class="col-md-3 col-sm-12 mx-auto mt-3">
        <a 
        href="#" 
        class="my-plugin-button btn-sm" 
        id="send-zatca-sellected" 
        data-bs-toggle="tooltip" 
        data-bs-placement="top" 
        title="<?php echo _e('Send Sellected To Zatca', 'zatca') ?>"><?php echo _e('Send Sellected To Zatca', 'zatca') ?>
        <span class="dashicons dashicons-cloud-upload"></span> 
        </a>
    </div>
    <!-- / Add Btn -->
    </div>
    
    <!-- Table Of Data -->
    <table border="0" cellspacing="5" cellpadding="5">
        <tbody>
            <tr>
                <td><?php echo _e('Unsubmitted /Rejected Documents', 'zatca') ?></td>
                <td><input type="checkbox" id="doc-table-failed"></td>
                <td><?php echo _e('Min Invoice Date', 'zatca') ?></td>
                <td><input type="text" id="min" name="min"></td>
                <td><?php echo _e('Max Invoice Date', 'zatca') ?></td>
                <td><input type="text" id="max" name="max"></td>
                <td><button class="btn btn-primary btn-sm" type="button" id="reset"><?php echo _e('Clear', 'zatca') ?></button></td>
            </tr>
        </tbody>
    </table>
    
    <table id="document-table" class="table table-striped" width="100%">

        <thead>
            
            <tr>
                <th><input type="checkbox" onchange="checkAll(this)"></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('System Invoice No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Date', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Type', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Payed', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Discount', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Net', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Tax', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Subnet Total plus tax', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Status', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('zatcaSuccessResponse', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('zatcaAcceptedReissueInvoiceNo', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Action', 'zatca') ?></th>
            </tr>
        </thead>
    
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data
            $resultes = $wpdb->get_results( "SELECT * FROM zatcaDocument");
            
            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {?>

                    <?php
                        $zatcaCompanySatge1 = $wpdb->get_var($wpdb->prepare("SELECT zc.zatcaStage 
                        FROM zatcaDocument zd, zatcaCompany zc 
                        WHERE zd.vendorId = zc.VendorId AND zd.documentNo =  $result->documentNo"));

                        $zatcaStatus = $wpdb->get_var($wpdb->prepare("SELECT zatcaSuccessResponse 
                        FROM zatcaDocument 
                        WHERE documentNo =  $result->documentNo"));
                    ?>
                    <tr 
                    <?php if($zatcaStatus == 1) {?> style="background-color: #00800085" <?php } ?>
                    <?php if($zatcaStatus == 2) {?> style="background-color: #ffff0082" <?php } ?>
                    <?php if($zatcaStatus == 3) {?> style="background-color: #ff00007d" <?php } ?>
                    >
                        <td>
                            <input type="checkbox"
                             class="rowCheckbox"
                             data-document-no = "<?php echo $result->documentNo ?>"
                             data-success-response = "<?php echo $result->zatcaSuccessResponse ?>"
                             data-invoice-type = "<?php echo $result->zatcaInvoiceType ?>"
                             data-vatcategorycodesubtypeno = "<?php echo $result->VATCategoryCodeSubTypeNo ?>"
                             data-buyer-aname = "<?php echo $result->buyer_aName ?>"
                             data-buyer-secondbusinesstype = "<?php echo $result->buyer_secondBusinessIDType ?>"
                             data-buyer-secondbusinessid = "<?php echo $result->buyer_secondBusinessID ?>"
                             data-buyer-vat = "<?php echo $result->buyer_VAT ?>"
                             data-invoicetransactioncode-isexports = "<?php echo $result->zatcaInvoiceTransactionCode_isExports ?? 1 ?>"
                             data-seller-secondbusinessid = "<?php echo $result->seller_secondBusinessID ?>"
                             data-company-stage = "<?php echo $zatcaCompanySatge1 ?>">
                        </td>
                        <td style="font-size: 0.8rem;"><?php echo $result->documentNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->invoiceNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->dateG ?></td>
                        <td style="font-size: 0.8rem;">
                            <?php 
                            // Get Invoice Type Name:
                            if($result->zatcaInvoiceType == 0){
                                echo 'B2C';
                            }else{
                                echo 'B2B';
                            }
                            
                            ?>
                        </td>
                        <td style="font-size: 0.8rem;"><?php echo $result->amountCalculatedPayed ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->subTotalDiscount ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->subNetTotal ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->taxRate1_Total ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->subNetTotalPlusTax ?></td>
                        
                        <td style="font-size: 0.8rem;">
                            <?php if($zatcaStatus == 1){ echo __("Accepted Invoice", "zatca"); } ?>
                            <?php if($zatcaStatus == 2){ echo __("Accepted With Warning Invoice", "zatca"); } ?>
                            <?php if($zatcaStatus == 3){ echo __("Rejected Invoice", "zatca"); } ?>
                        </td>

                        <td style="font-size: 0.8rem;"><?php echo $result->zatcaSuccessResponse ?></td>
                        <td style="font-size: 0.8rem;"><?php if($result->zatcaAcceptedReissueInvoiceNo == NULL){echo "NULL";} ?></td>


                        <td style="font-size: 0.8rem;" class="action_btns">
                            <?php
                            // validation in zatcasuccessresponse - if 2  disable edit btn:
                            global $wpdb;

                            $zatcaSuccessResponse = $wpdb->get_var($wpdb->prepare("SELECT zatcaSuccessResponse 
                                                                                        FROM zatcaDocument 
                                                                                        WHERE documentNo =  $result->documentNo"));

                            $zatcaRejectedInvoiceNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaRejectedInvoiceNo 
                                                                                        FROM zatcaDocument 
                                                                                        WHERE documentNo =  $result->documentNo"));

                            $zatcaAcceptedReissueInvoiceNo = $wpdb->get_var($wpdb->prepare("SELECT zatcaAcceptedReissueInvoiceNo 
                                                                                        FROM zatcaDocument 
                                                                                        WHERE documentNo =  $result->documentNo"));
                                                                                        
                            $isZatcaReissued = $wpdb->get_var($wpdb->prepare("SELECT isZatcaReissued 
                                                                                        FROM zatcaDocument 
                                                                                        WHERE documentNo =  $result->documentNo"));

                            if ($zatcaSuccessResponse != NULL && (int)$zatcaSuccessResponse == 0){?>

                                <!-- Edit Btn -->
                                <a 
                                    href="<?php echo admin_url('admin.php?page=zatca-documents&action=edit-document&doc-no='.$result->documentNo.'')  ?>" 
                                    class="my-plugin-button  me-1" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Edit-Document', 'zatca') ?>">
                                    <span class="dashicons dashicons-edit"></span>
                                </a>
                                <!-- / Edit Btn -->

                                <!-- View Request Data Btn -->
                                <button 
                                    type="button" 
                                    class="my-plugin-button btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#request" 
                                    data-bs-backdrop="false" 
                                    data-document-no="<?php echo $result->documentNo; ?>" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('View Request Data', 'zatca') ?>">
                                    <span class="dashicons dashicons-welcome-view-site"></span>
                                </button>
                                <!-- / View Request Data Btn -->

                                <?php
                            }
                        
                            /*  Validation on document have zatcaSuccessResponse = 0 
                                to view send to zatca docemnt Btn;  */
                            global $wpdb;

                            $zatcaSuccessResponse = $wpdb->get_var($wpdb->prepare("SELECT zatcaSuccessResponse 
                                                                                    FROM zatcaDocument 
                                                                                    WHERE documentNo =  $result->documentNo"));
                            
                            $zatcaInvoiceType = $wpdb->get_var($wpdb->prepare("SELECT zatcaInvoiceType 
                                                                                    FROM zatcaDocument 
                                                                                    WHERE documentNo =  $result->documentNo"));


                            $zatcaCompanySatge = $wpdb->get_var($wpdb->prepare("SELECT zc.zatcaStage 
                            FROM zatcaDocument zd, zatcaCompany zc 
                            WHERE zd.vendorId = zc.VendorId AND zd.documentNo =  $result->documentNo"));    
                            // Check If zatcaSuccessResponse = 0 to show for all documents:
                            if ($zatcaSuccessResponse != NULL && (int)$zatcaSuccessResponse === 0){
                                
                                
                                $zatcaCompanySatge = $wpdb->get_var($wpdb->prepare("SELECT zc.zatcaStage 
                                FROM zatcaDocument zd, zatcaCompany zc 
                                WHERE zd.vendorId = zc.VendorId AND zd.documentNo =  $result->documentNo"));
                                    
                                // Check If 1 docuemnt B2B will redirect to clear():
                                if($zatcaInvoiceType == 1){?>

                                    <!-- Send To Zatca Btn [ clear() ] -->
                                    <a 
                                        href="#" 
                                        class="my-plugin-button btn-sm me-1" 
                                        id="send-zatca-clear" 
                                        data-doc-no = "<?php echo $result->documentNo ?>" 
                                        data-company-stage = "<?php echo $zatcaCompanySatge ?>"
                                        data-seller-secondbusinessid = "<?php echo $result->seller_secondBusinessID ?>"
                                        data-buyer-vat = "<?php echo $result->buyer_VAT ?>"
                                        data-invoicetransactioncode-isexports = "<?php echo $result->zatcaInvoiceTransactionCode_isExports ?? 1 ?>"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="<?php echo _e('Send To Zatca', 'zatca') ?>">
                                        <span class="dashicons dashicons-cloud-upload"></span>
                                    </a>
                                    <!-- / Send To Zatca Btn [ clear() ] -->
                                    <?php
                                }
                                else{ //if B2C will redirect to Report(): ?>

                                    
                                    <!-- Send To Zatca Btn [ report() ] -->
                                    <a 
                                        href="#" 
                                        class="my-plugin-button btn-sm me-1" 
                                        id="send-zatca-report" 
                                        data-doc-no = "<?php echo $result->documentNo ?>"
                                        data-vatcategorycodesubtypeno = "<?php echo $result->VATCategoryCodeSubTypeNo ?>"
                                        data-buyer-aname = "<?php echo $result->buyer_aName ?>"
                                        data-buyer-secondbusinesstype = "<?php echo $result->buyer_secondBusinessIDType ?>"
                                        data-buyer-secondbusinessid = "<?php echo $result->buyer_secondBusinessID ?>"
                                        data-seller-secondbusinessid = "<?php echo $result->seller_secondBusinessID ?>"
                                        data-company-stage = "<?php echo $zatcaCompanySatge ?>"
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="<?php echo _e('Send To Zatca', 'zatca') ?>">
                                        <span class="dashicons dashicons-cloud-upload"></span>
                                    </a>
                                    <!-- / Send To Zatca Btn  [ report() ] -->
                                    <?php

                                }
                            }

                            // check for if zatca success reponse > 0 - show download xml:
                            if((int)$zatcaSuccessResponse > 0){?>

                                <!--  Download XML Btn -->
                                <button 
                                    type="button" 
                                    class="my-plugin-button btn-sm me-1" 
                                    id="download-xml" data-doc-no = "<?php echo $result->documentNo ?>" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Download XML', 'zatca') ?>">
                                    <span class="dashicons dashicons-external"></span>
                                </button>
                                <!-- / Download XML Btn --> 

                            <?php
                            }
                            
                            // Reissue Btn for doc have zatca success response = 3:
                            if($zatcaSuccessResponse != NULL && (int)$zatcaSuccessResponse === 3 && $zatcaRejectedInvoiceNo == NULL && $zatcaAcceptedReissueInvoiceNo == NULL && ($isZatcaReissued == 1 || $isZatcaReissued == NULL)){?>
                                
                                <!-- Reissue -->
                                <a 
                                    href="#" 
                                    class="my-plugin-button btn-sm me-1" 
                                    id="send-zatca-reissue" 
                                    data-doc-no = "<?php echo $result->documentNo ?>"
                                    data-invoice-type = "<?php echo $result->zatcaInvoiceType ?>"
                                    data-vatcategorycodesubtypeno = "<?php echo $result->VATCategoryCodeSubTypeNo ?>"
                                    data-buyer-aname = "<?php echo $result->buyer_aName ?>"
                                    data-buyer-secondbusinesstype = "<?php echo $result->buyer_secondBusinessIDType ?>"
                                    data-buyer-secondbusinessid = "<?php echo $result->buyer_secondBusinessID ?>"
                                    data-company-stage = "<?php echo $zatcaCompanySatge ?>"
                                    data-seller-secondbusinessid = "<?php echo $result->seller_secondBusinessID ?>"
                                    data-buyer-vat = "<?php echo $result->buyer_VAT ?>"
                                    data-invoicetransactioncode-isexports = "<?php echo $result->zatcaInvoiceTransactionCode_isExports ?? 1 ?>"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Reissue', 'zatca') ?>">
                                    <span class="dashicons dashicons-controls-repeat"></span>
                                </a>
                                <!-- / Reissue-->
                                 

                                <!-- Return -->
                                <a 
                                    href="#" 
                                    class="my-plugin-button btn-sm me-1" 
                                    id="send-zatca-return" 
                                    data-doc-no = "<?php echo $result->documentNo ?>" 
                                    data-invoice-type = "<?php echo $result->zatcaInvoiceType ?>"
                                    data-vatcategorycodesubtypeno = "<?php echo $result->VATCategoryCodeSubTypeNo ?>"
                                    data-buyer-aname = "<?php echo $result->buyer_aName ?>"
                                    data-buyer-secondbusinesstype = "<?php echo $result->buyer_secondBusinessIDType ?>"
                                    data-buyer-secondbusinessid = "<?php echo $result->buyer_secondBusinessID ?>"
                                    data-company-stage = "<?php echo $zatcaCompanySatge ?>"
                                    data-seller-secondbusinessid = "<?php echo $result->seller_secondBusinessID ?>"
                                    data-buyer-vat = "<?php echo $result->buyer_VAT ?>"
                                    data-invoicetransactioncode-isexports = "<?php echo $result->zatcaInvoiceTransactionCode_isExports ?? 1 ?>"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Return', 'zatca') ?>">
                                    <span class="dashicons dashicons-dismiss"></span>
                                </a>
                                <!-- / Return-->
                            
                                <?php
                            }
                            
                            // view warning Btn for doc have zatca success response = 2-3:
                            if((int)$zatcaSuccessResponse === 2 || (int)$zatcaSuccessResponse === 3){?>
                            
                                <!--  view warning Btn -->
                                <button 
                                    type="button" 
                                    class="my-plugin-button btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#warning-<?php echo $result->documentNo; ?>" 
                                    data-bs-backdrop="false" 
                                    data-document-no="<?php echo $result->documentNo; ?>" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('View Warning MSG', 'zatca') ?>">
                                    <span class="dashicons dashicons-welcome-comments"></span>
                                </button>
                                <!-- / view warning Btn -->
                                 

                            <?php
                                // get warning msg:
                                $warningMsg = $wpdb->get_var($wpdb->prepare("SELECT zatcaErrorResponse FROM zatcaDocument Where documentNo = $result->documentNo"));
                                $modalWarningMsg = $warningMsg;
                                $doc_no = $result->documentNo;
                            }
                            ?>

                            

                            <?php
                                if((((int)$zatcaSuccessResponse === 1 || (int)$zatcaSuccessResponse === 2) && $zatcaInvoiceType == 1) || ((int)$zatcaSuccessResponse > 0 && $zatcaInvoiceType == 0)){
                            ?>
                                <a href="<?php echo plugin_dir_url(__FILE__) . 'documentA4.php?docno='. $result->documentNo ?>"
                                target="_blank"
                                id="create-pdf"
                                class="my-plugin-button btn-sm me-1"
                                title="<?php echo __("Print Document", "zatca") ?>">
                                <span class="dashicons dashicons-download"></span></a>
                            <?php } ?>
                            
                            <!--Reissued Invoice Card Link-->
                            <!--
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
                                                        <p class="card-text"><?php echo _e('Invoice No:', 'zatca') ?> <span class="badge bg-primary"><?php echo $reissuance->documentNo ?></span></p>
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
                                                        <p class="card-text"><?php echo _e('Invoice No:', 'zatca') ?> <span class="badge bg-primary"><?php echo $original->documentNo ?></span></p>
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
                            -->
                        </td>
                        <!-- view warning Modal -->
                        <div class="modal fade" id="warning-<?php echo $result->documentNo; ?>" tabindex="-1" aria-labelledby="warningLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" >
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="viewWarningLabel">Warning Message </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $modalWarningMsg; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / view warning Modal -->
                    </tr>

                    
                    <?php
                } 
            }?>


            
            

            <!-- view request data Modal -->
            <div class="modal fade" id="request" tabindex="-1" aria-labelledby="requestLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" >
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #f1e9e9;">
                            <h5 class="modal-title text-center fw-bold mx-3 mb-2" id="viewRequestLabel"><?php echo _e('Request Data', 'zatca') ?> </h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="background-color: #f1e9e9;">
                            <?php
                            $requestData = update_zatca($doc_no);
                            
                            // Decode JSON string to an array
                            $data = json_decode($requestData, true);
                            
                            ?>
                            <div class="row g-3">
                                <?php displayArray($data);?>
                            </div>

                            <?php
                            function displayArray($array) {
                                foreach ($array as $key => $val) {?>

                                    <?php if (in_array($key, array('seller', 'buyer', 'lineItems'))):?>
                                        <div class="col-md-9">
                                    <?php else:?>
                                        <div class="col-md-9">
                                    <?php endif;?>
                                        <label for="data" class="form-label m-2"><?php echo $key?></label>
                                        <?php if (is_array($val)):?>
                                            <?php displayArray($val);?>
                                        <?php else:?>
                                            <input type="text" class="form-control" id="data" value="<?php echo $val?>" disabled>
                                        <?php endif;?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / view request data Modal -->

        </tbody>
    </table>
    <!-- / Table Of Data -->

</div>

<div id="try-res" ></div>

<?php



?>


