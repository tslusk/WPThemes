<?php
/* ------------------------------------------------------------ */
/* ------------------ MINIMA COMMENTS LAYOUT ------------------ */
/* ------------------------------------------------------------ */
extract( p3_general_css_vars() );



/* overall styles */
$scrollbox_css              = p3_comments_scrollbox_css( '.comments-body-inner-wrap' );
$comments_header_lr_padding = p3_get_option( 'comments_header_lr_padding' );
$comments_header_tb_padding = p3_option_or_val( 'comments_header_tb_padding', '0.6em', 'px' );
$minima_count_color_css     = p3_color_css_if_bound( 'comments_header_link_font_color', '' );
$minima_no_cmt_color        = ( p3_optional_color_bound( 'comments_header_nonlink_font_color' ) )
						      ? p3_get_option( 'comments_header_nonlink_font_color' )
						      : p3_get_option( 'gen_font_color' );
$minima_hdr_linkcolor       = p3_get_option( 'comments_header_link_font_color' );
$comments_header_bg_color   = ( p3_optional_color_bound( 'comments_header_bg_color' ) )
                              ? 'background-color:'  . p3_get_option( 'comments_header_bg_color' ) . ';'
                              : '';


/* border lines */
// border line above comment header
if ( p3_test( 'comment_header_border_top', 'on' ) ) {
	$minima_header_top_border_css = p3_border_css( 'comments_area_border', 'top' );
}

// border line below comment header
if ( p3_test( 'comment_header_border_bottom', 'on' ) ) {
	$minima_header_bottom_border_css = p3_border_css( 'comments_area_border', 'bottom' );
}

// left/right border lines
if ( !p3_test( 'comments_lr_margin_switch', 'set' ) || p3_get_option( 'comments_lr_margin' ) > 0 ) {
	$comments_area_lr_border_css = p3_border_css( 'comments_area_border', 'left' ) . "\n" . p3_border_css( 'comments_area_border', 'right' );
}

// border below entire comment area, set to match top bottom border
$minima_bottom_border = p3_border_css( 'comments_area_border', 'bottom' );



/* post interaction icons */

// add a comment icon
$addacomment_icon_height  = p3_imageheight( 'comments_addacomment_icon', NO_ECHO );
$addacomment_icon_width   = p3_imagewidth( 'comments_addacomment_icon', NO_ECHO );
$addacomment_icon_padding = ( p3_image_exists( 'comments_addacomment_icon' ) )
					        ? $addacomment_icon_width + 4 // gives icon room to breathe
					        : '';
$addacomment_icon_url	  = p3_imageurl( 'comments_addacomment_icon', NO_ECHO );
$addacomment_icon_bg_img  = ( p3_image_exists( 'comments_addacomment_icon' ) )
							? "background: url('{$addacomment_icon_url}') no-repeat left center;"
							: '';

// link to this post icon
$linktothispost_icon_height  = p3_imageheight( 'comments_linktothispost_icon', NO_ECHO );
$linktothispost_icon_width   = p3_imagewidth( 'comments_linktothispost_icon', NO_ECHO );
$linktothispost_icon_padding = ( p3_image_exists( 'comments_linktothispost_icon' ) )
					           ? $linktothispost_icon_width + 4 // gives icon room to breathe
					           : '';
$linktothispost_icon_url	 = p3_imageurl( 'comments_linktothispost_icon', NO_ECHO );
$linktothispost_icon_bg_img  = ( p3_image_exists( 'comments_linktothispost_icon' ) )
						   	   ? "background: url('{$linktothispost_icon_url}') no-repeat left center;"
							   : '';

// email a friend icon
$emailafriend_icon_height  = p3_imageheight( 'comments_emailafriend_icon', NO_ECHO );
$emailafriend_icon_width   = p3_imagewidth( 'comments_emailafriend_icon', NO_ECHO );
$emailafriend_icon_padding = ( p3_image_exists( 'comments_emailafriend_icon' ) )
					        ? $emailafriend_icon_width + 4 // gives icon room to breathe
					        : '';
$emailafriend_icon_url	   = p3_imageurl( 'comments_emailafriend_icon', NO_ECHO );
$emailafriend_icon_bg_img  = ( p3_image_exists( 'comments_emailafriend_icon' ) )
							? "background: url('{$emailafriend_icon_url}') no-repeat left center;"
							: '';


/* custom post interaction images */

// custom add a comment image
$addacomment_image_height     = p3_imageheight( 'comments_addacomment_image', NO_ECHO );
$addacomment_image_width      = p3_imagewidth( 'comments_addacomment_image', NO_ECHO );
$addacomment_image_url	      = p3_imageurl( 'comments_addacomment_image', NO_ECHO );
$addacomment_image_bg_img     = "background: url('{$addacomment_image_url}') no-repeat left top;";

// link to this post image
$linktothispost_image_height  = p3_imageheight( 'comments_linktothispost_image', NO_ECHO );
$linktothispost_image_width   = p3_imagewidth( 'comments_linktothispost_image', NO_ECHO );
$linktothispost_image_url	  = p3_imageurl( 'comments_linktothispost_image', NO_ECHO );
$linktothispost_image_bg_img  = "background: url('{$linktothispost_image_url}') no-repeat left top;";

