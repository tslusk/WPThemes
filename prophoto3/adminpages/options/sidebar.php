<?php
/* --------------------- */
/* -- sidebar options -- */
/* --------------------- */

// tabas and header
p3_subgroup_tabs( array( 'fixed' => 'Fixed sidebar', 'drawer' => 'Sliding drawer sidebars', ) );
p3_option_header( 'Sidebar Options', 'sidebar' );


/* fixed sidebar */
p3_option_subgroup( 'fixed' );

// general
p3_start_multiple( 'Fixed sidebar' );
p3_o( 'sidebar', 'radio|false|no fixed sidebar|left|fixed sidebar on left|right|fixed sidebar on right' );
if ( P3_HAS_STATIC_HOME ) {
	$homepage = 'posts';
	$frontpage = '|sidebar_on_front_page|yes|static front page';
} else {
	$homepage = 'home';
}
p3_o( 'sidebar_on_which_pages', "checkbox{$frontpage}|sidebar_on_index|yes|$homepage page|sidebar_on_single|yes|individual post pages|sidebar_on_page|yes|static WordPress \"Pages\"|sidebar_on_archive|yes|archive, category, author, and search", 'display sidebar on which types of pages' );
p3_stop_multiple();

// sidebar width and padding, bg color
p3_start_multiple( 'Fixed sidebar appearance' );
p3_o( 'sidebar_width', 'slider|0|300| px|5', 'width of sidebar content area' );
p3_o( 'sidebar_padding', 'slider|0|60| px', 'space between sidebar and edge of blog' );
p3_o( 'sidebar_inner_padding_override', 'text|3', 'override padding on inner side of sidebar (in pixels)' );
p3_o( 'sidebar_widget_margin_bottom', 'slider|0|100| px', 'spacing below widgets', 'Widget bottom spacing' );
p3_stop_multiple();

// bg image
p3_bg( 'sidebar_bg', 'Fixed sidebar background' );

// widget headlines
p3_font_group( array(
	'key' => 'sidebar_headlines',
	'title' => 'Fixed sidebar headlines',
	'inherit' => 'all',
	'add' => array( 'margin_bottom' ),
	'is_link' => TRUE,
) );


// widget text
p3_font_group( array(
	'key' => 'sidebar_text',
	'title' => 'Fixed sidebar text',
	'inherit' => 'all',
	'add' => array( 'margin_bottom' ),
	'margin_bottom_comment' => 'paragraphs',
) );

// widget links
p3_font_group( array(
	'key' => 'sidebar_link',
	'title' => 'Fixed sidebar links',
	'inherit' => 'all',
) );

// border
p3_start_multiple( 'Fixed sidebar border' );
p3_o( 'sidebar_border_switch', 'radio|on|line|off|no line', 'show/hide line separating sidebar from content' );
p3_border_group( array( 'key' => 'sidebar_border', 'comment' => 'fixed sidebar line appearance' ) );
p3_stop_multiple();

// sep image
p3_image( 'sidebar_widget_sep_img', 'Fixed sidebar widget separator image' );

p3_end_option_subgroup();



/* sliding sidebar */
p3_option_subgroup( 'drawer' );

// intro note
p3_o( 'sliding_drawer_sidebar_note', 'note', 'To turn a sliding drawer sidebar on or off, just go to the widgets page <a href="' . admin_url('widgets.php') . '">here</a> and add a widget to any of the sidebar drawers.' );

// default drawer stuff
p3_start_multiple( 'Sliding drawer defaults' );
p3_o( 'drawer_default_bg_color', 'color', 'default background color' );
p3_o( 'drawer_default_opacity', 'slider', 'drawer opacity' );
p3_o( 'drawer_tab_rounded_corners', 'radio|on|round corners|off|do not round corners', 'round tab corners for good browsers' );
p3_o( 'drawer_padding', 'slider|0|50| pixels', 'drawer left/right margins' );
p3_o( 'drawer_widget_btm_margin', 'slider|0|80| pixels', 'drawer widget bottom margins' );
p3_stop_multiple();

// drawer sidebar widget headlines
p3_font_group( array(
	'key' => 'drawer_widget_headlines',
	'title' => 'Drawer widget headline text',
	'inherit' => 'all',
) );

// drawer sidebar widget text
p3_font_group( array(
	'key' => 'drawer_widget_text',
	'title' => 'Drawer widget text',
	'inherit' => 'all',
) );

// drawer tab text
p3_font_group( array(
	'key' => 'drawer_tab',
	'title' => 'Drawer tab text appearance',
	'not' => array( 'weight', 'style' ),
) );

// drawer widget links
p3_font_group( array(
	'title' => 'Drawer widget links',
	'key' => 'drawer_widget_link',
	'inherit' => 'all',
	'not' => array( 'size', 'family', 'weight', 'style', 'transform', 'hover_color', 'visited_color' ),
) );

// individual drawer settings
for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) { 
	p3_start_multiple( 'Drawer #' . $i . ' settings' );
	p3_o( 'drawer_tab_text_' . $i, 'text|30', 'tab title for tab #' . $i );
	p3_o( 'drawer_tab_font_color_' . $i, 'color|optional', 'tab text font color' );
	p3_o( 'blank', 'blank' );
	p3_o( 'drawer_content_width_' . $i, 'slider|50|500| pixels', 'default width for drawer #' . $i );
	p3_o( 'drawer_bg_color_' . $i, 'color|optional', 'background color for drawer #' . $i );
	p3_o( 'blank', 'blank' );
	p3_o( 'drawer_widget_text_font_color_' . $i, 'color|optional', 'widget text color' );
	p3_o( 'drawer_widget_headlines_font_color_' . $i, 'color|optional', 'widget headlines color' );
	p3_o( 'drawer_widget_link_font_color_' . $i, 'color|optional', 'widget link color' );
	p3_stop_multiple();	
}

p3_end_option_subgroup();

?>