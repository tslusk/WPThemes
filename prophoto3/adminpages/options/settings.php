<?php
/* ----------------------- */
/* ----ADVANCED OPTIONS--- */
/* ----------------------- */


/* ad banner css/js */
echo <<<HTML
<style type="text/css" media="screen">
#subgroup-ads .upload-row,
#add-banner-upload {
	display:none;
}
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	function show_img_stuff() {
		jQuery('#subgroup-ads .has-img, #subgroup-ads .no-img:first, #add-banner-upload').css('display', 'block');
	}
	if (jQuery('#p3-input-sponsors-on').is(':checked')) show_img_stuff();
	jQuery('#p3-input-sponsors-on').click(function(){show_img_stuff()});
	jQuery('#p3-input-sponsors-off').click(function(){
		jQuery('#subgroup-ads .upload-row, #add-banner-upload').hide();
	});
});
</script>
HTML;


// tabs and header
p3_subgroup_tabs(  array(
	'settings' => 'Blog Settings',
	'seo' => 'Search Engine Optimization (SEO)',
	'audio' => 'Music Player',
	'ads' => 'Ad Banners',
	'socialmedia' => 'Social Media',
	'translation' => 'Translation',
	'advanced' => 'Advanced',
) );
p3_option_header('Settings', 'settings' );



/* blog settings */
p3_option_subgroup( 'settings' );

// designer license options
if ( $_GET['designer'] ) {
	p3_start_multiple( 'Designer options' );
	p3_o( 'des_export_widgets', 'radio|false|do not export widgets|true|export widgets with zip file', 'optionally also export widgets with design zip if they are needed for design' );
	p3_o( 'des_html_mark', 'text|39', 'custom HTML for added footer attribution link (one link max.)' );
	p3_stop_multiple();
}

// maintainance mode
p3_start_multiple( 'Under construction mode' );
p3_o( 'maintenance_mode', 'radio|on|under construction mode on|off|under construction mode off', 'Display an "under construction" message to all blog visitors except you. Used to prevent viewing of your blog while customizations are being worked on.' );
p3_o( 'maintenance_message', 'textarea|3|32', 'message displayed to visitors when in "under construction" mode' );
p3_stop_multiple();

// google analytics 
p3_o('google_analytics_code', 'textarea|8|75', 'Paste in your Google Analytics tracking code (new version - ga.gs) here. Will not count your own visits to your blog.', 'Google Analytics' );

// statcounter 
p3_o('statcounter_code', 'textarea|8|75', 'Paste in your Statcounter setup code here. Will not count your own visits to your blog.', 'Statcounter Analytics' );

// favicon image
p3_file( 'favicon', 'Favicon', 'This is the little icon that appears left of the website address in your web browser. You must create a square image file and then convert it to a <strong>.ico</strong> file before uploading. Read a <a href="' . FAVICON_TUT_URL .'">tutorial here</a>.' );

//iphone webclip image
p3_image( 'iphone_webclip_icon', 'iPhone Webclip Icon', 'This is the image that is displayed on an iPhone when someone saves your blog address to their iPhone homepage. This image must conform to the size recommendations.' );

// pathfixer
p3_start_multiple( 'Blog path fixer' );
p3_o( 'pathfixer', 'radio|on|on|off|off', 'If you have changed the address of your blog and your old uploaded images no longer load, turn on to fix your image paths.' );
p3_o( 'pathfixer_old', 'text|37', 'Address of blog before address change' );
p3_stop_multiple();

// backup reminder
p3_start_multiple( 'Backup reminder' );
p3_o( 'backup_reminder', 'radio|on|on|off|off', 'Email me monthly with a reminder to backup my blog' );
p3_o( 'backup_email', 'text|37', 'Email address that backup reminders will be sent to.' );
p3_stop_multiple();

