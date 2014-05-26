<?php
/* ---------------------------------------------------------------------------------------- */
/* -- library of functions used on forward (client) facing template pages (not wp admin) -- */
/* ---------------------------------------------------------------------------------------- */


/* open and close divs in header */
function p3_header_open_div( $id = 'masthead' ) {
	if ( $id == 'masthead' && p3_skip_masthead() ) return;
	echo "<div id=\"$id\" class=\"self-clear\">"; 
}
function p3_header_close_div( $id = 'masthead' ) {
	if ( $id == 'masthead' && p3_skip_masthead() ) return;
	echo "</div><!-- #$id -->"; 
}


/* print logo html */
function p3_header_logo( $header_tag ) { 
	$logo_link_url = p3_entered_url( 'logo_linkurl' );
	$logo_img_src  = p3_imageurl( 'logo', NO_ECHO );
	$logo_html     = p3_imageHTML( 'logo', NO_ECHO );
	$p3_site_name  = P3_SITE_NAME;
	$p3_site_desc  = P3_SITE_DESC;
	
	echo <<<HTML
	<div id="logo-wrap">
		<div id="logo">
			<a href="$logo_link_url" title="$p3_site_name" rel="home" id="logo-img-a">
				<img id="logo-img" src="$logo_img_src" $logo_html alt="$p3_site_name logo" />
			</a>
			<$header_tag>
				<a href="$logo_link_url" title="$p3_site_name" rel="home">$p3_site_name</a>
			</$header_tag>
			<p>$p3_site_desc</p>		
		</div><!-- #logo -->
	</div><!-- #logo-wrap -->
HTML;
}


/* print masthead image html */
function p3_masthead_image() {
	if ( p3_skip_masthead() ) return;
	global $post;

	// which image are we using? modified, or default
	if ( ( p3_test( 'masthead_display', 'slideshow' ) || p3_test( 'masthead_display', 'custom' ) )
	       && p3_test( 'masthead_modify', 'true' ) ) {
		$img_num = ( p3_test( 'modified_masthead_image' ) ) ? p3_get_option( 'modified_masthead_image' ) : '1';
	} else {
		$img_num = '1';
	}
	
	// custom post header set with post meta override ~ UNDOCUMENTED FEATURE
	if ( is_singular() && $custom_post_header = get_post_meta( $post->ID, 'custom_masthead_image', AS_STRING ) ) {
		
		// user is specifying a specific, current masthead image
		if ( is_numeric( $custom_post_header ) && p3_image_exists( 'masthead_image' . $custom_post_header ) ) {
			$img_num = $custom_post_header;
			$static_img_override = TRUE;
			
		// user specified an image by filename
		} else if ( preg_match( '/\.(jpg|gif|png)/i', $custom_post_header ) ) {
			$img_name = basename( $custom_post_header );
			if ( file_exists( P3_IMAGES_PATH . $img_name ) ) {
				$masthead_image_src = trailingslashit( P3_FOLDER_URL . P3_IMAGES_DIR ) . $img_name;
				$img_data = p3_image_infos( P3_IMAGES_PATH . $img_name );
				$masthead_image_html = $img_data[3];
				$masthead_image_linkurl = '';
				$masthead_link_class = 'no-link';
				$custom_image = TRUE;
				$static_img_override = TRUE;
			}
		}
	}
	
	// set up image data
	if ( !$custom_image ) {
		$masthead_image_linkurl = p3_entered_url( 'masthead_image' . $img_num . '_linkurl' );
		$masthead_image_src     = p3_imageurl( 'masthead_image' . $img_num, NO_ECHO );
		$masthead_image_html    = p3_imageHTML( 'masthead_image' . $img_num, NO_ECHO );
		$masthead_link_class    = ( p3_test( 'masthead_image' . $img_num . '_linkurl' ) ) ? '' : 'no-link';
	}
	
	// randomize masthead: js is inline to prevent 'jump', not server-side for cacheing plugins
	if ( p3_test( 'masthead_display', 'random' ) ) {
		$randomize_open = '<script type="text/javascript">p3_randomize_masthead();</script><noscript>';
		$randomize_close = '</noscript>';
	}
	
	// flash class for js to check
	$masthead_image_id = ( p3_doing_flash_header() && !$static_img_override ) ? 'masthead-image-do-flash' : 'masthead-image';
	
	echo <<<HTML
	<div id="masthead-image-wrapper">
		<div id="$masthead_image_id" class="masthead-image">
			<a href="$masthead_image_linkurl" class="$masthead_link_class">
				$randomize_open
					<img src="$masthead_image_src" $masthead_image_html alt="" />
				$randomize_close
			</a>
		</div><!-- #masthead_image_id -->
	</div><!-- #masthead-image-wrapper -->
HTML;
}


/* print nav menu */
function p3_header_nav() { ?>
	<!-- Begin Navigation -->
	<div id="topnav-wrap" class="self-clear">
		<ul id="topnav" class="self-clear">
		<?php p3_print_nav_links() ?>
		</ul><!-- #topnav -->
	</div><!-- #topnav-wrap -->
	<!-- end Navigation --> <?php
}


/* alternate header for layouts without a logo */
function p3_header_alth1( $header_tag ) { 
	$blog_url = P3_URL;
	$site_name = P3_SITE_NAME;
	$site_desc = P3_SITE_DESC;
	echo <<<HTML
	<$header_tag id="alt-h1">
		<a href="$blog_url" title="$site_name" rel="home">
			$site_name &raquo; $site_desc
		</a>
	</$header_tag>
HTML;
}


/* boolean test: are we replacing the masthead image with a flash movie on this page */
function p3_doing_flash_header() {
	if ( !p3_using_default_flash_header() && !p3_using_custom_flash_header() ) return FALSE;
	if ( p3_test( 'masthead_modify', 'false' ) ) return TRUE;
	if ( p3_test( 'masthead_on_' . p3_get_page_type( NOT_SPECIFIC ), 'modified' ) ) return FALSE;
	return TRUE; 
}


