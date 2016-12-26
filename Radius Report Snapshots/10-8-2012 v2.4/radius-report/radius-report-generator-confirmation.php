<?php 

require_once dirname(__FILE__) . '/classes/class.MySQL.php';
require_once dirname(__FILE__) . '/radius-report-config.php';

//error_log( "CONFIRMATION PAGE _POST VALUES: " . print_r( $_POST, 1) );

/**
 *	Parses the  $_POST array and prints out relevent values.
 */

	function list_db_fields() {
					
		foreach ( $_POST as $sPOST_Index => $sPOST_Value ) {
			
			if ( preg_match( "/_ABB/m" , $sPOST_Index) ) {
				
				$sAbbFieldValue = $sPOST_Value;										// set the human readable abbreviation of the current database group
				$sDB_Name = preg_replace( "/_ABB/m", "", $sPOST_Index);				// set the name of this DB grouping
				$sRadiusFieldValue = $_POST[ $sDB_Name . "_RADIUS" ];				// set the radius field value

				if ( isset( $_POST[ $sDB_Name . "_CHECKBOX" ] ) ) {
					
					$sCheckboxValue = $_POST[ $sDB_Name . "_CHECKBOX" ];
					
				} else {
					
					$sCheckboxValue = "";
					
				}
				
				if ( $sCheckboxValue == "" || $sRadiusFieldValue == "") {			// translate the checkbox value to the HTML tag state
					
					$sRadiusFieldValue = "0"; 
				
				}		
				
				echo "	<tr>
					    	<td align='right'><strong>$sAbbFieldValue:</strong></td>
					    	<td align='left'>$sRadiusFieldValue</td>
					  	</tr>";
				
			} // end if
		
		} // end foreach					
		
	} // end function


?>


<!doctype html>
<html>
<head>
    <title>Confirmation</title>
    <link rel="stylesheet" href="style.css">
    
    <style type='text/css'> 

 	#box_frame_conf_page {
		border-radius: 15px;
		float: left;
		height: auto;
		width: 500px;
		margin-left: 15%;
		margin-top: 15%;
		padding: 35px;
		color: #333;
		background-color: #FFF; }

	</style>    
    
</head>
<body>
  
  <div id="box_frame_conf_page">

    <h2 align="center">Confirm Report Criteria</h2>
    <hr />

	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	  <tr>
	    <td width="50%" align="right"><strong> Project Reference: </strong></td>
	    <td width="50%" align="left"><?php echo $_POST["radius_report_project_reference"]?></td>
	  </tr>
	  <tr>
	    <td align="right"><strong>Site Name:</strong></td>
	    <td align="left"><?php echo $_POST["radius_report_site_name"]?></td>
	  </tr>
	  <tr>
	    <td align="right"><strong>Site Location:</strong></td>
	    <td align="left"><?php echo $_POST["radius_report_site_address"]?></td>
	  </tr>
	  <tr>
	    <td align="right"><strong>Computed Lat & Lon:</strong></td>
	    <td align="left"><?php echo $_POST["this_current_user_lat"]?>, <?php echo $_POST["this_current_user_lng"]?></td>
	  </tr>
	  <tr>
	    <td colspan="2" align="center"><hr /></td>
	  </tr>
		
		<?php list_db_fields(); ?>

	</table>


	<input type="hidden" name="this_current_user_login" value="<?php echo $_POST["this_current_user_login"]; ?>">

	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	  <tr>
	    <td align="right">
    		<!-- GO BACK BUTTON -->
		    <form method="post" action="/wp-admin/admin.php?page=products-menu">
		      <input type="submit" class="submit" value=" &laquo; Go Back ">
		    </form>
		</td>
	    
	    <td align="left">
	    	<!-- SUBMIT REPORT BUTTON -->	
			<form action="/wp-content/plugins/radius-report/radius-report-generator-engine.php" method="post" onsubmit="return confirm('ATTENTION! \n\nThere is no turning back after you click OK \n\nAre you SURE your REPORT CRITERIA is correct? \n\nIf not click Cancel now')">
				<input type="hidden" name="this_current_user_login" value="<?php echo $_POST['this_current_user_login']; ?>">
				<input type="submit" class="submit" value=" Generate Report &raquo;">
			</form>
		</td>
	  </tr>
	</table>

    
    
    </div>
  </body>
</html>
