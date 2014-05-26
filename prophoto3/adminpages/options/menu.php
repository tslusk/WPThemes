<?php
/* ----------------------- */
/* NAVIGATION MENU OPTIONS */
/* ----------------------- */


echo <<<HTML
<style type="text/css" media="screen">
.nav-icon-link-empty {
	display:none;
}
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	jQuery('.nav-icon-link-empty:first').show();
	jQuery('#add-custom-menu-upload').unbind('click').click(function(){
		if ( jQuery('.nav-icon-link-empty:hidden').length )
			jQuery('.nav-icon-link-empty:hidden:first').show();
		else
			jQuery(this).hide();
	});
	
	// custom menu link UI
	jQuery('.option[id^=nav_customlink]').each(function(){
		if ( jQuery('.text-input', this).eq(1).val() == '' ) {
			jQuery(this).addClass('url-empty');
		} 
	});
	jQuery('.url-empty').not(':first').hide();

});	
</script>
HTML;



// tabs and header
p3_subgroup_tabs(  array(
	'background' => 'Background',
	'appearance-general' => 'General Appearance',
	'menu-text' => 'Text Appearance',
	'menu-items' => 'Add/Remove Menu Items',
	'order' => 'Reorder Menu Items',
) );
p3_option_header('Navigation Menu Options', 'menu' );


/* background subgroup  */
p3_option_subgroup( 'background' );

// main menu background image/color
p3_bg( 'nav_bg', 'Menu background color &amp; image' );

// dropdown bg colors
p3_start_multiple( 'Menu dropdown background colors' );
p3_o( 'nav_dropdown_bg_color', 'color|optional', 'background color of dropdown area of menus' );
p3_o( 'nav_dropdown_bg_hover_color', 'color|optional', 'color of background of dropdown menu links when being hovered over' );
p3_stop_multiple();

// dropdown options
p3_o('nav_dropdown_opacity', 'slider', 'opacity of dropdown menu<br /><em>(note: does not affect Internet Explorer)</em>', 'Dropdown menu opacity' );

p3_end_option_subgroup();



/* menu-text subgroup  */
p3_option_subgroup( 'menu-text' );

// menu links
p3_font_group( array(
	'key' => 'nav_link',
	'title' => 'Menu link appearance',
	'inherit' => 'all',
	'add' => array( 'letterspacing' ),
) );

// dropdown links
p3_start_multiple( 'Menu dropdown links' );
p3_o( 'nav_dropdown_link_textsize', 'text|3', 'size (in pixels) for link text in drop down menus' );
p3_o( 'nav_dropdown_link_font_color', 'color|optional', 'dropdown link color' );
p3_o( 'nav_dropdown_link_hover_font_color', 'color|optional', 'dropdown link hover state color' );
p3_stop_multiple();

p3_end_option_subgroup();


/* add/remove menu items */
p3_option_subgroup( 'menu-items' );

// portfolio link
p3_start_multiple( 'Menu portfolio link' );
p3_o( 'navportfolioonoff', 'radio|on|display portfolio link|off|do not display portfolio link', 'the link to your portfolio website in your navigation menu' );
p3_o( 'navportfoliotitle', 'text|25', 'Link text for your portfolio website link' );
p3_o( 'navportfoliourl', 'text|25', 'web address for your portfolio link' );
p3_o( 'portfoliotarget', 'radio|_self|same window|_blank|new window', 'link, when clicked, opens in same browser window or new window/tab' );
p3_stop_multiple();

// home link
p3_start_multiple( 'Home Link' );
p3_o( 'nav_home_link', 'radio|on|add home link|off|do not add home link', 'Show a "Home" link in the left-hand side of the navigation menu. When clicked, always takes user to home page of blog' );
p3_o( 'nav_home_link_text', 'text|25', 'text of "home" link' );
p3_stop_multiple();

// featured galleries
p3_start_multiple( 'Featured galleries dropdown' );
p3_o( 'nav_galleries_dropdown', 'radio|on|show dropdown of featured galleries|off|do not show dropdown' );
p3_o( 'nav_galleries_dropdown_title', 'text|25', 'Text for header of featured galleries dropdown menu' );
p3_stop_multiple();

// archives
p3_start_multiple( 'Archives dropdown' );
p3_o( 'navarchivesonoff', 'radio|on|show Archives|off|do not show Archives', 'display monthly archive links in a dropdown menu' );
p3_o( 'navarchivestitle', 'text|25', 'Text for header of Archives drop-down menu' );
p3_o( 'nav_archives_threshold', 'text|2', 'after this many months displayed in dropdown menu, switch to months nested within years' );
p3_stop_multiple();

// categories
p3_start_multiple( 'Categories dropdown' );
p3_o( 'navcategoriesonoff', 'radio|on|show categories|off|do not show categories', 'display category links in a dropdown menu' );
p3_o( 'navcategoriestitle', 'text|25', 'Text for header of categories drop-down menu' );
p3_stop_multiple();

// blogroll
p3_o('navblogrollonoff', 'radio|on|show blogroll|off|do not show blogroll',  'display menu of links (blogroll) in a dropdown menu', 'Blogroll dropdown' );

// contact form link
p3_o('contactform_link_text', 'text', 'Text for "Contact" form link', 'Contact form link text' );

// pages
p3_start_multiple( 'Pages dropdown' );
p3_o( 'navpagesonoff', 'radio|on|show pages|off|do not show pages', 'display links to "Pages" in a dropdown menu' );
p3_o( 'navpagestitle', 'text|25', 'Text for header of pages drop-down menu' );
p3_o( 'nav_pages_exclude', 'function|p3_exclude_pages', 'exclude checked pages from dropdown' );
p3_stop_multiple();

