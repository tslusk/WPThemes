<?php
/* --------------------------------------------------- */
/* -- library of functions used for flash galleries -- */
/* --------------------------------------------------- */


/* get our custom css into tinymce, to show the placeholders, always hidden elsewhere */
add_filter( 'mce_css', 'p3_mce_css' );
function p3_mce_css( $css ) {
	return $css . ',' . P3_THEME_URL . '/adminpages/css/tinymce.css';
}


/* add our p3 gallery tabs to the media upload iframe */
add_filter( 'media_upload_tabs', 'p3_flash_gallery_tab' );
function p3_flash_gallery_tab( $tabs ) {
	$newtabs = array( 'p3_lightbox' => 'P3 Lightbox Gallery', 'p3_gallery' => 'P3 Flash Gallery' );
    return array_merge( $tabs, $newtabs );
}


/* media upload flash gallery tab - show form or process form submission */
add_action( 'media_upload_p3_gallery', 'media_upload_p3_gallery' );
function media_upload_p3_gallery() {
	
	// no POST data, show form
	if ( !isset( $_POST['insert'] ) && !isset( $_POST['update'] ) ) {
		return wp_iframe( 'media_upload_p3_gallery_form' );
	}

	// get object dimensions
	$object_dimensions = p3_flash_gallery_dimensions( $_POST['post_id'] );
	
	// store gallery instance information in post meta
	if ( $_POST['mp3'] == 'add' ) $_POST['mp3'] = '';
	$gallery_data = array(
		'title'      => urlencode( $_POST['title'] ),
		'subtitle'   => urlencode( $_POST['subtitle'] ),
		'proofurl'   => urlencode( $_POST['proofurl'] ),
		'mp3'        => urlencode( $_POST['mp3'] ),
		'auto_start' => urlencode( $_POST['auto_start'] ),
		'speed'      => urlencode( $_POST['slideshow_speed'] ),
	);
	delete_post_meta( $_POST['post_id'], 'p3_flash_gal_info' );
	update_post_meta( $_POST['post_id'], 'p3_flash_gal_info', serialize( $gallery_data ) );
	
	// clear the meta on the parent page to prevent wrong re-saving, esp when updating
	p3_parent_window_js( "p3_clear_gallery_meta('p3_flash_gal_info')" );
	
	// just updating? 
	if ( isset( $_POST['update'] ) ) {
		p3_flash_gallery_json_generate( intval( $_REQUEST['post_id'] ) );
		return media_send_to_editor( '' ); 
	}
	
	// insert gallery
	p3_parent_window_js( 'p3_show_featured_gallery_meta_box()' );
	return media_send_to_editor( FLASH_GALLERY_PLACEHOLDER );
}


