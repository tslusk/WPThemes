<?php
/* --------------------------------------------------------------- */
/* -- library of functions related to debugging and development -- */
/* --------------------------------------------------------------- */


/* check for and advise of common p3 installation/permissions problems */
function p3_self_check( $write_result = FALSE, $echo = TRUE ) {
	
	// check for nested prophoto3/prophoto3 problem which throws a wp-config require error
	if ( strpos( P3_THEME_URL, '/prophoto3/prophoto3' ) !== FALSE ) {
		return p3_error_msg( 'nested_theme_folder' );
	}
	
	// check for misnamed theme folder
	if ( strpos( P3_THEME_URL, '/prophoto3' ) === FALSE ) {
		return p3_error_msg( 'misnamed_theme_folder' );
	}
	
	// fix potentially screwed-up standard-looking upload path
	if ( strpos( WP_UPLOAD_PATH, 'wp-content/uploads' ) !== FALSE && get_option( 'upload_path' ) != 'wp-content/uploads' ) {
		update_option( 'upload_path', 'wp-content/uploads' );
	}

	// some sort of folder creation error
	if ( P3_FOLDERS_ERROR ) return p3_folder_errors();
	
	if ( !$write_result ) return;
	
	// static files created successfully
	if ( strpos( implode( '', $write_result ), '0' ) === FALSE  ) {
		return ( P3_DEV ) ? '' : p3_updated_msg( 'Options updated.' );
	
	// static file writing problem
	} else {
		return p3_error_msg( 'static_file_write_error' );
	}
}


/* diagnose and report folder error problems */
function p3_folder_errors() {
	// because no "uploads" folder
	if ( !file_exists( WP_UPLOAD_FULL_PATH ) ) {
		return p3_error_msg( 'cant_create_folder', NO_UPLOADS_FOLDER_TUT );
	
	// uploads exists, but no "p3" folder			
	} else if ( !file_exists( P3_FOLDER_PATH ) ) {
		return p3_error_msg( 'cant_create_folder', NO_P3_FOLDER_TUT );
	
	// safe mode problem
	} else if ( ini_get( 'safe_mode' ) ) {
		return p3_error_msg( 'safe_mode_subfolder_problem' );
		
	// um, if this happens, i want to know about it
	} else {
		return p3_error_msg( 'tell_jared_problem', 'p3_folder_errors' );
	}
}


/* the debug box printing out stored values in the DB */
function p3_debug_report() {
	if ( !P3_DEV && !P3_TECH ) return;
	global $p3, $wpdb;
	
	echo '<div id="debug-wrap" class="wrap">
	<div id="jared_debug">
	<p><pre class="updated">'."\n\n";
	
	$debug_array = ( $_GET['page'] == 'p3-customize' ) 
		? get_option( P3_DB_OPTION_NAME ) : get_option( P3_DB_STORAGE_NAME );
	p3_debug_printarray( $debug_array );
	
	echo "\n".'</pre>';
	
	$show = ( $_GET['show'] == 'ids' ) ? 'true' : 'false';
	
	echo <<<HTML
	<style type="text/css" media="screen">
		#devsave { display:inline; }
		#debug-wrap {
			display:none;
			border:1px solid #ccc;
			background:#ececec;
			padding:5px;
		}
		#show-debug {
			margin-left:15px;
			color:#999 !important;
			font-size:10px !important;
		}
		pre.updated {
			margin:0 5px 5px 5px !important;
			overflow:auto;
			max-height: 320px;
		}
		#form-weight-warn {
			opacity:.8;
			font-size:.5em;
			color:red;
			background:#fff;
			margin-left:20px;
			padding:1px 5px;
		}
	</style>
	<script type="text/javascript" charset="utf-8">
		var p3_debug = true;
		var show_ids = '$show';
		jQuery(document).ready(function(){
			jQuery('a[title="Visit Site"]').after('<a id="show-debug">P3 DEBUG</a>');
			jQuery('#show-debug').live('click', function(){
				jQuery('#debug-wrap').slideToggle();
			});
			if ( show_ids == 'true' ) jQuery('tt').show();
			jQuery('.options-grouping-header').click(function(){
				jQuery('tt').toggle();
			});
			jQuery('.option-section-label, .upload-row-label').click(function(){
				var option = jQuery(this).parents('.option');
				jQuery('tt', option).toggle();
			});

		});
	</script>
	</p></div></div>
HTML;
}


/* recursive debug box subroutine */
function p3_debug_printarray($ar, $group = '') {
	$var = ( $_GET['page'] == 'p3-customize' ) ? 'p3' : 'p3_store';
	// design meta info first
	if ( $ar['active_design'] ) {
		echo "\${$var}['active_design'] = \"{$ar['active_design']}\"\n***\n";
		unset( $ar['active_design'] );
		if ( $designs = $ar['designs'] ) {
			unset( $ar['designs'] );
			p3_debug_printarray( $designs, "['designs']" );
		}
		if ( $design_meta = $ar['design_meta'] ) {
			unset( $ar['design_meta'] );
			p3_debug_printarray( $design_meta, "['design_meta']" );
			echo "***\n";
		}
	}
	// rest of the data
	ksort( $ar );
	foreach( (array) $ar as $key => $val ) {
		if ( is_array( $val ) ) {
			p3_debug_printarray( $val, "{$group}['$key']" );
		} else {
			$val = str_replace( '<','&lt;', (string) $val );
			if ( $val ) {
				echo "\${$var}{$group}['$key'] = \"$val\"\n";
			}
		}
	}
}

?>