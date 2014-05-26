		<div id="footer" class="clearfix">
			<div class="frame">
				<div class="bar">
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Left') ) : else : ?>		
					<?php endif; ?>
					
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Center') ) : else : ?>		
					<?php endif; ?>
					
					<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Footer Right') ) : else : ?>		
					<?php endif; ?>
				</div><!--bar-->
			</div><!--frame-->
			
			<div style="clear:both;"></div>
			
			<div class="footer-copy">
				<?php wp_nav_menu(array('menu_id' => 'menu-footer', 'theme_location' => 'footer', 'menu_class' => 'footernav')); ?>

				<p class="copyright">&copy; <?php echo date("Y"); ?> <a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a> | <?php bloginfo('description'); ?></p>
			</div>
			
		</div><!--footer-->
		<div style="clear:both;"></div>
	</div><!-- wrapper -->
	</div>

	<!-- google analytics code -->
	<?php if (of_get_option('of_tracking_code') == true) { ?>
		<!-- Google Analytics -->
		<script type="text/javascript">
		  var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		  document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		
		<script type="text/javascript">
		  try {
		    var pageTracker = _gat._getTracker("<?php echo of_get_option('of_tracking_code', 'no entry' ); ?>");
		    pageTracker._trackPageview();
		  } catch(err) {
		  }
		</script>
	<?php } ?>
	
	<?php wp_footer(); ?>

</body>
</html>