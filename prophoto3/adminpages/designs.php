<?php
/* ----------------------- */
/* -- "P3 Designs" page -- */
/* ----------------------- */


/* the main function, prints "P3 Designs" page */
function p3_designs_page() {
	global $p3, $p3_store, $p3_starter_designs;
	do_action( 'p3_pre_designs_page' );

	if ( isset( $_GET['restore_from_backups'] ) ) return p3_restore_from_backups();
	if ( P3_TECH && $_GET['recreate_p3_store'] || !is_array( $p3_store ) ) p3_recreate_p3_store();
	
	$svn           = P3_SVN;
	$active_design = $p3['active_design'];
	$nonce         = wp_nonce_field( 'p3-settings', '_wpnonce_p3', TRUE, NO_ECHO );
		
	// process submitted form
	if ( isset( $_POST['p3_designs_action'] ) ) p3_process_design_form_post();

	p3_debug_report();

	// available designs
	$counter = 1;
	foreach ( (array) $p3_store['designs'] as $design ) {
		$export_href     = p3_popup_href( "do=export_design&design=$design&context=designs", '440', '300' );
		$edit_href       = p3_popup_href( "do=edit_design_form&design=$design&context=designs", '440', '315' );
		$clone_href      = p3_popup_href( "do=clone_design&design=$design&context=designs", '440', '470' );
		$edit_link       = "<a class='button-secondary thickbox' href='$edit_href'>Edit Info</a>";
		$export_link     = "<a class='button-secondary thickbox' href='$export_href'>Export</a>";
		$clone_link      = "<a class='button-secondary thickbox' href='$clone_href'>Copy</a>";
		$design_nicename = stripslashes( $p3_store['design_meta'][$design]['name'] );
		$design_desc     = stripslashes( $p3_store['design_meta'][$design]['description'] );
		$is_active       = ( $p3['active_design'] == $design ) ? TRUE : FALSE;
				
		// currently active design
		if ( $is_active ) {
			$active_design_prefix = '<span id="active-design-prefix">Active design name:</span> ';
			$design_desc_prefix = '<span id="active-design-desc-prefix">Design description:</span> ';
			$customize_btn = "<a class='button-secondary customize_design' href='" . p3_get_admin_url( 'background' ) . "'>Customize</a>";
			$active_class = ' active-design';
			$design_links = "
			<div class='edit-designs'>
				$clone_link
				$edit_link
				$export_link
				$customize_btn
			</div>";
		
		// inactive designs
		} else {
			$counter++;
			$active_design_prefix = $design_desc_prefix = '';
			$active_class = ' inactive-design';
			$design_links = "
			<div class='edit-designs'>
				<a class='button-secondary delete_design' action='delete_design' val='$design'>Delete</a>
				<a class='button-secondary activate_design' action='activate_design' val='$design'>Activate</a>
				$clone_link
				$edit_link
				$export_link
			</div>";
		}
		
		// force a break after every two to make quasi-table
		$br = ( $counter & 1 ) ? '<br style="clear:both" />' : '';
		
		$this_design = <<<HTML
		<div class="design{$active_class}">
			<h4>{$active_design_prefix}$design_nicename</h4>
			<p>{$design_desc_prefix}$design_desc</p>
			$design_links
		</div>
		$br
HTML;
		if ( $is_active ) {
			$active_design = $this_design;
		} else {
			$inactive_designs .= $this_design;
		}
	} 
	
	// inacative designs
	if ( $inactive_designs ) $inactive_designs_section = "
	<h3>Inactive designs</h3>
	<div class='self-clear'>$inactive_designs</div>";
	
	// start new design button
	$start_new_design_btn = "<a href='" . p3_popup_href( 'do=start_new_design_form&context=designs', '800', '690' ) . "' class='button-primary thickbox p3-design-btn'>Start a New Design</a>";
	
	// upload zip
	$upload_zip = "<a href='" . p3_popup_href( "do=upload&p3_image=design_upload&misc=true", '410', '110' ) . "' class='button-primary thickbox p3-design-btn'>Upload Design Zip</a>";
	
	// import from p2 button
	if ( p3_import_p2_layouts_markup() ) {
		$import_p2_explanation = ', or to import layouts from ProPhoto version 2';
		$import_from_p2_btn    = "<a href='" . p3_popup_href( 'do=p2import_form&context=designs', '470', '520' ) . "' class='button-primary thickbox p3-design-btn'>Import P2 Layouts</a>";
	}
	
	// import from P3 button
	if ( p3_available_design_imports() ) {
		$import_from_p3_btn = "<a href='" . p3_popup_href( 'do=import_design_form&context=designs', '640', '350' ) . "/' class='button-primary thickbox p3-design-btn'>Import P3 designs</a>";
	}
		
	// reset button
	if ( P3_DEV || P3_TECH || $_GET['show_reset'] == 'true'  ) {
		$reset_btn = "
		<form id='reset-everything' action='' method='post'>
			<input type='hidden' name='p3_designs_action' value='reset_everything'/>
			<input type='submit' value='Reset everything' class='button-secondary' />
			$nonce
		</form>";
	}
	
	// export all button display
	if ( P3_DEV || P3_TECH || $_GET['show_export'] == 'true'  ) {
		$export_everything_btn = "<a class='button-secondary thickbox' href='" . p3_popup_href( 'do=export_everything', '450', '175' ) . "'>Export everything</a>";
	}
	
	// design templates
	foreach ( (array) $p3_starter_designs as $design_id => $design_name ) {
		$href = p3_popup_href( 'do=start_new_design_form&context=designs&template=' . $design_id, '420', '470' );
		$img = "<img src='" . STATIC_RESOURCE_URL . "img/starter_design_{$design_id}.jpg' width='180' />";
		$design_templates .= "<a class='design-template thickbox' href='$href'>$img <p>$design_name</p></a>";
	}
	
	$designs_page_tut = DESIGNS_PAGE_TUT;
	$designs_page_markup = <<<HTML
	<div class="wrap p3-settings" svn="$svn">
		<div class="icon32" id="icon-themes"><br/></div>
		<h2>P3 Designs: save &amp; manage your customizations</h2>
		
		<p class="intro">This page allows you to manage saved groups of customization options, called <strong>"designs"</strong>.  If this is your first time using this page, <a href="$designs_page_tut">click here</a> to watch a short overview video showing you how everything works.</p>
		
		<div id="top-group" class="self-clear">
			<div id="active-design-wrap" class="self-clear">
				<h3>Active design</h3>
				<p class="secondary">Whenever you are saving customization decisions or uploading custom images, all of your design choices are being saved to your <strong>active design</strong>.  Information about your current active design is below:</p>
				$active_design
			</div>

			<div id="new-designs-wrap" class="self-clear">
				<h3>Create new designs</h3>
				<p class="secondary">Click below to start a new design{$import_p2_explanation}. You can always re-activate the current design if you change your mind.</p>
				<div>$start_new_design_btn</div>
				<div>$import_from_p2_btn</div>
				<div>$import_from_p3_btn</div>
				<div>$upload_zip</div>
				$export_everything_btn
				$reset_btn
			</div>
		</div>
					
		$inactive_designs_section
		
		<h3>Design templates</h3>
		<p class="secondary">ProPhoto3 comes with four built-in design-templates that you can use to start a new design of your own.</p>
		<div id="starter-designs" class="self-clear">
			$design_templates
		</div>
	
		<form id="misc_form" action="" method="post">
			<input class="action" type="hidden" name="p3_designs_action" value="">
			<input class="value" type="hidden" name="misc_value" value="" id="misc_value">
			<input type="submit" value="misc"/>
			$nonce
		</form>
	</div>
HTML;
	echo apply_filters( 'p3_designs_page_markup', $designs_page_markup );
	do_action( 'p3_post_designs_page' );
}

?>