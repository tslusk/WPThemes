<?php

class P3_Twitter_Slider extends WP_Widget {
	
	/* js function for slider widget */
	function js() {
		$js = <<<JAVASCRIPT
		
		function p3_sliding_twitter_controls() {
			jQuery('.sliding .controls a').click(function(){
				p3_sliding_twitter_control_click(jQuery(this));
			});
		}
		
		function p3_sliding_twitter_control_click(this_click) {
			if ( this_click.hasClass('disabled') ) return false;
			var click_type    = ( this_click.hasClass('prev') ) ? 'prev' : 'next';
			var this_widget   = this_click.parents('.p3-twitter-slider');
			var tweet_height  = parseInt(jQuery('.tweet_height', this_widget).text()) + 5; // 5 = padding below
			var current_tweet = parseInt(jQuery('.twitter_current', this_widget).text());
			var num_tweets    = parseInt(jQuery('.twitter_count', this_widget).text());
			( click_type == 'prev' ) ? current_tweet-- : current_tweet++;
			var new_top = -(current_tweet * tweet_height);
			jQuery('.controls a', this_widget).removeClass('disabled');
			// disable prev/next button when appropriate
			if ( current_tweet == 0 ) {
				jQuery('.prev', this_widget).addClass('disabled');
			} else if ( current_tweet == ( num_tweets - 1 ) ) {
				jQuery('.next', this_widget).addClass('disabled');
			}
			jQuery('.twitter_current', this_widget).text(current_tweet);
			jQuery('.viewer ul', this_widget).animate({top: new_top+'px'},210,'swing');
		}
JAVASCRIPT;
		return apply_filters( 'p3_twitter_slider_js', $js );
	}
	
	
	/* css for slider widget */
	function css() {
		extract( p3_general_css_vars() );
		$css = <<<CSS
		
		/* sliding twitter widget */
		.p3-twitter-slider .controls {
			float:left;
			display:inline;
			padding-top:1px;
			padding-right:8px;
			width:15px;
		}
		.p3-twitter-slider .controls a {
			display:none;
			margin-bottom:4px;
			cursor:pointer;
			width:15px;
			height:15px;
			overflow:hidden;
			text-indent:-999em;
		}
		.p3-twitter-slider .controls .next {
			background-image:url({$static_resource_url}img/sliding-twitter-btn-next.png);
		}
		.p3-twitter-slider .controls .prev {
			background-image:url({$static_resource_url}img/sliding-twitter-btn-prev.png);
		}
		.p3-twitter-slider .controls .disabled {
			background-position: 0 -15px;
			cursor:default;
		}
		.p3-twitter-slider .viewer {
			float:left;
			display:inline;
			overflow:hidden;
			position:relative;	
		}
		.p3-twitter-slider {
			position:relative;
		}
		.has-img .badge-inner {
			position:absolute;
		}
		.p3-twitter-slider ul {
			position:absolute;
			top:0px;
			left:0px;
		}
		.p3-twitter-slider li {
			line-height:1.1em;
			overflow:hidden;
			margin-bottom:5px !important;
			#margin-bottom:4px !important;
			margin-left:0 !important;
		}
		.p3-twitter-slider .follow-me, 
		.p3-twitter-slider .follow-me a {
			font-size:10px;
			font-style:italic;
			margin-top:.5em;
			text-align:right;
		}
		.p3-twitter-slider .follow-me {
			margin:0;
			padding:0;
		}
		.twitter-time {
			white-space:nowrap;
		}
		.p3-twitter-slider a {
			font-size:inherit !important;
			font-family:inherit !important;
		}
CSS;
		return apply_filters( 'p3_twitter_slider_css', $css );
	}
	
	
	/* prints markup for widget, used by widget output and form preview */	
	function widget_markup( $instance, $args = '' ) {
		extract( $instance );

		$box_width = $tweet_width + 25; // 25 = width of controls
		$image_prefix = STATIC_RESOURCE_URL . 'img/';
		$sliding = ( $twitter_count > 1 ) ? 'sliding' : 'not-sliding';
		$img_class = ( $image != 'no' ) ? 'has-img' : 'no-img';
		$twitter_name = strtolower( $twitter_name );
		
		// built in images
		if ( $image == A OR $image == B ) {
			$image_path = $image_prefix . 'twitter-thought-bubble' . $image . '.png';
			$widget_width = ( $image == A ) ? '277' : '194';
		
		// no image
		} else if ( $image == 'no' ) {
			$image_path = $image_prefix . 'nodefaultimage.gif';
			$widget_width = $box_width;
			
		// custom uploaded image
		} else {
			$image_path = p3_imageurl( 'widget_custom_image_' . $image, NO_ECHO );
			$widget_width = p3_imagewidth( 'widget_custom_image_' . $image, NO_ECHO );
		}
		
		// front end widget output
		if ( $args ) {
			$content = $loading_text;
			$image_prefix_attr = $controls_click = '';
			$widget_id = $args['widget_id'];
			
		// widget output for form preview	
		} else {
			$widget_id = 'i' . time();
			$image_prefix_attr = ' rel="' . $image_prefix .'"';
			$controls_click = ' onclick="javascript:p3_sliding_twitter_control_click(jQuery(this));"';
			$height = 'style="height:' . $tweet_height .'px;"';
			$content = <<<HTML
			<li {$height}>This is a nice sample tweet with a link <a href="http://bit.ly/u3Alr">http://bit.ly/u3Alr</a> showing you how long 140 characters looks. WordPress plus ProPhoto theme rocks!! <a href="#">about an hour ago</a></li>
			<li {$height}>@<a href="http://twitter.com/prophotoblogs">prophotoblogs</a> how did you guys get so smart, handsome, and helpful? you really are the wind beneath my wings. Lorem ipsum is 140 characters <a href="#">two days ago</a></li>
			<li {$height}>a shorter tweet, that doesn't quite fill up the whole space <a href="#">364 years ago</a></li>
HTML;
			
			$twitter_count = 3;
			
			// account for 1px added border
			$box_width = $box_width + 1;
		}
		
		$markup = <<<HTML
		
		<div id="{$widget_id}" class="p3-twitter-slider self-clear p3-html-twitter-widget-{$twitter_name} p3-html-twitter-widget $img_class" style="width:{$widget_width}px">		
			<span class="twitter_name js-info">$twitter_name</span>
			<span class="twitter_count js-info">$twitter_count</span>
			<span class="tweet_height js-info">$tweet_height</span>
			<span class="twitter_current js-info">0</span>
			
			<img src="{$image_path}"{$image_prefix_attr} />
			
			<div class="badge-inner $sliding" style="height:{$tweet_height}px; width:{$box_width}px; top:{$pos_top}px; left:{$pos_left}px;">
				<div class="controls">
					<a class="prev disabled"{$controls_click}>prev</a>
					<a class="next"{$controls_click}>next</a>
				</div>
				<div class="viewer" style="height:{$tweet_height}px;width:{$tweet_width}px;">
					<ul style="width:{$tweet_width}px;font-family:{$font};font-size:{$fontsize}px">$content</ul>
				</div>
			</div>
		</div> <!-- .p3-twitter-slider  -->
HTML;
		echo apply_filters( 'p3_twitter_slider_markup', $markup, $instance );
	}
	
