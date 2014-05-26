<?php
/* ----------------------- */
/* ---CONTACT  OPTIONS---- */
/* ----------------------- */

echo <<<HTML
<style type="text/css" media="screen">
#tab-section-contact-link .option, #subgroup-nav {
	display:none;
}
</style>
<script type="text/javascript" charset="utf-8">
jQuery(document).ready(function(){
	function p3_contact_display_ux( choice ) {
		if ( choice == 'yes' ) {
			jQuery('#tab-section-contact-link .option, #subgroup-nav').show();
		} else {
			jQuery('#tab-section-contact-link .option, #subgroup-nav').hide();
		}
		jQuery('#contactform_yesno-option-section').show();
	}
	var contact_display = jQuery('#contactform_yesno-option-section input:checked').val();
	p3_contact_display_ux( contact_display );
	jQuery('#contactform_yesno-option-section input').click(function(){
		p3_contact_display_ux( jQuery(this).val() );
	});
});
</script>
HTML;

// tabs and header
p3_subgroup_tabs(  array(
	'options' => 'General Options',
	'form' => 'Text &amp; Fields',
	'log' => 'Log',
) );

p3_option_header('Contact Form Options', 'contact' );


/* options subgroup */
p3_option_subgroup( 'options' );

// include/don't include contact form
p3_o('contactform_yesno', 'radio|yes|Include the built-in contact form|no|Do not include the built-in contact form', '', 'Include contact form?' );

// bg
p3_bg( 'contact_bg', 'Contact form background color &amp; image' );

p3_start_multiple( 'Contact form bottom border' );
p3_o( 'contact_btm_border', 'radio|on|show border|off|no border', 'show/hide a custom border below the contact section' );
p3_border_group( array( 'key' => 'contact_btm_border', 'comment' => 'bottom border appearance' ) );
p3_stop_multiple();

// text and background colors
p3_start_multiple( 'Contact form text colors' );
p3_o( 'contact_header_color', 'color|optional', 'color of contact form headers' );
p3_o( 'contact_text_color', 'color|optional', 'color of contact form text' );
p3_stop_multiple();

// form error and success messages
p3_start_multiple( 'Contact form submitted success message' );
p3_o( 'contact_success_msg', 'text|33', 'text displayed when contact form successfully submitted' );
p3_o( 'contact_success_bg_color', 'color', 'color of background of success message area' );
p3_o( 'contact_success_text_color', 'color', 'color of text of success message' );
p3_stop_multiple();
p3_start_multiple( 'Contact form submitted error message' );
p3_o( 'contact_error_msg', 'text|33', 'text displayed when contact form error' );
p3_o( 'contact_error_bg_color', 'color', 'color of background of error message area' );
p3_o( 'contact_error_text_color', 'color', 'color of text of error message' );
p3_stop_multiple();

// contact email to
p3_o( 'contactform_emailto', 'text|60', 'email address that contact form submissions are mailed to: if left blank, will go to WordPress admin user email address', 'Contact form email address' );

// disable ajax
p3_start_multiple( 'Contact form troubleshooting tweaks' );
p3_o( 'contactform_ajax', 'radio|off|Loading simple mode on|on|Loading simple mode off', 'turn on "Simple mode" if your contact form is not <strong>loading correctly</strong> even after trying all of the fixes <a href="' . CONTACT_FORM_PROBLEMS_TUT . '">here</a>.', 'Contact form simple mode' );

// troubleshooting tweaks
$host_parts = explode( '.', $_SERVER['HTTP_HOST'] );
$email_domain = $host_parts[count($host_parts)-2] . '.' . $host_parts[count($host_parts)-1];
p3_o( 'contact_sendmail_from', 'text|50', 'if your contact form is submitting successfully, but <strong>you are not receiving email</strong>, enter a <span style="text-decoration:underline">valid email address</span> with the same domain name as this blog, something like <strong>example@' . $email_domain . '</strong> - but the email must actually exist' );
p3_o( 'contact_blank', 'blank' );
p3_o( 'contact_remote_send', 'radio|off|standard email sending|on|enable remote sending', 'as a last resort, if you don\'t get emails from your contact form even after trying all of the fixes described <a href="' . CONTACT_FORM_PROBLEMS_TUT . '">here</a>, enabling remote sending may cause the emails to come through' );

