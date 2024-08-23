<form id="" method="post" class="container">
    <div class="row p-3">
        <div class="form-group col-md-12 p-3">
            <label for="from_date"><?php echo _e('Zatca Subscription ID', 'zatca') ?> </label>
            <input type="text" 
            name="subscription_id" 
            id="subscription_id" 
            class="form-control" 
            placeholder="<?php echo _e('Enter Your Zatca Subscription ID', 'zatca') ?>"
            required>
        </div>
    </div>
    <div class="row m-3">
        <div class="form-group col-md-12 m-3" style="display: flex;justify-content: center;">
            <button class="btn btn-sm btn-primary m-2" id="check_counter_gap"><?php echo _e('Activate Plugin Now', 'zatca') ?> </button>
        </div>
    </div>
</form>