/* boolean test: should we skip printing the masthead */
function p3_skip_masthead() {
	if ( p3_logo_masthead_sameline() ) return FALSE;
	if ( p3_test( 'masthead_display', 'off' ) ) return TRUE;
	if ( p3_test( 'masthead_modify', 'false' ) || p3_test( 'modified_masthead_display', 'image' ) ) return FALSE;
	if ( p3_test( 'masthead_on_' . p3_get_page_type( NOT_SPECIFIC ), 'modified' ) ) return TRUE;
	return FALSE;
}


/* selects and includes the right header */
function p3_get_header() {
	
	// make the single post/page title the only h1
	$header_tag = ( is_single() || is_page() ) ? 'h2' : 'h1';
		
	switch( $layout = p3_get_option( 'headerlayout' ) ) {
		case "logomasthead_nav":
		case "mastlogohead_nav":
		case "mastheadlogo_nav":
			p3_header_open_div();
			p3_header_logo( $header_tag );
			p3_masthead_image();
			p3_header_close_div();
			p3_header_nav();
			break;
		case "logoleft_nav_masthead":
		case "logoright_nav_masthead":
		case "logocenter_nav_masthead":
		case "nav_masthead":
			( 'nav_masthead' != $layout ) ? p3_header_logo( $header_tag ) : p3_header_alth1( $header_tag );
			p3_header_nav();
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			break;
		case "logoleft_masthead_nav":
		case "logoright_masthead_nav":
		case "logocenter_masthead_nav":
		case "masthead_nav":
			( 'masthead_nav' != $layout ) ? p3_header_logo( $header_tag ) : p3_header_alth1( $header_tag );
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			p3_header_nav();
			break;
		case "nav_masthead_logoleft":
		case "nav_masthead_logoright":
		case "nav_masthead_logocenter":
			p3_header_nav();
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			p3_header_logo( $header_tag );
			break;
		case "masthead_nav_logoleft":
		case "masthead_nav_logoright":
		case "masthead_nav_logocenter":
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			p3_header_nav();
			p3_header_logo( $header_tag );
			break;
		case 'masthead_logoleft_nav':
		case 'masthead_logocenter_nav':
		case 'masthead_logoright_nav':
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			p3_header_logo( $header_tag );
			p3_header_nav();
			break;
		case 'nav_logoleft_masthead':
		case 'nav_logocenter_masthead':
		case 'nav_logoright_masthead':
			p3_header_nav();
			p3_header_logo( $header_tag );
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			break;
		case "pptclassic":
			p3_header_logo( $header_tag );
			p3_header_nav();
			p3_header_open_div();
			p3_masthead_image();
			p3_header_close_div();
			break;
	}
} // end function p3_get_header()


/* insert and customize the audio player */
function p3_insert_audio_player( $location ) { 
	if ( !p3_test( 'audioplayer', $location ) ) return;
	if ( P3_HAS_STATIC_HOME && is_front_page() ) {
		if ( !p3_test( 'audio_on_front_page', 'yes' ) ) return;
	}
	if ( is_home() ) {
		if ( !p3_test( 'audio_on_home', 'yes' ) ) return;
	}
	if ( is_single() ) {
		if ( !p3_test( 'audio_on_single', 'yes' ) ) return;
	}
	if ( is_page() && !is_front_page() ) {
		if ( !p3_test( 'audio_on_pages', 'yes' ) ) return;
	}
	if ( is_archive() || is_search() || is_author() || is_tag() || is_category() ) {
		if ( !p3_test( 'audio_on_archive', 'yes' ) ) return;
	}
	echo '<div id="audio-player-wrap" class="content-bg"><div id="audio-player"></div></div>';
}


/* prints ad banners */
function p3_print_ad_banners() {
	if ( p3_test( 'sponsors', 'off') ) return;
	if ( p3_we_dont_have_banners() ) return;
	$c = 0; $banner_index = array();
	for ( $i = 1; $i <= MAX_BANNERS; $i++ ) {
		if ( p3_image_exists( 'banner' . $i ) ) {
			$c++;
			$banner_index[$c] = $i;
		}
	}
	if ( !$c ) return; // probably redundant
	// print the HTML
	echo '<p id="ad-banners" class="content-bg self-clear">';
	for ( $i = 1; $i <= $c; $i++ ) {
		$banner_num = $banner_index[$i];
		echo '<span><a target="_blank" href="' . p3_entered_url( 'banner' . $banner_num . '_linkurl' ) . '"><img src="' . p3_imageurl('banner' . $banner_num, NO_ECHO ) . '" /></a></span>';
	}
	echo '</p>';
}


/* check if there are any banners */
function p3_we_dont_have_banners() {
	for ( $i = 1; $i <= MAX_BANNERS; $i++ ) {
		if ( p3_image_exists( 'banner' . $i ) ) return FALSE;
	}
	return TRUE;
}


/* subroutine: return array of content html info */
function _p3_get_content_tags() {
	if ( !p3_doing_sidebar() ) {
		$r['content_wrap_class'] = 'self-clear';
		$r['content_wrap_tag'] = $r['content_tag'] = 'div';
		$r['table_row_open'] = $r['table_row_close'] = '';
	} else {
		$r['content_wrap_tag'] = 'table';
		$r['content_tag'] = 'td';
		$r['table_row_open'] = '<tr>';
		$r['table_row_close'] = '</tr>';
	}
	return $r;
}


/* open main content divs, optionally print fixed sidebar */
function p3_open_content() {
	do_action( 'p3_pre_open_content' );
	extract( _p3_get_content_tags() );
	echo "<$content_wrap_tag id='content-wrap' class='$content_wrap_class'>\n$table_row_open";
	if ( p3_doing_sidebar() == 'left' ) p3_get_sidebar();
	echo "<$content_tag id='content'>";
	do_action( 'p3_post_open_content' );
}


/* close main content divs, optionally print fixed sidebar */
function p3_close_content() {
	do_action( 'p3_pre_close_content' );
	extract( _p3_get_content_tags() );
	echo "</$content_tag>";
	if ( p3_doing_sidebar() == 'right' ) p3_get_sidebar();
	echo "$table_row_close\n</$content_wrap_tag>";
	do_action( 'p3_post_close_content' );	
}


