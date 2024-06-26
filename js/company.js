// Script For Company Details For First time:
jQuery(document).ready(function($) {

    // Second Bussiness Id Input:
    const secondBusIdInput = document.getElementById('second-id-company');
    const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');
    var po = document.getElementById("po-company");
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

      // Add event listener to get Company Data From wp_options:
      copyBtn.addEventListener('click', function() {

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

    // Insert Company Details Form [ For First Time ]:
    $('#insert_form_company').submit(function(event){
        
        event.preventDefault();

        var formData = $(this).serialize();

        // validation on second Bussiness Id input:
        if (secondBusIdInput.value.length != 10 ) {
            alert("Second Business Id Must be 10 Digits.");
            return;
        }

        // validation on PO input:
        if (po.value.length != 5 ) {
            alert("Po Box Must be 5 Digits.");
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
            alert("Second Business Id Must be 10 Digits.");
            return;
        }

        // validation on PO input:
        if (po.value.length != 5 ) {
            alert("Po Box Must be 5 Digits.");
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
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    })

})