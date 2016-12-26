<?php

/**
 * EXAMPLE USAGE: /Applications/MAMP/bin/php5.3/bin/php geocoder_main.php
 * 
 * 
EXAMPLE query: http://nominatim.openstreetmap.org/search?q=1370 Willow Road, Menlo Park, CA 94025&format=xml&polygon=0&countrycodes=US&limit=1&email=smconner@gmail.com

&format=json (format options are XML | JSON | HTML - JSON is about half the size of XML)
&polygon=0 (do not return polygon information)
&countrycodes=US (only searches the US for matches)
&limit=1 (limits query results to one match)
&email=smconner@gmail.com (must include a valid email for large numbers of queries)

OTHER options:
&addressdetails=1 (breaks down the returned address into xml tagged fields)

EXAMPLE OF VALID ADDRESS FORMATS:

    1370 Willow Road, CA
    1370 Willow Road, California
    1370 Willow Road, Menlo Park
    1370 Willow Road, Menlo Park, CA
    1370 Willow Road, Menlo Park, CA 94025
    1370 Willow Road, Menlo Park, CA 94025-1516
    1370 Willow Road, Menlo Park, California
    1370 Willow Road, Menlo Park, US
    1370 Willow Road, Menlo Park, USA
    1370 Willow Road, Menlo Park, united states
    1370 Willow Road, Menlo Park, California, US
    1370 Willow Road, Menlo Park, California, USA
    1370 Willow Road, 94025
    1370 Willow Road, 2nd Floor, Menlo Park, CA 94025
    1370 Willow Road, 2nd Floor, Menlo Park, CA 94025-1516
    Willow Road & Hamilton Ave, Menlo Park, CA 94025
    Willow Road & Hamilton Ave, Menlo Park, CA 94025-1516
    Willow Road and Hamilton Ave, Menlo Park, CA 94025
    Willow Road and Hamilton Ave, Menlo Park, CA 94025-1516

**/

require_once 'class.MySQL.php';

define( "GEO_MYSQL_HOST", "localhost:8889" );
define( "GEO_MYSQL_USER", "root" );
define( "GEO_MYSQL_PASS", "root" );
define( "GEO_MYSQL_DB_NAME", "PARCAST_DB" );

// identify the table we want to process
$GEO_MYSQL_TABLE_NAME = "CERCLIS_TEST";

// identify the column names for the table define by: $GEO_MYSQL_TABLE_NAME
$GEO_ADDRESS 		= "Address" ;
$GEO_ADDRESS_CITY 	= "City";
$GEO_ADDRESS_STATE 	= "State" ;
$GEO_ADDRESS_ZIP 	= "ZIP_Code" ;
$GEO_LAT_COLUMN		= "Latitude";
$GEO_LON_COLUMN		= "Longitude";

	// Open a connection to the MySQL server
	$geo_database_connection = mysql_connect( GEO_MYSQL_HOST, GEO_MYSQL_USER, GEO_MYSQL_PASS );
				
	// Set the active MySQL database
	$geo_database_selected = mysql_select_db( GEO_MYSQL_DB_NAME, $geo_database_connection );

	// instantiate the MySQL object from: class.MySQL.php
	$oMySQL = new MySQL();

	
/** 
 * Simply adds an incremented column to the table being geocoded called GEO_ID
 * @param object $oMySQL
 * @param string $GEO_MYSQL_TABLE_NAME
 */
	
function geo_add_row_id( $oMySQL, $GEO_MYSQL_TABLE_NAME ) {
	
	$sql = "ALTER TABLE $GEO_MYSQL_TABLE_NAME ADD `GEO_ID` INT(32) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`GEO_ID`)";
	
	$result = $oMySQL->ExecuteSQL( $sql );
	
	//error_log("ADD RESULT: ". $result);
	
	return $result; // 1 for sucess, null for fail
	
}

/** 
 * Simply drops the column called GEO_ID
 * @param object $oMySQL
 * @param string $GEO_MYSQL_TABLE_NAME
 */
	
