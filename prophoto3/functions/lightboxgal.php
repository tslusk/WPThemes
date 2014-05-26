<?php
/* ------------------------------------------------------ */
/* -- library of functions used for lightbox galleries -- */
/* ------------------------------------------------------ */


/* boolean test, does a post have a lightbox gallery? accepts post_id or post content string */
function p3_post_has_lightbox_gallery( $post, $is_id = TRUE ) {
	if ( $is_id ) $post = p3_post_content( $post );
	if ( strpos( $post, 'p3-lightbox-gallery-holder p3-placeholder' ) === FALSE ) return FALSE;
	return TRUE;
}


/* media upload flash gallery tab - show form or process form submission */
add_action( 'media_upload_p3_lightbox', 'media_upload_p3_lightbox' );
function media_upload_p3_lightbox() {
	
	// if gallery tab form submitted
	if ( isset( $_POST['insert'] ) OR isset( $_POST['update'] ) ) {
		
		// update post meta
		$lightbox_data = array(
			'thumb_size'      => urlencode( $_POST['thumb_size'] ),
			'show_main_image' => urlencode( $_POST['show_main_image'] ),
		);
		delete_post_meta( $_POST['post_id'], 'p3_lightbox_gal_info' );
		update_post_meta( $_POST['post_id'], 'p3_lightbox_gal_info', serialize( $lightbox_data ) );
		
		// clear the meta on the parent page to prevent wrong re-saving, esp when updating
		p3_parent_window_js( "p3_clear_gallery_meta('p3_lightbox_gal_info')" );
		
		// insert gallery
		if ( isset( $_POST['insert'] ) ) {
			return media_send_to_editor( LIGHTBOX_GALLERY_PLACEHOLDER );
		// update gallery
		} else {
			return media_send_to_editor( '' );
		}
	}
	
	// else show gallery form
	return wp_iframe( 'media_upload_p3_lightbox_form' );
}


/* draw the form for the flash gallery media upload tab */
function media_upload_p3_lightbox_form() {
	$post_id = intval( $_REQUEST['post_id'] );

	// WordPress assigns negative id as temporary id for posts without actual ID
	if ( $post_id < 0 ) return p3_msg( 'no_postid_for_gallery' );
	
	// default vals
	$true_checked = ' checked="checked"';
	$submit_btn   = '<input id="p3-insert-lightbox-gallery-btn" type="submit" class="button savebutton" name="insert" value="Insert Lightbox Gallery" />';
	
	// gallery meta already exists
	if ( $stored_lightbox_data = get_post_meta( $post_id, 'p3_lightbox_gal_info', AS_STRING ) ) {
		
		// meta without a gallery, clean up
		if ( !p3_post_has_lightbox_gallery( $post_id ) ) {
			delete_post_meta( $post_id, 'p3_lightbox_gal_info' );
		
		// meta + gallery, prefill form
		} else {
			extract( unserialize( $stored_lightbox_data ) );
			$thumb_size      = urldecode( $thumb_size );
			$show_main_image = urldecode( $show_main_image );
			
			// checked state
			if ( $show_main_image == 'true' ) {
				$true_checked = ' checked="checked"';
			} else {
				$false_checked = ' checked="checked"';
			}

			// update button
			$submit_btn = '<input type="submit" class="button savebutton p3_btn" name="update" value="Update Lightbox Gallery" />';
		}
	}
	
	// prints the media upload tabs
	media_upload_header();	
	
?>
	<form action="" method="post" class="p3-flash-lightbox-tab">
		<h3 class="media-title">ProPhoto3 Lightbox Overlay Gallery</h3>
		<input type="hidden" name="type" value="<?php echo attribute_escape( $GLOBALS['type'] ); ?>" />
		<input type="hidden" name="tab" value="<?php echo attribute_escape( $GLOBALS['tab'] ); ?>" />
		<input type="hidden" name="post_id" value="<?php echo $post_id; ?>" />
		<p id="p3_gallery_no_imgs_warning" style="display:none;">You don't seem to have any images uploaded yet associated with this post. Please upload some images before attempting to insert a P3 Gallery.</p>
		<p>You can modify the below fields, or leave them as is, then click below to <strong>insert all of this post's images as a lightbox overlay gallery.</strong> You can customize the appearance of your lightbox overlay gallery on <a href="<?php p3_admin_url( 'galleries', 'lightbox' )  ?>">this page</a>.</p>
		<label for="thumb_size">Override requested thumbnail size:</label>
		<input type="text" name="thumb_size" value="<?php echo $thumb_size ?>" id="thumb_size" size="3"><label>px</label><br />
		<div id="show_main_image">
			<input type="radio" name="show_main_image" value="true"<?php echo $true_checked ?>><label>Show one full-size image, then thumbs</label><br />
			<input type="radio" name="show_main_image" value="false"<?php echo $false_checked ?>><label>Show just thumbs</label><br />
		</div>
		<?php echo $submit_btn ?>
	</form>
<?php 	
}


