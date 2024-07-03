// Script For Company Details For First time:
jQuery(document).ready(function($) {

    // Second Bussiness Id Input:
    const secondBusIdInput = document.getElementById('second-id-company');
    const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');
    var po = document.getElementById("po-company");
    var cityAr = document.getElementById("city_ar");
    const copyBtn = document.getElementById('copy-company-data');
    const comAddressInput = document.getElementById('company-address');
    const comCityInput = document.getElementById('company-city');

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
                    
                    comAddressInput.value = data.address;
                    comCityInput.value = data.city;
                    

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

        var formData = $(this).serialize();

        // validation on second Bussiness Id input:
        if (secondBusIdInput.value.length != 10 ) {
            msg = "<?php echo _e('Second Business Id Must be 10 Digits.', 'zatca') ?>"
            // alert("Second Business Id Must be 10 Digits.");
            alert(msg);
            return;
        }

        // validation on PO input:
        if (po.value.length != 5 ) {
            msg = "<?php echo _e('Po Box Must be 5 Digits.', 'zatca') ?>"
            alert(msg);
            // alert("Po Box Must be 5 Digits.");
            return;
        }

        // validation on city arabic name input:
        if (cityAr.value == null || cityAr.value === '' ) {
            msg = "<?php echo _e('Please Insert City Name.', 'zatca') ?>"
            alert(msg);
            // alert("Po Box Must be 5 Digits.");
            return;
        }


        $.ajax({
            url: myCompany.ajaxUrl,
            method: "POST", // Specify the method
            data: {
                "action": "submit_company",
                "form_data_ajax_company": formData,
                "Status":"Insert"
            },
            success: function(data){
                // console.log(data);
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

        var formData = $(this).serialize();

        // validation on second Bussiness Id input:
        if (secondBusIdInput.value.length != 10 ) {
            msg = "<?php echo _e('Second Business Id Must be 10 Digits.', 'zatca') ?>"
            // alert("Second Business Id Must be 10 Digits.");
            alert(msg);
            return;
        }

        // validation on PO input:
        if (po.value.length != 5 ) {
            msg = "<?php echo _e('Po Box Must be 5 Digits.', 'zatca') ?>"
            alert(msg);
            // alert("Po Box Must be 5 Digits.");
            return;
        }

        // validation on city arabic name input:
        if (cityAr.value == null || cityAr.value === '' ) {
            // msg = "<?php echo _e('Please Insert City Name.', 'zatca') ?>"
            // alert(msg);
            alert("Please Insert City Name.");
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