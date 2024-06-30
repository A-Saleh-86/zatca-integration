<?php
// Get the current language code (e.g., 'en' for English, 'ar' for Arabic)
$current_lang = get_locale();
// Define text strings based on language
$from_date_label = ($current_lang == 'en') ? 'From Date' : 'من تاريخ';
$to_date_label = ($current_lang == 'en') ? 'To Date' : 'الى تاريخ';
$Branch_label = ($current_lang == 'en') ? 'Branch' : 'الفرع';
$check_counter_btn = ($current_lang == 'en') ? 'Check Counter Gap' : 'فحص خلل الترتيب';
$check_hash_btn = ($current_lang == 'en') ? 'Check Hash Gap' : 'فحصل خلل الهاش';

?>
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
            <label for="from_date"><?php echo esc_html($from_date_label); ?> </label>
            <input type="date" name="from_date" id="from_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="to_date"><?php echo esc_html($to_date_label); ?> </label>
            <input type="date" name="to_date" id="to_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="Branch"> <?php echo esc_html($Branch_label); ?> </label>
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
            <button class="btn btn-sm" name="check_counter_gap" type="submit"><?php echo esc_html($check_counter_btn); ?> </button>
            <button class="btn btn-sm" name="check_hash_gap" type="submit"><?php echo esc_html($check_hash_btn); ?> </button>
        </div>
    </form>
</body>
</html>