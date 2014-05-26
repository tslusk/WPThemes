<?php get_header() ?>
	
<?php the_post() ?>
<div id="post-<?php the_ID() ?>" <?php p3_post_class() ?>>
	
	<div class="post-wrap self-clear content-bg">
	<div class="post-wrap-inner">
		
		<?php p3_post_header() ?>
		
		<div class="entry-content self-clear">
		
			<?php the_content() ?>
				
		</div><!-- .entry content -->  

		<?php p3_comments_template() ?>
	
	</div><!-- .post-wrap-inner -->
	<div class="post-footer"></div>	
	</div><!-- .post-wrap -->

</div><!-- #post-<?php the_ID() ?>-->
<?php p3_nav_sidebar_footer() ?>