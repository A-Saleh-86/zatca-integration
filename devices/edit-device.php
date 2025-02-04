<?php

$deviceNo = $_GET['deviceNo'];

global $wpdb;



// Table Name:
$table_name = 'zatcaDevice';

// Prepare the query with a condition on the deviceNo: 
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE deviceNo = $deviceNo" ) );

// Check if there are results
if (!empty($results)) {
    foreach ($results as $result) {?>

        <div class="container">

            <!-- Back Btn -->
            <div class=" mx-auto mt-3">
                <a 
                    href="<?php echo admin_url('admin.php?page=zatca-devices&action=view'); ?>" 
                    class="btn my-plugin-button"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="<?php echo _e('Back', 'zatca') ?>">
                    <span class="dashicons dashicons-undo"></span>
                </a>
            </div>
            <!-- / Back Btn -->

            <!-- Header -->
            <div class="col-xl-9 mx-auto mt-0">
                <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Edit Device Details', 'zatca') ?></h5>
            </div>
            <!-- / Header -->

            <!-- Input Form -->
            <form class="form-horizontal main-form mt-1" id="device_edit_form">

                <!-- Hidden Device No. -->
                <input type="hidden" name="device_no_id" value="<?php echo $result->deviceNo ?>">
                <!-- / Hidden Device No. -->
                
                <!--  Device No field -->
                <div class="mb-3 row col-mid-6 deviceNo_hidden">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Device No.:', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="device-no" 
                                class="form-control"
                                value="<?php echo $result->deviceNo ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  Device No field -->

                <div class="row">
                    <!--  deviceCSID field -->
                    <div class="mb-3 col-md-6">
                        <div class="form-group">
                            <label class="col-form-label"><?php echo _e('Cryptographic Stamp ID:', 'zatca') ?></label>
                            <div class="">
                                <div class="form-group">
                                    <input 
                                        type="text"
                                        name="device-csid" 
                                        class="form-control" 
                                        autocomplete="off"
                                        value="<?php echo $result->deviceCSID ?>"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /  deviceCSID field -->

                    <!--  CsID_ExpiryDate field -->
                    <div class="mb-3 col-md-6">
                        <div class="form-group">
                            <label class="col-form-label"><?php echo _e('Expiry Date:', 'zatca') ?></label>
                            <div class="">
                                <div class="form-group">
                                    <input 
                                        type="datetime-local"
                                        name="csid-ex-date" 
                                        class="form-control" 
                                        autocomplete="off"
                                        value="<?php echo $result->CsID_ExpiryDate ?>"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /  CsID_ExpiryDate field -->

                    <!--  tokenData field -->
                    <div class="mb-3 col-md-12">
                        <div class="form-group">
                            <label class="col-form-label"><?php echo _e('Token Data:', 'zatca') ?></label>
                            <div class="">
                                <div class="form-group">
                                    <input 
                                        type="text"
                                        name="token-data" 
                                        class="form-control" 
                                        autocomplete="off"
                                        value="<?php echo $result->tokenData ?>"
                                        required
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /  tokenData field -->

                    <!-- Device Status field -->
                    <div class="mb-3 col-md-12">
                        <div class="form-group">
                            <label class="col-form-label text-wrap label-style" >
                                <?php echo _e('Device Status :', 'zatca') ?>
                            </label>
                            <div class="">
                                <div class="form-group">
                                    <input type="hidden" name="deviceStatus" value="1"> <!-- Hidden input for unchecked state -->  
                                    <input
                                        class="form-check-input form-check-input-sm"
                                        type="checkbox"
                                        name="deviceStatus"
                                        class="form-control"
                                        value="0"
                                        <?php
                                        echo (isset($result->deviceStatus) && $result->deviceStatus==0) ? 'checked' : ''; 
                                        ?>
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Device Status field -->

                </div>
                

                <!-- Submit Btn -->
                <div class="mb-3 row">
                    <div class="d-grid gap-2 col-12 md-flex justify-content-md-end" 
                    style="display: flex!important;
                    justify-content: center!important;
                    align-items: center!important;">
                        <input type="submit" value="<?php echo _e('Edit Device Details', 'zatca') ?>" class="my-plugin-button" />
                    </div>
                </div>
                <!-- / Submit Btn -->

            </form>
            <!-- / input Form -->

        </div>
        <?php
    }
} else {
    echo "No data found.";
}
?>

