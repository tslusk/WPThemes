<?php

require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' ); // WP 2.6+

@header('Content-type: text/xml' );

$flash_speed = p3_get_option( 'flash_speed' );
$flash_order = p3_get_option( 'flash_order' );
$flash_fadetime = p3_get_option( 'flash_fadetime' );
$flash_loop = p3_get_option( 'flash_loop' );
$flashheader_transition_effect = p3_get_option( 'flashheader_transition_effect' );


for ( $counter = 1; $counter <= MAX_MASTHEAD_IMAGES; $counter++) {
	$img_name = 'masthead_image' . $counter;
	if ( !p3_image_exists( $img_name ) ) continue;

	// optional link url
	if ( p3_test( $img_name . '_linkurl' ) ) {
		$linkurl = '<![CDATA[ ' . p3_entered_url( $img_name . '_linkurl' ) . ' ]]>';
	} else {
		$linkurl = '';
	}
	
	// image path
	$path = p3_imageurl( $img_name, NO_ECHO );
	
	// echo out xml for this image
	$gallery_images .= "\t<galleryimage path=\"$path\">\n";
	$gallery_images .= "\t\t<linkURL>$linkurl</linkURL>\n";
	$gallery_images .= "\t</galleryimage>\n";
}

$output = <<<XML
<!-- do not edit this file, it is created by the theme, any edits will be lost -->
<gallery timer="$flash_speed" order="$flash_order" fadetime="$flash_fadetime" looping="$flash_loop" transtype="$flashheader_transition_effect">
$gallery_images
</gallery>
XML;

echo apply_filters( 'p3_imagesxml_output', $output ); ?>

