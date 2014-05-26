<?php get_header() ?>
		
<?php while ( have_posts() ) : the_post()  // begin loop ?>

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