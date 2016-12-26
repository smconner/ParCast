<?php

##
#	Declare initial values
##

$aInitVals = array(
	"myHostname" => ":/Applications/MAMP/tmp/mysql/mysql.sock",
	"myUsername" => "root",
	"myPassword" => "root",
	"mySrcDatabase" => "PARCAST_DB",	// DB with the source CERCLIS tables
	"mySrcTable" => "CSCSL_WA",			// Source table
	"mySrcID" => "Facility Site ID",	// Source ID Column to Reference
	"myDstDatabase" => "CSCSL_WA_DB",	// DB to write the destination monolithic CERCLIS table
	"myDstTable" => "CSCSL_WA",			// Destination table
	"myDstID" => "FacilitySiteId",		// Destination ID Column to Reference		
);


##
#	Copy a unique list of `Facility Site ID` and corresponding `Latitude` and `Longitude` to new table
##

function copy_geocoding($aInitVals) {
	
	$myHostname = $aInitVals["myHostname"];
	$myUsername = $aInitVals["myUsername"];
	$myPassword = $aInitVals["myPassword"];
	$mySrcDatabase = $aInitVals["mySrcDatabase"];
	$mySrcTable = $aInitVals["mySrcTable"];
	$mySrcID =  $aInitVals["mySrcID"];
	$myDstDatabase =  $aInitVals["myDstDatabase"];
	$myDstTable =  $aInitVals["myDstTable"];
	$myDstID =  $aInitVals["myDstID"];
	$dbConnection = mysql_connect( $myHostname, $myUsername, $myPassword );
	
	$iRefTime = microtime(true);
	error_log("------------- STARTING COPY GEOCODING @ t = 0 ms");
	
	// SELECT `Facility Site ID`,`Latitude`,`Longitude` FROM `$mySrcTable`
	$sql = 'SELECT `'.$mySrcID.'`,`Latitude`,`Longitude`,`GEOCODE_METHOD`,`GEOCODE_QUALITY`,`GEOCODE_RADIUS` FROM `'.$mySrcDatabase.'`.`'.$mySrcTable.'` ';
	$result = mysql_query( $sql, $dbConnection );
	$aGEO = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aGEO[] = $row; }
	$aGEO = array_unique($aGEO, SORT_REGULAR);
	
	// Add Latitude and Longitude columns
	mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` ADD `Latitude` DECIMAL(11,6) NOT NULL" , $dbConnection );
	mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` ADD `Longitude` DECIMAL(11,6) NOT NULL" , $dbConnection );
	mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` ADD `GEOCODE_METHOD` VARCHAR(30) NOT NULL" , $dbConnection );
	mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` ADD `GEOCODE_QUALITY` INT(2) NOT NULL" , $dbConnection );
	mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` ADD `GEOCODE_RADIUS` INT(12) NOT NULL" , $dbConnection );


	// delete all indexes from `".$myDstDatabase."`.`".$myDstTable."`. table and add one $myDstID index
	$sql = "SHOW INDEX FROM `".$myDstDatabase."`.`".$myDstTable."` ";
	$result = mysql_query( $sql, $dbConnection );
	$aINDEX = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aINDEX[] = $row["Key_name"]; }
	foreach ($aINDEX as $key => $value) { mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` DROP INDEX `".$value."`", $dbConnection ); }
	mysql_query( "ALTER TABLE `".$myDstDatabase."`.`".$myDstTable."` ADD INDEX(`$myDstID`) ", $dbConnection );	

	// transfer over the lat and lon, and geo coding metrics by $mySrcID column
    foreach ( $aGEO as $key => $value ) {
		$sql = 'UPDATE `'.$myDstDatabase.'`.`'.$myDstTable.'` SET 
		`Latitude`	=	"'.mysql_real_escape_string($value["Latitude"]).'",
		`Longitude`	=	"'.mysql_real_escape_string($value["Longitude"]).'",
		`GEOCODE_METHOD`	=	"'.mysql_real_escape_string($value["GEOCODE_METHOD"]).'",
		`GEOCODE_QUALITY`	=	"'.mysql_real_escape_string($value["GEOCODE_QUALITY"]).'",
		`GEOCODE_RADIUS`	=	"'.mysql_real_escape_string($value["GEOCODE_RADIUS"]).'"
		WHERE `'.$myDstID.'` = "'.$value["$mySrcID"].'" ';
		mysql_query( $sql, $dbConnection );
	}

	$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
	error_log("------------- CLOSING DB CONNECTION @ $iTimeNow ms");
	mysql_close( $dbConnection );
}

##
#	Geocode new sites / empty lat and lon rows
##

