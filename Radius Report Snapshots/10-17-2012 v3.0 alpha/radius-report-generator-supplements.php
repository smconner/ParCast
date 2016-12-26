<?php

function make_supplements($local_user_path, $base_filename, $lng_site, $lat_site, $user_max_search_radius) {
	
	make_first_zip_check($local_user_path, $base_filename, $lng_site, $lat_site);
	make_frs_check($local_user_path, $base_filename, $lng_site, $lat_site, $user_max_search_radius);
	make_orphans($local_user_path, $base_filename);
	make_readme($local_user_path);
	make_archive($local_user_path, $base_filename);

}

// make a list of sites within a given zip code
function make_first_zip_check($local_user_path, $base_filename, $lng_site, $lat_site) {

	error_log("      CHILD MINDER: GENERATING FIRST DEGREE ZIP CHECK");

	// return the zip code for the given lat and lon
	$url = "http://maps.google.com/maps/geo?output=json&oe=utf-8&ll=$lat_site,$lng_site&sensor=false";
	$result = json_decode( geo_get_data( $url ), true );
	$zip = 0;
	if ( isset($result["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["Locality"]["PostalCode"]["PostalCodeNumber"]) ) {
		$zip = $result["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["Locality"]["PostalCode"]["PostalCodeNumber"];
	} else { error_log("      CHILD MINDER: ERROR - UNABLE TO DETERMINE ZIP CODE"); return; }
    

	// open csv file and connect to database
	$dbConnection = mysql_connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS );
	$fp = fopen($local_user_path.$base_filename." first_degree_zip_check.csv", 'w');
	
	
	// get the table names, and the zip code columns for each database
	$aTable = array();
	$sql = 'SELECT `DB_NAME`,`ZIP_COLUMN` FROM `PARCAST_DB`.`DATA_TRACKING_TABLE`';
	$result = mysql_query( $sql, $dbConnection );
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aTable[] = $row; }
	
	
	// parse each table listed in the data tracking table one at a time
	foreach ( $aTable as $index => $aArray ) {
		
		$sql = 'SELECT * FROM `PARCAST_DB`.`'.$aArray["DB_NAME"].'` WHERE `'.$aArray["ZIP_COLUMN"].'`="'.$zip.'" ';
		$result = mysql_query( $sql, $dbConnection );
		$row_count = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$header = array();	// csv column header
			$data = array();	// csv column data
			
			// remove columns we don't want to display
			unset( $row["GEOCODE_QUALITY"] );
			unset( $row["GEOCODE_RADIUS"] ); 
			unset( $row["GEOCODE_METHOD"] ); 
			unset( $row["MYSQL_POINT_GEOMETRY"] );
			
			// if the current row is the first row get column headers 
			if ($row_count == 0) { 
				foreach ( $row as $key => $value) { 
					$header[] = $key;
				}
				fputcsv($fp, array());
				fputcsv($fp, array($aArray["DB_NAME"]));
				fputcsv($fp, $header);
			}
			
			// for every row get the row data	
			foreach ( $row as $key => $value) { 
				$data[] = str_replace(array("\r\n", "\r", "\n"), null, $value); 
			}
			fputcsv($fp, $data);
			
			$row_count++;
		}
	
	}
	
	fclose($fp);
	mysql_close( $dbConnection );
	
	
}

