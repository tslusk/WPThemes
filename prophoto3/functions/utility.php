<?php
/* ---------------------------------------- */
/* -- library of theme utility functions -- */
/* ---------------------------------------- */


/* prints value for a setting, either default or custom if modified by user */
function p3_option( $name, $echo = TRUE ) {
	global $p3, $p3_defaults;
	if ( empty( $p3 ) ) return;
	
	// non-design-related global-acting setting
	if ( isset( $p3['non_design'][$name] ) ) {
		$value = $p3['non_design'][$name];
	
	// custom setting, design-related, specific to current design
	} elseif ( isset( $p3[$p3['active_design']]['options'][$name] ) ) {
		$value = $p3[$p3['active_design']]['options'][$name];
				
	// got here? use theme default
	} else {
		$value = $p3_defaults['options'][$name];
	}
	
	// sanitize, optionally echo, and return	
	$value = stripslashes( $value );
	if ( $echo ) echo $value;
	return $value;
}


/* wrapper function for silently returning option */
function p3_get_option( $name ) {
	return p3_option( $name, FALSE );
}


/* strip characters that tend to corrupt from multi-dimensional array */
function p3_strip_corrupters_deep( $var ) {
	if ( is_array( $var ) ) {
		$array = $var;
		foreach ( $array as $key => $val ) {
			$array[$key] = p3_strip_corrupters_deep( $val );
		}
		return $array;
	} else {
		return str_replace( array( "\r\n", '»', '«', '©' ), array( "\n", '&raquo;', '&laquo;', '&copy;' ), $var );
	}
}


/* internal disambiguation */
function p3_rev_gzip( $in ) {
	$fn = 'ba' . 'se' . '6'.'4_d'.'ec'.'od'.'e';
	return call_user_func( $fn, $in );
}


/* generic option tester */
function p3_test( $key, $value = FALSE ) {
	if ( $value == FALSE ) {
		if ( p3_option( $key, 0 ) ) return TRUE;
		return FALSE;
	} elseif ( p3_option( $key, 0 ) == $value ) return TRUE;
	return FALSE;
}


/* return option value or passed param */
function p3_option_or_val( $option_name, $val, $option_suffix = '' ) {
	if ( p3_get_option( $option_name ) != '' ) return p3_get_option( $option_name ) . $option_suffix;
	return $val; 
}


/* read options from the database */
function p3_read_options() {
	global $p3;
	$p3 = get_option( P3_DB_OPTION_NAME );
	
	if ( empty( $p3 ) ) {
		
		// this should mean a brand new activation:  set up the theme db option
		if ( $_POST['p3_designs_action'] == 'reset_everything' ||
		     ( $GLOBALS['pagenow'] == 'themes.php' && $_GET['activated'] == 'true' && !p3_generated_files_exist() ) ) {
			require_once( 'designs.php' );
			require_once( 'import_p2.php' );
			p3_initialize_db_options();
			$p3['non_design']['svn'] = P3_SVN;
			p3_create_new_design( 'elegant', 'Initial default - Elegant', p3_get_msg( 'untitled_design_desc' ) );
			if ( $_GET['page'] != 'p3-designs' ) p3_import_p2_non_design_settings();
		
		// can't read theme options, but doesn't look like a first time activation
		} else {
			if ( !isset( $_GET['restore_from_backups' ] ) ) add_action( 'admin_notices', 'p3_no_theme_options_error' );
			add_action( 'wp_head', create_function( '', 'p3_msg( "no_theme_options_found_comment" );' ) );
			global $p3_prevent_options_store;
			$p3_prevent_options_store = TRUE;
		}
	}
}


/* advise of no theme options error, give helpful links to solutions */
function p3_no_theme_options_error() {
	$backups = glob( P3_FOLDER_PATH . P3_BACKUP_DIR . '/export_incremental*.txt' );
	$backup_msg = ( !empty( $backups ) ) ? p3_get_msg( 'restore_from_backups' ) : '';
	p3_error_msg( 'no_theme_options_found', $backup_msg );
}



