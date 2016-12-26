<?php

/**
 * Generates a PDF report of users data
 * @uses TCPDF Package
 * @param string | $execute_time
 * @param string | $local_user_path
 * @param string | $base_filename
 * @see http://www.tuxradar.com/practicalphp/5/11/0 and http://nl.php.net/serialize
 */
function radius_report_create_pdf( $execute_time, $local_user_path, $base_filename, $iDetailScaleDenominator, $iOverviewScaleDenominator, $iMaxradScaleDenominator ){

	//xdebug_start_trace("/Applications/MAMP/htdocs/webgrind/xdebug/radius-report-generator-TCPDF.php");
	// for performance issues see: http://www.tcpdf.org/performances.php
	
	@unlink($local_user_path . ".tmp/.updater/" . $base_filename . " - Generating maps..."); // remove the old updater file
	touch($local_user_path . ".tmp/.updater/" . $base_filename . " - Generating PDF...");
	
	$iRefTime = microtime(true);
	error_log("------------- STARTING TCDPF @ t = 0 ms");
	
	$bigArray = unserialize( file_get_contents( "$local_user_path.tmp/$execute_time/bigArray" ) );
	$siteArray = $bigArray["SITE"];
		$lat_site = $siteArray[ "site_lat" ];
		$lng_site = $siteArray[ "site_lng" ];
	$optionsArray = $bigArray["INPUT"];
	$trackingTableArray = $bigArray["TABLE"];
	$dataArray = $bigArray["DATA"]; 

	/**
	 * ================================== Begin PDF Template ============================================
	 * 
	 *  This initial class definition extends the TCPDF classes to our own and defines or
	 *	re-defines some standard functions in our new "MYPDF" class
	 *
	 *	NOTE: All measurements are in millimeters (mm)
	 * 
	 */
	
	require_once( dirname(__FILE__) . "/radius-report-generator-TCPDF-functions.php" );
	require_once( dirname(__FILE__) . "/lib/tcpdf/config/tcpdf_config.php" );
	require_once( dirname(__FILE__) . "/lib/tcpdf/config/lang/eng.php" );
	require_once( dirname(__FILE__) . "/lib/tcpdf/tcpdf.php" );

	class MYPDF extends TCPDF {
				
		/** Header **/

			public function Header() { }
								
		/** Footer **/
			
			public function Footer() {
				$this->SetY(-15);
				$this->SetFont("HELVETICA", "I", 8);
				$this->Cell(0, 5, 'page '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, false, 'R');	// add page numbers
				$this->SetAutoPageBreak(true, 15);
			}
				
		/** CreateTextBox Override **/
				
			public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 8, $fontstyle = "", $align = "L") {
				$this->SetXY( $x+10, $y ); // respect left margin
				$this->SetFont( "HELVETICA", $fontstyle, $fontsize );
				$this->Cell( $width, $height, $textval, 0, false, $align );
			}
				
	} // end CLASS
		

	
	/**
	 * ============================== Class Instantiation & Document Defaults ========================================
	 */
		    
			$oPDF = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);	// create a new PDF object
			$oPDF->SetCreator("TCPDF");																	// set document (meta) information
			$oPDF->SetAuthor("Parcel Forecast, LLC.");													// set author title
			$oPDF->SetTitle("Radius Map Report");														// set Doc title
			$oPDF->SetFont("HELVETICA", "", 9);															// set font from this point on
			$oPDF->SetDefaultMonospacedFont("HELVETICA");												// set monospace font
			$oPDF->SetMargins(10, 10, 10, true); 														// (PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT)
			$oPDF->SetHeaderMargin(10); 																// (PDF_MARGIN_HEADER)
			$oPDF->SetFooterMargin(10); 																// (PDF_MARGIN_FOOTER)
			
			
	/**
	 * ================================== Cover Page =====================================
	 */
			
			$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
			error_log("------------- CREATING COVER PAGE @ $iTimeNow ms");
			
			$oPDF->AddPage();						// add a page
			$oPDF->SetFont("HELVETICA", "", 9);															// set font from this point on
			$oPDF->Bookmark("Cover Page", 0, 0);	// add bookmark

			//   				 											$x,		$y,		$w,		$h,		$type,	$link,	$align,	$resize,	$dpi,	$palign, (boolean) $ismask, (mixed) $imgmask, (mixed) $border, (mixed) $fitbox, (boolean) $hidden, (boolean) $fitonpage)
			$oPDF->Image( dirname(__FILE__) . "/images/report_header.png",	"",		"", 	"",		20, 	"PNG",	"",		"T",	true,		300,	"L"	);
			$oPDF->CreateTextBox("Radius Map Report",															0, 118, 0, 10, 16, "", "C");	// create text box
			$oPDF->CreateTextBox(date("m / d / Y"), 															0, 125, 0, 10, 12, "", "C");	// set date
			$oPDF->CreateTextBox("Project Reference: " .$optionsArray[ "radius_report_project_reference" ],		0, 131, 0, 10, 12, "", "C");	// project ref
			$oPDF->CreateTextBox("Site Name: " .$optionsArray[ "radius_report_site_name" ],						0, 137, 0, 10, 12, "", "C");	// site name
			$oPDF->CreateTextBox("Site Location: " .$optionsArray[ "radius_report_site_address" ],				0, 143, 0, 10, 12, "", "C");	// site location
			$oPDF->setPrintFooter(false);																										// dont print footer on this page
				
	/**
	 * ================================== Page 2 > TOC ON LAST PAGE ===========================================
	 */

			
	/**
	 * ================================== Page 3 - 1/4 mile Detail Map ============================================
	 */
			
			// $iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
			// error_log("------------- CREATING 1/4 MILE DETAIL MAP @ $iTimeNow ms");
			// 
			// $oPDF->AddPage();											// add a page
			// $oPDF->SetFont("HELVETICA", "", 9);							// set font from this point on
			// $oPDF->Bookmark("1/4 Mile Radius Detail Map", 0, 0);		// add bookmark
			// $oPDF->SetFont("HELVETICA", "", 12);						// set font from this point on
			// 		
			// $map_title = '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			// 			  <tr>
			// 			    <th height="40" colspan="3" align="center" valign="middle" scope="col"><h2><strong>1/4 MILE RADIUS DETAIL MAP</strong></h2></th>
			// 			  </tr>
			// 			  <tr>
			// 			    <td width="5%" align="left" valign="middle"><p>&nbsp;</p></td>
			// 			    <td width="90%" align="left" valign="middle">&nbsp;</td>
			// 			    <td width="5%" align="left" valign="middle">&nbsp;</td>
			// 			  </tr>
			// 		 	</table>';
			// $oPDF->writeHTML( $map_title, true, false, false, false, "" );			// write out html for map title
			// 
			// //																				(mm)	(mm)	(mm)	(mm)										(px)	
			// //    				 															$x 		$y,		$w,		$h,		$type,	$link,	$align,	$resize,	$dpi,	$palign,	$ismask,	$imgmask,	$border,	$fitbox,	$hidden,	$fitonpage );
			// $oPDF->Image( $local_user_path. ".tmp/" .$execute_time. "/detail.png",			"", 	"27", 	210,	"",		"PNG",	"",		"T", 	true, 		218, 	"C",		"",			"",			"",			"",			"",			""	);
			// 
			// // add the map legend
			// // ■ ▼ ● ↑ <br /> N
			// $map_scale_detail = round( $iDetailScaleDenominator * .25 / 2);
			// $oPDF->SetXY(0, 240);
			// $oPDF->SetFont("DEJAVUSANS", "", 12);												// set font from this point on
			// $map_legend = '<table width="1050" border="0" cellspacing="0" cellpadding="3">
			// 				<tr>
			// 				<td><table width="95%" border="0" cellspacing="0" cellpadding="3">
			// 				  <tr>
			// 				    <td valign="middle"><div align="right">RELEASE: <span style="color: #F00"> ■ </span></div></td>
			// 				    <td valign="middle"><div align="center">USE: <span style="color: #F9F509"> ▼ </span></div></td>
			// 				    <td valign="middle"><div align="left">USE RESTRICTION: <span style="color: #0F0"> ● </span></div></td>
			// 				  </tr>
			// 				</table></td>
			// 				<td rowspan="2" width="30" align="left" valign="bottom">↑ <br /> N</td>
			// 				</tr>
			// 				<tr>
			// 				<td><table width="95%" border="0" cellspacing="0" cellpadding="3">
			// 				  <tr>
			// 				    <td align="right" valign="middle">0</td>
			// 				    <td width="'.$map_scale_detail.'">&nbsp;</td>
			// 				    <td  align="left" valign="middle">1/4 mile</td>
			// 				  </tr>
			// 				  <tr>
			// 				    <td></td>
			// 				    <td><hr width="'.$map_scale_detail.'" /></td>
			// 				    <td></td>
			// 				  </tr>
			// 				</table></td>
			// 				</tr>
			// 				</table>';
			// $oPDF->writeHTML( $map_legend, true, false, false, false, "" );			// write out html for map legend
			// $oPDF->setPrintFooter( true );											// print footer on this page

				
	/**
	 * ================================== Page 4 - Overvew Map ============================================
	 */
			
			//  			$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
			// error_log("------------- CREATING 1 MILE OVERVIEW MAP @ $iTimeNow ms");
			// 
			// $oPDF->AddPage();											// add a page
			// $oPDF->SetFont("HELVETICA", "", 9);							// set font
			// $oPDF->Bookmark("1 Mile Radius Overview Map", 0, 0);		// add bookmark
			// $oPDF->SetFont("HELVETICA", "", 12);						// set font
			// $map_title = '<table width="100%" border="0" cellspacing="0" cellpadding="3">
			// 			  <tr>
			// 			    <th height="40" colspan="3" align="center" valign="middle" scope="col"><h2><strong>1 MILE RADIUS OVERVIEW MAP</strong></h2></th>
			// 			  </tr>
			// 			  <tr>
			// 			    <td width="5%" align="left" valign="middle"><p>&nbsp;</p></td>
			// 			    <td width="90%" align="left" valign="middle">&nbsp;</td>
			// 			    <td width="5%" align="left" valign="middle">&nbsp;</td>
			// 			  </tr>
			// 		 </table>';
			// $oPDF->writeHTML($map_title, true, false, false, false, '');		// write out html for map title
			// 
			// //$dpi_overview ;
			// //																				(mm)	(mm)	(mm)	(mm)										(px)	
			// //    				 															$x 		$y,		$w,		$h,		$type,	$link,	$align,	$resize,	$dpi,	$palign,	$ismask,	$imgmask,	$border,	$fitbox,	$hidden,	$fitonpage );
			// $oPDF->Image( $local_user_path. ".tmp/" .$execute_time. "/overview.png",		"", 	"27", 	210,	"",		"PNG",	"",		"T", 	true, 		300, 	"C",		"",			"",			"",			"",			"",			""	);
			// 
			// // add the map legend
			// // ■ ▼ ● ↑ <br /> N
			// $map_scale_overview = round( $iOverviewScaleDenominator * .5 / 2);
			// $oPDF->SetXY(0, 240);
			// $oPDF->SetFont("DEJAVUSANS", "", 12);												// set font from this point on
			// $map_legend = '<table width="1050" border="0" cellspacing="0" cellpadding="3">
			// 							<tr>
			// 							<td><table width="95%" border="0" cellspacing="0" cellpadding="3">
			// 							  <tr>
			// 							    <td valign="middle"><div align="right">RELEASE: <span style="color: #F00"> ■ </span></div></td>
			// 							    <td valign="middle"><div align="center">USE: <span style="color: #F9F509"> ▼ </span></div></td>
			// 							    <td valign="middle"><div align="left">USE RESTRICTION: <span style="color: #0F0"> ● </span></div></td>
			// 							  </tr>
			// 							</table></td>
			// 							<td rowspan="2" width="30" align="left" valign="bottom">↑ <br /> N</td>
			// 							</tr>
			// 							<tr>
			// 							<td><table width="95%" border="0" cellspacing="0" cellpadding="3">
			// 							  <tr>
			// 							    <td align="right" valign="middle">0</td>
			// 							    <td width="'.$map_scale_overview.'">&nbsp;</td>
			// 							    <td  align="left" valign="middle">1 mile</td>
			// 							  </tr>
			// 							  <tr>
			// 							    <td></td>
			// 							    <td><hr width="'.$map_scale_overview.'" /></td>
			// 							    <td></td>
			// 							  </tr>
			// 							</table></td>
			// 							</tr>
			// 							</table>';
			// $oPDF->writeHTML( $map_legend, true, false, false, false, "" );			// write out html for map legend
			// $oPDF->setPrintFooter( true );											// print footer on this page
				
	/**
	 * ================================== Page 5 - Largest Search Radius Overvew Map ============================================
	 */

		// if ( $iMaxradScaleDenominator != false ) { 
		// 	
		// 			$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
		// 			error_log("------------- CREATING MAXRAD MAP @ $iTimeNow ms");
		// 				
		// 			$oPDF->AddPage();												// add a page
		// 			$oPDF->SetFont("HELVETICA", "", 9);							// set font
		// 			$oPDF->Bookmark("Maximum Search Radius Overview Map", 0, 0);	// add bookmark
		// 			$oPDF->SetFont("HELVETICA", "", 12);							// set font
		// 			$map_title = '<table width="100%" border="0" cellspacing="0" cellpadding="3">
		// 							  <tr>
		// 							    <th height="40" colspan="3" align="center" valign="middle" scope="col"><h2><strong>MAX SEARCH RADIUS OVERVIEW MAP</strong></h2></th>
		// 							  </tr>
		// 							  <tr>
		// 							    <td width="5%" align="left" valign="middle"><p>&nbsp;</p></td>
		// 							    <td width="90%" align="left" valign="middle">&nbsp;</td>
		// 							    <td width="5%" align="left" valign="middle">&nbsp;</td>
		// 							  </tr>
		// 						 </table>';
		// 			$oPDF->writeHTML($map_title, true, false, false, false, '');		// write out html for map title
		// 				
		// 			//$dpi_overview ;
		// 			//																				(mm)	(mm)	(mm)	(mm)										(px)
		// 			//    				 															$x 		$y,		$w,		$h,		$type,	$link,	$align,	$resize,	$dpi,	$palign,	$ismask,	$imgmask,	$border,	$fitbox,	$hidden,	$fitonpage );
		// 			$oPDF->Image( $local_user_path. ".tmp/" .$execute_time. "/maxrad.png",			"", 	"27", 	210,	"",		"PNG",	"",		"T", 	true, 		300, 	"C",		"",			"",			"",			"",			"",			""	);
		// 				
		// 			// add the map legend
		// 			// ■ ▼ ● ↑ <br /> N
		// 			$map_scale_maxrad = round( $iMaxradScaleDenominator * 1 / 2);
		// 			$oPDF->SetXY(0, 240);
		// 			$oPDF->SetFont("DEJAVUSANS", "", 12);												// set font from this point on
		// 			$map_legend = '<table width="1050" border="0" cellspacing="0" cellpadding="3">
		// 							<tr>
		// 							<td><table width="95%" border="0" cellspacing="0" cellpadding="3">
		// 							  <tr>
		// 							    <td valign="middle"><div align="right">RELEASE: <span style="color: #F00"> ■ </span></div></td>
		// 							    <td valign="middle"><div align="center">USE: <span style="color: #F9F509"> ▼ </span></div></td>
		// 							    <td valign="middle"><div align="left">USE RESTRICTION: <span style="color: #0F0"> ● </span></div></td>
		// 							  </tr>
		// 							</table></td>
		// 							<td rowspan="2" width="30" align="left" valign="bottom">↑ <br /> N</td>
		// 							</tr>
		// 							<tr>
		// 							<td><table width="95%" border="0" cellspacing="0" cellpadding="3">
		// 							  <tr>
		// 							    <td align="right" valign="middle">0</td>
		// 							    <td width="'.$map_scale_maxrad.'">&nbsp;</td>
		// 							    <td  align="left" valign="middle">2 miles</td>
		// 							  </tr>
		// 							  <tr>
		// 							    <td></td>
		// 							    <td><hr width="'.$map_scale_maxrad.'" /></td>
		// 							    <td></td>
		// 							  </tr>
		// 							</table></td>
		// 							</tr>
		// 							</table>';
		// 			$oPDF->writeHTML( $map_legend, true, false, false, false, "" );			// write out html for map legend
		// 			$oPDF->setPrintFooter( true );											// print footer on this page
		// 	
		// } // end if
				
				
	/**
	 * ========================================== Page 6 - Tabular Summary  ================================================
	 */
				$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
				error_log("------------- CREATING TABULAR SUMMARY @ $iTimeNow ms");	
				
				
				$oPDF->AddPage();										// add a new page
				$oPDF->Bookmark("Tabular Summary", 0, 0);				// add bookmark
				makeTitleHeader($oPDF, "TABULAR SUMMARY");				// adds a standard title header
				$aTabularSummary = makeTabularSummary( $bigArray );		// create a tabbular summary array
				$oPDF->SetFont("HELVETICA", "", 9);						// set font from this point on
							
				$table =	'<table width="100%" border="1" cellspacing="0" cellpadding="3">
							  <tr>
							    <td width="44%" align="left" valign="middle">Primary Database Listing:</td>
							    <td width="8%" align="center" valign="middle">Search Radius (miles)</td>
							    <td width="8%" align="center" valign="middle">&lt; 0.25</td>
							    <td width="8%" align="center" valign="middle">0.25 to 0.50</td>
							    <td width="8%" align="center" valign="middle">0.50 to 0.75</td>
							    <td width="8%" align="center" valign="middle">0.75 to 1.00</td>
							    <td width="8%" align="center" valign="middle">&gt; 1.00</td>
							    <td width="8%" align="center" valign="middle">Total by Type</td>
							  </tr>
							</table>';
				
				
				foreach ( $aTabularSummary as $key => $array ) {

					if ( "$key" != "TOTALS" ) {
						
							$table .=	'<table width="100%" border="1" cellspacing="0" cellpadding="3">
										  <tr>
										    <td width="44%" align="left" valign="middle">' .$array[0]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[1]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[2]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[3]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[4]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[5]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[6]. '</td>
										    <td width="8%" align="center" valign="middle">' .$array[7]. '</td>
										  </tr>
										</table>';
							
					} // end if
					
				} // end foreach
				
				
				$table .=	'<table width="100%" border="1" cellspacing="0" cellpadding="3">
							  <tr>
							    <td width="44%" align="left" valign="middle">Table Totals</td>
							    <td width="8%" align="center" valign="middle"> - </td>
							    <td width="8%" align="center" valign="middle">' .$aTabularSummary["TOTALS"][0]. '</td>
							    <td width="8%" align="center" valign="middle">' .$aTabularSummary["TOTALS"][1]. '</td>
							    <td width="8%" align="center" valign="middle">' .$aTabularSummary["TOTALS"][2]. '</td>
							    <td width="8%" align="center" valign="middle">' .$aTabularSummary["TOTALS"][3]. '</td>
							    <td width="8%" align="center" valign="middle">' .$aTabularSummary["TOTALS"][4]. '</td>
							    <td width="8%" align="center" valign="middle">' .$aTabularSummary["TOTALS"][5]. '</td>
							  </tr>
							</table>';
				
				$oPDF->writeHTML( $table, false, false, false, false, "" );			// write out the html
				unset( $table );													// flush
	
				
				
	/**
	 * ================================== Match Summary (n pages) ============================================
	 */
				$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
				error_log("------------- CREATING MATCH SUMMARY @ $iTimeNow ms");

				$oPDF->AddPage();									// add a new page
				$oPDF->Bookmark("Match Summary", 0, 0);				// add bookmark
				makeTitleHeader($oPDF, "MATCH SUMMARY");			// adds a standard title header
				$aMatchSummary = makeMatchSummary( $dataArray );	// create match summary array
				$oPDF->SetFont("HELVETICA", "", 9);					// set font from this point on
				
				$table =	'<table width="100%" border="1" cellspacing="0" cellpadding="3">
							  <tr>
							    <td width="6%" align="center">Map ID</td>
							    <td width="8%" align="center">Distance (miles)</td>
							    <td width="8%" align="center">Direction</td>
							    <td width="54%" align="center">Site Location</td>
							    <td width="24%" align="center">Primary Database</td>
							  </tr>
							</table>';

				
				foreach ( $aMatchSummary as $key => $matchArray ) {
					
					$table .=	'<table width="100%" border="1" cellspacing="0" cellpadding="3">
								  <tr>
								    <td width="6%" align="center">' .$matchArray[0]. '</td>
								    <td width="8%" align="center">' .$matchArray[1]. '</td>
								    <td width="8%" align="center">' .$matchArray[2]. '</td>
								    <td width="54%" align="left">' .$matchArray[3]. '<br />' .$matchArray[4]. '</td>
								    <td width="24%" align="center">' .$matchArray[5]. '</td>
								  </tr>
								</table>';
					
					
					
				} // end foreach
				
				$oPDF->writeHTML( $table, true, false, false, false, "" );		// write out the html
				unset( $table );												// flush
	

	/**
	 * ================================== Match Details (n pages) ============================================
	 */
				$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
				error_log("------------- CREATING MATCH DETAIL @ $iTimeNow ms");				

				$oPDF->AddPage();												// add a page
				$oPDF->Bookmark("Match Details", 0, 0);							// add bookmark
				makeTitleHeader($oPDF, "MATCH DETAILS");						// adds a standard title header
				$oPDF->SetFont("HELVETICA", "", 9); 							// set font from this point on
				makeReportData( $oPDF, $dataArray );							// get table details HTML
				
	/**
	 * ==================================  DISCLAIMER  ============================================
	 */
				$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
				error_log("------------- CREATING DISCLAIMER @ $iTimeNow ms");				
				
				$oPDF->AddPage();												// add a new page
				$oPDF->Bookmark("Disclaimer", 0, 0); 							// add bookmark
				makeDisclaimer( $oPDF );										// add the static disclaimer
				
				
	/**
	 * ==================================  GOVT RECORD TRACKING  ============================================
	 */
				$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
				error_log("------------- CREATING GOV TRACKING @ $iTimeNow ms");
				
				$oPDF->AddPage(); 														// add a page
				$oPDF->Bookmark("Government Record Tracking", 0, 0);					// add bookmark 
				$oPDF->SetFont("HELVETICA", "", 12);									// set font from this point on
				makeGovTracking( $oPDF, $bigArray ); 									// get HTML
				unset( $record_tracking );												// flush	

				
	/**
	 * ================================== TOC (MUST BE ADDED LAST) ============================================
	 */
				$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
				error_log("------------- CREATING TOC @ $iTimeNow ms");
				
				$oPDF->addTOCPage();													// add a new page for TOC
				$oPDF->SetMargins(10, 10, 20, true); 									// (PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT)
				$oPDF->setPrintFooter(false);											// do not print footer on TOC
				makeTitleHeader( $oPDF, "TABLE OF CONTENTS <br /> <br />" );			// adds a standard title header
				$oPDF->SetFont("HELVETICA", "", 14);									// set font from this point on
				$oPDF->addTOC( 2, "HELVETICA", "- ", "Table of Contents" ); 			// add a simple Table Of Content at first page (check the example n. 59 for the HTML version)
				$oPDF->endTOCPage();													// end of TOC page
				

				
			
