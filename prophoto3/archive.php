<?php get_header(); 

/* get context-appropriate page title */
// daily archive
if ( is_day() ) {
	$prefix = 'Daily Archives:';
	$time   = get_the_time( get_option( 'date_format' ) );

// monthly archive
} elseif ( is_month() ) {
	$prefix = p3_translate( 'archives_monthly', NO_ECHO );
	$time   = get_the_time( 'F Y' );

// yearly archiv3	
} elseif ( is_year() ) {
	$prefix = p3_translate( 'archives_yearly', NO_ECHO );
	$time   = get_the_time( 'Y' );

// fallback for paged archives? not sure exactly why this is here
} elseif ( isset($_GET['paged']) && !empty($_GET['paged']) ) {
	$prefix = p3_translate( 'blog_archives' );
	$time   = '';
}
// assemble the page title
$archive_page_title = $prefix . ' ' . $time;

?>
	
<?php the_post() ?>

<div class="page-title-wrap content-bg">
	
	<h2 class="page-title"><?php echo $archive_page_title ?></h2>
	
</div> <!-- .page-title-wrap  -->

<?php rewind_posts() ?>
		
<?php while ( have_posts() ) : the_post() // begin loop ?>

<div id="post-<?php the_ID() ?>" <?php p3_post_class() ?>>
	<div class="post-wrap self-clear content-bg">
	<div class="post-wrap-inner">
		<?php p3_post_header() ?>

		<div class="entry-content self-clear">
	
			<?php p3_the_post() ?>
	
		</div><!-- .entry content -->  

		<?php p3_post_footer_meta() ?>

		<?php p3_comments_template() ?>		
	</div><!-- .post-wrap inner-->
	<div class="post-footer"></div>
	</div><!-- .post-wrap -->
		
</div><!-- #post-<?php the_ID() ?>-->

<?php endwhile; // end loop ?>	
	
<?php p3_nav_sidebar_footer() ?>