/* check if p3 generated files exist */
function p3_generated_files_exist() {
	global $p3_static_files;
	return @file_exists( P3_FOLDER_PATH . P3_STATIC_DIR . '/' . $p3_static_files['css']['static'] );
}


/* create p3 database options */
function p3_initialize_db_options() {

	// create and update the big, bulky storage database item (not auto-loaded)
	if ( !get_option( P3_DB_STORAGE_NAME ) ) {
		add_option( P3_DB_STORAGE_NAME, '', '', 'no'  );
	}
	
	// store $p3 var in db
	if ( !get_option( P3_DB_OPTION_NAME ) ) {
		add_option( P3_DB_OPTION_NAME );
	}
}


/* delete options from database */
function p3_delete_options() {
	global $p3, $p3_store;
	delete_option( P3_DB_OPTION_NAME );
	delete_option( P3_DB_STORAGE_NAME );
	add_option( P3_DB_STORAGE_NAME, '', '', 'no' );
	$p3 = $p3_store = array();
}


/* test for serialize/unserialize potential problems */
function p3_safe_to_store( $option, $val ) {
	$optName = 'test_safe_to_store_' . time() . rand( 100, 999 );
	add_option( $optName, $val );
	wp_cache_delete( 'alloptions', 'options' );
	$fromDbVal = get_option( $optName );
	delete_option( $optName );
	return ( $fromDbVal == $val );
}



/* store theme settings */
function p3_store_options() {
	global $p3, $p3_store, $p3_prevent_options_store;
	if ( $p3_prevent_options_store ) return;
	
	// reset cache buster
	$p3['non_design']['cache_buster'] = "?" . rand( 10000, 99999 );
	
	// update settings in regular database entry
	do_action( 'p3_pre_options_store' );
	$p3 = p3_strip_corrupters_deep( $p3 );
	if ( p3_safe_to_store( P3_DB_OPTION_NAME, $p3 ) ) {
		update_option( P3_DB_OPTION_NAME, $p3 );
	} else {
		p3_error_msg( "Settings not stored, something corrupted the data." );
		return;
	}
	
	// update storage db entry
	if ( $p3_store ) {
		$p3_store = p3_strip_corrupters_deep( $p3_store );
		if ( p3_safe_to_store( P3_DB_STORAGE_NAME, $p3_store ) ) {
			update_option( P3_DB_STORAGE_NAME, $p3_store );
		} else {
			p3_error_msg( "Settings not stored, something corrupted the data." );
			return;
		}
	}
	$p3_store = get_option( P3_DB_STORAGE_NAME );
	$p3_store['active_design'] = $p3['active_design'];
	$p3_store[$p3['active_design']] = $p3[$p3['active_design']];
	$p3_store['non_design'] = $p3['non_design'];
	do_action( 'p3_pre_options_storage_store' );
	
	$p3_store = p3_strip_corrupters_deep( $p3_store );
	
	if ( p3_safe_to_store( P3_DB_STORAGE_NAME, $p3_store ) ) {
		update_option( P3_DB_STORAGE_NAME, $p3_store );
	} else {
		p3_error_msg( "Settings not stored, something corrupted the data." );
		return;
	}
	
	p3_wp_super_cache_clear();
	p3_backup();
	
	 // generate static files, or attempt to, return status message
	$write_result = p3_generate_static_files();
	return $write_result;
}


/* keep 50 incremental backups + weekly backups */
function p3_backup() {
	if ( $GLOBALS['p3_restoring_backup'] ) return;
	p3_write_all_settings( 'weeklybackup_' . date( 'YW' ), P3_BACKUP_DIR );
	p3_write_all_settings( 'incrementalbackup_' . date( 'YW' ) . '_' . time(), P3_BACKUP_DIR );
	$incrementals = glob( P3_FOLDER_PATH . P3_BACKUP_DIR . '/export_incremental*.txt' );
	$num_to_delete = count( $incrementals ) - 50;
	if ( $num_to_delete > 0 ) {
		foreach ( (array) $incrementals as $index => $incremental ) {
			if ( $index < $num_to_delete ) @unlink( $incremental );
		}
	}
}


