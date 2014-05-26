<?php  
/* ----------------------- */
/* -- comments template -- */
/* ----------------------- */
	

/* set up data */
// get numbers of pings and comments 
$ping_count = $comment_count = 0;
foreach ( $comments as $comment )
	get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;

// pull in correct author data
global $authordata;

// post id
$post_id = get_the_ID();

// post permalink
$the_permalink = apply_filters( 'the_permalink', get_permalink() );

// active class, added to comments header when there are comments, and comments shown
$active_class = p3_comments_header_active_class( $comment_count + $ping_count );

// extra div markup for boxy layout
$boxy_open_extra_divs = ( p3_test( 'comments_layout', 'boxy' ) ) ? "<div class=\"comments-header-inner\">\n\t\t<div class=\"comments-header-inner-inner\">\n" : '';
$boxy_close_extra_divs = ( p3_test( 'comments_layout', 'boxy' ) ) ? "</div>\n\t\t</div>\n" : '';

// cursor pointer when comments present
$cursor_style = ( ( $comment_count + $ping_count ) > 0  ) ? ' style="cursor:pointer;"' : '';

// author link markup
$author_link = p3_get_option( 'translate_by' ) . ' <a class="url fn n" href="' . get_author_posts_url( $authordata->ID, $authordata->user_nicename ) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a>';
$post_author = ( p3_test( 'comments_show_postauthor', 'true' ) ) ? "<p class='postedby'>$author_link</p>" : '';


// special class used by minima layout
$minima_addclass = ( p3_test( 'comments_layout', 'minima' ) AND ( $comment_count + $ping_count ) == 0 ) ? ' class="no-comments"' : '';

// show/hide comments button
$minima_show_hide_button = ( p3_test( 'comments_layout', 'minima' ) AND p3_test( 'comments_show_hide_method', 'button' ) )
						   ? "<div id='show-hide-button'></div>"
						   : '';

// formatted and filtered comment count
$comments_count = p3_get_comments_count();

// "add a comment" post-interact link markup
$add_a_comment = ( is_single() OR is_page() ) 
	? '' 
	: '<div class="addacomment post-interact-div"><div class="button-outer"><div class="button-inner"><a class="pi-link" href="' . $the_permalink . '#addcomment">' . p3_get_option( 'comments_addacomment_text' ) . '</a></div></div></div>';

// "link to this post" post-interact link markup
$link_to_this_post = ( ( !p3_test( 'comments_post_interact_display', 'custom' ) AND p3_test( 'comments_linktothispost', 'yes' ) ) OR ( p3_test( 'comments_post_interact_display', 'custom' ) AND p3_image_exists( 'comments_linktothispost_image' ) ) )
	? '<div class="linktothispost post-interact-div"><div class="button-outer"><div class="button-inner"><a class="pi-link" href="' . $the_permalink . '" title="Permalink to ' . get_the_title() . '">' . p3_get_option( 'comments_linktothispost_text' ) . '</a></div></div></div>'
	: '';

// "email a friend" post-interact link markup
$email_a_friend = ( ( !p3_test( 'comments_post_interact_display', 'custom' ) AND p3_test( 'comments_emailafriend', 'yes' ) ) OR ( p3_test( 'comments_post_interact_display', 'custom' ) AND p3_image_exists( 'comments_emailafriend_image' ) ) )
	? 	'<div class="emailafriend post-interact-div"><div class="button-outer"><div class="button-inner"><a class="pi-link" href="mailto:?subject=' . 
		p3_encode_url_for_email( p3_get_option( 'comments_emailafriend_subject' ) ) . 
		'&amp;body=' . 
		p3_encode_url_for_email( p3_get_option( 'comments_emailafriend_body' ) ) . 
		'%20' . 
		p3_encode_url_for_email( $the_permalink ) . 
		'">' . 
		p3_get_option( 'comments_emailafriend_text' ) . 
		'</a></div></div></div>'
	:	'';

// inline css display style for comments body	
$comments_body_display = p3_comments_body_display();

// sort newest comment on top, if specified
if ( p3_test( 'reverse_comments', 'true' ) ) $comments = array_reverse( $comments );

$open_class = ( comments_open() ) ? 'accepting-comments' : 'not-accepting-comments';

/* comments markup */
echo <<<HTML
<div id="entry-comments-{$post_id}" class="entry-comments {$open_class}{$active_class}">
	<div class="comments-header self-clear">
		$boxy_open_extra_divs
		<div class="comments-header-left-side-wrap self-clear">
			$post_author
			<div class="comments-count" id="comments-count-{$post_id}"{$cursor_style}>
				<div>
					<p{$minima_addclass}>
						$comments_count
					</p>
					$minima_show_hide_button
				</div>
			</div>
		</div>
		<div class="post-interact">
			$add_a_comment
			$link_to_this_post
			$email_a_friend
		</div>
		$boxy_close_extra_divs	
	</div>
	<div id="comments-body-{$post_id}" class="comments-body"{$comments_body_display}>		
