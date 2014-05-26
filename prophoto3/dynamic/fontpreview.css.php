<?php
/* ----------------------------------------------------------------- */
/* -- dynamic css for initial states of admin font preview groups -- */
/* ----------------------------------------------------------------- */

// load WordPress environment
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );

@header( 'Content-type: text/css' );


/* contextual stuff: backgrounds & colors of non-updating items */
echo "/* contextual stuff, backgrounds, non-changing items */\n";
// content area background color/image
echo p3_bg_css( 'body_bg', '.font-preview' );

// all individual comment background preview areas
$comment_bg_color = p3_color_css_if_bound( 'comments_comment_bg_color' );
$comment_color    = p3_color_css_if_bound( 'comments_comment_font_color', '' );
echo "\n\n\n/* individual comment backgrounds - all */\n";
echo <<< CSS
#comments_comment_link-font-preview,
#comments_comment_timestamp-font-preview,
#comments_comment-font-preview {
	$comment_bg_color
	$comment_color
}
CSS;



/* cascading settings */
// default non-link,non-header text style
p3_preview_area_css( 'gen', '.font-preview' );
$gen_margin_bottom = 'margin-bottom:' . p3_option_or_val( 'gen_margin_bottom', '1.2em', 'px' ) . ';';
echo ".font-preview .margin-bottom {\n\t$gen_margin_bottom\n}";

// places that inherit general header styles
p3_preview_area_css( 'header', '#header-font-preview, #bio_header-font-preview, #post_title_link-font-preview a, #sidebar_headlines-font-preview, #drawer_widget_headlines-font-preview' );



/* fonts tab */
// Overall font appearance
p3_preview_area_css( 'gen' );

// Overall link font appearance
p3_preview_area_css( 'gen_link', '.font-preview', '01' );

// Overall headline appearance
p3_preview_area_css( 'header' );



/* bio tab */
// bio area  context
echo "\n\n\n/* bio background */\n";
echo p3_bg_css( 'bio_bg', '#bio_header-font-preview, #bio_para-font-preview, #bio_link-font-preview' );

// Bio area headline appearance
p3_preview_area_css( 'bio_header' );

// Bio area headline appearance
p3_preview_area_css( 'bio_para', '#bio_para-font-preview, #bio_link-font-preview' );

// bio area links
p3_preview_area_css( 'bio_link', '', '01' );



/* comments tab */
// comments header postauthor/show/hide
echo p3_font_css( 'comments_header_link', '#comments_header_link-font-preview' );
p3_preview_area_css( 'comments_header_link' );

// post interaction links
p3_preview_area_css( 'comments_post_interaction_link', '', FALSE, 'a|margin-right:15px' );

// comment author link
p3_preview_area_css( 'comments_comment', '#comments_comment_link-font-preview' );
p3_preview_area_css( 'comments_comment_link', '', '01' );

// comment timestamp
p3_preview_area_css( 'comments_comment_timestamp' );

// individual comments
p3_preview_area_css( 'comments_comment' );



/* content tab */
// post titles
p3_preview_area_css( 'post_title_link' );

// post title meta
p3_preview_area_css( 'post_header_meta_link', '', '11' );

// post title date/time
echo p3_font_css( 'post_header_meta_link', '#post_header_postdate-font-preview' );
p3_advanced_postdate_css( '#post_header_postdate-font-preview span' );
p3_preview_area_css( 'post_header_postdate' );

// post text
p3_preview_area_css( 'post_text' );

// post footer meta
p3_preview_area_css( 'post_footer_meta_link', '', '11' );

// nav below
p3_preview_area_css( 'nav_below_link', '', '01' );

// archive headlines
p3_preview_area_css( 'archive_h2' );



/* footer tab */
// background context
$footer_bg_color = p3_get_option( 'footer_bg_color' );
echo "\n\n\n/* footer backgrounds */\n";
echo <<<CSS
#footer_headings-font-preview,
#footer_link-font-preview {
	background-color:$footer_bg_color;
}
CSS;

// footer headings
p3_preview_area_css( 'footer_headings' );

// footer text/links
p3_preview_area_css( 'footer_link', '', '11' );



/* galleries tab */
// background
$flash_gal_bg_color = p3_get_option( 'flash_gal_bg_color' );
$lightbox_bg_color  = p3_get_option( 'lightbox_bg_color' );
echo "\n\n\n/* gallery context */\n";
echo <<<CSS
#flash_gal_title-font-preview,
#flash_gal_subtitle-font-preview {
	text-align:center;
	background-color:$flash_gal_bg_color;
}
#lightbox-font-preview {
	line-height:1.5em;
	background-color:$lightbox_bg_color;
}
CSS;

// flash gal title
p3_preview_area_css( 'flash_gal_title', '#flash_gal_title-font-preview, #flash_gal_subtitle-font-preview' );

// flash gal title
p3_preview_area_css( 'flash_gal_subtitle' );

// lightbox image info
p3_preview_area_css( 'lightbox' );


/* sidebar tab */
// sidebar background
$sidebar_width  = p3_get_option( 'sidebar_width' );
$sidebar_bg_css = p3_bg_css( 'sidebar_bg', '#sidebar_headlines-font-preview, #sidebar_text-font-preview, #sidebar_link-font-preview' );
$drawer_default_bg_color = p3_get_option( 'drawer_default_bg_color' );
$drawer_content_width_1  = p3_get_option( 'drawer_content_width_1' );
$drawer_tab_width        = p3_get_option( 'drawer_tab_font_size' ) * 2;

echo "\n\n\n/* sidebar context */\n";
echo <<<CSS
$sidebar_bg_css
#sidebar_headlines-font-preview, 
#sidebar_text-font-preview, 
#sidebar_link-font-preview {
	width:{$sidebar_width}px !important;
}
#drawer_widget_headlines-font-preview,
#drawer_widget_text-font-preview,
#drawer_tab-font-preview,
#drawer_widget_link-font-preview {
	background-color:{$drawer_default_bg_color};
	width:{$drawer_content_width_1}px !important;
}
#drawer_tab-font-preview {
	border-color:$drawer_default_bg_color;
	-moz-border-radius-topright:10px;
	-moz-border-radius-bottomright:10px;
	-webkit-border-top-right-radius:10px;
	-webkit-border-bottom-right-radius:10px;
	text-align:center;
	line-height:1em;
	padding-left:0 !important;
	padding-right:0 !important;
	width:{$drawer_tab_width}px !important;
}
CSS;
// sidebar widget headlines
p3_preview_area_css( 'sidebar_headlines' );

// sidebar widget text
p3_preview_area_css( 'sidebar_text' );

// sidebar widget links
p3_preview_area_css( 'sidebar_link', '', '01' );

// drawer widget headers
p3_preview_area_css( 'drawer_widget_headlines' );

// drawer widget text
p3_preview_area_css( 'drawer_widget_text' );

// drawer tabs
p3_preview_area_css( 'drawer_tab' );

// drawer widget links
p3_preview_area_css( 'drawer_widget_text', '#drawer_widget_link-font-preview' );
p3_preview_area_css( 'drawer_widget_link', '', '01' );

?>