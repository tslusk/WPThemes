<?php
/* ---------------------------------------------------------- */
/* -- library of functions used for uploading theme images -- */
/* ---------------------------------------------------------- */

// Shortcircuit the default upload dir
add_filter( 'upload_dir', 'p3_custom_img_upload_dir' ); 
// Add CSS and JS to the iframed upload form
add_action( 'admin_head_p3_upload_form', 'p3_upload_form_head' ); 
// Add CSS and JS to the iframed reset form
add_action( 'admin_head_p3_reset_form', 'p3_reset_form_head' ); 
// Add styles to the iframed upload form
add_action( 'admin_head_p3_upload_misc_form', 'p3_upload_form_head' ); 
// Add styles to the iframed reset form
add_action('admin_head_p3_reset_misc_form', 'p3_reset_form_head' ); 


/* filters the WordPress upload dir based on custom upload settings */
function p3_custom_img_upload_dir() {
	$custom_img_upload_dir['path']   = untrailingslashit( P3_IMAGES_PATH );
	$custom_img_upload_dir['url']    = P3_FOLDER_URL  . P3_IMAGES_DIR;
	$custom_img_upload_dir['subdir'] = P3_IMAGES_DIR;
	$custom_img_upload_dir['error']  = P3_FOLDERS_ERROR;
	return $custom_img_upload_dir;
}


/* prints the upload form. Triggered by wp_iframe('p3_upload_form') in upload-form.php */
function p3_upload_form() {
	media_upload_type_form( 'image_p3' ); // image_p3 = type
}


/* prints the misc upload form. Triggered by wp_iframe('p3_upload_form') in upload-form.php */
function p3_upload_misc_form() {
	media_upload_type_form( 'misc_p3' ); // misc_p3 = type
}


/* Add stuff into the upload form's <head> */
function p3_upload_form_head( $msg = '', $misc = '' ) {
	echo '<link href="' . P3_THEME_URL . '/adminpages/css/popup-upload.css" rel="stylesheet" type="text/css" />' . "\n";
	echo '<script type="text/javascript" src="' . P3_THEME_URL . '/adminpages/js/popup-upload.js"></script>' . "\n";
	
	// set global JS variable for misc (non-image) files
	if ( is_bool( $misc ) ) {
		$is_misc = ( $misc === TRUE ) ? 'true' : 'false' ;
		echo '<script type="text/javascript">var is_misc = ' . $is_misc . ';</script>';
	}
	
	// also print any passed message
	if ( $msg ) echo $msg;
}


/* print the img reset form. Triggered by wp_iframe('p3_reset_form') in reset-form.php */
function p3_reset_form() {
	include( dirname( dirname( __FILE__ ) ) . '/adminpages/popup-reset.php' );
}


/* print the misc-file reset form. Triggered by wp_iframe('p3_reset_form') in reset-form.php */
function p3_reset_misc_form() {
	include( dirname( dirname( __FILE__ ) ) . '/adminpages/popup-reset.php' );
}


/* Add stuff into the upload form's <head> */
function p3_reset_form_head() {
	echo '<link href="' . P3_THEME_URL . '/adminpages/css/common.css" rel="stylesheet" type="text/css" />' . "\n";
	echo '<link href="' . P3_THEME_URL . '/adminpages/css/popup-reset.css" rel="stylesheet" type="text/css" />' . "\n";
	echo '<script type="text/javascript" src="' . P3_THEME_URL . '/adminpages/js/popup-reset.js"></script>' . "\n";
}


