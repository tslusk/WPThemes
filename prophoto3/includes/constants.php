<?php
/* ---------------------------- */
/* -- define theme constants -- */
/* ---------------------------- */

// current subversion commit#
define( 'P3_SVN', require( dirname( dirname( __FILE__ ) ) . '/svn.php' ) );

// blog info
define( 'P3_URL', 		      get_bloginfo( 'url', 'display' ) );
define( 'P3_THEME_URL',       get_bloginfo( 'template_directory' ) );
define( 'P3_WPURL', 	      get_bloginfo( 'wpurl' ) );
define( 'P3_SITE_NAME',       get_bloginfo( 'name', 'display' ) );
define( 'P3_SITE_DESC',       get_bloginfo( 'description' ) );
define( 'WP_THUMB_WIDTH',     get_option( 'thumbnail_size_w' ) );
define( 'WP_THUMB_HEIGHT',    get_option( 'thumbnail_size_h' ) );
define( 'WP_M_THUMB_WIDTH',   get_option( 'medium_size_w' ) );
define( 'WP_M_THUMB_HEIGHT',  get_option( 'medium_size_h' ) );
define( 'P3_DB_OPTION_NAME',  'p3theme_options' ); // db entry name for autoloaded active options
define( 'P3_DB_STORAGE_NAME', 'p3theme_storage' ); // db entry name for non-autoloaded option storage
define( 'P3_DB_CONTACT_LOG',  'p3theme_contact_log' ); // db entry name for contact form send log
define( 'WP_UPLOAD_PATH', ( trim( get_option( 'upload_path' ) ) === '' ) ? 'wp-content/uploads' : get_option( 'upload_path' ) );
define( 'P3_HAS_STATIC_HOME', ( get_option( 'show_on_front' ) == 'page' && get_option( 'page_on_front' ) ) );
define( 'P3_DEV',    ( $_SERVER['SERVER_ADMIN'] == 'netrivet@devmachine.com' || $_GET['p3debug'] == 'true' ) );
define( 'P3_TECH',   ( strpos( $_SERVER['HTTP_USER_AGENT'], 'prophototech' ) !== FALSE ) );
define( 'IS_IPAD',   ( strpos( $_SERVER['HTTP_USER_AGENT'], 'iPad' ) !== FALSE ) );
define( 'IS_IPHONE', ( !IS_IPAD && strpos( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) !== FALSE ) );


// misc
define( 'MAX_BANNERS', 						 30 );   // num available sponsor ad banners uploads
define( 'MAX_MASTHEAD_IMAGES', 				 60 );   // num available masthead image upload slots
define( 'MAX_INLINE_BANNER_WIDTH', 	 		 220 );  // pixels
define( 'DROPSHADOW_NARROW_IMG_WIDTH',       7 );    // pixels
define( 'DROPSHADOW_WIDE_IMG_WIDTH',     	 60 );   // pixels
define( 'DROPSHADOW_NARROW_IMG_ADDED_WIDTH', DROPSHADOW_NARROW_IMG_WIDTH * 2 ); // total pixels from both sides
define( 'DROPSHADOW_WIDE_IMG_ADDED_WIDTH',	 DROPSHADOW_WIDE_IMG_WIDTH * 2 );   // total pixels from both sides
define( 'MAX_BIO_IMAGES', 			 		 30 );   // num available bio image uploads
define( 'NUM_BIO_TEXT_SECTIONS', 	 		 4 );    // num available bio text sections
define( 'MAX_BIO_WIDGET_COLUMNS',	 		 4 );
define( 'MAX_FOOTER_WIDGET_COLUMNS', 		 4 );
define( 'HEADER_PREVIEW_THUMB_WIDTH',		 55 );
define( 'HEADER_PREVIEW_IMG_WIDTH',  		 200 );
define( 'MAX_UPLOAD_BOX_IMG_WIDTH',  		 568 );
define( 'MAX_CUSTOM_MENU_LINKS', 	 		 10 );
define( 'MAX_CUSTOM_WIDGET_IMAGES',  		 25 );
define( 'NUM_AVAILABLE_DRAWERS',     		 4 );
define( 'MAX_CONTACT_CUSTOM_FIELDS', 		 4 );
define( 'MAX_AUDIO_FILE_UPLOADS', 		     6 );