// registration
p3_start_multiple( 'P3 Registration' );
if ( !p3_test( 'payer_email' ) || !p3_test( 'txn_id' ) ) {
	p3_o( 'reg', 'note', 'You have not successfully registered your copy of ProPhoto.  This means that you will not be able to receive free updates as they are released, or get a discounted price on future major upgrades until you do.  To register, <a href="' . admin_url( 'themes.php?activated=true' ) . '">click here</a> and fill out the simple form.' );
	
} else {
	p3_o( 'reg', 'note', 'You have successfully registered your copy of ProPhoto.  Your registration info is shown below:<br /><br />Purchase email: ' . p3_get_option( 'payer_email' ) . '<br />Transaction ID: ' . p3_get_option( 'txn_id' ) );
}
p3_stop_multiple();

p3_end_option_subgroup();



/* SEO */
p3_option_subgroup( 'seo' );

if ( class_exists( 'All_in_One_SEO_Pack' ) ) {
	p3_o( 'seo_pack_advise', 'note', 'ProPhoto has detected that you are using the plugin <strong>"All in One SEO Pack"</strong>, so it has disabled it\'s own SEO options.  If you would prefer to use ProPhoto\'s SEO options go to the <a href="' . admin_url( 'plugins.php' ) . '">plugins page</a> and deactivate "All in One SEO Pack."', 'SEO options disabled' );
	
} else {
	// disable SEO
	p3_o( 'seo_disable', 'radio|false|SEO options enabled|true|SEO options disabled', 'disable ProPhoto3 SEO options if you would rather use a plugin to handle your SEO options', 'SEO options' );

	// title tags
	p3_start_multiple( 'Title tags' );
	p3_o( 'seo_title_home', 'text|30', 'title tag on <strong>blog ' . $home = ( P3_HAS_STATIC_HOME ) ? 'posts' : 'home' . ' page</strong>' );
	if ( P3_HAS_STATIC_HOME ) p3_o( 'seo_title_front', 'text|30', 'title tag on <strong>static home page</strong>' );
	p3_o( 'seo_title_post', 'text|30', 'title tags on <strong>single posts</strong>' );
	p3_o( 'seo_title_page', 'text|30', 'title tags on <strong>pages</strong>' );
	p3_o( 'seo_title_category', 'text|30', 'title tags on <strong>category pages</strong>' );
	p3_o( 'seo_title_archive', 'text|30', 'title tags on <strong>archive pages</strong>' );
	p3_o( 'seo_title_search', 'text|30', 'title tags on blog <strong>search result pages</strong>' );
	p3_stop_multiple();

	// meta descriptions
	p3_start_multiple( 'Meta descriptions' );
	p3_o( 'metadesc', 'textarea|5|30', 'Write a custom "meta description" - this will often be what appears in search engine results pages beneath the link to your blog. If blank, the theme will use the "Tagline" for your blog from "Settings" => "General"' );
	p3_o( 'metadesc_options', 'checkbox|seo_meta_use_excerpts|true|use excerpts for post/page meta descriptions|seo_meta_auto_generate|true|auto-generate meta descriptions when no excerpt' );

	// meta keywords
	p3_start_multiple( 'Meta keywords' );
	p3_o( 'metakeywords', 'textarea|3|30', 'Enter a list of keywords and keyword phrases, separated by commas, for the meta keywords tag -- 10 max.' );
	p3_o( 'metakeywords_tags', 'checkbox|seo_tags_for_keywords|true|use post tags as keywords when possible' );
	p3_stop_multiple();

	// noindex
	p3_o( 'noindexoptions', 'checkbox|noindex_archive|true|monthly archives|noindex_category|true|categories|noindex_search|true|search results|noindex_tag|true|tag archives|noindex_author|true|author archives', 'If you have elected to show full posts on archive and category pages instead of excerpts, checking these can help prevent duplicate content penalties from search engines.', 'Prevent duplicate content' );
	p3_stop_multiple();
}

p3_end_option_subgroup();



/* music player */
p3_option_subgroup( 'audio' );

echo <<<HTML
<style type="text/css" media="screen">
#subgroup-audio .no-file { display:none; }
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	var audioplayer = jQuery('#audioplayer-individual-option input:checked').val();
	jQuery('body').addClass('audioplayer-'+audioplayer);
	jQuery('#audioplayer-individual-option input').click(function(){
		jQuery('body').removeClass('audioplayer-off audioplayer-top audioplayer-bottom').addClass('audioplayer-'+jQuery(this).val());
	});
	jQuery('.no-file:first').show();
});
</script>
HTML;

