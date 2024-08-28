document.addEventListener('DOMContentLoaded', function() {

  const customCheckboxContainer = document.querySelector('.my-custom-checkbox-container');
  const hiddenCheckboxContainer = document.getElementById('hidden-checkbox-container');
  const formElement = document.getElementById('customForm');

  if (customCheckboxContainer && hiddenCheckboxContainer) {

    // Function to move the checkbox
    function moveCheckbox() {
        const targetElement = document.querySelector('#order-notes');
        if (targetElement) {
            targetElement.appendChild(customCheckboxContainer);
            // targetElement.parentNode.insertBefore(customCheckboxContainer, targetElement.nextSibling);
            hiddenCheckboxContainer.style.display = 'block';
            observer.disconnect(); // Stop observing once the element is found
        }
    }

    // Observe the DOM for changes
    const observer = new MutationObserver(moveCheckbox);
    observer.observe(document.body, { childList: true, subtree: true });

    // Try to move the checkbox immediately in case the element is already present
    moveCheckbox();
  }

  let checkboxselected = document.querySelector('#my_checkbox_field');
  
  if (checkboxselected && formElement) {

    // Function to handle checkbox change event
    function handleCheckboxChange() {
        if (checkboxselected.checked) {
            const targetElement = document.querySelector('#order-notes'); // The same section
            if (targetElement) {
              targetElement.appendChild(formElement);
            }
            formElement.style.display = 'block'; // Show the form
            
        } else {
            formElement.style.display = 'none'; // Hide the form
        }
    }

    // Attach event listener for checkbox change
    checkboxselected.addEventListener('change', handleCheckboxChange);

    // Check the state of the checkbox on page load
    handleCheckboxChange();

  }

  // Get all input containers
  const inputContainers = document.querySelectorAll('.wc-block-components-text-input');
  
});

// function to check if a string contains Arabic characters
function isArabic(string) {
  var arabicPattern = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
  return arabicPattern.test(string);
};

// Use Form Data to Insert into database with place order button:
jQuery(document).ready(function($) {

  // Select the WooCommerce Place Order button using its specific classes
  var placeOrderButton = $('.wc-block-components-checkout-place-order-button');
  
  // Get the checkbox element
  // let checkbox2 = document.getElementById('my_checkbox_field');
  let checkboxStatus = false;
  
  // Unbind any previous click events to prevent duplication
  $('.wc-block-components-checkout-place-order-button').off('click');

  $('#my_checkbox_field').on('change', function() {

    checkboxStatus = $(this).prop('checked');

    console.log('Checkbox status outside:', checkboxStatus);

  });

  
  // Listen for the click event on the Place Order button
  placeOrderButton.on('click ', function(event) {
    
    
    event.preventDefault(); 
      // Get the checkbox status

      // checkboxStatus = document.getElementById('my_checkbox_field').checked;
      // Get the checkbox status from the global variable or data attribute

    // Get the checkbox element
    // let checkbox2 = document.getElementById('my_checkbox_field');
    console.log('Checkbox status inside:', checkboxStatus);
    
      if (checkboxStatus) {

        // localStorage.setItem("taxInvoice","checked");
        // var checkboxStatus = localStorage.getItem("taxInvoice");
        //localStorage.clear();
        
        // validation on client name ar not empty:
        if ($("#client_name_ar").val() == '') {

          // Error notification:
          popupValidation.error({
            title: checkout.notification_error_title,
            message: checkout.client_name_not_empty
          });

          return false;
        }

        // validation on client name ar must be Arabic:
        if (!isArabic($("#client_name_ar").val())) {

          // Error notification:
          popupValidation.error({
            title: checkout.notification_error_title,
            message: checkout.client_name_must_arabic
          });

          return false;
        }

        // Collect form data from the custom form
        var formData = {
          clientId: $("[name='client-id']").val(),
          operationType: $("[name='operation-type']").val(),
          clientNameAr: $("#client_name_ar").val(),
          clientNameEn: $("#client_name_en").val(),
          districtNameAr: $("#dist_ar").val(),
          vatId: $("#vat-id").val(),
          apartmentNo: $("#apartment-no").val(),
          secondBusinessIdType: $("#second-business-id-type").val(),
          secondBusinessId: $("#second-business-id").val(),
          addressNameArabic: $("#address_ar").val(),
          addressNameEnglish: $("#address_en").val(),
          cityNameArabic: $("#city_ar").val(),
          cityNameEnglish: $("#city_en").val(),
          postalCode: $("#postal_code").val(),
          status:checkboxStatus
        };

        // Send the custom form data via AJAX
        $.ajax({
          url: checkoutPage.ajaxUrl,
          method: "POST",
          data: {
            "action": "edit_checkout_page",
            "edit_form_data_ajax": JSON.stringify(formData)
          },
          success: function(data) {
            // Success notification or further actions
            popup.success({
              title: 'Success',
              message: data
            });
            

          },
          error: function(xhr, status, error) {
            console.error(xhr.responseText);
          }
        });
      }else {
        // localStorage.setItem("taxInvoice","unchecked");
        // If the checkbox is not checked, proceed with the default place order action
        placeOrderButton.off('click');  // Unbind the event handler
        placeOrderButton.click();  // Trigger the default action
      }
  });

});



jQuery(document).ready(function($) {

  function autofillFields() {
    // Autofill based on stored data for first name
    if (isArabic($('#billing-first_name').val())) {
      $('#client_name_ar').val($('#billing-first_name').val());
    } else {
      $('#client_name_en').val($('#billing-first_name').val());
    }

    // Autofill based on stored data for city
    if (isArabic($('#billing-city').val())) {
      $('#city_ar').val($('#billing-city').val());
    } else {
      $('#city_en').val($('#billing-city').val());
    }

    // Autofill based on stored data for address
    if (isArabic($('#billing-address_1').val())) {
      $('#address_ar').val($('#billing-address_1').val());
    } else {
      $('#address_en').val($('#billing-address_1').val());
    }

    // Autofill based on stored data for postal code
    $('#postal_code').val($('#billing-postcode').val());
  }

  // Initial autofill when the page loads
  autofillFields();

  // Handle changes in the first name input
  $('#billing-first_name').on('input', function() {
    var val = $(this).val();
    if (isArabic(val)) {
      $('#client_name_ar').val(val);
      $('#client_name_en').val('');
    } else {
      $('#client_name_en').val(val);
      $('#client_name_ar').val('');
    }
  });

  // Handle changes in the city input
  $('#billing-city').on('input', function() {
    var val = $(this).val();
    if (isArabic(val)) {
      $('#city_ar').val(val);
      $('#city_en').val('');
    } else {
      $('#city_en').val(val);
      $('#city_ar').val('');
    }
  });

  // Handle changes in the address input
  $('#billing-address_1').on('input', function() {
    var val = $(this).val();
    if (isArabic(val)) {
      $('#address_ar').val(val);
      $('#address_en').val('');
    } else {
      $('#address_en').val(val);
      $('#address_ar').val('');
    }
  });

  // Handle changes in the postal code input
  $('#billing-postcode').on('input', function() {
    $('#postal_code').val($(this).val());
  });

  // Re-run the autofill logic when the checkbox state changes
  $('#checkbox-control-0').on('change', function() {
    if (!$(this).is(':checked')) {
      autofillFields(); // Trigger autofill when the checkbox is unchecked and inputs become visible
    }
  });

});


