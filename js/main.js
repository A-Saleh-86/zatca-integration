
// Confirm Delete:
jQuery(document).ready(function($) {
    $('.confirm').click(function () {

        return confirm('Are You Sure?');
    })
});


// DataTables Function :
jQuery(document).ready( function () {
    $('.select2').select2();

   
    $('#example').DataTable( {
        responsive: true,

        select: {  
            style: 'multi'  
        },

        "columnDefs": [
            { "orderable": false, "targets": 0 }, // Disables sorting for the first column (index 0)  
            {
                "targets": [ 17, 18 ],
                "searchable": true,
                "visible": false
            }
        ]                 
    } );

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
     
    // DataTables initialisation
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
            table.column(17).search('^(0|3)$', true, false).column(18).search('^(NULL)$', true, false).draw();
            //table.draw();
        } else {
            // Clear the filter
            table.column(17).search('').column(18).search('').draw();
        }
    });

    // username filter:
    $('#username').on('change', function() {
        var selectedUsername = $(this).val();
        if (selectedUsername) {
            // Filter the table by the selected username
            table.column(2).search(selectedUsername).draw();
        } else {
            // Reset the filter if "..." is selected
            table.column(2).search('').draw();
        }
    });
})


