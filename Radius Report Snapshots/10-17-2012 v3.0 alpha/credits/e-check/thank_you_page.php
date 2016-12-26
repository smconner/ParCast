<?php 

require_once '../../radius-report-config.php';

?>

<!doctype html>
<html>
  <head>
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  
   <div class="box_frame">
  
    	<h2>Thank You!</h2>
    	Your order has been processed.
    	<h3>Your transaction ID: <?php echo htmlentities($_GET['transaction_id'])?></h3>
    		
    <div class="id"></div>
    You may now generate upto <?php echo htmlentities($_GET['new_credit_balance'])?> more reports through your account.<br>
	&nbsp &nbsp &nbsp (A confirmation email has been sent to: <?php echo htmlentities($_GET['email_address'])?>)
	
	<form method="get" action="<?php echo RR_HTTP_HOST ?>/wp-admin/">
      <input type="submit" class="submit" value="Leave Secure Checkout">
    </form>

	</div>
  </body>
</html>
