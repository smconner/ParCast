<?php 

require_once dirname(__FILE__) . "/radius-report-config.php";

function master_minder() {
	
	while(1) {
		
		// to prod the php scheduler "cron" scripts ->
		// 1) wget RR_HTTP_HOST > don't store data
		// 2) sleep for 5 mins then do again... etc. etc.
		
		$sCommand = "/usr/bin/wget ";
		$sCommand .= " -O /dev/null -o /dev/null ";
		$sCommand .= RR_HTTP_HOST . "/wp-login.php";
		$sCommand = escapeshellcmd( $sCommand );
		
		exec( $sCommand, $sOutput);

		sleep(300);
		
	} // end while
	
} // end function

master_minder();

?>