<?php
include_once dirname( plugin_basename( __FILE__ ) ) . '.php';  
//include_once dirname(dirname(__FILE__)) . 'zatca.php'; 

global $wpdb;

// Get User Id:
$user_id = get_current_user_id();


// Get Data From zatcaCustomer:
$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM zatcaCustomer WHERE clientVendorNo = $user_id") );


if(!empty($results)){

    foreach($results as $result){?>

        <!-- Input Form -->
        <form class="form-horizontal main-form mt-1" id="customForm">

            <div id="billing" class="wc-block-components-address-form checkout-inputs">
                
                <!--  Client Name Arabic field & District Name - AR field -->
                <div class="row g-3 mb-3">
                    
                    <!--  Client Name Arabic field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="client-name-ar-input"
                                name="client-name-ar"
                                id="client_name_ar"
                                value="<?php echo $result->aName ?>"
                                placeholder=""
                                required>
                            <label for="client_name_ar" class="floating-label"><?php echo _e('Client Name ( Arabic )', 'zatca') ?></label>
                            <div class="error-message" ></div>
                        </div>
                    </div>
                    <!--  / Client Name Arabic field -->

                    <!--  District Name - AR field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="district-name-ar-input"
                                name="district-name-ar"
                                id="dist_ar"
                                value="<?php echo $result->district_Arb ?>"
                                placeholder=""
                                >
                            <label for="district-name-ar" class="floating-label"><?php echo _e('District Name (Arabic)', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / District Name - AR field -->

                </div>
                <!-- / Client Name Arabic field & District Name - AR field -->
               
                <!--  Address Name - AR field & City Name - AR field -->
                <div class="row g-3 mb-3">

                    <!--  Address Name - AR field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="address-name-ar-input"
                                name="address-name-ar"
                                id="address_ar"
                                value="<?php echo $result->street_Arb ?>"
                                placeholder=""
                                >
                            <label for="address-name-ar" class="floating-label"><?php echo _e('Address Name (Arabic)', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / Address Name - AR field -->

                    <!--  City Name - AR field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="city-name-ar-input"
                                name="city-name-ar"
                                id="city_ar"
                                value="<?php echo $result->city_Arb ?>"
                                placeholder=""
                                >
                            <label for="city-name-ar" class="floating-label"><?php echo _e('City Name (Arabic)', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / City Name - AR field -->

                </div>
                <!--  Address Name - AR field & City Name - AR field -->


                <!--  Vat ID field & Apartment No field -->
                <div class="row g-3 mb-3">

                    <!--  Vat ID field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="vat-id-input"
                                name="vat-id"
                                id="vat-id"
                                value="<?php echo $result->VATID ?>"
                                placeholder=""
                                >
                            <label for="vat-id" class="floating-label"><?php echo _e('VAT ID', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / Vat ID field -->

                    <!--  Apartment No field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="apartment-no-input"
                                name="apartment-no"
                                id="apartment-no"
                                value="<?php echo $result->apartmentNum ?>"
                                placeholder=""
                                >
                            <label for="apartment-no" class="floating-label"><?php echo _e('Apartment No', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / Apartment No field -->

                </div>
                <!--  / Vat ID field & Apartment No field -->


                <!--  Postal field & Second Business ID field-->
                <div class="row g-3 mb-3">

                    <!--  Postal field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="postal-code"
                                name="postal-code"
                                id="postal_code"
                                value="<?php echo $result->postalCode ?>"
                                placeholder=""
                                >
                            <label for="postal-code" class="floating-label"><?php echo _e('Postal Code', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / Postal field -->

                    <!--  Second Business ID field -->
                    <div class="col-md-6">
                        <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                            <input 
                                type="text"
                                class="second-bussiness-id"
                                name="second-business-id"
                                id="second-business-id"
                                value="<?php echo $result->secondBusinessID ?>"
                                placeholder=""
                                >
                            <label for="second-business-id" class="floating-label"><?php echo _e('Second Business ID', 'zatca') ?></label>
                        </div>
                    </div>
                    <!--  / Second Business ID field -->

                </div>
                <!--  / Postal field & Second Business ID field-->
            
                <!-- Second Business ID Type field -->
                <div id="new-combobox" class="wc-block-components-combobox wc-block-components-address-form__state wc-block-components-state-input">
                    <div>
                        <div class="components-base-control wc-block-components-combobox-control components-combobox-control css-1wzzj1a e1puf3u3">
                            <div class="components-base-control__field css-1kyqli5 e1puf3u2">
                                <label class="components-base-control__label css-4dk55l e1puf3u1" for="second-business-id-type"><?php echo _e('Second Business ID Type', 'zatca')?></label>
                                <div class="components-combobox-control__suggestions-container" tabindex="-1">
                                    <div data-wp-c16t="true" data-wp-component="Flex" class="components-flex css-dfwk0q css-0 em57xhy0">
                                        <div data-wp-c16t="true" data-wp-component="FlexBlock" class="components-flex-item components-flex-block css-106zala css-0 em57xhy0">
                                            <select name="second-business-id-type" id="second-business-id-type" class="components-combobox-control__input components-form-token-field__input" aria-label="second-business-id-type" aria-describedby="components-form-token-suggestions-howto-1">
                                                <option value="0">...</option>
                                                <?php 
                                                global $wpdb;
                                                $buyers = $wpdb->get_results("SELECT * FROM zatcabusinessidtype WHERE isBuyer=1");
                                                foreach($buyers as $buyer) {
                                                    $selected = ($result->secondBusinessIDType == $buyer->codeNumber) ? ' selected="selected"' : '';
                                                    echo '<option value="'.$buyer->codeNumber.'"'.$selected.'>'.$buyer->aName. ' - ' . $buyer->eName.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Second Business ID Type field -->


            </div>

            <!-- Hidden Inputs -->

                <!-- Hidden Input for operation Type -->
                <input type="hidden" name="operation-type" value="edit">
                <!-- / Hidden Input for operation Type -->

                <!-- Hidden Input for Client Id -->
                <input type="hidden" name="client-id" value="<?php echo $user_id; ?>">
                <!-- / Hidden Input for Client Id -->

                <!--  Hidden Input Client Name English field -->
                <input type="hidden" name="client-name-en" id="client_name_en" >
                <!--  / Hidden Input Client Name English field -->

                <!--  Hidden Input Address Name - En field -->
                <input  type="hidden" name="address-name-en" id="address_en">
                <!--  / Hidden Input Address Name - En field -->

                <!--  Hidden Input City Name - En field -->
                <input type="hidden" name="city-name-en" id="city_en" >
                <!--  / Hidden Input City Name - En field -->

            <!-- / Hidden Inputs -->

            <!-- <div class="mb-3 row">
                <div class="d-flex justify-content-center">
                    <input type="submit" value="<?php //echo _e('Confirm Data', 'zatca') ?>" class="my-plugin-button " />
                </div>
            </div> -->

        </form>
        <!-- / Input Form -->
        <?php
    }
        
}else{?>

    <!-- Input Form -->
    <form class="form-horizontal main-form mt-1" id="customForm">
        
        <div id="billing" class="wc-block-components-address-form checkout-inputs">

            <!--  Client Name Arabic field & District Name - AR field -->
            <div class="row g-3 mb-3">

                <!--  Client Name Arabic field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="client-name-ar-input"
                            name="client-name-ar"
                            id="client_name_ar"
                            placeholder=""
                            required>
                        <label for="client_name_ar" class="floating-label"><?php echo _e('Client Name ( Arabic )', 'zatca') ?></label>
                        <div class="error-message" ></div>
                    </div>
                </div>
                <!--  / Client Name Arabic field -->

                <!--  District Name - AR field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="district-name-ar-input"
                            name="district-name-ar"
                            id="dist_ar"
                            placeholder=""
                            >
                        <label for="district-name-ar" class="floating-label"><?php echo _e('District Name (Arabic)', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / District Name - AR field -->
            </div>
            <!-- / Client Name Arabic field & District Name - AR field -->
            

            <!-- Address Name - AR field & City Name - AR field -->
            <div class="row g-3 mb-3">

                <!--  Address Name - AR field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="address-name-ar-input"
                            name="address-name-ar"
                            id="address_ar"
                            placeholder=""
                            >
                        <label for="address-name-ar" class="floating-label"><?php echo _e('Address Name (Arabic)', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / Address Name - AR field -->

                <!--  City Name - AR field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="city-name-ar-input"
                            name="city-name-ar"
                            id="city_ar"
                            placeholder=""
                            >
                        <label for="city-name-ar" class="floating-label"><?php echo _e('City Name (Arabic)', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / City Name - AR field -->

            </div>
            <!--  / Address Name - AR field & City Name - AR field -->


            <!-- Vat ID field & Apartment No field -->
            <div class="row g-3 mb-3">

                <!--  Vat ID field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="vat-id-input"
                            name="vat-id"
                            id="vat-id"
                            placeholder="">
                        <label for="vat-id" class="floating-label"><?php echo _e('VAT ID', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / Vat ID field -->

                <!--  Apartment No field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="apartment-no-input"
                            name="apartment-no"
                            id="apartment-no"
                            placeholder=""
                            >
                        <label for="apartment-no" class="floating-label"><?php echo _e('Apartment No', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / Apartment No field -->

            </div>
            <!-- / Vat ID field & Apartment No field -->

            
            
            <!-- Postal field & Second Business ID field -->
            <div class="row g-3 mb-3">

                <!--  Postal field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="postal-code"
                            name="postal-code"
                            id="postal_code"
                            placeholder=""
                            >
                        <label for="postal-code" class="floating-label"><?php echo _e('Postal Code', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / Postal field -->

                <!--  Second Business ID field -->
                <div class="col-md-6">
                    <div class="wc-block-components-text-input wc-block-components-address-form__postcode">
                        <input 
                            type="text"
                            class="second-bussiness-id"
                            name="second-business-id"
                            id="second-business-id"
                            placeholder=""
                            >
                        <label for="second-business-id" class="floating-label"><?php echo _e('Second Business ID', 'zatca') ?></label>
                    </div>
                </div>
                <!--  / Second Business ID field -->

            </div>
            <!-- / Postal field & Second Business ID field -->

            <!-- Second Business ID Type field -->
            <div id="new-combobox" class="wc-block-components-combobox wc-block-components-address-form__state wc-block-components-state-input">
                <div>
                    <div class="components-base-control wc-block-components-combobox-control components-combobox-control css-1wzzj1a e1puf3u3">
                        <div class="components-base-control__field css-1kyqli5 e1puf3u2">
                            <label class="components-base-control__label css-4dk55l e1puf3u1" for="second-business-id-type"><?php echo _e('Second Business ID Type', 'zatca')?></label>
                            <div class="components-combobox-control__suggestions-container" tabindex="-1">
                                <div data-wp-c16t="true" data-wp-component="Flex" class="components-flex css-dfwk0q css-0 em57xhy0">
                                    <div data-wp-c16t="true" data-wp-component="FlexBlock" class="components-flex-item components-flex-block css-106zala css-0 em57xhy0">
                                        <select name="second-business-id-type" id="second-business-id-type" class="components-combobox-control__input components-form-token-field__input second-business-id-type" aria-label="second-business-id-type" aria-describedby="components-form-token-suggestions-howto-1">
                                            <?php 
                                            global $wpdb;
                                            $buyers = $wpdb->get_results("SELECT * FROM zatcabusinessidtype WHERE isBuyer=1");
                                            echo '<option value="..">..</option>';
                                            foreach($buyers as $buyer) {
                                                
                                                echo '<option value="'.$buyer->codeNumber.'">'.$buyer->aName. ' - ' . $buyer->eName.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- / Second Business ID Type field -->


        </div>

        <!-- Hidden Inputs -->

            <!-- Hidden Input for Client Id -->
            <input type="hidden" name="client-id" value="<?php echo $user_id; ?>">
            <!-- / Hidden Input for Client Id -->
            
            <!--  Hidden Input Client Name English field -->
            <input type="hidden" name="client-name-en" id="client_name_en" >
            <!--  / Hidden Input Client Name English field -->

            <!--  Hidden Input Address Name - En field -->
            <input  type="hidden" name="address-name-en" id="address_en">
            <!--  / Hidden Input Address Name - En field -->
            
            <!--  Hidden Input City Name - En field -->
            <input type="hidden" name="city-name-en" id="city_en" >
            <!--  / Hidden Input City Name - En field -->

        <!-- / Hidden Inputs -->

        <!-- <div class="mb-3 row">
            <div class="d-flex justify-content-center">
                <input type="submit" value="<?php //echo _e('Confirm Data', 'zatca') ?>" class="my-plugin-button " />
            </div>
        </div> -->

    </form>
    <!-- / Input Form -->

<?php
}

?>


