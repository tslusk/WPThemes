<?php
/* ----------------------------- */
/* -- default images settings -- */
/* ----------------------------- */


/* default images */
$p3_defaults['images'] = array(
	'comments_header_bg' => 'nodefaultimage.gif',
	'bio_inner_bg' => 'nodefaultimage.gif',
	'bio_bg' => 'nodefaultimage.gif',
	'post_bg' => 'nodefaultimage.gif',
	'page_bg' => 'nodefaultimage.gif',
	'comments_comment_outer_bg' => 'nodefaultimage.gif',
	'logo' => 'nodefaultimage.gif',
	'masthead_image1' => 'default-header1.jpg',
	'post_sep' => 'nodefaultimage.gif',
	'blog_bg' => 'nodefaultimage.gif',
	'iphone_webclip_icon' => 'nodefaultimage.gif',
	'bio_separator' => 'nodefaultimage.gif',
	'biopic1' => 'prophoto2_biopic1.jpg',
	'bio_signature' => 'nodefaultimage.gif',
	'lightbox_blank' => 'lightbox-blank.gif',
	'lightbox_close' => 'lightbox-btn-close.gif',
	'lightbox_next' => 'lightbox-btn-next.gif',
	'lightbox_prev' => 'lightbox-btn-prev.gif',
	'lightbox_loading' => 'lightbox-ico-loading.gif',
	'watermark' => 'watermark.png',
	'flash_gal_logo' => 'nodefaultimage.gif',
	'sidebar_bg' => 'nodefaultimage.gif',
	'sidebar_widget_sep_img' => 'nodefaultimage.gif',
	'comments_linktothispost_icon' => 'nodefaultimage.gif',
	'comments_addacomment_icon' => 'nodefaultimage.gif',
	'comments_emailafriend_icon' => 'nodefaultimage.gif',
	'comments_linktothispost_image' => 'nodefaultimage.gif',
	'comments_addacomment_image' => 'nodefaultimage.gif',
	'comments_emailafriend_image' => 'nodefaultimage.gif',
	'post_header_separator' => 'nodefaultimage.gif',
	'body_bg' => 'nodefaultimage.gif',
	'footer_bg' => 'nodefaultimage.gif',
	'contact_bg' => 'nodefaultimage.gif',
	'nav_bg' => 'nodefaultimage.gif',
	'like_btn_homepage_image' => 'nodefaultimage.gif',
	'fb_home' => 'nodefaultimage.gif',	
);
// all ad banners are empty by default
for ( $i = 1; $i <= MAX_BANNERS; $i++ ) { 
	$p3_defaults['images']['banner'.$i] = 'nodefaultimage.gif';
}
// extra empty masthead images
for ( $i = 2; $i <= MAX_MASTHEAD_IMAGES; $i++ ) { 
	$p3_defaults['images']['masthead_image'.$i] = 'nodefaultimage.gif';
}
// extra empty bio images
for ( $i = 2; $i <= MAX_BIO_IMAGES; $i++ ) { 
	$p3_defaults['images']['biopic'.$i] = 'nodefaultimage.gif';
}
// custom widget icons are empty by default
for ( $i = 1; $i <= MAX_CUSTOM_WIDGET_IMAGES; $i++ ) { 
	$p3_defaults['images']['widget_custom_image_'.$i] = 'nodefaultimage.gif';
}

for ( $i = 1; $i <= MAX_CUSTOM_MENU_LINKS; $i++ ) {
	$p3_defaults['images']['nav_customlink'.$i.'_icon'] = 'nodefaultimage.gif';
}


/* image size recommendations.  can do W & H or just one */
$p3_img_size_recommendations = array(
	'iphone_webclip_icon' => array('width' => '57', 'height' => '57'),
);
// all ad banners have same recommendations
for ( $i = 1; $i <= MAX_BANNERS; $i++ ) { 
	$p3_img_size_recommendations['banner'.$i] = array('height' => '100', 'width' => '155');
}
?>