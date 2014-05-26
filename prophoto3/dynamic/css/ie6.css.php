<?php
/* css browser hacks for IE6 */

// load WordPress environment
require_once( dirname(dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) ) . '/wp-load.php' ); // WP 2.6+
@header('Content-type: text/css' ); ?>
/* browser hacks for IE6 (I can't believe I'm still supporting this browser) */

<?php if ( !p3_test( 'nav_align', 'left' ) ) { ?>
#topnav {
	display:inline;
}
<?php } ?>
#subscribebyemail-nav-input {
	margin-right:-9px;
}
.bio-col,
#biopic,
.bio-col li.widget {
	display:inline;
}
.self-clear {
	height: 1%;
}
a.icon-link {
	cursor:pointer;
}
#contact-form #widget-content {
	display:inline-block;
}
<?php

// content bg IE6 fallback color for semi-transparent PNG usage
if ( p3_optional_color_bound( 'body_bg_ie6_color' ) ) { ?>
#main-wrap-inner #content-wrap .content-bg {
	background-image:none !important;
	background-color:<?php p3_option( 'body_bg_ie6_color' ) ?> !important;
}
<?php
}

// plugin hook
do_action( 'p3_post_ie6css' );

?>