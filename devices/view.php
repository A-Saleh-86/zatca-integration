
<div class="container" >
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Devices', 'zatca') ?></h4>
    </div>

    <!-- Add Btn -->
    <div class="col-xl-12 mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-devices&action=insert-device')  ?>" 
            class="btn my-plugin-button add-btn"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="<?php echo _e('Insert New Device', 'zatca') ?>">
            <span class="dashicons dashicons-insert"></span>
        </a>
    </div>
    <!-- / Add Btn -->


    <table id="example" class="table table-striped" width="100%">
        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Device No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Cryptographic Stamp ID', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Expiry Date', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Token Data', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Device Status', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Action', 'zatca') ?></th>
            </tr>
        </thead>
    
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data
            $resultes = $wpdb->get_results( "SELECT * FROM zatcaDevice");
            
            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <td class="text-center"><?php echo $result->deviceNo ?></td>
                        <td class="text-center"><?php echo $result->deviceCSID ?></td>
                        <td class="text-center"><?php echo $result->CsID_ExpiryDate ?></td>
                        <td class="text-center"><?php echo $result->tokenData ?></td>
                        <td class="text-center">
                            <?php 
                            echo (isset($result->deviceStatus) && $result->deviceStatus==0) ? _e('Active', 'zatca') : _e('Inactive', 'zatca'); 
                            ?>
                        </td>
                        
                        <td class=" text-center">

                            <!-- Edit Btn -->
                            <a 
                                href="<?php echo admin_url('admin.php?page=zatca-devices&action=edit-device&deviceNo='.$result->deviceNo.'')  ?>" 
                                class="my-plugin-button btn-sm me-1"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="<?php echo _e('Edit Device', 'zatca') ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </a>
                            <!-- / Edit Btn -->
    
                            
                            <!-- Delete Btn -->
                            <button 
                                type='button' 
                                class='btn my-plugin-button me-1'
                                id="delete_device"
                                data-device-no='<?php echo $result->deviceNo ?>'
                                data-bs-placement="top" 
                                title="<?php echo _e('Delete Device', 'zatca') ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                            <!-- /Delete Btn -->

                        </td>
    
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

