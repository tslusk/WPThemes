<?php

echo <<<HTML
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	p3_image_dependent('blog_bg', 'blog_bg_img_repeat' );
});	
</script>
HTML;

p3_option_header('Background Options', 'background' );

/* bg colors & appearance subgroup */

// blog bg
p3_bg( 'blog_bg', 'Blog outer background <span>color and background image behind your entire blog</span>' );

// top & bottom margins
p3_start_multiple( 'Blog top &amp; bottom margins' );
p3_o( 'blog_top_margin', 'slider|0|120| px|2', 'margin for top of blog (this is the distance between the top of the main area of the blog and the browser viewing area)' );
p3_o( 'blog_btm_margin', 'slider|0|120| px|2', 'margin for bottom of blog (this is the distance between the bottom of the main area of the blog and the bottom of the browser viewing area)' );
p3_stop_multiple();

// blog width & content margin
p3_start_multiple( 'Blog width and content margin' );
p3_o( 'blog_width', 'slider|600|1600| pixels', 'Overall width of blog, not counting borders/dropshadow. NOTE: Use arrow keys for precise control.' );
p3_o( 'content_margin', 'slider|0|150| pixels', 'Content margin: spacing between blog border and the edge of the content area, where images and text go' );
p3_stop_multiple();

// blog border
p3_start_multiple( 'Blog border style' );
p3_o( 'blog_border', 'radio|border|use the default solid border|dropshadow|use a subtle dropshadow|none|no border or dropshadow', 'choose the appearance of the sides and top of your blog' );
p3_o( 'blog_border_topbottom', 'radio|no|left and right only|yes|all four sides ', 'show blog border/dropshadow just on left and right, or all four sides' );
p3_o( 'blog_border_shadow_width', 'radio|narrow|narrow|wide|wide', 'set the width of the dropshadow effect' );
p3_border_group( array( 'key' => 'blog_border', 'comment' => 'blog border appearance' ) );
p3_stop_multiple();

// prophoto classic bar
p3_start_multiple( 'ProPhoto classic colored bar' );
p3_o( 'prophoto_classic_bar', 'radio|on|add colored bar|off|do not add colored bar', 'add a solid colored bar on the top of the blog like the original ProPhoto theme design' );
p3_o( 'prophoto_classic_bar_height', 'text|3', 'height (in pixels) of ProPhoto Classic colored bar' );
p3_o( 'prophoto_classic_bar_color', 'color', 'color of ProPhoto Classic colored bar' );
p3_stop_multiple();

// splitters
p3_start_multiple( 'Blog section separation <span>splitting these areas causes outer blog background color &amp; image to be seen between sections</span>' );
$sidebar_note = ( p3_test( 'sidebar', 'false' ) ) ? '' : ' <em>(where no fixed sidebar shown)</em>'; 
p3_o( 'splitter_note', 'note', 'IMPORTANT: if you are separating any of your blog sections using the below options, you should set the "Blog border style" above to "none" as any blog border or dropshadow will not wrap around the separated sections of your blog.' );
p3_o( 'menu_top_splitter', 'slider|0|120| px', 'separation above nav menu' );
p3_o( 'menu_btm_splitter', 'slider|0|120| px', 'separation below nav menu' );
p3_o( 'blank', 'blank' );
p3_o( 'post_splitter', 'slider|0|120| px', 'separation between posts' . $sidebar_note );
p3_o( 'archive_post_splitter', 'slider|0|120| px', 'separation between posts on archive-type pages' . $sidebar_note );
p3_o( 'blank', 'blank' );
p3_o( 'masthead_top_splitter', 'slider|0|120| px', 'separation above masthead section' );
p3_o( 'masthead_btm_splitter', 'slider|0|120| px', 'separation below masthead section' );
p3_o( 'blank', 'blank' );
p3_o( 'bio_top_splitter', 'slider|0|120| px', 'separation above bio section' );
p3_o( 'bio_btm_splitter', 'slider|0|120| px', 'separation below bio section' );
p3_o( 'blank', 'blank' );
p3_o( 'logo_top_splitter', 'slider|0|120| px', 'separation above logo section' );
p3_o( 'logo_btm_splitter', 'slider|0|120| px', 'separation below logo section' );
p3_stop_multiple();

?>