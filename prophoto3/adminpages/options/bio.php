<?php
/* ----------------------- */
/* -- BIO AREA OPTIONS --- */
/* ----------------------- */

echo <<<HTML
<style type="text/css" media="screen">
#tab-section-bio-link .option, #subgroup-nav {
	display:none;
}
#tab-section-bio-link #upload-row-bio_separator {
	display:block;
}
#tab-section-bio-link #upload-row-bio_separator.start-hidden {
	display:none;
}
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	function p3_bio_display_ux( choice ) {
		var show_hide = jQuery('#tab-section-bio-link .option, #subgroup-nav').not('#upload-row-bio_separator');
		if ( choice == 'yes' ) {
			show_hide.show();
		} else {
			show_hide.hide();
		}
		jQuery('#bio_include-option-section').show();
	}
	var bio_display = jQuery('#bio_include-option-section input:checked').val();
	p3_bio_display_ux( bio_display );
	jQuery('#bio_include-option-section input').click(function(){
		p3_bio_display_ux( jQuery(this).val() );
	});
});
</script>
HTML;

$subgroups =  array(
	'general'    => 'General',
	'background' => 'Background',
	'biopic'     => 'Picture',
	'appearance' => 'Appearance',
	'content'    => 'Content',
);


p3_subgroup_tabs( $subgroups );
p3_option_header('Bio Area Options', 'bio' );


/* bio general subgroup */
p3_option_subgroup( 'general' );

// include / don't include bio area
p3_o( 'bio_include', 'radio|yes|Include bio area|no|Do not include bio area', '', 'Include bio area?' );

// hidden (minimized) bio
p3_start_multiple( 'Bio area display type' );
p3_o( 'use_hidden_bio', 'radio|no|shown normally|yes|minimized', 'Bio area shown normally, or "minimized" to an "About Me" link in the navigation menu' );
p3_o( 'hidden_bio_link_text', 'text|20', 'text of nav link to show/hide bio section' );
p3_stop_multiple();

// bio on which pages?
p3_start_multiple( 'Bio on page types' );
if ( P3_HAS_STATIC_HOME ) {
	$static = '|bio_front_page|on|Static front page';
	$home = 'Posts page';
} else {
	$home = 'Home';
}
p3_o( 'bio_pages_options', "checkbox{$static}|bio_index|on|$home|bio_single|on|Single post|bio_pages|on|Pages|bio_archive|on|Archive|bio_category|on|Category|bio_tag|on|Tag archive|bio_search|on|Search results|bio_author|on|Author archives", 'choose which types of pages you want the bio to appear on' );
p3_o( 'bio_pages_minimize', 'radio|none|no bio section on unchecked|minimized|bio section minimized on unchecked', 'on unchecked pages, choose to either not show bio at all, or have bio "minimized" to a "about me" link in nav menu' );
p3_o( 'bio_pages_minimize_text', 'text|25', 'text for minimized "About Me" link for non-checked pages' );
p3_stop_multiple();

p3_end_option_subgroup();



/* background subgroup */
p3_option_subgroup( 'background' );

// main bg
p3_bg( 'bio_bg', 'Bio area main background' );

// inner bg
p3_bg( 'bio_inner_bg', 'Bio area optional inner background image' );

// bio separator (border or image)
p3_start_multiple( 'Separator below bio area' );
p3_o( 'bio_border', 'radio|border|custom line below bio area|image|upload image as a bottom border|noborder|no border or image' );
p3_border_group( array( 'key' => 'bio_border', 'comment' => 'custom line appearance' ) );
p3_stop_multiple();

// bio separator image
p3_image( 'bio_separator', 'Bio section custom separator', 'Upload a custom image to be displayed centered beneath your bio.' );

p3_end_option_subgroup();



/* biopic subgroup */
p3_option_subgroup( 'biopic' );

