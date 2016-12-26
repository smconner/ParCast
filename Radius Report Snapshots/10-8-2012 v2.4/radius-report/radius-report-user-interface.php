<?php

// Adds admin toolbar icon and credit displays
function radius_report_admin_toolbar() {  
		
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<img src="/wp-content/plugins/radius-report/images/radius-report-header-icon_28x28.png" />',
		'href'  => false,
		'meta'  => array( 'title' => __('Return to Frontend') ), // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' => '', target => '', title => '' );
	) );
	
	$wp_admin_bar->remove_menu('new-content');
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('about');
	$wp_admin_bar->remove_menu('wporg');
	$wp_admin_bar->remove_menu('documentation');
	$wp_admin_bar->remove_menu('support-forums');
	$wp_admin_bar->remove_menu('feedback');
	$wp_admin_bar->remove_menu('view-site');
	
	// Purchased Credits: 88 | Remaining Credit Line: 94

	$current_user_object = wp_get_current_user();
	$user_identity = $current_user_object->display_name;
	$user_id = $current_user_object->ID;
	$purchased_credits = get_user_meta( $user_id, 'radius_report_user_credit_balance', true );
	$credit_line_remaining = get_user_meta( $user_id, 'radius_report_user_credit_line_remaining', true );


	// Displays number of purchased credits 
	$wp_admin_bar->add_menu( array(
		'parent' => false, // use 'false' for a root menu, or pass the ID of the parent menu
		'id' => 'purchased_credits', // link ID, defaults to a sanitized title value
		'title' => "Purchased Credits: $purchased_credits" , // link title
		'href' => false, // name of file
		'meta' => array('title'=>'Your Purchased Report Credits: These are report credits that have been pre-payed. When you generate a report, credits are subtracted from this balance first.')
	));
	
	// Displays number of credits remaining in credit line
	if ($credit_line_remaining != 0) {
		$wp_admin_bar->add_menu( array(
			'parent' => false, // use 'false' for a root menu, or pass the ID of the parent menu
			'id' => 'credit_line', // link ID, defaults to a sanitized title value
			'title' => "Remaining Credit Line: $credit_line_remaining" , // link title
			'href' => false, // name of file
			'meta' => array('title'=>'Your Credit Line: These are report credits that have been extended to you as a line of credit, and will be invoiced to your account. If both balances reach 0 then you will need to request a larger credit line and/or purchase credits instantly via Credit Card or Check.')
		));
	}

}  


// Adds Remaining Credit Line for each user at the admin level.
function display_credit_line_remaining() {

	add_action('admin_head', 'credit_line_remaining_css');

		// Output CSS for width of new column
		function credit_line_remaining_css() {
			?>
			<style type="text/css">
				#credit_line_remaining { width: 85px; } /* Show Remaining Credit Line */
			</style>
			<?php	
		} // end function

	add_action('manage_users_columns', 'credit_line_remaining_column');
	add_filter('manage_users_custom_column', 'credit_line_remaining_return_value', 10, 3);
	
		// Prepend the new column to the columns array
		function credit_line_remaining_column( $cols ) {
			$cols['credit_line_remaining'] = 'Remaining Credit Line';
			return $cols;
		} // end function
		
		// Get the current users number of credits
		function credit_line_remaining_return_value( $value, $column_name, $user_id ) {
			if ($column_name == 'credit_line_remaining') {
				$value = get_user_meta( $user_id, "radius_report_user_credit_line_remaining", true );
			}
			return $value;
		} // end function

} //end function

// Adds Credit Line for each user at the admin level.
function display_credit_line() {

	add_action('admin_head', 'credit_line_css');

		// Output CSS for width of new column
		function credit_line_css() {
				?>
				<style type="text/css">
					#credit_line { width: 55px; } /* Show Credit Line */
				</style>
				<?php	
		} // end function

	add_action('manage_users_columns', 'credit_line_column');
	add_filter('manage_users_custom_column', 'credit_line_return_value', 10, 3);
	
		// Prepend the new column to the columns array
		function credit_line_column( $cols ) {
			$cols['credit_line'] = 'Credit Line';
			return $cols;
		} // end function
		
		// Get the current users number of credits
		function credit_line_return_value( $value, $column_name, $user_id ) {
			if ($column_name == 'credit_line') {
				if ( get_user_meta( $user_id, "radius_report_user_invoice_enabled", true ) == "off" ) {
					$value = "off";
				} else {
					$value = get_user_meta( $user_id, "radius_report_user_credit_line", true );
				}
			}
			return $value;
		} // end function

} //end function

