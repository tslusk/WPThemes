<?php
/* -- OPTIONS TO VARS FOR HEREDOC -- */
$nav_bg_color				 = p3_color_css_if_bound( 'nav_bg_color' ); 
$nav_link_font_size			 = p3_get_option( 'nav_link_font_size' );
$nav_dropdown_link_textsize	 = p3_get_option( 'nav_dropdown_link_textsize' );
$nav_dropdown_bg_hover_color = p3_color_css_if_bound( 'nav_dropdown_bg_hover_color' );
$nav_link_visited_font_color = p3_get_option( 'nav_link_visited_font_color' );
$nav_link_font_color		 = p3_get_option( 'nav_link_font_color' );


/* -- OVERALL ALIGNMENT -- */
if ( p3_test( 'nav_align', 'left' ) ) {
	$menu_float = 'float:none;';
} else if ( p3_test( 'nav_align', 'center' ) ) {
	$menu_float = 'float:left;';
} else if ( p3_test( 'nav_align', 'right' ) ) {
	$menu_float = 'float:right;';
}


/* -- DEFAULT SPACING --  */
$default_nav_spacing = round( ( p3_get_option( 'nav_link_font_size' ) / 1.4 ), 0 );


/* -- CUSTOM LINES ABOVE/BELOW -- */
// custom border line above
if ( p3_test( 'nav_border_top', 'on' ) ) {
	$nav_top_border = p3_border_css( 'nav_border_top', 'top' );
}
// custom border line below
if ( p3_test( 'nav_border_bottom', 'on' ) ) {
	$nav_btm_border = p3_border_css( 'nav_border_bottom', 'bottom' );
}


/* -- LEFT/RIGHT PADDING -- */
// if dropshadow, or no border
if ( p3_test( 'blog_border', 'dropshadow' ) || p3_test( 'blog_border', 'none' ) ) $menu_lr_padding = "padding: 0 18px;"; 
// if extra custom padding added
if ( p3_get_option( 'nav_edge_padding' ) != '' && !p3_test( 'nav_align', 'center' ) && !p3_test( 'headerlayout', 'pptclassic' ) ) {
	$menu_lr_padding = 'padding:0 ' . p3_get_option( 'nav_edge_padding' ) . 'px;'; 
// or if standard border calculate it based on font-size
} else if ( p3_test( 'blog_border', 'border' ) ) { 
	$menu_lr_padding = "padding:0 " . $default_nav_spacing . "px;";
}


/* -- PADDING BETWEEN LINKS -- */
$nav_link_spacing_between = p3_option_or_val( 'nav_link_spacing_between', round( p3_get_option( 'nav_link_font_size' ) * 1.8, 0 ) );
$nav_link_margin_side = ( p3_test( 'nav_align', 'right' ) ) ? 'left' : 'right';
if ( p3_test( 'headerlayout', 'pptclassic' ) ) $nav_link_margin_side = 'left'; 


/* -- PADDING ABOVE/BELOW LINKS -- */
define( 'DEFAULT_NAV_TB_PADDING', 10 );
define( 'ADJUST_NAV_TB_PADDING_THRESHOLD', 14 );
define( 'SEARCH_INPUT_HEIGHT_PX', 32 );

$nav_link_top_bottom_padding = DEFAULT_NAV_TB_PADDING;

if ( p3_get_option( 'nav_link_font_size' ) < ADJUST_NAV_TB_PADDING_THRESHOLD ) {
	
	// search form in menu bar, account for it's height
	if ( p3_test( 'nav_search_dropdown', 'off' ) && p3_test( 'nav_search_onoff', 'on' ) ) {
		$nav_link_top_bottom_padding = ( SEARCH_INPUT_HEIGHT_PX - p3_get_option( 'nav_link_font_size' ) ) / 2;
	
	// search in dropdown, use multiplier of font size
	} else {
		$nav_link_top_bottom_padding = $default_nav_spacing;
	}
	
	$nav_link_top_bottom_padding = round( $nav_link_top_bottom_padding, 0 );
}


/* -- DROPDOWN BG COLOR -- */
if ( p3_optional_color_bound( 'nav_dropdown_bg_color' ) ) {
	$nav_dropdown_bg_color = p3_get_option( 'nav_dropdown_bg_color' );
} else if ( p3_optional_color_bound( 'nav_bg_color' ) ) {
	$nav_dropdown_bg_color = p3_get_option( 'nav_bg_color' );
} else {
	$nav_dropdown_bg_color = 'transparent';
}
$nav_dropdown_bg_color = 'background: ' . $nav_dropdown_bg_color . ' !important;';


/* -- DROPDOWN LINEHEIGHT -- */
$dropdown_lineheight = round( ( p3_get_option( 'nav_dropdown_link_textsize' ) * 1.5 ), 0 );


