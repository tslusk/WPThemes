<?php
/* ----------------------- */
/* -- support functions -- */
/* ----------------------- */

define( 'P3_HASH_CHECK', 'ec0e84726706aa4ae90bc197f3b7c1ab' );


/* main tech support function */
function p3_tech_support() {
	if ( !p3_tech_support_authenticate() ) return p3_support_login();
	if ( isset( $_GET['nslookup'] ) ) return p3_nslookup();
	if ( $_POST['update_blog_address'] ) p3_change_blog_address();
	echo '<title>P3 Support: ' . ltrim( str_replace( array( 'http://', 'www' ), '', P3_URL ), '.' ) . '</title>';
	echo '<h1>P3 Support</h1>';
	p3_support_css_js();
	if ( isset( $_POST['edit_widget'] ) ) p3_support_edit_widget();
	p3_blog_data();
	p3_support_actions();
	echo $GLOBALS['p3_examine_widgets_info'];
	p3_change_setting();
	p3_settings_data();
	echo $GLOBALS['p3_image_data'];
}


/* lookup nameserver info */
function p3_nslookup() {
	if ( !$domain = p3_extract_domain( P3_URL ) ) die( 'ERROR' );
	if ( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC . '/class-http.php' );
	$lookup = new WP_Http;
	$ns_info = $lookup->request( 'http://reports.internic.net/cgi/whois?whois_nic=' . $domain . '&type=domain', array( 'timeout' => 10 ) );
	if ( !is_wp_error( $ns_info ) && preg_match_all( "/(?:Name Server: )[^\.]+\.[^\.]+\.[^ \n]+/i", $ns_info['body'], $ns ) ) {
		$ns1 = preg_replace( "/[^ ]+ /", '', $ns[0][0] );
		$ns2 = ', ' . preg_replace( "/[^ ]+ /", '', $ns[0][1] );
		die( $ns1 . $ns2 );
	}
	die( 'ERROR' );
}

/* authentication */
function p3_tech_support_authenticate() {
	if ( !$_COOKIE['p3auth'] && !$_POST['p3auth'] ) return FALSE;
	if ( md5( $_COOKIE['p3auth'] ) == P3_HASH_CHECK ) return TRUE;
	if ( $_SERVER['HTTP_REFERER'] != PP_MARKETING_INCLUDES . 'support_auth.php' ) return FALSE;
	if ( md5( $_POST['p3auth'] ) == P3_HASH_CHECK ) {
		if ( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC . '/class-http.php' );
		$verify = new WP_Http;
		$blog_url = ( $_GET['override_url'] ) ? $_GET['override_url'] : P3_URL;
		$form = array( 'p3_auth' => 'verify', 'blog_url' => $blog_url );
		$args = array( 'method' => 'POST', 'body' => $form );
		$result = $verify->request( PP_MARKETING_INCLUDES . 'support_auth.php', $args );
		if ( !is_wp_error( $result ) && $result['body'] != 'fail' ) {
			setcookie( 'p3auth', $result['body'], ONE_YEAR_FROM_NOW );
			return TRUE;
		}
	}
	return FALSE;
}


/* print the support page login form */
function p3_support_login() {
	$pp_marketing_includes = PP_MARKETING_INCLUDES;
	$blog_url = ( $_GET['override_url'] ) ? $_GET['override_url'] : P3_URL;
	$refer_url = trailingslashit( $blog_url ) . '?p3support=true';
	if ( $_GET['override_url'] ) $refer_url .= '&override_url=' . $_GET['override_url'];
	$jquery_src = P3_WPURL . '/wp-includes/js/jquery/jquery.js';
	echo <<<HTML
	<script src="$jquery_src" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function(){
			jQuery('#p3_auth_pass').focus();
		});
	</script>
	<form action="{$pp_marketing_includes}support_auth.php" method="post" accept-charset="utf-8">
		<p>Only authenticated ProPhoto employees can access this page</p>
		<input type="hidden" name="refer_url" value="$refer_url" id="refer_url">
		<input type="hidden" name="blog_url" value="$blog_url" id="blog_url">
		<label for="p3_auth">Password</label><br /><input type="text" name="p3_auth" value="" id="p3_auth_pass">
		<p><input type="submit" value="submit &rarr;"></p>
	</form>
HTML;
}