// Adds Purchased Credits for each user at the admin level.
function display_credits_purchased() {
	
	add_action('admin_head', 'credits_css');
	
		// Output CSS for width of new column
		function credits_css() {
			?>
			<style type="text/css">
				#credits { width: 80px; } /* Show Purchased Credits */
			</style>
			<?php	
		}

	add_action('manage_users_columns', 'credits_column');
	add_filter('manage_users_custom_column', 'credits_return_value', 10, 3);
	
		// Prepend the new column to the columns array
		function credits_column( $cols ) {
			$cols['credits'] = 'Purchased Credits';
			return $cols;
		} // end function
		
		// Get the current users number of credits
		function credits_return_value( $value, $column_name, $user_id ) {
			if ($column_name == 'credits') {
				$value = get_user_meta( $user_id, "radius_report_user_credit_balance", true );
			}
			return $value;
		}

} //end function


// Adds ID columns, author: Matt Martz
function ssid_add() {

	add_action('admin_head', 'ssid_css');
	
		// Output CSS for width of new column
		function ssid_css() {
			?>
			<style type="text/css">
				#ssid { width: 50px; } /* Show IDs */
			</style>
			<?php	
		}

	add_filter('manage_posts_columns', 'ssid_column');
	add_action('manage_posts_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_pages_columns', 'ssid_column');
	add_action('manage_pages_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_media_columns', 'ssid_column');
	add_action('manage_media_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_link-manager_columns', 'ssid_column');
	add_action('manage_link_custom_column', 'ssid_value', 10, 2);
	
	add_action('manage_edit-comments_columns', 'ssid_column');
	add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
	
		// Echo the ID for the new column
		function ssid_value($column_name, $id) {
			if ($column_name == 'ssid')
			echo $id;
		}
	
	add_action('manage_edit-link-categories_columns', 'ssid_column');
	add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);

		foreach ( get_taxonomies() as $taxonomy ) {
			add_action("manage_edit-${taxonomy}_columns", 'ssid_column');
			add_filter("manage_${taxonomy}_custom_column", 'ssid_return_value', 10, 3);
		}

	add_action('manage_users_columns', 'ssid_column');
	add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);
	
		function ssid_return_value($value, $column_name, $id) {
			if ($column_name == 'ssid')
			$value = $id;
			return $value;
		}
	
		// Prepend the new column to the columns array
		function ssid_column($cols) {
			$cols['ssid'] = 'ID';
			return $cols;
		}
	
} // end function



