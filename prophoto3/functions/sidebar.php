<?php
/* -------------------------------------------------- */
/* -- library of functions used for theme sidebars -- */
/* -------------------------------------------------- */


/* return data about fixed sidebar, either a requested piece of info, or array of all info */
function p3_fixed_sidebar_info( $requested_var = FALSE ) {
	if ( p3_test( 'sidebar', 'false' ) ) return FALSE;
	
	// basic facts
	$r['sidebar_side'] = $r['sidebar_outer_side'] = p3_get_option( 'sidebar' );
	$r['content_side'] = $r['sidebar_inner_side'] = ( $r['sidebar_side'] == 'right' ) ? 'left' : 'right';
	$r['sidebar_content_width'] = p3_get_option( 'sidebar_width' );
	$r['sidebar_padding_width_input'] = $r['sidebar_outer_padding_width'] = p3_get_option( 'sidebar_padding' );
	
	// border line info
	if ( p3_test( 'sidebar_border_switch', 'on' ) ) {
		$r['using_border'] = TRUE;
		$r['border_color'] = p3_get_option( 'sidebar_border_color' );
		$r['border_width'] = p3_get_option( 'sidebar_border_width' );
		$r['border_style'] = p3_get_option( 'sidebar_border_style' );
	}
	
	// inner padding
	if ( p3_get_option( 'sidebar_inner_padding_override' ) != '' ) {
		$r['sidebar_inner_padding_width'] = p3_get_option( 'sidebar_inner_padding_override' );
	} else {
		if ( $r['using_border'] ) {
			$r['sidebar_inner_padding_width'] = $r['sidebar_outer_padding_width'];
		} else {
			$r['sidebar_inner_padding_width'] = 0;
			$r['move_post_footer_items'] = TRUE;
		}
	}
	
	// total width
	$r['sidebar_total_width'] = 
		$r['sidebar_content_width'] + 
		$r['sidebar_outer_padding_width'] + 
		$r['sidebar_inner_padding_width'];
	if ( $r['using_border'] ) {
		$r['sidebar_total_width'] += $r['border_width'];
	}
	
	/* debug */
	// echo '</style>'; p3_debug_array( $r ); echo '<style type="text/css" media="screen">';
	
	// return single var, or entire array
	if ( $requested_var ) return $r[$requested_var]; 
	return $r;
}



/* boolean test: are any sliding drawer sidebars in use? */
function p3_using_sidebar_drawers() {
	for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) {
		if ( is_active_sidebar( 'drawer-' . $i ) ) return TRUE;
	}
	return FALSE;
}


/* print drawer-type sidebars */
function p3_print_drawers() {
	if ( isset( $_GET['gallery_popup'] ) ) return;
	for ( $i = 1; $i <= NUM_AVAILABLE_DRAWERS; $i++ ) {
		if ( is_active_sidebar( 'drawer-' . $i ) ) { ?>	
			<div id="drawer_<?php echo $i ?>" class="drawer">

				<div id="tab_<?php echo $i ?>" class="tab">
					<?php p3_drawers_tab_text( p3_get_option( 'drawer_tab_text_' . $i ) ) ?>	
				</div><!-- .tab -->

				<ul id="drawer_content_<?php echo $i ?>" class="drawer_content">					
					<?php dynamic_sidebar( 'Sliding Drawer Sidebar #' . $i ); ?>					
				</ul><!-- .drawer_content -->

			</div><!-- .drawer -->
<?php
		}
	}
}


/* print text vertically for drawer tabs */
function p3_drawers_tab_text( $text ) {
	if ( $text == '' ) $text = 'INFO';
	$text_length = mb_strlen( $text ); 
	for ( $i = 0; $i <= $text_length; $i++ ) {
		$character = mb_substr( $text, $i, 1 );
		$character =  ( ' ' == $character ) ? '&nbsp;' : $character;
		if ( $character != '' ) echo '<span>' . $character . "</span>\n";
	}
}


/* do we have a sidebar on this page */
function p3_doing_sidebar() {
	if ( p3_test( 'sidebar', 'false' ) ) return FALSE;
	$side = p3_get_option( 'sidebar' );

	// front page
	if ( P3_HAS_STATIC_HOME && is_front_page() ) {
		return ( p3_get_option( 'sidebar_on_front_page' ) ) ? $side : FALSE;
	} 
	
	// home page and archives: test p3 setting
	if ( is_home() && p3_get_option( 'sidebar_on_index' ) ) return $side;
	if ( ( is_archive() || is_search() ) && p3_get_option( 'sidebar_on_archive' ) ) return $side;
	
	// single posts and pages
	if ( is_single() || is_page() ) {
		global $post;
		
		// custom meta trumps p3 setting *UNDOCUMENTED FEATURE
		if ( $per_post_do_sidebar = get_post_meta( $post->ID, 'do_sidebar', AS_STRING ) ) {
			return ( strtolower( $per_post_do_sidebar ) == 'true' ) ? $side : FALSE;
		}
		
		// no custom meta, use global p3 option
		if ( is_single() && p3_get_option( 'sidebar_on_single' ) ) return $side;
		if ( is_page()   && p3_get_option( 'sidebar_on_page' ) ) return $side;
		
	}
	return FALSE;	
}

?>