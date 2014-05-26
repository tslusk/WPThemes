<?php
/* ---------------------------------------------------------------- */
/* -- library of general functions for the theme (always loaded) -- */
/* ---------------------------------------------------------------- */

global $p3;

/* WordPress actions, filters */
// When everything starts, trigger conditional events if needed
add_action( 'init', 'p3_theme_startup' );
// On 'admin_menu' hook, add our menus
add_action( 'admin_menu', 'p3_add_options_page' );
add_action( 'admin_menu', 'p3_add_settings_page' );
// Upload hooks
add_action( 'media_upload_image_p3', 'p3_do_image_uploaded' ); // What to do when an image is uploaded
add_action( 'media_upload_misc_p3', 'p3_do_misc_uploaded' ); // What to do when a misc file is uploaded
// p3_feedburner_redirect
add_action( 'template_redirect', 'p3_feedburner_redirect' );
// This hooks into whenever a #media-items is added (WP media uploader)
add_filter( 'media_meta', 'p3_collect_image_list', -100 );


/* Main function used to trigger events when the theme loads */
function p3_theme_startup() {
	global $p3;

	// Initialize theme options
	p3_read_options();
	
	// regenerate static files if theme update
	p3_theme_update();
	
	// ajax widget save
	if ( $_GET['ajax_widget_save'] == 'true' ) {
		if ( current_user_can( 'level_1' ) ) p3_store_options();
		exit;
	}
	
	// silently log ajax comment error to db
	if ( $_POST['ajax_comment_error'] ) {
		$p3_store = get_option( P3_DB_STORAGE_NAME );
		$ajax_errors = $p3_store['ajax_comment_errors'];
		$ajax_errors[] = array( 
			'XMLHttpRequest' => $_POST['XMLHttpRequest'], 
			'textStatus' => $_POST['textStatus'],
			'errorThrown' => $_POST['errorThrown'],
			'userAgent' => $_SERVER['HTTP_USER_AGENT'],
			'time' => time(),
		);
		$p3_store['ajax_comment_errors'] = $ajax_errors;
		$p3_store = p3_strip_corrupters_deep( $p3_store );
		if ( p3_safe_to_store( P3_DB_STORAGE_NAME, $p3_store ) ) {
			update_option( P3_DB_STORAGE_NAME, $p3_store );
		}
		exit;
	}
	
	// display ajax comment errors for debugging
	if ( $_GET['show_ajax_comment_errors'] ) {
		$p3_store = get_option( P3_DB_STORAGE_NAME );
		p3_debug_array( $p3_store['ajax_comment_errors'] );
		exit;
	}

	// if we've just activated the theme
	global $pagenow;
	if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == "themes.php" ) {
		
		// establish ProPhoto unique id, blog identifier
		p3_set_uid();
		
		// setup default widgets
		p3_default_widget_instances();

		// generate static files
		p3_generate_static_files();
		
		// simplify standard upload path for common error prevention
		if ( strpos( WP_UPLOAD_PATH, 'wp-content/uploads' ) !== FALSE  ) 
			update_option( 'upload_path', 'wp-content/uploads' );
		
		// schedule the monthly backup reminder function to run on 1st of month, min two weeks away	
		wp_clear_scheduled_hook( 'p3_backup_remind_action' );
		wp_schedule_event( time() + 1209600, 'daily', 'p3_backup_remind_action' );
		
		// schedule the monthly out-of-date WordPress nag email	
		wp_clear_scheduled_hook( 'p3_wp_hack_warning_action' );
		wp_schedule_event( time(), 'daily', 'p3_wp_hack_warning_action' );

		// change some defaults to be more photog-friendly
		update_option( 'blog_public', 1 );
		if ( get_option( 'posts_per_page' ) == 10 ) update_option( 'posts_per_page', 5 );
		if ( get_option( 'default_post_edit_rows' ) == 10 ) update_option( 'default_post_edit_rows', 20 );
		if ( get_option( 'blogdescription' ) == 'Just another WordPress site' ) 
			update_option( 'blogdescription', 'Blog' );
		
		// first-ever activation welcome procedure
		if ( ( !p3_test( 'payer_email' ) || !p3_test( 'txn_id' ) ) || $_GET['p3activate'] == 'inprocess' ) {
			require_once( 'welcome.php' );
			p3_activation_welcome();
		}

		// disable admin bar
		if ( !p3_test( 'admin_bar_disabled', 'true' ) ) {
			global $wpdb, $p3;
			$wpdb->update( $wpdb->usermeta, array( 'meta_value' => 'false' ), array( 'meta_key' => 'show_admin_bar_front' ) );
			$wpdb->update( $wpdb->usermeta, array( 'meta_value' => 'false' ), array( 'meta_key' => 'show_admin_bar_admin' ) );
			$user = wp_get_current_user();
			update_user_option( $user->ID, 'show_admin_bar_front', 'false', true );
			update_user_option( $user->ID, 'show_admin_bar_admin', 'false', true );
			$p3['non_design']['admin_bar_disabled'] = 'true';
			p3_store_options();
		}
		
	}
	
	// On WP built in media uploader, add our library
	if ( $pagenow == 'media-upload.php' ) {
		p3_enqueue_script( '/adminpages/js/wp_media_upload.js' );	   
		add_action( 'admin_head', 'p3_media_upload_head' );
		
	// on post/page writing/editing type pages, do stuff
	} else if ( $pagenow == 'page-new.php' OR 
		 		$pagenow == 'post-new.php' OR 
		 		$pagenow == 'post.php' 	   OR 
		 		$pagenow == 'page.php' ) {
		add_action( 'admin_notices', 'p3_upload_width_advisement' );
		p3_enqueue_script( '/adminpages/js/posting.js' );
		add_action( 'admin_head', 'p3_posting_css' );
		add_filter( 'mce_external_plugins','p3_tinymce_plugins' );
		add_filter( 'mce_buttons_2','p3_tinymce_buttons' );
	
	// add p3 stuff into widgets page
	} else if ( $pagenow == 'widgets.php' ) {
		p3_enqueue_script( '/adminpages/colorpicker/farbtastic.js' );
		p3_enqueue_script( '/adminpages/js/widgets.js' );
		p3_enqueue_style( '/adminpages/css/widgets.css' );
		p3_enqueue_style( '/adminpages/colorpicker/farbtastic.css' );
		add_action( 'admin_head', 'p3_widgets_admin_head' );

	// theme editor warning
	} else if ( $pagenow == 'theme-editor.php' ) {
		add_action( 'admin_notices', create_function( '', 'p3_error_msg( "dont_edit_theme_files" );' ) );
	
	// blog url change warning
	} else if ( $pagenow == 'options-general.php' ) {
		add_action( 'admin_head', create_function( '', 'p3_msg( "wpurl_change_warning_js" );' ) );
	
	// menus page
	} else if ( $pagenow == 'nav-menus.php' ) {
		add_action( 'admin_notices', create_function( '', 'p3_error_msg( "wp_menus_not_used", p3_get_admin_url( "menu" ) );' ) );
	}
	
	// deny MU
	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		if ( is_admin() ) add_action( 'admin_notices', create_function( '', 'p3_error_msg( "wpmu_warning" );' ) );
		else die( $p3_notices['wpmu_warning'] );
	}

	// tech support
	if ( $_GET['open'] == 'sesame' ) setcookie( 'p3tech', 'true', ONE_YEAR_FROM_NOW );
	if ( $_GET['p3support'] == 'true' || $_GET['ppblogdata'] == 'show' ) {
		require( 'support.php' );
		p3_tech_support();
		exit;
	}
	
	// Add content path fixer for blogs that have moved and have broken image paths
	add_filter( 'the_content', 'p3_pathfixer', 1000 );
	
	// Add content filter to fix bad image title and alt tags from a WordPress 3.1 / "P3 Insert All" bug that was inserting "undefined" values
	add_filter( 'the_content', 'p3_title_alt_bug_fixer', 1001 );
	
	// add stuff into all admin pages head
	if ( is_admin() ) {
		add_action( 'admin_head', 'p3_info_for_js' );
		add_action( 'admin_head', 'p3_load_remote_js' );
		p3_enqueue_style( '/adminpages/css/admin.css' );
		p3_backup_plugin_nag();
	}
	
	// force img link url default to none
	if ( is_admin() ) update_option( 'image_default_link_type', 'none' );
	
	// akismet notice
	if ( $pagenow == 'edit-comments.php' && !function_exists( 'akismet_init' ) ) {
		add_action( 'admin_notices', create_function( '', 'p3_error_msg( "akismet_notice" );' ) );
	}
	
	// force UTF-8 encoding
	if ( is_admin() ) update_option( 'blog_charset', 'UTF-8' );
	
	// ProPhoto content filters
	if ( is_callable( 'p3_like_button' ) )
		add_filter( 'the_content', 'p3_like_button', p3_get_option( 'like_btn_filter_priority' ) );
	if ( is_callable( 'p3_post_signature' ) )
		add_filter( 'the_content', 'p3_post_signature', p3_get_option( 'post_signature_filter_priority' ) );
}