// scan the FRS database at the maximum radius at the given location
function make_frs_check($local_user_path, $base_filename, $lng_site, $lat_site, $user_max_search_radius) {
	
	error_log("      CHILD MINDER: GENERATING FRS CHECK");
		
	// create an bounding box of the largest radius
	$minLon = $lng_site - $user_max_search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$maxLon = $lng_site + $user_max_search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$minLat = $lat_site - ( $user_max_search_radius / 69.2 );
	$maxLat = $lat_site + ( $user_max_search_radius / 69.2 );
		
	$dbConnection = mysql_connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS );
	$fp = fopen($local_user_path.$base_filename." frs_check.csv", 'w');
	
	// need to get the table name, and the zip code column, lat and lon column
	$aTable = array();	
	$sql = "SELECT FRS_FACILITY_DETAIL_REPORT_URL, REGISTRY_ID, PRIMARY_NAME, LOCATION_ADDRESS, CITY_NAME, COUNTY_NAME, FIPS_CODE, STATE_CODE, POSTAL_CODE, FEDERAL_FACILITY_CODE, FEDERAL_AGENCY_NAME, TRIBAL_LAND_CODE, TRIBAL_LAND_NAME,AsText(MYSQL_POINT_GEOMETRY) FROM `FRS_DB`.`NATIONAL_FACILITY` FORCE INDEX (MYSQL_POINT_GEOMETRY) WHERE MBRContains(GeomFromText('LineString($minLon $maxLat, $maxLon $minLat)'),MYSQL_POINT_GEOMETRY)";
	$result = mysql_query( $sql, $dbConnection );	
	$row_count = 0;
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
						
			$header = array();	// csv column header
			$data = array();	// csv column data
			
			// remove columns we don't want to display
			unset( $row["AsText(MYSQL_POINT_GEOMETRY)"] );
			
			// re-format ZIP Code to have 5 digits
			$row["POSTAL_CODE"] = substr($row["POSTAL_CODE"], 0, 5);
			
			// look more information on this row by REGISTRY_ID		
			$sql = 'SELECT PGM_SYS_ACRNM, PGM_SYS_ID, ALTERNATIVE_NAME FROM `FRS_DB`.`NATIONAL_ALTERNATIVE_NAME` FORCE INDEX (REGISTRY_ID) WHERE `REGISTRY_ID`="'.$row["REGISTRY_ID"].'"';
			$inner_array = mysql_fetch_array( mysql_query( $sql, $dbConnection ), MYSQL_ASSOC );
			if ( $row_count == 0 ) {
				$row = array_merge( $row, array("PGM_SYS_ACRNM" => "", "PGM_SYS_ID" => "", "ALTERNATIVE_NAME" => "") );
			} else if ( is_array( $inner_array ) ) {
				$row = array_merge( $row, $inner_array );
			} else {
				$row = array_merge( $row, array("","","") );
			}

			// look more information on this row by REGISTRY_ID	
			$sql = 'SELECT NAICS_CODE, CODE_DESCRIPTION FROM `FRS_DB`.`NATIONAL_NAICS` FORCE INDEX (REGISTRY_ID) WHERE `REGISTRY_ID`="'.$row["REGISTRY_ID"].'"';
			$inner_array = mysql_fetch_array( mysql_query( $sql, $dbConnection ), MYSQL_ASSOC );
			if ( $row_count == 0 ) {
				$row = array_merge( $row, array("NAICS_CODE" => "", "CODE_DESCRIPTION" => "") );
			} else if ( is_array( $inner_array ) ) {
				$row = array_merge( $row, $inner_array );
			} else {
				$row = array_merge( $row, array("","") );
			}
			
			// look more information on this row by REGISTRY_ID	
			$sql = 'SELECT AFFILIATION_TYPE, FULL_NAME, TITLE, PHONE_NUMBER, FAX_NUMBER, EMAIL_ADDRESS FROM `FRS_DB`.`NATIONAL_CONTACT` FORCE INDEX (REGISTRY_ID) WHERE `REGISTRY_ID`="'.$row["REGISTRY_ID"].'"';
			$inner_array = mysql_fetch_array( mysql_query( $sql, $dbConnection ), MYSQL_ASSOC );
			if ( $row_count == 0 ) {
				$row = array_merge( $row, array("AFFILIATION_TYPE" => "", "FULL_NAME" => "", "TITLE" => "", "PHONE_NUMBER" => "", "FAX_NUMBER" => "", "EMAIL_ADDRESS" => "") );
			} else if ( is_array( $inner_array ) ) {
				$row = array_merge( $row, $inner_array );
			} else {
				$row = array_merge( $row, array("","","","","","") );
			}
			

			// if the current row is the first row get column headers 
			if ($row_count == 0) { 
				foreach ( $row as $key => $value) { 
					$header[] = $key;
				}
				fputcsv($fp, array());
				fputcsv($fp, array("FACILITY REPORT"));
				fputcsv($fp, $header);
			}
			
			// for every row get the row data	
			foreach ( $row as $key => $value) { 
				$data[] = str_replace(array("\r\n", "\r", "\n"), null, $value); 
			}
			fputcsv($fp, $data);
			$row_count++;
	}	
	fclose($fp);
	mysql_close( $dbConnection );
}

