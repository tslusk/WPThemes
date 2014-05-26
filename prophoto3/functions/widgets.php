<?php
/* ------------------------------------------------ */
/* -- library of functions for p3 custom widgets -- */
/* ------------------------------------------------ */


/* set up p3 widgets */
add_action( 'widgets_init', 'p3_widgets_init' );

// generate static files on widget update
add_action( 'sidebar_admin_setup', 'p3_generate_static_files' );

// reigister our custom p3 widgets
function p3_widgets_init() {
	register_widget( 'P3_Social_Media_Icons' );
	register_widget( 'P3_Custom_Icon' );
	register_widget( 'P3_Facebook_Likebox' );
	register_widget( 'P3_Twitter_HTML' );
	register_widget( 'P3_Twitter_Slider' );
	register_widget( 'P3_Twitter_Widget' );
	register_widget( 'P3_Subscribe_By_Email' );
	register_widget( 'P3_Text' );
}

// require the widget classes
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3TwitterSlider.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3TwitterHTML.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3FacebookLikebox.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3CustomIcon.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3SocialMediaIcons.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3TwitterWidget.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3SubscribeByEmail.php' );
require( dirname( dirname( __FILE__ ) ) . '/widgets/class.p3Text.php' );


// register "sidebar" sidebar :)
register_sidebar( array(
	'id' => 'p3-contact-form',
	'before_title' => "<h2>",
	'after_title' => "</h2>\n",
	'name' => 'Contact Form Content Area',
) );


// register bio sidebars
register_sidebar( array(
	'id' => 'bio-spanning-col',
	'before_title' => "<h3 class='widgettitle'>",
	'after_title' => "</h3>\n",
	'name' => 'Bio Area Spanning Column',
 ) );
for ( $i = 1; $i <= MAX_BIO_WIDGET_COLUMNS; $i++ ) { 
	register_sidebar( array(
		'id' => 'bio-col-' . $i,
		'before_title' => "<h3 class='widgettitle'>",
		'after_title' => "</h3>\n",
		'name' => 'Bio Area Column #' . $i,
	 ) );
}

// register "sidebar" sidebar :)
register_sidebar( array(
	'id' => 'p3-sidebar',
	'before_title' => "<h3 class='widgettitle'>",
	'after_title' => "</h3>\n",
	'name' => 'Fixed Sidebar',
) );

// register sliding drawers
for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) {
	register_sidebar( array(
		'id' => 'drawer-' . $i,
		'before_title' => "<h3 class='widgettitle'>",
		'after_title' => "</h3>\n",
		'name' => 'Sliding Drawer Sidebar #' . $i,
		'description' => 'Change text for this drawer\'s tab in "Appearance" > "P3 Customize" > "Sidebar" > "Sliding Drawer Sidebars".',
	 ) );
}

// register footer sidebars
for ( $i = 1; $i <= MAX_FOOTER_WIDGET_COLUMNS; $i++ ) { 
	register_sidebar( array(
		'id' => 'footer-col-' . $i,
		'before_title' => "<h3 class='widgettitle'>",
		'after_title' => "</h3>\n",
		'name' => 'Footer Column #' . $i,
	 ) );
}


/* regenerate static files when widgets are dragged, reordered */
add_action( 'update_option_sidebars_widgets', 'p3_store_options' );


/* wrapper function to test for whether a widget is in use */
function p3_is_active_widget( $id ) {
	if ( is_active_widget( FALSE, FALSE, $id ) ) return TRUE;
	return FALSE;
}


/* return all or specific info about a widget based on instance ID */
function p3_get_widget_info( $instance, $requested_var = FALSE ) {
	$last_dash_pos = strrpos( $instance, '-' );
	$instance[$last_dash_pos] = '^';
	list( $r['widget_type'], $r['widget_id'] ) = explode( '^', $instance );
	if ( !$requested_var || $requested_var == 'widget_data' ) {
		$widget_type_array = get_option( 'widget_' . $r['widget_type'] );
		$r['widget_data'] = $widget_type_array[$r['widget_id']];
	}
	if ( $requested_var ) return $r[$requested_var];
	return $r;
}


/* boolean function to test if HTML-based twitter updates are needed */
function p3_using_twitter_html() {
	if ( p3_test( 'twitter_onoff', 'on' ) ) return TRUE;
	if ( p3_using_twitter_html_widget() ) return TRUE;
	return FALSE;
}


