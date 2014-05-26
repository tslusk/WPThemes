<?php get_header() ?>
		
<?php if ( have_posts() ) : ?>
	
<div class="page-title-wrap content-bg">

	<h2 class="page-title">
		<?php p3_translate( 'search_results' ) ?> <span><?php the_search_query() ?></span>
	</h2>

</div> <!-- .page-title-wrap  -->


<?php while ( have_posts() ) : the_post() // results found ?>
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

<?php endwhile; ?>	


<?php else : // no results ?>
<div id="post-0" class="post no-results not-found">
	
	<div class="post-wrap self-clear content-bg">
	<div class="post-wrap-inner">
		
		<div class="post-header">
			<h2 class="entry-title"><?php p3_translate( 'search_notfound_header' ) ?></h2>
		</div>
	
		<div class="entry-content">
		
			<p><?php p3_translate( 'search_notfound_text' ) ?></p>
		
		</div>
	
		<form id="searchform-no-results" class="blog-search" method="get" action="<?php echo P3_URL ?>">
			<div>
				<input id="s-no-results" name="s" class="text" type="text" value="<?php the_search_query() ?>" size="40" />
				<input class="button" type="submit" value="<?php p3_translate( 'search_notfound_button' ) ?>" />
			</div>
		</form>
	
	</div><!-- .post-wrap-inner -->
	<div class="post-footer"></div>
	</div><!-- .post-wrap -->
	
</div><!-- .post -->

<?php endif; ?>	

<?php p3_nav_sidebar_footer() ?>