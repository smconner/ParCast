<?php
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
	
	$oMySQL->ExecuteSQL( "SELECT $GEO_ADDRESS,$GEO_ADDRESS_CITY,$GEO_ADDRESS_STATE,$GEO_ADDRESS_ZIP,$GEO_LAT_COLUMN,$GEO_LON_COLUMN FROM $GEO_MYSQL_TABLE_NAME WHERE 1 LIMIT 25" );
	
	$MYSQL_ALL_ROWS_ARRAY = $oMySQL->ArrayResults();

	
	$PREV_OSM_API_QUERY_STRING = null;
	
	$OSM_API_QUERY_RESULTS = null;
		
	foreach ( $MYSQL_ALL_ROWS_ARRAY as $row_number_key => $row_array_value ) {
		
		
		$OSM_API_ROOT_STRING = "http://nominatim.openstreetmap.org/search?q=";
			
		$OSM_API_OPTIONS_STRING = "&format=json&polygon=0&countrycodes=US&limit=1&email=smconner@gmail.com";
			
			$OSM_API_ADDRESS_STRING = "$row_array_value[$GEO_ADDRESS], $row_array_value[$GEO_ADDRESS_CITY], $row_array_value[$GEO_ADDRESS_STATE] $row_array_value[$GEO_ADDRESS_ZIP]";
							
				$OSM_API_QUERY_STRING = $OSM_API_ROOT_STRING . $OSM_API_ADDRESS_STRING . $OSM_API_OPTIONS_STRING;
		
		
		// if this row has either gelocation columns = 0 then geocode it
		if ( $row_array_value[$GEO_LAT_COLUMN] == 0 || $row_array_value[$GEO_LON_COLUMN] == 0 ) {
			
				// if previous OSM address is NOT equal to current OSM address then
				if ( $PREV_OSM_API_QUERY_STRING != $OSM_API_QUERY_STRING ) {
	
					$OSM_API_QUERY_RESULTS = geo_get_data( $OSM_API_QUERY_STRING );
							
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
						
				
				// if OSM query address IS the same as the last, insert the last geolocation into the current row 
				} else if ( $PREV_OSM_API_QUERY_STRING == $OSM_API_QUERY_STRING ) {
					
						// if json results from OSM are NOT empty "[]"
						if ( $OSM_API_QUERY_RESULTS != "[]" ) {
							
							error_log(" DID NOT QUERY OSM API FOR THIS ROW BECAUSE IT IS EQUAL TO PREVIOUS ROW" );
												
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

					
				} else {
					
					error_log("SOMETHING ELSE HAPPENED");
					
				}

		// if this row has non-zero values in both geolocation columns skip it and go on to the next row
		} else {
			
			error_log( " DID NOT QUERY OSM API BECAUSE LAT AND LON ARE NON-ZERO: ROW# $row_number_key is != 0 : $row_array_value[$GEO_LAT_COLUMN]:$row_array_value[$GEO_LON_COLUMN]" );
			
		}
		
		$PREV_OSM_API_QUERY_STRING = $OSM_API_QUERY_STRING;
		
	}
}