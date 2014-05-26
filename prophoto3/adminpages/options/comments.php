<?php
/* ----------------------- */
/* ----COMMENTS OPTIONS--- */
/* ----------------------- */


echo <<<HTML
<style type="text/css" media="screen">
#tab-section-comments-link .option, 
#comments_enable-option-section .individual-option, 
#subgroup-nav {
	display:none;
}
#comments_enable-option-section {
	display:block;
}
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	function p3_comments_display_ux( choice ) {
		if ( choice == 'true' ) {
			jQuery('#tab-section-comments-link .option, #comments_enable-option-section .individual-option, #subgroup-nav').show();
		} else {
			jQuery('#tab-section-comments-link .option, #comments_enable-option-section .individual-option, #subgroup-nav').hide();
		}
		jQuery('#comments_enable-option-section, #comments_enable-individual-option').show();
	}
	var comments_display = jQuery('#comments_enable-individual-option input:checked').val();
	p3_comments_display_ux( comments_display );
	jQuery('#comments_enable-individual-option input').click(function(){
		p3_comments_display_ux( jQuery(this).val() );
	});
});
</script>
HTML;

// CSS and JS for complicated "post interact" links interface
p3_post_interaction_interface();


// tabs and header
p3_subgroup_tabs( array( 
	'general' => 'General', 
	'header' => 'Comments header', 
	'body' => 'Comment options' )
);
p3_option_header('Comments', 'comments' );


/* options subgroup */
p3_option_subgroup( 'general' );

// comments layout & display
p3_start_multiple( 'Comments layout & display' );
p3_o( 'comments_enable', 'radio|true|enable comments|false|disable &amp; hide all comments', 'disable if you don\'t want any comments anywhere on your blog' );
p3_o( 'comments_layout', 'radio|tabbed|Tabbed layout|boxy|Boxy layout|minima|ProPhoto3', 'overall comment layout appearance' );
p3_o( 'comments_open_closed', 'radio|closed|Comments are hidden by default|open|Comments are open by default', 'comments area open or hidden by default' );
p3_stop_multiple();

// comments on template pages
p3_start_multiple( 'Archive pages comments' );
p3_o( 'archive_comments', 'radio|on|include comments|off|no comments (recommended)', 'include comments on archive-type pages' );
p3_o( 'archive_comments_showhide', 'radio|show|comments visible by default|hide|comments not visible by default', 'comments on other pages visible or hidden by default' );
p3_stop_multiple();

// scrollbox
p3_start_multiple( 'Comments area scrollbox height' );
p3_o( 'comments_body_scroll_height', 'slider|50|600| px|5', 'height of comment area scrollbox' );
p3_o( 'comments_body_scroll_index', 'radio|fixed|comments in fixed-height scrollbox|all|no scrollbox - show all comments', 'comment display on home pages' );
p3_o( 'comments_body_scroll_singular', 'radio|fixed|comments in fixed-height scrollbox|all|no scrollbox - show all comments', 'comment display on single post pages' );
p3_stop_multiple();


// comments overall left/right margin
p3_start_multiple( 'Overall comment area spacing' );
p3_o( 'comments_lr_margin_switch', 'radio|inherit|inherit from post content|set|set pixel margin', 'spacing between comments area and the left and right edges of blog' );
p3_o( 'comments_lr_margin', 'slider|0|100| px', 'set specific spacing amount' );
p3_stop_multiple();

// bg colors
p3_start_multiple( 'Comments area background colors' );
p3_o( 'comments_area_bg_color', 'color|optional', 'background color of the entire comments area - (header and comments)' );
p3_o( 'comments_comment_bg_color', 'color|optional', 'background color of individual comments', 'Comment background' );
p3_stop_multiple();

// boxy comments line color
p3_start_multiple( 'Comments area borders' );
p3_border_group( array( 'key' => 'comments_area_border', 'comment' => 'appearance of overall comment area borders', 'minwidth' => '0' ) );
p3_o( 'comments_header_border_lines', 'checkbox|comment_header_border_top|on|show border above comments header|comment_header_border_bottom|on|show border below comments header', 'where to show borders' );
p3_stop_multiple();

// gravatars
p3_start_multiple( 'Comment gravatars' );
p3_o( 'gravatars', 'radio|on|enabled|off|disabled' );
p3_o( 'gravatar_size', 'text|4', 'display size (in pixels) of gravatars' );
p3_o( 'gravatar_align', 'radio|left|left|right|right', 'alignment of gravatar in comment' );
p3_o( 'gravatar_padding', 'text|3', 'spacing (in pixels) on the sides and below gravatar' );
p3_stop_multiple();

