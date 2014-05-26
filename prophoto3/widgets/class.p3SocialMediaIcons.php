<?php

/* social networking icon link sets */
class P3_Social_Media_Icons extends WP_Widget {
	
	// widget setup
	function P3_Social_Media_Icons() {
		$this->WP_Widget( 'p3-social-media-icons', 'P3 Social Media Icon', array( 'description' => '5 sets of high-quality Facebook, Twitter, and RSS icons for you to choose from. Link them to your feed, Facebook, or Twitter pages so your readers can stay connected.' ), array( 'width' => '256' ) );
	}
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		$title    = apply_filters('widget_title', $instance['title'] );
		$type     = $instance['type'];
		$style    = $instance['style'];
		$link     = $instance['link'];
		$target   = ( $instance['target'] == '_blank' ) ? 'target="_blank"' : '';
		$size     = p3_get_icon_size_info( $instance );
		$htmlsize = $size['html_size'];
		$filesize = $size['filename_size'];
		$file_src = STATIC_RESOURCE_URL . "img/{$type}_{$style}_{$filesize}.png";
		$note     = ( $instance['note'] ) ? "<p class='icon-note'>{$instance['note']}</p>" : '';
		
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$markup = <<<HTML
		<a id="{$widget_id}" href="{$link}" class="icon-link"{$target}>
			<img src="{$file_src}" class="p3-icon p3-png  p3-social-media-icon" width="{$htmlsize}" height="{$htmlsize}" />
		</a>
		$note
HTML;
		echo apply_filters( 'p3_socialmediaicons_markup', $markup, $instance );
		
		echo $after_widget;		
	}
	
	// update widget settings
	function update( $new_instance, $old_instance ) {
		$new_instance['link'] = p3_prepend_http( $new_instance['link'] );
		return apply_filters( 'p3_socialmediaicons_update', $new_instance, $old_instance );
	}
	
	
	// widget admin form
	function form( $instance ) {
		
		$defaults = array( 
			'type' => 'facebook', 
			'style' => 'glass',
			'size' => 'large',
			'link' => '',
			'custom_size' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$size     = p3_get_icon_size_info( $instance );
		$htmlsize = $size['html_size'];
		$filesize = $size['filename_size'];
			
		$src_path = STATIC_RESOURCE_URL . 'img/';
		$prev_src = $src_path . $instance['type'] . '_' . $instance['style'] . '_' . $filesize . '.png';
		$onchange = 'onchange="javascript:p3_update_icon_preview( jQuery(this).parents(\'.p3-social-media-icons\') );"';
		
		$custom_size_display = ( $instance['size'] == 'custom' ) ? 'block' : 'none';
		
		p3_widget_help_link( 'Social Media Icon' );
		
		ob_start(); ?>
		
	<div class="p3-social-media-icons">
	
		<div class="p3-social-media-icons-preview">
			<p style="margin:0;padding:0;text-align:center;cursor:pointer;">
				<img class="p3-social-media-icons-preview-image" width="<?php echo $htmlsize ?>" height="<?php echo $htmlsize ?>" src="<?php echo $prev_src ?>" rel="<?php echo $src_path ?>" style="padding-bottom:10px"/>
			</p>
		</div>
	
		<div class="p3-social-media-icons-form">	
			<p class="column first-column">
				<label for="<?php echo $this->get_field_id( 'type' ); ?>">Icon type:</label>
				<select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>" class="widefat p3-type" <?php echo $onchange ?>>
					<option value="facebook"<?php selected( $instance['type'], 'facebook' ); ?>>Facebook</option>
					<option value="twitter"<?php selected( $instance['type'], 'twitter' ); ?>>Twitter</option>
					<option value="rss"<?php selected( $instance['type'], 'rss' ); ?>>RSS</option>
				</select>
			</p>					
			<p class="column">
				<label for="<?php echo $this->get_field_id( 'style' ); ?>">Icon style:</label>
				<select name="<?php echo $this->get_field_name( 'style' ); ?>" id="<?php echo $this->get_field_id( 'style' ); ?>" class="widefat p3-style" <?php echo $onchange ?>>
					<option value="glass"<?php selected( $instance['style'], 'glass' ); ?>>glass</option>
					<option value="chrome"<?php selected( $instance['style'], 'chrome' ); ?>>chrome</option>
					<option value="sketched"<?php selected( $instance['style'], 'sketched' ); ?>>sketched</option>
					<option value="wood"<?php selected( $instance['style'], 'wood' ); ?>>wood</option>
					<option value="orb"<?php selected( $instance['style'], 'orb' ); ?>>orb</option>
					<option value="grunge"<?php selected( $instance['style'], 'grunge' ); ?>>grunge</option>
					<option value="can"<?php selected( $instance['style'], 'can' ); ?>>can</option>
				</select>
			</p>
			<p class="column first-column">
				<label for="<?php echo $this->get_field_id( 'size' ); ?>">Icon size:</label>
				<select name="<?php echo $this->get_field_name( 'size' ); ?>" id="<?php echo $this->get_field_id( 'target' ); ?>" class="widefat p3-size" <?php echo $onchange ?>>
					<option value="large"<?php selected( $instance['size'], 'large' ); ?>>large</option>
					<option value="small"<?php selected( $instance['size'], 'small' ); ?>>small</option>
					<option value="custom"<?php selected( $instance['size'], 'custom' ); ?>>custom</option>
				</select>
			</p>
			<p class="column">
				<label for="<?php echo $this->get_field_id( 'target' ); ?>">Links open in:</label>
				<select name="<?php echo $this->get_field_name( 'target' ); ?>" id="<?php echo $this->get_field_id( 'target' ); ?>" class="widefat">
					<option value="self"<?php selected( $instance['target'], 'self' ); ?>>same window</option>
					<option value="_blank"<?php selected( $instance['target'], '_blank' ); ?>>new window</option>
				</select>
			</p>
			<p style="display:<?php echo $custom_size_display ?>;" class="p3-custom-size-holder">
				<label for="<?php echo $this->get_field_id( 'custom_size' ); ?>">Custom height:</label>
				<input size="3" class="inline p3-custom-size" id="<?php echo $this->get_field_id( 'custom_size' ); ?>" name="<?php echo $this->get_field_name( 'custom_size' ); ?>" type="text" value="<?php echo $instance['custom_size']; ?>" onblur="javascript:p3_update_icon_preview( jQuery(this).parents('.p3-social-media-icons') );" />px
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'link' ); ?>">Links to:</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo $instance['link']; ?>" />
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
		</div> <!-- .p3-social-media-icons-form -->		
	</div> <!-- .p3-social-media-icons -->	
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_socialmediaicons_form', $form, $instance );
	}
}


?>