/* boolean function to test if any HTML-based twitter widgets are in use */
function p3_using_twitter_html_widget() {
	if ( p3_is_active_widget( 'p3-twitter-html' ) ) return TRUE;
	if ( p3_is_active_widget( 'p3-twitter-slider' ) ) return TRUE;
	return FALSE;
}


/* uses p3 defined font families to create font-select dropdown for widgets */
function p3_widget_font_select( $instance_val ) { 
	$fonts = explode( '|', FONT_FAMILIES );
	$num_fonts = count( $fonts );
	
	for ( $i = 0; $i <= $num_fonts; $i = $i+2 ) {
		$font_name  = $fonts[$i+1];
		$font_value = str_replace('"', "'", $fonts[$i]);
		$selected   = selected( $instance_val, $font_value, NO_ECHO );
		if ( !$font_name ) continue;
		echo "<option value=\"$font_value\"$selected>$font_name</option>\n";
	}
}


/* return array of calculated widths for each widgetized bio column */
function p3_bio_column_widths() {
	$column_count = $flex_column_count = 0;
	
	// working area: blog width - 2x bio margings
	$bio_lr_padding = ( p3_test( 'bio_lr_padding' ) ) ? p3_get_option( 'bio_lr_padding' ) : p3_get_option( 'content_margin' );
	$working_area = $initial_working_area =  p3_get_option( 'blog_width' ) - ( $bio_lr_padding * 2 );
	
	// gutter width
	$bio_gutter_width = ( p3_test( 'bio_gutter_width' ) ) ? p3_get_option( 'bio_gutter_width' ) : p3_get_option( 'content_margin' );
	
	// if we have a bio picture, subtract it's width - also set spanning column width
	if ( !p3_test( 'biopic_display', 'off' ) ) {
		$column_count++; // for the sake of calculating number of gutters
		$border_width = ( p3_test( 'biopic_border', 'on' ) ) ? p3_get_option( 'biopic_border_width' ) * 2 : 0;
		$working_area = $working_area - p3_imagewidth( 'biopic1', NO_ECHO ) - $border_width;
		$widths['spanning-col'] = $working_area - $bio_gutter_width;
		$widths['biopic-offset'] = p3_imagewidth( 'biopic1', NO_ECHO ) + $border_width + $bio_gutter_width;
	} else {
		$widths['spanning-col'] = $working_area;
	}
		
	// loop through available bio columns, checking each one
	for ( $i = 1; $i <= MAX_BIO_WIDGET_COLUMNS; $i++ ) {
		// skip if no widgets in column
		if ( !is_active_sidebar( 'bio-col-' . $i ) ) continue;
		// we have widgets, count this as a column
		$column_count++;	
		// get width information for this column
		$widths['bio-widget-col-' . $i] = p3_this_widget_column_width( 'bio-col-' . $i );	
	}
	//p3_debug_array( $widths );
	
	// now that we know column count, subtract gutters from working area
	$bio_gutter_count = $column_count - 1;
	if ( $bio_gutter_count < 0 ) $bio_gutter_count = 0;
	$working_area = $working_area - ( $bio_gutter_width * $bio_gutter_count );
	
	/* temp pass: calculate using minimum width values */
	$temp_working_area = $working_area;
	foreach ( $widths as $column_width ) {
		// count our flex columns
		if ( 'flex' == $column_width['width'] ) {
			$flex_column_count++;
		// subtract width of known columns from working area
		} elseif ( $column_width['width'] ) {
			$temp_working_area = $temp_working_area - $column_width['width'];
		}
	}
	// compute temp-pass flexible column width, assuming we're using minimum values
	$temp_flex_column_count = $flex_column_count;
	if ( $temp_flex_column_count == 0 ) $temp_flex_column_count = 1;	
	$temp_flex_column_width = $temp_working_area / $temp_flex_column_count;
	
	// now we have a computed flex width, if it's bigger than min-width of a column
	// reset that column to 'flex' to recalulate
	foreach ( $widths as $column => $col_width_array ) {
		if ( $col_width_array['min-width'] ) {
			if ( $temp_flex_column_width > $col_width_array['min-width'] ) {
				$widths[$column]['min-width'] = '';
				$widths[$column]['width'] = 'flex';
			// or else use the min width as the actual width
			} else {
				$widths[$column]['width'] = $widths[$column]['min-width'];
				$widths[$column]['min-width'] = '';
				$flex_column_count--;
			}
		}
	}

	/* final pass: min-widths that were less than temp flex width were reset to flex */
	
	// subtract known column widths from working-area
	foreach ( $widths as $column => $col_width_array ) {
		if ( 'flex' != $col_width_array['width'] ) {
			$working_area = $working_area - $col_width_array['width'];
		}
	}
	
	// calculate final flex column width
	if ( $flex_column_count == 0 ) $flex_column_count = 1;	
	$flex_column_width = $working_area / $flex_column_count;
	$flex_column_width = round( $flex_column_width, 0 ) - 1;		//p3_debug_array( $widths );
	
	// use the final flex column width for the return width of flex columns
	foreach ( $widths as $column => $col_width_array ) {
		if ( 'flex' == $col_width_array['width'] ) {
			$widths[$column]['width'] = $flex_column_width;
		}
	}
	
	// debug
	// $widths['bio_lr_padding'] = $bio_lr_padding;
	// $widths['initial_working_area'] = $initial_working_area;
	// $widths['bio_gutter_width'] = $bio_gutter_width;
	// p3_debug_array( $widths );
	
	return apply_filters( 'p3_biocolwidths', $widths );
}


