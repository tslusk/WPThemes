<?php
/* ------------------------- */
/* ----WIDGETS OPTIONS ----- */
/* ------------------------- */

echo <<<HTML
<style type="text/css" media="screen">
	#tab-section-widgets-link .empty {
		display:none;
	}
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	jQuery('.upload-row:first').show();
});
</script>
HTML;


p3_option_header('Widget Custom Images', 'widgets' );

p3_o( 'widget_images_note', 'note', 'These upload areas work in conjunction with the <a href="' . admin_url( 'widgets.php' ) . '">widgets page</a>.  Here you can upload images that will, after uploading, be available for use in the <strong>P3 Custom Icon</strong> and <strong>P3 Sliding Twitter</strong> widgets.', 'Widget custom images' );

for ( $i = 1; $i <= MAX_CUSTOM_WIDGET_IMAGES; $i++ ) { 
	p3_image( 'widget_custom_image_' . $i, 'Widget custom image #' . $i );
}
p3_add_image_upload_btn( 'widget custom' );


?>