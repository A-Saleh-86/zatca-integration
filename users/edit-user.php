<?php

$personNo = $_GET['personNo'];

global $wpdb;



// Table Name:
$table_name = 'zatcauser';

// Prepare the query with a condition on the DeviceId column using the %d placeholder
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE personNo = $personNo" ) );

// Check if there are results
if (!empty($results)) {
    foreach ($results as $result) {?>

        <div class="container">

            <!-- Back Btn -->
            <div class=" mx-auto mt-3">
                <a 
                    href="<?php echo admin_url('admin.php?page=zatca-users&action=view'); ?>" 
                    class=" my-plugin-button"
                    data-bs-toggle="tooltip" 
                    data-bs-placement="top" 
                    title="<?php echo _e('Back', 'zatca') ?>">
                    <span class="dashicons dashicons-undo"></span>
                </a>
            </div>
            <!-- / Back Btn -->

            <!-- Header -->
            <div class="col-xl-9 mx-auto mt-0">
                <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Edit User Details', 'zatca') ?></h5>
            </div>
            <!-- / Header -->

            <!-- Input Form -->
            <form class="form-horizontal main-form mt-1 custom-user" id="edit_user_form">

                <!-- Hidden input for personNo  -->
                <input type="hidden" name="personNo" value="<?php echo $result->personNo   ?>">
                <!-- / Hidden input for personNo  -->

                <!-- Get nickname -->
                <?php
                $admin_nickname = get_user_meta($result->personNo , 'nickname', true);
                ?>
                <!-- / Get nickname -->
                
                <!--  nickname field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label label-style"><?php echo _e('Nick Name:', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="nick-name" 
                                class="form-control"
                                value="<?php echo $admin_nickname ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  nickname field -->
                
                <!--  a Name field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label label-style"><?php echo _e('Arabic name:', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="arabic-name"
                                id="arabic-name"
                                class="form-control" 
                                autocomplete="off"
                                value="<?php echo $result->aName ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  a Name field -->
               
                <!--  e Name field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label label-style"><?php echo _e('English Name :', 'zatca') ?></label>
                    <div class="col-sm-6 col-md-5">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="english-name"
                                id="english-name" 
                                class="form-control" 
                                autocomplete="off"
                                value="<?php echo $result->eName ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  e Name field -->

                <!-- ZATCA_B2C_NotIssuedDocuments_isRemind field -->
                <div class="mb-3 row col-md-6">
                    <label class="col-sm-4 col-form-label text-wrap label-style" >
                        <?php echo _e('ZATCA_B2C_NotIssuedDocuments_isRemind :', 'zatca') ?>
                    </label>
                    <div class="col-sm-8 col-md-8">
                        <div class="form-group">
                            <input
                                class="form-check-input form-check-input-sm"
                                type="checkbox"
                                name="is-remind" 
                                id="is-remind"
                                class="form-control"
                                <?php
                                echo (isset($result->ZATCA_B2C_NotIssuedDocuments_isRemind) && $result->ZATCA_B2C_NotIssuedDocuments_isRemind==1) ? 'checked' : ''; 
                                ?>
                            />
                        </div>
                    </div>
                </div>
                <!-- / ZATCA_B2C_NotIssuedDocuments_isRemind field -->

                <!-- ZATCA_B2C_NotIssuedDocuments_RemindInterval field -->
                <div class="mb-3 row col-md-6">
                    <label class="col-sm-4 col-form-label label-style" >
                        <?php echo _e('ZATCA_B2C_NotIssuedDocuments_RemindInterval :', 'zatca') ?>
                    </label>
                    <div class="col-sm-8 col-md-8">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="remindInterval" 
                                id="remindInterval"
                                class="form-control" 
                                autocomplete="off"
                                value="<?php echo $result->ZATCA_B2C_NotIssuedDocumentsReminderInterval ?>"
                                <?php
                                echo (isset($result->ZATCA_B2C_NotIssuedDocuments_isRemind) && $result->ZATCA_B2C_NotIssuedDocuments_isRemind==1) ? '' : 'disabled'; 
                                ?>
                            />
                        </div>
                    </div>
                </div>
                <!-- / ZATCA_B2C_NotIssuedDocuments_RemindInterval field -->

                <!-- Submit Btn -->
                <div class="mb-3 row">
                    <div class="d-grid gap-2 col-8 md-flex justify-content-md-end">
                        <input type="submit" value="<?php echo _e('Update User Details', 'zatca') ?>" class="btn btn-primary " />
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

