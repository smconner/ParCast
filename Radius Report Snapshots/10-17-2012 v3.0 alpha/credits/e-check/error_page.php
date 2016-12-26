<?php 

require_once '../../radius-report-config.php';

//

?>

<!doctype html>
<html>
  <head>
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  
  <div class="box_frame">
  

    <h2>Processing Error!</h2>
    <div class="error">
      <h3>We're sorry, but we can't process your order at this time due to the following error:</h3>
      <?php echo htmlentities($_GET['response_reason_text'])?>
      <br>
      <?php echo htmlentities($_GET['response_alt_reason_text'])?>
      <br>



    </div>
    
    <form method="post" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/e-check/checkout_form.php">
    	
		<input type="hidden" name="x_plan" value="<?php echo $_GET['x_plan'] ?>">
		<input type="hidden" name="x_amount" value="<?php echo $_GET['x_amount'] ?>">
   		<input type="hidden" name="x_current_user_id" value="<?php echo $_GET['x_current_user_id'] ?>">
		<input type="hidden" name="x_current_user_email" value="<?php echo $_GET['x_current_user_email'] ?>">
		<input type="hidden" name="x_current_user_street_address" value="<?php echo $_GET['x_current_user_street_address'] ?>">
    	<input type="hidden" name="x_current_user_zip" value="<?php echo $_GET['x_current_user_zip'] ?>">
    	<input type="hidden" name="x_current_user_state" value="<?php echo $_GET['x_current_user_state'] ?>">
    	<input type="hidden" name="x_current_user_city" value="<?php echo $_GET['x_current_user_city'] ?>">
    	<input type="hidden" name="x_current_user_country" value="<?php echo $_GET['x_current_user_country'] ?>">

      <input type="submit" class="submit" value="Go Back">
    </form>
    
    
    <form method="post" action="<?php echo RR_HTTP_HOST ?>/wp-admin/">
      <input type="submit" class="submit" value="Leave Secure Checkout">
    </form>
    
    
    
      
  </div>
  
  </body>
</html>