function geocode_new($aInitVals) {
	
	$myHostname = $aInitVals["myHostname"];
	$myUsername = $aInitVals["myUsername"];
	$myPassword = $aInitVals["myPassword"];
	$mySrcDatabase = $aInitVals["mySrcDatabase"];
	$mySrcTable = $aInitVals["mySrcTable"];
	$mySrcID =  $aInitVals["mySrcID"];
	$myDstDatabase =  $aInitVals["myDstDatabase"];
	$myDstTable =  $aInitVals["myDstTable"];
	$myDstID =  $aInitVals["myDstID"];
	$dbConnection = mysql_connect( $myHostname, $myUsername, $myPassword );
	
	$idCol = $myDstID;
	$addrCol = "Address";
	$cityCol = "City";
	$stateCol = "State";
	$zipCol = "ZipCode";
	$latCol = "Latitude";
	$lonCol = "Longitude";
	$table = "`" . $myDstDatabase . "`.`" . $myDstTable . "`";
	
	$iRefTime = microtime(true);
	error_log("------------- STARTING COPY GEOCODING @ t = 0 ms");
	
		
	// grab all the addresses and lat lon cols and put them in $aGEO
	$aGEO = array();
	$result = mysql_query("SELECT `$idCol`,`$addrCol`,`$cityCol`,`$stateCol`,`$zipCol`,`$latCol`,`$lonCol` FROM $table", $dbConnection );
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aGEO[] = $row; }
	$aGEO = array_unique($aGEO, SORT_REGULAR);
	
	foreach ( $aGEO as $key => $value ) {
		$cleanAddr = preg_replace( "/\&+/", "AND", $value[$addrCol] ); 					// replace "&" symbol with "AND" in address
		$cleanZip = substr($value[$zipCol], 0, 5);										// trim Zip Code to 5 digits if not already
		$apiString = "http://where.yahooapis.com/geocode?q=";							// construct the API query string
		$apiString .= "$cleanAddr,$value[$cityCol],$value[$stateCol] $cleanZip";		// query values
		$apiString .= "&flags=J" . "&flags=C" . "&count=1" . "&appid=JKpwM84k";			// query options	

		// geocode if current row has lat/lon = 0 or NULL or non-numeric
		if ( $value[$latCol] == 0 || $value[$lonCol] == 0 || $value[$latCol] == NULL || $value[$lonCol] == NULL || !is_numeric( $value[$latCol] ) || !is_numeric( $value[$lonCol] ) ) {

			$queryResult = geo_get_data($apiString);
			$queryResult = json_decode($queryResult, true);
			
			// if non-error from YAHOO
			if ( $queryResult["ResultSet"]["Error"] == "0" && $queryResult["ResultSet"]["Found"] != "0") {
				error_log( "YAHOO API QUERY RESULTS LAT:LON : " . print_r( $queryResult["ResultSet"]["Results"]["0"]["latitude"], 1) . ":" . print_r( $queryResult["ResultSet"]["Results"]["0"]["longitude"], 1) );
				error_log( "YAHOO API QUERY RESULTS QUALITY: " . print_r( $queryResult["ResultSet"]["Results"]["0"]["quality"], 1) );
				error_log( "YAHOO API QUERY RESULTS RADIUS IN METERS: " . print_r( $queryResult["ResultSet"]["Results"]["0"]["radius"], 1) );
				
				$sql = 'UPDATE '.$table.' SET 
				`'.$latCol.'` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["latitude"]).'",
				`'.$lonCol.'` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["longitude"]).'",
				`GEOCODE_METHOD` = "YAHOO_FULL_ADDRESS",
				`GEOCODE_QUALITY` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["quality"]).'",
				`GEOCODE_RADIUS` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["radius"]).'"
				WHERE `'.$idCol.'` = "'.$value["$idCol"].'" ';
				mysql_query( $sql, $dbConnection );
				error_log("SQL STRING: " . $sql);
		
			} else {
				error_log("YAHOO API ERROR OCCURED: " . print_r( $queryResult["ResultSet"]["ErrorMessage"], 1 ) );
				error_log("YAHOO API FOUND: " . print_r( $queryResult["ResultSet"]["Found"], 1 ) );
			}	

		
		}
	} // end foreach

	$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
	error_log("------------- CLOSING DB CONNECTION @ $iTimeNow ms");
	mysql_close( $dbConnection );
}

##
#	RE-Geocode rows with "&" in the address
##

