<?php

/**
 * EXAMPLE USAGE: /Applications/MAMP/bin/php5.3/bin/php EPA_API_feed_main.php
 * 
 * build and update a big table from the EPA API RCRA data tables
 *
 **/

require_once 'class.MySQL.php';

define( "EPA_API_MYSQL_HOST", "localhost:8889" );
define( "EPA_API_MYSQL_USER", "root" );
define( "EPA_API_MYSQL_PASS", "root" );
define( "EPA_API_MYSQL_DB_NAME", "PARCAST_DB" );


// Open a connection to the MySQL server
$database_connection = mysql_connect( EPA_API_MYSQL_HOST, EPA_API_MYSQL_USER, EPA_API_MYSQL_PASS );
			
// Set the active MySQL database
$database_selected = mysql_select_db( EPA_API_MYSQL_DB_NAME, $database_connection );

// instantiate the MySQL object from: class.MySQL.php
$oMySQL = new MySQL();


// call the main function
RCRA_bigtable( $oMySQL );
	
	
// main function
function RCRA_bigtable( $oMySQL ) {
	
	// what to name the new local RCRA table
	$MASTER_TABLE_NAME = "RCRAINFO_MASTER";
	
	// the main column in the new RCRA table to use as the table index
	$TABLE_INDEX = "HANDLER_ID";
	
	// the root URL used to access the EPA's API
	$API_ROOT_STRING = "http://iaspub.epa.gov/enviro/efservice"; 
	
	$ROWS = 999; // total number of rows to process at a time, total rows = n+1, max n = 999
	
	$STATE = "WA"; // which state to populate the database with 
	
	/**
	 *  Aggregate a big prototype table called $MASTER_TABLE_NAME, using $TABLE_INDEX as the index
	 */
	
			//make_prototype_table( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "", $API_ROOT_STRING . "/RCR_HREPORT_UNIV/LOCATION_STATE/WA/rows/1:1" );
			//make_prototype_table( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "RCR_CA_EVENT_", $API_ROOT_STRING . "/RCR_CA_EVENT/HANDLER_ID/WAD058362336/rows/1:1" );
			//make_prototype_table( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "RCR_CMECOMP3_", $API_ROOT_STRING . "/RCR_CMECOMP3/HANDLER_ID/WAD982652208/rows/1:1" );
						
	/**
	 *	Pull in all the records from the following EPA API tables, which are accessed by state postal code
	 *	TODO: populate the table: RCRAINFO for WA, OR, and CA
	 */

			//populate_bigtable_by_state( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "", $API_ROOT_STRING . "/RCR_HREPORT_UNIV/LOCATION_STATE/", $ROWS, $STATE );
			//populate_bigtable_by_state( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "RCR_CA_EVENT_", $API_ROOT_STRING . "/RCR_CA_EVENT/ACTIVITY_LOCATION/", $ROWS, $STATE );
			//populate_bigtable_by_state( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "RCR_CMECOMP3_", $API_ROOT_STRING . "/RCR_CMECOMP3/STATE/", $ROWS, $STATE );
	
	
	/**
	 *	Pull in some records that require special functions to access
	 */
	
			//populate_bigtable_with_PUNIT_DETAIL( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING, $ROWS, $STATE );
			//populate_bigtable_with_CA_EVENT_DESC( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING );
	

	// OPTIMIZE AND FLUSH RCRAINFO_MASTER table after lots of changes
	//$oMySQL->ExecuteSQL( "OPTIMIZE TABLE $MASTER_TABLE_NAME" );
	//$oMySQL->ExecuteSQL( "FLUSH TABLE $MASTER_TABLE_NAME" );
	
	
	/**
	 *	Uncomment to create/refresh RCRA non-TSD and non-CORRACTS sub-table from RCRAINFO_MASTER
	 */
	
			make_subtable_RCRAINFO( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX );

			
	/**
	 *	Uncomment to create/refresh RCRA TSD and RCRA CORRACTS sub-table from RCRAINFO_MASTER
	 */
	
			make_subtable_RCRAINFO_TSD_CORRACTS( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX );

	
	
/**
 * This table is a sub-table created from RCRAINFO. Each time RCRAINFO is refreshed this table will have to be recreated like this:



 */
	
				
} // end function

/**
 * Adds the mysql point geometry columns to an array.
 * @param object $oMySQL
 * @param string $tableName
 */

function addGeometry( $oMySQL, $tableName ) {
	
	error_log("-------------- ADDING GEOMETRY --------------");
	
	$sql_string = "ALTER TABLE $tableName ADD MYSQL_POINT_GEOMETRY GEOMETRY NOT NULL AFTER longitude";
	$oMySQL->ExecuteSQL( $sql_string );
	
	$sql_string = "UPDATE $tableName set MYSQL_POINT_GEOMETRY = point(longitude, latitude)";
	$oMySQL->ExecuteSQL( $sql_string );
	
} // end function





/**
 * Remakes RCRA non-TSD and RCRA non-CORRACTS sub-table from RCRAINFO_MASTER.
 * @param object $oMySQL
 * @param string $MASTER_TABLE_NAME The name of the master RCRA table to be pulling records from.
 * @param string $TABLE_INDEX The name of the column that holds the main index.
 */

