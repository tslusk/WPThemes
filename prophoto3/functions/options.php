<?php
/* ------------------------------------------------------------------------ */
/* -- library of functions used for creating option sections (non-image) -- */
/* ------------------------------------------------------------------------ */


/* wrapper factory function to create a new instance of p3_option_box class */
function p3_o( $name, $params, $comment = '', $title = '' ) {
	global $p3_multiple_option;
	
	$option = ( $p3_multiple_option ) 
		? new p3_option_box_multiple( $name, $params, $comment, $title )
		: new p3_option_box_individual( $name, $params, $comment, $title );
		
	$option->wrap_and_print_markup();
}


/* opens a multiple option group by setting global index, and optionally title */
function p3_start_multiple( $title = '' ) {
	global $p3_multiple_option, $p3_option_group_title;
	
	// nominal case: set global indext to 1
	if ( !$p3_multiple_option ) {
		$p3_multiple_option = 1;
	
	// OOPS: forgot to close a multiple group, handle it gracefully
	} else {
		p3_stop_multiple();
		$p3_multiple_option = 1;
	}
	
	// set the title for the whole following option group
	if ( $title ) $p3_option_group_title = $title;
}


/* closes a multiple option group */
function p3_stop_multiple() {
	global $p3_multiple_option, $p3_option_group_title;
	
	// close out markup
	if ( $p3_multiple_option != FALSE ) p3_option_box_multiple::end_multiple_section();
	
	// reset global counter and group title
	$p3_multiple_option = $p3_option_group_title = FALSE;
}


/* creates a title header for option section */
function p3_option_header( $title, $id, $comment = '' ) {
	echo <<<HTML
	<div id="tab-section-$id-link" class="tabbed-sections" style="display:none">
	<div class="self-clear header options-grouping-header" name="$id-link">
		<span class="option-section-label">$title</span>
		<span class="individual-option">$comment</span>
	</div>
HTML;
	do_action( "p3_options_pre_tab_{$id}" );
}


/* Handle POST data on the options page (when form submitted) */
function p3_process_options() {

	global $p3, $p3_non_design_settings;

	// only run when POST data present
	if ( !isset( $_POST['p3-options'] ) ) return;
	
	// don't try to save when not in utf-8, bad things happen
	if ( get_option( 'blog_charset' ) != 'UTF-8' )
		return p3_error_msg( 'non_utf8_encoding', admin_url( 'options-reading.php' ) );
	
	// handle ga_verifysum differently
	if ( isset( $_POST['p3_input_ga_verifysum'] ) && $_POST['p3_input_ga_verifysum'] != $p3['non_design']['ga_verifysum'] ) {
		$id = trim( $_POST['p3_input_ga_verifysum'] );
		p3_set_uid();
		$verify_response = wp_remote_get( PROPHOTO_SITE_URL . "?ga_verify=$id&nr_uid=" . get_option( 'nr_uid' ) );
		if ( $_GET['debug_verify'] ) p3_debug_array( $verify_response );
		if ( 'verified' == wp_remote_retrieve_body( $verify_response ) ) {
			$p3['non_design']['ga_checksum'] = md5( get_option( 'nr_uid' ) );
		} else {
			$p3['non_design']['ga_checksum'] = '';
			$_POST['p3_input_ga_verifysum'] = 'verification failed, try again';
			p3_error_msg( 'link_removal_txn_id_not_found' );
		}
	}
	
	// warn if form weight too high for suhosin
	if ( P3_DEV && count( $_POST ) > 200 )
		echo '<p style="color:red">Form weight warning: currently <strong>' . count( $_POST ) . '</strong></p>';

	// only POST data from admin pages or die
	check_admin_referer( 'p3-options' );
	
	// create arrays to store just the submitted options
	$submitted_design_options = $submitted_non_design_options = array();
	
	// loop through every POSTed input, store into new options array
	foreach ( $_POST as $key => $value ) {
		
		// process each 'p3_input_*' entry, ignore others
		if ( strpos( $key, 'p3_input_' ) !== FALSE) {
			
			// remove the input markup prefix
			$key = str_replace( 'p3_input_', '', $key);
			
			// set the POSTed value in one of the new options array
			if ( in_array( $key, $p3_non_design_settings ) ) {
				$submitted_non_design_options[$key] = $value;
			} else {
				$submitted_design_options[$key] = $value;
			}
			
			$new_data_to_store = TRUE;
		}
	}

	// Check if there was a masthead image reordering
	if ( $_POST['masthead_order_reordered'] == 'true' ) {
		p3_reorder_masthead( $_POST['masthead_order_string'] );
		$new_data_to_store = TRUE;
	}

	if ( $new_data_to_store ) {
		
		// if our target arrays are not arrays, make them so, avoid warning
		if ( !is_array( $p3[$p3['active_design']]['options'] ) ) $p3[$p3['active_design']]['options'] = array();
		if ( !is_array( $p3['non_design'] ) ) $p3['non_design'] = array();
		
		// merge design settings, newly submitted ones with existing ones for active design
		$p3[$p3['active_design']]['options'] = array_merge( $p3[$p3['active_design']]['options'], $submitted_design_options );
		
		// merge global, non-design settings, submitted ones with existing ones
		$p3['non_design'] = array_merge( $p3['non_design'], $submitted_non_design_options );
		
		// update the database, capture result status into variable
		$write_result = p3_store_options();
	}

	return $write_result;
}


