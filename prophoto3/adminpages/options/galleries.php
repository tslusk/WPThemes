<?php
/* -------------------------------- */
/* -- gallery options input page -- */
/* -------------------------------- */

// tabs and header
p3_subgroup_tabs( array( 'flash' => 'Flash Slideshow Galleries', 'lightbox' => 'Lightbox Overlay Galleries',
) );
p3_option_header( 'Galleries Options', 'galleries' );



/* flash gallery slideshow subgroup */
p3_option_subgroup( 'flash' );

// intro note
p3_o( 'flash_gallery_note', 'note', 'Click <a href="' . CREATING_GALLERIES_TUT . '" target="_blank">here</a> for an overview about creating and managing ProPhoto galleries.', 'Flash galleries overview' );


// general options
p3_start_multiple( 'General options' );
p3_o( 'flash_gal_bg_color', 'color', 'background color of slideshow player' );
p3_o( 'flash_gal_main_corners', 'slider|0|30| pixels', 'rounded corner radius of slideshow player (0 = no corner rounding)' );
p3_o( 'flash_gal_button_color', 'color', 'color of control buttons' );
p3_o( 'flash_gal_fallback', 'radio|lightbox|show lightbox gallery|images|show all full-sized images|remove|show nothing', 'iPad/iPhone fallback' );
p3_o( 'flash_gal_disable_full_screen', 'radio|false|allow slideshow to fullscreen|true|do not allow fullscreen' );
p3_o( 'flash_gal_start_full_screen', 'radio|true|immediately fullscreen when clicked|false|user chooses if/when to fullscreen' );
p3_stop_multiple();


// overlay options
p3_start_multiple( 'Initial overlay options' );
p3_o( 'flash_gal_overlay_height', 'slider', 'height of initial overlay as a percentage of the overall slideshow height' );
p3_o( 'flash_gal_overlay_opacity', 'slider', 'opacity of initial overlay' );
p3_o( 'flash_gal_overlay_color', 'color|optional', 'color of initial overlay' );
p3_stop_multiple();


// overlay main-title font
p3_font_group( array(
	'title' => 'Initial overlay main title text',
	'key' => 'flash_gal_title',
	'not' => array( 'weight', 'style', 'transform' ),
) );

// overlay sub-title font
p3_font_group( array(
	'title' => 'Initial overlay sub-title text',
	'key' => 'flash_gal_subtitle',
	'inherit' => 'all',
	'not' => array( 'weight', 'style', 'transform' ),
) );



// logo
p3_image( 'flash_gal_logo', 'Initial overlay logo' );


// slideshow options
p3_start_multiple( 'Slideshow options' );
p3_o( 'flash_gal_auto_start', 'radio|true|slideshow plays when gallery clicked|false|slideshow paused when gallery clicked', 'after user clicks to see the flash gallery, should the slideshow start playing immediately or do they start by browsing static images, with the slideshow paused' );
p3_o( 'flash_gal_loop_slideshow', 'radio|true|loop slideshow|false|do not loop slideshow', 'what to do when slideshow finishes' );
p3_o( 'flash_gal_slideshow_transition_effect', 'radio|fade|fade out, fade in|crossFade|cross-fade|slide|slide horizontally|jump|no transition effect', 'transition effect style between images' );
p3_o( 'flash_gal_disable_slideshow_timer', 'radio|false|show timer|true|do not show timer', 'show/hide the animated timer during slideshow playback' );
p3_o( 'flash_gal_slideshow_duration', 'slider|0.1|7.0| seconds|0.1', 'time each slide is shown during slideshow' );
p3_o( 'flash_gal_slideshow_transition_speed', 'slider|0.1|4.0| seconds|0.1', 'time of slideshow transition effect' );
p3_stop_multiple();


// filmstrip location
p3_start_multiple( 'Thumbnail filmstrip &amp controls location' );
p3_o( 'flash_gal_filmstrip_position', 'radio|top|top|bottom|bottom|right|right|left|left', 'position of filmstrip/controls area' );
p3_o( 'flash_gal_filmstrip_overlay', 'radio|true|overlay|false|separate', 'show filmstrip/controls overlaid on top of main images, or separately' );
p3_o( 'flash_gal_filmstrip_autohide', 'radio|true|yes, show and hide|false|no, always show', 'filmstrip/controls area shows and hides based on user mouse movement' );
p3_o( 'flash_gal_filmstrip_autohide_timeout', 'slider|0|4| seconds|0.2', 'how long (in seconds) until filmstrip/controls auto-hides after user\'s last mouse movement' );
p3_stop_multiple();


// thumbstrip appearance
p3_start_multiple( 'Thumbnail filmstrip &amp controls appearance' );
p3_o( 'flash_gal_filmstrip_bg_color', 'color|optional', 'background color of filmstrip/controls area' );
p3_o( 'flash_gal_icon_opacity', 'slider', 'opacity of controls buttons (play/pause, fullscreen, music, shopping cart)' );
p3_o( 'flash_gal_filmstrip_opacity', 'slider', 'opacity of filmstrip/controls area background' );
p3_stop_multiple();