/* analyzes widget/s in column, returns width value recommendations */
function p3_this_widget_column_width( $column ) {
	$all_widgets     = wp_get_sidebars_widgets(); 	
	$this_widget_col = $all_widgets[$column];
	$widgets_in_col  = count( $this_widget_col );
	$column_width    = 0;
	$has_undefined_width_widget = FALSE;

	// store widest width of defined-width widget
	// make note if a non-defined widget is present
	foreach ( $this_widget_col as $index => $widget_id ) {
		
		// social media icons
		if ( strpos( $widget_id, 'p3-social-media-icons' ) !== FALSE ) {
			$widget_num = str_replace( 'p3-social-media-icons-', '', $widget_id );
			if ( !$icon_info ) $icon_info = get_option( 'widget_p3-social-media-icons' );
			$icon_size = p3_get_icon_size_info( $icon_info[$widget_num] );
			$this_widget_width = $icon_size['html_size'];
			$column_width = p3_larger_widget_or_column( $this_widget_width, $column_width );
		
		// custom icon
		} elseif ( strpos( $widget_id, 'p3-custom-icon' ) !== FALSE ) {
			$widget_num = str_replace( 'p3-custom-icon-', '', $widget_id );
			if ( !$widget_info ) $widget_info = get_option( 'widget_p3-custom-icon' );
			$img_number = $widget_info[$widget_num]['number'];
			$this_widget_width = p3_imagewidth( 'widget_custom_image_' . $img_number, NO_ECHO );
			$column_width = p3_larger_widget_or_column( $this_widget_width, $column_width );
		
		// facebook likebox
		} elseif ( strpos( $widget_id, 'p3-facebook-likebox' ) !== FALSE ) {
			$widget_num = str_replace( 'p3-facebook-likebox-', '', $widget_id );
			if ( !$likebox_info ) $likebox_info = get_option( 'widget_p3-facebook-likebox' );
			$likebox_code = $likebox_info[$widget_num]['box_code'];
			preg_match( '/;width=[0-9]*/', $likebox_code, $match );
			$this_widget_width = preg_replace( '/[^0-9]/', '', $match[0] );
			$column_width = p3_larger_widget_or_column( $this_widget_width, $column_width );
			
		// twitter.com widget
		} elseif ( strpos( $widget_id, 'p3-twitter-com-widget' ) !== FALSE ) {
			$widget_num = str_replace( 'p3-twitter-com-widget-', '', $widget_id );
			if ( !$twitter_widget_info ) $twitter_widget_info = get_option( 'widget_p3-twitter-com-widget' );
			$twitter_widget_width = $twitter_widget_info[$widget_num]['width'];
			if ( $twitter_widget_width == "'auto'" ) {
				$has_undefined_width_widget = TRUE;
			} else {
				if ( $twitter_widget_width < 150 ) $twitter_widget_width = 150; // undocumented min-width of widget
				$column_width = p3_larger_widget_or_column( $twitter_widget_width, $column_width );
			}
		
		// sliding twitter widget
		} elseif ( strpos( $widget_id, 'p3-twitter-slider' ) !== FALSE ) {
			if ( !$widget_info ) $widget_info = get_option( 'widget_p3-twitter-slider' );
			$widget_num = str_replace( 'p3-twitter-slider-', '', $widget_id );
			$img_val = $widget_info[$widget_num]['image'];
			// using an image
			if ( $img_val != 'no' ) {
				// built in images
				if ( $img_val == 'A' ) {
					$this_widget_width = 300; // twitter image1 width
				} elseif ( $img_val == 'B' ) {
					$this_widget_width = 194; // twitter image2 width
				// custom images
				} else {
					$this_widget_width = p3_imagewidth( 'widget_custom_image_' . $img_val, NO_ECHO );
				}
			// no background image
			} else {
				$this_widget_width = intVal( $widget_info[$widget_num]['tweet_width'] ) + 25; 
			}
			$column_width = p3_larger_widget_or_column( $this_widget_width, $column_width );
		
		// widget with no known width (flexible)
		} else {
			$has_undefined_width_widget = TRUE;
		}
	};

	// non-zero column width means at least one widget has defined width
	if ( $column_width ) {
		// there is also an undefined-width widget, call it a flex, but note the mininum width
		if ( $has_undefined_width_widget ) {
			$col_width['width'] = 'flex';
			$col_width['min-width'] = $column_width;
		// only defined-width widgets, return this as actual width
		} else {
			$col_width['width'] = $column_width;
		}
	// column_width still 0, no defined-width widgets found
	} else {
		$col_width['width'] = 'flex';
	}
	  
	return $col_width;
}