function make_subtable_RCRAINFO( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX ) {
	
		error_log("-------------- MAKING RCRAINFO SUB-TABLE --------------");
	
		// what we will call out sub-table
		$SUB_TABLE_NAME = "RCRAINFO";
	
		// delete table if it exists
		$oMySQL->ExecuteSQL( "DROP TABLE IF EXISTS $SUB_TABLE_NAME" );
		
		// create a duplicate of the master table and copy it to $SUB_TABLE_NAME
		$oMySQL->ExecuteSQL( "CREATE TABLE $SUB_TABLE_NAME SELECT * FROM $MASTER_TABLE_NAME" );


		$sql_string = 	"DELETE FROM $SUB_TABLE_NAME WHERE ";
		$sql_string .= 	" AS_FEDERALLY_REGULATED_TSDF != '-----' AND AS_FEDERALLY_REGULATED_TSDF != '' ";
		$sql_string .= 	" OR AS_CONVERTER_TSDF != '-----' AND AS_CONVERTER_TSDF != '' ";
		$sql_string .=	" OR AS_STATE_REGULATED_TSDF != '--------' AND AS_STATE_REGULATED_TSDF != '' ";
		$sql_string .=	" OR COMMERCIAL_TSD != 'N' AND COMMERCIAL_TSD != '' ";
		$sql_string .=	" OR TSD_TYPE != '-----' AND TSD_TYPE != '' ";
		$sql_string .=	" OR SUBJCA_NON_TSD != 'N' AND SUBJCA_NON_TSD != '' ";
		$sql_string .=	" OR SUBJCA_TSD_3004 != 'N' AND SUBJCA_TSD_3004 != '' ";
		$sql_string .=	" OR SUBJCA_TSD_DISCRETION != 'N' AND SUBJCA_TSD_DISCRETION != '' ";
		$sql_string .=	" OR OPERATING_TSDF != '-----' AND OPERATING_TSDF != '' ";
		$sql_string .=	" OR SUBJCA = 'Y' ";
		$sql_string .=	" OR RCR_CA_EVENT_EVENT_SEQ != '' ";
		
		//error_log( "SQL STRING TO EXECUTE: $sql_string" );
		
		// create new table
		$oMySQL->ExecuteSQL( $sql_string );

		
		// add a primary index
		$oMySQL->ExecuteSQL( "ALTER TABLE $SUB_TABLE_NAME ADD UNIQUE INDEX($TABLE_INDEX)" );
		
		
		// OPTIMIZE AND FLUSH the table after lots of changes
		$oMySQL->ExecuteSQL( "OPTIMIZE TABLE $SUB_TABLE_NAME" );
		$oMySQL->ExecuteSQL( "FLUSH TABLE $SUB_TABLE_NAME" );
		
		// add geometry columns
		addGeometry( $oMySQL, $SUB_TABLE_NAME );
		
} // end function




/**
 * Remakes RCRA TSD and RCRA CORRACTS sub-table from RCRAINFO_MASTER.
 * @param object $oMySQL
 * @param string $MASTER_TABLE_NAME The name of the master RCRA table to be pulling records from.
 * @param string $TABLE_INDEX The name of the column that holds the main index.
 */

function make_subtable_RCRAINFO_TSD_CORRACTS( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX ) {
	
		error_log("-------------- MAKING RCRAINFO_TSD_CORRACTS SUB-TABLE --------------");
	
		// what we will call out sub-table
		$SUB_TABLE_NAME = "RCRAINFO_TSD_CORRACTS";
	
		// delete table if it exists
		$oMySQL->ExecuteSQL( "DROP TABLE IF EXISTS $SUB_TABLE_NAME" );
		
		// make our long sql query string
		$sql_string = 	"CREATE TABLE $SUB_TABLE_NAME SELECT * FROM `$MASTER_TABLE_NAME` WHERE ";
		$sql_string .= 	" AS_FEDERALLY_REGULATED_TSDF != '-----' AND AS_FEDERALLY_REGULATED_TSDF != '' ";
		$sql_string .= 	" OR AS_CONVERTER_TSDF != '-----' AND AS_CONVERTER_TSDF != '' ";
		$sql_string .=	" OR AS_STATE_REGULATED_TSDF != '--------' AND AS_STATE_REGULATED_TSDF != '' ";
		$sql_string .=	" OR COMMERCIAL_TSD != 'N' AND COMMERCIAL_TSD != '' ";
		$sql_string .=	" OR TSD_TYPE != '-----' AND TSD_TYPE != '' ";
		$sql_string .=	" OR SUBJCA_NON_TSD != 'N' AND SUBJCA_NON_TSD != '' ";
		$sql_string .=	" OR SUBJCA_TSD_3004 != 'N' AND SUBJCA_TSD_3004 != '' ";
		$sql_string .=	" OR SUBJCA_TSD_DISCRETION != 'N' AND SUBJCA_TSD_DISCRETION != '' ";
		$sql_string .=	" OR OPERATING_TSDF != '-----' AND OPERATING_TSDF != '' ";
		$sql_string .=	" OR SUBJCA = 'Y' ";
		$sql_string .=	" OR RCR_CA_EVENT_EVENT_SEQ != '' ";
		
		//error_log( "SQL STRING TO EXECUTE: $sql_string" );
		
		// create new table
		$oMySQL->ExecuteSQL( $sql_string );

		
		// add a primary index
		$oMySQL->ExecuteSQL( "ALTER TABLE $SUB_TABLE_NAME ADD UNIQUE INDEX($TABLE_INDEX)" );
		
		
		// OPTIMIZE AND FLUSH the table after lots of changes
		$oMySQL->ExecuteSQL( "OPTIMIZE TABLE $SUB_TABLE_NAME" );
		$oMySQL->ExecuteSQL( "FLUSH TABLE $SUB_TABLE_NAME" );
		
		// add geometry columns
		addGeometry( $oMySQL, $SUB_TABLE_NAME );
		
} // end function




