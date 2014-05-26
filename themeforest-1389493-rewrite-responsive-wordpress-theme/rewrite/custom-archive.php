<?php 
/* 
Template Name: Custom Archive
*/ 
?>

<?php get_header(); ?>
		
		<div id="content" class="filter-posts">
			
			<!-- grab the posts -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div data-id="post-<?php the_ID(); ?>" data-type="post-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> post page clearfix project">
				<div class="box">
					
					<div class="frame frame-full">
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="post-content post-content-full">
							<?php the_content(); ?>
								
							<div id="archive">
								<div class="columnize">
									<h3>By Day</h3>
									<ul>
										<?php wp_get_archives('type=daily&limit=15'); ?>
									</ul>	
								</div>
								
								<div class="columnize">
									<h3>Latest Posts</h3>
									<ul>
										<?php wp_get_archives('type=postbypost&limit=20'); ?>
									</ul>
								</div>
								
								<div class="columnize">
									<h3>Contributors</h3>
									<ul>
										<?php wp_list_authors('show_fullname=1&optioncount=1&orderby=post_count&order=DESC'); ?>
									</ul>
									
									<h3>Pages</h3>
									<ul>
										<?php wp_list_pages('sort_column=menu_order&title_li='); ?>
									</ul>
									
									<h3>Categories</h3>
									<ul>
										<?php wp_list_categories('orderby=name&title_li='); ?> 
									</ul>
								</div>
								
								<div class="columnize columnize-last">				
									<h3>By Month</h3>
									<ul>
										<?php wp_get_archives('type=monthly&limit=12'); ?>
									</ul>
									
									<h3>By Year</h3>
									<ul>
										<?php wp_get_archives('type=yearly&limit=12'); ?>
									</ul>
								</div>
							</div>
							
						</div>
					</div><!-- frame -->
					
					
				</div><!-- box -->
			</div><!--writing post-->				
			
			
			<?php endwhile; ?>
			
			<?php /* END REST OF POSTS */ endif; ?>
		
			<div style="clear:both;"> </div>
							
			<div class="post-nav">
				<div class="postnav-left"><?php previous_posts_link('&laquo; Previous Page') ?></div>
				<div class="postnav-right"><?php next_posts_link('Next Page &raquo;') ?></div>
				<div style="clear:both;"> </div>
			</div><!--end post navigation-->
			
			
			
			<!-- grab comments on single pages -->
			<?php if(is_single ()) { ?>
				<div class="comments">
					<?php comments_template(); ?>
				</div>
			<?php } ?>
		</div><!--content-->
		
		<!-- grab the sidebar -->
		<?php get_sidebar(); ?>

		<!-- grab footer -->
		<?php get_footer(); ?>
	</div><!--main-->