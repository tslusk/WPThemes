<?php
/* ----------------------------------------- */
/* -- library of functions related to CSS -- */
/* ----------------------------------------- */

/* output font-related CSS for a group of user-defined options */
function p3_font_css( $key, $selector = '', $optional_colors = FALSE ) {
	
	// are we extracting a font color from a link-based css group?
	if ( strpos( $key, '_link' ) !== FALSE ) {
		$color_key = str_replace( '_link', '', $key );
	} else {
		$color_key = $key;
	}
		
	$size 	= p3_get_option( $key . '_font_size' );
	$color 	= p3_get_option( $color_key . '_font_color' );
	$style 	= p3_get_option( $key . '_font_style' );
	$family = p3_get_option( $key . '_font_family' );
	$effect = p3_get_option( $key . '_text_transform' );
	$line_h = p3_get_option( $key . '_line_height' );
	$margin = p3_get_option( $key . '_margin_bottom' );
	$weight = p3_get_option( $key . '_font_weight' );
	$letterspacing = p3_get_option( $key . '_letterspacing' );
	
	
	// don't print color if it's an optional color that unchecked/bound
	if ( $optional_colors && !p3_optional_color_bound( $color_key . '_font_color' ) ) $color = FALSE;
	
	if ( $size . $color . $style . $family . $effect . $line_h . $weight . $margin != '' ) {	
		if ( $selector ) 	 $output .= $selector . " {\n";
		if ( $family ) 		 $output .= "\tfont-family:"    . $family . ";\n";
		if ( $color ) 		 $output .= "\tcolor:"          . $color  . ";\n";
		if ( $style ) 		 $output .= "\tfont-style:"     . $style  . ";\n";
		if ( $size ) 		 $output .= "\tfont-size:"      . $size   . "px;\n";
		if ( $effect )		 $output .= "\ttext-transform:" . $effect . ";\n";
		if ( $margin != '' ) $output .= "\tmargin-bottom:"  . $margin . "px;\n";
		if ( $line_h ) 		 $output .= "\tline-height:"    . $line_h . "em;\n";
		if ( $weight )		 $output .= "\tfont-weight:"    . $weight . ";\n";
		if ( $letterspacing )$output .= "\tletter-spacing:"	. $letterspacing . "\n";
		if ( $selector ) 	 $output .= "}\n";	
	}
	return $output;
}


