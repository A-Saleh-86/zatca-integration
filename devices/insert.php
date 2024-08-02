<?php
include_once dirname(dirname(__FILE__)) . '/zatca.php';
?>
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
        <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Add New Device', 'zatca') ?></h5>
    </div>
    <!-- / Header -->

    <!-- Input Form -->
    <form class="form-horizontal main-form mt-1" id="device_insert_form">

        <!--  deviceCSID field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Cryptographic Stamp ID:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="device-csid" 
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Cryptographic Stamp ID', 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  deviceCSID field -->

        <!--  CsID_ExpiryDate field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Expiry Date:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="date"
                        name="csid-ex-date" 
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Expiry Date', 'zatca') ?>"
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
                        autocomplete="off"
                        placeholder="<?php echo _e('Token Data', 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  tokenData field -->

        <!-- Device Status field -->
        <div class="mb-3 row col-md-6">
            <label class="col-sm-4 col-form-label text-wrap label-style" >
                <?php echo _e('Device Status :', 'zatca') ?>
            </label>
            <div class="col-sm-8 col-md-8">
                <div class="form-group">
                    <input type="hidden" name="deviceStatus" value="1"> <!-- Hidden input for unchecked state -->  
                    <input
                        class="form-check-input form-check-input-sm"
                        type="checkbox"
                        name="deviceStatus"
                        class="form-control"
                        value="0"
                    />
                </div>
            </div>
        </div>
        <!-- / Device Status field -->

        <!-- Submit Btn -->
        <div class="mb-3 row">
            <div class="d-grid gap-2 col-8 md-flex justify-content-md-end">
                <input type="submit" value="<?php echo _e('Insert New Device', 'zatca') ?>" class="my-plugin-button" />
            </div>
        </div>
        <!-- / Submit Btn -->
        
    </form>
    <!-- / Input Form -->
     
</div>
