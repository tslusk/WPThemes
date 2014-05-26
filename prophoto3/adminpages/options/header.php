<?php
/* --------------------- */
/* HEADER CUSTOMIZATIONS */
/* --------------------- */


/* Define subgroups */
$subgroups =  array(
	// 'shortname' => 'Long Pretty Name',
	'layout' => 'Layout &amp; Appearance',
	'logo' => 'Logo',
	'masthead' => 'Masthead Image &amp; Slideshow',
);
// How many masthead images defined?
global $mastheads_array;
for ( $i = 1; $i <= MAX_MASTHEAD_IMAGES; $i++ ) {
	if ( p3_image_exists( 'masthead_image' . $i ) ) {
		$mastheads_array[] = 'masthead_image' . $i;
	}
}
if ( count($mastheads_array) > 1 )
	$subgroups['reorder-masthead'] = 'Reorder Masthead images';


echo <<<HTML
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	
	// header layout custom javascript
	jQuery('.header-thumb-button').mouseover(function(){
		var left = jQuery(this).attr('id' );
		left = layouts[left] * 200;
		jQuery('#headerlayout-viewer').css('background-position', '-'+left+'px 0' );
		jQuery(this).addClass('hovered');
	});
	jQuery('.header-thumb-button').mouseout(function(){
		var selected = jQuery('#headerlayout-input').attr('value' );
		left = layouts[selected] * 200;
		jQuery('#headerlayout-viewer').css('background-position', '-'+left+'px 0' );
		jQuery(this).removeClass('hovered');
	});
	jQuery('.header-thumb-button').click(function(){
		var selected = jQuery(this).attr('id');
		jQuery('#headerlayout-input').attr('value', selected);
		left = layouts[selected] * 200;
		jQuery('#headerlayout-viewer').css('background-position', '-'+left+'px 0' );
		jQuery('.header-thumb-button').removeClass('active-thumb');
		jQuery(this).addClass('active-thumb');
	});
	
	// masthead display ux
	var saved_masthead_choice = jQuery('#masthead_display-individual-option input[type=radio]:checked').val();	
	p3_process_masthead_display_choice( saved_masthead_choice );
	jQuery('#masthead_display-individual-option input[type=radio]').click(function(){
		p3_process_masthead_display_choice( jQuery(this).val() );
	});
	jQuery('#subgroup-masthead .empty').hide();
	
	// classes for masthead modification, used for show/hide ux of complex masthead modified options
	var masthead_modify = jQuery('#masthead_modify-individual-option input[type=radio]:checked').val();
	jQuery('#masthead_display-option-section').addClass('masthead-modify-'+masthead_modify);
	jQuery('#masthead_modify-individual-option input[type=radio]').click(function(){
		jQuery('#masthead_display-option-section')
			.removeClass('masthead-modify-true masthead-modify-false')
			.addClass( 'masthead-modify-'+jQuery(this).val() );
	});
	var modify_display = jQuery('#modified_masthead_display-individual-option input[type=radio]:checked').val();
	jQuery('#masthead_display-option-section table').addClass('modify-display-'+modify_display);
	jQuery('#modified_masthead_display-individual-option input[type=radio]').click(function(){
		jQuery('#masthead_display-option-section table')
			.removeClass('modify-display-none modify-display-image')
			.addClass( 'modify-display-'+jQuery(this).val() );
	});
});