/* draw the form for the flash gallery media upload tab */
function media_upload_p3_gallery_form() {
	$post_id = intval( $_REQUEST['post_id'] );

	// WordPress assigns negative id as temporary id for posts without actual ID
	if ( $post_id < 0 ) return p3_msg( 'no_postid_for_gallery' );
	
	// defaults
	$false_checked = ' checked="checked"';
	$title = get_the_title( $post_id );
	$submit_btn = '<input id="p3-insert-flash-gallery-btn" type="submit" class="button savebutton" name="insert" value="Insert Flash Gallery" />';

	// gallery meta already exists
	if ( $stored_gallery_data = get_post_meta( $post_id, 'p3_flash_gal_info', AS_STRING ) ) {
		
		// meta without a gallery, clean up
		if ( !p3_post_has_flash_gallery( $post_id ) ) {
			delete_post_meta( $post_id, 'p3_flash_gal_info' );
		
		// meta + gallery, prefill form
		} else {
			extract( unserialize( $stored_gallery_data ) );
			$title = urldecode( $title );
			$subtitle = urldecode( $subtitle );
			$proofurl = urldecode( $proofurl );
			$mp3 = urldecode( $mp3 );
			$slideshow_speed = ( floatval( $speed ) ) ? floatval( $speed ) : '';
			
			// checked state
			if ( $auto_start == 'true' ) {
				$true_checked = ' checked="checked"';
			} else {
				$false_checked = ' checked="checked"';
			}

			// submit btn
			$submit_btn = '<input type="submit" class="button savebutton p3_btn" name="update" value="Update Flash Gallery" />';
		}
	}

	// ftp-ed mp3's into dropdown
	$available_mp3s = glob( P3_FOLDER_PATH . P3_MUSIC_DIR . '/*.mp3' );
	foreach ( (array) $available_mp3s as $mp3_path ) {
		$mp3_name = basename( $mp3_path );
		$mp3_url  = P3_FOLDER_URL . P3_MUSIC_DIR . '/' . $mp3_name;
		$mp3_options .= '<option value="' . $mp3_url . '"' . selected( $mp3_url, $mp3, NO_ECHO ) . '>' . $mp3_name . '</option>' . "\n";
	}
	
	// add any uploaded mp3's
	for ( $i = 1; $i <= MAX_AUDIO_FILE_UPLOADS; $i++ ) { 
		if ( !p3_file_exists( 'audio' . $i ) ) continue;
		$mp3_url  = p3_imageurl( 'audio' . $i, NO_ECHO );
		$mp3_name = ( p3_test( 'audio' . $i . '_filename' ) ) 
			? p3_get_option( 'audio' . $i . '_filename' ) 
			: 'P3 uploaded song #' . $i;
		$mp3_options .= '<option value="' . $mp3_url . '"' . selected( $mp3_url, $mp3, NO_ECHO ) . '>' . $mp3_name . '</option>' . "\n";
	}
	
	// prints the media upload tabs
	media_upload_header(); 
	
?>
	<form action="" method="post" class="p3-flash-gallery-tab">
		<h3 class="media-title">ProPhoto3 Flash Gallery</h3>
		<input type="hidden" name="type" value="<?php echo attribute_escape( $GLOBALS['type'] ); ?>" />
		<input type="hidden" name="tab" value="<?php echo attribute_escape( $GLOBALS['tab'] ); ?>" />
		<input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
		<p id="p3_gallery_no_imgs_warning" style="display:none;">You don't seem to have any images uploaded yet associated with this post. Please upload some images before attempting to insert a P3  Flash Gallery.</p>
		<p>Fill in any or all of the below option areas and click to <strong>insert all of this post's images as a flash gallery.</strong> You can customize the appearance of your gallery player on <a href="<?php p3_admin_url( 'galleries', 'flash' )  ?>">this page</a>.</p>
		<label for="title">Title:</label>
		<input type="text" name="title" value="<?php echo $title ?>" id="title">
		<label for="subtitle">Sub-title:</label>
		<input type="text" name="subtitle" value="<?php echo $subtitle ?>" id="subtitle">
		<label for="proofurl">Proofing page URL:</label>
		<input type="text" name="proofurl" value="<?php echo $proofurl ?>" id="proofurl">
		<div id="slideshow-speed">
			<label for="slideshow_speed">Override slideshow image hold time:</label>
			<input type="text" name="slideshow_speed" value="<?php echo $slideshow_speed ?>" id="slideshow_speed" size="3"><label> seconds</label><br />
		</div><!-- #slideshow-speed -->
		<div id="auto_start">
			<input type="radio" name="auto_start" value="false"<?php echo $false_checked ?>><label>click to start using gallery</label><br />
			<input type="radio" name="auto_start" value="true"<?php echo $true_checked ?>><label>auto-start slideshow</label><br />
		</div>
		<label for="mp3">Music:</label>
		<select name="mp3" id="p3-mp3" onchange="javascript:p3_mp3_select_change(jQuery(this));">
			<option value="">no music</option>
			<?php echo $mp3_options ?>
			<option value='add'>add a music file...</option>	
		</select>
		<p id="p3-add-mp3">To add music file so that you can select it here, you'll need to <strong>use an FTP program to upload a .mp3 file</strong> into your "wp-content/uploads/p3/<strong>music</strong>" folder. Once you've done that, come back here and the mp3 file will be available for selection. Alternatively, <strong>if the mp3 file is smaller than 2mb</strong>, you can upload it through the <a href="<?php p3_admin_url( 'settings', 'audio' ) ?>" target="_blank">Music Player Tab</a>. In depth tutorial <a href="<?php echo FTP_UPLOAD_AUDIO_TUT ?>">here</a>.</p>
		
		<?php echo $submit_btn ?>
	</form>
<?php 	
}


/* retrieves array of info about images attached to a given post */
function p3_get_gallery_images_data( $id ) {
	$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) );
	
	// loop goes moo when running on non copy, no idea why
	$check_attachments = $attachments;
	
	// exclude images with description including "nogallery"
	foreach ( $check_attachments as $id => $attachment ) {
		if ( preg_match( '/nogallery/i', $attachment->post_content ) ) unset( $attachments[$id] );
	}

	return $attachments;
}