//add_filter('admin_user_info_links', 'display_user_credits_fn', 10, 1);
function display_user_credits_fn( $aLinks ) {
	
	//error_log("LINKS: " . print_r( $aLinks, 1) );
	
	$current_user_object = wp_get_current_user();
	$user_identity = $current_user_object->display_name;
	$user_id = $current_user_object->ID;
	
	if ( empty( $purchased_credits ) ) {
		
		update_user_meta($user_id, 'radius_report_user_credit_balance', "0");

	}
	
	$purchased_credits = get_user_meta( $user_id, 'radius_report_user_credit_balance', true );
	$credit_line_remaining = get_user_meta( $user_id, 'radius_report_user_credit_line_remaining', true );
	
	$links = array();
	
	if ( $credit_line_remaining != "" ) {
		$links[5] = sprintf( __('Howdy, %1$s'), $user_identity ) . ' | <a href="#" title="Your Purchased Report Credits: These are report credits that have been pre-payed. When you generate a report, credits are subtracted from this balance first."> Purchased Credits: '.$purchased_credits.'</a> | <a href="#" title="Your Report Credit Line: These are report credits that have been extended to you as a line of credit, and will be invoiced to your account. If both balances reach 0 then you will need to request a larger credit line and/or purchase credits instantly via Credit Card or Check. "> Remaining Credit Line: '.$credit_line_remaining.' </a>';
	} else {
		$links[5] = sprintf( __('Howdy, %1$s'), $user_identity ) . ' | <a href="#" title="Your Purchased Report Credits: These are report credits that have been pre-payed. When you generate a report, credits are subtracted from this balance first."> Purchased Credits: '.$purchased_credits.' </a>';
	}
	
	$links[6] = ' <a href="/wp-admin/admin.php?page=credits-menu" title="Purchase More Reports">Purchase More Reports</a>';
	$links[8] = '<a href="profile.php" title="' . esc_attr__('Edit your profile') . '">' . __('Your Profile') . '</a>';
	
	if ( is_multisite() && is_super_admin() ) {
		if ( !is_network_admin() ) {
			$links[10] = '<a href="' . network_admin_url() . '" title="' . ( ! empty( $update_title ) ? $update_title : esc_attr__('Network Admin') ) . '">' . __('Network Admin') . ( ! empty( $total_update_count ) ? ' (' . number_format_i18n( $total_update_count ) . ')' : '' ) . '</a>';
		} else {
			$links[10] = '<a href="' . get_dashboard_url( get_current_user_id() ) . '" title="' . esc_attr__('Site Admin') . '">' . __('Site Admin') . '</a>';
		}
	}

	$links[15] = '<a href="' . wp_logout_url() . '" title="' . esc_attr__('Log Out') . '">' . __('Log Out') . '</a>';
	
	return $links;
}



// Removes wordpress update nag for everyone but the admin
add_filter('pre_site_transient_update_core','remove_user_update_nag');
function remove_user_update_nag() {
	if ( !current_user_can( 'update_plugins' ) ) {
		return null;
	} 
}

// Removes all wordpress contextual help menus
add_filter( 'contextual_help', 'remove_help', 999, 3 );
function remove_help($old_help, $screen_id, $screen){
    $screen->remove_help_tabs();
    return $old_help;
}



// Replaces the default registration messages
add_filter( 'login_message', 'edit_login_message_fn', 10, 2 );
function edit_login_message_fn( $message ) {
		
    if ( '<p class="message register">Register For This Site</p>' == $message ) {
    	
        return '<p class="message register">Register for ParCast</p>';
    
    } else {

    	return $message;
    }
    
} // end function


// Replace default WP login logo with ParCast logo
function radius_report_login_logo_fn() {
	
	?>
		<style>
			#radius_report_login_logo {
				padding: 0 0 10px 7px;
				max-width: 314px;
				margin: 0;
			}
			#login h1 {
				display: none;
				text-align:center;
			}
		</style>
		<script type="text/javascript">
			window.onload = addLogo;						
			function addLogo() {
				var myBody = document.getElementById("login").getElementsByTagName("h1")[0];
				myBody.innerHTML = "";
				var img = document.createElement("img");
				img.setAttribute("src","<?php echo '/wp-content/plugins/radius-report/images/radius-report-login-logo.png'; ?>");
				img.setAttribute("id", "radius_report_login_logo");				
				myBody.appendChild(img);				
				myBody.style.display = "block";
				return false;
			}
		</script>
    <?php

} // end function


// Replaces default WP footer
function radius_report_admin_footer_fn() {
	
	// replaces the lower left admin footer
	add_filter('admin_footer_text', 'replace_footer_admin');
	
	function replace_footer_admin() {
		
		$current_user = wp_get_current_user();
	    $current_user_username = $current_user->user_login;
	    $current_user_ID = $current_user->ID;
		 
		$email_us_string = "<a href='mailto:support@parcast.com?subject= User name: ".$current_user_username." - Time stamp: " . date('F d, Y h:i') . " GMT" . "&amp;body=We are here to help! %0A%0A Please take a moment to explain your issue.  %0A%0A%0A --%0AThank you for your patience, and we are sorry for any inconvenience. %0AReference username: ".$current_user_username." %0AReference User ID: ".$current_user_ID." '>Help</a>";
				
		//echo "$email_us_string Our smart phones are always on.";
		
		echo "&#169; ".date('Y')." Parcel Forecast, LLC | <a href='http://en.wikipedia.org/wiki/All_rights_reserved'> All Rights Reserved </a>  | $email_us_string" ;
	}

	
	// replaces the lower right admin footer with our plugin version
	add_filter('update_footer', 'replace_update_footer');
	
	function replace_update_footer() {
		return "ParCast Version: " . radius_report_version ;
	}
	
	
	// replace the browser title - must be executed during the admin_menu action
	add_filter( 'admin_title', 'replace_admin_browser_title' );
	
	function replace_admin_browser_title() {
		return "ParCast";
	}
	
	
} // end function


