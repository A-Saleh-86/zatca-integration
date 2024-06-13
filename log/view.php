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

            // Query to retrieve data
            $resultes = $wpdb->get_results( "SELECT * FROM zatcacustomer");
            
            // Check if there are results
            // if ($resultes) {
                // foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <!-- <td style="font-size: 0.8rem;"><?php //echo $result->ID ?></td> -->
                        <td style="font-size: 0.8rem;">1</td>
                        <td style="font-size: 0.8rem;">13-june-2024</td>
                        <td style="font-size: 0.8rem;">Ahmed</td>
                        <td style="font-size: 0.8rem;">00:1A:2B:3C:4D:5E</td>
                        <td style="font-size: 0.8rem;">192.168.1.255</td>
                        <td style="font-size: 0.8rem;">True</td>
                        <td style="font-size: 0.8rem;">Login / تسجيل دخول</td>

                    </tr>
                    <?php
                // }
            // }
            ?>
        </tbody>
    </table>
</div>