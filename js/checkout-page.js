document.addEventListener('DOMContentLoaded', function() {



  const customCheckboxContainer = document.querySelector('.my-custom-checkbox-container');
  const hiddenCheckboxContainer = document.getElementById('hidden-checkbox-container');
  const formElement = document.getElementById('customForm');

  if (customCheckboxContainer && hiddenCheckboxContainer) {

      // Function to move the checkbox
      function moveCheckbox() {
          const targetElement = document.querySelector('#billing-fields');
          if (targetElement) {
              targetElement.appendChild(customCheckboxContainer);
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

  const checkbox = document.querySelector('#my_checkbox_field');
  
  if (checkbox && formElement) {

      // Function to handle checkbox change event
      function handleCheckboxChange() {
          if (checkbox.checked) {
              const targetElement = document.querySelector('#billing-fields'); // The same section
              if (targetElement) {
                  targetElement.appendChild(formElement);
              }
              formElement.style.display = 'block'; // Show the form
          } else {
              formElement.style.display = 'none'; // Hide the form
          }
      }

      // Attach event listener for checkbox change
      checkbox.addEventListener('change', handleCheckboxChange);

      // Check the state of the checkbox on page load
      handleCheckboxChange();
  }

  // Get all input containers
  const inputContainers = document.querySelectorAll('.wc-block-components-text-input');

  // Loop through each input container
  inputContainers.forEach((inputContainer) => {
      const inputField = inputContainer.querySelector('input');
      const errorMessage = inputContainer.querySelector('.error-message');
      let isFocused = false;

      // Add event listeners for focus and blur
      inputField.addEventListener('focus', () => {
          isFocused = true;
      });

      inputField.addEventListener('blur', () => {
          // Only validate if the field is required
          if (inputField.hasAttribute('required')) {
              // If the field is required and empty, show an error
              if (isFocused && inputField.value.trim() === '') {
                  inputField.style.border = '1px solid #ff0000';
                  errorMessage.textContent = `Please fill in the ${inputField.name} field`;
                  errorMessage.style.display = 'block';
              } else { // If field is not empty, remove error message
                  inputField.style.border = '1px solid #50575e';
                  errorMessage.style.display = 'none';
              }
          }
          isFocused = false;
      });
  });


  function initCombobox(comboboxId, labelId, suggestionListId) {
      const comboboxInput = document.querySelector(`#${comboboxId}`);
      const suggestionList = document.querySelector(`#${suggestionListId}`);
      const label = document.querySelector(`label[for="${comboboxId}"]`);
    
      // Function to update the label position
      function updateLabelPosition() {

        if (label !== null) {
          if (comboboxInput !== null && comboboxInput.value === '') {
            label.style.top = '50%';
            label.style.transform = 'translateY(-50%)';
            label.style.fontSize = '1em';
          } else {
            label.style.top = '1px';
            label.style.transform = 'translateY(0)';
            label.style.fontSize = '0.75em';
          }
        }
      }
    
      // Initial label position update
      updateLabelPosition();
      
      if (comboboxInput !== null){
        comboboxInput.addEventListener('focus', function() {
          label.style.top = '1px';
          label.style.transform = 'translateY(0)';
          label.style.fontSize = '0.75em';
        });
      
        comboboxInput.addEventListener('blur', function() {
          updateLabelPosition();
        });
      
        comboboxInput.addEventListener('input', function() {
          updateLabelPosition();
        });
      
        comboboxInput.addEventListener('mousedown', function() {
          suggestionList.style.display = 'block';
        });
      
        label.addEventListener('mousedown', function() {
          suggestionList.style.display = 'block';
        });
        
        document.addEventListener('mouseup', function(event) {
          if (!comboboxInput.contains(event.target) &&!suggestionList.contains(event.target) &&!label.contains(event.target)) {
            suggestionList.style.display = 'none';
          }
        });
      
        const dropdownItems = document.querySelectorAll(`#${suggestionListId} li`);
        dropdownItems.forEach(function(item) {
          item.addEventListener('mousedown', function() {
            const selectedItemId = this.getAttribute('id');
            comboboxInput.setAttribute('aria-activedescendant', selectedItemId);
            comboboxInput.value = this.innerText;
            suggestionList.style.display = 'none';
  
            updateLabelPosition();
          });
        });
      }
    
  }
  
  // Initialize the first combobox
  initCombobox('zatca-invoice-type-combobox', 'zatca-invoice-type-label', 'zatca-invoice-type-combobox-token-suggestions-1');
  
  // Initialize the second combobox
  initCombobox('second-business-id-type', 'new-combobox-label', 'second-bussiness-id-token-suggestions-2');

  
});

// function to check if a string contains Arabic characters
function isArabic(string) {
  var arabicPattern = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
  return arabicPattern.test(string);
}



jQuery(document).ready(function($) {

  // Notification Function:
  const popup = Notification({
    position: 'center',
    duration: 50000,
    isHidePrev: false,
    isHideTitle: false,
    maxOpened: 3,
  });

  // Check If woo fields have arabic inputs use in zatcaCustomer form :

  // If come with data stored before [ first name input ]:
  if (isArabic($('#billing-first_name').val())) {
    $('#client_name_ar').val($('#billing-first_name').val());
  }else{
    $('#client_name_en').val($('#billing-first_name').val());
  }
    
  // handle data on change [ first name input ]:
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
  
  // If come with data stored before [ city input ]:
  if (isArabic($('#billing-city').val())) {
    $('#city_ar').val($('#billing-city').val());
  }else{
    $('#city_en').val($('#billing-city').val());
  }
    
  // handle data on change [ city input ]:
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

  // If come with data stored before [ address input ]:
  if (isArabic($('#billing-address_1').val())) {
    $('#address_ar').val($('#billing-address_1').val());
  }else{
    $('#address_en').val($('#billing-address_1').val());
  }
    
  // handle data on change [ address input ]:
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


  // If come with data stored before [ postal code ]:
  $('#postal_code').val($('#billing-postcode').val());

  // handle data on change [ postal code  ]:
  $('#billing-postcode').on('input', function() {
    $('#postal_code').val($('#billing-postcode').val());
  });


  // Use Form Data to Insert into database:
  $("#customForm").submit(function(event){

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
        postalCode: $("#postal_code").val()
    };

    event.preventDefault();
    // console.log(formData);

    $.ajax({
        url: checkoutPage.ajaxUrl,
        method: "POST",
        data: {
            "action": "edit_checkout_page",
            "edit_form_data_ajax": JSON.stringify(formData)
        },
        success: function(data){


          // success notification:
          popup.success({
            title: 'Success',
            message: data
          });
            
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

  })


})

