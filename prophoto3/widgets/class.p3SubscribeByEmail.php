<?php


class P3_Subscribe_By_Email extends WP_Widget {
	
	// widget setup
	function P3_Subscribe_By_Email() {
		$widget_ops = array( 'classname' => 'p3-subscribe-by-email', 'description' => 'Feedburner "Subscribe by Email" widget' );
		$this->WP_Widget( 'p3-subscribe-by-email', 'P3 Feedburner Subscribe by Email', $widget_ops );
	}

	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		$title         = apply_filters('widget_title', $instance['title'] );
		$message       = ( $instance['message'] ) ? '<p>' . $instance['message'] . '</p>' : '';
		$feedburner_id = p3_feedburner_id();
		$language      = p3_get_option( 'subscribebyemail_lang' );
		$submit_text   = $instance['submit_text'];
			
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		
		$markup = <<<HTML
		<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="_blank">
			<p><input type="text" style="width:150px" name="email" /></p>
			<p style="margin-top:-8px;">$message</p>
			<input type="hidden" value="{$feedburner_id}" name="uri" />
			<input type="hidden" name="loc" value="{$language}" />
			<input type="submit" value="{$submit_text}" />
		</form>
HTML;
		echo apply_filters( 'p3_subscribebyemail_markup', $markup, $instance );

		echo $after_widget;
	}
	
	// update widget settings
	function update( $new_instance, $old_instance ) {
		return apply_filters( 'p3_subscribebyemail_update', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) {

		
		// option defaults
		$defaults = array( 
			'title' => 'Subscribe by Email',
			'message' => 'Enter your email below to get notifications of blog updates by email.',
			'submit_text' => 'Subscribe',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		// build options for language select
		$lang_params = explode( '|', FEEDBURNER_LANG_OPTIONS );
		$num_params  = count( $lang_params );
		for ( $i = 1; $i <= $num_params; $i = $i + 2 ) { 
			$value = $lang_params[$i-1];
			$name  = $lang_params[$i];
			$lang_options .= "<option value='$value'>$name</option>\n";
		}
		
		p3_widget_help_link( 'Feedburner Subscribe by Email' );
		
		if ( !p3_test( 'feedburner' ) ) {
			return p3_msg( 'requires_feedburner_feed', p3_get_admin_url( 'content', 'feed' ) ) . '</p>';
		}
		
		ob_start(); ?>
		
	<div class="p3-subscribe-by-email">

		<div class="p3-subscribe-by-email-form">
			
			<p style="font-style:italic;font-size:.9em"><?php p3_msg( 'requires_email_subscription_enabled' ) ?></p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (optional):</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'submit_text' ); ?>">Submit button text:</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'submit_text' ); ?>" name="<?php echo $this->get_field_name('submit_text'); ?>" type="text" value="<?php echo $instance['submit_text']; ?>" />
			</p>		
			
			<p>
				<label for="<?php echo $this->get_field_id( 'message' ); ?>">Message (optional):</label>
				<textarea class="widefat" rows="6" id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>"><?php echo $instance['message'] ?></textarea>
			</p>
					
		</div> <!-- .p3-subscribe-by-email-form -->		
	</div> <!-- .p3-subscribe-by-email -->	
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_subscribebyemail_form', $form, $instance );
	}
}
?>