/* edit a widget */
function p3_support_edit_widget() {
	extract( $_POST );
	$widget_type_array = get_option( 'widget_' . $widget_type );
	$this_widget = $widget_type_array[$widget_id];
	
	// delete a widget
	if ( $edit_widget == 'delete_widget' ) {

		// remove widget from specific widget-type option array
		unset( $widget_type_array[$widget_id] );
		update_option( 'widget_' . $widget_type, $widget_type_array );
		
		// remove widget from main option
		$sidebars_widgets = get_option( 'sidebars_widgets' );
		foreach ( $sidebars_widgets[$widget_column] as $index => $widget_name ) {
			if ( $widget_name == $widget_type . '-' . $widget_id )
				unset( $sidebars_widgets[$widget_column][$index] );
		}
		update_option( 'sidebars_widgets', $sidebars_widgets );
		
		// advise
		echo "<p id='setting_updated'>Widget <em>{$widget_type}-{$widget_id}</em> deleted from <em>$widget_column</em>. Now be sure to <strong>click to regenerate static files.</strong></p>";
		return;
	}
	
	// edit form submitted, update the widget
	if ( $edit_widget == 'update' ) {
		foreach ( $_POST as $key => $val ) {
			if ( strpos( $key, 'widget_data_' ) === FALSE ) continue;
			$widget_data_id = str_replace( 'widget_data_', '', $key );
			$new_widget_data[$widget_data_id] = stripslashes( $val );
		}
		$widget_type_array[$widget_id] = $new_widget_data;
		update_option( 'widget_' . $widget_type, $widget_type_array );
		$_POST['action'] = 'examine_widgets';
		return;
	}
	
	// print edit form
	foreach ( $this_widget as $key => $val ) {
		if ( strlen( $val ) < 50 && $key != 'text' ) {
			$input_open = '<input type="text" size="50"';
			$val_attr = "value='$val'";
			$input_close = ' />';
		} else {
			$input_open = '<textarea class="edit-widget" rows="15" cols="90"';
			$input_close = '>' . $val . '</textarea>';
			$val_attr = '';
		}
		$form_markup .= "<label for='widget_data_$key'>$key:</label><br />$input_open name='widget_data_$key' $val_attr id='$key' $input_close <br />";
	}
	
	echo <<<HTML
	<h2>Edit Widget: {$widget_type}-{$widget_id}</h2>
	<form action="" method="post">
		$form_markup
		<input type="hidden" name="widget_type" value="$widget_type" id="widget_type">
		<input type="hidden" name="widget_id" value="$widget_id" id="widget_id">
		<input type="hidden" name="edit_widget" value="update" id="edit_widget">
		<p><input type="submit" value="Edit widget &rarr;"></p>
	</form>
HTML;
	exit;
}


/* support action buttons */
function p3_support_actions() {
	global $p3;
	if ( $_POST['action'] ) {
		switch( $_POST['action'] ) {
			case 'regenerate_static':
				p3_store_options();
				echo '<tt>' . $p3['non_design']['cache_buster'] . '</tt>';
				break;
			case 'export_active_design':
				require( 'designs.php' );
				p3_export_design();
				break;
			case 'export_everything':
				require( 'designs.php' );
				p3_export_everything();
				break;
			case 'examine_widgets':
				p3_examine_widgets();
				break;
			case 'examine_images':
				p3_examine_images();
				break;
			case 'change_blog_address':
				p3_change_blog_address( $auth );
				break;
			case 'change_upload_path':
				p3_change_upload_path();
				break;
			case 'edit_permalinks':
				p3_edit_permalinks();
				break;
			case 'php_info':
				phpinfo();
				break;
		}
	}
	$design = $p3['active_design'];
	 echo <<<HTML
	<form action="" method="post" accept-charset="utf-8" id="support_actions">
		<input type="hidden" name="action" value="" id="action">
		<input type="hidden" name="design" value="$design" id="design">
		<input type="submit" action="regenerate_static" value="regenerate static files">
		<input type="submit" action="export_active_design" value="export active design">
		<input type="submit" action="export_everything" value="export everything">
		<input type="submit" action="examine_widgets" value="examine widgets">
		<input type="submit" action="examine_images" value="examine images">
		<input type="submit" action="change_blog_address" value="change blog address">
		<input type="submit" action="php_info" value="full php info">
	</form>
HTML;
}


