/* -- javascript for live font previews -- */

jQuery(document).ready(function(){
	// disable clicking on font-preview links
	jQuery('.font-preview a').click(function(){return false;});
	
	// identify the various groups of inputs
	var font_groups             = jQuery('.p3-font-group');
	var font_size_inputs        = jQuery('.text-input[name$=font_size]', font_groups);
	var font_family_inputs      = jQuery('.select-input[name$=font_family]', font_groups);
	var font_style_inputs       = jQuery('.select-input[name$=font_style]', font_groups);
	var text_transform_inputs   = jQuery('.select-input[name$=text_transform]', font_groups);
	var font_lineheight_inputs  = jQuery('.select-input[name$=line_height]', font_groups);
	var link_decoration_inputs  = jQuery('.select-input[name$=link_decoration]', font_groups);
	var hover_decoration_inputs = jQuery('.select-input[name$=link_hover_decoration]', font_groups);
	var link_hover_color_inputs = jQuery('.color-picker[id$=link_hover_font_color]',font_groups);
	var font_weight_inputs      = jQuery('.select-input[name$=font_weight]', font_groups);
	var margin_bottom_inputs    = jQuery('.text-input[id$=margin_bottom]', font_groups);
	var letterspacing_inputs    = jQuery('.select-input[id$=_letterspacing]', font_groups);
	var font_color_inputs       = jQuery('.color-picker[id$=_font_color]',font_groups)
									.not('[id$=link_hover_font_color]')
									.not('[id$=link_visited_font_color]')
									.not('[id$=link_visited_hover_font_color]');
									
	// bind all of the normal change events
	p3_font_change(font_family_inputs, 'font-family');
	p3_font_change(font_size_inputs, 'font-size', 'px');
	p3_font_change(font_weight_inputs, 'font-weight');
	p3_font_change(font_style_inputs, 'font-style');
	p3_font_change(text_transform_inputs, 'text-transform');
	p3_font_change(font_lineheight_inputs, 'line-height', 'em');
	p3_font_change(link_decoration_inputs, 'text-decoration');
	p3_font_change(margin_bottom_inputs, 'margin-bottom', 'px');
	p3_font_change(letterspacing_inputs, 'letter-spacing');
	p3_font_color_bind(font_color_inputs);
	
	// color bind checkbox clicks
	jQuery('.optional-color-bind-checkbox').not('[id$=hover_font_color-bind]').click(function(){
		var checkbox = jQuery(this);
		var color_input = jQuery('.color-picker', checkbox.parent());
		if (checkbox.is(':checked')) {			
			color_input.blur();
		} else {
			p3_update_preview_css( color_input, 'color', '' );
		}
	});
	
	// color bind checkbox clicks for hover font colors
	jQuery('.optional-color-bind-checkbox[id$=hover_font_color-bind]').click(function(){
		var checkbox = jQuery(this);
		var color_input = jQuery('.color-picker', checkbox.parent());
		if (checkbox.is(':checked')) {			
			p3_hover_preview(color_input, 'color');
		} else {
			p3_hover_preview(color_input, 'color', true);
		}
	});
	
	// link hover decoration
	hover_decoration_inputs.change(function(){
		p3_hover_preview( jQuery(this), 'text-decoration' );
	});
	
	// link hover color
	link_hover_color_inputs.each(function(){
		var self = jQuery(this);
		var option_id = self.attr('id').replace(/p3-input-/, '');
		p3_bind_callback_to_color_events( option_id, function(){
			p3_hover_preview(self, 'color');
		});
	});
	
	// show each relevant font-preview area when user focuses on that section
	jQuery('.font-group').click(function(){
		var group = jQuery(this);
		var this_font_preview = jQuery('.font-preview', group);
		if ( this_font_preview.is(':visible') ) return;
		jQuery('.font-preview').not(this_font_preview).css('opacity',0).slideUp('fast',function(){
			this_font_preview.css('opacity', 0).slideDown('fast',function(){
				this_font_preview.fadeTo('fast', 1);
			})
		});
	});
});


