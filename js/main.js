
// Confirm Delete:
jQuery(document).ready(function($) {
    var currentLang = main.locale;

    // $('.confirm').click(function () {
    //     if (currentLang === 'ar') 
    //     {
    //         return confirm('هل أنت متأكد؟');
    //     }
    //     else
    //     {
    //         return confirm('Are you sure?');
    //     }

        
    // })

    $('.confirm').click(function (event) {
        // Prevent the default action immediately
        event.preventDefault();
    
        // Store the current element reference
        const currentElement = $(this);
    
        // Determine the message based on the current language
        let message = currentLang === 'ar' ? 'هل أنت متأكد؟' : 'Are you sure?';
        let title = currentLang === 'ar' ? 'حذف' : 'Delete';

    
        // Use the custom dialog
        popup.dialog({
            title: title,
            message: message,
            callback: (result) => {
                if (result != 'cancel') {
                    // User confirmed: Proceed with the action
                    window.location.href = currentElement.attr('href');
                }
            }
        });
    
        // Return false to ensure that the default action does not continue
        return false;
    });
    
    

    // Normal Notification
    window.popup = Notification({
        position: 'center',
        duration: 5000,
        isHidePrev: false,
        isHideTitle: false,
        maxOpened: 3,
    });

    // Notification cant Hide:
    window.popupValidation = Notification({
        position: 'center',
        duration: 0,
        isHidePrev: false,
        isHideTitle: false,
        maxOpened: 3,
    });
});


// DataTables Function :
jQuery(document).ready( function () {
    
    $('.select2').select2();


    var currentLang = main.locale;

    // For View Customer Table: 
    if (currentLang === 'ar') {
        $('#scroll_table').DataTable({
            scrollX: true,
            language: {
                url: main.dtLoc,
            },
        });
    } else {
        $('#scroll_table').DataTable({
            scrollX: true,
        });
    }

    // For All Table:
    if (currentLang === 'ar') {
        $('#example').DataTable({
            responsive:true,
            language: {
                url: main.dtLoc,
            },
            select: {  
                style: 'multi'  
            },
        });
    } else {
        $('#example').DataTable({
            responsive:true,
            select: {  
                style: 'multi'  
            },
        });
    }




} );

// DataTable Date Filter
$(document).ready(function($){

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
    let table = new DataTable('#example');
    
     
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
        $('#failed').on('change', function() {
            if (this.checked) {
                // Filter to show only rows with "Failed" status
                table.column(5).search('false').draw();
            } else {
                // Clear the filter
                table.column(5).search('').draw();
            }
        });

    // username filter:
    $('#username').on('change', function() {
        var selectedUsername = $(this).val();
        if (selectedUsername) {
            // Filter the table by the selected username
            table.column(0).search(selectedUsername).draw();
        } else {
            // Reset the filter if "..." is selected
            table.column(0).search('').draw();
        }
    });

    

})