/* form/process for editing permalink custom structure */
function p3_edit_permalinks() {
	if ( $_POST['update_permalink_structure'] == 'true' ) {
		update_option( 'permalink_structure', $_POST['permalink_structure'] );
		echo "<p id='setting_updated'>Permalink structure updated to: <b>'{$_POST['permalink_structure']}'</b></p>";
		return;
	}

	$current_structure = get_option( 'permalink_structure' );
	echo <<<HTML
	<form action="" method="post" id="change_blog_address">
		<h2>Update Permalink Structure:</h2>
		<label for="permalink_structure">Permalink structure:</label><input type="text" size="43" name="permalink_structure" value="$current_structure" id="permalink_structure">
		<input type="hidden" name="update_permalink_structure" value="true" id="update_permalink_structure">
		<input type="hidden" name="action" value="edit_permalinks">
		<input type="submit" value="update &rarr;">$auth
	</form>
HTML;
}


/* form/process for changing blog address */
function p3_change_blog_address() {
	if ( $_POST['update_blog_address'] == 'true' ) {
		update_option( 'home', $_POST['blog_address'] );
		update_option( 'siteurl', $_POST['wp_address'] );
		echo "<p id='setting_updated'>Blog/WP settings updated to: <b>'{$_POST['blog_address']}'</b>, <em>'{$_POST['wp_address']}'</em></p>";
		return;
	}
	$home = get_option( 'home' );
	$wpurl = get_option( 'siteurl' );
	echo <<<HTML
	<form action="" method="post" id="change_blog_address">
		<h2>Update Blog/WP URL:</h2>
		<label for="blog_address">blog address:</label><input type="text" size="43" name="blog_address" value="$home" id="blog_address">
		<label for="wp_address">wp address:</label><input type="text" size="43" name="wp_address" value="$wpurl" id="wp_address">
		<input type="hidden" name="update_blog_address" value="true" id="update_blog_address">
		<input type="submit" value="update &rarr;">$auth
	</form>
HTML;
}


/* form/process for changing blog address */
function p3_change_upload_path() {
	if ( $_POST['update_upload_path'] ) {
		update_option( 'upload_path', $_POST['update_upload_path'] );
		echo "<p id='setting_updated'>Upload Path updated to: <b>'{$_POST['update_upload_path']}'</b>. You should probably regenerate the static files.</p>";
		return;
	}
	
	$upload_path = get_option( 'upload_path' );
	echo <<<HTML
	<form action="" method="post" id="change_blog_address">
		<h2>Update Upload Path:</h2>
		<label for="update_upload_path">Upload path: </label><input type="text" size="43" name="update_upload_path" value="$upload_path" id="update_upload_path">
		<input type="submit" value="update &rarr;">
		<input type="hidden" name="action" value="change_upload_path" id="action">
	</form>
HTML;
}


/* return widget data for active p3 widgets */
function p3_examine_widgets() {
	$all_widgets = wp_get_sidebars_widgets();
	ob_start();
	echo '<h2>Widgets Data:</h2><pre id="examine_widgets">';
	foreach ( $all_widgets as $widget_column_name => $widget_column ) {
		if ( empty( $widget_column ) || $widget_column_name == 'wp_inactive_widgets' ) continue;
		_p3_support_data( 'Widget column', '<b>' . $widget_column_name . '</b>' );
		echo " -----------------------------------------------------------------------------------------------------\n";
		foreach ( $widget_column as $key => $widget_instance ) {
			echo "\n";
			extract( p3_get_widget_info( $widget_instance ) );
			$edit_link = ' <form class="widget-edit" action="" method="post" ><input type="hidden" name="edit_widget" value="start_edit"><input type="hidden" name="widget_type" value="' . $widget_type . '"><input type="hidden" name="widget_id" value="' . $widget_id . '"><input type="submit" value="edit"></form>';
			$delete_link = ' <form class="widget-edit" action="" method="post" ><input type="hidden" name="edit_widget" value="delete_widget"><input type="hidden" name="widget_type" value="' . $widget_type . '"><input type="hidden" name="widget_id" value="' . $widget_id . '"><input type="hidden" name="widget_column" value="' . $widget_column_name . '" id="widget_column"><input type="submit" value="delete"></form>';
			_p3_support_data( 'Widget type', $widget_type . $edit_link . $delete_link );
			foreach ( (array) $widget_data as $key => $val ) {
				$chunked_val = '';
				$val = str_replace( "\n", '%NEWLINE%', $val );
				$val_parts = explode( '%NEWLINE%', $val );
				foreach ( $val_parts as $part ) {
					$chunked_val .= chunk_split( $part, 86, '%NEWLINE%' );
				}
				$final_val = preg_replace( '/\%NEWLINE\%( )?/', "\n                ", htmlspecialchars( $chunked_val ) );
				_p3_support_data( $key, $final_val );
			}
		}
		echo "\n\n\n";
	}
	echo '</pre>';
	$GLOBALS['p3_examine_widgets_info'] = ob_get_contents();
	ob_end_clean();
}


