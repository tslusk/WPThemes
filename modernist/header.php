<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ie-fixes.css" type="text/css" media="screen" />
	<![endif]-->	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<script type="text/javascript" charset="utf-8">
	//<![CDATA[
		sfHover = function() {
			var sfEls = document.getElementById("nav").getElementsByTagName("LI");
			for (var i=0; i<sfEls.length; i++) {
				sfEls[i].onmouseover=function() {
					this.className+=" sfhover";
				}
				sfEls[i].onmouseout=function() {
					this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
				}
			}
		}
		if (window.attachEvent) window.attachEvent("onload", sfHover);		
	//]]>
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">	
	<div id="header-wrapper">
		<div id="nav">
			<ul>
				<?php wp_list_pages('title_li='); ?>
			</ul>
		</div>
		
		<div id="header">
			<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
		</div>
	</div>
	
	<hr />
	
	<div id="content">