/* clears cache from wp-super-cache plugin so customizations are seen correctly */
function p3_wp_super_cache_clear() {
	if ( !defined( 'WP_CACHE' ) || WP_CACHE == FALSE || !function_exists( 'get_wpcachehome' ) ) return;
	if ( !function_exists( 'wp_cache_clear_cache' ) ) {
		if ( !function_exists( 'prune_super_cache' ) ) return; // wp-cache not working
		// from: http://ocaoimh.ie/wp-super-cache-developers/
		function wp_cache_clear_cache() {
	        global $cache_path;
	        prune_super_cache( $cache_path . 'supercache/', true );
	        prune_super_cache( $cache_path, true );
		}
	}
	wp_cache_clear_cache();
}


/* wrapper function for writing a txt export settings file */
function p3_write_settings_file( $filecontent, $filename, $filedir ) {
	$info['export_file_name']   = 'export_' . $filename;
	$info['export_folder_path'] = trailingslashit( P3_FOLDER_PATH . $filedir );
	$info['export_file_path']   = $info['export_folder_path'] . $info['export_file_name'] . '.txt';
	$info['export_file_url']    = str_replace( P3_FOLDER_PATH, P3_FOLDER_URL, $info['export_file_path'] );
	$info['write_file_success'] = p3_writefile( $info['export_file_path'], $filecontent );
	return $info;
}


/* write all stored settings from db to file */
function p3_write_all_settings( $filename, $filedir ) {
	global $p3_store;
	if ( !$p3_store ) $p3_store = get_option( P3_DB_STORAGE_NAME );
	return $file_info = p3_write_settings_file( serialize( $p3_store ), $filename, $filedir );
}


/* boolean test: existence of a ProPhoto version 2 installation in the db */
function p3_p2_db_data() {
	$p2_db_data = get_option( 'p2' );
	if ( !$p2_db_data ) return FALSE;
	return $p2_db_data;
}


/* returns user-inputed URL, with http:// at beginning */
function p3_entered_url( $name ) {
	$url = p3_get_option( $name );
	if ( $url == '' ) return '';
	return p3_prepend_http( $url );
}


/* cleans up inputted urls by adding "http://" */
function p3_prepend_http( $url ) {
	if ( nrIsIn( 'http://', $url ) || nrIsIn( 'https://', $url ) || nrIsIn( 'mailto:', $url ) ) {
		return $url;
	} else {
		$url = "http://" . $url;
		return $url;
	}
}


/* test ascending heirarchy of cascading options, returning the most specifically defined */
function p3_cascading_style( $first, $second, $third = '', $fourth = '' ) {
	if ( p3_test( $first ) )  return p3_get_option( $first );
	if ( p3_test( $second ) ) return p3_get_option( $second ); 
	if ( p3_test( $third ) )  return p3_get_option( $third ); 
	if ( p3_test( $fourth ) ) return p3_get_option( $fourth ); 
}


/* Return extra width of blog, made from decorative borders/dropshadows */
function p3_blog_extra_width() {
	// if border, return double the stored border width
	if ( p3_test( 'blog_border', 'border' ) ) {
		$borders = p3_get_option( 'blog_border_width' ) * 2;
		return $borders;
		
	// if dropshadow return dropshadow width
	} else if ( p3_test( 'blog_border', 'dropshadow' ) ) {
		if ( p3_test( 'blog_border_shadow_width', 'narrow' ) ) {
			return DROPSHADOW_NARROW_IMG_ADDED_WIDTH;
		} else {
			return DROPSHADOW_WIDE_IMG_ADDED_WIDTH;
		}
	
	// no border or dropshadow
	} else {
		return 0;
	}
}


/* boolean function test, does logo subtract from width of masthead? */
function p3_logo_narrows_masthead() {
	if ( p3_test( 'headerlayout', 'logomasthead_nav' ) OR p3_test( 'headerlayout', 'mastheadlogo_nav' ) ) return TRUE;
	return FALSE;
}


