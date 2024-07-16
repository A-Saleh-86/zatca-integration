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
                alert(data);
                localStorage.setItem("deviceCSID", formData1[0].value);
                window.location.href = myDevice.adminUrl;
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
                alert(data);
                window.location.href = myDevice.adminUrl;
                
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});