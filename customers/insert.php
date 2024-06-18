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
        <form class="form-horizontal main-form mt-1" id="contact-form__form">
            
            <!-- Hidden input -->
                <input type="hidden" name='status' value="customers">
            <!-- Hidden input -->

            <!--  clientVendorNo field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Client / VendorNo:', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="input-group">
                        <input 
                            type="text"
                            id="client-no"
                            name="client-no" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Client / Vendor', 'zatca') ?>"
                            disabled
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
                            title="<?php echo _e('Search Invoices', 'zatca') ?>">
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
                                                        $checks = $wpdb->get_results("SELECT * FROM zatcacustomer WHERE clientVendorNo = $customer->user_id");
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

            <!--  Client Name Arabic field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Client Name - AR:', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text"
                            name="client-name-ar" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Client Name - AR', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Client Name Arabic field -->

            <!--  Client Name English field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Client Name - EN:', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text"
                            id="client-name"
                            name="client-name-en" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Client Name - EN', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Client Name English field -->

            <!--  VAT ID field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('VAT ID:', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="vat-id" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('VAT ID', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  VAT ID field -->

            <!--  Second Business ID Type field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Second Business ID Type:', 'zatca') ?></label>
                <div class="col-sm-9 col-md-8">
                    <div class="form-group">
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
                </div>
            </div>
            <!-- /  Second Business ID Type field -->


            <!--  Second Business ID  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Second Business ID :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="second-business-id" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Second Business ID', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Second Business ID  field -->

            <!--  ZATCA Invoices Type  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('ZATCA Invoices Type :', 'zatca') ?></label>
                <div class="col-sm-10 col-md-9">
                    <div class="form-group">
                        <select class="form-select select2"  name="zatca-invoice-type">
                            <option value=""> ...</option>
                            <option value="B2B">B2B</option>
                            <option value="B2C">B2C</option>
                            <option value="Both">Both</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- /  ZATCA Invoices Type  field -->

            <!--  Apartment No  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Apartment No :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="apartment-no" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Apartment No', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Apartment No  field -->

            <!--  PO Box  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('PO Box :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="po-box"
                            id="po-insert-customer"
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('PO Box', 'zatca') ?>"
                            required
                            
                        />
                    </div>
                </div>
            </div>
            <!-- /  PO Box  field -->

            <!--  PO Box Additional Number  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('PO Box Additional Number :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="po-box-additional-no" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('PO Box Additional Number', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  PO Box Additional Number  field -->

            <!--  Street Name - AR  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Street Name - AR :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="street-name-ar"
                            id="address-ar"
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Street Name - AR', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Street Name - AR  field -->

            <!--  Street Name - EN  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Street Name - EN :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text"
                            id="address-en"
                            name="street-name-en" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Street Name - EN', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Street Name - EN  field -->

            <!--  District Name - AR  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('District Name - AR :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="district-name-ar" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('District Name - AR', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  District Name - AR  field -->

            <!--  District Name - EN  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('District Name - EN :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="district-name-en" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('District Name - EN', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  District Name - EN  field -->

            <!--  City Name - AR  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('City Name - AR :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="city-name-ar"
                            id="city-ar"
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('City Name - AR', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  City Name - AR  field -->
            
            <!--  City Name - EN  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('City Name - EN :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="city-name-en"
                            id="city-en"
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('City Name - EN', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  City Name - EN  field -->

            <!--  Country Subdivision - AR  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Country Subdivision - AR :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                            type="text" 
                            name="country-sub-name-ar" 
                            class="form-control" 
                            autocomplete="off"
                            placeholder="<?php echo _e('Country Subdivision - AR', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Country Subdivision - AR  field -->

            <!--  Country Subdivision - EN  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Country Subdivision - EN :', 'zatca') ?></label>
                <div class="col-sm-6 col-md-5">
                    <div class="form-group">
                        <input 
                        type="text" 
                        name="country-sub-name-en" 
                        class="form-control" 
                        autocomplete="off"
                        placeholder="<?php echo _e('Country Subdivision - EN', 'zatca') ?>"
                        />
                    </div>
                </div>
            </div>
            <!-- /  Country Subdivision - EN  field -->

            <!--  Country  field -->
            <div class="mb-3 row col-mid-6">
                <label class="col-sm-2 col-form-label"><?php echo _e('Country :', 'zatca') ?></label>
                <div class="col-sm-10 col-md-9">
                    <div class="form-group">
                        <select class="form-select select2"  name="country">
                            <option value=""> ...</option>
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
                </div>
            </div>
            <!-- /  Country  field -->

            <!-- Submit Btn -->
            <div class="mb-3 row">
                <div class="d-grid gap-2 col-8 md-flex justify-content-md-end">
                    <input type="submit" value="<?php echo _e('Insert New Customer', 'zatca') ?>" class="my-plugin-button " />
                </div>
            </div>
            <!-- / Submit Btn -->
            
        </form>
        <!-- / Input Form -->

    </div>