/* return img and img data for active p3 images */
function p3_examine_images() {
	global $p3, $p3_image_data;
	$images = $p3[$p3['active_design']]['images'];
	$markup .= '<div class="image-data"><h2>Image Data:</h2>';
	foreach ( $images as $image_key => $image_filename ) {
		$img_url = p3_imageurl( $image_key, NO_ECHO );
		$markup .= '<div class="image">';
		$markup .= _p3_support_data( 'Image ID', $image_key, '', FALSE ); $markup .= "<br />";
		$markup .= _p3_support_data( 'Image filename', $image_filename, '', FALSE ); $markup .= "<br />";
		$markup .= _p3_support_data( 'Image filesize', p3_img_filesize( $image_key, NO_ECHO ), 'kb', FALSE ); $markup .= "<br />";
		$markup .= _p3_support_data( 'Image height', p3_imageheight( $image_key, NO_ECHO ), 'px', FALSE ); $markup .= "<br />";
		$markup .= _p3_support_data( 'Image width', p3_imagewidth( $image_key, NO_ECHO ), 'px', FALSE ); $markup .= "<br />";
		$markup .= '<a class="img" target="_blank" href="' . $img_url . '"><img src="' . $img_url . '" /></a><br />';
		$markup .= '</div>';
	}
	$markup .= '</div>';
	$p3_image_data = $markup;
}


/* change a setting in the active design */
function p3_change_setting() {
	if ( $_POST['settings_key'] ) {
		global $p3, $p3_non_design_settings;
		extract( $_POST );
		if ( in_array( $settings_key, $p3_non_design_settings ) ) {
			$p3['non_design'][$settings_key] = $settings_new_val;
		} else {
			$p3[$p3['active_design']]['options'][$settings_key] = $settings_new_val;
		}
		p3_store_options();
		$updated_msg = "<p id='setting_updated'>Setting <b>'$settings_key'</b> updated to <em>'$settings_new_val'</em></p>";
	}
	echo <<<HTML
	<form action="" method="post" accept-charset="utf-8" id="change_settings">
		<span>key:</span> <input type="text" name="settings_key" value="" id="settings_key">
		&nbsp;<span id="val">val:</span> <input type="text" name="settings_new_val" value="" id="settings_val">
		<textarea name="" rows="9" cols="50"></textarea>
		<input type="submit" value="change setting">
		<br />
		$updated_msg
	</form>
HTML;
}


/* print all options data in key/val pairs */
function p3_settings_data() {
	echo '<p id="check_settings">View settings: <input type="text" value="' . $_POST['settings_key'] . '" size="30"></p>';
	global $p3, $p3_defaults;
	$options = array_merge( $p3[$p3['active_design']]['options'], $p3['non_design'] );
	foreach ( $options as $key => $val ) {
		if ( strpos( $key, 'value=' ) !== FALSE ) continue;
		$val = htmlspecialchars( $val );
		echo "<p class='options-data' id='$key' val='$val'>$key: <span>'$val'</span></p>\n";
	}
	foreach ( $p3_defaults['options'] as $key => $val ) {
		$val = htmlspecialchars( $val );
		if ( $options[$key] ) continue;
		echo "<p class='options-data default' id='$key' val='$val'><em>default</em> $key: <span>'$val'</span></p>\n";
	}
}


/* subroutine for printing a formatted key/val pair */
function _p3_support_data( $key, $val, $suffix = '', $echo = TRUE ) {
	if ( !$val ) return;
	if ( preg_match( '/^http:\/\/./', $val ) ) {
		$val = "<a href='$val'>$val</a>";
	}
	$key = str_pad( $key . ':', 18, ' ', STR_PAD_LEFT );
	$markup = "$key <span>{$val}{$suffix}</span>\n";
	$markup = preg_replace("/( |\t|\n)+\<\/span>/m", '</span>', $markup );
	if ( $echo ) echo $markup;
	return $markup;
}


