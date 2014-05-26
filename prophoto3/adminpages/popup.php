<?php
/* -------------------------- */
/* -- iframed popup window -- */
/* -------------------------- */


// upload or design-page iframe?
if ( $_GET['do'] == 'upload' || $_GET['do'] == 'reset' ) {
	$functions  = 'upload';
	$is_upload  = TRUE;
	$is_designs = FALSE;
} else {
	$functions  = 'designs';
	$is_upload  = FALSE;
	$is_designs = TRUE;
}

// security & permissions handled by current_user_can()
function auth_redirect() { return true; } 

// load WordPress environment, plus admin bootstrap
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-admin/admin.php' );

// load correct function library
require_once( dirname( dirname( __FILE__ ) ) . "/functions/{$functions}.php" );

// scripts and styles
wp_enqueue_script( 'jquery' );
if ( $is_upload ) {
	wp_enqueue_script( 'swfupload' );
	wp_enqueue_script( 'swfupload-degrade' );
	wp_enqueue_script( 'swfupload-queue' );
	wp_enqueue_script( 'swfupload-handlers' );
	wp_enqueue_script( 'utils' );
} else {
	wp_enqueue_style( 'designs-popup', P3_THEME_URL . '/adminpages/css/designs-popup.css' );
}

// send header
@header( 'Content-Type: ' . get_option('html_type') . '; charset=' . get_option( 'blog_charset' ) );


/* upload page content */
if ( $is_upload ) {

	// double-check permissions
	if ( !current_user_can( 'upload_files' ) ) wp_die( 'You do not have permission to upload files.' );
	
	// no flash uploader: it wont allow us to define our own upload dir
	add_filter( 'flash_uploader', create_function( '', 'return false;' ) ); 
	add_filter( 'media_upload_tabs', create_function( '', 'return false;' ) );

	$action = attribute_escape( $_GET['do'] );
	if ( attribute_escape( $_GET['misc'] ) == 'true' ) {
		$action .= '_misc';
	}
	
	// main page content
	wp_iframe( 'p3_' . $action . '_form' ); 
	
	// NOTE: additional form fields added by p3_insert_upload_fields()
}


/* designs page content */
if ( $is_designs ) {
	
	// doublel-check permissions
	if ( !current_user_can( 'level_1' ) ) wp_die( 'Administrator access only' );
	
	// main page content
	wp_iframe( 'p3_' . attribute_escape( $_GET['do'] ) );
}

?>