jQuery(document).ready(function($) {

    // Btn Of Copy Customer Data:
    const searchBtn = document.getElementById('search-customer-data');
                
    // Cache the table rows for better performance
    const tableRows = $('tr');

    // Get the clientVendorNo input element
    const clientVendorNoInput = document.getElementById('client-no');

    // Get the client-name-ar input element to populate with fetched data
    const clientNameArabicInput = document.getElementById('client_name_ar');

    // Get the client-name-ar input element to populate with fetched data
    const clientNameEnglishInput = document.getElementById('customer_client_name_en');

    // Get the address input element to populate with fetched data
    const addressArabicInput = document.getElementById('address-ar');

    // Get the address input element to populate with fetched data
    const addressEnglishInput = document.getElementById('address-en');

    // Get the Arabic city input element to populate with fetched data
    const cityArabicInput = document.getElementById('city-ar');

    // Get the English city input element to populate with fetched data
    const cityEnglishInput = document.getElementById('city-en');

    // Get Postal Code Input element:
    var postal_Code = document.getElementById("customer_postal_code");


    // Insert New Customer Form:
    $("#insert_customer_form").submit(function(event){
        
        event.preventDefault();

        // validation on aName [ Cant be null]:
        if (clientNameArabicInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.arabic_name
            });

            return;
        }


        var formData = $(this).serialize();
        // console.log(formData)

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
            
                    // Check For address arabic or english:
                    if (containsArabic(address)) {
                        
                        // Insert New Value
                        addressArabicInput.value = address;

                    } else if (containsEnglish(address)) {

                        // Insert New Value
                        addressEnglishInput.value = address;

                    } else {

                        console.log('Unable to determine the language of the address.');
                    }

                    // Check For client name arabic or english:
                    if (containsArabic(first_name) && containsArabic(last_name)  ) {
                        
                        // Insert New Value
                        clientNameArabicInput.value = first_name + ' ' + last_name;

                    } else if (containsEnglish(first_name) && containsEnglish(last_name)) {

                        // Insert New Value
                        clientNameEnglishInput.value = first_name + ' ' + last_name;

                    } else {

                        console.log('Unable to determine the language of the client name.');
                    }

                    // Check For city arabic or english:
                    if (containsArabic(city)) {

                        // Insert New Value
                        cityArabicInput.value = city;

                    } else if (containsEnglish(city)) {

                        // Insert New Value
                        cityEnglishInput.value = city;

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

    // Edit customer Form:
    $("#edit_customer_form").submit(function(event){

        event.preventDefault();
        
        var formData = $(this).serialize();


        // validation on aName :
        if (clientNameArabicInput.value == '' ) {

            // Error notification:
            popupValidation.error({
                title: myCustomer.notification_error_title,
                message: myCustomer.arabic_name
            });
            return;
        }

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

})