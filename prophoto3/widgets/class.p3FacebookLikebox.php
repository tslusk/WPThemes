<?php

/* facebook fanbox */
class P3_Facebook_Likebox extends WP_Widget {
		
	// widget setup
	function P3_Facebook_Likebox() {
		$this->WP_Widget( 'p3-facebook-likebox', 'P3 Facebook Likebox', array( 'description' => 'Add a Facebook likebox for your Facebook business page.' ),  array( 'width' => '300' ) );
	}	
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		
		$box_code = ( $instance['box_code'] ) ? $instance['box_code'] : $this->prophoto_fanbox;
		
		echo $before_widget;
		
		echo apply_filters( 'p3_facebook_fanbox_box_code', $box_code, $instance ); 
		
		echo $after_widget;
	}
	
	// update widget settings
	function update( $new_instance, $old_instance ) {
		return apply_filters( 'p3_facebook_fanbox_update', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) {

		$defaults = array( 'box_code' => '' );
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		if ( nrIsIn( '<fb:like-box', $instance['box_code'] ) ) {
			$fbxml_warn = '<p style="color:red">You must use the iFrame code and not the XFBML code from Facebook.</p>';
			$fbxml_warn_style = 'border-color:red';
		}
		
		p3_widget_help_link( 'Facebook Likebox' );
		
		ob_start(); ?>
		
		<p id="fb-page-explain">To create a Facebook Likebox, you first need to have a <a href="<?php echo FACEBOOK_PAGE_URL ?>" target="_blank">Facebook 'Page'</a> set up for your business.</p>
		<p id="fb-fanbox-explain">Once you've got a fanpage, <a href="<?php echo FACEBOOK_FANBOX_URL ?>" target="_blank">create and customize your likebox here</a>. Then click "Get Code", copy the iFrame code <em>(not the XFBML code)</em>, and paste it below.</p>
		<?php echo $fbxml_warn ?>
		<textarea style="font-size:.9em;font-family:Courier,monospace;<?php echo $fbxml_warn_style ?>" class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id( 'box_code' ); ?>" name="<?php echo $this->get_field_name( 'box_code' ); ?>"><?php echo $instance['box_code'] ?></textarea>
		
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_facebook_fanbox_form', $form, $instance );
	}
}


?>