<?php
/* -- master file for writing CSS */

// load WordPress environment
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );

@header( 'Content-type: text/css' );
echo '/* ProPhoto3 build #' . P3_SVN . " */\n\n";


/* sandbox, examine first */


// include individual stylesheet sub-files
require( 'css/hardcoded.css' );
require( 'css/general.css.php' );
require( 'css/header.css.php' );
require( 'css/bio.css.php' );
require( 'css/widgets.css.php' );
require( 'css/nav.css.php' );
require( 'css/contact.css.php' );
require( 'css/postheader.css.php' );
require( 'css/content.css.php' );
if ( p3_test( 'comments_enable', 'true' ) ) require( 'css/comments.css.php' );
require( 'css/sidebar.css.php' );
require( 'css/footer.css.php' );

// plugin hook
do_action( 'p3_post_customcss' );

// add custom user files, if present
echo strip_tags( p3_get_option( 'override_css' ) ); ?>