<?php



global $wpdb;



// Table Name:
$table_name = 'zatcacompany';

// Prepare the query with a condition on the VendorId column using the %d placeholder
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE companyNo = %d", 1 ) );

// Check if there are results
if (!empty($results)) {
    foreach ($results as $result) {

        ?>
        <div class="container">
            <div class="col-xl-12 mx-auto mt-3">
                <h4 class="mb-3 text-uppercase text-center"><?php echo _e('Company', 'zatca') ?></h4>
            </div>
            <form class="form-horizontal main-form mt-1" id="edit-form-company__form">

                <!-- Hidden input for company Id -->
                <input type="hidden" name="id" value="<?php echo $result->companyNo  ?>">
                <!-- / Hidden input for company Id -->


                <!-- Zatca Stage - Get Data From wp_options -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Zatca Stage:', 'zatca') ?></label>
                    <div class="col-sm-8 col-md-7">
                        <div class="input-group"> 
                            <select class="form-select"  name="zatca-stage">
                                <option value="ZATCA V1" <?php if($result->zatcaStage  == 'ZATCA V1'){ echo 'selected';}?>>ZATCA V1</option>
                                <option value="ZATCA V2" <?php if($result->zatcaStage  == 'ZATCA V2'){ echo 'selected';}?> >ZATCA V2</option>
                                <option value="No ZATCA" <?php if($result->zatcaStage  == 'No ZATCA'){ echo 'selected';}?> >No ZATCA</option>
                            </select>
                            
                            <div class="mx-1"></div>

                            <!-- Get Seller Data Btn -->
                            <button 
                                class="my-plugin-button me-1" 
                                type="button" 
                                id='copy-company-data'
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="<?php echo _e('Get Seller Data', 'zatca') ?>">
                                <span class="dashicons dashicons-update"></span>
                            </button>
                            <!-- / Get Seller Data form -->

                        </div>
                    </div>
                </div>
                <!-- /  Zataca Stage -->

                <!--  Second Business ID Type field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Second Business ID Type:', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <select class="form-select select2"  name="second-business-id-type">
                                <option value="">...</option>
                                <?php
                                    global $wpdb;
    
                                    // Fetch Data From Database:
                                    $sellers = $wpdb->get_results( "SELECT * FROM zatcabusinessidtype WHERE isSeller=1" );
                                    foreach($sellers as $seller) {?>
                                        
                                        <option value="<?php echo $seller->codeNumber ?>" <?php if($result->secondBusinessIDType == $seller->codeNumber){ echo 'selected';} ?> ><?php echo $seller->aName. ' - ' . $seller->eName ?></option>
                                        <?php
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
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text"
                                name="second-business-id"
                                id="second-id-company"
                                class="form-control"
                                value="<?php echo $result->secondBusinessID ?>"
                                required
                            />
                        </div>
                    </div>
                </div>
                <!-- /  Second Business ID  field -->


                <!--  VATCategoryCode field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('VAT Category Code:', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <select class="form-select select2"  name="vat-cat-code" id="vat-cat-code">
                                <option value="">...</option>
                                <?php
                                    global $wpdb;
    
                                    // Fetch Data From Database:
                                    $categories = $wpdb->get_results( "SELECT * FROM met_vatcategorycode" );
                                    foreach($categories as $category) {?>
                                        
                                        <option value="<?php echo $category->VATCategoryCodeNo ?>" <?php if($result->VATCategoryCode == $category->VATCategoryCodeNo){ echo 'selected';} ?> ><?php echo $category->aName. ' - ' . $category->eName ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /  VATCategoryCode field -->

                <!--  VATCategoryCodeSubTypeNo field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('VAT Category Code Sub Type No:', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <select class="form-select select2"  name="vat-cat-code-sub-no" id="vat-cat-code-sub">
                                <option value="">...</option>
                                <?php
                                    global $wpdb;
    
                                    // Fetch Data From Database:
                                    $subCategories = $wpdb->get_results( "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeNo = $result->VATCategoryCode" );
                                    foreach($subCategories as $subCat) {?>
                                        
                                        <option value="<?php echo $subCat->VATCategoryCodeSubTypeNo ?>" <?php if($result->VATCategoryCodeSubTypeNo == $subCat->VATCategoryCodeSubTypeNo){ echo 'selected';} ?> ><?php echo $subCat->aName. ' - ' . $subCat->eName ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /  VATCategoryCodeSubTypeNo field -->

                <!--  VAT ID field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('VAT ID:', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="vat-id" 
                                class="form-control"
                                value="<?php echo $result->VATID ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  VAT ID field -->

                <!--  aName field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Name:', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control"
                                value="<?php echo $result->aName ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  aName field -->

                <!--  Apartment No  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Apartment No :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="apartment-no" 
                                class="form-control"
                                value="<?php echo $result->apartmentNum ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  Apartment No  field -->

                <!--  PO Box  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('PO Box :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="po-box"
                                id="po-company"
                                class="form-control"
                                value="<?php echo $result->POBox ?>"
                                required
                            />
                        </div>
                    </div>
                </div>
                <!-- /  PO Box  field -->

                <!--  PO Box Additional Number  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('PO Box Additional Number :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="po-box-additional-no" 
                                class="form-control"
                                value="<?php echo $result->POBoxAdditionalNum ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  PO Box Additional Number  field -->

                <!--  Street Name - AR  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Street Name - AR :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="street-name-ar" 
                                class="form-control"
                                value="<?php echo $result->street_Arb ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  Street Name - AR  field -->

                <!--  Street Name - EN  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Street Name - EN :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="street-name-en"
                                id="company-address"
                                class="form-control"
                                value="<?php echo $result->street_Eng ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  Street Name - EN  field -->

                <!--  District Name - AR  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('District Name - AR :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="district-name-ar" 
                                class="form-control"
                                value="<?php echo $result->district_Arb ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  District Name - AR  field -->

                <!--  District Name - EN  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('District Name - EN :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="district-name-en" 
                                class="form-control"
                                value="<?php echo $result->district_Eng ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  District Name - EN  field -->

                <!--  City Name - AR  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('City Name - AR :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="city-name-ar" 
                                class="form-control"
                                value="<?php echo $result->city_Arb ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  City Name - AR  field -->

                <!--  City Name - EN  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('City Name - EN :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                            type="text" 
                            name="city-name-en"
                            id="company-city"
                            class="form-control"
                            value="<?php echo $result->city_Eng ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  City Name - EN  field -->

                <!--  Country Subdivision - AR  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Country Subdivision - AR :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                                type="text" 
                                name="country-sub-name-ar" 
                                class="form-control"
                                value="<?php echo $result->countrySubdivision_Arb ?>"
                            />
                        </div>
                    </div>
                </div>
                <!-- /  Country Subdivision - AR  field -->

                <!--  Country Subdivision - EN  field -->
                <div class="mb-3 row col-mid-6">
                    <label class="col-sm-2 col-form-label"><?php echo _e('Country Subdivision - EN :', 'zatca') ?></label>
                    <div class="col-sm-10 col-md-9">
                        <div class="form-group">
                            <input 
                            type="text" 
                            name="country-sub-name-en" 
                            class="form-control"
                            value="<?php echo $result->countrySubdivision_Eng ?>"
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
                                foreach($countries as $country) {?>
                                     '<option  value="<?php echo $country->country_id ?>" <?php if($result->countryNo == $country->country_id){echo 'selected';} ?>><?php echo $country->arabic_name ?></option>';
                                    <?php
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
                        <input type="submit" value="<?php echo _e('Update Company Details', 'zatca') ?>" class="my-plugin-button" />
                    </div>
                </div>
                <!-- / Submit Btn -->

            </form>
        </div>
        <?php
    }
} else {
    echo "No data found.";
}
?>

