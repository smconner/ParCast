<?php 

require_once dirname(__FILE__) . "/../../../../radius-report-generator-TCPDF.php";
require_once dirname(__FILE__) . "/../../../../radius-report-generator-orphans.php";


function report_minder( $execute_time, $local_user_path, $base_filename, $lng_site, $lat_site, $user_max_search_radius, $this_current_user_login ) {

	require_once dirname(__FILE__) . "/../../../../radius-report-config.php";
	require_once dirname(__FILE__) . "/../../../../lib/phpseclib/Net/SSH2.php";
	require_once dirname(__FILE__) . "/../../../../lib/phpseclib/Net/SFTP.php";
	
	// just for fun list the incoming parameters
	error_log("      CHILD MINDER: 1. execute_time: $execute_time" );
	error_log("      CHILD MINDER: 2. local_user_path: $local_user_path" );
	error_log("      CHILD MINDER: 3. base_filename: $base_filename" );
	error_log("      CHILD MINDER: 4. lng_site, lat_site: $lng_site, $lat_site" );
	error_log("      CHILD MINDER: 5. MAPNIK_HOST: " . MAPNIK_HOST );
	error_log("      CHILD MINDER: 6. MAPNIK_PG_USER: " . MAPNIK_PG_USER );
	error_log("      CHILD MINDER: 7. current_user: " . $this_current_user_login );
	
	unlink($local_user_path . ".tmp/.updater/" . $base_filename . " - Querying Databases...");	// remove the old file
	touch($local_user_path . ".tmp/.updater/" . $base_filename . " - Generating maps...");		// create the new update file


	
	
	/**  create a detail view @ .25 mile radius (plus a little padding) bounding box to render  **/
	$iDetailSize = .28; // miles
	$minLon_detail = $lng_site - $iDetailSize / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$maxLon_detail = $lng_site + $iDetailSize / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$minLat_detail = $lat_site - ( $iDetailSize / 69.2 );
	$maxLat_detail = $lat_site + ( $iDetailSize / 69.2 );
	$bbox_detail = "--bbox $minLon_detail $minLat_detail $maxLon_detail $maxLat_detail ";
	
	$iDimDetailH = 1200;														// map height in pixels
	$iDimDetailW = 1200;														// map width in pixels
	$dimensions_detail = "--dimensions $iDimDetailW $iDimDetailH";				// map H and W in pixels
	$iDetailScaleDenominator = ( $iDimDetailH / ( 2 * $iDetailSize ) );			// calculates this maps scale denominator in px/mile - assumes a square map
	$iDetailScaleFactor = 1.5;													// set the detail scalefactor
	$scale_factor_detail = "--scale-factor $iDetailScaleFactor";				// make nik2nik scalefactor string
	
	
	/**  create an overview @ 1.25 mile radius bounding box to render  **/
	$iOverviewSize = 1.25; // miles
	$minLon_overview = $lng_site - $iOverviewSize / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$maxLon_overview = $lng_site + $iOverviewSize / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
	$minLat_overview = $lat_site - ( $iOverviewSize / 69.2 );
	$maxLat_overview = $lat_site + ( $iOverviewSize / 69.2 );
	$bbox_overview = "--bbox $minLon_overview $minLat_overview $maxLon_overview $maxLat_overview ";

	$iDimOverviewH = 2400;															// map height in pixels
	$iDimOverviewW = 2400;															// map width in pixels
	$dimensions_overview = "--dimensions $iDimOverviewW $iDimOverviewH";			// map H and W in pixels
	$iOverviewScaleDenominator = ( $iDimOverviewH / ( 2 * $iOverviewSize ) );		// calculates this maps scale denominator in px/mile - assumes a square map
	$iOverviewScaleFactor = 3;														// set the detail scalefactor
	$scale_factor_overview = "--scale-factor $iOverviewScaleFactor";				// make nik2nik scalefactor string
	

	/**  if max radius is bigger than 1.25 then make a nother map  **/
	if ( $user_max_search_radius > 1.25 ) {
		
		// create an overview @ max radius bounding box to render from
		$minLon_maxRad = $lng_site - $user_max_search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
		$maxLon_maxRad = $lng_site + $user_max_search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
		$minLat_maxRad = $lat_site - ( $user_max_search_radius / 69.2 );
		$maxLat_maxRad = $lat_site + ( $user_max_search_radius / 69.2 );
		$bbox_maxRad = "--bbox $minLon_maxRad $minLat_maxRad $maxLon_maxRad $maxLat_maxRad ";
		
		$iDimMaxradH = 2400;																// map height in pixels
		$iDimMaxradW = 2400;																// map width in pixels
		$dimensions_maxrad = "--dimensions $iDimMaxradW $iDimMaxradH";						// map H and W in pixels
		$iMaxradScaleDenominator = ( $iDimMaxradH / ( 2 * $user_max_search_radius ) );		// calculates this maps scale denominator in px/mile - assumes a square map
		$iMaxradScaleFactor = 3;															// set the detail scalefactor
		$scale_factor_maxrad = "--scale-factor $iMaxradScaleFactor";						// make nik2nik scalefactor string
		
		
	} else {
		
		$bbox_maxRad = false;
		$dimensions_maxrad = false;
		$iMaxradScaleDenominator = false;
		$iMaxradScaleFactor = false;
		$scale_factor_maxrad = false;
		
	} // end if
	
	
	/**
	 *	Modify and return each maps xml file  
	 */
	
		$sMapnikDetailXML = file_get_contents( dirname(__FILE__) . "/../../../../lib/mapnik/mapnik-detail.xml" );				// import mapnik.xml file
		$sMapnikDetailXML = makeMapnikRadiiXML( $sMapnikDetailXML, $iDetailScaleDenominator, $iDetailScaleFactor );				// add scaled radii markers @ .25, .50 and 1 mile radius
		
		$sMapnikOverviewXML = file_get_contents( dirname(__FILE__) . "/../../../../lib/mapnik/mapnik-overview.xml" );			// mapnik.xml file
		$sMapnikOverviewXML = makeMapnikRadiiXML( $sMapnikOverviewXML, $iOverviewScaleDenominator, $iOverviewScaleFactor );		// add scaled radii markers @ .25, .50 and 1 mile radius
		
		if ( $bbox_maxRad ) {
			$sMapnikMaxradXML = file_get_contents( dirname(__FILE__) . "/../../../../lib/mapnik/mapnik-maxrad.xml" );			// mapnik.xml file
			$sMapnikMaxradXML = makeMapnikRadiiXML( $sMapnikMaxradXML, $iMaxradScaleDenominator, $iMaxradScaleFactor );			// add scaled radii markers @ .25, .50 and 1 mile radius
			$sMapnikMaxradXML = makeMaxradRadiusXML( $sMapnikMaxradXML, $iMaxradScaleDenominator, $iMaxradScaleFactor, $user_max_search_radius ); 	// add scaled radius marker @ maxrad radius
		} else {
			
			$sMapnikMaxradXML = false;
		}
		
		
	/**
	 *	Import nodes.osm file generated by generator-engine.php
	 */
		
		$sNodesOSM = file_get_contents( $local_user_path. ".tmp/" .$execute_time. "/nodes.osm" );
	
	
	/**
	 *	Make Mapnik maps
	 */
	
		makeMapnikPNG( $sNodesOSM, $bbox_detail, $bbox_overview, $bbox_maxRad, $dimensions_detail, $dimensions_overview, $dimensions_maxrad, $execute_time, $base_filename, $local_user_path, $this_current_user_login, $sMapnikDetailXML, $sMapnikOverviewXML, $sMapnikMaxradXML, $scale_factor_detail, $scale_factor_overview, $scale_factor_maxrad );
	
	
	/**
	 *	Run TCPDF generator
	 */
		radius_report_create_pdf( $execute_time, $local_user_path, $base_filename, $iDetailScaleDenominator, $iOverviewScaleDenominator, $iMaxradScaleDenominator );
		//error_log("      CHILD MINDER: PDF GENERATOR OFFLINE");
	
	
	/**
	 *	Run Orphans and Suspects
	 */
		make_orphans_and_suspects($local_user_path, $base_filename);

					
} // end function


