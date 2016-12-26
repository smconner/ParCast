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
	$TABLE_NAME = "RCRAINFO";
	
	// the main column in the new RCRA table to use as the table index
	$TABLE_INDEX = "HANDLER_ID";
	
	// the root URL used to access the EPA's API
	$API_ROOT_STRING = "http://iaspub.epa.gov/enviro/efservice"; 
	
	$ROWS = 100; // number of rows to process at a time
	
	$STATE = "WA"; // which state to populate the database with 
	
	// make a prototype table called RCRAINFO, of the API result from: RCR_HREPORT_UNIV
	//make_prototype_table( $oMySQL, $TABLE_NAME, $TABLE_INDEX, "", $API_ROOT_STRING . "/RCR_HREPORT_UNIV/LOCATION_STATE/WA/rows/1:1" );

	// add to the prototype above from the following tables
	//make_prototype_table( $oMySQL, $TABLE_NAME, $TABLE_INDEX, "", $API_ROOT_STRING . "/RCR_HHANDLER/HANDLER_ID/WAD058362336/rows/1:1" );
	//make_prototype_table( $oMySQL, $TABLE_NAME, $TABLE_INDEX, "RCR_PUNIT_DETAIL_", $API_ROOT_STRING . "/RCR_PUNIT_DETAIL/HANDLER_ID/WAD058362336/rows/1:1" );
	//make_prototype_table( $oMySQL, $TABLE_NAME, $TABLE_INDEX, "RCR_CA_AUTHORITY_", $API_ROOT_STRING . "/RCR_CA_AUTHORITY/HANDLER_ID/WAD058362336/rows/1:1" );
	
	// not needed according to Paul
	//make_prototype_table( $oMySQL, $TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING . "/RCR_PSERIES/HANDLER_ID/WAD058362336/rows/1:1" );

	// pull all the records from the tables that can be accessed by State
	// TODO: populate the table: RCRAINFO for WA, OR, and CA
	//populate_bigtable_by_state( $oMySQL, $TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING . "/RCR_HREPORT_UNIV/LOCATION_STATE/", $ROWS, $STATE );
	//populate_bigtable_by_state( $oMySQL, $TABLE_NAME, $TABLE_INDEX, $API_ROOT_STRING . "/RCR_HHANDLER/LOCATION_STATE/", $ROWS, $STATE );

		// take the above records and pull all the records from the EPA API tables accessed by HANDLER_ID
		populate_bigtable_by_HANDLER_ID($oMySQL, $TABLE_NAME, $TABLE_INDEX, "RCR_PUNIT_DETAIL_", $API_ROOT_STRING . "/RCR_PUNIT_DETAIL/HANDLER_ID/" , $ROWS);

	$oMySQL->ExecuteSQL( "ALTER TABLE $TABLE_NAME ADD INDEX($TABLE_INDEX)" );
	
}





// TODO: dealing with multiple results from RCR_PUNIT_DETAIL

// write function that will cycle throught the table and use the key value in HANDLER_ID 
// to grab the remaining data in the other EPA API tables and splice it into our bigtable