function geo_del_row_id ( $oMySQL, $GEO_MYSQL_TABLE_NAME ) {
	
	$sql = "ALTER TABLE $GEO_MYSQL_TABLE_NAME DROP `GEO_ID`";
	
	$result = $oMySQL->ExecuteSQL( $sql );
	
	//error_log("DEL RESULT: ". $result);
	
	return $result; // 1 for sucess, null for fail
	
}
	
/**
 * Checks a MySQL table for the existance of a Longitude and/or Latitude columns
 * @param object $oMySQL
 * @param sting $GEO_MYSQL_TABLE_NAME
 * @param sting $GEO_ADDRESS
 * @param sting $GEO_ADDRESS_CITY
 * @param sting $GEO_ADDRESS_STATE
 * @param sting $GEO_ADDRESS_ZIP
 * @param sting $GEO_LON_COLUMN
 * @param sting $GEO_LAT_COLUMN
 * @TODO needs proper commenting
 */	
	
function geo_add_lat_lon_sql_columns( $oMySQL, $GEO_MYSQL_TABLE_NAME,$GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ){
	
	$oMySQL->ExecuteSQL( "SHOW COLUMNS FROM $GEO_MYSQL_TABLE_NAME LIKE '$GEO_LON_COLUMN'" );
	
		$COLUMN_INFO_LON = $oMySQL->ArrayResults();
	
	$oMySQL->ExecuteSQL( "SHOW COLUMNS FROM $GEO_MYSQL_TABLE_NAME LIKE '$GEO_LAT_COLUMN'" );
	
		$COLUMN_INFO_LAT = $oMySQL->ArrayResults();
	
	if ( $COLUMN_INFO_LON != NULL || $COLUMN_INFO_LAT != NULL ) {
		
		//error_log("SQL Returned: " . print_r( $COLUMN_INFO_LON, 1 ) );
		//error_log("SQL Returned: " . print_r( $COLUMN_INFO_LAT, 1 ) );
		
		return "lat and lon columns not added";
		
	} else {
		
		//error_log("SQL Returned NULL:NULL ...adding columns.");
		
		//$oMySQL->ExecuteSQL( "ALTER TABLE $GEO_MYSQL_TABLE_NAME DROP COLUMN $GEO_LON_COLUMN" );
    	//$oMySQL->ExecuteSQL( "ALTER TABLE $GEO_MYSQL_TABLE_NAME DROP COLUMN $GEO_LAT_COLUMN" );
    
    	$oMySQL->ExecuteSQL( "ALTER TABLE $GEO_MYSQL_TABLE_NAME ADD $GEO_LON_COLUMN DECIMAL(11,6) NOT NULL AFTER $GEO_ADDRESS_ZIP" );
    	$oMySQL->ExecuteSQL( "ALTER TABLE $GEO_MYSQL_TABLE_NAME ADD $GEO_LAT_COLUMN DECIMAL(11,6) NOT NULL AFTER $GEO_ADDRESS_ZIP" );
    	
    	return "lat and lon columns added";
		
	}
		
}

	


/**
 * 
 * Gets the data from a URL
 * @param sting $url
 * @return string $data
 * @TODO needs proper commenting
 */				

function geo_get_data( $url ) {
	
	$url = preg_replace( "/\s+/", "+", $url );
	
	$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );	# URL to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); # return results into a variable
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 ); # max time in seconds to allow for execution
			$data = curl_exec( $ch ); # run curl
	curl_close($ch);
	
	sleep( 1 );
	
	error_log("API ENCODED URL: " . $url );
	
	return $data;

}


/**
 * Attempts to get geolocations from the OSM Geolocation API
 * @param object $oMySQL
 * @param string $GEO_MYSQL_TABLE_NAME
 * @param string $GEO_ADDRESS
 * @param string $GEO_ADDRESS_CITY
 * @param string $GEO_ADDRESS_STATE
 * @param string $GEO_ADDRESS_ZIP
 * @param string $GEO_LON_COLUMN
 * @param string $GEO_LAT_COLUMN
 * @TODO needs proper commenting
 */

