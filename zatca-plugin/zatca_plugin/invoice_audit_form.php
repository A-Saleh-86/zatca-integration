<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampering Detector</title>
</head>
<body>
    <form action="" method="post">
        <div class="form-group">
            <label for="from_date">From Date </label>
            <input type="date" name="from_date" id="from_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="to_date">To Date </label>
            <input type="date" name="to_date" id="to_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="Branch">Select Branch </label>
            <select name="BuildingNo" id="Branch" class="form-control">
                <option value="">Select Branch</option>
                <?php
                    global $wpdb;
                    
                    // Custom SQL query to retrieve branches from wp_zatcaBranch table
                    $query = "SELECT BuildingNo FROM {$wpdb->zatcaBranch}";

                    // Fetch results from the database
                    $results = $wpdb->get_results($query);

                    // Loop through each user to populate the select options
                    if ($results) {
                        foreach ($results as $branch) {
                            // Get the BuildingNo (Branch No)
                            $Building_No = $branch->BuildingNo;

                            // Output an option for each branch
                            echo '<option value="' . esc_attr($branch->BuildingNo) . '">' . esc_html($Building_No) . '</option>';
                            
                        
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-sm" name="check_counter_gap" type="submit">Check Counter Gap </button>
            <button class="btn btn-sm" name="check_hash_gap" type="submit">Check Hash Gap </button>
        </div>
    </form>
</body>
</html>