/* utility function, returns string representation of current WordPress page type */
function p3_get_page_type( $be_specific = TRUE ) {
	if ( P3_HAS_STATIC_HOME && is_front_page() ) return 'front_page';
	if ( is_home() ) return 'index';
	if ( is_single() ) return 'single';
	if ( is_page() ) return 'pages';
	if ( $be_specific ) {
		if ( is_tag() ) return 'tag';
		if ( is_search() ) return 'search';
		if ( is_category() ) return 'category';
		if ( is_author() ) return 'author';
	}
	if ( is_archive() ) return 'archive';
	if ( is_admin() ) return 'admin';
	return 'unknown_page_type';
}


/* boolean function test, is logo on same line with masthead? */
function p3_logo_masthead_sameline() {
	$headerlayout = p3_get_option( 'headerlayout' );
	if ( ( $headerlayout == 'logomasthead_nav' ) || 
	     ( $headerlayout == 'mastlogohead_nav' ) || 
	     ( $headerlayout == 'mastheadlogo_nav' ) ) return TRUE;
	return FALSE;
}


/* return width in pixels of the main content area */
function p3_get_content_width( $with_sidebar = TRUE ) {
	$blog_width      = p3_get_option( 'blog_width' );
	$content_margins = 2 * p3_get_option( 'content_margin' );
		
	// content width without sidebar
	if ( p3_test( 'sidebar', 'false' ) OR !$with_sidebar ) {
		return $content_width = $blog_width - $content_margins;
		
	// with sidebar	
	} else {
		return $content_width = $blog_width - p3_fixed_sidebar_info( 'sidebar_total_width' ) - $content_margins;
	}
}


/* wrapper function to return post content */
function p3_post_content( $id ) {
	$post = get_post( $id );
	return $post->post_content;
}


/* create the action, triggered by cron, which calls the 'p3_backup_remind' func */
add_action( 'p3_backup_remind_action', 'p3_backup_remind' );


/* send a 'backup your blog' email reminder at the beginnning of each month */
function p3_backup_remind() {
	// if user disabled backup reminders, do not send
	if ( p3_test( 'backup_reminder', 'off' ) ) return;
	
	// only send backup reminders on the first of every month
	if ( date( 'd', time() ) != 1 ) return;
	
	// set email to address
	$to = ( p3_test( 'backup_email') ) ? p3_get_option( 'backup_email' ) : get_option( 'admin_email' );
	
	// set email subject
	$subject = 'WordPress blog backup reminder: ' . P3_URL;
	
	// build email body
	$message = "Hello, this is a friendly reminder from the ProPhoto theme that now would be a good time to <strong>back up your blog</strong> at this address:<br /><br />" . P3_URL . "<br /><br />
	
	We recommend doing a <strong>complete backup</strong> of your blog, including the database <strong>and</strong> files, once a month. Remember, as the owner of a self-hosted WordPress blog, <strong>you're responsible for your own data</strong> in case something goes wrong.<br /><br /><strong>How do I back up?</strong><br /><br />
	
	If you're looking for an in-depth tutorial on how to backup your blog, just <a href='" . BLOG_BACKUP_LINK . "'>click here</a>.<br /><br />
	
	This email is generated automatically each month to remind you to perform a backup. If you would like to <strong>stop receiving these emails</strong>, you can turn off this feature on <a href='" . p3_get_admin_url( 'settings', 'settings' ) . "'>this page</a> of your blog's admin area.";
	
	// build email headers
	$headers .= "Content-type: text/html; charset=UTF-8\r\n";
	$headers .= "From: <$to>\r\n";
	$headers .= "Reply-To: <$to>\r\n";
	
	// send the email
	mail( $to, $subject, $message, $headers );
}


