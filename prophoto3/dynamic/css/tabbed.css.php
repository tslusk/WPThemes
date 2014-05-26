<?php
/* ----------------------------------------------------------- */	
/* ------------------- TABBED COMMENT LAYOUT ----------------- */
/* ----------------------------------------------------------- */	
extract( p3_general_css_vars() );



/* tabbed comment layout specific vars */
$scrollbox_css = p3_comments_scrollbox_css( '.comments-body' );

define( 'TABBED_ICON_HEIGHT', 12 ); // height of post-interact icons in pixels
// font size taller than icons, no extra padding needed
if ( TABBED_ICON_HEIGHT <= p3_get_option( 'comments_header_link_font_size' ) ) {
	$post_interact_icon_padding_top = 0;
// font size shorter than icons, add padding to ensure icons show completely
} else {
	$post_interact_icon_padding_top = ( TABBED_ICON_HEIGHT - p3_get_option( 'comments_header_link_font_size' ) ) / 2;
}
$tabbed_cmt_header_fontlink_css  = p3_link_css( 'comments_header_link', '.comments-header', TRUE, TRUE );



// vertically center the post-interact links
$comment_header_height  = 42;
$post_interact_fontsize = 
	p3_cascading_style( 'comments_post_interaction_link_font_size', 'comments_header_link_font_size' );
if ( $comment_header_height > $post_interact_fontsize ) {
	$post_interact_top_pad = ( $comment_header_height - $post_interact_fontsize ) / 2;
} else {
	$post_interact_top_pad = 0;
}


// some extra padding if by-author is removed
if ( p3_test( 'comments_show_postauthor', 'false' ) ) echo ".comments-count { margin-left: 1em; }\n";


/* echo out tabbed comment CSS */

echo <<<CSS
		
/* tabbed comment layout */		
.comments-body div.p3comment {
	margin-bottom:0;
	line-height:1.1em;
}
.entry-comments {
	border: 1px solid #cac9c9;
	border-bottom:none;
	margin-left: {$content_margin}px;
	margin-right: {$content_margin}px;
}
.comments-header {
	background: #f4f4f4 url({$p3_theme_url}/images/borderCAC9C9.gif) repeat-x bottom;
}
.comments-header p {
	margin-bottom:0;
	line-height:1.3em;
}

$tabbed_cmt_header_fontlink_css
.postedby {
	float:left;
	display:inline;
	padding: 14px 16px 13px 16px;
}
.comments-count, .comments-count div {
	float:left;
	display:inline;
}
.comments-count {
	background: url({$p3_theme_url}/images/tab1-left.jpg) no-repeat bottom left;
}
.comments-count div {
	background: url({$p3_theme_url}/images/tab1-right.jpg) no-repeat bottom right;
}
.comments-count-active .comments-count {
	background: url({$p3_theme_url}/images/tab2-left.jpg) no-repeat bottom left;
}
.comments-count-active .comments-count div {
	background: url({$p3_theme_url}/images/tab2-right.jpg) no-repeat bottom right;
}
.comments-count p {
	line-height:1.3em;
	float:left;
	display:inline;
	background: none;
	padding: 13px 21px 13px 34px;
	margin:1px 0 0 0;
	background: url({$p3_theme_url}/images/comments-closed.gif) no-repeat 21px 54%;
}
.comments-count-active .comments-count p {
	background: url({$p3_theme_url}/images/comments-open.gif) no-repeat 21px 54%;
}
.post-interact a {
	display:block;
	line-height:1em;
}
.post-interact {
	float:right;
	display:inline;
	padding:{$post_interact_top_pad}px 16px 0 0;
	line-height: 1.3em;
}
.post-interact-div {
	padding-top:{$post_interact_icon_padding_top}px;
	margin-left:14px;
	float:left;
}
.addacomment {
	background: url({$p3_theme_url}/images/comment.gif) no-repeat left center;
	padding-left:15px
}
.emailafriend {
	background: url({$p3_theme_url}/images/email.gif) no-repeat left center;
	padding-left:20px;
}
.linktothispost {
	background: url({$p3_theme_url}/images/link.gif) no-repeat left center;
	padding-left:18px;
}
.comments-body {
	border-bottom:1px solid #cac9c9;
}
$scrollbox_css
.comments-body-inner {

}
.comments-body-inner {
	padding:15px;
}	
.p3comment {
	padding:.4em .5em;
}
.comment-author {
	margin-right:.2em;
}
CSS;
?>