function populate_bigtable_by_HANDLER_ID( $oMySQL, $TABLE_NAME, $TABLE_INDEX, $COLUMN_PREFIX, $API_QUERY_STRING, $ROWS ) {
	
	error_log("Populating table: $TABLE_NAME by HANDLER_ID.");
	
	// maybe use: serialize($value) to store more than one set of instances in a single row element
	
	// test via this link, should be 6 results: http://iaspub.epa.gov/enviro/efservice/RCR_PUNIT_DETAIL/HANDLER_ID/OHD980683544/
	// TODO: add gross number of PA (punitive actions)
	// <RCR_PUNIT_DETAILList Count="17" Rows="1-1000" > => to array
	// TODO: add date range of effective dates,  as Earliest and Most Recent

	
	// grab all rows from the HANDLER_ID column
	$oMySQL->ExecuteSQL( "SELECT $TABLE_INDEX FROM $TABLE_NAME" );
	
	$table_index_array = $oMySQL->ArrayResults();
	
	//error_log( "RETURN ARRAY: " . print_r( $return_array, 1 ) );
	
	
	// for each element in the array of HANDLER_IDs create a API query string based on the HANDLER_ID and request the data
	foreach ( $table_index_array as $key => $value_array ) {
		
		foreach ( $value_array as $sub_key => $sub_value ) {
			
			
			error_log( "RETURN KEY:VALUE $sub_key : $sub_value" );
			
			//$API_QUERY_STRING_FQ = $API_QUERY_STRING . "$sub_value"; 
	
			$API_QUERY_STRING_FQ = "http://iaspub.epa.gov/enviro/efservice/RCR_PUNIT_DETAIL/HANDLER_ID/WAD009256819";
			
			$API_QUERY_RESULTS_ARRAY = get_data_from_url( $API_QUERY_STRING_FQ, true, true);

			error_log("API RESULTS: " . print_r($API_QUERY_RESULTS_ARRAY, 1) );
			
			
			
			// cycles through each element in the returned array
			foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
				
				foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
					
					$RCR_PUNIT_DETAIL_COUNT = 0;
					
					if ( $child_key == "Count" ) {
						
						$RCR_PUNIT_DETAIL_COUNT = $child_key;
						
						error_log( "RCR_PUNIT_DETAIL_COUNT: " . $RCR_PUNIT_DETAIL_COUNT );
						
					} else if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
						
			
					// needs to processes each row into the database
					

						$sql_row = array();
						$sql_col = array();
						
						foreach ( $child_value as $sub_child_key => $sub_child_value ) {
	
							
							// make a stack of the column names
							if ( !is_array($sub_child_key) ) {
								
								$sql_col[] = $COLUMN_PREFIX . $sub_child_key;
						
							} else {
								
								$sql_col[] = implode( "", $COLUMN_PREFIX . $sub_child_key );
								
							}
							
							// make a stack of the each row value corresponding to the column above
							if ( !is_array($sub_child_value) ) {
								
								$sql_row[] = "'" . $sub_child_value . "'";
						
							} else {
								
								$sql_row[] = "'" . implode( "", $sub_child_value ) . "'" ;
								
							}
							
							
						}
	
						// insert new row into table or replace row with new data if $TABLE_INDEX is in the table already
						$sql_string = "REPLACE INTO " .$TABLE_NAME. " (" . implode(', ', $sql_col) . ") VALUES (" . implode(', ', $sql_row) . ")";
						
						$oMySQL->ExecuteSQL( $sql_string );
					
						//error_log("SQL STRING: " . $sql_string );
					}
				}	
			}
			
			


		}
		
	}
	
	
	
	// take the returned data array and UPDATE the database under "COLUMN_PREFIX + sub_child_key"
	

	
	// http://iaspub.epa.gov/enviro/efservice/RCR_CA_AUTHORITY/HANDLER_ID/OHD980683544/rows/1:1
	// add gross number of CA (corrective actions)
	// add date range of effective dates, as Earliest and Most Recent
	
}








/**
 * Pull all the records from the EPA API and populate the table for WA, OR and CA.
 * @param object $oMySQL The MySQL object.
 * @param string $TABLE_NAME
 * @param string $TABLE_INDEX The name of the column used as the index.
 * @param string $API_QUERY_STRING The API query string without the row information.
 * @param integer $ROWS The number of rows to request from the API at a time. Max 1000.
 * @param string $STATE The state to populate the database with.
 */

function populate_bigtable_by_state( $oMySQL, $TABLE_NAME, $TABLE_INDEX, $API_QUERY_STRING, $ROWS, $STATE ) {
	
	error_log("Populating table: $TABLE_NAME with data from $STATE.");
	
	
	$empty_row_count = 0; // this keeps track of how many times the EPA API returns an empty result
	$current_row_index = 1; // this tracks which row the function is currently trying to process, starts on row 1
	
	// this loop grabs a number of rows in $ROWS
	while ( $empty_row_count < 1 ) { // execute until the API returns 3 empty results in a row
	
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
					
					// needs to processes each row into the database
					if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
						
						
						$sql_row = array();
						$sql_col = array();
						
						foreach ( $child_value as $sub_child_key => $sub_child_value ) {

							
							// make a stack of the column names
							if ( !is_array($sub_child_key) ) {
								
								$sql_col[] = $sub_child_key;
						
							} else {
								
								$sql_col[] = implode( "", $sub_child_key );
								
							}
							
							// make a stack of the each row value corresponding to the column above
							if ( !is_array($sub_child_value) ) {
								
								$sql_row[] = "'" . $sub_child_value . "'";
						
							} else {
								
								$sql_row[] = "'" . implode( "", $sub_child_value ) . "'" ;
								
							}
							
							
						}

						// insert new row into table or replace row with new data if $TABLE_INDEX is in the table already
						$sql_string = "REPLACE INTO " .$TABLE_NAME. " (" . implode(', ', $sql_col) . ") VALUES (" . implode(', ', $sql_row) . ")";
						
						$oMySQL->ExecuteSQL( $sql_string );
					
						//error_log("SQL STRING: " . $sql_string );

					}
				
				}
				
			}
		
		// if the EPA API returns an empty result
		} else {
			
			error_log( "Got an empty array in population of prototype table!" );
			//$empty_row_count++;
			
		}
		
		error_log("Current Row Index: " .$current_row_index );
		error_log("Empty row count: " .$empty_row_count );
		
		$empty_row_count++; // this will limit the while loop to a few iterations for testing
	}
	
}









