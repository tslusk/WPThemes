			<div id="sidebar-wrap" class="clearfix">
				<div id="sidebar-mid">
				<a id="sidebar-close" href="#" title="close"></a>
				<div id="sidebar">			
					
					
					
					
						
					<!-- grab sidebar widgets -->				
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar') ) : else : ?>		
					<?php endif; ?>
					
					<div style="clear:both;"></div>
					
					<div id="sticky" class="clearfix">
						<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sticky Sidebar') ) : else : ?>		
						<?php endif; ?>
						
						<div class="widget">
							<!-- social icons -->
							<?php if ( of_get_option('of_social_title') ) { ?>
								<h2>
									<?php echo of_get_option('of_social_title'); ?>
								</h2>
							<?php } ?>
							
							<div id="social-strip-icons">
								<?php if ( of_get_option('of_twitter_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_twitter_user'); ?>" title="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-twitter.png" alt="twitter" class="a" /></a>
										<a href="<?php echo of_get_option('of_twitter_user'); ?>" title="twitter"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-twitter-color.png" alt="twitter" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_dribbble_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_dribbble_user'); ?>" title="dribbble"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-dribbble.png" alt="dribbble" class="a" /></a>
										<a href="<?php echo of_get_option('of_dribbble_user'); ?>" title="dribbble"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-dribbble-color.png" alt="twitter" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_vimeo_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_vimeo_user'); ?>" title="vimeo"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-vimeo.png" alt="vimeo" class="a" /></a>
										<a href="<?php echo of_get_option('of_vimeo_user'); ?>" title="vimeo"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-vimeo-color.png" alt="vimeo" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_digg_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_digg_user'); ?>" title="digg"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-digg.png" alt="digg" class="a" /></a>
										<a href="<?php echo of_get_option('of_digg_user'); ?>" title="digg"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-digg-color.png" alt="digg" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_youtube_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_youtube_user'); ?>" title="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-youtube.png" alt="youtube" class="a" /></a>
										<a href="<?php echo of_get_option('of_youtube_user'); ?>" title="youtube"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-youtube-color.png" alt="youtube" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_google_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_google_user'); ?>" title="google"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-google.png" alt="google" class="a" /></a>
										<a href="<?php echo of_get_option('of_google_user'); ?>" title="google"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-google-color.png" alt="google" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_email_user') ) { ?>
									<div class="fadehover">
										<a href="mailto:<?php echo of_get_option('of_email_user'); ?>" title="email"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-email.png" alt="email" class="a" /></a>
										<a href="mailto:<?php echo of_get_option('of_email_user'); ?>" title="email"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-email-color.png" alt="email" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_facebook_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_facebook_user'); ?>" title="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-facebook.png" alt="facebook" class="a" /></a>
										<a href="<?php echo of_get_option('of_facebook_user'); ?>" title="facebook"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-facebook-color.png" alt="facebook" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_flickr_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_flickr_user'); ?>" title="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-flickr.png" alt="flickr" class="a" /></a>
										<a href="<?php echo of_get_option('of_flickr_user'); ?>" title="flickr"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-flickr-color.png" alt="flickr" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_forrst_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_forrst_user'); ?>" title="forrst"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-forrst.png" alt="forrst" class="a" /></a>
										<a href="<?php echo of_get_option('of_forrst_user'); ?>" title="forrst"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-forrst-color.png" alt="forrst" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_myspace_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_myspace_user'); ?>" title="myspace"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-myspace.png" alt="flickr" class="a" /></a>
										<a href="<?php echo of_get_option('of_myspace_user'); ?>" title="myspace"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-myspace-color.png" alt="flickr" class="b" /></a>
									</div>
								<?php } ?>
								
								<?php if ( of_get_option('of_tumblr_user') ) { ?>
									<div class="fadehover">
										<a href="<?php echo of_get_option('of_tumblr_user'); ?>" title="tumblr"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-tumblr.png" alt="flickr" class="a" /></a>
										<a href="<?php echo of_get_option('of_tumblr_user'); ?>" title="tumblr"><img src="<?php echo get_template_directory_uri(); ?>/images/icon-tumblr-color.png" alt="tumblr" class="b" /></a>
									</div>
								<?php } ?>
							</div><!-- social icons -->
						</div><!-- widget -->
					</div><!-- sticky -->
				</div><!--sidebar-->
				
				</div>
	
				
			</div><!-- sidebar wrap -->