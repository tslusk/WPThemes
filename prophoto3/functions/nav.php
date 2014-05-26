<?php
/* ----------------------------------------------- */
/* -- library of functions used by the nav menu -- */
/* ----------------------------------------------- */


/* print the nav link. used both on the blog and in Options/Menu */
function p3_print_nav_links() {

	$default_order = array(
		'home', 'portfolio', 'galleries', 'hiddenbio', 'pages',
		'recentposts', 'archives', 'categories', 'blogroll',
		'customlink1', 'customlink2', 'customlink3', 'customlink4',
		'customlink5', 'customlink6', 'customlink7', 'customlink8', 'customlink9', 'customlink10',
		'twitter', 'emaillink', 'contact', 'subscribebyemail', 'search', 'rss' );

	// if we have a custom order, get it into array for use
	if ( p3_test( 'menuorder' ) ) {
		$custom_order = p3_get_option( 'menuorder' ); // string: "home;portfolio;hiddenbio;...."
		$custom_order = split( ';', $custom_order );
		foreach ( (array) $custom_order as $key => $val ) {
			if ( !$val ) unset( $custom_order[$key] ); // clean up js wackiness
		}
	}
	
	// get order in which to attempt to print menu items by merging custom with default
	if ( $custom_order ) {
		foreach ( $default_order as $nav_link ) {
			if ( !in_array( $nav_link, $custom_order ) ) $custom_order[] = $nav_link;
		}
		$menu_order = $custom_order;
	} else {
		$menu_order = $default_order;
	}

	// loop through order and print each link by calling named function
	foreach ( $menu_order as $nav_link ) {
		
		//  if it's a custom option, extract number into argument
		if ( nrIsIn( 'customlink', $nav_link ) ) {
			$arg = preg_replace( '/[^0-9]/', '', $nav_link );
			$nav_link = 'customlink';
		// not custom option, argument is null
		} else {
			$arg = NULL;
		}
		
		// call the custom function
		call_user_func( 'p3_navlink_' . $nav_link, $arg );
	}
}


/* print nav home link */
function p3_navlink_home() {
	// return if option disabled
	if ( !p3_test( 'nav_home_link', 'on' ) ) return; ?>
	
	<li id='navlink_home'>
		<a href="<?php echo P3_URL ?>/" title="<?php echo P3_SITE_NAME ?>" rel="home">
			<?php p3_option( 'nav_home_link_text' ); ?>
		</a>
	</li>
<?php
}


/* print dropdown of featured galleries */
function p3_navlink_galleries() {
	if ( !p3_test( 'nav_galleries_dropdown', 'on' ) ) return;
	
	if ( is_admin() ) {
		echo '<li id="navlink_galleries">' . p3_get_option( 'nav_galleries_dropdown_title' ) . '</li>';
		return;
	}
	
	$dropdown_markup = p3_get_featured_galleries();
	if ( !$dropdown_markup ) return;
	
?>	
	<li id="navlink_galleries"><a href="#"><?php p3_option( 'nav_galleries_dropdown_title' ) ?></a>
		<ul>
			<?php echo $dropdown_markup ?>
		</ul>
	</li>
<?php
}


/* print nav portfolio link */
function p3_navlink_portfolio() {
	// return if link disabled
	if ( !p3_test('navportfolioonoff','on' ) ) return;
	
	// retrieve and sanitize stored url
	$portfolio_url = ( p3_test( 'navportfoliourl' ) ) ? p3_entered_url( 'navportfoliourl' ) : '#'; ?>
		
	<li id='navlink_portfolio'>
		<a href="<?php echo $portfolio_url ?>" target="<?php p3_option( 'portfoliotarget' ) ?>">
			<?php p3_option( 'navportfoliotitle' ) ?>
		</a>
	</li>
<?php
}


/* print nav hidden bio link */
function p3_navlink_hiddenbio() {

	// return if main bio link disabled
	if ( p3_test( 'bio_include', 'no' ) ) return;

	// return if bio is being shown on this page, only hidden on other page types
	if ( !p3_bio_is_minimized() ) return; 

	$key = ( p3_test( 'use_hidden_bio', 'yes' ) ) ? 'hidden_bio_link_text' : 'bio_pages_minimize_text'; ?>
	<li id='navlink_hiddenbio'><a id="hidden-bio"><?php p3_option( $key ) ?></a></li>
<?php
}


