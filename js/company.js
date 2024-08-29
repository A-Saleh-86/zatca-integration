// Company Details:
jQuery(document).ready(function($) {

    // Second Bussiness Id Input:
    const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');
    const copyBtn = document.getElementById('copy-company-data');
    const streetNameArInput = document.getElementById('street_name_ar');
    const streetNameEnInput = document.getElementById('street_name_en');
    const cityArInput = document.getElementById('company_city_ar');
    const cityEnInput = document.getElementById('company_city_en');
    const postalCodeInput = document.getElementById('company_postal_code');
    const districtNameArInput = document.getElementById('district_name_ar');
    const districtNameEnInput = document.getElementById('district_name_en');
    const subDivArInput = document.getElementById('sub_div_ar');
    const subDivEnInput = document.getElementById('sub_div_en');
    const companyNameInput = document.getElementById('company_name');
    const appartmentNoInput = document.getElementById('appartment_no');
    const additionalNoInput = document.getElementById('additional_no');
    const secBusIdInput = document.getElementById('second-id-company');


    // Change Vat_Category_Code_Sub_Type Depend On Vat_Category_Code Selected:
    $('select#vat-cat-code').on('change', function(event) {
        
        const selectedVatCat = this.value;

        // Make an AJAX request to fetch data based on selectedUserId
        $.ajax({
            url: myCompany.ajaxUrl,
            method: 'POST',
            data: {
                'action': 'company',
                'vat_cat_code_ajax': selectedVatCat
            },
            
            success: function(data) {
                
                // console.log(data);
                vatCatCodeSubInput.innerHTML = data; 

            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    // on click get Company Data From wp_options:
    if (copyBtn) {
        $('#copy-company-data').on('click', function() {

            // Make an AJAX request to fetch data based on selectedUserId
            $.ajax({
                url: myCompany.ajaxUrl,
                method: 'POST',
                data: {
                    'action': 'woo-company-data'
                },
                
                success: function(data) {

                    // Variables Come From Ajax:
                    var postalCode = data.postal_code;
                    var address_1 = data.address;
                    var address_2 = data.address_2;
                    var city = data.city;
                    
                    // change values of inputs:
                    postalCodeInput.value = postalCode;

                    function containsArabic(text) {
                        var arabicRegex = /[\u0600-\u06FF]/;
                        return arabicRegex.test(text);
                    }
            
                    function containsEnglish(text) {
                        var englishRegex = /^[a-zA-Z0-9\s]*$/;
                        return englishRegex.test(text);
                    }

                    // Empty the inputs if have data:
                    streetNameArInput.value = '';
                    streetNameEnInput.value = '';
                    cityArInput.value = '';
                    cityEnInput.value = '';

                    // Check For address if address 1 is arabic:
                    if(containsArabic(address_1)){
                        
                        if(containsArabic(address_2)){

                            // Insert New Value
                            streetNameArInput.value = address_1 + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            streetNameArInput.value = address_1;
                        }

                    }

                    // Check For address if address 2 is arabic:
                    if(containsArabic(address_2)) {

                        if(containsArabic(address_1)){

                            // Insert New Value
                            streetNameArInput.value = address_1 + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            streetNameArInput.value = address_2;
                        }

                    } 
                    
                    // Check For address if address 1 is english:
                    if(containsEnglish(address_1)) {

                        if(containsEnglish(address_2)){

                            // Insert New Value
                            streetNameEnInput.value = address_1 + ' - ' + address_2;
                            // streetNameEnInput.value = address_1;

                        }else{

                            // Insert New Value
                            streetNameEnInput.value = address_1;
                        }

                    } 

                    // Check For address if address 2 is english:
                    if(containsEnglish(address_2)) {

                        if(containsEnglish(address_1)){

                            // Insert New Value
                            streetNameEnInput.value = address_1 + ' - ' + address_2;
                            // streetNameEnInput.value = address_2;

                        }else{

                            // Insert New Value
                            streetNameEnInput.value = address_2;
                        }

                    }

                    // Check For city arabic or english:
                    if (containsArabic(city)) {

                        // Insert New Value
                        cityArInput.value = city;

                    }else if(containsEnglish(city)) {

                        // Insert New Value
                        cityEnInput.value = city;

                    }else{

                        console.log('Unable to determine the language of the city.');
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    }

    // Check if number or not:
    function isNumber(field) {

        var nonNumericPattern = /[^0-9]/;
    
        return !nonNumericPattern.test(field.value);
    }

    // Function to get error if Field must arabic and it have English:
    function charachter_lang_validation(field, msg, lang){

        if(field.value != ''){

            if(lang == 'ar'){

                if (!isArabic(field.value)) {
        
                    // Error notification:
                    popupValidation.error({
                    title: myCompany.notification_error_title,
                    message: msg
                    });
        
                    return false;
                }

            }else{

                if (isArabic(field.value)) {
        
                    // Error notification:
                    popupValidation.error({
                    title: myCompany.notification_error_title,
                    message: msg
                    });
        
                    return false;
                }

            }

        }
        return true;
    }

    // Insert Company Details Form [ For First Time ]:
    $('#insert_form_company').submit(function(event){
        
        event.preventDefault();

        // validation on city arabic name input [ Cant be Null ]:
        if (cityArInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.city_ar_null
            });

            return;
        }

        // validation on city name AR must be Arabic:
        if(!charachter_lang_validation(cityArInput, myCompany.city_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on city name EN must be English:
        if(!charachter_lang_validation(cityEnInput, myCompany.city_en_must_english, 'en')) {

            return;
        }

        // validation on street arabic name input:
        if (streetNameArInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.street_ar_null
            });

            return;
        }

        // validation on city name AR must be Arabic:
        if(!charachter_lang_validation(streetNameArInput, myCompany.street_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on street name EN must be English:
        if(!charachter_lang_validation(streetNameEnInput, myCompany.street_en_must_english, 'en')) {

            return;
        }

        // validation on district arabic name input:
        if (districtNameArInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.dist_ar_null
            });

            return;
        }

        // validation on district name AR must be Arabic:
        if(!charachter_lang_validation(districtNameArInput, myCompany.dist_ar_must_arabic, 'ar')) {

            return;
        }
        
        // validation on district name EN must be English:
        if(!charachter_lang_validation(districtNameEnInput, myCompany.dist_en_must_english, 'en')) {
            
            return;
        }

        // validation on subdivision name AR must be Arabic:
        if(!charachter_lang_validation(subDivArInput, myCompany.subDiv_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on subdivison name EN must be English:
        if(!charachter_lang_validation(subDivEnInput, myCompany.subDiv_en_must_english, 'en')) {
    
            return;
        }

        // validation on company name input:
        if (companyNameInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.company_name
            });

            return;
        }

        // Check If second business id not number:
        if(!isNumber(secBusIdInput)){
    
            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.sec_bus_must_number
            });

            return;

        }

        // validation on appartment No input [ cant be null ]:
        if (appartmentNoInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.appartment_no_null
            });

            return;
        }

        // Check If appartment no not number:
        if(!isNumber(appartmentNoInput)){

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.appart_must_number
            });

            return;

        }

        // validation on additional No input:
        if (additionalNoInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.po_box_additional_null
            });

            return;
        }

        // Check If PO additional no not number:
        if(!isNumber(additionalNoInput)){

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.po_additional_no_must_number
            });

            return;

        }

        var formData = $(this).serialize();

        $.ajax({
            url: myCompany.ajaxUrl,
            method: "POST", 
            data: {
                "action": "submit_company",
                "form_data_ajax_company": formData,
                "Status":"Insert"
            },
            success: function(data){

                // success notification:
                popup.success({
                    title: myCompany.notification_success_title,
                    message: data
                });

                setTimeout(function() {
                    window.location.reload();
                }, 3000); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

    })

    // Edit Company Details Form :
    $('#edit_form_company').submit(function(event){
        
        event.preventDefault();

        // $("#branch_id").prop("disabled", false);

        var formData = $(this).serialize();

        // validation on city arabic name input:
        if (cityArInput.value == null || cityAr.value === '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.city_ar
            });

            return;
        }

        // validation on street arabic name input:
        if (streetNameArInput.value == null || streetNameArInput.value === '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.street_ar
            });

            return;
        }

        // validation on district arabic name input:
        if (districtNameArInput.value == null || districtNameArInput.value === '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.dist_ar
            });

            return;
        }

        // validation on company name input:
        if (companyNameInput.value == null || companyNameInput.value === '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.company_name
            });

            return;
        }

        // validation on appartment No input:
        if (appartmentNoInput.value == null || appartmentNoInput.value === '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.appartment_no
            });

            return;
        }

        // validation on additional No input:
        if (additionalNoInput.value == null || additionalNoInput.value === '' ) {

            // Error notification:
            popupValidation.error({
                title: myCompany.notification_error_title,
                message: myCompany.po_box_additional
            });

            return;
        }


        $.ajax({
            url: myCompany.ajaxUrl,
            method: "POST",
            data: {
                "action": "submit_company",
                "form_data_ajax_company": formData,
                "Status":"Edit"
            },
            success: function(data){

                // success notification:
                popup.success({
                    title: myCompany.notification_success_title,
                    message: data
                });

                setTimeout(function() {
                    window.location.reload();
                }, 3000); 
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    })

})