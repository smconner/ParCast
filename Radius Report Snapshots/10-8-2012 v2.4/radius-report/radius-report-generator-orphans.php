<?php

function make_orphans_and_suspects($local_user_path, $base_filename) {	
	make_orphans($local_user_path, $base_filename);
	//make_suspects($local_user_path, $base_filename);
	make_archive($local_user_path, $base_filename);
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

// make a single zip file from the orphan and suspects CSV files
function make_archive($local_user_path, $base_filename){
	
	error_log("      CHILD MINDER: GENERATING ARCHIVE FILE");
	
	$file_one = $local_user_path . $base_filename . " orphan_list.csv";
	//$file_two = $local_user_path . $base_filename . " suspects.csv";
	$out_file = $local_user_path . $base_filename . " orphan_list.zip";

	$sCommand = "/usr/bin/zip -q -j";
	$sCommand .= " '$out_file' ";
	//$sCommand .= " '$file_one' '$file_two' ";
	$sCommand .= " '$file_one' ";
	$sCommand = escapeshellcmd( $sCommand );

	exec( $sCommand, $sOutput);
	
	unlink($file_one);
	//unlink($file_two);
}




?>