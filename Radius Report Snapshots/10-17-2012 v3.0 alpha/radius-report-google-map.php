<?php

/** 
 * ==================================================================================================
 *
 *	Reference: http://seriouscodage.blogspot.com/2010/11/visualizing-data-as-circles-in-google.html
 *  TODO: REMOVE KML rendering code: geoxml3.js & ProjectedOverlay.js for live release
 *  
 * ==================================================================================================
 **/


function radius_report_printmap() {

	// grab the current user id object
	$current_user = wp_get_current_user();
	
	// check user credentials 
    if ( !($current_user instanceof WP_User) ) { return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See: " . __FILE__); }
	else { $user_id = $current_user->ID; }
    
    
    $aUserInput = get_user_meta( $user_id, "radius_report_user_input", true );													// user specified map options keyed to 'radius_report_user_input'
	
	$request_url = "http://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode( $aUserInput[ "radius_report_site_address" ] ) . "&sensor=false";			// create a formatted google geolocation request URL for 'Google Maps API Web Services'
		
	$json_string = file_get_contents( $request_url );																			// send the formatted geolocation request URL, which returns a JSON string
	$json_object = json_decode( $json_string );																					// use PHP's json_decode function to return a php object
	//error_log(print_r($json_object,1));
	
	$formatted_address = $json_object->results[0]->formatted_address;															// parse the php object for the formatted_address key
	
	// parse the php object for the lat and lng keys
	$lat_site = $json_object->results[0]->geometry->location->lat;
	$lng_site = $json_object->results[0]->geometry->location->lng;

	update_user_meta( $user_id, "radius_report_user_site", array( 'site_lat' => $lat_site, 'site_lng' => $lng_site ) );

	$language =  "en";
	$zoom_level =  "17";
	$map_type =  "ROADMAP";

	// Setup the content box for the site marker and strip out all unallowed characters
	$content_box =  "Site Location: " . $formatted_address . " <br/> Geolocation: " . $lat_site . ":" . $lng_site;
	$content_box = str_replace('&lt;', '<', $content_box);
	$content_box = str_replace('&gt;', '>', $content_box);
	$content_box = mysql_escape_string($content_box);

	
	return "
	<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false&language=".$language."'></script>
	<script type='text/javascript' src='" . RR_HTTP_HOST . "/wp-content/plugins/radius-report/lib/js/geoxml3.js'></script>
	<script type='text/javascript' src='" . RR_HTTP_HOST . "/wp-content/plugins/radius-report/lib/js/ProjectedOverlay.js'></script>
	<script type='text/javascript'>
	//<![CDATA[
	
	
		function makeMap() {
			var latlng = new google.maps.LatLng(".$lat_site.", ".$lng_site.");
			
			var myOptions = {
				zoom: ".$zoom_level.",
				center: latlng,
				mapTypeControl: true,
				mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
				scaleControl: true,
				navigationControl: true,
				navigationControlOptions: {style: google.maps.NavigationControlStyle.ZOOM_PAN},
				mapTypeId: google.maps.MapTypeId.".$map_type."
				
			};
			var map = new google.maps.Map(document.getElementById('RadiusReportMap'), myOptions);
			
			// this isn't understood by our local geoXML3.parser - only by the google maps api
			//var infowindow = new google.maps.InfoWindow();
			//infowindow.close();
			
			// creates the parcast image as a site marker
			var site_marker = new google.maps.MarkerImage('/wp-content/plugins/radius-report/images/radius-report-icon_14x13.png',
					// indicates the size of this image
				new google.maps.Size(14, 13),
					// indicates the origin for this image
				new google.maps.Point(0, 0),
					// indicates the anchor point for this image
				new google.maps.Point(7, 6));
			
			// adds the parcast site marker to the map
			var marker = new google.maps.Marker({
				map: map,
				position: latlng,
				icon: site_marker
			});
			
			var circle_quarter_mile = new google.maps.Circle({
				map: map,
				radius: 403,
				fillColor: '#AA0000',
				fillOpacity:0,
				strokeColor: '#333333',
				strokeWeight: 1
			  
			});
			
			var circle_half_mile = new google.maps.Circle({
				map: map,
				radius: 806,
				fillColor: '#AA0000',
				fillOpacity:0,
				strokeColor: '#333333',
				strokeWeight: 1
			  
			});
			
			var circle_full_mile = new google.maps.Circle({
				map: map,
				radius: 1612,
				fillColor: '#AA0000',
				fillOpacity:0,
				strokeColor: '#333333',
				strokeWeight: 1
			  
			});
			
			circle_quarter_mile.bindTo('center', marker, 'position');
			circle_half_mile.bindTo('center', marker, 'position');
			circle_full_mile.bindTo('center', marker, 'position');
		
			
			var myParserOptions = {
				map: map,
				markerOptions: {clickable: false, icon: '" . RR_HTTP_HOST . "/wp-content/plugins/radius-report/images/googleMap_icon_small.png'},
				singleInfoWindow: true
			};
			
			var myParser = new geoXML3.parser( myParserOptions );
			myParser.parse('" . RR_HTTP_HOST . "/wp-content/plugins/radius-report/radius-report-google-map-kmlgen.php');
			
			
			
			// references google KML live rendering engine uncomment when ready to go live
			//var radius_report_markerLayer = new google.maps.KmlLayer('" . RR_HTTP_HOST . "/wp-content/plugins/radius-report/radius-report-google-map-kmlgen.php/wp-content/plugins/radius-report/radius-report-google-map-kmlgen.kml');
			//radius_report_markerLayer.setMap(map);
			
			
		}
		window.onload = makeMap;
		
	//]]>
	</script>

	";


} // end function


?>