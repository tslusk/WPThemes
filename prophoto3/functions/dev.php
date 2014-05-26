<?php
/* -------------------------------------------------- */
/* -- css/js/html testing and development workarea -- */
/* -------------------------------------------------- */

// p3_develop_css( 'contact' );
// p3_isolate_section( '#content, .post-header' );
// p3_dev_toolbar();


/* CSS */
echo "\n\n\n\n\n\n<!-- DEV CSS -->\n" . <<<HTML
<style type="text/css" media="screen">

</style>
HTML;




/* JAVASCRIPT */
echo "\n\n\n\n<!-- DEV JAVASCRIPT -->\n" . <<<HTML
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){

});
</script>
HTML;




/* ECHO TO PAGE */
echo "\n\n\n\n<!-- DEV OUTPUT -->\n<div id='console' style='background:white;color:red;font-family:monospace;padding-left:50px'>";

echo "\n</div>\n\n\n\n\n\n";

















































/* echo out css to isolate a certain section for viewing */
function p3_isolate_section( $selector ) {
	$isolate =  "\n" . <<<HTML
	<style type="text/css" media="screen">
	#logo-wrap, #topnav-wrap, #masthead-image-wrapper, #sidebar, #masthead,
	#content, .drawer, #footer, #copyright-footer, .post-header, #bio, .entry-comments, #addcomment {
		display:none;
	}
	$selector {
		display:block !important;
	}
	</style>
HTML;
	echo str_replace( array( "\n", "\t"), '', $isolate );
}


/* dev function, print array info of current p3 files for p3_file_check to use */
if ( $_GET['files'] ) _p3_dev_get_file_list();
function _p3_dev_get_file_list() {
	$dir_iterator = new RecursiveDirectoryIterator( TEMPLATEPATH );
	$iterator     = new RecursiveIteratorIterator( $dir_iterator, RecursiveIteratorIterator::CHILD_FIRST );
	echo '<pre>return array(';
	foreach ( $iterator as $file ) {
		if ( strpos( $file, '.svn' ) !== FALSE )      continue;
		if ( strpos( $file, '.DS_Store' ) !== FALSE ) continue;
		if ( strpos( $file, '.' ) === FALSE )         continue;
		if ( strpos( $file, '/source/' ) !== FALSE )  continue;
	    echo "'" . str_replace( TEMPLATEPATH, '', $file ) . "',";
	}
	echo ");\n</pre>"; die;
}


/* toolbar for dev */
function p3_dev_toolbar() {
	if ( $_SERVER['SERVER_ADMIN'] != 'netrivet@devmachine.com' ) return;
	if ( nrIsIn( 'MSIE', $_SERVER['HTTP_USER_AGENT'] ) ) return;
	$wp_url = P3_WPURL;
	$margin = p3_get_option( 'blog_top_margin' );
	$toolbar = <<<HTML
	<style type="text/css" media="screen">\
		html body {
			padding-top:0 !important;
		}
		p#dev-toolbar {
			text-align:center;
			padding:4px;
			color:white;
			background:grey;
			margin:0 !important;
			margin-bottom:{$margin}px !important;
			border:0 solid black;
			border-bottom-width:1px;
		}
		p#dev-toolbar a {
			color:white;
			padding:0 10px;
		}
	</style>
	<p id="dev-toolbar">
		<a href="{$wp_url}/?p3support=true">P3 Support</a>
		<a href="{$wp_url}/wp-admin/themes.php?page=p3-customize">P3 Customize</a>
		<a href="{$wp_url}/wp-admin/themes.php?page=p3-designs">P3 Designs</a>
		<a href="{$wp_url}/wp-admin/widgets.php">Widgets Admin</a>
		<a href="{$wp_url}/wp-admin/post-new.php">Add New Post</a>
		<a href="" style="float:right;" onclick="javascript:jQuery('#dev-toolbar').fadeOut();return false;">X</a>
	</p>
HTML;
	echo str_replace( array( "\n", "\t"), '', $toolbar );
}


/* reset theme for dev */
// if ( 0 && $_GET['reset_theme'] == 'jared' && $_SERVER['SERVER_ADMIN'] == 'netrivet@devmachine.com' ) {
	// $w = get_option( 'sidebars_widgets' );
	// $w['p3-contact-form'] = $w['bio-spanning-col'] = $w['bio-col-1'] 
	// 	= $w['bio-col-2'] = $w['bio-col-3']= $w['bio-col-4'] = array();
	// update_option( 'sidebars_widgets', $w );
// 	delete_option( 'p3theme_options' );
// 	delete_option( 'p3theme_storage' );
// 	die( 'P3 fully reset. <a href="' . admin_url( 'themes.php?activated=true' ) . '">Faux activate</a>.' );
// }


?>