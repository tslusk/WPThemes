<?php
/* ------------------------------------- */
/* -------custom css for footer--------- */
/* ------------------------------------- */
extract( p3_general_css_vars() );

// calculate width of footer area
define( 'WIDTH_OF_DROPSHADOW', 7*2 );
$width = p3_get_option( 'blog_width' );
if ( p3_test( 'blog_border', 'border' ) ) $width = $width - ( p3_get_option('blog_border_width' ) * 2 );
if ( p3_test( 'blog_border', 'dropshadow' ) ) $width = $width - WIDTH_OF_DROPSHADOW;

// general footer styles
$footer_link_font_size      = p3_get_option( 'footer_link_font_size' );
$footer_widget_margin_below = p3_get_option( 'footer_widget_margin_below' );
$body_bg_color              = p3_get_option( 'body_bg_color' );
$gen_font_color             = p3_get_option( 'gen_font_color' );
$footer_p_padding_top       = ( p3_test( 'footer_include', 'no' ) ) ? 'padding-top: 20px;' : '';
$footer_font_color          = p3_color_css_if_bound( 'footer_font_color', '' );
$footer_bg_css              = p3_bg_css( 'footer_bg', '#footer' );

// footer heading styles
$footer_headings_font_css = p3_font_css( 'footer_headings', '#footer h3' );

// footer text styles
$footer_link_css   = p3_link_css( 'footer_link', '#footer ', TRUE, TRUE );
$footer_text_css   = p3_font_css( 'footer_link', '#footer, #footer p' );
$footer_font_color = p3_get_option( 'footer_link_font_color' );

// footer columns
$footer_col_count = 0;
for ( $i = 1; $i <= MAX_FOOTER_WIDGET_COLUMNS; $i++ ) { 
	if ( is_active_sidebar( 'footer-col-' . $i ) ) {
		$footer_col_count++;
		$last_col_num = $i;
	}
}
$footer_col_padding   = p3_option_or_val( 'footer_col_padding', $content_margin );
$footer_left_padding  = p3_option_or_val( 'footer_left_padding', $content_margin );
$footer_right_padding = p3_option_or_val( 'footer_right_padding', $content_margin );
$footer_working_area  = p3_get_option( 'blog_width' ) - $footer_left_padding - $footer_right_padding 
						- ( ( $footer_col_count - 1 ) * $footer_col_padding );
$footer_col_width     = intval( $footer_working_area / $footer_col_count );


/* Output the CSS */
echo <<<CSS
#footer .footer-col {
	width: {$footer_col_width}px;
	margin-right:{$footer_col_padding}px;
	float:left;
}
#footer #footer-widget-col-{$last_col_num} {
	margin-right:0;
}
#copyright-footer-sep {
	padding:0 .3em;
}
span.statcounter {
	display:inline;
}
#footer {
	padding: 30px {$footer_right_padding}px 30px {$footer_left_padding}px;
	$footer_font_color
	line-height:1.4em !important;
}
$footer_headings_font_css
$footer_bg_css
#footer li {
	margin-bottom: {$footer_widget_margin_below}px;
}
#footer li li {
	margin-bottom:0.4em;
	margin-left:20px;
	line-height:1.2em;
}
#footer, #footer p {
	color:$footer_font_color;
}
$footer_text_css
$footer_link_css
#copyright-footer {
	text-align:center;
	padding:11px {$content_margin}px 10px {$content_margin}px;
}
#copyright-footer p {
	color: $gen_font_color;
	font-size:11px;
	margin-bottom:0;
	$footer_p_padding_top
}
#footer-sep {
	padding:0 0.3em;
}
CSS;
?>