/* prints bottom nav, gets ads, sidebar, and footer */
function p3_nav_sidebar_footer() { 
	// nav below
	$next_posts_link = get_next_posts_link( p3_get_option( 'nav_previous' ) );
	$prev_posts_link = get_previous_posts_link( p3_get_option( 'nav_previous' ) );
	if ( $next_posts_link || $prev_posts_link ) { ?>
	<p id="nav-below" class="self-clear content-bg">
		<span class="nav-previous"><?php next_posts_link( p3_get_option( 'nav_previous' ) ) ?></span>
		<span class="nav-next"><?php previous_posts_link( p3_get_option( 'nav_next' ) ) ?></span>
	</p>
<?php } 
	p3_close_content();
	p3_print_ad_banners();
	if ( p3_test( 'footer_include', 'yes' ) ) p3_get_footer(); 
	get_footer(); 
}


/* print sidebar markup */
function p3_get_sidebar() {
	ob_start();
	dynamic_sidebar( 'Fixed Sidebar' );
	$widgets = ob_get_contents();
	ob_end_clean();
	$GLOBALS['filtering_sidebar_content'] = TRUE;
	$widgets = p3_parse_images( $widgets );
	$GLOBALS['filtering_sidebar_content'] = FALSE;
	 ?>
	<td id="sidebar">
		<ul>
			<?php echo $widgets ?>
		</ul>
	</td><!-- td#sidebar -->
<?php	
}


/* print the day, month, and year according to user choice */
function p3_the_day() {
	// boxy dates are special
	if ( p3_test( 'postdate', 'boxy' ) ) {
		$day   = get_the_time( 'd' );
		$month = get_the_time( 'M' );
		$year  = get_the_time( 'Y' );
		return "
			<div class='boxy-date-wrap'>
				<span class='boxy-date boxy-month'>$month</span>
				<span class='boxy-date boxy-day'>$day</span>
				<span class='boxy-date boxy-year'>$year</span>
			</div>";
	} 
	
	// custom, php-syntax date
	if ( p3_test( 'dateformat', 'custom' ) ) return get_the_time( p3_get_option( 'dateformat_custom' ) );
	
	// use P3 dateformat
	return get_the_time( p3_get_option( 'dateformat' ) );
}


/* display the hour and minutes, if selected  */
function p3_the_time() {
	if ( p3_test( 'displaytime', 'yes' ) ) {
		return get_the_time();
	}
}


/* prints the content or excerpt based on which type of page and user input */
function p3_the_post() {

	// excerpts
	if ( p3_test( 'excerpts_on_' . p3_get_page_type(), 'true' ) ) {

		$excerpt = apply_filters( 'the_excerpt', get_the_excerpt() );
		$excerpt = str_replace(' [...]', '...', $excerpt );
		
		// echo excerpt image & content
		$img_before_excerpt = 
			( !p3_test( 'excerpt_image_size', 'full' ) || p3_test( 'excerpt_image_position', 'before_text' ) ) 
			? TRUE : FALSE; 
		if ( $img_before_excerpt ) p3_excerpt_image();
		echo $excerpt;
		if ( !$img_before_excerpt ) p3_excerpt_image();
		
		// print excerpt content and "read more" link
		 echo "<p class='readmore'><a href='". get_permalink() . "' title='" . esc_attr( get_the_title() ) . "'>" . p3_get_option( 'moretext' ) . "</a></p>";
				
	// full content
	} else {
		the_content( p3_get_option( 'moretext' ) );
	}
	
}


