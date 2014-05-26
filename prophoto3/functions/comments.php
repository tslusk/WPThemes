<?php
/* -------------------------------------------------- */
/* --- library of functions for comments template --- */
/* -------------------------------------------------- */


/* calls the comments_template() function when appropriate */
function p3_comments_template() {
	do_action( 'p3_comments_template_pre' );
	$continue = TRUE;
	$continue = apply_filters( 'p3_comments_template_continue', $continue );
	if ( !$continue ) return;
	
	if ( post_password_required() || ( !comments_open() && get_comments_number() < 1 ) || p3_test( 'comments_enable', 'false' ) ) return;
	$GLOBALS['withcomments'] = TRUE;	
	
	// for archive-ish pages we test the user option		
	if ( !is_single() && !is_page() && !is_home() ) {
		if ( p3_test( 'archive_comments', 'on' ) ) comments_template();
	
	// single and page and home always have comments
	} else {
		comments_template();
	}
	do_action( 'p3_comments_template_post' );
}


/* returns "comments-count-active" class to comment header when comments are shown */
function p3_comments_header_active_class( $num_comments ) {
	$active_class = ' comments-count-active';

	// no comments? no need to add class
	if ( ( $num_comments ) < 1 ) return;

	// home page
	if ( is_home() ) {
		return ( p3_test( 'comments_open_closed', 'open' ) ) ? $active_class : '';
	}
	
	// single page, comments always shown
	if ( is_single() ) {
		return $active_class;
	}
	
	// archive-type page. echo only if comments set to show
	else if ( p3_test( 'archive_comments_showhide', 'show' ) ) {
		return $active_class;
	} 
	
}


/* returns comments count text for comments header */
function p3_get_comments_count() {
	global $id;
	
	// modify the 0 comments language, apply WordPress filter
	$number = get_comments_number( $id );
	if ( $number == 0 )
		$p3_modified_number = p3_translate( 'no', NO_ECHO );
	else
		$p3_modified_number = $number;
	
	// add extra spans for minima comment layout
	if ( p3_test( 'comments_layout', 'minima' ) AND p3_test( 'comments_show_hide_method', 'text' ) AND $number > 0 ) {
		$output .= '<span class="show-text">' . p3_get_option( 'comments_minima_show_text' ) . ' </span>';
		$output .= '<span class="hide-text">' . p3_get_option( 'comments_minima_hide_text' ) . ' </span>';
	}
	
		
	$output .= apply_filters( 'comments_number', $p3_modified_number, $number );
	
	// add translated " comment" or " comments" based on number
	$output .= ' ';
	$output .= ( $number != 1 ) ? p3_translate( 'comments', NO_ECHO ) : p3_translate( 'comment', NO_ECHO );
	
	return $output;
}


/* optionally hides comments body based on page type, visibility setting, and # comments */
function p3_comments_body_display() {
	global $id;
	$hidden = ' style="display:none;"';
	
	// always hidden if no comments
	if ( get_comments_number( $id ) == 0 ) return $hidden;

	// boxy never hidden by nature
	if ( p3_test( 'comments_layout', 'boxy' ) ) return;

	// never hidden on single/page
	if ( is_single() || is_page() ) return;

	// on home page
	if ( is_home() AND p3_test( 'comments_open_closed', 'closed' ) ) return $hidden;
	
	// archive-ish pages
	if ( ( is_archive() ) || ( is_search() ) || ( is_tag() ) ) {
		if ( p3_test( 'archive_comments_showhide', 'hide' ) ) return $hidden; 
	}
	
}


/* prints individual comment */
function p3_print_a_comment() {
	$cmt_authorlink = p3_comment_author_link();
	$cmt_timestamp_left = $cmt_timestamp_right = '';
	$cmt_timestamp  = ( !p3_test( 'comment_timestamp_display', 'off' ) ) ? '<span class="comment-time">' . get_comment_date() . ' - ' . get_comment_time() . '</span>' : '';
	
	// comment author and timestamp shown ABOVE comment
	if ( p3_test ( 'comment_meta_display', 'above' ) ) {
		$cmt_top = '<div class="comment-top">' . $cmt_authorlink . $cmt_timestamp . '</div>';
	
	// comment author & timestamp shown inline with comment
	} else {
		if ( p3_test( 'comment_timestamp_display', 'right' ) ) $cmt_timestamp_right = $cmt_timestamp;
		if ( p3_test( 'comment_timestamp_display', 'left' ) )  $cmt_timestamp_left  = $cmt_timestamp;
	}
	
	// comment data, markup into variables for heredoc
	$cmt_id         = get_comment_ID();
	$cmt_class      = comment_class( array( 'self-clear', 'p3comment' ), null, null, NO_ECHO );
	$the_comment    = p3_get_the_comment( $cmt_timestamp_left );
	$gravatar       = ( p3_test( 'gravatars', 'on' ) ) ? 
		get_avatar( get_comment_author_email(), p3_get_option( 'gravatar_size' ) ) : '';

	echo <<<HTML
	<div id="comment-{$cmt_id}" $cmt_class>
		$gravatar
		$cmt_timestamp_right
		$cmt_top 		
		<div class="comment-text">
			$the_comment
		</div>
	</div>
HTML;
}


/* returns comment author link, comment, awaiting moderation text, properly filtered */
function p3_get_the_comment( $cmt_timestamp_left ) {
	global $comment;
	$comment_text   = get_comment_text() . $cmt_timestamp_left;
	$cmt_authorlink = ( p3_test( 'comment_meta_display', 'inline' ) ) ? p3_comment_author_link() : '';
	$cmt_moderation = ( $comment->comment_approved == '0' ) 
		? ' <span class="unapproved">' . p3_get_option( 'comments_moderation_text' ) . '</span>' : '';
	$comment_start = substr( $comment_text, 0, 2 );

	// comment starts with block-level html tag, insert author after tag and filter
	if ( stripos( $comment_start, '<p' ) !== FALSE OR stripos( $comment_start, '<d' ) !== FALSE ) {
		$tag_end = stripos( $comment_text, '>' );
		$comment_text = substr_replace( $comment_text, $cmt_authorlink, $tag_end + 1, 0 );
		$the_comment = apply_filters( 'comment_text', $comment_text . $cmt_moderation );
		$the_comment = apply_filters( 'comment_text', $comment_text . $cmt_moderation );
	
	// comment doesn't start with block-level html tag, prepend our author link to comment and filter
	} else {
		$the_comment = apply_filters( 'comment_text', $cmt_authorlink . $comment_text .  $cmt_moderation );
	}

	return $the_comment;
}


/* returns comment author link, with target="_blank" added, when chosen */
function p3_comment_author_link() {
	$p3_author = get_comment_author_link();
	if ( p3_test( 'comment_author_link_blank', 'blank' ) ) {
		$p3_author = str_replace( "class=", "target='_blank' class=", $p3_author );
	}
	$sep = ( p3_test( 'comment_meta_display', 'inline' ) || p3_test( 'comment_timestamp_display', 'left' ) ) ? ' <span>-</span> ' : ''; 
	$p3_author = '<span class="comment-author">' . $p3_author . $sep . '</span>';
	return $p3_author;
}


/* sanitize strings to be passed to mail program */
function p3_encode_url_for_email( $input ) {
	$encoded = str_replace( ' ', '%20', $input );
	$encoded = str_replace( '/', '%2F', $encoded );
	return trim( $encoded );
}
?>