<div class="container">
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Documents', 'zatca') ?></h4>
    </div>

    <!-- Add Btn -->
    <div class="col-xl-12 mx-auto mt-3">
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
    
    <!-- Table Of Data -->
    <table id="example" class="table table-striped" width="100%">

        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('System Invoice No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Delivery Date', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Maximum Delivery Date', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Type', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Payed', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Discount', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoice Net', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Subnet Total plus tax', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('VAT Category Code', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('VAT Category SubType Code', 'zatca') ?></th>
                <!-- <th class="text-center" style="font-size: 0.7rem;"><?php //echo _e('Exemption Reason', 'zatca') ?></th> -->
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('isNominal', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('isExports', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('isSummary', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Notes', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Action', 'zatca') ?></th>
            </tr>
        </thead>
    
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data
            $resultes = $wpdb->get_results( "SELECT * FROM zatcadocument");
            
            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {?>

                    <tr>
                        <td style="font-size: 0.8rem;"><?php echo $result->documentNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->invoiceNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->deliveryDate ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->gaztLatestDeliveryDate ?></td>
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
                        <td style="font-size: 0.8rem;"><?php echo $result->subNetTotalPlusTax ?></td>
                        <td style="font-size: 10px;">
                            <?php
                            $vatcategoryName = $wpdb->get_var($wpdb->prepare("SELECT aName FROM met_vatcategorycode WHERE VATCategoryCodeNo = $result->VATCategoryCodeNo")); 
                            echo $vatcategoryName;
                            ?>
                        </td>
                        <td style="font-size: 10px;">
                            <?php 
                            $vatcategorySubTypeName = $wpdb->get_var($wpdb->prepare("SELECT aName FROM met_vatcategorycodesubtype WHERE VATCategoryCodeSubTypeNo = $result->VATCategoryCodeSubTypeNo")); 
                            echo $vatcategorySubTypeName;
                            ?>
                        </td>
                        <!-- <td style="font-size: 0.8rem;"><?php //echo mb_substr($result->zatca_TaxExemptionReason, 0, 29, "UTF-8");  ?></td> -->
                        
                        <!-- Is Nominal -->
                        <td style="font-size: 0.8rem;">
                            <?php echo (isset($result->zatcaInvoiceTransactionCode_isNominal) && $result->zatcaInvoiceTransactionCode_isNominal==0) ? 'Yes' : 'No'; ?>
                        </td>
                        <!--/ Is Nominal -->

                        <!-- Is Exports -->
                        <td style="font-size: 0.8rem;">
                            <?php echo (isset($result->zatcaInvoiceTransactionCode_isExports) && $result->zatcaInvoiceTransactionCode_isExports==0) ? 'Yes' : 'No'; ?>
                        </td>
                        <!--/ Is Exports -->

                        <!-- Is Summary -->
                        <td style="font-size: 0.8rem;">
                            <?php echo (isset($result->zatcaInvoiceTransactionCode_isSummary) && $result->zatcaInvoiceTransactionCode_isSummary==0) ? 'Yes' : 'No'; ?>
                        </td>
                        <!--/ Is Summary -->

                        <td style="font-size: 0.8rem;">Notes</td>
                        <td style="font-size: 0.8rem;" class="">
                            <?php
                            // validation in zatcasuccessresponse - if 2  disable edit btn:
                            global $wpdb;

                            $zatcaSuccessResponse = $wpdb->get_var($wpdb->prepare("SELECT zatcaSuccessResponse 
                                                                                        FROM zatcadocument 
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
                                                                                    FROM zatcadocument 
                                                                                    WHERE documentNo =  $result->documentNo"));
                            
                            $zatcaInvoiceType = $wpdb->get_var($wpdb->prepare("SELECT zatcaInvoiceType 
                                                                                    FROM zatcadocument 
                                                                                    WHERE documentNo =  $result->documentNo"));


                                
                            // Check If zatcaSuccessResponse = 0 to show for all documents:
                            if ($zatcaSuccessResponse != NULL && (int)$zatcaSuccessResponse === 0){
                                
                                // Check If docuemnt B2B will redirect to clear():
                                if($zatcaInvoiceType == 1){?>

                                    <!-- Send To Zatca Btn [ clear() ] -->
                                    <a 
                                        href="#" 
                                        class="my-plugin-button btn-sm me-1" 
                                        id="send-zatca-clear" 
                                        data-doc-no = "<?php echo $result->documentNo ?>" 
                                        data-bs-toggle="tooltip" 
                                        data-bs-placement="top" 
                                        title="<?php echo _e('Send To Zatca', 'zatca') ?>">
                                        <span class="dashicons dashicons-cloud-upload"></span>
                                    </a>
                                    <!-- / Send To Zatca Btn [ clear() ] -->
                                    <?php
                            
                                }else{ //if B2C will redirect to Report(): ?>

                                    <?php
                                    $zatcaCompanySatge = $wpdb->get_var($wpdb->prepare("SELECT zc.zatcaStage 
                                    FROM zatcadocument zd, zatcacompany zc 
                                    WHERE zd.vendorId = zc.VendorId AND zd.documentNo =  $result->documentNo"));
                                    ?>
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

                            // check for if zatca success reponse 1-2 - show download xml:
                            if((int)$zatcaSuccessResponse == 1 || (int)$zatcaSuccessResponse == 2){?>

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
                            if($zatcaSuccessResponse != NULL && (int)$zatcaSuccessResponse === 3){?>
                                
                                <!-- Reissue -->
                                <a 
                                    href="#" 
                                    class="my-plugin-button btn-sm me-1" 
                                    id="send-zatca-reissue" 
                                    data-doc-no = "<?php echo $result->documentNo ?>" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Reissue', 'zatca') ?>">
                                    <span class="dashicons dashicons-controls-repeat"></span>
                                </a>
                                <!-- / Reissue-->
                            
                                <?php
                            }
                            
                            // view warning Btn for doc have zatca success response = 2-3:
                            if((int)$zatcaSuccessResponse === 2 || (int)$zatcaSuccessResponse === 3){?>
                            
                                <!--  view warning Btn -->
                                <button 
                                    type="button" 
                                    class="my-plugin-button btn-sm me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#warning" 
                                    data-bs-backdrop="false" 
                                    data-document-no="<?php echo $result->documentNo; ?>" 
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('View Warning MSG', 'zatca') ?>">
                                    <span class="dashicons dashicons-welcome-comments"></span>
                                </button>
                                <!-- / view warning Btn -->

                                <?php
                            }

                            ?>

                            <a href="<?php echo plugin_dir_url(__FILE__) . 'pdf-document.php?doc-no='. $result->documentNo ?>"
                            target="_blank"
                            id="create-pdf"
                            class="my-plugin-button btn-sm me-1"
                             data-doc-no="<?php echo $result->documentNo ?>"
                             title="Print Document">
                                Print <span class="dashicons dashicons-external"></span></a>

                            <?php

                            // get warning msg:
                            $warningMsg = $wpdb->get_var($wpdb->prepare("SELECT zatcaErrorResponse FROM zatcadocument Where documentNo = $result->documentNo"));
                            $modalWarningMsg = $warningMsg;
                            $doc_no = $result->documentNo;
                            ?>
                        </td>
                    </tr>

                    <?php
                }
            }?>

            <!-- view warning Modal -->
            <div class="modal fade" id="warning" tabindex="-1" aria-labelledby="warningLabel" aria-hidden="true">
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


