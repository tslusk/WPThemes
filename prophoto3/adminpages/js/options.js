/* Scripts for the Options page */
var p3_debug = false;
var p3_form_changed = false;

// jquery tooltips
if (typeof(jQuery.fn.tTips) != 'function') {
	(function(JQ) {
		JQ.fn.tTips = function() {
			JQ('body').append('<div id="tTips"><p id="tTips_inside"></p></div>' );
			var TT = JQ('#tTips' );
			this.each(function() {
				var el = JQ(this), txt;
				if ( txt = el.attr('title') ) el.attr('tip', txt).removeAttr('title' );
				else return;
				el.find('img').removeAttr('alt' );
				el.mouseover(function(e) {
					txt = el.attr('tip'), o = el.offset();
					clearTimeout(TT.sD);
					TT.find('p').html(txt);
					TT.css({'top': o.top - 43, 'left': o.left - 15});
					TT.sD = setTimeout(function(){TT.fadeIn(150);}, 100);
				});
				el.mouseout(function() {
					clearTimeout(TT.sD);
					TT.css({display : 'none'});
				})
			});
		}
	}(jQuery));
}

// get the p3_tab=XXX part of the current URL
function p3_get_option_tab() {
	var objURL = {};
	window.location.search.replace(
		new RegExp( "([^?=&]+)(=([^&]*))?", "g" ),
		function( $0, $1, $2, $3 ){
			objURL[ $1 ] = $3;
		}
	);
	return (objURL['p3_tab']);
}

// constrains text input areas to required limits
function p3_constrain_textinput(id, bottom, top) {
	jQuery(id).blur(function(){
		var opacity = jQuery(this).val();
		if ( opacity > top ) {
			jQuery(this).val(top);
		}
		if ( opacity < bottom ) {
			jQuery(this).val(bottom);		
		}
	});	
}