// recent posts
p3_start_multiple( 'Recent Posts dropdown' );
p3_o( 'navrecentpostsonoff', 'radio|on|show recent posts|off|do not show recent posts', 'display links to recent posts in a dropdown menu' );
p3_o( 'navrecentpoststitle', 'text|25', 'Text for header of recent posts drop-down menu' );
p3_o( 'navrecentpostslimit', 'text|3', 'number of recent posts shown in drop down menu' );
p3_stop_multiple();

// email me link
p3_start_multiple( 'Email link' );
p3_o( 'nav_emaillink_onoff', 'radio|on|include "email me" link|off|do not include "email me" link', 'Include a link to your email address. This is an alternative to the built-in contact form, and is protected against spam.' );
p3_o( 'nav_emaillink_text', 'text|25', 'text of email link' );
p3_o( 'nav_emaillink_address', 'text|32', 'email address' );
p3_stop_multiple();

// rss
p3_start_multiple( 'RSS link' );
p3_o( 'nav_rss', 'radio|on|include link|off|do not include link', 'link to your RSS feed in the nav menu' );
p3_o( 'nav_rss_options', 'checkbox|nav_rss_use_icon|yes|include RSS icon|nav_rss_use_linktext|yes|include link text', 'use icon or text link or both?' );
p3_o( 'nav_rsslink_text', 'text|15', 'text for RSS link' );
p3_stop_multiple();

// subscribe by email
p3_start_multiple( 'Subscribe by email' );
if ( p3_test( 'feedburner' ) ) {
	p3_o( 'note', 'note', p3_get_msg( 'requires_email_subscription_enabled' ) );
	p3_o( 'subscribebyemail_nav', 'radio|on|include subscribe by email form|off|do not include subscribe by email form', 'include option for users to subscribe to blog updates by email' );
	p3_o( 'subscribebyemail_nav_leftright', 'radio|left|align left|right|align right', 'alignment of subscribe by email form in nav bar' );
	p3_o( 'subscribebyemail_nav_textinput_value', 'text', 'text shown in input box before user types' );
	p3_o( 'subscribebyemail_nav_submit', 'text', 'text shown on submit button' );
	
	
} else {
	p3_o( 'subscribebyemail_note', 'note', p3_get_msg( 'requires_feedburner_feed', p3_get_admin_url( 'content', 'feed' ) ) );
	
}
	
p3_stop_multiple();

// search
p3_start_multiple( 'Search options' );
p3_o( 'nav_search_onoff', 'radio|on|show search box|off|do not show search box', 'display the search box in the main navigation menu' );
p3_o( 'nav_search_align', 'radio|right|search box aligned right|left|search box aligned normally', 'alignment of search box in navigation menu' );
p3_o( 'nav_search_btn_text', 'text|15', 'text on search submit button' );
p3_o( 'nav_search_dropdown', 'radio|off|show search form in nav bar|on|show search form in dropdown box', 'search form appearance - in nav menu, or in a box that drops down when a text "search" link is hovered over' );
p3_o( 'nav_search_dropdown_linktext', 'text', 'text of search dropdown link (only used if search shown in dropdown box)' );
p3_stop_multiple();

// twitter
p3_start_multiple( 'Twitter dropdown menu' );
p3_o( 'twitter_onoff', 'radio|off|do not include twitter updates|on|include twitter updates', 'Embed <a href="http://www.twitter.com">Twitter</a> tweets in dropdown menu. <strong>You must enter your Twitter ID in the <a href="' . p3_get_admin_url( 'settings', 'social media' ) . '">Social media tab</a> for this feature to work.</strong>' );
p3_o( 'twitter_title', 'text|17', 'title of Twitter dropdown menu' );
p3_o( 'twitter_count', 'text|2', 'show how many tweets?' );
p3_stop_multiple();

//custom links
for ( $i = 1; $i <= MAX_CUSTOM_MENU_LINKS; $i++ ) {
	$link = new p3_nav_icon_link( 'nav_customlink' . $i . '_icon' );
	echo $link->markup;
}
p3_add_image_upload_btn( 'custom menu', 'link' );



p3_end_option_subgroup();


/* reorder menu items */
p3_option_subgroup( 'order' );

// arrange menu items
p3_o( 'reorder_menu', 'function|p3_reorder_menu_option', 'Click and drag to reorder your currently selected menu items', 'Arrange menu items' );
p3_end_option_subgroup();


/* advanced menu appearance */
p3_option_subgroup( 'appearance-general' );

// nav alignment
p3_o( 'nav_align', 'radio|left|align navigation menu to the left|center|center align navigation menu|right|align navigation menu to the right', '', 'Menu alignment' );

// link padding/spacing
p3_start_multiple( 'Menu link custom spacing' );
p3_o( 'nav_link_spacing_between', 'text|3', 'override default spacing (in pixels) between menu links' );
p3_o( 'nav_edge_padding','text|3', 'override spacing (in pixels) between the menu and the edge of the blog' );
p3_stop_multiple();

// nav top border
p3_start_multiple( 'Custom line above menu' );
p3_o( 'nav_border_top', 'radio|on|add custom line|off|do not add custom line', 'add/remove a custom line above the menu' );
p3_border_group( array( 'key' => 'nav_border_top', 'comment' => 'custom line appearance' ) );
p3_stop_multiple();

// nav bottom border
p3_start_multiple( 'Custom line below menu' );
p3_o( 'nav_border_bottom', 'radio|on|add custom line|off|do not add custom line', 'add/remove a custom line below the menu' );
p3_border_group( array( 'key' => 'nav_border_bottom', 'comment' => 'custom line appearance' ) );
p3_stop_multiple();

p3_end_option_subgroup();

?>