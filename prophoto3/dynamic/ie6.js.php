<?php
/* load WordPress environmment */
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );

@header('Content-type: text/javascript' );

echo <<<JAVASCRIPT

// deal with semi-transparent .png files
DD_belatedPNG.fix('#logo-img, .content-bg, #main-wrap-inner, #main-wrap-outer, .dropshadow-center, .dropshadow-corner, .p3-png, .pi-link, #biopic, .comments-header');

jQuery(document).ready(function(){
	// suckerfish classes
	jQuery('#topnav li').hover(function(){
		jQuery(this).addClass('sfhover');
	},function(){
		jQuery(this).removeClass('sfhover');
	});
});
JAVASCRIPT;

// plugin hook
do_action( 'p3_post_ie6js' );
?>