<?php
/*
The General Options admin page
*/


// Draw the option page itself
function p3_options_page() {
	global $p3;
	
	// if form was submitted, handle POST information
	$write_result = p3_process_options(); 
	
	// print the debug box if we're in debug mode
	p3_debug_report(); 
	
	// array of main options tabs 'shortname' => 'prettyname'
	$blocks = array(
		'background' => 'Background',
		'fonts'      => 'Fonts',
		'header'     => 'Header',
		'menu'       => 'Menu',
		'contact'    => 'Contact',
		'bio'        => 'Bio',
		'content'    => 'Content',
		'comments'   => 'Comments',
		'sidebar'    => 'Sidebars',
		'widgets'    => 'Widgets',
		'footerz'    => 'Footer',
		'galleries'  => 'Galleries',
		'settings'   => 'Settings',
	);
	
	echo '<div id="options-page-content" class="wrap" svn="' . P3_SVN . '">';
	
	// p3_color_clipboard();

	// tell people they can't use IE6 to admin this theme
	echo NO_IE6_PLEASE;
	
	// thems icon and header
	echo '
	<div class="icon32" id="icon-themes"><br/></div>
	<h2>P3 Customize <a id="devsave" onclick="javascript:jQuery(\'#p3-options\').submit();">Save</a></h2>';
	
	
	
	// check for and advise of common problems
	p3_self_check( $write_result );
	
	// print main tab links
	echo '<ul id="maintabs" class="self-clear">';
	foreach ( $blocks as $anchor => $text ) {
		echo "<li><a class='tab-link' id='$anchor-link' href='themes.php?page=p3-customize&p3_tab=$anchor'>$text</a></li>\n";
	}
	echo '</ul>';

	// start the form
	$sameline_class = ( p3_logo_masthead_sameline() ) ? 'sameline' : '';
	echo '<form id="p3-options" method="post" action="" curtab="' . $p3_tab . '" class=' . $sameline_class . '>';
	wp_nonce_field('p3-options' );
	
	// print options for tab
	$p3_tab = ( isset( $_GET['p3_tab'] ) ) ? $_GET['p3_tab'] : 'background';
	include( "options/$p3_tab.php" );
	do_action( "p3_options_post_tab_{$p3_tab}" );
		
	// save button, close form and html
	echo '<a name="save-reset" class="save-reset"></a>
	<p class="submit self-clear"><input id="p3-save-changes" type="submit" value="Save Changes" name="Submit" class="button-primary"/>
	<input type="hidden" value="update" name="p3-options"/></p>
	</form>
	</div>
	';
	
}

?>