/* hides and shows different areas based on masthead display choice */
function p3_process_masthead_display_choice( choice ) {
	jQuery('#subgroup-masthead').removeClass('static random slideshow custom off').addClass(choice);
	
	// image titles and comments
	jQuery('span.masthead-conditional').hide();
	jQuery('span.mc-'+choice).show();
	
	// add image and custom swf upload show/hide
	if ( choice == 'static' ) {
		jQuery('#add-masthead-upload').addClass('hidden').hide();
		jQuery('#subgroup-masthead .upload-row').not(':first').hide();
		jQuery('#subgroup-masthead .upload-row:first').show();
		jQuery('#upload-row-masthead_custom_flash').addClass('hidden').hide();
	} else if ( choice == 'random' ) {
		jQuery('#subgroup-masthead .upload-row').not('.empty').show();
		jQuery('.add-image-upload').removeClass('hidden').show();
		jQuery('#upload-row-masthead_custom_flash').addClass('hidden').hide();
	} else if ( choice == 'slideshow' ) {
		jQuery('#subgroup-masthead .upload-row').not('.empty').show();
		jQuery('.add-image-upload').removeClass('hidden').show();
		jQuery('#upload-row-masthead_custom_flash').addClass('hidden').hide();
	} else if ( choice == 'custom' ) {
		jQuery('#subgroup-masthead .upload-row').not(':first').hide();
		jQuery('.add-image-upload').addClass('hidden').hide();
		jQuery('#upload-row-masthead_custom_flash').removeClass('hidden').show();
	} else if ( choice == 'off' ) {
		jQuery('#subgroup-masthead .upload-row').hide();
		jQuery('.add-image-upload').addClass('hidden').hide();
	}
}

</script>
HTML;


/* give extra explanation about masthead height and width, context-sensitive */
function p3_masthead_height_explain() {
	$explain = ( p3_logo_masthead_sameline() )
		? "<span id='mhe'> Because of the header layout you've chosen, the height of this image must be the height of the logo you have uploaded" 
		: "<span id='mhe'> Because of the header layout you've chosen, this image <span class='first-image'>can be any height</span><span class='non-first-image'>must be the <strong>same height as your first masthead image</strong></span>";
	$explain .= ".</span>";
	return $explain;
}


/* draws header options form */
function p3_header_options() { 
	// get current stored layout
	$stored_layout = p3_get_option( 'headerlayout' );
	
	// array of layout names and positions sprite
	$layout = array(
			'logomasthead_nav'           => 0,
			'mastlogohead_nav'           => 1,
			'mastheadlogo_nav'           => 2,
			'logoleft_nav_masthead'      => 12,
			'logocenter_nav_masthead'    => 10,
			'logoright_nav_masthead'     => 14,
			'logoleft_masthead_nav'      => 13,
			'logocenter_masthead_nav'    => 11,
			'logoright_masthead_nav'     => 15,
			'nav_masthead_logoleft'      => 6,
			'nav_masthead_logocenter'    => 4,
			'nav_masthead_logoright'     => 8,
			'masthead_nav_logoleft'      => 7,
			'masthead_nav_logocenter'    => 5,
			'masthead_nav_logoright'     => 9,
			'nav_masthead'               => 16,
			'masthead_nav'               => 17,
			'pptclassic'                 => 3,
			'masthead_logoleft_nav'      => 18,
			'masthead_logocenter_nav'    => 19,
			'masthead_logoright_nav'     => 20,
			'nav_logoleft_masthead'      => 21,
			'nav_logocenter_masthead'    => 22,
			'nav_logoright_masthead'     => 23,
		);
	
	// starting css offset for main image sprite
	$main_img_offset = $layout[$stored_layout] * HEADER_PREVIEW_IMG_WIDTH;	
	
	// use $layout array to echo javascript array
	ob_start();
	echo '<script type="text/javascript" charset="utf-8">var layouts = new Object();';
	foreach ( $layout as $layout_name => $layout_position ) {
		echo "layouts['$layout_name'] = $layout_position;\n";
	}
	echo '</script>';
	
	// start HTML
	echo <<<HTML
	<input id="headerlayout-input" type="hidden" name="p3_input_headerlayout" value="$stored_layout">
	<div id="headerlayout-viewer-wrapper">
		<p>Currently Selected Header Layout:</p>
		<div id="headerlayout-viewer" style="background-position:-{$main_img_offset}px 0"></div>
	</div>
	<div id="header-thumbs" class="self-clear">
HTML;
	
	// thumb divs
	foreach ( $layout as $layout_name => $layout_position ) {
		
		// give a special class to the current stored choice
		$class = ( $layout_name == $stored_layout ) ? ' active-thumb': '';
		
		// calculate CSS offset to show right part of sprite
		$left = $layout_position * HEADER_PREVIEW_THUMB_WIDTH;
		
		// print markup including CSS offset
		echo "<div id='$layout_name' class='header-thumb-button self-clear$class'>
			     <div style='cursor:pointer;background-position:-{$left}px 0;'></div>
		     </div>";
	} 
	
	// close header-thumbs div		
	echo '</div><!-- #header-thumbs -->';			

	// get buffered output into var and return
	$markup = ob_get_contents();
	ob_end_clean();
	return $markup;
	
} // end function p3_header_options()





