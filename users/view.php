<div class="container">
    
    <!-- Header -->
    <div class="col-xl-12 mx-auto m-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Users', 'zatca') ?></h4>
    </div>
    <!-- / Header -->

    <!-- Table Of View -->
    <table id="example" class="table table-striped " style="width:100%">
        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('User No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name Arabic', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name English', 'zatca') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data from zatca log:
            $resultes = $wpdb->get_results("SELECT * FROM zatcalog");

            // Check if there are results
            // if ($resultes) {
            //     foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <td style="font-size: 0.8rem;">1</td>
                        <td style="font-size: 0.8rem;">احمد</td>
                        <td style="font-size: 0.8rem;">Ahmed</td>

                    </tr>
                    <?php
            //     }
            // }
            ?>
        </tbody>
    </table>
    <!-- / Table Of View -->

</div>