/**
 * Pulls all CA_EVENT_DESC records by RCR_CA_EVENT_CA_EVENT_CODE for just the records we need.
 * @param object $oMySQL
 * @param string $MASTER_TABLE_NAME
 * @param string $TABLE_INDEX
 * @param string $API_ROOT_STRING
 */

function populate_bigtable_with_CA_EVENT_DESC( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING ) {
	
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "CA_EVENT_DESC" ); // create prototype column called CA_EVENT_DESC
	
	$oMySQL->ExecuteSQL( "SELECT $TABLE_INDEX,RCR_CA_EVENT_CA_EVENT_CODE FROM `RCRAINFO` WHERE RCR_CA_EVENT_CA_EVENT_CODE != '' " ); // grab all the columns from $MASTER_TABLE_NAME with the column "RCR_CA_EVENT_CA_EVENT_CODE" not empty
	
	$table_index_array = $oMySQL->ArrayResults(); // create an array of just those HANDLER_IDs and RCR_CA_EVENT_CA_EVENT_CODEs

	if ( !empty( $table_index_array ) ) {
		
		foreach ($table_index_array as $key => $value ) {

			$HANDLER_ID = $value["$TABLE_INDEX"]; // get this rows HANDLER ID
			$RCR_CA_EVENT_CA_EVENT_CODE = $value["RCR_CA_EVENT_CA_EVENT_CODE"]; // get this rows RCR_CA_EVENT_CA_EVENT_CODE
			
			// query API for the correct action description
			$CA_EVENT_DESC_ARRAY = get_data_from_url( "$API_ROOT_STRING/RCR_CA_EVENTCODES/CA_EVENT_CODE/$RCR_CA_EVENT_CA_EVENT_CODE/rows/1:1" , true, true);
			
			// isolate the CA description
			$CA_EVENT_DESC = preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $CA_EVENT_DESC_ARRAY["RCR_CA_EVENTCODES"]["CA_EVENT_DESC"] );
			
			// update the row via the HANDER_ID
			$sql_string = "UPDATE $MASTER_TABLE_NAME SET CA_EVENT_DESC = '" .$CA_EVENT_DESC. "' WHERE $TABLE_INDEX = '". $HANDLER_ID ."'";
					
			$oMySQL->ExecuteSQL( $sql_string );
		
		}

	}
	
} // end function




/**
 * Pulls all HANDLER_ID records from RCR_EVENT_UNIT and then queries RCR_PUNIT_DETAIL by HANDER_ID for just the records we need.
 * @param object $oMySQL
 * @param string $MASTER_TABLE_NAME
 * @param string $TABLE_INDEX
 * @param string $API_ROOT_STRING
 * @param string $ROWS How many rows to grab at a time, max 999.
 * @param string $STATE The state to populate the DB with.
 */