// reverse comments (SHOW - ALL)
p3_o('reverse_comments', 'radio|false|oldest comments on top|true|newest comments on top', 'sort your comments, most recent comment on the bottom of the list, or on top', 'Sort comments' );

// disable ajax comments
p3_o( 'comments_ajax_add', 'radio|true|enable ajax|false|disable ajax', 'set to disable for compatibility with poorly coded plugins', 'Ajax comment submission' );

p3_end_option_subgroup();



/* comments header subgroup */
p3_option_subgroup( 'header' );

// comments header bg area
p3_bg( 'comments_header_bg', 'Comments header area background' );


// comment header gen options
p3_start_multiple( 'Comment header options' );
p3_o( 'comments_show_postauthor', 'radio|true|show post author|false|do not show post author', 'include or remove the post author from the comments header' );
p3_o( 'comments_header_lr_padding', 'slider|0|100| px', 'spacing between text/links in comments header and edges of blog' );
p3_o( 'comments_header_tb_padding', 'text|3', 'override spacing above/below comments header (in pixels)' );

p3_stop_multiple();

// post author, comments count, and show/hide link options (SHOW - ???)
p3_font_group( array(
	'title' => 'Post author and show/hide comments appearance',
	'key' => 'comments_header_link',
	'inherit' => 'all',
	'add' => array( 'nonlink_color' ),
	'not' => array( 'visited_color' ),
) );

// force remove outdated option
echo '<input type="hidden" value="" name="p3_input_comments_header_link_visited_font_color_bind" id="p3-input-comments_header_link_visited_font_color-bind" />';

// post interaction font
p3_font_group( array(
	'title' => 'Post interaction link appearance',
	'key' => 'comments_post_interaction_link',
	'inherit' => 'all',
) );

// minima show/hide text
p3_start_multiple( 'Show/hide comments link display' );
p3_o( 'comments_show_hide_method', 'radio|button|use button|text|use text', 'Select a button or text interface for showing/hiding your comments' );
p3_o( 'comments_minima_show_text', 'text|15', '"show" text' );
p3_o( 'comments_minima_hide_text', 'text|15', '"hide" text' );
p3_stop_multiple();

// post interaction display
p3_start_multiple( 'Post interaction links options' );
p3_o( 'comments_post_interact_display', 'radio|text|text &amp; optional icons|button|text &amp; optional icons in P3 buttons|images|use custom images', 'Select the display type for your post interaction links' );
p3_o( 'comments_post_interact_spacing', 'slider|0|50| px', 'spacing between post interaction links' );
p3_stop_multiple();

// add a comment link
p3_start_multiple( 'Add a comment link' );
p3_o( 'comments_addacomment_text', 'text|29', 'text used for the "add a comment" link' );
p3_o( 'comments_addacomment_icon', 'image', 'optional "Add a comment" icon' );
p3_o( 'comments_addacomment_image', 'image', 'custom "Add a comment" image' );
p3_stop_multiple();

// link to this post
p3_start_multiple( '"Link to this Post" link' );
p3_o( 'comments_linktothispost', 'radio|yes|include|no|do not include', 'include or remove "link to this post" permalink option' );
p3_o( 'comments_linktothispost_text', 'text|29', 'text used for the "link to this post" permalink link' );
p3_o( 'comments_linktothispost_icon', 'image', 'optional "Link to this post" icon' );
p3_o( 'comments_linktothispost_image', 'image', 'Custom "Link to this post" image' );
p3_stop_multiple();

// email a friend link
p3_start_multiple( '"Email a friend" link' );
p3_o( 'comments_emailafriend', 'radio|yes|include|no|do not include', 'include or remove "email a friend" permalink option' );
p3_o( 'comments_emailafriend_text', 'text|29', 'text used for the "email a friend" links' );
p3_o( 'comments_emailafriend_body', 'text|19', 'Email a Friend body text', 'Text used in the body of the email sent when someone clicks on the Email a Friend link' );
p3_o( 'comments_emailafriend_subject', 'text|29', 'Text used in subject line of email sent when someone clicks on the "Email a Friend" link' );
p3_o( 'comments_emailafriend_icon', 'image', 'custom "Email a friend" icon' );
p3_o( 'comments_emailafriend_image', 'image','Custom "Email a friend" image' );
p3_stop_multiple();

p3_end_option_subgroup();



/* options subgroup */
p3_option_subgroup( 'body' );

