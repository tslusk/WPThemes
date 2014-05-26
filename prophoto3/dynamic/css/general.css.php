<?php
/* ---------------------------------- */
/* -------general custom css--------- */
/* ---------------------------------- */


/* general vars for heredoc legibility */
extract( p3_general_css_vars() );

/* body styles */
$bg_color            = p3_get_option( 'blog_bg_color' );
$blog_bg             = p3_imageurl( 'blog_bg', NO_ECHO );
$blog_bg_img_repeat        = p3_get_option( 'blog_bg_img_repeat' );
$blog_bg_img_attachment    = p3_get_option( 'blog_bg_img_attachment' );
$blog_bg_img_position      = p3_get_option( 'blog_bg_img_position' );
$gen_font_color      = p3_get_option( 'gen_font_color' );
$gen_font_family     = p3_get_option( 'gen_font_family' );
$blog_top_margin     = p3_get_option( 'blog_top_margin' );
$blog_bottom_margin  = p3_get_option( 'blog_btm_margin' );
$gen_text_transform  = ( p3_test( 'gen_text_transform' ) ) ? 'text-transform:' . p3_get_option( 'gen_text_transform' ) . ";": "";

/* general font styles */
$gen_font_css = p3_font_css( 'gen', 'body, p' );

/* general link styles */
$gen_link_css = p3_link_css( 'gen_link', '', FALSE,  TRUE );

/* headlines throughout, applies overall headline styles, can be overriden below */
$gen_h2_css = p3_font_css( 'header', 'h2' ); 
$gen_h3_css = p3_font_css( 'header', 'h3' );
$gen_h1_css = p3_font_css( 'header', '.entry-title' ); 

/* calculate outer wrap width */
$outer_wrap_width = p3_get_option( 'blog_width' ) + p3_blog_extra_width();
if ( intval( $outer_wrap_width ) < 600 ) $outer_wrap_width = 980; // safeguard

/* inner wrapper styles */
$blog_width     = p3_get_option( 'blog_width' );
$content_bg_css = p3_bg_css( 'body_bg', 'body.single #content-wrap, body.page #content-wrap, .content-bg, body.has-sidebar #content-wrap' );

/* blog border styles */
$blog_border_width = p3_get_option( 'blog_border_width' );
$blog_border_color = p3_get_option( 'blog_border_color' );
$blog_border = ( p3_test( 'blog_border', 'border' ) ) ? p3_border_css( 'blog_border' ) : '';
$blog_border_topbottom = ( p3_test( 'blog_border_topbottom', 'no' ) ) ? "\n\tborder-top-width:0;\n\tborder-bottom-width:0;" : '';

/* header styles */
$header_bg_color = ( p3_optional_color_bound( 'header_bg_color' ) ) ? p3_get_option( 'header_bg_color' ) : 'transparent';

/* nav below styles */
$nav_previous_align = p3_get_option( 'nav_previous_align' );
$nav_next_align = p3_get_option( 'nav_next_align' );
$nav_below_link_css = p3_link_css( 'nav_below_link', '#nav-below ' );



/* Output the CSS */
echo <<<CSS
/*----------------------------------*/
/* General CSS                      */
/*----------------------------------*/

body {
	background: $bg_color url({$blog_bg}) $blog_bg_img_repeat $blog_bg_img_attachment $blog_bg_img_position;
	color: $gen_font_color;
	font-family: $gen_font_family;
	padding: {$blog_top_margin}px 0 {$blog_bottom_margin}px 0;
	$gen_text_transform
}
p, .p3-flash-gallery-wrapper {
	margin-bottom: 1.2em;
}
$gen_font_css

$gen_link_css

$gen_h2_css 

$gen_h3_css

$gen_h1_css
#outer-wrap-centered {
	width:{$outer_wrap_width}px;
	margin:0 auto;
}
$content_bg_css
body.has-sidebar #content-wrap .content-bg,
body.single #content-wrap .content-bg,
body.page #content-wrap .content-bg {
	background-color:transparent !important;
	background-image:none !important;
}
#inner-wrap {
	width:{$blog_width}px; 
	$blog_border
	$blog_border_topbottom
	margin:0 auto;
	overflow:hidden;
}
#logo-wrap {
	background-color: $header_bg_color;
}
.entry-content, 
.post-header,
.page-title,
.archive-meta,
.entry-meta-bottom,
#searchform-no-results {
	margin-left: {$content_margin}px; 
	margin-right: {$content_margin}px;
}
.entry-content {
	clear:both;
}
p#nav-below {
	padding: 1.6em {$content_margin}px;
	margin-bottom:0;	
}
.nav-previous {
	float: $nav_previous_align;
}
.nav-next {
	float: $nav_next_align;
}

$nav_below_link_css
CSS;

/*----------------------------------*/
/* begin conditional CSS            */
/*----------------------------------*/


/* Conditional dropshadow CSS */

// Set variables based on whether narrow or wide dropshadow is selected
if ( p3_test( 'blog_border_shadow_width', 'narrow' ) ) {
	$dropshadow_url_prefix = $p3_theme_url . '/images/dropshadow_';
	$dropshadow_dimension  = DROPSHADOW_NARROW_IMG_WIDTH;
} else {
	$dropshadow_url_prefix = $p3_theme_url . '/images/dropshadow_wide_';
	$dropshadow_dimension  = DROPSHADOW_WIDE_IMG_WIDTH;
}


