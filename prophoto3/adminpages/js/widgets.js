jQuery(document).ready(function(){
	// widget farbtastic color wheel clicks
	jQuery('.widget-liquid-right .p3_swatch').live('click', function(){
		var this_click = jQuery(this);
		var id = this_click.attr('id' );
		var target = id.replace(/swatch/, 'picker-wrap' );
		var display = jQuery('#'+target).css('display' );
		(display == 'block') ? jQuery('#'+target).fadeOut(300) : jQuery('#'+target).fadeIn(300);
		var bg = (display == 'block') ? '0px 0px' : '0px -24px';
		jQuery(this).css('background-position', bg);
	});
	// add spacing between bio and footer widget cols
	jQuery('.widget-liquid-right .widgets-holder-wrap:eq(0), .widget-liquid-right .widgets-holder-wrap:eq(5), .widget-liquid-right .widgets-holder-wrap:eq(6), .widget-liquid-right .widgets-holder-wrap:eq(10)').css('padding-bottom', '30px');
	
	// add class to our widgets to highlight them
	jQuery('.widget').each(function(){	
		if ( /P3/.test( jQuery( 'h4', this ).text() ) ) jQuery( this ).addClass( 'p3-widget' );
	});
	
	// js hook for re-generating static
	jQuery('input.widget-control-save').click(function(){
		clearInterval(p3_text_live_update);
		function p3_js_regenerate_static() {
			jQuery.get( p3info.blogurl+'/?ajax_widget_save=true' );
		}
		setTimeout(function(){
			p3_js_regenerate_static();
		}, 1000 );
		setTimeout(function(){
			p3_js_regenerate_static();
		}, 4000 );		
	});
	
	/* html widget live preview */
	var p3_text_live_update;
	jQuery('.p3-text textarea, .p3_tag_btn, .p3-text input[type="checkbox"]').live('click',function(){
		var clicked = jQuery(this);
		var form = clicked.parents('.p3-text');
		var preview = jQuery('.p3-text-preview', form);
		var wpautop = jQuery('input[type="checkbox"]', form);
		clearInterval(p3_text_live_update);
		p3_text_live_update = setInterval(function(){
			var html = jQuery('textarea', form).val();
			if ( html == '' || html.indexOf('<') == -1 || html.indexOf('<script') != -1 || html.indexOf('<style') != -1 ) {
				jQuery('.p4-text-target', form).html('');
				preview.addClass('hidden');
			} else {
				if (wpautop.is(':checked')) html = html.replace(/\n/g, '<br />');
				jQuery('.p3-text-target', form).html(html);
				preview.removeClass('hidden');
			}
		}, 500);
	});
	
	/* html widget */
	jQuery('.p3_tag_btn').live('click',function(){
		// build tags	
		var tag = jQuery(this).attr('tag');
		var open_tag  = '<' + tag + '>';
		var close_tag = '</' + tag + '>';
		if ( tag == 'a' ) {
			var href = prompt('Enter URL:', 'http://');
			if (href == '' || href == null) return false;
			open_tag = open_tag.replace('>', ' href="' + href + '">')
		} else if ( tag == 'e' ) {
			open_tag  = '<a>';
			close_tag = '</a>';
			var email = prompt("Enter email address:\n(will be hidden from spambots)");
			if (email == '' || email == null) return false;
			open_tag = open_tag.replace('>', ' href="mailto:' + email + '">')
		}
		
		// setup browser variants
		var textbox = jQuery('textarea', jQuery(this).parent())[0];
		if ( typeof textbox.selectionStart != 'undefined' ) {
			var good_browser = true;
		} else if ( document.selection ) {
			var good_brower = false;
		} else {
			return alert('Sorry, browser not supported. Try using Firefox, Safari, or Internet Explorer');
		}
		
		// get highlighted selection
		if ( good_browser ) {
			var len = textbox.value.length;
	        var start = textbox.selectionStart;
	        var end = textbox.selectionEnd;
	        var highlighted = textbox.value.substring(start, end);
		} else {
			textbox.focus();
	        var sel = document.selection.createRange();
			var highlighted = sel.text;
		}
		
		// remove tags if open and close found in selection
		if (highlighted.indexOf(open_tag) >= 0 && highlighted.indexOf(close_tag) >= 0) {
			var open_tag_regex  = new RegExp(open_tag, 'gi');
			var close_tag_regex = new RegExp(close_tag, 'gi');
			highlighted = highlighted.replace(open_tag_regex, '');
			highlighted = highlighted.replace(close_tag_regex, '');
			open_tag = close_tag = '';
		
		// selection spans just one tag
		} else if (highlighted.indexOf(open_tag) >= 0 || highlighted.indexOf(close_tag) >= 0) {
			return false;
		}
		
		// update textarea
		var replace = open_tag + highlighted + close_tag;
		if ( good_browser ) {
	        textbox.value = textbox.value.substring(0, start) + replace + textbox.value.substring(end, len);
	        textbox.focus();
	        var new_end = open_tag.length + end;
	        textbox.setSelectionRange(new_end, new_end);
		} else {
			sel.text = replace;
	        var range = textbox.createTextRange();
	        range.collapse(false);
	        range.select();
		}
	});
});