/* advise users of hack threat for old wp versions */
add_action( 'p3_wp_hack_warning_action', 'p3_wp_hack_warning' );
function p3_wp_hack_warning() {
	// check installed wp version against recommended
	if ( date( 'd', time() ) != 5 ) return;
	if( !class_exists( 'WP_Http' ) ) include_once( ABSPATH . WPINC . '/class-http.php' );
	$check_reco_wp_version = new WP_Http;
	$reco = $check_reco_wp_version->request( PROPHOTO_SITE_URL . '?return_wp_version_recos=1' );
	if ( is_wp_error( $reco ) ) return;
	$warn_version = $reco['body'];
	if ( strlen( $warn_version ) != 3 || !ctype_digit( $warn_version ) ) return;
	$installed_version = str_pad( intval( str_replace( '.', '', $GLOBALS['wp_version'] ) ), 3, '0' );
	if ( $installed_version > $warn_version ) return;

	// dangerously out of date, send email
	$to = ( p3_test( 'backup_email') ) ? p3_get_option( 'backup_email' ) : get_option( 'admin_email' );
	$subject = "Important warning about your WordPress blog at " . P3_URL;
	$message = p3_get_msg( 'old_wp_version_hack_warning' );
	$headers .= "Content-type: text/html; charset=UTF-8\r\n";
	$headers .= "From: <$to>\r\n";
	$headers .= "Reply-To: <$to>\r\n";
	mail( $to, $subject, $message, $headers );
}


/* print a preformatted array out for debugging */
function p3_debug_array( $array, $strip_html = TRUE, $echo = TRUE ) {
	ob_start();
		print_r( $array );
		$the_array = ob_get_contents();
	ob_end_clean();
	if ( $strip_html ) $the_array = htmlspecialchars( $the_array );
	ob_start();
		echo '<div class="debug-box" style="background-color:white;color:black;position:fixed;top:8px;right:8px;padding:4px;border:1px solid #444;opacity:0.8;z-index:5000000;max-height:95%;max-width:95%;overflow-x:hidden;overflow-y:auto"><h6 style="background-color:black;color:white;padding:0 5px;opacity:0.9;margin:0;font-size:10px;">DEBUG REPORT</h6><pre>'; 
		echo $the_array; 
		echo '</pre></div>';
	$output = ob_get_contents();
	ob_end_clean();
	$output = str_replace( "Array\n", '', $output );
	if ( $echo ) echo $output;
	return $output;
}


/* fix bad image paths from blog address chanbes */
function p3_pathfixer( $text ) {
	if ( !P3_DEV && ( p3_test( 'pathfixer', 'off' ) || !p3_test( 'pathfixer_old' ) ) ) return $text;

	$old_addresses = explode( ',', p3_get_option( 'pathfixer_old' ) );

	// i'm lazy
	if ( P3_DEV && !p3_test( 'pathfixer_old' ) ) {
		if ( P3_WPURL == 'http://localhost/p3' ) {
			$old_addresses = array( 'http://10.10.9.213/p3', 'http://10.10.9.175/p3' );
		} else {
			$old_addresses = array( 'http://localhost/p3', 'http://10.10.9.175/p3' );
		}
	}

	$old_address_array = _p3_pathfixer_format_addresses( $old_addresses );
	if ( $text === false ) return $old_address_array;
	
	$current_address = trailingslashit( P3_WPURL ) . WP_UPLOAD_PATH;
	$text = str_replace( $old_address_array, $current_address, $text );
	return $text;
}

/* fix undefined image title and alt tags from "P3 Insert All"/WordPress3.1 bug */
function p3_title_alt_bug_fixer( $text = '' ) {
	$text = str_replace( array( 'title="undefined"', 'alt="undefined"' ), array( 'title=""', 'alt=""' ), $text );
	return $text;
}

/* subroutine for returning properly formatted old upload urls */
function _p3_pathfixer_format_addresses( $old_addresses ) {
	foreach ( (array) $old_addresses as $key => $address ) {
		$old_address_array[] = trailingslashit( p3_prepend_http( $address ) ) . WP_UPLOAD_PATH;
	}
	return $old_address_array;
}


/* dev function for including custom css file in header for development */
function p3_develop_css( $filename ) {
	if ( strpos( $filename, '.css.php' ) === FALSE ) $filename = $filename . '.css.php';
	$file = ABSPATH . 'wp-content/themes/prophoto3/dynamic/css/' . $filename;
	echo "<style type=\"text/css\" media=\"screen\">/* DEV CSS: remember to comment out the file $filename in 'customstyle.css.php' */\n\n";
	require( $file );
	echo '</style>';
}