function populate_bigtable_with_PUNIT_DETAIL( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING, $ROWS, $STATE) {
	
	/**
	 *	Grabs all the RCR_EVENT_UNIT records from the EPA API and puts them in a big array
	 */ 
	
	$empty_row_count = 0; // this keeps track of how many times the EPA API returns an empty result
	$current_row_index = 1; // this tracks which row the function is currently trying to process, starts on row 1
	$WHOLE_API_QUERY_RESULTS_ARRAY = array(); // initilize scope of array
	$SUBSET_API_QUERY_RESULTS_ARRAY = array();  // initilize scope of array
	
	while ( $empty_row_count < 2 ) { // execute until the API returns n empty results in a row
	
		$first_row = $current_row_index;
		
		$last_row = $first_row + $ROWS;

		$PART_API_QUERY_RESULTS_ARRAY = get_data_from_url( "$API_ROOT_STRING/RCR_EVENT_UNIT/ACTIVITY_LOCATION/$STATE/rows/$first_row:$last_row", true, true);

		
		// if the EPA API returns a non-empty result
		if ( !empty( $PART_API_QUERY_RESULTS_ARRAY ) ) {
			
			$WHOLE_API_QUERY_RESULTS_ARRAY = array_merge_recursive( $WHOLE_API_QUERY_RESULTS_ARRAY, $PART_API_QUERY_RESULTS_ARRAY );
			
			$current_row_index = $current_row_index + $ROWS;
		
		} else { // if the EPA API returns an empty result
			
			error_log( "Got an empty array in population of table!" );
			$empty_row_count++; // this will limit the while loop to a few iterations for testing
		}
		
		//$empty_row_count++;
		
		error_log("Current Row Index: " .$current_row_index );
		error_log("Empty row count: " .$empty_row_count );
		
		
	} // end while loop
	
	//error_log("API RESULTS: " . print_r( $WHOLE_API_QUERY_RESULTS_ARRAY, 1 ) );

	
	
	/**
	 *	Isolates HANDLER_IDs, remove duplicates, sorts HANDLER_IDs
	 */  
		
		// cycles through each element in the returned array
		foreach ( $WHOLE_API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
			
			foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
				
				$child_key = trim( $child_key ); // trim the whitespace and other hidden characters
				
				// needs to processes each row into the database
				if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
				
					$SUBSET_API_QUERY_RESULTS_ARRAY[] =  $child_value[ "HANDLER_ID" ];
				
				}
				
			}
			
		}
		
		$SUBSET_API_QUERY_RESULTS_ARRAY = array_unique( $SUBSET_API_QUERY_RESULTS_ARRAY );
	
		error_log( "SUBSET_API_QUERY_RESULTS_ARRAY: " . print_r($SUBSET_API_QUERY_RESULTS_ARRAY,1 ) );


		
		
	/**
	 *	Puts the remaining HANDLER_IDs in $table_index_array and processes updates the local database table
	 */ 
	
	// add the extra columns to store the subset of data we want from RCR_PUNIT_DETAIL API call (will not overwrite if already exist)
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "RCR_PUNIT_DETAIL_ACTIONS" ); // if changed here must be changed below too
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST" ); // if changed here must be changed below too
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST" ); // if changed here must be changed below too

	
	$table_index_array = $SUBSET_API_QUERY_RESULTS_ARRAY; // array of just those HANDLER_IDs we want to query the API with
	
		// error_log("Populating table: $MASTER_TABLE_NAME by HANDLER_ID.");
		// error_log( "RETURNED HANDLER_ID ARRAY: " . print_r( $table_index_array, 1 ) );

	$RCR_PUNIT_DETAIL_COUNT = 0; // initilize scope and set punitive count to 0
	$HANDLER_ID = null; // initilize scope
	$first_EFFECTIVE_DATE = null; // initilize scope
	$last_EFFECTIVE_DATE = null; // initilize scope
	
	// for each element in the array of HANDLER_IDs create a API query string based on the HANDLER_ID and request the data
	foreach ( $table_index_array as $sub_key => $sub_value ) {
		
		$date_range = array(); // initilize scope to store out dates of interest
		
		// if the $sub_value i.e. the HANDLER_ID is empty for some reason skip row
		if ( empty( $sub_value ) ) {
			
			error_log( "sub_value empty: $sub_value skipping row!");
			
		} else {
			
			error_log( "RETURNED (KEY:VALUE) = ($sub_key:$sub_value)" );
			
			$HANDLER_ID = $sub_value; // grab the HANDLER_ID for this row
			
			$API_QUERY_STRING_FQ = "$API_ROOT_STRING/RCR_PUNIT_DETAIL/HANDLER_ID/" . "$sub_value"; // make fully qualified API query sting
					
			$API_QUERY_RESULTS_ARRAY = get_data_from_url( $API_QUERY_STRING_FQ, true, true); // get data for row
	
				//error_log("API RESULTS: " . print_r($API_QUERY_RESULTS_ARRAY, 1) );
			
				
			if ( !empty( $API_QUERY_RESULTS_ARRAY ) ) {
				
				// cycles through each element in the returned array
				foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
					
					foreach ( $master_value as $child_key => $child_value ) {

						if ( !is_array( $child_key ) ) { 
							
							$child_key = trim( $child_key ); // do not remove
							
						} 
						
							if ( $child_key == "Count" ) { // get the count value, tells us the gross number of punitive incidents
								             
								$RCR_PUNIT_DETAIL_COUNT = trim( $child_value ); // do not remove
								
								error_log("RCR_PUNIT_DETAIL_COUNT: " . $RCR_PUNIT_DETAIL_COUNT );
								error_log("child_key: " . print_r( $child_key, 1 ) );
								error_log("child_value: " . print_r( $child_value, 1 ) );
								
								
							}
							
							
							
							if ( $RCR_PUNIT_DETAIL_COUNT == "1" ) {
								
								//error_log("RCR_PUNIT_DETAIL_COUNT is = 1 : $RCR_PUNIT_DETAIL_COUNT ");
																		
								if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
									
									if ( $child_key == "EFFECTIVE_DATE" && !empty( $child_value ) ) {
										
										$unix_time = strtotime( $child_value );
										$date_range[] = $unix_time;
										
										error_log( "(child_key:child_value) = ($child_key:$child_value) " );
										error_log( "unix_time: $unix_time " );
										 
									} // end if

								} // end if
								
								
							} else { // $RCR_PUNIT_DETAIL_COUNT is not == 1 then it is an array
								
								//error_log("RCR_PUNIT_DETAIL_COUNT is > 1 ");
								
								if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
									
									foreach ( $child_value as $sub_child_key => $sub_child_value ) {
									
										if ( $sub_child_key == "EFFECTIVE_DATE" && !empty( $sub_child_value ) ) {
											
											$unix_time = strtotime( $sub_child_value );
											$date_range[] = $unix_time;
											
											error_log( "(sub_child_key:sub_child_value) = ($sub_child_key:$sub_child_value) " );
											error_log( "unix_time: $unix_time " );
											 
										} // end if

									} // end foreach

								} // end if
								
							} 
								
								
					} // end inner foreach
					
				} // end outer foreach
					

				
			sort( $date_range ); // order from oldest to newest dates of EFFECTIVE_DATE
			
			$first_EFFECTIVE_DATE = $date_range[0]; // grab oldest EFFECTIVE_DATE
			$last_EFFECTIVE_DATE = array_pop( array_values( $date_range ) ); // grab most recent EFFECTIVE_DATE
			
			// convert dates to human readable format
			$first_EFFECTIVE_DATE = date( "d M Y", $first_EFFECTIVE_DATE );
			$last_EFFECTIVE_DATE = date( "d M Y", $last_EFFECTIVE_DATE );
	
				//error_log( "DATE ARRAY: " . print_r( $date_range , 1) );
				//error_log( "DATE RANGE: (first_EFFECTIVE_DATE:last_EFFECTIVE_DATE) = ($first_EFFECTIVE_DATE:$last_EFFECTIVE_DATE)" );
				
			
			} else {
				
				error_log("API RESULTS EMPTY: " . print_r($API_QUERY_RESULTS_ARRAY, 1) . "NO PUNUTIVE DETAILS" );
				
				$RCR_PUNIT_DETAIL_COUNT = 0; // reset to 0
				$first_EFFECTIVE_DATE = null; // reset to null
				$last_EFFECTIVE_DATE = null; // reset to null
				
			}
			
		
			$sql_string = "UPDATE $MASTER_TABLE_NAME SET RCR_PUNIT_DETAIL_ACTIONS = '" .$RCR_PUNIT_DETAIL_COUNT. "', RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST = '" .$first_EFFECTIVE_DATE. "', RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST = '" .$last_EFFECTIVE_DATE. "' WHERE $TABLE_INDEX = '". $HANDLER_ID ."'";
			
			$oMySQL->ExecuteSQL( $sql_string );
			
				error_log("SQL STRING: " . $sql_string );
	
	
		} // end if - the $sub_value i.e. the HANDLER_ID is empty skip row
	
		
	} // end inner foreach

	
} // end function




