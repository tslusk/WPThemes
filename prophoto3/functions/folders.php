<?php
/* ------------------------------------------------------------- */
/* -- functions & definitions for creation/use of p3 folders --  */
/* ------------------------------------------------------------- */


/** 
 * Returns an array containing the prophoto3 upload directory's 
 * path and url, or an error message. Creates the upload directors, 
 * if necessary, or returns an error if unsuccessful. 
 */
function p3_folders( $attempt_num = 1 ) {

	$uploads_subdirectory = '/p3'; // our custom theme uploads subdirectory
	
	/* set absolute server filesystem path to prophoto3 upload folder */
	// start by reading the upload path from the database
	$upload_path = WP_UPLOAD_PATH;
	
	// if there is no stored upload path in database, create one
	if ( trim( $upload_path ) === '' )
		$upload_path = WP_CONTENT_DIR . '/uploads';
		
	// use the server path to the WordPress installation folder plus the
	// upload path to define an absolute server path to prophoto3 upload folder
	$upload_dir_abs_path = @path_join( ABSPATH, $upload_path ); 
	
	// allow user-set constant UPLOADS to trump other test
	if ( defined( 'UPLOADS' ) ) $upload_dir_abs_path = ABSPATH . UPLOADS;
	
	// Sanitize path for Win32 installs, use this as returned upload basepath
	$upload_dir_abs_path = str_replace('\\', '/', $upload_dir_abs_path);
	$uploads['wp_upload_full_path'] = $upload_dir_abs_path;
	$uploads['p2_upload_path'] = trailingslashit( $upload_dir_abs_path ) . 'p2/';
	
	// append subdirectory
	$upload_dir_abs_path .= $uploads_subdirectory;
	
	/* set absolute web URL path to prophoto3 folder */
	// set the absolute URL path to upload folder by reading the database entry
	$upload_dir_abs_url = get_option( 'upload_url_path' );
	
	// if there is no absolute URL path set, build it by...
	if ( !$upload_dir_abs_url || !nrIsIn( 'http://', $upload_dir_abs_url ) ) {
		
		// taking the relative server path from the WordPress installation root folder
		$upload_dir_rel_path = str_replace( ABSPATH, '', trim( $upload_path ) );
		
		// and joining it with the WordPress URL
		$upload_dir_abs_url = trailingslashit( P3_WPURL ) . $upload_dir_rel_path;
	}  
	
	// allow user-set constant UPLOADS to trump other tests
	if ( defined( 'UPLOADS' ) ) $upload_dir_abs_url = trailingslashit( P3_WPURL ) . UPLOADS;
		
	// append subdirectory
	$upload_dir_abs_url .= $uploads_subdirectory;
	
	// sanitize path for windows installs
	$upload_dir_abs_url = str_replace( '\\', '/', $upload_dir_abs_url );
	
	// fill in the info we now know
	$uploads['path'] = $upload_dir_abs_path;
	$uploads['url'] = $upload_dir_abs_url;
	$uploads['subdir'] = $uploads_subdirectory;
	$uploads['upload_error'] = FALSE;
	$sub_subdirs = array( 'images', 'gallery', 'static', 'designs', 'music', 'backup' );
	
	/* paths determined, now create folders (if necessary) and return paths/errors */
	
	// couldn't find/create main p3 folder
	if ( !wp_mkdir_p( $upload_dir_abs_path ) ) {
		$uploads['upload_error'] = p3_get_msg( 'create_dir_error', $upload_dir_abs_path );
	
	// main p3 folder exists/was created, create sub-dirs
	} else {
		foreach ( $sub_subdirs as $subdir ) {
			if ( !wp_mkdir_p( $upload_dir_abs_path . '/' . $subdir ) ) {
				$uploads['upload_error'] = p3_get_msg( 'create_dir_error', $upload_dir_abs_path . '/' . $subdir  );
				break;
			} else {
				$uploads[$subdir.'dir'] = '/' . $subdir;
			}
		}
	}

	// try to fix errors
	if ( $uploads['upload_error'] && $attempt_num < 3 ) return p3_repair_folder_permissions( $uploads, $attempt_num );
	
	return apply_filters( 'p3_folders', $uploads );
}


/* try to repair uploads folder permissions */
function p3_repair_folder_permissions( $uploads, $attempt_num ) {
	$attempt_num++;
	@chmod( WP_CONTENT_DIR, 0755 );
	@chmod( $uploads['wp_upload_full_path'], 0777 );
	@chmod( $uploads['path'], 0777 );
	return p3_folders( $attempt_num );
}


/* p3 folders info - defined here to prevent repeated expensive calls to p3_folders() */
extract( p3_folders() );
define( 'P2_UPLOAD_PATH',      $p2_upload_path );
define( 'P3_FOLDER_PATH',      $path );
define( 'P3_FOLDER_URL',       $url );
define( 'P3_FOLDER_DIR',       $subdir );
define( 'P3_IMAGES_DIR',       $imagesdir );
define( 'P3_GALLERY_DIR',      $gallerydir );
define( 'P3_MUSIC_DIR',        $musicdir );
define( 'P3_DESIGNS_DIR',      $designsdir );
define( 'P3_STATIC_DIR',       $staticdir );
define( 'P3_BACKUP_DIR',       $backupdir );
define( 'P3_FOLDERS_ERROR',    $upload_error );
define( 'P3_IMAGES_PATH',      trailingslashit( P3_FOLDER_PATH . P3_IMAGES_DIR ) );
define( 'WP_UPLOAD_FULL_PATH', $wp_upload_full_path );
define( 'WP_UPLOAD_FULL_URL',  trailingslashit( P3_WPURL ) . WP_UPLOAD_PATH );


/* debug */
if ( P3_TECH && $_GET['debug_p3_folders'] ) p3_debug_array( p3_folders() );

?>