<?php


require_once( '../../../' . 'wp-blog-header.php' );													// "wp-blog-header.php" needed to run: wp_get_current_user() AND "../../../" needed because this php file gets called from the client
require_once dirname(__FILE__) . '/classes/class.MySQL.php';
require_once dirname(__FILE__) . '/radius-report-config.php';
		
	$current_user = wp_get_current_user(); 																// grab the current user id object
		
	if ( !($current_user instanceof WP_User) ) {
		    	
		return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See: " . __FILE__);
		    	
	} else {

		$radius_report_user_max_search_radius = "radius_report_user_max_search_radius";
		$user_id = $current_user->ID;
	}
			    
			    	
	$aUserSite = get_user_meta( $user_id, "radius_report_user_site", true );						// get the user_options for this user's site
	
	$lng_site = $aUserSite[ 'site_lng' ];															// get the the point coordinates specific to our user's site
	$lat_site = $aUserSite[ 'site_lat' ];
	
	$user_options_input = get_user_meta( $user_id, "radius_report_user_input", true );				// get the user_options
			
    				
	$radius_report_database_connection = mysql_connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS );			// Opens a connection to a MySQL server and sets the reference to: $radius_report_database_connection
	$radius_report_database_selected = mysql_select_db( RR_MYSQL_NAME, $radius_report_database_connection );		// Set the active MySQL database
    
		    
	$oMySQL = new MySQL(); 
	$oMySQL->Connect( RR_MYSQL_HOST, RR_MYSQL_USER, RR_MYSQL_PASS, RR_MYSQL_NAME );																																// instantiate our MySQL object from: class.MySQL.php
	$oMySQL->ExecuteSQL( "SELECT DB_NAME,DB_NAME_HUMAN,HAZMAT_CLASS_NAME,DB_GEOMETRY_COLUMN,DB_LAT_COLUMN,DB_LON_COLUMN FROM DATA_TRACKING_TABLE" );		// get an array of results from the table
	$aTrackingTable = $oMySQL->ArrayResults();																									// put that arrary in a local variable
				    
				    
	/**
	 *	Generates an ondemand KML file for google (or anyone on the internet)
	 */
			
		$dom = new DOMDocument('1.0', 'UTF-8');												// Creates a new instance of a DOM (Document Object Model) Document 
		$node = $dom->createElementNS('http://earth.google.com/kml/2.2', 'kml');			// Creates the root KML element and appends it to the root document.
		$parNode = $dom->appendChild($node);
		$dnode = $dom->createElement('Document');											// Creates a KML Document element and append it to the KML element.
		$docNode = $parNode->appendChild($dnode);
							
								

	/**
     *	Iterate through this stuff to generate the rest of the KML file
     */

		$max_search_radius = 0;																										// initialize variable outside of loop for increased scope
		
		foreach ( $user_options_input as $user_options_input_key => $user_options_input_value ) {									// iterate through the user's options file
			
			if ( preg_match( "/_CHECKBOX/m" , $user_options_input_key) && $user_options_input_value =='on' ) {						// if the current iterated key is a 'checkbox' and it is set to 'on' do this 
				
				$search_radius = $user_options_input[ preg_replace("/_CHECKBOX/m", "_RADIUS", $user_options_input_key) ];			// since the current key and value are checked get the corresponding search radius from the users options
				
				if ($max_search_radius < $search_radius) { $max_search_radius = $search_radius; }									// if this is a bigger search radius than the last assign it to a user option variable
				
				// define our bounding box so we can query the mysql databases for just the results we want
				$lng_1 = $lng_site - $search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
				$lng_2 = $lng_site + $search_radius / abs( cos( $lat_site * pi() / 180 ) * 69.172 );
				$lat_1 = $lat_site - ( $search_radius / 69.2 );
				$lat_2 = $lat_site + ( $search_radius / 69.2 );
				
		
				// Set the name of the current database being iterated on
				$DB_NAME = preg_replace( "/_CHECKBOX/m", "", $user_options_input_key);
				
				foreach ( $aTrackingTable as $db_array => $db_array_value ) {								// iterate through each array in $aTrackingTable
				
					
					if ($DB_NAME == $db_array_value[ 'DB_NAME' ]) { 									// only write KLM data if the current array matches the above 
						
						$DB_GEOMETRY_COLUMN = $db_array_value[ 'DB_GEOMETRY_COLUMN' ];					// get the name of the column with the mySQL GEOMETRY information
						$BOUNDING_BOX_SEARCH = "SELECT * FROM ".$DB_NAME." WHERE MBRContains(GeomFromText('LineString($lng_1 $lat_1, $lng_2 $lat_2)'), $DB_GEOMETRY_COLUMN )"; 	// generate a MySQL search query string, to select the whole row for everything in our bounding box
						
						$BOUNDING_BOX_SEARCH_RESULTS = mysql_query( $BOUNDING_BOX_SEARCH, $radius_report_database_connection );		// search and select out the rows within our bounding box
			
						while ( $row = @mysql_fetch_assoc( $BOUNDING_BOX_SEARCH_RESULTS ) ) { 	// Iterates through the MySQL results row by row, creating one Placemark for each
							
							// Creates a Placemark and append it to the Document.
							$node = $dom->createElement('Placemark');
								$placeNode = $docNode->appendChild($node);
							
							// Creates a Point element.
							$pointNode = $dom->createElement('Point');
								$placeNode->appendChild($pointNode);
							
							//Creates a 'coordinates' element and gives it the value of the lng and lat columns from the results
							$coorStr = $row[ $db_array_value[ 'DB_LON_COLUMN' ] ] . ','  . $row[ $db_array_value[ 'DB_LAT_COLUMN' ] ];
							$coorNode = $dom->createElement('coordinates', $coorStr);
								$pointNode->appendChild($coorNode);
								
						} // end while
									
					} // end inner if
					
				} // end inner foreach
	
			} // end outer if
		
		} // end outer foreach
		
		
	mysql_close( $radius_report_database_connection );											// close the open database in the link identifier: $radius_report_database_connection
	update_user_meta($user_id, "radius_report_user_max_search_radius", $max_search_radius); 	// the max search radius is referenced when deciding what bounding box to get from the OSM server (i.e. how big of a map to get)

	 
	$kmlOutput = $dom->saveXML();											// output DOM as XML/KML
	header('Content-type: application/vnd.google-earth.kml+xml');			// tell the client what format the output file is
	echo $kmlOutput;														// return our xml/kml results to the client who called this file


?>