// audio player
p3_start_multiple( 'Audio MP3 Player' );
p3_o( 'audioplayer', 'radio|off|off|bottom|bottom of page|top|top of page', 'activate and place the built-in audio MP3 player to provide music for your blog' );
p3_o( 'audiooptions', 'checkbox|audioplayer_autostart|yes|music should autostart|audioplayer_loop|yes|music should loop when finished' );
if ( P3_HAS_STATIC_HOME ) {
	$home = 'Posts page';
	$static = '|audio_on_front_page|yes|Static front page';
} else {
	$home = 'Home page';
}
p3_o( 'audio_where', "checkbox{$static}|audio_on_home|yes|$home|audio_on_single|yes|Single post pages|audio_on_pages|yes|Pages|audio_on_archive|yes|Archive, category, tag, search, & author", 'choose which types of pages you want the audio player to appear on' );
p3_o( 'audio_hidden', 'radio|on|hide audio player|off|show audio player (recommended)', 'Hide audio player.  If hidden, user will not be able to stop music.' );
p3_stop_multiple();

// audio player colors
p3_start_multiple( 'Audio player custom colors' );
p3_o( 'audioplayer_center_bg', 'color', 'color of center of player when playing', 'first' );
p3_o( 'audioplayer_text', 'color', 'color of text in audio player' );
p3_o( 'audioplayer_left_bg', 'color', 'color of background of left side of player' );
p3_o( 'audioplayer_left_icon', 'color', 'color of speaker icon in left side of player' );
p3_o( 'audioplayer_right_bg', 'color', 'color of background of right side of player' );
p3_o( 'audioplayer_right_icon', 'color', 'color of play/pause icon in right side of player' );
p3_o( 'audioplayer_right_bg_hover', 'color', 'color of background of right side of player when hovered over' );
p3_o( 'audioplayer_right_icon_hover', 'color', 'color of play/pause icon in right side of player when hovered over' );
p3_o( 'audioplayer_slider', 'color', 'color of slider' );
p3_o( 'audioplayer_loader', 'color', 'color of loader bar' );
p3_o( 'audioplayer_track', 'color', 'color of track bar' );
p3_o( 'audioplayer_track_border', 'color', 'color of border of track bar' );
p3_stop_multiple();

// FTP-uploaded audio files
if ( p3_get_ftped_audio_files() ) {
	p3_o( 'audio_ftp_files', 'function|p3_audio_player_ftped', 'List above shows the MP3 files you have uploaded with FTP into the <code>music</code> folder of <code>' . WP_UPLOAD_PATH . '/p3</code>. Click to select them for inclusion in your audio player, or you can upload more files and return to this page. More info <a href="' . FTP_UPLOAD_AUDIO_TUT . '" target="_blank">here</a>.', 'Include FTP-uploaded audio MP3 files' );
} else {
	p3_o( 'audio_ftp_files', 'note', 'Uploading MP3 files through this page is limited by most web hosts to <strong>files of 2mb or less</strong>. Many audio MP3 files are larger than that, and cannot be uploaded here.  If you have larger file you want to use, you <strong>can upload it using an FTP program</strong> into your <code>p3/music</code> folder.  Then refresh this page and you will be able to select that file for use in your audio player. Full tutorial is <a href="' . FTP_UPLOAD_AUDIO_TUT . '" target="_blank">here</a>.', 'About manually uploading MP3 files' );
}

// audio uploads
for ( $i = 1; $i <= MAX_AUDIO_FILE_UPLOADS; $i++ ) { 
	$mp3_upload = new p3_audio_upload( 'audio' . $i, 'Audio MP3 File ' . $i, '' );
	echo $mp3_upload->markup;
}
p3_add_image_upload_btn( 'Audio', 'upload' );

p3_end_option_subgroup();


 
/* sponsor banner ads */
p3_option_subgroup( 'ads' );

