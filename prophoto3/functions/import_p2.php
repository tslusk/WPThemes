<?php
/* -------------------------------------------------------- */
/* -- library of functions used for importing P2 layouts -- */
/* -------------------------------------------------------- */


/* import from P2 - iframed popup content */
function p3_p2import_form() {
	// form submitted, process POST and refresh parent page
	if ( isset( $_POST['p3_designs_action'] ) ) {
		p3_process_design_form_post();
		p3_msg( 'p2_layouts_imported' );
	
	// iframe popup display
	} else {
		echo p3_import_p2_layouts_markup();
	}
}


/* returns form checkbox markup for importing P2 layouts, when appropriate */
function p3_import_p2_layouts_markup() {
	global $p3_store;
	
	// return blank if there is no sign of ProPhoto2 in the database
	if ( !p3_p2_db_data() ) return FALSE;
	
	// checkbox for currently saved p2 design, if not already imported
	if ( !in_array( 'current_p2_saved_settings', (array) $p3_store['designs'] ) ) {
		$options .= '<p class="input-wrap"><input type="checkbox" name="p2_current" value="true" id="p2_current"><label><strong>Current P2 saved settings</strong></p>';
	}
	
	// loop through all found p2 settings flat files, creating checkboxes and building data for hidden input
	$p2_layouts_files = glob( P2_UPLOAD_PATH . 'settings_*.php' );
	foreach ( (array) $p2_layouts_files as $filepath ) {
		$layout_name = str_replace( array( P2_UPLOAD_PATH . 'settings_', '.php' ), '', $filepath );
		$layout_nicename = ucfirst( str_replace( array( '_', '-' ), ' ', $layout_name ) );
		
		// update hidden data array
		$hidden_form_layouts_data[] = array( 'filepath' => $filepath, 'name' => $layout_name, 'nicename' => $layout_nicename );
		
		// add checkbox for this layout if not already imported
		$layout_index = array_pop( array_keys( $hidden_form_layouts_data ) );
		if ( !in_array( p3_sanitize_for_design_id( $layout_nicename ), (array) $p3_store['designs'] ) ) {
			$options .= "<p class='input-wrap'><input type='checkbox' name='p2_layouts_{$layout_index}' value='true' id='p2_layouts_{$layout_index}'><label>Saved layout: <em>\"$layout_name\"</em></p>";
		}

	}
	
	// everything is imported
	if ( !$options ) return FALSE;
	
	// add data to hidden form for use in POST deciphering
	$options .= '<input type="hidden" name="p2_layouts_data" value="' . htmlspecialchars( serialize( $hidden_form_layouts_data ) ) . '" id="p2_layouts_data">';
	$nonce = wp_nonce_field( 'p3-settings', '_wpnonce_p3', TRUE, NO_ECHO );
	
	$markup = <<<HTML
	<h3>Import designs from P2</h3>
	<p class="intro">ProPhoto3 was able to find the following sets of <strong>ProPhoto version 2 saved layouts</strong>.  You may import as many as you wish by checking the boxes and submitting the form.</p>
	<form id="import-p2-layouts" action="" method="post">
		<input type="hidden" name="p3_designs_action" value="import_p2_layouts"/>
		$options
		<p><input type="submit" value="import selected P2 layouts"></p>
		$nonce
	</form>
HTML;

	return $markup;
}


/* on theme activation, transfer non-design settings from P2, if available */
function p3_import_p2_non_design_settings() {
	if ( !$p2 = p3_p2_db_data() ) return;
	global $p3_non_design_settings, $p3;
	
	foreach ( (array) $p2['settings'] as $key => $val ) {
		// careful with pathfixer
		if ( $key == 'pathfixer_old' && $val ) {
			if ( $p2['settings']['pathfixer'] == 'off' ) {
				$val = '';
			} else {
				$val = preg_replace( '/\/wp-content.+/', '', $val );
			}
		}
		
		// tranform any names that changed from p2 to p3
		$transformed = p3_transform_option_names( $key, $val );
		
		// only do p3 'non-design' type settings
		if ( !in_array( $transformed['key'], $p3_non_design_settings ) ) continue;
		
		// update the non-design setting
		$p3['non_design'][$transformed['key']] = $transformed['val'];
	}

	// update the db
	p3_store_options();
}


/* transform option names from P2 to P3 */
function p3_transform_option_names( $key, $val ) {
	global $p3_transform;

	// make tranformations as necessary
	if ( $p3_transform[$key]['p3name'] ) $new_key = $p3_transform[$key]['p3name'];
	if ( $p3_transform[$key][$val] )     $new_val = $p3_transform[$key][$val];
	
	// take care of old twitter_name problem
	if ( $key == 'twitter_name' && ( $val == 'netrivet' || $val == 'prophotoblogs' ) ) $new_key = 'x';
	
	// return values in P3 terminology
	$transformed['key'] = ( $new_key ) ? $new_key : $key;
	$transformed['val'] = ( $new_val ) ? $new_val : $val;

	return $transformed;
}


