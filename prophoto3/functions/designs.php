<?php
/* ----------------------------------------------------------- */
/* -- library of functions used for the layouts saving page -- */
/* ----------------------------------------------------------- */

/* read storage db item, put into global var */
function p3_read_storage() {
	global $p3_store;
	$p3_store = get_option( P3_DB_STORAGE_NAME );
}
p3_read_storage();


/* create a new design */
function p3_create_new_design( $template, $new_design_name, $new_design_desc = '' ) {
	global $p3, $p3_store, $p3_starter_designs;

	// add design, get ID
	$new_design_id = p3_add_new_design( $new_design_name, $new_design_desc );
	
	// activate new design
	$p3['active_design'] = $new_design_id;
	
	// get initial options from a saved design layout	
	if ( isset( $p3[$template] ) ) {
		$template_options = $p3[$template];
	
	// get initial options options from a starter-design
	} else {
		require( dirname( dirname( __FILE__ ) ) . "/includes/starters/{$template}.php" );
		$template_options = $options;
	}
	
	// set up the new design layout based on template
	if ( !is_array( $template_options ) ) $template_options = array();
	foreach ( $template_options as $settings_array => $settings_values ) {
		$p3[$new_design_id][$settings_array] = $settings_values;
	}
	
	// save the new settings to the database;
	p3_store_options();
}


/* processes POSTed info from design page */
function p3_process_design_form_post() {
	check_admin_referer( 'p3-settings', '_wpnonce_p3' );
	global $p3, $p3_store, $p3_starter_designs;
	extract( $_POST );

	switch( $p3_designs_action ) {
		
		/* create a new design */
		case 'create_new_design' :
			// status message
			echo '<p id="creating">creating new design...</p>';
			
			// no design name given
			if ( !$new_design_name ) {
				echo '<style type="text/css" media="screen">#creating{display:none;}#new_design_name{border:1px solid red;}</style><p style="color:red;">You must provide a name for your new design</p>';
				p3_start_new_design_markup();
				exit;
			}
			
			// call function that creates the new design
			p3_create_new_design( $template, $new_design_name, $new_design_desc );
			break;
			
			
		/* import P2 layouts */	
		case 'import_p2_layouts' : 
			echo '<p id="importing">importing...</p>';
			
			// first, deal with importing current saved design
			if ( $p2_current == 'true' ) {
				
				// get P2 data from db
				$p2 = get_option( 'p2' );
				$p2_current_data['options'] = $p2['settings'];
				$p2_current_data['images']  = $p2['images'];
				
				// transfer to new layout
				p3_transfer_p2_layout( $p2_current_data, 'Current P2 saved settings', 'This design was created by importing the most recently saved settings and images from ProPhoto version 2.' );
			}
			
			// next, deal with importing any saved layouts from flatfiles
			$p2_layouts_data = unserialize( stripslashes( $_POST['p2_layouts_data'] ) );
			foreach ( (array) $p2_layouts_data as $key => $layout_info ) {
				if ( $_POST['p2_layouts_' . $key] == 'true' ) {
					// zero out the arrays
					$p2 = $p2_layout_data = array();
					
					// require settings file
					require( $layout_info['filepath'] );
					
					// setup data array for new design creation
					$p2_layout_data['options'] = $p2['options']['settings'];
					$p2_layout_data['images']  = $p2['options']['images'];

					// transfer to new layout
					p3_transfer_p2_layout( $p2_layout_data, $layout_info['nicename'], 'This design was created by importing the settings and images from the ProPhoto version 2 saved layout called <em>"' . $layout_info['name'] . '"</em>.' );
				}
			}
			
			p3_store_options();
			break;
			
		
		/* update design meta */
		case 'update_design_meta':
			echo '<p>processing...</p>';
			$p3_store['design_meta'][$layout]['name'] = $design_name;
			$p3_store['design_meta'][$layout]['description'] = $design_desc;
			p3_store_options();
			break;
		
			
		/* activate an inactive design */
		case 'activate_design' :
			p3_activate_design( $misc_value );
			break;
			
			
		/* activate an inactive design */
		case 'delete_design' :
			foreach ( $p3_store['designs'] as $key => $design_id ) {
				if ( $design_id == $misc_value ) {
					unset( $p3_store['designs'][$key] );
					unset( $p3_store['design_meta'][$val] );
					unset( $p3_store[$design_id] );
				}
			}
			p3_store_options();
			break;
			
			
		/* export everything */
		case 'export_everything':
			p3_export_everything();
			break;
		
		
		/* reset everything */
		case 'reset_everything' :
			unset( $p3 );
			unset( $p3_store );
			p3_delete_options();
			p3_delete_static();
			p3_initialize_db_options();
			p3_read_options();
			// force double refresh
			echo '<p>resetting all db options...</p>';
			p3_refresh_parent_from_iframe();
			break;
	}
}


