<div class="container">
    
    <!-- Header -->
    <div class="col-xl-12 mx-auto m-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Logs', 'zatca') ?></h4>
    </div>
    <!-- / Header -->

    <!-- Filter Inputs -->
    <div class="d-flex flex-column mb-3">
        <div class="row justify-content-start align-items-end">

            <!-- From Date -->
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <div class="form-group grid-align">  
                    <label for="min"><?php echo _e('From-Date:', 'zatca')?></label>
                    <div class="input-group">  
                        <input type="text" id="min" name="min" class="form-control">
                        <button type="button" class="btn btn-secondary btn-sm" id="reset">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- / From Date -->

            <!-- To Date -->
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <div class="form-group grid-align">  
                    <label for="max"><?php echo __('To-Date', 'zatca')?></label>
                    <div class="input-group">  
                        <input type="text" id="max" name="max" class="form-control">
                        <button type="button" class="btn btn-secondary btn-sm" id="reset-max">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- / To Date -->

            <!-- Username -->
            <div class="col-12 col-md-3 mb-2 mb-md-0">
                <div class="form-group grid-align">  
                    <label for="username"><?php echo _e('user name', 'zatca')?></label>
                    <select class="form-select select2 p-2" name="username" id="username">
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From wp_users:
                            $table_users = $wpdb->prefix . 'users';
                            $users = $wpdb->get_results("SELECT * FROM $table_users");
                            foreach ($users as $user) {?>
                                <option value="<?php echo $user->user_login?>">
                                    <?php echo $user->user_nicename ?>
                                </option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <!-- / Username -->

            <!-- is Failuer -->
            <div class="col-12 col-md-3 mb-2 mb-md-0 p-1">
                <div class="form-group d-flex align-items-center">  
                    <div class="form-check d-flex align-items-center">
                        <input type="checkbox" id="failed" name="failed" class="form-check-input form-check-input-sm">
                        <label for="failed" class="form-check-label ml-2"><?php echo _e('Failed', 'zatca')?></label>
                    </div>
                </div>
            </div>
            <!-- / is Failuer -->

        </div>
    </div>
    <!-- / Filter Inputs -->

    <!-- Table Of View -->
    <div class="table-responsive">
        <table id="example" class="table table-striped " style="width:100%">
            <thead>
                <tr>
                    <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Log No', 'zatca') ?></th>
                    <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Date Time', 'zatca') ?></th>
                    <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('user name', 'zatca') ?></th>
                    <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('mac', 'zatca') ?></th>
                    <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('IP', 'zatca') ?></th>
                    <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Is Success', 'zatca') ?></th>
                    <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Operation', 'zatca') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                global $wpdb;

                // Query to retrieve data from zatca log:
                $resultes = $wpdb->get_results("SELECT * FROM zatcalog");

                // Check if there are results
                if ($resultes) {
                    foreach ($resultes as $result) {

                        ?>
                        <tr>
                            <td style="font-size: 0.8rem;"><?php echo $result->profilerLogNo ?></td>
                            <td style="font-size: 0.8rem;"><?php echo date('Y-m-d', strtotime($result->dateG)) ?></td>
                            <td style="font-size: 0.8rem;"><?php echo $result->login_personName ?></td>
                            <td style="font-size: 0.8rem;"><?php echo $result->macAddress ?></td>
                            <td style="font-size: 0.8rem;"><?php echo $result->IP ?></td>
                            <td style="font-size: 0.8rem;">
                                <?php
                                // check if isSuccess 1 => true - else => false:
                                if ($result->isSuccess == 1) {

                                    echo 'True';
                                } else {

                                    echo 'False';
                                }
                                ?>
                            </td>
                            <td style="font-size: 0.8rem;">
                                <?php

                                // check for operation value:
                                if ($result->operationType == 1) {

                                    echo 'Login / تسجيل دخول';
                                } elseif ($result->operationType == 2) {

                                    echo 'Create Document / إنشاء فاتورة';
                                } elseif ($result->operationType == 3) {

                                    echo 'Send To ZATCA / إرسال لهيئة الزكاة';
                                } elseif ($result->operationType == 4) {

                                    echo 'Print Document/ طباعة';
                                } else {
                                }
                                ?>
                            </td>

                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- / Table Of View -->

</div>
