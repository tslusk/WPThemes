<?php get_header() ?>

<?php

/* look for custom 404, or use default */
$custom404 = new WP_Query();
$custom404->query( array( 'post_type' => 'page', 'name' => 'custom-404' ) );
wp_reset_query();
if ( $custom404->post != '' ) {
	$entry_title = $custom404->post->post_title;
	$entry_content = apply_filters( 'the_content', $custom404->post->post_content );
} else {
	$entry_title = p3_translate( '404_header', NO_ECHO );
	$entry_content = '<p>' . p3_translate( '404_text', NO_ECHO ) . '</p>';
}
 
?>

<div id="post-0" class="error404 post no-results not-found">
	<div class="post-wrap self-clear content-bg">
		<div class="post-wrap-inner">
			<div class="post-header">
				<div class="post-title-wrap">
					<h2 class="entry-title"><?php echo $entry_title ?></h2>
				</div>
			</div>
	
			<div class="entry-content">
				<?php echo $entry_content ?>
			</div>
	
			<form id="searchform-no-results" class="blog-search" method="get" action="<?php echo P3_URL ?>">
				<div>
					<input id="s-no-results" name="s" class="text" type="text" value="<?php the_search_query() ?>" size="40" />
					<input class="button" type="submit" value="<?php p3_translate( 'search_notfound_button' ) ?>" />
				</div>
			</form>
		</div><!-- .post-inner -->
		<div class="post-footer"></div>
	</div><!-- .post-wrap -->	
</div><!-- .post -->

<?php p3_nav_sidebar_footer(); ?>