// Adds the "Purchase" main menu item
function radius_report_credits_menu_fn() {
	
	$capability = 'edit_posts'; // minimum access_level to view these menus
	$parent_slug = 'credits-menu'; // what appears in the URL when in the main products menu
	add_menu_page( 'Purchase', 'Purchase', $capability, $parent_slug, '', '', '4' );
	add_submenu_page( $parent_slug, 'Pay with Credit Card', 'Pay with Credit Card', $capability, $parent_slug, 'radius_report_creditcards_page' );
	add_submenu_page( $parent_slug, 'Pay with Check', 'Pay with Check', $capability, 'e-check', 'radius_report_echeck_page' );
}


// Adds the "Generate" main menu item
function radius_report_products_menu_fn() {
	
	$capability = 'edit_posts'; // minimum access_level to view these menus
	$parent_slug = 'products-menu'; // what appears in the URL when in the main products menu
	add_menu_page( 'Generate', 'Generate', $capability, $parent_slug, 'radius_report_page', '/wp-content/plugins/radius-report/images/radius-report-icon_14x13.png', 15 );
	add_submenu_page( $parent_slug, 'Radius Reports', 'Radius Reports', $capability, $parent_slug, 'radius_report_page' );
	
	
	// get the current user ID
	$current_user = wp_get_current_user();
	$current_user_ID = $current_user->ID;
	
	// if the current user ID is not an administrator
	if ( !current_user_can( "administrator" ) ) {
		remove_menu_page( 'edit.php' );
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'tools.php' );
		remove_action("admin_color_scheme_picker", "admin_color_scheme_picker");
		add_action( 'personal_options', 'radius_report_remove_personal_options');
	}
	
	// reference from here: http://api.jquery.com/remove/
	function radius_report_remove_personal_options() {

		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery(" .form-table:contains('Personal Options'), h3:contains('Personal Options') , h3:contains('About Yourself') , h3:contains('About the user') ").empty().remove();
				jQuery("tr").remove(" :contains('AIM') , :contains('Jabber / Google Talk') , :contains('Yahoo IM') , :contains('Biographical Info') ");
			});
		</script>
		<?php
		
	} // end function
	
	
} // end function


// Removes the default "Dashboard" widgets and adds our widget containers
function add_dashboard_widgets() {
	
	// Remove all the default meta boxes, and thus widgets, in the admin dashboard
	global $wp_meta_boxes;	
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
	
	// Add our admin dashboard widgets
	wp_add_dashboard_widget('radius_report_dashboard_widget', "Radius Reports", 'radius_report_dashboard_widget_function');
	
	
	// override the number of columns in the dashboard to 1 
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	$user_meta_key = 'screen_layout_dashboard';
	$new_meta_value = '1';
	update_user_meta($current_user_id, $user_meta_key, $new_meta_value);

} // end function