/* transfer a layout from ProPhoto version 2 */
function p3_transfer_p2_layout( $settings_data, $name, $description = '' ) {
	global $p3, $p3_store, $p3_non_design_settings;
	
	// add design, get design id
	$design_id = p3_add_new_design( $name, $description );
	
	// update p3 variable with transferred settings data
	foreach ( $settings_data as $sub_array_key => $sub_array ) {
		foreach ( (array) $sub_array as $key => $val ) {
			
			// tranform any names that changed from p2 to p3
			$transformed = p3_transform_option_names( $key, $val );

			// skip p3 non-design settings
			if ( in_array( $transformed['key'], $p3_non_design_settings ) ) continue;
			
			// design settings
			$p3_store[$design_id][$sub_array_key][$transformed['key']] = $transformed['val'];
		}
	}

	// some more transformations from P2 to P3
	p3_p2_import_helper( $design_id );

	// store transferred data
	p3_store_options();
		
	// copy p2 images/files into p3 folder
	foreach ( (array) $settings_data['images'] as $shortname => $filename ) {
		p3_move_p2_uploaded_file( $filename );
	}
}


/* move a p2 uploaded file: replicates php copy() because some hosts disable this function */
function p3_move_p2_uploaded_file( $filename ) {
	$file_data    = @file_get_contents( P2_UPLOAD_PATH . $filename );
	$new_file     = @fopen( P3_IMAGES_PATH . $filename, "w" );
	$write_status = @fwrite( $new_file, $file_data );
	@fclose( $new_file );
	if ( $file_data && $write_status ) return TRUE;
	return FALSE;
}


