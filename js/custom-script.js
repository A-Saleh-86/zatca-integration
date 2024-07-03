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