// IF dropshadow right/left on
if ( p3_test( 'blog_border', 'dropshadow' ) ) { echo <<<CSS

#main-wrap-outer {
	background: transparent url({$dropshadow_url_prefix}sides.png) repeat-y top left;
}
#main-wrap-inner {
	background: transparent url({$dropshadow_url_prefix}sides.png) repeat-y top right;
}
CSS;
}


// IF dropshadow top/bottom on
if ( p3_test( 'blog_border', 'dropshadow' ) && p3_test( 'blog_border_topbottom', 'yes' ) ) { echo <<<CSS

.dropshadow-topbottom,
.dropshadow-topbottom div  {
	height:{$dropshadow_dimension}px;
	overflow:hidden;
}
.dropshadow-corner {
	background-image: url({$dropshadow_url_prefix}corners.png);
	width:{$dropshadow_dimension}px;
}
.dropshadow-center {
	background-image: url({$dropshadow_url_prefix}topbottom.png);
	background-repeat: repeat-x;
}
#dropshadow-top-left {
	float:left;
	background-position:top left;
}
#dropshadow-top-center {
	background-position: top left;
}
#dropshadow-top-right {
	float:right;
	background-position:top right;
}
#dropshadow-bottom-left {
	float:left;
	background-position:0px -{$dropshadow_dimension}px;
}
#dropshadow-bottom-center {
	background-position: 0 -{$dropshadow_dimension}px;
}
#dropshadow-bottom-right {
	float:right;
	background-position:{$dropshadow_dimension}px -{$dropshadow_dimension}px;
}		
CSS;
}


/* IF audio player hidden */
if ( p3_test( 'audio_hidden', 'on' ) ) { 
	echo <<<CSS
	#audio-player {
		height:0;
		text-indent:-9999em;
	}

CSS;
}


/* IF add banner on */
if ( p3_test( 'sponsors', 'on' ) ) {
	$sponsors_side_margins     = p3_get_option( 'sponsors_side_margins' );
	$sponsors_border_color     = p3_get_option( 'sponsors_border_color' );
	$sponsors_img_margin_right = p3_get_option( 'sponsors_img_margin_right' );
	$sponsors_img_margin_below = p3_get_option( 'sponsors_img_margin_below' );
	echo <<<CSS
	#ad-banners {
		padding:0 {$sponsors_side_margins}px;
		text-align:center;
		margin-bottom:0
	}
	#ad-banners img {
		border:1px solid $sponsors_border_color;
		margin-right: {$sponsors_img_margin_right}px;
		margin-bottom: {$sponsors_img_margin_below}px;
	}
	
CSS;
}


/* floaty splitters */
// posts
$post_splitter         = p3_get_option( 'post_splitter' );
$archive_post_splitter = p3_get_option( 'archive_post_splitter' );
// masthead
$masthead_top_splitter = p3_get_option( 'masthead_top_splitter' );
$masthead_btm_splitter = p3_get_option( 'masthead_btm_splitter' );
// menu
$menu_top_splitter = ( !p3_test( 'headerlayout', 'pptclassic' ) ) ? p3_get_option( 'menu_top_splitter' ) : '0';
$menu_btm_splitter = ( !p3_test( 'headerlayout', 'pptclassic' ) ) ? p3_get_option( 'menu_btm_splitter' ) : '0';
// logo
$logo_top_splitter = p3_get_option( 'logo_top_splitter' );
$logo_btm_splitter = p3_get_option( 'logo_btm_splitter' );
$logo_splitter_css = ( p3_logo_masthead_sameline() ) 
	? '' : "margin: {$logo_top_splitter}px 0 {$logo_btm_splitter}px 0;";
// bio
$bio_top_splitter = ( p3_test( 'bio_include', 'yes' ) ) ? p3_get_option( 'bio_top_splitter' ) : '0';
$bio_btm_splitter = ( p3_test( 'bio_include', 'yes' ) ) ? p3_get_option( 'bio_btm_splitter' ) : '0';


echo <<<CSS
#masthead {
	margin: {$masthead_top_splitter}px 0 {$masthead_btm_splitter}px 0;
}
#topnav-wrap {
	margin: {$menu_top_splitter}px 0 {$menu_btm_splitter}px 0;
}
#inner-wrap #bio {
	margin: {$bio_top_splitter}px 0 {$bio_btm_splitter}px 0;
}
#contact-form {
	margin-bottom: {$menu_btm_splitter}px;
}
#logo-wrap {
	$logo_splitter_css
}
.post-wrap, .page-title-wrap {
	margin-bottom:{$post_splitter}px;
}
#bio {
	margin-bottom:{$post_splitter}px;
}
body.archive .post-wrap {
	margin-bottom:{$archive_post_splitter}px;
}
body.has-sidebar .post-wrap, body.has-sidebar .page-title-wrap {
	margin-bottom:0;
}
body.single .post-wrap, body.page .post-wrap {
	margin-bottom:0;
}
CSS;
?>