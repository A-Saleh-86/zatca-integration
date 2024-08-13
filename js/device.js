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

    // Delete Device:
    $(document).on('click', '#delete_device', function(event) {
       
        event.preventDefault();

        const deviceNo = $(this).data('device-no');
    
        // Global Normal Notification for delete dialog:
        window.popupDialog = Notification({
            position: 'center',
            duration: 5000,
            isHidePrev: false,
            isHideTitle: false,
            maxOpened: 3,
        });
    
        // Use the delete dialog:
        popupDialog.dialog({
            title: myDevice.delete_title,
            message: myDevice.delete_msg,
            callback: (result) => {
                if (result != 'cancel') {

                    $.ajax({
                        url: myDevice.ajaxUrl,
                        method: "POST",
                        data: {
                            "action": "delete_device",
                            "device-no": deviceNo
                        },
                        success: function(data){
            
                            // success notification:
                            popup.success({
                                title: myDevice.notification_success_title,
                                message: data
                            });
            
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000); 

                            
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });

                }
            }
        });

    });

});