/* get rid of weirdly filtered gallery js info */
add_filter( 'the_excerpt', 'p3_gallery_scrub_excerpts', 1 );
function p3_gallery_scrub_excerpts( $content ) {
	return $content = preg_replace( '/1[0-9]+\s+[0-9\.]+\s+[0-9\.]+\s+[0-9\.]+\s+[0-9\.]+/', '', $content );
}


/* boolean test, does a post have a flash gallery? accepts post_id or post content string */
function p3_post_has_flash_gallery( $post, $is_id = TRUE ) {
	if ( $is_id ) $post = p3_post_content( $post );
	if ( strpos( $post, 'p3-flash-gallery-holder p3-placeholder' ) === FALSE ) return FALSE;
	return TRUE;
}


/* custom gallery filter to replace holder with correct markup for js/flash */
add_filter( 'the_content', 'p3_flash_gallery_markup', 1000 );
function p3_flash_gallery_markup( $content ) {
	global $post;	
	$id = $post->ID;

	// return if placeholder not present in post
	if ( !p3_post_has_flash_gallery( $content, FALSE ) ) return $content;
	
	// get array of images associated with posts
	$attachments = p3_get_gallery_images_data( $id );
	
	// show images, not flash for the feed
	if ( is_feed() ) {
		$markup = p3_gal_images_unfiltered( $attachments );
	
	// iPad/iPhone	
	} else if ( IS_IPAD || IS_IPHONE ) {
		switch ( p3_get_option( 'flash_gal_fallback' ) ) {
			case 'lightbox':
				$markup = p3_lightbox_gallery_markup( LIGHTBOX_GALLERY_PLACEHOLDER );
				break;
			case 'images':
				$markup = p3_gal_images_unfiltered( $attachments );
				break;
			case 'remove':
				$markup = '';
				break;
		}
		
	// not a feed, setup markup for javascript/flash
	} else {

		// image src & info for the first image attachment
		reset( $attachments ); /* for bad hosts with register globals on */
		$src = $attachments[ key( $attachments ) ]->guid;
		$src = p3_pathfixer( $src );
		$path = str_replace( trailingslashit( P3_WPURL ), ABSPATH, $src );
		
		// nominal: path substitution succeeded, OK to check img size info & prep flash embed
		if ( $path != $src ) {
			
			$info = @getimagesize( $path );
			$size = $info[3];

			// modified time (this is used for object-specific cache-busting)
			$mod_time = get_post_modified_time( 'G', FALSE, $id );

			// object_dimensions
			$force_fullsize = ( isset( $_GET['gallery_popup'] ) && $_GET['gallery_popup'] == TRUE );
			$gallery_data   = p3_flash_gallery_dimensions( $id, $force_fullsize );
			$gallery_height = $gallery_data['obj_height'];
			$gallery_width  = $gallery_data['obj_width'];
			$image_height   = $gallery_data['img_height'];
			$image_width    = $gallery_data['img_width'];

			// default HTML is wrapping div and first image
			$markup = <<<HTML
			<div class='p3-flash-gallery-wrapper' style='width:{$gallery_width}px;height:{$gallery_height}px;'>
				<div class='p3-flash-gallery' id='p3-flash-gallery-$id'>
					<span class='modtime js-info'>$mod_time</span>
					<span class='gal_height js-info'>$gallery_height</span>
					<span class='gal_width js-info'>$gallery_width</span>
					<span class='img_height js-info'>$image_height</span>
					<span class='img_width js-info'>$image_width</span>
					<img src='$src' $size />
				</div>
			</div>
HTML;

		// problem, can't find path, probably pathfixer needed
		} else {
			if ( current_user_can( 'level_1' ) ) $error = p3_get_msg( 'flash_gal_needs_pathfixer', p3_get_admin_url( 'settings', 'settings' ) );
			
			// debug pathfixer issues
			if ( P3_TECH ) {
				ob_start();
				echo "\n\n<!-- FlashGal Pathfixer Error Debug report:\n\n";
				print_r( array( '$src' => $src, '$path' => $path, 'P3_WPURL' => P3_WPURL, 'ABSPATH' => ABSPATH, '$attachments' => $attachments ) );
				echo " -->\n\n";
				$debug_report = ob_get_contents();
				ob_end_clean();
			}
			
			$markup = $error . $debug_report . p3_gal_images_unfiltered( $attachments );
		}
	}

	$content = preg_replace( '/<img[^>]+p3-(flash|lightbox)-gal-placeholder\.gif[^>]+>/', $markup, $content );
	return $content;
}


