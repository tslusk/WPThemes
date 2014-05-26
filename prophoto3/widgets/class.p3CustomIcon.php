<?php

/* custom icon image link */
class P3_Custom_Icon extends WP_Widget {
	
	// widget setup
	function P3_Custom_Icon() {
		$this->WP_Widget( 'p3-custom-icon', 'P3 Custom Icon', array( 'description' => 'Use a custom-uploaded image icon and link it to whatever you want.' ), array( 'width' => '400' ) );
	}
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		$title  = apply_filters('widget_title', $instance['title'] );
		$number = $instance['number'];
		$link   = $instance['link'];
		$target = ( $instance['target'] == '_blank' ) ? 'target="_blank"' : '';	
		$size   = p3_get_icon_size_info( $instance );
		
		$file_src = p3_imageurl( 'widget_custom_image_' . $number, NO_ECHO );
		$img_html = p3_imageHTML( 'widget_custom_image_' . $number, NO_ECHO );
		$note     = ( $instance['note'] ) ? "<p class='icon-note'>{$instance['note']}</p>" : '';
		
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		
		if ( !empty( $link ) && $link != 'http://' ) {
			if ( p3_is_valid_email( $link ) )
				$link = 'mailto:' . $link;
			$open_a = "<a id='{$widget_id}' href='{$link}' class='icon-link'{$target}>";
			$close_a = '</a>';
		}
		
		$img_tag = "<img src='{$file_src}' class='p3-custom-icon p3-png' alt='' {$image_html} />";
		
		if ( p3_is_valid_email( $link ) )
			$markup = p3_js_email_encode( '', '', FALSE, $open_a . $img_tag . $close_a ) . $note;
		else
			$markup = $open_a . $img_tag . $close_a . $note;
		
		echo apply_filters( 'p3_customicon_markup', $markup, $instance );

		echo $after_widget;		
	}

	// update widget settings
	function update( $new_instance, $old_instance ) {
		if ( !empty( $new_instance['link'] ) && p3_is_not_valid_email( $new_instance['link'] ) )
			$new_instance['link'] = p3_prepend_http( $new_instance['link'] );
		return apply_filters( 'p3_customicon_update', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) {

		$defaults = array( 
			'number' => '1',
			'link' => '',
			'target' => 'self',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$number = $instance['number'];
		$preview_src = p3_imageurl( 'widget_custom_image_' . $number, NO_ECHO );
		$no_img_display = ( p3_image_exists( 'widget_custom_image_' . $number ) ) ? 'none' : 'block';
		
		// currently available upload slots
		$upload_slots = '';
		for ( $i = 1; $i <= MAX_CUSTOM_WIDGET_IMAGES; $i++ ) {
			if ( !p3_image_exists( 'widget_custom_image_' . $i ) ) continue;
			$upload_slots .= "<option value='$i'";
			$upload_slots .= selected( $instance['number'], $i, NO_ECHO );
			$upload_slots .= ">$i</option>";
		}
		
		// show explanation or widget form
		if ( $upload_slots ) {
			$instructions_display = 'none';
			$form_display = 'block';
		} else {
			$instructions_display = 'block';
			$form_display = 'none';
		}

		p3_widget_help_link( 'Custom Icon' );
		
		ob_start(); ?>
		
	<div class="p3-custom-icon">
		
		<p class="no-imgs-uploaded" style="font-style:italic;display:<?php echo $instructions_display ?>;">To use this widget, first go <a href="<?php p3_admin_url( 'widgets' ) ?>">here</a> and upload one or more custom images for icons.  Then come back to select a custom image.</p>

		<div class="p3-custom-icon-preview" style="display:<?php echo $form_display ?>;">
			<p style="margin:0;padding:0;text-align:center;cursor:pointer;">
				<img class="p3-custom-icon-preview-image" <?php p3_imageHTML( 'widget_custom_image_' . $number ) ?> src="<?php echo $preview_src ?>" style="padding-bottom:10px"/>
			</p>
		</div>

		<div class="p3-custom-icon-form" style="display:<?php echo $form_display ?>;">	
			<p>
				<select name="<?php echo $this->get_field_name( 'number' ); ?>" id="<?php echo $this->get_field_id( 'number' ); ?>" class="p3-number" onchange="javascript:p3_custom_icon_preview(jQuery(this).parents('.p3-custom-icon'));">
					<?php echo $upload_slots ?>
					<option value="add">add...</option>
				</select>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>">image number</label>
				
			</p>
			
			<p class="add-image-explain" style="font-style:italic;display:<?php echo $no_img_display ?>;">To add a custom image, go <a href="<?php p3_admin_url( 'widgets' ); ?>">here</a> and upload a new Custom Widget Image. Then return here and you will be able to select it from the list above.</p>					
			<p class="column first-column">
				<label for="<?php echo $this->get_field_id( 'link' ); ?>">Links to:</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $instance['link']; ?>" />
			</p>
			<p class="column">
				<label for="<?php echo $this->get_field_id( 'target' ); ?>">Links open:</label>
				<select name="<?php echo $this->get_field_name( 'target' ); ?>" id="<?php echo $this->get_field_id( 'target' ); ?>" class="widefat">
					<option value="self"<?php selected( $instance['target'], 'self' ); ?>>in the same window</option>
					<option value="_blank"<?php selected( $instance['target'], '_blank' ); ?>>in a new window</option>
				</select>
			</p>
			<h3>Text:</h3>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: <em>(optional, above icon)</em></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'note' ); ?>">Label: <em>(optional, below icon)</em></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'note' ); ?>" name="<?php echo $this->get_field_name( 'note' ); ?>" type="text" value="<?php echo $instance['note']; ?>" />
			</p>
			<p class="note">NOTE: <strong>Title &amp; Label</strong> will often be constrained to the <strong>width of the icon</strong>, so use only with a relatively large icon size.</p>
		</div> <!-- .p3-custom-icon-form -->		
	</div> <!-- .p3-custom-icon -->	
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_customicon', $form, $instance );
	}
	
}


?>