/* activate an inactive design */
function p3_activate_design( $design_id ) {
	global $p3, $p3_store;
	if ( !is_array( $p3_store[$design_id] ) )
		die( "Design with ID: $design_id not found in \$p3_store" );
	unset( $p3[$p3['active_design']] );
	$p3['active_design'] = $p3_store['active_design'] = $design_id;
	$p3[$design_id] = $p3_store[$design_id];
	p3_store_options();
	p3_generate_static_files();
}


/* add new design based on name, return id of new design */
function p3_add_new_design( $new_design_name, $new_design_desc = '' ) {
	global $p3_store;
	
	// sanitize name into ID
	$id = p3_sanitize_for_design_id( $new_design_name );
		
	// deal with duplicate names
	if ( in_array( $id, (array) $p3_store['designs'] ) ) $id .= '_' . time();
	
	// update p3 var with new design meta
	$p3_store['designs'][] = $id;
	$p3_store['design_meta'][$id]['name'] = $new_design_name;
	if ( $new_design_desc ) $p3_store['design_meta'][$id]['description'] = $new_design_desc;
	
	// save the new settings to the database;
	p3_store_options();
	
	return $id;
}


/* sanitize a name into design ID */
function p3_sanitize_for_design_id( $name ) {
	$sanitized = ltrim( rtrim( strtolower( preg_replace( '/[^A-Za-z0-9]/', '_', $name ) ), '_' ), '_' );
	$sanitized = str_replace( array( '____', '___', '__' ), '_', $sanitized );
	return $sanitized;
}


/* remove a default widget */
function p3_remove_default_widget( $widget_column ) {
	$sidebars_widgets = get_option( 'sidebars_widgets' );
	unset( $sidebars_widgets[$widget_column][0] );
	update_option( 'sidebars_widgets', $sidebars_widgets );
}


/* boolean test, is a default widget being used */
function p3_using_default_widget( $widget_column ) {
	$sidebars_widgets = get_option( 'sidebars_widgets' );
	$possible_default_widget = $sidebars_widgets[$widget_column][0];
	
	// default widget will always be first 'p3-text' widget in spanning col
	if ( !$possible_default_widget || strpos( $possible_default_widget, 'p3-text-' ) === FALSE ) return FALSE;

	// get widget id
	$widget_id = str_replace( 'p3-text-', '', $possible_default_widget );

	// default widget has an extra array param, 'p3_default'
	$text_widgets = get_option( 'widget_p3-text' );
	if ( $text_widgets[$widget_id]['p3_default'] ) return TRUE;
	return FALSE;
}


/* return array of available designs for importing, or FALSE if none found */
function p3_available_design_imports() {
	$available_imports = glob( P3_IMAGES_PATH . 'export_*.txt' );
	if ( empty( $available_imports ) ) return FALSE;
	return $available_imports;
}


/* popup content for importing from p3 exported designs */
function p3_import_design_form() {
	
	// initial form display
	if ( empty( $_POST ) ) {
		
		// check img directory for export files
		$available_imports = p3_available_design_imports();
		if ( !$available_imports ) return p3_notice( 'no_available_p3_imports' );
		
		// build form checkbox markup
		foreach ( $available_imports as $filepath ) {
			$filename = str_replace( array( P3_IMAGES_PATH, '.txt' ), '', $filepath );
			$options .= "<div><input type='checkbox' name='$filename' value='true'><label style='font-size:12px'>$filename</label></div>";
		}
		
		// non design warning
		$import_non_design_warning = p3_get_notice( 'import_non_design_warning' );
		
		echo <<<HTML
		<form id="import-p3" action="" method="post" accept-charset="utf-8">
			<h3>Import P3 designs:</h3>
			<p>The following design export files were found:</p>
			$options
			<p id="show" onclick="javascript:jQuery('#advanced').toggle();">show advanced option &darr;</p>
			<div id="advanced">
				<p>$import_non_design_warning</p>
				<div>
					<input type='checkbox' name='import_non_design' value='true'><label style='font-size:12px'><em>import and overwrite non-design settings</em></label>
				</div>
			</div>
			<p><input type="submit" value="Import selected designs from export files"></p>
		</form>
HTML;

	// process form submission
	} else {
		echo '<p>importing...</p>';
		
		// are we doing non-design settings too?
		if ( $_POST['import_non_design'] == 'true' ) {
			$do_non_design = TRUE;
			unset( $_POST['import_non_design'] );
		} else {
			$do_non_design = FALSE;
		}

		// import selected layouts
		foreach ( $_POST as $export_filename => $val ) {
			$export_file = P3_IMAGES_PATH . $export_filename . '.txt';
			p3_import_design( $export_file, $do_non_design );
			@rename( $export_file, str_replace( 'export_', 'imported_', $export_file ) );
		}
		p3_refresh_parent_from_iframe();
	}
}


