<?php


/* display helpful message when wp version too old */
function p3_wp_version_fail() {
	
	// setup vars and strings
	global $wp_version, $pagenow;
	$upgrade_tut = UPGRADING_WP_TUT;
	$goto_themes = '<a href="' . admin_url( 'themes.php' ) . '">click here</a>, to go to the Themes page, and then ';
	$click_to_deactivate = 'click on one of the "activate" buttons below a different theme at the bottom of the page (they are highlighted with a <span class="activatelink">green box</span> around them).';
	$only_admin_can_see = '<div id="note-to-admin"><strong>Only you</strong> are seeing this error message be cause <strong>you are logged in as an administrator</strong>.  Any <strong>blog visitors</strong> who see your page right now are getting a temporary maintenance message instructing them to come back in a few minutes.</div>';
	$visitor_msg = '<p style="text-align:center">Temporarily down for maintenance and upgrade. Please come back in a few minutes.</p>';
	$css = <<<HTML
	<style type="text/css" media="screen">
	.activatelink {
		white-space:nowrap;
		border:#4bd93f 3px solid;
		padding:0 2px 1px 2px;
	}
	.action-link .activatelink {
		padding: 3px;
	}
	#wp-version-fail {
		max-width:800px;
		background:#fee7e7;
		padding:10px 20px 20px 20px;
		margin:20px 20px 20px 0;
		border:1px solid red;
	}
	#wp-version-fail h1 {
		color:red;
	}
	#wp-version-fail p {
		font-size:14px;
	}
	#note-to-admin {
		width: 800px;
		padding:20px;
	}
	</style>
HTML;

	// deactivate_steps
	if ( $pagenow != 'themes.php' ) $deactivate = $goto_themes;
	$deactivate .= $click_to_deactivate;
	
	if ( !is_admin() ) {
		$meta = '<title>' . P3_SITE_NAME . '</title><meta name="description" content="' . P3_SITE_DESC . '" />';
		$head_close = '</head>';
	}
	
	// head and style elements
	echo "$meta\n$css\n$head_close\n";
	
	// show message to admin
	if ( current_user_can( 'level_1' ) ) {
		if ( !is_admin() ) $non_admin_note = $only_admin_can_see;
		echo <<<HTML
		<!--googleoff: all-->
		<div id="wp-version-fail">
			<h1>ERROR: WordPress version not supported</h1>
			<p><em>ProPhoto3</em> requires at least WordPress <strong>version 2.9 or higher</strong>. You are currently running <code>$wp_version</code>. To fix this follow these steps:</p>
			<p>First, <strong>bookmark the web address</strong> of this page: <a target="_blank" href="$upgrade_tut">our tutorial on upgrading WordPress</a>.</p>
			<p>Next, you must <strong>deactivate ProPhoto3</strong> before upgrading. To de-activate ProPhoto3,  $deactivate</p>
			<p>Then, follow the tutorial linked above to <strong>upgrade WordPress to the most current version</strong>.</p>
			<p>Finally, <strong>re-activate the ProPhoto3 theme</strong>.</p>
		</div><!-- #wp-version-fail -->
		$non_admin_note
		<!--googleon: all-->
HTML;

	// show under construction to blog visitors
	} else {
		echo $visitor_msg;
	}
	
	if ( !is_admin() ) die; // prevent fatal errors
}


?>