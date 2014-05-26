<?php
/* --------------------------------------------------------------------- */
/* -- library of functions related to default and custom theme images -- */
/* --------------------------------------------------------------------- */


/* tell WordPress we're going to use post-thumbnails */
add_theme_support( 'post-thumbnails' );


/** 
 * Returns the filesystem path of a theme image. This can be:
 * a) custom image in custom upload folder - wp-content/uploads/prophoto3
 * b) semi-custom default layout image in prophoto3/images folder
 * c) default image in prophoto3/images folder 
 */
function p3_imagepath($shortname, $echo = TRUE) {
	global $p3,$p3_defaults;

	// first set the path to a custom image, to test for it first
	$custom_image_path =  P3_IMAGES_PATH . $p3[$p3['active_design']]['images'][$shortname];
	
	// if there is a record of a non-default image, and file exists in custom upload location
	if ( $p3[$p3['active_design']]['images'][$shortname] && @file_exists( $custom_image_path ) ) 
		$imgpath = $custom_image_path; // set custom image path
	
	// if non-default image and file exists in theme images folder	
	else if ( @file_exists( TEMPLATEPATH . '/images/' . $p3[$p3['active_design']]['images'][$shortname] ) && $p3[$p3['active_design']]['images'][$shortname] )
		$imgpath = TEMPLATEPATH . '/images/' . $p3[$p3['active_design']]['images'][$shortname]; // set layout image path
		
	// if we get here, it means that this must be a default theme image
	else $imgpath = TEMPLATEPATH . '/images/' . $p3_defaults['images'][$shortname];

	// scrub path for Win32 slashes
	$imgpath = str_replace( '\\','/',$imgpath );

	if ( $echo ) echo $imgpath;
	return $imgpath;
}


/** 
 *  Returns the URL of a theme image. This can be:
 *  a) custom image in custom upload folder - wp-content/uploads/prophoto3
 *  b) semi-custom default layout image in prophoto3/images folder
 *  c) default image in prophoto3/images folder 
 *  d) '#' if error 
 */
function p3_imageurl( $shortname, $echo = TRUE ) {
	global $p3, $p3_defaults;
	
	// define filesystem absolute path to image's directory
	$img_path       = p3_imagepath( $shortname, FALSE );
	$img_actual_dir = trailingslashit( dirname( $img_path ) );

	// define prophoto theme image directory
	$p3_theme_images_dir = str_replace('\\', '/', TEMPLATEPATH . '/images/');
	
	// file is in custom prophoto3 uploads folder means custom uploaded image
	if ( $img_actual_dir == P3_IMAGES_PATH ) 
		$img_url = P3_FOLDER_URL . P3_IMAGES_DIR . '/' . $p3[$p3['active_design']]['images'][$shortname];
		
	// file in theme "images" dir means layout or default image
	elseif ( $img_actual_dir == $p3_theme_images_dir ) {

		// layout image - image shipped with one of the non-default built-in layouts
		if ( $p3[$p3['active_design']]['images'][$shortname] )
			$img_url = P3_THEME_URL . '/images/' . $p3[$p3['active_design']]['images'][$shortname];
		// default layout image
		else $img_url = P3_THEME_URL . '/images/' . $p3_defaults['images'][$shortname];
		
	// Reached this point? This means the image is missing.
	} else {
		$img_url = '#';
	}
	

	// optionally echo before returning image URL
	if ( $echo ) echo $img_url;
	return $img_url;
}


/**
 * Return image information in array or FALSE if error.
 * Array: 0=>width, 1=>height, 2=>type, 3=>height="yyy" width="xxx"
 * Type value: 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 
 *     7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 
 *     10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF,  15 = WBMP, 16 = XBM
 */
function p3_image_infos( $path ) {
	$size = @getimagesize( $path );
	return $size;
}


