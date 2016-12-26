<?php 

require_once '../../radius-report-config.php';

?>

<!doctype html>
<html>
  <head>
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="../../lib/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="../../lib/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../../lib/js/jquery.validate.creditcardtypes.js"></script>
    <script>
      $(document).ready(function(){
        $("#checkout_form").validate();
      });
      </script>
  </head>
  <body>
   <div class="box_frame">
    <h2>Secure Checkout</h2>

        <form method="post" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/e-check/process_sale.php" id="checkout_form">

        <input type="hidden" name="x_plan" value="<?php echo $_POST['x_plan']?>">
        <input type="hidden" name="x_amount" value="<?php echo $_POST['x_amount']?>">
        <input type="hidden" name="x_current_user_id" value="<?php echo $_POST['x_current_user_id'] ?>">
        <input type="hidden" name="x_current_user_email" value="<?php echo $_POST['x_current_user_email']?>">    	
        <input type="hidden" name="x_email_customer" value="true">
        
        
        <input type="hidden" name="x_method" value="ECHECK">
        <input type="hidden" name="x_echeck_type" value="WEB">
        <input type="hidden" name="x_recurring_billing" value="FALSE">
        
        
        
        
       <fieldset>
        <div>
          <label>ABA Routing Number </label> 
          <input type="text" class="digits required" size="18" name="x_bank_aba_code" minlength="9" maxlength="9" value=""></input>
        </div>
        <div>
          <label>Account Number</label>
          <input type="text" class="digits required" size="22" name="x_bank_acct_num" maxlength="20" value=""></input>
        </div>

      </fieldset>
    
      <fieldset>

        <div>
          <label>Account Type</label>          
          	<select class="select required" size="1" name="x_bank_acct_type">
				<option selected value="BUSINESSCHECKING">Business Checking</option>
				<option value="CHECKING">Personal Checking</option>
				<option value="SAVINGS">Savings Account</option>
			</select>
        </div>
      </fieldset>

        <div>
          <label>Bank Name</label>
          <input type="text" class="text required" size="36" name="x_bank_name" value=""></input>
        </div>
        
        <div>
          <label>Name on Account</label>
          <input type="text" class="text required" size="36" name="x_bank_acct_name" value=""></input>
        </div>
        
        <div>
          <label>Email Receipt/Confirmation to:</label>
          <input type="text" class="text required" size="45" name="x_current_user_email" value="<?php echo $_POST['x_current_user_email']?>"></input>
        </div>
        
        <p><i>( By clicking "Authorize Payment" I authorize Parcel Forecast, LLC to charge this bank account one-time in the amount of <b>$<?php echo $_POST['x_amount']?></b> )</i></p>
        
        
        <input type='submit' value='Authorize Payment' class='submit buy'>
        
        
    </form>
    
    </div>
    
  </body>
</html>