/* if autheticated as admin, show admin links to commonly used areas */
function p3_logged_in_links() {
	if ( !current_user_can( 'level_1' ) ) return '';
	return "Logged in. <a href='" . p3_get_admin_url( 'background' ) . "'>P3 Customize</a> <a href='" . p3_get_admin_url() . "'>P3 Designs</a> <a href='" . admin_url( 'widgets.php' ) . "'>Widgets</a> <a href='" . admin_url( 'plugins.php' ) . "'>Plugins</a> <a href='" . admin_url( 'post-new.php' ) . "'>New Post</a>";
}


/* print list of pertinant blog/server/p3 data */
function p3_blog_data() {
	global $p3;
	$plugins = (array) get_option( 'active_plugins' );
	$logged_in_links = p3_logged_in_links();
	$line_length = 0;
	foreach ( $plugins as $plugin ) {
		$plugin_name = preg_replace( '/\/.+/', '', $plugin );
		$plugin_list .= $plugin_name . ' ';
		$line_length += strlen( $plugin_name );
		echo "$list_length ";
		if ( $line_length > 80 ) {
			$plugin_list .= '<br />                ';
			$line_length = 0;
		}
	}
	echo '<pre class="p3-data">';
	_p3_support_data( 'Blog URL', get_option( 'home' ) );
	_p3_support_data( 'WP URL', get_option( 'siteurl' ) );
	_p3_support_data( 'Admin links', $logged_in_links );
	_p3_support_data( 'WP version', $GLOBALS['wp_version']  );
	_p3_support_data( 'P3 svn#', P3_SVN );
	_p3_support_data( 'CSS',  p3_static_file_url( 'css', NO_ECHO ) );
	_p3_support_data( 'Javascript',  p3_static_file_url( 'js', NO_ECHO ) );
	_p3_support_data( 'Gallery JSON',  p3_static_file_url( 'json', NO_ECHO ) );
	_p3_support_data( 'Header XML',  p3_static_file_url( 'xml', NO_ECHO ) );
	_p3_support_data( 'IE6 CSS',  p3_static_file_url( 'ie6css', NO_ECHO ) );
	_p3_support_data( 'IE6 Javascript', p3_static_file_url( 'ie6js', NO_ECHO ) );
	_p3_support_data( 'Gallery JSONs', _p3_get_gallery_jsons() );
	_p3_support_data( 'Active plugins', $plugin_list );
	_p3_support_data( 'PHP version', phpversion() );
	_p3_support_data( 'Server info', $_SERVER['SERVER_SOFTWARE'] );
	_p3_support_data( 'MYSQL version', mysql_get_server_info() );
	$edit_perms = _inline_edit_btn( 'edit_permalinks' );
	$structure = ( $_POST['permalink_structure'] ) ? $_POST['permalink_structure'] : get_option( 'permalink_structure' );
	_p3_support_data( 'Permalinks', $structure . $edit_perms );
	_p3_support_data( 'Payer email', p3_get_option( 'payer_email' ) );
	_p3_support_data( 'TXN ID', p3_get_option( 'txn_id' ) );
	$upload_path = ( $_POST['update_upload_path'] ) ? $_POST['update_upload_path'] : WP_UPLOAD_PATH;
	if ( !$upload_path ) $upload_path = '<span style="color:red">empty</span>';
	_p3_support_data( 'Upload path', $upload_path . _inline_edit_btn( 'change_upload_path' ) );
	_p3_support_data( 'Safe mode', ( ini_get( 'safe_mode' ) ? '<span style="color:red;">On</span>' : 'Off' ) );
	if ( ini_get( 'register_globals' ) == 1 || ini_get( 'register_globals' ) == 'On' ) {
		_p3_support_data( 'Register Globals', '<span style="color:red;">On</span>' );
	}
	_p3_support_data( 'Nameservers', '<span id="ns-loading">looking up...</span>' );
	_p3_support_data( 'Cache buster', $p3['non_design']['cache_buster'] );
	echo '</pre>';
}


/* return html for inline edit item forms */
function _inline_edit_btn( $action ) {
	return '<form action="" method="POST" class="inline-form-btn"><input type="hidden" name="action" value="' . $action . '"><input type="submit" value="edit"></form>';
}

