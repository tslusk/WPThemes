<?php
/* ----------------------------------------------------- */
/* -- load function libraries, constants and settings -- */
/* ----------------------------------------------------- */




/* dependencies */
require_once( dirname( __FILE__ ) . '/includes/constants.php' );
require_once( dirname( __FILE__ ) . '/includes/settings/notices.php' );
require_once( dirname( __FILE__ ) . '/functions/version.php' );

/* wp 2.9+ required */
if ( WP_VERSION_FAIL ) {
	if ( is_admin() ) add_action( 'admin_notices', create_function( '', 'p3_wp_version_fail();' ) );
	
} else {
	
	/* other files depend on these */
	require_once( dirname( __FILE__ ) . '/functions/general.php' );
	require_once( dirname( __FILE__ ) . '/functions/folders.php' );

	/* sandbox/dev functions */
	require_once( dirname( __FILE__ ) . '/functions/sandbox.php' );

	/* more function libraries */
	require_once( dirname( __FILE__ ) . '/functions/nav.php' );
	require_once( dirname( __FILE__ ) . '/functions/flashgal.php' );
	require_once( dirname( __FILE__ ) . '/functions/lightboxgal.php' );
	require_once( dirname( __FILE__ ) . '/functions/widgets.php' );
	require_once( dirname( __FILE__ ) . '/functions/sidebar.php' );

	/* conditionally loaded function libraries */
	// only loaded when viewing a front-facing, non-admin page  
	if ( !is_admin() ) {
		require_once( dirname( __FILE__ ) . '/functions/comments.php' );
		require_once( dirname( __FILE__ ) . '/functions/template.php' );
	}

	// only loaded on "P3 Layouts" page
	if ( is_admin() && $pagenow == "themes.php" && ( $_GET['page'] == 'p3-designs' ) ) {
		require_once( dirname( __FILE__ ) . '/adminpages/designs.php' );
		require_once( dirname( __FILE__ ) . '/functions/designs.php' );
		require_once( dirname( __FILE__ ) . '/functions/import_p2.php' );
		require_once( dirname( __FILE__ ) . '/includes/settings/p2names.php' );
	}

	// only loaded on "P3 Options" page
	if ( is_admin() && $pagenow == "themes.php" && ( $_GET['page'] == 'p3-customize' ) ) {
		require_once( dirname( __FILE__ ) . '/adminpages/options.php' );
		require_once( dirname( __FILE__ ) . '/functions/options.php' );
		require_once( dirname( __FILE__ ) . '/functions/upload.php' );
		require_once( dirname( __FILE__ ) . '/includes/settings/interface.php' );
	}

	// only when importing a P2 layout
	if ( $pagenow == 'popup.php' && $_GET['do'] == 'p2import_form' ) {
		require_once( dirname( __FILE__ ) . '/functions/import_p2.php' );
		require_once( dirname( __FILE__ ) . '/includes/settings/p2names.php' );
	}

	/* load settings */
	require_once( dirname( __FILE__ ) . '/includes/settings/options.php' );
	require_once( dirname( __FILE__ ) . '/includes/settings/images.php' );
	require_once( dirname( __FILE__ ) . '/includes/settings/misc.php' );
}

?>