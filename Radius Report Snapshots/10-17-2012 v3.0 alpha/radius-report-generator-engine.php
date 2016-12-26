<?php

// Retrieve the hidden form variable from the calling user
$this_current_user_login = $_POST['this_current_user_login'];

if ( isset($this_current_user_login) ){
	
	error_log("------------- STARTING GENERATOR ENGINE! -------------");
	
	require_once( '../../../wp-blog-header.php' );
	require_once('lib/classes/class.MySQL.php');

	// instantiate our MySQL object for the PARCAST_DB
	$oMySQL = new MySQL(); 
	$oMySQL->Connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS, RR_MYSQL_NAME );
	
	// redirect the web browser
	wp_redirect( '/wp-admin/index.php' );

	// call the main function
	report_generator_main( $oMySQL, $this_current_user_login );
	
} // end if


				
/**
 * Main radius report generator function.
 * @param object $oMySQL
 * @param string $this_current_user_login
 */		
function report_generator_main( $oMySQL, $this_current_user_login ) {

	
	/**
	 *	Security check
	 */

		$current_user = wp_get_current_user(); // get the current user id object
					
		if ( !($current_user instanceof WP_User) || ($current_user->user_login != $this_current_user_login)) { exit("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See instance 1 in file:" . __FILE__); }
	
	
	/**
	 *	Define lots of variables
	 */
	
		$execute_time = time();																				// current time in seconds since Unix Epoch
		$user_id = $current_user->ID;																		// current user id number
		$user_options = get_user_meta( $user_id, "radius_report_user_input", true );						// current users options
		$user_max_search_radius = get_user_meta( $user_id, "radius_report_user_max_search_radius", true );	// get max search radius from user meta
		$site_options = get_user_meta( $user_id, "radius_report_user_site", true );
			$lng_site = $site_options[ 'site_lng' ];
			$lat_site = $site_options[ 'site_lat' ];
		
		
		// Generate the directory path for our output files keyed to the current the user name
		$user_directory_path = dirname(__FILE__) . '/generated-reports/' . $this_current_user_login;
		
		// if the directory doesn't exist create a directory for the output files
		if (!file_exists($user_directory_path)) { mkdir( $user_directory_path, 0755 ); }
		if (!file_exists($user_directory_path . "/.tmp")) { mkdir( $user_directory_path . "/.tmp", 0755 ); }
		if (!file_exists($user_directory_path . "/.tmp/.trash")) { mkdir( $user_directory_path . "/.tmp/.trash", 0755 ); }
		if (!file_exists($user_directory_path . "/.tmp/$execute_time")) { mkdir( $user_directory_path . "/.tmp/$execute_time", 0755 ); }
		if (!file_exists($user_directory_path . "/.tmp/.updater")) { mkdir( $user_directory_path . "/.tmp/.updater", 0755 ); }


			// set the base filename of the final report
			$base_filename = $user_options["radius_report_project_reference"] . " " . $user_options["radius_report_site_name"] . " " . date("n-j-Y g_ia");
			$base_filename = preg_replace( "~[^[:alnum:][:space:]()-]~", "", $base_filename );
			
			$local_user_path = dirname(__FILE__) . "/generated-reports/" .$this_current_user_login. "/"; // path to current users main dir
			$local_user_tmp_path = dirname(__FILE__) . "/generated-reports/" .$this_current_user_login. "/.tmp/"; // path to current users .tmp dir
			
			
			// the file to put our node in
			$sNodesFilePath = $local_user_tmp_path . $execute_time . "/";
			
			// the file to put bigArray in
			$bigArray_file_path = $local_user_tmp_path . $execute_time . "/bigArray";
			
			// the name of the minder.php file that will monitor this chain of operations
			$report_minder_php_file_path = $local_user_tmp_path . $execute_time . "/report_minder.php";
			
			// the name of the file that will update the user on the report generation progress
			$report_updater_file = $local_user_path . ".updater";
			 
	
	/**
	 *	Execute functions
	 */

			//myInvoiceMailer();
			
			// run/check master-mind
			masterMinder();
			
			// subtract a credit
			subtractCredit( $user_id, $current_user, $execute_time );
			
			//error_log("generating report... blah blah blah");
			//exit;
			
			// touch the updater file
			touch($local_user_path . ".tmp/.updater/" . $base_filename . " - Querying Databases...");

			
			// move any old files to a .trash folder for later removal
			collectTrash( $local_user_tmp_path );
			
			// remove old files in .trash folder
			dumpTrash( $local_user_tmp_path );
			
			// makes an array of all the relevent data to this report
			$bigArray = makeBigArray( $oMySQL, $user_id );

			
			// add distance, bearing, and compass direciton to each site
			$bigArray = processBigArray( $bigArray );
			
// not messed up here
	
			// take an array an group some sites into logical sub-arrays
			$bigArray = groupBigArray( $bigArray );

// messed up here, must be groupBigArray
		
			// RE-ORDER the array acording to distance
			$bigArray = orderBigArray( $bigArray );

			
			// write a serilized copy of big array to file
			writeBigArray( $bigArray, $bigArray_file_path );

			
			// generate the osm nodes file from our database
			make_osm_nodes( $bigArray, $sNodesFilePath, $user_max_search_radius );

			// set our minder function for this report
			make_minder_fn( $execute_time, $report_minder_php_file_path, $local_user_path, $base_filename, $lng_site, $lat_site, $user_max_search_radius, $this_current_user_login );
		
	
} // end function



/**
 * This is called specially by a series of scheduled events. It takes care of checking the database web_invoice_meta for changes and:
 * 1) creates single scheduled events that send out emails and reminder emails...
 * 2) does the digesting of invoices on the day several are due
 */