/**
 * Make a new column in the table: $MASTER_TABLE_NAME if the column is absent.
 * @param object $oMySQL The MySQL object.
 * @param string $MASTER_TABLE_NAME The name of the table to add the column to.
 * @param string $COLUMN_NAME The name of the column.
 */

function make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, $COLUMN_NAME ) {

	// test for table and column
	if ( !mysql_num_rows( mysql_query( "SHOW COLUMNS FROM $MASTER_TABLE_NAME LIKE '".$COLUMN_NAME."'" ) ) ) {
						
		error_log("Column $COLUMN_NAME does not exist, creating it!");
						
		$oMySQL->ExecuteSQL( "ALTER TABLE $MASTER_TABLE_NAME ADD COLUMN ($COLUMN_NAME TEXT NOT NULL)" );
												

	} else {
						
		error_log("Column $COLUMN_NAME already exists.");

	}

}





/**
 * Pull all the records from the EPA API and populate the table for WA, OR and CA.
 * @param object $oMySQL The MySQL object.
 * @param string $MASTER_TABLE_NAME
 * @param string $TABLE_INDEX The name of the column used as the index.
 * @param string $COLUMN_PREFIX The prefix added to the column names in the prototype function, if none use "".
 * @param string $API_QUERY_STRING The API query string without the row information.
 * @param integer $ROWS The number of rows to request from the API at a time. Max 1000.
 * @param string $STATE The state to populate the database with.
 */

function populate_bigtable_by_state( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $COLUMN_PREFIX, $API_QUERY_STRING, $ROWS, $STATE ) {
	
	error_log("Populating table: $MASTER_TABLE_NAME with data from $STATE.");
	
	
	$empty_row_count = 0; // this keeps track of how many times the EPA API returns an empty result, when empty row count gets to 10 it must be done
	$current_row_index = 1; // this tracks which row the function is currently trying to process, starts on row 1
	
	// this loop grabs a number of rows in $ROWS
	while ( $empty_row_count < 3 ) { // execute until the API returns n empty results in a row
	
		$first_row = $current_row_index;
		
		$last_row = $first_row + $ROWS;
		
		$API_QUERY_STRING_FQ = $API_QUERY_STRING . "$STATE/rows/$first_row:$last_row"; 
		
		$API_QUERY_RESULTS_ARRAY = get_data_from_url( $API_QUERY_STRING_FQ, true, true);
	
		//error_log("API RESULTS: " . print_r($API_QUERY_RESULTS_ARRAY, 1) );
		
		// if the EPA API returns a non-empty result
		if ( !empty( $API_QUERY_RESULTS_ARRAY ) ) {
			
			//$empty_row_count = 0; // resets the empty_row_count if a non-empty result is returned
			$current_row_index = $current_row_index + $ROWS;
		
			// cycles through each element in the returned array
			foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
				
				foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
					
					$child_key = trim( $child_key ); // trim the whitespace and other hidden characters
					
					// needs to processes each row into the database
					if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys

						$sql_col_row = array(); // initilize scope
						
								foreach ( $child_value as $sub_child_key => $sub_child_value ) {

										// make a combined stack of column name ($sub_child_key) = data to update ($sub_child_value)
										if ( !is_array($sub_child_value) ) { // is NOT an array
											
											if ( $sub_child_key == "$TABLE_INDEX" ) { // never add prefix to the $TABLE_INDEX
												
												$sql_col_row[] = $sub_child_key . " = " . "'" . $sub_child_value . "'";
												
											} else {
												
												$sql_col_row[] = $COLUMN_PREFIX . $sub_child_key . " = " . "'" . $sub_child_value . "'";
												
											}
											
											//error_log("SUB KEY:VALUE =  $sub_child_key:$sub_child_value");
									
										} else { // IS an array
											
											$sub_child_value = implode( "", $sub_child_value ); // make a simple value
			
											
											if ( $sub_child_key == "$TABLE_INDEX" ) { // never add prefix to the $TABLE_INDEX
												
												$sql_col_row[] = $sub_child_key . " = " . "'" . $sub_child_value . "'";
												
											} else {
												
												$sql_col_row[] = $COLUMN_PREFIX . $sub_child_key . " = " . "'" . $sub_child_value . "'";
												
											}
	
											//error_log("SUB KEY:VALUE =  $sub_child_key:$sub_child_value");
										}
		
								} // inner sub-foreach 

								
								
								// insert new row into table or replace row with new data if $TABLE_INDEX is in the table already
								$sql_string = "INSERT INTO $MASTER_TABLE_NAME SET " . implode(', ', $sql_col_row ) . " ON DUPLICATE KEY UPDATE " . implode(', ', $sql_col_row );

								// Produces:
								// INSERT INTO RCRAINFO SET HANDLER_ID = 'WAD093639946', RCR_CA_EVENT_EVENT_SEQ = '2', RCR_CA_EVENT_RESPONSIBLE_AGENCY = 'S', RCR_CA_EVENT_ACTIVITY_LOCATION = 'WA', RCR_CA_EVENT_ACTUAL_DATE = '05-AUG-04', RCR_CA_EVENT_OWNER = 'HQ', RCR_CA_EVENT_CA_EVENT_CODE = 'CA750YE', RCR_CA_EVENT_SCHEDULE_DATE_ORIG = '', RCR_CA_EVENT_SCHEDULE_DATE_NEW = '', RCR_CA_EVENT_BEST_DATE = '05-AUG-04', RCR_CA_EVENT_PERSON_OWNER = 'WA', RCR_CA_EVENT_PERSON_ID = 'WABKM', RCR_CA_EVENT_SUB_ORGANIZATION_OWNER = 'WA', RCR_CA_EVENT_SUB_ORGANIZATION = 'NW', RCR_CA_EVENT_RNUM = '5' 
								//  ON DUPLICATE KEY UPDATE HANDLER_ID = 'WAD093639946', RCR_CA_EVENT_EVENT_SEQ = '2', RCR_CA_EVENT_RESPONSIBLE_AGENCY = 'S', RCR_CA_EVENT_ACTIVITY_LOCATION = 'WA', RCR_CA_EVENT_ACTUAL_DATE = '05-AUG-04', RCR_CA_EVENT_OWNER = 'HQ', RCR_CA_EVENT_CA_EVENT_CODE = 'CA750YE', RCR_CA_EVENT_SCHEDULE_DATE_ORIG = '', RCR_CA_EVENT_SCHEDULE_DATE_NEW = '', RCR_CA_EVENT_BEST_DATE = '05-AUG-04', RCR_CA_EVENT_PERSON_OWNER = 'WA', RCR_CA_EVENT_PERSON_ID = 'WABKM', RCR_CA_EVENT_SUB_ORGANIZATION_OWNER = 'WA', RCR_CA_EVENT_SUB_ORGANIZATION = 'NW', RCR_CA_EVENT_RNUM = '5'
								
								
								$oMySQL->ExecuteSQL( $sql_string );
							
								//error_log("SQL STRING: " . $sql_string );
								
				
					} // end inner if
				
				} // end inner foreach
			
			} // end outer foreach
		
		
		} else { // if the EPA API returns an empty result
			
			error_log( "Got an empty array in population of table!" );
			
			$empty_row_count++; // this will limit the while loop to a few iterations for testing
			
		} // end outer API if
		
		error_log("Current Row Index: " .$current_row_index );
		error_log("Empty row count: " .$empty_row_count );
		
		
		
	} // end while loop
	
} // end function 





