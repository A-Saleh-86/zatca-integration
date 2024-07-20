jQuery(document).ready(function($) {

    ///////////////////////////Checkout Page in web Page or store/////////////////////////////////////////

    // Add your custom message or content before the "Place Order" button  
    var checkboxHtml = '<p class="form-row form-row-wide tax-invoice-request" style="width:100%;font-size:18px;">' +  
    '<label for="tax_invoice_request">' +  
    '<input type="checkbox" class="input-checkbox" name="tax_invoice_option" id="tax_invoice_option1"> Tax Invoice Request' +  
    '</label>' +  
    '</p>'; 

    // Append the custom content before the "Place Order" button  
    $('#order-notes').before(checkboxHtml);

    // hide cutome section (Customer Form Here)
    $('#insert_customer_form').hide();

    // Function to show or hide custom_section1 based on tax_invoice_option1 status
    function toggleCustomSection1Visibility() {
        if ($('#tax_invoice_option1').is(':checked')) {
            $('#insert_customer_form').show();
        } else {
            $('#insert_customer_form').hide();
        }
    }
    // Initial visibility check on page load
    toggleCustomSection1Visibility();

    // Toggle visibility on tax_invoice_option change
    $('#tax_invoice_option1').on('change', function() {
        toggleCustomSection1Visibility();
    });


    ///////////////////////////Order Page in Admin Panel/////////////////////////////////////////

    $('.custom-section').hide();
    // Function to show or hide custom section based on tax_invoice_option status
    function toggleCustomSectionVisibility() {
        if ($('#tax_invoice_option').is(':checked')) {
            $('.custom-section').show();
        } else {
            $('.custom-section').hide();
        }
    }

    // Initial visibility check on page load
    toggleCustomSectionVisibility();

    // Toggle visibility on tax_invoice_option change
    $('#tax_invoice_option').on('change', function() {
        toggleCustomSectionVisibility();
    });
  
    

});