/* bind a hover preview update to a link area */
function p3_hover_preview( option, css_attr, unset ) {
	var preview_area = p3_get_update_target(option);
	var old_val = preview_area.css(css_attr);
	var new_val = option.val();
	if ( unset == undefined ) unset = false;
	if ( unset ) new_val = old_val;
	preview_area.hover(function(){
		jQuery(this).css(css_attr, new_val);
	},function(){
		jQuery(this).css(css_attr, old_val);
	});
}

/* bind preview change events to a group of color inputs */
function p3_font_color_bind( color_type_input ) {
	color_type_input.each(function(){
		var self = jQuery(this);
		var option_id = self.attr('id').replace(/p3-input-/, '');
		p3_bind_callback_to_color_events( option_id, function(){
			p3_update_preview_css(self, 'color', self.val());
		});
	})
}


/* bind change callback to all the different change events for color picker */
function p3_bind_callback_to_color_events( id, callback ) {
	jQuery('#p3-picker-wrap-'+id+' .farbtastic div')
		.mouseup(function(){callback()})
		.change(function(){callback()});
	jQuery('#p3-input-'+id).blur(function(){callback()});
}


/* boolean test, is this option in a group with a non-link option? */
function p3_doing_nonlink( option ) {
	var nonlink_font_color = 
		jQuery('.color-picker', option.parents('.option') )
			.not('[id$=link_font_color]')
			.not('[id$=link_hover_font_color]')
			.not('[id$=link_visited_font_color]')
			.not('[id$=link_visited_hover_font_color]');
	if ( nonlink_font_color.length ) return true;
	return false;
}


/* bool test: does this font option type apply to text and links */
function p3_is_dual_purpose( option ) {
	if ( /decoration/.test(option.attr('id'))) return false;
	if ( /link_font_color/.test(option.attr('id'))) return false;
	if ( /link_hover_font_color/.test(option.attr('id'))) return false;
	return true;
}


/* return preview area updating target for given font option change */
function p3_get_update_target( option ) {
	var preview_section = jQuery('.font-preview', option.parents('.individual-option'));
	
	// margin bottoms
	if ( /margin_bottom/.test(option.attr('id')) ) return jQuery('.margin-bottom', preview_section);
	
	// if we're inside a link area
	if ( option.parents('.individual-option').hasClass('link-font-group')) {

		// areas with a 'nonlink' need special attention
		if ( p3_doing_nonlink( option) ) {
			
			// non link font color: only affect non-link text in preview area
			if (!(/link/.test(option.attr('id')))) return preview_section;
			
			// option types that effect both link and non-link text
			if ( p3_is_dual_purpose( option ) ) return preview_section.add(jQuery('a', preview_section));
		}
		
		// font size changes affect link and overall section, to keep section div size correct
		if (/_font_size/.test(option.attr('id'))) return preview_section.add(jQuery('a', preview_section));
		
		return jQuery('a', preview_section);
	
	// not in a link section
	} else {
		return preview_section;
	}
}


/* update the font preview area */
function p3_update_preview_css( option, type, val ) {
	var preview_target = p3_get_update_target( option );
	preview_target.css(type, val);
	p3_rebind_hover_decoration(option);
}


/* bind a change event to update the preview area */
function p3_font_change( input_option, css_attr, val_suffix ) {
	input_option.change(function(){
		var self = jQuery(this);
		var new_val = self.val();
		if ( val_suffix == undefined ) val_suffix = '';
		if ( new_val == '' ) {
			new_val = 'inherit';
			val_suffix = '';
		}
		p3_update_preview_css(self, css_attr, new_val+val_suffix);
	})
}


/* re-bind the hover decoration hover function after non-hover link decoration changed */
function p3_rebind_hover_decoration(option) {
	if (!(/_link_decoration/.test(option.attr('id')))) return;
	var this_hover = jQuery('.select-input[name$=link_hover_decoration]',option.parents('.individual-option'));
	p3_hover_preview(this_hover, 'text-decoration', true);
	p3_hover_preview(this_hover, 'text-decoration');
}