//$oPDF->writeHTML( $table, false, false, false, false, "" );		
//error_log( "ROW SUMMARY: " . print_r( $rowSummary, 1 ) );
//error_log( "DB NAME ARRAY: " . print_r( $dbNameArray, 1 ) );
//error_log( "BIG ARRAY: " . print_r( $bigArray, 1 ) );

writePDF( $oPDF, $local_user_path, $base_filename );

$iTimeNow = round( ( (microtime(true) - $iRefTime) * 1000));
error_log("------------- DONE @ $iTimeNow ms");

} // end main function


/**
 * Creates the html formatted report details.
 * @param object $oPDF
 * @param array $dataArray
 * @return string $sReportData
 */
function makeReportData( $oPDF, $dataArray ) {
	
	$matchDetail = makeMatchDetail( $dataArray ); // make the header details for all the sites
	
	foreach ( $dataArray as $nKey => $aSite ) {
		
		$table = "";
		
			$table .=	 '<table width="100%" border="1" cellspacing="0" cellpadding="3">
						  <tr>
						    <td width="11%" align="left" bgcolor="#EEEEEE">Map ID: ' .$matchDetail[$nKey][0]. '</td>
						    <td width="11%" align="left" bgcolor="#EEEEEE">DIST: ' .$matchDetail[$nKey][1]. '</td>
						    <td width="11%" align="left" bgcolor="#EEEEEE">DIR: ' .$matchDetail[$nKey][2]. '</td>
						    <td width="15%" align="left" bgcolor="#EEEEEE">LAT: ' .$matchDetail[$nKey][3]. '</td>
						    <td width="15%" align="left" bgcolor="#EEEEEE">LON: ' .$matchDetail[$nKey][4]. '</td>
						    <td width="37%" align="left" bgcolor="#EEEEEE">Primary DB: ' .$matchDetail[$nKey][5]. '</td>
						  </tr>
						  <tr>
						    <td align="right" valign="top" bgcolor="#EEEEEE">Site Name:</td>
						    <td colspan="5" align="left" valign="top" bgcolor="#EEEEEE">' .$matchDetail[$nKey][6]. '</td>
						  </tr>
						  <tr>
						    <td align="right" valign="top" bgcolor="#EEEEEE">Address:</td>
						    <td colspan="5" align="left" valign="top" bgcolor="#EEEEEE">' .$matchDetail[$nKey][7]. '</td>
						  </tr>
						</table>';
			
			
			$sReportData = processSite( $aSite );
		
			$table .= '<table width="100%" border="0" cellspacing="0" cellpadding="3">
						  <tr>
						    <td width="15%" align="right" valign="top">Details:</td>
						    <td width="85%" align="left" valign="top">' .$sReportData. '</td>
						  </tr>
						</table>';
		
		
			$oPDF->writeHTML( $table, true, false, false, false, "" );		// write out the html
			
		// unset( $table );
			
	} // end main foreach
	

} // end function 


