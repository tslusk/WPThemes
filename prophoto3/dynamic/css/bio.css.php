<?php
/* ----------------------------- */
/* -- custom css for bio area -- */
/* ----------------------------- */

// general
extract( p3_general_css_vars() );

// text/link css
$bio_header_font_css = p3_font_css( 'bio_header', '#bio h3' );
$bio_para_font_css   = p3_font_css( 'bio_para', '#bio, #bio p' );
$bio_link_css        = p3_link_css( 'bio_link', '#bio', FALSE, TRUE );

// paddings and margins
$bio_top_padding       = p3_get_option( 'bio_top_padding' );
$bio_gutter_width      = ( p3_test( 'bio_gutter_width' ) ) 
	? p3_get_option( 'bio_gutter_width' ) : p3_get_option( 'content_margin' );
$bio_col_margin        = p3_option_or_val( 'bio_gutter_width', $content_margin );
$bio_lr_margin         = p3_option_or_val( 'bio_lr_padding', $content_margin );
$bio_header_margin_btm = ( p3_test( 'bio_headline_margin_btm' ) ) ? p3_get_option( 'bio_headline_margin_btm' ) . 'px' : '0.5em';
$bio_btm_padding       = p3_get_option( 'bio_btm_padding' ) - p3_get_option( 'bio_widget_margin_btm' );
if ( $bio_btm_padding < 1 ) $bio_btm_padding = 1;

//bio background
$bio_bg_css = p3_bg_css( 'bio_bg', '#bio' );
$bio_inner_bg_img_css = p3_bg_css( 'bio_inner_bg', '#bio-inner-wrapper' );

// bottom border
if ( p3_test( 'bio_border', 'border' ) ) {
	$bio_btm_border = p3_border_css( 'bio_border', 'bottom' );
}
	
// bio bottom separator custom image
$bio_sep_imgurl    = p3_imageurl( 'bio_separator', NO_ECHO );
$bio_sep_imgheight = p3_imageheight( 'bio_separator', NO_ECHO );

// bio picture
$biopic_align        =  p3_get_option( 'biopic_align' );
$biopic_right_margin = ( 'left' == $biopic_align ) ? $bio_col_margin : '0';
if ( p3_test( 'biopic_border', 'on' ) ) $biopic_border_css = p3_border_css( 'biopic_border' );

// bio content/widgets
$bio_columns_css         = p3_bio_column_css();
$twitter_link_color      = ( p3_test( 'bio_para_font_color' ) ) 
	? p3_get_option( 'bio_para_font_color' ) : p3_get_option( 'gen_font_color' );
$bio_headline_margin_btm = ( p3_test( 'bio_headline_margin_btm' ) ) 
	? p3_get_option( 'bio_headline_margin_btm' ) . 'px' : '0.5em';
$bio_widget_margin_btm   =  p3_get_option( 'bio_widget_margin_btm' );
if ( !p3_test( 'biopic_display', 'off' ) ) {
	$biopic_height = p3_imageheight( 'biopic1', NO_ECHO ) + ( p3_get_option( 'biopic_border_width' ) * 2 );
	$bio_content_height = $biopic_height + $bio_widget_margin_btm;
	$bio_min_height_css = 'min-height:' . $bio_content_height . 'px;';
	$bio_min_height_ie6_css = 'height:' . $bio_content_height . 'px;';
}



echo <<<CSS
/* -- bio area css -- */
$bio_bg_css
$bio_inner_bg_img_css
#bio {
	$bio_btm_border
}
#bio-content {
	margin:0 {$bio_lr_margin}px {$bio_btm_padding}px {$bio_lr_margin}px;
	padding-top:{$bio_top_padding}px;
	$bio_min_height_css
}
* html #bio-content {
	height:auto !important;
	$bio_min_height_ie6_css
}
#biopic {
	float:{$biopic_align};
	$biopic_border_css
	margin-right:{$biopic_right_margin}px;
	margin-bottom:{$bio_widget_margin_btm}px !important;
}
$bio_header_font_css
$bio_para_font_css
$bio_link_css
.bio-col {
	margin-right:{$bio_col_margin}px;
}
.bio-widget-col {
	float:left;
}
#bio-content .widget h3 {
	margin-bottom:{$bio_header_margin_btm};
}
#bio-content li.widget,
#bio-content li.widget span.pngfixed {
	list-style-type:none;
	margin-bottom:{$bio_widget_margin_btm}px;
}
#bio-content .twitter-follow-link a {
	color:{$twitter_link_color};
}
$bio_columns_css
CSS;

// IF there is a bio signature image
if ( p3_image_exists( 'bio_signature' ) ) { ?>
#bio-signature {
	float:<?php p3_option( 'bio_sig_float' ); ?>;
	clear:both;
	margin: <?php p3_option( 'bio_sig_top_margin' ); echo 'px '; p3_option( 'bio_sig_right_margin' ); echo 'px 0 '; p3_option( 'bio_sig_left_margin' ); ?>px; 
}	
<?php } // END IF there is a bio signature image 


/* custom bio separator image */
if ( p3_test( 'bio_border', 'image' ) ) echo <<<CSS
#bio-separator {
	background-image:url({$bio_sep_imgurl});
	background-position:bottom center;
	background-repeat:no-repeat;
	height:{$bio_sep_imgheight}px;	
}
CSS;

?>