// banner ad options
p3_start_multiple( 'Ad banners' );
p3_o( 'sponsors', 'radio|on|on|off|off', 'add banner links or ads in the bottom of your blog', 'first' );
p3_o( 'sponsors_side_margins', 'text|3', 'spacing (in pixels) on left and right side of entire sponsor banner area', '' );
p3_o( 'sponsors_img_margin_right', 'text|3', 'spacing (in pixels) between sponsor banners (side to side)', '' );
p3_o( 'sponsors_img_margin_below', 'text|3', 'spacing (in pixels) below sponsor banners' );
p3_o( 'sponsors_border_color', 'color', 'color of border around sponsor banners' );
p3_o( '', 'blank' );
p3_stop_multiple();

// banner ad images
for ( $i = 1; $i <= MAX_BANNERS; $i++ ) { 
	p3_linked_image( 'banner' . $i, 'Banner ' . $i, '', 'link banner to this address' );
}

p3_add_image_upload_btn( 'Banner' );

p3_end_option_subgroup();



/* social media */
p3_option_subgroup( 'socialmedia' );

// main twitter info
p3_start_multiple( 'Main Twitter account info' );
p3_o( 'twitter_name', 'text', 'Your Twitter name. [<a style="cursor:pointer" onclick="javascript: jQuery(\'#twitter-name-explain\').slideToggle();">?</a>]</p><p id="twitter-name-explain" style="display:none">This is your twitter username, and is also the end of your twitter address. So if your twitter address is <span style="white-space:nowrap;font-family:courier, monospace;">http://twitter.com/susiephoto</span><br />then "susiephoto" is your twitter name' );
p3_stop_multiple();

// facebook homepage img
p3_image( 'fb_home', 'Facebook homepage thumbnail image', 'ProPhoto will tell Facebook to use this image thumbnail when someone links to your blog\'s <strong>home page</strong>' );

// facebook set post images
p3_o( 'fb_set_post_image', 'radio|false|select image when linking|true|use featured or first image', 'when sharing links to posts or pages on Facebook, should ProPhoto specify what image to use as thumbnail, or allow selection', 'Facebook post/page thumbnail images' );

p3_end_option_subgroup();



/* customizations */
p3_option_subgroup( 'advanced' );

// developer license options
if ( nrIsIn( 'pp_dev', $_SERVER['HTTP_USER_AGENT'] ) ) {
	p3_start_multiple( 'Developer options' );
	p3_o( 'dev_hide_options', 'radio|false|do not hide options from end user|true|hide options from end user', 'optionally hide the "P3 Customize" and "P3 Designs" pages from end user.' );
	p3_o( 'dev_html_mark', 'text|39', 'custom HTML for added footer attribution link' );
	p3_stop_multiple();
}

// custom css
p3_o( 'override_css', 'textarea|18|105', 'Custom CSS rules', 'Custom CSS' );

// insert into head
p3_o( 'insert_into_head', 'textarea|5|105', 'Custom code inserted into blogs <code>&lt;head&gt;&lt;/head&gt;</code> tag' );

// post signature
p3_start_multiple( 'Post signature' );
p3_o( 'post_signature_placement', 'checkbox|post_signature_on_home|true|on blog home page|post_signature_on_single|true|on individual post pages|post_signature_on_page|true|on WordPress static "Pages"', 'where to show the post signature' );
p3_o( 'post_signature_filter_priority', 'text|5', 'filter priority (adjust number to affect interaction with like button & plugins)' );
p3_o( 'key', 'blank' );

p3_o( 'post_signature', 'textarea|7|105', 'Text or HTML added below each post. Special strings: <code>%post_title%</code>, <code>%permalink%</code>, <code>%post_id%</code>, and <code>%post_author_id%</code> will be replaced with per-post values.' );

p3_stop_multiple();

// custom javascript
p3_o( 'custom_js', 'textarea|18|105', 'Custom javascript', 'Custom javascript' );

p3_end_option_subgroup();



/* translation */
p3_option_subgroup( 'translation' );