function myInvoiceMailer() {

	$iRefTime = microtime(true);
	error_log("------------- myInvoiceMailer STARTED @ t = 0 ms");
	
	$iNow = time();	// the time in seconds, right now!								
	
	// instantiate our MySQL object for the WPDB
	$oMySQL_WPDB = new MySQL();
	$oMySQL_WPDB->Connect(RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS, RR_WEB_INVOICE_DB);
		
	// 1) ARCHIVE - I WANT TO KNOW WHICH INVOICES HAVE BEEN PAID BUT ARE NOT ARCHIVED
		
			$oMySQL_WPDB->ExecuteSQL( "SELECT * FROM `wp_web_invoice_meta` WHERE `meta_key` LIKE 'archive_status'" );
			$aInvoiceMeta_Archived = $oMySQL_WPDB->ArrayResults();
		
			$oMySQL_WPDB->ExecuteSQL( "SELECT * FROM `wp_web_invoice_meta` WHERE `meta_key` LIKE 'paid_status'" );
			$aInvoiceMeta_Paid = $oMySQL_WPDB->ArrayResults();
			
			$DiffPaidArch = array_udiff($aInvoiceMeta_Paid, $aInvoiceMeta_Archived, "compareThese");
			
			foreach ( $DiffPaidArch as $iCountPaid => $aMetaPaid ) {

				web_invoice_archive( $aMetaPaid["invoice_id"] );
				
			} // end foreach
		
		
	// 2) DIGEST - I WANT KNOW WHICH INVOICES, FOR A USER WITH DIGESTING=on, ARE NOT ARCHIVED AND NOT PAID (process multiple single invoices into a single invoice with multiple items)
		
			$oMySQL_WPDB->ExecuteSQL( "SELECT invoice_num AS invoice_id,user_id,invoice_date,amount,description,subject,itemized FROM wp_web_invoice_main" );
			$aInvoiceMain = $oMySQL_WPDB->ArrayResults();
				
			$oMySQL_WPDB->ExecuteSQL( "SELECT * FROM `wp_usermeta` WHERE `meta_key` LIKE '%radius_report_user_digest_enabled%' AND `meta_value` = 'on'" );
			$aWPuserMeta = $oMySQL_WPDB->ArrayResults();
			
			$DiffMainArch = array_udiff( $aInvoiceMain, $aInvoiceMeta_Archived, "compareThese" );
			
			// parse the array for each user
			foreach ( $aWPuserMeta as $iCountMeta => $aMetaWP ) {
				
				$sUserID = $aMetaWP['user_id']; 						// get the user_id of interest
				$invoice_info = array( "user_id" => $sUserID );			// initilize an array for this users invoice info of interest
				$iInvCount = 0;											// initilize a count of the number of invoices for this user
				$invoice_info["amount"] = 0;							// initilize new digest invoice total
				
				// count the number of invoices for the current user_id of interest
				foreach ( $DiffMainArch as $iCountMain => $aMainMeta ) {
					if ( $aMainMeta["user_id"] == $sUserID && strtotime( $aMainMeta["invoice_date"] ) < $iNow ) {
						$iInvCount++;
					} // end if
				} // end foreach
				
				// if the number of invoices for a user is greater than 1 then digest all of that users invoices into a single invoice.
				if ( $iInvCount > 1 ) {
					
						// digest the invoices for the current user of interest
						foreach ( $DiffMainArch as $iCountMain => $aMainMeta ) {
							if ( $aMainMeta["user_id"] == $sUserID && strtotime( $aMainMeta["invoice_date"] ) < $iNow ) {

								// MAKE NEW ARRAY OF ITEMIZED ITEMS
								$aItemizedArray = unserialize( urldecode( $aMainMeta["itemized"] ) );
								foreach ( $aItemizedArray as $iCountItems => $aItem ) { 

									$aItem["name"] = $aItem["name"] . " (" . $aMainMeta["invoice_id"] . ") ";
									$invoice_info["itemized_list"][] = $aItem;
								
								}
								
								// RECALCULATE TOTAL INVOICE AMOUNT
								$invoice_info["amount"] = $invoice_info["amount"] + $aMainMeta["amount"];			

								// ARCHIVE/DELETE OLD INVOICE
								web_invoice_archive( $aMainMeta["invoice_id"] );
								
								// OR
								// web_invoice_delete( $invoice_id );
								
								// CLONE INVOICE DATE AND DUE DATE OF LAST - MOST RECENTLY IN THE PAST INVOICE
								$invoice_info["web_invoice_due_date_month"] = web_invoice_meta( $aMainMeta["invoice_id"], 'web_invoice_due_date_month' );
								$invoice_info["web_invoice_due_date_day"] = web_invoice_meta( $aMainMeta["invoice_id"], 'web_invoice_due_date_day' );
								$invoice_info["web_invoice_due_date_year"] = web_invoice_meta( $aMainMeta["invoice_id"], 'web_invoice_due_date_year' );
								
								$invoice_info["web_invoice_date_month"] = date('m', strtotime( $aMainMeta["invoice_date"] ) );
								$invoice_info["web_invoice_date_day"] = date('d', strtotime( $aMainMeta["invoice_date"] ) );
								$invoice_info["web_invoice_date_year"] = date('Y', strtotime( $aMainMeta["invoice_date"] ) );
								
							} // end if
						} // end foreach
						
						// generate new invoice id
						$invoice_id = $sUserID . "-" . rand(10000000, 90000000);
						
						// new digest invoice description
						$invoice_info["subject"] = "ParCast Invoice Digest of ASTM-05 Radius Reports: ";		// generic product description
						$invoice_info["description"] = "ASTM-05 Radius Reports";								// generic product description
						
						$invoice_info["web_invoice_tax"] = $tax;
						$invoice_info["web_invoice_payment_methods"] = array( "CC", "Check", "Other" );
						$invoice_info["web_invoice_currency_code"] = "USD";
						
						
						// GENERATE NEW "DIGEST" INVOICE 
						web_invoice_generate_auto_invoice($invoice_id, $sUserID, $invoice_info);
					
				} // end if

			} // end foreach
			
			
			
			
	// 3) EMAIL REMINDERS - INTERESTED IN NON-ARCHIVED, NON-PAID & PAST DUE INVOICES

			//error_log( "THESE ARE: NON-ARCHIVED & NON-PAID INVOICES: " . print_r( $DiffMainArch, 1 ) );
			
			// THESE ARE: NON-ARCHIVED & NON-PAID INVOICES
			foreach ( $DiffMainArch as $iCountMain => $aMainMeta ) {
				
				$sDueDateTime = strtotime( web_invoice_meta( $aMainMeta["invoice_id"], 'web_invoice_due_date_month' ) ."/". web_invoice_meta( $aMainMeta["invoice_id"], 'web_invoice_due_date_day' ) ."/". web_invoice_meta( $aMainMeta["invoice_id"], 'web_invoice_due_date_year' ) );
				
				// THESE ARE: NON-ARCHIVED, NON-PAID & PAST DUE INVOICES
				if ( $sDueDateTime < $iNow ) {
					
					//error_log( "THESE ARE: NON-ARCHIVED, NON-PAID INVOICES PAST DUE: " . print_r( $aMainMeta, 1 ) );
					
					$PastDueInvoiceHistory = get_user_meta( $aMainMeta["user_id"], "radius_report_user_past_due_invoice_history", true );
					
					//error_log("PAST DUE INVOICE HISTORY for user: " . $aMainMeta["user_id"] . print_r( $PastDueInvoiceHistory, 1 ) );

						if ( $PastDueInvoiceHistory[ $aMainMeta["invoice_id"] ] != "" ) {
							
							//error_log("NOT FIRST NOTICE FOR THIS INVOICE - WILL CHECK IF LAST REMINDER DATE +30 Days IS IN THE PAST YET");
							
							$iReminderFrequency = 30 * 86400; 																							// Days x Seconds in a Day
							$iCheckReminderDateTime = $PastDueInvoiceHistory[ $aMainMeta["invoice_id"] ] + $iReminderFrequency;							// The next date/time a reminder WILL be sent for this invoice
							
							if ( $iCheckReminderDateTime < $iNow ) {
								
								//error_log("SENDING REMINDER NOW");
								web_invoice_send_email( $aMainMeta["invoice_id"], "reminder" ); 														// send invoice (can also send an array of invoice numbers)
								
								//error_log("UPDATING USER META NOW");
								$PastDueInvoiceHistory[ $aMainMeta["invoice_id"] ] = $iNow;																// this now becomes the "last" time a reminder invoice was sent for this invoice
								update_user_meta( $aMainMeta["user_id"], "radius_report_user_past_due_invoice_history", $PastDueInvoiceHistory);
								
							} else {
								
								//error_log("NOT SENDING REMINDER YET");
								//error_log("NEXT REMINDER WILL BE SENT ON: " . date("m/d/Y" , $iCheckReminderDateTime ) );
								
							} // end if
							
							
						} else {
							
							//error_log("FIRST LATE NOTICE FOR THIS INVOICE - CREATING USER META FOR THIS INVOICE");
							
							$PastDueInvoiceHistory[ $aMainMeta["invoice_id"] ] = $iNow;
							update_user_meta( $aMainMeta["user_id"], "radius_report_user_past_due_invoice_history", $PastDueInvoiceHistory);
							
						} // end if
					
						
				} // end if
				
			} // end foreach
			
			

	// 4) EMAIL - INTERESTED IN 'THE REST': INVOICES THAT ARE NOT SENT, BUT INVOICE DATE IS IN THE PAST
			
			$oMySQL_WPDB->ExecuteSQL( "SELECT * FROM `wp_web_invoice_meta` WHERE `meta_key` LIKE 'sent_date'" );
			$aInvoiceMeta_Sent = $oMySQL_WPDB->ArrayResults();
			
			$oMySQL_WPDB->ExecuteSQL( "SELECT invoice_num AS invoice_id,user_id,invoice_date FROM wp_web_invoice_main" );
			$aInvoiceMain_Rest = $oMySQL_WPDB->ArrayResults();
			
			$DiffRestSent = array_udiff($aInvoiceMain_Rest, $aInvoiceMeta_Sent, "compareThese");
			
			foreach ( $DiffRestSent as $iCountMeta => $aRest ) {
				if ( strtotime( $aRest["invoice_date"] ) < $iNow ) {
					web_invoice_send_email( $aRest["invoice_id"] );	// send invoice (can also send an array of invoice numbers)
				} // end if
			} // end foreach
	
			
	$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
	error_log("------------- myInvoiceMailer ENDED @ $iTimeNow ms");
	
} // end function



/**
 * Used in conjunction with: array_udiff( $aInvoiceMain, $aInvoiceMeta_Archived, "compareThese" ) 
 * @param array $a NOTE: a$ and $b does NOT correspond to first array or second array in calling function!
 * @param array $b
 * @return number
 */
function compareThese($a, $b) {
	
	$aVal = is_array($a) ? $a['invoice_id'] : $a;
	$bVal = is_array($b) ? $b['invoice_id'] : $b;
	
	return strcasecmp($aVal, $bVal);
	
} // end function