function geo_osm( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ) {
	
	$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS,$GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME WHERE 1 LIMIT 20" );
	
	$MYSQL_ALL_ROWS_ARRAY = $oMySQL->ArrayResults();

	
	$PREV_OSM_API_QUERY_STRING = null;
	
	$OSM_API_QUERY_RESULTS = null;
		
	foreach ( $MYSQL_ALL_ROWS_ARRAY as $row_number_key => $row_array_value ) {
		
		
		$OSM_API_ROOT_STRING = "http://nominatim.openstreetmap.org/search?q=";
			
		$OSM_API_OPTIONS_STRING = "&format=json&polygon=0&countrycodes=US&limit=1&email=smconner@gmail.com";
			
			$OSM_API_ADDRESS_STRING = "$row_array_value[$GEO_ADDRESS], $row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]";
							
				$OSM_API_QUERY_STRING = $OSM_API_ROOT_STRING . $OSM_API_ADDRESS_STRING . $OSM_API_OPTIONS_STRING;
		
		//TODO: Add NULL capability i.e.  || $row_array_value[$GEO_LAT_COLUMN] == NULL || $row_array_value[$GEO_LON_COLUMN] == NULL
			
		// if this row has either gelocation columns = 0 then geocode it
		if ( $row_array_value[$GEO_LAT_COLUMN] == 0 || $row_array_value[$GEO_LON_COLUMN] == 0 || $row_array_value[$GEO_LAT_COLUMN] == NULL || $row_array_value[$GEO_LON_COLUMN] == NULL || !is_numeric( $row_array_value[$GEO_LAT_COLUMN] ) || !is_numeric( $row_array_value[$GEO_LON_COLUMN] ) ) {
			
				// if previous OSM address is NOT equal to current OSM address then
				if ( $PREV_OSM_API_QUERY_STRING != $OSM_API_QUERY_STRING ) {
	
					$OSM_API_QUERY_RESULTS = geo_get_data( $OSM_API_QUERY_STRING );
					
				// if OSM query address IS the same as the last, insert the last geolocation into the current row 
				} else if ( $PREV_OSM_API_QUERY_STRING == $OSM_API_QUERY_STRING ) {

					error_log(" DID NOT QUERY OSM API FOR THIS ROW BECAUSE IT IS EQUAL TO PREVIOUS ROW" );
					
				} else {
					
					error_log("OSM - SOMETHING ELSE HAPPENED");
					
				}
					
					// if json results from OSM are NOT empty "[]"
					if ( $OSM_API_QUERY_RESULTS != "[]" ) {
	
						$OSM_API_QUERY_RESULTS_ARRAY = json_decode($OSM_API_QUERY_RESULTS, true);
						error_log( "OSM_API_QUERY_RESULTS: " . print_r( $OSM_API_QUERY_RESULTS_ARRAY["0"]["lat"], 1) . ":" . print_r( $OSM_API_QUERY_RESULTS_ARRAY["0"]["lon"], 1) );
						
						$GEO_ID = $row_number_key + 1;
						$GEO_LAT = $OSM_API_QUERY_RESULTS_ARRAY["0"]["lat"];
						$GEO_LON = $OSM_API_QUERY_RESULTS_ARRAY["0"]["lon"];
						
						$OSM_GEO_LAT_LON = "UPDATE $GEO_MYSQL_TABLE_NAME SET Latitude = $GEO_LAT, Longitude = $GEO_LON WHERE GEO_ID = $GEO_ID";
						
						$oMySQL->ExecuteSQL( $OSM_GEO_LAT_LON );
						
					// if json results are empty "[]"
					} else if ( $OSM_API_QUERY_RESULTS == "[]" ) {
					
						error_log("OSM API QUERY RESULTS WERE EMPTY"); 
						
					} else {
						
						error_log("OSM API - SOMETHING ELSE HAPPENED"); 
						
					}
					
		// if this row has non-zero values in both geolocation columns skip it and go on to the next row
		} else {
			
			error_log( " DID NOT QUERY OSM API BECAUSE LAT AND LON ARE NON-ZERO: ROW# $row_number_key is != 0 : $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LON_COLUMN]" );
			
		}
		
		$PREV_OSM_API_QUERY_STRING = $OSM_API_QUERY_STRING;
		
	}
}