/* wrapper function to echo and return width of an image */
function p3_imagewidth( $shortname, $echo = TRUE ) {
	return p3_img_data( $shortname, $echo, 0 );
}


/* wrapper function to echo and return height of an image */
function p3_imageheight( $shortname, $echo = TRUE ) {
	return p3_img_data( $shortname, $echo, 1 );
}


/* wrapper function to return HTML attributes of an image (width="444px" height="123px") */
function p3_imageHTML( $shortname, $echo = TRUE) {
	return p3_img_data( $shortname, $echo, 3 );
}


/* subroutine for echoing and returning image data */
function p3_img_data( $shortname, $echo, $index ) {
	global $p3;
	$path = p3_imagepath( $shortname, FALSE );
	$size = (array) p3_image_infos( $path );
	if ( $echo ) echo $size[$index];
	return $size[$index];
}


/* return size (in kb) of the image */
function p3_img_filesize($shortname) {
	global $p3;
	$path = p3_imagepath( $shortname, FALSE );
	$size = number_format( ( @filesize( $path ) ) / 1024 ); // 1024 bits in kb
	if ( $echo ) echo $size;
	return $size;
}


/* test if an optional image exists */
function p3_image_exists( $name ) {
	if ( strpos( p3_imageurl( $name, NO_ECHO ), 'nodefaultimage.gif' ) === FALSE ) {
		return TRUE;
	} 
	return FALSE;
}


/* test if an optiona misc uploaded file exists */
function p3_file_exists( $name ) {
	if ( p3_imageurl( $name, NO_ECHO ) == '#' ) return FALSE;
	return TRUE;
}


/* look for and return WordPress thumbnail img */
function p3_find_thumbnail( $img_url, $largest = TRUE ) {	
	// don't look for non-local, non-WordPress uploaded image thumbs
	if ( strpos( $img_url, WP_UPLOAD_FULL_URL ) === FALSE ) return FALSE;

	// get array of image paths for available thumbnails
	global $root_no_ext;
	$img_name    = basename( $img_url );
	$img_root    = preg_replace( '/-[0-9]*x[0-9]*\./', '.', $img_name );
	$root_no_ext = preg_replace( '/\..*/', '', $img_root );
	$img_path    = str_replace( WP_UPLOAD_FULL_URL, WP_UPLOAD_FULL_PATH, $img_url );
	$img_folder  = str_replace( $img_name, '', $img_path );
	$thumbs      = @glob( $img_folder . $root_no_ext . '-[0-9]*' );
	
	// narrow the glob with a bit of regex
	$regex_root = preg_quote( $root_no_ext );
	foreach ( (array) $thumbs as $key => $thumb ) {
		if ( !preg_match( "/$regex_root-[0-9]+x[0-9]+\./", $thumb ) ) unset( $thumbs[$key] );
	}

	if ( empty( $thumbs ) ) return FALSE;
	usort( $thumbs, '_thumb_sort' );
	
	// skip wptouch plugin super small thumbs
	$smallest = array_shift( $thumbs );
	if ( !nrIsIn( '-50x50.', $smallest ) ) $thumb_path = array_unshift( $thumbs, $smallest );
	
	// return thumb url of requested size
	if ( $largest ) {
		$thumb_path = ( $thumbs[1] ) ? $thumbs[1] : $thumbs[0];
	} else {
		$thumb_path = array_shift( $thumbs );
	}

	$thumb_url  = str_replace( WP_UPLOAD_FULL_PATH, WP_UPLOAD_FULL_URL, $thumb_path );
	return $thumb_url;
}


/* subroutine to sort array of thumbnails by extracting size from filename */
function _thumb_sort( $a, $b ) {
	$a_size = _thumb_get_size( $a );
	$b_size = _thumb_get_size( $b );
	if ( $a_size == $b_size ) return 0;
	return ( $a_size < $b_size ) ? -1 : 1;
}