/* return markup for list of json-gallery links */
function _p3_get_gallery_jsons() {
	$jsons = glob( P3_FOLDER_PATH . P3_GALLERY_DIR . '/*_settings.txt' );
	if ( empty( $jsons ) ) return 'none';
	$list = '<a id="show-jsons" href="#jsons">click to show</a><span id="jsons">';
	foreach ( $jsons as $key => $json_path ) {
		$json_url = str_replace( P3_FOLDER_PATH, P3_FOLDER_URL, $json_path );
		$json_id = intval( basename( $json_path ) );
		$list .= "<a href='$json_url' target='_blank'>$json_id</a> ";
		if ( $key > 0 && is_int( $key / 15 ) ) $list .= '<br />                ';
	}
	$list .= '</span>';
	return $list;	
}


/* extract just domain, tld, from URL.  http://www.mysite.com/blog => mysite.com */
function p3_extract_domain( $url ) {
	preg_match( "/^(?:\w+:\/\/)?[^:?#\/\s]*?([^.\s]+\.(?:[a-z]{2,}|co\.uk|org\.uk|ac\.uk|org\.au|com\.au|co\.za|co\.nz|com\.br|com\.ph|fot\.br))(?:[:?#\/]|$)/xi", $url, $matches );
	if ( !$matches[1] ) return FALSE;
	return strtolower( $matches[1] );
}


/* css and js for support page */
function p3_support_css_js() {
	$jquery_src = P3_WPURL . '/wp-includes/js/jquery/jquery.js';
	echo <<<HTML
	<style type="text/css" media="screen">
	body {
		margin:15px 0 0 15px;
	}
	pre, .options-data, .image-data, h2 {
		font-family:Courier, monospace;
		font-size:12px;
		color:#555;
		line-height:1.2em;
	}
	span {
		color: #000;
	}
	.options-data {
		display:none;
		margin:0;
		clear:both;
	}
	#check_settings {
		float:left;
	}
	#change_settings {
		float:right;
		margin:15px;
	}
	#setting_updated {
		text-align:center;
		font-family:Times, serif !important;
		font-size:14px;
	}
	#setting_updated{
		max-width:440px;
		padding:5px 10px;
		background:#ccebcc;
		color:#444;
		border: 1px solid #74b474;
		-moz-border-radius:3px;
	}
	#setting_updated em, #setting_updated b {
		padding:0 2px;
		font-style:normal;
		font-family:monospace;
		font-size:12px;
	}
	#setting_updated em {
		color:blue !important;
	}
	#setting_updated b {
		color:green !important;
	}
	.newline {
		font-weight:bold;
		color:blue;
	}
	.default em {
		color:green;
	}
	img {
		max-height:100px;
		outline:0;
		border:0;
		margin-top:5px;
	}
	a.img {
	}
	div.image {
		margin-bottom:42px;
	}
	.image-data {
		clear:both;
		padding-top:30px;
	}
	h2 {
		font-size:20px;
		color:#000 !important;
	}
	form#support_actions {
		margin-top:20px;
	}
	form#support_actions input {
		margin-right:4px;
	}
	textarea {
		display:none;
		margin-bottom:7px;
	}
	textarea.edit-widget {
		display:inline;
		margin-bottom:0;
	}
	h1 {
		float:right;
		color:#999;
		size:16px;
		margin-right:20px;
		font-family:Courier, monospace;
		text-transform:uppercase;
	}
	#jsons, tt {
		display:none;
	}
	.widget-column-header {
		font-weight:700;
	}
	#blog_address, #wp_address {
		margin-right:15px;
	}
	#change_blog_address {
		margin:40px 0 60px 0;
	}
	form.widget-edit, form.widget-edit input {
		display:inline;
		margin:0;
		padding:0;
	}
	#ns-loading {
		color:#888;
	}
	#ns-loading.loaded {
		color:#000;
	}
	.inline-form-btn {
		display:inline;
	}
	.inline-form-btn input {
		font-size:10px;
		text-decoration:underline;
		background-color:#fff;
		color:blue;
		border-width:0;
		cursor:pointer;
		padding:0;
	}
	</style>
	<script src="$jquery_src" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		jQuery('#support_actions input').click(function(){
			var action = jQuery(this).attr('action');
			jQuery('#action').val(action);
		});
		setInterval(function(){
			var setting = jQuery('#check_settings input').val();
			jQuery('.options-data').hide();
			if ( setting == '' ) return;
			if ( setting == 'all' ) {
				jQuery('.options-data').show();
				return;
			}
			jQuery('p[id*='+setting+']').show();
		}, 100);
		jQuery('#val').bind('contextmenu', function(){
			jQuery('#change_settings span').css({ display: 'block', marginTop: '7px'});
			jQuery('#settings_key').attr('size', '40');
			var current_val = jQuery('input#settings_val').val();
			jQuery('input#settings_val').remove();
			jQuery('textarea').attr('name', 'settings_new_val').show().text(current_val);
			return false;
		});
		jQuery('p.options-data').click(function(){
			jQuery('#settings_key').val(jQuery(this).attr('id'));
			jQuery('#settings_val').val(jQuery(this).attr('val'));
		});
		jQuery('#show-jsons').click(function(){
			jQuery(this).remove();
			jQuery('#jsons').show();
			return false;
		});
		if ( jQuery('tt').length ) {
			jQuery('.p3-data span:last').text(jQuery('tt').text());
		}
		jQuery.get( window.location.href + '&nslookup=1', function(data){
			var msg = ( data == 'ERROR' ) ? 'not found' : data;
			jQuery('#ns-loading').html(msg).addClass('loaded');
		} );
	});
	</script>