p3_subgroup_tabs( $subgroups );
p3_option_header('Header Area Options', 'header' );

/* layout subgroup */
p3_option_subgroup( 'layout' );

// header layout
p3_o( 'headerlayout', 'function|p3_header_options', '', 'Header layout' );

// header bg color
p3_o('header_bg_color', 'color|optional', 'background color behind logo (only seen in certain header layouts with logos less than width of blog)', 'Header background color' );

// masthead border top
p3_start_multiple( 'Custom line above masthead' );
p3_o( 'masthead_border_top', 'radio|on|add custom line|off|do not add custom line', 'add/remove a custom line above the masthead area' );
p3_border_group( array( 'key' => 'masthead_border_top', 'comment' => 'custom line appearance' ) );
p3_stop_multiple();

// masthead border bottom
p3_start_multiple( 'Custom line below masthead' );
p3_o( 'masthead_border_bottom', 'radio|on|add custom line|off|do not add custom line', 'add/remove a custom line below the masthead area' );
p3_border_group( array( 'key' => 'masthead_border_bottom', 'comment' => 'custom line appearance' ) );
p3_stop_multiple();

p3_end_option_subgroup();



/* logo subgroup */
p3_option_subgroup( 'logo' );
p3_linked_image( 'logo', 'Main blog logo' );
p3_o( 'logo_swf_switch', 'radio|off|use a static logo image|on|use a custom flash movie for logo', 'Turn on if you\'d like to replace your static logo image with a custom flash movie', 'Custom logo flash movie' );
p3_file( 'logo_swf', 'Custom logo flash movie' );
p3_end_option_subgroup();



/* masthead subgroup */
p3_option_subgroup( 'masthead' );

// masthead options
$off_option = ( p3_logo_masthead_sameline() && !p3_test( 'masthead_display', 'off' ) ) ? '' : '|off|do not display masthead';
for ( $i = 1; $i <= MAX_MASTHEAD_IMAGES; $i++ ) { 
	if ( !p3_image_exists( 'masthead_image' . $i ) ) continue;
	$img_options .= '|' . $i . '|Masthead image #' . $i;
}
p3_start_multiple( 'Masthead display options' );
p3_o( 'masthead_display', 'radio|static|single static image|random|random static image|slideshow|built-in flash slideshow|custom|custom uploaded flash .swf file' . $off_option );
p3_o( 'masthead_modify', 'radio|false|same masthead on all pages|true|modify masthead on certain pages' );
if ( P3_HAS_STATIC_HOME ) {
	$home = 'posts page';
	$static = '|masthead_on_front_page|modified|static front page';
} else {
	$home = 'home page';
}
p3_o( 'modify_masthead_on', "checkbox{$static}|masthead_on_index|modified|$home|masthead_on_single|modified|individual post pages|masthead_on_pages|modified|static WordPress \"Pages\"|masthead_on_archive|modified|archive, category, author, and search", 'modify masthead on which types of pages' );
p3_o( 'modified_masthead_display', 'radio|none|no masthead on modified pages|image|static image on modified page-types');
p3_o( 'modified_masthead_image', 'select' . $img_options, 'choose one of your uploaded masthead images to show on modified page-types' );
p3_stop_multiple();