/* remove html for p3 flash gallery placeholder img */
function p3_remove_gallery_placeholder( $content ) {
	return $content = preg_replace( '/<img[^>]+p3-(flash|lightbox)-gal-placeholder\.gif[^>]+>/', '', $content );
}


/* return raw img tag markup for gallery images */
function p3_gal_images_unfiltered( $attachments ) {
	foreach ( $attachments as $the_id => $attachment ) {
		$markup .= wp_get_attachment_link( $the_id, '', TRUE ) . "\n";
	}
	return $markup;
}


/*  wp hook to generate/regenerate settings file on post save */
add_action( 'save_post', 'p3_flash_gallery_json_generate' );

/* write image and settings data to individual per-instance gallery file */
function p3_flash_gallery_json_generate( $post_ID ) {
	if ( wp_is_post_revision( $post_ID ) ) return;
	if ( !p3_post_has_flash_gallery( $post_ID ) ) return; 
	
	// write custom file in to p3 upload dir
	$settings_filename    = P3_FOLDER_PATH . P3_GALLERY_DIR . '/' . $post_ID . '_settings.txt';
	$settings_filecontent = p3_flash_settings_file_content( $post_ID );
	p3_writefile( $settings_filename, $settings_filecontent );
	@chmod( $settings_filename, 0755 ); // Zeus servers set to 600
}


/* generate per-instance settings file content for flash gallery to use */
function p3_flash_settings_file_content( $post_id ) {	
	$attachments = p3_get_gallery_images_data( $post_id );

	// images
	foreach ( $attachments as $id => $attachment ) { 
		$full  = wp_get_attachment_image_src( $id, '', FALSE, FALSE );
		$thumb = wp_get_attachment_image_src( $id, 'thumbnail', FALSE, FALSE );
		$images .= "\n{ \"full\":\"$full[0]\", \"thumb\":\"$thumb[0]\" },";
	}
	$images = rtrim( $images, ',' );
	$images = "\"gallery\" : [" . $images . "\n],";
	
	// specific settings
	$gallery_data = maybe_unserialize( get_post_meta( $post_id, 'p3_flash_gal_info', AS_STRING ) );
	if ( !is_array( $gallery_data ) ) return;
	extract( $gallery_data );
	$title    = urldecode( $title );
	$subtitle = urldecode( $subtitle );
	$proofurl = urldecode( $proofurl );
	$mp3      = urldecode( $mp3 );
	
	// gallery titles
	$title = ( $title ) ? '"titleText": "' . $title . '",' : '';
	$subtitle = ( $subtitle ) ? '"subTitleText": "' . $subtitle . '",' : '';
	
	// music and prooflink
	$proofurl = ( $proofurl ) ? '"shoppingCartURL" : "' . $proofurl . '",' : '';
	$mp3 = ( $mp3 ) ? '"MP3URL" : "' . $mp3 . '",' : '';
	
	// gallery auto start params
	if ( $auto_start == 'true' ) {
		$playback = '"skipSplashScreen" : true, "autoStartSlideshow": true, "startFullScreen" : false,';
	}
	
	// slideshow hold time override
	if ( $speed = floatval( $speed ) ) {
		$playback .= '"slideShowHoldTime" : ' . $speed . ',';
	}
	
	// the json-formated output	
	$inner_output = rtrim( $images . $title . $subtitle . $proofurl . $mp3 . $sizes . $playback, ',' );
	$output = "{" . $inner_output . "}";
	return apply_filters( 'p3_localjson_output', $output, $post_id );
}


