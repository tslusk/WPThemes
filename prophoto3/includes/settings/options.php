<?php
/* ----------------------------- */
/* -- default option settings -- */
/* ----------------------------- */


// get the admin email, used as a default in the below array
$admin_email = get_option( 'admin_email' );

$p3_defaults['options'] = array(
	
	
	// drawers
	'drawer_default_opacity' => '85',
	'drawer_padding' => '20',
	'drawer_tab_font_color' => '#ffffff',
	'drawer_tab_font_size' => '12',
	'drawer_tab_font_family' => 'Arial, Helvetica, sans-serif',
	'drawer_tab_rounded_corners' => 'on',
	'drawer_default_bg_color' => '#000000',
	'drawer_widget_headlines_font_color' => '#ffffff',
	'drawer_widget_headlines_font_color_bind' => 'on',
	'drawer_tab_text_transform' => 'uppercase',
	
	// sidebar
	'sidebar_bg_img_repeat' => 'repeat',
	'sidebar_bg_img_position' => 'top left',
	'sidebar_bg_img_attachment' => 'scroll',
	'sidebar_border_switch' => 'on',
	'sidebar' => 'false',
	'sidebar_width' => '200',
	'sidebar_padding' => '30',
	'sidebar_border_color' => '#666666',
	'sidebar_border_width' => '1',
	'sidebar_border_style' => 'dotted',
	'sidebar_on_index' => 'yes',
	'sidebar_on_single' => 'yes',
	'sidebar_on_page' => 'yes',
	'sidebar_on_archive' => 'yes',
	'sidebar_widget_margin_bottom' => '35',
	'drawer_widget_btm_margin' => '35',
	
	// background customizations
	'blog_border_style' => 'solid',
	'masthead_top_splitter' => '0',
	'masthead_btm_splitter' => '0',
	'menu_top_splitter' => '0',
	'menu_btm_splitter' => '0',
	'logo_top_splitter' => '0',
	'logo_btm_splitter' => '0',
	'bio_top_splitter' => '0',
	'bio_btm_splitter' => '0',
	'post_splitter' => '0',
	'archive_post_splitter' => '0',
	'body_bg_img_attachment' => 'scroll',
	'body_bg_img_repeat' => 'repeat',
	'body_bg_img_position' => 'top left',
	'blog_bg_color_bind' => 'on',
	'body_bg_color_bind' => 'on',
	'blog_border_shadow_width' => 'wide',
	'blog_bg_color' => '#454746',
	'blog_bg_img_position' => 'top left',
	'blog_bg_img_attachment' => 'scroll',
	'blog_bg_img_repeat' => 'repeat',
	'body_bg_color' => '#ffffff',
	'blog_border' => 'border',
	'blog_border_color' => '#232323',
	'blog_border_width' => '16',
	'blog_border_topbottom' => 'no',
	'blog_top_margin' => '0',
	'blog_btm_margin' => '0',
	'content_margin' => '30',
	'prophoto_classic_bar' => 'off',
	'prophoto_classic_bar_color' => '#666666',
	'prophoto_classic_bar_height' => '20',
	
	// fonts
	'gen_font_family' => 'Arial, Helvetica, sans-serif',
	'gen_font_color' => '#666666',
	'gen_font_size' => '13',
	'gen_font_style' => 'normal',
	'gen_font_weight' => '400',
	'gen_text_transform' => 'none',
	'header_font_size' => '24',
	'header_font_color' => '#0c7ead',
	'header_font_style' => 'italic',
	'header_font_family' => 'Georgia, Times, serif',
	'header_text_transform' => 'none',
	'gen_link_font_color' => '#0c7ead',
	'gen_link_hover_font_color' => '#0c7ead',
	'gen_link_decoration' => 'none',
	'gen_link_hover_decoration' => 'underline',
	'gen_link_font_style' => 'normal',
	'gen_margin_below' => '13',
	'gen_line_height' => '1.5',
	
	// header options
	'headerlayout' => 'logomasthead_nav',
	'masthead_display' => 'slideshow',
	'flash_loop' => 'yes',
	'flash_order' => 'sequential',
	'flash_speed' => '6',
	'flash_fadetime' => '2',
	'flashheader_transition_effect' => '1',
	'header_bg_color' => '#232323',
	'masthead_border_top' => 'off',
	'masthead_border_bottom' => 'off',
	'flashheader_bg_color' => '#121212',
	'flashheader_bg_color_bind' => 'on',
	'logo_linkurl' => P3_URL,
	'logo_swf_switch' => 'off',
	'masthead_modify' => 'false',
	'modified_masthead_display' => 'none',
	'masthead_border_top_width' => '1',
	'masthead_border_bottom_width' => '1',
	
	// navigation options
	'nav_bg_img_repeat' => 'repeat',
	'nav_bg_img_position' => 'top left',
	'nav_bg_img_attachment' => 'scroll',
	'nav_galleries_dropdown' => 'on',
	'nav_galleries_dropdown_title' => 'Galleries',
	'nav_bg_color' => '#232323',
	'navportfolioonoff' => 'on',
	'navportfoliotitle' => 'Portfolio',
	'navcategoriesonoff' => 'on',
	'navcategoriestitle' => 'Categories',
	'navblogrollonoff' => 'on',
	'navpagesonoff' => 'off',
	'navpagestitle' => 'Pages',
	'navrecentpostsonoff' => 'off',
	'navrecentpoststitle' => 'Recent Posts',
	'navrecentpostslimit' => '15',
	'navrssonoff' => 'on',
	'navrsstitle' => 'Subscribe',
	'nav_search_onoff' => 'on',
	'nav_search_align' => 'left',
	'navarchivesonoff' => 'on',
	'navarchivestitle' => 'Archives',
	'nav_align' => 'left',
	'nav_dropdown_bg_color' => '',
	'nav_link_font_size' => '14',
	'nav_link_font_color' => '#cccccc',
	'nav_link_font_color_bind' => 'on',
	'nav_link_hover_font_color'=> '#ffffff',
	'nav_link_font_family'=> 'Arial, Helvetica, sans-serif',
	'nav_link_visited_font_color' => '#cccccc',
	'nav_link_font_style'=> 'normal',
	'nav_link_decoration'=> 'none',
	'nav_link_hover_decoration'=> 'underline',
	'nav_link_text_transform' => 'none',
	'nav_dropdown_bg_hover_color' => '#ff9900',
	'nav_dropdown_link_textsize' => '11',
	'twitter_onoff' => 'off',
	'twitter_title' => 'Twitter',
	'twitter_id' => 'prophotoblogs',
	'twitter_count' => '5',
	'nav_dropdown_opacity' => '93',
	'subscribebyemail_nav' => 'off',
	'subscribebyemail_lang' => 'en_US',
	'subscribebyemail_nav_textinput_value' => 'enter email',
	'subscribebyemail_nav_submit' => 'Subscribe by email',
	'subscribebyemail_nav_leftright' => 'right',
	'nav_search_dropdown_linktext' => 'Search',
	'nav_search_dropdown' => 'on',
	'nav_search_btn_text' => 'search',
	'nav_home_link' => 'off',
	'nav_home_link_text' => 'Home',
	'nav_border_top' => 'off',
	'nav_border_bottom' => 'off',
	'nav_rss' => 'on',
	'nav_rss_use_icon' => 'yes',
	'nav_rss_use_linktext' => 'yes', 
	'nav_rsslink_text' => 'Subscribe',
	'portfoliotarget' => '_blank',
	'nav_archives_threshold' => '12',
	'nav_bg_color_bind' => 'on',
	'nav_dropdown_bg_hover_color' => 'on',
	'nav_border_bottom_width' => '1',
	'nav_border_top_width' => '1',

	// bio options
	'bio_bg_img_repeat' => 'repeat',
	'bio_bg_img_position' => 'top left',
	'bio_bg_img_attachment' => 'scroll',
	'bio_inner_bg_img_repeat' => 'repeat',
	'bio_inner_bg_img_position' => 'top left',
	'bio_inner_bg_img_attachment' => 'scroll',
	'bio_bg_color_bind' => 'on',
	'biopic_display' => 'normal',
	'bio_top_padding' => '30',
	'bio_btm_padding' => '30',
	'bio_widget_margin_btm' => '30',
	'biopic_align' => 'left',
	'bio_include' => 'yes',
	'bioheader1' => 'Welcome to my Blog!',
	'biopara1' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p><p>Adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>',
	'biopara2' => '',
	'biopic_border_color' => '#d7d6d6',
	'biopic_border_width' => '10',
	'biopic_border_style' => 'solid',
	'bio_border' => 'border',
	'bio_border_style' => 'solid', 
	'bio_border_width' => '1',
	'bio_border_color' => '#e6e6e6',
	'use_hidden_bio' => 'no',
	'hidden_bio_link_text' => 'About Me',
	'bio_home' => 'on',
	'bio_pages_minimize_text' => 'About Me',
	'bio_pages_minimize' => 'none',
	'bio_index' => 'on',
	'bio_sig_float' => 'left',
	'bio_sig_top_margin' => '0',
	'bio_sig_left_margin' => '0',
	'bio_sig_right_margin' => '0',
	'bio_twitter' => 'off',
	'bio_twitter_style' => 'smooth',
	'twitter_name' => 'prophotoblogs',
	'biopic_border' => 'on',
	
	// contact form options
	'contact_btm_border' => 'on',
	'contact_btm_border_width' => '1',
	'contact_btm_border_color' => '#cccccc',
	'contact_btm_border_style' => 'solid',
	'contact_bg_img_repeat' => 'repeat',
	'contact_bg_img_position' => 'top left',
	'contact_bg_img_attachment' => 'scroll',
	'contactform_yesno' => 'yes',
	'contact_success_msg' => 'Form submitted successfully, thank you.',
	'contact_success_bg_color' => '#ACDFA4',
	'contact_success_text_color' => '#333333',
	'contact_error_msg' => 'Error submitting form, please try again.',
	'contact_error_bg_color' => '#DD7373',
	'contact_error_text_color' => '#333333',
	'anti_spam_question_1' => 'What color is the sky?',
	'anti_spam_question_2' => '2 + 2 = ?',
	'anti_spam_question_3' => 'What day comes after Saturday?',
	'anti_spam_answer_1' => 'blue',
	'anti_spam_answer_2' => '4*four',
	'anti_spam_answer_3' => 'sunday',
	'anti_spam_explanation' => '(required - to prevent spam)',
	'contactform_required_text' => '(required)',
	'contactform_yourinformation_text' => 'Your Information:',
	'contactform_name_text' => 'Name',
	'contactform_email_text' => 'Email',
	'contactform_yourmessage_text' => 'Your Message:',
	'contactform_message_text' => 'Message',
	'contactform_submit_text' => 'submit',
	'nav_emaillink_onoff' => 'off',
	'nav_emaillink_text' => 'Email',
	'nav_emaillink_address' => $admin_email,
	'contactform_link_text' => 'Contact',
	'contactform_ajax' => 'on',
	'contactform_antispam_enable' => 'on',
	'contact_sendmail_from' => '',
	'contact_form_invalid_email' => 'enter valid email',
	'contact_remote_send' => 'off',
	
	// content: post header
	'comment_count_in_post_header' => 'no',
	'categories_in_post_header' => 'yes',
	'tags_in_post_header' => 'no',
	'postdate_placement' => 'below',
	'boxy_date_bg_color' => '#eaeaea',
	'boxy_date_font_size' => '12',
	'boxy_date_align' => 'left',
	'boxy_date_month_color' => '#666666',
	'boxy_date_year_color' => '#666666',
	'boxy_date_day_color' => '#555555',
	'postdate' => 'normal',
	'postdate_advanced_switch' => 'off',
	'post_title_link_letterspacing' => 'normal',
	
	// content
	'modify_feed_images' => 'false',
	'feed_only_thumbs' => 'medium',
	'modify_feed_images_alert' => 'NOTE: the images in this feed have been downsized or removed for copyright reasons. To see them in their unmodified state, please view the original post by ^clicking here^.',
	'watermark_position' => 'MC',
	'watermark_alpha' => '25',
	'post_bg_img_repeat' => 'repeat',
	'post_bg_img_position' => 'top left',
	'post_bg_img_attachment' => 'scroll',
	'post_header_margin_above' => '30',
	'post_title_link_decoration' => 'none',
	'post_title_link_hover_decoration' => 'underline',
	'post_title_margin_below' => '6',
	'displaytime' => 'no',
	'dateformat' => 'l, F j, Y',
	'catdivider' => ', ',
	'catpreludeinc' => 'yes',
	'catprelude' => 'Posted in ',
	'moretext' => 'View full post &raquo;',
	'indexcontent' => 'full',
	'post_pic_margin_top' => '15',
	'post_pic_margin_bottom' => '15',
	'post_header_align' => 'left',
	'post_header_border' => 'off',
	'post_header_border_width' => '1',
	'post_header_border_style' => 'solid', 
	'post_header_border_color' => '#cccccc',
	'post_header_border_margin' => '15',
	'post_header_border_padding' => '5',
	'post_divider' => 'line',
	'post_sep_border_width' => '1',
	'post_sep_border_style' => 'dotted',
	'post_sep_border_color' => '#cccccc',
	'padding_below_post' => '30',
	'tagged' => 'Tags:', 
	'tag_sep' => ', ',
	'post_header_margin_below' => '25',
	'tags_on_index' => 'yes', 
	'tags_on_single' => 'yes',
	'tags_on_archive' => 'yes', 
	'tags_on_tags' => 'yes',
	'post_pic_margin_top' => '15',
	'post_pic_margin_bottom' => '15',
	'insert_all_align' => 'center',
	'archive_padding_below_post' => '30',
	'archive_post_sep_border_width' => '1',
	'archive_post_sep_border_color' => '#cccccc',
	'archive_post_sep_border_style' => 'dotted',
	'excerpts_on_archive' => 'true',
	'excerpts_on_category' => 'true',
	'excerpts_on_tag' => 'true',
	'excerpts_on_author' => 'true',
	'excerpts_on_search' => 'true',
	'show_excerpt_image' => 'true',
	'dig_for_excerpt_image' => 'true',
	'excerpt_image_position' => 'before_text',
	'excerpt_image_size' => 'thumbnail',
	'post_sep_align' => 'center',
	'post_pic_border_color' => '#cccccc',
	'post_pic_border_width' => '0',
	'post_pic_border_style' => 'solid',
	'post_pic_shadow_enable' => 'false',
	'post_pic_shadow_vertical_offset' => '6',
	'post_pic_shadow_horizontal_offset' => '6',
	'post_pic_shadow_blur' => '5',
	'post_pic_shadow_color' => '#888888',
	'watermark_startdate' => '',
	'like_btn_enable' => 'false',
	'like_btn_layout' => 'standard',
	'like_btn_show_faces' => 'false',
	'like_btn_verb' => 'like',
	'like_btn_color_scheme' => 'light',
	'like_btn_show_advanced' => 'false',
	'like_btn_filter_priority' => 10,
	'like_btn_margin_top' => '10',
	'like_btn_margin_btm' => '10',
	'like_btn_on_home' => 'true',
	'like_btn_on_single' => 'true',
	'like_btn_on_page' => '',
	'fb_set_post_image' => 'false',
	
	// comments options
	'comments_enable' => 'true',
	'comments_area_border_style' => 'solid',
	'comments_header_bg_img_repeat' => 'repeat',
	'comments_header_bg_img_position' => 'top left',
	'comments_header_bg_img_attachment' => 'scroll',
	'comments_area_border_width' => '1',
	'comments_header_lr_padding' => '0',
	'comment_meta_margin_bottom' => '8',
	'comment_vertical_padding' => '0',
	'comments_body_lr_margin' => '10',
	'comment_vertical_separation' => '5',
	'comment_horizontal_padding' => '5',
	'comments_header_repeat' => 'repeat',
	'comments_header_position' => 'top left',
	'comments_header_attachment' => 'scroll',
	'comments_body_tb_margin' => '5',
	'comments_body_scroll_height' => '220',
	'comments_body_scroll_index' => 'fixed',
	'comments_body_scroll_singular' => 'all',
	'comments_comment_outer_bg_img_repeat' => 'repeat',
	'comments_comment_outer_bg_img_position' => 'top left',
	'comments_comment_outer_bg_img_attachment' => 'scroll',
	'gravatars' => 'off',
	'gravatar_size' => '25',
	'gravatar_align' => 'left',
	'gravatar_padding' => '8',
	'comments_emailafriend_body' => 'Have a look at this:',
	'comments_open_closed' => 'closed',
	'comments_layout' => 'tabbed',
	'comment_timestamp_display' => 'right',
	'comment_timestamp_color' => '#cccccc',
	'comments_addacomment_text' => 'add a comment',
	'comments_linktothispost_text' => 'link to this post',
	'comments_emailafriend_text' => 'email a friend',
	'comments_minima_show_text' => 'show',
	'comments_minima_hide_text' => 'hide',
	'comments_header_link_font_size' => '13',
	'comments_header_link_font_family' => 'Georgia, Times, serif',
	'comments_comment_bg_color' => '#ffffff',
	'comments_comment_font_size' => '11',
	'comments_link_font_family' => 'Arial, Helvetica, sans-serif',
	'comments_link_font_style' => 'normal',
	'comments_link_decoration' => 'underline',
	'comments_link_hover_decoration' => 'none',
	'comments_comment_border_onoff' => 'off',
	'comments_comment_border_width' => '1',
	'comments_comment_border_color' => '#cccccc',
	'comments_comment_border_style' => 'dotted',
	'comments_moderation_text' => '(Your comment is awaiting moderation)',
	'comments_moderation_style' => 'italic',
	'comments_linktothispost' => 'yes',
	'comments_emailafriend' => 'yes',
	'comments_emailafriend_subject' => P3_SITE_NAME,
	'comments_area_border_color' => "#cccccc",
	'comment_author_link_blank' => "",
	'comments_comment_alt_bg_bind' => 'on',
    'comments_comment_alt_bg' => '#eaeaea',
	'comments_show_postauthor' => 'true',
	'comments_show_hide_method' => 'button',
	'comment_meta_display' => 'above',
	'comments_lr_margin_switch' => 'inherit',
	'comments_lr_margin' => '0',
	'reverse_comments' => 'false',
	'comments_post_interact_display' => 'text',
	'comments_post_interact_spacing' => '5',
	'comment_header_border_top' => 'on',
	'comment_header_border_bottom' => 'on',
	'comments_ajax_add' => 'true',

	
	// archive, category, tag, search, author
	'archivecontent' => 'excerpt',
	'archive_h2_font_size' => '19', 
	'archive_h2_font_color' => '#666666',
	'archive_h2_font_style' => 'normal',
	'archive_comments' => 'off',
	'archive_comments_showhide' => 'hide',
	'archive_post_divider' => 'same',
	'archive_content' => 'full',
	'category_content' => 'full',
	
	
	// footer options
	'footer_bg_img_repeat' => 'repeat',
	'footer_bg_img_position' => 'top left',
	'footer_bg_img_attachment' => 'scroll',
	'footer_include' => 'yes',
	'footer_bg_color_bind' => 'on',
	'footer_bg_color' => '#232323',
	'footer_headings_font_family' => 'Arial, Helvetica, sans-serif',
	'footer_headings_font_color' => '#ffffff',
	'footer_headings_font_size' => '17',
	'footer_headings_margin_below' => '10',
	'footer_headings_font_style' => 'normal',
	'footer_link_font_size' => '13',
	'footer_widget_margin_below' => '30',
	'footer_subscribebyemail' => 'off',
	'footer_subscribebyemail_placement' => 'left',
	'footer_subscribebyemail_header' => 'Subscribe by email',
	'footer_subscribebyemail_note' => 'enter your email address to receive notification via email whenever there is a new post.',
	'footer_subscribebyemail_submit' => 'subscribe',
	
	
	// flash gallery options
	'flash_gal_fallback' => 'lightbox',
	'flash_gal_disable_full_screen' => 'false',
	'flash_gal_disable_slideshow_timer' => 'false',
	'flash_gal_button_color' => '#ffffff',
	'flash_gal_start_full_screen' => 'false',
	'flash_gal_bg_color' => '#000000',
	'flash_gal_main_corners' => '0',
	'flash_gal_overlay_height' => '30',
	'flash_gal_overlay_opacity' => '80',
	'flash_gal_title_font_color' => '#ffffff',
	'flash_gal_title_font_family' => '"Century Gothic", Helvetica, Arial, sans-serif',
	'flash_gal_title_font_size' => '33',
	'flash_gal_subtitle_font_size' => '22',
	'flash_gal_auto_start' => 'true',
	'flash_gal_loop_slideshow' => 'true',
	'flash_gal_slideshow_duration' => '2.5',
	'flash_gal_slideshow_transition_effect' => 'fade',
	'flash_gal_slideshow_transition_speed' => '1.2',
	'flash_gal_filmstrip_position' => 'bottom',
	'flash_gal_filmstrip_overlay' => 'true',
	'flash_gal_filmstrip_autohide' => 'true',
	'flash_gal_filmstrip_autohide_timeout' => '1',
	'flash_gal_filmstrip_opacity' => '45',
	'flash_gal_icon_opacity' => '40',
	'flash_gal_thumb_size' => '60',
	'flash_gal_thumb_corners' => '0',
	'flash_gal_thumb_border_width' => '1',
	'flash_gal_thumb_border_color' => '#ffffff',
	'flash_gal_thumb_size' => '60',
	'flash_gal_thumb_padding' => '10',
	'flash_gal_thumb_border_color' => '#ffffff',
	'flash_gal_thumb_border_width' => '1',
	'flash_gal_thumb_corners' => '0',
	'flash_gal_thumb_effect' => 'fade',
	'flash_gal_thumb_effect_speed' => '0.2',
	'flash_gal_thumb_effect_over' => '100',
	'flash_gal_thumb_effect_out' => '70',
	'flash_gal_mp3_autostart' => 'true',
	'flash_gal_mp3_loop' => 'true',
	

	// settings options
	// seo 
	'seo_title_home' => '%blog_name% &raquo; %blog_description%',
	'seo_title_post' => '%post_title% &raquo; %blog_name%',
	'seo_title_page' => '%page_title% &raquo; %blog_name%',
	'seo_title_front' => '%page_title% &raquo; %blog_name%',
	'seo_title_category' => '%category_title% &raquo; %blog_name%',
	'seo_title_archive' => '%archive_title% &raquo; %blog_name%',
	'seo_title_search' => '%search% &raquo; %blog_name%',
	'metadesc' => P3_SITE_DESC,
	'seo_tags_for_keywords' => 'true',
	'seo_meta_use_excerpts' => 'true',
	'seo_meta_auto_generate' => 'true',
	'seo_disable' => 'false',
	
	'audioplayer' => 'off',
	'audioplayer_autostart' => 'yes',
	'audioplayer_loop' => 'yes',
	'audioplayer_center_bg'=> '#f8f8f8',
	'audioplayer_text'  => '#666666',
	'audioplayer_left_bg' => '#eeeeee',
	'audioplayer_left_icon'=> '#666666',
	'audioplayer_right_bg'=> '#cccccc',
	'audioplayer_right_icon'=> '#666666',
	'audioplayer_right_bg_hover'=> '#999999',
	'audioplayer_right_icon_hover'=> '#ffffff',
	'audioplayer_slider'=> '#666666',
	'audioplayer_loader'=> '#9FFFB8',
	'audioplayer_track'=> '#ffffff',
	'audioplayer_track_border'=> '#666666',
	'audio_on_home' => 'yes',
	'audio_on_single' => 'no',
	'audio_on_pages' => 'no',
	'audio_on_archive' => 'no',
	'audio_hidden' => 'off',
	'sponsors' => 'off',
	'sponsors_side_margins' => '70',
	'sponsors_img_margin_right' => '15',
	'sponsors_img_margin_below' => '20',
	'sponsors_border_color' => '#cccccc',
	'nav_previous' => '&laquo; Older posts',
	'nav_next' => 'Newer posts &raquo;',
	'nav_previous_align' => 'left',
	'nav_next_align' => 'right',
	'blog_width' => '960',
	'backup_reminder' => 'on',
	'backup_email' => $admin_email,
	'show_hidden' => 'hide',
	'image_protection' => 'clicks',
	'maintenance_message' => 'This blog is temporarily unavailable while customizations are being made.  Please check back soon.  Thanks for your patience!',
	'maintenance_mode' => 'off',
	'pathfixer' => 'off',
	'pathfixer_old' => '',
	'override_css' => '',
	'custom_js' => '',
	'insert_into_head' => '',
	'post_signature' => '',
	'post_signature_on_home' => 'true',
	'post_signature_on_single' => '',
	'post_signature_on_page' => '',
	'post_signature_filter_priority' => 9,
	'dev_hide_options' => 'false',
	'des_export_widgets' => 'false',
	'des_html_mark' => '',
			
	// translation
	'translate_by' => 'by',
	'translate_commentform_message' => 'Your email is <em>never</em> published or shared.',
	'translate_comments' => 'comments',
	'translate_comment' => 'comment',
	'translate_no' => 'no',
	'translate_comments_required' => 'Required fields are marked',
	'translate_comments_name' => 'Name',
	'translate_comments_email' => 'Email',
	'translate_comments_website' => 'Website',
	'translate_comments_comment' => 'Comment',
	'translate_comments_button' => 'Post Comment',
	'translate_search_notfound_header' => 'Nothing found',
	'translate_search_notfound_text' => 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.',
	'translate_search_results' => 'Search Results for:',
	'translate_search_notfound_button' => 'Find',
	'translate_archives_monthly' => 'Monthly Archives:',
	'translate_archives_yearly' => 'Yearly Archives:',
	'translate_blog_archives' => 'Blog Archives',
	'translate_tag_archives' => 'Tag Archives:',
	'translate_category_archives' => 'Category Archives:',
	'translate_author_archives' => 'Author Archives:',
	'translate_404_header' => 'Not Found',
	'translate_404_text' => 'Apologies, but we were unable to find what you were looking for. Perhaps searching will help.',
	'translate_lightbox_image' => 'Image',
	'translate_lightbox_of' => 'of',
	'translate_comments_cancel_reply' => 'Cancel Reply',
	'translate_comments_error_message' => 'There was an error submitting your comment.  Please try again.',
	'translate_password_protected' => WP_PASSWORD_PROTECT_PHRASE,
	
	// galleries
	'lightbox_border_width' => '10',
	'lightbox_bg_color' => '#ffffff',
	'lightbox_font_size' => '10',
	'lightbox_font_family' => 'Verdana, Tahoma, sans-serif',
	'lightbox_font_color' => '#666666',
	'lightbox_overlay_color' => '#000000',
	'lightbox_overlay_opacity' => '80',
	'lightbox_resize_speed' => '400',
	'lightbox_image_fadespeed' => '400',
	'lightbox_fixed_navigation' => 'false',
	'lightbox_nav_btns_opacity' => '65',
	'lightbox_nav_btns_fadespeed' => '200',
	'lightbox_thumb_default_size' => '90',
	'lightbox_thumb_opacity' => '65',
	'lightbox_thumb_mouseover_speed' => '200',
	'lightbox_thumb_mouseout_speed' => '500',
	'lightbox_thumb_margin' => '5',
	'lightbox_main_img_center' => 'true',
);

// sliding drawers
for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) { 
	$p3_defaults['options']['drawer_content_width_'.$i] = '200';
	$p3_defaults['options']['drawer_tab_text_'.$i] = 'More info';	
}

// custom menu links
for ( $i = 1; $i <= MAX_CUSTOM_MENU_LINKS; $i++ ) { 
	$p3_defaults['options']['nav_customlink' . $i . '_target'] = '_self';
}

// contact form custom fields
for ( $i = 1; $i <= MAX_CONTACT_CUSTOM_FIELDS; $i++ ) { 
	$p3_defaults['options']['contact_customfield' . $i . '_required'] = 'no';
}
?>