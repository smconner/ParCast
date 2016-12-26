<?php

/**
 * EXAMPLE USAGE: /Applications/MAMP/bin/php5.3/bin/php EPA_API_feed_main.php
 * 
 * build and update a LANDFILLS big table from the EPA API FRS table
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
LANDFILLS_bigtable( $oMySQL );
	
	
// main function
function LANDFILLS_bigtable( $oMySQL ) {
	
	// what to name the new local FRS_LANDFILLS table
	$MASTER_TABLE_NAME = "FRS_MASTER";
	
	// the main column in the new FRS_LANDFILLS table to use as the table index
	$TABLE_INDEX = "REGISTRY_ID";
	
	// the root URL used to access the EPA's API
	$API_ROOT_STRING = "http://iaspub.epa.gov/enviro/efservice"; 

	
	$ROWS = 999; // total number of rows to process at a time, total rows = n+1, max n = 999
	
	$STATE = "WA"; // which state to populate the database with 
	
	/**
	 *  Aggregate a big prototype table called $MASTER_TABLE_NAME, using $TABLE_INDEX as the index
	 */
	
			//make_prototype_table( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "", $API_ROOT_STRING . "/FRS_FACILITY_SITE/STATE_CODE/WA/rows/1:1" );
	
			
	/**
	 *	Pull in all the records from the following EPA API tables, which are accessed by state postal code
	 *	TODO: populate the table for WA, OR, and CA
	 */

			//populate_bigtable_by_state( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX, "", $API_ROOT_STRING . "/FRS_FACILITY_SITE/STATE_CODE/", $ROWS, $STATE );
	
			
	/**
	 *	Uncomment to create/refresh LANDFILLS sub-table from FRS_MASTER
	 */
	
			//make_subtable_LANDFILLS( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX );
			
			


	// OPTIMIZE AND FLUSH the table after lots of changes
	$oMySQL->ExecuteSQL( "OPTIMIZE TABLE $MASTER_TABLE_NAME" );
	$oMySQL->ExecuteSQL( "FLUSH TABLE $MASTER_TABLE_NAME" );
	
				
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
 * Create/refresh RCRA TSD and RCRA CORRACTS sub-table from RCRAINFO_MASTER.
 * @param object $oMySQL
 * @param string $MASTER_TABLE_NAME The name of the master RCRA table to be pulling records from.
 * @param string $TABLE_INDEX The name of the column that holds the main index.
 */

function make_subtable_LANDFILLS( $oMySQL, $MASTER_TABLE_NAME, $TABLE_INDEX ) {
	
		// what we will call out sub-table
		$SUB_TABLE_NAME = "LANDFILLS";
	
		// delete table if it exists
		$oMySQL->ExecuteSQL( "DROP TABLE IF EXISTS $SUB_TABLE_NAME" );
		
		// make our long sql query string
		//$sql_string = 	"CREATE TABLE $SUB_TABLE_NAME SELECT * FROM `$MASTER_TABLE_NAME` WHERE ";
		//$sql_string .= 	" `PRIMARY_NAME` LIKE '%landfill%' OR `PRIMARY_NAME` LIKE '%solid waste%' OR";
		//$sql_string .= 	" `LOCATION_ADDRESS` LIKE '%landfill%' OR `LOCATION_ADDRESS` LIKE '%solid waste%' OR ";
		//$sql_string .= 	" `SUPPLEMENTAL_LOCATION` LIKE '%landfill%' OR `SUPPLEMENTAL_LOCATION` LIKE '%solid waste%'";
		
		// make our long sql query string
		//$sql_string = 	"CREATE TABLE $SUB_TABLE_NAME SELECT * FROM `$MASTER_TABLE_NAME` WHERE ";
		//$sql_string .= 	" `PRIMARY_NAME` LIKE '%landfill%' OR `PRIMARY_NAME` LIKE '%solid waste%' OR `PRIMARY_NAME` LIKE '%dump%' OR ";
		//$sql_string .= 	" `LOCATION_ADDRESS` LIKE '%landfill%' OR `LOCATION_ADDRESS` LIKE '%solid waste%' OR `LOCATION_ADDRESS` LIKE '%dump%' OR ";
		//$sql_string .= 	" `SUPPLEMENTAL_LOCATION` LIKE '%landfill%' OR `SUPPLEMENTAL_LOCATION` LIKE '%solid waste%' OR `SUPPLEMENTAL_LOCATION` LIKE '%dump%' ";
		
		// make our long sql query string
		$sql_string = 	"CREATE TABLE $SUB_TABLE_NAME SELECT * FROM `$MASTER_TABLE_NAME` WHERE ";
		$sql_string .= 	" `PRIMARY_NAME` LIKE '%landfill%' OR `PRIMARY_NAME` LIKE '%solid waste%' OR `PRIMARY_NAME` LIKE '%dump%' OR `PRIMARY_NAME` LIKE '%junkyard%' OR ";
		$sql_string .= 	" `LOCATION_ADDRESS` LIKE '%landfill%' OR `LOCATION_ADDRESS` LIKE '%solid waste%' OR `LOCATION_ADDRESS` LIKE '%dump%' OR `LOCATION_ADDRESS` LIKE '%junkyard%' OR ";
		$sql_string .= 	" `SUPPLEMENTAL_LOCATION` LIKE '%landfill%' OR `SUPPLEMENTAL_LOCATION` LIKE '%solid waste%' OR `SUPPLEMENTAL_LOCATION` LIKE '%dump%' OR `SUPPLEMENTAL_LOCATION` LIKE '%junkyard%'";
		
		
		error_log( "SQL STRING TO EXECUTE: $sql_string" );
		
		// create new table
		$oMySQL->ExecuteSQL( $sql_string );

		
		// add a primary index
		$oMySQL->ExecuteSQL( "ALTER TABLE $SUB_TABLE_NAME ADD UNIQUE INDEX($TABLE_INDEX)" );

		
		// alter the lat and lon comlumns from GEOCODE_LONGITUDE & GEOCODE_LATITUDE to "Latitude" and "Longitude"
		$oMySQL->ExecuteSQL( "ALTER TABLE $SUB_TABLE_NAME CHANGE GEOCODE_LONGITUDE Longitude decimal(11,6) NOT NULL" );
		$oMySQL->ExecuteSQL( "ALTER TABLE $SUB_TABLE_NAME CHANGE GEOCODE_LATITUDE Latitude decimal(11,6) NOT NULL" );
		
		
		// OPTIMIZE AND FLUSH the table after lots of changes
		$oMySQL->ExecuteSQL( "OPTIMIZE TABLE $SUB_TABLE_NAME" );
		$oMySQL->ExecuteSQL( "FLUSH TABLE $SUB_TABLE_NAME" );
		
		
		addGeometry( $oMySQL, $SUB_TABLE_NAME );
		
} // end function





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
		$oMySQL->ExecuteSQL( "CREATE TABLE $MASTER_TABLE_NAME ($TABLE_INDEX VARCHAR(14) NOT NULL)" );
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


?>