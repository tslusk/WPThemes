<?php
/* ------------------------------------------------ */
/* --- global flash gallery settings generation --- */
/* ------------------------------------------------ */

// load WordPress environmment
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' ); 

@header( 'Content-type: text/javascript' );

// general
$cache_buster        = apply_filters( 'p3_flash_gal_cache_buster', '?ver=' . P3_SVN );
$main_corners        = p3_get_option( 'flash_gal_main_corners' );
$main_bg_color       = p3_flash_color( p3_get_option( 'flash_gal_bg_color' ) );
$button_color        = p3_flash_color( p3_get_option( 'flash_gal_button_color' ) );
$dont_loop_playback  = ( p3_test( 'flash_gal_loop_slideshow', 'true' ) ) ? 'false' : 'true'; 
$disable_full_screen = p3_get_option( 'flash_gal_disable_full_screen' ); 
$start_full_screen   = ( p3_test( 'flash_gal_start_full_screen', 'true' ) && p3_test( 'flash_gal_disable_full_screen', 'false' ) ) ? 'true' : 'false';

// overlay
$overlay_height  = p3_convert_percentage( p3_get_option( 'flash_gal_overlay_height' ) );
$overlay_opacity = p3_convert_percentage( p3_get_option( 'flash_gal_overlay_opacity' ) );
if ( p3_optional_color_bound( 'flash_gal_overlay_color' ) ) {
	$overlay_color = p3_flash_color( p3_get_option( 'flash_gal_overlay_color' ) );
} else {
	$overlay_color = $main_bg_color;
}

// title text options
$font_path   = apply_filters( 'p3_flash_gal_font_path', P3_THEME_URL . '/flash/' );
$title_font  = p3_flash_font( p3_get_option( 'flash_gal_title_font_family' ) );
$title_size  = intval( p3_get_option( 'flash_gal_title_font_size' ) );
$title_color = p3_flash_color( p3_get_option( 'flash_gal_title_font_color' ) );


// subtitle text options
$subtitle_size  = intval( p3_get_option( 'flash_gal_subtitle_font_size' ) );
if ( p3_test( 'flash_gal_subtitle_font_family' ) ) {
	$subtitle_font  = p3_flash_font( p3_get_option( 'flash_gal_subtitle_font_family' ) );
} else {
	$subtitle_font = $title_font;
}
if ( p3_optional_color_bound( 'flash_gal_subtitle_font_color' ) ) {
	$subtitle_color = p3_flash_color( p3_get_option( 'flash_gal_subtitle_font_color' ) );
} else {
	$subtitle_color = $title_color;
}

// logo
$logo = ( p3_image_exists( 'flash_gal_logo' ) ) 
	? '"logoURL": "' . p3_imageurl( 'flash_gal_logo', NO_ECHO ) . '",' : '';

// slideshow
$auto_start                  = p3_get_option( 'flash_gal_auto_start' );
$slideshow_duration          = p3_flash_number( p3_get_option( 'flash_gal_slideshow_duration' ) );
$slideshow_effect            = p3_get_option( 'flash_gal_slideshow_transition_effect' );
$slideshow_transistion_speed = p3_flash_number( p3_get_option( 'flash_gal_slideshow_transition_speed' ) );
$disable_slideshow_timer     = p3_get_option( 'flash_gal_disable_slideshow_timer' );

// thumbnails/filmstrip position - this sux because of the way Carnevale coded the flash object
$filmstrip_position = p3_get_option( 'flash_gal_filmstrip_position' );
switch ( $filmstrip_position ) {
	case 'top':
		$vert = $pos = 'false';
		break;
	case 'bottom':
		$vert = 'false';
		$pos  = 'true';
		break;
	case 'right':
		$vert = $pos = 'true';
		break;
	case 'left':
		$vert = 'true';
		$pos  = 'false';
		break;
}
$filmstrip_position_output = '"thumbsVertical": ' . $vert . ",\n\t\"thumbsPosition\": " . $pos . ',';

// filmstrip & controls area
$thumbs_overlay           = p3_get_option( 'flash_gal_filmstrip_overlay' );
$icon_opacity             = p3_convert_percentage( p3_get_option( 'flash_gal_icon_opacity' ) );
$auto_hide_thumbs         = p3_get_option( 'flash_gal_filmstrip_autohide' );
$auto_hide_thumbs_timeout = p3_flash_number( p3_get_option( 'flash_gal_filmstrip_autohide_timeout' ) );
if ( p3_optional_color_bound( 'flash_gal_filmstrip_bg_color' ) ) {
	$thumbstrip_color = p3_flash_color( p3_get_option( 'flash_gal_filmstrip_bg_color' ) );
} else {
	$thumbstrip_color = $main_bg_color;
}
$thumbstrip_opacity = ( p3_test( 'flash_gal_filmstrip_overlay', 'false' ) ) 
	? '1' : p3_convert_percentage( p3_get_option( 'flash_gal_filmstrip_opacity' ) );


