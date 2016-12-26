<?php

// set the default timezone
date_default_timezone_set('America/Los_Angeles');


/**
 *	DEV vs. LIVE server switch
 */


$DEV_SERVER = true;

if ( $DEV_SERVER ) {
	
	define( "RR_HTTP_HOST", "http://localhost" );
	define( "RR_HTTPS_HOST", "https://localhost" );
	
define( "RR_LOCALHOST", "localhost" );  // IS THIS NEEDED?
	
	define( "RR_MYSQL_HOST", "127.0.0.1:8889");
	define( "RR_MYSQL_USER", "root");
	define( "RR_MYSQL_PASS", "root");
	define( "RR_MYSQL_NAME", "PARCAST_DB");
	
	define( "RR_WEB_INVOICE_DB", "wp_parcast_com");
	
} else {
	
	define( "RR_HTTP_HOST", "http://parcast.com" );
	define( "RR_HTTPS_HOST", "https://parcast.com" );
	
define( "RR_LOCALHOST", "localhost" ); // IS THIS NEEDED?
	
	define( "RR_MYSQL_HOST", "localhost");
	define( "RR_MYSQL_USER", "root");
	define( "RR_MYSQL_PASS", "Cochlear91");
	define( "RR_MYSQL_NAME", "PARCAST_DB");
	
	define( "RR_WEB_INVOICE_DB", "wordpress_parcast_com");
}


/**
 *	Mapnik server info
 */

define( "MAPNIK_HOST", "184.106.218.99");
define( "MAPNIK_PG_USER", "postgres");
define( "MAPNIK_PASS", "Cochlear91");


/**
 *	Credit card processing info
 */

require_once 'lib/anet_php_sdk/AuthorizeNet.php';		// Adjust this to point to the Authorize.Net PHP SDK

$TEST_MODE = true;

if ( $TEST_MODE ) {
	
	define( "AUTHORIZENET_SANDBOX", true );						// To post transactions to the Authorize.Net TEST server:
	define( "AUTHORIZENET_API_LOGIN_ID", "39g7vJTW" );    			// TEST ACCOUNT API LOGIN ID
	define( "AUTHORIZENET_TRANSACTION_KEY", "78Z974f45aKNm3YU" ); 	// TEST ACCOUNT API transaction key
	
} else {
	
	define( "AUTHORIZENET_SANDBOX", false );						//To post transactions to the live Authorize.Net gateway:
	define( "AUTHORIZENET_API_LOGIN_ID","3M9us9bQm6m" );    		// LIVE API LOGIN ID
	define( "AUTHORIZENET_TRANSACTION_KEY","33ApjY5Fg88T56yq" ); 	// LIVE API transaction key
	
	
}


if ( AUTHORIZENET_API_LOGIN_ID == "" ) { die('Error: ParCast merchant credentials missing!'); }




// Test Accounts vs. Live Accounts Ð Which Does What Anyway?
// 
// Authorize.Net offers a few different ways for you to test your connection. 
// 
// 1. 	We offer test accounts in a testing environment that mimics the functionality of our live accounts. 
// 	You can test your connection and submit transactions to the test environment, but as it is only a test server, 
// 	no transaction data will ever be sent on to a bank.
// 
// 
// 2.	We also offer a Test Mode option on all live accounts which allows you to test transactions using your live connection. 
// 	Test Mode basically allows you to validate that the transaction information was submitted correctly, but the info is 
// 	not passed on for processing. All new live accounts are automatically placed in Test Mode, which must be turned off 
// 	before you can process real transactions.
// 
// 3.	Lastly, you can also submit test transactions from your live account on a per-transaction basis by submitting 
// 	x_test_request=TRUE in your API call. When you include this value, your live account will treat the transaction 
// 	as if it were in Test Mode.
// 
// 
// NOTE: 	By far, the most common and recommended way of testing is to build your connection using a developer test account 
// 		and test in the test environment. Then when youÕre ready to go live, you simply change the API Login ID and 
// 		Transaction Key from your test account credentials to your live credentials, and change the posting URL from 
// 		the test URL to the live URL, and youÕre ready to go. Using a live account and placing it in Test Mode or 
// 		submitting x_test_request=TRUE are also useful in some test cases, but using a test account really is the way to go.
// 
// 
// http://community.developer.authorize.net/t5/The-Authorize-Net-Developer-Blog/Test-Accounts-vs-Live-Accounts-Which-Does-What-Anyway/ba-p/9350
// 
// 
// 
// For your reference, you can use the following test credit card numbers when testing your connection. 
// The expiration date must be set to the present date or later:
// - American Express Test Card: 370000000000002
// - Discover Test Card: 6011000000000012	
// - Visa Test Card: 4007000000027	
// - Second Visa Test Card: 4012888818888	
// - JCB: 3088000000000017	
// - Diners Club/ Carte Blanche: 38000000000006
//
// You only need to adjust the two variables below if testing DPM
//define("AUTHORIZENET_MD5_SETTING","");   // Add your MD5 Setting.

//$site_root = "http://localhost:8888/wp-content/plugins/radius-report/credits/"; // Add the URL to your site




?>