/* load remote js after everything else is done, via ajax, to prevent hangs */
function p3_load_remote_js() {
	$script = STATIC_RESOURCE_URL . 'js/p3remote.js?ver=' . date( 'ymdH' );
	if ( ( $GLOBALS['pagenow'] == 'themes.php' ) AND ( $_GET['page'] == 'p3-customize' ) ) {
		$p3_tab = ( $_GET['p3_tab'] ) ? $_GET['p3_tab'] : 'background';
		$tab_script = STATIC_RESOURCE_URL . 'js/p3remote-' . $p3_tab . '.js?ver=' . date( 'ymdH' );
		$tab_script_get = "setTimeout(function(){jQuery.getScript('$tab_script');},1250);";
	}
	echo "\n" . <<< HTML
	<script type="text/javascript" charset="utf-8">
		jQuery(window).load(function(){
			jQuery.getScript('$script');
			$tab_script_get
		});
	</script>
HTML;
}


/* the "P3 options" menu */
function p3_add_options_page() {
	if ( p3_dev_hide_options() ) return;
	$options = add_theme_page( 
		"ProPhoto3 &raquo; P3 Customize", // Page title
		"P3 Customize", 				// menu title.
		'edit_themes',					// User min level
		'p3-customize',					// page identifier (for url: themes.php?page=p3-options) 
		'p3_options_page' 				// function
	);
	add_action( "load-$options", 'p3_load_options_page' );
}