/**
 * Starts the psudo-daemon 'radius-report-master-minder.php' and writes PID to /tmp/master-minder.pid
 * the daemon simply prods wordpress to run scheduled "cron" jobs.
 */
function masterMinder() {
	
	// MAKE COMMAND JOB STRING
	if ( file_exists("/Applications/MAMP/bin/php5.3/bin/php") ) { $commandJob = "/Applications/MAMP/bin/php5.3/bin/php -q -f radius-report-master-minder.php"; } 
	else if ( file_exists("/usr/bin/php")) { $commandJob = "/usr/bin/php -q -f radius-report-master-minder.php"; } 
	else { error_log("   MASTER MINDER: Unable to locate PHP executable"); }
	
	
	// PID FILE EXISTS
	if ( file_exists( "/tmp/master-minder.pid" ) ) {
		
			$pid = file_get_contents( "/tmp/master-minder.pid" );
			error_log( "   MASTER MINDER: PID FILE EXISTS: $pid" );
				
			// IF PID is NOT running execute command and write PID to file
			if ( !pidExists( $pid ) ) {
				
				$pid = pidExec( $commandJob );
				error_log( "   MASTER MINDER: PROCESS NOT RUNNING - EXECUTING NEW PROCESS: $pid" );
				
				$fileHandle = fopen( "/tmp/master-minder.pid", "w" );
				fwrite( $fileHandle, $pid);
				fclose( $fileHandle );
				
			} else {
				
				error_log( "   MASTER MINDER: PROCESS ALREADY RUNNING: $pid" );
				
			} // end if
		
	// PID FILE DOES NOT EXIST	
	} else {
		
			$pid = pidExec( $commandJob );
			error_log( "   MASTER MINDER: NO PID FILE - EXECUTING NEW PROCESS: $pid" );
			
			$fileHandle = fopen( "/tmp/master-minder.pid", "w" );
			fwrite( $fileHandle, $pid);
			fclose( $fileHandle );
		
	} // end if
		
} // end function



/**
* Given a command executes the command and return PID, or false if fails.
* @param string $commandJob
* @return number|boolean
*/
function pidExec( $commandJob ) {

	$command = $commandJob.' > /dev/null 2>&1 & echo $!';
	exec( $command , $op );
	$pid = intval( $op[0] );

	if ( $pid != "" ) {
		return $pid;
	}
	return false;

} // end function



/**
 * Given a PID returns true if process is running, or false if not.
 * @param int $pid
 * @return boolean
 */
function pidExists( $pid ) {
	
			try{
				$result = shell_exec(sprintf("ps %d", $pid));
				if( count(preg_split("/\n/", $result)) > 2) { return true; }
			} catch(Exception $e){ }
			return false;
		
} // end function



/**
 * Given a PID, kills it.
 * @param int $pid
 * @return string $output
 */
function pidKill( $pid ) {
	exec("kill -9 $pid", $output);
	return $output;
} // end function




// 5. WRITE array(s) to file(s) using serialize function & write nodes file

/**
 * Writes a serialized copy of array to file location.
 * @param array $bigArray
 * @param string $bigArray_file_path
 */
function writeBigArray( $bigArray, $bigArray_file_path ) {
	
	error_log("WRITING BIGARRAY FILE");
	
	// make file handle, set to overwrite a zero length file
	$handle = fopen( $bigArray_file_path, "w" );
	
	// write to the file a serialized copy of bigArray
	fwrite( $handle, serialize( $bigArray ) );
	
	// close the file
	fclose( $handle );
	    									    								
	
} // end function




// 4. RE-ORDER the array acording to distance

/**
 * Re-orders array by ["DATA"]["DISTANCE"] sub keys
 * @param array $bigArray
 * @return array
 */
function orderBigArray( $bigArray ) {
	
	$dataArray = $bigArray["DATA"];

	usort( $dataArray, 'sortByDistance' );
	
	$bigArray["DATA"] = $dataArray;
	
	return $bigArray;
	
} // end function




// 3. GROUP logical sites in array memory

/**
 * GROUP logical sites in array memory
 * @param array $bigArray Un-Grouped.
 * @return array $bigArray Logically Re-Grouped into sub-sub-arrays.
 */
function groupBigArray( $bigArray ) {
	
	// NOTE: groupDataByKey() takes upto three DB NAMES in an array and one key
	
	/**
	 *	Groups all the UST/LUST sites for Washington State by "FS_ID"
	 */	
	
		$bigArray = groupDataByKey( $bigArray, array( "UST_WA", "LUST_WA" ), "FS_ID" );
		
	/**
	 *	Groups all the CSCSL sites for Washington State by "FacilitySiteId"
	 */	
	
		$bigArray = groupDataByKey( $bigArray, array( "CSCSL_WA" ), "FacilitySiteId" );
		
				
	/**
	 *	[DB_NAME] => CERCLISNFRAP via the [SITE_ID] => 1000551
	 */
		
		//$bigArray = groupDataByKey( $bigArray, array( "CERCLISNFRAP" ), "SITE_ID" ); 
				

	/**
	 *	[DB_NAME] => CERCLIS via the [SITE_ID] => 1000551
	 */
		//error_log( "BIG ARRAY BEFORE GROUP DATA BY KEY: ". print_r($bigArray["DATA"],1));
		//$bigArray = groupDataByKey( $bigArray, array( "CERCLIS" ), "SITE_ID" );
		//error_log( "BIG ARRAY AFTER GROUP DATA BY KEY: ". print_r($bigArray["DATA"],1));
	
	/**
	 *	[DB_NAME] => NPL via the [SITE_ID] => 1000551
	 */
	
		//$bigArray = groupDataByKey( $bigArray, array( "NPL" ), "SITE_ID" );
		
	
	/**
	 * [DB_NAME] => BROWNFIELDS via the [Property_Name] => Doc Freeman Properties  
	 */
		
		$bigArray = groupDataByKey( $bigArray, array( "BROWNFIELDS_WA" ), "FacilitySiteId" );
		
		
	return $bigArray;
	
} // end function




// 2. PROCESS sites, add distance, direction

/**
 * Adds a distance, direction and bearing to every site with a lat and lon.
 * @param array $bigArray
 * @return array $bigArray
 */
function processBigArray( $bigArray ) {
	
	$site_lat = $bigArray["SITE"]["site_lat"];
	$site_lng = $bigArray["SITE"]["site_lng"];


	// check if the sub array is empty (ie no hits in the DB) if it is empty the foreach() will throw a PHP WARNING
	if ( !empty($bigArray["DATA"]) ) {
		
			// sort out distance, direction and bearing
			foreach ( $bigArray["DATA"] as $key => $this_array ) {
			
				$hazmat_site_lat = $this_array["Latitude"];
				$hazmat_site_lng = $this_array["Longitude"];
			
				$this_distance = round( getGreatArcDistance($site_lat, $site_lng, $hazmat_site_lat, $hazmat_site_lng), 4 ); ;
			
				$this_bearing = getRhumbLineBearing($site_lat, $site_lng, $hazmat_site_lat, $hazmat_site_lng);
			
				$this_direction = getCompassDirection( $this_bearing );
			
				$bigArray["DATA"][$key]["DISTANCE"] = $this_distance;
				$bigArray["DATA"][$key]["BEARING"] = $this_bearing;
				$bigArray["DATA"][$key]["DIRECTION"] = $this_direction;
				$bigArray["DATA"][$key]["Latitude"] =  round( floatval( $hazmat_site_lat ), 6);
				$bigArray["DATA"][$key]["Longitude"] = round( floatval( $hazmat_site_lng ), 6);
			
			} // end foreach
		
	} else {
		
		error_log( "NOTICE: No sites located within the defined radius... this may produce PHP warnings" );
		
	} // end if
	
	return $bigArray;
	
} // end function




// 1. PULL all the data from MySQL into a big array within the bounding box

/**
 * Pulls all the possible data for a given report and puts the data into logical sub arrays.
 * @param object $oMySQL
 * @param string $user_id
 * @return array $ARRAY_STACK
 */
