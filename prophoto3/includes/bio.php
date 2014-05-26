<?php $bio_display = ( p3_bio_is_minimized() ) ? ' style="display:none;"' : ''; ?>
<div id="bio" class="self-clear"<?php echo $bio_display ?>>
	<div id="bio-inner-wrapper" class="self-clear">
		<div id="bio-content" class="self-clear">
<?php

// random bio pics, function call to prevent onready switch src delay
if ( p3_using_random_biopics() ) {
	echo '<script type="text/javascript" charset="utf-8">p3_randomize_biopic();</script>';
	$noscript_open = '<noscript>';
	$noscript_close = '</noscript>';
}

// bio picture
if ( !p3_test( 'biopic_display', "off" ) ) {
	$biopic_src = p3_imageurl( 'biopic1', NO_ECHO );
	$biopic_html = p3_imageHTML( 'biopic1', NO_ECHO );
	$p3_site_name = P3_SITE_NAME;
	echo <<< HTML
	$noscript_open
		<img id="biopic" src="$biopic_src" $biopic_html alt="$p3_site_name bio picture" class="bio-col" />
	$noscript_close
HTML;
}

// spanning column
if ( is_active_sidebar( 'bio-spanning-col' ) ) {
	echo "<ul id='bio-widget-spanning-col'>";
	dynamic_sidebar( 'Bio Area Spanning Column' );
	echo '</ul>';
}

// echo out widget columns
echo '<div id="bio-widget-col-wrap" class="self-clear">';
for ( $i = 1; $i <= MAX_BIO_WIDGET_COLUMNS; $i++ ) { 
	if ( is_active_sidebar( 'bio-col-' . $i ) ) {
		echo "<ul id='bio-widget-col-$i' class='bio-col bio-widget-col'>";
		dynamic_sidebar( 'Bio Area Column #' . $i );
		echo '</ul>';
	}
}
echo '</div>';
?>
		</div><!-- #bio-content -->
	</div><!-- #bio-inner-wrapper -->	
	<?php if ( p3_test( 'bio_border', 'image' ) ) { ?>
	<div id="bio-separator" class="self-clear"></div>	
	<?php } ?>
</div><!-- #bio-->