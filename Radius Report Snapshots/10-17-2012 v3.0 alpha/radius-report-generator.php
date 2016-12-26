<?php


require_once dirname(__FILE__) . '/lib/classes/class.MySQL.php';
require_once dirname(__FILE__) . '/radius-report-config.php';


function radius_report_page() { 
	
	
	$current_user = wp_get_current_user();	// grab the current user object
	
    if ( !($current_user instanceof WP_User) ) {
    	
    	return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See file: " . __FILE__);
    	
    } else {
    	
    	$this_current_user_login = $current_user->user_login;
    	$user_id = $current_user->ID;
    	
		$aUserOptions = get_user_meta( $user_id, "radius_report_user_input", true );	    	
		    	
		$oMySQL = new MySQL(); 
		$oMySQL->Connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS, RR_MYSQL_NAME ); 
		$oMySQL->ExecuteSQL( "SELECT DB_NAME,DB_NAME_HUMAN,DB_NAME_HUMAN_ABBREVIATION,ASTM_SEARCH_RADIUS FROM DATA_TRACKING_TABLE" ); 																						// get an array of results from the table
		$aDB_Names = $oMySQL->ArrayResults();
		
		// check if user inferface should be reset
		$userResetFlag = get_user_meta( $user_id, "radius_report_user_reset", true );
		if ( $userResetFlag != "NO" || $userResetFlag == "YES") {
			error_log( " ----- USER RESET FLAG DETECTED -----  ");
			
			// set flag for 
			$userReset = "RESET";
			
			// turn off user reset flag
			update_user_meta( $user_id, "radius_report_user_reset", "NO" );
		}
		
    }
    
    error_log( " ----- GETTING POST VALUES -----  " );
    //error_log( "_POST VALUES: " . print_r( $_POST, 1) );
    
    
    /** 
	 *	Saves Changes to the page or save & rest settings
	 */

		if ( $_POST["SAVE_SETTINGS"] == "SAVE" && $userReset != "RESET") {
	    		
	    	error_log( " ----- SAVING CHANGES -----  " );
	    	
	    	$aStoreMe = array( 	"radius_report_project_reference" => $_POST["radius_report_project_reference"],
								"radius_report_site_name" => $_POST["radius_report_site_name"],
								"radius_report_site_address" => $_POST["radius_report_site_address"]);
			
			foreach ( $_POST as $sPOST_Index => $sPOST_Value ) {
				
				if ( $sPOST_Index != "SAVE_SETTINGS" || $sPOST_Index != "RESET_ASTM" ) {
				
					$aStoreMe["$sPOST_Index"] = $sPOST_Value;
				}
			}
			update_user_meta( $user_id, "radius_report_user_input", $aStoreMe );	
			
		} else if ( $_POST["RESET_ASTM"] == "RESET" || empty($aUserOptions) || $userReset == "RESET" ) {
			
			error_log( " ----- RESETTING TO ASTM DEFAULTS & SAVING -----  " );
			//error_log( "REST/EMPTY - OPTIONS FROM MySQL DB: " . print_r( $aDB_Names, 1) );
			
			$aStoreMe = array( 	"radius_report_project_reference" => $_POST["radius_report_project_reference"],
								"radius_report_site_name" => $_POST["radius_report_site_name"],
								"radius_report_site_address" => $_POST["radius_report_site_address"]);
			
			foreach ( $aDB_Names as $sDB_Index => $aDB_Array ) {
				$aStoreMe[ $aDB_Array["DB_NAME"] . "_RADIUS" ] = $aDB_Array["ASTM_SEARCH_RADIUS"];
				$aStoreMe[ $aDB_Array["DB_NAME"] . "_CHECKBOX" ] = "on";
				$aStoreMe[ $aDB_Array["DB_NAME"] . "_ABB"  ] = $aDB_Array["DB_NAME_HUMAN_ABBREVIATION"];
			}
			
			//error_log( "DB NAMES ARRAY: " . print_r( $aStoreMe, 1) );
			update_user_meta( $user_id, "radius_report_user_input", $aStoreMe );
			
			// unset the user reset flag flag
			$userReset = "";
			
		}

	
	/** 
	 *	Generates the check boxes and databases
	 */
	
		function radius_report_list_dbs() {
			
			$current_user = wp_get_current_user(); // grab the current user object
			
		    if ( !($current_user instanceof WP_User) ) {
		    	
		    	return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See file:" . __FILE__);
		    	
		    } else {
		    	
		    	$user_id = $current_user->ID;
				$aUserOptions = get_user_meta( $user_id, "radius_report_user_input", true );	    	
		    
		    }
			
		    error_log( " ----- GENERATING DB LIST FROM USER META ----- " );
		    //error_log( "OPTIONS FROM USER META: " . print_r( $aUserOptions, 1) );
		  
			foreach ( $aUserOptions as $sOptionKey => $sOptionValue ) {
				
				if ( preg_match( "/_ABB/m" , $sOptionKey) ) {
					
					$sAbbFieldName = $sOptionKey;
					$sAbbFieldValue = $sOptionValue;									// set the human readable abbreviation of the current database group
					
					$sDB_Name = preg_replace( "/_ABB/m", "", $sOptionKey);				// set the name of this DB grouping
					
					$sRadiusFieldName =	$sDB_Name . "_RADIUS";							// set the HTML tag name of the field
					$sRadiusFieldValue = $aUserOptions[ $sDB_Name . "_RADIUS" ];		// set the radius field value
					
					$sCheckboxName = $sDB_Name . "_CHECKBOX";							// set the HTML tag name of the checkbox
					$sCheckboxValue = $aUserOptions[ $sDB_Name . "_CHECKBOX" ];			// set the checkbox value
					
					if ( $sCheckboxValue == "on" ) { $sCheckboxState = "checked"; } else { $sCheckboxState = ""; }					// translate the checkbox value to the HTML tag state
					
					echo "<b style=' font-size: 20px; color: #E40000; font-weight: bold;'>&raquo;</b>";
					echo "<input type='text' name='$sRadiusFieldName' value='$sRadiusFieldValue' size='5' /> ";						// print out the text fields for the first time and put in the default ASTM_SEARCH_RADIUS
					echo "<input type='checkbox' name='$sCheckboxName' $sCheckboxState /> $sAbbFieldValue <br>";					// print out the corresponding check boxes for each field for the first time and input their 'check' state
					echo "<input type='hidden' name='$sAbbFieldName' value='$sAbbFieldValue'>";
					
					
				} // end if
			
			} // end foreach
			
		} // end function

		
		
	/**
	 * Generates input fields.
	 */
		
		function radius_report_list_fields() {
			
			$current_user = wp_get_current_user(); // grab the current user object
			
		    if ( !($current_user instanceof WP_User) ) {
		    	
		    	return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See file:" . __FILE__);
		    	
		    } else {
		    	
		    	$user_id = $current_user->ID;
				$aUserOptions = get_user_meta( $user_id, "radius_report_user_input", true );	    	
		    }
		    
			error_log( " ----- GENERATING INPUT FIELDS FROM USER META -----  " );
			
			$project_reference = $aUserOptions["radius_report_project_reference"];
			echo "<label><b style=' font-size: 20px; color: #E40000; font-weight: bold;'>&raquo;</b> Project Reference: </label>";
			echo "<input type='text' name='radius_report_project_reference' size='50' value='$project_reference' /><br />";
			
			$site_name = $aUserOptions["radius_report_site_name"];
			echo "<label><b style=' font-size: 20px; color: #E40000; font-weight: bold;'>&raquo;</b> Site Name: </label>";
			echo "<input type='text' name='radius_report_site_name' size='57' value='$site_name' /><br />";
			
			$site_address = $aUserOptions["radius_report_site_address"];
			echo "<label><b style=' font-size: 20px; color: #E40000; font-weight: bold;'>&raquo;</b> Site Address / Coordinates: </label>";
			echo "<input type='text' name='radius_report_site_address' size='41' value='$site_address' />";
			
			
		}	// end function
		
	/**
	 *	List out the hidden fields to be sent to the confirmation page
	 */
		
		function next_button_fields() {
			
			$current_user = wp_get_current_user(); // grab the current user object
			
		    if ( !($current_user instanceof WP_User) ) {
		    	
		    	return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See file:" . __FILE__);
		    	
		    } else {
		    	
		    	$user_id = $current_user->ID;
				$aUserOptions = get_user_meta( $user_id, "radius_report_user_input", true );	    	
		    	
		    }
		    
		    error_log( " ----- GENERATING HIDDEN FIELDS FROM USER META -----  " );
		    
		    foreach ( $aUserOptions as $sOptionKey => $sOptionValue ) {
		    	
		    	if ( $sOptionKey != "SAVE_SETTINGS" ) {
		    		
		    		echo "<input type='hidden' name='$sOptionKey' value='$sOptionValue'>";
		    		
		    	} // end if
		    	
		    	echo "<input type='hidden' name='this_current_user_lat' value=''>";
		    	echo "<input type='hidden' name='this_current_user_lng' value=''>";
		    	
		    	
		    } // end foreach
			
		} // end function
		
		

		
