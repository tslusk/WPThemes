<?php
// Sets content and images width
if ( !isset($content_width) ) $content_width = 500;

// Add default posts and comments RSS feed links to head
if ( function_exists('add_theme_support') ) add_theme_support('automatic-feed-links');

// Enables the navigation menu ability
if ( function_exists('add_theme_support') ) add_theme_support('menus');

// Enables post-thumbnail support
if ( function_exists('add_theme_support') ) add_theme_support('post-thumbnails');

// Adds callback for custom TinyMCE editor stylesheets 
if ( function_exists('add_editor_style') ) add_editor_style();

// Support for custom headers
define('HEADER_TEXTCOLOR', '21759B');
define('HEADER_IMAGE', ''); 
define('HEADER_IMAGE_WIDTH', 918);
define('HEADER_IMAGE_HEIGHT', 172);

function modernist_header_style() {
    ?><style type="text/css">
        #header {
			height: 132px;
            background: url(<?php header_image(); ?>);
        }
		
		#header a {
		<?php
		if ( 'blank' == get_header_textcolor() ) { ?>
		    display: none;
		<?php } else { ?>
		    color: #<?php header_textcolor(); ?>;
		}
		<?php } ?>
    </style><?php
}

function modernist_admin_header_style() {
    ?><style type="text/css">
        #headimg {
            width: 878px !important;
            height: 132px !important;
			margin: 0 -20px;
			padding: 20px;
			border: 0 none !important;
        }

		#headimg h1 {
			margin: 0;
			font-family: Georgia, "Times New Roman", serif;
			font-size: 4.8em;
			font-weight: normal;
			line-height: normal;
		}
		
		#headimg a {
			color: #21759B;
			text-decoration: none;
		}
		
		#desc {
			display: none;
		}
    </style><?php 
}

if ( function_exists('add_custom_image_header') ) add_custom_image_header('modernist_header_style', 'modernist_admin_header_style');

// Registers a widgetized sidebar and replaces default WordPress HTML code with a better HTML
if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<div class="section">',
        'after_widget' => '</div>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));

// Sets the post excerpt length to 40 characters.
function modernist_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'modernist_excerpt_length' );

// Prepares a 'continue reading' link for post excerpts
function modernist_continue_reading_link() {
	return '</p><p><a href="'. get_permalink() . '">' . 'Read the rest of this entry &raquo;' . '</a></p>';
}

// Replaces [...] for (...) in post excerpts and appends modernist_continue_reading_link()
function modernist_excerpt_more($more) {
	return ' (&hellip;)' . modernist_continue_reading_link();
}
add_filter('excerpt_more', 'modernist_excerpt_more');

// Returns TRUE if more than one page exists. Useful for not echoing .post-navigation HTML when there aren't posts to page
function show_posts_nav() {
	global $wp_query;
	return ($wp_query->max_num_pages > 1);
}

// Removes inline CSS style for Recent Comments widget
function modernist_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'modernist_remove_recent_comments_style' );

// Custom commments HTML
function modernist_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <div <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
      <div class="comment-avatar">
         <?php echo get_avatar($comment,$size='54'); ?>
      </div>

 	  <div class="comment-body">
		 	<?php if ($comment->comment_approved == '0') : ?>
			<p><strong>Your comment is awaiting moderation.</strong></p>
			<?php endif; ?>
			
			<?php comment_text(); ?>
			
			<p class="comment-meta">by <?php comment_author_link(); ?> on <?php comment_date() ?> at <?php comment_time() ?>. <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?> <a href="#comment-<?php comment_ID() ?>" title="Permanent Link for this comment">#</a><?php edit_comment_link('Edit this comment', ' ', ''); ?></p>
	  </div>
<?php }

// Adds a handy 'tag-cloud' class to the Tag Cloud Widget for better styling
function modernist_tag_cloud($tags) {
	$tag_cloud = '<div class="tag-cloud">' . $tags . '</div>';
	return $tag_cloud;
}
add_action('wp_tag_cloud', 'modernist_tag_cloud');

// Footer
function modernist_footer() { ?>
		<div id="footer">
			<p>Proudly powered by <a href="http://www.wordpress.org">WordPress</a> and <a href="http://www.rodrigogalindez.com/themes/modernist/" title="Free WordPress theme">Modernist</a>, a theme by <a href="http://www.rodrigogalindez.com" title="Web Designer">Rodrigo Galindez</a>. <a href="<?php bloginfo('rss2_url'); ?>" title="Syndicate this site using RSS"><acronym title="Really Simple Syndication">RSS</acronym> Feed</a>.</p>
		</div>
	</div>
<?php } 
add_action('wp_footer', 'modernist_footer');
?>