function makeBigArray( $oMySQL, $user_id ) {
	
	
	$radius_report_database_connection = mysql_connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS );			// Opens a connection to a MySQL server and sets the reference to: $radius_report_database_connection
	$radius_report_database_selected = mysql_select_db( RR_MYSQL_NAME, $radius_report_database_connection );	// Set the active MySQL database

	$ARRAY_STACK = array(); // initilize scope
	
	/**
	 *	Get user options arrays
	 */
    	
    	// get site options
    	$site_options = get_user_meta( $user_id, "radius_report_user_site", true );
		$lng_site = $site_options[ 'site_lng' ];
		$lat_site = $site_options[ 'site_lat' ];
    	$ARRAY_STACK["SITE"] = $site_options;
    	
		// get user input
		$user_options_input = get_user_meta( $user_id, "radius_report_user_input", true );
		$ARRAY_STACK["INPUT"] = $user_options_input;
	

	/**
	 *	Get Data Tracking Table array
	 */

	    $oMySQL->ExecuteSQL( "SELECT * FROM DATA_TRACKING_TABLE" );		// get results from the data tracking table
	    $aTrackingTable = $oMySQL->ArrayResults();						// put results in an associative array
		$ARRAY_STACK["TABLE"] = $aTrackingTable;
		
	/**
	 *	Get all the data from the ASTM DBs
	 */
				
		// iterate through the user's options
		foreach ( $user_options_input as $user_options_input_key => $user_options_input_value ) {
			
			// if the current iterated key is a 'checkbox' and it is set to 'on' do this 
			if ( preg_match( "/_CHECKBOX/m" , $user_options_input_key) && $user_options_input_value =='on' ) {
				
				// since the current key and value are checked get the corresponding search radius from the users options
				$search_radius = $user_options_input[ preg_replace("/_CHECKBOX/m", "_RADIUS", $user_options_input_key) ];
									
				// define our bounding box so we can query the mysql databases for just the results we want
				$lng_1 = $lng_site - $search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
				$lng_2 = $lng_site + $search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
				$lat_1 = $lat_site - ( $search_radius / 69.2 );
				$lat_2 = $lat_site + ( $search_radius / 69.2 );
				
				// Set the name of the current database being iterated on
				$DB_NAME = preg_replace( "/_CHECKBOX/m", "", $user_options_input_key);

				// iterate through each array in $aTrackingTable
				foreach ( $aTrackingTable as $db_array => $db_array_value ) {

						// only write data if the current array matches the above 
						if ($DB_NAME == $db_array_value[ 'DB_NAME' ]) {
										
								// get the name of the column with the mySQL GEOMETRY information
								$DB_GEOMETRY_COLUMN = $db_array_value[ 'DB_GEOMETRY_COLUMN' ];
								
								// generate a MySQL search query string, to select the whole row for everything in our bounding box
								$BOUNDING_BOX_SEARCH = "SELECT * FROM ".$DB_NAME." WHERE MBRWithin($DB_GEOMETRY_COLUMN,GeomFromText('LineString($lng_1 $lat_1, $lng_2 $lat_2)'))";

								// search and select out the rows within our bounding box
								$BOUNDING_BOX_SEARCH_RESULTS = mysql_query( $BOUNDING_BOX_SEARCH, $radius_report_database_connection );
	
								// Iterates through the MySQL results row by row, creating one Placemark for each
								while ( $row = @mysql_fetch_assoc( $BOUNDING_BOX_SEARCH_RESULTS ) ){
	
									unset( $row["MYSQL_POINT_GEOMETRY"] ); 		// remove MYSQL_POINT_GEOMETRY, because binary will not print_r
									unset( $db_array_value["DB_DESCRIPTION"] ); // remove database description
									unset( $db_array_value["INSTRUCTIONS"] ); 	// remove admin instructions
									
									$ARRAY_STACK["DATA"][] = $db_array_value + $row;
								
								} // end while
							
						} // end inner if
					
				} // end inner foreach
				
			} // end if
			
		} // end outer foreach
			
		mysql_close( $radius_report_database_connection );	// close the open database
		
		return $ARRAY_STACK;
	
} // end function



	
/**
 * Generates the nodes.osm file, which gets injected into the data.osm file, but take care the sql calls can fail silently which can be frusterating for debugging.
 * @param array $bigArray
 * @param string $sNodesFilePath Where to put the file
 */
function make_osm_nodes( $bigArray, $sNodesFilePath, $user_max_search_radius ) {
	
	error_log("WRITING OSM NODES FILE");
	
	$dataArray = $bigArray["DATA"];
	
	$lat_site = $bigArray["SITE"]["site_lat"];
	$lng_site = $bigArray["SITE"]["site_lng"];
	
	$oDoc = new DOMDocument('1.0', 'UTF-8'); 	// create a new DOM (Document Object Model) instance  
	$oDoc -> formatOutput = true;				// put in human readable formatting
	
	$osm = $oDoc -> createElement( 'osm' );
	$osm -> setAttribute( 'version', '0.6' );
	$osm -> setAttribute( 'generator', 'Radius Report v ' . radius_report_version );
	$oDoc -> appendChild( $osm );
	
	/** WRITE THE SITE LOCATION **/
	
		$node = $oDoc -> createElement( 'node' );
		$node -> setAttribute('id', '1');
		$node -> setAttribute('lat', $lat_site );
		$node -> setAttribute('lon', $lng_site );
		$osm -> appendChild( $node );
		
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'site_location' );
			$tag -> setAttribute( 'v', 'site' );
			$node -> appendChild( $tag );
			
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'ref' );
			$tag -> setAttribute( 'v', ' ' );
			$node -> appendChild( $tag );

			
	/** WRITE THE THREE OR FOUR RADII **/

		$node = $oDoc -> createElement( 'node' );
		$node -> setAttribute('id', '2');
		$node -> setAttribute('lat', $lat_site );
		$node -> setAttribute('lon', $lng_site );
		$osm -> appendChild( $node );
			
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'nth_mile_radius' );
			$tag -> setAttribute( 'v', 'fourth_mile' );
			$node -> appendChild( $tag );
				
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'name' );
			$tag -> setAttribute( 'v', '1/4 Mile Radius' );
			$node -> appendChild( $tag );
			
		$node = $oDoc -> createElement( 'node' );
		$node -> setAttribute('id', '3');
		$node -> setAttribute('lat', $lat_site );
		$node -> setAttribute('lon', $lng_site );
		$osm -> appendChild( $node );
				
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'nth_mile_radius' );
			$tag -> setAttribute( 'v', 'half_mile' );
			$node -> appendChild( $tag );
			
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'name' );
			$tag -> setAttribute( 'v', '1/2 Mile Radius' );
			$node -> appendChild( $tag );
			
		$node = $oDoc -> createElement( 'node' );
		$node -> setAttribute('id', '4');
		$node -> setAttribute('lat', $lat_site );
		$node -> setAttribute('lon', $lng_site );
		$osm -> appendChild( $node );
			
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'nth_mile_radius' );
			$tag -> setAttribute( 'v', 'full_mile' );
			$node -> appendChild( $tag );
				
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'name' );
			$tag -> setAttribute( 'v', '1 Mile Radius' );
			$node -> appendChild( $tag );

		/** WRITE MAXRAD RADIUS **/
			
		$node = $oDoc -> createElement( 'node' );
		$node -> setAttribute('id', '5');
		$node -> setAttribute('lat', $lat_site );
		$node -> setAttribute('lon', $lng_site );
		$osm -> appendChild( $node );
				
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'nth_mile_radius' );
			$tag -> setAttribute( 'v', 'maxrad' );
			$node -> appendChild( $tag );
			
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'name' );
			$tag -> setAttribute( 'v', "$user_max_search_radius Mile Radius" );
			$node -> appendChild( $tag );
			
				
	/** WRITE THE HAZMAT SITES **/
		
		//error_log("DATA ARRAY: " . print_r( $dataArray, 1 ) );
		foreach( $dataArray as $siteIndex => $aHazmatSite ) {
						
			$siteIndex++;
			
			// IS ARRAY
			if ( is_array( $aHazmatSite["Latitude"] ) || is_array( $aHazmatSite["Longitude"] ) ){ 		// if $aHazmatSite["Latitude"] is an array then take the first element of the array this: $aHazmatSite["Latitude"][0]
				
				$node = $oDoc -> createElement( 'node' );
				$node -> setAttribute('id', '-' . $siteIndex);
				$node -> setAttribute('lat', $aHazmatSite["Latitude"][0] );
				$node -> setAttribute('lon', $aHazmatSite["Longitude"][0] );
				$osm -> appendChild( $node );
				
				// error_log("aHazmatSite ARRAY: " . print_r( $aHazmatSite["Latitude"][0], 1 ) );
				// error_log("aHazmatSite ARRAY: " . print_r( $aHazmatSite["Longitude"][0], 1 ) );
			
			// NOT ARRAY
			} else {

				$node = $oDoc -> createElement( 'node' );
				$node -> setAttribute('id', '-' . $siteIndex);
				$node -> setAttribute('lat', $aHazmatSite["Latitude"] );
				$node -> setAttribute('lon', $aHazmatSite["Longitude"] );
				$osm -> appendChild( $node );
				
			} // end if
			
			
			// IS ARRAY
			if ( is_array( $aHazmatSite["HAZMAT_CLASS"] ) ) {
				
					if ( $aHazmatSite["HAZMAT_CLASS"][0] == "release" ) {
						
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'hazmat_class' );
						$tag -> setAttribute( 'v', 'release' );
						$node -> appendChild( $tag );
							
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'symbol' );
						$tag -> setAttribute( 'v', '&#x25A9;' );
						$node -> appendChild( $tag );
						
					} else if ( $aHazmatSite["HAZMAT_CLASS"][0] == "use" ) {
						
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'hazmat_class' );
						$tag -> setAttribute( 'v', 'use' );
						$node -> appendChild( $tag );
						
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'symbol' );
						$tag -> setAttribute( 'v', '&#x25BC;' );
						$node -> appendChild( $tag );
						
					} else {
						
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'hazmat_class' );
						$tag -> setAttribute( 'v', 'use-restriction' );
						$node -> appendChild( $tag );
						
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'symbol' );
						$tag -> setAttribute( 'v', '&#x25C9;' );
						$node -> appendChild( $tag );
						
					} // end inner if
			
			// NOT ARRAY
			} else {
				
					if ( $aHazmatSite["HAZMAT_CLASS"] == "release" ) {
					
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'hazmat_class' );
						$tag -> setAttribute( 'v', 'release' );
						$node -> appendChild( $tag );
							
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'symbol' );
						$tag -> setAttribute( 'v', '&#x25A9;' );
						$node -> appendChild( $tag );
					
					} else if ( $aHazmatSite["HAZMAT_CLASS"] == "use" ) {
					
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'hazmat_class' );
						$tag -> setAttribute( 'v', 'use' );
						$node -> appendChild( $tag );
					
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'symbol' );
						$tag -> setAttribute( 'v', '&#x25BC;' );
						$node -> appendChild( $tag );
					
					} else {
					
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'hazmat_class' );
						$tag -> setAttribute( 'v', 'use-restriction' );
						$node -> appendChild( $tag );
					
						$tag = $oDoc -> createElement( 'tag' );
						$tag -> setAttribute( 'k', 'symbol' );
						$tag -> setAttribute( 'v', '&#x25C9;' );
						$node -> appendChild( $tag );
					
					} // end inner if
					
			} // end if

					
			$tag = $oDoc -> createElement( 'tag' );
			$tag -> setAttribute( 'k', 'name' );
			$tag -> setAttribute( 'v', $siteIndex );
			$node -> appendChild( $tag );
					
		} // end foreach
						
				
	/**
     *	Write out the DOM as XML to a file
     */

		$osmOutputXML = $oDoc->saveXML();									// save the doc string as XML
		$osmOutputXML = str_replace( "&amp;", "&", $osmOutputXML );			// XML parsers turns "&" into "&amp;" so we need to convert it back to just "&"
		$file_pointer = fopen( $sNodesFilePath . "nodes.osm", 'w' ); 		// open the file
		fwrite( $file_pointer, $osmOutputXML ); 							// write to the file
		fclose( $file_pointer ); 											// close the file
		//checkCollisionsOSM( $osmOutputXML, .01, $sNodesFilePath, "nodes-detail.osm" );

} // end function



