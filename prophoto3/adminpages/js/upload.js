/* Scripts for the Options page : upload functions only */

// what: url, where: shortname
function p3_update_theme_image(url, shortname, width, height, size, show_reset) {
	/* debug *///alert('url:'+url+' shortname:'+shortname+' width:'+width+' height:'+height+' size:'+size+' show_reset:'+show_reset);
	
	// some basic facts about this image
	var img_is_very_small = ( ( parseInt(width) + parseInt(height) ) < 50 ) ? true : false;
	var upload_row = jQuery('#upload-row-'+shortname);
	var is_bg_img_group = ( upload_row.hasClass('bg-option-group') ) ? true : false;
	var image_text = ( is_bg_img_group ) ? 'Background Image' : 'Image';
	if ( upload_row.hasClass('file') ) image_text = 'File';
	if ( upload_row.hasClass('nav-icon-link') ) image_text = 'Icon';
		
	
	/* image display update */
	if (jQuery('#p3_image_'+shortname).length) {
		var max_size = ( is_bg_img_group ) ? 394 : 568;
		jQuery('#p3_image_'+shortname).fadeOut(250, function() {
			
			// fade in new image
			jQuery(this)
				.attr('src', url+'?' + Math.random())
				.fadeIn(250);
			
			// shrink images that are too larg
			if (width >= max_size) {
				jQuery(this).attr('width', max_size);
				jQuery('#p3_imginfos_'+shortname+' .p3_widthmsg')
					.show()
					.html("Not shown <a href=\"" +url + "\">fullsize</a>" );
			
			// set actual width for images not too large
			} else {
				jQuery(this).attr('width', width);
				jQuery('#p3_imginfos_'+shortname+' .p3_widthmsg')
					.hide().html('');
			}
			
			// trigger change event that other functions hook to
			jQuery('#p3_image_'+shortname).change(); 	
		});
	}
		
	// update the misc file
	if (jQuery('#upload-row-'+shortname).length) {
		file_name = url.substring(url.lastIndexOf("/")+1,url.length);
		jQuery('#upload-row-'+shortname+' .p3_upload_misc p a')
			.attr('href', url)
			.text(file_name);
	};


	/* stats box data update */
	jQuery('#p3_imginfos_'+shortname+' .p3_imginfos_width').html(width);
	jQuery('#p3_imginfos_'+shortname+' .p3_imginfos_height').html(height);
	jQuery('#p3_imginfos_'+shortname+' .p3_imginfos_size').html(size);
	jQuery('#p3_imginfos_'+shortname+' .p3_imginfos_url a').attr('href', url);
	
	
	/* recommendations update */
	if (jQuery('#p3_recommendation_'+shortname).length) {
		
		// in #p3_masthead_image_shortname: update classname of p3_recommended p3_recommended:W:H
		var actual = jQuery('#p3_recommendation_'+shortname+' .p3_recommended').attr('class' );
		actual = actual.replace(/actual:[0-9]+:[0-9]+/, 'actual:'+width+':'+height);
		jQuery('#p3_masthead_image_'+shortname+' .p3_recommended').attr('class', actual);
		
		// Update the recommendation display
		var should_w = jQuery('#p3_recommendation_'+shortname+' .p3_recommended_width').text();
		var should_h = jQuery('#p3_recommendation_'+shortname+' .p3_recommended_height').text();
		if (
			(should_w && width == should_w && should_h && height == should_h)
			||
			(should_w && width == should_w)
			||
			(should_h && height == should_h)
		) {
			var displaygood = '';
			var displaybad = 'none';		
		} else {
			var displaygood = 'none';
			var displaybad = '';
		}
		jQuery('#p3_recommendation_'+shortname+' .p3_recommended .p3_fh_dimensions_ok').css('display', displaygood);
		jQuery('#p3_recommendation_'+shortname+' .p3_recommended .p3_fh_dimensions_notok').css('display', displaybad);
	}
	
	// hide the "no default image" msg and make sure image info is shown
	jQuery('#p3_imginfos_'+shortname).css('display', 'block' );
	jQuery('#p3_noimg_'+shortname).css('display', 'none' );
		
	
	/* DELETING an image routines */
	if ( show_reset == false ) {
		upload_row.removeClass('is-png');
		jQuery('#p3_reset_button_'+shortname).css('display','none');
		jQuery('#p3_noimg_'+shortname).css('display', 'block');
		
		// deleting an image that has NO DEFAULT image, i.e, an optional image
		if ( jQuery('#p3_imginfos_'+shortname).hasClass('nodefault') || upload_row.hasClass('nodefault') ) {
			upload_row.addClass('no-img').removeClass('has-file');
			upload_row.removeClass('very-small-image');
			jQuery('a.p3_upload_button', jQuery('#upload-row-'+shortname) ).text('Upload '+image_text);
			jQuery('#p3_imginfos_'+shortname).css('display', 'none' );
			jQuery('#p3_recommendation_'+shortname+' .p3_fh_dimensions_notok').css('display', 'none' );
			jQuery('.p3_upload_misc', upload_row).hide();
			
		// deleting an image that DOES have a default
		} else {
			// toggle class for very small images
			if ( img_is_very_small ) upload_row.addClass('very-small-image');
			else upload_row.removeClass('very-small-image');
		}
		
		// hide background image options because image has been deleted
		jQuery('#bg-img-options-'+shortname).hide();
		
	
	/* UPLOADING or replacing image routines */
	} else {
		
		// toggle class for .png files
		if (/.png/.test(url)) upload_row.addClass('is-png');
		else upload_row.removeClass('is-png');
		
		// toggle class for very small images
		if ( img_is_very_small ) upload_row.addClass('very-small-image');
		else upload_row.removeClass('very-small-image');
		
		// file extensions
		if ( upload_row.hasClass('file') ) {
			var ext = url.replace(/.+\./,'');
			jQuery('#p3_imginfos_'+shortname+' .p3_imginfos_ext').text(ext);
		}
		
		// other updates
		upload_row.removeClass('no-img').addClass('has-file');
		jQuery('a.p3_upload_button', jQuery('#upload-row-'+shortname) ).text('Replace '+image_text);
		jQuery('#p3_reset_button_'+shortname).css('display','' );
		jQuery('#bg-img-options-'+shortname).show();
	}
	window.p3_highlight_img_size_problems( shortname, height );
}

function p3_hide_reset_button(shortname) {
	jQuery('#'+shortname+' a.p3_reset_button').hide();
}
