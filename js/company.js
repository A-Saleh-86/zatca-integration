// Script For Company Details For First time:
jQuery(document).ready(function($) {

    // Second Bussiness Id Input:
    const secondBusIdInput = document.getElementById('second-id-company');
    const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');
    var po = document.getElementById("po-company");
    var cityAr = document.getElementById("city_ar");
    const copyBtn = document.getElementById('copy-company-data');
    const streetNameArInput = document.getElementById('street_name_ar');
    const streetNameEnInput = document.getElementById('street_name_en');
    const cityArInput = document.getElementById('city_ar');
    const cityEnInput = document.getElementById('city_en');
    const postalCodeInput = document.getElementById('postal_code');
    const districtNameArInput = document.getElementById('district_name_ar');
    const companyNameInput = document.getElementById('company_name');
    const appartmentNoInput = document.getElementById('appartment_no');
    const additionalNoInput = document.getElementById('additional_no');

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

                    // Check For address arabic or english:
                    if (containsArabic(address_1)) {
                        
                        if(containsArabic(address_2)){

                            // Insert New Value
                            streetNameArInput.value = address_1 + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            streetNameArInput.value = address_1;
                        }

                    } else if(containsArabic(address_2)) {

                        if(containsArabic(address_1)){

                            // Insert New Value
                            streetNameArInput.value = address_1 + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            streetNameArInput.value = address_2;
                        }

                    }else if(containsEnglish(address_1)) {

                        if(containsEnglish(address_2)){

                            // Insert New Value
                            streetNameEnInput.value = address_1 + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            streetNameEnInput.value = address_1;
                        }

                    } else if(containsEnglish(address_2)) {

                        if(containsEnglish(address_1)){

                            // Insert New Value
                            streetNameEnInput.value = address_1 + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            streetNameEnInput.value = address_2;
                        }

                    } else {

                        console.log('Unable to determine the language of the address.');
                    }

                    // Check For city arabic or english:
                    if (containsArabic(city)) {

                        // Insert New Value
                        cityArInput.value = city;

                    } else if (containsEnglish(city)) {

                        // Insert New Value
                        cityEnInput.value = city;

                    } else {

                        console.log('Unable to determine the language of the city.');
                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    }


    // Insert Company Details Form [ For First Time ]:
    $('#insert_form_company').submit(function(event){
        
        event.preventDefault();

        $("#branch_id").prop("disabled", false);

        var formData = $(this).serialize();

        // console.log(formData)

        // validation on second Bussiness Id input:
        if (secondBusIdInput.value.length != 10 ) {
            alert(myCompany.second_bus);
            return;
        }

        // validation on city arabic name input:
        if (cityArInput.value == null || cityAr.value === '' ) {
            alert(myCompany.city_ar);
            return;
        }

        // validation on street arabic name input:
        if (streetNameArInput.value == null || streetNameArInput.value === '' ) {
            alert(myCompany.street_ar);
            return;
        }

        // validation on district arabic name input:
        if (districtNameArInput.value == null || districtNameArInput.value === '' ) {
            alert(myCompany.dist_ar);
            return;
        }

        // validation on company name input:
        if (companyNameInput.value == null || companyNameInput.value === '' ) {
            alert(myCompany.company_name);
            return;
        }

        // validation on appartment No input:
        if (appartmentNoInput.value == null || appartmentNoInput.value === '' ) {
            alert(myCompany.appartment_no);
            return;
        }

        // validation on additional No input:
        if (additionalNoInput.value == null || additionalNoInput.value === '' ) {
            alert(myCompany.po_box_additional);
            return;
        }


        $.ajax({
            url: myCompany.ajaxUrl,
            method: "POST", 
            data: {
                "action": "submit_company",
                "form_data_ajax_company": formData,
                "Status":"Insert"
            },
            success: function(data){
                // console.log(data);
                alert(data);
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    })

    // Edit Company Details Form :
    $('#edit_form_company').submit(function(event){
        
        event.preventDefault();

        $("#branch_id").prop("disabled", false);

        var formData = $(this).serialize();

        // validation on second Bussiness Id input:
        if (secondBusIdInput.value.length != 10 ) {
            alert(myCompany.second_bus);
            return;
        }

        // validation on city arabic name input:
        if (cityArInput.value == null || cityAr.value === '' ) {
            alert(myCompany.city_ar);
            return;
        }

        // validation on street arabic name input:
        if (streetNameArInput.value == null || streetNameArInput.value === '' ) {
            alert(myCompany.street_ar);
            return;
        }

        // validation on district arabic name input:
        if (districtNameArInput.value == null || districtNameArInput.value === '' ) {
            alert(myCompany.dist_ar);
            return;
        }

        // validation on company name input:
        if (companyNameInput.value == null || companyNameInput.value === '' ) {
            alert(myCompany.company_name);
            return;
        }

        // validation on appartment No input:
        if (appartmentNoInput.value == null || appartmentNoInput.value === '' ) {
            alert(myCompany.appartment_no);
            return;
        }

        // validation on additional No input:
        if (additionalNoInput.value == null || additionalNoInput.value === '' ) {
            alert(myCompany.po_box_additional);
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

                // console.log(data);
                alert(data);
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    })

})