/* output link-related CSS for a group of user-defined options */
function p3_link_css( $key, $selector, $do_nonlink = FALSE, $optional_colors = FALSE ) {
	$nonlink_key    = str_replace( '_link', '', $key );
	$size 			= p3_get_option( $key . '_font_size' );
	$color 			= p3_get_option( $key . '_font_color' );
	$color_visited 	= p3_get_option( $key . '_visited_font_color' );
	$color_hover	= p3_get_option( $key . '_hover_font_color' );
	$family 		= p3_get_option( $key . '_font_family' );
	$style 			= p3_get_option( $key . '_font_style' ); 
	$weight			= p3_get_option( $key . '_font_weight' ); 
	$decoration		= p3_get_option( $key . '_decoration' );
	$decoration_h	= p3_get_option( $key . '_hover_decoration' );
	$effect 		= p3_get_option( $key . '_text_transform' );
	$margin_bottom  = p3_get_option( $key . '_margin_bottom' );
	$letterspacing  = p3_get_option( $key . '_letterspacing' );
	$nonlink_color 	= p3_get_option( $nonlink_key . '_font_color' );
	
	// check optional bound colors
	if ( $optional_colors ) {
		if ( !p3_optional_color_bound( $key . '_font_color' ) ) $color = FALSE;
		if ( !p3_optional_color_bound( $key . '_hover_font_color' ) ) $color_hover = FALSE;
	}
	// visited is always optional 
	if ( $color_visited && !p3_optional_color_bound( $key . '_visited_font_color' ) ) $color_visited = FALSE;
	
	
	if ( ( $do_nonlink || $margin_bottom != '' ) && ( $nonlink_color || $family || $size || $effect || $weight || $margin_bottom != '' || $letterspacing ) ) { 
		$output .= $selector . " {\n\t";
		if ( $nonlink_color ) 	    $output .= "color:" 				. $nonlink_color . ";";
		if ( $family ) 			    $output .= "\n\tfont-family:" 		. $family 	  	 . ";";
		if ( $size ) 			    $output .= "\n\tfont-size:" 		. $size 		 . "px;";
		if ( $effect ) 			    $output .= "\n\ttext-transform:"	. $effect 		 . ";";
		if ( $weight )			    $output .= "\n\tfont-weight:"		. $weight . ';';
		if ( $letterspacing )	    $output .= "\n\tletter-spacing:"	. $letterspacing . ';';
		if ( $margin_bottom != '' ) $output .= "\n\tmargin-bottom:"  	. $margin_bottom . "px;";
		$output .= "\n}\n";
	}	 
	if ( $style || $family || $effect || $color || $size ) {
		$output .= $selector . " a {";
		if ( $style )		  $output .= "\n\tfont-style:"  	. $style  . ";"; 
		if ( $color )  		  $output .= "\n\tcolor:" 			. $color  . ";";
		if ( $family )   	  $output .= "\n\tfont-family:" 	. $family . ";";
		if ( $size )   		  $output .= "\n\tfont-size:" 		. $size   . "px;";
		if ( $effect )  	  $output .= "\n\ttext-transform:"	. $effect . ";";
		if ( $letterspacing ) $output .= "\n\tletter-spacing:"	. $letterspacing . ';';
		if ( $weight )   	  $output .= "\n\tfont-weight:" 	. $weight . ";";
		$output .= "\n}\n";
	}
	if ( $decoration || $color ) {
		$output .= $selector . " a:link {";
		if ( $color ) 		$output .= "\n\tcolor:" 				. $color  		. ";";
		if ( $decoration )	$output .= "\n\ttext-decoration:"		. $decoration  	. ";";
		$output .= "\n}\n";
	}
	if ( $color_visited || $color || $decoration ) {
		$output .= $selector . " a:visited {";
		if ( $color ) 			$output .= "\n\tcolor:" 			. $color . 		  ";";
		if ( $color_visited ) 	$output .= "\n\tcolor:" 			. $color_visited. ";";
		if ( $decoration )		$output .= "\n\ttext-decoration:"	. $decoration  	. ";";
		$output .= "\n}\n";
	}
	if ( $decoration_h || $color_hover ) {
		$output .= $selector . " a:hover {";
		if ( $color_hover )		$output .= "\n\tcolor:" 			. $color_hover	. ";";
		if ( $decoration_h )	$output .= "\n\ttext-decoration:"	. $decoration_h	. ";";
		$output .= "\n}\n";
	}
	return $output;
}


/* return css for advanced postdate */
function p3_advanced_postdate_css( $selector = '' ) {
	if ( p3_test( 'postdate_advanced_switch', 'off' ) || p3_test( 'postdate', 'boxy' ) ) return;
	$selector = ( $selector ) ? $selector : '.post-date span';
	
	$postdate_bg_color     = p3_get_option( 'postdate_bg_color' );
	$postdate_tb_padding   = p3_get_option( 'postdate_tb_padding' );
	$postdate_lr_padding   = p3_get_option( 'postdate_lr_padding' );
	$postdate_border_style = p3_get_option( 'postdate_border_style' );
	$postdate_border_color = p3_get_option( 'postdate_border_color' );
	$postdate_border_width = p3_get_option( 'postdate_border_width' );
	$postdate_border_top   = ( p3_test( 'postdate_border_top', 'on' ) ) ? $postdate_border_width : '0';
	$postdate_border_btm   = ( p3_test( 'postdate_border_bottom', 'on' ) ) ? $postdate_border_width : '0';
	$postdate_border_left  = ( p3_test( 'postdate_border_left', 'on' ) ) ? $postdate_border_width : '0';
	$postdate_border_right = ( p3_test( 'postdate_border_right', 'on' ) ) ? $postdate_border_width : '0';
	echo <<<CSS

	$selector {
		margin-right:0;
		border-top-width:{$postdate_border_top}px;
		border-bottom-width:{$postdate_border_btm}px;
		border-left-width:{$postdate_border_left}px;
		border-right-width:{$postdate_border_right}px;
		border-style: $postdate_border_style;
		border-color: $postdate_border_color;
		background-color:$postdate_bg_color;
		padding:{$postdate_tb_padding}px {$postdate_lr_padding}px;
	}
CSS;
}