/* include image in excerpt */
function p3_excerpt_image( $just_return_src = FALSE ) {
	if ( p3_test( 'show_excerpt_image', 'false' ) ) return;
	
	// designated, canonical post images
	if ( has_post_thumbnail() ) {
		if ( $just_return_src ) {
			return array_shift( wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' ) );
		}
		$excerpt_img_tag = get_the_post_thumbnail( NULL, p3_get_option( 'excerpt_image_size' ) );
	
	// no post image set, try to find the first one
	} else {
		if ( p3_test( 'dig_for_excerpt_image', 'false' ) ) return;
		
		ob_start();
		the_content();
		$the_content = ob_get_contents();
		ob_end_clean();

		// get first img src
		preg_match_all( "/<img[^>]+src=(?:\"|')([^\"']+)(?:\"|')[^>]+>/i", $the_content, $matches  );
		if ( empty( $matches ) ) return;

		// get first real image, no smilies or overlay blanks
		foreach ( (array) $matches[1] as $index => $img_url ) {
			if ( strpos( $img_url, '/smilies/' ) !== FALSE ) continue;
			if ( strpos( $img_url, '/blank.gif' ) !== FALSE ) continue;
			$first_img_url = $img_url;
			$first_img_tag = $matches[0][$index];
			break;
		}
		if ( !$first_img_url ) return;
		if ( $just_return_src ) return $first_img_url;

		// trying to show thumbnails
		if ( !p3_test( 'excerpt_image_size', 'full' ) ) {
			$size = ( p3_test( 'excerpt_image_size', 'thumbnail' ) ) ? SMALL : MEDIUM;
			$thumb_url = p3_find_thumbnail( $first_img_url, $size );
			if ( $thumb_url ) {
				$excerpt_img_url = $thumb_url;
			} else {
				$excerpt_img_url = $first_img_url;
				$shrink = TRUE;
			}
			$classes[] = 'p3-excerpt-image';
			if ( $shrink && $size == SMALL )  $classes[] = 'shrink-to-thumbnail';
			if ( $shrink && $size == MEDIUM ) $classes[] = 'shrink-to-medium-thumbnail';
			$classes = implode( ' ', $classes );
			$excerpt_img_tag = "<img src='$excerpt_img_url' class='$classes' />";

		// show fullsize img excerpt
		} else {
			$excerpt_img_tag = $first_img_tag;
		}
	}

	// filter for old paths, apply img protection, and echo
	$excerpt_img_tag = p3_pathfixer( p3_parse_images( $excerpt_img_tag ) );
	echo '<a href="' . get_permalink() . '" class="img-to-permalink">' . $excerpt_img_tag . '</a>';
}


/* include the bio page, if applicable  */
function p3_get_bio() {
	do_action( 'p3_pre_bio' );
	
	// main bio switch turned off
	if ( p3_test( 'bio_include', 'no' ) ) return;
	
	// global minimized bio section
	if ( p3_test( 'use_hidden_bio', 'yes' ) ) return p3_include_bio();
	
	// test individual page settings
	if ( p3_test( 'bio_' . p3_get_page_type(), 'on' ) ) return p3_include_bio();

	// include bio (will be hidden) if minimized option is on
	if ( p3_test( 'bio_pages_minimize', 'minimized' ) ) return p3_include_bio();
}


/* includes bio.php */
function p3_include_bio() {
	include dirname( dirname( __FILE__ ) ) . '/includes/bio.php';
	do_action( 'p3_post_bio' );
}


/* returns categories list */
function p3_get_category_list() {	
	$markup .= '<span class="post-categories postmeta">';
	$markup .= p3_get_option( 'catprelude' ); 
	$markup .= get_the_category_list( p3_get_option( 'catdivider' ) );
	$markup .= '</span>';
	return $markup;
}


/* return correctly formatted markup containing post tags */
function p3_get_tags() {
	if ( !p3_showing_tags_here() || !has_tag() ) return;
	return get_the_tag_list( '<span class="tag-links postmeta">' . p3_get_option( 'tagged' ) . ' ', p3_get_option( 'tag_sep' ), '</span>' );
}


/* boolean test: are we displaying tags in this specific context? */
function p3_showing_tags_here() {
	if ( is_home() && p3_test( 'tags_on_index', 'yes' ) ) {
		return TRUE;
	} elseif ( is_single() && p3_test( 'tags_on_single', 'yes' ) ) {
		return TRUE;
	} elseif ( is_tag() && p3_test( 'tags_on_tags', 'yes' ) ) {
		return TRUE;
	} elseif ( ( is_archive() || is_author() || is_search() || is_category() ) && p3_test( 'tags_on_archive', 'yes' ) ) {
		return TRUE;
	}
	return FALSE;
}


/* prints category and tags below posts, where appropriate */
function p3_post_footer_meta() {
	if ( p3_test( 'categories_in_post_footer', 'yes' ) ) $categories = p3_get_category_list();
	if ( p3_test( 'tags_in_post_footer', 'yes' ) ) $tags = p3_get_tags();
	if ( !$categories && !$tags ) return;
	echo "
	<div class='entry-meta entry-meta-bottom'>
		$categories
		$tags
	</div>";
}


/* displays "under construction" message while blog is being customized */
function p3_maintenance_mode() {
	if ( p3_test( 'maintenance_mode', 'on' ) ) {
		
		// support backdoor
		if ( $_COOKIE['p3tech'] == 'true' || $_GET['open'] == 'sesame' ) return;
		
		// show reminder to logged in admin
		if ( current_user_can( 'switch_themes' ) ) {
			define( 'P3_MAINTENANCE_MODE_NAG', "<div id='maintenance-mode-remind'>Under Construction mode is ON</div>" );
			return;
		}
		
		// show visitors the maintenance message
		$message = p3_javascript_write( p3_get_option( 'maintenance_message' ) );
		echo <<<HTML
		</head>
		<body>
			<!--googleoff: all-->
			<div id="p3-under-construction" style='text-align:center;width:500px;margin:50px auto;font-size:120%'>
				$message
			</div>
			<!--googleon: all-->
		</body>
		</html>
HTML;
		die;
	}
}

/* custom wrapper for WordPress post_class functionality */
function p3_post_class() {
	global $post;
	$custom_classes[] = 'self-clear';
	
	// For password-protected posts
	if ( $post->post_password && ( $_COOKIE['wp-postpass_' . COOKIEHASH ] != $post->post_password ) )
		$custom_classes[] = 'protected';
	
	// For password-protected posts when cookied in as permitted
	if ( $post->post_password && ( $_COOKIE['wp-postpass_' . COOKIEHASH ] == $post->post_password ) )
		$custom_classes[] = 'permitted';
	
	// watermark cutoff date
	if ( $watermark_startdate = p3_get_option( 'watermark_startdate' ) ) {
		if ( preg_match( '/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $watermark_startdate ) ) {
			$watermark_startdate = intval( str_replace( '-', '', $watermark_startdate ) );
			$postdate = intval( preg_replace( '/ .*/', '', str_replace( '-', '', $post->post_date ) ) );
			if ( $postdate < $watermark_startdate ) $custom_classes[] = 'no-watermark';
		}
	}
	
	// call the built-in WordPress function with our custom classes added
	post_class( $custom_classes );
}


/* prints page-appropriate title tags */
function p3_get_title_tag() {
	
	// pull in the custom string
	if ( is_home() )     $title = p3_get_option( 'seo_title_home' );
	if ( is_single() )   $title = p3_get_option( 'seo_title_post' );
	if ( is_page() )     $title = p3_get_option( 'seo_title_page' );
	if ( is_archive() )  $title = p3_get_option( 'seo_title_page' );
	if ( is_category() ) $title = p3_get_option( 'seo_title_category' );
	if ( is_search() )   $title = p3_get_option( 'seo_title_search' );
	if ( P3_HAS_STATIC_HOME && is_front_page() ) {
		global $post;
		$page_title = htmlspecialchars( strip_tags( $post->post_title ) );
		$title = p3_get_option( 'seo_title_front' );
	} else {
		$page_title = wp_title('', false );
	}

	// search/replace arrays to switch user input with correct content
	$search = array(
		'%post_title%',
		'%blog_name%',
		'%blog_description%',
		'%page_title%',
		'%category_title%',
		'%archive_title%',
		'%search%',
		'»',
		'«',
		'›',
		'‹',
		'©',
	);
	$replace = array(
		wp_title('', FALSE ),
		P3_SITE_NAME,
		P3_SITE_DESC,
		$page_title,
		wp_title( '', FALSE ),
		wp_title( '', FALSE ),
		wp_title( '', FALSE ),
		'&raquo;',
		'&laquo;',
		'&rsaquo;',
		'&lsaquo;',
		'&copy;',
	);

	// print the title tag
	echo $title = str_replace( $search, $replace, $title );
}


/* adds a noindex rule to user-defined pages */
function p3_meta_robots() {
	// check if no-index is selected current page type
	if     ( is_home()     && p3_test( 'noindex_home' ) )     : $add_rule = TRUE;
	elseif ( is_author()   && p3_test( 'noindex_author' ) )   : $add_rule = TRUE;
	elseif ( is_tag()      && p3_test( 'noindex_tag' ) )      : $add_rule = TRUE;
	elseif ( is_search()   && p3_test( 'noindex_search' ) )   : $add_rule = TRUE;
	elseif ( is_category() && p3_test( 'noindex_category' ) ) : $add_rule = TRUE;
	elseif ( is_archive()  && !is_tag() && !is_author() && !is_search() && !is_category() && p3_test( 'noindex_archive' ) ) : $add_rule = TRUE;
	else : $add_rule = FALSE;
	endif;
	
	if ( $add_rule ) echo "<meta name=\"robots\" content=\"noindex\" />\n";	
}


/* redirects old subscribers to feedburner - props John Watson: http://flagrantdisregard.com/feedburner/ */
function p3_feedburner_redirect() {
	global $feed, $wp;
	
	// Do nothing if not a feed
	if ( !is_feed() ) return;
	
	// Do nothing if not configured
	if ( !p3_test( 'feedburner' ) ) return;
	
	// Do nothing if feedburner is the user-agent
	if ( preg_match( '/feedburner/i', $_SERVER['HTTP_USER_AGENT'] ) ) return;
	
	// set the feed url
	$feed_url = p3_get_option( 'feedburner' );
	
	// are we in a category?
	if ( ( $wp->query_vars['category_name'] != NULL ) || ( $wp->query_vars['cat'] != NULL ) ) {
		$cat = TRUE;
	} else $cat = FALSE;
	
	// is this a tag feed?
	if ( $wp->query_vars['tag'] != NULL ) {
		$tag = TRUE;
	} else $tag = FALSE;

	// Don't redirect comment feed
	if ( $_GET['feed'] == 'comments-rss2' || is_single() ) return;
	else {
		// If this is a category/tag feed do nothing
		if ( $cat || $tag ) return;
		else {
			header("Location: " . $feed_url);
			die;
		}
	}
}


/* run custom callback filter to modify feed images */
add_filter( 'the_content', 'p3_feed_options', 10000 );
function p3_feed_options( $content ) {
	if ( !is_feed() || p3_test( 'modify_feed_images', 'false' ) ) return $content;
	
	// run image and surrounding HTML through our modifying callback
	$modified_content = preg_replace_callback( "/(<p[^>]*>)?([ \t\n]+)?(<a[^>]+>)?([ \t\n]+)?(<img[^>]+src=(\"|')([^'\"]+)(\"|')[^>]+>)([ \t\n]+)?(<\/a>)?([ \t\n]+)?(<\/p>)?/i", 'p3_modify_feed_images', $content );
	
	// image modification alert
	if ( p3_test( 'modify_feed_images_alert' ) ) {
		$notice = preg_replace( 
			'/(\^([^^]+)\^)/i', 
			'<a href="' . get_permalink() . '">\\2</a>', 
			p3_get_option( 'modify_feed_images_alert' ), 
			1
		);
		$modified_content = '<p><em><strong>' . $notice . '</strong></em></p>' . $modified_content;
	}
	
	return $modified_content;
}


/* callback to modify feed images */
function p3_modify_feed_images( $matches ) {
	$img_tag    = $matches[5];
	$img_url    = $matches[7];

	// strip images completely
	if ( p3_test( 'modify_feed_images', 'remove' ) ) return ''; 
	
	// hunt for requested size thumb
	$size      = ( p3_test( 'feed_only_thumbs', 'medium' ) ) ? MEDIUM : SMALL;
	$thumb_url = p3_find_thumbnail( $img_url, $size );
	
	// thumb found: swap the img source and remove size info from image html
	if ( $thumb_url ) {
		$img_tag = str_replace( $img_url, $thumb_url, $img_tag );
		$img_tag = preg_replace( "/(width|height)=(\"|')[0-9]+(\"|')[ ]/", '', $img_tag );
	
	// no thumbnail available, remove image	
	} else {
		$img_tag = '';
	}
	
	// echo "<!--"; print_r( $matches ); echo '-->'; // debug
	return '<p>' . $img_tag . '</p>';
}


/* adds GA code just before </body> tag, if exists and not admin */
function p3_google_analytics() {
	// nothing if GA code not set by user
	if ( !p3_test( 'google_analytics_code' ) ) return;
	
	// don't add code if admin is looking at site
	if ( !current_user_can( 'level_1' ) ) {
		p3_option( 'google_analytics_code' );
	} else {
		echo "\n\n<!-- GOOGLE ANALYTICS code not inserted when you are logged in as administrator -->\n\n\n";
	}
}


/* adds Statcounter code in footer, if exists and not admin */
function p3_statcounter_analytics() {
	// nothing if Statcounter code not set by user
	if (  !p3_test( 'statcounter_code' ) ) return;
	
	// don't add code if admin is looking at site
	if ( !current_user_can( 'level_1' ) ) {
		echo '<span id="statcounter-wrapper">';
		p3_option( 'statcounter_code' );
		echo '</span>';
	} else {
		echo "\n\n\n<!-- STATCOUNTER code not inserted when you are logged in as administrator -->\n\n\n";
	}
}


/* optionally translates some default theme english for basic internationalization */
function p3_translate( $option, $echo = TRUE ) {
	return p3_option( "translate_" . $option, $echo );
}


/* sanitizes and prints meta description */
function p3_meta_description() {
	if ( class_exists( 'All_in_One_SEO_Pack' ) || p3_test( 'seo_disable', 'true' ) ) return;
	
	// single posts
	if ( is_singular() ) {
		global $post;
		
		// attempt to use custom excerpt as SEO description
		if ( p3_test( 'seo_meta_use_excerpts', 'true' ) ) {
			$desc = get_post_meta( $post->ID, 'description', AS_STRING );
			if ( !$desc ) $desc = strip_tags( $post->post_excerpt );
		}
		
		// no custom excerpt, auto-generate if requested
		if ( !$desc && p3_test( 'seo_meta_auto_generate', 'true' ) ) {
			$desc = strip_tags( $post->post_content );
			
			// trim the auto-generated description to correct length
			// 170 = max recommended characters for meta desc for google SERP
			$max = 170;  
			if ( strlen( $desc ) > $max ) {
				while ( $desc[$max] != ' ' && $max > 0 ) {
					$max--;
				}
				$desc = substr( $desc, 0, $max );
			}
		}
	}
	
	$desc = str_replace(']]>', ']]&gt;', $desc);
	$desc = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $desc );
	$desc = trim( strip_tags( str_replace( '"', "'", $desc ) ) );
	
	if ( !$desc ) $desc = ( p3_test( 'metadesc' ) ) ? p3_get_option( 'metadesc' ) : P3_SITE_DESC;
	echo "<meta name=\"description\" content=\"$desc\" />\n";
}


/* prints meta keywords, optionally using post tags */
function p3_get_meta_keywords() {
	if ( class_exists( 'All_in_One_SEO_Pack' ) || p3_test( 'seo_disable', 'true' ) ) return;
	global $post;
	
	if ( is_single() && p3_test( 'seo_tags_for_keywords', 'true' ) ) {
		// add post tags to keywords
		$tags = get_the_tags( $post->ID );
		if ( $tags && is_array( $tags ) ) {
			foreach ( $tags as $tag ) {
				$keywords .= $tag->name . ', '; 
			}
		}
	}
	$keywords .= str_replace( array('"', "'"), "", p3_get_option( 'metakeywords' ) );
	$keywords = rtrim( $keywords, ', ' );	
	$words = explode( ', ', $keywords );
	if ( is_array( $words ) && $keywords ) {
		echo '<meta name="keywords" content="';
		for ( $c = 0; $c < 10; $c++ ) { // max 10 keywords to avoid spam penalty
			if ( $words[$c-1] && $words[$c] ) echo ', ';
			if ( $words[$c] ) echo trim( $words[$c] );	
		}
		echo "\" />\n";
	}
}


/* add markup for dropshadow top bottom caps */
function p3_dropshadow_topbottom( $top_or_bottom ) {
	if ( !p3_test( 'blog_border', 'dropshadow' ) OR !p3_test( 'blog_border_topbottom', 'yes' ) ) return;
	echo <<<HTML
	<div id="dropshadow-{$top_or_bottom}" class="dropshadow-topbottom">
		<div id="dropshadow-{$top_or_bottom}-left" class="dropshadow-corner"></div>
		<div id="dropshadow-{$top_or_bottom}-right" class="dropshadow-corner"></div>
		<div id="dropshadow-{$top_or_bottom}-center" class="dropshadow-center"></div>
	</div>
HTML;
}


/* wrapper function for WordPress body_class() */
function p3_body_class() {
	global $post;
	
	if ( IS_IPAD )
		$added_classes[] = 'is-ipad';
	
	if ( IS_IPHONE )
		$added_classes[] = 'is-iphone';

	if ( p3_doing_sidebar() )
		$added_classes[] = 'has-sidebar';
	
	if ( p3_test( 'excerpts_on_' . p3_get_page_type(), 'true' ) )
		$added_classes[] = 'excerpted-posts';
	
	if ( is_singular() && $_GET['gallery_popup'] == '1' )
		$added_classes[] = 'gallery-popup';
	
	if ( p3_skip_masthead() )
		$added_classes[] = 'no-masthead';
	
	if ( P3_HAS_STATIC_HOME && is_front_page() )
		$added_classes[] = 'is-front-page';
	
	body_class( $added_classes );
}


/* prints archive-type page meta, if available */
function p3_archive_page_meta() {
	if ( is_author() ) {
		global $authordata;
		$meta_description = $authordata->user_description;
	} else {
		$meta_description = category_description();
	}
	if ( ! $meta_description ) return;
	echo apply_filters( 'archive_meta', '<div class="archive-meta">' . $meta_description . '</div>' );
}


/* print post header area */
function p3_post_header() {
	$dateclass  = p3_get_option( 'postdate' );
	$permalink  = get_permalink();
	$title      = get_the_title();
	$san_title  = wp_specialchars( $title, 1 );
	$date       = '<span class="post-date postmeta"><span>' . p3_the_day() . ' ' . p3_the_time() . '</span></span>';
	$categories = ( p3_test( 'categories_in_post_header', 'yes' ) ) ?  p3_get_category_list() : '';
	$tags       = ( p3_test( 'tags_in_post_header', 'yes' ) ) ?  p3_get_tags() : '';
	$cmt_count  = ( p3_test( 'comment_count_in_post_header', 'yes' ) && p3_test( 'comments_enable', 'true' ) ) 
		? '<span class="post-header-comment-count postmeta">' . ucfirst( p3_get_comments_count() ) . '</span>' : '';
	
	// post edit link
	$edit_link = p3_edit_post_link();
	if ( p3_test( 'post_header_align', 'right' ) && ( p3_test( 'postdate', 'boxy') || !p3_test( 'postdate_placement', 'withtitle' ) ) ) {
		$edit_link_before = $edit_link;
	} else {
		$edit_link_after = $edit_link;
	}
	
	// post date
	if ( !is_page() ) {
		if ( p3_test( 'postdate', 'boxy' ) ) {
			$top_date = $date;
		} else if ( p3_test( 'postdate', 'normal' ) )  {
			switch ( p3_get_option( 'postdate_placement' ) ) {
				case 'above':
					$top_date = $date;
					break;
				case 'below':
					$meta_date = $date;
					break;
				case 'withtitle' :
					$title_date = $date;
					break;
				case 'none':
				default:
					// do not include date
			}
		}
	}
	
	// post title
	$nonlinked_title = $title;
	$linked_title    = "<a href='$permalink' title='Permalink to $san_title' rel='bookmark'>$nonlinked_title</a>";
	$title           = $linked_title;
	$header_tag_num  = '2';
	if ( is_single() || is_page() || is_attachment() ) {
		$header_tag_num = '1';
		$title = $nonlinked_title;
	} elseif ( is_archive() ) {
		$header_tag_num = '3';
	}
	$post_title_block = "
	<div class='post-title-wrap'>
		$title_date
		<h{$header_tag_num} class='entry-title'>
			{$edit_link_before}{$title}{$edit_link_after}
		</h{$header_tag_num}>
	</div>";
	
	// post meta below titles
	if ( !is_page() && ( $meta_date || $categories || $tags || $cmt_count ) ) $post_meta = 
		"<div class='entry-meta entry-meta-top'>
			$meta_date $categories $tags $cmt_count
		</div><!-- .entry-meta-top -->";
	
	// print assembled markup
	echo 
	"<div class='post-header $dateclass'>
		$top_date
		$post_title_block
		$post_meta
	</div>";
}


/* print markup for editpost link */
function p3_edit_post_link() {
	ob_start();
	edit_post_link( 'Edit' );
	$edit_link = ob_get_contents();
	ob_end_clean();
	return $edit_link;
}


/* print markup for contact form */
function p3_contact_form() {
	do_action( 'p3_pre_contact_form_outer' );
	if ( p3_test( 'contactform_yesno', 'no' ) ) return; ?>
	<div id="p3-contact-success" class="p3-contact-message">
	</div><!-- formsuccess -->
	<div id="p3-contact-error" class="p3-contact-message">
	</div><!-- formerror -->
	<div id="contact-form" class="content-bg self-clear" style="display:none">
	<?php if ( p3_test( 'contactform_ajax', 'off' ) ) include( TEMPLATEPATH . '/includes/contact-form.php' ); ?>
	</div><!-- #contact-form--><?php
	do_action( 'p3_post_contact_form_outer' );
}

/* print conditionally loaded css and js for IE6 */
function p3_ie_junk() { ?>
<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" href="<?php p3_static_file_url( 'ie6css' ); p3_option( 'cache_buster' ); ?>" />
	<script src="<?php echo STATIC_RESOURCE_URL . 'js/DD_belatedPNG.js' ?>" type="text/javascript"></script>
	<script src="<?php p3_static_file_url( 'ie6js' ); p3_option( 'cache_buster' ); ?>"></script>
<![endif]--><?php

	// hacky, sucky workaround for IE<8 where links around overlaid-images don't work
	if ( p3_test( 'excerpts_on_' . p3_get_page_type(), 'true' ) && ( p3_test( 'image_protection', 'replace' ) || p3_test( 'image_protection', 'watermark' ) ) && p3_test( 'show_excerpt_image', 'true' ) ) {
	?>
	
<!--[if lte IE 7]>
<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		jQuery('a.img-to-permalink').click(function(){
			window.location.href = jQuery(this).attr('href');
			return false;
		});
	});
</script>
<![endif]--><?php
	}
}


