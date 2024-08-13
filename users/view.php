
<div class="container">

    
    <!-- Header -->
    <div class="col-xl-12 mx-auto m-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Users', 'zatca') ?></h4>
    </div>
    <!-- / Header -->

    <!-- Add Btn -->
    <div class="col-xl-12 mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-users&action=insert')  ?>" 
            class="btn my-plugin-button add-btn"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="<?php echo _e('Insert New User', 'zatca') ?>">
            <span class="dashicons dashicons-insert"></span>
        </a>
    </div>
    <!-- / Add Btn -->

    <!-- Table Of View -->
    <table id="example" class="table table-striped " style="width:100%">
        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('User No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name Arabic', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name English', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Remind with Late B2C Invoices', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Remind with B2C before ZATCA grace period', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Actions', 'zatca') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data from zatca log:
            $resultes = $wpdb->get_results("SELECT * FROM zatcaUser");

            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <td style="font-size: 0.8rem;"><?php echo $result->personNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->aName ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->eName ?></td>
                        <td style="font-size: 0.8rem;" class="text-center">
                            <?php 
                            // echo $result->eName;
                            echo (isset($result->ZATCA_B2C_NotIssuedDocuments_isRemind) && $result->ZATCA_B2C_NotIssuedDocuments_isRemind==1) ? 'Yes' : 'No'; 
                            ?>
                        </td>
                        <td style="font-size: 0.8rem;" class="text-center"><?php echo $result->ZATCA_B2C_NotIssuedDocumentsReminderInterval ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                
                                <!-- Edit Btn -->
                                <a 
                                    href="<?php echo admin_url('admin.php?page=zatca-users&action=edit-user&personNo='.$result->personNo.'') ?>" 
                                    class="my-plugin-button btn-sm me-1"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Edit User', 'zatca') ?>">
                                    <span class="dashicons dashicons-edit"></span>
                                </a>
                                <!-- / Edit Btn -->

                                <!-- Delete Btn -->
                                <!-- <a 
                                    href="<?php //echo admin_url('admin.php?page=zatca-users&action=delete&personNo='.$result->personNo.'') ?>" 
                                    id="delete" 
                                    class="my-plugin-button btn-sm me-1 confirm"
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php //echo _e('Delete User', 'zatca') ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                </a> -->
                                <!-- /Delete Btn -->

                                <!-- Delete Btn -->
                                <button 
                                    type='button' 
                                    class='btn my-plugin-button me-1'
                                    id="delete_user"
                                    data-user-no='<?php echo $result->personNo ?>'
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Delete User', 'zatca') ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                </button>
                                <!-- /Delete Btn -->

                            </div>
                        </td>

                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <!-- / Table Of View -->

</div>