?>


<!-- PAGE CSS -->	

<style type='text/css'> 

	#RadiusReportMap {
		width:100%; 
		height:685px; 
		position:relative; 
		margin:10px 0 10px 0;
		z-index: 1;
	}
	
	#RadiusReportMap .infoWindow {
		line-height:13px; 
		font-size:10px;
	}
	
	#RadiusReportMap input {
		margin:4px 4px 0 0; 
		font-size:10px;
	}
	
	#RadiusReportMap input.text {
		border:solid 1px #ccc; 
		background-color:#fff; 
		padding:2px;
	}
	
	#radius_report_inputs_widget .inside {
		padding:15px 15px 15px 15px;
		line-height:2em;
	}
	
	#radius_report_inputs_widget .inside div {
		margin-left: 175px;
	}
	
	
	#radius_report_database_widget .inside {
		margin:12px 0 10px 0;
	}
	
	#radius_report_database_widget .button-primary {
	    margin-bottom: 17px;
	    margin-left: 0;
	    margin-top: 12px;
	}
	
	#radius_report_database_widget .inside div {
	    margin-bottom: 5px;
	    margin-left: 5px;
    	margin-top: 5px;
	}


</style> 

<!-- FULL PAGE -->	
<div class="wrap">
<div class="icon32" id="icon-edit-pages"><br></div>
<h2>Radius Reports</h2>
<div id="database-widgets-wrap">
<div id="database-widgets" class="metabox-holder">
	
	<!-- LEFT SIDE OF PAGE -->
	<div class='postbox-container' style='width:49%; margin-right: 10px;'>
	
				<!-- GOOGLE MAP PREVIEW -->	
				<div id="radius_report_google_map_widget" class="postbox " >
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class='hndle'><span>Radius Map Preview</span></h3>
				<div class="inside"> 
					<form action="" method="post"> 
						<div id='RadiusReportMap'>
						 
							<?php echo radius_report_printmap(); ?> 
						 
						</div>
					</form>
				</div>
				</div>
	</div>
		
		
	<!-- RIGHT SIDE OF PAGE -->	
	<div class='postbox-container' style='width:49%;'>
		<form name="save-settings" method="post" action="/wp-admin/admin.php?page=products-menu" >

	
				<!-- INPUT FIELDS -->
				<div id="normal-sortables" class="meta-box-sortables">
				<div id="radius_report_inputs_widget" class="postbox" >
				<div class="handlediv" title="Click to toggle"><br></div>
				<h3 class='hndle'><span>Radius Report Input Fields</span></h3>
				<div class="inside">
						<b style="color: #E40000;">Step 1</b> - Enter report criteria, select desired databases and radii <br>
						<b style="color: #FFC95A;">Step 2</b> - <b> Save Settings</b>&#151; the <i>Radius Map Preview</i> will update <br>
						<b style="color: #00E800;">Step 3</b> - <b> Next</b> <hr />
					
						<?php radius_report_list_fields(); ?>
						
						 <div> [ example: "205 Nickerson Street, 98109" ] <br>
							 [ example: "47.64743,-122.35308" ] </div>
						
				</div>
				</div>
				</div>
				
						
				<!-- AVAILABLE DATABASES -->
				<div id="normal-sortables" class="meta-box-sortables">
				<div id="radius_report_database_widget" class="postbox open" >
				<div class="handlediv" title="Click to toggle"><br /></div>
				<h3 class='hndle'><span>Available Databases</span></h3>
				<div class="inside">
					
					
					<!-- RESET BUTTON -->
					<form name="RESET_ASTM" method="post" action="/wp-admin/admin.php?page=products-menu" >
						<button type="submit" class="button-secondary" name="RESET_ASTM" value="RESET" /> Reset ASTM Values </button>
					</form>

					<br><br><?php radius_report_list_dbs(); ?>
				
					<!-- SAVE SETTINGS BUTTON class="button-primary" -->
					<div id="save-button">
						<b style=" position: absolute; font-size: 28px; color: #FFC95A; font-weight: bold;">&raquo;</b>
						<button type="submit" class="button-secondary" name="SAVE_SETTINGS" value="SAVE" style="margin-left: 17px; margin-top: 3px;" /> Save Settings </button>
					</div>
			
					<!-- NEXT BUTTON -->	
					<form action="/wp-content/plugins/radius-report/radius-report-generator-confirmation.php" method="post" >
						
						<!-- EVENRYTHING ON CONFIRMATION PAGE NEEDS TO BE SENT FROM HERE -->
						<?php next_button_fields(); $aUserSite_POST = get_user_meta( $user_id, "radius_report_user_site", true ); ?>
						
						<input type="hidden" name="this_current_user_login" value="<?php echo $this_current_user_login; ?>">
						<input type="hidden" name="this_current_user_lat" value="<?php echo $aUserSite_POST["site_lat"]; ?>">
						<input type="hidden" name="this_current_user_lng" value="<?php echo $aUserSite_POST["site_lng"]; ?>">
						
						
						<div id="next-button">
							<b style="position: absolute; font-size: 28px; color: #00E800; font-weight: bold;">&raquo;</b>	
							<button type="submit" class="button-secondary" name="NEXT" value="NEXT" style="margin-left: 17px; margin-top: 3px;" > Next &raquo; </button>
						</div>
						
					</form>
					
					
				</div>
				</div>
				</div>
		</form>

		
		


	</div>

</div>	<!-- <div class="clear"></div> -->
</div>
</div> <!-- END FULL PAGE -->	

<?php	

} // end main function

?>