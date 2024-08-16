<div class="container">

    <div class="col-xl-12 mx-auto mt-3">
        <h4 class="mb-3 text-uppercase text-center"><?php echo _e('Company', 'zatca') ?></h4>
    </div>

    <?php
    
    $results = get_all_data('zatcaCompany');

    // Check if there are results
    if (!empty($results)) { // if data already inserted:
        foreach ($results as $result) { ?>

            <form class="form-horizontal main-form mt-1" id="edit_form_company">

                <!-- Hidden input for company Id -->
                <input type="hidden" name="id" value="<?php echo $result->companyNo ?>">
                <!-- / Hidden input for company Id -->

                <!-- Zatca Stage & Country -->
                <div class="row mb-3">

                    <!-- Zatca Stage - Get Data From wp_options -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Zatca Stage:', 'zatca') ?></label>
                        <div class="input-group"> 
                            <select class="form-select"  name="zatca-stage">
                                <option value="1" <?php if($result->zatcaStage  == 1){ echo 'selected';}?>><?php echo _e('ZATCA V1', 'zatca') ?></option>
                                <option value="2" <?php if($result->zatcaStage  == 2){ echo 'selected';}?> ><?php echo _e('ZATCA V2', 'zatca') ?></option>
                                <option value="0" <?php if($result->zatcaStage  == 0){ echo 'selected';}?> ><?php echo _e('No ZATCA', 'zatca') ?></option>
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
                    <!-- /  Zataca Stage -->

                    <!--  Country  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Country :', 'zatca') ?></label>
                        <div class="form-group">
                        <select class="form-select select2"  name="country">
                            <option value=""> ...</option>
                            <?php 
                            global $wpdb;
                            
                            // Fetch Data From Database:
                            $countries = get_all_data('country');
                            
                            foreach($countries as $country) {?>
                                <option  value="<?php echo $country->country_id ?>" <?php if($result->countryNo == $country->country_id){echo 'selected';} ?>><?php echo $country->arabic_name ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        </div>
                    </div>
                    <!-- /  Country  field -->

                </div>
                <!-- / Zatca Stage & Country -->

                <!-- VATCategoryCode & Type -->
                <div class="row mb-3">

                    <!--  VATCategoryCode field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('VAT Category Code:', 'zatca') ?></label>
                        <div class="form-group">
                            <select class="form-select select2"  name="vat-cat-code" id="vat-cat-code">
                                <option value="">...</option>
                                <?php
                                    // Fetch Data From Database:
                                    $categories = get_all_data('met_vatcategorycode');
                                    foreach($categories as $category) {?>
                                        
                                        <option value="<?php echo $category->VATCategoryCodeNo ?>" <?php if($result->VATCategoryCode == $category->VATCategoryCodeNo){ echo 'selected';} ?> ><?php echo $category->aName. ' - ' . $category->eName ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- /  VATCategoryCode field -->

                    <!--  VATCategoryCodeSubTypeNo field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('VAT Category Code Sub Type No:', 'zatca') ?></label>
                        <div class="form-group">
                            <select class="form-select select2"  name="vat-cat-code-sub-no" id="vat-cat-code-sub">
                                <option value="">...</option>
                                <?php
                                    // Fetch Data From Database:
                                    $subCategories = get_data_with_one_condition('met_vatcategorycodesubtype', 'VATCategoryCodeNo', $result->VATCategoryCode);
                                    foreach($subCategories as $subCat) {?>
                                        
                                        <option value="<?php echo $subCat->VATCategoryCodeSubTypeNo ?>" <?php if($result->VATCategoryCodeSubTypeNo == $subCat->VATCategoryCodeSubTypeNo){ echo 'selected';} ?> ><?php echo $subCat->aName. ' - ' . $subCat->eName ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- /  VATCategoryCodeSubTypeNo field -->

                </div>
                <!-- / VATCategoryCode & Type  -->

                <!-- Second Business ID No. & Type -->
                <div class="row mb-3">

                    <!--  Second Business ID Type field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Second Business ID Type:', 'zatca') ?></label>
                        <div class="form-group">
                            <select class="form-select select2"  name="second-business-id-type">
                                <option value="">...</option>
                                <?php
                                    // Fetch Data From Database:
                                    $sellers = get_data_with_one_condition('zatcabusinessidtype', 'isSeller', 1);
                                    foreach($sellers as $seller) {?>
                                        
                                        <option value="<?php echo $seller->codeNumber ?>" <?php if($result->secondBusinessIDType == $seller->codeNumber){ echo 'selected';} ?> ><?php echo $seller->aName. ' - ' . $seller->eName ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- /  Second Business ID Type field -->


                    <!--  Second Business ID  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Second Business ID :', 'zatca') ?></label>
                        <input 
                            type="text"
                            name="second-business-id"
                            id="second-id-company"
                            class="form-control"
                            value="<?php echo $result->secondBusinessID ?>"
                        />
                    </div>
                    <!-- /  Second Business ID  field -->

                </div>
                <!-- / Second Business ID No. & Type -->

                <!-- VAT ID & aName -->
                <div class="row mb-3">

                    <!--  VAT ID field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('VAT ID:', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="vat-id"
                            id="vat_id"
                            class="form-control"
                            value="<?php echo $result->VATID ?>"
                            disabled
                        />
                    </div>
                    <!-- /  VAT ID field -->

                    <!-- hidden input to send Vat Id -->
                    <input type="hidden" value="<?php echo $result->VATID ?>" name="vat-id">
                    <!-- / hidden input to send Vat Id -->

                    <!--  companyName field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Company name:', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="name"
                            id="company_name"
                            class="form-control"
                            value="<?php echo $result->companyName ?>"
                        />
                    </div>
                    <!-- /  companyName field -->

                </div>
                <!-- / VAT ID & aName -->

                <!-- Apartment No &  PO Box Additional Number-->
                <div class="row mb-3">

                    <!--  Apartment No  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Apartment No :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="apartment-no"
                            id="appartment_no"
                            class="form-control"
                            value="<?php echo $result->apartmentNum ?>"
                        />
                    </div>
                    <!-- /  Apartment No  field -->

                    <!--  PO Box Additional Number  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('PO Box Additional Number :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="po-box-additional-no"
                            id="additional_no"
                            class="form-control"
                            value="<?php echo $result->POBoxAdditionalNum ?>"
                        />
                    </div>
                    <!-- /  PO Box Additional Number  field -->

                </div>
                <!-- / Apartment No &  PO Box Additional Number -->

                <!-- Postal Code & PO Box -->
                <div class="row mb-3">

                    <!--  PO Box  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('PO Box :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="postal-code"
                            id="company_postal_code"
                            class="form-control"
                            value="<?php echo $result->postalCode ?>"
                            
                        />
                    </div>
                    <!-- /  PO Box  field -->

                    <!--  Postal code field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Postal Code:', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="po-box"
                            id="po-company"
                            class="form-control"
                            value="<?php echo $result->POBox ?>"
                        />
                        
                    </div>
                    <!--  / Postal code field -->

                   

                </div>
                <!-- / Postal Code & PO Box -->

                <!-- Street Name Ar - En -->
                <div class="row mb-3">

                    <!--  Street Name - AR  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Street Name - AR :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="street-name-ar"
                            id="street_name_ar"
                            class="form-control"
                            value="<?php echo $result->street_Arb ?>"
                        />
                    </div>
                    <!-- /  Street Name - AR  field -->

                    <!--  Street Name - EN  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Street Name - EN :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="street-name-en"
                            id="street_name_en"
                            class="form-control"
                            value="<?php echo $result->street_Eng ?>"
                        />
                    </div>
                    <!-- /  Street Name - EN  field -->

                </div>
                <!-- / Street Name Ar - En -->

                <!-- District Name Ar - En -->
                <div class="row mb-3">

                    <!--  District Name - AR  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('District Name - AR :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="district-name-ar"
                            id="district_name_ar"
                            class="form-control"
                            value="<?php echo $result->district_Arb ?>"
                        />
                    </div>
                    <!-- /  District Name - AR  field -->

                    <!--  District Name - EN  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('District Name - EN :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="district-name-en" 
                            class="form-control"
                            value="<?php echo $result->district_Eng ?>"
                        />
                    </div>
                    <!-- /  District Name - EN  field -->

                </div>
                <!-- / District Name - AR  field -->

                <!-- City Name Ar - En -->
                <div class="row mb-3">

                    <!--  City Name - AR  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('City Name - AR :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="city-name-ar"
                            id="company_city_ar"
                            class="form-control"
                            value="<?php echo $result->city_Arb ?>"
                        />
                    </div>
                    <!-- /  City Name - AR  field -->

                    <!--  City Name - EN  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('City Name - EN :', 'zatca') ?></label>
                        <input 
                        type="text" 
                        name="city-name-en"
                        id="company_city_en"
                        class="form-control"
                        value="<?php echo $result->city_Eng ?>"
                        />
                    </div>
                    <!-- /  City Name - EN  field -->

                </div>
                <!-- / City Name Ar - En -->

                <!-- Country Subdivision Ar - En -->
                <div class="row mb-3">

                    <!--  Country Subdivision - AR  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Country Subdivision - AR :', 'zatca') ?></label>
                        <input 
                            type="text" 
                            name="country-sub-name-ar" 
                            class="form-control"
                            value="<?php echo $result->countrySubdivision_Arb ?>"
                        />
                    </div>
                    <!-- /  Country Subdivision - AR  field -->

                    <!--  Country Subdivision - EN  field -->
                    <div class="col-md-6">
                        <label class="form-label"><?php echo _e('Country Subdivision - EN :', 'zatca') ?></label>
                        <input 
                        type="text" 
                        name="country-sub-name-en" 
                        class="form-control"
                        value="<?php echo $result->countrySubdivision_Eng ?>"
                        />
                    </div>
                    <!-- /  Country Subdivision - EN  field -->

                </div>
                <!-- / Country Subdivision Ar - En -->


                <!-- ######## zatcaBranch INPUTS ######## -->
                
                <?php 
                $branches = get_all_data('zatcaBranch');
                foreach($branches as $branch){?>

                    <!--  Branch No field -->
                    <input 
                        type="hidden" 
                        name="branch-no"
                        id="branch_id"
                        class="form-control"
                        value="<?php echo $branch->buildingNo ?>"
                        disabled
                    />
                    <!-- /  Branch No field -->

                    <!-- Device & Interval &  ZATCA Invoice Type -->
                    <div class="row mb-3">

                        <!--  Device  field -->
                        <div class="col-md-4">
                            <label class="form-label"><?php echo _e('Device :', 'zatca') ?></label>
                            <select class="form-select select2"  name="device">
                                <option value=""> ...</option>
                                <?php 
                                global $wpdb;
                                
                                // Fetch Data From Database:
                                $devices = $wpdb->get_results( "SELECT * FROM zatcaDevice WHERE deviceStatus = 0 AND CsID_ExpiryDate > NOW()" );
                                // $devices = get_data_with_two_conditions('zatcaDevice', 'deviceStatus', 0, 'CsID_ExpiryDate', 'NOW()');
                                foreach($devices as $device) {?>
                                    <option  value="<?php echo $device->deviceNo ?>" <?php if($branch->deviceID == $device->deviceNo){echo 'selected';} ?>><?php echo $device->deviceCSID ?></option>
                                    <?php
                                }
                                ?>
                                ?>
                            </select>
                        </div>
                        <!-- /  Device  field -->

                        <!--  ZATCA B2C Sending Interval  field -->
                        <div class="col-md-4">
                            <label class="form-label"><?php echo _e('ZATCA B2C Sending Interval :', 'zatca') ?></label>
                            <select class="form-select select2"  name="zatca-interval">
                                <option  value="1" <?php if($branch->ZATCA_B2C_SendingIntervalType == 1){echo 'selected';} ?> >Manual يدوي </option>
                                <option  value="2" <?php if($branch->ZATCA_B2C_SendingIntervalType == 2){echo 'selected';} ?> >Instant and continue invoicing ارسال اثناء الفوترة في الخلفية</option>
                                <option  value="3" <?php if($branch->ZATCA_B2C_SendingIntervalType == 3){echo 'selected';} ?> > Instant and hold invoicing إيقاف الفوترة حتى يصل رد من هيئة الزكاة</option>
                            </select>
                        </div>
                        <!-- /  ZATCA B2C Sending Interval  field -->

                        <!--  ZATCA Invoice Type field -->
                        <div class="col-md-4">
                            <label class="form-label"><?php echo _e('ZATCA Invoices Type:', 'zatca') ?></label>
                            <select class="form-select select2"  name="zatca-invoice-type">
                                <option  value="0" <?php if($branch->zatcaInvoiceType == 0){echo 'selected';} ?> >B2C</option>
                                <option  value="1" <?php if($branch->zatcaInvoiceType == 1){echo 'selected';} ?>>B2B</option>
                                <option  value="2" <?php if($branch->zatcaInvoiceType == 2){echo 'selected';} ?>><?php echo _e('Both', 'zatca') ?></option>
                            </select>
                        </div>
                        <!-- /  ZATCA Invoice Type field -->

                    </div>
                    <!-- / Device & Interval &  ZATCA Invoice Type  -->
                
                    <?php
                }
                ?>
                <!-- ########  / zatcaBranch INPUTS ######## -->


                <!-- Submit Btn -->
                <div class="mb-3 row">
                    <div class="d-flex justify-content-center">
                        <input type="submit" value="<?php echo _e('Update Company Details', 'zatca') ?>" class="my-plugin-button" />
                    </div>
                </div>
                <!-- / Submit Btn -->

            </form>
            <?php
        }
    }else{ //If Insert Data For First Time ?>

        <form class="form-horizontal main-form mt-1" id="insert_form_company">

            <!-- Zatca Stage & Country -->
            <div class="row mb-3">

                <!-- Zatca Stage - Get Data From wp_options -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Zatca Stage:', 'zatca') ?></label>
                    <div class="input-group"> 
                        <select class="form-select"  name="zatca-stage">
                            <option value="">...</option>
                            <option value="0"><?php echo _e('No ZATCA', 'zatca') ?></option>
                            <option value="1"><?php echo _e('ZATCA V1', 'zatca') ?></option>
                            <option value="2"><?php echo _e('ZATCA V2', 'zatca') ?></option>
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
                <!-- /  Zataca Stage -->

                <!--  Country  field -->
                <div class="col-md-5">
                    <label class="form-label"><?php echo _e('Country :', 'zatca') ?></label>
                    <select class="form-select select2"  name="country">
                        <option value=""> ...</option>
                        <?php 
                        global $wpdb;
                        
                        // Fetch Data From Database:
                        $countries = $wpdb->get_results( "SELECT * FROM country" );
                        foreach($countries as $country) {?>
                            '<option  value="<?php echo $country->country_id ?>"><?php echo $country->arabic_name ?></option>';
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <!-- /  Country  field -->

            </div>
            <!-- / Zatca Stage & Country -->

            <!-- VATCategoryCode & Type -->
            <div class="row mb-3">

                <!--  VATCategoryCode field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('VAT Category Code:', 'zatca') ?></label>
                    <select class="form-select select2"  name="vat-cat-code" id="vat-cat-code">
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From Database:
                            $categories = $wpdb->get_results( "SELECT * FROM met_vatcategorycode" );
                            foreach($categories as $category) {?>
                                
                                <option value="<?php echo $category->VATCategoryCodeNo ?>"><?php echo $category->aName. ' - ' . $category->eName ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
                <!-- /  VATCategoryCode field -->

                <!--  VATCategoryCodeSubTypeNo field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('VAT Category Code Sub Type No:', 'zatca') ?></label>
                    <select class="form-select select2"  name="vat-cat-code-sub-no" id="vat-cat-code-sub">
                        <option value="">...</option>
                        <?php
                        global $wpdb;

                        // Assuming $result is set somewhere before this code
                        // If $result is potentially not an object or could be null, handle it

                        if (isset($result) && is_object($result)) {
                            $vatCatCode = $result->VATCategoryCode;

                            // Fetch Data From Database:
                            $subCategories = $wpdb->get_results($wpdb->prepare(
                                "SELECT * FROM met_vatcategorycodesubtype WHERE VATCategoryCodeNo = %d",
                                $vatCatCode
                            ));

                            foreach ($subCategories as $subCat) { ?>
                                <option value="<?php echo esc_attr($subCat->VATCategoryCodeSubTypeNo); ?>">
                                    <?php echo esc_html($subCat->aName . ' - ' . $subCat->eName); ?>
                                </option>
                            <?php }
                        } else {
                            // Handle the case where $result is not set or not an object
                            echo '<option value="">Invalid VAT Category Code</option>';
                        }
                        ?>

                    </select>
                </div>
                <!-- /  VATCategoryCodeSubTypeNo field -->

            </div>
            <!-- / VATCategoryCode & Type -->

            <!-- Second Business ID & Type -->
            <div class="row mb-3">

                <!--  Second Business ID Type field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Second Business ID Type:', 'zatca') ?></label>
                    <select class="form-select select2"  name="second-business-id-type">
                        <option value="">...</option>
                        <?php
                            global $wpdb;

                            // Fetch Data From Database:
                            $sellers = $wpdb->get_results( "SELECT * FROM zatcabusinessidtype WHERE isSeller=1" );
                            foreach($sellers as $seller) {?>
                                
                                <option value="<?php echo $seller->codeNumber ?>"><?php echo $seller->aName. ' - ' . $seller->eName ?></option>
                                <?php
                            }
                        ?>
                    </select>
                </div>
                <!-- /  Second Business ID Type field -->

                <!--  Second Business ID  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Second Business ID :', 'zatca') ?></label>
                    <input 
                        type="text"
                        name="second-business-id"
                        id="second-id-company"
                        class="form-control"
                        placeholder="<?php echo _e('Second Business ID', 'zatca') ?>"
                    />
                </div>
                <!-- /  Second Business ID  field -->

            </div>
            <!-- / Second Business ID & Type -->

            <!-- VAT ID & company name -->
            <div class="row mb-3">

                <!--  VAT ID field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('VAT ID:', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="vat-id-show"
                        id="vat_id"
                        class="form-control"
                        value="399999999900003"
                        disabled
                    />
                </div>
                <!-- /  VAT ID field -->

                <!-- hidden input to send Vat Id -->
                <input type="hidden" value="399999999900003" name="vat-id">
                <!-- / hidden input to send Vat Id -->

                <!--  companyName field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Company name:', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="name"
                        id="company_name"
                        class="form-control"
                        placeholder="<?php echo _e('company Name', 'zatca') ?>"
                    />
                </div>
                <!-- /  companyName field -->

            </div>
            <!-- / VAT ID & company name  -->

            <!-- Apartment No & PO Box Additional Number -->
            <div class="row mb-3">

                <!--  Apartment No  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Apartment No :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="apartment-no"
                        id="appartment_no"
                        class="form-control"
                        placeholder="<?php echo _e('Apartment No :', 'zatca') ?>"
                    />
                </div>
                <!-- /  Apartment No  field -->

                <!--  PO Box Additional Number  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('PO Box Additional Number :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="po-box-additional-no"
                        id="additional_no"
                        class="form-control"
                        placeholder="<?php echo _e('PO Box Additional Number :', 'zatca') ?>"
                    />
                </div>
                <!-- /  PO Box Additional Number  field -->

            </div>
            <!-- / Apartment No & PO Box Additional Number -->
            
            <!-- Postal code  & PO Box -->
            <div class="row mb-3">

                <!--  PO Box  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('PO Box :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="po-box"
                        id="po-company"
                        class="form-control"
                        placeholder="<?php echo _e('PO Box :', 'zatca') ?>"
                    />
                </div>
                <!-- /  PO Box  field -->

                <!--  Postal code field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Postal Code:', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="postal-code"
                        id="company_postal_code"
                        class="form-control"
                        placeholder="<?php echo _e('Postal Code:', 'zatca') ?>"
                    />
                </div>
                <!-- /  Postal code  field -->

            </div>
            <!-- / Postal code  & PO Box  -->


            <!-- Street Name Ar - En -->
            <div class="row mb-3">

                <!--  Street Name - AR  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Street Name - AR :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="street-name-ar"
                        id="street_name_ar"
                        class="form-control"
                        placeholder="<?php echo _e('Street Name - AR :', 'zatca') ?>"
                    />
                </div>
                <!-- /  Street Name - AR  field -->

                <!--  Street Name - EN  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Street Name - EN :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="street-name-en"
                        id="street_name_en"
                        class="form-control"
                        placeholder="<?php echo _e('Street Name - EN :', 'zatca') ?>"
                    />
                </div>
                <!-- /  Street Name - EN  field -->

            </div>
            <!-- / Street Name Ar - En -->

            <!-- District Name Ar - En -->
            <div class="row mb-3">

                <!--  District Name - AR  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('District Name - AR :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="district-name-ar"
                        id="district_name_ar"
                        class="form-control"
                        placeholder="<?php echo _e('District Name - AR :', 'zatca') ?>"
                    />
                </div>
                <!-- /  District Name - AR  field -->

                <!--  District Name - EN  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('District Name - EN :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="district-name-en" 
                        class="form-control"
                        placeholder="<?php echo _e('District Name - EN :', 'zatca') ?>"
                    />
                </div>
                <!-- /  District Name - EN  field -->

            </div>
            <!-- / District Name Ar - En  -->

            <!-- City Name Ar - En -->
            <div class="row mb-3">

                <!--  City Name - AR  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('City Name - AR :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="city-name-ar" 
                        id="company_city_ar"
                        class="form-control"
                        placeholder="<?php echo _e('City Name - AR :', 'zatca') ?>"
                    />
                </div>
                <!-- /  City Name - AR  field -->

                <!--  City Name - EN  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('City Name - EN :', 'zatca') ?></label>
                    <input 
                    type="text" 
                    name="city-name-en"
                    id="company_city_en"
                    class="form-control"
                    placeholder="<?php echo _e('City Name - EN :', 'zatca') ?>"
                    />
                </div>
                <!-- /  City Name - EN  field -->

            </div>
            <!-- / City Name Ar - En -->

            <!-- Country Subdivision Ar - En -->
            <div class="row mb-3">

                <!--  Country Subdivision - AR  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Country Subdivision - AR :', 'zatca') ?></label>
                    <input 
                        type="text" 
                        name="country-sub-name-ar" 
                        class="form-control"
                        placeholder="<?php echo _e('Country Subdivision - AR :', 'zatca') ?>"
                    />
                </div>
                <!-- /  Country Subdivision - AR  field -->

                <!--  Country Subdivision - EN  field -->
                <div class="col-md-6">
                    <label class="form-label"><?php echo _e('Country Subdivision - EN :', 'zatca') ?></label>
                    <input 
                    type="text" 
                    name="country-sub-name-en" 
                    class="form-control"
                    placeholder="<?php echo _e('Country Subdivision - EN :', 'zatca') ?>"
                    />
                </div>
                <!-- /  Country Subdivision - EN  field -->

            </div>
            <!-- / Country Subdivision Ar - En -->

            
            <!-- ######## zatcaBranch INPUTS ######## -->
            
            <!--  Branch No field -->
            <input 
                type="hidden" 
                name="branch-no"
                id="branch_id"
                class="form-control"
                value="1"
                disabled
            />
            <!-- /  Branch No field -->

            <!-- Device & Interval & ZATCA Invoice Type -->
            <div class="row mb-3">

                <!--  Device  field -->
                <div class="col-md-4">
                    <label class="form-label"><?php echo _e('Device :', 'zatca') ?></label>
                    <select class="form-select select2"  name="device">
                        <option value=""> ...</option>
                        <?php 
                        global $wpdb;
                        
                        // Fetch Data From Database:
                        $devices = $wpdb->get_results( "SELECT * FROM zatcaDevice WHERE deviceStatus = 0 AND CsID_ExpiryDate > NOW()" );
                        foreach($devices as $device) {?>
                            '<option  value="<?php echo $device->deviceNo ?>"><?php echo $device->deviceCSID ?></option>';
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <!-- /  Device  field -->

                <!--  ZATCA B2C Sending Interval  field -->
                <div class="col-md-4">
                    <label class="form-label"><?php echo _e('ZATCA B2C Sending Interval :', 'zatca') ?></label>
                    <select class="form-select select2"  name="zatca-interval">
                        <option value=""> ...</option>
                        <option  value="1">Manual يدوي </option>
                        <option  value="2">Instant and continue invoicing ارسال اثناء الفوترة في الخلفية</option>
                        <option  value="3"> Instant and hold invoicing إيقاف الفوترة حتى يصل رد من هيئة الزكاة</option>   
                    </select>
                </div>
                <!-- /  ZATCA B2C Sending Interval  field -->

                <!--  ZATCA Invoice Type field -->
                <div class="col-md-4">
                    <label class="form-label"><?php echo _e('ZATCA Invoices Type:', 'zatca') ?></label>
                    <select class="form-select select2"  name="zatca-invoice-type">
                        <option value=""> ...</option>
                        <option  value="0">B2C</option>
                        <option  value="1">B2B</option>
                        <option  value="2"><?php echo _e('Both', 'zatca') ?></option>
                    </select>
                </div>
                <!-- /  ZATCA Invoice Type field -->

            </div>
            <!-- / Device & Interval & ZATCA Invoice Type -->
            
          
            <!-- ########  / zatcaBranch INPUTS ######## -->

            <!-- Submit Btn -->
            <div class="mb-3 row">
                <div class="d-flex justify-content-center">
                    <input type="submit" value="<?php echo _e('Insert Company Details', 'zatca') ?>" class="my-plugin-button" />
                </div>
            </div>
            <!-- / Submit Btn -->

        </form>
    <?php
    }
    ?>
</div>

