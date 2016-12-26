<?php 

require_once '../../radius-report-config.php';

?>

<!doctype html>
<html>
  <head>
    <title>Secure Checkout</title>
    <link rel="stylesheet" href="style.css">
    
	    <script type="text/javascript" language="javascript">
	    
			function radio_checker() {
				
					if ( document.x_plan_custom.x_plan_id.checked ) {
						
						document.x_plan_custom.x_plan_custom_id.disabled = false;
						document.x_plan_custom.x_plan_custom_id.focus();
						
					} else {
						
						document.x_plan_custom.x_plan_custom_id.disabled = true;
						
					}
			}
			
		</script>
		
  	<style type='text/css'> 
	
		#selection {
			font-size: 16px;
	    	padding-left: 30px;
	    	padding-right: 30px;
	    	line-height: 40px;
		}
		
		#subscript {
			font-size: 12px;
	    	padding-left: 57px;
	    	margin-top: 17px;
		}

	</style>   
    
    
  </head>
  <body>
  <div class="box_frame">

    <h2>Select plan</h2>

    <form method="post" name="x_plan_custom" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/e-check/review_order.php" method="POST">
    
		<div id="selection" >
				<input name="x_plan_custom" type="radio" value="custom" id="x_plan_id" onchange="radio_checker();" /> <b>Pay-As-You-Go </b> 
				<input name="x_plan_custom" type="text" value="1" id="x_plan_custom_id" disabled="true" size="2" maxlength="2" /> report(s) @ $99/report <br />
				<input name="x_plan_custom" type="radio" value="50" onchange="radio_checker();" /> <b>Subscription </b> Upto 50 reports per year @ $2,450/yr<br />
		</div>
		
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