/* imports a p3 design from a previously exported p3 design */
function p3_import_design( $export_file, $do_non_design ) {
	global $p3, $p3_store;

	// get array of settings by reading and unserializing file data
	$data = @file_get_contents( $export_file );
	if ( $data ) $import = unserialize( $data );
	if ( !$data || !$import ) {
		p3_notice( 'cant_read_txt_export_file', $export_file );
		return FALSE;
	}

	// imported design ID
	$design_id = ( $import['design_id'] ) ? $import['design_id'] : $import['active_design'];
	
	// prevent overwriting
	if ( in_array( $design_id, $p3_store['designs'] ) ) {
		$new_design_id = $design_id . '_copy_' . time();
		$import['design_meta'][$design_id]['name'] .= ' (Copy)';
	} else {
		$new_design_id = $design_id;
	}
	
	// add to the storage db item
	$p3_store['designs'][] = $new_design_id;
	$p3_store['design_meta'][$new_design_id] = $import['design_meta'][$design_id];
	$p3_store[$new_design_id] = $import[$design_id];
	
	// non-design settings
	if ( $do_non_design ) {
		$p3['non_design'] = array_merge( (array) $p3['non_design'], (array) $import['non_design'] );
	}
	
	// import widgets, optionally
	if ( $import['widgets'] ) {
		define( 'IMPORTING_WIDGETS', true );		
		p3_import_widgets( unserialize( $import['widgets'] ) );
	}
	
	// update & return design id
	p3_store_options();
	$r = $import['design_meta'][$design_id];
	$r['design_id'] = $new_design_id;
	return $r;
}




/* generates export txt file and zip, if possible, returns links or helpful info */
function p3_export_design() {
	global $p3_store;
	$design_id = $_REQUEST['design'];
	
	// get all settings into single array and serialize
	$export['design_id']               = $design_id;
	$export['design_meta'][$design_id] = $p3_store['design_meta'][$design_id];
	$export[$design_id]                = $p3_store[$design_id];
	$export['non_design']              = $p3_store['non_design'];
	if ( p3_test( 'des_export_widgets', 'true' ) ) $export['widgets'] = p3_widget_export_data();

	$export = p3_strip_corrupters_deep( $export );
	$filecontent = serialize( $export );
	
	// write the design settings .txt file
	$file_name = p3_sanitize_for_design_id( $export['design_meta'][$design_id]['name'] );
	$file_info = p3_write_settings_file( $filecontent, $file_name, P3_DESIGNS_DIR );
	extract( $file_info );
	
	// could not create flat .txt file
	if ( $write_file_success != 1 ) {
		p3_msg( 'cant_create_export_txt_file', P3_DESIGNS_DIR, $export_folder_path );
	
	// txt file created, attempt to zip it with images
	} else {
		$to_zip   = $p3_store[$design_id]['images'];
		$to_zip[] = $export_file_path;
		$zip_url  = p3_zip_files( 'export_' . $file_name, $to_zip );
		echo p3_zip_result( $zip_url, $p3_store['design_meta'][$design_id]['name'], $export_file_url );
	}
}


/* return notice based on result of zipping export text file and images */
function p3_zip_result( $zip_url, $zip_name, $export_file_url ) {
	// no zip, but we do have a text file
	if ( !$zip_url )
		return p3_get_notice( 'txt_success_zip_failure', $export_file_url, P3_IMAGES_PATH );
	
	// "export everything" zip
	if ( strpos( $zip_name, 'export_everything' ) !== FALSE )
		return p3_get_msg( 'zip_everything_success', $zip_url );
	
	// single design export zip
	return p3_get_msg( 'zip_design_success', stripslashes( $zip_name ), $zip_url );
	
}