/* The "Settings" menu */
function p3_add_settings_page() {
	if ( p3_dev_hide_options() ) return;
	$settings = add_theme_page( 
		"ProPhoto3 &raquo; P3 Designs", // page title
		"P3 Designs",					// menu title. 
		'edit_themes',					// user min level
		'p3-designs',					// page identifier (for url: themes.php?page=p3-upload)
		'p3_designs_page'				// function
	); 
	add_action( "load-$settings", 'p3_load_settings_page' );
}


/* boolean test - are we hiding options from end user? */
function p3_dev_hide_options() {
	return ( p3_test( 'dev_hide_options', 'true' ) && !nrIsIn( 'pp_dev', $_SERVER['HTTP_USER_AGENT'] ) );
}


/* our own custom wrapper for the wp_enqueue_style function */
function p3_enqueue_style( $endpath ) {
	$handle = _p3_get_enqueue_handle( $endpath );
	wp_enqueue_style( $handle, P3_THEME_URL . $endpath, FALSE, P3_SVN );
}


/* our own custom wrapper for the wp_enqueue_script function */
function p3_enqueue_script( $endpath ) {
	$handle = _p3_get_enqueue_handle( $endpath );
	wp_enqueue_script( $handle, P3_THEME_URL . $endpath, FALSE, P3_SVN );
}


/* subroutine to get a unique enqueue handle from filename */
function _p3_get_enqueue_handle( $endpath ) {
	$path_parts = explode( '/', $endpath );
	$last_index = count( $path_parts ) - 1;
	$handle = 'p3_' . str_replace( '.', '', $path_parts[$last_index] );
	return $handle;
}