/**
 * Gets a single prototype row and converts that in to a protptype table.
 * @param object $oMySQL The mysql object.
 * @param string $TABLE_NAME Identify the name you would like to give the prototype new table.
 * @param string $TABLE_INDEX Identify the column which will have unique values, which you would like to use as the table index.
 * @param string $COLUMN_PREFIX Some columns will want a unique prefix to track non-duplicate columns with duplicate names.
 * @param string $API_QUERY_STRING returned prototype array must be a single row i.e. 1:1, for the foreach loop to function correctly
 */

function make_prototype_table( $oMySQL, $TABLE_NAME, $TABLE_INDEX, $COLUMN_PREFIX, $API_QUERY_STRING ) {
	
	// test for table
	if ( !mysql_num_rows( mysql_query( "SHOW TABLES LIKE '".$TABLE_NAME."'" ) ) ) {

		// Creates the table: $TABLE_NAME if it does not exist and adds the column: HANDLER_ID
		$oMySQL->ExecuteSQL( "CREATE TABLE $TABLE_NAME ($TABLE_INDEX VARCHAR(12) NOT NULL)" );
		$oMySQL->ExecuteSQL( "ALTER TABLE $TABLE_NAME ADD UNIQUE INDEX($TABLE_INDEX)" );
		
		//error_log("Table $TABLE_NAME does not exist, creating table.");
		
	} else {
		
		//error_log("Table $TABLE_NAME already exists.");
		
	}
	
	

	$API_QUERY_RESULTS_ARRAY = get_data_from_url( $API_QUERY_STRING, true, true);
	
	if ( !empty( $API_QUERY_RESULTS_ARRAY ) ) {
	
		foreach ( $API_QUERY_RESULTS_ARRAY as $master_key => $master_value ) {
		
			foreach ( $master_value as $child_key => $child_value ) { // here we have access to the array elements to make column names
				
				if ( $child_key != "Count" && $child_key != "Rows" ) { // bypass the "Count" and "Rows" keys
					
					$child_key = trim($child_key);
					
					// test for columns
					if ( !mysql_num_rows( mysql_query( "SHOW COLUMNS FROM $TABLE_NAME LIKE '".$COLUMN_PREFIX . $child_key."'" ) ) ) {
						
						// error_log("Column $COLUMN_PREFIX$child_key does not exist, creating it");
						
						$oMySQL->ExecuteSQL( "ALTER TABLE $TABLE_NAME ADD COLUMN ($COLUMN_PREFIX$child_key TEXT NOT NULL)" );
												

					} else {
						
						// error_log("Column $COLUMN_PREFIX$child_key already exists");

					}

				}
				
			}
			
		}
	
	} else {
		
		error_log( "Got an empty array in making prototype table!" );
		
	}
	
}









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
 * Snippets:
 * 


	EXAMPLES: 
	
	http://iaspub.epa.gov/enviro/efservice/ <table_name> / <column_name> / <column_value> /rows/ <first_row> : <last_row>
	
	http://iaspub.epa.gov/enviro/efservice/multisystem/minLatitude/42.15981/maxLatitude/42.4629/minLongitude/-88.09867/maxLongitude/-87.80414/rows/1:1
	
	
	Unfortunately, we currently do not have a direct bounding box query against the FRS database tables, but we do have a bounding box query
	within the API that will query multiple Envirofacts program office databases that make up our MultiSystem query (http://www.epa.gov/enviro/facts/multisystem.html).  
	
	The results of this query will make use of data from FRS to provide facility location information, but the results will only be provided for facilities that
	report to AFS, ACRES, BRS, CERCLIS, PCS, RADInfo, RCRAInfo, or TRI. Descriptions of these program systems can be found at http://www.epa.gov/enviro/facts/qmr.html#efsearch.
	
	http://www.epa.gov/enviro/facts/ef_restful.html

	
	 * METHOD 1
	 * 
	 * THIS WILL RESULT IN LIVE SEARCHES WHICH WILL TAKE A LOT OF TIME AND NOT BE AVAILABLE 
	 * ALL THE TIME. A BETTER SOLUTION IS TO CREATE A LOCAL TABLE OF WA, OR AND CA BASED ON
	 * THIS
	 * 
	1. Search this > "/multisystem/minLatitude/$padded_lat_south/maxLatitude/$padded_lat_north/minLongitude/$padded_lng_west/maxLongitude/$padded_lng_east/rows/1:1";
	2. Then extract this:
	
		<FacilityRegistryIdentifier>110018850894</FacilityRegistryIdentifier>
		  <LatitudeMeasure>47.640166</LatitudeMeasure>
		  <LongitudeMeasure>-122.129746</LongitudeMeasure>
		  <FacilitySiteName>MICROSOFT CORPORATION</FacilitySiteName>
		  <LocationAddressText>1 MICROSOFT WAY BLDG 26 N PARKING LOT</LocationAddressText>
		  <LocalityName>REDMOND</LocalityName>
		  <CountyName>KING</CountyName>
		  <StateUSPSCode>WA</StateUSPSCode>
		  <LocationZIPCode>98052</LocationZIPCode>

	 3. Use this: 110018850894 
	 to search this: "/FRS_INTEREST/REGISTRY_ID/110005377207/"; 
	 for this:  
	 			<PGM_SYS_ACRNM>RCRAINFO</PGM_SYS_ACRNM>
  				<PGM_SYS_ID>WAD988508834</PGM_SYS_ID>
	 4. then search each of these:
			"/RCR_HREPORT_UNIV/HANDLER_ID/WAD058362336/rows/1:1";
			"/RCR_HHANDLER/HANDLER_ID/WAD058362336/rows/1:1";
			"/RCR_CA_AUTHORITY/HANDLER_ID/WAD058362336/rows/1:1";
			"/RCR_PSERIES/HANDLER_ID/WAD058362336/rows/1:1";
		and extract the information we want

	
	// THESE SHOULD CONTAIN A LOT OF THE DATA I NEED:
	// "/RCR_HREPORT_UNIV/HANDLER_ID/WAD058362336/ROWS/1:1";
	// "/RCR_HHANDLER/HANDLER_ID/WAD058362336/ROWS/1:1";
	// "/RCR_CA_AUTHORITY/HANDLER_ID/WAD058362336/ROWS/1:1";
	// "/RCR_PSERIES/HANDLER_ID/WAD058362336/ROWS/1:1";
	
	// THIS MIGHT BE EXTRA, WAITING ON ANSWER FROM EPA
	// RCR_PUNIT_DETAIL > RCR_LU_LEGAL_OPERATING_STATUS
	
	// "/RCR_PUNIT_DETAIL/HANDLER_ID/WAD058362336/ROWS/1:1";
	// "/RCR_PUNIT/HANDLER_ID/WAD058362336/ROWS/1:1";
	// "/RCR_HBASIC/HANDLER_ID/WAD058362336/ROWS/1:1";
	// "/RCR_HBASIC/HANDLER_ID/WAD058362336/STATE/WA/ROWS/1:1";
	
	// define a site, my house
	$lng_site = -122.35308;
	$lat_site = 47.64743;
	
	// define the search radius in miles
	$user_max_search_radius = 1;

	// define our bounding box so we can query the mysql databases for just the results we want
	$lng_west = $lng_site - $user_max_search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$lng_east = $lng_site + $user_max_search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$lat_south = $lat_site - ( $user_max_search_radius / 69.2 );
	$lat_north = $lat_site + ( $user_max_search_radius / 69.2 );
	
	// give the bounding box some padding
	$padded_lng_west = $lng_west - .0005;
	$padded_lng_east = $lng_east + .0005;
	$padded_lat_south = $lat_south - .0005;
	$padded_lat_north = $lat_north + .0005;
	
	
	
	USED_OIL NNNNNNN =>
			  <USED_OIL_TRANSPORTER>N</USED_OIL_TRANSPORTER>
			  <USED_OIL_TRANSFER_FACILITY>N</USED_OIL_TRANSFER_FACILITY>
			  <USED_OIL_PROCESSOR>N</USED_OIL_PROCESSOR>
			  <USED_OIL_REFINER>N</USED_OIL_REFINER>
			  <USED_OIL_BURNER>N</USED_OIL_BURNER>
			  <USED_OIL_MARKET_BURNER>N</USED_OIL_MARKET_BURNER>
			  <USED_OIL_SPEC_MARKETER>N</USED_OIL_SPEC_MARKETER>

	
	
	
 * 
 **/


?>