// make a list orphans, sites without geolocation information
function make_orphans($local_user_path, $base_filename) {
	
	error_log("      CHILD MINDER: GENERATING ORPHANS");
		
	$dbConnection = mysql_connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS );
	$fp = fopen($local_user_path.$base_filename." orphan_list.csv", 'w');
	
	// need to get the table name, and the zip code column, lat and lon column
	$aTable = array();
	$sql = 'SELECT `DB_NAME`,`DB_LON_COLUMN` FROM `PARCAST_DB`.`DATA_TRACKING_TABLE`';	
	$result = mysql_query( $sql, $dbConnection );
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aTable[] = $row; }

	// parse each table listed in the data tracking table one at a time
	foreach ( $aTable as $index => $aArray ) {
		
		$sql = 'SELECT * FROM `PARCAST_DB`.`'.$aArray["DB_NAME"].'` WHERE `'.$aArray["DB_LON_COLUMN"].'`=""';
		$result = mysql_query( $sql, $dbConnection );
		$row_count = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$header = array();	// csv column header
			$data = array();	// csv column data
			
			// remove columns we don't want to display
			unset( $row["GEOCODE_METHOD"] ); unset( $row["MYSQL_POINT_GEOMETRY"] );
			
			// if the current row is the first row get column headers 
			if ($row_count == 0) { 
				foreach ( $row as $key => $value) { $header[] = $key; }
				fputcsv($fp, array());
				fputcsv($fp, array($aArray["DB_NAME"]));
				fputcsv($fp, $header);
			}
			
			// for every row get the row data	
			foreach ( $row as $key => $value) { $data[] = str_replace(array("\r\n", "\r", "\n"), null, $value); }
			fputcsv($fp, $data);
			$row_count++;
		}
	}
	
	fclose($fp);
	mysql_close( $dbConnection );
}

// make a list of suspected poor geolocation (excluding orphans)
function make_suspects($local_user_path, $base_filename) {
	
	error_log("      CHILD MINDER: GENERATING SUSPECTS");
		
	$dbConnection = mysql_connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS );
	$fp = fopen($local_user_path.$base_filename." suspects.csv", 'w');
	
	// need to get the table name, and the zip code column, lat and lon column
	$aTable = array();
	$sql = 'SELECT `DB_NAME`,`DB_LON_COLUMN`,`PRIMARY_ID_COLUMN` FROM `PARCAST_DB`.`DATA_TRACKING_TABLE`
			WHERE `DB_NAME`="CERCLIS" 
			OR `DB_NAME`="CERCLISNFRAP"
			OR `DB_NAME`="ERNS"
			OR `DB_NAME`="NPL"
			OR `DB_NAME`="NPL_DELETED"
			OR `DB_NAME`="RCRAINFO"
			OR `DB_NAME`="RCRAINFO_TSD_CORRACTS"
	';
	
	$result = mysql_query( $sql, $dbConnection );
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aTable[] = $row; }

	// parse each table listed in the data tracking table one at a time
	foreach ( $aTable as $index => $aArray ) {
		
		$sql = 'SELECT * FROM `PARCAST_DB`.`'.$aArray["DB_NAME"].'` WHERE `'.$aArray["DB_LON_COLUMN"].'`!="" AND `GEOCODE_RADIUS`>"500" GROUP BY `'.$aArray["PRIMARY_ID_COLUMN"].'`';		
		$result = mysql_query( $sql, $dbConnection );
		$row_count = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$header = array();	// csv column header
			$data = array();	// csv column data
			
			// remove columns we don't want to display
			unset( $row["GEOCODE_METHOD"] ); unset( $row["MYSQL_POINT_GEOMETRY"] );
			
			// if the current row is the first row get column headers 
			if ($row_count == 0) { 
				foreach ( $row as $key => $value) { 
					$header[] = $key;
				}
				fputcsv($fp, array());
				fputcsv($fp, array($aArray["DB_NAME"]));
				fputcsv($fp, $header);
			}
			
			// for every row get the row data	
			foreach ( $row as $key => $value) { 
				$data[] = str_replace(array("\r\n", "\r", "\n"), null, $value); 
			}
			fputcsv($fp, $data);
			
			$row_count++;
		}

	}
	
	fclose($fp);
	mysql_close( $dbConnection );
}

