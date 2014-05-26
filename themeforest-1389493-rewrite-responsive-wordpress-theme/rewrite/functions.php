<?php

//Include Scripts
add_action('init', 'ok_theme_js');
function ok_theme_js() {
	if (is_admin()) return;
	
	//jQuery Via Google
	wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
    wp_enqueue_script( 'jquery' );
	
	//Fancybox Easing
	wp_enqueue_script('fancybox_js', get_stylesheet_directory_uri() . '/includes/js/fancybox/jquery.fancybox-1.3.4.pack.js', false, false , true);
	
	//Sticky Sidebar
	wp_enqueue_script('sticky_js', get_stylesheet_directory_uri() . '/includes/js/jquery.sticky.js', false, false , true);
	
	//Tooltip
	wp_enqueue_script('tooltip_js', get_stylesheet_directory_uri() . '/includes/js/jquery.tipTip.minified.js', false, false , true);
	
	//Tabs
	wp_enqueue_script('tabs_js', get_stylesheet_directory_uri() . '/includes/js/jquery.tools.min.js', false, false , true);
	
	//Cycle
	wp_enqueue_script('cycle_js', get_stylesheet_directory_uri() . '/includes/js/jquery.cycle.all.js', false, false , true);
	
	//FlexSlider
	wp_enqueue_script('flex_js', get_stylesheet_directory_uri() . '/includes/js/flex/jquery.flexslider.js', false, false , true);
	
	//FidVid
	wp_enqueue_script('fitvid_js', get_stylesheet_directory_uri() . '/includes/js/jquery.fitvids.js', false, false , true);
	
	
}

//Add Javascript To Footer
add_action('wp_footer', 'ok_footer_scripts');
function ok_footer_scripts() { ?>
	<script type="text/javascript">
	 /* <![CDATA[  */   
	 
	var J = jQuery.noConflict();

	J(document).ready(function(){
						
		// Drop Menu
		function mainmenu(){
		J(".nav ul ").css({display: "none"}); // Opera Fix
		J(".nav li").hover(function(){
				J(this).find('ul:first').css({visibility: "visible",display: "none"}).slideDown(200);
				},function(){
				J(this).find('ul:first').css({visibility: "hidden"});
				});
		}
			
		mainmenu();
				
		// Remove Margins
		J(".flickrPhotos > li:nth-child(2n)").addClass('remove-margin');
		J('#sidebar > div').last().addClass('last-sidebar');
		
		//If it's a mobile device, remove the sticky code.
		if (
		navigator.userAgent.match(/Android/i) ||
		navigator.userAgent.match(/webOS/i) ||
		navigator.userAgent.match(/iPhone/i) ||
		navigator.userAgent.match(/iPod/i) ||
		navigator.userAgent.match(/BlackBerry/)
		) {
			//remove sticky
		} else {
			
			var sidebarht = J("#sidebar").height();
			var contentht = J("#content").height();
			
			if (sidebarht < contentht ) {
			    // Sticky Sidebar
			    J("#sticky").sticky({topSpacing:50,bottomSpacing:280,className:'sticky-side'});
			}	
		}
		
		//Tooltip
		J(".tooltip").tipTip({
		defaultPosition: "top",
		fadeIn: 100
		});
		
		// Lightbox
		J(".lightbox").fancybox({
			'titlePosition'		: 'outside',
			'overlayColor'		: '#ddd',
			'overlayOpacity'	: 0.9,
			'titleShow'			: 'false',
			'speedIn' : '1400', 
			'speedOut' : '1400'
		});
		
		// Fade Icons
		J("img.a").hover(
			function() {
			J(this).stop().animate({"opacity": "0"}, "fast");
			},
			function() {
			J(this).stop().animate({"opacity": "1"}, "fast");
		});
		
		// Tabs
		J("ul.tabs").tabs("div.panes > div");
		
		//Cycle Gallery
		J('.post-gallery').each(function(){
		    var $this = J(this);
		    $this.cycle({
		        fx:     'fade', 
		        speed:  'fast', 
		        timeout: 0, 
		        next: $this.next('.gallery-nav').find('.next2'),
		        prev: $this.next('.gallery-nav').find('.prev2'),
		        after: onAfter
		    });
		});
		
		function onAfter(curr, next, opts, fwd) {
		  var $ht = J(this).height();
		
		  //set the container's height to that of the current slide
		  J(this).parent().animate({height: $ht});
		}
		
		J('#sidebar-close').toggle(function () {
		    J("#sidebar-wrap").addClass("show-sidebar");
		}, function () {
		    J("#sidebar-wrap").removeClass("show-sidebar");
		});
		
		//Flex Slider
		J(window).load(function() {
			J('.flexslider').flexslider();
		});
		
		//FitVids
		J(".okvideo").fitVids();	
		
		
	});
	/* ]]> */  
</script>
<?php }

//Editor Styles and Shortcodes
require_once(dirname(__FILE__) . "/includes/editor/add-styles.php");
require_once(dirname(__FILE__) . "/includes/shortcodes/friendly-shortcode-buttons.php");

