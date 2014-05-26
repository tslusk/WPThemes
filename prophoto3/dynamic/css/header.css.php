<?php

/* ------------------------------ */
/* ----custom css for header----- */
/* ------------------------------ */


/* general vars for heredoc legibility */
$header_width     = p3_get_option( 'blog_width' );
$logowidth		  = p3_imagewidth( 'logo', NO_ECHO );
$logoheight	      = p3_imageheight( 'logo', NO_ECHO );
$mastheadwidth    = $header_width - $logowidth;
$headerlayout     = p3_get_option( 'headerlayout' );



/*----------------------------------*/
/* begin logo styles                */
/*----------------------------------*/

/* set logo position according to layout */
if ( p3_logo_masthead_sameline() ) {
	$logo_position_type       = 'position: absolute;';
	$logo_vertical_position   = 'top: 0;';
} else {
	$logo_position_type       = '';
	$logo_vertical_position   = '';
}

/* horizontally position the logo area according to layout */
switch( $headerlayout ) {
case "mastlogohead_nav":
	$left = ( $header_width / 2 ) - ( $logowidth / 2 );
	$logo_horizontal_position = "left: {$left}px;";
	break;
case "mastheadlogo_nav":
	$logo_horizontal_position = "right: 0;";
	break;
case "logomasthead_nav":
	$logo_horizontal_position = "left: 0;";
	break;
default:
	$logo_horizontal_position = "";
	break;
}

/* logo alignment */
if ( strpos( $headerlayout, 'logocenter' ) !== FALSE ) {
	$logo_margin = "margin:0 auto;";
 	$logo_float  = "float:none;";
} elseif ( strpos( $headerlayout, 'logoleft' ) !== FALSE ) {
	$logo_margin = "margin:0;";
 	$logo_float  = "float:none;";
} elseif ( strpos( $headerlayout, 'logoright' ) !== FALSE ) {
	$margin = $header_width - $logowidth;
	$logo_margin = "margin:0 0 0 {$margin}px;";
 	$logo_float  = "float:none;";
} else {
	$logo_margin = $logo_float = '';
}



/*----------------------------------*/
/* Begin masthead styles            */
/*----------------------------------*/


/* masthead wrapper css */
$masthead_image_wrapper_height = p3_imageheight( 'masthead_image1', NO_ECHO );


/* masthead image css */

// position the flash header according to header layout
switch( $headerlayout ) {
	case "logomasthead_nav":
		$masthead_image_width         = "width: {$mastheadwidth}px;";
		$masthead_image_position_type = "position: absolute;";
		$masthead_image_top           = "top: 0;";
		$masthead_image_left          = "left: {$logowidth}px;";
		$masthead_image_float         = "float: none;";
		$masthead_image_height        = $logoheight;
		break;
	case "mastheadlogo_nav":
		$masthead_image_width  = "width: {$mastheadwidth}px;";
		$masthead_image_height = $logoheight;
		break;
	case "mastlogohead_nav":
		$masthead_image_height = $logoheight;
		break;
	default:
		$masthead_image_width  = "width: {$header_width}px;";
		$masthead_image_float  = "float: none;";
		$masthead_image_height = $masthead_image_wrapper_height;
		break;
}


/* masthead css */

// masthead position
switch ( $headerlayout ) {
	case "logomasthead_nav":
	case "mastlogohead_nav":
	case "mastheadlogo_nav":
	case "pptclassic":
		$masthead_position = 'position:relative;';
		break;
	default:
		$masthead_position = '';
	}
	
// masthead height
$masthead_height = ( p3_logo_masthead_sameline() ) ? "height: {$logoheight}px" : "";

// masthead top/bottom border
$masthead_selector = ( $headerlayout == 'pptclassic' ) ? '#masthead-image-wrapper' : '#masthead';
if ( p3_test( 'masthead_border_top', 'on' ) ) {
	$masthead_border_top_css = p3_border_css( 'masthead_border_top', 'top', $masthead_selector );
}
if ( p3_test( 'masthead_border_bottom', 'on' ) ) {
	$masthead_border_bottom_css = p3_border_css( 'masthead_border_bottom', 'bottom', $masthead_selector );
}
	
	
/* Output the CSS */
echo <<<CSS
#inner-header {
	position:relative;
}

/* logo css */

#logo h1,
#logo h2,
#logo p {
	text-indent:-9999em;
}
h1#alt-h1,
h1#alt-h1 a,
h2#alt-h1,
h2#alt-h1 a {
	height:0 !important;
	overflow:hidden;
	width:0 !important;
	display:none !important;
}
#logo {
	$logo_position_type
	$logo_vertical_position
	$logo_horizontal_position
	width: {$logowidth}px;
	height: {$logoheight}px;
	overflow: hidden;
	$logo_margin
	$logo_float
}

$logo_img_css

/* masthead css */ 
.masthead-image {
	$masthead_image_width
	$masthead_image_position_type
	$masthead_image_top
	$masthead_image_left
	$masthead_image_float
	height: {$masthead_image_height}px;
	overflow: hidden;	
}
#masthead {
	overflow:hidden;
	$masthead_position
	$masthead_height
}
#topnav li#search-top {
	float:left;
	display:inline;
}
#masthead-image-wrapper {
	overflow:hidden;
	height: {$masthead_image_wrapper_height}px;
}
.masthead-image a.no-link {
	cursor:default;
}
$masthead_border_top_css
$masthead_border_bottom_css
CSS;

/*----------------------------------*/
/* begin conditional CSS            */
/*----------------------------------*/

/* IF prophoto classic layout */
if ( $headerlayout == "pptclassic") {
	$top_line = ( p3_test( 'masthead_border_top', 'on' ) ) ? p3_get_option( 'masthead_border_top_width' ) : 0;
	$btm_line = ( p3_test( 'masthead_border_bottom', 'on' ) ) ? p3_get_option( 'masthead_border_bottom_width' ) : 0;
	$logo_btm_split     = p3_get_option( 'logo_btm_splitter' );
	$masthead_top_split = p3_get_option( 'masthead_top_splitter' );
	$masthead_btm_split = p3_get_option( 'masthead_bottom_splitter' );	
	$offset = $masthead_image_wrapper_height + $top_line + $btm_line + $logo_btm_split + $masthead_top_split + $masthead_btm_split + 3;
	$no_masthead_offset = $offset - $masthead_image_wrapper_height - $masthead_top_split - $masthead_btm_split - $top_line - $btm_line;

	echo <<<CSS
	#header {
		position:relative;
	}
	#topnav {
		float:left;
		display:inline;
		position:absolute;
		right:0;
		bottom:{$offset}px;
	}
	body.no-masthead #topnav {
		bottom:{$no_masthead_offset}px;
	}
CSS;
}


/* IF prophoto classic bar on*/
$prophoto_classic_bar_height = p3_get_option( 'prophoto_classic_bar_height' );
$prophoto_classic_bar_color  = p3_get_option( 'prophoto_classic_bar_color' );

if ( p3_test( 'prophoto_classic_bar', 'on' ) ) { echo <<<CSS
	
#top-colored-bar {
	background: $prophoto_classic_bar_color;
	height: {$prophoto_classic_bar_height}px;
}

CSS;
}


/* IF logomasthead_nav or mastlogohead_nav layout */
if ( $headerlayout == 'logomasthead_nav' || $headerlayout == 'mastlogohead_nav' ) { echo <<<CSS
	
#logo-img-a {
	position:absolute;
	top:0;
	left:0;
	z-index:50;
}

CSS;
}
?>