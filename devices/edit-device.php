<?php

$id = $_GET['id'];

global $wpdb;



// Table Name:
$table_name = 'zatcadevice';

// Prepare the query with a condition on the DeviceId column using the %d placeholder
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE ID = %d", $id ) );

// Check if there are results
if (!empty($results)) {
    foreach ($results as $result) {

        ?>
        <div class="container">

            <div class=" mx-auto mt-3">
                <a href="<?php echo admin_url('admin.php?page=zatca-devices&action=view'); ?>" class="btn btn-secondary ">
                    <span class="dashicons dashicons-undo"></span>
                </a>
            </div>
            <div class="col-xl-9 mx-auto mt-0">
                <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Edit Device Details', 'zatca') ?></h5>
            </div>
            <form class="form-horizontal main-form mt-1" id="edit-form-device__form">

                <!-- Hidden input for Vendor Id -->
                <input type="hidden" name="id" value="<?php echo $result->ID   ?>">
                <!-- / Hidden input for Vendor Id -->

                <!--  Device No field -->
                <div class="mb-3 row col-mid-6">
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
                
                <!--  deviceCSID field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Device CSID:', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="device-csid" 
                                class="form-control"
                                value="<?php echo $result->deviceCSID ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  deviceCSID field -->

                <!--  CsID_ExpiryDate field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('CsID_Expiry Date:', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="datetime-local"
                                name="csid-ex-date" 
                                class="form-control"
                                value="<?php echo $result->CsID_ExpiryDate ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  CsID_ExpiryDate field -->

                <!--  tokenData field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Token Data:', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="token-data" 
                                class="form-control"
                                value="<?php echo $result->tokenData ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  tokenData field -->


                <!-- Submit Btn -->
                <div class="mb-3 row">
                    <div class="d-grid gap-2 col-8 md-flex justify-content-md-end">
                        <input type="submit" value="<?php echo _e('Update Device Details', 'zatca') ?>" class="btn btn-primary " />
                    </div>
                </div>
                <!-- / Submit Btn -->

            </form>
        </div>
        <?php
    }
} else {
    echo "No data found.";
}
?>

