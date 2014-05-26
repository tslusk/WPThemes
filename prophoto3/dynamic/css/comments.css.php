<?php
/* --------------------------------------- */
/* --------------------------------------- */
/* ---- custom css for theme comments ---- */
/* --------------------------------------- */
/* --------------------------------------- */

/* load correct layout-specific comment styles */
require( p3_get_option( 'comments_layout' ) . '.css.php' );


/* general comment css for all layouts */
// general vars for heredoc legibility
extract( p3_general_css_vars() );
$gen_font_size 			        = p3_get_option( 'gen_font_size' );
$comments_area_bg_color         = p3_color_css_if_bound( 'comments_area_bg_color' );
$comment_bg_color               = p3_color_css_if_bound( 'comments_comment_bg_color' );
$comments_body_bg_color         = p3_color_css_if_bound( 'comments_comment_outer_bg_color' );
$comments_post_interact_spacing = p3_get_option( 'comments_post_interact_spacing' );
$comments_body_bg_css           = p3_bg_css( 'comments_comment_outer_bg', '.comments-body' );

// overall comment area margin
$comment_lr_margin = ( p3_test( 'comments_lr_margin_switch', 'set' ) ) 
	? p3_get_option( 'comments_lr_margin' ) : $content_margin;

// comment timestamp  
$cmt_timestamp_float     = ( p3_test( 'comment_timestamp_display', 'right' ) ) ? 'float:right;' : '';
$cmt_timestamp_weight    = p3_option_or_val( 'comments_comment_timestamp_font_weight', '400' );
$cmt_timestamp_style     = p3_option_or_val( 'comments_comment_timestamp_font_style', 'normal' );
$cmt_timestamp_transform = p3_option_or_val( 'comments_comment_timestamp_text_transform', 'none' );
$cmt_timestamp_color     = p3_get_option( 'comments_comment_timestamp_font_color' );


// general individual comment styling 
$cmt_font_size        = ( p3_test( 'comments_comment_font_size' ) ) 
						? p3_get_option( 'comments_comment_font_size' ) 
						: $gen_font_size;

$cmt_font_family      = ( p3_test( 'comments_comment_font_family' ) ) 
					    ? 'font-family:' . p3_get_option( 'comments_comment_font_family' ) . ';' 
					    : '';
					
$cmt_font_style       = ( p3_test( 'comments_comment_font_style' ) ) 
					    ? 'font-style:' . p3_get_option( 'comments_comment_font_style' ) . ';' 
					    : '';

$cmt_font_weight	  = ( p3_test( 'comments_comment_font_weight' ) )
						? 'font-weight:' . p3_get_option( 'comments_comment_font_weight' ) . ';'
						: '';
					
$cmt_font_transform   = ( p3_test( 'comments_comment_text_transform' ) ) 
					    ? 'text-transform:' . p3_get_option( 'comments_comment_text_transform' ) . ';' 
					    : '';
					
$cmt_color            = ( p3_optional_color_bound( 'comments_comment_font_color' ) ) 
					    ? 'color:' . p3_get_option( 'comments_comment_font_color' ) . ';' 
					    : '';
					
$cmt_link_color       = ( p3_optional_color_bound( 'comments_comment_link_font_color' ) ) 
					    ? 'color:' . p3_get_option( 'comments_comment_link_font_color' ) . ';'
					    : 'color:' . p3_get_option( 'gen_link_font_color' ) . ';';

$cmt_link_weight      = ( p3_test( 'comments_comment_link_font_weight' ) )
						? 'font-weight:' . p3_get_option( 'comments_comment_link_font_weight' ) . ';'
						: '';

$cmt_link_style       = ( p3_test( 'comments_comment_link_font_style' ) )
						? 'font-style:' . p3_get_option( 'comments_comment_link_font_style' ) . ';'
						: '';
						
$cmt_link_transform   = ( p3_test( 'comments_comment_link_text_transform' ) )
						? 'text-transform:' . p3_get_option( 'comments_comment_link_text_transform' ) . ';'
						: '';
						
$cmt_link_decoration  = ( p3_test( 'comments_comment_link_decoration' ) )
						? 'text-decoration:' . p3_get_option( 'comments_comment_link_decoration' ) . ';'
						: '';

$cmt_link_hoverdec    = ( p3_test( 'comments_comment_link_hover_decoration' ) )
						? 'text-decoration:' . p3_get_option( 'comments_comment_link_hover_decoration' ) . ';'
						: '';

$cmt_border_bottom	  = ( p3_test( 'comments_comment_border_onoff', 'on' ) )
						? p3_border_css( 'comments_comment_border', 'bottom' )
						: '';
						
$cmt_line_height        = ( p3_test( 'comments_comment_line_height' ) ) 
						? 'line-height:' . p3_get_option( 'comments_comment_line_height' )  . '' : '';

