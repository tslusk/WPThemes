<?php
/* ----------------------------- */
/* -- custom css for bio area -- */
/* ----------------------------- */
extract( p3_general_css_vars() );

/* FIRST SECTION: fixed sidebar */
if ( !p3_test( 'sidebar', 'false' ) ) {
	
	extract( p3_fixed_sidebar_info() );
	
	// content wrap width
	$content_wrap_width = p3_get_content_width() + ( 2 * $content_margin );

	// sidebar border
	if ( $using_border ) {
		$sidebar_border_css = p3_border_css( 'sidebar_border', $content_side );
	}

	// headline css
	$headline_css = p3_font_css( 'sidebar_headlines', '#sidebar h3.widgettitle', TRUE );

	// sidebar general text css
	$text_css = p3_font_css( 'sidebar_text', '#sidebar, #sidebar p', TRUE );
	
	// sidebar link css
	$link_css = p3_link_css( 'sidebar_link', '#sidebar ', FALSE, TRUE );
	
	// sidebar background
	$sidebar_bg_css = p3_bg_css( 'sidebar_bg', '#sidebar' );

	// margin below widgets
	$widget_margin_bottom =  p3_get_option( 'sidebar_widget_margin_bottom' );
	
	// use post header margin top for upper margin to make things look reeeeel nice :)
	$post_header_margin_above = p3_get_option( 'post_header_margin_above' );
	
	// widget separator image
	if ( p3_image_exists( 'sidebar_widget_sep_img' ) ) {
		$widget_sep_image = 'background:url(' . p3_imageurl( 'sidebar_widget_sep_img', NO_ECHO ) . ') no-repeat bottom center;';
		$widget_padding_bottom = p3_imageheight( 'sidebar_widget_sep_img', NO_ECHO );	
	} else {
		$widget_padding_bottom = 0;
	}
	
	// move comments and post footer when necessary
	if ( $move_post_footer_items ) {
		$move_post_footer_items_css = <<<CSS
		body.has-sidebar .entry-comments,
		body.has-sidebar .post-footer {
			margin-{$sidebar_side}:{$content_margin}px;
		}
CSS;
	}



	/* output the CSS */
	echo <<<CSS
	table#content-wrap {
		border-collapse:collapse;
	}
	body.has-sidebar #content {
		width:{$content_wrap_width}px;
		float:$content_side;
	}
	#sidebar {
		vertical-align:top;
		padding-top:{$post_header_margin_above}px;
		overflow:hidden;
		width:{$sidebar_content_width}px;
		max-width:{$sidebar_content_width}px;
		padding-{$sidebar_outer_side}:{$sidebar_outer_padding_width}px;
		padding-{$sidebar_inner_side}:{$sidebar_inner_padding_width}px;
		$sidebar_background
		$sidebar_border_css
		border-top-width:0;
		border-bottom-width:0;
		border-{$sidebar_outer_side}-width:0;
	}
	#sidebar .widget {
		margin-bottom:{$widget_margin_bottom}px;
		padding-bottom:{$widget_padding_bottom}px;
		$widget_sep_image
	}
	#sidebar img {
		height:auto;
		max-width:{$sidebar_content_width}px;
	}
	#sidebar img.p3-social-media-icon {
		max-height:{$sidebar_content_width}px !important;
	}
	$text_css
	$headline_css
	$link_css
	$move_post_footer_items_css
	$sidebar_bg_css
CSS;
}