/* Load everything regarding the "Options" page if needed (called by p3_add_options_page()) */
function p3_load_options_page() {
	// javascript
	wp_enqueue_script( 'jquery-ui-draggable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	
	if ( intval( str_pad( intval( str_replace( '.', '', $GLOBALS['wp_version'] ) ), 3, '0' ) ) < 310 ) {
		wp_enqueue_script( 'jquery-ui-slider', P3_THEME_URL . '/adminpages/js/jquery.ui.slider.1.7.2.js', array( 'jquery-ui-core' ), FALSE, TRUE );
		wp_enqueue_style( 'jquery-ui-theme-redmond', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/themes/smoothness/jquery-ui.css' );
		
	} else {
		wp_enqueue_script( 'jquery-ui-slider', P3_THEME_URL . '/adminpages/js/jquery.ui.slider.1.8.5.js', array( 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-mouse' ), FALSE, TRUE );
		wp_enqueue_style( 'jquery-ui-theme-redmond', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/themes/smoothness/jquery-ui.css' );
		
	}
	
	p3_enqueue_script( '/adminpages/js/options.js' );
	p3_enqueue_script( '/adminpages/js/fontpreview.js' );
	p3_enqueue_script( '/adminpages/js/upload.js' );
	p3_enqueue_script( '/adminpages/colorpicker/farbtastic.js' );
	wp_enqueue_script( 'thickbox' );
	
	// css
	p3_enqueue_style( '/adminpages/colorpicker/farbtastic.css' );
	p3_enqueue_style( '/adminpages/css/common.css' );
	p3_enqueue_style( '/adminpages/css/options.css' );
	wp_enqueue_style( 'p3_fontpreview', p3_static_file_url( 'preview', NO_ECHO ), FALSE, p3_get_option( 'cache_buster' ) );
	p3_enqueue_style( '/adminpages/css/upload.css' );
	wp_enqueue_style( 'thickbox' );
	
}


/* Load everything regarding the "Upload" page if needed (called by p3_add_upload_page()) */
function p3_load_upload_functions() {
	require_once( dirname( dirname( __FILE__ ) ) . '/functions/upload.php' );
}


/* Load everything regarding the "Settings" page if needed (called by p3_add_settings_page()) */
function p3_load_settings_page() {
	// javascript
	p3_enqueue_script( '/adminpages/js/designs.js' );
	wp_enqueue_script( 'thickbox' );
	
	// css
	p3_enqueue_style( '/adminpages/css/common.css' );
	p3_enqueue_style( '/adminpages/css/designs.css' );
	wp_enqueue_style( 'thickbox' );
}


/* On popup.php we need to load the upload functions */
function p3_do_image_uploaded() {
	p3_load_upload_functions();
	p3_image_uploaded();
}
function p3_do_misc_uploaded() {
	p3_load_upload_functions();
	p3_image_uploaded( TRUE );
}


/* echoes CSS into widgets admin head */
function p3_widgets_admin_head() {
	echo '<style type="text/css" media="screen">' . P3_Twitter_Slider::css() . '</style>';
	for ( $i = 1; $i <= MAX_CUSTOM_WIDGET_IMAGES; $i++ ) { 
		if ( !p3_image_exists( 'widget_custom_image_' . $i ) ) continue;
		$image_source = p3_imageurl( 'widget_custom_image_' . $i, NO_ECHO );
		$image_width  = p3_imagewidth( 'widget_custom_image_' . $i, NO_ECHO );
		$array .= "\n\tp3_custom_images['$i'] = '$image_source';";
		$array .= "\n\tp3_custom_images['{$i}-width'] = '$image_width';"; 
	}
	$slider_js = P3_Twitter_Slider::js();
	echo <<<HTML
	<script type="text/javascript" charset="utf-8">
	p3_custom_images = new Object();
	$array
	$slider_js
	</script>
HTML;
}


/* print admin notice of max content width for image upload recommendation */
function p3_upload_width_advisement() {
	if ( !p3_test( 'sidebar', 'false' ) && 
		  p3_test( 'sidebar_on_index', 'yes' ) &&
		  p3_test( 'sidebar_on_single', 'yes' ) &&
		  p3_test( 'sidebar_on_page', 'yes' ) &&
		  p3_test( 'sidebar_on_archive', 'yes' ) ) {
		$max_width = p3_image_max_widths( 'with_sidebar_max_image_width' );
	} else {
		$max_width = p3_image_max_widths( 'max_image_width' );
	}
	p3_updated_msg( 'recommended_img_max_upload_width', $max_width );
}


/* js for media upload head */
function p3_media_upload_head() {
	$insert_all_align =  'align' . p3_get_option( 'insert_all_align' );
	echo <<<HTML
	<script type="text/javascript" charset="utf-8">
	var p3_insert_all_align = '$insert_all_align';
	
	function p3_mp3_select_change( changed ) {
		if ( changed.val() == 'add' ) {
			jQuery('#p3-add-mp3').show();
		} else {
			jQuery('#p3-add-mp3').hide();
		}
	}
	</script>
	<style type="text/css" media="screen">
		#p3_all_images {
			display:none;
		}
		button.urlpost {
			display:none;
		}
		#media-upload .p3_btn {
			margin-left:3px;
			background-color:#ededed;
			background-image:none;
		}
		body.p3crunching .p3_btn {
			display:none !important;
		}
		body.p3crunching .clicked {
			display:inline !important;
		}
		*:first-child+html #p3_all_images {
			width:100px;
		}
		*:first-child+html #p3_lightbox {
			width:140px;
		}
		*:first-child+html #p3_flash {
			width:190px;
		}
		form.p3-flash-gallery-tab {
			padding:0.1em 1.7em;
		}
		form.p3-flash-gallery-tab label {
			margin:12px 0 3px 0;
			display:block;
		}
		form.p3-flash-gallery-tab input {
			width:100%;
		}
		#auto_start {
			margin-top:10px;
		}
		#slideshow-speed {
			margin:14px 0;
		}
		#slideshow-speed input {
			width:auto;
		}
		#slideshow-speed label {
			display:inline;
		}
		form.p3-flash-gallery-tab #auto_start input {
			width:auto;
		}
		form.p3-flash-gallery-tab #auto_start label {
			display:inline;
			margin-left:4px;
		}
		form.p3-flash-gallery-tab input.savebutton {
			margin-top:20px;
			width:auto;
		}
		#p3-add-mp3 {
			display:none;
		}
		select#p3-mp3 {
			display:block;
		}
		#appended-p3btns {
			margin-left:10px;
		}
		#thumb_size, #slideshow_speed {
			text-align:right;
			padding:1px;
		}
		#show_main_image {
			margin:15px 0;
		}
		#show_main_image input {
			margin-right:3px;
		}
		#gallery-settings .title span {
			font-size:.8em;
			font-style:italic;
		}
		.shrinktabs ul#sidemenu {
			font-size:11px;
			padding-left:1px;
		}
		.shrinktabs ul#sidemenu a {
			padding:0 6px !important;
		}
		.super-shrinktabs ul#sidemenu {
			font-size:10px;
			padding-left:1px;
		}
		.super-shrinktabs ul#sidemenu a {
			padding:0 5px !important;
		}
	</style>
