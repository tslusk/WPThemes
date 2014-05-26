<?php

/* ----------------------------------------------------------- */
/* ------------------- BOXY COMMENT LAYOUT ------------------- */
/* ----------------------------------------------------------- */
extract( p3_general_css_vars() );

$comments_header_bg_color   = p3_color_css_if_bound( 'comments_header_bg_color' );
/* boxy comment layout specific vars */

$boxy_header_fontsize_big   = ( p3_get_option( 'comments_header_link_font_size' ) * 1.2 );
$boxy_header_fontsize_small = ( p3_get_option( 'comments_header_link_font_size' ) * .85 );
$comments_area_border_color = p3_get_option( 'comments_area_border_color' );
$comments_area_border_style = p3_get_option( 'comments_area_border_style' );
$comments_area_border_width = p3_get_option( 'comments_area_border_width' );
$comments_area_border_rule  = "$comments_area_border_style {$comments_area_border_width}px $comments_area_border_color";

/* echo out boxy comment CSS */

		echo <<<CSS
		
/* boxy comments */
.comments-header-left-side-wrap {
	float:none !important;
}
.comments-header div.post-interact-div {
	margin-left:0 !important;
}
.entry-comments .comments-header p {
	font-size:{$boxy_header_fontsize_big}px !important;
}
.entry-comments .comments-header p.postedby, 
.entry-comments .comments-header p.postedby a, 
.comments-header p.post-interact a {
	font-size:{$boxy_header_fontsize_small}px !important;
}
.comments-body h3 {
	display:none;
}
.entry-comments {
	border:$comments_area_border_rule;
	height:150px;
	margin-left: {$content_margin}px;
	margin-right: {$content_margin}px;
}
.entry-comments p {
	margin-bottom:0;
}
.comments-header {
	font-size:14px;
	width:200px;
	border-right:$comments_area_border_rule;
	float:left;
	height:150px !important;
	max-height:150px;
	text-align:center;
	display:table;
	#position:relative; 
	overflow:hidden;
	$comments_header_bg_color
}
#content .comments-header-left-side-wrap {
	display:block;
}
* html .comments-header {
	margin-right:-3px;
}
.comments-header-inner {
	#position:absolute;
	#top: 50%;
	#left: 0;
	display:block;
	#width:200px;
	display:table-cell;
	vertical-align:middle;
}
.comments-header-inner-inner {
	#position: relative;
	#top: -50%;
}
.comments-count {
	margin:8px 0 4px 0;
}
.comments-body {
	height:137px;
	border-left:none;
	overflow:auto;
	padding:5px 8px 8px 8px;
}
.comments-body div.p3comment {
	margin-bottom:0;
}
p.post-interact {
	line-height:1.2em;
}
.post-interact span a {
	padding:3px 0;
	text-decoration:none;
	display:block;
	margin:0px 30px 0 30px;
}
.p3comment {
	padding: 5px;
}
CSS;
?>