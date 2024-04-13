<?php
if (!defined('ABSPATH')) {
    exit;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    if (isset($_POST['submit'])) {
        // Retrieve form data
        $company_name = $_POST['company_name'];
        $vat_id = $_POST['vat_id'];
        $group_vat_number = $_POST['group_vat_number'];
        $second_business_id_type = $_POST['second_business_id_type'];
        $second_business_id = $_POST['second_business_id'];
        $vat_category = $_POST['vat_category'];
        $vat_category_subcode = $_POST['vat_category_subcode'];
        $zatca_stage = $_POST['zatca_stage'];
        $apartment_no = $_POST['apartment_no'];
        $postal_code = $_POST['postal_code'];
        $street_name = $_POST['street_name'];
        $district_name = $_POST['district_name'];
        $city_name = $_POST['city_name'];
        $country_name = $_POST['country_name'];
        $country_no = $_POST['country_no'];
        $csid = $_POST['csid'];
        $uuid = $_POST['uuid'];
        $token_data = $_POST['token_data'];
        $csid_expiry_date = $_POST['csid_expiry_date'];
        $zatca_b2c_sending_interval_type = $_POST['zatca_b2c_sending_interval_type'];

        // Insert data into the database
        global $wpdb;
        $table_name = $wpdb->prefix . 'zatca_settings';

        $result = $wpdb->insert(
            $table_name,
            array(
                'companyName' => $company_name,
                'VATID' => $vat_id,
                'groupVATNumber' => $group_vat_number,
                'secondBusinessIDType' => $second_business_id_type,
                'secondBusinessID' => $second_business_id,
                'VATCategory' => $vat_category,
                'VATCategorySubCode' => $vat_category_subcode,
                'zatcaStage' => $zatca_stage,
                'apartmentNo' => $apartment_no,
                'postalCode' => $postal_code,
                'streetName' => $street_name,
                'districtName' => $district_name,
                'cityName' => $city_name,
                'countryName' => $country_name,
                'countryNo' => $country_no,
                'CSID' => $csid,
                'UUID' => $uuid,
                'tokenData' => $token_data,
                'CSID_expirydate' => $csid_expiry_date,
                'ZATCA_B2C_SendingIntervalType' => $zatca_b2c_sending_interval_type
            )
        );

        if ($result === false) {
        // Error handling
        echo '<p>Error inserting data: ' . $wpdb->last_error . '</p>';
    } else {
        echo '<p>Data submitted successfully!</p>';
        // Redirect after displaying success message
        echo '<script>window.location.href="https://appyfruits.com/wp-admin/admin.php?page=wc-settings&tab=zatca_settings";</script>';
        exit;
    }
    }
}
?>

