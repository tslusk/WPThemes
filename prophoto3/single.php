<?php get_header() ?>

<?php the_post() ?>
<div id="post-<?php the_ID() ?>" <?php p3_post_class() ?>>
	
	<div class="post-wrap self-clear content-bg">
	<div class="post-wrap-inner">
		<?php p3_post_header() ?>
	
		<div class="entry-content self-clear">
			<?php the_content() ?>
		</div>
	
		<?php p3_post_footer_meta() ?>
	
		<p id="nav-below" class="navigation self-clear content-bg">
			<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">&laquo;</span> %title' ) ?></span>
			<span class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">&raquo;</span>' ) ?></span>
		</p>
		<?php p3_comments_template() ?>
	</div><!-- .post-wrap-inner -->
	<div class="post-footer"></div>
	</div><!-- .post-wrap -->
	
</div><!-- .post -->

<?php p3_nav_sidebar_footer() ?>