/**
 * Gets a single prototype row and converts that in to a protptype table.
 * @param object $oMySQL The mysql object.
 * @param string $MASTER_TABLE_NAME Identify the name you would like to give the prototype new table.
 * @param string $TABLE_INDEX Identify the column which will have unique values, which you would like to use as the table index.
 * @param string $COLUMN_PREFIX Some columns will want a unique prefix to track non-duplicate columns with duplicate names.
 * @param string $API_QUERY_STRING returned prototype array must be a single row i.e. 1:1, for the foreach loop to function correctly
 */

function make_prototype_table( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $COLUMN_PREFIX, $API_QUERY_STRING ) {
	
	// test for table
	if ( !mysql_num_rows( mysql_query( "SHOW TABLES LIKE '".$MASTER_TABLE_NAME."'" ) ) ) {

		// Creates the table: $MASTER_TABLE_NAME if it does not exist and adds the column: HANDLER_ID
		$oMySQL->ExecuteSQL( "CREATE TABLE $MASTER_TABLE_NAME ($TABLE_INDEX VARCHAR(12) NOT NULL)" );
		$oMySQL->ExecuteSQL( "ALTER TABLE $MASTER_TABLE_NAME ADD UNIQUE INDEX($TABLE_INDEX)" );
		
		//error_log("Table $MASTER_TABLE_NAME does not exist, creating table.");
		
	} else {
		
		//error_log("Table $MASTER_TABLE_NAME already exists.");
		
	}
	
	

	$API_QUERY_RESULTS_ARRAY = get_data_from_url( $API_QUERY_STRING, true, true);
	
	if ( !empty( $API_QUERY_RESULTS_ARRAY ) ) {
	
		foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
		
			foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
				
				$child_key = trim( $child_key ); // trim the whitespace and other hidden characters
				
				if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
					
					$child_key = trim($child_key); // do not remove
					
					if ( $child_key != "$TABLE_INDEX" ) { // never add prefix to the $TABLE_INDEX
						
							// create new columns
							if ( !mysql_num_rows( mysql_query( "SHOW COLUMNS FROM $MASTER_TABLE_NAME LIKE '".$COLUMN_PREFIX . $child_key."'" ) ) ) {
								
								error_log("Column $COLUMN_PREFIX$child_key does not exist, creating it");
								
								$oMySQL->ExecuteSQL( "ALTER TABLE $MASTER_TABLE_NAME ADD COLUMN ($COLUMN_PREFIX$child_key TEXT NOT NULL)" );
		
							} else {
								
								error_log("Column $COLUMN_PREFIX$child_key already exists");
							}
												
					}

				}
				
			} // end inner foreach
			
		} // end outer foreach
	
	} else {
		
		error_log( "Got an empty array in making prototype table!" );
		
	}
	
} // end function