// flash header advanced
p3_start_multiple( 'Masthead slideshow options' );
p3_o( 'flash_speed', 'slider|1|30| seconds|0.5', 'hold time (in seconds) each slideshow image is shown' );
p3_o( 'flash_fadetime', 'slider|0|6| seconds|0.2', 'time of transition effect between slideshow images' );
p3_o( 'flash_order', 'radio|random|play images in random order|sequential|play images in sequential order' );
p3_o( 'flash_loop', 'radio|yes|loop images|no|stop on last image' );
p3_o( 'flashheader_transition_effect', 'radio|1|cross-fade|2|fade to bg color then to image|3|hold then slide horizontally|4|hold then slide vertically|5|steady horizontal slide', 'image transition effect' );
p3_o( 'flashheader_bg_color', 'color|optional', 'background color of flash slideshow' );
p3_stop_multiple();


$masthead_title = '
	<span class="masthead-conditional mc-static">Static masthead image</span>
	<span class="masthead-conditional mc-random">Masthead image %NUM%</span>
	<span class="masthead-conditional mc-slideshow">Masthead slideshow image %NUM%</span>
	<span class="masthead-conditional mc-custom">Masthead flash fallback image</span>';
	
$masthead_comment = '
	<span class="masthead-conditional mc-static">This is your single, static masthead image.</span>
	<span class="masthead-conditional mc-random">This is one of your randomly-displayed masthead images.</span>
	<span class="masthead-conditional mc-slideshow">This is one of your masthead slideshow images.</span>
	<span class="masthead-conditional mc-custom">This is your fallback masthead image, displayed if the user does not have flash enabled. The dimensions of this image must be exactly the same as your custom flash movie.</span> ' . p3_masthead_height_explain();


// print masthead images	
for ( $i = 1; $i <= MAX_MASTHEAD_IMAGES; $i++ ) {
	$numbered_masthead_title = str_replace( '%NUM%', $i, $masthead_title );
	p3_linked_image( 'masthead_image' . $i, $numbered_masthead_title, $masthead_comment );
}
p3_add_image_upload_btn( 'Masthead' );
p3_file( 'masthead_custom_flash', 'Masthead custom flash movie', 'Custom flash movie (in .swf format) to replace masthead image area. Must be exactly the same dimensions as the fallback image' );
p3_end_option_subgroup();

/* custom option function for re-ordering masthead images */
function p3_masthead_reorder_option() {
	global $mastheads_array;
	foreach( $mastheads_array as $image ) {
			$masthead_img_list .= '<li id="' . $image . '">';
			$masthead_img_list .= '<img src="' . p3_imageurl( $image, NO_ECHO ) . '" /></li>'."\n";
		}
	return <<<HTML
	<input type="hidden" name="masthead_order_string" value="" id="masthead_order_string" size="180"><br />
	<input type="hidden" name="masthead_order_reordered" value="false" id="masthead_order_reordered">
	<ul id="masthead_order">
		$masthead_img_list
	</ul>
	<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		jQuery('#masthead_order_reordered').val('false');
		jQuery('#masthead_order').sortable({
			opacity: 0.4,
			scroll: true,
			containment: '#masthead_order',
			update: function() {
				jQuery('#masthead_order_reordered').val('true');
				jQuery('#masthead_order_string')
					.val(jQuery('#masthead_order')
					.sortable('serialize',{key:'p3_masthead_order[]'}));
			}
		});
	});
	</script>
HTML;
}


/* Reorder Masthead Images */
if ( count( $mastheads_array ) > 1 ) {
	p3_option_subgroup( 'reorder-masthead' );
	p3_o( 'masthead_reorder', 'function|p3_masthead_reorder_option', 'drag your masthead images to reorder them' );
	p3_end_option_subgroup();
}

?>