/* print nav pages dropdown */
function p3_navlink_pages() {
	// return if option disabled
	if ( !p3_test( 'navpagesonoff', 'on' ) ) return;
	
	// custom excluded pages
	$exclude = ( p3_test( 'nav_excluded_pages' ) ) ? '&exclude=' . trim( p3_get_option( 'nav_excluded_pages' ), ',' ) : '';
	
	// flatten out list for ipad/iphone
	if ( IS_IPAD || IS_IPHONE ) $flat = '&depth=-1';
	
	// build parameters to pass to WordPress function
	$pages_params = 'orderby=name&title_li=<a>' . p3_sanitizer( 'navpagestitle', NO_ECHO ) . '</a>' . $exclude . $flat;

	// call the WordPress function with our custom parameters
	wp_list_pages( $pages_params );
}


/* print nav recent posts dropdown */
function p3_navlink_recentposts() {
	// return if option disabled
	if ( !p3_test('navrecentpostsonoff', 'on' ) ) return;
	
	// params for WordPress function
	$recent_post_params  = 'type=postbypost&limit=' . p3_get_option( 'navrecentpostslimit' ); ?>
	
	<li id='navlink_recentposts'>
		<a><?php p3_option( 'navrecentpoststitle' ); ?></a>	
		<ul> 
			<?php wp_get_archives( $recent_post_params ); ?> 
		</ul>			
	</li>
<?php
}


/* print nav archives dropdown */
function p3_navlink_archives() {
	// return if option disabled
	if (! p3_test( 'navarchivesonoff', 'on' ) ) return;
	$threshold = ( IS_IPAD || IS_IPHONE ) ? '5000' : p3_get_option( 'nav_archives_threshold' ); ?>
	
	<li id='navlink_archives'>
		<a><?php p3_option( 'navarchivestitle' ); ?></a>
		<?php p3_archive_drop_down( 'threshold=' . $threshold ); ?> 
	</li>
<?php
}


/* print nav categories dropdown */
function p3_navlink_categories() {
	if ( !p3_test( 'navcategoriesonoff', 'on' ) ) return;
	wp_list_categories( 'title_li=<a>' . p3_sanitizer( 'navcategoriestitle', NO_ECHO ) . "</a>\n" );
}


/* print nav blogrol dropdown/s */
function p3_navlink_blogroll() {
	if ( p3_test( 'navblogrollonoff', 'off' ) ) {
		 return;
	}
	if ( function_exists( 'mylinkorder_init' ) ) {
		$order = '&orderby=order&category_orderby=order';
	}
	if ( function_exists( 'mylinkorder_list_bookmarks' ) ) {
		$list_bookmarks = 'mylinkorder_list_bookmarks';
		$order = '&orderby=order&category_orderby=order';
	} else {
		$list_bookmarks = 'wp_list_bookmarks';
	}
	$list_bookmarks( "title_before=<a>&title_after=</a>\t\t\t&category_before=<li class=\"%class\" id=\"navlink_blogroll\">&before=\t\t<li>$order" );
}