// WORK IN PROGRESS -- This function aims to eliminate overlaping markers on the maps
// this function should probably check tolerances and create 2 or 3 .osm files for detail, overview, and maxrad
function checkCollisionsOSM( $osmOutputXML, $tol, $sNodesFilePath, $sOsmFileName ) {
	
	$tol = .000000001; //TEMP
	
	$iRefTime = microtime(true);										// get the current time in microseconds
	$aXML = simpleXMLToArray( simplexml_load_string($osmOutputXML) ); 	// convert xml to array
	$aNodesXML = $aXML["node"];											// get array of nodes from array
	$nodeTable = array();												// initialize node table
	$nodeIndex = -1;													// only calculate half of the 2D matrix by tracking the index
	
		// generate 2D matrix of distances between all nodes
		foreach( $aNodesXML as $key_outer => $array_outer ) {
			if ( $array_outer['id'] < 0) {

					foreach( $aNodesXML as $key_inner => $array_inner ) {
						if ( $array_inner['id'] < 0 && $array_inner['id'] > $nodeIndex ) { 
							
							//$nodeTable[$array_outer['id']][$array_inner['id']] = getGreatArcDistance( $array_outer['lat'], $array_outer['lon'], $array_inner['lat'], $array_inner['lon'] );
							$dist = getGreatArcDistance( $array_outer['lat'], $array_outer['lon'], $array_inner['lat'], $array_inner['lon'] );
							
							
							// if the distance between any two nodes is less than $tolerance (.01 miles is 52 feet ) then calculate the destination point of where the textsymbolizer should go
							if ( $dist < $tol ) {

								$bearing = getRhumbLineBearing( $array_outer['lat'], $array_outer['lon'], $array_inner['lat'], $array_inner['lon'] );
								
								$aDestPoint = getDestPoint( $array_outer['lat'], $array_outer['lon'], $bearing, .05 );
								
								error_log("DISTANCE: $dist      NODE ID1: $key_outer      NODE ID2: $key_inner ");
								error_log("destination POINT: "  . print_r($aDestPoint, 1) );
								
							} // end if
							
						
						
						} // end inner if
					} // end inner foreach
				
			$nodeIndex--;
			} // end outer if
		} // end outer foreach

		//error_log("node table: "  . print_r($nodeTable, 1) );
		
		
	//$aDestPoint = getDestPoint( 47.648462, -122.37707, 45, 124.54 );
	//error_log("DEST POINT: "  . $aDestPoint["0"] . ", " . $aDestPoint["1"]);
	
	
	//error_log("NODES XML: " . print_r($aNodesXML, 1) );
	//error_log("ID: "  . print_r($array['id'], 1) );
	//error_log("LAT: " . print_r($array['lat'], 1) );
	//error_log("LON: " . print_r($array['lon'], 1) );
			
	
	//$osmOutputXML = str_replace( "&amp;", "&", $osmOutputXML );			// XML parsers turns "&" into "&amp;" so we need to convert it back to just "&"
	//$file_pointer = fopen( $sNodesFilePath . $sOsmFileName, 'w' ); 		// open the file
	//fwrite( $file_pointer, $osmOutputXML ); 							// write to the file
	//fclose( $file_pointer ); 											// close the file
	//error_log("OSM XML: " . $osmOutputXML  );
	
	$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
	error_log("FUNCTION TOOK @ $iTimeNow ms");
	
} // end function



/**
 * Writes and executes a unique and separate minder php process that 'minds' each report submitted by each user to its completion or failure
 * @param int $execute_time
 * @param string $report_minder_php_file_path
 * @param string $local_user_path
 * @param string $base_filename
 * @param float $lng_site
 * @param float $lat_site
 * @param float $user_max_search_radius
 */
