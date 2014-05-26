<?php
/*
This is the reset image to default form. Nothing really to edit here.
*/

// reset confirm clicked
if ( $_POST['action'] == 'reset' ) {
	check_admin_referer('reset-image' );
	global $p3;
	unset( $p3[$p3['active_design']]['images'][ attribute_escape( $_POST['image'] ) ] );
	p3_store_options();
	p3_reset_image_done();
	
// show initial reset form
} else {
	p3_reset_image_form();
}


/* iframed form to reset an image */
function p3_reset_image_form() {
	global $p3_defaults, $p3_misc_files;
	$image = attribute_escape( $_GET['p3_image'] );
	
	// default messages
	$reset_title = "Delete this image?";
	$reset_button = 'delete';
	
	// optional images messages
	if ( $p3_defaults['images'][$image] != 'nodefaultimage.gif') {
		$reset_title = 'Reset this image to default?';
		$reset_button = "reset";
	}
	
	// misc file messages
	if ( in_array( $image, $p3_misc_files ) ){
		$reset_title = 'Delete this file?';
		$reset_button = "delete";
	}
	
	echo <<<HTML
	<h3>$reset_title</h3>
	<form method="post">
	<input type="hidden" name="image" value="$image" />
	<input type="hidden" name="action" value="reset" />
	<input type="submit" value="$reset_button"/>
HTML;
	wp_nonce_field('reset-image' );
	// DO NOT close the form. It's closed somewhere else.
}


/* reset image/file confirmation, update parent frame via js */
function p3_reset_image_done() {
	global $p3_defaults, $p3_misc_files;
	$shortname = $_POST['image'];
	$is_misc = in_array( $shortname, $p3_misc_files );
	
	// context appropriate reset message
	$reset_msg = 'Image reverted to default.';
	if ( $p3_defaults['images'][$shortname] == 'nodefaultimage.gif' ) $reset_msg = 'Image deleted.';
	if ( $is_misc ) $reset_msg = 'File deleted.';
	echo '<p>'.$reset_msg.'</p>';
	
	// Not in a frame? send back to main page
	if ($_POST['iframed'] == 'false')
		die(' <a href="' . p3_get_admin_url( 'background' ) . '">Return</a></p>' );
	
	// In a frame: update background page and add close link
	$width  = p3_imagewidth( $shortname, NO_ECHO );
	$height = p3_imageheight( $shortname, NO_ECHO );
	$size   = p3_img_filesize($shortname, NO_ECHO );
	$imgurl = p3_imageurl( attribute_escape( $_POST['image'] ), NO_ECHO );
	
	// trigger js to update parent frame
	echo "<script type='text/javascript'>
	p3_sendtopage('$imgurl', '$shortname', '$width', '$height', '$size' );
	top.tb_remove()
	</script>";
}

?>