function fix_geocoding($aInitVals){
	
	$myHostname = $aInitVals["myHostname"];
	$myUsername = $aInitVals["myUsername"];
	$myPassword = $aInitVals["myPassword"];
	$mySrcDatabase = $aInitVals["mySrcDatabase"];
	$mySrcTable = $aInitVals["mySrcTable"];
	$mySrcID =  $aInitVals["mySrcID"];
	$myDstDatabase =  $aInitVals["myDstDatabase"];
	$myDstTable =  $aInitVals["myDstTable"];
	$myDstID =  $aInitVals["myDstID"];
	$dbConnection = mysql_connect( $myHostname, $myUsername, $myPassword );
	
	$idCol = $myDstID;
	$addrCol = "Address";
	$cityCol = "City";
	$stateCol = "State";
	$zipCol = "ZipCode";
	$latCol = "Latitude";
	$lonCol = "Longitude";
	$table = "`" . $myDstDatabase . "`.`" . $myDstTable . "`";
	
	$iRefTime = microtime(true);
	error_log("------------- STARTING COPY GEOCODING @ t = 0 ms");
	
		
	// grab all the addresses and lat lon cols and put them in $aGEO
	$aGEO = array();
	$result = mysql_query("SELECT `$idCol`,`$addrCol`,`$cityCol`,`$stateCol`,`$zipCol`,`$latCol`,`$lonCol` FROM $table WHERE `Address` LIKE '%&%'", $dbConnection);
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { $aGEO[] = $row; }
	$aGEO = array_unique($aGEO, SORT_REGULAR);
	
	foreach ( $aGEO as $key => $value ) {
		$cleanAddr = preg_replace( "/\&+/", "AND", $value[$addrCol] ); 					// replace "&" symbol with "AND" in address
		$cleanZip = substr($value[$zipCol], 0, 5);										// trim Zip Code to 5 digits if not already
		$apiString = "http://where.yahooapis.com/geocode?q=";							// construct the API query string
		$apiString .= "$cleanAddr,$value[$cityCol],$value[$stateCol] $cleanZip";		// query values
		$apiString .= "&flags=J" . "&flags=C" . "&count=1" . "&appid=JKpwM84k";			// query options	

			$queryResult = geo_get_data($apiString);
			$queryResult = json_decode($queryResult, true);
			
			// if non-error from YAHOO
			if ( $queryResult["ResultSet"]["Error"] == "0" && $queryResult["ResultSet"]["Found"] != "0") {
				error_log( "YAHOO API QUERY RESULTS LAT:LON : " . print_r( $queryResult["ResultSet"]["Results"]["0"]["latitude"], 1) . ":" . print_r( $queryResult["ResultSet"]["Results"]["0"]["longitude"], 1) );
				error_log( "YAHOO API QUERY RESULTS QUALITY: " . print_r( $queryResult["ResultSet"]["Results"]["0"]["quality"], 1) );
				error_log( "YAHOO API QUERY RESULTS RADIUS IN METERS: " . print_r( $queryResult["ResultSet"]["Results"]["0"]["radius"], 1) );
				
				$sql = 'UPDATE '.$table.' SET 
				`'.$latCol.'` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["latitude"]).'",
				`'.$lonCol.'` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["longitude"]).'",
				`GEOCODE_METHOD` = "YAHOO_FULL_ADDRESS",
				`GEOCODE_QUALITY` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["quality"]).'",
				`GEOCODE_RADIUS` = "'.mysql_real_escape_string($queryResult["ResultSet"]["Results"]["0"]["radius"]).'"
				WHERE `'.$idCol.'` = "'.$value["$idCol"].'" ';
				mysql_query( $sql, $dbConnection );
				error_log("SQL STRING: " . $sql);
		
			} else {
				error_log("YAHOO API ERROR OCCURED: " . print_r( $queryResult["ResultSet"]["ErrorMessage"], 1 ) );
				error_log("YAHOO API FOUND: " . print_r( $queryResult["ResultSet"]["Found"], 1 ) );
			}	
	
	} // end foreach
	
	$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
	error_log("------------- CLOSING DB CONNECTION @ $iTimeNow ms");
	mysql_close( $dbConnection );
	
} // end function



/**
 * Gets the data from a URL
 * @param sting $url
 * @return string $data
 * @TODO needs proper commenting
 */				

function geo_get_data( $url ) {
	$url = preg_replace( "/\s+/", "+", $url );		# replace spaces with "+"
	$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );	# URL to post to
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); # return results into a variable
		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 ); # max time in seconds to allow for execution
			$data = curl_exec( $ch ); # run curl
	curl_close($ch);
	sleep( 1 );
	error_log("API ENCODED URL: " . $url );
	return $data;
} // end function


//copy_geocoding($aInitVals);
//geocode_new($aInitVals);
//fix_geocoding($aInitVals);

?>