HTML;

// only print this inner stuff if we have at least one comment/ping
if ( $comment_count OR $ping_count ) {   ?>
		<div class="comments-body-inner-wrap">
			<div class="comments-body-inner">
				<?php 
				// standard comments
				if ( $comment_count ) {  
					foreach ( $comments as $comment ) { 
						if ( get_comment_type() == "comment" ) p3_print_a_comment();
					}
				}

				// pings
				if ( $ping_count ) {  
					foreach ( $comments as $comment ) { 
						if ( get_comment_type() != "comment" ) p3_print_a_comment();
					}
				}
				 ?>		
			</div> <!-- .comments-body-inner -->
		</div> <!-- .comments-body-inner-wrap -->
<?php } ?>
	</div><!-- .comments-body -->
</div><!-- .entry-comments -->
<div id='addcomment-holder-<?php echo $post_id ?>' class='addcomment-holder'></div>
<?php 




/* set up data for add a comment form */
// WordPress url
$p3_wpurl = P3_WPURL;

// shown to user who is logged in
$logged_in_link = '<p id="login"><span class="loggedin">Logged in as <a href="' . $p3_url . '/wp-admin/profile.php">' . wp_specialchars( $user_identity, TRUE ) . '</a>.</span> <span class="logout"><a href="' . wp_logout_url( get_permalink() ) . '" title="Log out of this account">Log out?</a></span></p>';

// comment form top custom message
$commentform_message = p3_translate( 'commentform_message', NO_ECHO );

// explanation of required comment fields, if needed ( $req is a WordPress global var )
$req_explain = ( $req ) ? p3_translate( 'comments_required', NO_ECHO ) . ' <span class="required">*</span>' : '';

// visual indicator of required field, if needed
$req_indicator = ( $req ) ? '<span class="required">*</span>' : '';

// comment form field labels
$comments_name    = p3_translate( 'comments_name',    NO_ECHO );
$comments_email   = p3_translate( 'comments_email',   NO_ECHO );
$comments_website = p3_translate( 'comments_website', NO_ECHO );
$comments_comment = p3_translate( 'comments_comment', NO_ECHO );
$comments_button  = p3_translate( 'comments_button',  NO_ECHO );


/* add a comment form markup */
// if appropriate, display the add comment form 
if ( comments_open() && ( is_single() ) || ( is_page() ) ) { 
	echo <<<HTML

<div class="formcontainer" id="addcomment">	

	<form id="commentform" action="{$p3_wpurl}/wp-comments-post.php" method="post">

HTML;

	// user is logged in
	if ( $user_ID ) {
		echo $logged_in_link;
		
	// not logged in	
	} else {  
		echo <<<HTML

		<p id="comment-notes">$commentform_message $req_explain</p>

		<div class="cmt-name"><p><label for="author">$comments_name</label> $req_indicator</p></div>
		<div class="cmt-name">
			<input id="author" name="author" type="text" value="$comment_author" size="40" maxlength="60" tabindex="3" />
		</div>

		<div class="cmt-email"><p><label for="email">$comments_email</label> $req_indicator</p></div>	
		<div class="cmt-email">
			<input id="email" name="email" type="text" value="$comment_author_email" size="40" maxlength="60" tabindex="4" />
		</div>

		<div class="cmt-url"><p><label for="url">$comments_website</label></p></div>	
		<div class="cmt-url">
			<input id="url" name="url" type="text" value="$comment_author_url" size="40" maxlength="60" tabindex="5" />
		</div>

HTML;
	}
	
	// logged in and not logged in users both see this
	$comments_error_message = p3_get_option( 'translate_comments_error_message' );
	echo <<<HTML
		<div id="addcomment-error"><span>$comments_error_message</span></div>
		<div class="cmt-comment"><p><label for="comment">$comments_comment</label></p></div>
		<div class="cmt-comment">
			<textarea id="comment" name="comment" cols="65" rows="12" tabindex="6"></textarea>
		</div>

		<div class="cmt-submit">
			<input id="submit" name="submit" type="submit" value="$comments_button" tabindex="7" accesskey="P" />
			<input type="hidden" name="comment_post_ID" value="$post_id" />
		</div>
HTML;
	do_action( 'comment_form', $post->ID ); ?>
	</form><!-- #commentform -->

</div><!-- .formcontainer -->
<?php 
} 
?>