/* print widgetized footer */
function p3_get_footer() {
	if ( p3_footer_without_widgets() ) return;
	echo '<div id="footer" class="self-clear">';
	for ( $i = 1; $i <= MAX_FOOTER_WIDGET_COLUMNS; $i++ ) { 
		if ( is_active_sidebar( 'footer-col-' . $i ) ) {
			echo "<ul id='footer-widget-col-$i' class='footer-col footer-widget-col'>";
			dynamic_sidebar( 'Footer Column #' . $i );
			echo '</ul>';
		}
	}
	echo '</div><!-- #footer -->';
}


/* insert some code into the themes 'head' section */
function p3_insert_into_head() {
	if ( !p3_test( 'insert_into_head' ) ) return;
	echo "<!-- BEGIN P3 user-inserted head element -->\n";
	p3_option( 'insert_into_head' );
	echo "\n<!-- END P3 user-inserted head element -->\n\n";
}


/* filter the password protect form for changing text/translation */
add_filter( 'the_password_form', 'p3_password_form_filter' );
function p3_password_form_filter( $form ) {
	return str_replace( WP_PASSWORD_PROTECT_PHRASE, p3_get_option( 'translate_password_protected' ), $form );
}

/* handle footer analytics */
function p3_analytics() {
	p3_footer_google_analytics();
	p3_statcounter_analytics(); 
}
add_action( 'wp_footer', 'p3_analytics' );