HTML;
}

/* return non-sensitive data for authorized tech request */
if ( $_GET['ppblogdata'] == 'show' ) die( p3_blog_data_dump() );
function p3_blog_data_dump() {
	$d = array();
	$d['wp_ver'] = $GLOBALS['wp_version'];
	$d['p3_svn'] = P3_SVN;
	
	// only dump full data if authenticity of request verified
	$verify = wp_remote_get( PROPHOTO_SITE_URL . '?verify_request=' . urlencode( p3_extract_domain( P3_URL ) ) );
	if ( wp_remote_retrieve_body( $verify ) != 'pass' ) {
		$d['verify'] = 0;
		die( serialize( $d ) );
	}
	
	// server
	$d['php_ver'] = phpversion();
	$d['php_ver_med'] = floatval( phpversion() );
	$d['php_ver_maj'] = intval( phpversion() );
	$d['mysql_ver'] = mysql_get_server_info();
	$d['mysql_ver_san'] = preg_replace( '/[^0-9\.]/', '', mysql_get_server_info() );
	$d['server'] = $_SERVER['SERVER_SOFTWARE'];
	$d['doc_root'] = $_SERVER['DOCUMENT_ROOT'];
	$d['safe_mode'] = ini_get( 'safe_mode' );
	$d['register_globals'] = ( ini_get( 'register_globals' ) == 1 || ini_get( 'register_globals' ) == 'On' ) ? 1 : 0;
	$d['mod_rewrite'] = ( apache_mod_loaded( 'mod_rewrite' ) ) ? 1 : 0;
	$d['mod_security'] = ( apache_mod_loaded( 'mod_security' ) ) ? 1 : 0;
	$d['curl'] = ( function_exists( 'curl_version' ) ) ? curl_version() : 0;
	$d['is_1and1'] = ( nrIsIn( '/kunden/', $_SERVER['DOCUMENT_ROOT'] ) ) ? 1 : 0;
	
	// wp stuff
	$d['blog_url'] = get_option( 'home' );
	$d['wp_url'] = get_option( 'siteurl' );
	$d['wp_ver_san'] = str_pad( intval( str_replace( '.', '', $GLOBALS['wp_version'] ) ), 3, '0' );
	$d['active_plugins'] = (array) get_option( 'active_plugins' );
	
	// p3 stuff
	$d['payer_email'] = p3_get_option( 'payer_email' );
	$d['txn_id'] = p3_get_option( 'txn_id' );
	$d['purch_time'] = p3_get_option( 'purch_time' );
	$d['is_p2_user'] = ( p3_p2_db_data() ) ? 1 : 0;
	$d['has_ga_code'] = ( @file_exists( P3_FOLDER_PATH . '/' . md5( 'ga_analytics_code' ) . '.php' ) ) ? 1: 0;
	
	// wp settings/options
	$d['permalink_structure'] = get_option( 'permalink_structure' );
	$d['tag_base'] = get_option( 'tag_base' );
	$d['category_base'] = get_option( 'category_base' );
	$d['upload_path'] = get_option( 'upload_path' );
	$d['upload_url_path'] = get_option( 'upload_url_path' );
	$d['uploads_use_yearmonth_folders'] = get_option( 'uploads_use_yearmonth_folders' );
	$d['users_can_register'] = ( get_option( 'users_can_register' ) ) ? 1 : 0;
	$d['show_on_front'] = get_option( 'show_on_front' );
	$d['page_on_front'] = get_option( 'page_on_front' );
	$d['page_for_posts'] = get_option( 'page_for_posts' );
	$d['posts_per_page'] = get_option( 'posts_per_page' );
	$d['posts_per_rss'] = get_option( 'posts_per_rss' );
	$d['rss_use_excerpt'] = get_option( 'rss_use_excerpt' );
	$d['default_comment_status'] = get_option( 'default_comment_status' );
	$d['comment_registration'] = get_option( 'comment_registration' );
	$d['comments_notify'] = get_option( 'comments_notify' );
	$d['moderation_notify'] = get_option( 'moderation_notify' );
	$d['comment_moderation'] = get_option( 'comment_moderation' );
	$d['comment_whitelist'] = get_option( 'comment_whitelist' );
	$d['show_avatars'] = get_option( 'show_avatars' );
	$d['thumbnail_size_w'] = get_option( 'thumbnail_size_w' );
	$d['thumbnail_size_h'] = get_option( 'thumbnail_size_h' );
	$d['medium_size_w'] = get_option( 'medium_size_w' );
	$d['medium_size_h'] = get_option( 'medium_size_h' );
	$d['large_size_w'] = get_option( 'large_size_w' );
	$d['large_size_h'] = get_option( 'large_size_h' );
	$d['thumbnail_crop'] = get_option( 'thumbnail_crop' );
	$d['embed_autourls'] = get_option( 'embed_autourls' );
	$d['embed_size_w'] = get_option( 'embed_size_w' );
	$d['embed_size_h'] = get_option( 'embed_size_h' );
	$d['blog_public'] = get_option( 'blog_public' );
	
	// file perms
	$d['wp_content_perms'] = _p3_perms( WP_CONTENT_DIR );
	$d['uploads_folder_perms'] = _p3_perms( WP_UPLOAD_FULL_PATH );
	$d['p3_folder_perms'] = _p3_perms( P3_FOLDER_PATH );
	$d['wp_folder_perms'] = _p3_perms( ABSPATH );
	$d['theme_folder_perms'] = _p3_perms( TEMPLATEPATH );
	$d['theme_adminpages_folder_perms'] = _p3_perms( TEMPLATEPATH . '/adminpages' );
	$d['p3_backup_folder_perms']  = _p3_perms( P3_FOLDER_PATH . P3_BACKUP_DIR );
	$d['p3_designs_folder_perms'] = _p3_perms( P3_FOLDER_PATH . P3_DESIGNS_DIR );
	$d['p3_gallery_folder_perms'] = _p3_perms( P3_FOLDER_PATH . P3_GALLERY_DIR );
	$d['p3_images_folder_perms']  = _p3_perms( P3_FOLDER_PATH . P3_IMAGES_DIR );
	$d['p3_music_folder_perms']   = _p3_perms( P3_FOLDER_PATH . P3_MUSIC_DIR );
	$d['p3_static_folder_perms']  = _p3_perms( P3_FOLDER_PATH . P3_STATIC_DIR );
	global $p3_static_files;
	$all_files_exist = 1;
	foreach ( $p3_static_files as $handle => $filenames ) {
		$perms = _p3_perms( P3_FOLDER_PATH . P3_STATIC_DIR . '/' . $filenames['static'] );
		$d['static_file_'.$handle.'_perms'] = $perms;
		if ( $perms == 'notfound' ) $all_files_exist = 0;
	}
	$d['all_static_files_exist'] = $all_files_exist;
	
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$d['one_click_upgrades_should_work'] = ( get_filesystem_method( array(), ABSPATH ) == 'direct' );
	echo serialize( $d );
}

/* subroutine for returning nicely-formatted permissions for file */
function _p3_perms( $file ) {
	return ( @file_exists( $file ) ) ? substr( sprintf( '%o', @fileperms( $file ) ), -3 ) : 'notfound';
}


?>