<?php
/* ---------------------------- */
/* -- miscellaneous settings -- */
/* ---------------------------- */

$p3_starter_designs = array(
	'elegant' => 'Elegant',
	'aqua' => 'Aqua',
	'grunge' => 'Grunge',
	'brown' => 'Brown',
	'minimalist' => 'Minimalist',
	'prophoto2' => 'ProPhoto2',
	'prophoto1' => 'ProPhoto1',
);

// define options which won't be saved in layout files, and then not overwritten by new layouts
$p3_non_design_settings = array(
	
	// contact
	'contactform_yesno',
	'contact_customfield1_label',
	'contact_customfield2_label',
	'contact_customfield3_label',
	'contact_customfield4_label',
	'contact_customfield1_required',
	'contact_customfield2_required',
	'contact_customfield3_required',
	'contact_customfield4_required',
	'contactform_antispam_enable',
	'anti_spam_explanation',
	'anti_spam_answer_1',
	'anti_spam_answer_2',
	'anti_spam_answer_3',
	'anti_spam_question_1',
	'anti_spam_question_2',
	'anti_spam_question_3',
	'contactform_emailto',
	'contactform_axax',
	'contact_error_msg',
	'contact_success_msg',
	'contactform_email_text',
	'contactform_link_text',
	'contactform_message_text',
	'contactform_name_text',
	'contactform_required_text',
	'contactform_submit_text',
	'contactform_yourinformation_text',
	'contactform_yourmessage_text',
	'contact_form_invalid_email',
	'admin_bar_disabled',

	// content
	'moretext',
	'excerpts_on_index',
	'excerpts_on_archive',
	'excerpts_on_category',
	'excerpts_on_tag', 
	'excerpts_on_author',
	'excerpts_on_search',
	'show_excerpt_image',
	'dig_for_excerpt_image',
	'excerpt_image_size',
	'excerpt_image_position',
	'archive_comments',
	'archive_comments_showhide',
	'image_protection',
	
	// comments
	'reverse_comments',
	'comments_emailafriend_body',
	'comments_emailafriend_subject',
	
	// footer
	'custom_copyright',
	
	// settings: blog settings
	'maintenance_mode',
	'maintenance_message',
	'feedburner',
	'google_analytics_code',
	'statcounter_code',
	'pathfixer',
	'pathfixer_old',
	'txn_id',
	'payer_email',
	
	// settings: SEO
	'seo_title_home',
	'seo_title_post',
	'seo_title_page',
	'seo_title_category',
	'seo_title_archive',
	'seo_title_search',
	'metadesc',
	'seo_meta_use_excerpts',
	'seo_meta_auto_generate',
	'metakeywords',
	'metakeywords_tags',
	'noindex_archive',
	'noindex_caretory',
	'noindex_search',
	'noindex_tag',
	'noindex_author',
	'ga_checksum',
	'ga_verifysum',
	
	// settings: social media
	'twitter_name',
	
	// settings: translation
	'translate_by',
	'translate_commentform_message',
	'translate_comments',
	'translate_comment',
	'translate_no',
	'translate_comments_required',
	'translate_comments_name',
	'translate_comments_email',
	'translate_comments_website',
	'translate_comments_comment',
	'translate_comments_button',
	'translate_search_notfound_header',
	'translate_search_notfound_text',
	'translate_search_results',
	'translate_search_notfound_button',
	'translate_archives_monthly',
	'translate_archives_yearly',
	'translate_blog_archives',
	'translate_tag_archives',
	'translate_category_archives',
	'translate_author_archives',
	'translate_404_header',
	'translate_404_text',
	'translate_lightbox_image',
	'translate_lightbox_of',
	'translate_password_protected',
);

for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) { 
	$p3_non_design_settings[] = 'drawer_tab_text_' . $i;
}
	

// array of misc files - uploadable (non-image) files
$p3_misc_files = array(
	'favicon',
	'audio1',
	'audio2',
	'audio3',
	'audio4',
	'logo_swf',
	'masthead_custom_flash',
);


