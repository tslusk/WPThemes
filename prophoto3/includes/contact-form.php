<?php
// Load WordPress environment
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );
do_action( 'p3_pre_contact_form' );

$p3_theme_url = P3_THEME_URL;
$name_text    = p3_get_option( 'contactform_name_text' );
$required     = '<span class="required">' . p3_get_option( 'contactform_required_text' ) . '</span>';
$email_text   = p3_get_option( 'contactform_email_text' );
$message_text = p3_get_option( 'contactform_message_text' );
$submit_text  = p3_get_option( 'contactform_submit_text' );
$refer_page   = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$valid_email  = p3_get_option( 'contact_form_invalid_email' );
$nonce        = wp_nonce_field( 'p3-contact-form', '_wpnonce_p3', TRUE, NO_ECHO );

if ( p3_test( 'contactform_yourinformation_text' ) ) {
	$yourinformation_header = '<h2>' . p3_get_option( 'contactform_yourinformation_text' ) . '</h2>';
}

if ( p3_test( 'contactform_yourmessage_text' ) ) {
	$yourmessage_header = '<h2>' . p3_get_option( 'contactform_yourmessage_text' ) . '</h2>';
}

// custom fields
for ( $i = 1; $i <= MAX_CONTACT_CUSTOM_FIELDS; $i++ ) {
	if ( !p3_get_option( 'contact_customfield' . $i . '_label' ) ) continue; 
	$custom_field_label = p3_get_option( 'contact_customfield' . $i . '_label' );
	if ( p3_test( 'contact_customfield' . $i . '_required', 'yes' ) ) {
		$this_required_text = $required;
		$this_reauired_class = ' p3-required-field';
	} else {
		$this_required_text = $this_reauired_class = '';
	}
	$custom_fields_markup .= <<<HTML
	<div class="p3-field{$this_reauired_class}">
		<p><label for="custom-field{$i}">$custom_field_label $this_required_text</label></p>
		<input id="custom-field{$i}" size="35" name="custom-field{$i}" type="text"  />
	</div>
HTML;
}

// anti-spam
if ( p3_test( 'contactform_antispam_enable', 'on' ) ) {
	$spam_num = rand( 1, 3 );
	$spam_label = p3_get_option( 'anti_spam_question_' . $spam_num );
	$spam_required = '<span class="required">' . p3_get_option( 'anti_spam_explanation' ) . '</span>';
	$anti_spam = <<<HTML
	<div class="p3-field p3-required-field">
		<p><label for="anti-spam">$spam_label $spam_required</label></p>
		<input id="anti-spam" size="35" name="anti-spam" type="text"  />
		<input type="hidden" name="spam_question" value="$spam_num" />
	</div>
HTML;
}


// widgetized content
if ( is_active_sidebar( 'p3-contact-form' ) ) {
	$form_class = ' class="with-widget-content"';
	echo '<ul id="widget-content">';
	dynamic_sidebar( 'Contact Form Content Area' );
	echo '</ul>';
}


// output form
$form_markup = <<<HTML

<form id="contactform" action='{$p3_theme_url}/includes/contact-form-process.php' method='post'{$form_class}>
		
	$yourinformation_header
	
	<div class="p3-field">
		<p class="firstname"><label for="firstname">First name (required)</label></p>
	<input id="firstname" size="35" name="firstname" type="text" class="firstname" />
	</div>	
	
	<div class="p3-field p3-required-field">
		<p><label for="lastname">$name_text $required</label></p>
		<input id="lastname" size="35" name="lastname" type="text" />
	</div>
	
	<div class="p3-field p3-required-field">
		<p><label for="email">$email_text $required</label></p>
		<input id="email" size="35" name="email" type="text" /><span id="invalid-email">$valid_email</span>
	</div>
	
	$custom_fields_markup

	$anti_spam
	
	$yourmessage_header
	
	<fieldset>
		<div class="p3-field p3-required-field">
			<p><label for="message">$message_text $required</label></p>
			<textarea id="message" name="message" rows="10"></textarea>
		</div>
	</fieldset>
	
	<input type="hidden" id="referpage" name="referpage" value="$refer_page" />
	
	<input type='submit' name='submit' value='$submit_text' />
	
	$nonce
</form>
HTML;
echo apply_filters( 'p3_contact_form_filter', $form_markup );
do_action( 'p3_post_contact_form' );

?>