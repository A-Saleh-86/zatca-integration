jQuery(document).ready(function($) {  
    // Handle the form submission  
    $('#sub_btn').click(function(e) {  

        e.preventDefault(); // Prevent the default form submission  
        
        var subscription_id = $('#subscription_id').val(); 
        
        if (subscription_id == '') 
        {
            // Error notification:
            popupValidation.error({
                title: "Error",
                message: "Subscription ID must be have a value, cant't be empty!"
            });

            return;
        }
        else 
        {
            // Validation passed, you can proceed with the rest of the code
            $.ajax({  
                type: 'POST',  
                url: ajax_subscribe.ajax_url,  
                data: {  
                    action: 'handle_form_subscription',  
                    subscription_id: subscription_id,  
                },  
                success: function(response) {  
                    //console.log(response);
                    $('#response').html(response); // Display the result in the response div  
                },  
                error: function(xhr, status, error) {  
                    //console.error(xhr); // Log errors in the console  
                    $('#response').html('An error occurred.'); // Display an error message  
                }  
            });
        }
        
    });  

});