/* returns file path (without filename) of server filesystem path to file */
function p3_zipfile_path( $full_file_path ) {
	$file_name = strrchr( $full_file_path, '/' );
	$just_path = str_replace( $file_name, '/', $full_file_path );
	return $just_path;
}


/* create zip based on passed filepath array, returns zip url or FALSE on error */
function p3_zip_files( $zip_file_name, $file_array ) {
	$designs_folder_path = trailingslashit( P3_FOLDER_PATH . P3_DESIGNS_DIR );
	
	// load the pclzip class
	if ( !class_exists( 'PclZip' ) ) {
		define( 'PCLZIP_TEMPORARY_DIR', $designs_folder_path );
		require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
	}
	
	// remove leading "DRIVE:" on Win setups (needed for the PCLZIP_OPT_REMOVE_PATH option below)
	$p3_img_dir_path = preg_replace( '/^\S+:/', '', P3_IMAGES_PATH );
	$designs_folder_path = preg_replace( '/^\S+:/', '', $designs_folder_path );
	$zip_filepath = $designs_folder_path . $zip_file_name . '.zip';
	
	// delete export zip if it already exists
	@unlink( $zip_filepath );
	
	// prepend p3 image directory path if we've got just image filenames anywhere
	foreach ( $file_array as $key => $file_path ) {
		if ( strpos( $file_path, '/' ) === FALSE ) {
			$file_array[$key] = $p3_img_dir_path . $file_path;
		}
	}

	// create zip by adding a single file
	$starter_file_path = array_pop( $file_array );
	$zip = new PclZip( $zip_filepath );
	$zip->create( $starter_file_path, PCLZIP_OPT_REMOVE_PATH, p3_zipfile_path( $starter_file_path ) );
	if ( $zip == 0 ) die( "Error creating zip file <tt>$zip_filepath</tt>: " . $zip->errorInfo( TRUE ) );
	
	// continue adding all of the other files except mp3s
	foreach ( (array) $file_array as $key => $file_path ) {
		if ( @file_exists( $file_path ) && !nrIsIn( '.mp3', $file_path ) ) {
			$zip->add( $file_path, PCLZIP_OPT_REMOVE_PATH, p3_zipfile_path( $file_path ) );
		}
	}

	// return url of zip, or false if error
	if ( $zip->error_code == FALSE ) {
		return str_replace( $designs_folder_path, trailingslashit( P3_FOLDER_URL . P3_DESIGNS_DIR ), $zip->zipname );
	}
	return FALSE;
}


/* unzip and import an uploaded p3 design export zip */
function p3_import_uploaded_zip( $uploaded_zip ) {
	if ( strpos( $uploaded_zip, '.zip' ) === FALSE ) return p3_msg( 'invalid_design_zip', basename( $uploaded_zip ) );
	if ( !class_exists( 'PclZip' ) ) require_once( ABSPATH . 'wp-admin/includes/class-pclzip.php' );
	
	// create zip, look for p3 export .txt file
	$zip = new PclZip( $uploaded_zip );
	$contents = $zip->listContent();
	foreach ( $contents as $file_id => $file_array ) {
		if ( strpos( $file_array['filename'], '.txt' ) !== FALSE ) {
			$export_txt_file = basename( $file_array['filename'] );
			break;
		}
	}
	if ( !$export_txt_file ) return p3_msg( 'invalid_design_zip', basename( $zip->zipname ) );
	
	// $zip->extract( PCLZIP_OPT_PATH, P3_IMAGES_PATH, PCLZIP_OPT_REMOVE_PATH, $contents[0]['filename'] );
	$zip_result = $zip->extract( P3_IMAGES_PATH );
	if ( $zip_result == 0 ) return print( "Unzipping error: " . $zip->errorInfo( TRUE ) );
	
	// import
	echo '<p id="importing">importing...</p>';
	if ( !function_exists( 'p3_import_design' ) ) require_once( 'designs.php' );
	$export_txt_filepath = trailingslashit( P3_IMAGES_PATH ) . $export_txt_file;
	$new_design_info = p3_import_design( $export_txt_filepath, FALSE );
	
	// design successfully imported
	if ( $new_design_info ) {
		@rename( $export_txt_filepath, str_replace( '/export_', '/imported_', $export_txt_filepath ) );
		if ( defined( 'IMPORTING_WIDGETS' ) && IMPORTING_WIDGETS == true ) {
			p3_activate_design( $new_design_info['design_id'] );
			p3_refresh_parent_from_iframe();
		} else {
			p3_msg( 'design_upload_import_success', stripslashes( $new_design_info['name'] ) );
		}
	} else {
		print( 'Design import failed. Please try again.' );
	}
}


