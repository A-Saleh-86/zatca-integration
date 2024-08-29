
// function to select all displayed rows in datatables
function checkAll(source) {  
    var checkboxes = document.querySelectorAll('.rowCheckbox');  
    checkboxes.forEach(function(checkbox) {  
        checkbox.checked = source.checked;  
    });  
}

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

// Oredr Status Input:
const wooInvoiceOrderStatus = document.getElementById('woo-order-status');

// Payed Input:
const wooInvoiceNoInput = document.getElementById('woo-invoice-no');

// Payed (cash) Input:
const payedInput = document.getElementById('payed-input');

// Payed (visa) Input:
const payedVisaInput = document.getElementById('payed_visa');

// Payed (bank) Input:
const payedBankInput = document.getElementById('payed_bank');

// Total Payed Input:
const totalPayedInput = document.getElementById('amountCalculatedPayed');

// sub Total Input:
const subTotalInput = document.getElementById('subTotal');

// Total Tax Input:
const totalTaxInput = document.getElementById('total_tax');

// Discount Input:
const discountInput = document.getElementById('discount-input');

// Invoice Net Input:
const invoiceNetInput = document.getElementById('invoice-net-input');

// left amount Input:
const leftAmountInput = document.getElementById('left-amount-input');

// Sub Net Total Plus Tax Input:
const subnetTotalPlusTaxInput = document.getElementById('subnet-total-plus-tax-input');

// zatcaInvoiceTypeInput Input:
const zatcaInvoiceTypeInput = document.getElementById('zatcaInvoiceType');


// Get the vat cat code sub Input
const vatCatCodeSubInput = document.getElementById('vat-cat-code-sub');

// Get the exemption-reason Input [ vat cat code sub ]:
const exemptionReasonInput = document.getElementById('exemption-reason');

// Get the exemption-reason Input [ vat cat code sub ]:
const discountPercentageInput = document.getElementById('discount-percentage');

// Function to update exemption-reason [ Insert Page]:
function updateInput() {

    var vatCatSubValue = $('vat-cat-code-sub').val();
    $.ajax({
        url:myDoc.ajaxUrl,
        method: 'POST',
        data: {
            'action': 'woo-document-data',
            'vat_cat_code': vatCatSubValue,
        },
        success: function(data) {

            // Get VATCategoryCodeSubTypeNo aName and Put In exemption Reason:
            if(exemptionReasonInput)
            {
                exemptionReasonInput.value = data.vatCatName;
            }
             
        },
        error: function(xhr, status, error) {
            console.error('Error fetching data:', error);
        }
    });
}


// Change return reason on change returnReasonType[ Insert Page]:
(function($) {
    $(document).ready(function() {

        if($('#return-reason-type').val() == '')
        {
            $('#returnReason').val('');
        }

        $('#return-reason-type').on('change', function(event) {
            const selectedReasonType = this.value;

            var selectedText = $('#return-reason-type option:selected').text();

            // Show or hide Exemption Reason Div depend on vat-cat-code value
            if(selectedReasonType != '')
            {
                $('#returnReason').val(selectedText);
            }
            else
            {
                $('#returnReason').val('');
            }
        });
    });
})(jQuery);