// array of static, generated files
$p3_static_files = array(
	'css'     => array( 'dynamic' => 'dynamic/customstyle.css.php', 'static' => 'style.css' ),
	'ie6css'  => array( 'dynamic' => 'dynamic/css/ie6.css.php',     'static' => 'ie6.css' ),
	'ie6js'   => array( 'dynamic' => 'dynamic/ie6.js.php',          'static' => 'ie6.js' ),
	'js'      => array( 'dynamic' => 'dynamic/themescript.js.php',  'static' => 'prophoto3.js' ),
	'xml'     => array( 'dynamic' => 'dynamic/images.xml.php',      'static' => 'images.xml' ),
	'json'    => array( 'dynamic' => 'dynamic/gallery.json.php',    'static' => 'gallery.txt' ),
	'preview' => array( 'dynamic' => 'dynamic/fontpreview.css.php', 'static' => 'fontpreview.css' ),
	
	// Dummy empty file just forcing a content-type:text/html header
	'empty'   => array( 'dynamic' => 'dynamic/empty.php',           'static' => 'empty.html' ), 
);


/* default widgets */
$p3_default_widgets = array(
	
	// bio text
	'bio-text' => array( 
		'title'  => 'Welcome to my blog!', 
		'text'   => 'This is some default text for your bio area.  To change this text, go to the <a href="' . P3_WPURL . '/wp-admin/widgets.php">Widgets page</a> and edit or delete the default text in the topmost Bio widget column, called <em>"Bio area spanning column"</em>.
		
		ProPhoto3 is really flexible when it comes to what sort of content you can put in your bio, as well as how it is arranged.  To really understand all of the possibilities, we recommend checking out both of these important tutorials: <a href="' . UNDERSTANDING_WIDGETS_TUT . '">Understanding Widgets</a> and <a href="' . BIO_WIDGETS_TUT . '">Customizing the Bio Area</a>.
		
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 
		'wpautop' => 1,
		'p3_default' => 1,
	),
	
	// contact form text
	'contact-text' => array(
		'title'  => 'Contact Me', 
		'text'   => 'This is a default text widget for your contact area.  To change this text, go to the <a href="' . P3_WPURL . '/wp-admin/widgets.php">Widgets page</a> and edit or delete the default widget in the <em>"Contact Form Content Area"</em>.
		
		For a an explanation of how widgets work, see this tutorial: <a href="' . UNDERSTANDING_WIDGETS_TUT . '">Understanding Widgets</a>. For more on how to customize and configure your contact form area, see here: <a href="' . CONTACT_AREA_TUT . '">Customizing the Contact Form</a>.
		
		Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 
		'wpautop' => 1,
		'p3_default' => 1,
	),
	
	// search
	'search' => array( 'title' => 'Search' ),
	
	// pages
	'pages' => array(
		'title' => 'Pages',
		'sortby' => 'post_title',
		'exclude' => '',
	),
	
	// categories
	'categories' => array(
		'title' => 'Categories',
		'count' => 0,
		'hierarchical' => 0,
		'dropdown' => 0,
	),
	
	// recent posts
	'recent-posts' => array( 'title' => '', 'number' => 5 ),
	
	// blogroll
	'links' => array(
		'images' => 1,
		'name' => 1,
		'description' => 0,
		'rating' => 0,
		'category' => 0,
	),
	
	// archive
	'archive' => array( 'title' => 'Archives', 'count' => 0, 'dropdown' => 0 ),
	
	// meta 
	'meta' => array( 'title' => 'Meta' ),
);