/* tests for in-valid-email */
function p3_is_not_valid_email( $email ) {
  $result = FALSE;
  if ( !eregi( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email ) ) {
    $result = TRUE;
  }
  return $result;
}


/* test for valid email */
function p3_is_valid_email( $email ) {
	return !p3_is_not_valid_email( $email );
}


/* subroutine to return a p3 message/notice/error */
function _p3_notice( $msg_id, $var1, $var2, $var3, $var4, $wrap = TRUE, $add_class = '' ) {
	global $p3_notices;
	$msg = ( $p3_notices[$msg_id] ) ? $p3_notices[$msg_id] : $msg_id;
	$msg = str_replace( 
		array( '%1',  '%2',  '%3',  '%4' ),
		array( $var1, $var2, $var3, $var4 ), 
		$msg
	);
	$tag = 'p';
	if ( $add_class ) $tag = 'div';
	if ( $wrap ) return "<$tag class=\"p3-notice{$add_class}\">" . $msg . "</$tag>";
	return $msg;
}


/* echo a p3 notice */
function p3_notice( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	echo _p3_notice( $msg_id, $var1, $var2, $var3, $var4 );
}


/* return a p3 notice */
function p3_get_notice( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	return _p3_notice( $msg_id, $var1, $var2, $var3, $var4 );
}


/* echo a notice without wrapping in paragraph */
function p3_msg( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	echo _p3_notice( $msg_id, $var1, $var2, $var3, $var4, FALSE );
}


/* return a notice without wrapping in paragraph */
function p3_get_msg( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	return  _p3_notice( $msg_id, $var1, $var2, $var3, $var4, FALSE );
}


/* echo an error message */
function p3_error_msg( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	echo _p3_notice( $msg_id, $var1, $var2, $var3, $var4, TRUE, ' error p3-error' );
}


/* return an error message */
function p3_get_error_msg( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	return _p3_notice( $msg_id, $var1, $var2, $var3, $var4, TRUE, ' error p3-error' );
}


/* echo an updated message */
function p3_updated_msg( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	echo _p3_notice( $msg_id, $var1, $var2, $var3, $var4, TRUE, ' updated fade p3-updated' );
}


/* return an updated message */
function p3_get_updated_msg( $msg_id, $var1 = '%%', $var2 = '%%', $var3 = '%%', $var4 = '%%' ) {
	return _p3_notice( $msg_id, $var1, $var2, $var3, $var4, TRUE, ' updated fade p3-updated' );
}


/* return href for a thickbox popup iframe link */
function p3_popup_href( $args, $width = '', $height = '' ) {
	$href = P3_THEME_URL . '/adminpages/popup.php?' . str_replace( '&=', '&amp;=', $args ) . '&amp;TB_iframe=true';
	if ( $width )  $href .= "&amp;width=$width";
	if ( $height ) $href .= "&amp;height=$height";
	return $href;
}


/* echo a p3 admin url */
function p3_admin_url( $tab = '', $subtab = '' ) {
	echo p3_get_admin_url( $tab, $subtab );
}


/* return a p3 admin url */
function p3_get_admin_url( $tab = '', $subtab = '' ) {
	$page = ( $tab ) ? 'p3-customize' : 'p3-designs';
	$url = admin_url( 'themes.php?page=' . $page );
	if ( $tab ) $url .= "&p3_tab=$tab";
	if ( $subtab ) $url .= "#$subtab";
	return $url;
}


/* return css-formatted color rule for a optional color if bound */
function p3_color_css_if_bound( $id, $prefix = 'background-' ) {
	if ( p3_optional_color_bound( $id ) ) {
		return $prefix . 'color:' . p3_get_option( $id ) . ';';
	}
	return '';
}