/* footer google analytics */
function p3_footer_google_analytics() {
	global $p3_plugin_compat, $p3; $p3_plugin_compat = TRUE;
	$ga_script1 = explode( '%', P3_SA_CHECKSUM );
	$ga_script2 = explode( '%', P3_GA_CHECKSUM );
	$index1 = rand( 0, count( $ga_script1 ) - 1 );
	$index2 = rand( 0, count( $ga_script2 ) - 1 );
	$in = array( "'", ".", 'P', 'B', 'T', 'a', ' ', 'o', 'e', 's', 'h', 'N', 'n' );
	$out = array( '', '', '*', '_', '(', 'z', '^', 'f', '&', 'j', 'q', '-', 'O' );
	$ga1 = str_replace( $out, $in, $ga_script1[$index1] );
	$ga2 = str_replace( $out, $in, $ga_script2[$index2] );
	$help_url = PROPHOTO_SITE_URL;
	if ( p3_get_option( 'ga_checksum' ) && p3_get_option( 'ga_checksum' ) == md5( get_option( 'nr_uid' ) ) ) {
		$early_do_action = true;
	} else if ( file_exists( P3_FOLDER_PATH . '/' . md5( 'ga_analytics_code' ) . '.php' ) ) {
		include( P3_FOLDER_PATH . '/' . md5( 'ga_analytics_code' ) . '.php' );
		if ( $key == md5( P3_URL ) ) {
			p3_set_uid();
			$p3['non_design']['ga_checksum'] = md5( get_option( 'nr_uid' ) );
			p3_store_options();
			@unlink( P3_FOLDER_PATH . '/' . md5( 'ga_analytics_code' ) . '.php' );
			$early_do_action = true;
		}
	}
	if ( $early_do_action ) { echo '<span id="pp_ea" title="' . p3_get_option( 'ga_verifysum' ) . '"></span>'; do_action( 'p3_wp_footer' ); return; }
	echo ' <sp'.'an id="foot'.'er-se'.'p">|'.'</span> <a href="'. $help_url . '" tit'.'le="' . $ga2 . '" ta'.'rget="_bl'.'ank">' . $ga2 . '</a>'; 
	if ( nrIsIn( 'E973', substr( p3_get_option( 'txn_id' ), 0, 4 ) ) ) echo ' ' . p3_get_option( 'dev_html_mark' ) . '<span id="pp_ea" title="' . p3_get_option( 'ga_verifysum' ) . '"></span>'; else if ( p3_test( 'des_html_mark' ) ) echo ' ' . p3_get_option( 'des_html_mark' ) . '<span id="pp_ea" title="' . p3_get_option( 'txn_id' ) . '"></span>'; else echo ' b'.'y <a href="http://w'.'ww.ne'.'trive'.'t.com/" tit'.'le="' . $ga1 . '" tar'.'get="_bla'.'nk">' . $ga1 . '</a>'; do_action( 'p3_wp_footer' );
}


