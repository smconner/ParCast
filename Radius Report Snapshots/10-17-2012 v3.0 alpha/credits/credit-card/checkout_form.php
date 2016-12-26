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

        <form method="post" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/credit-card/process_sale.php" id="checkout_form">

        <input type="hidden" name="x_plan" value="<?php echo $_POST['x_plan']?>">
        <input type="hidden" name="x_amount" value="<?php echo $_POST['x_amount']?>">
        <input type="hidden" name="x_current_user_id" value="<?php echo $_POST['x_current_user_id'] ?>">
        <input type="hidden" name="x_current_user_email" value="<?php echo $_POST['x_current_user_email']?>">    	
        <input type="hidden" name="x_email_customer" value="true">

      <fieldset>
        <div>
          <label>Credit Card Number</label>
          <input type="text" class="text required creditcard" size="18" name="x_card_num" value=""></input>
        </div>
        <div>
          <label>Exp.</label>
          <input type="text" class="text required" size="5" name="x_exp_date" value="mm/yy"></input>
        </div>
        <div>
          <label>CCV</label>
          <input type="text" class="text required" size="4" name="x_card_code" value=""></input>
        </div>
      </fieldset>
      
      <img src="../../images/cc_logo.png" />
      
      <fieldset>
        <div>
          <label>Address</label>
          <input type="text" class="text required" size="26" name="x_current_user_street_address" value="<?php echo $_POST['x_current_user_street_address'] ?>"></input>
        </div>
        <div>
          <label>City</label>
          <input type="text" class="text required" size="15" name="x_current_user_city" value="<?php echo $_POST['x_current_user_city'] ?>"></input>
        </div>
      </fieldset>
      <fieldset>
        <div>
          <label>State</label>
          <input type="text" class="text required" size="4" name="x_current_user_state" value="<?php echo $_POST['x_current_user_state'] ?>"></input>
        </div>
        <div>
          <label>Zip Code</label>
          <input type="text" class="text required" size="9" name="x_current_user_zip" value="<?php echo $_POST['x_current_user_zip'] ?>"></input>
        </div>
        <div>
          <label>Country</label>
          <input type="text" class="text required" size="22" name="x_current_user_country" value="<?php echo $_POST['x_current_user_country'] ?>"></input>
        </div>
      </fieldset>
        <div>
          <label>Email Receipt/Confirmation to:</label>
          <input type="text" class="text required" size="45" name="x_current_user_email" value="<?php echo $_POST['x_current_user_email']?>"></input>
        </div>
        <?php 
        
        $number_credits = $_POST['x_plan'];
        
        if ($number_credits == 1) {
        	
       		echo " <input type='submit' value='Purchase $number_credits Report' class='submit buy'> ";
        	
        } else {
        	
        	echo " <input type='submit' value='Purchase $number_credits Reports' class='submit buy'> ";
        	
        }

        
        ?>
    </form>
    
    </div>
    
  </body>
</html>