/* print nav custom option links */
function p3_navlink_customlink( $num ) {
	if ( !p3_test( 'nav_customlink' . $num . '_url' ) ) return;
	$url    = p3_entered_url( 'nav_customlink' . $num . '_url' );
	$target = p3_get_option( 'nav_customlink' . $num . '_target' );
	
	if ( $title = p3_get_option( 'nav_customlink' . $num . '_title' ) ) {
		$text_link = "<a href='$url' target='$target'>$title</a>";
	}
	
	if ( p3_image_exists( 'nav_customlink' . $num . '_icon' ) ) {
		$menu_height = p3_get_option( 'nav_link_font_size' );
		$src = p3_imageurl( 'nav_customlink' . $num . '_icon', NO_ECHO );
		$img_w = p3_imagewidth( 'nav_customlink' . $num . '_icon', NO_ECHO );
		$img_h = p3_imageheight( 'nav_customlink' . $num . '_icon', NO_ECHO );
		if ( $img_h == $img_w ) {
			$icon_h = $icon_w = $menu_height;
		} else {
			$icon_w = intval( $menu_height / ( $img_h / $img_w ) );
			$icon_h = $nav_link_font_size;
		}
		$icon_link = <<<HTML
		<a href="$url" target="$target" class="icon-link">
			<img src="$src" class="png custom-nav-icon" height="$icon_h" width="$icon_w" alt="$title" />
		</a>
HTML;
}

	if ( $text_link && $icon_link ) $add_class = ' nav-link-icon-text';
	
	 echo <<<HTML
	
	<li id='navlink_customlink{$num}' class="custom-link{$add_class}">
		$icon_link
		$text_link
	</li>
HTML;
}


/* print nav twitter dropdown */
function p3_navlink_twitter() {
	// return if option disabled
	if ( p3_test( 'twitter_onoff', 'off' ) ) return; ?>

	<li id="navlink_twitter"><a><?php p3_option( 'twitter_title' ); ?></a>
		<ul></ul>
	</li>
<?php
}


/* print nav email link */
function p3_navlink_emaillink() {
	// return if option disabled
	if ( p3_test( 'nav_emaillink_onoff', 'off' ) ) return; ?>
	
	<li id='navlink_emaillink'>
		<?php p3_js_email_encode( p3_get_option( 'nav_emaillink_address' ), p3_get_option( 'nav_emaillink_text' ) ) ?>
	</li>
<?php
}


/* print nav contact form link */
function p3_navlink_contact() {
	
	// return if option disabled
	if ( p3_test( 'contactform_yesno', 'no' ) ) return;
	$contact_link_text = p3_get_option( 'contactform_link_text' );
	$link_markup = <<<HTML
	
	<li id="navlink_contact" >
		<a id="p3-nav-contact">$contact_link_text</a>
	</li>
HTML;

	// extensible for plugins
	echo apply_filters( 'p3_contact_form_link', $link_markup );
	do_action( 'p3_contact_form_link_post' );
}


/* print nav rss link */
function p3_navlink_rss() {
	// return if option disabled
	if ( p3_test( 'nav_rss', 'off' ) ) return;
	
	// return if both the icon and the text link disabled
	if ( p3_test( 'nav_rss_use_icon', 'no' ) AND p3_test( 'nav_rss_use_linktext', 'no' ) ) return;
	
	// choose feedburner or built-in RSS url
	$nav_rsslink_address = p3_rss_url( NO_ECHO ); ?>
		
	<li id="nav-rss">
		<?php if ( p3_test( 'nav_rss_use_icon', 'yes' ) ) { ?>
			<a href="<?php echo $nav_rsslink_address ?>">
				<img src="<?php echo P3_THEME_URL ?>/images/rss-icon.png" class="png" height="<?php p3_option( 'nav_link_font_size' ) ?>" width="<?php p3_option( 'nav_link_font_size' ) ?>" alt="" />
			</a><?php } ?>
			<?php  if ( p3_test( 'nav_rss_use_linktext', 'yes' ) ) { ?><a href="<?php echo $nav_rsslink_address ?>" id="nav-rss-subscribe">
				<?php p3_option( 'nav_rsslink_text' ) ?>
			</a><?php } ?>
	</li>
<?php
}


/* print subscribe by email nav form */
function p3_navlink_subscribebyemail() {
	// return if option disabled
	if ( p3_test( 'subscribebyemail_nav', 'off' ) ) return;
	
	// full form makes IE go moo in reordering option
	if ( is_admin() ) return print( '<li id="subscribebyemail-nav">' . p3_get_option( 'subscribebyemail_nav_submit' ) . '</li>' ); ?>
	
	<li id="subscribebyemail-nav">
		<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="_blank">
			<input type="text" size="12" name="email" value="<?php p3_option( 'subscribebyemail_nav_textinput_value' ) ?>" id="subscribebyemail-nav-input" /> 		
			<input type="hidden" value="<?php echo p3_feedburner_id() ?>" name="uri" />
			<input type="hidden" name="loc" value="<?php p3_option( 'subscribebyemail_lang' ); ?>" />
			<input type="submit" value="<?php p3_option( 'subscribebyemail_nav_submit' ); ?>" />
		</form>
	</li>
<?php	
}


