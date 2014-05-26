<?php

/* twitter.com javascript widget */
class P3_Twitter_Widget extends WP_Widget {
		
	// widget setup
	function P3_Twitter_Widget() {
		$this->WP_Widget( 'p3-twitter-com-widget', 'P3 twitter.com Widget', array( 'description' => 'Add a customized Twitter Profile, Search, Faves, or List widget.' ),  array( 'width' => '300' ) );
	}	
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		if ( $title ) echo $before_title . $title . $after_title;
		echo $before_widget;
		$markup = '<div>' . $instance['widget_code'] . '</div>';
		echo apply_filters( 'p3_twittercom_markup', $markup, $instance );
		echo $after_widget;
	}

	// update widget settings
	function update( $new_instance, $old_instance ) {
		
		// deal with width
		preg_match( '/width: ([^,]+),/', $new_instance['widget_code'], $matches );
		if ( !$new_instance['width'] ) {
			$new_instance['width'] = $matches[1];
		} else if ( $new_instance['width'] != $matches[1] ) {
			$new_instance['widget_code'] = preg_replace( '/width: ([^,]+),/', "width: " . $new_instance['width'] . ",", $new_instance['widget_code'] );
		}


		// add modify the footer "follow_text" js param
		if ( strpos( $new_instance['widget_code'], 'footer:' ) === FALSE ) {
			$new_instance['widget_code'] = str_replace( 'new TWTR.Widget({', "new TWTR.Widget({\n  footer: '" . $new_instance['follow_text'] . "',", $new_instance['widget_code'] );
		} else {
			$new_instance['widget_code'] = preg_replace( '/footer: ([^,]+),/', "footer: '" . $new_instance['follow_text'] . "',", $new_instance['widget_code'] );
		}
		
		return apply_filters( 'p3_twittercom_update', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) { 
		
		$defaults = array( 
			'follow_text' => 'Follow Me', 
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		p3_widget_help_link( 'twitter.com' );
		
		ob_start();  ?>
		
		<p>To create a Twitter Widget, go to <a href="<?php echo TWITTER_WIDGET_URL ?>" target="_blank">this page</a> and then click "My Website" to select and customize your Twitter widget.  When you're done, click to get the code, copy it, <strong>and paste it below</strong>.</p>
			
		<textarea style="font-size:.9em;font-family:Courier,monospace;margin-bottom:1em" class="widefat" rows="12" cols="20" id="<?php echo $this->get_field_id( 'widget_code' ); ?>" name="<?php echo $this->get_field_name( 'widget_code' ); ?>"><?php echo $instance['widget_code'] ?></textarea>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (optional):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'follow_text' ); ?>">Follow text:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'follow_text' ); ?>" name="<?php echo $this->get_field_name( 'follow_text' ); ?>" type="text" value="<?php echo $instance['follow_text']; ?>" />
		</p>
		
		<?php if ( $instance['width'] ) { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">Width:</label>
			<input type="text" size="4" class="inline" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width'] ?>" id="<?php echo $this->get_field_id( 'width' ); ?>">px
		</p>
		<?php } ?>
		
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_twittercom_form', $form, $instance );
	}	
}


?>