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

    // Get the client-name-eng input element to populate with fetched data
    // const clientNameInput = document.getElementById('client-name');

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
    
    // Get second_bus_id Input element:
    var second_bus_id_input = document.getElementById("second_bus_id");

    // Get dist_ar Input element:
    var dist_ar_input = document.getElementById("dist_ar");


    // Insert New Customer Form:
    $("#insert_customer_form").submit(function(event){
        event.preventDefault();


        // validation on postalCode input:
        // if (postal_Code.value == '' ) {
        //     // alert("Postal Code Cant be Null");
        //     alert(myCustomer.postal_null);
        //     return;
        // }
       

        // validation on postalCode input:
        // if (postal_Code.value.length != 5 ) {
        //     // alert("Postal Code Must be 5 Digits.");
        //     alert(myCustomer.postal_digits);
        //     return;
        // }

        // validation on addressArabicInput :
        // if (addressArabicInput.value == '' ) {
        //     // alert("Street Arabic Name Cant be Null.");
        //     alert(myCustomer.street);
        //     return;
        // }

        // validation on second_bus_id_input :
        // if (second_bus_id_input.value == '' ) {
        //     // alert("second business id Cant be Null.");
        //     alert(myCustomer.second_id);
        //     return;
        // }

        // validation on dist_ar_input input:
        // if (dist_ar_input.value == '' ) {
        //     // alert("District Arabic Name Cant be Null.");
        //     alert(myCustomer.district);
        //     return;
        // }

        // validation on cityArabicInput :
        // if (cityArabicInput.value == '' ) {
        //     // alert("City Arabic Name Cant be Null.");
        //     alert(myCustomer.city);
        //     return;
        // }

        // validation on aName :
        if (clientNameArabicInput.value == '' ) {
            // alert("City Arabic Name Cant be Null.");
            alert(myCustomer.arabic_name);
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
                // console.log(data);
                // alert(data);

                // Check for Which Page client come from:
                if(data == "customers"){ // if from customers page:
                    
                    // alert('Customer Inserted Success')
                    alert(myCustomer.customer_inserted);
                    window.location.href = myCustomer.adminUrl;
                    
                }else{ // if from document insert customer
                    
                    // alert('Customer Inserted Success')
                    alert(myCustomer.customer_inserted);
                    window.location.href = myCustomer.document;
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
                // alert('Please select a customer first.');
                alert(myCustomer.select_customer);
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
        // clientVendorNoInput.disabled = false
        var formData = $(this).serialize();

        // validation on postalCode input:
        // if (postal_Code.value == '' ) {
        //     // alert("Postal Code Cant be Null.");
        //     alert(myCustomer.postal_null);
        //     return;
        // }

        // validation on postalCode input:
        // if (postal_Code.value.length != 5 ) {
        //     // alert("Postal Code Must be 5 Digits.");
        //     alert(myCustomer.postal_digits);
        //     return;
        // }

        // validation on addressArabicInput :
        // if (addressArabicInput.value == '' ) {
        //     // alert("Street Arabic Name Cant be Null.");
        //     alert(myCustomer.street);
        //     return;
        // }

        // validation on second_bus_id_input :
        // if (second_bus_id_input.value == '' ) {
        //     // alert("second business id Cant be Null.");
        //     alert(myCustomer.second_id);
        //     return;
        // }

        // validation on dist_ar_input :
        // if (dist_ar_input.value == '' ) {
        //     // alert("District Arabic Name Cant be Null.");
        //     alert(myCustomer.district);
        //     return;
        // }

        // validation on cityArabicInput :
        // if (cityArabicInput.value == '' ) {
        //     // alert("City Arabic Name Cant be Null.");
        //     alert(myCustomer.city);
        //     return;
        // }

        // validation on aName :
        if (clientNameArabicInput.value == '' ) {
            // alert("City Arabic Name Cant be Null.");
            alert(myCustomer.arabic_name);
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
                
                alert(data);
                window.location.href = myCustomer.adminUrl;
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

})