/**
 * Does the same as makeMapnikRadiiXML but just for the unique maxrad marker.
 * @param string $sMapnikXML
 * @param integer $iScaleDenominator
 * @param integer $user_max_search_radius
 * @return string
 */

function makeMaxradRadiusXML( $sMapnikXML, $iScaleDenominator, $iScaleFactor, $user_max_search_radius ) {
	
	$maxrad_width = $iScaleDenominator * $user_max_search_radius;
	$sMapnikXML = str_replace( "maxrad_width", $maxrad_width, $sMapnikXML );
	
	$maxrad_height = $iScaleDenominator * $user_max_search_radius;
	$sMapnikXML = str_replace( "maxrad_height", $maxrad_height, $sMapnikXML );
	
	$maxrad_text = $iScaleDenominator * $user_max_search_radius / $iScaleFactor - 10;
	$sMapnikXML = str_replace( "maxrad_text", $maxrad_text, $sMapnikXML );
	
	return $sMapnikXML;
	
} // end function



/**
 * Converts radius tags in mapnik-.xml to appropriate H and W in pixels.
 * @param string $sMapnikXML
 * @param integer $iScaleDenominator
 * @return string
 */

function makeMapnikRadiiXML( $sMapnikXML, $iScaleDenominator, $iScaleFactor ) {
	
	$fourth_mile_width = $iScaleDenominator * .25;
	$sMapnikXML = str_replace( "fourth_mile_width", $fourth_mile_width, $sMapnikXML );
	
	$fourth_mile_height = $iScaleDenominator * .25;
	$sMapnikXML = str_replace( "fourth_mile_height", $fourth_mile_height, $sMapnikXML );	
	
	$fourth_mile_text = $iScaleDenominator * .25 / $iScaleFactor;
	$sMapnikXML = str_replace( "fourth_mile_text", $fourth_mile_text, $sMapnikXML );
	
	
	
	$half_mile_width = $iScaleDenominator * .50;
	$sMapnikXML = str_replace( "half_mile_width", $half_mile_width, $sMapnikXML );
	
	$half_mile_height = $iScaleDenominator * .50;
	$sMapnikXML = str_replace( "half_mile_height", $half_mile_height, $sMapnikXML );
	
	$half_mile_text = $iScaleDenominator * .50 / $iScaleFactor;
	$sMapnikXML = str_replace( "half_mile_text", $half_mile_text, $sMapnikXML );
	
	
	
	$full_mile_width = $iScaleDenominator * 1.0;
	$sMapnikXML = str_replace( "full_mile_width", $full_mile_width, $sMapnikXML );
	
	$full_mile_height = $iScaleDenominator * 1.0;
	$sMapnikXML = str_replace( "full_mile_height", $full_mile_height, $sMapnikXML );
	
	$full_mile_text = $iScaleDenominator * 1.0 / $iScaleFactor;
	$sMapnikXML = str_replace( "full_mile_text", $full_mile_text, $sMapnikXML );

	
	return $sMapnikXML;
	
} // end function



