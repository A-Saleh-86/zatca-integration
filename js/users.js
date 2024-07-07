// user insert page - checkbox:
$(document).ready(function() {
    $('#is-remind').change(function() {
        if ($(this).is(':checked')) {
            $('#remindInterval').prop('disabled', false);
        } else {
            $('#remindInterval').prop('disabled', true);
        }
    });
});


// user insert page:
$(document).ready(function() {

    // get aName \ eName:
    $('#person-no').change(function() {
        
        //user Id of choosen: 
        var userId = $(this).val();
        
        // arabic name input:
        const aNameInput = document.getElementById('arabic-name');
        
        // english name input:
        const eNameInput = document.getElementById('english-name');

        $.ajax({
            url: myUser.ajaxUrl,
            method: "POST",
            data: {
                action: 'get_user_admin_data',
                user_id: userId
            },
            success: function(response) {

                if (response.first_name && response.last_name) {
                    aNameInput.value = response.first_name + ' ' + response.last_name;
                    eNameInput.value = response.first_name + ' ' + response.last_name;
                }else{

                    aNameInput.value = '';
                    eNameInput.value = '';
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });

    });

    // Insert Data into Database:
    $("#user_form").submit(function(event){
        event.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: myUser.ajaxUrl, 
            method: "POST", 
            data: {
                "action": "insert_user",
                "insert_user_data": formData
            },
            success: function(data){

                if(data == 'denied'){

                    alert('User Already Exist');
                    
                }else{
                    
                    alert('User Inserted Success');
                    
                    window.location.href = myUser.adminUrl;
                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Edit User Data:
    $("#edit_user_form").submit(function(event){
        event.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: myUser.ajaxUrl, 
            method: "POST", 
            data: {
                "action": "edit_user",
                "edit_user_data": formData
            },
            success: function(data){

                alert(data);

                window.location.href = myUser.adminUrl;
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

    })
});