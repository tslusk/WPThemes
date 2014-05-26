/* Scripts for the Settings page */

jQuery(document).ready(function(){
	/* activate and delete designs */
	jQuery('.activate_design').click(function(){
		var clicked = jQuery(this);
		var form = jQuery('#misc_form');
		jQuery('.action', form).val(clicked.attr('action'));
		jQuery('.value', form).val(clicked.attr('val'));
		form.submit();
	});
	jQuery('.delete_design').click(function(){
		var permission = confirm('This completely deletes this design and all its settings.  There is no undo.  Are you sure you want to continue?');
		if (!permission) return;
		var clicked = jQuery(this);
		var form = jQuery('#misc_form');
		jQuery('.action', form).val(clicked.attr('action'));
		jQuery('.value', form).val(clicked.attr('val'));
		form.submit();
	});
	
	jQuery('#reset-everything input').click(function(){
		if ( !confirm('This erases all of your saved designs completely.\n\nThere is no undo.\n\nAre you sure you want to DELETE ALL SAVED CUSTOMIZATIONS FOR ALL DESIGNS?' ) ) return false;
		return true;
	});
	
	// zebra stripe the layouts list
	jQuery('.flatfile-list li:even').addClass('alt' );
	jQuery('.flatfile-list li').mouseover(function(){
		jQuery(this).addClass('hover' );
	}).mouseout(function(){
		jQuery(this).removeClass('hover' );
	});
	
	// Add some behavior to the input field of the Save form: sanitize field
	jQuery('.p3_new_flatfile_name').blur(function(){
		var setname = p3_sanitize_setname(jQuery(this).val());
		// if we had to modify the set name, make it flash once to warn user
		if (setname != jQuery(this).val()) {
			p3_flash(this);
		}
		jQuery(this).val(setname);
	});
	
	// The "Save" form behavior
	jQuery('.p3_new_flatfile_form').submit(function(){
		var field = jQuery(this).find('.p3_new_flatfile_name' );
		var type = jQuery(this).find(':hidden[name=p3_flatfile_type]').val();
		
		// trigger event that sanitizes the field
		jQuery(field).blur();
		
		// No empty stuff or warn
		var setname = jQuery(this).find('.p3_new_flatfile_name').val();
		if (setname == '') {
			p3_flash(field);
			jQuery(field).focus();
			setTimeout(function(){alert ('Please enter a name' );}, 100);
			return false;
		}

		// Warn if about to overwrite existing file
		if (p3_settings_alreadyexists(setname, type)) {
			if ( !confirm('You are about to overwrite setting file "'+setname+'"\n \'Cancel\' to stop, \'OK\' to proceed.') ) {
				return false;
			}
		}
	});
});

function p3_refresh_designs_page() {
	window.location.href = window.location.href;
}


