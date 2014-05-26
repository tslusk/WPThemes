<?php
/*
Server side processing of the contact form
*/


/* Access only on POST */
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	@header('HTTP/1.1 405 Method Not Allowed' );
	echo '<h1>Method Not Allowed</h1><p>The requested method is not allowed</p>';
	die();
}

// Load WordPress environment
require_once( dirname( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) ) . '/wp-load.php' );

// Check origin
check_admin_referer( 'p3-contact-form', '_wpnonce_p3' );

// allow plugins to do stuff here
do_action( 'p3_contact_pre_form_process' );

// set the variables
$name    = attribute_escape( $_POST['lastname'] );
$email   = attribute_escape( $_POST['email'] );
$phone   = attribute_escape( $_POST['phone'] );
$message = attribute_escape( $_POST['message'] );
$_POST['referpage'] = preg_replace( '/#.*$/', '', $_POST['referpage'] );


// referpage includes error hash unless it passes all the test
$referpage = apply_filters( 'p3_contact_early_referpage', attribute_escape( $_POST['referpage'] ) . '#error' );


// did the spambot fill out the invisible field?  ha ha!  caught you!
if ( !empty($_POST['firstname'] ) ) {
	@header( 'HTTP/1.1 403 Forbidden' );
	echo '<h1>Forbidden</h1><p>If you feel you got this message and should not, please try again.</p>';
	die();
}


// do some validation, first the spam question
if ( p3_test( 'contactform_antispam_enable', 'on' ) ) {
	if ( empty( $_POST['anti-spam'] ) ) {
		@header( "Location: $referpage" );
	  	exit ;	
	}
	$user_input = attribute_escape( strtolower( trim( $_POST['anti-spam'] ) ) );
	if ( !$user_input ) {
		@header( "Location: $referpage" );
	  	exit ;
	}
	// we have some input check it against acceptible answers
	$answers = p3_option( 'anti_spam_answer_' . intval( $_POST['spam_question'] ), 0 );
	$answers = explode( '*', strtolower( $answers ) );
	$spam = TRUE;
	foreach ( $answers as $answer ) {
		if ( $user_input == $answer ) $spam = FALSE;
	}
	if ( $spam ) {
		@header( "Location: $referpage" );
	  	exit ;
	}
}

// check for required fields
if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
   @header( "Location: $referpage" );
   exit ;
}

// is it an email address?
if ( p3_is_not_valid_email( $email ) ) {
   @header( "Location: $referpage" );
   exit ;	
}

// if custom fields are present and required, validate
for ( $i = 1; $i <= MAX_CONTACT_CUSTOM_FIELDS; $i++ ) { 
	if ( 
		p3_get_option( 'contact_customfield' . $i . '_label' ) && 
		p3_get_option( 'contact_customfield' . $i . '_required' ) == 'yes' && 
		empty( $_POST['custom-field' . $i] )
	) {
	   @header( "Location: $referpage" );
	   exit ;	
	}
}

// successful submission, change the hash
$referpage = $_POST['referpage'] . '#success';


if ( get_magic_quotes_gpc() ) {
	$message = stripslashes( $message );
}

// use custom email address, or snag the admin's email address
if ( p3_test( 'contactform_emailto' ) ) {
	$mailto = p3_get_option( 'contactform_emailto' );
} else {
	$user_info = get_userdata( 1 );
	$mailto = $user_info->user_email;
}

// make the subject
$subject = P3_SITE_NAME . " - blog contact form submission - " . $name;

// pull in the field labels
$name_label = p3_get_option( 'contactform_name_text' );
$email_label = p3_get_option( 'contactform_email_text' );
$message_label = p3_get_option( 'contactform_message_text' );
$custom_label = p3_get_option( 'contact_customfield_label' );

// create the email body
$email_text =
	$name_label . ": " .
	$name
	."\n\n " . $email_label . ": " .
	$email;
	
// custom fields
for ( $i = 1; $i <= MAX_CONTACT_CUSTOM_FIELDS; $i++ ) { 
	if ( p3_test( 'contact_customfield' . $i . '_label' ) ) {
		$email_text .= "\n\n" . p3_get_option( 'contact_customfield' . $i . '_label' ) . ':  ' . $_POST['custom-field' . $i];
	}
}

// message
$email_text .= "\n\n " . $message_label . ": \n\n " . $message;

// scrub funky characters
$email_text = p3_email_sanitize( $email_text );
$subject    = p3_email_sanitize( $subject );
$name       = p3_email_sanitize( $name );

// log to the db
if ( !$contact_log = get_option( P3_DB_CONTACT_LOG ) ) {
	add_option( P3_DB_CONTACT_LOG, '', '', 'no'  );
	$contact_log = array();
}
array_unshift( $contact_log, array( 'time' => time(), 'data' => $email_text ) );
update_option( P3_DB_CONTACT_LOG, $contact_log );

// more plugin hooks
$mailto = apply_filters( 'p3_contact_mailto', $mailto );
$subject = apply_filters( 'p3_contact_subject', $subject );
$email_text = apply_filters( 'p3_contact_email_text', $email_text );
$referpage = apply_filters( 'p3_contact_late_referpage', $referpage );
do_action( 'p3_contact_pre_email' );


// remote send for troublesome hosts
if ( p3_test( 'contact_remote_send', 'on' ) ) {
	wp_remote_post( 'http://prophotoblogtech.com/theme_email_send.php', array(
		'body' => array( 
			'email_to' => $mailto, 
			'email_from' => $email, 
			'subject' => $subject,
			'name' => $name, 
			'message' => $email_text
		)
	));
}

// ship it
if ( p3_test( 'contact_sendmail_from' ) ) ini_set( 'sendmail_from', p3_get_option( 'contact_sendmail_from' ) );
wp_mail( $mailto, $subject, $email_text, "Reply-To: \"$name\" <$email>" );
@header( "Location: $referpage" );
do_action( 'p3_contact_post_email' );

exit;


?>