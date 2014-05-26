/* javascript inserted into pages where the user is posting */

jQuery(document).ready(function(){
	// keep the post custom meta closed
	jQuery('#postcustom,#pagecustomdiv').addClass('closed');
	
	if ( jQuery('#p3-featured-gallery-meta-body').hasClass('has-flash-gallery') ) {
		jQuery('#p3-gallery-meta').show();
	}
});




/* clear p3 gallery meta on update so that next save doesn't reset it */
function p3_clear_gallery_meta(meta_id) {
	jQuery('#postcustomstuff input[value="'+meta_id+'"]')
		.val('')
		.parent().next().children('textarea').val('');
}


/* show the featured gallery meta box when user inserts flash gallery */
function p3_show_featured_gallery_meta_box() {
	jQuery('#p3-gallery-meta').show();
}