<div class="wrap">
    <h2>ZATCA Settings</h2>

    <form method="post" action="">
        <input type="hidden" name="action" value="save_zatca_settings">

        <table class="form-table">
            <tr>
                <th><label for="company_name">Company Name</label></th>
                <td><input type="text" id="company_name" name="company_name" required></td>
            </tr>
            <tr>
                <th><label for="vat_id">VAT Number</label></th>
                <td><input type="text" id="vat_id" name="vat_id" required></td>
            </tr>
            <tr>
                <th><label for="group_vat_number">Group VAT Number</label></th>
                <td><input type="text" id="group_vat_number" name="group_vat_number"></td>
            </tr>
            <tr>
                <th><label for="second_business_id_type">Additional Business ID Type</label></th>
                <td>
                    <select id="second_business_id_type" name="second_business_id_type" required>
                        <option value="1">Type 1</option>
                        <option value="2">Type 2</option>
                        <option value="3">Type 3</option>
                        <!-- Add more options as needed -->
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="second_business_id">Additional Business ID</label></th>
                <td><input type="text" id="second_business_id" name="second_business_id"></td>
            </tr>
            <tr>
                <th><label for="vat_category">VAT Category Code</label></th>
                <td>
                    <select id="vat_category" name="vat_category" required>
                        <option value="1">S</option>
                        <option value="2">O</option>
                        <option value="3">E</option>
                        <option value="4">Z</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="vat_category_subcode">VAT Category SubCode</label></th>
                <td>
                    <select id="vat_category_subcode" name="vat_category_subcode" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="zatca_stage">ZATCA Compliance Stage</label></th>
                <td>
                    <select id="zatca_stage" name="zatca_stage" required>
                        <option value="0">No ZATCA</option>
                        <option value="1">ZATCA v1</option>
                        <option value="2">ZATCA V2</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="apartment_no">Apartment Number</label></th>
                <td><input type="text" id="apartment_no" name="apartment_no" pattern="\d{4}" title="Must be 4 digits" required></td>
            </tr>
            <tr>
                <th><label for="postal_code">Postal Code</label></th>
                <td><input type="text" id="postalCode" name="postal_code" required></td>
            </tr>
            <tr>
                <th><label for="street_name">Street Name</label></th>
                <td><input type="text" id="streetName" name="street_name" required></td>
            </tr>
            <tr>
                <th><label for="district_name">District Name</label></th>
                <td><input type="text" id="districtName" name="district_name" required></td>
            </tr>
            <tr>
                <th><label for="city_name">City Name</label></th>
                <td><input type="text" id="cityName" name="city_name" required></td>
            </tr>
            <tr>
                <th><label for="country_name">Country Name</label></th>
                <td><input type="text" id="countryName" name="country_name" value="Saudi Arabia" required></td>
            </tr>
            <tr>
                <th><label for="country_no">Country Number</label></th>
                <td><input type="text" id="countryNo" name="country_no" required></td>
            </tr>
            <tr>
                <th><label for="csid">CSID</label></th>
                <td><input type="text" id="csid" name="csid"></td>
            </tr>
            <tr>
                <th><label for="uuid">UUID</label></th>
                <td><input type="text" id="uuid" name="uuid"></td>
            </tr>
            <tr>
                <th><label for="token_data">ZATCA token</label></th>
                <td><input type="text" id="token_data" name="token_data"></td>
            </tr>
            <tr>
                <th><label for="csid_expiry_date">CSID Expiry Date</label></th>
                <td><input type="text" id="csid_expiry_date" name="csid_expiry_date"></td>
            </tr>
            <tr>
                <th><label for="zatca_b2c_sending_interval_type">ZATCA B2C Sending Interval</label></th>
                <td>
                    <select id="zatca_b2c_sending_interval_type" name="zatca_b2c_sending_interval_type" required>
                        <option value="1">Manual</option>
                        <option value="2">Instant and continue invoicing</option>
                        <option value="3">Instant and hold invoicing</option>
                    </select>
                </td>
            </tr>
			<tr>
				<th>
			  <input type="submit" name="submit" class="button-primary" value="Save Settings">
		 	  <input type="button" class="button-primary" name="copy_button" value="Copy" onclick="copyFormData()">
				</th></tr>
			<!-------------- for copy ------------------>
			  <tr>
               <!-- <th><label for="postal_code">Postal Code</label></th> -->
                <td><input type="hidden" class="form-control" id="postalCode1" name="postalCode1" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_postcode', true)); ?>"></td>
            </tr>
            <tr>
                <!--  <th><label for="street_name">Street Name</label></th>-->
                <td><input type="hidden" id="streetName1" name="street_name1" value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'shipping_address_1', true)); ?>"></td>
            </tr>
            <tr>
                 <!-- <th><label for="district_name">District Name</label></th> -->
                <td><input type="hidden" class="form-control" id="districtName1" name="districtName1" required value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_city', true)); ?>"></td>
            </tr>
            <tr>
                 <!-- <th><label for="city_name">City Name</label></th> -->
                <td><input type="hidden" id="cityName1" name="cityName1" value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_city', true)); ?>"></td>
            </tr>
            <tr>
                <!--  <th><label for="country_name">Country Name</label></th> -->
                <td><input type="hidden" id="countryName1" name="country_name1" value="Saudi Arabia" required></td>
            </tr>
            <tr>
                <!--  <th><label for="country_no">Country Number</label></th> -->
                <td><input type="hidden" id="countryNo1" name="country_no1" value="<?php echo esc_attr(get_user_meta(get_current_user_id(), 'billing_country', true)); ?>"></td>
            </tr>
        </table>

       
    </form>
</div>

<?php


?>
<script>
    function copyFormData() {
        // Get values from the second form
        var postalCode1 = document.getElementById('postalCode1').value;
        var streetName1 = document.getElementById('streetName1').value;
        var districtName1 = document.getElementById('districtName1').value;
        var cityName1 = document.getElementById('cityName1').value;
        var countryName1 = document.getElementById('countryName1').value;
        var countryNo1 = document.getElementById('countryNo1').value;
        // Get elements in the first form
        var postalCode = document.getElementById('postalCode');
        var streetName = document.getElementById('streetName');
        var districtName = document.getElementById('districtName');
        var cityName = document.getElementById('cityName');
        var countryName = document.getElementById('countryName');
        var countryNo = document.getElementById('countryNo');

        // Set values to the first form
        postalCode.value = postalCode1;
        streetName.value = streetName1;
        districtName.value = districtName1;
        cityName.value = cityName1;
        countryName.value = countryName1;
        countryNo.value = countryNo1;

        // Change the color of retrieved data to red
        postalCode.style.color = 'red';
        streetName.style.color = 'red';
        districtName.style.color = 'red';
        cityName.style.color = 'red';
        countryName.style.color = 'red';
        countryNo.style.color = 'red';
    }
</script>



