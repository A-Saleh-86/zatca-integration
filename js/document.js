// Change background Color Of Selected TR:
(function($) {
    $(document).ready(function() {
        $('table').on('click', 'td', function() {

            // Remove selected from all 
            $('tr').removeClass('selected');

            // Add selected to clicked row
            $(this).closest('tr').addClass('selected');

        });
    });
})(jQuery);

// Cache the table rows for better performance
const tableRows = $('tr');

// Btn Of Copy Order Data:
const copyBtn = document.getElementById('search-invoices-data');

// Payed Input:
const wooInvoiceNoInput = document.getElementById('woo-invoice-no');

// Payed Input:
const payedInput = document.getElementById('payed-input');

// Discount Input:
const discountInput = document.getElementById('discount-input');

// Invoice Net Input:
const invoiceNetInput = document.getElementById('invoice-net-input');

// Sub Net Total Plus Tax Input:
const subnetTotalPlusTaxInput = document.getElementById('subnet-total-plus-tax-input');

// zatcaInvoiceTypeInput Input:
const zatcaInvoiceTypeInput = document.getElementById('zatcaInvoiceType');

// Discount Total Input:
const discountTotalInput = document.getElementById('discount-total-input');

// Get the vat cat code sub Input
const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');

// Get the exemption-reason Input [ vat cat code sub ]:
const exemptionReasonInput = document.getElementById('exemption-reason');

// Get the exemption-reason Input [ vat cat code sub ]:
const discountPercentageInput = document.getElementById('discount-percentage');

// Function to update exemption-reason [ Insert Page]:
function updateInput() {

    var vatCatSubValue = vatCatCodeSubInput.value;
    $.ajax({
        url:myDoc.ajaxUrl,
        method: 'POST',
        data: {
            'action': 'woo-document-data',
            'vat_cat_code': vatCatSubValue,
        },
        success: function(data) {

            // Get VATCategoryCodeSubTypeNo aName and Put In exemption Reason:
            exemptionReasonInput.value = data.vatCatName;
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}

// Change vat cat code sub on change vat cat code [ Insert Page]:
(function($) {
    $(document).ready(function() {

        // Update exemption-reason on vat-cat-code-sub change:
        $('#vat-cat-code-sub').on('change', function(event) {
            updateInput();
        });

        $('select#vat-cat-code').on('change', function(event) {
            const selectedVatCat = this.value;


            // Make an AJAX request to fetch data based on selectedUserId
            $.ajax({
                url: myDoc.ajaxUrl,
                method: 'POST',
                data: {
                    'action': 'company',
                    'company_form_ajax': selectedVatCat,
                },
                
                success: function(data) {
                    
                    // console.log(data);
                    
                    vatCatCodeSubInput.innerHTML = data;
                    
                    // Update exemption-reason:
                    updateInput();


                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        });
    });
})(jQuery);

// Search Btn [ Insert Page ]:
$('#search-invoices-data').on('click', function(event) {

    const selectedRow = tableRows.filter('.selected');

    // If Client Not Choose a Order:
    if (!selectedRow.length) {
        alert('Please select a Order first.');
        return;
    }

    // Get the Order ID from the data-user-id attribute of the selected row
    const selectedOrderId = selectedRow.data('order-id');

    const selectedcustomerId = selectedRow.data('customer-id');

    
    // Make an AJAX request to fetch data based on selectedUserId
    $.ajax({
        url: myDoc.ajaxUrl,
        method: 'POST',
        data: {
            'action'        : 'doc_check_customer',
            'woo_customer_id'  : selectedcustomerId
        },
        
        success: function(data) {
            
            // Return if customer not inserted in woo order:
            if (data !== 'Exist') { 

                // View Modal to choose if need to add customer now:
                $('#customer-modal').modal('show');

                // if yes redirect to insert new customer - send customer id in url:
                $('#document-add-customer').on('click', function() {

                    window.location.href = myDoc.customer + "&customerId=" + encodeURIComponent(selectedcustomerId);
                    
                });
                
                return;
            }else{

                // Make an AJAX request to fetch data based on selectedOrderId
                $.ajax({
                    url: myDoc.ajaxUrl,
                    method: 'POST',
                    data: {
                        'action'        : 'woo-document-data',
                        'woo_order_id'  : selectedOrderId
                    },
                    
                    success: function(data) {
            
                        $('#exampleModal-search-invoices').modal('hide');
            
                        // Put Data In Woo Invoice No input:
                        wooInvoiceNoInput.value = selectedOrderId;
                        
                        // Put Data to Payed Input:
                        payedInput.value = data.payed;
                        
                        // Put Data to Dicount Input
                        discountInput.value = data.discount;
                        
                        // Put Data to invoice net Input
                        invoiceNetInput.value = data.subNetTotal;
                        
                        // Put Data to subnetTotalPlusTax Input [ it's same like payed ]:
                        subnetTotalPlusTaxInput.value = data.payed;
                        
                        // Put Data to Dicount Total Input
                        discountTotalInput.value = data.discount;
            
                        // Put Data to Discount Percentage Input:
                        discountPercentageInput.value = data.discountPercentage;


                        var selectedValue = data.invoiceTypeCode;

                        // Select the option based on the retrieved value
                        $("#zatcaInvoiceType").val(selectedValue).trigger("change");
                  
                        // Get the selected option text
                        var selectedOptionText = $("#zatcaInvoiceType option:selected").text();
                  
                        // Display the selected option text in the span
                        $("#selectedInvoiceTypeName").text(selectedOptionText);

                        
                        console.log(data.invoiceTypeCode);
                       
            
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching data:', error);
                    }
                });
            }

            
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });

    
});

// Get Data from insert page and return it as ajax data to db:
jQuery(document).ready(function($) {
    $("#insert-document__form").submit(function(event){
        event.preventDefault();
        var formData = $(this).serialize();
        // console.log(formData);
        
        $.ajax({
            url: myDoc.ajaxUrl, 
            method: "POST", 
            data: {
                "action": "insert-documents",
                "insert_form_ajax_documents": formData
            },
            success: function(data){
                // console.log(data);
                alert(data);
                window.location.href = myDoc.adminUrl;
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});



// Send To Zatca Btn - view page:
jQuery(document).ready(function($){
    
    
    $(document).on('click', '#send-zatca-clear', function(event) {
        const docNo = $(this).data('doc-no');
        // alert(docNo);
        $.ajax({
            url: myDoc.ajaxUrl, 
            method: "POST", 
            data: {
                action: 'zatca_clear',
                "doc_no_from_ajax": docNo
            },
            success: function(response) {
              
                alert(response.msg);

                // $('#try-res').html(response.data);
                window.location.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error: ', textStatus, errorThrown);
            }
        });
    });

    $(document).on('click', '#send-zatca-report', function(event){

        alert('Report Function');
    })

    $(document).on('click', '#send-zatca-reissue', function(event){

        alert('Reissue');
    })


});


// Get response XML and Download file:
jQuery(document).ready(function($) {
    $(document).on('click', '#download-xml', function(event) {
        const docNo = $(this).data('doc-no');

        $.ajax({
            url: myDoc.ajaxUrl,
            method: "POST",
            data: {
                action: 'download_xml',
                doc_no_from_ajax: docNo
            },
            success: function(response) {
                if (response.success) {
                    const downloadUrl = response.data.download_url;
                    const link = document.createElement('a');
                    link.href = downloadUrl;
                    link.download = 'invoice_' + docNo + '.xml';
                    link.click();
                    // alert(response.data.file_created);
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
});
