<?php
/* -------------------------------------------- */
/* -- settings for interface/user experience -- */
/* -------------------------------------------- */


/* this array holds interface dependency relationships data */
$p3_interface = array(
	
	// background
	'blog_border_group'           => 'hide if blog_border dropshadow|none',
	'blog_border_shadow_width'    => 'hide if blog_border border|none',
	'blog_border_topbottom'       => 'hide if blog_border none',
	'prophoto_classic_bar_height' => 'hide if prophoto_classic_bar off',
	'prophoto_classic_bar_color'  => 'hide if prophoto_classic_bar off',
	'masthead_top_splitter'       => 'hide if masthead_display off',
	'masthead_btm_splitter'       => 'hide if masthead_display off',
	'bio_top_splitter'            => 'hide if bio_include no',
	'bio_btm_splitter'            => 'hide if bio_include no',
	'menu_top_splitter'           => 'hide if headerlayout pptclassic',
	'menu_btm_splitter'           => 'hide if headerlayout pptclassic',
	'logo_top_splitter'           => 'hide if headerlayout logomasthead_nav|mastlogohead_nav|mastheadlogo_nav',
	'logo_btm_splitter'           => 'hide if headerlayout logomasthead_nav|mastlogohead_nav|mastheadlogo_nav',
	
	// header
	'masthead_border_top_group'    => 'hide if masthead_border_top off',
	'masthead_border_bottom_group' => 'hide if masthead_border_bottom off',
	'flash_speed'                  => 'hide if masthead_display static|random|custom|off',
	'logo_swf' => 'hide if logo_swf_switch off',
	
	// menu
	'nav_galleries_dropdown_title'         => 'hide if nav_galleries_dropdown off',
	'navportfoliotitle'                    => 'hide if navportfolioonoff off',
	'navportfoliourl'                      => 'hide if navportfolioonoff off',
	'portfoliotarget'                      => 'hide if navportfolioonoff off',
	'nav_home_link_text'                   => 'hide if nav_home_link off',
	'nav_archives_threshold'               => 'hide if navarchivesonoff off',
	'navarchivestitle'                     => 'hide if navarchivesonoff off',
	'navcategoriestitle'                   => 'hide if navcategoriesonoff off',
	'navpagestitle'                        => 'hide if navpagesonoff off',
	'nav_pages_exclude'                    => 'hide if navpagesonoff off',
	'navrecentpoststitle'                  => 'hide if navrecentpostsonoff off',
	'navrecentpostslimit'                  => 'hide if navrecentpostsonoff off',
	'nav_emaillink_text'                   => 'hide if nav_emaillink_onoff off',
	'nav_emaillink_address'                => 'hide if nav_emaillink_onoff off',
	'nav_rss_options'                      => 'hide if nav_rss off',
	'nav_rsslink_text'                     => 'hide if nav_rss off',
	'subscribebyemail_nav_leftright'       => 'hide if subscribebyemail_nav off',
	'subscribebyemail_nav_submit'          => 'hide if subscribebyemail_nav off',
	'subscribebyemail_nav_textinput_value' => 'hide if subscribebyemail_nav off',
	'nav_search_dropdown'                  => 'hide if nav_search_onoff off',
	'nav_search_btn_text'                  => 'hide if nav_search_onoff off',
	'nav_search_align'                     => 'hide if nav_search_onoff off',
	'nav_search_dropdown_linktext'         => 'hide if nav_search_onoff off',
	'twitter_title'                        => 'hide if twitter_onoff off',
	'twitter_count'                        => 'hide if twitter_onoff off',
	'nav_border_top_group'                 => 'hide if nav_border_top off',
	'nav_border_bottom_group'              => 'hide if nav_border_bottom off',
	'nav_border_top'                       => 'hide if headerlayout pptclassic',
	'nav_border_bottom'                    => 'hide if headerlayout pptclassic',
	'nav_edge_padding'                     => 'hide if nav_align center',
	'nav_align'                            => 'hide if headerlayout pptclassic',
	'nav_edge_padding'                     => 'hide if headerlayout pptclassic',
	
	// contact
	'anti_spam_question_1'     => 'hide if contactform_antispam_enable off',
	'anti_spam_explanation'    => 'hide if contactform_antispam_enable off',
	'contact_btm_border_group' => 'hide if contact_btm_border off',
	
	// bio
	'hidden_bio_link_text'    => 'hide if use_hidden_bio no',
	'bio_pages_minimize_text' => 'hide if bio_pages_minimize none',
	'bio_border_group'        => 'hide if bio_border image|noborder',
	'biopic_border'     	  => 'hide if biopic_display off',
	'bio_separator'           => 'hide if bio_border border|noborder',
	'biopic_border_group'     => 'hide if biopic_border off',
	'biopic_align'            => 'hide if biopic_display off',
	'bio_pages_options'       => 'hide if use_hidden_bio yes',
	
	// content
	'postdate_advanced_switch'        => 'hide if postdate boxy|off',
	'feed_only_thumbs'                => 'hide if modify_feed_images false|remove',
	'modify_feed_images_alert'        => 'hide if modify_feed_images false',
	'watermark_position'              => 'hide if image_protection none|right_click|clicks|replace',
	'watermark_alpha'                 => 'hide if image_protection none|right_click|clicks|replace',
	'watermark_startdate'             => 'hide if image_protection none|right_click|clicks|replace',
	'watermark'                       => 'hide if image_protection none|right_click|clicks|replace',
	'post_header_separator'           => 'hide if post_header_border on|off',
	'postdate_bg_color'               => 'hide if postdate_advanced_switch off',
	'postdate_border_group'           => 'hide if postdate_advanced_switch off',
	'postdate_lr_padding'             => 'hide if postdate_advanced_switch off',
	'postdate_tb_padding'             => 'hide if postdate_advanced_switch off',
	'postdate_border_sides'           => 'hide if postdate_advanced_switch off',
	'dig_for_excerpt_image'           => 'hide if show_excerpt_image false',
	'excerpt_image_size'              => 'hide if show_excerpt_image false',
	'excerpt_image_position'          => 'hide if show_excerpt_image false',
	'post_header_postdate_font_group' => 'hide if postdate boxy|off',
	'postdate_placement'              => 'hide if postdate boxy|off',
	'displaytime'                     => 'hide if postdate boxy|off',
	'dateformat'                      => 'hide if postdate boxy|off',
	'dateformat_custom'               => 'hide if postdate boxy|off',
	'boxy_date_font_size'             => 'hide if postdate normal|off',
	'boxy_date_bg_color'              => 'hide if postdate normal|off',
	'boxy_date_align'                 => 'hide if postdate normal|off',
	'boxy_date_month_color'           => 'hide if postdate normal|off',
	'boxy_date_day_color'             => 'hide if postdate normal|off',
	'boxy_date_year_color'            => 'hide if postdate normal|off',
	'post_header_border_group'        => 'hide if post_header_border off|image',
	'post_header_border_margin'       => 'hide if post_header_border off|image',
	'post_sep_border_group'           => 'hide if post_divider none|image',
	'post_sep'                        => 'hide if post_divider line|none',
	'archive_comments_showhide'       => 'hide if archive_comments off',
	'archive_post_sep_border_group'   => 'hide if archive_post_divider same',
	'archive_padding_below_post'      => 'hide if archive_post_divider same',
	'like_btn_layout'                 => 'hide if like_btn_enable false',
	'like_btn_placement'              => 'hide if like_btn_enable false',
	'like_btn_show_faces'             => 'hide if like_btn_enable false',
	'like_btn_verb'                   => 'hide if like_btn_enable false',
	'like_btn_color_scheme'           => 'hide if like_btn_enable false',
	'like_btn_show_advanced'          => 'hide if like_btn_enable false',
	'like_btn_filter_priority'        => 'hide if like_btn_enable false',
	'like_btn_margin_top'             => 'hide if like_btn_enable false',
	'like_btn_margin_btm'             => 'hide if like_btn_enable false',
	'like_btn_on_home'                => 'hide if like_btn_enable false',
	'like_btn_on_single'              => 'hide if like_btn_enable false',
	'like_btn_on_page'                => 'hide if like_btn_enable false',
	'post_pic_shadow_vertical_offset' => 'hide if post_pic_shadow_enable false',
	'post_pic_shadow_horizontal_offset' => 'hide if post_pic_shadow_enable false',
	'post_pic_shadow_blur'            => 'hide if post_pic_shadow_enable false',
	'post_pic_shadow_color'           => 'hide if post_pic_shadow_enable false',
	'dateformat_custom'               => 'hide if dateformat long|medium|short|longabrvdash|shortabrvdash|longabrvast|shortabrvast|longdot|shortdot',
	
	
	// comments
	'comments_header_tb_padding'                => 'hide if comments_layout tabbed',
	'comments_body_scroll_height'               => 'hide if comments_layout boxy',
	'comments_open_closed'                      => 'hide if comments_layout boxy',
	'comments_post_interact_display'            => 'hide if comments_layout boxy|tabbed',
	'comments_show_hide_method'                 => 'hide if comments_layout boxy|tabbed',
	'comments_addacomment_image'                => 'hide if comments_layout boxy|tabbed',
	'comments_header_bg'                        => 'hide if comments_layout tabbed',
	'comments_lr_margin'                        => 'hide if comments_lr_margin_switch inherit',                 
	'comments_area_border_group'                => 'hide if comments_layout tabbed',
	'comments_header_border_lines'              => 'hide if comments_layout boxy',
	'comments_post_interaction_link_font_group' => 'hide if comments_post_interact_display images',
	'comments_minima_show_text'                 => 'hide if comments_show_hide_method button',
	'comments_minima_hide_text'                 => 'hide if comments_show_hide_method button',
	'gravatar_size'                             => 'hide if gravatars off',
	'gravatar_align'                            => 'hide if gravatars off',
	'gravatar_padding'                          => 'hide if gravatars off',
	'comments_comment_timestamp_font_group'     => 'hide if comment_timestamp_display off',
	'comments_header_bg_color'                  => 'hide if comments_layout tabbed',
	'comments_timestamp_byauthor_color'         => 'hide if comment_timestamp_display off',
	'comments_timestamp_alt_color'              => 'hide if comment_timestamp_display off',
	'comments_header_lr_padding'                => 'hide if comments_layout tabbed|boxy',
	'comment_meta_margin_bottom'                => 'hide if comment_meta_display inline',
	'comments_comment_border_group'             => 'hide if comments_comment_border_onoff off',
	
	// footer
	'footer_bg'                         => 'hide if footer_include no',
	'footer_left_padding'               => 'hide if footer_include no',
	'footer_headings_font_group'        => 'hide if footer_include no',
	'footer_link_font_group'            => 'hide if footer_include no',
	'sponsors_side_margins'             => 'hide if sponsors off',
	'sponsors_img_margin_right'         => 'hide if sponsors off',
	'sponsors_img_margin_below'         => 'hide if sponsors off',
	'sponsors_border_color'             => 'hide if sponsors off',
	
	// galleries
	'lightbox_nav_btns_fadespeed'          => 'hide if lightbox_fixed_navigation true',
	'flash_gal_filmstrip_autohide'         => 'hide if flash_gal_filmstrip_overlay false',
	'flash_gal_filmstrip_autohide_timeout' => 'hide if flash_gal_filmstrip_overlay false',
	'flash_gal_filmstrip_opacity'          => 'hide if flash_gal_filmstrip_overlay false',
	'flash_gal_filmstrip_autohide_timeout' => 'hide if flash_gal_filmstrip_autohide false',
	'flash_gal_start_full_screen'          => 'hide if flash_gal_disable_full_screen true',
	'flash_gal_slideshow_transition_speed' => 'hide if flash_gal_slideshow_transition_effect jump',
	
	// settings
	'maintenance_message'   => 'hide if maintenance_mode off',
	'pathfixer_old'         => 'hide if pathfixer off',
	'pathfixer_new'         => 'hide if pathfixer off',
	'backup_email'          => 'hide if backup_reminder off',
	'seo_title_home'        => 'hide if seo_disable true',
	'metadesc'              => 'hide if seo_disable true',
	'metakeywords'          => 'hide if seo_disable true',
	'noindexoptions'        => 'hide if seo_disable true',
	'audio_upload_note'     => 'hide if audioplayer off',
	'audioplayer_center_bg' => 'hide if audioplayer off',
	'audiooptions'          => 'hide if audioplayer off',
	'audio_where'           => 'hide if audioplayer off',
	'audio_hidden'          => 'hide if audioplayer off',
	'audio_ftp_files'       => 'hide if audioplayer off',
	
	// sidebar
	'sidebar_on_which_pages'       => 'hide if sidebar false',
	'sidebar_border_group'         => 'hide if sidebar_border_switch off',
	'sidebar_width'                => 'hide if sidebar false',
	'sidebar_bg_color'             => 'hide if sidebar false',
	'sidebar_headlines_font_group' => 'hide if sidebar false',
	'sidebar_text_font_group'      => 'hide if sidebar false',
	'sidebar_link_font_group'      => 'hide if sidebar false',
	'sidebar_border_switch'        => 'hide if sidebar false',
	'sidebar_widget_sep_img'       => 'hide if sidebar false',
	'sidebar_bg'                   => 'hide if sidebar false',
);