/**
 * Contact Mapnik server, make PNG files, and get them from the server.
 * @param string $sNodesOSM Location of the local nodes.osm file.
 * @param string $bbox_detail The formatted bounding box of the detail.png.
 * @param string $bbox_overview The formatted bounding box of the overview.png.
 * @param string $bbox_maxRad The formatted bounding box of the maxrad.png.
 * @param int $execute_time The execute time.
 * @param string $local_user_path The path to the current users dir.
 * @param string $this_current_user_login The current users login.
 */

function makeMapnikPNG( $sNodesOSM, $bbox_detail, $bbox_overview, $bbox_maxRad, $dimensions_detail, $dimensions_overview, $dimensions_maxrad, $execute_time, $base_filename, $local_user_path, $this_current_user_login, $sMapnikDetailXML, $sMapnikOverviewXML, $sMapnikMaxradXML, $scale_factor_detail, $scale_factor_overview, $scale_factor_maxrad ) {

	if ( empty($execute_time) ) {
		error_log( "      CHILD MINDER: NO execute time: $execute_time" );
		die;
	}
	
	// establish STFP connection
	$oSFTP = new Net_SFTP( MAPNIK_HOST );
	$oSFTP -> login( MAPNIK_PG_USER , MAPNIK_PASS );
		
	// establish SSH connection
	$oSSH = new Net_SSH2( MAPNIK_HOST );
	$oSSH -> login( MAPNIK_PG_USER , MAPNIK_PASS );


	// put nodes.osm and mapnik.xml on the mapnik server
	$oSFTP->chdir( 'tmp' );

	$aDirCheck = $oSFTP->nlist();
	if ( !in_array($this_current_user_login, $aDirCheck) ) {
		$oSFTP -> mkdir( $this_current_user_login );
		$oSFTP -> chdir( $this_current_user_login );
	} else {
		$oSFTP -> chdir( $this_current_user_login );
	}
	
	$oSFTP->mkdir( $execute_time );
	$oSFTP->chdir( $execute_time );
	$oSFTP->put( 'nodes.osm', $sNodesOSM );
	$oSFTP->put( 'mapnik-detail.xml', $sMapnikDetailXML );
	$oSFTP->put( 'mapnik-overview.xml', $sMapnikOverviewXML );
	if ( $bbox_maxRad ) { $oSFTP->put( 'mapnik-maxrad.xml', $sMapnikMaxradXML ); }
	

	// move into the directory created above
	$oSSH -> write( "cd tmp/$this_current_user_login/$execute_time\n" );
	$oSSH -> read( MAPNIK_PG_USER . '@kila2:' );
	
	// run nik2img to generate the detail map
	$oSSH -> read( MAPNIK_PG_USER . '@kila2:' );
	$oSSH -> write( "/usr/local/bin/nik2img.py mapnik-detail.xml detail.png --format png --srs 900913 $bbox_detail $dimensions_detail $scale_factor_detail --no-open\n" );
	checkRemoteFile( $oSFTP, "detail.png", $local_user_path, $base_filename );
	
	// run nik2img to generate the overview map
	$oSSH -> read( MAPNIK_PG_USER . '@kila2:' );
	$oSSH -> write( "/usr/local/bin/nik2img.py mapnik-overview.xml overview.png --format png --srs 900913 $bbox_overview $dimensions_overview $scale_factor_overview --no-open\n" );
	checkRemoteFile( $oSFTP, "overview.png", $local_user_path, $base_filename );
	
	// run nik2img to generate the maximum overview map
	if ( $bbox_maxRad ) {
		$oSSH -> read( MAPNIK_PG_USER . '@kila2:' );
		$oSSH -> write( "/usr/local/bin/nik2img.py mapnik-maxrad.xml maxrad.png --format png --srs 900913 $bbox_maxRad $dimensions_maxrad $scale_factor_maxrad --no-open\n" );
		checkRemoteFile( $oSFTP, "maxrad.png", $local_user_path, $base_filename );
	}

	
	// get the finished files from the server and put them in the users directory so TCPDF can user them
	$aTransferStatus = array();

	error_log("      CHILD MINDER:  WRITING TO : $local_user_path" . ".tmp/$execute_time/detail.png");
	$aTransferStatus[] = $oSFTP->get( "detail.png", $local_user_path . ".tmp/$execute_time/detail.png" );
	
	error_log("      CHILD MINDER:  WRITING TO : $local_user_path" . ".tmp/$execute_time/overview.png");
	$aTransferStatus[] = $oSFTP->get( "overview.png", $local_user_path . ".tmp/$execute_time/overview.png" );
	
	if ( $bbox_maxRad ) {
		error_log("      CHILD MINDER:  WRITING TO : $local_user_path" . ".tmp/$execute_time/maxrad.png");
		$aTransferStatus[] = $oSFTP->get( "maxrad.png", $local_user_path . ".tmp/$execute_time/maxrad.png" );
	}
	
	
	// remove the dir $execute_time
	if ( !in_array( false, $aTransferStatus) ) {

		$oSFTP->delete("nodes.osm");
		$oSFTP->delete("mapnik-detail.xml");
		$oSFTP->delete("mapnik-overview.xml");
		$oSFTP->delete("detail.png");
		$oSFTP->delete("overview.png");
		if ( $bbox_maxRad ) { 
			$oSFTP->delete("mapnik-maxrad.xml");
			$oSFTP->delete("maxrad.png"); 
		}
		$oSFTP->chdir( ".." );
		$bRmStatus = $oSFTP->rmdir( "$execute_time" );
		
		error_log("      CHILD MINDER:  TRANSFER STATUS: OK" );
		error_log("      CHILD MINDER:  REMOVE STATUS: $bRmStatus" );
		
	} else {
		
		error_log("      CHILD MINDER:  ERROR IN TRANS STATUS: " . print_r( $aTransferStatus, 1 ) );
		error_log("      CHILD MINDER:  ABORT REMOVE" );
	}
	
	

	$oSFTP -> disconnect();		// close SFTP connection
	$oSSH -> disconnect();		// close SSh connection

	error_log("      CHILD MINDER:  ---- END SSH ---- " );

} // end function



