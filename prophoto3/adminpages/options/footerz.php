<?php
/* ----------------------- */
/* ----FOOTER OPTIONS----- */
/* ----------------------- */

// header
p3_option_header('Footer Options', 'footerz' );

// include footer?
p3_o( 'footer_include', 'radio|yes|Include footer area|no|Do not include footer area', '', 'Include footer area?' );

// footer options
p3_bg( 'footer_bg', 'Footer background' );

// custom spacing
p3_start_multiple( 'Footer custom spacing' );
p3_o( 'footer_left_padding', 'text|3', 'override default spacing (in pixels) on left side of footer' );
p3_o( 'footer_right_padding', 'text|3', 'override default spacing (in pixels) on right side of footer' );
p3_o( 'footer_col_padding', 'text|3', 'override default spacing (in pixels) between footer columns' );
p3_o( 'footer_widget_margin_below', 'text|3', 'spacing (in pixels) below footer content chunks' );
p3_stop_multiple();

// header font
p3_font_group( array(
	'title' => 'Footer headings text appearance',
	'key' => 'footer_headings',
	'add' => array( 'margin_bottom' )
) );

// link and text
p3_font_group( array(
	'title' => 'Footer links and text appearance',
	'key' => 'footer_link',
	'inherit' => 'all',
	'add' => array( 'nonlink_color' ),
) );

// custom copyright text
p3_o( 'custom_copyright', 'text|50', 'Write your own custom footer copyright text -- shown at the very bottom of your blog. If blank, will read: <em>&copy; ' . date('Y') .  " " . P3_SITE_NAME . '</em>', 'Custom copyright text' );

// remove footer attribution links
p3_o( 'ga_verifysum', 'text|25', 'If you have purchased a license to remove the NetRivet, Inc. and ProPhoto links from the footer, enter the transaction ID for that purchase here.', 'Footer attribution links removal' );

?>