/* subroutine to get total thumbnail dimensions from filename */
function _thumb_get_size( $path ) {
	$parts = explode( $GLOBALS['root_no_ext'] . '-', $path );
	if ( !is_array( $parts ) || !$parts[1] ) return 0;
	list( $width, $height ) = explode( 'x', preg_replace( '/\..*/', '', $parts[1] ) );
	return intval( $width ) + intval( $height );
}


/* returns array of (width,height) recommendations form images, if reco's available */
function p3_imgsize_recommendations($shortname) {
	global $p3;
	
	// if we have recommendations
	if ( $p3['recommendations'][$shortname] ) {
		$reco_width  = $p3['recommendations'][$shortname]['width'];
		$reco_height = $p3['recommendations'][$shortname]['height'];
	
	// or dependent from logo because it's a masthead_image image	
	} else if ( strpos( $shortname, 'masthead_image' ) !== FALSE ) { 
		$reco_width  = p3_masthead_image_recommendation( 'width' );
		$reco_height = p3_masthead_image_recommendation( 'height' );
		
	// no recommendations	
	} else return FALSE;

	return array( $reco_width, $reco_height );
}


/* boolean returns if an image complies to recommendations */
function p3_img_follows_recommendations($shortname) {
	global $p3;
	
	// get recommended dimensions
	list( $reco_width, $reco_height ) = p3_imgsize_recommendations( $shortname );
	
	// get actual file dimensions
	$actual_width = p3_imagewidth( $shortname, NO_ECHO );
	$actual_height = p3_imageheight( $shortname, NO_ECHO );
	
	// start by assuming width and height are incorrect
	$width_follows = $height_follows = FALSE;
	
	// test the width, true if correct or no recommendation
	if ( $reco_width ) {
		if ( $actual_width == $reco_width ) $width_follows = TRUE;
	} else {
		$width_follows = TRUE;
	}
	
	// test the height, true if correct or no recommendation
	if ( $reco_height ) {
		if ( $actual_height == $reco_height ) $height_follows = TRUE;
	} else {
		$height_follows = TRUE;
	}
	
	if ( $width_follows AND $height_follows ) return TRUE;
	else return FALSE;
}


/* wrapper function to get masthead_image recommended width or height from compute function */
function p3_masthead_image_recommendation( $which_dimension ) {
	
	// get both computed recommended dimensions into an array
	$both_dimensions = p3_masthead_image_recommendation_compute();
	
	// return the requested dimension
	return $both_dimensions[ $which_dimension ];
}


/* return width (and height if needed) used to compute recommended width & height of an image */
function p3_masthead_image_recommendation_compute() {	
	$logo_width  = p3_imagewidth( 'logo', NO_ECHO );
	$logo_height = p3_imageheight( 'logo', NO_ECHO );

	switch ( p3_get_option( 'headerlayout' ) ) {
		case 'logomasthead_nav':
		case 'mastheadlogo_nav':
			$reco_width  = p3_get_option( 'blog_width' ) - $logo_width;
			$reco_height = $logo_height;
			break;
		case 'mastlogohead_nav':
			$reco_width  = p3_get_option( 'blog_width' );
			$reco_height = $logo_height;
			break;
		default:
			$reco_width  = p3_get_option( 'blog_width' );
			$reco_height = p3_imageheight( 'masthead_image1', NO_ECHO );
			break;
	}
	
	return array( 'width'=>$reco_width, 'height'=>$reco_height );
}


