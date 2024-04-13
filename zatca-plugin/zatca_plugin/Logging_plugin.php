<?php
/*
Plugin Name:  zatca logging  Plugin
Description: Adds ZATCA functionality to WooCommerce.
Version: 1.0

*/

// Shortcode callback function
function zatca_logging_form_shortcode() {
 ob_start(); 	
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZATCA Logging Form</title>
</head>
<body>
    <h2>ZATCA Logging Form</h2>
    <form action="" method="post">
		
	<div class="form-group">
        <label for="dateG" class="form-label">Date and Time:</label>
        <input type="datetime-local"  class="form-control"  id="dateG" name="dateG" required><br><br>
	</div>
	<div class="form-group">
   		  <label for="userId" class="form-label">User Name:</label>
			<select class="form-select" id="userId" name="userId">
    <?php
    global $wpdb;
    
    // Custom SQL query to retrieve user logins from wp_users table
    $query = "SELECT ID, user_login FROM {$wpdb->users}";

    // Fetch results from the database
    $results = $wpdb->get_results($query);

    // Loop through each user to populate the select options
    if ($results) {
        foreach ($results as $user) {
            // Get the user login (username)
            $user_login = $user->user_login;

            // Output an option for each user
            echo '<option value="' . esc_attr($user->ID) . '">' . esc_html($user_login) . '</option>';
			
         
        }
    }
    ?>
</select>
<br><br>

	</div>
	<div class="form-group">
        <label for="isSuccess" class="form-label">Is Success:</label>
        <select id="isSuccess" class="form-control"  name="isSuccess" required>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select><br><br>
	</div>
	<div class="form-group">
        <label for="operationType" class="form-label">Operation Type:</label>
        <select id="operationType" class="form-control"  name="operationType" required>
            <option value="login">Login</option>
            <option value="create document">Create Document</option>
            <option value="send document to ZATCA">Send Document to ZATCA</option>
            <option value="print document">Print Document</option>
        </select><br><br>
	</div>
	<div class="form-group">
        <label for="deviceIP" class="form-label">Device IP:</label>
        <input type="text" class="form-control"  id="deviceIP" name="deviceIP"><br><br>
	</div>
	<div class="form-group">
        <label for="macAddress" class="form-label">Mac Address:</label>
        <input type="text" class="form-control"  id="macAddress" name="macAddress"><br><br>
	</div>
        <input type="submit" class="btn btn-primary"  name="submit" value="Submit">
    </form>

	
</body>
</html>

<?php
	 return ob_get_clean();
}

function zatca_logging_form() {
    return zatca_logging_form_shortcode(); // Call the function and return its output
}
add_shortcode('zatca_logging_form_show', 'zatca_logging_form');

require_once(plugin_dir_path(__FILE__) . 'submit.php');

?>