// comments area background
p3_bg( 'comments_comment_outer_bg', 'Comments area (not comment header) background' );

// comment link author display
p3_start_multiple( 'Comment author link options' );
p3_o( 'comment_meta_display', 'radio|inline|comment author inline with comment|above|comment author on it\'s own line', 'how to display the comment author' );
p3_o('comment_author_link_blank', 'radio||open in same window|blank|open in new window', 'links to comment author websites open in same window or in a new window' );
// comment author
p3_font_group( array(
	'title' => 'Comment author appearance',
	'key' => 'comments_comment_link',
	'inherit' => 'all',
	'not' => array( 'size', 'family', 'hover_color', 'visited_color' ),
	'comment' => 'comment author link appearance',
) );
p3_o( 'comment_meta_margin_bottom', 'slider|0|20| px', 'spacing below comment author line' );

p3_stop_multiple();

// comment time
p3_start_multiple( 'Comment time display' );
p3_o( 'comment_timestamp_display', 'radio|right|right-aligned|left|left-aligned|off|do not show comment time', 'if/where to show the time of the comment' );
p3_font_group( array(
	'title' => 'Comment date/time appearance',
	'key' => 'comments_comment_timestamp',
	'not' => array( 'size', 'family', 'hover_color', 'visited_color' ),
	'comment' => 'text appearance of comment time',
	'preview' => date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ),
) );
p3_stop_multiple();

// comment font
p3_font_group( array(
	'title' => 'Comment font appearance',
	'key' => 'comments_comment',
	'inherit' => 'all',
	'add' => array( 'lineheight' ),
) );

// flex comment side margins (SHOW - MINIMA, )
p3_start_multiple( 'Comment body margins' );
p3_o( 'comment_vertical_padding', 'slider|0|40| pixels', 'vertical padding above and below comment text' );
p3_o( 'comment_horizontal_padding', 'slider|0|40| pixels', 'horizontal padding left and right of comment text' );
p3_o( 'comments_body_lr_margin', 'slider|0|150| pixels', 'space between comments and left/right edge of comment area' );
p3_o( 'comments_body_tb_margin', 'slider|0|150| pixels', 'space between comments and top/bottom edge of comment area' );
p3_o( 'comment_vertical_separation', 'slider|0|40| pixels', 'vertical spacing between comments' );
p3_stop_multiple();

// comment separator line (SHOW - ALL)
p3_start_multiple( 'Optional line separating individual comments' );
p3_o( 'comments_comment_border_onoff', 'radio|on|add a line|off|no line', 'add and customize a line to separate each individual comment' );
p3_border_group( array( 'key' => 'comments_comment_border', 'comment' => 'appearance of separating line' ) );
p3_stop_multiple();


// alt comment styling (SHOW - ALL)
p3_start_multiple( 'Alternate comment styling' );
p3_o( 'comments_comment_alt_bg', 'color|optional', 'override the inherited background color of every other comment' );
p3_o( 'comments_comment_alt_text_color', 'color|optional', 'override the inherited comment text color on every other comment' );
p3_o( 'comments_comment_alt_link_color', 'color|optional', 'override the inherited link color (for commenters names who leave a website address) on every other comment' );
p3_o( 'comments_timestamp_alt_color', 'color|optional', 'override the inherited text color of the comment timestamp on every other comment' );
p3_stop_multiple();

// by author styling (SHOW - ALL)
p3_start_multiple( 'Alternate styling for comments by post author' );
p3_o( 'comments_comment_byauthor_bg', 'color|optional', 'override the background color for your own comments' );
p3_o( 'comments_comment_byauthor_text_color', 'color|optional', 'override the comment text color for your own comments' );
p3_o( 'comments_comment_byauthor_link_color', 'color|optional', 'override link color on your own comments' );
p3_o( 'comments_timestamp_byauthor_color', 'color|optional', 'override the text color of the comment timestamp of your own comments' );
p3_stop_multiple();

// awaiting moderation (SHOW - ALL)
p3_start_multiple( 'Comment awaiting moderation text/style' );
p3_o( 'comments_moderation_text', 'text|35', 'Text shown to comment submitter when comment moderation is turned on' );
p3_o( 'comments_moderation_style', FONT_STYLE_SELECT, 'style of message text' );
p3_o( 'comments_moderation_color', 'color|optional', 'override default color of message text' );
p3_o( 'comments_moderation_alt_color', 'color|optional', 'override default color of message text on every other comment' );
p3_stop_multiple();


p3_end_option_subgroup();



?>