/* -- OPTIONAL INCLUSION OF SEARCH TOP IN RULES -- */
$search_top_include =  ( p3_test( 'nav_search_dropdown', 'off' ) ) ? '#topnav li#search-top,' : '';


/* -- TOP PADDING TO VERTICALLY CENTER EMBEDDED FORMS -- */
$nav_height = p3_get_option( 'nav_link_font_size' ) + ( $nav_link_top_bottom_padding * 2);
$nav_form_top_padding = ( ( $nav_height - 23 ) / 2 ); // 23 probably height of input?
// min padding is 4px
if ( p3_test( 'nav_search_dropdown', 'off' ) AND ( $nav_form_top_padding < 4 ) ) {
	$nav_form_top_padding = 4;
}
$nav_form_top_padding = round( $nav_form_top_padding, 0 );


/* -- LINK CSS -- */
$topnav_link_CSS   = p3_link_css( 'nav_link', '#topnav', FALSE, TRUE );
$dropdown_link_CSS = p3_link_css( 'nav_dropdown_link', '#topnav ul li', FALSE, TRUE );

$nav_bg_selector = ( p3_test( 'headerlayout', 'pptclassic' ) ) ? '#topnav' : '#topnav-wrap'; 
$nav_bg_css = p3_bg_css( 'nav_bg', $nav_bg_selector );

/* --------------------- */
/* -- ECHO OUT STYLES -- */
/* --------------------- */

echo <<<CSS
/* -- menu styles -- */
$nav_bg_css
#topnav-wrap {
	$nav_top_border
	$nav_btm_border
}
#topnav {
	line-height:{$nav_link_font_size}px; 
	font-size:{$nav_link_font_size}px;
	$menu_float
	$menu_lr_padding
}
#topnav li {
	margin-{$nav_link_margin_side}:{$nav_link_spacing_between}px;
	padding-top:{$nav_link_top_bottom_padding}px;
	padding-bottom:{$nav_link_top_bottom_padding}px; 
}
#topnav li ul {
	margin-top:{$nav_link_top_bottom_padding}px; 
	$nav_dropdown_bg_color
}
#topnav ul li {
	margin-{$nav_link_margin_side}:0px;
	$nav_dropdown_bg_color
	line-height:{$dropdown_lineheight}px;
}
li#search-top ul {
	$nav_dropdown_bg_color
}
#topnav li li {
	padding-top:0;
	margin-right:0;
}
#topnav li li,{$search_top_include}
#topnav li#subscribebyemail-nav {
	padding-bottom:0;
}
{$search_top_include}#topnav li#subscribebyemail-nav {
	padding-top:{$nav_form_top_padding}px;
}
$topnav_link_CSS
$dropdown_link_CSS
#topnav a {
	cursor:pointer;
	font-size:{$nav_link_font_size}px;
}
#topnav li li a {
	font-size:{$nav_dropdown_link_textsize}px;
}
#topnav li ul a:hover {
	{$nav_dropdown_bg_hover_color}; 
}
.nav-link-icon-text .icon-link {
	margin-right:0.5em;
}
.nav-link-icon-text a {
	float:left;
}
CSS;


/* -- IF SEARCH ALIGNED RIGHT -- */
if ( p3_test( 'nav_search_align', 'right' ) ) { 
echo "\n" . <<<CSS
#topnav li#search-top {
	float:right;
	margin-right:0;
}
CSS;
} 


/* -- IF SUBSCRIBE BY EMAIL ALIGNED RIGHT -- */
if ( p3_test( 'subscribebyemail_nav_leftright', 'right' ) ) { echo "\n" . <<<CSS
#topnav li#subscribebyemail-nav {
	float:right;
	margin: 0 0 0 20px;
}
CSS;
}


/* -- IF RSS ICON -- */
if ( p3_test( 'nav_rss', 'on' ) AND p3_test( 'nav_rss_use_icon', 'yes' ) ) { 
	$nav_rss_left_padding =  p3_get_option( 'nav_link_font_size' ) / 2;
	echo "\n" . <<<CSS
li#nav-rss a#nav-rss-subscribe {
	padding-left:{$nav_rss_left_padding}px; 
}
CSS;
}


/* -- IF TWITTER ON -- */
if ( p3_test( 'twitter_onoff', 'on' ) ) { echo <<<CSS

/* -- twitter -- */
#navlink_twitter ul li span a {
	display:inline;
	padding-right:0;
	text-decoration:underline !important;
}
#topnav #navlink_twitter li {
	padding:5px 8px 3px 8px;
	width:114px;
	font-size:{$nav_dropdown_link_textsize}px;
	color:{$nav_link_visited_font_color};
}
#topnav #navlink_twitter li a {
	padding-left:0;
}
#topnav #navlink_twitter li:hover {
	{$nav_dropdown_bg_hover_color};
}
#navlink_twitter li span {
	color:{$nav_link_font_color}; 
}
CSS;
}

?>