function make_minder_fn( $execute_time, $report_minder_php_file_path, $local_user_path, $base_filename, $lng_site, $lat_site, $user_max_search_radius, $this_current_user_login ) {

	error_log("MAKING MINDER FUNCTION");
	
	$minder_php_file_pointer = fopen( $report_minder_php_file_path, "a" );			// create a file pointer that points to our unique minder file
	$minder_php_file_source = file_get_contents('./radius-report-minder.php');		// get file contents	
	fwrite( $minder_php_file_pointer, $minder_php_file_source);						// write out our unique child minder file
	fwrite( $minder_php_file_pointer, "report_minder( '$execute_time', '$local_user_path', '$base_filename', '$lng_site', '$lat_site', '$user_max_search_radius', '$this_current_user_login' ); ?> ");
	fclose( $minder_php_file_pointer );
		
	error_log("   MINDER: Wrote the report_minder php file: report_minder.php");	
			
	// the command and file we want to run as a separate php process
	if ( file_exists("/Applications/MAMP/bin/php5.3/bin/php") ){ $minder_exec_command = "/Applications/MAMP/bin/php5.3/bin/php -q -f $report_minder_php_file_path"; } 
	else if ( file_exists("/usr/bin/php")) { $minder_exec_command = "/usr/bin/php -q -f $report_minder_php_file_path"; } 
	else { error_log("   MINDER: Radius Report Generator Engine not able to locate PHP executable"); }
					
	exec(sprintf("%s > %s 2>&1 & echo $!", $minder_exec_command, $report_minder_php_file_path . ".out"));	
	error_log( "   MINDER: Executed the report_minder php file and wrote output to: " .$execute_time. "/report_minder.php.out" );

} // end function			



/**
 * Subtracts a credit from the users balance sheet.
 * @param string $user_id
 * @param object $current_user
 * @param int $execute_time
 */
function subtractCredit( $user_id, $current_user, $execute_time ) {
		
	$purchased_credit_balance = get_user_meta( $user_id, "radius_report_user_credit_balance", true );
	$credit_line_remaining = get_user_meta( $user_id, "radius_report_user_credit_line_remaining", true );
	
	if ( $purchased_credit_balance > 0 ){
		
		error_log("SUBTRACT CREDIT: Credit balance is > 0, generating a report, subtracting 1 credit from balance: " . $purchased_credit_balance );
		
		$new_credit_balance = $purchased_credit_balance - 1;
		
		update_user_meta( $user_id, "radius_report_user_credit_balance", $new_credit_balance );
		
		
	} else if ( $credit_line_remaining > 0 ) {
		
		error_log("SUBTRACT CREDIT: Remaining credit line is > 0, generating a report, subtracting 1 credit remaining credit line: " . $credit_line_remaining );
		
		$new_credit_line_remaining = $credit_line_remaining - 1;
		
		update_user_meta( $user_id, "radius_report_user_credit_line_remaining", $new_credit_line_remaining );

		// generate an invoice
		makeInvoice( $current_user, $execute_time ); 	
		
		
	} else {	
		
		error_log("SUBTRACT CREDIT: Not enough Credits for user: $user_id  |  Current credit balance: $purchased_credit_balance | Current credit line remaining:  $credit_line_remaining" );
		wp_redirect( '/wp-admin/admin.php?page=credits-menu' );
		exit;
	}
	
	
} // end function



/**
 * Generates invoices with appropriate dues dates for auto-invoicing and digesting through the web-invoice functions - contains several hardcoded settings.
 * @param object $current_user
 * @param int $execute_time
 */
function makeInvoice( $current_user, $execute_time ) {
	
	// current user id
	$user_id = $current_user->ID;
	
	// Invoice Info Array
	$invoice_info = array();
	
	// invoicing variables
	$sInvoiceEnabled 			= $current_user->radius_report_user_invoice_enabled;				// invoicing "on" if enabled
	error_log( " INVOICING IS:  $sInvoiceEnabled" ); 
	$sInvoiceApprovalDate 		= $current_user->radius_report_user_date_approved;					// approval date
	$sInvoiceTermsInDays 		= $current_user->radius_report_user_terms; 							// invoice terms, in days

	// digest invoicing variables
	$sInvoiceDigestEnabled 		= $current_user->radius_report_user_digest_enabled;					// digest "on" if enabled
	$sInvoiceDigestStartDate 	= $current_user->radius_report_user_digest_period_start_date;		// digest start date
	$sInvoiceDigestDayOfMonth 	= $current_user->radius_report_user_day_of_month; 					// day of the month
	$sInvoiceDigestPeriod 		= $current_user->radius_report_user_digest_period; 					// digest period, in days
	
	// new invoice id
	$invoice_id = $user_id . "-" . rand(10000000, 90000000);
	
	$radius_report_project_reference 	= $current_user->radius_report_user_input["radius_report_project_reference"];
	$radius_report_site_name 			= $current_user->radius_report_user_input["radius_report_site_name"];
	$radius_report_site_address 		= $current_user->radius_report_user_input["radius_report_site_address"];
	
	$subject = "ParCast Invoice for ASTM-05 Radius Report: $radius_report_project_reference";	// generic product description
	$main_description = "ASTM-05 Radius Report";							// generic product description
	
	$tax = 0.0;																// tax if any
	$price = 99.00;															// the unit price
	$quantity = 1;															// number of units
	$amount = $price * $quantity + $price * $quantity * $tax;				// total price with tax 		
	$name = "ASTM-05 Radius Report";										// unit name
	$description = " Reference: " . $radius_report_project_reference . ", " . $radius_report_site_name . ", " . $radius_report_site_address ;		// unit description will need some more data
	
	//error_log( "DESC: " . $description);
	//error_log( "CURRENT USER: " . print_r($current_user , 1) );
	
	
	// common invoice fields
	//$invoice_info["this_current_user_login"] = $current_user->user_login;
	$invoice_info["user_id"] = $user_id;
	$invoice_info["amount"] = $amount;
	$invoice_info["subject"] = $subject;
	$invoice_info["description"] = $subject;
	$invoice_info["itemized_list"][] = array( "name" => $name, "description" => $description, "quantity" => $quantity, "price" => $price );
	$invoice_info["web_invoice_tax"] = $tax;
	$invoice_info["web_invoice_payment_methods"] = array( "CC", "Check", "Other" );
	$invoice_info["web_invoice_currency_code"] = "USD";
	
	// due date & digest date
	$sInvoiceDigestDate = "";
	$sInvoiceDueDate = "";
	
	
	// AUTO-INVOICING ON
	if ( $sInvoiceEnabled == "on" ) {
		
		error_log( "AUTO-INVOICING: ON" );

		// DIGEST AUTO-INVOICING ON
		if ( $sInvoiceDigestEnabled == "on" ) { 
			
				error_log( "AUTO-INVOICING: DIGEST ON" );
			
				if ( $sInvoiceDigestStartDate != "" ) {	// check if digest start date is empty
					
						if ( strtotime( $sInvoiceDigestStartDate ) < $execute_time ) {	// check if the start date is in the past
							
								if 	( $sInvoiceDigestDayOfMonth != "" ) {	// check if digest day of month IS set

											$sInvoiceDigestDateTime = strtotime( date('m', $execute_time) ."/". $sInvoiceDigestDayOfMonth ."/". date('Y', $execute_time) );
									
											if ( $sInvoiceDigestDateTime < $execute_time ) { // digest day of current month has passes then make the digest invoice date for the same day in one month
												
												$sInvoiceDigestDateTime = strtotime( " +1 month", $sInvoiceDigestDateTime );
												
											} // end if
											
											$sInvoiceDigestDate = date( 'm/d/Y',  $sInvoiceDigestDateTime );
											$sInvoiceDueDate = date( 'm/d/Y',  strtotime( " +$sInvoiceTermsInDays days", $sInvoiceDigestDateTime ) );
											
								} else if ( $sInvoiceDigestPeriod != "" ) {	// if day of month is NOT set, check if digest PERIOD is set
									
											$sInvoiceDigestDateTime = strtotime( $sInvoiceDigestStartDate );
									
											while ( $sInvoiceDigestDateTime < $execute_time ) {	// while the digest date is in the past update it by one period untile it is in the future
												
												$sInvoiceDigestDateTime = strtotime( " +$sInvoiceDigestPeriod days", $sInvoiceDigestDateTime );
												
											} // end while
									
											$sInvoiceDigestDate = date( 'm/d/Y',  $sInvoiceDigestDateTime );
											$sInvoiceDueDate = date( 'm/d/Y',  strtotime( " +$sInvoiceTermsInDays days", $sInvoiceDigestDateTime ) );

								} else {
									error_log( "AUTO-INVOICING: ERROR: Neither digest day of month or digest period are set or valid: $sInvoiceDigestDayOfMonth and $sInvoiceDigestPeriod ... can not create invoice for user: $user_id." );
								} // end if
							
						} else {
							error_log( "AUTO-INVOICING: ERROR: Digest Start Date in the future: $sInvoiceDigestStartDate not reached yet ... can not create invoice for user: $user_id." );
						} // end inner if

				} else {
					error_log( "AUTO-INVOICING: ERROR: radius_report_user_digest_period_start_date not set ... can not create invoice for user: $user_id." );
				}
				
				// invoice digest due date will be = start date + digest period days + terms -> past the start date
				// invoice date will be = start date + digest period days
				
				error_log( "AUTO-INVOICING: DIGEST DATE: $sInvoiceDigestDate" );
				error_log( "AUTO-INVOICING: DUE DATE: $sInvoiceDueDate" );
				
				$invoice_info["web_invoice_date_month"] = date( 'm', strtotime($sInvoiceDigestDate));
				$invoice_info["web_invoice_date_day"] = date( 'd', strtotime($sInvoiceDigestDate));
				$invoice_info["web_invoice_date_year"] = date( 'Y', strtotime($sInvoiceDigestDate));
				
				$invoice_info["web_invoice_due_date_month"] = date( 'm', strtotime($sInvoiceDueDate));
				$invoice_info["web_invoice_due_date_day"] = date( 'd', strtotime($sInvoiceDueDate));
				$invoice_info["web_invoice_due_date_year"] = date( 'Y', strtotime($sInvoiceDueDate));
				
				// GENERATE INVOICE
				web_invoice_generate_auto_invoice($invoice_id, $user_id, $invoice_info); 
				
		// DIGEST AUTO-INVOICING OFF
		} else { 
			
			// invoice due date will be = current date + terms -> past the start date
			// invoice date will be = current date
			
			$sInvoiceDueDate = date( 'm/d/Y',  strtotime( " +$sInvoiceTermsInDays days", $execute_time ) );
			
			error_log( "AUTO-INVOICING: DIGEST OFF" );
			error_log( "AUTO-INVOICING: DUE DATE: $sInvoiceDueDate" );
			
			$invoice_info["web_invoice_date_month"] = date( 'm', $execute_time );
			$invoice_info["web_invoice_date_day"] = date( 'd', $execute_time );
			$invoice_info["web_invoice_date_year"] = date( 'Y', $execute_time );
			
			$invoice_info["web_invoice_due_date_month"] = date( 'm', strtotime($sInvoiceDueDate));
			$invoice_info["web_invoice_due_date_day"] = date( 'd', strtotime($sInvoiceDueDate));
			$invoice_info["web_invoice_due_date_year"] = date( 'Y', strtotime($sInvoiceDueDate));
			
			// GENERATE INVOICE
			web_invoice_generate_auto_invoice( $invoice_id, $user_id, $invoice_info );
			
		} // end if - DIGEST ON/OFF 
		
		
	// AUTO-INVOICING OFF
	} else {
		
		error_log( "AUTO-INVOICING: OFF" );
		
	} // end if - AUTO-INVOICING ON/OFF

} // end function 