/* subroutine for comparing widget width with working column width  */
function p3_larger_widget_or_column( $widget_width, $column_width ) {
	if ( $widget_width > $column_width ) {
		return $column_width = $widget_width;
	}
	return $column_width;
}


/* returns CSS for bio columns, including widths and final gutter correction */
function p3_bio_column_css() {
	$widths = p3_bio_column_widths();  // p3_debug_array( $widths );
	
	// spanning column
	$output .= 
	"#bio-widget-spanning-col {
		width:{$widths['spanning-col']}px;
		float:left;
		margin-right:0;
	}\n";
	unset( $widths['spanning-col'] );
	
	// non-spanning column margin
	if ( $widths['biopic-offset'] ) {
		$side = p3_get_option( 'biopic_align' );
		$output .=
		"#bio-widget-col-wrap {
			margin-{$side}:{$widths['biopic-offset']}px;
		}\n";
		unset( $widths['biopic-offset'] );
	}
	
	// non-spanning columns
	$column_count = count( $widths );	
	$c = 1;
	foreach ( $widths as $key => $val ) {
		$output .= "#$key {\n\twidth:{$val['width']}px;";
		// if it is the last column
		if ( $c == $column_count ) {
			// remove the right margin (gutter)
			$output .= "\n\tmargin-right:0;";
		}
		$output .= "\n}\n";
		$c++;
	}
	
	return $output;
}


/* computes and returns filename and display size info for social media icon widget */
function p3_get_icon_size_info( $instance ) {
	if ( 'large' == $instance['size'] ) {
		$filesize = $htmlsize = '256';
	} else if ( 'small' == $instance['size'] ) {
		$filesize = $htmlsize  = '128';
	} else {
		// icons available in two saved sizes, 256px and 128px, size also included in filename
		$custom_size = intval( $instance['custom_size'] );
		$filesize = ( $custom_size > 128 ) ? '256' : '128';
		$htmlsize = $custom_size;
	}
	$size['filename_size'] = $filesize;
	$size['html_size']     = $htmlsize;
	return $size;
}


/* creates some default widget instances on first theme activation */
function p3_default_widget_instances() {
	global $p3_default_widgets;
	
	if ( !is_active_sidebar( 'p3-contact-form' ) ) {
		p3_add_widget( 'p3-contact-form', 'p3-text', $p3_default_widgets['contact-text'] );
	}
	
	if ( p3_bio_without_widgets() ) {
		p3_add_widget( 'bio-spanning-col', 'p3-text', $p3_default_widgets['bio-text'] );
	}
	
	if ( p3_footer_without_widgets() ) {
		p3_add_widget( 'footer-col-1', 'search',       $p3_default_widgets['search'] );
		p3_add_widget( 'footer-col-1', 'links',        $p3_default_widgets['links'] );
		p3_add_widget( 'footer-col-2', 'archives',     $p3_default_widgets['archives'] );
		p3_add_widget( 'footer-col-3', 'categories',   $p3_default_widgets['categories'] );
		p3_add_widget( 'footer-col-3', 'meta',         $p3_default_widgets['meta'] );
		p3_add_widget( 'footer-col-4', 'recent-posts', $p3_default_widgets['recent-posts'] );
		p3_add_widget( 'footer-col-4', 'pages',        $p3_default_widgets['pages'] );
	}
}