/* crude color code validation and correction */
function p3_color_validate(color,id) {
	var color_in = color;
	if (color.charAt(0) != '#') {
		color = '#'+color;
	}
	if (color.length > 7) {
		color = color.substring(0,7);
	}
	if (color.length > 4 && color.length < 7) {
		color = color.substring(0,4);
		color = color+color.substring(1,4);
	}
	if ( !color.match(/^#([\da-fA-F]{3}$)|([\da-fA-F]{6}$)/) ) {
		color = '#ffffff';
	}
	if ( color_in != color )
		jQuery('#'+id).val(color).blur();
	return color;
}


/* functions for live font preview */
function p3_is_checked(optionid) {
	if ( jQuery(optionid).children().children('.optional-color-bind-checkbox').length > 0 ) {
		if ( jQuery(optionid).children().children('.optional-color-bind-checkbox').is(':checked') ) {
			return true;
		}
		return false;
	}
	return true;
}
function p3_get_color_input(optionid) {
	return color = jQuery(optionid).children().children().children('.color-picker').val();
}
function p3_get_sectionid(optionid) {
	return sectionid = '#'+jQuery(optionid).parents('.option-section').attr('id' );
}
function p3_get_color_setting_type(optionid) {
	var type = 'none';
	if ( jQuery(optionid).hasClass('nonlink-font-color-picker') ) {type='';}
	if ( jQuery(optionid).hasClass('font-color-picker') ) {type=' a.unvisited';}
	if ( jQuery(optionid).hasClass('visited-link-font-color-picker') ) {type=' a.visited';}
	return type;
}
function p3_update_color_preview(sectionid, type, color) {
	if ( type != 'none' ) {
		jQuery(sectionid+' .font-preview'+type).css('color', color);
	}
}
/* END functions for live font preview */



/* handle relationshiop between uploaded images and dependant option sections */
function p3_image_dependent( img_shortname, dependant ) {
	jQuery('#p3_image_'+img_shortname).change(function(){
		if (/nodefaultimage/.test(jQuery('#p3_image_'+img_shortname).attr('src'))) {
			jQuery('#'+dependant+'-option-section').hide();
		} else {
			jQuery('#'+dependant+'-option-section').show();
		}
	}).change();
}



// Close color pickers when click on the document. This function is hijacked by 
// farbtastic's event when a color picker is open
jQuery(document).mousedown(function(){
	p3_hideothercolorpickers();
});


// Close color pickers except "what"
function p3_hideothercolorpickers(what) {
	jQuery('.p3_picker_wrap').each(function(){
		var id = jQuery(this).attr('id' );
		if (id == what) {
			return;
		}
		var display = jQuery(this).css('display' );
		if (display == 'block') {
			jQuery(this).fadeOut(300);
			var swatch = id.replace(/picker-wrap/, 'swatch' );
			jQuery('#'+swatch).css('background-position', '0px 0px' );
		}
	});
}


// activate a sub-tab options group
function p3_subtab_activate(subtab) {
	// activate a subtab based on passed value
	if ( subtab ) {
		subtab_select   = '#subgroup-nav-'+subtab;
		subgroup_select = '#subgroup-'+subtab;
	// no passed value, activate the first one
	} else {
		subtab_select   = '#subgroup-nav li:first';
		subgroup_select = '.subgroup:first';
	}
	
	// shown only requested subgroup
	jQuery('.subgroup').hide();
	jQuery(subgroup_select).not('.hidden').show();
	
	// highlight only selected subtab
	jQuery('#subgroup-nav li').removeClass('active');
	jQuery(subtab_select).addClass('active');
	
	if ( subtab ) {
		// change form action to hash so save stays on same page
		jQuery('form#p3-options').attr('action', '#'+subtab);
	}
}


function p3_handle_subtabs() {
	// load_correct subgroup based on page load hash
	var window_hash = window.location.hash.substr(1);
	// no window hash, activate first subtab
	if ( window_hash == '' ) {
		p3_subtab_activate();
	// activate tab/section based on window hash
	} else {
		p3_subtab_activate(window_hash);
	} 
	
	// show requested subgroup on subtab click
	jQuery('.subgroup-link').click(function(){
		// get the key from the clicked subtab
		var key = jQuery(this).attr('key');
		// silently update the window hash
		window.location.hash = key;
		// activate the requested subtab/section
		p3_subtab_activate(key);
		return false;
	});
}


/* show and hide options based on radio button clicks */
function p3_show_hide_options() {
	jQuery('.radio-input').click(function(){
		var val = jQuery(this).val();
		var option_id = jQuery(this).parent().attr('id').replace('-individual-option', '');
		jQuery('.show-when-'+option_id+'-clicked').show();
		jQuery('.hide-when-'+option_id+'-val-'+val).hide();
	});
}


/* slider input areas */
function p3_slider_inputs() {
	// slider controls
	jQuery('.p3_slider').each(function(){
		var slider = jQuery(this);
		var option = slider.parent();
		slider.slider({
			range: "min",
			value: parseFloat(jQuery('.val', option).text()),
			min:   parseFloat(jQuery('.min', option).text()),
			max:   parseFloat(jQuery('.max', option).text()),
			step:  parseFloat(jQuery('.step', option).text()),
			slide: function(event, ui) {
				jQuery('.p3_slider_display span', option).text(ui.value);
				jQuery('input', option).val(ui.value);
				p3_form_changed = true;
			}
		});
	});
}


/* font button clicks */
function p3_font_button_inputs() {
	jQuery('.font-button').click(function(){
		// get data from markup
		var button      = jQuery(this);
		var type        = button.attr('type');
		var old_val     = button.attr('val');
		var options     = button.attr('options').split('|');
		var last_index  = options.length - 1;
		
		// debug css display class
		var old_classes = button.attr('class');
		var old_classes_array = old_classes.split(' ');
		var old_display_class = old_classes_array[old_classes_array.length - 1];
		
		// determine index of currently selected value from options array
		var options_debug     = '';
		var old_val_index = 0;
		for ( index in options ) {
			options_debug += '&nbsp;&nbsp;&nbsp; options[' + index + '] => ' + options[index] + '<br />';
			if ( old_val == options[index] ) old_val_index = parseInt(index);
		}
		
		// get index of next value
		var next_val_index = 'ERROR';
		if ( old_val_index < last_index ) {
			new_val_index = old_val_index + 1;
		} else {
			new_val_index = 0;
		}
		
		// switch to next value
		new_val = options[new_val_index];
		button.attr('val', new_val);
		
		// update class for css button display toggle/change
		var new_display_class = 'font-button-' + type + '-val-' + new_val;
		new_display_class = new_display_class.replace('.','');
		var remove_class = 'font-button-' + type + '-val-' + old_val.replace('.','');
		button
			.removeClass( remove_class )
			.addClass( new_display_class );
			var new_classes = button.attr('class'); // debug
			
		// update hidden select box
		var context = button.parent();
		jQuery( '.font-group-hidden-input-' + type + ' select', context ).val(new_val).change();
		
		
		/* debug */
		// var debug = '<p class="debug" style="font-family:Courier,monospace; font-size:10px;clear:left;padding-top:15px;max-width:900px">';
		// debug += 'var type => ' + type + '<br />';
		// debug += 'var options => ' + options + '<br />';
		// debug += options_debug;
		// debug += 'var last_index => ' + last_index + '<br />';
		// debug += 'var old_val_index => ' + old_val_index + '<br />';
		// debug += 'var new_val_index => ' + new_val_index + '<br />';
		// debug += 'var old_val => ' + old_val + '<br />';
		// debug += 'var new_val => ' + new_val + '<br />';
		// debug += 'var old_display_class => ' + old_display_class + '<br />';
		// debug += 'var new_display_class => ' + new_display_class + '<br />';
		// debug += 'var remove_class => ' + remove_class + '<br />';
		// debug += 'var old_classes => ' + old_classes + '<br />';
		// debug += 'var new_classes => ' + new_classes + '<br />';
		// debug += '</p>';
		// button.next().show().end().parent().parent().next('.debug').remove().end().after(debug);
	});
}

function p3_unsaved_changes_warning() {
	jQuery('.tab-link').click(function(){
		if ( p3_form_changed === true ) {
			if ( p3_debug ) return true;
			if (!confirm("You have unsaved changes on this tab. Do you want to go to another tab and lose those changes?")) return false;
		}
	});
}


	
function p3_highlight_img_size_problems( shortname, masthead_height ) {
	jQuery('.upload-row:has(ul.p3_image_infos_recommend)').each(function(){
		var a_height = jQuery('.p3_imginfos_height', this);
		var a_width  = jQuery('.p3_imginfos_width', this);
		var r_height = jQuery('.p3_recommended_height', this);
		var r_width  = jQuery('.p3_recommended_width', this);
		a_height.parent().removeClass('wrong');
		a_width.parent().removeClass('wrong');
		var found_problem = false;
		if ( shortname == 'masthead_image1' && !jQuery('body').hasClass('sameline') ) {
			jQuery('#subgroup-masthead .p3_recommended_height').text(masthead_height);
			shortname = '';
		}
		if (jQuery(this).hasClass('no-img')) return;
		if ( a_height.length && r_height.length && ( a_height.text() != r_height.text() ) ) {
			a_height.parent().addClass('wrong');
			found_problem = true;
		}
		if ( a_width.length && r_width.length && ( a_width.text() != r_width.text() ) ) {
			a_width.parent().addClass('wrong');
			found_problem = true;
		}
		jQuery('.p3_recommended span', this).hide();
		if ( found_problem ) jQuery('.p3_fh_dimensions_notok', this).show();
		else jQuery('.p3_fh_dimensions_ok', this).show();
	});
}



jQuery(document).ready(function() {
	

		
	p3_highlight_img_size_problems();
	
	// toggle class for ie6-fallback color display for semi-transparent .png's
	jQuery('.bg-option-group .upload-row-body input').click(function(){
		if ( jQuery(this).is(':checked') ) {
			jQuery('.bg-img-options', jQuery(this).parents('.upload-row')).removeClass('bg-color-not-set');
		} else {
			jQuery('.bg-img-options', jQuery(this).parents('.upload-row')).addClass('bg-color-not-set');
		}
	});
	
	jQuery('.add-image-upload').click(function(){
		var clicked = jQuery(this);
		var parent = ( clicked.parents('.subgroup').length ) ? clicked.parents('.subgroup') : clicked.parents('.tabbed-sections');
		var type = ( /image/.test(clicked.text())) ? '.upload-row' : '.option';
		var next_hidden = jQuery(type+':hidden:first', parent)
		next_hidden.show();
		if( !next_hidden.next().hasClass('option') ) clicked.hide();
	});
	
	// help keep from POSTing more than 200 fields
	jQuery('#p3-options').submit(function(){
		jQuery('.individual-option:hidden').remove();
	});
	
	p3_unsaved_changes_warning();
	
	p3_show_hide_options();
	
	p3_slider_inputs();
		
	p3_font_button_inputs();
	
	// watch for unsaved changes when going to different tab
	jQuery(['input', 'select', 'textarea']).each(function() {
		jQuery(this.toString()).change(function() {
			p3_form_changed = true;
		});
	});
	
	p3_handle_subtabs();
	
	// add a color picker to every div.p3_picker
	jQuery('.p3_picker').each(function(){
		var id = jQuery(this).attr('id' );
		var target = id.replace(/picker/, 'input' );
		jQuery(this).farbtastic('#'+target);
	});
	
	// add the toggling behavior to .p3_swatch
	jQuery('.p3_swatch').click(function(){
		var id = jQuery(this).attr('id' );
		var target = id.replace(/swatch/, 'picker-wrap' );
		p3_hideothercolorpickers(target);
		var display = jQuery('#'+target).css('display' );
		(display == 'block') ? jQuery('#'+target).fadeOut(300) : jQuery('#'+target).fadeIn(300);
		var bg = (display == 'block') ? '0px 0px' : '0px -24px';
		jQuery(this).css('background-position', bg);
		}).tTips(); // tooltipize

	// validate color entries
	jQuery('.color-picker').blur(function(){
		jQuery(this).val(p3_color_validate(jQuery(this).val(),jQuery(this).attr('id')));
	});
	
	// inline documentation hide/reveal links
	jQuery('a.click-for-explain').click(function(){
		var thisid = jQuery(this).attr('id');
		var id = thisid.replace('-cfe', '' );
		if ( typeof p3_external_explain == "undefined" || typeof p3_external_slugs == "undefined" ) {
			p3_external_explain = new Object();
			p3_external_slugs = new Object();
			p3_external_explain[id] = '<p><strong>How embarrassing.</strong> The file we use to show the extra help information did not load properly. The server where it is stored might be down temporarily, or there may be a connectivity problem.  If this problem persists for more than a few hours, please <a href="http://www.prophotoblogs.com/support/contact/">notify us</a> through our contact form.</p>';
			p3_external_slugs[id] = undefined;
		}
		var speed = 1;
		if ( jQuery(this).hasClass('help-active') ) {
			var speed = 130;
		}
		jQuery(this).toggleClass('help-active');
		var second_para = ''
		if ( p3_external_explain[id] == undefined ) {
			p3_external_explain[id] = '';
		}

		var support_url = 'http://www.prophotoblogs.com/';
		var second_para = '<p class="explain-link">Need more help with this specific area? We got it. <a href="'+support_url+'support/about/'+p3_external_slugs[id]+'/" target="_blank">Click here</a>.</p>';
		if ( p3_external_slugs[id] == undefined ) {
			second_para = '';
		}
		var help = p3_external_explain[id] + second_para;
		jQuery('#explain-' + id)
			.html(help)
			.animate({opacity: 0}, speed) 
			.slideToggle(185)
			.animate({opacity: 1}, 130);
	});
	
	
	// this for the "tabbed" options areas
	var p3_hash = p3_get_option_tab();
	if (p3_hash != undefined) {
		jQuery('.tabbed-sections').css('display', 'none' );
		jQuery('#tab-section-'+p3_hash+'-link').css('display', 'block' );
		jQuery('a.tab-link').removeClass('active' );
		jQuery('#'+p3_hash+'-link').addClass('active' );
	} else {
		jQuery('#background-link').addClass('active' );
		jQuery('#tab-section-background-link').css('display', 'block' );
	}

	
	jQuery('.click-for-explain').tTips();
	jQuery('.blank-comment').next().css('margin-top', '0' );
	
	if ( p3info.p2user ) {
		var now = new Date();
		var now_secs = parseInt( now.getTime() / 1000 );
		if ( ( now_secs - p3info.purchtime ) < ( 60 * 60 * 24 * 40 ) )
			jQuery('body').addClass('recent-upgrade');
	}
});

/* recent P2 => P3 upgrader flags */
jQuery(window).load(function(){
	if ( !jQuery('body').hasClass('recent-upgrade')) return;
	if ( typeof p3_external_explain == "undefined" ) return;
	jQuery('a.click-for-explain').each(function(){	
		var thisid 			   = jQuery(this).attr('id');
		var id 				   = thisid.replace('-cfe', '' );
		var this_explanation   = p3_external_explain[id];
		var has_explanation	   = (typeof(this_explanation) != 'undefined') ? true : false;
		var is_new_option 	   = (has_explanation && (this_explanation.search('p3_new') != -1)) ? true : false;
		var is_enhanced_option = (has_explanation && (this_explanation.search('p3_enhanced') != -1)) ? true : false;
		if (is_new_option) {
			jQuery(this).after('<span class="p3-new" style="float: right;">NEW</span>');
		} else if (is_enhanced_option) {
			jQuery(this).after('<span class="p3-enhanced">ENHANCED</span>');
		}
	});
});