/**
 * Attempts to get geolocations from the GOOGLE Geolocation API
 * @param object $oMySQL
 * @param string $GEO_MYSQL_TABLE_NAME
 * @param string $GEO_ADDRESS
 * @param string $GEO_ADDRESS_CITY
 * @param string $GEO_ADDRESS_STATE
 * @param string $GEO_ADDRESS_ZIP
 * @param string $GEO_LON_COLUMN
 * @param string $GEO_LAT_COLUMN
 * @TODO needs proper commenting
 */

function geo_google( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ) {
	
	$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS,$GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME WHERE 1 LIMIT 20" );
	
	$MYSQL_ALL_ROWS_ARRAY = $oMySQL->ArrayResults();

	
	$PREV_GOOGLE_API_QUERY_STRING = null;
	
	$GOOGLE_API_QUERY_RESULTS = null;
		
	
	foreach ( $MYSQL_ALL_ROWS_ARRAY as $row_number_key => $row_array_value ) {
		
		
		$GOOGLE_API_ROOT_STRING = "http://maps.googleapis.com/maps/api/geocode/json?address=";
		
		$GOOGLE_API_OPTIONS_STRING = "&sensor=false";
		
			$GOOGLE_API_ADDRESS_STRING = "$row_array_value[$GEO_ADDRESS], $row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]";
							
				$GOOGLE_API_QUERY_STRING = $GOOGLE_API_ROOT_STRING . $GOOGLE_API_ADDRESS_STRING . $GOOGLE_API_OPTIONS_STRING;

		
		// if this row has either gelocation columns = 0 then geocode it
		if ( $row_array_value[$GEO_LAT_COLUMN] == 0 || $row_array_value[$GEO_LON_COLUMN] == 0 || $row_array_value[$GEO_LAT_COLUMN] == NULL || $row_array_value[$GEO_LON_COLUMN] == NULL || !is_numeric( $row_array_value[$GEO_LAT_COLUMN] ) || !is_numeric( $row_array_value[$GEO_LON_COLUMN] ) ) {
			
				// if previous GOOGLE address is NOT equal to current GOOGLE address
				if ( $PREV_GOOGLE_API_QUERY_STRING != $GOOGLE_API_QUERY_STRING ) {
	
					$GOOGLE_API_QUERY_RESULTS = geo_get_data( $GOOGLE_API_QUERY_STRING );
					
				// if GOOGLE query address IS the same as the last, insert the last geolocation into the current row 
				} else if ( $PREV_GOOGLE_API_QUERY_STRING == $GOOGLE_API_QUERY_STRING ) {

					error_log(" DID NOT QUERY GOOGLE API FOR THIS ROW BECAUSE IT IS EQUAL TO PREVIOUS ROW" );
					
				} else {
					
					error_log("GOOGLE SOMETHING ELSE HAPPENED");
					
				}
					
					$GOOGLE_API_QUERY_RESULTS_ARRAY = json_decode($GOOGLE_API_QUERY_RESULTS, true);
						
					if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "OK" ) {

						error_log("GOOGLE_API_QUERY_RESULTS:" . print_r($GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lat"], 1 ) .":". print_r($GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lng"], 1 ));
						
						$GEO_ID = $row_number_key + 1;
						$GEO_LAT = $GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lat"];
						$GEO_LON = $GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lng"];
						
						$GOOGLE_GEO_LAT_LON = "UPDATE $GEO_MYSQL_TABLE_NAME SET Latitude = $GEO_LAT, Longitude = $GEO_LON WHERE GEO_ID = $GEO_ID";
						
						$oMySQL->ExecuteSQL( $GOOGLE_GEO_LAT_LON );
						
					// non-OK results
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "ZERO_RESULTS" ) {
																				
						error_log("GOOGLE API QUERY RESULTS: ZERO_RESULTS: indicates that the geocode was successful but returned no results. This may occur if the geocode was passed a non-existent address or a latlng in a remote location."); 
						
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "OVER_QUERY_LIMIT" ) {
						
						error_log("GOOGLE API QUERY RESULTS: OVER_QUERY_LIMIT: indicates that you are over your quota."); 
						
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "REQUEST_DENIED" ) {
						
						error_log("GOOGLE API QUERY RESULTS: REQUEST_DENIED: indicates that your request was denied, generally because of lack of a sensor parameter."); 
						
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "INVALID_REQUEST" ) {
						
						error_log("GOOGLE API QUERY RESULTS: INVALID_REQUEST: generally indicates that the query (address or latlng) is missing."); 
						
					} else {
						
						error_log("GOOGLE API QUERY RESULTS: SOMETHING ELSE HAPPENED");
					}
						
				
		// if this row has non-zero values in both geolocation columns skip it and go on to the next row
		} else {
			
			error_log( " DID NOT QUERY GOOGLE API BECAUSE LAT AND LON ARE NON-ZERO: ROW# $row_number_key is != 0 : $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LON_COLUMN]" );
			
		}
		
		$PREV_GOOGLE_API_QUERY_STRING = $GOOGLE_API_QUERY_STRING;
		
	}

}