//Auto Feed Links
add_theme_support( 'automatic-feed-links' );

// Bring in the Widgets
require_once(dirname(__FILE__) . "/includes/widgets/twitter.php");
require_once(dirname(__FILE__) . "/includes/widgets/flickr.php");

//Custom Excerpt Limit
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}


//Add lightbox to image attachments
add_filter( 'wp_get_attachment_link', 'gallery_lightbox');
		
function gallery_lightbox ($content) {
	$galleryid = get_the_ID();
	$perma = get_attachment_link($attachment_id);
	
	// adds a lightbox to single page elements
	if(is_single() || is_page()) {
	 
	return str_replace("<a", "<a class='lightbox' rel='gallery-$galleryid' " , $content);
	
	} else {
	
	return str_replace("<a", "<a href='$perma' " , $content);
	
	}
}


// Adding Menus
add_theme_support( 'menus' );
register_nav_menu('main', 'Main Menu');
register_nav_menu('footer', 'Footer Menu');
register_nav_menu('custom', 'Custom Menu');

//Featured Image
add_theme_support('post-thumbnails');
set_post_thumbnail_size( 150, 150, true ); // Default Thumb
add_image_size( 'large-image', 690, 9999, false ); // Large Post Image
add_image_size( 'small-image', 210, 9999, false ); // Large Post Image

if ( ! isset( $content_width ) ) $content_width = 690;

// Custom comment output
function mytheme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class('clearfix'); ?> id="li-comment-<?php comment_ID() ?>">
		
		<div class="comment-block" id="comment-<?php comment_ID(); ?>">
			<div class="comment-info">
				
				
				<div class="comment-author vcard clearfix">
					<?php echo get_avatar( $comment->comment_author_email, 35 ); ?>
					
					<div class="comment-meta commentmetadata">
						<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
						<div style="clear:both;"></div>
						<a class="comment-time" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','') ?>
					</div>
				</div>
			<div style="clear:both;"></div>
			</div>
			
			<div class="comment-text">
				<?php comment_text() ?>
				<p class="reply">
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</p>
			</div>
		
			<?php if ($comment->comment_approved == '0') : ?>
			<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
			<?php endif; ?>    
		</div>
				
		<div style="clear:both;"></div>
		 
<?php
}

//Sidebar widgets
if ( function_exists('register_sidebars') )

register_sidebar(array(
'name' => 'Sidebar',
'description' => 'Widgets in this area will be shown on the sidebar of all pages.',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>'
));

register_sidebar(array(
'name' => 'Sticky Sidebar',
'description' => 'Widgets in this area will be shown on the sticky sidebar which follows users as they scroll.',
'before_widget' => '<div class="widget">',
'after_widget' => '</div>'
));

register_sidebar(array(
'name' => 'Footer Left',
'description' => 'Left column of the footer widget area.',
'before_widget' => '<div class="footer-left">',
'after_widget' => '</div>'
));

register_sidebar(array(
'name' => 'Footer Center',
'description' => 'Center column of the footer widget area.',
'before_widget' => '<div class="footer-center">',
'after_widget' => '</div>'
));

register_sidebar(array(
'name' => 'Footer Right',
'description' => 'Right column of the footer widget area.',
'before_widget' => '<div class="footer-right">',
'after_widget' => '</div>'
));


// Check for Options Framework Plugin
options_check();

function options_check()
{
  if ( !function_exists('optionsframework_activation_hook') )
  {
    add_thickbox(); // Required for the plugin install dialog.
    add_action('admin_notices', 'options_check_notice');
  }
}

// The Admin Notice
function options_check_notice()
{
?>
  <div class='updated fade'>
    <p>The Options Framework plugin is required for this theme to function properly. <a href="<?php echo admin_url('plugin-install.php?tab=plugin-information&plugin=options-framework&TB_iframe=true&width=640&height=589'); ?>" class="thickbox onclick">Install now</a>.</p>
  </div>
<?php
}

/* 
 * Helper function to return the theme option value. If no value has been saved, it returns $default.
 * Needed because options are saved as serialized strings.
 *
 * This code allows the theme to work without errors if the Options Framework plugin has been disabled.
 */

if ( !function_exists( 'of_get_option' ) ) {
function of_get_option($name, $default = false) {
	
	$optionsframework_settings = get_option('optionsframework');
	
	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];
	
	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}
		
	if ( isset($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
}

/* 
 * This is an example of how to add custom scripts to the options panel.
 * This one shows/hides the an option when a checkbox is clicked.
 */

add_action('optionsframework_custom_scripts', 'optionsframework_custom_scripts');

function optionsframework_custom_scripts() { ?>

<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#example_showhidden').click(function() {
  		jQuery('#section-example_text_hidden').fadeToggle(400);
	});
	
	if (jQuery('#example_showhidden:checked').val() !== undefined) {
		jQuery('#section-example_text_hidden').show();
	}
	
});
</script>
 
<?php
}