/*What to do when an image has been uploaded. 
Triggered by add_action('media_upload_image_p3') 
(the do_action is in wp-admin/media-upload.php)
$misc: true for any file, false for image only (default) */
function p3_image_uploaded( $misc = FALSE ) {
	global $p3;
	if ( !isset( $_POST['html-upload'] ) || empty( $_FILES ) ) return;

	/** debug **/ if ( 0 ) {
	ob_start();echo "<pre>POST:\n ";print_r($_POST);
	echo "\n\nFILES:\n";print_r($_FILES);
	echo "\n\nREQUEST:\n";print_r($_REQUEST);
	echo "\n\n\$_SERVER['REQUEST_URI']:\n" . $_SERVER['REQUEST_URI'];echo "</pre>";
	$debug = ob_get_contents();ob_end_clean();die($debug);}
		
	// check for problems first
	switch( $_FILES['async-upload']['error'] ) {
		case 1:
		case 2:
			die( sprintf( 'File is too big. Please <a href="%s">try again</a>', $_POST['formurl'] ) );
			break;
		case 3:
			die( sprintf( 'File partially uploaded. Please <a href="%s">try again</a>', $_POST['formurl'] ) );
			break;
		case 4:
			die( sprintf( 'No file selected. Please <a href="%s">try again</a>', $_POST['formurl'] ) );
			break;
		case 6:
			die( 'Critical: Missing a temporary folder. Your webserver will not accept any upload!' );
			break;
		case 7:
			die( 'Failed to write file to disk while uploading. Your webserver will not accept any upload!' );
			break;
	}
	
	// no errors, now check filesize
	if ( !$misc && $_FILES['async-upload']['size'] > 2084000) {
		die( sprintf( 'File is too big. Please <a href="%s">try again</a>', $_POST['formurl'] ) );
	}

	// So far, so good, we have a good-looking file. upload it and return info
	$upload = p3_media_handle_upload( 'async-upload', $misc );

	// oops, we have errors, handle them with helpful info if possible
	if ( $upload['error'] ) {
		
		// missing "uploads" or "p3" folder
		if ( strpos( $upload['error'], P3_FOLDER_PATH . '.' ) !== FALSE ) {
			return p3_folder_errors();
		
		// missing "images" sub-folder
		} else {
			return p3_error_msg( 'cant_create_folder', NO_UPLOADS_FOLDER_TUT );
		}
	}
	
	// design zip upload
	if ( $_REQUEST['shortname'] == 'design_upload' ) {
		require_once( 'designs.php' );
		return p3_import_uploaded_zip( $upload['file'] );
	} 
	
	// Save image name in DB
	$p3[$p3['active_design']]['images'][$_POST['shortname']] = basename( $upload['file'] );
	p3_store_options();
	
	// Not in a frame? send back to main page
	if ( $_POST['iframed'] == 'false') {
		$msg .= (' <a href="' . p3_get_admin_url( 'background' ) . '">Return</a></p>' );
	
	} else {
		// In a frame: update background page via JS and add close link
		$shortname = $_POST['shortname'];
		$width  = p3_imagewidth( $shortname, NO_ECHO );
		$height = p3_imageheight( $shortname, NO_ECHO );
		$size = number_format( $_FILES['async-upload']['size'] / 1024 );
		$url = $upload['url'];
		$msg .= "
		<script type='text/javascript'>
			p3_sendtopage('$url', '$shortname', '$width', '$height', '$size' );
			top.tb_remove()
		</script>";
	}
	
	// Forcing content-type because text/xml makes IE7 go moo
	@header( 'Content-Type: text/html' );
	
	// re-print he iframe
	wp_iframe( 'p3_upload_form_head', $msg, $misc );
}



/*  upload the file and return file info in array like:
    ---
	array (  'file' => 'E:\\home\\ozh\\planetozh.com\\blog/wp-content/uploads/p3/image.jpg',
	  	     'url'  => 'http://192.168.0.1/planetozh.com/blog/wp-content/uploads/p3/image.jpg',
	  		 'type' => 'image/jpeg' )
	OR 
	array( 'error' => 'no file uploaded' )
*/
function p3_media_handle_upload( $file_id, $misc = FALSE ) {
	$overrides = array(
		'test_form' => FALSE,
		'unique_filename_callback' => 'p3_rename_uploaded_image',
	);
	
	if ( !$misc ) { // for images, test what is sent. For misc stuff, we don't care
		$overrides['test']  = TRUE; 
		$overrides['mimes'] = array( 'jpg|jpeg|jpe' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png' );
	}
	
	// disable capability of "unfiltered_upload" to make sure we'll comply to mime types specified here
	add_filter('user_has_cap', create_function('$in', '$in["unfiltered_upload"] = false; return $in;'));
	
	// send the file and custom info off to the WordPress internal uploading function, capture result
	$file = wp_handle_upload( $_FILES[$file_id], $overrides );
	
	// return array of file info or error
	return $file;
}


/* Rename the uploaded image to match the given 'shortname'. Called by callback in p3_media_handle_upload() */
function p3_rename_uploaded_image( $dir, $name ) {
	global $p3;
	$shortname = $_POST['shortname'];
	$original  = $_FILES['async-upload']['name'];

	// missing info, print debug & error message
	if ( !$shortname || !$original ) {
		die( sprintf( 'Missing upload information, please <a href="%s">try again</a>.', $_POST['formurl'] ) );
	}
	
	$path_info = pathinfo( $original );
    $ext = $path_info['extension'];

	//return $shortname_234234532.$ext format
	return $shortname . '_' . time() . '.' . $ext;
}


/* get custom P3 fields inside of upload form */
add_action( 'pre-upload-ui', 'p3_insert_upload_fields' );
function p3_insert_upload_fields() {
	if ( !$GLOBALS['pagenow'] == 'popup.php' ) return;
	
	// add additional fields
	echo '<input type="hidden" name="shortname" value="' . $_GET['p3_image'] . '" />';
	echo '<input type="hidden" name="formurl" value="' . $_SERVER['REQUEST_URI'] . '" />';
	
	// Are we in a frame ? A 'standalone' (not iframed) page has an URL with TB_iframe=true in the GET
	parse_str( $_SERVER['REQUEST_URI'] );
	$iframe = ( $TB_iframe ) ? 'false' : 'true' ;
	echo '<input type="hidden" name="iframed" value="' . $iframe . '" />';
}

?>