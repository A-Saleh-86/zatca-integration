<?php echo 'test saleh'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampering Detector</title>
</head>
<body>
<?php echo 'asd'; ?>
    <form id="myForm" method="post" class="container">
        <div class="row">
            <div class="form-group col-md-6">
                <label for="from_date"><?php echo _e('From Date', 'zatca') ?> </label>
                <input type="date" name="from_date" id="from_date" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
                <label for="to_date"><?php echo _e('To Date', 'zatca') ?> </label>
                <input type="date" name="to_date" id="to_date" class="form-control" required>
            </div>
            <div class="form-group col-md-12">
                <select name="BuildingNo" id="Branch" class="form-control" hidden>
                    <option value=""><?php echo _e('Branch', 'zatca') ?></option>
                    <?php
                        global $wpdb;
                        
                        // Custom SQL query to retrieve branches from wp_zatcaBranch table
                        $query = "SELECT buildingNo FROM zatcaBranch";

                        // Fetch results from the database
                        $results = $wpdb->get_results($query);

                        // Loop through each user to populate the select options
                        if ($results) {
                            foreach ($results as $branch) {
                                // Get the BuildingNo (Branch No)
                                $Building_No = $branch->buildingNo;

                                // Output an option for each branch
                                echo '<option value="' . esc_attr($branch->buildingNo) . '" selected>' . esc_html($Building_No) . '</option>';
                                
                            
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="row m-3">
            <div class="form-group col-md-12 m-3" style="display: flex;justify-content: center;">
                <button class="btn btn-sm btn-primary m-2" id="check_counter_gap"><?php echo _e('Check Counter Gap', 'zatca') ?> </button>
                <button class="btn btn-sm btn-primary m-2" id="check_hash_gap"><?php echo _e('Check Hash Gap', 'zatca') ?> </button>
            </div>
        </div>
    </form>
    <div id="response"></div> <!-- This div will display the response -->
</body>
</html>