<?php get_header(); ?>
		
		
		
		<div id="content" class="filter-posts">
			<!-- conditional subtitles -->
			<?php if(is_search()) { ?>
				<div class="sub-title"><?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $count = $allsearch->post_count; _e(''); echo $count . ' '; wp_reset_query(); ?>Search Results for "<?php the_search_query() ?>" </div>
			<?php } else if(is_tag()) { ?>
				<div class="sub-title">Tag: <?php single_tag_title(); ?></div>
			<?php } else if(is_day()) { ?>
				<div class="sub-title"> Archive: <?php echo get_the_date(); ?></div>
			<?php } else if(is_month()) { ?>
				<div class="sub-title">Archive: <?php echo get_the_date('F Y'); ?></div>
			<?php } else if(is_year()) { ?>
				<div class="sub-title">Archive:  <?php echo get_the_date('Y'); ?></div>
			<?php } else if(is_404()) { ?>
				<div class="sub-title">404 - Page Not Found!</div>
			<?php } else if(is_category()) { ?>
				<div class="sub-title">Category: <?php single_cat_title(); ?></div>
			<?php } else if(is_author()) { ?>
				<div class="sub-title">Posts by Author: <?php the_author_posts(); ?> posts by <?php
				$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author)); echo $curauth->nickname; ?></div>		
			<?php } ?>
			
			<!-- grab the posts -->
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<div <?php post_class('post clearfix'); ?>>
				<div class="box">
					
					
						
					<!-- Grab the image attachments -->		
					<?php 
					//find images in the content with "wp-image-{n}" in the class name
					preg_match_all('/<img[^>]?class=["|\'][^"]*wp-image-([0-9]*)[^"]*["|\'][^>]*>/i', get_the_content(), $result);  
					//echo '<pre>' . htmlspecialchars( print_r($result, true) ) .'</pre>';
					$exclude_imgs = $result[1];
					
					$args = array(
						'order'          => 'ASC',
						'post_type'      => 'attachment',
						'post_parent'    => $post->ID,
						'exclude'		 => $exclude_imgs, // <--
						'post_mime_type' => 'image',
						'post_status'    => null,
						'numberposts'    => -1,
					);
					
					$attachments = get_posts($args);
					if ($attachments) {
					
					
					echo "<div class='gallery-wrap'><div class='flexslider'><ul class='slides'>";
						foreach ($attachments as $attachment) {
							echo "<li>";
							echo wp_get_attachment_link($attachment->ID, 'large-image', false, false);
							echo "</li>";
						}
					echo "</ul></div></div>"; 
					
					if(count($attachments) > 1) {
					?>
					
					<div class="gallery-nav" style="display: none;">
						<a class="next2" href="#" title="next"></a>
						<a class="prev2" href="#" title="prev"></a>
					</div>
					
					<?php } } ?>
					
					
					<?php if ( get_post_meta($post->ID, 'okvideo', true) ) { ?>
						<div class="okvideo">
							<?php echo get_post_meta($post->ID, 'okvideo', true) ?>
						</div>
					<?php } ?>
					
					<div style="clear:both;"></div>
					
					<div class="frame">
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						
						<div class="post-content">
							<?php if(is_search() || is_archive()) { ?>
								<?php the_excerpt(); ?>
							<?php } else { ?>
								<?php the_content('Read more &rarr;'); ?>
								
								<?php if(is_single()) { ?>
									<div class="pagelink">
										<?php wp_link_pages(); ?>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
					</div><!-- frame -->
					
					<!-- meta info bar -->
					<div class="bar" <?php if(is_page()) { ?>style="display:none;"<?php } ?>>
						
						<div class="bar-frame clearfix">
							<div class="bigdate">
								<?php the_time('F d'); ?> <span><?php the_time('/ Y'); ?></span>
							</div>
							
							<div class="meta-info">
								<div class="share">
									<!-- google plus -->
									<div class="share-circle">
										<a class="share-google" href="https://m.google.com/app/plus/x/?v=compose&amp;content=<?php the_title(); ?> - <?php the_permalink(); ?>" onclick="window.open('https://m.google.com/app/plus/x/?v=compose&amp;content=<?php the_title(); ?> - <?php the_permalink(); ?>','gplusshare','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;"><img src="<?php echo get_template_directory_uri(); ?>/images/share-google.png" alt="google plus share" /></a>
									</div>
									
									<!-- facebook -->
									<div class="share-circle">
										<a class="share-facebook" onclick="window.open('http://www.facebook.com/share.php?u=<?php the_permalink(); ?>','facebook','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;" href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>" title="<?php the_title(); ?>"  target="blank"><img src="<?php echo get_template_directory_uri(); ?>/images/share-facebook.png" alt="facebook share" /></a>
									</div>
									
									<!-- twitter -->
									<div class="share-circle">
										<a class="share-twitter" onclick="window.open('http://twitter.com/home?status=<?php the_title(); ?> - <?php the_permalink(); ?>','twitter','width=450,height=300,left='+(screen.availWidth/2-375)+',top='+(screen.availHeight/2-150)+'');return false;" href="http://twitter.com/home?status=<?php the_title(); ?> - <?php the_permalink(); ?>" title="<?php the_title(); ?>" target="blank"><img src="<?php echo get_template_directory_uri(); ?>/images/share-twitter.png" alt="twitter share" /></a>
									</div>
								</div><!-- share -->
								
								<div class="author">
									<strong class="title">Author</strong>
									<?php the_author_link(); ?>
								</div>
								<div class="categories">
									<strong class="title">Category</strong>
									<?php the_category(', '); ?>
								</div>
								<div class="page-comments">
									<strong class="title">Comments</strong>
									<a href="<?php the_permalink(); ?>/#comment-jump"><?php comments_number('No Comments','1 Comment','% Comments'); ?></a>
								</div>								
							  	
							  	<?php the_tags('<div class="tags">
							  		<strong class="title">Tags</strong>
							  		',', ','
							  	</div>'); ?>
						  	</div><!-- meta info -->
						</div><!-- bar frame -->
					</div><!-- bar -->
				</div><!-- box -->
			</div><!-- post-->	
			
			<?php if(is_single()) { ?>
				<!-- next and previous posts -->	
				<div class="next-prev">
					<?php previous_post_link('<div class="prev-post"><strong class="title">&larr; Previous Post</strong><span>%link</span></div>'); ?>
					<?php next_post_link('<div class="next-post"><strong class="title">Next Post &rarr;</strong><span>%link</span></div>'); ?>
				</div>	
			<?php } ?>			
			
			<?php endwhile; ?>
							
			<?php if(is_single()) { } else { ?>	
				<div class="post-nav">
					<div class="postnav-left"><?php previous_posts_link('&larr; Newer Posts') ?></div>
					<div class="postnav-right"><?php next_posts_link('Older Posts &rarr;') ?></div>
					<div style="clear:both;"> </div>
				</div><!--end post navigation-->
			<?php } ?>
			
			<?php /* END REST OF POSTS */ endif; ?>
			
			<?php if(is_404()) { ?>
				<p>Sorry, but the page you are looking for is no longer here. Please use the navigations or the search to find what what you are looking for.</p>
				
				<form action="<?php echo home_url( '/' ); ?>" class="search-form clearfix">
					<fieldset>
						<input type="text" class="search-form-input text" name="s" onfocus="if (this.value == 'Search') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search';}" value="Search"/>
						<input type="submit" value="Go" class="submit" />
					</fieldset>
				</form>
			<?php } ?>
			
			<!-- comments -->
			<?php if(is_single ()) { ?>
				<div id="comment-jump" class="comments">
					<?php comments_template(); ?>
				</div>
			<?php } ?>
		</div><!--content-->
		
		<!-- grab the sidebar -->
		<?php get_sidebar(); ?>
	
		<!-- grab footer -->
		<?php get_footer(); ?>