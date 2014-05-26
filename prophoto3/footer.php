<?php
// copyright statement
$copyright = p3_option_or_val( 'custom_copyright', '&copy; ' . date( 'Y' ) . ' ' . P3_SITE_NAME );
?>
	<div id="copyright-footer" class="content-bg">
		
		<?php p3_insert_audio_player( 'bottom' ); ?>
		
		<p><?php echo $copyright ?> <?php wp_footer() ?></p>
		
	</div><!-- #copyright-footer -->
</div><!-- #inner-wrap -->
</div><!-- #main-wrap-inner -->
</div><!-- #main-wrap-outer -->
<?php p3_dropshadow_topbottom( 'bottom' ) ?>
</div><!-- #outer-wrap-centered -->

<?php p3_google_analytics() ?>

<?php p3_print_drawers() ?>

<?php p3_closing_tags() ?>