<?php
/* ---------------------------------------------- */
/* ---- custom css for post/page header area ---- */
/* ---------------------------------------------- */
extract( p3_general_css_vars() );

/* post/page header alignment */
$post_header_align        = p3_get_option( 'post_header_align' );
$post_title_align         = ( p3_test( 'postdate', 'normal' ) && p3_test( 'postdate_placement', 'withtitle' ) ) 
	? 'text-align:left;' : '';
$post_date_display_type   = ( p3_test( 'postdate_placement', 'above' ) ) ? 'display:block;' : ''; 
$floated_date_top_padding = intval(
	( p3_cascading_style( 'post_title_link_font_size', 'header_font_size' ) - 
	p3_cascading_style( 'postdate_font_size', 'gen_font_size' ) ) / 1.8 ); 
	// NOTE: division by 1.8 roughly keeps the post titles & floated dates baselines lined up

echo <<<CSS
.entry-title {
	line-height:1em;
}
.post-title-wrap .post-date {
	float:right;
	margin-top:{$floated_date_top_padding}px;
}
.entry-title, .entry-meta-top, .post-date {
	text-align: $post_header_align;
}
.entry-title {
	$post_title_align
}
.post-date {
	$post_date_display_type
}
CSS;


/* post title font/link CSS */
$header_font_css        = p3_font_css( 'header', '.entry-title a, #content .entry-title' );
$post_header_font_css   = p3_font_css( 'post_title_link', '#content .entry-title', TRUE, TRUE );
$post_title_link_css    = p3_link_css( 'post_title_link', '.entry-title', FALSE, TRUE );
$post_header_link_color = p3_color_css_if_bound( 'post_title_link_font_color', '' );
echo <<<CSS
$header_font_css
$post_header_font_css
$post_title_link_css
#content .entry-title {
	$post_header_link_color
}
.entry-title a {
	line-height:1em;
}
#content a.post-edit-link {
	font-size:10px !important;
	font-weight:400 !important;
	letter-spacing:normal !important;
	font-family: Arial, sans-serif;
	text-transform:uppercase;
	text-decoration:none;
	font-style:normal;
	margin: 0 8px;
}
CSS;
// if post title link isn't set, use general header color for visited and hover 
// states so we don't get default browser blue
if ( !p3_optional_color_bound( 'post_title_link_font_color' ) ) {
	$header_font_color = p3_get_option( 'header_font_color' );
	echo <<<CSS
	.entry-title a:visited,
	.entry-title a:hover {
		color: $header_font_color;
	}
CSS;
}


/* post header meta styles - category list, tags list, comment count */
$post_header_meta_font_css = p3_font_css( 'post_header_meta_link', '.post-header .postmeta', TRUE );
$post_header_meta_link_css = p3_link_css( 'post_header_meta_link', '.post-header .postmeta ', FALSE, TRUE );
$post_header_postdate_css  = p3_font_css( 'post_header_postdate', '.post-header .post-date', TRUE );
$entry_meta_spacing_side   = ( p3_test( 'post_header_align', 'right' ) ) ? 'left' : 'right';

echo <<<CSS
$post_header_meta_font_css
$post_header_meta_link_css
$post_header_postdate_css
.entry-meta-top span {
	margin-{$entry_meta_spacing_side}:1.1em;
}
.post-header-comment-count span {
	display:none;
}
CSS;


/* boxy dates styles */
if ( p3_test( 'postdate', 'boxy' ) ) {
	$boxy_date_font_size      = p3_get_option( 'boxy_date_font_size' );
	$boxy_date_height         = intval( $boxy_date_font_size * 3.6 );
	$boxy_date_letter_spacing = intval( $boxy_date_font_size / 7 );
	$boxy_date_width          = intval( $boxy_date_font_size * 4.3 );
	$boxy_date_padding        = intval( $boxy_date_font_size / 2 );
	$boxy_date_offset         = $boxy_date_width + $boxy_date_font_size;
	$boxy_date_bg_color       = p3_color_css_if_bound( 'boxy_date_bg_color' );
	$boxy_date_align          = p3_get_option( 'boxy_date_align' );
	$boxy_date_month_color    = p3_get_option( 'boxy_date_month_color' );
	$boxy_date_day_color      = p3_get_option( 'boxy_date_day_color' );
	$boxy_date_year_color     = p3_get_option( 'boxy_date_year_color' );
	
	// output css
	echo <<<CSS
	.post-header {

	}
	.boxy-date-wrap {
		font-size:{$boxy_date_font_size}px;
		width:{$boxy_date_width}px;
		padding:{$boxy_date_padding}px 0;
		$boxy_date_bg_color
	}
	.boxy .post-date {
		float:$boxy_date_align;
		margin-top:0;
		display:inline;
	}
	.boxy .entry-title,
	.boxy .entry-meta-top {
		margin-{$boxy_date_align}:{$boxy_date_offset}px;
	}
	body.page .entry-title,
	body.page .entry-meta-top {
		margin-{$boxy_date_align}:0;
	}
	body.page .boxy-date-wrap {
		display:none;
	}
	body.page .post-header {
		padding-left:0;
		padding-right:0;
	}

	.boxy-date {
		margin-right:0 !important;
		display:block;
		line-height:.8em;
		text-align:center;
		text-transform:uppercase;
		font-family:Arial;
	}
	.boxy-month {
		letter-spacing:2px;
		color:$boxy_date_month_color;
	}
	.boxy-day {
		font-size:2.3em;
		margin-top:1px;
		#margin-top:2px;
		font-weight:700;
		color:$boxy_date_day_color;
	}
	.boxy-year {
		margin-top:.1em;
		font-size:.9em;
		letter-spacing:{$boxy_date_letter_spacing}px;
		color:$boxy_date_year_color;
	}
	.boxy-year {
		margin-left:5%;
	}
	.boxy-month {
		margin-left:3%;
	}
CSS;
}


/* post header border below */
if ( p3_test( 'post_header_border', 'on' ) ) {
	$post_header_border_css    = p3_border_css( 'post_header_border', 'top' );
	$post_header_border_margin = p3_get_option( 'post_header_border_margin' );
	echo <<<CSS
	.entry-content {
		$post_header_border_css
		padding-top: {$post_header_border_margin}px !important;
	}
CSS;
}


/* margins above and below post header */
$post_header_margin_below = p3_get_option( 'post_header_margin_below' );
$post_header_margin_above = p3_get_option( 'post_header_margin_above' );
echo <<<CSS
.post-header {
	margin-bottom: {$post_header_margin_below}px;
}
.post-wrap-inner {
	padding-top: {$post_header_margin_above}px;
}
CSS;


/* post header image separator */
if ( p3_test( 'post_header_border', 'image' ) && p3_image_exists( 'post_header_separator' ) ) {
	$sep_img_height = p3_imageheight( 'post_header_separator', NO_ECHO );
	$sep_padding    = $sep_img_height + $post_header_margin_below;
	$sep_img_url    = p3_imageurl( 'post_header_separator', NO_ECHO );
	echo "\n" . <<<CSS
	.post-header {
		background: transparent url($sep_img_url) no-repeat bottom center;
		padding-bottom:{$sep_padding}px;
	}
CSS;
}


/* post date advanced features */
p3_advanced_postdate_css();

?>