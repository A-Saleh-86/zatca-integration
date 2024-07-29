<?php
include_once dirname(dirname(__FILE__)) . '/zatca.php';
?>

<div class="container">
    
    <!-- Back Btn -->
    <div class=" mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-users&action=view'); ?>" 
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
        <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Add New User', 'zatca') ?></h5>
    </div>
    <!-- / Header -->

    <!-- Input Form -->
    <form class="form-horizontal main-form mt-1 custom-user" id="user_form">


        <!--  personNo field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label label-style"><?php echo _e('User No:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <select class="form-select select2"  name="person-no" id="person-no">
                        <option value="">...</option>
                        <?php 
                        global $wpdb;
                        
                        $table_usermeta = $wpdb->prefix . 'usermeta';
                        $meta_key_capabilities = $wpdb->prefix .'capabilities'; 
                        $meta_value_admin = '%administrator%';
                        $meta_key_capabilities_escaped = $wpdb->_real_escape($meta_key_capabilities);
                        $admins = $wpdb->get_results("SELECT * FROM $table_usermeta WHERE meta_key = '$meta_key_capabilities_escaped' AND meta_value LIKE '$meta_value_admin'");
                        foreach($admins as $admin){
                            $user_id = $admin->user_id;
                            $admin_nickname = get_user_meta($user_id, 'nickname', true);?>
                                <option value="<?php echo $user_id ?>"><?php echo $admin_nickname ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <!-- /  personNo field -->

        <!--  a Name field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label label-style"><?php echo _e('Arabic Name:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="arabic-name"
                        id="arabic-name"
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Arabic Name', 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  a Name field -->

        <!--  e Name field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label label-style"><?php echo _e('English Name:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="form-group">
                    <input 
                        type="text"
                        name="english-name"
                        id="english-name" 
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('English Name', 'zatca') ?>"
                    />
                </div>
            </div>
        </div>
        <!-- /  e Name field -->

        <!-- ZATCA_B2C_NotIssuedDocuments_isRemind field -->
        <div class="mb-3 row col-md-6">
            <label class="col-sm-4 col-form-label text-wrap label-style" >
                <?php echo _e('Remind with Late B2C Invoices:', 'zatca') ?>
            </label>
            <div class="col-sm-8 col-md-8">
                <div class="form-group">
                    <input
                        class="form-check-input form-check-input-sm"
                        type="checkbox"
                        name="is-remind" 
                        id="is-remind"
                        class="form-control"
                    />
                </div>
            </div>
        </div>
        <!-- / ZATCA_B2C_NotIssuedDocuments_isRemind field -->

        <!-- ZATCA_B2C_NotIssuedDocuments_RemindInterval field -->
        <div class="mb-3 row col-md-6">
            <label class="col-sm-4 col-form-label label-style" >
                <?php echo _e('Remind with Late B2C Invoices before ZATCA grace period ending with ( number box ) hours:', 'zatca') ?>
            </label>
            <div class="col-sm-8 col-md-8">
                <div class="form-group">
                    <div class="input-group">
                        <input 
                            type="text"
                            name="remindInterval" 
                            id="remindInterval"
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Remind Interval', 'zatca')?>"
                            disabled
                        />
                        <span class="d-inline mx-2"><?php echo _e('Hours', 'zatca');?></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- / ZATCA_B2C_NotIssuedDocuments_RemindInterval field -->


        <!-- Submit Btn -->
        <div class="mb-3 row">
            <div class="d-grid gap-2 col-8 md-flex justify-content-md-end">
                <input type="submit" value="<?php echo _e('Insert New User', 'zatca') ?>" class="my-plugin-button" />
            </div>
        </div>
        <!-- / Submit Btn -->
        
    </form>
    <!-- / Input Form -->
     
</div>
