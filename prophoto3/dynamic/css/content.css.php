<?php 
/* ------------------------------------------------- */
/* ------ dynamic styles for post content ---------- */
/* ------------------------------------------------- */
extract( p3_general_css_vars() );

/* post and page bg color, image */
echo p3_bg_css( 'post_bg', '.post-wrap-inner' );
echo "body.page .post-wrap-inner {\n\tbackground-image:none;\n}";
echo p3_bg_css( 'page_bg', 'body.page .post-wrap-inner' );


/* post content TEXT & IMAGES */
// post text
$post_text_font_css  = p3_font_css( 'post_text', '.entry-content p, .entry-content li', TRUE );
$general_font_size   = p3_get_option( 'gen_font_size' );
$post_li_line_height = p3_get_option( 'gen_line_height' ) * .85;
$post_text_link_css  = p3_link_css( 'post_text_link', '.entry-content p', FALSE, OPTIONAL_COLORS );
$post_text_link_css2 = p3_link_css( 'post_text_link', '.entry-content li', FALSE, OPTIONAL_COLORS );

// blockquote
$blockquote_fontsize = intval( p3_cascading_style( 'post_text_font_size', 'gen_font_size' ) * 0.85 );
$post_blockquote_border_color = ( p3_test( 'post_text_font_color' ) ) 
	? p3_get_option( 'post_text_font_color' )
	: p3_get_option( 'gen_font_color' );
	
// image borders
if ( intval( p3_get_option( 'post_pic_border_width' ) > 0 ) ) {
	$post_pic_border_css = p3_border_css( 'post_pic_border', '', '', IMPORTANT );
}

// image margins
$post_pic_margin_top    = p3_get_option( 'post_pic_margin_top' );
$post_pic_margin_bottom = p3_get_option( 'post_pic_margin_bottom' );

// image max-width
extract( p3_image_max_widths() );

// watermark
if ( p3_test( 'image_protection', 'watermark' ) ) {
	$opacity = p3_convert_percentage( p3_get_option( 'watermark_alpha' ) );
	$ms_opacity = p3_get_option( 'watermark_alpha' );
	$watermark_pos = p3_get_option( 'watermark_position' );
	$watermark_url = p3_imageurl( 'watermark', NO_ECHO );
	$watermark_css = 'opacity:' . $opacity . ';
	background: transparent url(' . $watermark_url . ') no-repeat ' . $watermark_pos . ';
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $ms_opacity . ')";
	filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=' . $ms_opacity . ');';
}

// output CSS
echo <<<CSS
.entry-content li {
	font-size: {$general_font_size}px;
	line-height: {$post_li_line_height}em;
	margin-bottom: 0.7em;
	margin-left: 3em;
}
$post_text_font_css
$post_text_link_css
$post_text_link_css2
.entry-content img, 
.entry-content .gallery img, 
.p3-img-protect {
	border:solid 0px #fff !important;
	$post_pic_border_css
	margin-top: {$post_pic_margin_top}px;
	margin-bottom: {$post_pic_margin_bottom}px;
}
#main-inner-wrap #content .entry-content .p3-image-protect img {
	border:solid 0px #fff !important;
	margin-top:0 !important;
	margin-bottom:0 !important;
}
blockquote {
	padding-left:.8em;
	margin-left:3.2em;
	border-left: 1px dotted {$post_blockquote_border_color};
}
.entry-content blockquote p {
	font-size: {$blockquote_fontsize}px;
}
.p3-post-sig {
	clear:both;
}
/* image protection */
.p3-img-protect {
	position:relative;
}
#content-wrap #content .p3-img-protect .p3-overlay {
	position:absolute;
	top:0;
	left:0;
	margin:0 !important;
	padding:0 !important;
	border-width:0 !important;
	$watermark_css
}
#content-wrap #content .no-watermark .p3-img-protect .p3-overlay {
	background-image:none;
}
#content-wrap #content .entry-content .p3-img-protect img {
	border:solid 0px #fff !important;
	margin-top:0 !important;
	margin-bottom:0 !important;
}
/* constrain captions to entry-content width */
.wp-caption {
	max-width:{$entry_content_width}px !important;
}
body.has-sidebar .wp-caption {
	max-width:{$with_sidebar_entry_content_width}px !important;
}
CSS;



