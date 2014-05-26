<?php
/* ----------------------- */
/* ---POST AREA OPTIONS--- */
/* ----------------------- */



/* -----option required javascript/css----- */

echo <<<HTML
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	p3_custom_dateformat_display();
	// show custom date format field when appropriate
	jQuery('#p3-input-dateformat').change(function(){
		p3_custom_dateformat_display();
	});
	jQuery('#postdate-individual-option').change(function(){
		p3_custom_dateformat_display();
	});
	// category links prelude
	jQuery('#p3-input-catprelude').blur(function(){
		var catprelude = jQuery(this).val();
		jQuery('.cat-links').html(catprelude);
	});
});
function p3_custom_dateformat_display() {
	var postdate = jQuery('#postdate-individual-option input:checked').val();
	var choice = jQuery('#p3-input-dateformat option:selected').val();
	if ( postdate == 'normal' && choice == 'custom') {
		jQuery('#dateformat_custom-individual-option').show();
	} else {
		jQuery('#dateformat_custom-individual-option').hide();
	}
}
</script>
HTML;




/* -----begin printing options------ */

$subgroups =  array(
	'background' => 'Background',
	'header'     => 'Post/Page Header',
	'content'    => 'Text &amp; Images',
	'footerz'    => 'Post/Page Footer',
	'meta'	     => 'Category &amp; Tag Lists',
	'excerpts'   => 'Excerpts',
	'feed'       => 'RSS Feed',
	'archive'    => 'Archive Pages',
);


p3_subgroup_tabs( $subgroups );
p3_option_header('Content Options', 'content' );



/* header subgroup */
p3_option_subgroup( 'background' );

// content area bg
p3_bg( 'body_bg', 'Content area background <span>color and background image of all your blog\'s content areas</span>', 'Background image will tile and fill <strong>all</strong> of your content areas', NO_IMG_OPTIONS );

// individual post bg
p3_bg( 'post_bg', 'Individual post area background', 'Background image will be applied separately to each <strong>post</strong>' );

// individual page bg
p3_bg( 'page_bg', 'Individual page area background', 'Background image will be applied separately to each <strong>page</strong>' );

p3_end_option_subgroup();



/* header subgroup */
p3_option_subgroup( 'header' );

// header alignment and margin below
p3_start_multiple( 'Post header alignment & spacing below' );
p3_o( 'post_header_align', 'radio|left|left aligned|center|centered|right|right aligned', 'overall aligment of post/page headers' );
p3_o( 'post_header_margin_above', 'slider|0|80| px', 'spacing above header' );
p3_o( 'post_header_margin_below', 'slider|0|80| px', 'spacing below header (between header and beginning of content)' );
p3_stop_multiple();

// post titles
p3_font_group( array(
	'key' => 'post_title_link',
	'title' => 'Post title appearance',
	'add' => array( 'margin_bottom', 'letterspacing' ),
	'inherit' => 'all',
) );

// post date/time options
p3_start_multiple( 'Post date/time' );

// controlling option
p3_o( 'postdate', 'radio|normal|normal|boxy|boxy style|off|do not show post date', 'post date display style', 'Post date/time' );
	
// boxy date options
p3_o( 'boxy_date_font_size', 'slider|10|25| px', 'relative font size' );
p3_o( 'boxy_date_bg_color', 'color|optional', 'background color' );
p3_o( 'boxy_date_align', 'radio|left|left|right|right', 'alignment' );
p3_o( 'boxy_date_month_color', 'color', 'month text color' );
p3_o( 'boxy_date_day_color', 'color', 'day text color' );
p3_o( 'boxy_date_year_color', 'color', 'year text color' );
p3_o( 'blank', 'blank' );
p3_o( 'blank', 'blank' );



// normal date options
p3_o( 'postdate_placement', 'radio|above|above post title|withtitle|same line with post title|below|below post title', 'Post date/time placement' );
p3_o( 'displaytime', 'radio|yes|yes|no|no', 'also display time of day posted?' );
p3_o( 'dateformat', 'select|l, F j, Y|Monday, February 23, 2009|D. F j, Y|Mon. February 23, 2009|F j, Y|February 23, 2009|m-d-Y|02-23-2009|m-d-y|02-23-09|m*d*Y|02*23*2009|m*d*y|02*23*09|m.d.Y|02.23.2009|m.d.y|02.23.09|custom|custom...', 'Choose your date display format for your posts' );
p3_o( 'dateformat_custom', 'text|14', 'enter custom <a href="http://codex.wordpress.org/Formatting_Date_and_Time">PHP-syntax date format</a> here' );
p3_stop_multiple();

