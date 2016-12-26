<?php

/**
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
$GEO_MYSQL_TABLE_NAME = "CERCLISNFRAP_TEST";

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
 * 
 * Checks a MySQL table for the existance of a Longitude and/or Latitude columns
 * @param object $oMySQL
 * @param sting $GEO_MYSQL_TABLE_NAME
 **/
	
function geo_add_sql_tables( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ){
	
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
 * (i.e. $row_number_key => $row_array_value)
 *  [4358] => Array
        (
        	(i.e. $column_key => $column_value)
            [Address] => 11600 BLOCK OF NORTH MARKET ST.
            [City] => MEAD
            [State] => WA
            [ZIP_Code] => 99021
            [Latitude] => 0.000000
            [Longitude] => 0.000000
        )
 * 
 * Enter description here ...
 * @param object $oMySQL
 * @param string $GEO_MYSQL_TABLE_NAME
 * @param string $GEO_ADDRESS
 * @param string $GEO_ADDRESS_CITY
 * @param string $GEO_ADDRESS_STATE
 * @param string $GEO_ADDRESS_ZIP
 */

function geo_return_sql_rows( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN ) {
	
	//$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS,$GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME" );
	$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS,$GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME WHERE 1 LIMIT 1" );
	
		$MYSQL_ALL_ROWS_ARRAY = $oMySQL->ArrayResults();
		// error_log("MYSQL RESULTS: " . print_r($MYSQL_ROWS_ARRAY,1) );
		
		foreach ( $MYSQL_ALL_ROWS_ARRAY as $row_number_key => $row_array_value ) {
			
			//foreach ( $row_array_value as  $column_key => $column_value ) {}
			
			if ( $row_array_value[$GEO_LAT_COLUMN] == 0 || $row_array_value[$GEO_LON_COLUMN] == 0 ) {
				
				//error_log( "LAT:LON COLUMN #$row_number_key is == 0  $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LAT_COLUMN]" );
				//error_log( "$row_array_value[$GEO_ADDRESS], $row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]" );
				
				$OSM_API_ROOT_STRING = "http://nominatim.openstreetmap.org/search?q=";
				$OSM_API_OPTIONS_STRING = "&format=json&polygon=0&countrycodes=US&limit=1&email=smconner@gmail.com";
				
				$API_ADDRESS_STRING = "$row_array_value[$GEO_ADDRESS], $row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]";
				
				$API_QUERY_STRING = $OSM_API_ROOT_STRING . $API_ADDRESS_STRING . $OSM_API_OPTIONS_STRING ;
				
				$WGET_COMMAND_STRING = "/usr/bin/wget $API_QUERY_STRING";
				
				error_log( $WGET_COMMAND_STRING );

				//exec( $WGET_COMMAND_STRING, $API_QUERY_RESULTS = array() );
				
				
				
				//$API_QUERY_RESULTS = file_get_contents( $API_QUERY_STRING );
				
				//error_log( print_r($API_QUERY_RESULTS) );
				
				sleep( 1 );
				
			} else {
				
				//error_log( "LAT:LON COLUMN #$row_number_key is != 0  $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LAT_COLUMN]" );
				
			}
			
		}
}



 // urlencode()

// file_get_contents($filename)

$return_result = geo_add_sql_tables(  $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );
error_log("geo_add_sql_tables returned: $return_result");

geo_return_sql_rows( $oMySQL, $GEO_MYSQL_TABLE_NAME, $GEO_ADDRESS, $GEO_ADDRESS_CITY, $GEO_ADDRESS_STATE, $GEO_ADDRESS_ZIP, $GEO_LON_COLUMN, $GEO_LAT_COLUMN );


//error_log("EXAMPLE USAGE: /Applications/MAMP/bin/php5.3/bin/php geocoder_main.php ");

?>