/* SECOND SECTION: Sliding drawer sidebars */
if ( p3_using_sidebar_drawers() ) {

	// the magic numbers here are pixel amounts to achieve normal-looking visual spacing
	$drawer_bg_color      = p3_get_option( 'drawer_default_bg_color' );
	$drawer_padding       = p3_get_option( 'drawer_padding' );
	$first_tab_top        = 20;
	$tab_spacing          = 5;
	$tab_font_size        = ( p3_get_option( 'drawer_tab_font_size' ) ) 
							? intval( p3_get_option( 'drawer_tab_font_size' ) ) : 12;
	$tab_letter_margin    = ( ( $tab_font_size / 12 ) < 1 ) ? 1 : intval( $tab_font_size / 12 );
	$tab_width            = $tab_font_size * 2;
	$tab_vertical_padding = intval( $tab_font_size * 0.75 );

	// get drawer-specific settings and calculated info
	$total_prev_tabs_offset = 0;
	for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) {
		if ( !is_active_sidebar( 'drawer-' . $i ) ) continue;
		
		// calculate this offset
		$drawer[$i]['tab_offset'] = $first_tab_top + $total_prev_tabs_offset;
	
		// add this tab's height to total offset for next tab's calculation
		$drawer[$i]['tab_height'] = ( $tab_font_size + ( $tab_letter_margin * 2 ) ) * mb_strlen( p3_get_option( 'drawer_tab_text_' . $i ) ) + ( $tab_vertical_padding * 2 );
		$total_prev_tabs_offset = $total_prev_tabs_offset + $drawer[$i]['tab_height'] + $tab_spacing;
	
		// override tab font colors
		if ( p3_optional_color_bound( 'drawer_tab_font_color_' . $i ) ) {
			$drawer[$i]['tab_font_color'] = 'color:' . p3_get_option( 'drawer_tab_font_color_' . $i ) . ';';
		}
	
		// override widget headlines font colors
		if ( p3_optional_color_bound( 'drawer_widget_headlines_font_color_' . $i ) ) {
			$drawer[$i]['widget_headline_color'] = 'color:' . p3_get_option( 'drawer_widget_headlines_font_color_' . $i ) . ';';
		}
	
		// override widget font colors
		if ( p3_optional_color_bound( 'drawer_widget_text_font_color_' . $i ) ) {
			$drawer[$i]['widget_font_color'] = 'color:' . p3_get_option( 'drawer_widget_text_font_color_' . $i ) . ';';
		}
	
		// override widget font colors
		if ( p3_optional_color_bound( 'drawer_widget_link_font_color_' . $i ) ) {
			$drawer[$i]['widget_link_font_color'] = 'color:' . p3_get_option( 'drawer_widget_link_font_color_' . $i ) . ';';
		}
	
		// override bg colors	
		if ( p3_optional_color_bound( 'drawer_bg_color_' . $i ) ) {
			$drawer[$i]['bg_color'] = 'background-color:' . p3_get_option( 'drawer_bg_color_' . $i ) . ';';
		}
	
		// calculate width of each drawer
		$drawer_default_width     = p3_get_option( 'drawer_content_width_' . $i );
		$drawer_widget_width_info = p3_this_widget_column_width( 'drawer-' . $i );

		// min width, check against default drawer width
		if ( intval( $drawer_widget_width_info['min-width'] > $drawer_default_width  ) ) {
			$drawer[$i]['content_width'] = $drawer_widget_width_info['min-width'];

		// use exact width of widest widget (no flexible width widgets)
		} elseif ( $drawer_widget_width_info['width'] != 'flex' ) {
			$drawer[$i]['content_width'] = $drawer_widget_width_info['width'];

		// flexible-width widgets only, or min-width less than default, use default width
		} else {
			$drawer[$i]['content_width'] = $drawer_default_width;
		}
	
		// drawer total width
		$drawer[$i]['width'] = $drawer[$i]['content_width'] + ( 2 * $drawer_padding );
	
		// drawer specific css
		$drawer_specific_css .= <<< CSS
		#tab_{$i} {
			top:{$drawer[$i]['tab_offset']}px;
			{$drawer[$i]['bg_color']}
			{$drawer[$i]['tab_font_color']}
		}
		#drawer_{$i} {
			left:-{$drawer[$i]['width']}px;
		}
		#drawer_content_{$i} {
			width:{$drawer[$i]['content_width']}px;
			{$drawer[$i]['bg_color']}
		}
		#drawer_content_{$i} .widgettitle {
			{$drawer[$i]['widget_headline_color']}
		}
		#drawer_content_{$i}, #drawer_content_{$i} p {
			{$drawer[$i]['widget_font_color']}
		}
		#drawer_content_{$i} a,
		#drawer_content_{$i} a:link,
		#drawer_content_{$i} a:hover,
		#drawer_content_{$i} a:visited {
			{$drawer[$i]['widget_link_font_color']}
		}
		* html #drawer_{$i} {
			position: absolute; 
			top: expression((0 + (ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px'); 
			left:-{$drawer[$i]['width']}px;
		}
	
CSS;
	}


	// drawer widgets
	$drawer_widget_titles     = p3_font_css( 'drawer_widget_headlines', '.drawer_content h3.widgettitle' );
	$drawer_widget_text       = p3_font_css( 'drawer_widget_text', '.drawer_content, .drawer_content p' );
	$drawer_widget_links      = p3_link_css( 'drawer_widget_link', '.drawer_content ', FALSE, TRUE );
	$drawer_widget_btm_margin = p3_get_option( 'drawer_widget_btm_margin' );
	
	// drawer tab font
	$drawer_tab_text_transform = p3_get_option( 'drawer_tab_text_transform' );
	$drawer_tab_font_color     = p3_get_option( 'drawer_tab_font_color' );
	$drawer_tab_font_family    = ( p3_test( 'drawer_tab_font_family' ) ) 
		? 'font-family:' . p3_get_option( 'drawer_tab_font_family' ) . ';' : '';
	$drawer_tab_font_size      = ( p3_test( 'drawer_tab_font_size' ) ) 
		? p3_get_option( 'drawer_tab_font_size ' ) : '12';
	
	// drawer opacity
	$drawer_opacity = floatval( p3_get_option( 'drawer_default_opacity' ) / 100 );

	// rounded corners for drawer tabs
	if ( p3_test( 'drawer_tab_rounded_corners', 'on' ) ) $tab_border_radius = 
		'-moz-border-radius-topright:10px;
		-moz-border-radius-bottomright:10px;
		-webkit-border-top-right-radius:10px;
		-webkit-border-bottom-right-radius:10px;';
	

	echo <<<CSS
	$drawer_widget_titles
	$drawer_widget_text
	$drawer_widget_links
	.tab {
		$drawer_tab_font_family
		font-size:$drawer_tab_font_size;
		background:$drawer_bg_color;
		position:absolute;
		right:-{$tab_width}px;
		width:{$tab_width}px;
		text-align:center;
		color:$drawer_tab_font_color;
		padding:{$tab_vertical_padding}px 0;
		opacity:$drawer_opacity;
		$tab_border_radius	
	}
		.tab span {
			display:block;
			height:{$tab_font_size}px;
			padding:{$tab_letter_margin}px 0;
			line-height:{$tab_font_size}px;
			text-transform:{$drawer_tab_text_transform};
			font-size:{$tab_font_size}px;
		}
	.drawer {
		padding:0;
		z-index:5000;
		position:fixed;
		top:0px;
	}
	.drawer_content {
		opacity:$drawer_opacity;
		padding:{$drawer_padding}px;
		background:$drawer_bg_color;
		overflow:hidden;
	}
	.drawer li.widget {
		margin-bottom:{$drawer_widget_btm_margin}px;
	}
	/* drawer-specific settings */
	$drawer_specific_css

CSS;

}
?>