/* return flash gallery object width/height by analyzing post image + gallery customization data */
function p3_flash_gallery_dimensions( $post_id, $force_fullsize = FALSE ) {
	$post_images = p3_get_gallery_images_data( $post_id );
	$widest_image_width = $widest_image_id = 0;
	
	// get index and width of post's widest image
	foreach ( $post_images as $image_id => $image_data_obj ) {
		$image_info = wp_get_attachment_image_src( $image_id, '' );
		$image_width = $image_info[1];
		if ( $image_width > $widest_image_width ) {
			$widest_image_width = $image_width;
			$widest_image_id = $image_id;
			$widest_image_data = $image_info;	
		}
	}
	
	// start by setting object image width/height to the widest found image
	$gallery_image_width = $widest_image_width;
	$gallery_image_height = $widest_image_data[2];
	
	// get current content width, accounting for presence of fixed sidebar
	if ( p3_doing_sidebar() && !$force_fullsize ) {
		$content_width = p3_get_content_width( WITH_SIDEBAR );
	} else {
		$content_width = p3_get_content_width( NO_SIDEBAR );
	}
	
	// downsize object image size if they are larger than content width
	if ( $gallery_image_width > $content_width ) {
		$gallery_image_width  = $content_width;
		$downsize_divisor     = $widest_image_width / $gallery_image_width;
		$gallery_image_height = $gallery_image_height / $downsize_divisor;		
	}
	
	// default config: thumbstrip overlays image, does not add to height/width
	$gallery_object_width  = $gallery_image_width;
	$gallery_object_height = $gallery_image_height;
	
	// non overlay mode
	if ( p3_test( 'flash_gal_filmstrip_overlay', 'false' ) ) {
		
		// first calculate added height/width of thumbstrip
		$thumb_size         = p3_get_option( 'flash_gal_thumb_size' );
		$thumb_padding      = p3_get_option( 'flash_gal_thumb_padding' );
		$thumb_border_width = p3_get_option( 'flash_gal_thumb_border_width' );
		$thumbstrip_size    = $thumb_size + ( $thumb_padding + $thumb_border_width ) * 2 ;
		
		// top or bottom
		if ( p3_test( 'flash_gal_filmstrip_position', 'top' ) || 
			 p3_test( 'flash_gal_filmstrip_position', 'bottom' ) ) {
			$gallery_object_height += $thumbstrip_size;
			
		// left or right
		} else {
			$gallery_image_width -= $thumbstrip_size;
			$divisor = $gallery_object_width / $gallery_object_height;
			$gallery_image_height = $gallery_object_height = $gallery_image_width / $divisor;
		}
	} 
	
	// return array of main image and object dimensions
	$dimensions['img_width']  = $gallery_image_width;
	$dimensions['img_height'] = $gallery_image_height;
	$dimensions['obj_width']  = $gallery_object_width;
	$dimensions['obj_height'] = $gallery_object_height;
	return $dimensions;
}


/* converts css-style color codes to flash-ready */
function p3_flash_color( $input_color ) {
	if ( !preg_match( '/#[0-9a-fA-F]{6}/', $input_color ) ) return '0x000000';
	return $output_color = '0x' . strtoupper( substr( str_replace( '#', '', $input_color ), 0, 6 ) );
}


/* gets just the first font value from a css-style font fallback list for flash */
function p3_flash_font( $input_font ) {
	$font_pieces = explode( ',', $input_font );
	if ( $font_pieces[0] == 'Bookman' ) return "Bookman Old Style";
	return $flash_font = str_replace( '"', '', $font_pieces[0] );
}


/* makes sure numbers between 0 and 1 are formatted like 0.x instead of .x */
function p3_flash_number( $number ) {
	if ( $number[0] == '.' ) {
		$number = '0' . $number;
	}
	return $number;
}


/* converts percantage inputs into 0.xx type values */
function p3_convert_percentage( $num ) {
	$num = intval( str_replace( '%', '', $num ) );
	if ( $num == '100' ) return '1';
	if ( $num < 10 ) $num = '0' . $num;
	return '0.' . $num; 
}