/**
 * Checks if the file in the current SFTP dir is finished being created by checking the files size over a second.
 * @param object $oSFTP
 * @param string $sFileName
 */

function checkRemoteFile( $oSFTP, $sFileName, $local_user_path, $base_filename ) {
	
	error_log("      CHILD MINDER:  Waiting 1 Seconds..." );
	sleep(1);
	
	$bDone = false;
	$iCount = 0;
	
	while ( !$bDone ) {
		
		$aStatOut = $oSFTP->stat( $sFileName );
		$iSizeOne = $aStatOut["size"];
		
		error_log("      CHILD MINDER:  Waiting 1 Seconds..." );
		sleep(1);
		$iCount++;
		
		$aStatOut = $oSFTP->stat( $sFileName );
		$iSizeTwo = $aStatOut["size"];
		
		error_log("      CHILD MINDER:  DIFF SIZE: " . (($iSizeTwo - $iSizeOne)/1000) . "KB");
		
			if ( !empty($iSizeOne) && !empty($iSizeTwo) && ($iSizeTwo - $iSizeOne) == 0 ) {
	
				error_log("      CHILD MINDER:  Done!");
				$bDone = true;
			
			// if the file is not there or accessible then timeout the minder and throw an error to the user.
			} else if ( $iCount >= 120 ) {
				
				error_log("      CHILD MINDER:  Process timeout at $iCount seconds!");
				error_log("      CHILD MINDER:  Posting error to user");
				unlink($local_user_path . ".tmp/.updater/" . $base_filename . " - Generating maps...");		
				touch($local_user_path . ".tmp/.updater/" . $base_filename . " - Failed on generating maps! Please email helpdesk.");
					
				exit();
				
			} // end if
	
	} // end while

} // end function