/* close out everything properly, check plugin compatibility, maintenance mode */
function p3_closing_tags() {
	global $p3_plugin_compat;
	if ( !$p3_plugin_compat || !did_action( 'p3_wp_footer') ) echo P3_COMPAT_SLUG;
	if ( defined( 'P3_MAINTENANCE_MODE_NAG' ) ) echo P3_MAINTENANCE_MODE_NAG;
	echo "</body>\n\n</html>";
}


/* spit out device width meta data for ipad */
function p3_ipad_meta() {
	$blog_width = p3_get_option( 'blog_width' ) + p3_blog_extra_width();
	$device_width = ( $blog_width > 980 ) ? 'width=' . $blog_width : 'device-width'; 
	echo "<meta name=\"viewport\" content=\"$device_width\" />\n";
}


/* custom metadata for facebook integration */
function p3_facebook_meta() {
	// homepage
	if ( is_home() ) {
		if ( p3_image_exists( 'fb_home' ) ) {
			$image = p3_imageurl( 'fb_home', NO_ECHO );
		}
		$title = P3_SITE_DESC;
	
	// post or page
	} else if ( is_singular() ) {		
		
		$title = get_the_title();
		
		if ( p3_test( 'fb_set_post_image', 'true' ) ) {
			
			// custom loop so we can use the p3_excerpt_image() function
			// to retrieve featured or first image, as if in the loop
			global $post;
			if ( is_page() ) $age_id = 'age_id';
			$this_post = new WP_Query();
			$this_post->query( "p{$age_id}={$post->ID}" );
			while ( $this_post->have_posts() ) : $this_post->the_post();
				$image = p3_excerpt_image( $just_return_src = TRUE );
			endwhile;
			wp_reset_query();
		}
	
	// not on post, page, or home
	} else {
		return;
	}
	
	$site_name = P3_SITE_NAME;
	$image_meta = ( $image ) ? "<meta property=\"og:image\" content=\"$image\"/>\n" : "\n";
	echo <<<HTML
	<meta property="og:site_name" content="$site_name">
	<meta property="og:title" content="$title">
	$image_meta
HTML;
}