/**
 * Process a site either generically or as a RCRAINFO site.
 * @param array $aSite
 * @return string $sReportData
 */
function processSite( $aSite ) {
	
	
	$sReportData = "";

	// IS ARRAY
	if ( is_array( $aSite["DB_NAME"] ) ) {
			
				/**
				 *	RCRAINFO || RCRAINFO_TSD_CORRACTS
				 */
					
					// NO RCRAINFO Sites are currently grouped together
				
				
				/**
				 *	UST_WA || LUST_WA
				 */
				
					if ( $aSite["DB_NAME"][0] == "UST_WA" || $aSite["DB_NAME"][0] == "LUST_WA" ) {  
						$sReportData .= processSiteLUST_UST( $aSite ); 
						return $sReportData; 
					}
				
					
					
				/**
				 *	Process multiple grouped sites generically i.e. using only REPORT_COLUMNS
				 */
				
					$aReportColumns = explode_trim( $aSite["REPORT_COLUMNS"][0] ); 																						// create numbered array of this sites REPORT_COLUMNS 
					foreach ( $aReportColumns as $key => $value ) { 																									// adds the generic columns from REPORT_COLUMNS	
						if ( !empty( $aSite[$value][0] ) && $aSite[$value][0] != "N/A" && $aSite[$value][0] != "NA" ) { 												// only process the current database field if it is not empty (i.e. NULL), or NA, N/A
							$sReportData .= "<b>" . $value . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite[$value][0] ) . "<br />"; 	// remove everything that is not a number, letter, space or #$%&()*+,./:;?@-
						}																																				// end if 
					}																																					// end foreach
					return $sReportData;																																// return data
				

			
	// NOT ARRAY
	} else if ( !is_array( $aSite["DB_NAME"] ) ) {
		
				/**
				 *	RCRAINFO || RCRAINFO_TSD_CORRACTS
				 */
					if ( $aSite["DB_NAME"] == "RCRAINFO" || $aSite["DB_NAME"] == "RCRAINFO_TSD_CORRACTS" ) {  
						$sReportData .= processSiteRCRAINFO( $aSite ); 
						return $sReportData; 
					} 
				
				
				/**
				 *	UST_WA || LUST_WA
				 */
					if ( $aSite["DB_NAME"] == "UST_WA" || $aSite["DB_NAME"] == "LUST_WA" ) {  

								$aReportColumns = explode_trim( $aSite["REPORT_COLUMNS"] ); // create numbered array of this sites REPORT_COLUMNS
								
								$sReportData .= "<b>Facility Site ID: </b>" . $aSite["FS_ID"] . "&nbsp;&nbsp;&nbsp;";
								$sReportData .= "<b>County: </b>" . $aSite["County"];
								$sReportData .= "<br /> <hr width='35%' /> <br />";
								
									foreach ( $aReportColumns as $key => $sColumnName ) { 	
										
										if ( !empty( $aSite[$sColumnName] ) && $aSite[$sColumnName] != "N/A" && $aSite[$sColumnName] != "NA" ) {  // only process the current database field if it is not empty (i.e. NULL), or NA, N/A
											
													if ( $sColumnName == "Groundwater" || $sColumnName == "SurfaceWater" || $sColumnName == "Soil" || $sColumnName == "Sediment" || $sColumnName == "Air" || $sColumnName == "Bedrock" ) {
																														
																switch ( trim( $aSite["$sColumnName"] ) ) {
																    case "C":
																        $sReportData .= "&nbsp;<b> $sColumnName: </b> Confirmed   "; // <br />
																        break;
																    case "S":
																        $sReportData .= "&nbsp;<b> $sColumnName: </b> Suspected   ";
																        break;
																    case "B":
																       	$sReportData .= "&nbsp;<b> $sColumnName: </b> Below MTCA Levels   ";
																        break;
																    case "R":
																        $sReportData .= "&nbsp;<b> $sColumnName: </b> Remediated   ";
																        break;    
																} // end switch
																
													} else {
														
														
														$sReportData .= "&nbsp;<b>" . $sColumnName . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite[$sColumnName] ) ;	// remove everything that is not a number, letter, space or #$%&()*+,./:;?@-
														
														
													} // end if Groudwater
										
										} // end if empty
										
									} // end foreach
									
								$sReportData .= "<br />";
						
						return $sReportData; 
						
					} // end if UST_WA || LUST_WA
				
				
				/**
				 *	UECA_WA
				 */
					if ( $aSite["DB_NAME"] == "UECA_WA" ) {  

								$aReportColumns = explode_trim( $aSite["REPORT_COLUMNS"] ); // create numbered array of this sites REPORT_COLUMNS
								
								$sReportData .= "<b>Facility Site ID: </b>" . $aSite["FacilitySiteId"] . "&nbsp;&nbsp;&nbsp;";
								$sReportData .= "<b>Cleanup Site ID: </b>" . $aSite["CleanupSiteId"] . "&nbsp;&nbsp;&nbsp;";
								$sReportData .= "<b>County: </b>" . $aSite["County"];
								$sReportData .= "<br /> <hr width='35%' /> <br />";
								
									foreach ( $aReportColumns as $key => $sColumnName ) { 	
										
										if ( !empty( $aSite[$sColumnName] ) && $aSite[$sColumnName] != "N" && $aSite[$sColumnName] != "0000-00-00" ) {  // only process the current database field if it is not empty (i.e. NULL), or NA, N/A
											
											if ( ($sColumnName != "FacilitySiteId") && ($sColumnName != "CleanupSiteId") && ($sColumnName != "County")) {	
											
												$sReportData .= "&nbsp;<b>" . $sColumnName . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite[$sColumnName] ) ;	// remove everything that is not a number, letter, space or #$%&()*+,./:;?@-
												$sReportData .= "<br />";
											}
										}
									}
									
								$sReportData .= "<br />";
						
						return $sReportData; 
						
					} // end if UECA_WA
				
					
				
				/**
				 *	Process single site generically i.e. using only REPORT_COLUMNS
				 */
					
					$aReportColumns = explode_trim( $aSite["REPORT_COLUMNS"] ); 																					// create numbered array of this sites REPORT_COLUMNS 
					foreach ( $aReportColumns as $key => $sColumnName ) { 																								// adds the generic columns from REPORT_COLUMNS	
						if ( !empty( $aSite[$sColumnName] ) && $aSite[$sColumnName] != "N/A" && $aSite[$sColumnName] != "NA" ) { 
							
							// only process the current database field if it is not empty (i.e. NULL), or NA, N/A
							$sReportData .= "<b>" . $sColumnName . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite[$sColumnName] ) . "<br />";	// remove everything that is not a number, letter, space or #$%&()*+,./:;?@-
						
						} // end if
						
					} // end foreach
					
			return $sReportData;
			
	} // end if   ARRAY / NOT ARRAY 
	
} // end function