	// widget setup
	function P3_Twitter_Slider() {
		$this->WP_Widget( 'p3-twitter-slider', 'P3 Sliding Twitter Widget', array( 'description' => 'Show your recent tweets in an interactive, sliding box with optional built-in or custom background image integration.' ), array( 'width' => '450') );
	}
	
	// widget output
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;
		$this->widget_markup( $instance, $args );
		echo $after_widget;
	}
	
	// update widget settings
	function update( $new_instance, $old_instance ) {
		if ( $new_instance['image'] == 'add' ) {
			$new_instance['image'] = 'A';
		}
		return apply_filters( 'p3_twitter_slider_update', $new_instance, $old_instance );
	}
	
	// widget admin form
	function form( $instance ) {

		$defaults = array( 
			'twitter_name' => p3_get_option( 'twitter_name' ),
			'twitter_count' => '8',
			'loading_text' => 'loading...',
			'title' => '',
			'tweet_height' => 87,
			'tweet_width' => 145,
			'pos_top' => 24,
			'pos_left' => 13,
			'image' => A,
			'font' => 'Arial, Helvetica, sans-serif',
			'fontsize' => 11,
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		extract( $instance );
		
		$increment_controls = '<span class="incrementer"><a class="up" onclick="javascript:p3_increment(jQuery(this));"><img src="' . STATIC_RESOURCE_URL . 'img/arrow-down.png" /></a><a class="down" onclick="javascript:p3_increment(jQuery(this));"><img src="' . STATIC_RESOURCE_URL . 'img/arrow-up.png" /></a></span>';
		
		// currently available upload slots
		$custom_uploaded_image_options = '';
		for ( $i = 1; $i <= MAX_CUSTOM_WIDGET_IMAGES; $i++ ) {
			if ( !p3_image_exists( 'widget_custom_image_' . $i ) ) continue;
			$custom_uploaded_image_options .= "<option value='$i'";
			$custom_uploaded_image_options .= selected( $instance['image'], $i, NO_ECHO );
			$custom_uploaded_image_options .= ">Custom uploaded image #{$i}</option>";
		}
		
		$img_class = ( $image != 'no' ) ? 'has-img' : 'no-img';
		
		p3_widget_help_link( 'Sliding Twitter' );
		
		ob_start();
?>
		<div class="p3-twitter-slider-wrap <?php echo $img_class ?>">
			
			<?php $this->widget_markup( $instance ) ?>
			
			<div class="p3-twitter-slider-form">

				<p>
					<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title (optional):</label>
					<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" />
				</p>
				
				<p>
					<label for="<?php echo $this->get_field_id( 'image' ); ?>">Image:</label>
					<select name="<?php echo $this->get_field_name( 'image' ); ?>" id="<?php echo $this->get_field_id( 'image' ); ?>" class="widefat" onchange="javascript:p3_slider_image_preview(jQuery(this));">
						<option value="A"<?php selected( $instance['image'], 'A' ); ?>>Built-in image #1</option>
						<option value="B"<?php selected( $instance['image'], 'B' ); ?>>Built-in image #2</option>
						<?php echo $custom_uploaded_image_options ?>
						<option value="no"<?php selected( $instance['image'], 'no' ); ?>>no background image</option>
						<option value="add">add a custom image...</option>
					</select>
				</p>
				
				<p class="add-slider-image">To add a custom image to position behind your twitter slider, go <a href="<?php p3_admin_url( 'widgets' ) ?>">to this page</a> and upload a new Custom Widget Image. Then return here and you will be able to select it from the list above.</p>
				
				<p style="margin-bottom:0"><label>Tweet Slider size<span class="with-img"> and position</span>:</label></p>
				<p class="double">
					<span class="incrementable incrementable-2 with-img" rel="pos_top">
						<label for="<?php echo $this->get_field_id('pos_top'); ?>">Position from top: </label>
						<input class="inline" id="<?php echo $this->get_field_id('pos_top'); ?>" name="<?php echo $this->get_field_name('pos_top'); ?>" type="text" size="4" value="<?php echo $instance['pos_top']; ?>" onchange="javascript:p3_increment_manual(jQuery(this));" /><strong>px</strong>
						<?php echo $increment_controls ?>
					</span>
					<span class="incrementable" rel="tweet_height">
						<label for="<?php echo $this->get_field_id('tweet_height'); ?>">Tweet box height: </label>
						<input class="inline tweet-box-height" id="<?php echo $this->get_field_id('tweet_height'); ?>" name="<?php echo $this->get_field_name('tweet_height'); ?>" type="text" size="4" value="<?php echo $instance['tweet_height']; ?>" onchange="javascript:p3_increment_manual(jQuery(this));" /><strong>px</strong>
					<?php echo $increment_controls ?>
					</span>
				</p>
				
				<p class="double">
					<span class="incrementable incrementable-2 with-img" rel="pos_left">
						<label for="<?php echo $this->get_field_id('pos_left'); ?>">Position from left: </label>
						<input class="inline" id="<?php echo $this->get_field_id('pos_left'); ?>" name="<?php echo $this->get_field_name('pos_left'); ?>" type="text" size="4" value="<?php echo $instance['pos_left']; ?>" onchange="javascript:p3_increment_manual(jQuery(this));" /><strong>px</strong>
						<?php echo $increment_controls ?>
					</span>
					<span class="incrementable" rel='tweet_width'>
						<label class="tweet_width" for="<?php echo $this->get_field_id('tweet_width'); ?>">Tweet box width: </label>
						<input class="inline" id="<?php echo $this->get_field_id('tweet_width'); ?>" name="<?php echo $this->get_field_name('tweet_width'); ?>" type="text" size="4" value="<?php echo $instance['tweet_width']; ?>" onchange="javascript:p3_increment_manual(jQuery(this));" /><strong>px</strong>
						<?php echo $increment_controls ?>
					</span>
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('twitter_name'); ?>">Your Twitter username:</label>
					<input class="widefat" id="<?php echo $this->get_field_id('twitter_name'); ?>" name="<?php echo $this->get_field_name('twitter_name'); ?>" type="text" value="<?php echo $instance['twitter_name']; ?>" />
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('twitter_count'); ?>">Number tweets loaded:</label>
					<input class="widefat" id="<?php echo $this->get_field_id('twitter_count'); ?>" name="<?php echo $this->get_field_name('twitter_count'); ?>" type="text" value="<?php echo $instance['twitter_count']; ?>" onchange="p3_slider_count_change(jQuery(this));" />
				</p>

				<p>
					<label for="<?php echo $this->get_field_id('loading_text'); ?>">Temporary "loading" text:</label>
					<input class="widefat" id="<?php echo $this->get_field_id('loading_text'); ?>" name="<?php echo $this->get_field_name('loading_text'); ?>" type="text" value="<?php echo $instance['loading_text']; ?>" />
				</p>
				
				<p>
					<label for="<?php echo $this->get_field_id( 'font' ); ?>">Font family:</label>
					<select name="<?php echo $this->get_field_name( 'font' ); ?>" id="<?php echo $this->get_field_id( 'font' ); ?>"  onchange="javascript:p3_slider_fontfam(jQuery(this));">
						<?php p3_widget_font_select( $instance['font'] ) ?>
					</select>
					
					<span style="float:right">
						<label for="<?php echo $this->get_field_id('fontsize'); ?>">Font size:</label>
						<input class="inline" id="<?php echo $this->get_field_id('fontsize'); ?>" name="<?php echo $this->get_field_name('fontsize'); ?>" type="text" value="<?php echo $instance['fontsize']; ?>" size="3" onchange="javascript:p3_slider_fontsize(jQuery(this));" /><strong>px</strong>
					</span>
				</p>
				
			</div><!-- .p3-twitter-slider-form  -->
		</div><!-- .p3-twitter-slider -->
	<?php
		$form = ob_get_contents();
		ob_end_clean();
		echo apply_filters( 'p3_twitter_slider_form', $form, $instance );
	}
	
}

?>