/* Validate a color code */
function p3_validate_color( $color ) {
    $color = substr( $color, 0, 7 );
    return preg_match( '/#[0-9a-fA-F]{6}/', $color );
}


/* adds holder for extra explanation, added on request by javascript */
function p3_extra_explain( $name, $multi = FALSE ) { 
	$multi_class = ( $multi ) ? ' extra-explain-multiple' : '';
	return "<div id='explain-$name' class='extra-explain" . $multi_class . "'></div>";	
} 


/* adds clickable "?" to show extra explanation */
function p3_click_for_explain( $name ) {
		return " <a id=\"$name-cfa\" title=\"help\" class=\"click-for-explain\"></a>";
}


/* print list of subgroup subtabs for option areas */
function p3_subgroup_tabs( $subgroups ) {
	// keep track of subtab selected
	$helper = ( isset( $_POST['subtab'] ) ) ? $_POST['subtab'] : 'unset' ;
	echo '<input id="subtab-post" type="hidden" value="'.$helper.'" name="subtab" />';
	
	// echo out subtab nav
	echo "<ul id='subgroup-nav' class='self-clear'>\n";
	foreach ( $subgroups as $key => $val ) {
		echo "<li id='subgroup-nav-$key'><a key='$key' href='#$key' class='subgroup-link'>$val</a></li>\n";
	}
	echo "</ul>\n";
}

/* start an option-page subgroup div */
function p3_option_subgroup( $shortname ) {
	echo "<div id='subgroup-$shortname' class='subgroup'>\n";
}


/* adds images into option rows */
function p3_add_inline_banner_image( $name ) {
	if ( strstr( $name, 'banner' ) ) { 
		$img_name = str_replace( '_link', '', $name );
		if ( !p3_image_exists( $img_name ) ) return;
		$width = ( p3_imagewidth( $img_name, 0 ) > MAX_INLINE_BANNER_WIDTH ) ? " width=" . MAX_INLINE_BANNER_WIDTH : '';
		echo '<img class="image-inline" src="';
		p3_imageurl( $img_name ); echo '"';
		echo $width;
		echo ' /><br />';
	}
}


/* spit out a closing div to end an option subgroup */
function p3_end_option_subgroup() {
	echo '</div>';
}


/* write HTML for button to add another optional image */
function p3_add_image_upload_btn( $name, $image = 'image' ) {
	$id = str_replace( ' ', '-', strtolower( $name ) );
	echo "<div><a id='add-" . $id . "-upload' class='add-image-upload'>Add another $name $image.</a></div>";
}


/* css border option grouping */
function p3_border_options( $key, $nice_name = '', $prefix = '' ) {
	if ( !$prefix ) $prefix = $nice_name . ' border';
	p3_o( $key . '_border_width', 'slider|0|20| px', $prefix . ' width' );
	p3_o( $key . '_border_color', 'color', $prefix . ' color' );
	p3_o( $key . '_border_style', BORDER_STYLE_SELECT, $prefix . ' style' );
}


/* display contact log items */
function p3_contact_log() {
	$contact_log = get_option( P3_DB_CONTACT_LOG );
	if ( !$contact_log ) return p3_get_msg( 'no_contact_log_entries' );
	foreach ( $contact_log as $submission ) {
		$log .= '<p>Submitted: ' . date( 'm-d-Y', $submission['time'] ) . '</p>';
		$log .= '<p class="data">' . nl2br( $submission['data'] ) . '</p>';
	}
	return $log .= p3_get_msg( 'no_logging_before_pp31' );
}