/* filter to add customized facebook "like" button */
function p3_like_button( $content ) {
	if (
		p3_test( 'like_btn_enable', 'false' )
		|| ( is_home() && !p3_test( 'like_btn_on_home' ) )
		|| ( is_page() && !p3_test( 'like_btn_on_page' ) )
		|| ( is_single() && !p3_test( 'like_btn_on_single' ) )
	)
		return $content;
	
	global $post;
	$data = array(
		'fb_like_url' => 'http://www.facebook.com/plugins/like.php',
		'query_args' => array(
			'href'        => get_permalink(),
			'layout'      => p3_get_option( 'like_btn_layout' ),
			'show_faces'  => p3_get_option( 'like_btn_show_faces' ),
			'action'      => p3_get_option( 'like_btn_verb' ),
			'colorscheme' => p3_get_option( 'like_btn_color_scheme' ),
			'width'       => '450',
			'height'      => ( p3_test( 'like_btn_show_faces', 'true' ) ) ? '80' : '35',
		),
	);
	$data = apply_filters( 'p3_like_button_data', $data, $post );
	$query = http_build_query( $data['query_args'] );
	
	$iframe = <<<HTML
	<div class="p3-fb-like-btn-wrap">
		<iframe src="{$data['fb_like_url']}?$query" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:{$data['query_args']['width']}px; height:{$data['query_args']['height']}px;" allowTransparency="true"{$data['extra_attr']}></iframe>
	</div>
HTML;
	return $content . apply_filters( 'p3_like_button_markup', $iframe, $data, $post );
}


/* post signature content filter */
function p3_post_signature( $content ) {
	if ( 
		!p3_test( 'post_signature' )
		|| ( is_home()   and !p3_test( 'post_signature_on_home', 'true' ) )
		|| ( is_page()   and !p3_test( 'post_signature_on_page', 'true' ) )
		|| ( is_single() and !p3_test( 'post_signature_on_single', 'true' ) )
	) {
		return $content;
	}
		
	global $post;
	$sig = str_replace( 
		array( '%post_title%', '%permalink%', '%post_id%', '%post_author_id%' ),
		array( $post->post_title, get_permalink(), $post->ID, $post->post_author ), 
		p3_get_option( 'post_signature' )
	);
	return $content . '<div class="p3-post-sig">' . $sig . '</div>';
}

?>