/**
 * Attempts a last ditch effort to get geolocations from the OSM Geolocation API based on "City, State ZIP CODE" alone
 * @param unknown_type $oMySQL
 * @param unknown_type $GEO_MYSQL_TABLE_NAME
 * @param string $GEO_ADDRESS_CITY
 * @param string $GEO_ADDRESS_STATE
 * @param unknown_type $GEO_ADDRESS_ZIP
 * @param unknown_type $GEO_LON_COLUMN
 * @param unknown_type $GEO_LAT_COLUMN
 */

function geo_last_resort_osm( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ) {
	
	$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME" );
		
	$MYSQL_ALL_ROWS_ARRAY = $oMySQL->ArrayResults();

	
	$PREV_OSM_API_QUERY_STRING = null;
	
	$OSM_API_QUERY_RESULTS = null;
		
	foreach ( $MYSQL_ALL_ROWS_ARRAY as $row_number_key => $row_array_value ) {
		
		
		$OSM_API_ROOT_STRING = "http://nominatim.openstreetmap.org/search?q=";
			
		$OSM_API_OPTIONS_STRING = "&format=json&polygon=0&countrycodes=US&limit=1&email=smconner@gmail.com";
			
			$OSM_API_ADDRESS_STRING = "$row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]";
							
				$OSM_API_QUERY_STRING = $OSM_API_ROOT_STRING . $OSM_API_ADDRESS_STRING . $OSM_API_OPTIONS_STRING;
		
		
		// if this row has either gelocation columns = 0 then geocode it
		if ( $row_array_value[$GEO_LAT_COLUMN] == 0 || $row_array_value[$GEO_LON_COLUMN] == 0 || $row_array_value[$GEO_LAT_COLUMN] > 50 || $row_array_value[$GEO_LAT_COLUMN] < 24 || $row_array_value[$GEO_LON_COLUMN] < -125 || $row_array_value[$GEO_LON_COLUMN] > -67 || $row_array_value[$GEO_LAT_COLUMN] == NULL || $row_array_value[$GEO_LON_COLUMN] == NULL ) {
			
				// if previous OSM address is NOT equal to current OSM address then
				if ( $PREV_OSM_API_QUERY_STRING != $OSM_API_QUERY_STRING ) {
	
					$OSM_API_QUERY_RESULTS = geo_get_data( $OSM_API_QUERY_STRING );
					
				// if OSM query address IS the same as the last, insert the last geolocation into the current row 
				} else if ( $PREV_OSM_API_QUERY_STRING == $OSM_API_QUERY_STRING ) {

					error_log(" DID NOT QUERY OSM API FOR THIS ROW BECAUSE IT IS EQUAL TO PREVIOUS ROW" );
					
				} else {
					
					error_log("OSM - SOMETHING ELSE HAPPENED");
					
				}
					
					// if json results from OSM are NOT empty "[]"
					if ( $OSM_API_QUERY_RESULTS != "[]" ) {
	
						$OSM_API_QUERY_RESULTS_ARRAY = json_decode($OSM_API_QUERY_RESULTS, true);
						error_log( "OSM_API_QUERY_RESULTS: " . print_r( $OSM_API_QUERY_RESULTS_ARRAY["0"]["lat"], 1) . ":" . print_r( $OSM_API_QUERY_RESULTS_ARRAY["0"]["lon"], 1) );
						
						$GEO_ID = $row_number_key + 1;
						$GEO_LAT = $OSM_API_QUERY_RESULTS_ARRAY["0"]["lat"];
						$GEO_LON = $OSM_API_QUERY_RESULTS_ARRAY["0"]["lon"];
						
						$OSM_GEO_LAT_LON = "UPDATE $GEO_MYSQL_TABLE_NAME SET Latitude = $GEO_LAT, Longitude = $GEO_LON WHERE GEO_ID = $GEO_ID";
						
						$oMySQL->ExecuteSQL( $OSM_GEO_LAT_LON );
						
					// if json results are empty "[]"
					} else if ( $OSM_API_QUERY_RESULTS == "[]" ) {
					
						error_log("OSM API QUERY RESULTS WERE EMPTY"); 
						
					} else {
						
						error_log("OSM API - SOMETHING ELSE HAPPENED"); 
						
					}
					
		// if this row has non-zero values in both geolocation columns skip it and go on to the next row
		} else {
			
			error_log( " DID NOT QUERY OSM API BECAUSE LAT AND LON ARE NON-ZERO: ROW# $row_number_key is != 0 : $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LON_COLUMN]" );
			
		}
		
		$PREV_OSM_API_QUERY_STRING = $OSM_API_QUERY_STRING;
		
	}
	
	
}


