
<div class="container">
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Customers', 'zatca') ?></h4>
    </div>
    
    <!-- Add Btn -->
    <div class="col-xl-12 mx-auto mt-3">
        <a 
            href="<?php echo admin_url('admin.php?page=zatca-customers&action=insert')  ?>" 
            class="my-plugin-button btn add-btn"
            data-bs-toggle="tooltip" 
            data-bs-placement="top" 
            title="<?php echo _e('Insert New Customer', 'zatca') ?>">
            <span class="dashicons dashicons-insert"></span>
        </a>
    </div>
    <!-- / Add Btn -->

    <!-- Table Of View -->
    <table id="scroll_table" class="table table-striped" width="100%">

        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('Customer No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Client Name(Ar)', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Client Name(En)', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('VAT ID', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Second Business ID Type', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('ZATCA Invoices Type', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Street Name(Ar)', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Dist-AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('City-AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Sec-Business-Id', 'zatca') ?></th class="text-center" style="font-size: 0.7rem;">
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Apart No', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Postal Code', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('PO Box Additional Num', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('St Name - EN', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Dist Name - EN', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('City Name - EN', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Country Sub - EN', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Country', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Action', 'zatca') ?></th>
            </tr>
        </thead>
    
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data
            $customers = get_all_data('zatcaCustomer');
            
            // Check if there are results
            if ($customers) {
                foreach ($customers as $customer) {

                    ?>
                    <tr>
                        <td style="font-size: 0.8rem;"><?php echo $customer->clientVendorNo ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->aName ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->eName ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->VATID ?></td>
                        <td style="font-size: 0.8rem;">
                            <?php 
                                // get second business id name:
                                $buyers = get_data_with_one_condition('zatcabusinessidtype', 'codeNumber', $customer->secondBusinessIDType);
                                foreach($buyers as $buyer) {
                                    echo $buyer->aName;
                                }
                            ?>
                        </td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->zatcaInvoiceType ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->street_Arb ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->district_Arb ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->city_Arb ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->secondBusinessID ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->apartmentNum ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->postalCode ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->POBoxAdditionalNum ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->street_Eng ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->district_Eng ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->city_Eng ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $customer->countrySubdivision_Eng ?></td>
                        <td style="font-size: 0.8rem;">
                            <?php 
                                // get country name:
                                $countries = get_data_with_one_condition('country', 'country_id', $customer->country_No);
                                foreach($countries as $country) {
                                    echo $country->arabic_name;
                                }

                            ?>
                        </td>
                        <td style="font-size: 0.8rem;" class="d-flex align-items-center ">

                            <!-- Edit Btn -->
                            <a 
                                href="<?php echo admin_url('admin.php?page=zatca-customers&action=edit-customer&clientno='.$customer->clientVendorNo.'')  ?>" 
                                class="my-plugin-button btn-sm me-1"
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="<?php echo _e('Edit Customer', 'zatca') ?>">
                                <span class="dashicons dashicons-edit"></span>
                            </a>
                            <!-- / Edit Btn -->
    
                            <!-- Delete Btn -->
                            <button 
                                type='button' 
                                class='btn my-plugin-button me-1'
                                id="delete_customer"
                                data-client-no='<?php echo $customer->clientVendorNo ?>'
                                data-bs-placement="top" 
                                title="<?php echo _e('Delete Customer', 'zatca') ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                            <!-- /Delete Btn -->

                        </td>
    
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <!-- / Table Of View -->

</div>