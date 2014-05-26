/* Scripts for the Upload Images iframed popup */

function p3_sendtopage(url, shortname, width, height, size) {
	var win = window.opener ? window.opener : window.dialogArguments;
	if (!win) win = top;
	win.p3_update_theme_image(url, shortname, width, height, size);
}

jQuery(document).ready( function() {
	// Modify the default <h3> element
	jQuery('h3').html('Select a file to upload').css('display','block' );
	// remove the "Save all changes" button
	jQuery('input').filter(function(){return jQuery(this).attr('name')=='save';}).remove(); 
});
