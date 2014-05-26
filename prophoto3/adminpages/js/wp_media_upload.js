/* -------------------------------------------------- */
/* -- javascript used on WordPress' media uploader -- */
/* -------------------------------------------------- */


jQuery(document).ready(function(){
	if ( jQuery('#tab-gallery a #attachments-count').length ) {
		// timeout gets us past the js flip to flash uploader
		setTimeout('p3_media_uploader_add_buttons()',700);
	} else {
		// warn if we're on the P3 gallery tab with no images
		if ( jQuery('li#tab-p3_gallery a.current').length || jQuery('li#tab-p3_lightbox a.current').length ) {
			jQuery('p,label,input,select').hide();
			jQuery('#p3_gallery_no_imgs_warning').show();
		}
	}
	
	// ensure enough room for tabs
	jQuery('li#tab-nextgen a').text('NextGen');
	var all_tabs = '';
	jQuery('#sidemenu li a').each(function(){
		all_tabs = all_tabs + jQuery(this).text();
	});
	if ( all_tabs.length > 90 ) jQuery('#media-upload-header').addClass('super-shrinktabs');
	else if ( all_tabs.length > 74 ) jQuery('#media-upload-header').addClass('shrinktabs');
	
	// "gallery settings" is for default WordPress galleries only, fellas
	jQuery('#gallery-settings .title')
		.html(jQuery('#gallery-settings .title').html()+' <span>(default WP galleries, not P3)</span>');
});


/* triggered while images are being crunched, adds p3 buttons */
function p3_crunching_add_buttons() {
	if ( jQuery('#appended-p3btns').length ) {
		jQuery('#appended-p3btns').remove();
	}
	p3_media_uploader_add_buttons();
	jQuery('#p3_all_images').css('display', 'inline');
}
var counter = 1;
/* insert p3 custom media buttons into iframe */
function p3_media_uploader_add_buttons() {
	if ( jQuery('#p3_all_images').length ) return;
	var p3_img_btns = '<input value="P3 Insert All" id="p3_all_images" onclick="javascript:p3_send_them_all(jQuery(this));" type="button" class="button savebutton p3_btn" /><input id="p3_lightbox" value="P3 Lightbox Gallery" onclick="javascript:p3_lightbox_gallery_click(jQuery(this));" type="button" class="button savebutton p3_btn"/><input id="p3_flash" value="P3 Flash Slideshow Gallery" onclick="javascript:p3_flash_slideshow_click(jQuery(this));" type="button" class="button savebutton p3_btn"/><a id="p3_help" target="_blank" href="http://www.prophotoblogs.com/support/about/media-options/" class="button savebutton p3_btn" title="Click for help with ProPhoto3 media options">?</a>';

	// from computer tab
	if ( jQuery('li#tab-type a.current').length ) {
		if ( jQuery('p.ml-submit').css('display') != 'none' ) {
			jQuery('.ml-submit input[name=save]').after(p3_img_btns);
		} else {
			jQuery('body').append('<span id="appended-p3btns">'+p3_img_btns+'</span>');
		}
		
	// Gallery Tab
	} else if ( jQuery('li#tab-gallery a.current').length ) {
		jQuery('input#insert-gallery').after(p3_img_btns);
		jQuery('#p3_all_images').css('display', 'inline');
	}
	counter++;
}


/* send all uploaded images to editor */
function p3_send_them_all() {
	if (jQuery('div.bar').length) { // still crunching
		jQuery('body').addClass('p3crunching');
		jQuery('#p3_all_images').addClass('clicked').css('color', 'blue').val('waiting for crunching to complete...');
		setTimeout('p3_send_them_all()', 800);
		
	} else {
		var html = '';
		jQuery('.media-item').each(function(){
			var src = jQuery('button.urlfile', this).attr('title' );
			var size = p3_extract_image_size_html( jQuery('.image-size-item:last label.help',this).text() );
			var title = jQuery('input[id*=post_title]', this).val();
			title = ( title == undefined ) ? '' : title;
			var alt = jQuery('input[id*=image_alt]', this).val();
			alt = ( alt == undefined ) ? '' : alt;
			html += '<img class="p3-insert-all size-full '+p3_insert_all_align+'" src="'+src+'" '+size+' alt="'+alt+'" title="'+title+'" />';
		});
		p3_send_to_editor( '\n\n'+html+'\n\n' );
	}
}


/* subroutine for sending data to WordPress editor */
function p3_send_to_editor( string ) {
	var win = window.opener ? window.opener : window.dialogArguments;
	if (!win) win = top;
	win.send_to_editor( string );
}


/* take user to flash gallery tab after images crunched */
function p3_flash_slideshow_click( clicked ) {
	if (jQuery('div.bar').length) { // still crunching
		jQuery('body').addClass('p3crunching');
		clicked.addClass('clicked').css('color', 'blue').val('waiting for crunching to complete...');
		setTimeout(function(){p3_flash_slideshow_click(clicked)}, 800);

	} else {
		clicked.css('width',clicked.width()+'px').attr('value', 'loading...');
		window.location.href = jQuery('li#tab-p3_gallery a').attr('href');
	}
}


/* take user to lightbox gallery tab after images crunched */
function p3_lightbox_gallery_click( clicked ) {
	if (jQuery('div.bar').length) { // still crunching
		jQuery('body').addClass('p3crunching');
		clicked.addClass('clicked').css('color', 'blue').val('waiting for crunching to complete...');
		setTimeout(function(){p3_lightbox_gallery_click(clicked)}, 800);

	} else {
		clicked.css('width',clicked.width()+'px').attr('value', 'loading...');
		window.location.href = jQuery('li#tab-p3_lightbox a').attr('href');
	}
}


/* gets width/height html out of WordPress button label */
function p3_extract_image_size_html( text ) {
	text = text.substr(1,text.length); // get rid of non-standard first parens
	var parts = text.split(text.match(/[^0-9\(\)]+/));
	return 'width="'+ parts[0].replace(/[^0-9]/g,'')+'" height="'+parts[1].replace(/[^0-9]/g,'')+'"';
}