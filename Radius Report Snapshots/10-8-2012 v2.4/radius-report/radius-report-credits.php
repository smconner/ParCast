<?php

/** 
 * ===============================================================================
 *	This file is responsible for processing credits
 * ===============================================================================
 **/

require_once dirname(__FILE__) . '/radius-report-config.php';


/** 
 * ===============================================================================
 **/

function radius_report_creditcards_page() { 

	$current_user = wp_get_current_user();
	$current_user_id =  $current_user->ID;
	$current_user_email = $current_user->user_email;
	$current_user_street_address = $current_user->streetaddress;
	$current_user_zip = $current_user->zip;
	$current_user_state = $current_user->state;
	$current_user_city = $current_user->city;
	$current_user_country = $current_user->country;
	
	//error_log("User Info: " . print_r($current_user ,1) );

	?>

	<div class="wrap">
		<div class="icon32" id="icon-plugins"><br></div>
		<h2>Pay with Credit Card</h2>
	</div>
	

<h2>How it works</h2>

<table width="700" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="1%" rowspan="3" style="">&nbsp;</td>
    <td width="4%" align="center" valign="middle" style="color: #999; font-size: 24px;">1</td>
    <td width="15%" valign="middle" style="color: #333; font-size: 16px; font-weight: bold;">Register <img src="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/images/green-checkmark_63x84.png" width="63" height="84" align="absmiddle" /></td>
    <td width="1%" rowspan="3" style="border-right-width: thin; border-right-style: dotted; border-right-color: #CCC;">&nbsp;</td>
    <td width="4%" align="center" valign="middle" style="color: #999; font-size: 24px;">2</td>
    <td width="17%" valign="middle" style="color: #333; font-size: 16px; font-weight: bold;">Purchase Reports</td>
    <td width="1%" rowspan="3" style="border-right-width: thin; border-right-style: dotted; border-right-color: #CCC;">&nbsp;</td>
    <td width="4%" align="center" valign="middle" style="color: #999; font-size: 24px;">3</td>
    <td width="19%" valign="middle" style="color: #333; font-size: 16px; font-weight: bold;">Generate</td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
    <td valign="top" style="color: #333; font-size: 14px;">Good Job!</td>
    <td align="center" valign="top" style="color: #333">&nbsp;</td>
    <td valign="top" style="color: #333; font-size: 14px;">Now choose how many reports you'd like to purchase... <br /> <br /> Just click 'Add Reports' to get started!</td>
    <td align="center" valign="top" style="color: #333">&nbsp;</td>
    <td valign="top" style="color: #333; font-size: 14px;">Then start generating reports. <br /> <br /> Simple!</td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table> 

	<div style="padding-left: 265px;">
	
	    <form method="post" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/credit-card/select_size.php">
	    
    	<input type="hidden" name="x_current_user_id" value="<?php echo $current_user_id ?>">
    	<input type="hidden" name="x_current_user_email" value="<?php echo $current_user_email ?>">
    	<input type="hidden" name="x_current_user_street_address" value="<?php echo $current_user_street_address ?>">
    	<input type="hidden" name="x_current_user_zip" value="<?php echo $current_user_zip ?>">
    	<input type="hidden" name="x_current_user_state" value="<?php echo $current_user_state ?>">
    	<input type="hidden" name="x_current_user_city" value="<?php echo $current_user_city ?>">
    	<input type="hidden" name="x_current_user_country" value="<?php echo $current_user_country ?>">

	    	<p class="submit"><input type="submit" class="button-primary" value="Add Reports"  /></p>
	    </form>
	</div>

	<?php

}



/** 
 * ===============================================================================
 **/

function radius_report_echeck_page() {

	$current_user = wp_get_current_user();
	$current_user_id =  $current_user->ID;
	$current_user_email = $current_user->user_email;
	$current_user_street_address = $current_user->streetaddress;
	$current_user_zip = $current_user->zip;
	$current_user_state = $current_user->state;
	$current_user_city = $current_user->city;
	$current_user_country = $current_user->country;
	
	//error_log("User Info: " . print_r($current_user ,1) );
   
	?>
	<div class="wrap">
		<div class="icon32" id="icon-plugins">
			<br>
		</div>
	<h2>Pay with Check</h2>
	</div>
	
<h2>How it works</h2>

<table width="700" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td width="1%" rowspan="3" style="">&nbsp;</td>
    <td width="4%" align="center" valign="middle" style="color: #999; font-size: 24px;">1</td>
    <td width="15%" valign="middle" style="color: #333; font-size: 16px; font-weight: bold;">Register <img src="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/images/green-checkmark_63x84.png" width="63" height="84" align="absmiddle" /></td>
    <td width="1%" rowspan="3" style="border-right-width: thin; border-right-style: dotted; border-right-color: #CCC;">&nbsp;</td>
    <td width="4%" align="center" valign="middle" style="color: #999; font-size: 24px;">2</td>
    <td width="17%" valign="middle" style="color: #333; font-size: 16px; font-weight: bold;">Purchase Reports</td>
    <td width="1%" rowspan="3" style="border-right-width: thin; border-right-style: dotted; border-right-color: #CCC;">&nbsp;</td>
    <td width="4%" align="center" valign="middle" style="color: #999; font-size: 24px;">3</td>
    <td width="19%" valign="middle" style="color: #333; font-size: 16px; font-weight: bold;">Generate</td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
    <td valign="top" style="color: #333; font-size: 14px;">Good Job!</td>
    <td align="center" valign="top" style="color: #333">&nbsp;</td>
    <td valign="top" style="color: #333; font-size: 14px;">Now choose how many reports you'd like to purchase... <br /> <br /> Just click 'Add Reports' to get started!</td>
    <td align="center" valign="top" style="color: #333">&nbsp;</td>
    <td valign="top" style="color: #333; font-size: 14px;">Then start generating reports. <br /> <br /> Simple!</td>
  </tr>
  <tr>
    <td align="center" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table> 

	<div style="padding-left: 265px;">
	
	    <form method="post" action="<?php echo RR_HTTPS_HOST ?>/wp-content/plugins/radius-report/credits/e-check/select_size.php">
	    
    	<input type="hidden" name="x_current_user_id" value="<?php echo $current_user_id ?>">
    	<input type="hidden" name="x_current_user_email" value="<?php echo $current_user_email ?>">
    	<input type="hidden" name="x_current_user_street_address" value="<?php echo $current_user_street_address ?>">
    	<input type="hidden" name="x_current_user_zip" value="<?php echo $current_user_zip ?>">
    	<input type="hidden" name="x_current_user_state" value="<?php echo $current_user_state ?>">
    	<input type="hidden" name="x_current_user_city" value="<?php echo $current_user_city ?>">
    	<input type="hidden" name="x_current_user_country" value="<?php echo $current_user_country ?>">

	    	<p class="submit"><input type="submit" class="button-primary" value="Add Reports"  /></p>
	    </form>
	</div>
	
	
	
	<?php
	
}



?>