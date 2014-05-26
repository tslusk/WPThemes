<?php
/*-----------------------------------------------------------------------------------*/
/* Twitter Widget
/*-----------------------------------------------------------------------------------*/

add_filter('option_ok_twitter_title', 'stripslashes');

add_action( 'widgets_init', 'load_ok_twitter_widget' );

function load_ok_twitter_widget() {
	register_widget( 'okTwitterWidget' );
}

class okTwitterWidget extends WP_Widget {

	function okTwitterWidget() {
	$widget_ops = array( 'classname' => 'ok-twitter', 'description' => __('Dispatch Twitter Widget', 'ok-twitter') );
	$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'ok-twitter' );
	$this->WP_Widget( 'ok-twitter', __('Okay Twitter Widget', 'ok-twitter'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		extract( $args );
		$tweettitle = $instance['tweet_title'];
		$username = $instance['tweet_username'];
		$tweetcount= $instance['tweet_count'];
		echo $before_widget;
?>
			<div class="twitter-box">
				 <h2><?php echo $instance['tweet_title']; ?></h2>
				 <ul class="tweet-scroll">
				 	<?php print_ok_twitter($username, $tweetcount); ?>
				 </ul>
				 
				 <a class="tweets-more" href="http://twitter.com/<?php echo $instance['tweet_username']; ?>" target="_blank" title="twitter">More Tweets &rarr;</a>
				 
			</div>

<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['tweet_title'] = $new_instance['tweet_title'];
		$instance['tweet_username'] = $new_instance['tweet_username'];
		$instance['tweet_count'] = $new_instance['tweet_count'];		
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'tweet_title' => '', 'tweet_username' => '', 'tweet_count' => '') );
		$instance['tweet_title'] = $instance['tweet_title'];
		$instance['tweet_username'] = $instance['tweet_username'];
		$instance['tweet_count'] = $instance['tweet_count'];
?>
			
			<p>
				<label for="<?php echo $this->get_field_id('tweet_title'); ?>">Title: 
					<input class="widefat" id="<?php echo $this->get_field_id('tweet_title'); ?>" name="<?php echo $this->get_field_name('tweet_title'); ?>" type="text" value="<?php echo $instance['tweet_title']; ?>" />
				</label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('tweet_username'); ?>">Username: 
					<input class="widefat" id="<?php echo $this->get_field_id('tweet_username'); ?>" name="<?php echo $this->get_field_name('tweet_username'); ?>" type="text" value="<?php echo $instance['tweet_username']; ?>" />
				</label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('tweet_count'); ?>">Tweet count: 
					<input class="widefat" id="<?php echo $this->get_field_id('tweet_count'); ?>" name="<?php echo $this->get_field_name('tweet_count'); ?>" type="text" value="<?php echo $instance['tweet_count']; ?>" />
				</label>
			</p>
              
  <?php
	}
}

function print_ok_twitter($username, $numb){

	include_once(ABSPATH.WPINC.'/rss.php');
	
     $tweet = fetch_rss("http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=" . $numb );

     for($i=0; $i<$numb; $i++){
     	echo "<li>" .  html_entity_decode( $tweet->items[$i]['atom_content'] )  . "</li>";

     }
}
?>