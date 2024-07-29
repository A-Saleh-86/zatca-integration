// user insert page - checkbox:
$(document).ready(function() {

    // user insert page - checkbox:
    $('#is-remind').change(function() {
        if ($(this).is(':checked')) {
            $('#remindInterval').prop('disabled', false);
        } else {
            $('#remindInterval').prop('disabled', true);
        }
    });

    // get aName \ eName from woo:
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

        // validation on person-no input:
        if ($('#person-no').val() === '' ) {
            // alert("person-no Cant be Null");
            alert(myUser.person_no_validation);
            return;
        }

        // make validation to must insert value in reminder hours if check the reminder checkbox:
        if ($('#is-remind').is(':checked')){

            // if value = 0 OR Null:
            if($('#remindInterval').val() == 0 || $('#remindInterval').val() == ''){
                alert(myUser.reminder_hours_validation);
                return;
            }

            // if 0 > value < 23 :
            if($('#remindInterval').val() < 0 || $('#remindInterval').val() > 23){
                alert(myUser.reminder_hours_validation_number);
                return;
            }
            
        }

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

                    alert(myUser.user_exist);
                    
                }else{
                    
                    alert(myUser.user_inserted);
    
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

        // validation on person-no input:
        if ($('#person-no').val() === '' ) {
            // alert("person-no Cant be Null");
            alert(myUser.person_no_validation);
            return;
        }

        // make validation to must insert value in reminder hours if check the reminder checkbox:
        if ($('#is-remind').is(':checked')){

            if($('#remindInterval').val() == 0 || $('#remindInterval').val() == ''){
                alert(myUser.reminder_hours_validation);
                return;
            }

            // if 0 > value < 23 :
            if($('#remindInterval').val() < 0 || $('#remindInterval').val() > 23){
                alert(myUser.reminder_hours_validation_number);
                return;
            }
            
        }
        
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

// Function to check conditions to show notifications:
function checkConditions() {

    // run this function in admin panel only:
    if (myUser.isAdmin === 'true'){

    
        // Add your specific conditions here
        $.ajax({
            url: myUser.ajaxUrl,
            method: "POST",
            data: {
                action: 'check_admin_notification'
            },
            success: function(response) {

                if (response.msg !== null && response.msg !== undefined) {
                    alert(response.msg);
                }
                
                // Calculate the interval time based on the response value
                var hourMultiplier = parseInt(response.hours);
                var intervalTime = hourMultiplier * 3600000; // 1 hour in milliseconds
                
                // setTimeout(checkConditions, intervalTime);
                // Store the timer state in localStorage
                var timerState = {
                    startTime: new Date().getTime(),
                    intervalTime: intervalTime
                };
                localStorage.setItem('timerState', JSON.stringify(timerState));
                                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
            }
        });

    }
}

// show notification if exist after 1 min from start the application:
// var initialIntervalTime = 60000;

// run checkCondition function:
// setTimeout(checkConditions, initialIntervalTime);

// On page load, check if there's a stored timer state
var storedTimerState = localStorage.getItem('timerState');
if (storedTimerState) {
    var timerState = JSON.parse(storedTimerState);
    var timeElapsed = new Date().getTime() - timerState.startTime;
    var remainingTime = timerState.intervalTime - timeElapsed;
    
    // If the remaining time is positive, schedule the next execution
    if (remainingTime > 0) {
        setTimeout(checkConditions, remainingTime);
    } else {
        // If the remaining time is 0 or negative, run the function immediately
        checkConditions();
    }
} else {
    // If there's no stored timer state, schedule the first execution
    setTimeout(checkConditions, 60000); 
}