// date/time
p3_font_group( array(
	'title' => 'Post header date/time font appearance',
	'key' => 'post_header_postdate',
	'inherit' => 'all',
	'add' => array( 'margin_bottom', 'letterspacing' ),
	'preview' => '<span>' . date( p3_get_option( 'dateformat' ) ) . '</span>',
) );

// post date advanced
p3_start_multiple( 'Post date advanced features' );
p3_o( 'postdate_advanced_switch', 'radio|on|use advanced post date features|off|do not use advanced features' );
p3_o( 'postdate_lr_padding', 'slider|0|30| px', 'background spacing on left & right side of post date' );
p3_o( 'postdate_tb_padding', 'slider|0|30| px', 'background spacing on top & bottom side of post date' );
p3_o( 'postdate_bg_color', 'color|optional', 'background color of post date area' );
p3_o( 'postdate_border_sides', 'checkbox|postdate_border_top|on|top border|postdate_border_bottom|on|bottom border|postdate_border_left|on|left border|postdate_border_right|on|right border', 'where to show post date border' );
p3_border_group( array( 'key' => 'postdate_border', 'comment' => 'postdate border appearance' ) );
p3_stop_multiple();

// post meta
p3_o( 'post_title_below_meta', 'checkbox|categories_in_post_header|yes|include category list|tags_in_post_header|yes|include tags list|comment_count_in_post_header|yes|include comment count', 'include which items in line of info below post title', 'Post info below post title' );


// post header meta 
p3_font_group( array(
	'title' => 'Post info items appearance',
	'key' => 'post_header_meta_link',
	'comment' => 'overall font and link appearance for post info: <strong>date, categories, tags, and comment count</strong>',
	'inherit' => 'all',
	'add' => array( 'nonlink_color' ),
) );


// post header line below
p3_start_multiple( 'Line below post header' );
p3_o( 'post_header_border', 'radio|off|no line|on|add a line|image|upload an image', 'use a line below the post header to separate it from the post body content' );
p3_border_group( array( 'key' => 'post_header_border', 'comment' => 'appearance of line below post header' ) );
p3_o( 'post_header_border_margin', 'text|3', 'spacing (in pixels) between line and beginning of post' );
p3_stop_multiple();

// post header sep image
p3_image( 'post_header_separator', 'Post header separator image', 'decorative image between your post/page headers and content' );

p3_end_option_subgroup();


/* content (text and images) subgroup */
p3_option_subgroup( 'content' );

// post text
p3_font_group( array(
	'key' => 'post_text',
	'title' => 'Post text appearance',
	'inherit' => 'all',
	'add' => array( 'lineheight', 'margin_bottom' ),
	'margin_bottom_comment' => 'paragraphs',
) );

// post text links
p3_font_group( array(
	'key' => 'post_text_link',
	'title' => 'Post text link appearance',
	'inherit' => 'all',
) );

// pictures
p3_start_multiple( 'Post picture appearance' );
p3_o( 'post_pic_margin_top', 'slider|0|50| px', 'margin (in pixels) above posted pictures' );
p3_o( 'post_pic_margin_bottom', 'slider|0|50| px', 'margin below posted pictures' );
p3_border_group( array( 'key' => 'post_pic_border', 'comment' => 'post picture border appearance', 'minwidth' => '0' ) );
p3_stop_multiple();

// post picture dropshadow
p3_start_multiple( 'Post picture dropshadow<span>(SUPPORTED BROWSERS ONLY)</span>' );
p3_o( 'post_pic_shadow_enable', 'radio|true|enable picture dropshadows|false|disable picture dropshadows', 'dropshadows for post images - only works in browsers that support dropshadows <em>(not Internet Explorer)</em>' );
p3_o( 'post_pic_shadow_color', 'color', 'dropshadow color' );
p3_o( 'post_pic_shadow_blur', 'slider|0|20| px', 'dropshadow blur radius' );
p3_o( 'post_pic_shadow_vertical_offset', 'slider|-20|20| px', 'dropshadow vertical offset' );
p3_o( 'post_pic_shadow_horizontal_offset', 'slider|-20|20| px', 'dropshadow horizontal offset' );
p3_stop_multiple();

// image protection
p3_start_multiple( 'Post image theft protection' );
p3_o( 'image_protection', 'radio|none|none|right_click|disable right-click|clicks|disable left and right clicks|replace|disable clicks and dragging|watermark|no clicks/drag, add watermark', 'image protection' );
p3_o( 'watermark_position', 'select|top left|top left|top center|top center|top right|top right|center left|middle left|center center|middle center|center right|middle right|bottom left|bottom left|bottom center|bottom center|bottom right|bottom right', 'position of watermark overlay' );
p3_o( 'watermark_alpha', 'slider', 'opacity of watermark image' );
p3_o( 'watermark_startdate', 'text|10', 'do not watermark images from posts before date (format: YYYY-MM-DD)' );

