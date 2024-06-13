<!-- table Of View -->
<?php

?>


<div class="container" >
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Devices', 'zatca') ?></h4>
    </div>
    <!-- Add Btn -->
    <div class="col-xl-12 mx-auto mt-3">
        <a href="<?php echo admin_url('admin.php?page=zatca-devices&action=insert-device')  ?>" class="btn my-plugin-button add-btn">
            <span class="dashicons dashicons-insert"></span>
        </a>
    </div>
    <!-- / Add Btn -->


    <table id="ah" class="display" width="100%">
        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Device No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('DeviceCSID', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('CsID_ExpiryDate', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('tokenData', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Action', 'zatca') ?></th>
            </tr>
        </thead>
    
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data
            $resultes = $wpdb->get_results( "SELECT * FROM zatcadevice");
            
            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <td class="text-center"><?php echo $result->deviceNo ?></td>
                        <td class="text-center"><?php echo $result->deviceCSID ?></td>
                        <td class="text-center"><?php echo $result->CsID_ExpiryDate ?></td>
                        <td class="text-center"><?php echo $result->tokenData ?></td>
                        
                        <td class=" text-center">
                            <!-- Edit Btn -->
                            <a href="<?php echo admin_url('admin.php?page=zatca-devices&action=edit-device&id='.$result->ID.'')  ?>" class="my-plugin-button btn-sm me-1">
                                <span class="dashicons dashicons-edit"></span>
                            </a>
                            <!-- / Edit Btn -->
    
                            <!-- Delete Btn -->
                            <a href="<?php echo admin_url('admin.php?page=zatca-devices&action=delete&id='.$result->ID.'')  ?>" id="delete" class="my-plugin-button btn-sm me-1 confirm">
                                <span class="dashicons dashicons-trash"></span>
                            </a>
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