$cmt_vertical_spacing   = p3_get_option( 'comment_vertical_separation' );
$cmt_vertical_padding   = p3_get_option( 'comment_vertical_padding' );
$cmt_horizontal_padding = p3_get_option( 'comment_horizontal_padding' );
$cmnts_lr_margin        = p3_get_option( 'comments_body_lr_margin' );
$cmnts_tb_margin        = p3_get_option( 'comments_body_tb_margin' );



// alternate individual comment styling 
$cmt_alt_time_color   = p3_color_css_if_bound( 'comments_timestamp_alt_color', '' );
						
$cmt_alt_bg_color	  = ( p3_optional_color_bound( 'comments_comment_alt_bg' ) ) 
						? 'background-color:' . p3_get_option( 'comments_comment_alt_bg' ) . ';'
						: '';

$cmt_alt_text_color	  = ( p3_optional_color_bound( 'comments_comment_alt_text_color' ) ) 
						? 'color:' . p3_get_option( 'comments_comment_alt_text_color' ) . ';'
						: '';

$cmt_alt_link_color   = ( p3_optional_color_bound( 'comments_comment_alt_link_color' ) )
						? 'color:' . p3_get_option( 'comments_comment_alt_link_color' ) . ' !important;'
						: '';


// by post author comment styling 
$cmt_auth_time_color  = ( p3_optional_color_bound( 'comments_timestamp_byauthor_color' ) )
						? 'color:' . p3_get_option( 'comments_timestamp_byauthor_color' ) . ';'
						: '';

$cmt_auth_bg_color    = ( p3_optional_color_bound( 'comments_comment_byauthor_bg' ) )
						? 'background:' . p3_get_option( 'comments_comment_byauthor_bg' ) . ';'
						: '';

$cmt_auth_text_color  = ( p3_optional_color_bound( 'comments_comment_byauthor_text_color' ) )
						? 'color:' . p3_get_option( 'comments_comment_byauthor_text_color' ) . ';'
						: '';

$cmt_auth_link_color  = ( p3_optional_color_bound( 'comments_comment_byauthor_link_color' ) )
						? 'color:' . p3_get_option( 'comments_comment_byauthor_link_color' ) . ' !important;'
						: '';


// comment awaiting moderation styles 
$cmt_moderation_style = ( p3_test( 'comments_moderation_style' ) )
						? 'font-style:' . p3_get_option( 'comments_moderation_style' ) . ';'
						: '';

$cmt_moderation_color = ( p3_optional_color_bound( 'comments_moderation_color' ) )
						? 'color:' . p3_get_option( 'comments_moderation_color' ) . ';'
						: '';

$cmt_mod_alt_color    = ( p3_optional_color_bound( 'comments_moderation_alt_color' ) )
						? 'color:' . p3_get_option( 'comments_moderation_alt_color' ) . ';'
						: '';


// comments header 
$cmt_header_font_css = p3_font_css( 'comments_header_link', '.comments-header .comments-header-left-side-wrap p', OPTIONAL_COLORS );
$cmt_header_fontlink_css     = p3_link_css( 'comments_header_link', '.comments-header .comments-header-left-side-wrap', FALSE, OPTIONAL_COLORS );
$comments_count_color = ( p3_optional_color_bound( 'comments_header_link_font_color' ) ) ? p3_get_option( 'comments_header_link_font_color' ) : p3_get_option( 'gen_link_font_color' );
$post_interact_font_link_css = p3_link_css( 'comments_post_interaction_link', '.comments-header .post-interact ', TRUE );
$comments_header_bg_css   = ( p3_test( 'comments_layout', 'tabbed' ) ) ? '' : p3_bg_css( 'comments_header_bg', '.comments-header' );
$cmt_header_nonlink_color = p3_color_css_if_bound( 'comments_header_font_color', '' );


// gravatars
$grav_align          = p3_get_option( 'gravatar_align' );
$grav_align_opposite = ( p3_test( 'gravatar_align', 'right' ) ) ? 'left' : 'right';
$grav_padding        = p3_get_option( 'gravatar_padding' );
$grav_offset         = p3_get_option( 'gravatar_size' ) + $grav_padding;

if ( p3_test( 'gravatars', 'on' ) ) {
	$gravatar_css = <<<CSS

.avatar {
	float:$grav_align;
	margin-{$grav_align_opposite}:{$grav_padding}px;
	margin-bottom:{$grav_padding}px;
	margin-top:3px;
}
.comment-text {
	padding-{$grav_align}:{$grav_offset}px;
}

CSS;
}




/* echo out general comment CSS */

echo <<<CSS

/* -- comments css -- */