HTML;
}


/* make the theme screenshot BIGGER damn it. It deserves it. */
add_action('load-themes.php', create_function( '', "add_action('admin_head', 'p3_bigger_screenshot_please' );" ) );
function p3_bigger_screenshot_please() {
	echo '<style type="text/css">
	#current-theme img {width:300px}
	</style>
	';
}


/* check if built-in slideshow will be used, prevent flash script errors */
function p3_using_default_flash_header() {
	if ( !p3_test( 'masthead_display', 'slideshow' ) ) return FALSE;
	// check to make sure at least two images are uploaded
	for ( $counter = 2; $counter <= MAX_MASTHEAD_IMAGES; $counter++ ) {
		$img = 'masthead_image' . $counter;
		if ( p3_image_exists( $img ) ) return TRUE;
	}
	return FALSE;	
}


/* boolean test, if using custom header flash, and files present */
function p3_using_custom_flash_header() {
	if ( !p3_test( 'masthead_display', 'custom' ) ) return FALSE;
	if ( !p3_file_exists( 'masthead_custom_flash' ) ) return FALSE;
	return TRUE;
}


/* boolean, will swfobject.js be needed */
function p3_needs_swfobject() {
	if ( p3_using_default_flash_header() ) return TRUE;
	if ( p3_using_custom_flash_header() ) return TRUE;
	if ( p3_test( 'logo_swf_switch', 'on' ) ) return TRUE;
	if ( !p3_test( 'audioplayer', 'off' ) ) return TRUE;
	return FALSE;
}


