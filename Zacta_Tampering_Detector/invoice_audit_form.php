<?php
// Get the current language code (e.g., 'en' for English, 'ar' for Arabic)
$current_lang = get_locale();
// Define text strings based on language
$from_date_label = ($current_lang == 'en') ? 'من تاريخ' : 'From Date';
$to_date_label = ($current_lang == 'en') ? 'الى تاريخ' : 'To Date';
$Branch_label = ($current_lang == 'en') ? 'الفرع' : 'Branch';
$check_counter_btn = ($current_lang == 'en') ? 'فحص خلل الترتيب' : 'Check Counter Gap';
$check_hash_btn = ($current_lang == 'en') ? 'فحصل خلل الهاش' : 'Check Hash Gap';

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampering Detector</title>
</head>
<body>
    <form action="" method="post" class="container">
        <div class="row">
        <div class="form-group col-md-6">
            <label for="from_date"><?php echo esc_html($from_date_label); ?> </label>
            <input type="date" name="from_date" id="from_date" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label for="to_date"><?php echo esc_html($to_date_label); ?> </label>
            <input type="date" name="to_date" id="to_date" class="form-control" required>
        </div>
        </div>
        <div class="form-group">
            <label for="Branch"> <?php echo esc_html($Branch_label); ?> </label>
            <select name="BuildingNo" id="Branch" class="form-control">
                <option value=""><?php echo esc_html($Branch_label); ?></option>
                <?php
                    global $wpdb;
                    
                    // Custom SQL query to retrieve branches from wp_zatcaBranch table
                    $query = "SELECT buildingNo FROM zatcabranch";

                    // Fetch results from the database
                    $results = $wpdb->get_results($query);

                    // Loop through each user to populate the select options
                    if ($results) {
                        foreach ($results as $branch) {
                            // Get the BuildingNo (Branch No)
                            $Building_No = $branch->buildingNo;

                            // Output an option for each branch
                            echo '<option value="' . esc_attr($branch->buildingNo) . '">' . esc_html($Building_No) . '</option>';
                            
                        
                        }
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <button class="btn btn-sm btn-primary m-2" name="check_counter_gap" type="submit"><?php echo esc_html($check_counter_btn); ?> </button>
            <button class="btn btn-sm btn-primary m-2" name="check_hash_gap" type="submit"><?php echo esc_html($check_hash_btn); ?> </button>
        </div>
    </form>
</body>
</html>