/* export all settings and images */
function p3_export_everything() {
	// write all stored settings from db to file
	$file_info = p3_write_all_settings( 'everything_' . time(), P3_DESIGNS_DIR );
	extract( $file_info );
	
	// could not create flat .txt file
	if ( $write_file_success != 1 ) {
		$msg = p3_get_msg( 'cant_create_export_txt_file', P3_DESIGNS_DIR, $export_folder_path );
	}
		
	// add images and text file to array
	$files   = glob( P3_IMAGES_PATH . '*.{jpg,JPG,jpeg,JPEG,gif,GIF,png,PNG}',  GLOB_BRACE );
	$files[] = $export_file_path;
	
	// zip file
	$zip_name = $export_file_name;
	$zip_url  = p3_zip_files( $zip_name, $files );
	echo p3_zip_result( $zip_url, $zip_name, $export_file_url );
}


/* start new design - iframed popup content */
function p3_start_new_design_form() {
	// form submitted, process POST and refresh parent page
	if ( isset( $_POST['p3_designs_action'] ) ) {
		p3_process_design_form_post();
		p3_refresh_parent_from_iframe();
	
	// iframe popup display
	} else {
		p3_start_new_design_markup();
	}
}


/* edit a design - iframed popup content */
function p3_edit_design_form() {
	global $p3_store;
	
	// process POST
	if ( isset( $_POST['p3_designs_action'] ) ) {
		p3_process_design_form_post();
		p3_refresh_parent_from_iframe();
		return;
	}
	
	// update form
	$design = $_GET['design'];
	$name   = stripslashes( $p3_store['design_meta'][$design]['name'] );
	$desc   = strip_tags( stripslashes( $p3_store['design_meta'][$design]['description'] ) );
	$nonce  = wp_nonce_field( 'p3-settings', '_wpnonce_p3', TRUE, NO_ECHO );
	echo <<<HTML
	<h3>Update Design Meta Info</h3>
	<form action="" method="post" accept-charset="utf-8">
		<p>Design name:<br />
			<input type="text" name="design_name" value="$name" id="design_name" size="41">
		</p>
		<p>Design description:<br />
			<textarea type="text" name="design_desc" id="design_desc" rows="6" style="width:410px">$desc</textarea>
		</p>
		<p><input type="submit" value="Update design info"></p>
		<input type="hidden" name="p3_designs_action" value="update_design_meta"/>
		<input type="hidden" name="layout" value="$design"/>
		$nonce
	</form>
HTML;
}


/* markup for "start a new design" popup iframe */
function p3_start_new_design_markup() {
	global $p3_store, $p3_starter_designs;
	
	// "Start a New Design" was clicked
	if ( !isset( $_GET['template'] ) ) {
		
		// templates select list
		foreach ( $p3_store['designs'] as $layout ) {
			if ( $p3_store['active_design'] == $layout ) {
				$checked = 'checked="checked"';
				$prefix = '<strong>Current design:</strong> ';
			} else {
				$checked = $prefix = '';
			}
			$design_nicename = stripslashes( $p3_store['design_meta'][$layout]['name'] );
			$template_radio_btns .= "<div class='option-wrap'><input value='$layout' type='radio' name='template' $checked /><label>{$prefix}$design_nicename</label></div>\n";
		}

		// starter designs
		foreach ( (array) $p3_starter_designs as $design_id => $design_name ) {
			$starter_design_markup .= "<div class='option-wrap starter-design'><input value='$design_id' type='radio' name='template'><label>Template: $design_name</label><img src='" . STATIC_RESOURCE_URL . "img/starter_design_{$design_id}.jpg' width='140' /></div>\n";
		}
	
	// design template thumb was clicked	
	} else {
		$starter_design = ' <strong>' . $p3_starter_designs[$_GET['template']] . '</strong>';
		$starter_design_markup = '<input type="hidden" name="template" value="' . $_GET['template'] . '" />';
		$starter_design_markup .= "<img id='the-template' src='" . STATIC_RESOURCE_URL . "img/starter_design_" . $_GET['template'] . ".jpg' />";
	}

	$nonce = wp_nonce_field( 'p3-settings', '_wpnonce_p3', TRUE, NO_ECHO );
	
	// echo markup
	echo <<<HTML
	<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		// disable multiple submissions
		jQuery('form').submit(function(){
			jQuery('input[type="submit"]').attr('disabled', 'disabled');
		});	
	});
	</script>
	<h3>Start a new design:</h3>			
	<form action="" method="post" accept-charset="utf-8">
		<input type="hidden" name="p3_designs_action" value="create_new_design"/>
		<p>New design name:<br />
			<input type="text" name="new_design_name" value="" id="new_design_name" size="41">
		</p>
		<p>Design description:<br />
			<textarea name="new_design_desc" rows="3" style="width:95%"></textarea>
		</p>
		<p>New design is based on:{$starter_design}<br />
			$template_radio_btns
			<div id="starter-design-wrap" class="self-clear">
				$starter_design_markup
			</div>
		</p>
		<p><input type="submit" value="Save new design"></p>
		$nonce
	</form>	