/* print nav search form */
function p3_navlink_search() {
	// return if option disabled
	if ( p3_test( 'nav_search_onoff', 'off' ) ) return;
	
	// full form makes IE go moo in reordering option
	if ( is_admin() ) return print( '<li id="search-top">' . p3_get_option( 'nav_search_dropdown_linktext' ) . '</li>' );
	
	// put search dropdown status into boolean variable
	$search_in_dropdown = p3_test( 'nav_search_dropdown', 'on' ); ?>
	
	<li id="search-top"><?php if ( $search_in_dropdown ) { ?>
		<a><?php p3_option( 'nav_search_dropdown_linktext' ); ?></a>
		<ul>
			<li><?php } ?>
				<form id="searchform-top" method="get" action="<?php echo P3_URL ?>">
					<div>
						<input id="s-top" name="s" type="text" value="<?php echo wp_specialchars( stripslashes( $_GET['s'] ), TRUE ) ?>" size="12" tabindex="1" />
						<input id="searchsubmit-top" name="searchsubmit-top" type="submit" value="<?php p3_option( 'nav_search_btn_text' ); ?>" />
					</div>	
				</form>
	<?php if ( $search_in_dropdown ) { ?>
			</li>
		</ul><?php } ?>
	</li>
<?php
}


/* custom function for checkbox input to exclude specific pages by ID */
function p3_exclude_pages() {
	// javascript to bind checkbox clicks with hidden input area
	$js = <<< HTML
	<script type="text/javascript" charset="utf-8">
		jQuery(document).ready(function(){
			jQuery('#nav_pages_exclude-individual-option input.checkbox-input').click(function(){
				var clicked = jQuery(this);
				var page_id = clicked.val();
				var input   = jQuery('#p3-input-nav_excluded_pages');
				var cur_val = input.val();
				// add or subtract id from hidden input area
				if ( clicked.is(':checked') ) {
					var new_val = cur_val + page_id + ',';
				} else {
					var new_val = cur_val.replace( page_id + ',', '' );
				}
				// update hidden input
				input.val( new_val );
			});
		});
	</script>
HTML;
	
	// loop through pages building params for checkbox input
	$params = 'checkbox';
	$pages = get_pages();
	foreach ( $pages as $key => $page ) {
		$params .= '|exclude_page_' . $page->ID . '|' . $page->ID . '|' . str_replace( '|', '&#124;', $page->post_title ); 
	}
	
	// return javascript, hidden text input, and checkbox markup
	$hidden   = new p3_option_box( 'nav_excluded_pages', 'text', '', '' );
	$checkbox = new p3_option_box( 'nav_excluded_pages_ids', $params, '', '' );
	return $js . $hidden->input_markup . $checkbox->input_markup;
}


