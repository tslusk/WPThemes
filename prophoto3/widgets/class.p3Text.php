<?php

/* custom icon image link */
class P3_Text extends WP_Widget {
	
	// widget setup
	function P3_Text() {
		$this->WP_Widget( 'p3-text', 'P3 Text', array( 'description' => 'Enhanced text widget with buttons for adding links and formatting text' ), array( 'width' => '500' ) );
	}
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$text  = $instance['text'];
		$text = str_replace( array( '<b>', '</b>', '<i>', '</i>' ), array( '<strong>', '</strong>', '<em>', '</em>' ), $text );
		if ( $instance['wpautop'] == 'true' || intval( $instance['wpautop'] ) == 1 ) $text = wpautop( $text );
		
		// obfuscate email addresses
		if ( nrIsIn( 'href="mailto:', $text ) && preg_match( '/<a href\=\"mailto\:([^"]*)">([^<]*)<\/a>/', $text, $match ) ) {
			if ( !isset( $_GET['ajax'] ) ) { // doing a document.write() during ajax doesn't work
				list( $full_link, $email, $linktext ) = $match;
				$text = str_replace( $full_link, p3_js_email_encode( $email, $linktext, NO_ECHO ), $text );
			}
		}

		echo $before_widget;
		
		if ( $title ) echo $before_title . $title . $after_title;

		echo apply_filters( 'p3_p3text_markup', $text, $instance );

		echo $after_widget;		
	}

	// update widget settings
	function update( $new_instance, $old_instance ) {
		$new_instance['text'] = force_balance_tags( $new_instance['text'] );
		$new_instance['wpautop'] = isset( $new_instance['wpautop'] );
		return apply_filters( 'p3_p3text_markup', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) {

		$defaults = array( 'wpautop' => 1 );
		$instance = wp_parse_args( (array) $instance, $defaults );
		if ( !$instance['text'] || strpos( $instance['text'], '<' ) === FALSE ) $preview_class = ' hidden';
		
		$preview_text = force_balance_tags( $instance['text'] );
		if ( preg_match( '/(<script|<style)/i', $preview_text ) ) {
			$preview_text = '';
		}
		if ( $instance['wpautop'] == 1 ) {
			$preview_text = str_replace( "\n", '<br />', $preview_text );
		}
		
		p3_widget_help_link( 'Text' );
		
		ob_start(); ?>
		
	<div class="p3-text">

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (optional):</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p class="p3-text-textarea-holder">
			<input class="p3_tag_btn" type="button" value="Email" tag="e" />
			<input class="p3_tag_btn" type="button" value="Link" tag="a" />
			<input class="p3_tag_btn" type="button" value="Italic" tag="i" />
			<input class="p3_tag_btn" type="button" value="Bold" tag="b" />
			<label for="<?php echo $this->get_field_id('text'); ?>">Text:</label>
			<textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" rows="9"><?php echo $instance['text']; ?></textarea>
		</p>
		<input type="checkbox" name="<?php echo $this->get_field_name('wpautop'); ?>" value="true" id="<?php echo $this->get_field_id('wpautop'); ?>" <?php checked( $instance['wpautop'] ); ?> />&nbsp;<label for="<?php echo $this->get_field_name('wpautop'); ?>">Automatically add paragraphs.</label>
		
		<div class="p3-text-preview<?php echo $preview_class ?>">
			<h3>HTML Preview:</h3>
			<div class="p3-text-target">
				<?php echo $preview_text; ?>
			</div>
		</div>
	</div> <!-- .p3-text -->	
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_p3text_form', $form, $instance );
	}
}
?>