/* filter for inserting lightbox gallery */
add_filter( 'the_content', 'p3_lightbox_gallery_markup', 1000 );
function p3_lightbox_gallery_markup( $content ) {
	if ( !p3_post_has_lightbox_gallery( $content, FALSE ) ) return $content;
	global $post;
	$post_id = $post->ID;
	
	// get array of images associated with posts
	$attachments = p3_get_gallery_images_data( $post_id );
	if ( empty( $attachments ) ) return;
	
	// gallery meta data
	$lightbox_data = maybe_unserialize( get_post_meta( $post_id, 'p3_lightbox_gal_info', AS_STRING ) );
	if ( is_array( $lightbox_data ) ) {
		extract( $lightbox_data );
		$meta_thumb_size = intval( urldecode( $thumb_size ) );
		$show_main_image = ( urldecode( $show_main_image ) == 'true' ) ? TRUE : FALSE;
	} else {
		$show_main_image = TRUE;
	}
	
	// thumb size is stored meta val or p3 val
	$thumb_size = ( $meta_thumb_size ) ? $meta_thumb_size : p3_get_option( 'lightbox_thumb_default_size' );
	$d['requested_thumb_size'] = $thumb_size;
	
	// post (non-feed) markup
	if ( !is_feed() ) {
	
		// start the output, including main image
		$markup  = '<div class="p3-lightbox-gallery">'."\n";
		if ( $show_main_image ) {
			reset( $attachments );
			$first_img_index = key( $attachments );
			list( $first_img_src, $first_img_width, $first_img_height ) = wp_get_attachment_image_src( $first_img_index, '' );
			$first_img_tag = wp_get_attachment_image( $first_img_index, '' );
			if ( $downsized = p3_downsize_img( $first_img_tag, $first_img_width, $first_img_height ) ) {
				$first_img_tag = $downsized[0];
			}
			$first_img_title = $attachments[$first_img_index]->post_title;
			$markup .= "<a href='$first_img_src' title='$first_img_title'>$first_img_tag</a>";
			unset( $attachments[$first_img_index] );
		}

		// try to cause the rows to line up with the end as close as possible
		$thumb_margin  = p3_get_option( 'lightbox_thumb_margin' );
		$thumb_borders = 2 * p3_get_option( 'post_pic_border_width' );
		$thumb_unit    = $thumb_size + $thumb_borders + $thumb_margin;
		$content_width = p3_get_content_width( p3_doing_sidebar() );
		$fits_per_row  = intval( ( $content_width + $thumb_margin ) / $thumb_unit ); 
		$num_thumbs    = count( $attachments );
		
		// more images than fit on a row: add one extra img per row, resize all images to exactly fit
		if ( $num_thumbs > $fits_per_row ) { 
			$fits_per_row = $fits_per_row + 1;
			$thumb_unit = intval( ( $content_width + $thumb_margin ) / $fits_per_row );
			$thumb_size = $thumb_unit - $thumb_borders - $thumb_margin;
			$first_row_width = ( $fits_per_row * $thumb_unit ) - $thumb_margin;

		// num images is same as fits per row, recalculate size for perfect fit
		} else if ( $num_thumbs == $fits_per_row ) {
			$thumb_unit = intval( ( $content_width + $thumb_margin ) / $fits_per_row );
			$thumb_size = $thumb_unit - $thumb_borders - $thumb_margin;
			$first_row_width = ( $fits_per_row * $thumb_unit ) - $thumb_margin;

		// num images less than what would fit per row, leave size as requested, calc row width
		} else {
			$first_row_width = ( $num_thumbs * $thumb_unit ) - $thumb_margin;
		}

		// build thumbnail markup
		$markup .= "<div class='p3-lightbox-gallery-thumbs' style='width:{$first_row_width}px;'>\n";
		$i = 1; $num_thumbs_remaining = $num_thumbs;
		foreach ( $attachments as $id => $attachment ) {
			$num_thumbs_remaining--;

			// img tag source with calculated img size replacement
			$replace = "width='$thumb_size' height='$thumb_size' style='height:{$thumb_size}px; '";
			$link = wp_get_attachment_link( $id, 'thumbnail', FALSE, FALSE );
			$link = preg_replace( '/width="[0-9]+" height="[0-9]+"/', $replace,  $link );
			if ( is_int( $i / $fits_per_row  ) || $num_thumbs_remaining == 0 ) $link = "<span class='last'>$link</span>";
			$markup .= $link;

			// start a new row when necessary
			if ( is_int( $i / $fits_per_row  ) && $num_thumbs > $i ) {
				if ( $num_thumbs_remaining < $fits_per_row ) {
					$row_width = ( $num_thumbs_remaining * $thumb_unit ) - $thumb_margin;
				} else {
					$row_width = ( $fits_per_row * $thumb_unit ) - $thumb_margin;
				}
				$markup .= "</div><div class='p3-lightbox-gallery-thumbs' style='width:{$row_width}px;'>";
			}
			$i++;
		}

		// finish up and return the output
		$markup .= '</div></div>';
	
	// feed gets all full sized images
	} else  {
		foreach ( $attachments as $img_id => $attachment ) {
			$markup .= wp_get_attachment_link( $img_id, '', TRUE ) . "\n";			
		}
	}
	
	/* debug */
	// $d['content_width']=$content_width;$d['fits_per_row']=$fits_per_row;$d['first_row_width']=$first_row_width;
	// $d['thumb_size']=$thumb_size;$d['thumb_margin']=$thumb_margin;$d['num_thumbs']=$num_thumbs;p3_debug_array( $d );

	$content = preg_replace( '/<img[^>]+p3-(flash|lightbox)-gal-placeholder\.gif[^>]+>/', $markup, $content );
	return $content;
}
?>