p3_stop_multiple();

// watermark img
p3_image('watermark', 'Post image watermark overlay' );

// 'P3 Insert All' alignment
p3_o( 'insert_all_align', 'radio|none|no alignment|left|align left|center|align center|right|align right', 'Alignment of images inserted using the "P3 Insert All" button in the WordPress media uploader.', '"P3 Insert All" image alignment' );

p3_end_option_subgroup();



/* excerpts subgroup */
p3_option_subgroup( 'excerpts');

// exerpts, where?
p3_start_multiple( 'Excerpts' );
p3_o( 'excerpts', 'checkbox|excerpts_on_index|true|Home pages|excerpts_on_archive|true|Date archive pages|excerpts_on_category|true|Category archive pages|excerpts_on_tag|true|Tag archive pages|excerpts_on_author|true|Author archive pages|excerpts_on_search|true|Search results pages', 'show <em>excerpts instead of full post content</em> on certain types of pages' );
p3_o( 'moretext', 'text|15', 'Text used as link to full post where excerpts chosen' );
p3_stop_multiple();

// excerpt images
p3_start_multiple( 'Excerpt images' );
p3_o( 'show_excerpt_image', 'radio|true|show image|false|do not show image', 'show one image in each excerpt' );
p3_o( 'dig_for_excerpt_image', 'radio|true|always try to include an excerpt image|false|only show designated post thumbnail' );
p3_o( 'excerpt_image_size', 'radio|thumbnail|small thumbnail|medium|medium thumbnail|full|fullsize', 'size of image shown in excerpt' );
p3_o( 'excerpt_image_position', 'radio|before_text|before text|after_text|after text', 'placement of excerpt image' );
p3_stop_multiple();

p3_end_option_subgroup();



/* rss feed subgroup */
p3_option_subgroup( 'feed');
echo <<<HTML
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	jQuery('#p3-input-feedburner').blur(function(){
		if (/feedburner/.test(jQuery('#p3-input-feedburner').val()) == false && jQuery('#p3-input-feedburner').val() != '' ) {
			if ( !jQuery('#feedburner-error').length ) {
				jQuery('#p3-input-feedburner').css('border', 'red 1px solid').after('<p id="feedburner-error" style="color:red">This must be a feedburner address, not your blog\'s bult-in feed address. See the below linked tutorial for more info.</p>');
			}
			jQuery('#p3-save-changes').attr('disabled', 'disabled');
		} else {
			jQuery('#p3-input-feedburner').css('border', '#DFDFDF solid 1px');
			jQuery('#feedburner-error').remove();
			jQuery('#p3-save-changes').removeAttr('disabled');
		}
	});
});
</script>
HTML;

// feedburner
p3_o('feedburner', 'text|69', 'Enter your <a href="http://www.feedburner.com">Feedburner</a> URL here if you have burned your blog\'s feed to feedburner and want to use that instead of the built-in feed address.', 'Feedburner URL' );

// feed image protection
p3_start_multiple( 'Feed image protection' );
p3_o( 'modify_feed_images', 'radio|false|leave images untouched|remove|remove images completely|modify|downsize images', 'what to do with images that come through in your RSS feed' );
p3_o( 'modify_feed_images_alert', 'textarea|4|68', 'message displayed in feed only when images are modified - words inside carets (^) will be linked to the post permalink page' );
p3_o( 'blank', 'blank' );

p3_o( 'feed_only_thumbs', 'radio|medium|show medium thumbnails|thumbnail|show small thumbnails', 'show fullsize images or thumbnails - if ProPhoto can\'t find a thumbnail for an image, the image will not be shown' );

p3_stop_multiple();
p3_end_option_subgroup();



/* footer subgroup */
p3_option_subgroup( 'footerz' );

// facebook "like" button
p3_start_multiple( 'Facebook "Like Button" integration' );
p3_o( 'like_btn_enable', 'radio|true|enable like button|false|disable like button' );
p3_o( 'like_btn_placement', 'checkbox|like_btn_on_home|true|on blog home page|like_btn_on_single|true|on individual post pages|like_btn_on_page|true|on WordPress static "Pages"', 'where to show the like button' );
p3_o( 'like_btn_layout', 'radio|standard|standard, with explanation|button_count|small, show only "like count"', 'what is shown to the right of the actual like button' );
p3_o( 'like_btn_show_faces', 'radio|true|show profile pics|false|do not show profile pics', 'display mini-profile pics below button (will leave empty space below if no pics)' );
p3_o( 'like_btn_verb', 'radio|like|like|recommend|recommend', 'word used in like button' );
p3_o( 'like_btn_color_scheme', 'radio|light|light|dark|dark', 'color scheme' );
p3_o( 'like_btn_margin_top', 'slider|0|60|px', 'spacing above like button' );
p3_o( 'like_btn_margin_btm', 'slider|0|60|px', 'spacing below like button (if mini-profile pics set to be shown there will be extra empty space below if no pics displayed)' );
p3_o( 'like_btn_filter_priority', 'text|5', 'filter priority (adjust number to affect interaction with post signature & plugins)' );
p3_stop_multiple();

