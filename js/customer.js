jQuery(document).ready(function($) {

    // Btn Of Copy Customer Data:
    const searchBtn = document.getElementById('search-customer-data');
                
    // Cache the table rows for better performance
    const tableRows = $('tr');

    // Get the clientVendorNo input element
    const clientVendorNoInput = document.getElementById('client-no');

    // client name ar input:
    const clientNameArabicInput = document.getElementById('client_name_ar');

    // client-name-en input:
    const clientNameEnglishInput = document.getElementById('customer_client_name_en');

    // Arabic Street input:
    const addressArabicInput = document.getElementById('address-ar');

    // English Street input:
    const addressEnglishInput = document.getElementById('address-en');

    // Arabic city input:
    const cityArabicInput = document.getElementById('city-ar');

    //English city input:
    const cityEnglishInput = document.getElementById('city-en');
    
    // Arabic District input:
    const distArabicInput = document.getElementById('dist_ar');
    
    // English District input:
    const distEnglishInput = document.getElementById('dist-en');
    
    // Arabic Sub Division input:
    const subDivArabicInput = document.getElementById('sub_div_ar');
    
    // English Sub Division input:
    const subDivEnglishInput = document.getElementById('sub_div_en');

    // Postal Code Input:
    var postal_Code = document.getElementById("customer_postal_code");
    
    // po box Input:
    var poBoxInput = document.getElementById("po-insert-customer");
    
    // Vat Id Input:
    var vatIdInput = document.getElementById("customer_vat_id");
   
    // Second Business Id Input:
    var secondBusIdInput = document.getElementById("second_bus_id");
    
    // Appartment Input:
    var appartmentInput = document.getElementById("customer_appart_no");
    
    // PO Additional No Input:
    var poAddNoInput = document.getElementById("po_add_no");

    // function to check if a string Arabic or not:
    function isArabic(string) {
        var arabicPattern = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
        return arabicPattern.test(string);
    };

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
                    title: myCustomer.notification_error_title,
                    message: msg
                    });
        
                    return false;
                }

            }else{

                if (isArabic(field.value)) {
        
                    // Error notification:
                    popupValidation.error({
                    title: myCustomer.notification_error_title,
                    message: msg
                    });
        
                    return false;
                }

            }

        }
        return true;
    }


    // Insert New Customer Form:
    $("#insert_customer_form").submit(function(event){
        
        event.preventDefault();

        // Validation on client no [ Cant null ]:
        if(clientVendorNoInput.value == ''){

            // Error notification:
            popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.client_no_must_choose
            });

            return;
        }

        // validation on aName [ Cant be null]:
        if(clientNameArabicInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.arabic_name
            });

            return;
        }

        // validation on client name ar must be Arabic:
        if(!isArabic(clientNameArabicInput.value)) {

            // Error notification:
            popupValidation.error({
            title: myCustomer.notification_error_title,
            message: myCustomer.client_name_must_arabic
            });

            return false;
        }

        // validation on client name en must be English:
        if(!charachter_lang_validation(clientNameEnglishInput, myCustomer.client_name_english_must_english, 'en')) {

            return;
        }

        // validation on Street name AR must be Arabic:
        if(!charachter_lang_validation(addressArabicInput, myCustomer.strret_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on Street name EN must be English:
        if(!charachter_lang_validation(addressEnglishInput, myCustomer.street_en_must_english, 'en')) {

            return;
        }

        // validation on City name AR must be Arabic:
        if(!charachter_lang_validation(cityArabicInput, myCustomer.city_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on City name EN must be English:
        if(!charachter_lang_validation(cityEnglishInput, myCustomer.city_en_must_english, 'en')) {

            return;
        }

        // validation on District name AR must be Arabic:
        if(!charachter_lang_validation(distArabicInput, myCustomer.dist_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on District name EN must be English:
        if(!charachter_lang_validation(distEnglishInput, myCustomer.dist_en_must_english, 'en')) {

            return;
        }

        // validation on Sub Division name AR must be Arabic:
        if(!charachter_lang_validation(subDivArabicInput, myCustomer.sub_div_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on Sub Division name EN must be English:
        if(!charachter_lang_validation(subDivEnglishInput, myCustomer.sub_div_en_must_arabic, 'en')) {

            return;
        }

        // Check If vat id not number:
        if(!isNumber(vatIdInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.vat_id_must_number
            });

            return;

        }

        // Check If appartment not number:
        if(!isNumber(appartmentInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.appartment_must_number
            });

            return;

        }

        // Check If po additional no not number:
        if(!isNumber(poAddNoInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.poAdd_must_number
            });

            return;

        }

        // Check If po box no not number:
        if(!isNumber(poBoxInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.poBox_must_number
            });

            return;

        }

        // Check If Second Bus id not number:
        if(!isNumber(secondBusIdInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.second_bus_id_number
            });

            return;

        }

        // Check If postal code not number || Not 5 digits:
        if(postal_Code.value !== ''){

            if(!isNumber(postal_Code)){
                
                 // Error notification:
                 popupValidation.error({
                    title: myCustomer.notification_error_title,
                    message: myCustomer.postal_code_must_number
                });
    
                return;
    
            }

            if(postal_Code.value.length !== 5){

                // Error notification:
                popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.postal_code_must_5_digits
            });

            return;

            }
        }

        // Define the form data:
        var formData = $(this).serialize();

        $.ajax({
            url: myCustomer.ajaxUrl,
            method: "POST", 
            data: {
                "action": "insert_customer",
                "customer_data_ajax": formData
            },
            success: function(data){

                // Check for Which Page client come from:
                if(data == "customers"){ // if from customers page:
                    
                    // success notification:
                    popup.success({
                        title: myCustomer.notification_success_title,
                        message: myCustomer.customer_inserted
                    });

                    setTimeout(function() {
                        window.location.href = myCustomer.adminUrl;
                    }, 3000); 
                    
                }else{ // if from document insert customer

                    // success notification:
                    popup.success({
                        title: myCustomer.notification_success_title,
                        message: myCustomer.customer_inserted
                    });

                    setTimeout(function() {
                        window.location.href = myCustomer.document;
                    }, 3000);

                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Get Data Table in search Modal:
    $('table').on('click', 'td', function() {

        // Remove selected from all 
        $('tr').removeClass('selected');
    
        // Add selected to clicked row
        $(this).closest('tr').addClass('selected');
    
    });

    
    // on click the customer selected element data:
    if(searchBtn){

        $('#search-customer-data').on('click', function() {

            const selectedRow = tableRows.filter('.selected');

            // If Client Not Choose a Customer:
            if (!selectedRow.length) {

                // Error notification:
                popupValidation.error({
                    title: myCustomer.notification_error_title,
                    message: myCustomer.select_customer
                });

                return;
            }

            // Get the user ID from the data-user-id attribute of the selected row
            const selectedUserId = selectedRow.data('user-id');

            // Give VendorNo The Id:
            clientVendorNoInput.value = selectedUserId;
            clientVendorNoInput.disabled = false;

            $.ajax({
                url:  myCustomer.ajaxUrl,
                method: 'POST',
                data: {
                    'action': 'customer',
                    'customer_selected': selectedUserId
                },
                
                success: function(data) {

                    // Variables Come From Ajax:
                    var postalCode = data.postalCode;
                    var first_name = data.first_name;
                    var last_name = data.last_name;
                    var address = data.address;
                    var address_2 = data.address_2;
                    var city = data.city;
                    
                    
                    // change values of inputs:
                    postal_Code.value = postalCode;
                    

                    function containsArabic(text) {
                        var arabicRegex = /[\u0600-\u06FF]/;
                        return arabicRegex.test(text);
                    }
            
                    function containsEnglish(text) {
                        var englishRegex = /^[a-zA-Z0-9\s]*$/;
                        return englishRegex.test(text);
                    }
                    
                    if (!addressArabicInput || !addressEnglishInput || !cityArabicInput || !cityEnglishInput) {
                        console.error('Address input elements not found.');
                        return;
                    }

                    // Empty the inputs if have data:
                    addressArabicInput.value = '';
                    addressEnglishInput.value = '';
                    cityArabicInput.value = '';
                    cityEnglishInput.value = '';
                    clientNameArabicInput.value = '';
                    clientNameEnglishInput.value = '';
            

                    // Check For address if address 1 is arabic:
                    if(containsArabic(address)){
    
                        if(containsArabic(address_2)){

                            // Insert New Value
                            addressArabicInput.value = address + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            addressArabicInput.value = address;
                        }

                    }

                    // Check For address if address 2 is arabic:
                    if(containsArabic(address_2)) {

                        if(containsArabic(address)){

                            // Insert New Value
                            addressArabicInput.value = address + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            addressArabicInput.value = address_2;
                        }

                    } 
                    
                    // Check For address if address 1 is english:
                    if(containsEnglish(address)) {

                        if(containsEnglish(address_2)){

                            // Insert New Value
                            addressEnglishInput.value = address + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            addressEnglishInput.value = address;
                        }

                    } 

                    // Check For address if address 2 is english:
                    if(containsEnglish(address_2)) {

                        if(containsEnglish(address)){

                            // Insert New Value
                            addressEnglishInput.value = address + ' - ' + address_2;

                        }else{

                            // Insert New Value
                            addressEnglishInput.value = address_2;
                        }

                    }
                    

                    // Check For client name arabic or english:
                    if(containsArabic(first_name) && containsArabic(last_name)  ){
                        
                        // Insert New Value
                        clientNameArabicInput.value = first_name + ' ' + last_name;

                    }else if (containsEnglish(first_name) && containsEnglish(last_name)){

                        // Insert New Value
                        clientNameEnglishInput.value = first_name + ' ' + last_name;

                    }else{

                        console.log('Unable to determine the language of the client name.');
                    }

                    // Check For city arabic or english:
                    if(containsArabic(city)){

                        // Insert New Value
                        cityArabicInput.value = city;

                    }else if(containsEnglish(city)){

                        // Insert New Value
                        cityEnglishInput.value = city;

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

    // Edit customer Form:
    $("#edit_customer_form").submit(function(event){

        event.preventDefault();

        // Validation on client no [ Cant null ]:
        if(clientVendorNoInput.value == ''){

            // Error notification:
            popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.client_no_must_choose
            });

            return;
        }

        // validation on aName [ Cant be null]:
        if(clientNameArabicInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.arabic_name
            });

            return;
        }

        // validation on client name ar must be Arabic:
        if(!isArabic(clientNameArabicInput.value)) {

            // Error notification:
            popupValidation.error({
            title: myCustomer.notification_error_title,
            message: myCustomer.client_name_must_arabic
            });

            return false;
        }

        // validation on client name en must be English:
        if(!charachter_lang_validation(clientNameEnglishInput, myCustomer.client_name_english_must_english, 'en')) {

            return;
        }

        // validation on Street name AR must be Arabic:
        if(!charachter_lang_validation(addressArabicInput, myCustomer.strret_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on Street name EN must be English:
        if(!charachter_lang_validation(addressEnglishInput, myCustomer.street_en_must_english, 'en')) {

            return;
        }

        // validation on City name AR must be Arabic:
        if(!charachter_lang_validation(cityArabicInput, myCustomer.city_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on City name EN must be English:
        if(!charachter_lang_validation(cityEnglishInput, myCustomer.city_en_must_english, 'en')) {

            return;
        }

        // validation on District name AR must be Arabic:
        if(!charachter_lang_validation(distArabicInput, myCustomer.dist_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on District name EN must be English:
        if(!charachter_lang_validation(distEnglishInput, myCustomer.dist_en_must_english, 'en')) {

            return;
        }

        // validation on Sub Division name AR must be Arabic:
        if(!charachter_lang_validation(subDivArabicInput, myCustomer.sub_div_ar_must_arabic, 'ar')) {

            return;
        }

        // validation on Sub Division name EN must be English:
        if(!charachter_lang_validation(subDivEnglishInput, myCustomer.sub_div_en_must_arabic, 'en')) {

            return;
        }

        // Check If vat id not number:
        if(!isNumber(vatIdInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.vat_id_must_number
            });

            return;

        }

        // Check If appartment not number:
        if(!isNumber(appartmentInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.appartment_must_number
            });

            return;

        }

        // Check If po additional no not number:
        if(!isNumber(poAddNoInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.poAdd_must_number
            });

            return;

        }

        // Check If po box no not number:
        if(!isNumber(poBoxInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.poBox_must_number
            });

            return;

        }

        // Check If Second Bus id not number:
        if(!isNumber(secondBusIdInput)){
            
             // Error notification:
             popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.second_bus_id_number
            });

            return;

        }

        // Check If postal code not number || Not 5 digits:
        if(postal_Code.value !== ''){

            if(!isNumber(postal_Code)){
                
                 // Error notification:
                 popupValidation.error({
                    title: myCustomer.notification_error_title,
                    message: myCustomer.postal_code_must_number
                });
    
                return;
    
            }

            if(postal_Code.value.length !== 5){

                // Error notification:
                popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.postal_code_must_5_digits
            });

            return;

            }
        }
        
        var formData = $(this).serialize();


        $.ajax({
            url: myCustomer.ajaxUrl,
            method: "POST",
            data: {
                "action": "edit_customer",
                "edit_form_data_ajax": formData
            },
            success: function(data){

                // success notification:
                popup.success({
                    title: myCustomer.notification_success_title,
                    message: data
                });

                setTimeout(function() {
                    window.location.href = myCustomer.adminUrl;
                }, 3000); 
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Delete Customer:
    $(document).on('click', '#delete_customer', function(event) {
        event.preventDefault();

        const clientNo = $(this).data('client-no');
    
        // Global Normal Notification for delete dialog:
        window.popupDialog = Notification({
            position: 'center',
            duration: 5000,
            isHidePrev: false,
            isHideTitle: false,
            maxOpened: 3,
        });
    
        // Use the delete dialog:
        popupDialog.dialog({
            title: myCustomer.delete_title,
            message: myCustomer.delete_msg,
            callback: (result) => {
                if (result != 'cancel') {

                    $.ajax({
                        url: myCustomer.ajaxUrl,
                        method: "POST",
                        data: {
                            "action": "delete_customer",
                            "client_no": clientNo
                        },
                        success: function(data){
            
                            // success notification:
                            popup.success({
                                title: myCustomer.notification_success_title,
                                message: data
                            });
            
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000); 

                            
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });

                }
            }
        });

        return false;

    });

})