/* provides additional P2 => P3 import transformations that can't be represented in simple array */
function p3_p2_import_helper( $design_id ) {
	global $p3_store;
	$imp = $p3_store[$design_id];
	
	// reset flashheader fade time to default, totally different in P3
	unset( $imp['options']['flash_fadetime'] );
	
	// modify blog width, borders and shadows are not part of blog width in P3
	if ( $imp['options']['blog_border'] == 'dropshadow' ) {
		$imp['options']['blog_width'] = $imp['options']['blog_width'] - 14;
	} else {
		$imp['options']['blog_width'] = $imp['options']['blog_width'] - ( $imp['options']['blog_border_width'] * 2 );
	}
	if ( intval( $imp['options']['blog_width'] ) < 740 ) $imp['options']['blog_width'] = 980;
	
	// extra space for bio page-turn image
	if ( $imp['options']['bio_bg'] == 'default' ) {
		$imp['options']['bio_top_padding'] = '45';
	}
	
	// change categories prelude, add extra blank space
	$imp['options']['catprelude'] = $imp['options']['catprelude'] . ' ';
	
	// bgs now optional, turn bind on to get correct P2 behavioar
	$imp['options']['comments_comment_bg_color_bind'] = 'on';
	$imp['options']['nav_bg_color_bind'] = 'on';
	$imp['options']['nav_dropdown_bg_hover_color_bind'] = 'on';
	$imp['options']['body_bg_color_bind'] = 'on';
	$imp['options']['blog_bg_color_bind'] = 'on';
	$imp['options']['header_bg_color_bind'] = 'on';
	$imp['options']['gen_link_font_color_bind'] = 'on';
	$imp['options']['gen_link_hover_font_color_bind'] = 'on';
	
	// some misc modifications to match version 2 
	$imp['options']['comments_post_interact_spacing'] = '12';
	$imp['options']['blog_border_shadow_width'] = 'narrow';
	$imp['options']['comment_vertical_separation'] = '0';
	$imp['options']['comment_vertical_padding'] = '5';
	$imp['options']['comments_body_lr_margin'] = '0';
	$imp['options']['comments_body_tb_margin'] = '0';
	$imp['options']['comment_meta_display'] = 'inline';
	$imp['options']['comments_comment_line_height'] = '1.25';
	$imp['options']['comments_show_hide_method'] = 'text';
	$imp['options']['comments_header_tb_padding'] = '10';
	
	// post meta
	if ( $imp['options']['cattopbottom'] == 'top' ) {
		$imp['options']['categories_in_post_header'] = 'yes';
		$imp['options']['categories_in_post_footer'] = '';
	} else {
		$imp['options']['categories_in_post_header'] = '';
		$imp['options']['categories_in_post_footer'] = 'yes';
	}
	
	// layout-specific comments-stuff 
	if ( $imp['options']['comments_layout'] == 'boxy' ) {
		$imp['options']['comments_area_border_width'] = '1';
	} else {
		$imp['options']['comments_area_border_width'] = '0';
	}
	
	// blog background attributes
	switch ( $imp['options']['bg_img_position'] ) {
		case 'repeat':
			break;
		case 'no-repeat':
			$imp['options']['blog_bg_img_repeat'] = 'no-repeat';
			break;
		case 'repeat-x':
			$imp['options']['blog_bg_img_repeat'] = 'repeat-x';
			break;
		case 'repeat-y':
			$imp['options']['blog_bg_img_repeat'] = 'repeat-y';
			break;
		case 'repeat fixed':
			$imp['options']['blog_bg_img_repeat'] = 'repeat';
			$imp['options']['blog_bg_img_attachment'] = 'fixed';
			break;		
		case 'repeat-x fixed':
			$imp['options']['blog_bg_img_repeat'] = 'repeat-x';
			$imp['options']['blog_bg_img_attachment'] = 'fixed';
			break;
	}
	
	// archive pages content vs excerpt
	if ( $imp['options']['archive_content_option'] == 'excerpt' ) {
		$imp['options']['excerpts_on_archive']  = 'true';
		$imp['options']['excerpts_on_category'] = 'true';
		$imp['options']['excerpts_on_search']   = 'true';
		$imp['options']['excerpts_on_tag']      = 'true';
		$imp['options']['excerpts_on_author']   = 'true';
	}
	
	// transfer bio content to widget/s
	if ( p3_using_default_widget( 'bio-spanning-col' ) ) {
		p3_remove_default_widget( 'bio-spanning-col' );
		for ( $i = 1; $i <= 4; $i++ ) { // 4 is max p2 bio paras/headers
			if ( $imp['options']['bioheader'.$i] || $imp['options']['biopara'.$i] ) {
				$widget_col_num = ( $i < 3 ) ? '1' : '2';
				if ( $widget_col_num == '2' && $imp['options']['bio2column'] == 'off' ) continue;
				$bio_widget = array();
				$bio_widget['title']  = stripslashes( $imp['options']['bioheader'.$i] );
				$bio_widget['text']   = stripslashes( $imp['options']['biopara'.$i] );
				$bio_widget['wpautop'] = 1;
				p3_add_widget( 'bio-col-' . $widget_col_num , 'p3-text', $bio_widget );
			}
		}
	}
	
	// transfer contact text to widget
	if ( p3_using_default_widget( 'p3-contact-form' ) ) {
		p3_remove_default_widget( 'p3-contact-form' );
		$contact_widget['title'] = stripslashes( $imp['options']['contact_header'] );
		$contact_widget['text']  = stripslashes( $imp['options']['contact_text'] );
		$contact_widget['wpautop'] = 1;
		p3_add_widget( 'p3-contact-form', 'p3-text', $contact_widget );
	}
	
	// random bio pic
	for ( $i = 2; $i <= 15; $i++ ) { 
		if ( $imp['images']['biopic'.$i] ) {
			$imp['options']['biopic_display'] = 'random';
		}
	}
	
	// a no-logo layout
	if ( strpos( $imp['options']['headerlayout'], 'logo' ) === FALSE && $imp['options']['headerlayout'] != 'pptclassic' ) {
		$imp['images']['logo'] = 'nodefaultimage.gif';
	}
	
	// bio background area
	switch ( $imp['options']['bio_bg'] ) {
		case 'default':
			$imp['options']['bio_bg_color'] = '#ffffff';
			$imp['images']['bio_bg'] = 'prophoto2_bio_bg.jpg';
			$imp['options']['bio_bg_img_repeat'] = 'repeat-x';
			$imp['options']['bio_bg_img_position'] = 'bottom left';
			$imp['options']['bio_inner_bg_img_repeat'] = 'no-repeat';
			$imp['options']['bio_top_padding'] = '55';
			if ( $imp['options']['biopic_align'] == 'left') {
				$imp['images']['bio_inner_bg'] = 'prophoto2_bio_inner_bg.jpg';
				$imp['options']['bio_inner_bg_img_position'] = 'top right';
			} else {
				$imp['images']['bio_inner_bg'] = 'prophoto2_bio_inner_bg_alt.jpg';
				$imp['options']['bio_inner_bg_img_position'] = 'top left';
			}
			break;
		case 'gradient':
			$imp['options']['bio_bg_color'] = '#ffffff';
			$imp['images']['bio_bg'] = 'prophoto2_bio_bg.jpg';
			$imp['options']['bio_bg_img_repeat'] = 'repeat-x';
			$imp['options']['bio_bg_img_position'] = 'bottom left';
			break;
		case 'color':
			// do nothing, color already transferred with correct name
			break;
		case 'none':
			$imp['options']['bio_bg_color'] = $imp['options']['body_bg_color'];
			break;
	}
	
	// bio simple twitter widget
	if ( $imp['options']['bio_twitter'] == 'display' ) {
		$bio_col_num = ( $imp['options']['bio2column'] == 'on' ) ? '3' : '2';
		$twitter_widget = array(
			'badge_color'    => $imp['options']['bio_twitter_color'],
			'twitter_name'   => $imp['options']['twitter_name'],
			'follow_text'    => $imp['options']['twitter_linktext'],
		);
		p3_add_widget( 'bio-col-' . $bio_col_num, 'p3-twitter-simple-badge', $twitter_widget );
	}
	
	$p3_store[$design_id] = $imp;
}





?>