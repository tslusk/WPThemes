<?php
/* ----------------------------------- */
/* ----custom css for contact form---- */
/* ----------------------------------- */

// contact success/error message colors
$contact_success_bg_color   = p3_get_option( 'contact_success_bg_color' );
$contact_success_text_color = p3_get_option( 'contact_success_text_color' );
$contact_error_bg_color     = p3_get_option( 'contact_error_bg_color' );
$contact_error_text_color   = p3_get_option( 'contact_error_text_color' );

// optional contact area override colors
$contact_bg_css       = p3_bg_css( 'contact_bg', '#contact-form' );
$contact_text_color   = p3_color_css_if_bound( 'contact_text_color', '' );
$contact_header_color = p3_color_css_if_bound( 'contact_header_color', '' );

// bottom border
if ( !p3_test( 'contact_btm_border', 'off' ) ) $border_bottom_css = p3_border_css( 'contact_btm_border', 'bottom' );


/* Output the CSS */
echo <<<CSS
#p3-contact-success {
	background: $contact_success_bg_color;
}
#p3-contact-success p {
	color: $contact_success_text_color;
}
#p3-contact-error {
	background: $contact_error_bg_color;
}
#p3-contact-error p {
	color: $contact_error_text_color;
}
$contact_bg_css
#contact-form p {
	$contact_text_color
}
#main-wrap-inner #contactform div p {
	margin-bottom:0.2em;
}
#contact-form h2 {
	$contact_header_color
}
#contact-form {
	$border_bottom_css
}
#contact-form form  {
	padding:3.5% 3.5% 1.5% 3.5%;
	max-width:600px;
}
#contact-form textarea {
	width:95%;
}
#contact-form form.with-widget-content {
	margin-left:45%;
}
#contact-form #widget-content {
	padding:3.5% 3.5% 1.5% 4.5%;
	float:left;
	width:36%;
}
#contact-form div p,
#contact-form #widget-content p {
	margin-bottom:1.2em;
}
#contact-form h2 {
	margin-bottom:.4em;
}
#contact-form p {
	margin-bottom:0;
}
#contactform input, #contactform textarea {
	margin-bottom:10px;
}
.p3-contact-message p {
	padding:6px;
	text-align:center;
	margin-bottom:0;
	font-size:1.0em;
}
.p3-contact-message {
	display:none;
}
#contact-form .firstname {
	display:none !important;
}
#contact-form div.p3-has-error input, #contact-form div.p3-has-error textarea {
	border:red 2px solid;
}
div.p3-has-error span.required {
	color:red;
}
#invalid-email {
	color:red;
	margin-left:.5em;
	display:none;
}
div.p3-has-error #invalid-email {
	display:inline;
}
CSS;
?>