// URLs
define( 'PROPHOTO_SITE_URL', 	     'http://www.prophotoblogs.com/' );
define( 'SUPPORTED_VERSION_LINK',    PROPHOTO_SITE_URL . 'support/about/wp-version-not-supported/' );
define( 'BLOG_BACKUP_LINK', 	     PROPHOTO_SITE_URL . 'support/about/backup-blog/' );
define( 'FTP_UPLOAD_AUDIO_TUT',      PROPHOTO_SITE_URL . 'support/about/ftp-uploading-mp3s/' );
define( 'FAVICON_TUT_URL',           PROPHOTO_SITE_URL . 'support/about/favicon/' );
define( 'EDIT_THEME_FILES_TUT',      PROPHOTO_SITE_URL . 'support/about/editing-theme-files/' );
define( 'BIO_WIDGETS_TUT',           PROPHOTO_SITE_URL . 'support/about/customizing-bio-area/' );
define( 'UNDERSTANDING_WIDGETS_TUT', PROPHOTO_SITE_URL . 'support/about/understanding-widgets/' );
define( 'FEATURED_GALLERIES_TUT',    PROPHOTO_SITE_URL . 'support/about/featured-galleries/' );
define( 'DESIGNS_PAGE_TUT',          PROPHOTO_SITE_URL . 'support/about/p3-designs-page/' );
define( 'CHANGE_PERMISSIONS_TUT',    PROPHOTO_SITE_URL . 'support/about/changing-permissions/' );
define( 'FTP_TUT',                   PROPHOTO_SITE_URL . 'support/about/all-about-ftp/' );
define( 'IMPORT_P3_DESIGN_TUT',      PROPHOTO_SITE_URL . 'support/about/import-p3-designs/' );
define( 'FACEBOOK_FANBOX_URL',       PROPHOTO_SITE_URL . 'external/facebook-likebox/' );
define( 'FACEBOOK_PAGE_URL',         PROPHOTO_SITE_URL . 'external/facebook-page/' );
define( 'TWITTER_WIDGET_URL',        PROPHOTO_SITE_URL . 'external/twitter-widget/' );
define( 'FEEDBURNER_SUBSCRIBE_TUT',  PROPHOTO_SITE_URL . 'support/about/enable-email-subscriptions/' );
define( 'CONTACT_FORM_PROBLEMS_TUT', PROPHOTO_SITE_URL . 'support/how-to/fix-contact-form/' );
define( 'NESTED_THEME_FOLDER_TUT',   PROPHOTO_SITE_URL . 'support/about/nested-theme-folder/' );
define( 'MISNAMED_THEME_FOLDER_TUT', PROPHOTO_SITE_URL . 'support/about/misnamed-theme-folder/' );
define( 'NO_UPLOADS_FOLDER_TUT',     PROPHOTO_SITE_URL . 'support/about/uploads-folder/' );
define( 'NO_P3_FOLDER_TUT',          PROPHOTO_SITE_URL . 'support/about/missing-p3-folder/' );
define( 'P3_FOLDER_WRITABLE_TUT',    PROPHOTO_SITE_URL . 'support/about/static-files-not-written/' );
define( 'P3_CONTACT_SUPPORT_PAGE',   PROPHOTO_SITE_URL . 'support/contact/' );
define( 'CHANGE_BLOG_ADDRESS_TUT',   PROPHOTO_SITE_URL . 'support/about/change-blog-address/' );
define( 'CONTACT_AREA_TUT',          PROPHOTO_SITE_URL . 'support/about/contact-form/' );
define( 'DB_BACKUP_NAG_TUT',         PROPHOTO_SITE_URL . 'support/about/db-backup-plugin-nag/' );
define( 'BURN_FEEDBURNER_FEED_TUT',  PROPHOTO_SITE_URL . 'support/about/feedburner-url/' );
define( 'CREATING_GALLERIES_TUT',    PROPHOTO_SITE_URL . 'support/about/creating-prophoto-galleries/' );
define( 'UPGRADING_WP_TUT',          PROPHOTO_SITE_URL . 'support/about/upgrading-wordpress/' );
define( 'REGISTER_GLOBALS_TUT',      PROPHOTO_SITE_URL . 'support/about/register-globals/' );
define( 'SAFE_MODE_FIX_TUT',         PROPHOTO_SITE_URL . 'support/about/safe-mode-folders-fix/' );
define( 'COMMENT_SPAM_TUT',          PROPHOTO_SITE_URL . 'support/about/comment-spam/' );
define( 'MANUAL_PLUGIN_INSTALL_TUT', PROPHOTO_SITE_URL . 'support/about/manual-plugin-install/' );