/**
 * Given a Point (lat, lon), Bearing, and Distance, returns the destiantion point (lat, lon). 
 * @param float $lat1 Latitude in degrees (i.e. 47.648462).
 * @param float $lon1 Longitude in degress (i.e. -122.37707).
 * @param float $theta Bearing in degrees (i.e. 0 to 360, where North is 0).
 * @param float $dist Distance in miles.
 * @return array Destination Point ($lat, $lon).
 */
function getDestPoint( $lat1, $lon1, $theta, $dist ) {
	
	$R = 3959.8728; 				// earth radius in miles
	$dist = $dist/$R;				// convert to earth radians
	$PI = pi();						// get the value of PI
	$theta =  $theta * $PI/180;		// convert bearing in degrees to radians
	$lat1 = $lat1 * $PI/180;		// convert lat in degrees (i.e. 47.648462) to radians
	$lon1 = $lon1 * $PI/180;		// convert lon in degrees (i.e. -122.37707) to radians 
	
	$lat2 = asin(sin( $lat1 )*cos( $dist ) + cos( $lat1 )*sin( $dist )*cos( $theta ));									// calculate destination latitude
	$lon2 = $lon1 + atan2( sin( $theta ) * sin( $dist ) * cos( $lat1 ), cos( $dist ) - sin( $lat1 ) * sin( $lat2 ) );	// calculate destination longitude
	
	$lat2 = $lat2 * 180/$PI;		// convert lat in radians to degrees
	$lon2 = $lon2 * 180/$PI;		// convert lon in radians to degrees
	
	$lat2 = round( $lat2, 6);		// round to nearest half inch
	$lon2 = round( $lon2, 6);		// round to nearest half inch

	return array( $lat2, $lon2 );

} // end function



/**
 * Calculates the bearing (in degrees) of a rhumb line from two points.
 * @param float $lat1
 * @param float $lon1
 * @param float $lat2
 * @param float $lon2
 */
function getRhumbLineBearing( $lat1, $lon1, $lat2, $lon2 ) {
     //difference in longitudinal coordinates
     $dLon = deg2rad($lon2) - deg2rad($lon1);
 
     //difference in the phi of latitudinal coordinates
     $dPhi = log(tan(deg2rad($lat2) / 2 + pi() / 4) / tan(deg2rad($lat1) / 2 + pi() / 4));
 
     //we need to recalculate $dLon if it is greater than pi
     if(abs($dLon) > pi()) {
          if($dLon > 0) {
               $dLon = (2 * pi() - $dLon) * -1;
          }
          else {
               $dLon = 2 * pi() + $dLon;
          }
     }
     //return the angle, normalized
     return (rad2deg(atan2($dLon, $dPhi)) + 360) % 360;
}



/**
 * Calculates the compass direction (ie NNE, etc) from a bearing in degrees.
 */
function getCompassDirection( $bearing ) {
     $tmp = round($bearing / 22.5);
     switch($tmp) {
          case 1:
               $direction = "NNE";
               break;
          case 2:
               $direction = "NE";
               break;
          case 3:
               $direction = "ENE";
               break;
          case 4:
               $direction = "E";
               break;
          case 5:
               $direction = "ESE";
               break;
          case 6:
               $direction = "SE";
               break;
          case 7:
               $direction = "SSE";
               break;
          case 8:
               $direction = "S";
               break;
          case 9:
               $direction = "SSW";
               break;
          case 10:
               $direction = "SW";
               break;
          case 11:
               $direction = "WSW";
               break;
          case 12:
               $direction = "W";
               break;
          case 13:
               $direction = "WNW";
               break;
          case 14:
               $direction = "NW";
               break;
          case 15:
               $direction = "NNW";
               break;
          default:
               $direction = "N";
     }
     return $direction;
}



/**
 * Calculates the distance (in miles) between two points for latitude and longitude.
 * @param float $lat1
 * @param float $lon1
 * @param float $lat2
 * @param float $lon2
 */
function getGreatArcDistance( $lat1, $lon1, $lat2, $lon2 ) {
	
	// make sure the input is numeric
	$lat1 = floatval($lat1); $lon1 = floatval($lon1); $lat2 = floatval($lat2); $lon2 = floatval($lon2);
	
	// Mean Earth radius in miles 3963.1676 -> yields dist in miles
	// Mean Earth radius in kilometers 6371 -> yields dist in km
	// Mean Earth radius in meters 6371000 -> yields dist in m
	$dist = 3963.1676 * acos( cos( deg2rad( $lon1 ) - deg2rad( $lon2 ) ) * cos( deg2rad( $lat1 ) ) * cos( deg2rad( $lat2 ) ) + sin( deg2rad( $lat1 ) ) * sin( deg2rad( $lat2 ) ) );
	

	// if ( $dist < .0001 ) { // .0001 miles = 6.336 inches		
	// 	$dist = 0; 
	// } else {
	// 	$dist = round( $dist, 8); // .0000099 miles = 0.627264 inches
	// }

	return $dist;
	
} // end function



/**
 * Moves files older than 24 hours to a folder called (dot)trash in the direcotry string passed to it.
 * @param string $local_user_tmp_path
 */
function collectTrash( $local_user_tmp_path ) {
	
	$items = 0;
	
	// create a handle (pointer) for the directory
	$handle = opendir( $local_user_tmp_path );

		// scan the files in the directory and if they are older than 86400 second ( 24 hours) move them to .trash
	    while ( false !== ($file = readdir($handle)) ){
	    							
	        if ( $file != "." && $file != ".." && $file != ".updater" && $file != ".trash" ){
	        	
	        	if ( ( time() - filemtime( $local_user_tmp_path . "/" . $file ) ) > 86400 ) {
	        		
	        		//error_log( "Moving file to .trash: " . $file);
	        		rename($local_user_tmp_path . "/" . $file, $local_user_tmp_path . "/.trash/" . $file);
	        		
					$items++;	        		
	        	}
	        }
	    } // end while
    
    closedir($handle);
	
    error_log("COLLECTING TRASH: $items items");
	
} // end function