// Output the contents of our Radius Report Dashboard Widget
function radius_report_dashboard_widget_function() {
	

	// safely grab the current user id object
	$current_user = wp_get_current_user();
    if ( !($current_user instanceof WP_User) ) {
    	return error_log("Oops!: current_user != instanceof WP_User for User ID: ". $current_user->ID . " See: " . __FILE__);
    }
    else {
    	$this_current_user_login = $current_user->user_login;
    }
    
    // generate the directory path for this user
	$user_directory_path = dirname(__FILE__) . '/generated-reports/' . $this_current_user_login;
	
	// generate the path to hyper link the files in the user directory
    $user_directory_link_path = '/wp-content/plugins/radius-report/generated-reports/' . $this_current_user_login;
    
    $current_user_username = $current_user->user_login;
    $current_user_ID = $current_user->ID;
    
	
		// if the directory exists list the files and folders
		if ( file_exists( $user_directory_path . "/.tmp/.updater" ) ){
			
			// create a handle (pointer) for the directory
			$handle = opendir( $user_directory_path . "/.tmp/.updater" );
			
			echo "<br> Pending Reports - [<a href='javascript:location.reload(true)'> Click To Update Screen </a>]: <br>";
			
		    while ( false !== ($file = readdir($handle)) ){
		    	
		        if ($file != "." && $file != ".."){
		        	
		        	echo "<br>";
		        	print(" &nbsp &nbsp &nbsp &nbsp <a href='mailto:support@parcast.com?subject=Radius Report Issue - Time stamp: " . date('F d, Y h:i') . " GMT" . "&amp;body=Did our report generator tell you it &quot;FAILED&quot;. Sorry! We appreciate your patience--creating a dynamic report from multiple sources is tricky, and unfortunately problems sometimes occur.  %0A%0A Please take a moment to explain the parameters of your report (i.e. location, radius, databases) and any other information you think might help us recreate the issue. We will refund your report credit and respond to your inquiry as soon as possible. If we can&rsquo;t remedy the situation in 24 hours or less, we will give you a free credit. Your satisfaction is important to us! %0A%0A Issue(s): %0A%0A Preferred contact (phone, email, carrier pigeon): %0A%0A --%0AThank you for your patience, and we are sorry for any inconvenience. %0A-The Parcast Team%0A%0A%0AReference Report: ".$file." %0AReference username: ".$current_user_username." %0AReference User ID: ".$current_user_ID." '>[Help]</a>");
		        	
		            echo "&nbsp $file \n";
		            echo "<br>";

		        } 
		        
		    }
		   	echo "<br> Generated Reports: <br>";
		    closedir($handle);
  
		}
	
		// if the directory exists list the files and folders
		if ( file_exists($user_directory_path) ){
			
			// get lists of the pdf and zip files in the directory
			$file_list_pdf = array();
			$file_list_zip = array();
			$handle = opendir( $user_directory_path );
		    while ( false !== ($file = readdir($handle)) ){ 
				if (pathinfo($file, PATHINFO_EXTENSION) == "pdf") { 
					$file_list_pdf[] = $file; 
				}
				if (pathinfo($file, PATHINFO_EXTENSION) == "zip") {
					$file_list_zip[] = $file; 
				}
			}
			closedir($handle);

			// for each file present create html to dislay them
			foreach ( $file_list_pdf as $key => $value ) {

				$basename = basename($value, ".pdf");
				
				echo "<br>";
				print(" &nbsp &nbsp &nbsp &nbsp <a href='mailto:support@parcast.com?subject=Radius Report Issue - Time stamp: " . date('F d, Y h:i') . " GMT" . "&amp;body=Do you have an issue with this report? Need to generate the report again? Is your report different than expected? We are here to help! %0A%0A Please take a moment to explain your issue(s) and we will do our best to respond within 24 hours. %0A%0A Issue(s): %0A%0A Preferred contact (phone, email, carrier pigeon):  %0A%0A%0A%0A%0A --%0AThank you,%0A -The Parcast Team%0A%0A%0AReference Report: ".$basename."%0AReference username: ".$current_user_username." %0AReference User ID: ".$current_user_ID." '>[Issue with this report?]</a>");
			
				echo "&nbsp $basename.pdf \n";
				print("<a href=\"". $user_directory_link_path ."/". $basename.".pdf\">[Download PDF]</a>\n");
				
				if ( in_array( $basename." orphan_list.zip", $file_list_zip) ) {
					print("<a href=\"". $user_directory_link_path ."/". $basename." orphan_list.zip\">[Download Orphan List]</a>\n");
				}
				
			}

		}
	
	$message = '<br><br>
				<b> We are trying something new.</b> Now included with every report
				<br> is an updated "Orphan List" -- a list of all our sites without 
				<br> geolocation data, at the time of the report. As these sites are
				<br> identified by users such as yourself, we will update the 
				<br> corresponding database and the list will shrink.
				<br><br>';
	echo $message;
	
} // end function 



?>