/**
 * Attempts a last ditch effort to get geolocations from the GOOGLE Geolocation API based on "City, State ZIP CODE" alone
 * @param object $oMySQL
 * @param string $GEO_MYSQL_TABLE_NAME
 * @param string $GEO_ADDRESS_CITY
 * @param string $GEO_ADDRESS_STATE
 * @param string $GEO_ADDRESS_ZIP
 * @param string $GEO_LON_COLUMN
 * @param string $GEO_LAT_COLUMN
 * @TODO needs proper commenting
 */

function geo_last_resort_google( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ) {
	
	$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME" );
	
	$MYSQL_ALL_ROWS_ARRAY = $oMySQL->ArrayResults();

	
	$PREV_GOOGLE_API_QUERY_STRING = null;
	
	$GOOGLE_API_QUERY_RESULTS = null;
		
	
	foreach ( $MYSQL_ALL_ROWS_ARRAY as $row_number_key => $row_array_value ) {
		
		
		$GOOGLE_API_ROOT_STRING = "http://maps.googleapis.com/maps/api/geocode/json?address=";
		
		$GOOGLE_API_OPTIONS_STRING = "&sensor=false";
		
			$GOOGLE_API_ADDRESS_STRING = "$row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]";
							
				$GOOGLE_API_QUERY_STRING = $GOOGLE_API_ROOT_STRING . $GOOGLE_API_ADDRESS_STRING . $GOOGLE_API_OPTIONS_STRING;

		
		// if this row has either gelocation columns = 0 then geocode it
		if ( $row_array_value[$GEO_LAT_COLUMN] == 0 || $row_array_value[$GEO_LON_COLUMN] == 0 || $row_array_value[$GEO_LAT_COLUMN] > 50 || $row_array_value[$GEO_LAT_COLUMN] < 24 || $row_array_value[$GEO_LON_COLUMN] < -125 || $row_array_value[$GEO_LON_COLUMN] > -67 || $row_array_value[$GEO_LAT_COLUMN] == NULL || $row_array_value[$GEO_LON_COLUMN] == NULL ) {

				// if previous GOOGLE address is NOT equal to current GOOGLE address
				if ( $PREV_GOOGLE_API_QUERY_STRING != $GOOGLE_API_QUERY_STRING ) {
	
					$GOOGLE_API_QUERY_RESULTS = geo_get_data( $GOOGLE_API_QUERY_STRING );
					
				// if GOOGLE query address IS the same as the last, insert the last geolocation into the current row 
				} else if ( $PREV_GOOGLE_API_QUERY_STRING == $GOOGLE_API_QUERY_STRING ) {

					error_log(" DID NOT QUERY GOOGLE API FOR THIS ROW BECAUSE IT IS EQUAL TO PREVIOUS ROW" );
					
				} else {
					
					error_log("GOOGLE SOMETHING ELSE HAPPENED");
					
				}
					
					$GOOGLE_API_QUERY_RESULTS_ARRAY = json_decode($GOOGLE_API_QUERY_RESULTS, true);
						
					if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "OK" ) {

						error_log("GOOGLE_API_QUERY_RESULTS:" . print_r($GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lat"], 1 ) .":". print_r($GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lng"], 1 ));
						
						$GEO_ID = $row_number_key + 1;
						$GEO_LAT = $GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lat"];
						$GEO_LON = $GOOGLE_API_QUERY_RESULTS_ARRAY["results"]["0"]["geometry"]["location"]["lng"];
						
						$GOOGLE_GEO_LAT_LON = "UPDATE $GEO_MYSQL_TABLE_NAME SET Latitude = $GEO_LAT, Longitude = $GEO_LON WHERE GEO_ID = $GEO_ID";
						
						$oMySQL->ExecuteSQL( $GOOGLE_GEO_LAT_LON );
						
					// non-OK results
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "ZERO_RESULTS" ) {
																				
						error_log("GOOGLE API QUERY RESULTS: ZERO_RESULTS: indicates that the geocode was successful but returned no results. This may occur if the geocode was passed a non-existent address or a latlng in a remote location."); 
						
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "OVER_QUERY_LIMIT" ) {
						
						error_log("GOOGLE API QUERY RESULTS: OVER_QUERY_LIMIT: indicates that you are over your quota."); 
						
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "REQUEST_DENIED" ) {
						
						error_log("GOOGLE API QUERY RESULTS: REQUEST_DENIED: indicates that your request was denied, generally because of lack of a sensor parameter."); 
						
					} else if ( $GOOGLE_API_QUERY_RESULTS_ARRAY["status"] == "INVALID_REQUEST" ) {
						
						error_log("GOOGLE API QUERY RESULTS: INVALID_REQUEST: generally indicates that the query (address or latlng) is missing."); 
						
					} else {
						
						error_log("GOOGLE API QUERY RESULTS: SOMETHING ELSE HAPPENED");
					}
						
				
		// if this row has non-zero values in both geolocation columns skip it and go on to the next row
		} else {
			
			error_log( " DID NOT QUERY GOOGLE API BECAUSE LAT AND LON ARE NON-ZERO: ROW# $row_number_key is != 0 : $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LON_COLUMN]" );
			
		}
		
		$PREV_GOOGLE_API_QUERY_STRING = $GOOGLE_API_QUERY_STRING;
		
	}

}




geo_add_row_id( $oMySQL, $GEO_MYSQL_TABLE_NAME );

geo_add_lat_lon_sql_columns( $oMySQL, $GEO_MYSQL_TABLE_NAME,$GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );

geo_osm( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );

geo_google( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );

//geo_last_resort_osm( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );

//geo_last_resort_google( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );

geo_del_row_id( $oMySQL, $GEO_MYSQL_TABLE_NAME );


?>