/* IMAGE DROPSHADOWS */
if ( p3_test( 'post_pic_shadow_enable', 'true' ) ) {
	$shadow_color = p3_get_option( 'post_pic_shadow_color' );
	$shadow_blur = p3_get_option( 'post_pic_shadow_blur' );
	$shadow_vert_offset = p3_get_option( 'post_pic_shadow_vertical_offset' );
	$shadow_horiz_offset = p3_get_option( 'post_pic_shadow_horizontal_offset' );
	$shadow = "{$shadow_horiz_offset}px {$shadow_vert_offset}px {$shadow_blur}px $shadow_color";
	echo "\n" . <<<CSS
	.entry-content img,
	.entry-content .p3-img-protect {
		-moz-box-shadow:$shadow;
		-webkit-box-shadow:$shadow;
		box-shadow:$shadow;
	}
	.entry-content .p3-img-protect img,
	.entry-content img.wp-smiley,
	.entry-content .p3-lightbox-gallery img,
	.entry-content .no-dropshadow-imgs img,
	.entry-content img.no-dropshadow,
	.entry-content .sociable img,
	.entry-content .p3-post-sig img {
		-moz-box-shadow:none;
		-webkit-box-shadow:none;
		box-shadow:none;
	}
	.p3-img-protect {
		clear:both;
	}
CSS;
}


/* EXCERPT IMAGES */
if ( p3_test( 'show_excerpt_image', 'true' ) ) {
	if ( !p3_test( 'excerpt_image_size', 'full' ) ) {
		$excerpt_img_align = ( p3_test( 'excerpt_image_position', 'before_text' ) ) ? 'left' : 'right';
		$excerpt_img_align_reverse = ( $excerpt_img_align == 'right' ) ? 'left' : 'right';
		$small_thumb_width = WP_THUMB_WIDTH;
		$medium_thumb_width = WP_M_THUMB_WIDTH;
		echo <<<CSS
		body.excerpted-posts a.img-to-permalink {
			margin-top:0;
			margin-bottom:1.5em;
			margin-{$excerpt_img_align_reverse}: 1.5em;
			margin-{$excerpt_img_align}: 0;
			float:{$excerpt_img_align};
		}
		body.excerpted-posts .entry-content .p3-img-protect,
		body.excerpted-posts .entry-content img {
			float:{$excerpt_img_align};
			margin: 0 !important;
		}
		body.excerpted-posts .shrink-to-thumbnail {
			height:auto;
			width:{$small_thumb_width}px !important;
		}
		body.excerpted-posts .shrink-to-medium-thumbnail {
			height:auto;
			width:{$medium_thumb_width}px !important;
		}
CSS;
	}
	if ( p3_test( 'excerpt_image_size', 'full' ) && ( p3_test( 'excerpt_image_position', 'before_text' ) )  ) {
		echo <<<CSS
		body.excerpted-posts .attachment-thumbnail,
		body.excerpted-posts .p3-excerpt-image {
			margin-top:0;
		}	
CSS;
	}
}



/* post footer meta text/link */
echo p3_link_css( 'post_footer_meta_link', '.entry-meta-bottom', TRUE, TRUE );



/* archive-type pages */
$archive_h2_font_css     = p3_font_css( 'archive_h2', 'h2.page-title' );
$archive_h2_margin_below = p3_option_or_val( 'archive_h2_margin_bottom', '1.5em', 'px' );

echo <<<CSS
$archive_h2_font_css
body.category .page-title,
body.tag .page-title {
	margin-bottom:0;
}
.archive-meta {
	width:75%;
	font-style: italic !important;
	padding:1em 0 0 0;
}
.archive-meta p {
	margin-bottom:0;
}
.page-title-wrap {
	padding: {$archive_h2_margin_below} 0;
}

CSS;




/* gallery styles */
$lightbox_bg_color       = p3_get_option( 'lightbox_bg_color' );
$lightbox_font_size      = p3_get_option( 'lightbox_font_size' );
$lightbox_font_family    = p3_get_option( 'lightbox_font_family' );
$lightbox_bg_color       = p3_get_option( 'lightbox_bg_color' );
$lightbox_border_width   = p3_get_option( 'lightbox_border_width' );
$lightbox_font_color     = p3_get_option( 'lightbox_font_color');
$lightbox_thumb_margin   = p3_get_option( 'lightbox_thumb_margin' );
$lightbox_thumb_opacity  = p3_convert_percentage( p3_get_option( 'lightbox_thumb_opacity' ) );
$lightbox_center_main    = ( p3_test( 'lightbox_main_img_center', 'true' ) ) 
	? "margin-right:auto;\n\tmargin-left:auto;" : '';