/* return file type of uploaded misc. file based on extension */
function p3_get_filetype( $extension ) {
	// set up filetype/extension relationships in array
	$file_types = array(
		'favicon icon' => array( 'ico' ),
		'swf' => array( 'key', 'odp', 'swf' ),
		'audio' => array( 'aac', 'ac3', 'aif', 'aiff', 'mp1', 'mp3', 'mp3', 'm3a', 'm4a', 'm4b', 'ogg', 'ram', 'wav', 'wma' ),
		'video' => array( 'asf', 'avi', 'divx', 'dv', 'mov', 'mpg', 'mpeg', 'mp4', 'mpv', 'ogm', 'qt', 'rm', 'vob', 'wmv' ),
		'document' => array( 'doc', 'pages', 'odt', 'rtf', 'ppt' ),
		'pdf' => array( 'pdf' ),
		'spreadsheet' => array( 'xls', 'numbers', 'ods' ),
		'text' => array( 'txt' ),
		'archive' => array( 'tar', 'bz2', 'gz', 'cab', 'dmg', 'rar', 'sea', 'sit', 'sqx', 'zip' ),
	);
	// loop through the filetypes/extensions looking for a match
	foreach ( $file_types as $type => $extension_array ) {
		if ( in_array( $extension, $extension_array) ) return $type; // return matched type
	}
	return 'misc'; // or 'misc' if no match
}

/* Reorder masthead images */
function p3_reorder_masthead( $order ) {
	global $p3, $p3_defaults;
	parse_str( $order );
	
	$neworder = array();
	foreach( $p3_masthead_order as $i => $image ) {
		$i++;
		$neworder['masthead_image'.$i] = ( $p3[$p3['active_design']]['images']['masthead_'.$image] 
			? $p3[$p3['active_design']]['images']['masthead_'.$image] 
			: $p3_defaults['images']['masthead_'.$image] );
	}
	
	$p3[$p3['active_design']]['images'] = array_merge( $p3[$p3['active_design']]['images'], $neworder );
}


/* server-side regex image processing filter */
add_filter( 'the_content', 'p3_parse_images' );
function p3_parse_images( $content ) {
	if ( is_feed() ) return $content;
	$modified_content = preg_replace_callback( 
		"/(<a[^>]+href=" . MATCH_QUOTED . "[^>]*>)?(?:[ \t\n]+)?(<img[^>]+src=" . MATCH_QUOTED . "[^>]*>)(?:[ \t\n]+)?(<\/a>)?/i", 	
		'p3_parse_images_callback', 
		$content );
	return $modified_content;
}


/* image processing regex callback */
function p3_parse_images_callback( $matches ) {
	//p3_debug_array( $matches );
	list( $full, $a_open, $a_href, $img, $img_src, $a_close ) = $matches;
	
	// downsize image if necessary
	extract( p3_content_img_size( $img, $img_src ) );
	if ( $downsized = p3_downsize_img( $img, $width, $height, $no_img_attr ) ) {
		list( $img, $width, $height ) = $downsized;
	}
	
	// non-protected images
	if ( p3_test( 'image_protection', 'none' ) || strpos( $img, 'exclude' ) !== FALSE || $GLOBALS['filtering_sidebar_content'] === TRUE )
		return $a_open . $img . $a_close;
	
	// disable click to src
	$href_no_ext = substr( $a_href, 0, strlen( $a_href ) - 4 );
	if ( !p3_test( 'image_protection', 'right_click' ) && $a_open && $a_close && ( 
			( $a_href == $img_src ) || 
			( nrIsIn( 'attachment_id=', $a_href ) ) ||
			( preg_match( '/(\.jpg|\.jpeg|\.gif|\.png)/i', $a_href ) && nrIsIn( $href_no_ext, $img_src ) )
		) ) {
		$a_open = $a_close = '';
	}
		
	// blank img overlay
	if ( p3_test( 'image_protection', 'replace' ) || p3_test( 'image_protection', 'watermark' ) ) {
		
		if ( ( $width + $height ) > 399 && !nrIsIn( 'shrink-to-thumbnail', $img ) ) {

			// transfer alignment info
			if      ( strpos( $img, 'aligncenter' ) !== FALSE ) $align_class = 'aligncenter';
			else if ( strpos( $img, 'alignleft' )   !== FALSE ) $align_class = 'alignleft';
			else if ( strpos( $img, 'alignright' )  !== FALSE ) $align_class = 'alignright';
			else if ( strpos( $img, 'alignnone' )   !== FALSE ) $align_class = 'alignnone';
			else $align_class = 'aligncenter no-orig-alignclass';

			// add new markup
			$blank_img = P3_THEME_URL . '/images/blank.gif';
			$wrap_open  = "
			<div class='p3-img-protect p3-img-protect-{$align_class}' style='width:{$width}px;'>
				<img class='p3-overlay' style='width:{$width}px;height:{$height}px;' src='$blank_img' />";
			$wrap_close = '</div>';
		} 
	}
	return $a_open . $wrap_open . $img . $wrap_close . $a_close;
}