/**
 * Gets the data from a URL. Replaces spaces with "+" as a URL encoding measure.
 * @param sting $url The full URL to be queried over http.
 * @param booleen $array_flag True will try to return an associative array from XML data. False will return raw data. Default = false.
 * @param booleen $pause_flag True will pause for 1 second after executing the whole query process, helps avoid getting banned from external APIs. Default = true.
 * @return string | array $data Returns either the raw data from the API or associative array.
 */				

function get_data_from_url( $url, $array_flag = false, $pause_flag = true ) {
	
	$url = preg_replace( "/\s+/", "+", $url );
	
	$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );	# URL to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); # return results into a variable
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 ); # max time in seconds to allow for execution
			$data = curl_exec( $ch ); # run curl
	curl_close($ch);
	
	if ( $pause_flag != false ) {
		
		sleep( 1 ); // most APIs have a speed limit, this 1 second pause helps avoid getting banned from your chosen API.
		
	}
	
	if ( $array_flag == true ) {
	
		$data = json_decode( json_encode( simplexml_load_string( $data ) ) , 1 );
		
	}
	
	error_log("API ENCODED URL: " . $url );
	
	return $data;

}





/**
 * Cycles throught the table: $MASTER_TABLE_NAME row by row, and uses $TABLE_INDEX to grab the remaining data in the other EPA API tables and update our bigtable.
 * @param unknown_type $oMySQL
 * @param unknown_type $MASTER_TABLE_NAME
 * @param unknown_type $TABLE_INDEX
 * @param unknown_type $API_QUERY_STRING
 */

function populate_bigtable_by_HANDLER_ID( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, $API_QUERY_STRING ) {
	
	// add the extra columns to store the subset of data we want from RCR_PUNIT_DETAIL API call (will not overwrite if already exist)
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "RCR_PUNIT_DETAIL_ACTIONS" );
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST" );
	make_prototype_column( $oMySQL, $MASTER_TABLE_NAME, "RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST" );

	$oMySQL->ExecuteSQL( "SELECT $TABLE_INDEX FROM $MASTER_TABLE_NAME" ); // grab all rows from the $TABLE_INDEX i.e. HANDLER_ID column
	
	$table_index_array = $oMySQL->ArrayResults(); // create an array of just those HANDLER_IDs
	
		// error_log("Populating table: $MASTER_TABLE_NAME by HANDLER_ID.");
		// error_log( "RETURNED HANDLER_ID ARRAY: " . print_r( $table_index_array, 1 ) );

	$RCR_PUNIT_DETAIL_COUNT = 0; // initilize scope and set punitive count to 0
	$HANDLER_ID = null; // initilize scope
	$first_EFFECTIVE_DATE = null; // initilize scope
	$last_EFFECTIVE_DATE = null; // initilize scope
	
	// for each element in the array of HANDLER_IDs create a API query string based on the HANDLER_ID and request the data
	foreach ( $table_index_array as $key => $value_array ) {
		
		foreach ( $value_array as $sub_key => $sub_value ) {
			
			$date_range = array(); // initilize scope to store out dates of interest
			
			// if the $sub_value i.e. the HANDLER_ID is empty for some reason skip row
			if ( empty( $sub_value ) ) {
				
				error_log( "sub_value empty: $sub_value skipping row!");
				
			} else {
				
				error_log( "RETURNED (KEY:VALUE) = ($sub_key:$sub_value)" );
				
				$HANDLER_ID = $sub_value; // grab the HANDLER_ID for this row
				
				$API_QUERY_STRING_FQ = $API_QUERY_STRING . "$sub_value"; // make fully qualified API query sting
						
				$API_QUERY_RESULTS_ARRAY = get_data_from_url( $API_QUERY_STRING_FQ, true, true); // get data for row
	
					// error_log("API RESULTS: " . print_r($API_QUERY_RESULTS_ARRAY, 1) );
				
					
				if ( !empty( $API_QUERY_RESULTS_ARRAY ) ) {
					
					// cycles through each element in the returned array
					foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
						
						foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
													
							$child_key = trim( $child_key ); // trim the whitespace and other hidden characters
							
								if ( $child_key == "Count" ) { // get the count value, tells us the gross number of punitive incidents
									             
									$RCR_PUNIT_DETAIL_COUNT = $child_value;
									
									error_log( "RCR_PUNIT_DETAIL_COUNT: " . $RCR_PUNIT_DETAIL_COUNT );
									
								} else if ( $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
									
										foreach ( $child_value as $sub_child_key => $sub_child_value ) {
					
											$sub_child_key = trim( $sub_child_key ); // trim the whitespace and other hidden characters
											$sub_child_value = trim( $sub_child_value ); // trim the whitespace and other hidden characters
											
												if ( $sub_child_key == "EFFECTIVE_DATE" && !empty( $sub_child_value ) ) {
													
													$unix_time = strtotime( $sub_child_value );
													$date_range[] = $unix_time;
													
													//error_log( "(sub_child_key:sub_child_value) = ($sub_child_key:$sub_child_value) " );
													//error_log( "unix_time: $unix_time " );
													
												}
										}
								}
	
						} // end inner foreach
						
					} // end outer foreach

				
				sort( $date_range ); // order from oldest to newest dates of EFFECTIVE_DATE
				
				$first_EFFECTIVE_DATE = $date_range[0]; // grab oldest EFFECTIVE_DATE
				$last_EFFECTIVE_DATE = array_pop( array_values( $date_range ) ); // grab most recent EFFECTIVE_DATE
				
				// convert dates to human readable format
				$first_EFFECTIVE_DATE = date( "d M Y", $first_EFFECTIVE_DATE );
				$last_EFFECTIVE_DATE = date( "d M Y", $last_EFFECTIVE_DATE );
	
					//error_log( "DATE ARRAY: " . print_r( $date_range , 1) );
					//error_log( "DATE RANGE: (first_EFFECTIVE_DATE:last_EFFECTIVE_DATE) = ($first_EFFECTIVE_DATE:$last_EFFECTIVE_DATE)" );
					
				
				} else {
					
					error_log("API RESULTS EMPTY: " . print_r($API_QUERY_RESULTS_ARRAY, 1) . "NO PUNUTIVE DETAILS" );
					
					$RCR_PUNIT_DETAIL_COUNT = 0; // reset to 0
					$first_EFFECTIVE_DATE = null; // reset to null
					$last_EFFECTIVE_DATE = null; // reset to null
					
				}
				
			
				$sql_string = "UPDATE RCRAINFO SET RCR_PUNIT_DETAIL_ACTIONS = '" .$RCR_PUNIT_DETAIL_COUNT. "', RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST = '" .$first_EFFECTIVE_DATE. "', RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST = '" .$last_EFFECTIVE_DATE. "' WHERE HANDLER_ID = '". $HANDLER_ID ."'";
				
				$oMySQL->ExecuteSQL( $sql_string );
				
					error_log("SQL STRING: " . $sql_string );

	
			} // end if - the $sub_value i.e. the HANDLER_ID is empty skip row

			
		} // end inner foreach
	} // end outer foreach

	
} // end function