// these are just for code readability
define( 'NO_ECHO',           FALSE );
define( 'ECHO',              TRUE );
define( 'WITH_SIDEBAR',      TRUE );
define( 'NO_SIDEBAR',        FALSE );
define( 'NOT_SPECIFIC',      FALSE );
define( 'AS_STRING',         TRUE );
define( 'MEDIUM',            TRUE );
define( 'SMALL',             FALSE );
define( 'NO_IMG_OPTIONS',    FALSE );
define( 'IMPORTANT',         TRUE );
define( 'OPTIONAL_COLORS',   TRUE );
define( 'DO_NONLINK',        TRUE );
define( 'ONE_YEAR_FROM_NOW', time() + 60 * 60 * 24 * 365 );


// options stuff
define( 'FONT_WEIGHT_SELECT', 'select||select...|400|normal|700|bold' );
define( 'FONT_STYLE_SELECT', 'select||select...|normal|normal|italic|italic' );
define( 'FONT_TRANSFORM_SELECT', 'select||select...|none|Normal|uppercase|UPPERCASE|lowercase|lowercase' );
define( 'BORDER_STYLE_SELECT', 'select|solid|solid line|dashed|dashed line|dotted|dotted line|double|double line &nbsp;' );
define( 'FONT_LINEHEIGHT_SELECT', 'select||line spacing...|1|1.0|1.25|1.25|1.5|1.5|1.75|1.75|2|2.0' );
define( 'FEEDBURNER_LANG_OPTIONS', 'en_US|English|es_ES|Español|fr_FR|Français|da_DK|Dansk|de_DE|Deutsch|pt_PT|Portguese|ru_RU|русский язык|ja_JP|Japanese' );
define( 'LINK_DECORATION_SELECT', 'select||select...|none|no decoration|underline|underlined|overline|overlined|line-through|line through' );
define( 'FONT_FAMILIES', 'Arial, Helvetica, sans-serif|Arial|Times, Georgia, serif|Times|Verdana, Tahoma, sans-serif|Verdana|"Century Gothic", Helvetica, Arial, sans-serif|Century Gothic|Helvetica, Arial, sans-serif|Helvetica|Georgia, Times, serif|Georgia|"Lucida Grande","Lucida Sans Unicode",Tahoma,Verdana,sans-serif|Lucida Grande|Palatino, Georgia, serif|Palatino|Garamond, Palatino, Georgia, serif|Garamond|Tahoma, Verdana, Helvetica, sans-serif|Tahoma|Courier, monospace|Courier|"Trebuchet MS", Tahoma, Helvetica, sans-serif|Trebuchet MS|"Comic Sans MS", Arial, sans-serif|Comic Sans MS|Bookman, Palatino, Georgia, serif|Bookman');
define( 'FONT_FAMILY_SELECT', 'select||select font...|' . FONT_FAMILIES );


// gallery image placeholders
define( 'STATIC_RESOURCE_URL', ( P3_DEV ) ? 'http://' . $_SERVER['SERVER_NAME'] . '/amazon-prophoto/' : 'http://prophoto.s3.amazonaws.com/' );
define( 'LIGHTBOX_GAL_PLACEHOLDER_START', '<img class="p3-lightbox-gallery-holder p3-placeholder' );
define( 'LIGHTBOX_GALLERY_PLACEHOLDER', LIGHTBOX_GAL_PLACEHOLDER_START . ' aligncenter" style="display:none;" src="' . STATIC_RESOURCE_URL . 'img/p3-lightbox-gal-placeholder.gif" alt=""/>' );
define( 'FLASH_GAL_PLACEHOLDER_START', '<img class="p3-flash-gallery-holder p3-placeholder' );
define( 'FLASH_GALLERY_PLACEHOLDER', FLASH_GAL_PLACEHOLDER_START . ' aligncenter" style="display:none;" src="' . STATIC_RESOURCE_URL . 'img/p3-flash-gal-placeholder.gif" alt=""/>' );

// misc
define( 'WP_VERSION_FAIL', ( !function_exists( 'add_theme_support' ) ) );
define( 'WP_PASSWORD_PROTECT_PHRASE', 'This post is password protected. To view it please enter your password below:' );
define( 'MATCH_QUOTED', "(?:\"|')([^'\"]+)(?:\"|')" );
define( 'PP_MARKETING_INCLUDES', ( P3_DEV ) ? 'http://localhost/www.pblogs.com/wp-content/themes/marketing/includes/'  : PROPHOTO_SITE_URL . 'wp-content/themes/marketing/includes/' );


?>