// thumbnail images
$thumb_size         = intval( p3_get_option( 'flash_gal_thumb_size' ) );
$thumb_border_color = p3_flash_color( p3_get_option( 'flash_gal_thumb_border_color' ) );
$thumb_border_width = intval( p3_get_option( 'flash_gal_thumb_border_width' ) );
$thumb_padding      = intval( p3_get_option( 'flash_gal_thumb_padding' ) );
$thumb_corners      = intval( p3_get_option( 'flash_gal_thumb_corners' ) );

// thumbnail efect
$thumb_effect       = p3_get_option( 'flash_gal_thumb_effect' );
$thumb_effect_speed = p3_flash_number( p3_get_option( 'flash_gal_thumb_effect_speed' ) );
$thumb_effect_over  = p3_convert_percentage( p3_get_option( 'flash_gal_thumb_effect_over' ) );
$thumb_effect_out   = p3_convert_percentage( p3_get_option( 'flash_gal_thumb_effect_out' ) );

// mp3 playback
$mp3_autostart = p3_get_option( 'flash_gal_mp3_autostart' );
$mp3_loop      = p3_get_option( 'flash_gal_mp3_loop' );

// pathfixer 
$old_address_array = p3_pathfixer( false );
if ( !empty( $old_address_array ) ) {
	$pathfixer  = '"currentPath" : "' . trailingslashit( P3_WPURL ) . WP_UPLOAD_PATH . '",';
	$pathfixer .= '"oldPaths" : [';
	$pathfixer .= '"' . implode( '","', $old_address_array ) . '"],';
}


/* the output */
$output = <<<JAVASCRIPT
// do not edit this file, it is created by the theme, any edits will be lost
// global flash gallery settings

{
	// general
	$pathfixer
	"cacheBuster" : "$cache_buster",
	"mainPadding" : 0,
	"galleryBackground" : {
		"color": $main_bg_color,
		"alpha": 0,
		"corners": $main_corners
	},
	"mainBackground" : {
		"color": $main_bg_color,
		"alpha": 1,
		"corners": 0
	},
	"mainImageCorners": 0,
	"disableFullscreen" : $disable_full_screen,
	"startFullScreen" : $start_full_screen,
	"skipSplashScreen" : false,
	"buttonColor" : $button_color,
	"dontLoopSlideShow" : $dont_loop_playback,
	
	// initial overlay
	"initOverlayColor" : $overlay_color,
	"initOverlayOpacity" : $overlay_opacity,
	"initOverlayHeight" : $overlay_height,
	"titleFont": "{$title_font}",
	"fontPath" : "$font_path",
	"titleSize": $title_size,
	"titleColor": $title_color,
	"subTitleFont": "{$subtitle_font}",
	"subTitleSize": $subtitle_size,
	"subTitleColor": $subtitle_color,
	$logo
	
	// slideshow
	"autoStartSlideshow" : $auto_start,
	"mainTransitionType" : "{$slideshow_effect}",
	"mainTransitionSpeed" : $slideshow_transistion_speed,
	"slideShowHoldTime" : $slideshow_duration,
	"takeThumbsControlTime" : 5,
	"disableTimerGraphic": $disable_slideshow_timer,
	
	// thumbs
	"thumbWidth" : $thumb_size,
	"thumbHeight" : $thumb_size,
	"requestedThumbSpacing" : $thumb_padding,
	"thumbPadding" : $thumb_padding,
	"thumbImageCorners": $thumb_corners,
	"thumbsBorder" : {
		"color": $thumb_border_color,
		"width": $thumb_border_width
	},
	
	// thumb effect
	"thumbTransitionType" : "{$thumb_effect}",
	"thumbTransitionOverAmount" : $thumb_effect_over,
	"thumbTransitionOutAmount" : $thumb_effect_out,
	"thumbTransitionSpeed" : $thumb_effect_speed,
	
	// thumbstrip
	$filmstrip_position_output
	"autoHideThumbs" : $auto_hide_thumbs,
	"autoHideHoldTime" : $auto_hide_thumbs_timeout,
	"iconOpacity" : $icon_opacity,
	"thumbsOverlay" : $thumbs_overlay,
	"thumbsBackground" : {
		"color": $thumbstrip_color,
		"alpha": $thumbstrip_opacity,
		"corners": { "tl": 0, "br": 0 }
	},
	
	// mp3
	"loopMP3" : $mp3_loop,
	"autoStartMP3" : $mp3_autostart
}
JAVASCRIPT;

echo apply_filters( 'p3_galleryjson_output', $output );
?>