/* return unordered list of posts containing "Featured" galleries */
function p3_get_featured_galleries() {
	
	// build query object to get just posts tagged for slideshow gallery
	$gallery_posts = new WP_Query();
	$gallery_posts->query( 
		array( 
			'meta_key' => 'p3_featured_gallery', 
			'meta_value' => 'true', 
			'post_type' => array( 'post', 'page' ),
			'posts_per_page' => 100 
		)
	);

	// loop through the posts to build an array of all the data
	while ( $gallery_posts->have_posts() ) : $gallery_posts->the_post();
		$post_id = get_the_ID();
		if ( !p3_post_has_flash_gallery( $post_id ) ) continue;
		$gallery_slideshows[$post_id]['url']   = get_permalink();
		$slideshow_meta = maybe_unserialize( get_post_meta( $post_id, 'p3_flash_gal_info', AS_STRING ) );
		$gallery_slideshows[$post_id]['title'] = urldecode( $slideshow_meta['title'] );
		$order = ( $meta_order = get_post_meta( $post_id, 'p3_gallery_order', AS_STRING ) ) ? $meta_order : 100;
		$gallery_slideshows[$post_id]['order'] = $order;
		$dimensions = p3_flash_gallery_dimensions( $post_id, 'full_size' );
		$gallery_slideshows[$post_id]['width']  = $dimensions['obj_width'];
		$gallery_slideshows[$post_id]['height'] = $dimensions['obj_height'];
	endwhile;
	wp_reset_query();
	
	// boogey if we have no posts
	if ( !is_array( $gallery_slideshows ) ) return;

	// sort featured gallery posts based on saved order
	uasort( $gallery_slideshows, 'p3_compare_gallery_order' );

	// build and return markup
	foreach ( $gallery_slideshows as $key => $slideshow ) {
		$list .= '<li><a href="' . $slideshow['url'] . '">' . $slideshow['title'] . '<span class="js-info width">' . $slideshow['width'] . '</span><span class="js-info height">' . $slideshow['height'] . '</span></a></li>';
	}
	return $list;
}


/* print stripped down html for featured gallery-popup */
function p3_gallery_popup_markup() {
	if ( !isset( $_GET['gallery_popup'] ) ) return;
	echo '<div id="main-wrap-inner">' . p3_flash_gallery_markup( FLASH_GALLERY_PLACEHOLDER ) . '</div>';
	get_footer();
	die;
}


/* helper routine to sort gallery posts array by order stored in post meta */
function p3_compare_gallery_order( $a, $b ) {
	if ( $a['order'] == $b['order'] ) return 0;
	return ( $a['order'] < $b['order'] ) ? -1 : 1;
}


/* add gallery meta boxes to page and post write pages */
add_action( 'admin_menu', 'p3_add_gallery_meta_boxes' );
function p3_add_gallery_meta_boxes() {
	add_meta_box( 'p3-gallery-meta', 'P3 Featured Slideshows', 'p3_gallery_meta_box', 'post', 'side', 'low' );
	add_meta_box( 'p3-gallery-meta', 'P3 Featured Slideshows', 'p3_gallery_meta_box', 'page', 'side', 'low' );
}


/* process saved info from gallery post meta boxes */
add_action( 'save_post', 'p3_gallery_postmeta_save');
function p3_gallery_postmeta_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
	if ( $_POST['action'] == 'inline-save' ) return $post_id; // bail if in quick-edit screen
	if ( $_POST['p3_featured_gallery'] == 'true' ) {
		update_post_meta( $post_id, 'p3_featured_gallery', 'true' );
		update_post_meta( $post_id, 'p3_gallery_order', $_POST['p3_gallery_order'] );
	} else {
		if ( get_post_meta( $post_id, 'p3_featured_gallery', AS_STRING ) != 'true' ) return;
		update_post_meta( $post_id, 'p3_featured_gallery', 'false' );
		update_post_meta( $post_id, 'p3_gallery_order', $_POST['p3_gallery_order'] );
	}
}


/* input markup elements for gallery meta box */
function p3_gallery_meta_box() {
	
	// attempt to get saved data
	$post_id = $_GET['post'];
	if ( $post_id ) {
		$gallery_meta = get_post_meta( $post_id, 'p3_featured_gallery', AS_STRING );
		if ( $gallery_meta == 'true' ) $checked = ' checked="checked"';
		$gallery_order = get_post_meta( $post_id, 'p3_gallery_order', AS_STRING );
		if ( $gallery_order ) $order = $gallery_order;
		if ( p3_post_has_flash_gallery( $post_id ) ) $class = 'has-flash-gallery';
	} 
	?>
	<div id="p3-featured-gallery-meta-body" class="<?php echo $class ?>">
		<p><input type="checkbox" name="p3_featured_gallery" value="true"<?php echo $checked ?>> Make this a featured gallery slideshow</p>
		<p><label for="p3_gallery_order">Order: </label><input type="text" name="p3_gallery_order" value="<?php echo $order ?>" id="p3_gallery_order" size="4"></p>
		<p style="margin-bottom:15px">You can choose to make this post's flash gallery a "Featured gallery".  <a href="<?php echo FEATURED_GALLERIES_TUT ?>">See here</a> for lots more info.</p>
	</div>
	<?php
}
?>