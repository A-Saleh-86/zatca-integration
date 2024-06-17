
<div class="container">
    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-0 text-uppercase text-center"><?php echo _e('Customers', 'zatca') ?></h4>
    </div>
    
    <!-- Add Btn -->
    <div class="col-xl-12 mx-auto mt-3">
        <a href="<?php echo admin_url('admin.php?page=zatca-customers&action=insert')  ?>" class="my-plugin-button btn add-btn">
            <span class="dashicons dashicons-insert"></span>
        </a>
    </div>
    <!-- / Add Btn -->
    <table id="example" class="table table-striped" width="100%">

        <thead>
            <tr>
                <th class="text-center" style="font-size: 0.7rem;" ><?php echo _e('ID', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name-AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Name-EN', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('VAT ID', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Sec-Buss-Type', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Invoices Type', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Street AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Dist-AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('City-AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Sub-AR', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Country', 'zatca') ?></th>
                <th class="text-center" style="font-size: 0.7rem;"><?php echo _e('Action', 'zatca') ?></th>
            </tr>
        </thead>
    
        <tbody>
            <?php
            global $wpdb;

            // Query to retrieve data
            $resultes = $wpdb->get_results( "SELECT * FROM zatcacustomer");
            
            // Check if there are results
            if ($resultes) {
                foreach ($resultes as $result) {

                    ?>
                    <tr>
                        <td style="font-size: 0.8rem;"><?php echo $result->ID ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->aName ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->eName ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->VATID ?></td>
                        <td style="font-size: 0.8rem;">
                            <?php 
                                $buyers = $wpdb->get_results( "SELECT * FROM zatcabusinessidtype WHERE codeNumber =$result->secondBusinessIDType " );
                                foreach($buyers as $buyer) {
                                    echo $buyer->aName . ' - ' . $buyer->eName;
                                }
                            ?>
                        </td>
                        
                        <td style="font-size: 0.8rem;"><?php echo $result->zatcaInvoiceType ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->street_Arb ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->district_Arb ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->city_Arb ?></td>
                        <td style="font-size: 0.8rem;"><?php echo $result->countrySubdivision_Arb ?></td>

                        <td style="font-size: 0.8rem;">
                            <?php 
                                // echo $result->country_No 
                                $table_name = 'country';
                                $countries = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE country_id = %d", $result->country_No));
                                foreach($countries as $country) {
                                    echo $country->arabic_name;
                                }

                            ?>
                            </td>
                        <td style="font-size: 0.8rem;" class="d-flex align-items-center ">
                            <!-- Edit Btn -->
                            <a href="<?php echo admin_url('admin.php?page=zatca-customers&action=edit-customer&id='.$result->ID.'')  ?>" class="my-plugin-button btn-sm me-1">
                            <span class="dashicons dashicons-edit"></span>
                            </a>
                            <!-- / Edit Btn -->
    
                            <!-- Delete Btn -->
                            <a href="<?php echo admin_url('admin.php?page=zatca-customers&action=delete&id='.$result->ID.'')  ?>" id="delete" class="my-plugin-button btn-sm me-1 confirm">
                            <span class="dashicons dashicons-trash"></span>
                            </a>
                            <!-- /Delete Btn -->

                            <!-- View Btn -->
                            <button type='button' class='btn my-plugin-button btn-sm ' data-bs-toggle='modal' data-bs-target='#exampleModal-<?php echo $result->ID ?>'>
                                <span class="dashicons dashicons-welcome-view-site"></span>
                            </button>
                            <!-- / View Btn -->

                            <!-- View Modal -->
                            <div class='modal fade' id='exampleModal-<?php echo $result->ID ?>' tabindex='-1' aria-labelledby='exampleModalLabel-<?php echo $result->ID ?>' aria-hidden='true'>
                                <div class='modal-dialog modal-lg'>
                                    <div class='modal-content' >
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='exampleModalLabel-". $result->ID."'><?php echo _e('Items', 'zatca') ?></h5>
                                    </div>
                                    <div class='modal-body'>
                                        
                                        <div class="container ">
                                            <div class="table-responsive">
                                                <table  class="table table-success table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo _e('Sec-Business-Id', 'zatca') ?></th>
                                                            <th><?php echo _e('Apart No', 'zatca') ?></th>
                                                            <th><?php echo _e('PO Box', 'zatca') ?></th>
                                                            <th><?php echo _e('PO Box Additional Num', 'zatca') ?></th>
                                                            <th><?php echo _e('St Name - EN', 'zatca') ?></th>
                                                            <th><?php echo _e('Dist Name - EN', 'zatca') ?></th>
                                                            <th><?php echo _e('City Name - EN', 'zatca') ?></th>
                                                            <th><?php echo _e('Country Sub - EN', 'zatca') ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->secondBusinessID ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->apartmentNum ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->POBox ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->POBoxAdditionalNum ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->street_Eng ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->district_Eng ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->city_Eng ?></td>
                                                            <td style="font-size: 0.8rem;"><?php echo $result->countrySubdivision_Eng ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                        </div> 
                                    </div>
                                    <div class='modal-footer'>
                                        <button type='button' class='btn my-plugin-button' data-bs-dismiss='modal'><?php echo _e('Close', 'zatca') ?></button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <!-- / View Modal -->

                            
                            
                        </td>
    
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>