/* removes ' and " for when they foul things up */
function p3_sanitizer( $option, $echo = TRUE, $js = FALSE ) {
	$amp = "%26"; $quot = "%22";
	if ( $js ) { $amp = "&amp;"; $quot = "&quot;"; }
	$sanitized = p3_option( $option, FALSE );
	$sanitized = str_replace( "&", $amp, $sanitized );
	$sanitized = str_replace( '"', $quot, $sanitized );
	if ( $echo ) echo $sanitized;
	return $sanitized;
}


/* checks for extra bio images to initiate js randomizer */
function p3_using_random_biopics() {
	if ( !p3_test( 'biopic_display', 'random' ) ) return FALSE;
	// increment starts at 2 to test if at least 2 biopics, for randomization
	for ( $i = 2; $i <= MAX_BIO_IMAGES; $i++ ) { 
		if ( p3_image_exists( 'biopic' . $i ) ) return TRUE;
	}
	return FALSE;
}


/* add inline script to every image in #media-items (WP media uploader) */
function p3_collect_image_list( $in = '', $in2 = '' ) {
	echo '<script type="text/javascript">p3_crunching_add_buttons();</script>';
	return "$in";
}


/* add some CSS to the posting page */
function p3_posting_css() {
	echo <<<HTML
	<style type="text/css" media="screen">
		#p3-gallery-meta { display:none; }
	</style>
HTML;
}


/* use subversion number to auto-regenerate static files on theme update */
function p3_theme_update() {
	global $p3;
	if ( !is_array( $p3 ) ) return;
	if ( !$p3['non_design']['svn'] || intval( $p3['non_design']['svn'] ) < P3_SVN ) {
		$p3['non_design']['svn'] = P3_SVN;
		p3_store_options();
	}
}


/* set up js info object for remote notification */
function p3_info_for_js() {
	$blog_url    = P3_URL;
	$wpversion   = str_pad( intval( str_replace( '.', '', $GLOBALS['wp_version'] ) ), 3, '0' );
	$page_now    = preg_replace( '/\..+/', '', $GLOBALS['pagenow'] );
	$p3_svn      = P3_SVN;
	$payer_email = p3_get_option( 'payer_email' );
	$txn_id      = p3_get_option( 'txn_id' );
	$purch_time  = p3_option_or_val( 'purch_time', 'false' ) ;
	$is_dev      = ( P3_DEV ) ? 'true' : 'false';
	$p2_user     = ( p3_p2_db_data() ) ? 'true' : 'false';
	
	// plugins
	$plugins = (array) get_option( 'active_plugins' );
	$plugins_list = join( ' ', $plugins );

	// classify pages for levels of notices
	switch ( $page_now ) {
		case 'themes':
			$level = 1;
			break;
		case 'index': case 'post-new':
		case 'page-new': case 'edit-comments':
			$level = 2;
			break;
		default:
			$level = 3;
	}
	
	echo "\n" . <<< HTML
	<script type="text/javascript" charset="utf-8">
		var p3info = new Object();
		p3info.blogurl   = '$blog_url';
		p3info.wpversion = $wpversion;
		p3info.pagenow   = '$page_now';
		p3info.svn       = $p3_svn;
		p3info.level     = $level;
		p3info.plugins   = '$plugins_list';
		p3info.email     = '$payer_email';
		p3info.txn_id    = '$txn_id';
		p3info.purchtime = $purch_time;
		p3info.p2user    = $p2_user;
		p3info.is_dev    = $is_dev;
		jQuery(document).ready(function(){
			jQuery('#update-nag').append(' Staying up to date is the best way to prevent getting your <strong>blog hacked</strong>.');
		});
	</script>
HTML;
}


/* insert default or feedburner RSS feed link */
function p3_rss_url( $echo = TRUE ) {
	if ( p3_get_option( 'feedburner' ) ) {
		$feed_url = str_replace( ' ', '', p3_get_option( 'feedburner' ) );
	} else {
		$feed_url = get_bloginfo( 'rss2_url', 'display' );
	}
	if ( $echo ) echo $feed_url;
	return $feed_url;
}