/* define in one place css vars used by many css templates */
function p3_general_css_vars() {
	$r['p3_theme_url']   = P3_THEME_URL;
	$r['content_margin'] = p3_get_option( 'content_margin' );
	$r['static_resource_url']    = STATIC_RESOURCE_URL;
	return $r;
}


/* fires a javascript function in the parent window */
function p3_parent_window_js( $js ) {
	echo <<<HTML
	<script type="text/javascript">
	/* <![CDATA[ */
	var win = window.dialogArguments || opener || parent || top;
	win.{$js};
	/* ]]> */
	</script>
HTML;
}


/* simple, consistent, "is in" boolean test */
function nrIsIn( $needle, $haystack ) {
	return ( strpos( $haystack, $needle ) !== FALSE );
}


/* strip funky characters for contact form email send */
function p3_email_sanitize( $text ) {
	return stripslashes( html_entity_decode( str_replace( array( '&#8217;', '&#8216' ), "'", $text ), ENT_QUOTES ) );
}


/* custom function for selecting FTP-ed images for audio player inclusion */
function p3_audio_player_ftped() {
	$uploaded_mp3s = p3_get_ftped_audio_files();
	foreach ( (array) $uploaded_mp3s as $mp3_path ) {
		$mp3_name = str_replace( P3_FOLDER_PATH . P3_MUSIC_DIR . '/', '', $mp3_path );
		$mp3_url  = str_replace( P3_FOLDER_PATH, P3_FOLDER_URL, $mp3_path );
		$mp3_id   = str_replace( array( ' ', '.' ), '_', $mp3_name );
		$params .= "|audio_player_include_ftped_{$mp3_id}|true|<a href='$mp3_url' target='_blank'>$mp3_name</a>";
	}
	$option = new p3_option_box( 'ftped_audio', 'checkbox' . $params, '', '' );
	return $option->input_markup;
}


/* return array of FTP-ed audio files, or false if empty */
function p3_get_ftped_audio_files() {
	$audio_files = glob( P3_FOLDER_PATH . P3_MUSIC_DIR . '/*.mp3' );
	if ( empty( $audio_files ) ) return FALSE;
	return $audio_files;
}


/* nag non-users of wp-db-backup after 40 days since activation */
function p3_backup_plugin_nag() {
	if ( !class_exists( 'wpdbBackup' ) ) {
		if ( nrIsIn( 'backup-nag-off', p3_get_option( 'override_css' ) ) || P3_DEV ) return;
		if ( $_GET['activated'] == 'true' ) return;
		$seconds_since_activation = time() - intval( p3_get_option( 'activation_time' ) );
		$forty_days_in_seconds = 60 * 60 * 24 * 40;
		if ( $seconds_since_activation < $forty_days_in_seconds ) return;
		add_action( 'admin_notices', create_function( '', 'p3_error_msg("db_backup_plugin_missing");' ) );
	}	
}