p3_stop_multiple();
p3_end_option_subgroup();



/* form subgroup */
p3_option_subgroup( 'form' );

// content
p3_o( 'contact_note', 'note', 'Content for the left column of your contact area is handled through the <a href="' . admin_url( 'widgets.php' ) . '">widgets admin area</a>. Drag and edit any type of widget into the right-side widget area called <strong>"Contact Form Content Area"</strong>.' );


// custom fields
p3_start_multiple( 'Custom fields' );
for ( $i = 1; $i <= MAX_CONTACT_CUSTOM_FIELDS; $i++ ) { 
	p3_o( 'contact_customfield' . $i . '_label', 'text|29', 'label for a custom form field ' );
	p3_o( 'contact_customfield' . $i . '_required', 'radio|yes|yes, required|no|no, not required', 'is this a required field for the user to fill out?' );
	p3_o( 'blank', 'blank' );
	
}
p3_stop_multiple();

// form labels and headers
p3_start_multiple( 'Contact form text' );
p3_o( 'contactform_yourinformation_text', 'text|32', 'headline text for top part of contact form' );
p3_o( 'contactform_yourmessage_text', 'text|32', 'headline text for bottom part of contact form' );
p3_o( 'contactform_name_text', 'text|25', 'Label for "Name" input field' );
p3_o( 'contactform_email_text', 'text|25', 'Label for "Email" input field' );
p3_o( 'contactform_message_text', 'text|25', 'Label for the "Message" input field' );
p3_o( 'contactform_required_text', 'text|25', 'text shown when field is required' );
p3_o( 'contactform_submit_text', 'text|25', 'text on "submit" button' );
p3_o( 'contact_form_invalid_email', 'text|25', 'text shown when invalid email submitted' );
p3_o( 'anti_spam_explanation', 'text|25', 'text shown to explain anti-spam challenge' );
p3_stop_multiple();

// anti spam questions	
p3_o('contactform_antispam_enable', 'radio|on|enabled|off|disabled', 'Enable/disable the contact form anti-spam challenges.  If you are having any issues with spam coming from your contact form (as opposed to blog comment spam) then be sure to leave this enabled', 'Anti-spam challenges' );
p3_start_multiple( 'Anti-spam questions' );
p3_o( 'anti_spam_question_1', 'text|32', 'random challenge #1 - asked to prove form submitted by human and not spam bot' );
p3_o( 'anti_spam_answer_1', 'text|18', 'answer to anti-spam challenge #1 <br /><em>not case-sensitive, if multiple answers are correct, separate with an asterisk like &quot;4*four&quot;</em>' );
p3_o( 'anti_spam_blank_1', 'blank' );
p3_o( 'anti_spam_question_2', 'text|32', 'random challenge #2 - asked to prove form submitted by human and not spam bot' );
p3_o( 'anti_spam_answer_2', 'text|18', 'answer to anti-spam challenge #2 <br /><em>not case-sensitive, if multiple answers are correct, separate with an asterisk like &quot;4*four&quot;</em>' );
p3_o( 'anti_spam_blank_2', 'blank' );
p3_o( 'anti_spam_question_3', 'text|32', 'random challenge #3 - asked to prove form submitted by human and not spam bot' );
p3_o( 'anti_spam_answer_3', 'text|18', 'answer to anti-spam challenge #3 <br /><em>not case-sensitive, if multiple answers are correct, separate with an asterisk like &quot;4*four&quot;</em>' );
p3_stop_multiple();

p3_end_option_subgroup();


/* log subgroup */
p3_option_subgroup( 'log' );

p3_o( 'contact_log_note', 'note', 'Some webhosting server setups don\'t correctly handle code-generated email like what is sent by ProPhoto when the Contact form is submitted.  This log will allow you to <strong>look through all of the submissions made through your contact form, in case you did not receive the email</strong> for any reason.', 'About the Contact Form log' );
p3_o( 'contact_log', 'function|p3_contact_log' );

p3_end_option_subgroup();


?>