/**
 * Handles groupings of MULTIPLE multiple sites.
 * @param array $aSite
 */
function processSiteLUST_UST( $aSite ) {

	//error_log( "aSite: " . print_r( $aSite ,1 ) );

	$sReportData = "";
	$aReportOrder = array();
	
	/**
	 *	List once for each site group UST_WA, LUST_WA
	 */

		$sReportData .= "<b>Facility Site ID: </b>" . $aSite["FS_ID"][0] . "&nbsp;&nbsp;&nbsp;";
		$sReportData .= "<b>County: </b>" . $aSite["County"][0] ;
		
	/**
	 *	Make a reference array for parsing the $aSite
	 */
		
		foreach ( $aSite["DB_NAME"] as $iIndex => $sType ) { $aReportOrder["$sType"][] = $aSite["REPORT_COLUMNS"][$iIndex]; } 
		//error_log( "REPORT ORDER ARRAY: " . print_r( $aReportOrder ,1 ) );
		
	/**
	 *	Reach into the aSite and pull out the data for each group UST_WA, LUST_WA
	 */	
		
		foreach ( $aReportOrder as $sKey => $aSubSite ) {
			
			foreach ( $aSubSite as $iSubIndex => $sReportColumns ) { 
				
				$sReportData .= "<br /> <hr width='35%' /> <br />";
				
				$aReportColumns = explode_trim( $sReportColumns );
				//error_log( "REPORT COLUMNS ARRAY n = $iSubIndex :: " . print_r( $aReportColumns ,1 ) );
				
				
					foreach ( $aReportColumns as $key => $sColumnName ) {
						
						if ( !is_array( $aSite["$sColumnName"] ) ) {
							
								if ( !empty( $aSite["$sColumnName"] ) ) { 	 // only process the current database field if it is not empty (i.e. NULL), or NA, N/A
									
											if ( $sColumnName == "Groundwater" || $sColumnName == "SurfaceWater" || $sColumnName == "Soil" || $sColumnName == "Sediment" || $sColumnName == "Air" || $sColumnName == "Bedrock" ) {
														
												//error_log( "IS ARRAY: " . $sColumnName . " VALUE: " . trim( $aSite["$sColumnName"] ) );
												
												switch ( trim( $aSite["$sColumnName"] ) ) {
												    case "C":
												        $sReportData .= "&nbsp;<b> $sColumnName: </b> Confirmed   "; // <br />
												        break;
												    case "S":
												        $sReportData .= "&nbsp;<b> $sColumnName: </b> Suspected   ";
												        break;
												    case "B":
												       	$sReportData .= "&nbsp;<b> $sColumnName: </b> Below MTCA Levels   ";
												        break;
												    case "R":
												        $sReportData .= "&nbsp;<b> $sColumnName: </b> Remediated   ";
												        break;    
												} // end switch
												
											} else {
												
												$sReportData .= "&nbsp;<b> $sColumnName: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite["$sColumnName"] ) ; 	// remove everything that is not a number, letter, space or #$%&()*+,./:;?@-
												
											} // end if switch
									
								} // end if array empty
							
							//error_log( "Column Data: " . print_r( $aSite["$sColumnName"][$iSubIndex], 1 ) );
							
						} else {

								if ( !empty( $aSite["$sColumnName"][$iSubIndex] ) ) { // only process the current database field if it is not empty (i.e. NULL), or NA, N/A
											
													if ( $sColumnName == "Groundwater" || $sColumnName == "SurfaceWater" || $sColumnName == "Soil" || $sColumnName == "Sediment" || $sColumnName == "Air" || $sColumnName == "Bedrock" ) {
																
														//error_log( "IS NOT ARRAY: " . $sColumnName . " VALUE: " . trim( $aSite["$sColumnName"][$iSubIndex] ) );
														
														switch ( trim( $aSite["$sColumnName"][$iSubIndex] ) ) {
														    case "C":
														        $sReportData .= "&nbsp;<b> $sColumnName: </b> Confirmed   ";
														        break;
														    case "S":
														        $sReportData .= "&nbsp;<b> $sColumnName: </b> Suspected   ";
														        break;
														    case "B":
														       	$sReportData .= "&nbsp;<b> $sColumnName: </b> Below MTCA Levels   ";
														        break;
														    case "R":
														        $sReportData .= "&nbsp;<b> $sColumnName: </b> Remediated   ";
														        break;    
														} // end switch
														
													} else {
														
														$sReportData .= "&nbsp;<b> $sColumnName: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite["$sColumnName"][$iSubIndex] ) . "   "; 	// remove everything that is not a number, letter, space or #$%&()*+,./:;?@-
																												
													} // end if switch
											
								} // end if array empty
								
								//error_log( "Column Data: " . print_r( $aSite["$sColumnName"], 1 ) ); 
								
						} // end if array test
							
						
					} // end inner foreach
					
					
			} // end inner foreach
			
			$sReportData .= "<br />";
			
		} // end outer foreach
		

	return $sReportData;
	
} // end function