/**
 * Removes files older than 2 weeks from the direcotry string passed to it.
 * @param string $local_user_tmp_path
 */
function dumpTrash( $local_user_tmp_path ) {

	function deleteDirectory( $sDir ) {
	
		if ( !empty( $sDir ) ) {
			system( 'rm -rf ' . escapeshellarg( $sDir ), $sReturnVal );		// Run UNIX comand
			return $sReturnVal;												// UNIX commands return zero on success
		}
	
	} // end deleteDirectory function
	
	
	$iTrashPieces = 0;										// set the number of trash items	
	$sTrashPath = $local_user_tmp_path . ".trash";			// set the path to the .trash dir
	$pHandle = opendir( $sTrashPath );						// create a handle (pointer) for the directory
	
	// scan the files in the directory and if they are older than 14 days i.e. 86400 (seconds in 24 hours) * 14 (days) = (86400 * 7)
	while ( false !== ( $sFile = readdir( $pHandle ) ) ){

		if ( $sFile != "." && $sFile != ".." ){

			if ( ( time() - filemtime( $sTrashPath . "/" . $sFile ) ) > (86400 * 7) ) {
				 
				$sReturn = deleteDirectory( $sTrashPath . "/" .$sFile );
				$iTrashPieces++;
			}
		}
	} // end while

	closedir( $pHandle );

	error_log("DUMPING TRASH: $iTrashPieces items");
	
} // end function



/**
 * Compaison function for usort.
 * @param mixed $a
 * @param mixed $b
 * @return mixed 
 */
function sortByDistance($a, $b) {

	$aDist = $a["DISTANCE"];
	$bDist = $b["DISTANCE"];
	
	if ( is_array($aDist) && is_array($bDist) ) {				// if $aDist and $bDist are both arrays
		
		if ( $aDist[0] == $bDist[0] ) { return 0; }				// compare the first elements in each array
	    
		return ($aDist[0] < $bDist[0]) ? -1 : 1;
	
	} else if ( !is_array($aDist) && is_array($bDist) ) {		// if $aDist is an array but $bDist is not
		
		if ( $aDist == $bDist[0] ) { return 0; }				// compare the first elements in in $bDist to the value of $aDist
	    
	    return ($aDist < $bDist[0]) ? -1 : 1;
		
	} else if ( is_array($aDist) && !is_array($bDist) ) {		// if $bDist is an array but $aDist is not
		
		if ( $aDist[0] == $bDist ) { return 0; }				// compare the first elements in in $aDist to the value of $bDist

	    return ($aDist[0] < $bDist) ? -1 : 1;
				
	} else if ( !is_array($aDist) && !is_array($bDist) ) {		// if neither $aDist or $bDist is an array
		
		if ( $aDist == $bDist ) { return 0; }					// compare the value of $aDist to $bDist
				    
	    return ($aDist < $bDist) ? -1 : 1;

	}

} // end function



/**
 * Sorts $bigArray according to a shared key in an array and some black magic.
 * @param array $bigArray
 * @param array $DB_NAME_ARRAY
 * @param string $DB_KEY
 */
function groupDataByKey( $bigArray, $DB_NAME_ARRAY, $DB_KEY ) {
			
		$groupStack = array();
		$nonGroupStack = array();
		
		$EPAIDStack = array();
		$reEPAIDStack = array();
		
		// check if the sub array is empty (ie no hits in the DB) if it is empty the foreach() will throw a PHP WARNING
		if ( !empty($bigArray["DATA"]) ) {
		
			// fish for sites and put them in some stacks
			foreach ( $bigArray["DATA"] as $key => $this_array ) {
				
				// check the current site/array against the parameter passed to this function 
				if ( $this_array["DB_NAME"] == "$DB_NAME_ARRAY[0]" || $this_array["DB_NAME"] == "$DB_NAME_ARRAY[1]" || $this_array["DB_NAME"] == "$DB_NAME_ARRAY[2]") {
					
					$EPAIDStack[] = $this_array["$DB_KEY"]; 			// then put the "EPA_ID" number on a new stack
					$groupStack[] = $this_array;						// then copy the sub-array to a new stack
					unset( $bigArray["DATA"][$key] );					// then remove the sub-array from the $bigArray["DATA"] stack
					
				} else {
					
					$nonGroupStack[] = $this_array;	
				}
			}
		}	
		
		// reorder the EPAID stack according to the EPAID, but retaining the group stack ID
		foreach ( $EPAIDStack as $groupStackID => $EPAID ) { $reEPAIDStack["$EPAID"][] = $groupStackID; }
		
		// now cycle through the reordered FSID stack...
		foreach ( $reEPAIDStack as $EPAID => $gStackID_array ) {
			
			$index = count( $gStackID_array );
			
			if ( $index > 1 ) {											// if there is more than one element in the array
				
				for ($i = 0; $i < $index; $i++) { 						// then cycle thought the array
					
					if ( isset( $gStackID_array[$i + 1]) ) { 			// if there is a next element
						
						$gStackID_Base = $gStackID_array[0]; 			// set the base array always to the first one
						$gStackID_Next =  $gStackID_array[$i + 1]; 		// set the next array always to the next one
						
						$groupStack[$gStackID_Base] = array_merge_recursive( $groupStack[$gStackID_Base], $groupStack[$gStackID_Next] ); // merge the two arrays recursively
						
						unset( $groupStack[$gStackID_Next] ); 			// unset the array we just merged so it does not get counted again	
					}	
				}	
			}
		} // end foreach

		$bigArray["DATA"] = array_merge($groupStack, $nonGroupStack);
	
	return $bigArray;
	
} // end function



/**
 * Converts a simpleXML element into an array. Preserves attributes.<br/>
 * You can choose to get your elements either flattened, or stored in a custom
 * index that you define.
 * @param simpleXMLElement    $xml            the XML to convert
 * @param boolean|string    $attributesKey    if you pass TRUE, all values will be stored under an '@attributes' index. Note that you can also pass a string to change the default index.<br/> defaults to null.                                          
 * @param boolean|string    $childrenKey    if you pass TRUE, all values will be stored under an '@children' index. Note that you can also pass a string to change the default index.<br/> defaults to null.                                     
 * @param boolean|string    $valueKey        if you pass TRUE, all values will be stored under an '@values' index. Note that you can also pass a string to change the default index. <br/> defaults to null.                                         
 * @return array the resulting array.
 */
function simpleXMLToArray(SimpleXMLElement $xml,$attributesKey=null,$childrenKey=null,$valueKey=null){

	if($childrenKey && !is_string($childrenKey)){
		$childrenKey = '@children';
	}
	if($attributesKey && !is_string($attributesKey)){
		$attributesKey = '@attributes';
	}
	if($valueKey && !is_string($valueKey)){
		$valueKey = '@values';
	}

	$return = array();
	$name = $xml->getName();
	$_value = trim((string)$xml);
	if(!strlen($_value)){
		$_value = null;
	};

	if($_value!==null){
		if($valueKey){
			$return[$valueKey] = $_value;
		}
		else{$return = $_value;
		}
	}

	$children = array();
	$first = true;
	foreach($xml->children() as $elementName => $child){
		$value = simpleXMLToArray($child,$attributesKey, $childrenKey,$valueKey);
		if(isset($children[$elementName])){
			if(is_array($children[$elementName])){
				if($first){
					$temp = $children[$elementName];
					unset($children[$elementName]);
					$children[$elementName][] = $temp;
					$first=false;
				}
				$children[$elementName][] = $value;
			}else{
				$children[$elementName] = array($children[$elementName],$value);
			}
		}
		else{
			$children[$elementName] = $value;
		}
	}
	if($children){
		if($childrenKey){
			$return[$childrenKey] = $children;
		}
		else{$return = array_merge($return,$children);
		}
	}

	$attributes = array();
	foreach($xml->attributes() as $name=>$value){
		$attributes[$name] = trim($value);
	}
	if($attributes){
		if($attributesKey){
			$return[$attributesKey] = $attributes;
		}
		else{$return = array_merge($return, $attributes);
		}
	}

	return $return;
}


?>