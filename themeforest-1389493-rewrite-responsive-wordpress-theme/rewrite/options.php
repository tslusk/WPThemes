<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {

	// This gets the theme name from the stylesheet (lowercase and without spaces)
	$themename = get_theme_data(STYLESHEETPATH . '/style.css');
	$themename = $themename['Name'];
	$themename = preg_replace("/\W/", "", strtolower($themename) );
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = $themename;
	update_option('optionsframework', $optionsframework_settings);
	
	// echo $themename;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
	
	// Alignment
	$bg_image = array("light" => "Light","dark" => "Dark");
	
	// Backgrounds
	$bg_array = array("light_linen" => "Light Linen","dark_linen" => "Dark Linen","light_stone" => "Light Stone", "dark_stone" => "Dark Stone", "light_scales" => "Light Scales", "light_plaid" => "Light Plaid", "dark_plaid" => "Dark Plaid");
	
	// Multicheck Array
	$multicheck_array = array("one" => "French Toast", "two" => "Pancake", "three" => "Omelette", "four" => "Crepe", "five" => "Waffle");
	
	// Multicheck Defaults
	$multicheck_defaults = array("one" => "1","five" => "1");
	
	// Background Defaults
	$background_defaults = array('color' => '', 'image' => '', 'repeat' => 'repeat','position' => 'top center','attachment'=>'scroll');
	
	// Pull all the categories into an array
	$options_categories = array();  
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
    	$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the pages into an array
	$options_pages = array();  
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
    	$options_pages[$page->ID] = $page->post_title;
	}
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_stylesheet_directory_uri() . '/images/';
		
	$options = array();
		
	$options[] = array( "name" => "Basic Settings",
						"type" => "heading");
						
	$options[] = array( "name" => "Logo Upload",
						"desc" => "Upload your image to use in the header.",
						"id" => "of_logo",
						"type" => "upload");
						
	$options[] = array( "name" => "Link Color",
						"desc" => "Select the color you would like your links to be. The demo site uses #68AECF.",
						"id" => "of_colorpicker",
						"std" => "#68AECF",
						"type" => "color");																			
						
	$options[] = array( "name" => "Google Analytics UA Code",
						"desc" => "Put your Google Analytics UA code here. It looks like this: UA-XXXXXXX-X.",
						"id" => "of_tracking_code",
						"std" => "",
						"type" => "text"); 																						
																						
						
	$options[] = array( "name" => "Social Media Links",
						"type" => "heading");										
	
	$options[] = array( "name" => "Icon Widget Title",
						"desc" => "This will be the title above the social media icon links.",
						"id" => "of_social_title",
						"std" => "",
						"type" => "text");						
	
	
	$options[] = array( "name" => "Twitter URL",
						"desc" => "Enter the full url to your Twitter profile. (http://twitter.com/username)",
						"id" => "of_twitter_user",
						"std" => "",
						"type" => "text");	
						
	$options[] = array( "name" => "Dribbble URL",
						"desc" => "Enter the full url to your Dribbble profile. (http://dribbble.com/username)",
						"id" => "of_dribbble_user",
						"std" => "",
						"type" => "text");	
						
	$options[] = array( "name" => "Google+ URL",
						"desc" => "Enter the full url to your Google+ profile. (https://plus.google.com/username)",
						"id" => "of_google_user",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => "YouTube URL",
						"desc" => "Enter the full url to your YouTube profile. (http://youtube.com/user/username)",
						"id" => "of_youtube_user",
						"std" => "",
						"type" => "text");																					
						
	$options[] = array( "name" => "Vimeo URL",
						"desc" => "Enter the full url to your Vimeo profile. (http://vimeo.com/username)",
						"id" => "of_vimeo_user",
						"std" => "",
						"type" => "text");	
						
	$options[] = array( "name" => "Email Address",
						"desc" => "Enter your email address. (test@example.com)",
						"id" => "of_email_user",
						"std" => "",
						"type" => "text");						
	
	$options[] = array( "name" => "Facebook URL",
						"desc" => "Enter the full url to your Facebook profile. (http://facebook.com/username)",
						"id" => "of_facebook_user",
						"std" => "",
						"type" => "text");	
						
	$options[] = array( "name" => "Flickr URL",
						"desc" => "Enter the full url to your Flickr profile. (http://flickr.com/username)",
						"id" => "of_flickr_user",
						"std" => "",
						"type" => "text");
						
	$options[] = array( "name" => "Tumblr URL",
						"desc" => "Enter the full url to your Tumblr profile. (http://tumblr.com/username)",
						"id" => "of_tumblr_user",
						"std" => "",
						"type" => "text");																										
								
	return $options;
}