/**
 * Process RCRAINFO sites.
 * @param array $aSite
 * @return string $sDetailRCRA
 */
function processSiteRCRAINFO( $aSite ) {
	
	$sDetailRCRA = "";
	
	if ( $aSite["DB_NAME"] == "RCRAINFO" || $aSite["DB_NAME"] == "RCRAINFO_TSD_CORRACTS" ) { 
	
			
		/**
		 * Displays HANDLER_ID
		 */
				
			$sDetailRCRA .= "<b>HANDLER ID: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite["HANDLER_ID"] ) . "<br />";
			
			// I DO NOT UNDERSTAND WHAT THIS DATE REALLY IS
			//$sDetailRCRA .= "<b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; LAST CHANGE: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite["HHANDLER_LAST_CHANGE"] ) . "<br />";
			
				
		/**
		 *	Handles Generator Codes (i.e. GENSTATUS)
		 */
		
				// RCRA designations unique to washington state
				if ( $aSite["STATE"] == "WA" ) {
					
					if ( !empty( $aSite["GENSTATUS"] )  ) {
					
						$GENSTATUS = trim( $aSite["GENSTATUS"] );
						
						switch ( $GENSTATUS ) {
						    case "N":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> No Designation (ND) <br />";
						        break;
						    case "CEG":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> Small Quantity Generator (SQG) / (EPA EQUIV: CESQG) <br />";
						        break;
						    case "SQG":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> Medium Quantity Generator (MQG) / (EPA EQUIV: SQG) <br />";
						        break;
						    case "LQG":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> Large Quantity Generator (LQG) <br />";
						        break;
						                 
						} // end switch
						
					} // end inner if
					
				} else {
					
					if ( !empty( $aSite["GENSTATUS"] )  ) {
					
						$GENSTATUS = trim( $aSite["GENSTATUS"] );
						
						switch ( $GENSTATUS ) {
						    case "N":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> ND <br />";
						        break;
						    case "CEG":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> CESQG <br />";
						        break;
						    case "SQG":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> SQG <br />";
						        break;
						    case "LQG":
						        $sDetailRCRA .= "<b>GENERATOR STATUS: </b> LQG <br />";
						        break;
						                 
						} // end switch
						
					} // end inner if
		
				} // end outer if
				
				
				
				
		/**
		 * Handles NAICS Codes
		 */
			
				if ( $aSite["NAIC1"] || $aSite["NAIC2"] || $aSite["NAIC3"] || $aSite["NAIC4"]) {
				
					$NAIC1 = convert_NIACS( $aSite["NAIC1"] );
					$NAIC2 = convert_NIACS( $aSite["NAIC2"] );
					$NAIC3 = convert_NIACS( $aSite["NAIC3"] );
					$NAIC4 = convert_NIACS( $aSite["NAIC4"] );
					
					if ( !empty( $NAIC1 ) ) { $sDetailRCRA .= "<b>" . "NAICS DESC" . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $NAIC1 ) . "<br />"; }
					if ( !empty( $NAIC2 ) ) { $sDetailRCRA .= "<b>" . "NAICS DESC" . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $NAIC2 ) . "<br />"; }
					if ( !empty( $NAIC3 ) ) { $sDetailRCRA .= "<b>" . "NAICS DESC" . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $NAIC3 ) . "<br />"; }
					if ( !empty( $NAIC4 ) ) { $sDetailRCRA .= "<b>" . "NAICS DESC" . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $NAIC4 ) . "<br />"; }
		
				} // end NAICS Codes
				
				
				
		/**
		 *	Displays Contact name and Email
		 */
				
				
				if ( !empty( $aSite["CONTACT_NAME"] ) || !empty( $aSite["CONTACT_EMAIL_ADDRESS"] ) ) {
				
					$CONTACT_NAME = $aSite["CONTACT_NAME"];
					$CONTACT_EMAIL_ADDRESS = $aSite["CONTACT_EMAIL_ADDRESS"];
					
					$sDetailRCRA .= "<b>CONTACT NAME: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $CONTACT_NAME ); 
					$sDetailRCRA .= "<b>&nbsp;&nbsp;&nbsp; EMAIL: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $CONTACT_EMAIL_ADDRESS ) . "<br />"; 
					
				} 
				
		
				
		/**
		 *	Punitive Actions (i.e. RCR_PUNIT_DETAIL_ACTIONS)
		 */
				
				if ( $aSite["RCR_PUNIT_DETAIL_ACTIONS"] != "0" && $aSite["RCR_PUNIT_DETAIL_ACTIONS"] != "") {
				
					$RCR_PUNIT_DETAIL_ACTIONS = $aSite["RCR_PUNIT_DETAIL_ACTIONS"];
					$RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST = $aSite["RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST"];
					$RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST = $aSite["RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST"];
					
					$sDetailRCRA .= "<b>" . "PUNITIVE ACTIONS" . ": </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $RCR_PUNIT_DETAIL_ACTIONS ) . "  => <i> EFFECTIVE DATE RANGE: $RCR_PUNIT_DETAIL_EFFECTIVE_DATE_FIRST</i> to  <i>$RCR_PUNIT_DETAIL_EFFECTIVE_DATE_LAST </i> <br />";
					
				} // end Punitive Actions
				
				
				
				
		/**
		 *	Corrective Actions
		 */
				
				if ( $aSite["SUBJCA"] == "Y" || $aSite["RCR_CA_EVENT_EVENT_SEQ"] != "" ) {
					
					if ( !empty( $aSite["SUBJCA"] ) ) { $sDetailRCRA .= "<b>SUBJECT TO CORRECTIVE ACTION: </b> YES <br />"; }
					
					if ( !empty( $aSite["RCR_CA_EVENT_RESPONSIBLE_AGENCY"] ) ) { 
						
						$RCR_CA_EVENT_RESPONSIBLE_AGENCY = $aSite["RCR_CA_EVENT_RESPONSIBLE_AGENCY"];
						
						switch ( $RCR_CA_EVENT_RESPONSIBLE_AGENCY ) {
						    case "E":
						        $sDetailRCRA .= "<b>   RESPONSIBLE AGENCY: </b> EPA <br />";
						        break;
						    case "S":
						        $sDetailRCRA .= "<b>   RESPONSIBLE AGENCY: </b> State <br />";
						        break;
						    case "J":
						        $sDetailRCRA .= "<b>   RESPONSIBLE AGENCY: </b> Joint EPA & State <br />";
						        break;
						} // end switch
					} // end if
					
					if ( !empty( $aSite["RCR_CA_EVENT_ACTUAL_DATE"] ) ) { $sDetailRCRA .= "<b>   ACTUAL DATE: </b> " . $aSite["RCR_CA_EVENT_ACTUAL_DATE"] . "<br />"; }
					if ( !empty( $aSite["CA_EVENT_DESC"] ) ) { $sDetailRCRA .= "<b>   DESCRIPTION: </b> " . $aSite["CA_EVENT_DESC"] . "<br />"; }
					
				} // end outer if

				
				
		/**
		 *	NON_NOTIFIER FLAG
		 */
				
				if ( trim( $aSite["NON_NOTIFIER"] ) == "X" ) { $sDetailRCRA .= "<b>" . "NON-NOTIFIER" . ": </b> YES <br />"; }
				
				
				
		/**
		 * USED_OIL FLAGS > 7 flags Y/N of the form: YYYNYYY
		 */
				
				if ( $aSite["USED_OIL"] != "NNNNNNN" ) {
					
					$USED_OIL = str_split( $aSite["USED_OIL"], 1 );
													
					if ( $USED_OIL["0"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Transporter: </b> YES <br />"; }
					if ( $USED_OIL["1"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Transfer Facility: </b> YES <br />"; }
					if ( $USED_OIL["2"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Processor: </b> YES <br />"; }
					if ( $USED_OIL["3"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Refiner: </b> YES <br />"; }
					if ( $USED_OIL["4"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Fuel Burner: </b> YES <br />"; }
					if ( $USED_OIL["5"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Fuel Marketer to Burner: </b> YES <br />"; }
					if ( $USED_OIL["6"] == "Y" ) { $sDetailRCRA .= "<b>  Used Oil Specification Marketer: </b> YES <br />"; }
					
				} // end USED_OIL FLAGS
				
				
				
		/**
		 * TSD/TSDF Activity
		 * 
		 *  L = Land Disposal e.g. landfill
		 *  I = Incineration
		 * 	B = Boiler/Industrial Furnace
	 	 *  S = Storage
		 *	T = Treatment
		 */
				
				// run this if at least one of these is not the default negative indicator
				if ( 
					$aSite["AS_FEDERALLY_REGULATED_TSDF"] != "-----" ||
					$aSite["AS_CONVERTER_TSDF"] != "-----" ||
					$aSite["AS_STATE_REGULATED_TSDF"] != "--------" ||
					$aSite["COMMERCIAL_TSD"] != "N" ||
					$aSite["TSD_TYPE"] != "-----" ||
					$aSite["SUBJCA_NON_TSD"] != "N" ||
					$aSite["SUBJCA_TSD_3004"] != "N" ||
					$aSite["SUBJCA_TSD_DISCRETION"] != "N" ||
					$aSite["OPERATING_TSDF"] != "-----"
				) {
					
					$sDetailRCRA .= "<b>TSD ACTIVITY: </b> YES <br />";
					
					if ( $aSite["TSD_TYPE"] != "-----" && !empty($aSite["TSD_TYPE"]) ) { 
						
						$sDetailRCRA .= "<b>   TSD TYPE(S) </b>"; 
					
						$TSD_TYPE = str_split( $aSite["TSD_TYPE"], 1 );
														
						if ( $TSD_TYPE["0"] == "L" ) { $sDetailRCRA .= "<i>: LAND DISPOSAL </i>"; }
						if ( $TSD_TYPE["1"] == "I" ) { $sDetailRCRA .= "<i>: INCINERATION </i>"; }
						if ( $TSD_TYPE["2"] == "B" ) { $sDetailRCRA .= "<i>: BOILER & INDUSTRIAL FURNACES </i>"; }
						if ( $TSD_TYPE["3"] == "S" ) { $sDetailRCRA .= "<i>: STORAGE </i>"; }
						if ( $TSD_TYPE["4"] == "T" ) { $sDetailRCRA .= "<i>: TREATMENT </i>"; }
						
						$sDetailRCRA .= "<br />";
						
						//if ( $aSite["OPERATING_TSDF"] != "-----"  				&& !empty($aSite["OPERATING_TSDF"]) ) 				{ $sDetailRCRA .= "<b>" . "   OPERATING_TSDF" . ": </b> " . $aSite["OPERATING_TSDF"] . " <br />"; }
						//if ( $aSite["AS_FEDERALLY_REGULATED_TSDF"] != "-----" 	&& !empty($aSite["AS_FEDERALLY_REGULATED_TSDF"]) ) 	{ $sDetailRCRA .= "<b>" . "   AS_FEDERALLY_REGULATED_TSDF" . ": </b> " . $aSite["AS_FEDERALLY_REGULATED_TSDF"] . " <br />"; }
						//if ( $aSite["AS_CONVERTER_TSDF"] != "-----"  			&& !empty($aSite["AS_CONVERTER_TSDF"]) ) 			{ $sDetailRCRA .= "<b>" . "   AS_CONVERTER_TSDF" . ": </b> " . $aSite["AS_CONVERTER_TSDF"] . " <br />"; }
						//if ( $aSite["AS_STATE_REGULATED_TSDF"] != "--------"  	&& !empty($aSite["AS_STATE_REGULATED_TSDF"]) ) 		{ $sDetailRCRA .= "<b>" . "   AS_STATE_REGULATED_TSDF" . ": </b> " . $aSite["AS_STATE_REGULATED_TSDF"] . " <br />"; }
					}
					
					
					if ( $aSite["COMMERCIAL_TSD"] != "N"  					&& !empty($aSite["COMMERCIAL_TSD"]) ) 				{ $sDetailRCRA .= "<b>   COMMERCIAL TSD: </b> YES <br />"; }
					if ( $aSite["SUBJCA_NON_TSD"] != "N" 					&& !empty($aSite["SUBJCA_NON_TSD"]) ) 				{ $sDetailRCRA .= "<b>   SUBJ TO CA NON-TSD : </b> YES <br />"; }
					if ( $aSite["SUBJCA_TSD_3004"] != "N"  					&& !empty($aSite["SUBJCA_TSD_3004"]) ) 				{ $sDetailRCRA .= "<b>   SUBJ TO CA TSD-3004: </b> YES <br />"; }
					if ( $aSite["SUBJCA_TSD_DISCRETION"] != "N"  			&& !empty($aSite["SUBJCA_TSD_DISCRETION"]) ) 		{ $sDetailRCRA .= "<b>   SUBJ TO CA TSD-DISCRETION: </b> YES <br />"; }
		
		
				} // end TSD/TSDF Activity
		
			
				
		/**
		 * CMECOMP3 Compliance
		 */				
				
				
				if ( $aSite["RCR_CMECOMP3_FOUND_VIOLATION"] == "Y" ) {
					
					$sDetailRCRA .= "<b>VIOLATION FOUND: </b> YES <br />";
					$sDetailRCRA .= "<b>Eval Description: </b>" . preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aSite["RCR_CMECOMP3_EVAL_TYPE_DESC"] ) . "<br />";
					
				}
			 
				
		return $sDetailRCRA;
				
				
	} else { return "NOT A RCRAINFO OR RCRAINFO_TSD_CORRACTS SITE."; } 	// end big if

	
} // end function RCRAINFO


/**
 * Creates the match details header for each site.
 * @param array $dataArray
 * @return array $matchDetail
 */
function makeMatchDetail( $dataArray ) {
	
	$matchDetail = array();
	
		// build the dynamic part of our table - one for every site location
		foreach ( $dataArray as $mapIndex => $mixedArray ) {
			
			//error_log("mixed array: " . print_r($mixedArray,1));
			
			$MAP_ID = $mapIndex + 1; // set MAP ID
			
				if ( is_array( $mixedArray["DB_NAME"] ) ) {
	
					
					$DISTANCE = $mixedArray["DISTANCE"][0];
					$DIRECTION = $mixedArray["DIRECTION"][0];
					
						// one of the sub-array elements is not an array buy just a key and value
						if ( is_array( $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"][0] ] ) ) { $SITE_NAME = $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"][0] ][0]; } 
						else { $SITE_NAME = $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"][0] ]; }
					
					$ADDRESS = formatAddress( $mixedArray["DB_ADDRESS_COLUMNS"][0], $mixedArray );
					$DB_NAME_HUMAN = $mixedArray["DB_NAME_HUMAN_ABBREVIATION"][0];
					//$DB_NAME = $mixedArray["DB_NAME"][0];
					//$HAZMAT_CLASS = $mixedArray["HAZMAT_CLASS"][0];
					$LAT = $mixedArray["Latitude"][0];
					$LON = $mixedArray["Longitude"][0];
					
					$SITE_NAME = preg_replace( "~[^[:alnum:][:space:]#()+,.?-]~", "", $SITE_NAME );
					$ADDRESS = preg_replace( "~[^[:alnum:][:space:]#()+,.?-]~", "", $ADDRESS );
					
					
				} else {
					
					$DISTANCE = $mixedArray["DISTANCE"];
					$DIRECTION = $mixedArray["DIRECTION"];
					$SITE_NAME = $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"] ];
					$ADDRESS = formatAddress( $mixedArray["DB_ADDRESS_COLUMNS"], $mixedArray );
					$DB_NAME_HUMAN = $mixedArray["DB_NAME_HUMAN_ABBREVIATION"];
					//$DB_NAME = $mixedArray["DB_NAME"];
					//$HAZMAT_CLASS = $mixedArray["HAZMAT_CLASS"];
					$LAT = $mixedArray["Latitude"];
					$LON = $mixedArray["Longitude"];
					
					$SITE_NAME = preg_replace( "~[^[:alnum:][:space:]#()+,.?-]~", "", $SITE_NAME );
					$ADDRESS = preg_replace( "~[^[:alnum:][:space:]#()+,.?-]~", "", $ADDRESS );
					
				}
			
			$matchDetail[] = array( $MAP_ID, $DISTANCE, $DIRECTION, $LAT, $LON, $DB_NAME_HUMAN, $SITE_NAME, $ADDRESS );
			
		} // end foreach
	
	
	
	return $matchDetail;
	
} // end function


/**
 * Creates a summary of the exact matches.
 * @param array $dataArray
 * @return array $aMatchSummary
 */
function makeMatchSummary( $dataArray ) {
	
	$aMatchSummary = array();
	
		// build the dynamic part of our table - one for every site location
		foreach ( $dataArray as $mapIndex => $mixedArray ) {
			
			$MAP_ID = $mapIndex + 1; // set MAP ID
			
				if ( is_array( $mixedArray["DB_NAME"] ) ) {
	
					
					$DISTANCE = $mixedArray["DISTANCE"][0];
					$DIRECTION = $mixedArray["DIRECTION"][0];
					
						if ( is_array( $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"][0] ] ) ) { $SITE_NAME = $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"][0] ][0]; } 
						else { $SITE_NAME = $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"][0] ]; }
					
					$ADDRESS = formatAddress( $mixedArray["DB_ADDRESS_COLUMNS"][0], $mixedArray );
					$DB_NAME_HUMAN = $mixedArray["DB_NAME_HUMAN_ABBREVIATION"][0];
					//$DB_NAME = $mixedArray["DB_NAME"][0];
					//$HAZMAT_CLASS = $mixedArray["HAZMAT_CLASS"][0];
					
				} else {
					
					$DISTANCE = $mixedArray["DISTANCE"];
					$DIRECTION = $mixedArray["DIRECTION"];
					$SITE_NAME = $mixedArray[ $mixedArray["DB_SITE_NAME_COLUMN"] ];
					$ADDRESS = formatAddress( $mixedArray["DB_ADDRESS_COLUMNS"], $mixedArray );
					$DB_NAME_HUMAN = $mixedArray["DB_NAME_HUMAN_ABBREVIATION"];
					//$DB_NAME = $mixedArray["DB_NAME"];
					//$HAZMAT_CLASS = $mixedArray["HAZMAT_CLASS"];
				}
			
			$aMatchSummary[] = array( $MAP_ID, $DISTANCE, $DIRECTION, $SITE_NAME, $ADDRESS, $DB_NAME_HUMAN );
			
		} // end foreach
	
	
	return $aMatchSummary;
	
} // end function


/**
 * generates the information for the Tabular Summary as an array.
 * @param array $bigArray
 * @return array
 */
function makeTabularSummary( $bigArray ) {

	$optionsArray = $bigArray["INPUT"]; 
	$trackingTableArray = $bigArray["TABLE"];
	$dataArray = $bigArray["DATA"];
	
	//error_log( "BIG ARRAY: " . print_r( $bigArray, 1 ) );
	
	$rowSummary = array();
	$aTabularSummary = array();
	$dbNameArray = array();

	/**
	 *	Generates a DB NAME and search radius array from the users options
	 */
	
		foreach ( $optionsArray as $key => $value ) {	

			if ( preg_match( "/_CHECKBOX/m" , $key) && $value == "on" ) { 							// if checked = on
					
				$search_radius = $optionsArray[ preg_replace("/_CHECKBOX/m", "_RADIUS", $key) ];	// get search radius
				
				$DB_NAME = preg_replace( "/_CHECKBOX/m", "", $key);									// get BD NAME
				
				$rowSummary[$DB_NAME] = $search_radius;								// put in our tabular summary array

			}
			
		} // end foreach
		
		
	/**
	 *	Generates a reference table from the datatracking table
	 */

		foreach ( $trackingTableArray as $key => $array ) { $dbNameArray[ $array["DB_NAME"] ] = $array["DB_NAME_HUMAN_ABBREVIATION"]; }
		
		
	/**
	 *	Generates row totals and values and table totals and values
	 */
		
		foreach ( $rowSummary as $row_DB_NAME => $row_search_radius ) {
			
				$binAt = 0;
				$binBt = 0;
				$binCt = 0;
				$binDt = 0;
				$binEt = 0;
				$tableTotal = 0;
			
				$binAr = 0;
				$binBr = 0;
				$binCr = 0;
				$binDr = 0;
				$binEr = 0;
				$rowTotal = 0;
				
				$DB_NAME = "";
				$DISTANCE = 0;
				
				
				foreach ( $dataArray as $sub_key => $sub_array ) {
				
					if ( is_array( $sub_array["DB_NAME"] ) ) {
		
						$DB_NAME = $sub_array["DB_NAME"][0];
						$DISTANCE = $sub_array["DISTANCE"][0];
						
					} else {
						
						$DB_NAME = $sub_array["DB_NAME"];
						$DISTANCE = $sub_array["DISTANCE"];
						
					}
				
					// only count the bins for rows
					if ( $row_DB_NAME == $DB_NAME ) {
											
						if ( $DISTANCE < .25 ) {
							$binAr++;
						} else if ( ( $DISTANCE >= .25 ) && ( $DISTANCE < .50 ) ) {
							$binBr++;
						} else if ( ( $DISTANCE >= .50 ) && ( $DISTANCE < .75 ) ) {
							$binCr++;
						} else if ( ( $DISTANCE >= .75 ) && ( $DISTANCE < 1.00 ) ) {
							$binDr++;
						} else if ( ( $DISTANCE >= 1.00 ) ){
							$binEr++;
						}
						
					}
					
					$rowTotal = $binAr + $binBr + $binCr + $binDr + $binEr;
					
					
					// count the bins for the table
					if ( $DISTANCE < .25 ) {
						$binAt++;
					} else if ( ( $DISTANCE >= .25 ) && ( $DISTANCE < .50 ) ) {
						$binBt++;
					} else if ( ( $DISTANCE >= .50 ) && ( $DISTANCE < .75 ) ) {
						$binCt++;
					} else if ( ( $DISTANCE >= .75 ) && ( $DISTANCE < 1.00 ) ) {
						$binDt++;
					} else if ( ( $DISTANCE >= 1.00 ) ){
						$binEt++;
					}
					
					$tableTotal = $binAt + $binBt + $binCt + $binDt + $binEt;
					
				} // end foreach
			
				$aTabularSummary[] = array( $dbNameArray["$row_DB_NAME"], $row_search_radius, $binAr, $binBr, $binCr, $binDr, $binEr, $rowTotal );
				

				// error_log( "ROW BIN A: " . print_r( $binAr, 1 ) );
				// error_log( "ROW BIN B: " . print_r( $binBr, 1 ) );
				// error_log( "ROW BIN C: " . print_r( $binCr, 1 ) );
				// error_log( "ROW BIN D: " . print_r( $binDr, 1 ) );
				// error_log( "ROW BIN E: " . print_r( $binEr, 1 ) );
				// error_log( "ROW TOTAL: " . print_r( $rowTotal, 1 ) );
				// error_log( " ----------------------------- ");

				
				
		} // end foreach
		
		$aTabularSummary["TOTALS"] = array( $binAt, $binBt, $binCt, $binDt, $binEt, $tableTotal );
		

		// error_log( "TABLE BIN A: " . print_r( $binAt, 1 ) );
		// 	error_log( "TABLE BIN B: " . print_r( $binBt, 1 ) );
		// 	error_log( "TABLE BIN C: " . print_r( $binCt, 1 ) );
		// 	error_log( "TABLE BIN D: " . print_r( $binDt, 1 ) );
		// 	error_log( "TABLE BIN E: " . print_r( $binEt, 1 ) );
		// 	error_log( "TABLE TOTAL: " . print_r( $tableTotal, 1 ) );
		// 	error_log( " ----------------------------- ");

		
		
		//error_log( "TAB SUMMARY: " . print_r( $aTabularSummary, 1 ) );

						
	return $aTabularSummary;
	
} // end function


/**
 * Returns a string with the static disclaimer.
 * @return string $sDisclaimner
 */
function makeDisclaimer( $oPDF ) {
	
	$oPDF->SetFont("HELVETICA", "", 12); 								// set font from this point on
	
	$sDisclaimner = '<table width="100%" border="0" cellspacing="0" cellpadding="3">
					  <tr>
					    <th height="40" colspan="3" align="center" valign="middle" scope="col"><h2><strong>DISCLAIMER</strong></h2></th>
					  </tr>
					  <tr>
					    <td width="5%" align="left" valign="middle"><p>&nbsp;</p></td>
					    <td width="90%" align="left" valign="middle">Information in this Report has been collected and aggregated by Parcel Forecast,
							LLC. from government agencies and other publicly-available repositories that
							likely contain inaccuracies and incomplete data. The purpose of the Parcel
							Forecast, LLC. aggregation is to provide our Customer with information in an easy
							to use and understandable format. Parcel Forecast, LLC. cannot ensure the
							accuracy of the data that was aggregated and maintained by others. WE DO
							NOT WARRANT THAT THE REPORT WILL BE ERROR-FREE. TO THE EXTENT
							PERMITTED BY APPLICABLE LAW, WE DISCLAIM AND EXCLUDE ALL
							REPRESENTATIONS, WARRANTIES AND CONDITIONS WITH RESPECT TO THE
							INFORMATION IN THE REPORT, WHETHER EXPRESS, IMPLIED OR STATUTORY,
							OTHER THAN THOSE EXPRESSLY IDENTIFIED IN THIS AGREEMENT, INCLUDING,
							WITHOUT LIMITATION, WARRANTIES OF NON-INFRINGEMENT, TITLE,
							SATISFACTORY QUALITY, ACCURACY, RELIABILITY, MERCHANTABILITY, AND
							FITNESS FOR A PARTICULAR PURPOSE. TO THE MAXIMUM EXTENT PERMITTED BY
							LAW, OUR ENTIRE LIABILITY, AND YOUR ONLY REMEDY, FOR A BREACH OF A
							WARRANTY WILL BE EITHER REPLACEMENT OF THE REPORT, OR RETURN OF THE
							FEES YOU PAID FOR THE PRODUCT OR SERVICES. PARCEL FORECAST, LLC. AND ITS
							THIRD PARTY LICENSORS WILL NOT BE LIABLE IN ANY EVENT TO YOU OR ANY
							OTHER PERSON, REGARDLESS OF THE CAUSE, FOR THE EFFECTIVENESS OR
							ACCURACY OF THE PRODUCTS, FOR THE COST OF PROCURING REPLACEMENT
							GOODS OR SERVICES, OR FOR LOST PROFITS OR LOST SALES, OR FOR ANY
							SPECIAL, INDIRECT, INCIDENTAL, PUNITIVE, EXEMPLARY, MULTIPLE OR
							CONSEQUENTIAL DAMAGES ARISING FROM OR OCCASIONED BY YOUR USE OF
							THE REPORTS, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. The
							information in this report is provided &quot;as is&quot; and is not to be construed as legal
							advice.</td>
					    <td width="5%" align="left" valign="middle">&nbsp;</td>
					  </tr>
					</table>';
	
	
	$oPDF->writeHTML( $sDisclaimner, true, false, false, false, "" );		// write HTML

	
} // end function


/**
 * Creates the GOV tracking table in HTML.
 * @param array $bigArray
 * @return string
 */
function makeGovTracking( $oPDF, $bigArray ) {

	
	$optionsArray = $bigArray["INPUT"]; 
	$trackingTableArray = $bigArray["TABLE"];
		
	$aSearchRadii = array();
	$aRefList = array();
	
	/**
	 *	Generates an array of user defined search radii for each DB_NAME called $aSearchRadii
	 */
	
		foreach ( $optionsArray as $key => $value ) {	

			if ( preg_match( "/_CHECKBOX/m" , $key) && $value == "on" ) { 							// if checked = on
					
				$search_radius = $optionsArray[ preg_replace("/_CHECKBOX/m", "_RADIUS", $key) ];	// get search radius
				
				$DB_NAME = preg_replace( "/_CHECKBOX/m", "", $key);									// get BD NAME
				
					$aSearchRadii[$DB_NAME] = $search_radius;												

			}
			
		} // end foreach
		
		
	/**
	 *	Generates a reference array from the datatracking table called $aRefList
	 */
		
		foreach ( $trackingTableArray as $key => $array ) { 
			
			$aRefList[ $array["DB_NAME"] ] = array( $array["DB_NAME_HUMAN"], $array["DB_NAME_HUMAN_ABBREVIATION"], $array["ASTM_SEARCH_RADIUS"], $array["DB_SOURCE_URL"], $array["DB_DATE"], $array["DB_DESCRIPTION"] ); 
		
		}
		

	/**
	 *	Generates the HTML for the Gov Tracking Table
	 */		

		$sRecordTracking = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tr>
							    <th align="center" valign="middle" scope="col"><h2>GOVERNMENT RECORD TRACKING</h2></th>
							  </tr>
							</table>';

		$oPDF->writeHTML( $sRecordTracking, false, false, false, false, "" );	// write HTML
		
		foreach ( $aSearchRadii as $DB_NAME => $radiusReport ) {
			
				$dbNameHuman = $aRefList["$DB_NAME"][0];
				$dbAbbreviation = $aRefList["$DB_NAME"][1];
				
					// account for mile / miles / Adjacent Properties in report text 
					if ( $DB_NAME == "RCRAINFO" ) { $radiusASTM = "Adjacent Properties"; 
					} else if ( intval( $aRefList["$DB_NAME"][2] ) == 1 ) { $radiusASTM = $aRefList["$DB_NAME"][2] ." mile";
					} else { $radiusASTM = $aRefList["$DB_NAME"][2] ." miles"; }
					
					// account for mile / miles in report text
					if ( intval( $radiusReport ) == 1 ) { $radiusReport = $radiusReport . " mile";
					} else { $radiusReport = $radiusReport . " miles"; }
					
				$sourceURL = $aRefList["$DB_NAME"][3];
				$dbDate = $aRefList["$DB_NAME"][4];
				$dbDescription = preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $aRefList["$DB_NAME"][5] );
				
				
				$sRecordTracking = '<table width="100%" border="0" cellspacing="0" cellpadding="3">
									  <tr>
									    <td width="21%" align="right"><strong>Database Name:</strong></td>
									    <td width="79%" align="left">'. $dbNameHuman .'</td>
									  </tr>
									  <tr>
									    <td align="right"><strong>Abbreviation:</strong></td>
									    <td align="left">'. $dbAbbreviation .'</td>
									  </tr>
									  <tr>
									    <td align="right"><strong>ASTM Radius:</strong></td>
									    <td align="left">'. $radiusASTM .'</td>
									  </tr>
									  <tr>
									    <td align="right"><strong>Report Radius:</strong></td>
									    <td align="left">'. $radiusReport .'</td>
									  </tr>
									  <tr>
									    <td align="right"><strong>Source URL:</strong></td>
									    <td align="left">'. $sourceURL .'</td>
									  </tr>
									  <tr>
									    <td align="right"><strong>Database Update:</strong></td>
									    <td align="left">'. $dbDate .'</td>
									  </tr>
									  <tr>
									    <td colspan="2" align="center">&nbsp;</td>
									  </tr>
									  <tr>
									    <td colspan="2" align="left">'. $dbDescription .'</td>
									  </tr>
									  <tr>
									    <td colspan="2" align="center">____________________________________ <br /> <br /> <br /></td>
									  </tr>
									</table>';
				
				$oPDF->writeHTML( $sRecordTracking, false, false, false, false, "" );	// write HTML
				
		} // end foreach
	
} // end function


/**
 * Performs explode() on a string with the given delimiter and trims all whitespace for the elements
 * @param string | $str
 * @return array | trimmed and exploded OR original string on no delimiters
 */
function explode_trim($str, $delimiter = ',') {
	
	//error_log("Sting from DB that gets turned into array:" . print_r($str,1));
	
	// pulls apart comma separated entries from data tracking table so it can then use each term as a search term
    if ( is_string($delimiter) ) {
        $str = trim(preg_replace('|\\s*(?:' . preg_quote($delimiter) . ')\\s*|', $delimiter, $str));
        return explode($delimiter, $str);
    }
    return $str;
    
} // end function


/**
 * Writes out the PDF object contents to a file, given the name and path.
 * @param object $oPDF
 * @param string $local_user_path
 * @param string $base_filename
 */
function writePDF( $oPDF, $local_user_path, $base_filename ) {
	
	$fileName = $local_user_path . $base_filename . ".pdf";		// Generate the file name of our output pdf, keyed to user name, user directory, date and time

	if ( !file_exists($fileName) ) {

		$oPDF->Output( $fileName, "F");																// write PDF file
		@unlink($local_user_path . ".tmp/.updater/" . $base_filename . " - Generating PDF..."); 	// remove the old updater file

	} else {
		
		$fileName = $local_user_path . $base_filename . rand( 0, 99 ) . ".pdf";						// if the files exists add on a random integer between 0 and 99 on the end
		$oPDF->Output( $fileName, "F");																// write PDF file
		@unlink($local_user_path . ".tmp/.updater/" . $base_filename . " - Generating PDF..."); 	// remove the old updater file
	}
	
} // end function


/**
 * Writes a simple Title Header.
 * @param obect $oPDF
 * @param string $sTitle
 */
function makeTitleHeader( $oPDF, $sTitle ) {
	
	$oPDF->SetFont("HELVETICA", "", 12);							// set font from this point on
	
	// add the static header for our table
	$sHeader =	'<table width="100%" border="0" cellspacing="0" cellpadding="0">	
				  <tr>
				    <th align="center" valign="middle" scope="col"><h2><strong>' . $sTitle . '</strong></h2></th>
				  </tr>
				</table>';
	$oPDF->writeHTML( $sHeader, false, false, false, false, "" );	// write out the htm
	$oPDF->SetFont("HELVETICA", "", 9);			// set font from this point on
	
} // end function


/**
 * Formats the address columns with commas, and puts in a string.
 * @param array $addressArray
 * @return string 
 */
function formatAddress( $addressStr, $addrArray ) {

	//$addressStr = preg_replace( "~[^[:alnum:][:space:]#$%&()*+,./:;?@-]~", "", $addressStr );
	
	$ADDRESS = "";
	
	$addressColumns = explode_trim( $addressStr ); // turns comma separated string into array
		$endKey = end( $addressColumns ); // get the last key in the array
	
		if ( is_array( $addrArray["DB_NAME"] ) ) {
			
			$endValue = $addrArray["$endKey"][0]; // now get the last value in the passed array
			
		} else {
			
			$endValue = $addrArray["$endKey"]; // now get the last value in the passed array
			
		}
		
		
			//  append fields with commas, except for the last value, or if empty
			foreach ( $addressColumns as $key => $value ) {
				
				if ( is_array( $addrArray["DB_NAME"] ) ) {
		
					$addressValue = $addrArray[$value][0];
					
				} else {
					
					$addressValue = $addrArray[$value];
					
				}
				
					
				if ( !empty( $addressValue ) ) {
																								
					if ( $addressValue != $endValue ) {
					
						$ADDRESS .= $addressValue . ", ";
					
					} else {
					
						$ADDRESS .= $addressValue;
					}
				}
				
			} // end inner foreach 
	
	return $ADDRESS;
	
} // end function


//xdebug_stop_trace();

?>