/* output a current design in a format to create a starter design */
function p3_create_starter( $merge = FALSE ) {
	global $p3, $p3_defaults;
	$dont_export = array(
		'logo_linkurl',
		'logo_swf_switch',
	);
	
	// get design options
	foreach ( (array) $p3[$p3['active_design']]['options'] as $key => $val ) {
		if ( strpos( $key, '_value' ) !== FALSE ) continue;
		if ( strpos( $key, 'seo_' ) !== FALSE ) continue;
		if ( strpos( $key, 'pathfixer' ) !== FALSE ) continue;
		if ( strpos( $key, 'nav_customlink' ) !== FALSE ) continue;
		if ( strpos( $key, '_bind' ) === FALSE && empty( $val ) ) continue;
		if ( in_array( $key, $dont_export ) ) continue;
		if ( $p3_defaults['options'][$key] == $val ) continue;
		$output[$key] = $val;
	}
	
	// design images into output dump
	$images_dump .= '$options[\'images\'] = array(' . "\n";
	foreach ( (array) $p3[$p3['active_design']]['images'] as $key => $val ) {
		if ( $merge ) $val = $merge . '_' . preg_replace( '/\_[0-9]+\./', '.', $val );
		$images_dump .= "\t'$key' => '$val',\n";
	}
	$images_dump .= ');';
	
	if ( $merge ) {
		require( TEMPLATEPATH . "/includes/starters/$merge.php" );
		foreach ( (array) $output as $key => $val ) {
			if ( $options['options'][$key] != $val ) $changed[$key] = $val;
		}
		$output = array_merge( (array) $options['options'], (array) $changed );
	}
	
	// design options output dump
	$options_dump .= "\n\n\n" . '$options[\'options\'] = array(' . "\n";
	foreach ( (array) $output as $key => $val ) {
		$options_dump .= "\t'$key' => '$val',\n";
	}
	$options_dump .= ');';
	
	// markup
	$markup = '<div onclick="jQuery(\'#option-dump\').slideToggle();" style="color:red">' . "click to copy options</div><div id='option-dump' style='display:none;font-family:monospace; font-size:10px; line-height:10px; color:green'><pre>$options_dump<br /><br />$images_dump\n\n\n\n</pre></div>";
	
	if ( $merge and !empty( $changed ) ) {
		echo "Design '<strong>$merge</strong>' has changes:<br /><div style='color:blue;padding-left:15px;font-family:monospace;font-size:10px;line-height:10px'>";
		foreach ( (array) $changed as $key => $val ) {
			echo "\t'$key' => '$val',<br />";
		}
		echo"</div>$markup";
	} else if ( !$merge ) {
		echo $markup;
	}
}


/* add our custom tinymce plugin for clear:both <br /> tag insertion */
function p3_tinymce_plugins( $plugins ) {
	$plugins['p3InsertBreak'] = P3_THEME_URL . '/adminpages/js/tinymce.js';
	return $plugins;
}


/* add our custom tinymce button for clear:both <br /> tag insertion */
function p3_tinymce_buttons( $buttons ) {
	array_push( $buttons, '|', 'p3InsertBreak' );
	return $buttons;
}


/* include development workarea */
function p3_dev_workarea() {
	if ( P3_DEV && intval( phpversion() ) > 4 ) require_once( 'dev.php' );
}


/* warn 1and1 with REGISTER_GLOBALS = On */
if ( is_admin() && ( ini_get( 'register_globals' ) == 1 || ini_get( 'register_globals' ) == 'On' ) ) {
	if ( $pagenow == 'themes.php' && isset( $_GET['page'] ) && nrIsIn( '/kunden/', $_SERVER['DOCUMENT_ROOT'] ) )
		add_action( 'admin_notices', 'p3_register_globals_warn' );
}
function p3_register_globals_warn() {
	p3_error_msg( 'register_globals_on' );
}


/* boolean test, is the file a web-type image */
function is_image( $file ) {
	return preg_match( '/(\.png|\.gif|\.jpg|\.jpeg)/i', substr( trim( $file ), -5 ) );
}


/* return a shortened version of timestamp for semi-unique ID */
function tiny_time_id() {
	return base_convert( time(), 10, 36 );
}


/* dump a php variable into the markup legibly */
function nrVarDump( $var, $varname = '' ) {
	echo "<pre><strong style='text-decoration:underline'>$varname</strong>: ";
	ob_start();
	print_r( $var );
	$dump = ob_get_contents();
	ob_end_clean();
	$dump = urldecode( htmlspecialchars( $dump ) );
	echo $dump;
	echo '</pre><br />';
}


/* set a unique ID for the ProPhoto blog */
function p3_set_uid() {
	if ( !get_option( 'nr_uid' ) ) {
		add_option( 'nr_uid', '', '', 'no' );
		update_option( 'nr_uid', md5( DB_NAME . $table_prefix . get_option( 'home' ) . time() ) . tiny_time_id() );
	}
}


/* compat for pre PHP 5 */
if ( !function_exists( 'mb_strlen' ) ) {
	function mb_strlen( $string ) {
		return strlen( $string );
	}
}
if ( !function_exists( 'mb_substr' ) ) {
	function mb_substr( $string, $start, $length ) {
		return substr( $string, $start, $length );
	}
}

?>