/* live font area preview text */
$paragraphs = '<div class="margin-bottom">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</div>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';

$p3_font_preview_text = array(
	// bio
	'bio_header' => 'This is a medium-length sample headline',
	'bio_link' => 'Some sample bio text <a href="">with a link</a> and <a href="">another text link</a>.',
	
	// comments
	'comments_header_link' => 'by <a href="">Joe Postauthor</a> &nbsp; <a href="">show 5 comments</a>',
	'comments_comment_link' => '<a href="">Joe Commenter</a> - a comment',
	'comments_post_interaction_link' => '<a href="">Add a Comment</a><a href="">Link to this post</a><a href="">Email a friend</a>',
	
	// content
	'post_title_link' => '<a class="margin-bottom" href="">This is a sample post title link</a>',
	'post_header_meta_link' => 'February 23, 2010 &nbsp;&nbsp; Posted in <a href="">Weddings</a> &nbsp;&nbsp; Tagged: <a href="">Holland</a>, <a href="">Bridal</a>',
	'post_footer_meta_link' => 'Posted in <a href="">Weddings</a> &nbsp;&nbsp; Tagged: <a href="">Holland</a>, <a href="">Bridal</a>',
	'post_text' => $paragraphs,
	'archive_h2' => 'Monthly Archives: February 2010',
	
	// fonts
	'gen_link' => 'Some text <a href="">with a nice big link</a> followed by even more random text <a href="">and another example link</a>.',
	'header' => 'This is a medium-length sample headline',
	'gen' => $paragraphs,
	
	// footer
	'footer_headings' => 'A Sample Footer Heading',
	'footer_link' => 'Some footer plain text &nbsp;&nbsp;&nbsp;&nbsp;<a href="">A sample footer link</a>',
	
	// galleries
	'flash_gal_title' => 'Rick and Michelle\'s Slideshow',
	'flash_gal_subtitle' => 'Friday, February 23, 2010',
	'lightbox' => '<strong>Wedding_01</strong><br />Image 1 of 11',
	
	// sidebar
	'sidebar_headlines' => 'Sidebar Headline',
	'sidebar_link' => 'Some sidebar text <a href="">with a link</a> and <a href="">another text link</a>.',
	'drawer_widget_headlines' => 'Drawer Headline',
	'drawer_tab' => 'M<br />o<br />r<br />e<br /> <br />I<br />n<br />f<br />o',
	'drawer_widget_link' => 'Some drawer widget text <a href="">with a link</a> and <a href="">another text link</a>.',
	
	// default
	'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
	
);

?>