<?php 

require_once '../../radius-report-config.php';

/**
 * Sets the possible plan values
 */

$plan = abs( intval( $_POST['x_plan_custom'] ) ) ;

//error_log( "PLAN: $plan" );

$price = 0; // initilize the price 

if ( is_int( $plan ) && $plan <= 49 && $plan >= 1 ) {
	
	$price = 99 * $plan;
	
} else if ( $plan == 50 ) {
	
	$price = 2450;
	
} else if ( $plan <= 0 ) {
	
	$price = 0;

} else {	
	
	$price = ( 99 * ($plan - 50) ) + 2450;
	
}

$tax = number_format($price * 0,2); // Set tax

$amount = number_format($price + $tax,2); // Set total amount

?>
<!doctype html>
<html>
  <head>
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
  
  <div class="box_frame">
  
	    <h2>Order</h2>
	    <table>
	      <tfoot>
	        <tr>
	          <td>Total</td>
	          <td>$<?php echo $amount?></td>
	        </tr>
	      </tfoot>
	      <tbody>
	        <tr>
	          <td><?php echo ucfirst($plan)?> Report(s)</td>
	          <td>$<?php echo $price?></td>
	        </tr>
	        <tr>
	          <td>Tax (0.0%)</td>
	          <td>$<?php echo $tax?></td>
	        </tr>
	      </tbody>
	    </table>
	    
	    <form method="post" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/e-check/checkout_form.php">
	    
			<input type="hidden" name="x_plan" value="<?php echo $plan?>">
			<input type="hidden" name="x_amount" value="<?php echo $amount?>">
	   		<input type="hidden" name="x_current_user_id" value="<?php echo $_POST['x_current_user_id']?>">
			<input type="hidden" name="x_current_user_email" value="<?php echo $_POST['x_current_user_email']?>">
			<input type="hidden" name="x_current_user_street_address" value="<?php echo $_POST['x_current_user_street_address'] ?>">
	    	<input type="hidden" name="x_current_user_zip" value="<?php echo $_POST['x_current_user_zip'] ?>">
	    	<input type="hidden" name="x_current_user_state" value="<?php echo $_POST['x_current_user_state'] ?>">
	    	<input type="hidden" name="x_current_user_city" value="<?php echo $_POST['x_current_user_city'] ?>">
	    	<input type="hidden" name="x_current_user_country" value="<?php echo $_POST['x_current_user_country'] ?>">
	      	<input type="submit" class="submit" value="Continue">
	    </form>
    
  </div>
    
  </body>
</html>