$lightbox_center_thumbs = ( p3_test( 'lightbox_thumbs_center', 'true' ) ) 
	? "margin-right:auto;\n\tmargin-left:auto;" : '';
echo <<<CSS
.p3-lightbox-gallery {
	clear:both;
}
#lightbox-container-image-box {
	background-color: $lightbox_bg_color;
}
#lightbox-container-image-data-box {
	font-size: {$lightbox_font_size}px;
	font-family: $lightbox_font_family;
	background-color: $lightbox_bg_color;
}
#lightbox-container-image-data-box {
	padding: 0 {$lightbox_border_width}px;
	padding-bottom: {$lightbox_border_width}px;
}
#lightbox-container-image { 
	padding: {$lightbox_border_width}px; 
}
#lightbox-container-image-data {
	color: $lightbox_font_color; 
}
.p3-lightbox-gallery-thumbs {
	$lightbox_center_thumbs
}
#main-wrap-inner .entry-content .p3-lightbox-gallery img {
	margin-bottom: {$lightbox_thumb_margin}px;
}
.entry-content .p3-lightbox-gallery-thumbs a img {
	margin: 0 {$lightbox_thumb_margin}px {$lightbox_thumb_margin}px 0;
	display:inline;
	opacity:$lightbox_thumb_opacity;
}
.entry-content .p3-lightbox-gallery-thumbs .last a img {
	margin-right:0;
}
.p3-lightbox-gallery img {
	display:block;
	cursor:pointer !important;
	$lightbox_center_main
}
.p3-flash-gallery-wrapper {
	margin-left:auto;
	margin-right:auto;
}
#content .entry-content .p3-flash-gallery-holder {
	display:none !important;
}
CSS;



/* POST FOOTER area: spacing, seperator lines, separator images */
$padding_below_post = $post_footer_height = $last_post_footer_height = intval( p3_get_option( 'padding_below_post' ) );

// using an uploaded image as post divider
if ( p3_test( 'post_divider', 'image' ) && p3_image_exists( 'post_sep' ) ) {
	$post_footer_height += p3_imageheight( 'post_sep', NO_ECHO );
	$bg_image_css = 'background-image: url(' . p3_imageurl( 'post_sep', NO_ECHO ) . ');';
	$last_post_footer_height = $padding_below_post;
	$post_sep_align = p3_get_option( 'post_sep_align' );
	

// css line as post divider
} else if ( p3_test( 'post_divider', 'line' ) ) {
	$bottom_line_css = p3_border_css( 'post_sep_border', 'bottom' );
}

// facebook like button
$like_btn_margin_top = p3_get_option( 'like_btn_margin_top' );
$like_btn_margin_btm = p3_get_option( 'like_btn_margin_btm' );

// output the css
echo <<<CSS
.post-footer {
	background-repeat:no-repeat;
	background-position:bottom $post_sep_align;
	$bg_image_css
	$bottom_line_css
	height:{$post_footer_height}px;
}
.last-post .post-footer,
body.single .post-footer,
body.page .post-footer,
body.post .post-footer,
body.archive .last-post .post-footer {
	background-image:none;
	border-bottom-width:0;
	height:{$last_post_footer_height}px;
}
.p3-fb-like-btn-wrap {
	clear:both;
	margin:{$like_btn_margin_top}px 0 {$like_btn_margin_btm}px 0;
}
CSS;

// if archive-ish pages are set different
if ( p3_test( 'archive_post_divider', 'line' ) ) {
	$archive_post_footer_height = p3_get_option( 'archive_padding_below_post' );
	$archive_line_css = p3_border_css( 'archive_post_sep_border', 'bottom' );
	echo <<<CSS
	body.archive .post-footer,
	body.search-results .post-footer {
		background-image:none;
		height:{$archive_post_footer_height}px;
		$archive_line_css
	}
	body.archive .last-post .post-footer {
		height:{$archive_post_footer_height}px;
	}
CSS;
}


?>