<?php
include_once dirname(dirname(__FILE__)) . '/zatca.php';

?>


<div class="container">
    
    <!-- Back Btn -->
    <div class=" mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-customers&action=view'); ?>" 
            class="btn my-plugin-button"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="<?php echo _e('Back', 'zatca') ?>">
            <span class="dashicons dashicons-undo"></span>
        </a>
    </div>
    <!-- / Back Btn -->

    <!-- Header -->
    <div class="col-xl-9 mx-auto mt-3">
        <h5 class="mb-3 text-uppercase text-center"><?php echo _e('Add New Customer', 'zatca')?></h5>
    </div>
    <!-- / Header -->

    <!-- Input Form -->
    <form class="form-horizontal main-form mt-1" id="insert_customer_form">
        
        <!-- Hidden input to check for if come from customer page or document page-->
            <input type="hidden" name='status' value="documents">
        <!-- Hidden input to check for if come from customer page or document page-->

        <!--  clientVendorNo field -->
        <div class="mb-3 row col-mid-6">
            <label class="col-sm-2 col-form-label"><?php echo _e('Client No.:', 'zatca') ?></label>
            <div class="col-sm-6 col-md-5">
                <div class="input-group">
                    <input 
                        type="text"
                        id="client-no"
                        name="client-no" 
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Client No.', 'zatca') ?>"
                        readonly
                    />

                    <div class="mx-1"></div>

                    <!-- Search Btn -->
                    <button 
                        type='button' 
                        class='btn my-plugin-button me-1' 
                        data-bs-toggle='modal' 
                        data-bs-target='#exampleModal-search-customers'
                        data-bs-toggle="tooltip" 
                        data-bs-placement="top" 
                        title="<?php echo _e('Copy data from the system to here', 'zatca') ?>">
                        <span class="dashicons dashicons-search"></span>
                    </button>
                    <!-- / Search Btn -->

                    <!-- Customers Modal -->
                    <div class='modal fade' id='exampleModal-search-customers' tabindex='-1' aria-labelledby='exampleModalLabel-search-customers' aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content' >
                            <div class='modal-header'>
                                <h5 class='modal-title' id='exampleModalLabel-". $result->ID."'><?php echo _e('Customers', 'zatca') ?></h5>
                            </div>
                            <div class='modal-body'>
                                
                                <div class="container ">
                                    
                                    <table id="example" class="table table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Name', 'zatca') ?></th>
                                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Email', 'zatca') ?></th>
                                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Phone', 'zatca') ?></th>
                                                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Address', 'zatca') ?></th>
                                            </tr>
                                        </thead>
                                    
                                        <tbody>
                                            <?php
                                            global $wpdb;

                                            $table_usermeta = $wpdb->prefix . 'usermeta';
                                            $meta_key_capabilities = $wpdb->prefix .'capabilities'; 
                                            $meta_value_customer = '%customer%';
                                            $meta_key_capabilities_escaped = $wpdb->_real_escape($meta_key_capabilities);
                                            $customers = $wpdb->get_results("SELECT * FROM $table_usermeta WHERE meta_key = '$meta_key_capabilities_escaped' AND meta_value LIKE '$meta_value_customer'");
                                            
                                                foreach ($customers as $customer) {
                                                    $billing_first_name = $customer->meta_value;
                                                    $user_id = $customer->user_id;

                                                    $billing_first_name = get_user_meta($user_id, 'billing_first_name', true);
                                                    $billing_last_name = get_user_meta($user_id, 'billing_last_name', true);
                                                    $billing_address_1 = get_user_meta($user_id, 'billing_address_1', true);
                                                    $billing_phone = get_user_meta($user_id, 'billing_phone', true);
                                                    $billing_email = get_user_meta($user_id, 'billing_email', true);

                                                        // check if this customer choose before or not:
                                                    $checks = $wpdb->get_results("SELECT * FROM zatcaCustomer WHERE clientVendorNo = $customer->user_id");
                                                    if ($wpdb->num_rows > 0) {?>
                                                        <tr>
                                                            <!-- Disabled -->
                                                            <td class="disabled-td"><?php echo $billing_first_name . ' ' . $billing_last_name ?></td>
                                                            <td class="disabled-td"><?php echo $billing_email ?></td>
                                                            <td class="disabled-td"><?php echo $billing_phone ?></td>
                                                            <td class="disabled-td"><?php echo $billing_address_1 ?></td>
                                                        </tr>
                                                        <?php
                                                    } else {?>
                                                        <tr data-user-id="<?php echo $user_id; ?>">
                                                            <td class="user-name"><?php echo $billing_first_name . ' ' . $billing_last_name ?></td>
                                                            <td class="user-email"><?php echo $billing_email ?></td>
                                                            <td class="user-phone"><?php echo $billing_phone ?></td>
                                                            <td class="user-address"><?php echo $billing_address_1 ?></td>
                                                        </tr>
                                                        
                                                        <?php
                                                    }
                                                }
                                                
                                            ?>
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='my-plugin-button' data-bs-dismiss='modal'>
                                    <span class="dashicons dashicons-no"></span>
                                </button>

                                <!-- Copy btn -->
                                <button 
                                    class="my-plugin-button me-1" 
                                    type="button" 
                                    id='search-customer-data' 
                                    data-bs-dismiss='modal'
                                    data-bs-toggle="tooltip" 
                                    data-bs-placement="top" 
                                    title="<?php echo _e('Search Customer data', 'zatca') ?>">
                                    <span class="dashicons dashicons-saved"></span>
                                </button> 
                                <!-- / Copy Btn -->

                            </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Customers Modal -->

                </div>
            </div>
        </div>
        <!-- /  clientVendorNo field -->
        
        <!-- Client Name Ar - En -->
        <div class="row g-3 mb-3">

            <!--  Client Name Arabic field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Client Name ( Arabic ):', 'zatca') ?></label>
                <input 
                    type="text"
                    name="client-name-ar" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Client Name ( Arabic )', 'zatca') ?>"
                />
            </div>
            <!-- /  Client Name Arabic field -->

            <!--  Client Name English field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Client Name( English ):', 'zatca') ?></label>
                <input 
                    type="text"
                    id="client-name"
                    name="client-name-en" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Client Name ( English )', 'zatca') ?>"
                />
            </div>
            <!-- /  Client Name English field -->

        </div>
        <!-- / Client Name Ar - En -->


        <!-- VatId & Second Business ID -->
        <div class="row g-3 mb-3">

            <!--  VAT ID field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('VAT ID:', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="vat-id" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('VAT ID', 'zatca') ?>"
                />
            </div>
            <!-- /  VAT ID field -->

            <!--  Second Business ID  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Second Business ID :', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="second-business-id" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Second Business ID', 'zatca') ?>"
                />
            </div>
            <!-- /  Second Business ID  field -->

        </div>
        <!-- / VatId & Second Business ID -->

        <!-- Second Business ID Type & ZATCA Invoices Type & Country -->
        <div class="row g-3 mb-3">

            <!--  ZATCA Invoices Type  field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('ZATCA Invoices Type :', 'zatca') ?></label>
                <select class="form-select select2"  name="zatca-invoice-type">
                    <option value="Both">Both</option>
                    <option value="B2C">B2C</option>
                    <option value="B2B">B2B</option>
                </select>
            </div>
            <!-- /  ZATCA Invoices Type  field -->

            <!--  Second Business ID Type field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Second Business ID Type:', 'zatca') ?></label>
                <select class="form-select select2"  name="second-business-id-type">
                    <option value="">...</option>
                    <?php 
                    global $wpdb;
                    
                    // Fetch Data From Database:
                    $buyers = $wpdb->get_results( "SELECT * FROM zatcabusinessidtype WHERE isBuyer=1" );
                    foreach($buyers as $buyer) {
                        echo '<option  value="'.$buyer->codeNumber.'">'.$buyer->aName. ' - ' . $buyer->eName.'</option>';
                    }
                    ?>
                </select>
            </div>
            <!-- /  Second Business ID Type field -->

            <!--  Country  field -->
            <div class="col-md-4">
                <label class="form-label"><?php echo _e('Country Name:', 'zatca') ?></label>
                <select class="form-select select2"  name="country">
                    <?php 
                    global $wpdb;
                    
                    // Fetch Data From Database:
                    $countries = $wpdb->get_results( "SELECT * FROM country" );
                    foreach($countries as $country) {
                        echo '<option  value="'.$country->country_id.'">'.$country->arabic_name.'</option>';
                    }
                    ?>
                </select>
            </div>
            <!-- /  Country  field -->


        </div>
        <!-- / Second Business ID Type & ZATCA Invoices Type & Country -->

        <!-- Apartment No &  PO Box Additional Number-->
        <div class="row g-3 mb-3">

            <!--  Apartment No  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Apartment No :', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="apartment-no" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Apartment No', 'zatca') ?>"
                />
            </div>
            <!-- /  Apartment No  field -->

            <!--  PO Box Additional Number  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('PO Box Additional Number :', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="po-box-additional-no" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('PO Box Additional Number', 'zatca') ?>"
                />
            </div>
            <!-- /  PO Box Additional Number  field -->
             
        </div>
        <!-- / Apartment No &  PO Box Additional Number-->

        <!-- Postal Code & PO Box -->
        <div class="row g-3 mb-3">
            <!--  Postal Code field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Postal Code:', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="postal-code"
                    id="postal_code"
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Postal Code', 'zatca') ?>"
                    
                />
            </div>
            <!-- /  Postal Code field -->

            <!--  PO Box  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('PO Box :', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="po-box"
                    id="po-insert-customer"
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('PO Box', 'zatca') ?>"
                    
                />
            </div>
            <!-- /  PO Box  field -->

        </div>
        <!-- / Postal Code & PO Box -->

        <!-- Street Name AR - EN -->
        <div class="row g-3 mb-3">

            <!--  Street Name - AR  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Street Name (Arabic):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="street-name-ar"
                    id="address-ar"
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Street Name (Arabic)', 'zatca') ?>"
                />
            </div>
            <!-- /  Street Name - AR  field -->

            <!--  Street Name - EN  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Street Name (English):', 'zatca') ?></label>
                <input 
                    type="text"
                    id="address-en"
                    name="street-name-en" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Street Name (English)', 'zatca') ?>"
                />
            </div>
            <!-- /  Street Name - EN  field -->
        </div>
        <!-- / Street Name AR - EN -->

        <!-- District Name AR - EN -->
        <div class="row g-3 mb-3">

            <!--  District Name - AR  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('District Name (Arabic):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="district-name-ar" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('District Name (Arabic)', 'zatca') ?>"
                />
            </div>
            <!-- /  District Name - AR  field -->

            <!--  District Name - EN  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('District Name (English):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="district-name-en" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('District Name (English)', 'zatca') ?>"
                />
            </div>
            <!-- /  District Name - EN  field -->

        </div>
        <!-- / District Name AR - EN -->

        <!-- City Name AR - EN -->
        <div class="row g-3 mb-3">
        
            <!--  City Name - AR  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('City Name(Arabic):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="city-name-ar"
                    id="city-ar"
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('City Name(Arabic)', 'zatca') ?>"
                />
            </div>
            <!-- /  City Name - AR  field -->
            
            <!--  City Name - EN  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('City Name(English):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="city-name-en"
                    id="city-en"
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('City Name(English)', 'zatca') ?>"
                />
            </div>
            <!-- /  City Name - EN  field -->

        </div>
        <!-- / City Name AR - EN  -->

        <!-- Country Subdivision AR - EN -->
        <div class="row g-3 mb-3">
        
            <!--  Country Subdivision - AR  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Country Subdivision (Arabic):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="country-sub-name-ar" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Country Subdivision (Arabic)', 'zatca') ?>"
                />
            </div>
            <!-- /  Country Subdivision - AR  field -->

            <!--  Country Subdivision - EN  field -->
            <div class="col-md-6">
                <label class="form-label"><?php echo _e('Country Subdivision (English):', 'zatca') ?></label>
                <input 
                    type="text" 
                    name="country-sub-name-en" 
                    class="form-control" 
                    autocomplete="off"
                    placeholder="<?php echo _e('Country Subdivision (English)', 'zatca') ?>"
                />
            </div>
            <!-- /  Country Subdivision - EN  field -->

        </div>
        <!-- / Country Subdivision AR - EN -->

        <!-- Submit Btn -->
        <div class="mb-3 row">
            <div class="d-flex justify-content-center">
                <input type="submit" value="<?php echo _e('Insert New Customer', 'zatca') ?>" class="my-plugin-button " />
            </div>
        </div>
        <!-- / Submit Btn -->
        
    </form>
    <!-- / Input Form -->

</div>