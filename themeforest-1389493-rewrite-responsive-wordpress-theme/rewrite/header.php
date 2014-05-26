<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head> 
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" /> 
	<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" />
	
	<title><?php bloginfo('name'); ?><?php wp_title(); ?></title>
	
	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" />
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/includes/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/media-queries.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/includes/js/flex/flexslider.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	
	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); wp_head(); ?>
	
	<!-- conditional css -->
	<style type="text/css">
		a {
			color:<?php echo of_get_option('of_colorpicker', 'no entry' ); ?>;
		}
	</style>
</head>

<body <?php body_class( $class ); ?>>
	
	<div id="wrapper" class="clearfix">
		
		<div id="main" class="clearfix">
			<div class="header-wrapper">
				<!-- grab the logo -->
				<?php if ( of_get_option('of_logo') ) { ?>
		        	<h1>
						<a href="<?php echo home_url( '/' ); ?>"><img class="logo" src="<?php echo of_get_option('of_logo'); ?>" alt="<?php the_title(); ?>" /></a>
					</h1>
				<!-- otherwise show the site title and description -->	
		        <?php } else { ?>
		        	
	            <div class="logo-default">
		            <h1 class="logo-text">
		            	<a href="<?php echo home_url( '/' ); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name') ?></a>
		            	
		            	<span><?php bloginfo('description') ?></span>
		            </h1>
	            </div>
		        <?php } ?>    
				
				<div class="top-bar">			
					<!-- search field -->
					<?php include('searchform.php'); ?>
					
		            <!-- header navigation menu -->
		            <?php wp_nav_menu(array('theme_location' => 'main', 'menu_class' => 'nav')); ?>	
				</div><!-- top bar -->
				<div style="clear:both;"></div>
			</div><!-- header wrapper -->