/* generic option color bind tester */
function p3_optional_color_bound( $key ) {
	if ( p3_test( $key.'_bind', 'on' ) ) return TRUE;
	return FALSE;
}


/* return border css */
function p3_border_css( $key, $side = '', $selector = '', $important = FALSE ) {
	if ( $important ) $important = ' !important';
	if ( $side ) $side = '-' . $side;
	if ( $selector ) {
		$selector_start = $selector . " {\n\t";
		$selector_end = "\n}\n";
	}
	$border_style = ( ( p3_test( $key .'_style' ) ) ) ? p3_get_option( $key . '_style' ) : 'solid';
	return $css = $selector_start . 'border' . $side . ':' . 
		$border_style . ' ' . 
		p3_get_option( $key . '_width' ) . 'px ' . 
		p3_get_option( $key . '_color' ) . $important . ';' . $selector_end;
}


/* return css that handles vertical centering of comment header elements for minima layout */
function p3_minima_comment_header_css( $custom_image_heights ) {
	
	/* LEFT side: post author link & comments count area */
	$left_side_font_size  = p3_get_option( 'comments_header_link_font_size' );
	$left_side_height     = $left_side_font_size;
	$left_side_use_btn    = p3_test( 'comments_show_hide_method', 'button' );
	$left_side_btn_height = 21;
	
	// using button, and text SMALLER than button height
	if ( $left_side_use_btn AND $left_side_font_size < $left_side_btn_height ) {
		$left_side_height            = $left_side_btn_height;
		$r['left_side_inner_pad']    = ( $left_side_height - $left_side_font_size ) / 2;
		$r['left_side_pad_selector'] = 'p';
		
	// using button, text size LARGER than button height
	} else if ( $left_side_use_btn ) {
		$r['left_side_inner_pad']    = ( $left_side_font_size - $left_side_btn_height ) / 2;
		$r['left_side_pad_selector'] = '#show-hide-button';
	
	// no button
	} else {
		$r['left_side_inner_pad']    = 0;
		$r['left_side_pad_selector'] = 'p';
	}
	
	
	/* RIGHT side: "post interact" links */
	// not using custom uploaded images
	if ( !p3_test( 'comments_post_interact_display', 'images' ) ) {

		// post interaction links (add a comment, link to this post, etc)
		$link_size = p3_cascading_style( 'comments_post_interaction_link_font_size', 'comments_header_link_font_size' );

		// if using P3 link buttons account for hard-coded .6em top/bottom pad & borders
		if ( p3_test( 'comments_post_interact_display', 'button' ) ) {
			$link_added_height = ( ( $link_size * 0.6 ) * 2 ) + 4;
		}
		
		// get total post interact link area height
		$right_side_height = $link_size + $link_added_height;
	
	// using custom images
	} else {
		
		// post interact area height = tallest uploaded image height
		$order_image_heights = $custom_image_heights;
		rsort( $order_image_heights );
		$right_side_height = $order_image_heights[0];
	}
	
	
	/* get identity and added padding for shorter side */
	// left is smaller
	if ( $left_side_height < $right_side_height ) {
		$taller_side_height         = $right_side_height;
		$shorter_side_height        = $left_side_height;
		$r['shorter_side_selector'] = '.comments-header-left-side-wrap';
		
	// right is smaller
	} else {
		$taller_side_height         = $left_side_height;
		$shorter_side_height        = $right_side_height;
		$r['shorter_side_selector'] = '.post-interact';
	}
	
	// calculate padding for the shorter side
	$r['shorter_side_top_padding'] = ( $taller_side_height - $shorter_side_height ) / 2;
	
	
	/* get padding amounts to vertically center custom link images of varying heights */
	if ( p3_test( 'comments_post_interact_display', 'images' ) ) {
		list( $addacomment_height, $linktothispost_height, $emailafriend_height ) = $custom_image_heights;
		$r['addacomment_pad_top']    = ( $right_side_height - $addacomment_height )    / 2;
		$r['linktothispost_pad_top'] = ( $right_side_height - $linktothispost_height ) / 2;
		$r['emailafriend_pad_top']   = ( $right_side_height - $emailafriend_height )   / 2;
	} else {
		$r['addacomment_pad_top'] = $r['linktothispost_pad_top'] = $r['emailafriend_pad_top'] = 0;		
	}
	
	/* debug */
	// $r['left_side_height'] = $left_side_height; $r['right_side_height'] = $right_side_height;
	// $r['taller_side_height'] = $taller_side_height; $r['shorter_side_height'] = $shorter_side_height;
	// $r['link_added_height'] = $link_added_height; $r['link_size'] = $link_size;
	// echo '</style>'; p3_debug_array( $r ); echo '<style type="text/css" media="screen">';
	
	return $r;
}