/* twitter SLIDER functions */
// function to increase/decrease by 1 the incrementable control areas
function p3_increment( this_click ) {
	var click_parent = this_click.parents('.incrementable');
	var this_widget  = this_click.parents('.p3-twitter-slider-wrap');
	var to_adjust    = click_parent.attr('rel');
	var current_val  = parseInt(jQuery('input', click_parent).val());
	var new_val = ( this_click.hasClass('up') ) ? current_val + 1 : current_val - 1 ;
	jQuery('input', click_parent).val(new_val);
	p3_adjust_slider_preview(to_adjust,this_widget,new_val);
	return false;
}
// increment the same areas as with the clicks, but manually via pixel # entry
function p3_increment_manual(this_change) {
	var this_widget = this_change.parents('.p3-twitter-slider-wrap');
	var to_adjust   = this_change.parents('.incrementable').attr('rel');
	var new_val     = this_change.val();
	p3_adjust_slider_preview(to_adjust,this_widget,new_val);
}
// take new tweet dimensions and positioning and reflect in preview
function p3_adjust_slider_preview(to_adjust,this_widget,new_val) {
	if ( to_adjust == 'tweet_height' ) {
		jQuery('.viewer', this_widget).css('border-color', 'red');
		jQuery('.badge-inner, .viewer, li', this_widget).css('height', new_val+'px');
		jQuery('.tweet_height').text(new_val);
	} else if ( to_adjust == 'tweet_width' ) {
		jQuery('.viewer', this_widget).css('border-color', 'red');
		jQuery('.viewer, ul', this_widget).css('width', new_val+'px');
		var box_width = new_val + 26;
		jQuery('.badge-inner', this_widget).css('width', box_width+'px');
	} else if ( to_adjust == 'pos_top' ) {
		jQuery('.viewer', this_widget).css('border-color', 'transparent');
		jQuery('.badge-inner', this_widget).css('top', new_val+'px');
	} else if ( to_adjust == 'pos_left' ) {
		jQuery('.viewer', this_widget).css('border-color', 'transparent');
		jQuery('.badge-inner', this_widget).css('left', new_val+'px');
	}
}
// handle live preview of background images
function p3_slider_image_preview(this_change) {
	var widget_form = this_change.parents('.p3-twitter-slider-wrap');
	var preview_img = jQuery('.p3-twitter-slider img', widget_form);
	var this_widget = jQuery('.p3-twitter-slider', widget_form);
	var new_image = this_change.val();
	jQuery('.add-slider-image', widget_form).hide();
	widget_form.addClass('has-img').removeClass('no-img');
	if ( new_image == 'A' || new_image == 'B' ) {
		preview_img.attr('src', preview_img.attr('rel')+'twitter-thought-bubble'+new_image+'.png');
		var width = ( new_image == 'A' ) ? '300' : '194';
		this_widget.css('width', width+'px');
	} else if ( new_image == 'add' ) {
		jQuery('.add-slider-image', widget_form).show();
	} else if ( new_image == 'no' ) {
		widget_form.addClass('no-img').removeClass('has-img');
		preview_img.attr('src', preview_img.attr('rel')+'nodefaultimage.gif');
		this_widget.css('width', jQuery('.badge-inner', this_widget).css('width') );
		this_widget.css('height', jQuery('.tweet-box-height', widget_form).val()+'px' );	
	} else {
		preview_img.attr('src', p3_custom_images[new_image]);
		this_widget.css('width', p3_custom_images[new_image+'-width']+'px' );
	}
}
// show/hide sliding controls, hidden if only one tweet loaded/requested
function p3_slider_count_change(this_change) {
	var tweet_count = parseInt(this_change.val());
	var this_widget = jQuery('.p3-twitter-slider', this_change.parents('.p3-twitter-slider-wrap'));
	if (tweet_count == 1) {
		jQuery('.controls a', this_widget).css('display','none');
	} else {
		jQuery('.controls a', this_widget).css('display','block');
	}	
}
// live preview of slider fontsize
function p3_slider_fontsize(this_change) {
	var font_size   = this_change.val();
	var this_widget = jQuery('.p3-twitter-slider', this_change.parents('.p3-twitter-slider-wrap'));
	jQuery('ul', this_widget).css('font-size', font_size+'px');
}
// live preview of slider font family change
function p3_slider_fontfam(this_change) {
	var font_family   = this_change.val();
	var this_widget = jQuery('.p3-twitter-slider', this_change.parents('.p3-twitter-slider-wrap'));
	jQuery('ul', this_widget).css('font-family', font_family);
}