// make a txt file with descriptions of the other files in the archive
function make_readme($local_user_path) {
	
	error_log("      CHILD MINDER: GENERATING README");
		
	$myFile = $local_user_path . 'README.txt';
	$myContent = 'File Descriptions

*orphan_list.csv: A list of all sites in our databases without associated GIS information. This list will shrink over time or be eliminated as sites are geolocated and entered by hand. New objects may appear on this list as new databases come online or as database updates are processed containing new sites without GIS information.

*frs_check.csv: A list of all sites in the Facility Registry System that are within the largest search radius provided by the user. The FRS database only gives location and identification information--no hazardous material or contaminant specific information. The FRS database contains all state and federal sites but does not contain military databases such as ERNS.

*first_degree_zip_check.csv: A list of all the sites in our databases with a zip code that matches the search location zip code. If the search location is in a very rural area, the zip code used in this search is linked to the nearest postal address known to the Google Maps API.


';

	file_put_contents($myFile, utf8_encode($myContent)); 
	
}

// make a single zip file from the orphan and suspects CSV files
function make_archive($local_user_path, $base_filename){
	
	error_log("      CHILD MINDER: GENERATING ARCHIVE FILE");
	
	$file_one = $local_user_path . $base_filename . " first_degree_zip_check.csv";
	$file_two = $local_user_path . $base_filename . " orphan_list.csv";
	$file_three = $local_user_path . $base_filename . " frs_check.csv";
	$file_four = $local_user_path . "README.txt";
	$out_file = $local_user_path . $base_filename . " supplements.zip";

	$sCommand = "/usr/bin/zip -q -j";
	$sCommand .= " '$out_file' ";
	$sCommand .= " '$file_one' '$file_two' '$file_three' '$file_four' ";
	$sCommand = escapeshellcmd( $sCommand );

	exec( $sCommand, $sOutput);
	
	unlink($file_one);
	unlink($file_two);
	unlink($file_three);
	unlink($file_four);

}


// a helper function that get some address information from the Google API
function geo_get_data( $url ) {
	
	$url = preg_replace( "/\s+/", "+", $url );			# replace spaces with "+" 
	$ch = curl_init();									# init curl object
		curl_setopt( $ch, CURLOPT_URL, $url );			# URL to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );	# return results into a variable
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 );	# max time in seconds to allow for execution
	$data = curl_exec( $ch ); 							# run curl
	curl_close($ch);									# close curl connection

	return $data;

} // end function


// define( "RR_MYSQL_HOST", "127.0.0.1:8889");
// define( "RR_MYSQL_USER", "root");
// define( "RR_MYSQL_PASS", "root");
//make_first_zip_check("/Applications/MAMP/htdocs/wp-content/plugins/radius-report/generated-reports/smconner/", "1 Sean  Eva 10-8-2012 954am", "-122.042909", "47.598582");
//make_frs_check("/Applications/MAMP/htdocs/wp-content/plugins/radius-report/generated-reports/smconner/", "TEST RURAL 10-9-2012 1045am", "-121.9347791", "47.6718635", "3.00");
//make_readme("/Applications/MAMP/htdocs/wp-content/plugins/radius-report/generated-reports/smconner/");


?>