/* comments scrollbox css */
function p3_comments_scrollbox_css( $selector ) {
	// home page
	if ( p3_test( 'comments_body_scroll_index', 'fixed' ) ) {
		$height   = p3_get_option( 'comments_body_scroll_height' ) . 'px';
		$overflow = 'auto';
	} else {
		$height   = 'none';
		$overflow = 'visible';
	}
	
	// single-type pages
	if ( p3_test( 'comments_body_scroll_singular', 'fixed' ) ) {
		$singular_height   = p3_get_option( 'comments_body_scroll_height' ) . 'px';
		$singular_overflow = 'auto';
	} else {
		$singular_height   = 'none';
		$singular_overflow = 'visible';
	}
	
	//css
	return <<<CSS
	$selector {
		max-height:$height !important;
		overflow:$overflow;
	}
	body.single $selector,
	body.page $selector {
		max-height:$singular_height !important;
		overflow:$singular_overflow;
	}
CSS;
}


/* extract and return css from a bg-area group created by p3_bg() */
function p3_bg_css( $key, $selector ) {
	$img        = ( p3_image_exists( $key ) ) ? 'url(' . p3_imageurl( $key, NO_ECHO ) . ')' : 'none';
	$repeat     = p3_get_option( $key . '_img_repeat' );
	$bgcolor    = p3_color_css_if_bound( $key . '_color' );
	$position   = p3_get_option( $key . '_img_position' );
	$attachment = p3_get_option( $key . '_img_attachment' );
	$ie6_color  = p3_color_css_if_bound( $key . '_ie6_color' );
	$ie6_img    = ( $ie6_color ) ? 'background-image:none !important;' : '';
	return <<<CSS
	$selector {
		$bgcolor
		background-image:$img;
		background-repeat:$repeat;
		background-position:$position;
		background-attachment:$attachment;
	}
	* html $selector {
		$ie6img
		$ie6color
	}
CSS;
}


/* return max image widths */
function p3_image_max_widths( $requested_var = '') {
	$borders_added_width = 2 * p3_get_option( 'post_pic_border_width' );
	$r['entry_content_width'] = p3_get_content_width( NO_SIDEBAR );
	$r['max_image_width'] = $r['entry_content_width'] - $borders_added_width;
	$r['with_sidebar_entry_content_width'] = p3_get_content_width( WITH_SIDEBAR );
	$r['with_sidebar_max_image_width'] = $r['with_sidebar_entry_content_width'] - $borders_added_width;
	// need to return a integer to not cause js error
	$r['sidebar_max_image_width'] = ( p3_fixed_sidebar_info() ) ? p3_fixed_sidebar_info( 'sidebar_content_width' ) : 1000;
	if ( $requested_var ) return $r[$requested_var];
	return $r;
}


/* echo out preview area css for general font-group areas */
function p3_preview_area_css( $key, $selector = '', $linkparams = '', $extra_css = '' ) {
	echo "\n\n\n/* $key */\n";
	
	// build standard selector, if none passed
	if ( !$selector ) $selector = '#' . $key . '-font-preview';
	
	// font group
	if ( strpos( $key, '_link' ) === FALSE ) {
		echo p3_font_css( $key, $selector );
	
	// link group
	} else {
		$do_nonlink = $optional_colors = FALSE;
		if ( $linkparams ) {
			$do_nonlink = intval( $linkparams[0] );
			$optional_colors = intval( $linkparams[1] );
		}
		echo p3_link_css( $key, $selector, $do_nonlink, $optional_colors );
	}
	
	// misc, extra css rules
	if ( $extra_css ) {
		list( $sub_selector, $rules ) = explode( '|', $extra_css );
		echo "$selector $sub_selector { $rules }";
	}
}

?>