/**
 * Snippets:
 * 


foreach ( $child_value as $sub_child_key => $sub_child_value ) {
									
	// make a combined stack of column name ($sub_child_key) = data to update ($sub_child_value)
	if ( !is_array($sub_child_key) ) { // is NOT an array
		
		if ( $sub_child_key == "HANDLER_ID" ) { // grab the HANDLER_ID
			
			$HANDLER_ID = $sub_child_value;
			
		} else { // skip the HANDLER_ID
		
			$sql_col_row[] = $sub_child_key . " = " . "'" . $sub_child_value . "'";
		
		}

	} else { // IS an array
		
		$sub_child_key = implode( "", $sub_child_key ); // make a simple value
		$sub_child_value = implode( "", $sub_child_value ); // make a simple value
		
		if ( $sub_child_key == "HANDLER_ID" ) { // grab the HANDLER_ID
			
			$HANDLER_ID = $sub_child_value;
			
		} else { // skip the HANDLER_ID
		
			$sql_col_row[] = $sub_child_key . " = " . "'" . $sub_child_value . "'";
		
		}
	
	}

} // inner sub-foreach 



	EXAMPLES: 
	
	http://iaspub.epa.gov/enviro/efservice/ <table_name> / <column_name> / <column_value> /rows/ <first_row> : <last_row>
	
	http://iaspub.epa.gov/enviro/efservice/multisystem/minLatitude/42.15981/maxLatitude/42.4629/minLongitude/-88.09867/maxLongitude/-87.80414/rows/1:1
	
	
	Unfortunately, we currently do not have a direct bounding box query against the FRS database tables, but we do have a bounding box query
	within the API that will query multiple Envirofacts program office databases that make up our MultiSystem query (http://www.epa.gov/enviro/facts/multisystem.html).  
	
	The results of this query will make use of data from FRS to provide facility location information, but the results will only be provided for facilities that
	report to AFS, ACRES, BRS, CERCLIS, PCS, RADInfo, RCRAInfo, or TRI. Descriptions of these program systems can be found at http://www.epa.gov/enviro/facts/qmr.html#efsearch.
	
	http://www.epa.gov/enviro/facts/ef_restful.html


	



// cycles through each element in the returned array
foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
	
	foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
		
		$child_key = trim( $child_key ); // trim the whitespace and other hidden characters
		
		// needs to processes each row into the database
		if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys

			$sql_col_row = array(); // initilize scope
			
					foreach ( $child_value as $sub_child_key => $sub_child_value ) {

						// make a combined stack of column name ($sub_child_key) = data to update ($sub_child_value)
						if ( !is_array($sub_child_value) ) { // is NOT an array
							
							$sql_col_row[] = $sub_child_key . " = " . "'" . $sub_child_value . "'";
							
							//error_log("SUB KEY:VALUE =  $sub_child_key:$sub_child_value");
					
						} else { // IS an array
							
							$sub_child_value = implode( "", $sub_child_value ); // make a simple value

							$sql_col_row[] = $sub_child_key . " = " . "'" . $sub_child_value . "'";
					
							//error_log("SUB KEY:VALUE =  $sub_child_key:$sub_child_value");
						}

					} // inner sub-foreach 
					
					
					// TODO: make it so duplicate HANDLER_IDs don't overwrite but update and add instead!
					
					// insert new row into table or replace row with new data if $TABLE_INDEX is in the table already
					
					$sql_string = "INSERT INTO $MASTER_TABLE_NAME SET " . implode(', ', $sql_col_row ) . " ON DUPLICATE KEY UPDATE " . implode(', ', $sql_col_row );
													
					
					$oMySQL->ExecuteSQL( $sql_string );
				
					// error_log("SQL STRING: " . $sql_string );
					
	
		} // end inner if
	
	} // end inner foreach

} // end outer foreach
		

	
	
 * 
 **/




?>