/* farbtasticifies widgets */
function p3_handle_widget_farbtastic() {
	jQuery('.widget-liquid-right .p3_picker').each(function(){
		var id = jQuery(this).attr('id' );
		var target = id.replace(/p3-picker-/, '' );
		jQuery(this).farbtastic('#'+target);
	});
}


/* updates the social media icon preview image */
function p3_update_icon_preview( widget_form ) {
	var type    = jQuery('.p3-type', widget_form).val();
	var style   = jQuery('.p3-style', widget_form).val();
	var size    = jQuery('.p3-size', widget_form).val();
	var cstm_sz = jQuery('.p3-custom-size', widget_form).val();
	var img     = jQuery('.p3-social-media-icons-preview-image', widget_form);
	var path    = img.attr('rel');
	
	// size set to large or small
	if ( 'custom' != size ) {
		jQuery('.p3-custom-size-holder', widget_form ).hide();
		filesize = ( size == 'large' ) ? '256' : '128';
		htmlsize = filesize;
	// custom size
	} else {
		jQuery('.p3-custom-size-holder', widget_form ).show();
		// don't set width/height to 0 initially
		if ( cstm_sz == '' ) {
			cstm_sz = jQuery('.p3-social-media-icons-preview-image', widget_form ).attr('width');
		}
		filesize = ( parseFloat( cstm_sz ) > 128 ) ? '256' : '128';
		htmlsize = cstm_sz;
	}
	
	// update the image html
	img.attr({
		src: path+type+'_'+style+'_'+filesize+'.png',
		height: htmlsize,
		width: htmlsize
	});
}

/* update the custom icon widget preview */
function p3_custom_icon_preview( widget_form ) {
	var img_num = jQuery('.p3-number', widget_form).val(); 
	var noimg_alert = jQuery('.add-image-explain', widget_form);
	if ( img_num == 'add') {
		noimg_alert.show();
	} else {
		jQuery('.p3-custom-icon-preview-image', widget_form ).attr('src', p3_custom_images[img_num]);
		noimg_alert.hide();
	}
}