// Confirm Delete:
jQuery(document).ready(function($) {
    $('.confirm').click(function () {

        return confirm('Are You Sure?');
    })
});


// DataTables Function:
jQuery(document).ready( function () {
    $('.select2').select2();
    $('#ah').DataTable({
        responsive: true
    });
    
} );






