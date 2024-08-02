// Get Data from insert page and return it as ajax data to db:
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
                if(data.active == true && data.error == false)
                {
                    alert(myDevice.device_active);

                    //alert("Not allowed to add more one device active");
                    return;
                }
                else if(data.active == false && data.error == true)
                {
                    alert(data.msg);
                }
                else if(data.active == false && data.error == false)
                {
                    alert(myDevice.device_inserted);
                    localStorage.setItem("deviceCSID", formData1[0].value);
                    window.location.href = myDevice.adminUrl;
                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

// Get Data from Edit page and return it as ajax data to db:
jQuery(document).ready(function($) {
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
                //console.log(data);
                if(data.active == true && data.error == false)
                {
                    alert(myDevice.device_active);
                    return;
                }
                else if(data.active == false && data.error == true)
                {
                    alert(data.msg);
                }
                else if(data.active == false && data.error == false)
                {
                    alert(myDevice.device_updated);
                    window.location.href = myDevice.adminUrl;
                }
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});