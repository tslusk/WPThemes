<?php get_header(); 

/* get page title */

// author has no web address
if ( ( $authordata->user_url == 'http://' ) || ( $authordata->user_url == '' ) ) {
	$page_title = p3_get_option( 'translate_author_archives' ) . ' ' . $authordata->display_name;

// author has web address
} else {
	$page_title = p3_get_option( 'translate_author_archives' ) . ' <a href="' . $authordata->user_url. '">' . $authordata->display_name . '</a>';
}

the_post(); ?>

<div class="page-title-wrap content-bg">
	
	<h2 class="page-title author">
		<?php echo $page_title ?>
	</h2>
	
	<?php p3_archive_page_meta() ?>
	
</div> <!-- .page-title-wrap  -->
	
<?php rewind_posts() ?>

	
<?php while ( have_posts() ) : the_post() // begin loop  ?>
<div id="post-<?php the_ID() ?>" <?php p3_post_class() ?>>
	
	<div class="post-wrap self-clear content-bg">
	<div class="post-wrap-inner">	
		<?php p3_post_header() ?>
	
		<div class="entry-content self-clear">
	
			<?php p3_the_post() ?>
	
		</div><!-- .entry content -->  

		<?php p3_post_footer_meta() ?>

		<?php p3_comments_template() ?>		
		
	</div><!-- .post-wrap-inner -->
	<div class="post-footer"></div>
	</div><!-- .post-wrap -->

</div><!-- #post-<?php the_ID() ?>-->

<?php endwhile; // end loop ?>
	
<?php p3_nav_sidebar_footer() ?>