/* return HTML and JS for menu re-ordering option form area */
function p3_reorder_menu_option() {
	// start output buffer
	ob_start();                           

?>	
	<ul id="reorder_menu_sortable" class="self-clear">
		<?php p3_print_nav_links(); ?>
	</ul>
	<input id="p3-input-menuorder" type="hidden" size="100" name="p3_input_menuorder" value="<?php p3_get_option( 'menuorder' ) ?>">

	<script type="text/javascript">
		jQuery(document).ready(function(){
			// remove all sub elements (already hidden by CSS)
			jQuery("#reorder_menu_sortable ul").remove();
		
			// make all link into uniform simple span
			jQuery("#reorder_menu_sortable li").each(function(){
				jQuery(this).html( jQuery(this).text() );
			});
		
			// - list that are generated by WP functions need an id
			jQuery('#reorder_menu_sortable .categories').attr('id','navlink_categories' );
			jQuery('#reorder_menu_sortable .pagenav').attr('id','navlink_pages' );
			
			// simplify complicated elements
			jQuery('#reorder_menu_sortable #nav-rss').html('<?php echo str_replace( "'", '', p3_get_option( 'nav_rsslink_text' ) ); ?>').attr('id','navlink_rss' );
			jQuery('#reorder_menu_sortable #subscribebyemail-nav').html('<?php echo str_replace( "'", '', p3_get_option( 'subscribebyemail_nav_submit' ) ); ?>').attr('id','navlink_subscribebyemail' );
			jQuery('#reorder_menu_sortable #search-top').html('<?php echo str_replace( "'", '', p3_get_option( 'nav_search_btn_text' ) ); ?>').attr('id','navlink_search' );
			jQuery('#reorder_menu_sortable #navlink_emaillink').html('<?php echo str_replace( "'", '', p3_get_option( 'nav_emaillink_text' ) ); ?>' );
			jQuery('.linkcat').text('Blogroll').not('.linkcat:eq(0)').remove();
	
			// Now initialize the sortable stuff
			jQuery("#reorder_menu_sortable").sortable({
				axis: 'x',
				opacity: 0.9,
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				update:function(){p3_sortable_menu_store();},
				start: function(event, ui) { jQuery(ui.helper).addClass('dragged' ); },
				stop: function(event, ui) { jQuery('#reorder_menu_sortable li').removeClass('dragged' ); }
			});
			
		});

		// Store current sortable state to the input element
		function p3_sortable_menu_store() {
			var order = jQuery("#reorder_menu_sortable").sortable('toArray' );
			order = jQuery.map(order, function(n,i){return (n.replace(/^navlink_/,''));});
			jQuery('#p3-input-menuorder').val( order.join(";") );
		}
	</script>
			
<?php 

	// capture all of the buffered code into variable and return
	$code = ob_get_contents();
	ob_end_clean();
	return $code;
		
} // end function p3_reorder_menu_option()


/* figures out if a "about me" minimized bio link needs to be added for advanced minimize option */
function p3_bio_is_minimized() {
	// no bio? break and return FALSE
	if ( p3_test( 'bio_include', 'no' ) ) return FALSE;

	// global minimized bio setting
	if ( p3_test( 'use_hidden_bio', 'yes' ) ) return TRUE;

	// return if bio on, but minimize feature disabled
	if ( p3_test( 'bio_pages_minimize', 'none' ) ) return FALSE;

	// bio turned on, minimizer on, check page types
	$page = p3_get_page_type();
	$function_page = ( $page == 'index' ) ? 'home' : rtrim( $page, 's' );
	if ( 
		is_callable( 'is_' . $function_page ) && 
		call_user_func( 'is_' . $function_page ) && 
		!p3_test( 'bio_' . $page, 'on' )
	) {
		return TRUE;
	}
	return FALSE;
}


/* create encoded javascript email  */
function p3_js_email_encode( $email_address, $email_link_text, $echo = TRUE, $unencoded_markup = '' ) {
	if ( !$unencoded_markup )
		$unencoded_markup = '<a href="mailto:' . $email_address . '">' . $email_link_text . '</a>';
		
	$javascript = p3_javascript_write( $unencoded_markup );
	if ( $echo ) echo $javascript;
	return $javascript;
}


/* turns a string into a javascript encoded string */
function p3_javascript_write( $unencoded ) {
	$js_string = '<script type="text/javascript" language="javascript">{document.write(String.fromCharCode(';
	$unencoded_characters = p3_str_split( $unencoded );
	foreach ( $unencoded_characters as $letter ) {
		$js_string .= ord( $letter );
		$js_string .= ',';
	}
	$js_string = rtrim( $js_string, ',' );
	$js_string .= '))}</script>';
	return $js_string;
}


/* mimics php5 str_split() for php4 */
function p3_str_split( $string, $split_length = 1 ){
    $count = strlen( $string );
    if( $split_length < 1 ){
         //  return false if split length is less than 1 to mimic php 5 behavior
        return false;
   
    } elseif( $split_length > $count ){
         //  the entire string becomes a single element in an array
        return array( $string );
    } else {
         //  split the string at desired length
        $num = ( int )ceil( $count / $split_length );
        $ret = array();
        for( $i = 0; $i < $num; $i++ ){
            $ret[] = substr( $string, $i*$split_length, $split_length );
        }
        return $ret;      
    }   
}


/* generate archive drop down. String $args like 'echo=0&limit=10' to override defaults */
function p3_archive_drop_down($args = '') {
	global $wpdb, $wp_locale;
	
	// default parameters
	$defaults = array(
		'threshold' => 6, // XX months then nested dropdown
		'limit' => '',
		'before' => '',
		'after' => '',
		'show_post_count' => false,
		'echo' => 1
	);

	// override defaults with passed argument if any
	$r = wp_parse_args( $args, $defaults );
	extract( $r, EXTR_SKIP );
	
	// turn $limit into safe SQL statement
	if ( '' != $limit ) {
		$limit = absint($limit);
		$limit = ' LIMIT '.$limit;
	}

	// Fetch list of archive, from DB or from cache if applicable
	$query = 
		"SELECT DISTINCT YEAR(post_date) AS `year`, 
		MONTH(post_date) AS `month`, 
		count(ID) as posts 
		FROM $wpdb->posts 
		WHERE 
			post_type = 'post' AND 
			post_status = 'publish' 
		GROUP BY YEAR(post_date), MONTH(post_date) 
		ORDER BY post_date DESC 
		$limit";
		
	$key = md5( $query );
	$cache = wp_cache_get( 'p3_archive_drop_down' , 'p3' );
	
	// result not cached, perform the query
	if ( !isset( $cache[ $key ] ) ) {
		$arcresults = $wpdb->get_results($query);
		$cache[ $key ] = $arcresults;
		wp_cache_add( 'p3_archive_drop_down', $cache, 'p3' );
		
	// results are cached retrieve them
	} else {
		$arcresults = $cache[ $key ];
	}
	
	// At this point we have $arcresults[XX]->year, $arcresults[XX]->month, $arcresults[XX]->posts
	// where XX goes from 0 to whatever the number of monthly archive we have
	// $arcresults looks like:
	//     [0] => stdClass Object (
	//             [year] => 2009
	//             [month] => 7
	//             [posts] => 11
	//         )
	// 
	//     [1] => stdClass Object (
	//             [year] => 2009
	//             [month] => 6
	//             [posts] => 69
	//         ) 
	
	if ( !$arcresults ) return;
		
	// First pass: loop through the months and get all the unique years
	$years = array();
	foreach( $arcresults as $arcresult ) {
		// if the month's year isn't in the year array yet, add it
		if ( !in_array( $arcresult->year, $years ) )
			$years[] = $arcresult->year;
	}

	// initialize some variables
	$afterafter  = $after;
	$currentyear = 0;
	$nest        = FALSE; // we're nesting
	$nested      = FALSE; // we're not in the middle of a nested list
	$count       = 0; // counter
	
	$output = "<ul class='p3_archives'>\n";
	
	foreach ( (array) $arcresults as $arcresult ) {
		$count++;
		$year = $arcresult->year;
		$month = $arcresult->month;
		// Need to nest sub level?
		if ($nest && ($currentyear != $year)) {
			$currentyear = $year;
			if ($nested)
				$output .= "\t</ul>\n</li>\n";
			$output .= "<li class='p3_archives_parent'><a href='".trim(get_year_link($year))."'>$year</a><ul class='p3_archives_nested'>\n";
			$nested = TRUE;
		}
		$url = get_month_link( $year, $month );
		$text = sprintf(__('%1$s %2$d'), $wp_locale->get_month($month), $year);
		if ( $show_post_count )
			$after = '&nbsp;('.$arcresult->posts.')' . $afterafter;
		$tab = ($nested) ? "\t" : '';
		$output .= "$tab<li>".trim( get_archives_link( $url, $text, 'text', $before, $after ) ) . "</li>\n";
		if ( $count >= $threshold )
			$nest = TRUE;
	}
	
	if ($nested)
		$output .= "\n</ul></li>\n";
	$output .= "</ul>\n";
		
	if ( $echo ) echo $output;
	return $output;
}


?>