HTML;
}


/* fires a javascript function in the parent window that clears gallery meta */
function p3_refresh_parent_from_iframe() {
	p3_parent_window_js( 'location.href = win.location.href' );
}


/* clone a p3 design */
function p3_clone_design() {
	global $p3_store;
	
	// deal with POST
	if ( isset( $_POST['p3_designs_action'] ) ) {
		
		// no clone name set
		if ( empty( $_POST['design_name'] ) ) {
			unset( $_POST );
			p3_msg( 'clone_name_required' );
			p3_clone_design();
		
		// create clone
		} else {
			echo '<p>Copying design...</p>';
			p3_create_new_design( $_POST['layout'], $_POST['design_name'], $_POST['design_desc'] );
			p3_activate_design( $_POST['layout'] ); // reactivate cloned design
			p3_refresh_parent_from_iframe();
		}
	}

	// update form
	$design = $_GET['design'];
	$name   = 'Copied design: ' . $p3_store['design_meta'][$design]['name'];
	$desc   = 'Copied design: ' . strip_tags( $p3_store['design_meta'][$design]['description'] );
	$nonce  = wp_nonce_field( 'p3-settings', '_wpnonce_p3', TRUE, NO_ECHO );
	echo <<<HTML
	<h3>Copy Design</h3>
	<p>Copying a design creates an <strong>exact copy of a design</strong> and stores it in your inactive designs, where you can <strong>activate it later if you choose</strong>.</p>
	<p>This is helpful if you like your current design but want to keep experimenting -- <strong>copying creates a snapshot of how your design looks right now</strong> that you can switch back to later if you want.</p>
	<p style="margin-bottom:20px"><strong>Edit the design info below</strong> and click "Copy design" to save.</p>
	<form action="" method="post" accept-charset="utf-8">
		<p><strong>Design copy name:</strong><br />
			<input type="text" name="design_name" value="$name" id="design_name" size="41">
		</p>
		<p><strong>Design copy description:</strong><br />
			<textarea type="text" name="design_desc" id="design_desc" rows="6" style="width:410px">$desc</textarea>
		</p>
		<p><input type="submit" value="Copy design"></p>
		<input type="hidden" name="p3_designs_action" value="clone_design"/>
		<input type="hidden" name="layout" value="$design"/>
		$nonce
	</form>
HTML;
}


/* use viable $p3 var to recreate a $p3_store var in case of corruption */
function p3_recreate_p3_store() {
	global $p3, $p3_store;
	$active_design = $p3['active_design'];
	$pretty_name = ucwords( str_replace( '_', ' ', $active_design ) );
	$re['designs'] = array( $active_design );
	$re['design_meta'] = array( $active_design => array( 
		'name' => $pretty_name, 
		'description' => "Design: '$pretty_name' was restored by ProPhoto after recovering from an internal error." ) );
	$re['active_design'] = $active_design;
	$re['non_design'] = $p3['non_design'];
	$re[$active_design] = $p3[$active_design];
	$re = p3_strip_corrupters_deep( $re );
	if ( p3_safe_to_store( P3_DB_STORAGE_NAME, $re ) ) {
		update_option( P3_DB_STORAGE_NAME, $re );
	}
	$p3_store = $re;
}