// Change vat cat code sub on change vat cat code [ Insert Page]:
(function($) {
    $(document).ready(function() {

        if($('#vat-cat-code').val() == 0)
        {
            $('#exemptionReason').hide();
        }
        // Update exemption-reason on vat-cat-code-sub change:
        $('#vat-cat-code-sub').on('change', function(event) {
            //updateInput();
            const selectedVatCatSub = this.value;
            var selectedText = $('#vat-cat-code-sub option:selected').text();
            if(selectedVatCatSub != 0)
            {
                if(exemptionReasonInput)
                {
                    // show Exemption Reason Div if vat-cat-code != 0
                    exemptionReasonInput.value = selectedText;
                }
                
            }
        });

        $('#vat-cat-code').on('change', function(event) {
            const selectedVatCat = this.value;

            // Show or hide Exemption Reason Div depend on vat-cat-code value
            if(selectedVatCat != 0)
            {
                // show Exemption Reason Div if vat-cat-code != 0
                $('#exemptionReason').show();
            }
            else
            {
                // hide Exemption Reason Div if vat-cat-code = 0 >> Standard Rate
                $('#exemptionReason').hide();
            }

            // Make an AJAX request to fetch data based on selectedUserId
            $.ajax({
                url: myDoc.ajaxUrl,
                method: 'POST',
                data: {
                    'action': 'company',
                    'vat_cat_code_ajax': selectedVatCat,
                },
                
                success: function(data) {
                    
                    // console.log(data);
                    
                    $('vat-cat-code-sub').innerHTML = data;
                    
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

        // Error notification:
        popupValidation.error({
            title: myDoc.notification_error_title,
            message: myDoc.choose_customer_td
        });

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
            
                        // put order status input
                        var buyer_table = '';
                        var seller_table = '';

                        $('.seller').empty();
                        $('.buyer').empty();

                        buyer_table= `
                        <table class="table table-striped table-bordered text-center">
                            <tbody>
                                <tr>
                                    <th colspan="3">معلومات المشترى</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>${data.zatcaBuyer_array.buyer_aName_Customer}</td>
                                    <th>الاسم</th>
                                </tr>
                                
                                <tr>
                                    <th>Building No</th>
                                    <td>${data.zatcaBuyer_array.buyer_apartmentNum_Customer}</td>
                                    <th>رقم المبنى</th>
                                </tr>
                                
                                <tr>
                                    <th>Street Name</th>
                                    <td>${data.zatcaBuyer_array.buyer_street_Arb_Customer}</td>
                                    <th>اسم الشارع</th>
                                </tr>
                               
                                <tr>
                                    <th>District</th>
                                    <td>${data.zatcaBuyer_array.buyer_district_Arb_Customer}</td>
                                    <th>المقاطعة</th>
                                </tr>
                                
                                <tr>
                                    <th>City</th>
                                    <td>${data.zatcaBuyer_array.buyer_city_Arb_Customer}</td>
                                    <th>المدينة</th>
                                </tr>
                                
                                <tr>
                                    <th>Country</th>
                                    <td>${data.zatcaBuyer_array.buyer_arCountry_Customer}</td>
                                    <th>الدولة</th>
                                </tr>
                                
                                <tr>
                                    <th>Postal Code</th>
                                    <td>${data.zatcaBuyer_array.buyer_Postal_Code}</td>
                                    <th>الرمز البريدى</th>
                                </tr>
                                
                                <tr>
                                    <th>PO Box Add.No</th>
                                    <td>${data.zatcaBuyer_array.buyer_POBoxAdditionalNum}</td>
                                    <th>رقم صندوق البريد الاضافى</th>
                                </tr>
                                
                                <tr>
                                    <th>VAT Number</th>
                                    <td>${data.zatcaBuyer_array.buyer_VAT}</td>
                                    <th>الرقم الضريبى</th>
                                </tr>
                               
                                <tr>
                                    <th>Other Seller ID</th>
                                    <td>${data.zatcaBuyer_array.buyer_SecondBusinessID}</td>
                                    <th>المعرف الثانى</th>
                                </tr>
                                
                            </tbody>
                        <table>`;

                        seller_table= `
                        <table class="table table-striped table-bordered text-center">
                            <tbody>
                                <tr>
                                    <th colspan="3">معلومات البائع</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td>${data.zatcaSeller_array.seller_Name}</td>
                                    <th>الاسم</th>
                                </tr>
                                
                                <tr>
                                    <th>Building No</th>
                                    <td>${data.zatcaSeller_array.seller_apartmentNum_Company}</td>
                                    <th>رقم المبنى</th>
                                </tr>
                                
                                <tr>
                                    <th>Street Name</th>
                                    <td>${data.zatcaSeller_array.seller_street_Arb_Company}</td>
                                    <th>اسم الشارع</th>
                                </tr>
                                
                                <tr>
                                    <th>District</th>
                                    <td>${data.zatcaSeller_array.seller_district_Arb_Company}</td>
                                    <th>المقاطعة</th>
                                </tr>
                                
                                <tr>
                                    <th>City</th>
                                    <td>${data.zatcaSeller_array.seller_city_Arb_Company}</td>
                                    <th>المدينة</th>
                                </tr>
                                
                                <tr>
                                    <th>Country</th>
                                    <td>${data.zatcaSeller_array.seller_country_Arb_Company}</td>
                                    <th>الدولة</th>
                                </tr>
                                
                                <tr>
                                    <th>Postal Code</th>
                                    <td>${data.zatcaSeller_array.seller_postalCode}</td>
                                    <th>الرمز البريدى</th>
                                </tr>
                                
                                <tr>
                                    <th>PO Box Add.No</th>
                                    <td>${data.zatcaSeller_array.seller_POBoxAdditionalNum_Company}</td>
                                    <th>رقم صندوق البريد الاضافى</th>
                                </tr>
                                
                                <tr>
                                    <th>VAT Number</th>
                                    <td>${data.zatcaSeller_array.seller_VAT_Company}</td>
                                    <th>الرقم الضريبى</th>
                                </tr>
                                
                                <tr>
                                    <th>Other Seller ID</th>
                                    <td>${data.zatcaSeller_array.seller_secondBusinessID}</td>
                                    <th>المعرف الثانى</th>
                                </tr>
                                
                            </tbody>
                        <table>`;

                        // Append the tables to the div  
                        $(".seller").append(seller_table);
                        $(".buyer").append(buyer_table);

                        

                        if(data.order_status == 'wc-refunded')
                        {
                            // set span text
                            $('#statusOrderSpan').html(myDoc.sell_return_invoice);
                            wooInvoiceOrderStatus.value = 23;
                            $('#returnReasonType').show();
                        }
                        else
                        {
                            // set span text
                            $('#statusOrderSpan').html(myDoc.sell_invoice);
                            wooInvoiceOrderStatus.value = 33;
                            $('#returnReasonType').hide();
                        }

                        // Put Data In Woo Invoice No input:
                        wooInvoiceNoInput.value = selectedOrderId;

                        // Put Data In Woo Invoice No input:
                        wooInvoiceNoInput.value = selectedOrderId;
                        
                        // Put Data to Payed (cash) Input:
                        payedInput.value = data.payed;
                        
                        // Put Data to Payed (visa) Input:
                        payedVisaInput.value = data.payedVisa;
                        
                        // Put Data to Payed (bank) Input:
                        payedBankInput.value = data.payedBank;
                        
                        // Put Data to Total Payed Input:
                        totalPayedInput.value = data.totalPayed;
                        
                        // Put Data to Dicount Input
                        discountInput.value = data.discount;
                        
                        // Put Data to invoice net Input
                        invoiceNetInput.value = data.subNetTotal;
                        
                        // Put Data to subnetTotalPlusTax Input [ it's same like payed ]:
                        subnetTotalPlusTaxInput.value = data.payed;
                        
                        // Put Data to subTotal Input:
                        subTotalInput.value = data.subTotal;
                        
                        // Put Data to Total Tax Input:
                        totalTaxInput.value = data.totalTax;
                       
                        // Put Data to Total Tax Input:
                        leftAmountInput.value = data.leftAmount;

                        var selectedValue = data.invoiceTypeCode;
                        

                        // Select the option based on the retrieved value
                        $("#zatcaInvoiceType").val(selectedValue).trigger("change");
                  
                        // Get the selected option text
                        var selectedOptionText = $("#zatcaInvoiceType option:selected").text();
                  
                        // Display the selected option text in the span
                        $("#selectedInvoiceTypeName").text(selectedOptionText);

                        
                        // Assuming zatcaDocumentUnit_array is your array containing zatcaDocumentUnit data  

                        // Clear the trContent variable
                        var trContent = '';
                        // Clear the tbody element
                        $('tbody.order_details').empty();
                        $.each(data.zatca_document_unit_lines, function(index, data) {  
                            // Access each zatcaDocumentUnit data fields here  

                            trContent = '<tr>';
                            trContent += '<td>' + data.itemNo + '</td>';
                            trContent += '<td>' + data.eName + '</td>';
                            trContent += '<td>' + data.eName + '</td>';
                            trContent += '<td>' + data.price + '</td>';
                            trContent += '<td>' + data.quantity + '</td>';
                            trContent += '<td>' + data.discount + '</td>';
                            trContent += '<td>' + data.vatRate + '</td>';
                            trContent += '<td>' + data.vatAmount + '</td>';
                            trContent += '<td>' + data.netAmount + '</td>';
                            trContent += '<td>' + data.amountWithVAT + '</td>';
                            trContent += '</tr>';
                            var tr = $(trContent).appendTo('tbody.order_details');  
                        });
                        //
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

    // Add Dcument Form:
    $("#insert_document_form").submit(function(event){
        
        event.preventDefault();

        // Enable the inputs fields temporarily:
        /*$("#woo-invoice-no").prop("disabled", false);
        $("#payed-input").prop("disabled", false);
        $("#payed_visa").prop("disabled", false);
        $("#payed_bank").prop("disabled", false);
        $("#amountCalculatedPayed").prop("disabled", false);
        $("#subTotal").prop("disabled", false);
        $("#total_tax").prop("disabled", false);
        $("#invoice-net-input").prop("disabled", false);
        $("#subnet-total-plus-tax-input").prop("disabled", false);
        $("#discount-input").prop("disabled", false);
        $("#left-amount-input").prop("disabled", false);*/
        
        var formData = $(this).serialize();
        // console.log(formData)

        
        $.ajax({
            url: myDoc.ajaxUrl, 
            method: "POST", 
            data: {
                "action": "insert-documents",
                "insert_form_ajax_documents": formData
            },
            success: function(data){
                 //console.log(data);
                if(data.status == 'error'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: data.msg
                    });

                    return;

                }else if(data.status == 'expired'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.document_device_expired
                    });

                    return;

                }else if(data.status == 'no_zatcaCompany_data'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.zatca_company_empty
                    });

                    return;

                }else if(data.status == 'success'){

                    // success notification:
                    popup.success({
                        title: myDoc.notification_success_title,
                        message: myDoc.document_inserted
                    });

                    setTimeout(function() {
                        window.location.href = myDoc.adminUrl;
                    }, 3000); 

                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Edit Document Form:
    $("#edit_document_form").submit(function(event){
        event.preventDefault();

        // Enable the inputs fields temporarily:
        $("#documentNo").prop("disabled", false);
        $("#woo-invoice-no").prop("disabled", false);
        $("#payed-input").prop("disabled", false);
        $("#payed_visa").prop("disabled", false);
        $("#payed_bank").prop("disabled", false);
        $("#amountCalculatedPayed").prop("disabled", false);
        $("#subTotal").prop("disabled", false);
        $("#total_tax").prop("disabled", false);
        $("#invoice-net-input").prop("disabled", false);
        $("#subnet-total-plus-tax-input").prop("disabled", false);
        $("#discount-input").prop("disabled", false);
        $("#left-amount-input").prop("disabled", false);
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: myDoc.ajaxUrl,
            method: "POST", 
            data: {
                "action": "edit-document",
                "document_edit_form_ajax": formData
            },
            success: function(data){

                if(data.status == 'error'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: data.msg
                    });

                    return;

                }else if(data.status == 'success'){

                    // success notification:
                    popup.success({
                        title: myDoc.notification_success_title,
                        message: myDoc.document_updated
                    });

                    setTimeout(function() {
                        window.location.href = myDoc.adminUrl;
                    }, 3000); 

                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

// Unsubmitted checkbox - view page:
$(document).ready(function($){

    var currentLang = myDoc.locale;

    if (currentLang === 'ar') {
        $('#document-table').DataTable({
            responsive:true,
            language: {
                url: myDoc.dtLoc,
            },
            select: {  
                style: 'multi'  
            },
    
            "columnDefs": [
                { "orderable": false, "targets": 0 }, // Disables sorting for the first column (index 0)  
                {
                    "targets": [ 11, 12 ],
                    "searchable": true,
                    "visible": false
                }
            ]
        });
    } else {
        $('#document-table').DataTable({
            responsive:true,
            select: {  
                style: 'multi'  
            },
    
            "columnDefs": [
                { "orderable": false, "targets": 0 }, // Disables sorting for the first column (index 0)  
                {
                    "targets": [ 11, 12 ],
                    "searchable": true,
                    "visible": false
                }
            ]
        });
    }

    let minDate, maxDate;
 
    // Custom filtering function which will search data in column four between two values
    DataTable.ext.search.push(function (settings, data, dataIndex) {
        let min = minDate.val() ? new Date(minDate.val()) : null;
        let max = maxDate.val() ? new Date(maxDate.val()) : null;
        let date = new Date(data[3]);
     
        if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
        ) {
            return true;
        }
        return false;
    });
     
    // Create date inputs
    minDate = new DateTime('#min', {
        format: 'MMMM Do YYYY'
    });
    maxDate = new DateTime('#max', {
        format: 'MMMM Do YYYY'
    });
     
    // DataTables initialisation [ zatcaLog]
    let table = new DataTable('#document-table');
    
     
    // Refilter the table
    document.querySelectorAll('#min, #max').forEach((el) => {
        el.addEventListener('change', () => table.draw());
    });

    // reset min Btn:
    $('#reset').on('click', function() {
        // Clear the input values
        $('#min').val('');
        $('#max').val('');
        
        // Clear the date picker values
        minDate.val(null);
        maxDate.val(null);
        
        // Reset the table
        table.column(1).search("").draw();
    });

    // reset max btn
    $('#reset-max').on('click', function() { 
        $('#max').val('');
        // Clear the date picker values
        minDate.val(null);
        maxDate.val(null);
        
        // Reset the table
        table.column(1).search("").draw();
      });

    // checkbox - failed
    $('#doc-table-failed').on('change', function() {
        if (this.checked) {
            // Filter to show only rows with "Failed" status
            table.column(11).search('^(0|3)$', true, false).column(12).search('^(NULL)$', true, false).draw();
        } else {
            // Clear the filter
            table.column(11).search('').column(12).search('').draw();
        }
    });

});



// Send To Zatca Btn - view page:
jQuery(document).ready(function($){
    
    // send to clear
    $(document).on('click', '#send-zatca-clear', function(event) {
        const docNo = $(this).data('doc-no');
        const companyStage = $(this).data('company-stage');
        const sellerSecondbusinessid = $(this).data('seller-secondbusinessid');
        const buyerVat = $(this).data('buyer-vat');
        const invoicetransactioncode_isexports = $(this).data('invoicetransactioncode-isexports');

         
        $.ajax({
            url: myDoc.ajaxUrl, 
            method: "POST", 
            data: {
                action: 'zatca_clear',
                "doc_no_from_ajax": docNo
            },
            success: function(response) {

                // alert('response_time ' + response.response_time);
                // console.log(response);
                if(response.msg.status == 'insert_seller_additional_id'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.seller_second_business_id
                    });
                    
                    return;

                }else if(response.msg.status == 'isexport0_buyervat'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.isexport0_buyervat
                    });

                    return;

                }else if(response.msg.status == 'isexport1_buyervat'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.isexport1_buyervat
                    });

                    return;

                }else if(response.msg.status == 'insert_buyer_poBox_additionalNo'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.insert_buyer_poBox_additionalNo
                    });

                    return;

                }else if(response.msg.status == 'insert_buyer_additional_id'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.insert_buyer_additional_id
                    });

                    return;

                }else{

                    if(response.responseArray['clearanceStatus'] == "NOT_CLEARED"){

                        if(response.responseArray['zatcaStatusCode'] == 400 || response.responseArray['zatcaStatusCode'] == null || response.responseArray['zatcaStatusCode'] == 0){

                            if(response.msg.status == 'errorupdateresponse'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'no_rows_affected'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: myDoc.no_rows_affected
                                });

                            }else if(response.msg.status == 'http_status_msg'){

                                if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                                {
                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.mayBeDeviceError
                                    });
                                }
                                else
                                {
                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });
                                }
                                

                            }

                            if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                            {
                            }
                            else
                            {
                                const searchTerm = "Invoice Already Generated";
                                const str = response.responseArray['portalResults'];
                                if(str.toLowerCase().includes(searchTerm.toLowerCase()))
                                {
                                    const numbers = str.match(/\d+/g) || []; // Matches all sequences of digits
                                    const text = str.match(/[^\d]+/g) || []; // Matches all non-digit characters
                                    // Convert numbers from strings to actual numbers  
                                    const numberList = numbers.map(Number);
                                    // Clean up text segments (trimming spaces, etc.)  
                                    const cleanText = text.map(segment => segment.trim()).filter(Boolean);

                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: numberList[0] + " - " + myDoc.generatedAlready
                                    });

                                }
                                
                                else
                                {
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.error_word + " " + response.responseArray['portalResults']
                                    });
                                }
                            }
                            

                        }else if(response.responseArray['zatcaStatusCode'] == 303){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_303
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 401){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_401
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 413){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_413
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 429){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_429
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 500){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_500
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 503){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_503
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 504){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_504
                            });

                        }

                    }else{

                        if(response.responseArray['zatcaStatusCode'] == 202){

                            if(response.msg.status == 'errorxml'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'no_rows_affected'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: myDoc.no_rows_affected
                                });

                            }else if(response.msg.status == 'errorupdateresponse'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'errorupdatedevice'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'warning'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: response.msg.msg
                                });

                            }
                        }else if(response.responseArray['zatcaStatusCode'] == 200){

                            if(response.msg.status == 'errorxml'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'no_rows_affected'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: myDoc.no_rows_affected
                                });

                            }else if(response.msg.status == 'errorupdateresponse'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'errorupdatedevice'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'success'){

                                // success notification:
                                popup.success({
                                    title: myDoc.notification_success_title,
                                    message: response.msg.msg
                                });

                            }
                        }
                    }
                        
                    //console.log(response);
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                }

                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error: ', textStatus, errorThrown);
            }
        });
        
    });

    // send to report
    $(document).on('click', '#send-zatca-report', function(event){
        const docNo = $(this).data('doc-no');
        
        //ajax code here to send zatca B2C document
        $.ajax({
            url: myDoc.ajaxUrl, 
            method: "POST", 
            data: {
                action: 'zatca_report',
                "doc_no_from_ajax": docNo
            },
            success: function(response) {

                if(response.msg.status == 'buyer_arabic_name'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.buyer_arabic_name
                    });

                    
                    return;

                }
                else if(response.msg.status == 'seller_second_business_id'){

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.seller_second_business_id
                    });
                    return;
                    
                }
                
                else{

                    if(response.responseArray['reportingStatus'] == "NOT_REPORTED"){

                        if(response.responseArray['zatcaStatusCode'] == 400 || response.responseArray['zatcaStatusCode'] == null || response.responseArray['zatcaStatusCode'] == 0){

                            if(response.msg.status == 'errorupdateresponse'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'no_rows_affected'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: myDoc.no_rows_affected
                                });

                            }else if(response.msg.status == 'http_status_msg'){

                                if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                                {
                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.mayBeDeviceError
                                    });
                                }
                                else
                                {
                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });
                                }

                            }

                            if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                            {
                            }
                            else
                            {
                                const searchTerm = "Invoice Already Generated";
                                const str = response.responseArray['portalResults'];
                                if(str.toLowerCase().includes(searchTerm.toLowerCase()))
                                {
                                    const numbers = str.match(/\d+/g) || []; // Matches all sequences of digits
                                    const text = str.match(/[^\d]+/g) || []; // Matches all non-digit characters
                                    // Convert numbers from strings to actual numbers  
                                    const numberList = numbers.map(Number);
                                    // Clean up text segments (trimming spaces, etc.)  
                                    const cleanText = text.map(segment => segment.trim()).filter(Boolean);

                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: numberList[0] + " - " + myDoc.generatedAlready
                                    });

                                }
                                
                                else
                                {
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.error_word + " " + response.responseArray['portalResults']
                                    });
                                }
                            }

                        }else if(response.responseArray['zatcaStatusCode'] == 303){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_303
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 401){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_401
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 413){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_413
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 429){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_429
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 500){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_500
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 503){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_503
                            });

                        }else if(response.responseArray['zatcaStatusCode'] == 504){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.error_504
                            });

                        }
                        
                    }else{

                        if(response.responseArray['zatcaStatusCode'] == 202){

                            if(response.msg.status == 'errorxml'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'no_rows_affected'){

                                // Warning Notification:
                                popupValidation.warning({
                                    title: myDoc.notification_warning_title,
                                    message: myDoc.no_rows_affected
                                });

                            }else if(response.msg.status == 'errorupdateresponse'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'errorupdatedevice'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'warning'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: response.msg.msg
                                });

                            }
                            
                        }else if(response.responseArray['zatcaStatusCode'] == 200){

                            if(response.msg.status == 'errorxml'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'no_rows_affected'){

                                // Warning Notification:
                                popup.warning({
                                    title: myDoc.notification_warning_title,
                                    message: myDoc.no_rows_affected
                                });

                            }else if(response.msg.status == 'errorupdateresponse'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'errorupdatedevice'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: response.msg.msg
                                });

                            }else if(response.msg.status == 'success'){

                                // success notification:
                                popup.success({
                                    title: myDoc.notification_success_title,
                                    message: response.msg.msg
                                });
                            }
                        }
                    }

                    //console.log(response);
                    setTimeout(()=>{
                        window.location.reload();
                    }, 3000);
                }

                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error: ', textStatus, errorThrown);
            }
        });
            
        
    });

    // send sellected to zatca clear or report
    $(document).on('click', '#send-zatca-sellected', function(event){

        var checkboxes = document.querySelectorAll('.rowCheckbox:checked');
        var promises = [];  

        checkboxes.forEach(function(checkbox) {
            const documentNo = checkbox.getAttribute('data-document-no');  
            const zatcaSuccessResponse = checkbox.getAttribute('data-success-response');  
            const zatcaInvoiceType = checkbox.getAttribute('data-invoice-type');

            //B2B Invoices
            if(zatcaInvoiceType == 1 && zatcaSuccessResponse == 0){

                const promise = new Promise((resolve, reject) => {
                    $.ajax({
                        url: myDoc.ajaxUrl, 
                        method: "POST", 
                        data: {
                            action: 'zatca_clear',
                            "doc_no_from_ajax": documentNo
                        },
                        success: function(response) {
                            if(response.msg.status == 'seller_second_business_id'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.document_word + " " + documentNo + ": " + myDoc.seller_second_business_id
                                });

                                return;
                                
                            }else if(response.msg.status == 'isexport0_buyervat'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.document_word + " " + documentNo + ": " + myDoc.isexport0_buyervat
                                });

                                return;

                            }else if(response.msg.status == 'isexport1_buyervat'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.document_word + " " + documentNo + ": " + myDoc.isexport1_buyervat
                                });
                                
                                return;

                            }else if(response.msg.status == 'insert_buyer_poBox_additionalNo'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.insert_buyer_poBox_additionalNo
                                });

                                return;

                            }else if(response.msg.status == 'insert_buyer_additional_id'){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.insert_buyer_additional_id
                                });

                                return;

                            }else{

                                if(response.responseArray['clearanceStatus'] == "NOT_CLEARED"){

                                    if(response.responseArray['zatcaStatusCode'] == 400 || response.responseArray['zatcaStatusCode'] == null || response.responseArray['zatcaStatusCode'] == 0){

                                        if(response.msg.status == 'errorupdateresponse'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                            });

                                        }else if(response.msg.status == 'no_rows_affected'){

                                            // Warning Notification:
                                            popup.warning({
                                                title: myDoc.notification_warning_title,
                                                message: myDoc.no_rows_affected
                                            });

                                        }else if(response.msg.status == 'http_status_msg'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.error_word + " " + response.responseArray['portalResults']
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 303){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_303
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 401){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_401
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 413){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_413
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 429){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_429
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 500){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_500
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 503){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_503
                                        });

                                    }else if(response.responseArray['zatcaStatusCode'] == 504){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_504
                                        });

                                    }
                                
                                }else{

                                    if(response.responseArray['zatcaStatusCode'] == 202) {

                                        if(response.msg.status == 'errorxml'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }else if(response.msg.status == 'no_rows_affected'){

                                            // Warning Notification:
                                            popup.warning({
                                                title: myDoc.notification_warning_title,
                                                message: myDoc.no_rows_affected
                                            });

                                        }else if(response.msg.status == 'errorupdateresponse'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }else if(response.msg.status == 'errorupdatedevice'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }else if(response.msg.status == 'warning'){

                                            // Warning Notification:
                                            popup.warning({
                                                title: myDoc.notification_warning_title,
                                                message: response.msg.msg
                                            });

                                        }

                                    }else if(response.responseArray['zatcaStatusCode'] == 200){

                                        if(response.msg.status == 'errorxml'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }else if(response.msg.status == 'no_rows_affected'){

                                            // Warning Notification:
                                            popup.warning({
                                                title: myDoc.notification_warning_title,
                                                message: myDoc.no_rows_affected
                                            });

                                        }else if(response.msg.status == 'errorupdateresponse'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }else if(response.msg.status == 'errorupdatedevice'){

                                            // Error notification:
                                            popupValidation.error({
                                                title: myDoc.notification_error_title,
                                                message: response.msg.msg
                                            });

                                        }else if(response.msg.status == 'success'){

                                            // success notification:
                                            popup.success({
                                                title: myDoc.notification_success_title,
                                                message: response.msg.msg
                                            });
                                        }
                                    }
                                }
                                resolve(); // Resolve if successful 
                            }

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error: ', textStatus, errorThrown);
                            reject(); // Reject if there's an error
                        }
                    });
                });
                promises.push(promise); 
                
            }
            else if(zatcaInvoiceType == 0 && zatcaSuccessResponse == 0){ //B2C Invoices

                const promise = new Promise((resolve, reject) => {
                
                    //ajax code here to send zatca B2C document
                $.ajax({
                    url: myDoc.ajaxUrl, 
                    method: "POST", 
                    data: {
                        action: 'zatca_report',
                        "doc_no_from_ajax": documentNo
                    },
                    success: function(response) {
                        if(response.msg.status == 'buyer_arabic_name'){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.document_word + " " + documentNo + " - " + myDoc.buyer_arabic_name
                            });

                            return;

                        }
                        else if(response.msg.status == 'seller_second_business_id'){

                            // Error notification:
                            popupValidation.error({
                                title: myDoc.notification_error_title,
                                message: myDoc.document_word + " " + documentNo + " - " + myDoc.seller_second_business_id
                            });

                            
                        }else{

                            if(response.responseArray['reportingStatus'] == "NOT_REPORTED"){

                                if(response.responseArray['zatcaStatusCode'] == 400 || response.responseArray['zatcaStatusCode'] == null || response.responseArray['zatcaStatusCode'] == 0){

                                    if(response.msg.status == 'errorupdateresponse'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'no_rows_affected'){

                                        // Warning Notification:
                                        popup.warning({
                                            title: myDoc.notification_warning_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.no_rows_affected
                                        });

                                    }else if(response.msg.status == 'http_status_msg'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }
                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.error_word + " " + documentNo + " - " + response.responseArray['portalResults']
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 303){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_303
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 401){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_401
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 413){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_413
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 429){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_429
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 500){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_500
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 503){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_503
                                    });

                                }else if(response.responseArray['zatcaStatusCode'] == 504){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: myDoc.document_word + " " + documentNo + " - " + myDoc.error_504
                                    });

                                }
                            
                            }else{

                                if(response.responseArray['zatcaStatusCode'] == 202){
                                    
                                    if(response.msg.status == 'errorxml'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'no_rows_affected'){

                                        // Warning Notification:
                                        popup.warning({
                                            title: myDoc.notification_warning_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.no_rows_affected
                                        });

                                    }else if(response.msg.status == 'errorupdateresponse'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'errorupdatedevice'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'warning'){

                                        // Warning Notification:
                                        popup.warning({
                                            title: myDoc.notification_warning_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }
                                    
                                }else if(response.responseArray['zatcaStatusCode'] == 200){

                                    if(response.msg.status == 'errorxml'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'no_rows_affected'){

                                        // Warning Notification:
                                        popup.warning({
                                            title: myDoc.notification_warning_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + myDoc.no_rows_affected
                                        });

                                    }else if(response.msg.status == 'errorupdateresponse'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'errorupdatedevice'){

                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });

                                    }else if(response.msg.status == 'success'){

                                        // success notification:
                                        popup.success({
                                            title: myDoc.notification_success_title,
                                            message: myDoc.document_word + " " + documentNo + " - " + response.msg.msg
                                        });
                                    }
                                    
                                }
                            }
                            resolve(); // Resolve if successful 
                        }
                        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error: ', textStatus, errorThrown);
                        reject(); // Reject if there's an error
                    }
                });
            });
            promises.push(promise);
                        
            }
            else if((zatcaInvoiceType == 1 || zatcaInvoiceType == 0) && zatcaSuccessResponse == 3){// B2B Invoices that rejected

                const docNo = documentNo;
                const invoiceType = zatcaInvoiceType;

                // if B2B invoice type
                if(invoiceType == 1)
                {
                    //
                    const promise = new Promise((resolve, reject) =>{
                        $.ajax({
                            url: myDoc.ajaxUrl, 
                            method: "POST", 
                            data: {
                                action: 'zatca_reissue',
                                "doc_no_from_ajax": docNo
                            },
                            success: function(response) {
            
                                if(response.status == 'zatcaIssueDone')
                                {
                                    popup.success({
                                        title: myDoc.notification_success_title,
                                        message: response.msg
                                    });
                                }
                                
                                resolve(); // Resolve if successful
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error: ', textStatus, errorThrown);
                                reject(); // Reject if there's an error
                            }
                        });
                    });
                    promises.push(promise);
                    
                }else{  // if B2C invoice type or both

                    
                    const promise = new Promise((resolve, reject) => {
                        $.ajax({
                            url: myDoc.ajaxUrl, 
                            method: "POST", 
                            data: {
                                action: 'zatca_reissue',
                                "doc_no_from_ajax": docNo
                            },
                            success: function(response) {
            
                                if(response.status == 'zatcaIssueDone')
                                {
                                    popup.success({
                                        title: myDoc.notification_success_title,
                                        message: response.msg
                                    });
                                }
                                
                                resolve(); // Resolve if successful
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error: ', textStatus, errorThrown);
                                reject(); // Reject if there's an error
                            }
                        });
                    });
                    promises.push(promise);
                    //
                }
            }

        });

        // Wait for all promises to be resolved and then reload the page  
        Promise.all(promises)  
        .then(() => {  
            // All AJAX calls have completed successfully  
            setTimeout(() => {
                window.location.reload();
            }, 3000); 
        })  
        .catch(() => {  
            // Handle any errors here if needed  
            console.error('One or more requests failed.');  
        });
    });

    // resend the rejected doc to zatca again clear or report
    $(document).on('click', '#send-zatca-reissue', function(event){

        const docNo = $(this).data('doc-no');
        const invoiceType = $(this).data('data-invoice-typ');

        // if B2B invoice type
        if(invoiceType == 1){
            
            $.ajax({
                url: myDoc.ajaxUrl, 
                method: "POST", 
                data: {
                    action: 'zatca_reissue',
                    "doc_no_from_ajax": docNo
                },
                success: function(response) {

                    if(response.status == 'zatcaIssueDone')
                    {
                        popup.success({
                            title: myDoc.notification_success_title,
                            message: response.msg
                        });
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error: ', textStatus, errorThrown);
                }
            });
            
        }else{ // if B2C invoice type or both
            
            $.ajax({
                url: myDoc.ajaxUrl, 
                method: "POST", 
                data: {
                    action: 'zatca_reissue',
                    "doc_no_from_ajax": docNo
                },
                success: function(response) {

                    if(response.status == 'zatcaIssueDone')
                    {
                        popup.success({
                            title: myDoc.notification_success_title,
                            message: response.msg
                        });
                    }
                    
                    setTimeout(() => {
                        window.location.reload();
                    },3000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error: ', textStatus, errorThrown);
                }
            });
            //
        }
        

    });

    // return doc to zatca clear or report
    $(document).on('click', '#send-zatca-return', function(event) {

        const docNo = $(this).data('doc-no');
        const invoiceType = $(this).data('invoice-type');

        
        // B2B invoices
        if(invoiceType == 1){

            //start ajax
            $.ajax({
                url: myDoc.ajaxUrl, 
                method: "POST", 
                data: {
                    action: 'zatca_return',
                    "doc_no_from_ajax": docNo
                },
                success: function(response) {
                    //console.log(response);
                    if(response.msg.status == 'seller_second_business_id'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.seller_second_business_id
                        });

                        return;

                    }else if(response.msg.status == 'isexport0_buyervat'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.isexport0_buyervat
                        });

                        return;

                    }else if(response.msg.status == 'isexport1_buyervat'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.isexport1_buyervat
                        });

                        return;

                    }else if(response.msg.status == 'insert_buyer_poBox_additionalNo'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.insert_buyer_poBox_additionalNo
                        });

                        return;

                    }else if(response.msg.status == 'insert_buyer_additional_id'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.insert_buyer_additional_id
                        });

                        return;

                    }
                    else{

                        if(response.responseArray['clearanceStatus'] == "NOT_CLEARED")
                        {
                            if(response.responseArray['zatcaStatusCode'] == 400 || response.responseArray['zatcaStatusCode'] == null || response.responseArray['zatcaStatusCode'] == 0){

                                if(response.msg.status == 'errorupdateresponse'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'no_rows_affected'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: myDoc.no_rows_affected
                                    });

                                }else if(response.msg.status == 'http_status_msg'){

                                    if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                                    {
                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.mayBeDeviceError
                                        });
                                    }
                                    else
                                    {
                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: response.msg.msg
                                        });
                                    }

                                }

                                if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                                {
                                }
                                else
                                {
                                    const searchTerm = "Invoice Already Generated";
                                    const str = response.responseArray['portalResults'];
                                    if(str.toLowerCase().includes(searchTerm.toLowerCase()))
                                    {
                                        const numbers = str.match(/\d+/g) || []; // Matches all sequences of digits
                                        const text = str.match(/[^\d]+/g) || []; // Matches all non-digit characters
                                        // Convert numbers from strings to actual numbers  
                                        const numberList = numbers.map(Number);
                                        // Clean up text segments (trimming spaces, etc.)  
                                        const cleanText = text.map(segment => segment.trim()).filter(Boolean);
    
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: numberList[0] + " - " + myDoc.generatedAlready
                                        });
    
                                    }
                                    
                                    else
                                    {
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.error_word + " " + response.responseArray['portalResults']
                                        });
                                    }
                                }

                            }else if(response.responseArray['zatcaStatusCode'] == 303){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_303
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 401){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_401
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 413){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_413
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 429){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_429
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 500){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_500
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 503){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_503
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 504){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_504
                                });

                            }
                        
                        }else{

                            if(response.responseArray['zatcaStatusCode'] == 202){

                                if(response.msg.status == 'errorxml'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'no_rows_affected'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: myDoc.no_rows_affected
                                    });

                                }else if(response.msg.status == 'errorupdateresponse'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'errorupdatedevice'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'warning'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: response.msg.msg
                                    });
                                }
                           
                            }else if(response.responseArray['zatcaStatusCode'] == 200){

                                if(response.msg.status == 'errorxml'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'no_rows_affected'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: myDoc.no_rows_affected
                                    });
                                }else if(response.msg.status == 'errorupdateresponse'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'errorupdatedevice'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'success'){

                                    // success notification:
                                    popup.success({
                                        title: myDoc.notification_success_title,
                                        message: response.msg.msg
                                    });

                                }
                            }
                        }

                        setTimeout(() => {
                            window.location.reload();
                        },3000);
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error: ', textStatus, errorThrown);
                }
            });
            //end ajax

        }
        else{ // B2C invoices or both

            //start ajax
            $.ajax({
                url: myDoc.ajaxUrl, 
                method: "POST", 
                data: {
                    action: 'zatca_return',
                    "doc_no_from_ajax": docNo
                },
                success: function(response) {

                    //console.log(response);
                    if(response.msg.status == 'buyer_arabic_name'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.buyer_arabic_name
                        });

                        return;

                    }
                    else if(response.msg.status == 'seller_second_business_id'){

                        // Error notification:
                        popupValidation.error({
                            title: myDoc.notification_error_title,
                            message: myDoc.seller_second_business_id
                        });
                        return;
                        
                    }
                    else{

                        if(response.responseArray['reportingStatus'] == "NOT_REPORTED"){

                            if(response.responseArray['zatcaStatusCode'] == 400 || response.responseArray['zatcaStatusCode'] == null || response.responseArray['zatcaStatusCode'] == 0){

                                if(response.msg.status == 'errorupdateresponse'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'no_rows_affected'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: myDoc.no_rows_affected
                                    });

                                }else if(response.msg.status == 'http_status_msg'){

                                    if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                                    {
                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.mayBeDeviceError
                                        });
                                    }
                                    else
                                    {
                                        // Error notification:
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: response.msg.msg
                                        });
                                    }

                                }
                                if(response.responseArray['portalResults'] == "Object reference not set to an instance of an object.")
                                {
                                }
                                else
                                {
                                    const searchTerm = "Invoice Already Generated";
                                    const str = response.responseArray['portalResults'];
                                    if(str.toLowerCase().includes(searchTerm.toLowerCase()))
                                    {
                                        const numbers = str.match(/\d+/g) || []; // Matches all sequences of digits
                                        const text = str.match(/[^\d]+/g) || []; // Matches all non-digit characters
                                        // Convert numbers from strings to actual numbers  
                                        const numberList = numbers.map(Number);
                                        // Clean up text segments (trimming spaces, etc.)  
                                        const cleanText = text.map(segment => segment.trim()).filter(Boolean);
    
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: numberList[0] + " - " + myDoc.generatedAlready
                                        });
    
                                    }
                                    
                                    else
                                    {
                                        popupValidation.error({
                                            title: myDoc.notification_error_title,
                                            message: myDoc.error_word + " " + response.responseArray['portalResults']
                                        });
                                    }
                                }


                            }else if(response.responseArray['zatcaStatusCode'] == 303){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_303
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 401){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_401
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 413){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_413
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 429){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_429
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 500){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_500
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 503){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_503
                                });

                            }else if(response.responseArray['zatcaStatusCode'] == 504){

                                // Error notification:
                                popupValidation.error({
                                    title: myDoc.notification_error_title,
                                    message: myDoc.error_504
                                });

                            }
                        
                        }else{

                            if(response.responseArray['zatcaStatusCode'] == 202){

                                if(response.msg.status == 'errorxml'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'no_rows_affected'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: myDoc.no_rows_affected
                                    });

                                }else if(response.msg.status == 'errorupdateresponse'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'errorupdatedevice'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'warning'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: response.msg.msg
                                    });

                                }

                            }else if(response.responseArray['zatcaStatusCode'] == 200){

                                if(response.msg.status == 'errorxml'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'no_rows_affected'){

                                    popup.warning({
                                        title: myDoc.notification_warning_title,
                                        message: myDoc.no_rows_affected
                                    });

                                }else if(response.msg.status == 'errorupdateresponse'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                } else if(response.msg.status == 'errorupdatedevice'){

                                    // Error notification:
                                    popupValidation.error({
                                        title: myDoc.notification_error_title,
                                        message: response.msg.msg
                                    });

                                }else if(response.msg.status == 'success'){

                                    // success notification:
                                    popup.success({
                                        title: myDoc.notification_success_title,
                                        message: response.msg.msg
                                    });
                                    
                                }
                            }
                        }
                        setTimeout(() => {
                            window.location.reload();
                        },3000);
                    }
                    

                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error: ', textStatus, errorThrown);
                }
            });
            //end ajax 
        }


    });

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

                    // Error notification:
                    popupValidation.error({
                        title: myDoc.notification_error_title,
                        message: myDoc.error_word + " " + response.data.message
                    });
                    
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
});

  

  