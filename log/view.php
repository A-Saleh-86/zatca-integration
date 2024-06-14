<div class="container">
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Logs', 'zatca') ?></h4>
    </div>
    
    <table id="ah" class="display" width="100%">

        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Log No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Date Time', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('User Name', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('MAC Address', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('IP', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Is Success', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Operation', 'zatca') ?></th>
            </tr>
        </thead>

        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data from zatca log:
            $resultes = $wpdb->get_results( "SELECT * FROM zatcalog");
            
            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <td style="font-size: 0.8rem;"><?php echo $result->profilerLogNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->dateG ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->login_personName ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->macAddress ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->IP ?></td>
                        <td style="font-size: 0.8rem;">
                            <?php 
                            // check if isSuccess 1 => true - else => false:
                            if($result->isSuccess == 1){

                                echo 'True';
                            }else{

                                echo 'False';
                            }
                            ?>
                        </td>
                        <td style="font-size: 0.8rem;">
                            <?php 
                            
                            // check for operation value:
                            if($result->operationType == 1){

                                echo 'Login / تسجيل دخول';

                            }elseif($result->operationType == 2){

                                echo 'Create Document / إنشاء فاتورة';
                                
                            }elseif($result->operationType == 3){

                                echo 'Send To ZATCA / إرسال لهيئة الزكاة';

                            }elseif($result->operationType == 4){

                                echo 'Print Document/ طباعة';

                            }else{



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

<?php


?>