// email a friend image
$emailafriend_image_height    = p3_imageheight( 'comments_emailafriend_image', NO_ECHO );
$emailafriend_image_width     = p3_imagewidth( 'comments_emailafriend_image', NO_ECHO );
$emailafriend_image_url	      = p3_imageurl( 'comments_emailafriend_image', NO_ECHO );
$emailafriend_image_bg_img    = "background: url('{$emailafriend_image_url}') no-repeat left top;";

// array of image heights
$custom_image_heights = array( 
	$addacomment_image_height, 
	$linktothispost_image_height, 
	$emailafriend_image_height
);


/* vertically center header items */
// below function extracts to all the variables needed to do the complex
// vertical centering of all of the header elements
extract( p3_minima_comment_header_css( $custom_image_heights ) );






/* echo out minima comment CSS */

echo <<<CSS

.entry-comments {
	$comments_area_lr_border_css
}
		
/* minima comments */
{$shorter_side_selector} {
	padding-top: {$shorter_side_top_padding}px;
}
.addacomment {
	padding-top: {$addacomment_pad_top}px;
}
.linktothispost {
	padding-top: {$linktothispost_pad_top}px;
}
.emailafriend {
	padding-top: {$emailafriend_pad_top}px;
}
.comments-header-left-side-wrap $left_side_pad_selector {
	margin-top: {$left_side_inner_pad}px;
}

.comments-count p {
	$minima_count_color_css
}
.comments-count p.no-comments {
	color:{$minima_no_cmt_color};
}
.comments-count {
	color:{$minima_hdr_linkcolor};
	float: left;
}
.entry-comments span.hide-text {
	display:none;
}
.entry-comments span.show-text {
	display:inline;
}
.comments-count-active span.hide-text {
	display:inline !important;
}
.comments-count-active span.show-text {
	display:none !important;
}
#show-hide-button {
	float: left;
	width: 21px;
	height: 21px;
	margin: 0 0 0 1em;
	background: url('{$p3_theme_url}/images/minima-comments-show-hide.png') no-repeat left top;
}
.comments-count-active #show-hide-button {
	background-position: left bottom;
}
.entry-comments {
	$minima_bottom_border
}
.entry-comments p {
	margin-bottom:0;
}
.comments-header {
	$comments_header_bg_color
	$minima_header_top_border_css
	border-bottom: none;
	padding: $comments_header_tb_padding {$comments_header_lr_padding}px;
}
.comments-count-active .comments-header {
	$minima_header_bottom_border_css
}
.comments-header p {
	line-height: 1;
	float: left;
}
.postedby {
	margin-right: 15px;
}
.comments-header div.post-interact {
	float:right;
}
.comments-header .post-interact-div {
	float: left;
	margin-left: 14px;
}
.post-interact a {
	line-height: 1;
	display:block;
}
$scrollbox_css
.p3comment {
	line-height:1.2em;
}
$comments_header_vertical_centering
$comments_count_vertical_centering
CSS;


/*----------------------------------*/
/* Begin Conditional Minima Styles  */
/*----------------------------------*/

// If using text & icons (with or without buttons)
if ( !p3_test( 'comments_post_interact_display', 'images' ) ) {

echo <<<CSS

.addacomment a {
	padding-left: {$addacomment_icon_padding}px;
	$addacomment_icon_bg_img
}
.linktothispost a {
	padding-left: {$linktothispost_icon_padding}px;
	$linktothispost_icon_bg_img
}
.emailafriend a {
	padding-left: {$emailafriend_icon_padding}px;
	$emailafriend_icon_bg_img
}

CSS;
}

// If using ProPhoto 3 button
if ( p3_test( 'comments_post_interact_display', 'button' ) ) {

echo <<<CSS

.post-interact a {
	padding-top: 0.6em;
	padding-bottom: 0.6em;
	margin: 0 0.6em;
}
.button-outer {
	border: 1px solid;
	border-color: #c0bebe #c0bebe #959595 #959595;
}
.button-inner {
	border: 1px solid #ffffff;
	background: #ffffff url('{$p3_theme_url}/images/post-interaction-button-bg.jpg') repeat-x left bottom;
}

CSS;

// If using custom images
} else if ( p3_test( 'comments_post_interact_display', 'images' ) ) {
	$addacomment_text_indent = ( p3_image_exists( 'comments_addacomment_image' ) )
	                           ? "-99999"
	                           : 0;
echo <<<CSS

.addacomment a {
	width: {$addacomment_image_width}px;
	height: {$addacomment_image_height}px;
	$addacomment_image_bg_img
	text-indent: {$addacomment_text_indent}px;
}
.linktothispost a {
	width: {$linktothispost_image_width}px;	
	height: {$linktothispost_image_height}px;
	$linktothispost_image_bg_img
	text-indent: -99999px;
}
.emailafriend a {
	width: {$emailafriend_image_width}px;
	$emailafriend_image_bg_img
	text-indent: -99999px;
	height: {$emailafriend_image_height}px;
}

CSS;
}
?>