/* extract feedburner ID from feedburner URL */
function p3_feedburner_id() {
	$feedburner_parts = explode( '.com/', p3_get_option( 'feedburner' ) );
	return $feedburner_id = str_replace( ' ', '', $feedburner_parts[1] );
}


/* show SVN in admin footer */
add_filter( 'update_footer', 'p3_build', 100000 );
function p3_build( $text ) {
	return '<span style="margin-right:15px">ProPhoto 3.2 build #' . P3_SVN . '</span>' . $text;
}


/* override wp stupid mac + mod-security flash disabler */
add_filter( 'flash_uploader', 'p3_force_flash_upload' );
function p3_force_flash_upload() {
	return TRUE;
}


/* override standard WordPress auto-upgrader fail stuff */
add_filter( 'request_filesystem_credentials', 'p3_explain_filesystem_fail' );
function p3_explain_filesystem_fail() {
	if ( get_filesystem_method( array(), ABSPATH ) == 'direct' ) {
		return '';
	}
	
	static $done = false;
	if ( $done ) {
		return '';
	}
	
	if ( !isset( $_GET['action'] ) ) {
		$action = 'do-core-upgrade';
	} else {
		$action = $_GET['action'];
	}
	
	switch ( $action ) {
		case 'upgrade-plugin':
			$headerStart = 'One-click plugin upgrade';
			$headerEmph  = 'failed';
			$actionDesc  = 'upgrading';
			$option2Text = 'Assuming option 1 didn\'t work, it\'s relatively simple to <em>manually upgrade</em> a plugin, it just takes an FTP program (if you don\'t have one, you can get one for free), and a couple minutes.  See our detailed <a href="http://www.prophotoblogs.com/support/about/manual-plugin-install/">tutorial here</a> for step-by-step instructions.';
			$option3Head = '';
			break;
		case 'delete-selected':
			$headerStart = 'One-click plugin delete';
			$headerEmph  = 'failed';
			$actionDesc  = 'deleting';
			$option2Text = 'Assuming option 1 didn\'t work, you can manually delete the plugin by connecting to your web hosting account with an FTP program, navigating into your blog\'s installation folder, then into the "wp-content" folder, then inside that, into the "plugins" folder.  Inside the plugins folder, delete the folder with the same name as the plugin you\'re trying to delete.  If you need help with getting and/or using an FTP program, see our <a href="http://www.prophotoblogs.com/support/about/all-about-ftp/">tutorial here</a>.';
			break;
		case 'delete':
			$headerStart = 'One-click theme delete';
			$headerEmph  = 'failed';
			$actionDesc  = 'deleting';
			$option2Text = 'Assuming option 1 didn\'t work, you can manually delete the theme by connecting to your web hosting account with an FTP program, navigating into your blog\'s installation folder, then into the "wp-content" folder, then inside that, into the "themes" folder.  Inside the plugins folder, delete the theme you\'re trying to delete.  If you need help with getting and/or using an FTP program, see our <a href="http://www.prophotoblogs.com/support/about/all-about-ftp/">tutorial here</a>.';
			break;
		case 'upload-theme':
			$headerStart = 'Theme upload';
			$headerEmph  = 'failed';
			$actionDesc  = 'uploading';
			$option2Text = 'Assuming option 1 didn\'t work, it\'s relatively simple to <em>manually install</em> a theme, it just takes an FTP program (if you don\'t have one, you can get one for free), and a couple minutes.  See our detailed <a href="http://www.prophotoblogs.com/support/how-to/manually-install-prophoto/">tutorial here</a> for step-by-step instructions.';
			break;
		case 'upload-plugin':
			$headerStart = 'Plugin upload';
			$headerEmph  = 'failed';
			$actionDesc  = 'uploading';
			$option2Text = 'Assuming option 1 didn\'t work, it\'s relatively simple to <em>manually install</em> a plugin, it just takes an FTP program (if you don\'t have one, you can get one for free), and a couple minutes.  See our detailed <a href="http://www.prophotoblogs.com/support/how-to/manual-plugin-install/">tutorial here</a> for step-by-step instructions.';
			break;
		default:
			$headerStart = 'One-click upgrade';
			$headerEmph  = 'failed';
			$actionDesc  = 'upgrading';
			$option2Text = 'Assuming option 1 didn\'t work, it\'s relatively simple to <em>manually upgrade</em> your WordPress intallation, it just takes an FTP program (if you don\'t have one, you can get one for free), and a few minutes of careful work.  See our detailed <a href="http://www.prophotoblogs.com/support/about/wordpress-manual-upgrade/">tutorial here</a> for step-by-step instructions.<h3 style="font-size:16px;font-family:Georgia,Times,serif;">Option 3 - pay a fee to be manually upgraded</h3><p>If you\'re not confident you can learn to use an FTP program and get the manual installation done, or you\'ve tried and had problems in the fast, you can now pay us a small fee of <strong>$10 (USD)</strong> to perform the manual upgrade for you. To do so, just <a href="http://www.prophotoblogs.com/other-purchases/?show=wordpress-upgrade">click here</a>, then make the $10 purchase using credit card or PayPal.  You will be taken to a form where you can give us your FTP login information, and we will do the upgrade for you within 1-3 business days.</p>';
			$option4Head = 'consider switching web-hosting companies';
			$option4Body = 'There are good web-hosting companies out there that have taken care to make sure the WordPress auto-upgrader tool works flawlessly on their servers.  You could consider switching to one of those companies. Your blog and any other parts of your website that you have made would have to be carefully moved by a professional, but once that\'s done, <em>all of your future WordPress upgrades will work with just one click</em>.  <a href="http://www.bluehost.com/track/netrivet/auto-upgrade-fail/">Bluehost</a> is the company that we recommmend. If you do decide to switch hosts, read <a href="http://www.prophotoblogs.com/support/about/move-blog/">this tutorial</a> for information on who to contact to move your blog for you.';
	}
	
	
	echo <<<HTML
	<div class="wrap" style="width:600px">
		<div class="icon32" id="icon-tools" style="margin-right:15px"><br></div>

		<h2 style="font-size:33px;">$headerStart <span style="text-decoration:underline;">$headerEmph</span></h2>


		<p style="font-size:17px;margin-bottom:42px;font-family:Georgia,Times,serif;"><strong>Why?</strong>&nbsp; Unfortunately, your web-hosting company has configured their servers in a way that does not allow one-click, simple $actionDesc.  Please <em>carefully</em> read the below options for information on how you can complete the upgrade:</p>

		<h3 style="font-size:16px;font-family:Georgia,Times,serif;">Option 1 - try entering your FTP login information</h3>

		<p>If you know your web-hosting account <em>FTP login information</em>, scroll down to the bottom of this page and enter it into the form, then click "Proceed". If the process still fails, see option 2. If you <em>don't know your FTP login info</em>, contact your web-host tech support, <em>not ProPhoto</em> -- only they can give you this info. (Note: your FTP login info is NOT the same as your WordPress login username and password.)</p>

		<h3 style="font-size:16px;font-family:Georgia,Times,serif;">Option 2 - $actionDesc manually</h3>
		
		<p>$option2Text</p>
HTML;
	
	
	if ( $option4Head ) {
		echo <<<HTML
		<h3 style="font-size:16px;font-family:Georgia,Times,serif;">Option 3 - $option4Head</h3>
		
		<p>$option4Body</p>
HTML;
	}
	
	echo <<<HTML
	<h3 style="font-size:13px;margin-top:125px;">FTP Login Form: (see option 1 above)</h3>
	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function(){
			var table = jQuery('table.form-table');
			var explainPara = table.prev();
			var h2 = explainPara.prev();
			var icon = h2.prev();
			h2.remove();
			icon.remove();
			explainPara.remove();
			jQuery('input#hostname').after('<span class="description">this is very often just your domain name</span>');
			jQuery('input#ftps').parent().parent().after('<span class="description">if you don\'t know, leave as "FTP"</span>');
			jQuery('label:contains("Hostname")').text('FTP Host');
		});
	</script>
	</div>
HTML;

	$done = true;
	return '';
}


/* load more general functions */
require_once( dirname( __FILE__ ) . '/utility.php' );
require_once( dirname( __FILE__ ) . '/images.php' );
require_once( dirname( __FILE__ ) . '/static.php' );
require_once( dirname( __FILE__ ) . '/css.php' );
require_once( dirname( __FILE__ ) . '/debug.php' );

?>