/* gui form/mechanism for restoring from flat text file backups */
function p3_restore_from_backups() {
	
	// build form for selection of which backup to restore
	$weekly_backups = glob( P3_FOLDER_PATH . P3_BACKUP_DIR . '/export_weekly*.txt' );
	$incremental_backups = glob( P3_FOLDER_PATH . P3_BACKUP_DIR . '/export_incremental*.txt' );
	
	// form submitted, restore theme to backup requested
	if ( isset( $_POST['backup_to_restore'] ) || isset( $_GET['restore_most_recent']) ) {
		
		// restore most recent, or what requested through form
		if ( isset( $_GET['restore_most_recent'] ) ) {
			$backup_to_restore = array_pop( $incremental_backups );
		} else {
			$backup_to_restore = $_POST['backup_to_restore'];
		}
		
		// get backup data into variables for restoration
		$p3_store_backup = @file_get_contents( $backup_to_restore );
		$p3_store_backup_array = unserialize( $p3_store_backup );		
		$p3_backup = array(
			'non_design' => $p3_store_backup_array['non_design'],
			'active_design' => $p3_store_backup_array['active_design'],
			$p3_store_backup_array['active_design'] => $p3_store_backup_array[$p3_store_backup_array['active_design']],
		);
		
		// do restore
		p3_initialize_db_options();
		$p3_backup = p3_strip_corrupters_deep( $p3_backup );
		$p3_store_backup = p3_strip_corrupters_deep( $p3_store_backup );
		if ( p3_safe_to_store( P3_DB_OPTION_NAME, $p3_backup ) ) {
			update_option( P3_DB_OPTION_NAME, $p3_backup );
		} else {
			die( 'Backup not restored because data would not store properly.' );
			return;
		}
		if ( p3_safe_to_store( P3_DB_STORAGE_NAME, $p3_store_backup ) ) {
			update_option( P3_DB_STORAGE_NAME, $p3_store_backup );
		} else {
			die( 'Backup not restored because data would not store properly.' );
			return;
		}
		global $p3_restoring_backup, $p3;
		$p3_restoring_backup = TRUE;
		$p3 = $p3_backup;
		p3_store_options();
		p3_updated_msg( 'backup_restored' );
	}
	
	// build form elements for available incremental backups
	rsort( $incremental_backups );
	$num_incremental = count( $incremental_backups ); $index = 1;
	foreach ( $incremental_backups as $incremental_backup ) {
		if ( $index == 1 ) {
			$suffix = 'most recent';
			if ( !isset( $_POST['backup_to_restore'] ) ) $checked = 'checked="checked" ';
		}
		if ( $_POST['backup_to_restore'] == $incremental_backup ) {
			$checked = 'checked="checked" ';
		}
		if ( $index == $num_incremental ) $suffix = 'least recent';
		$incremental_backups_markup .= 
		"<input type='radio' name='backup_to_restore' {$checked}value='$incremental_backup' id='backup_to_restore'>
		 <label>Incremental backup <strong>#$index</strong> <span style='font-style:italic'>$suffix</span></label><br />";
		$checked = $suffix = '';
		$index++;
	}
	
	// build form elements for available weekly backups
	arsort( $weekly_backups );
	foreach ( $weekly_backups as $weekly_backup ) {
		if ( $_POST['backup_to_restore'] == $weekly_backup ) {
			$checked = 'checked="checked" ';
		}
		$week = substr( $weekly_backup, -6, -4 );
		$year = substr( $weekly_backup, -10, -6 );		
		$weekly_backups_markup .=
		"<input type='radio' name='backup_to_restore' {$checked}value='$weekly_backup' id='backup_to_restore'>
		<label>Archived backup, week <strong>$week, $year</strong></label><br />";
		$checked = '';
	}
	
	echo <<<HTML
	<div class="wrap p3-settings" svn="$svn">
		<div class="icon32" id="icon-tools"><br/></div>
		<h2>P3 Backup: restore customizations from automatic backups</h2>
		<form action="" method="POST">
			$incremental_backups_markup
			&nbsp;---<br />
			$weekly_backups_markup
			<input type="submit" name="submit" value="restore" id="submit" style="margin-top:10px">
		</form>
	</div>
HTML;
}

?>