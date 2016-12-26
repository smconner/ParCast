<?php
/*
Plugin Name: Radius Report Main
Description: Makes Radius Reports.
Version: v3.0 alpha
Author: Sean Conner
License: GPL
*/


/** 
 * ===============================================================================
 * 
 *	This file handles bootstrapping the rest of the package and initializes our
 *	database table for storing and passing varaibles and option
 * 
 * ===============================================================================
 **/

// include the other php files in this package
require_once dirname(__FILE__) . '/radius-report-generator.php';
require_once dirname(__FILE__) . '/radius-report-generator-engine.php';
require_once dirname(__FILE__) . '/radius-report-credits.php';
require_once dirname(__FILE__) . '/radius-report-google-map.php';
require_once dirname(__FILE__) . '/radius-report-user-interface.php';


// check for wordpress version
global $wp_version;

//error_log("WP VERSION: " . $wp_version);
if ( !version_compare( $wp_version, "3.0", ">=" ) ) { die( "You need at least Wordpress Version 3.0 or greater to use this plugin." ); }


// sets the value of the version that shows up in the lower right footer
define("radius_report_version", "3.0 alpha");


// plugin activattion calls "radius_report_plugin_activate"
register_activation_hook( __FILE__, "radius_report_plugin_activate" );

// deactivation calls "radius_report_plugin_deactivate"
register_deactivation_hook( __FILE__, "radius_report_plugin_deactivate" );

// add actions for this package
add_action( 'wp_dashboard_setup', 'add_dashboard_widgets' );
add_action( 'admin_menu', 'radius_report_products_menu_fn' );
add_action( 'admin_menu', 'radius_report_credits_menu_fn' );
add_action( 'admin_print_scripts', 'radius_report_enque_scripts_fn' );

add_action( 'wp_before_admin_bar_render', 'radius_report_admin_toolbar');
add_action( 'admin_menu', 'radius_report_admin_footer_fn' );

add_action( 'login_head', 'radius_report_login_logo_fn' );
add_action( 'login_head', 'edit_login_message_fn' );

add_action('admin_print_styles', 'big_site_title');

add_action('admin_init', 'ssid_add');
add_action('admin_init', 'display_credits_purchased');
add_action('admin_init', 'display_credit_line');
add_action('admin_init', 'display_credit_line_remaining');


add_action('my_hourly_event_00', 'myInvoiceMailer');
add_action('my_hourly_event_15', 'myInvoiceMailer');
add_action('my_hourly_event_30', 'myInvoiceMailer');
add_action('my_hourly_event_45', 'myInvoiceMailer');

add_action('wp', 'my_activation');



/**
 * Checks if the periodic (15 min interval) cron functions are scheduled, and if not, adds them.
 * This function is configured to have four functions set to run hourly but offset by 15 mins
 * i.e. time() + 15 mins, time() + 30 mins, and time() + 45 mins
 */
function my_activation() {
		
	//error_log("my activation ran @ " . date('m/d/Y h:m:s', time() ) );
		
	if ( !wp_next_scheduled( 'my_hourly_event_00' ) ) {
		wp_schedule_event(time(), 'hourly', 'my_hourly_event_00');
		error_log("setting up my_hourly_event_00");
	}
	if ( !wp_next_scheduled( 'my_hourly_event_15' ) ) {
		wp_schedule_event(time()+900, 'hourly', 'my_hourly_event_15');
		error_log("setting up my_hourly_event_15");
	}
	if ( !wp_next_scheduled( 'my_hourly_event_30' ) ) {
		wp_schedule_event(time()+1800, 'hourly', 'my_hourly_event_30');
		error_log("setting up my_hourly_event_30");
	}
	if ( !wp_next_scheduled( 'my_hourly_event_45' ) ) {
		wp_schedule_event(time()+2700, 'hourly', 'my_hourly_event_45');
		error_log("setting up my_hourly_event_45");
	}
		
} // end function

// reports to the error log that this plugin has been "Activated"
function radius_report_plugin_activate() {	

	error_log("Radius Report Package plugin activated");
}

// reports to the error log that this plugin has been "Deactivated" 
function radius_report_plugin_deactivate() {

	error_log( "Radius Report Package plugin deactivated" );
}

// enques all of the wordpress built-in scripts needed to allow certain 'widget like' funcitonality in this plugin
function radius_report_enque_scripts_fn() {

	wp_enqueue_script("admin-comments");
	wp_enqueue_script("common");
	wp_enqueue_script("dashboard");
	wp_enqueue_script("global");
	wp_enqueue_script("hoverIntent");
	wp_enqueue_script("jquery");
	wp_enqueue_script("jquery-color");
	wp_enqueue_script("jquery-query");
	wp_enqueue_script("jquery-ui-core");
	wp_enqueue_script("jquery-ui-widget");
	wp_enqueue_script("jquery-ui-mouse");
	wp_enqueue_script("jquery-ui-resizable");
	wp_enqueue_script("jquery-ui-sortable");
	wp_enqueue_script("jquery-ui-dialog");
	wp_enqueue_script("media-upload");
	wp_enqueue_script("plugin-install");
	wp_enqueue_script("postbox");
	wp_enqueue_script("quicktags");
	wp_enqueue_script("thickbox");
	wp_enqueue_script("wp-admin");
	wp_enqueue_script("wp-ajax-response");
	wp_enqueue_script("wp-lists");
	
} // end function

// adds some css for parcast branding
function big_site_title() {
?>

<style>

.wp-admin #header-logo {
	background-image: url("images/logo.gif");
	background-size:32px 32px;
	width:32px;
	height:32px;
}

.wp-admin #wphead {
	height: 42px;
}

.wp-admin #wphead h1 {
	font-size: 28px;
	# font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",Helvetica,Arial,sans-serif; 
	# uncomment this if you want to go to the sans-serif font
}

</style>

<?php
}



?>