/* return height and width of an image by extracting attributes, or testing with PHP */
function p3_content_img_size( $img, $img_src ) {
	// width and height in IMG tag
	if ( preg_match( "/width="  . MATCH_QUOTED . "/i", $img, $width_match ) && 
	     preg_match( "/height=" . MATCH_QUOTED . "/i", $img, $height_match ) ) {
			$height = $height_match[1];
			$width = $width_match[1];
			
	// not dims in IMG tag, let PHP hunt for it
	} else if ( nrIsIn( WP_UPLOAD_FULL_URL, $img_src ) ) {
		$img_path = str_replace( WP_UPLOAD_FULL_URL, WP_UPLOAD_FULL_PATH, $img_src );
		$dims = @getimagesize( $img_path );
		$width = $dims[0];
		$height = $dims[1];
		$no_img_attr = TRUE;
	
	// remote img
	} else {
		$height = $width = FALSE;
	}
	return array( 'height' => $height, 'width' => $width, 'no_img_attr' => $no_img_attr );
}


/* downsize and image by altering img tag attributes */
function p3_downsize_img( $img, $width, $height, $no_img_attr = FALSE ) {
	if ( !$width && !$height ) return FALSE;
	
	// set max width for downsizing
	if ( $GLOBALS['filtering_sidebar_content'] == TRUE ) {
		if ( !defined( 'SIDEBAR_MAX_IMG_WIDTH' ) ) 
			define( 'SIDEBAR_MAX_IMG_WIDTH', p3_image_max_widths( 'sidebar_max_image_width' ) );
		$max_width = SIDEBAR_MAX_IMG_WIDTH;		
	} else {
		if ( !defined( 'CONTENT_MAX_IMG_WIDTH' ) ) {
			$context = ( p3_doing_sidebar() ) ? 'with_sidebar_max_image_width' : 'max_image_width';
			define( 'CONTENT_MAX_IMG_WIDTH', p3_image_max_widths( $context ) );
		}
		$max_width = CONTENT_MAX_IMG_WIDTH;
	}
	
	if ( ( $width <= $max_width ) || $width == 0 || $height == 0 ) return FALSE;
	if ( $no_img_attr ) $img = preg_replace( '/<img /i', "<img width=\"$width\" height=\"$height\" ", $img );
	
	$height = intval( $max_width / ( $width / $height ) );
	$width = $max_width;
	
	// change width/height attributes
	$img = preg_replace( "/width="  . MATCH_QUOTED . "/i", 'width="' . $width . '"', $img );
	$img = preg_replace( "/height=" . MATCH_QUOTED . "/i", 'height="' . $height . '"', $img );
	
	// add a class to show we downsized
	if ( preg_match( "/class="  . MATCH_QUOTED . "/i", $img, $class_match ) ) {
		$classes = explode( ' ', $class_match[1] );
		$classes[] = 'p3-downsized';
		$img = preg_replace( "/class="  . MATCH_QUOTED . "/i", 'class="' . implode( ' ', $classes ) . '"', $img );
	} else {
		$img = preg_replace( "/<img /i", '<img class="p3-downsized" ', $img );
	}
	
	return array( $img, $width, $height );
}


?>