<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes() ?>>
<head><?php if ( WP_VERSION_FAIL ) p3_wp_version_fail() ?>
<title><?php p3_get_title_tag() ?></title><!-- p3 build #<?php echo P3_SVN ?> --> 
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ) ?>; charset=<?php bloginfo('charset' ) ?>" />
<meta http-equiv="imagetoolbar" content="no" />
<?php
// facebook like button custom meta
p3_facebook_meta();
// seo
p3_meta_description();
p3_get_meta_keywords();
p3_meta_robots(); 
// maintainence 
p3_maintenance_mode(); 
?>
<?php p3_ipad_meta() ?>
<link rel="stylesheet" type="text/css" href="<?php p3_static_file_url( 'css' ); p3_option( 'cache_buster' ); ?>" />
<link rel="alternate" type="application/rss+xml" href="<?php p3_rss_url() ?>" title="<?php echo P3_SITE_NAME ?> <?php echo 'Posts RSS feed'; ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />
<?php wp_enqueue_script('jquery' );
wp_head(); ?>
<script src="<?php p3_static_file_url( 'js' ); p3_option( 'cache_buster' ); ?>" type="text/javascript" charset="utf-8"></script>
<?php p3_ie_junk() ?>
<?php if ( p3_imageurl( 'favicon', 0 ) != '#' ) { echo "\n" ?><link rel="shortcut icon" href="<?php p3_imageurl( 'favicon' ); p3_option('cache_buster' ); ?>" type="image/x-icon" /><?php } ?> 
<?php if ( p3_image_exists('iphone_webclip_icon' ) ) { ?><link rel="apple-touch-icon" href="<?php p3_imageurl('iphone_webclip_icon' ); p3_option('cache_buster' ); ?>" /><?php } ?>
<?php p3_insert_into_head() ?>
</head>
<body <?php p3_body_class() ?>>
<?php p3_gallery_popup_markup() ?>
<?php p3_dev_workarea() ?>
<?php do_action( 'p3_begin_body' ) ?>
<div id="outer-wrap-centered">
<?php p3_dropshadow_topbottom( 'top' ) ?>
<div id="main-wrap-outer">
<div id="main-wrap-inner">
<div id="inner-wrap">	
<?php if ( p3_test( 'prophoto_classic_bar', 'on' ) ) echo '<div id="top-colored-bar"></div>'; ?>
<?php do_action( 'p3_before_header' ) ?>
<div id="header" class="self-clear">	
<?php p3_get_header() ?>
</div><!-- #header -->
<?php
p3_contact_form();
p3_get_bio();
p3_insert_audio_player( 'top' );
p3_open_content();
?>