/* user array data to create and return interface classes for css and js */
function p3_get_interface_classes( $option_name ) {
	global $p3_interface;
	$show_hide_info = $p3_interface[$option_name];
	if ( !$show_hide_info ) return array();
		
	// turn interface string into useable info
	$test_parts = explode( ' ', str_replace( 'hide if ', '', $show_hide_info ) );
	list( $controlling_option, $testing_values ) = $test_parts;
	$hidden_values = explode( '|', $testing_values );
	
	foreach ( $hidden_values as $hidden_value ) {
		// add appropriate classes for css and js to hook to
		if ( p3_test( $controlling_option, $hidden_value ) ) $interface_classes[] = 'start-hidden';
		$interface_classes[] = 'hide-when-' . $controlling_option . '-val-' . $hidden_value;
		$interface_classes[] = 'show-when-' . $controlling_option . '-clicked';
	}

	// return array of added classes
	if ( !is_array( $interface_classes ) ) $interface_classes = array();
	return $interface_classes;
}


/* write js and css to control complex interface displays of post-interaction link items */
function p3_post_interaction_interface() {
	// get which post-interaction link areas are currently set to be not included
	if ( p3_test( 'comments_linktothispost', 'no' ) ) $exclude[] ='#comments_linktothispost-option-section';
	if ( p3_test( 'comments_emailafriend', 'no' ) )   $exclude[] ='#comments_emailafriend-option-section';
	$exclude_list = implode( ',', (array) $exclude );
	
	// prepare a body class reflecting current post-interaction display stored value
	$body_class   = 'comments-layout-' . p3_get_option( 'comments_layout' ) . ' pi-display-' . p3_get_option( 'comments_post_interact_display' );
	if ( !p3_test( 'comments_layout', 'minima' ) ) $body_class .= ' comments-layout-not-minima';
	
	// print js and css to control interface interactions
	echo <<<HTML
	<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
	
		// earmark dependent items under optional post interact link areas
		jQuery('#comments_linktothispost-option-section,#comments_emailafriend-option-section').each(function(){
			jQuery('.individual-option', this).not(':first').addClass('dependent');
		});
		// body gets class showing state of 'comments_post_interact_display'
		jQuery('body').addClass('$body_class');
		// options shown only when pi-links is NOT set to 'images'
		jQuery('#comments_emailafriend_icon-individual-option, #comments_linktothispost_icon-individual-option, #comments_addacomment_icon-individual-option').addClass('if-display-not-images');
		// options shown only when pi-links is set to images
		jQuery('#comments_emailafriend_image-individual-option, #comments_linktothispost_image-individual-option, #comments_addacomment_image-individual-option').addClass('if-display-images');
		// add classes to sections that area currently excluded
		jQuery('$exclude_list').addClass('exclude');
	
		// post interact display clicks, update body class
		jQuery('#comments_post_interact_display-individual-option input').click(function(){
			jQuery('body')
				.removeClass('pi-display-button')
				.removeClass('pi-display-text')
				.removeClass('pi-display-images')
				.addClass('pi-display-'+jQuery(this).val());
		});
	
		// post interact individual option includes: update section class
		jQuery('#comments_linktothispost-individual-option input, #comments_emailafriend-individual-option input')
			.click(function(){
				var clicked = jQuery(this);
				var section = clicked.parents('.option-section')
				if (clicked.val() == 'yes') {
					section.removeClass('exclude');
				} else {
					section.addClass('exclude');
				}
			});
	
	});
	</script>
	<style type="text/css" media="screen">
	.if-display-images {
		display:none;
	}
	body.pi-display-images .if-display-images {
		display:block;
	}
	body.pi-display-images .if-display-not-images {
		display:none;
	}
	.exclude .dependent {
		display:none !important;
	}
	body.comments-layout-not-minima #comments_addacomment_image-individual-option,
	body.comments-layout-not-minima #comments_emailafriend_image-individual-option,
	body.comments-layout-not-minima #comments_linktothispost_image-individual-option,
	body.comments-layout-not-minima #comments_addacomment_icon-individual-option,
	body.comments-layout-not-minima #comments_emailafriend_icon-individual-option,
	body.comments-layout-not-minima #comments_linktothispost_icon-individual-option {
		display:none !important;
	}
	body.comments-layout-not-minima #comments_post_interaction_link_font_group-option-section,
	body.comments-layout-not-minima #comments_post_interaction_link_font_group-individual-option {
		display:block !important;
	}
	</style>
HTML;
}


/* load option classes */
require_once( dirname( __FILE__ ) . '/class.fonts.php' );
require_once( dirname( __FILE__ ) . '/class.borders.php' );
require_once( dirname( __FILE__ ) . '/class.options.php' );
require_once( dirname( __FILE__ ) . '/class.upload.php' );

?>