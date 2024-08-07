jQuery(document).ready(function($) {  
    // Handle the form submission  
    $('#check_counter_gap').click(function(e) {  
        e.preventDefault(); // Prevent the default form submission  
        
        var from_date = $('#from_date').val();  
        var to_date = $('#to_date').val();  
        var buildingNo = $('#Branch').val();  
        
        

        $.ajax({  
            type: 'POST',  
            url: ajax_object.ajax_url,  
            data: {  
                action: 'handle_form_tampering',  
                from_date: from_date,  
                to_date: to_date,  
                buildingNo: buildingNo,
                check_type: 'check_counter_gap'
            },  
            success: function(response) {  
                console.log(response);
                $('#response').html(response); // Display the result in the response div  
            },  
            error: function(xhr, status, error) {  
                console.error(xhr); // Log errors in the console  
                $('#response').html('An error occurred.'); // Display an error message  
            }  
        });  
    });  

    //
    $('#check_hash_gap').click(function(e) {  
        e.preventDefault(); // Prevent the default form submission  
        
        var from_date = $('#from_date').val();  
        var to_date = $('#to_date').val();  
        var buildingNo = $('#Branch').val();  
        
        

        $.ajax({  
            type: 'POST',  
            url: ajax_object.ajax_url,  
            data: {  
                action: 'handle_form_tampering',  
                from_date: from_date,  
                to_date: to_date,  
                buildingNo: buildingNo,
                check_type: 'check_hash_gap'
            },  
            success: function(response) {  
                $('#response').html(response); // Display the result in the response div  
            },  
            error: function(xhr, status, error) {  
                console.error(xhr); // Log errors in the console  
                $('#response').html('An error occurred.'); // Display an error message  
            }  
        });  
    });  


});