echo <<<HTML
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	// process biopic display options
	var saved_biopic_choice = jQuery('#biopic_display-individual-option input:checked').val();
	p3_process_biopic_display_choice(saved_biopic_choice);
	jQuery('#biopic_display-individual-option input').click(function(){
		var biopic_click = jQuery(this).val();
		if (biopic_click == 'off') {
			jQuery('#biopic_align-individual-option, #biopic_border_width-option-section').hide();
		} else {
			jQuery('#biopic_align-individual-option, #biopic_border_width-option-section').show();
		}
		p3_process_biopic_display_choice(biopic_click);
	});
	
	// biopic border live preview
	jQuery('#p3-input-biopic_border_width').blur(function(){
		var biopic_border_width = jQuery(this).val();
		jQuery('#p3_image_biopic1').css('border-width', biopic_border_width+'px');
	});
});
function p3_process_biopic_display_choice( choice ) {
	if ( 'normal' == choice ) {
		jQuery('#upload-row-biopic1').fadeIn();
		jQuery('#subgroup-biopic .upload-row, #subgroup-biopic .add-image-upload').not('#upload-row-biopic1').hide();
	} else if ( 'random' == choice ) {
		jQuery('#subgroup-biopic .empty').hide();
		jQuery('#subgroup-biopic .upload-row').not('.empty').show();
		jQuery('#subgroup-biopic .empty:first, #subgroup-biopic .add-image-upload').show();
	} else if ( 'off' == choice ) {
		jQuery('#subgroup-biopic .upload-row, #subgroup-biopic .add-image-upload').hide();
	}
}
</script>
HTML;

// biopic display options
p3_start_multiple( 'Bio picture' );
p3_o( 'biopic_display', 'radio|normal|Always show same bio picture|random|Random bio picture on each page load|off|Do not show bio picture' );
p3_o( 'biopic_align', 'radio|left|on left side of bio area|right|on right side of bio area', 'Alignment of bio picture', 'choose left or right alignment for your bio picture' );
p3_stop_multiple();


// bio picture border options
p3_start_multiple( 'Border around bio picture' );
p3_o( 'biopic_border', 'radio|on|show border|off|no border', 'show/hide custom border around bio picture' );
p3_border_group( array( 'key' => 'biopic_border', 'comment' => 'biopic border appearance' ) );
p3_stop_multiple();


// bio pictures
p3_image('biopic1', 'Bio picture' );
for ( $i = 2; $i <= MAX_BIO_IMAGES; $i++ ) { 
	p3_image( 'biopic' . $i, "Bio picture $i", 'Bio picture #' . $i . '. Must match the dimensions of the first bio picture to display correctly.' );
}
p3_add_image_upload_btn( 'biopic' );
p3_end_option_subgroup(); // END biopic



/* appearance subgroup */
p3_option_subgroup( 'appearance' );

// bio area padding & margins
p3_start_multiple( 'Bio area spacing' );
p3_o( 'bio_top_padding', 'slider|0|100| px', 'spacing above bio content' );
p3_o( 'bio_btm_padding', 'slider|0|100| px', 'spacing below bio content' );
p3_o( 'bio_gutter_width', 'text|3', 'override default spacing (in pixels) between bio content columns' );
p3_o( 'bio_lr_padding', 'text|3', 'override default spacing (in pixels) on left/right of bio content' );
p3_stop_multiple();


// widget spacing
p3_start_multiple( 'Bio content (widget) spacing' );
p3_o( 'bio_widget_margin_btm', 'text|3', 'spacing (in pixels) below bio area content chunks (widgets)' );
p3_o( 'bio_headline_margin_btm', 'text|3', 'override space (in pixels) below bio content (widget) headlines' );
p3_stop_multiple();


// bio headlines appearance
p3_font_group( array(
	'key' => 'bio_header',
	'title' => 'Bio area headline text appearance',
	'inherit' => 'all',
) );


// bio text appearance
p3_font_group( array(
	'key' => 'bio_para',
	'title' => 'Bio area text appearance',
	'inherit' => 'all',
) );


// bio text appearance
p3_font_group( array(
	'key' => 'bio_link',
	'title' => 'Bio area link appearance',
	'inherit' => 'all',
) );

p3_end_option_subgroup();



/* bio content subgroup */
p3_option_subgroup( 'content' );

p3_o( 'bio_content', 'note', 'All Bio content is handled by <strong>Widgets</strong>, which you can configure and edit on the <a href="' . admin_url( 'widgets.php' ) . '">Widgets Page</a>. For more information, see our tutorials: <a href="' . UNDERSTANDING_WIDGETS_TUT . '">understanding widgets</a>, and <a href="' . BIO_WIDGETS_TUT . '">customizing your bio area using widgets</a>.' );



p3_end_option_subgroup();
?>