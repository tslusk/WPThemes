<?php
/* ------------------------- */
/* FONT/LINK  CUSTOMIZATIONS */
/* ------------------------- */

p3_option_header( 'Overall Font & Link Font Options', 'fonts' );

//text appearance
p3_font_group( array( 
	'key' => 'gen',
	'title' => 'Overall font appearance',
	'add' => array( 'lineheight', 'margin_bottom' ),
	'margin_bottom_comment' => 'paragraphs',
) );

// generic link styling
p3_font_group( array( 
	'key' => 'gen_link',
	'title' => 'Overall link font appearance',
	'not' => array( 'family', 'size' ),
	'inherit' => 'all',
) );

// headlines
p3_font_group( array( 
	'key' => 'header',
	'title' => 'Overall headline appearance',
	'add' => array( 'letterspacing' ),
) );
	
?>