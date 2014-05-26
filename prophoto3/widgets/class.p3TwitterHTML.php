<?php


class P3_Twitter_HTML extends WP_Widget {
	
	// widget setup
	function P3_Twitter_HTML() {
		$this->WP_Widget( 'p3-twitter-html', 'P3 Twitter HTML Badge', array( 'description' => 'ProPhoto theme simple twitter HTML display.  Displays a list of recent tweets.' ) );
	}
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );

		$title          = apply_filters( 'widget_title', $instance['title'] );
		$twitter_count  = $instance['twitter_count'];
		$twitter_name   = strtolower( $instance['twitter_name'] );
		$loading_text	= $instance['loading_text'];
		$box_height		= $instance['box_height'];
		$follow_text    = $instance['follow_text'];
		$link_text      = $instance['link_text'];
		
		// font size specified
		if ( $instance['font_size'] ) {
			$font_size  = ' style="font-size:' . intval( $instance['font_size'] ) . 'px;"';
			$follow_font_size = intval( intval( $instance['font_size'] ) * 0.85 );
			$follow_font_size = ' style="font-size:' . $follow_font_size . 'px;"';
		}
		
		echo $before_widget;
		
		if ( $title ) echo $before_title . $title . $after_title;

		$markup = <<<HTML
		<div id="{$widget_id}" class="p3-twitter-html p3-html-twitter-widget p3-html-twitter-widget-{$twitter_name}">
			<span class="twitter_count js-info">$twitter_count</span>
			<span class="twitter_name js-info">$twitter_name</span>
			<ul{$font_size}>$loading_text</ul>
			<p{$follow_font_size}>$follow_text <a href="http://twitter.com/{$twitter_name}">{$link_text}</a></p>
		</div>
HTML;
		echo apply_filters( 'p3_twitter_html_markup', $markup, $instance );
		
		echo $after_widget;
		
	}
	
	// update widget settings
	function update( $new_instance, $old_instance ) {
		return apply_filters( 'p3_twitter_html_update', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) {
		
		$defaults = array( 
			'twitter_name' => p3_get_option( 'twitter_name' ),
			'follow_text' => 'Follow me on',
			'link_text' => 'Twitter',
			'twitter_count' => '5',
			'loading_text' => 'loading...',
			'title' => 'What I\'m doing:',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		p3_widget_help_link( 'Twitter HTML Badge' );
		
		ob_start(); ?>
			
	<div class="p3-twitter-html">

		<div class="p3-twitter-html-form">
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (optional):</label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('twitter_name'); ?>">Your Twitter username:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('twitter_name'); ?>" name="<?php echo $this->get_field_name('twitter_name'); ?>" type="text" value="<?php echo $instance['twitter_name']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('twitter_count'); ?>">Number tweets shown:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('twitter_count'); ?>" name="<?php echo $this->get_field_name('twitter_count'); ?>" type="text" value="<?php echo $instance['twitter_count']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('follow_text'); ?>">Follow link prefix:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('follow_text'); ?>" name="<?php echo $this->get_field_name('follow_text'); ?>" type="text" value="<?php echo $instance['follow_text']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('link_text'); ?>">Link text:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo $instance['link_text']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('loading_text'); ?>">"Loading" text:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('loading_text'); ?>" name="<?php echo $this->get_field_name('loading_text'); ?>" type="text" value="<?php echo $instance['loading_text']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('font_size'); ?>">Set font size (optional):</label>
				<input class="inline" id="<?php echo $this->get_field_id('font_size'); ?>" name="<?php echo $this->get_field_name('font_size'); ?>" type="text" size="3" value="<?php echo $instance['font_size']; ?>" />px
			</p>
		</div><!-- .p3-twitter-html-form  -->
	</div><!-- .p3-twitter-html  -->
<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_twitter_html_form', $form, $instance );
	}
}

?>