// post footer meta
p3_o( 'post_footer_meta', 'checkbox|categories_in_post_footer|yes|include category list|tags_in_post_footer|yes|include tags list', 'include which items in line of info below post', 'Post info below post content' );

// post footer meta font/links
p3_font_group( array(
	'title' => 'Post info below post content font/link appearance',
	'key' => 'post_footer_meta_link',
	'inherit' => 'all',
	'add' => array( 'nonlink_color' ),
) );

// nav previous/next
p3_start_multiple( 'Older/Newer posts navigation links' );
p3_o( 'nav_previous', 'text|18', 'text for "Older posts" links on paginated pages' );
p3_o( 'nav_next', 'text|18', 'text for "Newer posts" links on paginated pages' );
p3_o( 'blank', 'blank' );

p3_o( 'nav_previous_align', 'radio|left|left-aligned|right|right-aligned', 'alignment of "Older posts" link' );
p3_o( 'nav_next_align', 'radio|left|left-aligned|right|right-aligned', 'alignment of "Newer posts" links' );
p3_stop_multiple();

// nav below links
p3_font_group( array(
	'title' => 'Font and link options for "Newer/Older posts" links text',
	'key' => 'nav_below_link',
	'inherit' => 'all',
	'preview' => '<a href="" style="float:left;">' . p3_get_option( 'nav_previous' ) . '</a><a href="" style="float:right;">' . p3_get_option( 'nav_next' ) . '</a>'
) );

//post divider
p3_start_multiple( 'Post separator' );
p3_o( 'post_divider', 'radio|line|use a line to separate posts|none|no line or image separating posts|image|uploaded image to separate posts', 'which type of separator?' );
p3_o( 'padding_below_post', 'slider|0|120| px', 'spacing below each post' );
p3_border_group( array( 'key' => 'post_sep_border', 'comment' => 'line between posts appearance' ) );
p3_stop_multiple();

// post sep image
p3_image_with_option( array( 'post_sep', 'Post separator image', 'Upload a custom image to separate your posts' ), array( 'post_sep_align', 'radio|left|left|center|center|right|right', 'separator image alignment' ) );

p3_end_option_subgroup();


/* meta subgroup */
p3_option_subgroup( 'meta' );


// category links list display options
p3_start_multiple( 'Category links list display options' );	
p3_o( 'catdivider', 'text|5', 'When multiple categories, what should divide the category links? Default is ", "' );
p3_o( 'catprelude', 'text', 'text to be included before category list' );
p3_stop_multiple();


// tags
p3_start_multiple( 'Tag options' );
p3_o( 'tagged', 'text|20', 'text shown before list of tags' );
p3_o( 'tag_sep', 'text|4', 'text used to separate multiple tags' );
p3_o( 'tags_where_shown', 'checkbox|tags_on_index|yes|home page|tags_on_single|yes|individual post pages|tags_on_archive|yes|archive, category, author, and search|tags_on_tags|yes|tag archives', 'display tags below posts on which types of pages?' );
p3_stop_multiple();

p3_end_option_subgroup();



/* content other_pages subgroup */
p3_option_subgroup( 'archive' );

// header styling
p3_font_group( array(
	'title' => 'Archive pages headline styling',
	'key' => 'archive_h2',
	'add' => array( 'margin_bottom' ),
	'comment' => 'examples of archive page headlines include on category archive pages where it says <em>Category Archives: Weddings</em>, or on monthly archive pages where it says <em>Monthly Archives: October 2009</em>',
) );

// archive post divider
p3_start_multiple( 'Archive pages post separator' );
p3_o( 'archive_post_divider', 'radio|same|same as on home pages|line|use a custom line', 'use the same visual separator between posts on home page, or override with a custom line', 'first' );
p3_o( 'archive_padding_below_post', 'slider|0|120| px', 'First extra space (in pixels) below post.  This is the space between the bottom of the post and the line' );
p3_border_group( array( 'key' => 'archive_post_sep_border', 'comment' => 'line between archive posts appearance' ) );
p3_stop_multiple();

p3_end_option_subgroup();

?>