.entry-comments {
	clear:both;
	margin-top:16px;
	margin-left:{$comment_lr_margin}px;
	margin-right:{$comment_lr_margin}px;
	$comments_area_bg_color
}
body.single .entry-comments {
	margin-top: 15px;
	margin-bottom: 40px;
}
/* comments header */
.comments-header-left-side-wrap {
	float:left;
	display:inline;
}
$cmt_header_font_css
$comments_header_bg_css
$cmt_header_fontlink_css
$post_interact_font_link_css
#content .comments-header p, .comments-header .post-interact {
	$cmt_header_nonlink_color
}
#content .comments-header .comments-header-left-side-wrap .comments-count p {
	color:$comments_count_color;
}
.comments-header div.post-interact-div {
	margin-left:{$comments_post_interact_spacing}px;
}
.not-accepting-comments .addacomment {
	display:none;
}
/* general individual comments */
$comments_body_bg_css
.comments-body-inner {
	margin:{$cmnts_tb_margin}px {$cmnts_lr_margin}px;
}
.comments-body div.p3comment {
	$cmt_color
	clear:both; /* for floated gravatars */
	$comment_bg_color
	$cmt_border_bottom
	margin-bottom:{$cmt_vertical_spacing}px;
	padding:{$cmt_vertical_padding}px {$cmt_horizontal_padding}px;
}
.comments-body div.p3comment p {
	$cmt_color
	margin-bottom:0;
}
.comments-body div.p3comment,
.comments-body div.p3comment p, 
.comments-body div.p3comment a {
	font-size:{$cmt_font_size}px;
	$cmt_font_family
	$cmt_font_weight
	$cmt_font_style 
	$cmt_font_transform
	$cmt_line_height
}
.comments-body div.p3comment span.comment-author,
.comments-body div.alt span.comment-author,
.comments-body div.p3comment a:link,
.comments-body div.alt a:link,
.comments-body div.p3comment a:visited,
.comments-body div.alt a:visited,
.comments-body div.p3comment a:hover,
.comments-body div.alt a:hover {
	$cmt_link_color
	$cmt_link_weight
	$cmt_link_style
	$cmt_link_transform	
}
.comments-body div.p3comment a:link,
.comments-body div.alt a:link,
.comments-body div.p3comment a:visited,
.comments-body div.alt a:visited {
	$cmt_link_decoration 
}
.comments-body div.p3comment a:hover,
.comments-body div.alt a:hover {
	$cmt_link_hoverdec
}
.p3comment img.wp-smiley {
	height:{$cmt_font_size}px;
}
.last-comment {
	margin-bottom:0 !important;
	border-bottom-width:0 !important;
}
/* comment timestamp */
.comment-time {
	$cmt_timestamp_float
	color:$cmt_timestamp_color;
	font-weight: $cmt_timestamp_weight;
	font-style: $cmt_timestamp_style;
	text-transform: $cmt_timestamp_transform;
	margin-left:10px;
}
.comment-top .comment-time {
	margin-left:0;
}
.comment-top .comment-author span {
	padding:0 2px;
}
#content .alt .comment-time {
	$cmt_alt_time_color
}
.entry-comments .bypostauthor .comment-time {
	$cmt_auth_time_color
}
/* alt comments */
.comments-body div.alt {
	$cmt_alt_bg_color
}
.comments-body div.alt p {
	$cmt_alt_text_color
}
.comments-body .alt a:link {
	$cmt_alt_link_color
}
.comments-body .alt a:visited {
	$cmt_alt_link_color
}
/* by author comment styles */
.comments-body div.bypostauthor {
	$cmt_auth_bg_color
	$cmt_auth_text_color
}
.comments-body div.bypostauthor p {
	$cmt_auth_text_color
}
.comments-body div.bypostauthor a:link, 
.comments-body div.bypostauthor span.comment-author, 
.comments-body div.bypostauthor a:visited, 
.comments-body div.bypostauthor a:hover {
	$cmt_auth_link_color
}
/* comment awaiting moderation style */
.p3comment .unapproved {
	$cmt_moderation_style
	$cmt_moderation_color
}
.alt .unapproved {
	$cmt_mod_alt_color
}
/* add comment form styles */
.addcomment-holder {
	display:none;
	margin:0px {$content_margin}px;
}
.formcontainer {
	margin:0px {$content_margin}px;
}
.addcomment-holder .formcontainer {
	margin:0;
}
#commentform p {
	margin:18px 0 2px 0;
}
#commentform input#submit {
	margin-top:5px;
}
#addcomment-error {
	display:none;
	margin:20px 0;
}
#addcomment-error span {
	background:#fff;
	border:1px solid red;
	color:red;
	font-weight:bold;
	padding:4px;
	display:inline;
}
.cancel-reply {
	margin-left:5px;
}
$gravatar_css
CSS;


/* comment meta top */
if ( p3_test( 'comment_meta_display', 'above' ) ) {
	$comment_meta_margin_bottom = p3_get_option( 'comment_meta_margin_bottom' );
	echo <<< CSS
	.comment-top {
		margin-bottom:{$comment_meta_margin_bottom}px;
	}
CSS;
}



?>


