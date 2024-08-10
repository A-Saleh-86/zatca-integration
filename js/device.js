jQuery(document).ready(function($) {

    $("#device_insert_form").submit(function(event){

        event.preventDefault();
        var formData = $(this).serialize();
        var formData1 = $(this).serializeArray();

        $.ajax({
            url:myDevice.ajaxUrl,
            method: "POST",
            data: {
                "action": "insert_device",
                "insert_form_ajax_data": formData
            },
            success: function(data){
                //console.log(data);
                if(data.active == true && data.error == false){

                    // Error notification:
                    popupValidation.error({
                        title: myDevice.notification_error_title,
                        message: myDevice.device_active
                    });

                    return;

                }else if(data.active == false && data.error == true){
                    // alert(data.msg);
                    
                    // Error notification:
                    popupValidation.error({
                        title: myDevice.notification_error_title,
                        message: data.msg
                    });

                    return;

                }else if(data.active == false && data.error == false){
                   
                    // alert(myDevice.device_inserted);
                    // localStorage.setItem("deviceCSID", formData1[0].value);
                    // window.location.href = myDevice.adminUrl;

                    // success notification:
                    popup.success({
                        title: myDevice.notification_success_title,
                        message: myDevice.device_inserted
                    });

                    localStorage.setItem("deviceCSID", formData1[0].value);

                    setTimeout(function() {
                        window.location.href = myDevice.adminUrl;
                    }, 3000); 

                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    $("#device_edit_form").submit(function(event){

        event.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url:myDevice.ajaxUrl,
            method: "POST",
            data: {
                "action": "edit_device",
                "edit_form_ajax_data": formData
            },
            success: function(data){

                if(data.active == true && data.error == false){

                    // Error notification:
                    popupValidation.error({
                        title: myDevice.notification_error_title,
                        message: myDevice.device_active
                    });

                    return;

                }else if(data.active == false && data.error == true){

                    // Error notification:
                    popupValidation.error({
                        title: myDevice.notification_error_title,
                        message: data.msg
                    });

                    return;

                }else if(data.active == false && data.error == false){

                    // alert(myDevice.device_updated);
                    // window.location.href = myDevice.adminUrl;

                    // success notification:
                    popup.success({
                        title: myDevice.notification_success_title,
                        message: myDevice.device_updated
                    });

                    setTimeout(function() {
                        window.location.href = myDevice.adminUrl;
                    }, 3000); 
                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

});