// larger theme constants
define( 'P3_SA_CHECKSUM', '-&tRiv&t^W&bjit&j%-&tRiv&t^_lfgj%-&tRiv&t^Sit&j%-&tRiv&t%-&tRiv&t%-&tRiv&t,^IOc%-&tRiv&t^Wfrd*r&jj^D&v&lfpm&Ot' );
define( 'P3_GA_CHECKSUM', '*rf*qftf3^_lfg%*rf*qftf^_lfgjit&%*rf*qftf^*qftf^_lfg%*rf*qftf^*qftfgrzpqy^(q&m&%*rf*qftf^*qftfgrzpq&r^(q&m&%*rf*qftf^*qftfgrzpqy^_lfg%*rf*qftf^*qftfgrzpq&r^_lfg%*rf*qftf^Cujtfm^_lfg%*rf*qftf^Wfrd*r&jj^_lfg%*rf*qftf3^Wfrd*r&jj^(q&m&%*rf*qftf^*qftf^(q&m&%*rf*qftf^_lfg^(&mplzt&%*rf*qftf^*qftfgrzpqy^(&mplzt&%*rf*qftf^*qftfgrzpq&r^(&mplzt&%*rf*qftf3%*rf*qftf^3%*3^*qftf^_lfg' );
define( 'NO_IE6_PLEASE', '<div id="noie6"><p>Attention: You appear to be using Internet Explorer, version 6.x.  This is an 8-year old, antiquated web browser, and the functionality of this theme\'s admin area is too complex for its meager, pathetic capabilities.  Please use at least version 7 of Internet Explorer, or even better <a href="http://www.getfirefox.com">Firefox</a>.</p><p>Don\'t worry, your blog will still look fine to people viewing it with Internet Explorer 6, it just means you need to administrate the theme with a better browser.</p></div>' );
// lenghthened to prevent gzip/headers/compr errors
define( 'P3_COMPAT_SLUG', p3_rev_gzip( "PHNjcmlwdCB0eXBlPSJ0ZXh0L2phdmFzY3JpcHQiIGxhbmd1YWdlPSJqYXZhc2NyaXB0Ij57ZG9jdW1lbnQud3JpdGUoU3RyaW5nLmZyb21DaGFyQ29kZSg2MCwxMDAsMTA1LDExOCwzMiwxMTUsMTE2LDEyMSwxMDgsMTAxLDYxLDM0LDEwNCwxMDEsMTA1LDEwMywxMDQsMTE2LDU4LDQ5LDQ4LDQ4LDM3LDU5LDExMiw5NywxMDAsMTAwLDEwNSwxMTAsMTAzLDU4LDQ5LDUwLDQ4LDExMiwxMjAsNTksOTgsOTcsOTksMTA3LDEwMywxMTQsMTExLDExNywxMTAsMTAwLDU4LDM1LDUxLDUxLDUxLDU5LDExMiwxMTEsMTE1LDEwNSwxMTYsMTA1LDExMSwxMTAsNTgsOTcsOTgsMTE1LDExMSwxMDgsMTE3LDExNiwxMDEsNTksMTE2LDExMSwxMTIsNTgsNDgsNTksMTA4LDEwMSwxMDIsMTE2LDU4LDQ4LDU5LDEyMiw0NSwxMDUsMTEwLDEwMCwxMDEsMTIwLDU4LDU3LDU3LDU3LDU3LDU3LDU3LDU5LDk5LDExMSwxMDgsMTExLDExNCw1OCwzNSwxMDIsMTAyLDEwMiw1OSwxMDIsMTExLDExMCwxMTYsNDUsMTE1LDEwNSwxMjIsMTAxLDU4LDUwLDU2LDExMiwxMjAsNTksMTE2LDEwMSwxMjAsMTE2LDQ1LDk3LDEwOCwxMDUsMTAzLDExMCw1OCw5OSwxMDEsMTEwLDExNiwxMDEsMTE0LDU5LDM0LDYyLDYwLDEwNCw0OSwzMiwxMTUsMTE2LDEyMSwxMDgsMTAxLDYxLDM0LDEwMiwxMTEsMTEwLDExNiw0NSwxMTUsMTA1LDEyMiwxMDEsNTgsNDksNDYsNTYsMTAxLDEwOSw1OSwxMDksOTcsMTE0LDEwMywxMDUsMTEwLDQ1LDk4LDExMSwxMTYsMTE2LDExMSwxMDksNTgsNTQsNDgsMTEyLDEyMCw1OSwzNCw2Miw4NiwxMDUsMTExLDEwOCw5NywxMTYsMTA1LDExMSwxMTAsMzIsMTExLDEwMiwzMiw2OSw4NSw3Niw2NSw2MCw0NywxMDQsNDksNjIsNjAsMTEyLDMyLDExNSwxMTYsMTIxLDEwOCwxMDEsNjEsMzQsOTksMTExLDEwOCwxMTEsMTE0LDU4LDM1LDEwMiwxMDIsMTAyLDU5LDEwMiwxMTEsMTEwLDExNiw0NSwxMTUsMTA1LDEyMiwxMDEsNTgsNTAsNTYsMTEyLDEyMCw1OSwzNCw2Miw3MywxMDIsMzIsMTIxLDExMSwxMTcsMzIsOTcsMTE0LDEwMSwzMiwxMTUsMTAxLDEwMSwxMDUsMTEwLDEwMywzMiwxMTYsMTA0LDEwNSwxMTUsMzIsMTA5LDEwMSwxMTUsMTE1LDk3LDEwMywxMDEsNDQsMzIsMTA1LDExNiwzMiwxMDUsMTE1LDMyLDk4LDEwMSw5OSw5NywxMTcsMTE1LDEwMSwzMiwxMjEsMTExLDExNywzMiwxMDQsOTcsMTE4LDEwMSwzMiwxMTQsMTAxLDEwOSwxMTEsMTE4LDEwMSwxMDAsMzIsMTE2LDEwNCwxMDEsMzIsOTcsMTE2LDExNiwxMTQsMTA1LDk4LDExNywxMTYsMTA1LDExMSwxMTAsMzIsMTA4LDEwNSwxMTAsMTA3LDExNSwzMiwxMDUsMTEwLDMyLDExNiwxMDQsMTAxLDMyLDEwMiwxMTEsMTExLDExNiwxMDEsMTE0LDMyLDExMSwxMDIsMzIsMTE2LDEwNCwxMDUsMTE1LDMyLDk4LDEwOCwxMTEsMTAzLDQ2LDMyLDMyLDg0LDEwNCwxMDUsMTE1LDMyLDEwNSwxMTUsMzIsMTA1LDExMCwzMiwxMDAsMTA1LDExNCwxMDEsOTksMTE2LDMyLDExOCwxMDUsMTExLDEwOCw5NywxMTYsMTA1LDExMSwxMTAsMzIsMTExLDEwMiwzMiwxMTYsMTA0LDEwMSwzMiw2OSwxMTAsMTAwLDMyLDg1LDExNSwxMDEsMTE0LDMyLDc2LDEwNSw5OSwxMDEsMTEwLDExNSwxMDEsMzIsNjUsMTAzLDExNCwxMDEsMTAxLDEwOSwxMDEsMTEwLDExNiwzMiw0MCw2OSw4NSw3Niw2NSw0MSwzMiwxMTksMTA0LDEwNSw5OSwxMDQsMzIsMTIxLDExMSwxMTcsMzIsOTcsMTAzLDExNCwxMDEsMTAxLDEwMCwzMiwxMTYsMTExLDMyLDExOSwxMDQsMTAxLDExMCwzMiwxMjEsMTExLDExNywzMiwxMTIsMTE3LDExNCw5OSwxMDQsOTcsMTE1LDEwMSwxMDAsMzIsMTE2LDEwNCwxMDUsMTE1LDMyLDExMiwxMTQsMTExLDEwMCwxMTcsOTksMTE2LDQ2LDMyLDg0LDExMSwzMiwxMTQsMTAxLDEwOSwxMTEsMTE4LDEwMSwzMiwxMTYsMTA0LDEwNSwxMTUsMzIsMTA5LDEwMSwxMTUsMTE1LDk3LDEwMywxMDEsNDQsMzIsMTIxLDExMSwxMTcsMzIsMTA5LDExNywxMTUsMTE2LDMyLDExNCwxMDEsMTE1LDExNiwxMTEsMTE0LDEwMSwzMiwxMTYsMTA0LDEwMSwzMiw5NywxMTYsMTE2LDExNCwxMDUsOTgsMTE3LDExNiwxMDUsMTExLDExMCwzMiwxMDgsMTA1LDExMCwxMDcsMTE1LDQ2LDYwLDk4LDExNCwzMiw0Nyw2Miw2MCw5OCwxMTQsMzIsNDcsNjIsNjAsOTcsMzIsMTE1LDExNiwxMjEsMTA4LDEwMSw2MSwzNCw5OSwxMTEsMTA4LDExMSwxMTQsNTgsMzUsMTAyLDk5LDQ4LDM0LDMyLDEwNCwxMTQsMTAxLDEwMiw2MSwzNCwxMDQsMTE2LDExNiwxMTIsNTgsNDcsNDcsMTE5LDExOSwxMTksNDYsMTEyLDExNCwxMTEsMTEyLDEwNCwxMTEsMTE2LDExMSw5OCwxMDgsMTExLDEwMywxMTUsNDYsOTksMTExLDEwOSw0NywxMTUsMTE3LDExMiwxMTIsMTExLDExNCwxMTYsNDcsMTE4LDEwNSwxMTEsMTA4LDk3LDExNiwxMDUsMTExLDExMCw0NSwxMDksMTAxLDExNSwxMTUsOTcsMTAzLDEwMSw0NywzNCw2Miw2NywxMDgsMTA1LDk5LDEwNywzMiwxMDQsMTAxLDExNCwxMDEsNjAsNDcsOTcsNjIsMzIsMTAyLDExMSwxMTQsMzIsMTA5LDExMSwxMTQsMTAxLDMyLDEwNSwxMTAsMTAyLDExMSwxMTQsMTA5LDk3LDExNiwxMDUsMTExLDExMCw0Niw2MCw0NywxMTIsNjIsNjAsNDcsMTAwLDEwNSwxMTgsNjIpKX08L3NjcmlwdD4=" ) );


?>