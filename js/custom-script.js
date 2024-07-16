jQuery(document).ready(function($) {

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

// Add your custom message or content before the "Place Order" button  
var checkboxHtml = '<p class="form-row form-row-wide tax-invoice-request" style="width:100%;font-size:20px;">' +  
'<label for="tax_invoice_request">' +  
'<input type="checkbox" class="input-checkbox" name="tax_invoice_option" id="tax_invoice_option"> Tax Invoice Request' +  
'</label>' +  
'</p>'; 


// Append the custom content before the "Place Order" button  
$('#order-notes').before(checkboxHtml);