p3_o( 'translate_password_protected', 'text|75', 'phrase used as intro to password-protected posts', 'Translation: password protected posts' );


// translate: comments header area
p3_start_multiple( 'Translation: comments header' );
p3_o( 'translate_by', 'text|22', 'text shown before post author name link - default is "by", i.e. "<strong>by</strong> <span style="text-decoration:underline;">Admin</span>"' );
p3_o( 'translate_no', 'text|22', 'text shown before "comments" when there are no comments, as in "no" comments' );
p3_o( 'translate_comments', 'text|22', 'text shown in comment header area when there is zero or more than one comments' );
p3_o( 'translate_comment', 'text|22', 'text shown in comment header area when there is only 1 comment' );
p3_stop_multiple();

// translate: comment form
p3_start_multiple( 'Translation: comment form' );
p3_o( 'translate_commentform_message', 'textarea|2|29', 'text shown at top of comment form' );
p3_o( 'translate_comments_required', 'text|32', 'text shown indicating how required fields are marked' );
p3_o( 'translate_comments_name', 'text|26', 'text shown above Name input area of comment form' );
p3_o( 'translate_comments_email', 'text|26', 'text shown above Email input area of comment form' );
p3_o( 'translate_comments_website', 'text|26', 'text shown above Website input area of comment form' );
p3_o( 'translate_comments_comment', 'text|26', 'text shown above Comment input area of comment form' );
p3_o( 'translate_comments_button', 'text|26', 'text shown on "post comment" submit button of comment form' );
p3_o( 'translate_comments_cancel_reply', 'text|26', 'text shown on "cancel reply" button on home page inline comment forms' );
p3_o( 'translate_comments_error_message', 'textarea|2|29', 'message shown to user when error submitting comment' );
p3_stop_multiple();

// translate: archive pages 
p3_start_multiple( 'Translation: archive pages' );
p3_o( 'translate_archives_monthly', 'text|33', 'text shown in header of monthly archive pages' );
p3_o( 'translate_archives_yearly', 'text|33', 'text shown in header of yearly archive pages' );
p3_o( 'translate_blog_archives', 'text|33', 'text shown on certain other archive pages' );
p3_o( 'translate_tag_archives', 'text|33', 'text shown as header on tag archive pages' );
p3_o( 'translate_category_archives', 'text|33', 'text shown as header on category archive pages' );
p3_o( 'translate_author_archives', 'text|33', 'text shown as header on author archive pages' );
p3_stop_multiple();

// translate: search page
p3_start_multiple( 'Translation: search page' );
p3_o( 'translate_search_results', 'text|33', 'text used in header of search page when results are found' );
p3_o( 'translate_search_notfound_header', 'text|33', 'text used in header of search page when results are not found' );
p3_o( 'translate_search_notfound_text', 'textarea|2|29', 'text used in body of search page when results are not found' );
p3_o( 'translate_search_notfound_button', 'text|33', 'text used in on search page in button of new search form when results are not found' );
p3_stop_multiple();

// translate: 404 page
p3_start_multiple( 'Translation: 404 page' );
p3_o( 'translate_404_header', 'text|22', 'header for "404 Not Found" template page -- shown when broken link in blog clicked or incorrect URL typed' );
p3_o( 'translate_404_text', 'textarea|2|29', '', 'text for "404 Not Found" template page -- shown when broken link in blog clicked or incorrect URL typed' );
p3_stop_multiple();

// lightbox text
p3_start_multiple( 'Translation: lightbox gallery' );
p3_o( 'translate_lightbox_image', 'text|20', 'word for "Image" as in "<strong>Image</strong> 3 of 15"' );
p3_o( 'translate_lightbox_of', 'text|20', 'word for "of" as in "Image 3 <strong>of</strong> 15"' );
p3_stop_multiple();

// subscribe by email form language
p3_o( 'subscribebyemail_lang', 'select|' . FEEDBURNER_LANG_OPTIONS, 'Language that appears in the feedburner subscribe-by-email window', 'Translation: Feedburner subscribe by email form' );

p3_end_option_subgroup();
?>