/* boolean test: are all of the footer area widget columns empty? */
function p3_footer_without_widgets() {
	for ( $i = 1; $i <= MAX_FOOTER_WIDGET_COLUMNS; $i++ ) { 
		if ( is_active_sidebar( 'footer-col-' . $i ) ) return FALSE;
	}
	return TRUE;
}


/* boolean test: are all of the bio area widget columns empty? */
function p3_bio_without_widgets() {
	for ( $i = 1; $i <= MAX_BIO_WIDGET_COLUMNS; $i++ ) { 
		if ( is_active_sidebar( 'bio-col-' . $i ) ) return FALSE;
	}
	if ( is_active_sidebar( 'bio-spanning-col' ) ) return FALSE;
	return TRUE;
}


/* add an instance of a widget into the active sidebars */
function p3_add_widget( $widget_sidebar, $widget_type, $added_widget ) {
	// get current widget instances info array for this widget type
	$widgets = get_option( 'widget_' . $widget_type );
	if ( !is_array( $widgets ) ) $widgets = array();
	
	// add our custom widget instance
	$widgets[] = $added_widget;
	
	// get id of added widget
	$keys = array_keys( $widgets );
	$new_widget_id = array_pop( $keys );
	
	// update db with new widget added
	update_option( 'widget_' . $widget_type, $widgets );
	
	//  get current sidebar widgets info array
	$sidebars_widgets = get_option( 'sidebars_widgets' );
	if ( !is_array( $sidebars_widgets ) ) $sidebars_widgets = array();
	
	// update the info with our newly created widget, store in db
	$sidebars_widgets[$widget_sidebar][] = $widget_type . '-' . $new_widget_id;
	update_option( 'sidebars_widgets', $sidebars_widgets );
}


/* prepare widget data for designer-zip export */
function p3_widget_export_data() {
	$widgets = get_option( 'sidebars_widgets' );
	unset( $widgets['wp_inactive_widgets'] );

	foreach ( $widgets as $widget_type => $active_widgets_of_type ) {
		if ( is_array( $active_widgets_of_type ) && !empty( $active_widgets_of_type ) ) {
			$active_widgets[$widget_type] = $active_widgets_of_type;
			foreach ( $active_widgets_of_type as $sidebar => $widget ) {
				$widget_data[$widget] = p3_get_widget_info( $widget );
			}
		}
	}

	$widgetExport = p3_strip_corrupters_deep( compact( 'active_widgets', 'widget_data' ) );
	return serialize( $widgetExport );
}


/* import widget data from design zip */
function p3_import_widgets( $import ) {
	
	$current_widgets = get_option( 'sidebars_widgets' );
	
	// move active widgets that are in the way into inactive area
	foreach ( $import['active_widgets'] as $importing_sidebar => $widgets ) {
		if ( !empty( $current_widgets[$importing_sidebar] ) ) {
			foreach ( $current_widgets[$importing_sidebar] as $existing_widget ) {
				$current_widgets['wp_inactive_widgets'][] = $existing_widget;
			}
			$current_widgets[$importing_sidebar] = array();
		}
	}
	update_option( 'sidebars_widgets', $current_widgets );

	// insert new widgets from design
	foreach ( $import['active_widgets'] as $importing_sidebar => $widgets ) {
		foreach ( $widgets as $widget ) {
			p3_add_widget( 
				$importing_sidebar, 
				$import['widget_data'][$widget]['widget_type'], 
				$import['widget_data'][$widget]['widget_data'] 
			);
		}
	}
}


/* embedded widget help links */
function p3_widget_help_link( $widget_title ) {
	$slug = strtolower( str_replace( array( ' ', '.' ), '-', $widget_title ) ) . '-widget';
	$url = PROPHOTO_SITE_URL . "support/about/$slug/";
	echo <<<HTML
	<a class="p3-widget-help" href="$url" target="_blank" title="click for tutorial on P3 $widget_title Widget">&nbsp;&nbsp;</a>
HTML;
}

?>