// thumbnail images
p3_start_multiple( 'Thumbnail images' );
p3_o( 'flash_gal_thumb_size', 'slider|10|' . WP_THUMB_WIDTH . '| pixels', 'size of thumbnail images' );
p3_o( 'flash_gal_thumb_padding', 'slider|0|60| pixels', 'spacing between thumbnail images' );
p3_o( 'flash_gal_thumb_border_color', 'color', 'color of border around thumbnail images' );
p3_o( 'flash_gal_thumb_border_width', 'slider|0|20| pixels', 'width of border around thumbnail images' );
p3_o( 'flash_gal_thumb_corners', 'slider|0|20| pixels', 'Rounded corner radius of thumbnail images. 0 = no corner rounding.' );
p3_stop_multiple();


// thumbnail transitions
p3_start_multiple( 'Thumbnail mouseover effect' );
p3_o( 'flash_gal_thumb_effect', 'radio|fade|fade opacity|scale|magnify', 'effect seen when user mouses over thumbnail' );
p3_o( 'flash_gal_thumb_effect_speed', 'slider|0.1|3.0| seconds|0.1', 'duration of the thumbnail effect' );
p3_o( 'flash_gal_thumb_effect_over', 'slider', 'effect amount when user mouses over thumbnail image' );
p3_o( 'flash_gal_thumb_effect_out', 'slider', 'effect amount when thumbnail image not being moused over' );
p3_stop_multiple();


// mp3 options
p3_start_multiple( 'MP3 playback' );
p3_o( 'flash_gal_mp3_autostart', 'radio|true|autostart|false|click to play', 'linked MP3 file starts automatically, or requires user to click to play' );
p3_o( 'flash_gal_mp3_loop', 'radio|true|loops|false|does not loop', 'MP3 should loop after ending, or just end and not loop' );
p3_stop_multiple();

p3_end_option_subgroup();



/* javascript lightbox subgroup */
p3_option_subgroup( 'lightbox' );


p3_o( 'lightbox_gallery_note', 'note', 'Click <a href="' . CREATING_GALLERIES_TUT . '" target="_blank">here</a> for an overview about creating and managing ProPhoto galleries.', 'Lightbox galleries overview' );


// thumbnails
p3_start_multiple( 'Lightbox gallery thumbnails' );
p3_o( 'lightbox_thumb_default_size', 'slider|30|200| px', 'requested size of Lightbox thumbnails - actual display sizes will vary slightly to create neatly-lined up rows' );
p3_o( 'lightbox_thumb_opacity', 'slider', 'opacity of thumbnails when not hovered over' );
p3_o( 'lightbox_thumb_mouseover_speed', 'slider|0|1500| milliseconds|50', 'time it takes for thumbnail to fade to full opacity when hovered over' );
p3_o( 'lightbox_thumb_mouseout_speed', 'slider|0|1500| milliseconds|50', 'time it takes for thumbnail to fade back to partial opacity after mouse stops hovering' );
p3_o( 'lightbox_thumb_margin', 'slider|0|60| px', 'spacing between thumbnails' );
p3_o( 'lightbox_centering', 'checkbox|lightbox_main_img_center|true|center main image|lightbox_thumbs_center|true|center thumbnails', 'horizontal centering of gallery images' );
p3_stop_multiple();


// general options
p3_start_multiple( 'Lightbox overlay general appearance' );
p3_o( 'lightbox_border_width', 'slider|0|50| px', 'width of border around lightbox overlay image' );
p3_o( 'lightbox_bg_color', 'color', 'color of border/background around lightbox images' );
p3_stop_multiple();

// image info text
p3_font_group( array(
	'title' => 'Image info text appearance',
	'key' => 'lightbox',
	'not' => array( 'weight', 'style', 'transform' ),
) );

// overlay
p3_start_multiple( 'Lightbox overlay effects' );
p3_o( 'lightbox_overlay_color', 'color', 'color of background faded area when image is clicked' );
p3_o( 'lightbox_overlay_opacity', 'slider', 'opacity of background overlay faded area' );
p3_o( 'lightbox_resize_speed', 'slider|0|1500| milliseconds|50', 'box resize speed between images' );
p3_o( 'lightbox_image_fadespeed', 'slider|0|1500| milliseconds|50', 'main image fade in/fade out speed' );
p3_stop_multiple();


// image navigation
p3_start_multiple( 'Lightbox overlay image navigation' );
p3_o( 'lightbox_fixed_navigation', 'radio|true|always show "prev/next" buttons|false|show only when mousing over image' );
p3_o( 'lightbox_nav_btns_opacity', 'slider', 'opacity of prev/next image navigation buttons' );
p3_o( 'lightbox_nav_btns_fadespeed', 'slider|0|1500| milliseconds|50', 'speed of fade in/fade out of prev/next buttons when image is moused over' );
p3_stop_multiple();


// lightbox images
p3_image('lightbox_loading', 'Lightbox overlay loading image' );
p3_image('lightbox_close', 'Lightbox overlay close image button' );
p3_image('lightbox_next', 'Lightbox overlay next image button' );
p3_image('lightbox_prev', 'Lightbox overlay previous image button' );

p3_end_option_subgroup();

?>