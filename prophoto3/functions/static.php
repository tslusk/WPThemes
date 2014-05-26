<?php
/* ---------------------------------------------------------------- */
/* -- library of functions related to the generated static files -- */
/* ---------------------------------------------------------------- */

/* return array of static and dynamic paths to p3 custom files */
function p3_get_file_paths() {
	$paths['static_dir']  = P3_FOLDER_PATH . P3_STATIC_DIR;
	$paths['dynamic_dir'] = str_replace( '\\', '/', dirname( dirname( __FILE__ ) ) );
	return $paths;
}


/* Generate static files from dynamic ones. Triggered on every p3_store_options(); */
function p3_generate_static_files() {
	global $p3_static_files, $p3_prevent_options_store;
	if ( $p3_prevent_options_store ) return;

	// get static and dynamic path info
	extract( $paths = p3_get_file_paths() );

	// loop through each static file to be generated
	foreach ( $p3_static_files as $key => $file_name_array ) {
		
		// Get dynamic content into a variable
		ob_start();
		@include( $dynamic_dir . '/' . $file_name_array['dynamic'] );
		$static_file_contents = apply_filters( 'p3_static_file_content_' . $key,  ob_get_contents() );
		ob_end_clean();
		
		$static_filename = $static_dir . '/' . $file_name_array['static'];
	
		// write static file, collect result in array for return
		$return[ $file_name_array['static'] ] = p3_writefile( $static_filename, $static_file_contents );

	}
	return $return;
}


/* delete all static files. Called by "reset all" action on layouts page */
function p3_delete_static() {
	global $p3_static_files;
	
	// get static and dynamic path info
	extract( p3_get_file_paths() );
	
	// loop through each static file to be deleted
	foreach ( $p3_static_files as $key => $file_name_array ) {
		
		// setup static file name being operated on
		$static_filename = $static_dir . '/' . $file_name_array['static'];
		
		// delete file and collect status info for return
		if ( file_exists( $static_filename ) )
			$return[ $file_name_array['static'] ] = @unlink( $static_filename );
	}

	return $return;
}


/* write content to a file */
function p3_writefile( $filename, $content ) {
	// open file or return fopen error
	if ( ! $file_resource = @fopen( $filename, 'w+' ) ) {
		return 'could not fopen';
	}
	// write to file or return fwrite error
	if ( @fwrite( $file_resource, $content ) === FALSE ) {
		return 'could not fwrite';
	}
	fclose( $file_resource );
	// got here?  return success
	return TRUE;
}


/* get URL of a static file, or dynamic file resource if static missing */
function p3_static_file_url( $filename, $echo = TRUE ) {
	global $p3_static_files;
	if ( file_exists( P3_FOLDER_PATH . P3_STATIC_DIR . '/' . $p3_static_files[$filename]['static'] ) ) {
		// return static file
		$url = P3_FOLDER_URL . P3_STATIC_DIR . '/' . $p3_static_files[$filename]['static'];
	} else {
		// Ooops, problem, static file doesnt exist? return dynamic file
		$url = P3_THEME_URL . '/'. $p3_static_files[$filename]['dynamic'];
	}
	
	if ( $echo ) echo $url;
	return $url;
}

?>