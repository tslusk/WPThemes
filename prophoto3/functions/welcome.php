<?php
/* ------------------------------------------------------------------- */
/* -- libary of functions used for the activation welcome procedure -- */
/* ------------------------------------------------------------------- */


/* set up activation welcome procedure */
function p3_activation_welcome() {
	p3_enqueue_style( '/adminpages/css/welcome.css' );
	add_action( 'admin_head', 'p3_welcome_js' );
	add_action( 'admin_notices', 'p3_welcome_control' );
}


/* conrol display of various steps of P3 welcome procedure */
function p3_welcome_control() {
	switch ( $_GET['step'] ) {
		case 'register':  return p3_registration();
		case 'p2migrate': return p3_p2_welcome();
		case 'intro':     return p3_pages_intro();
		case 'end':       return p3_end_welcome();
		default:          return p3_file_check();
	} 
}


/* check theme folder for missing files, and advise accordingly */
function p3_file_check() {
	$p3_files = p3_get_file_list();
	
	// check for existence of all the files
	foreach ( (array) $p3_files as $key => $file ) {
		if ( !@file_exists( TEMPLATEPATH . $file ) ) $missing_files[] = $file;
	}
	
	// everything ship shape, proceed to registration
	if ( !$missing_files ) return p3_registration();
	
	// FAIL - we have missing files
	$error_class = ' missing-files';
	foreach ( $missing_files as $missing_file ) {
		$missing_file_list .= '<li><code>prophoto3' . $missing_file . '</code></li>';
	}
	
	$themes_url = admin_url( 'themes.php' );
	echo <<<HTML
	<div class="wrap p3wrap file-check{$error_class}">
		<div class="icon32" id="icon-tools"><br/></div>
		<h2>Installation Problem</h2>
		<div class="p3file-error"><strong>Ooops!</strong> ProPhoto just scanned itself, and has at least one <strong>missing file</strong>.  This is most likely due to a mistake made or problem that happened during installation. The files that it found missing were:
			<ul>$missing_file_list</ul>
			
		<p><strong>What now?</strong></p>
		<p>The best way to proceed is to de-activate the ProPhoto3 theme, delete it completely from WordPress, and then re-install.  This should fix it in most cases.</p>
		<p>To deactivate ProPhoto <a href="$themes_url">click here</a>, and then activate the <strong>Wordpress Default Theme</strong>.  After you've done that ProPhoto3 will be listed as an available theme, and you can click the <strong>"delete"</strong> button and start over.</p>
		</div>
	</div>
HTML;
}


/* theme registration */
function p3_registration() {
	global $p3;
	
	// registration attempted
	if ( $_GET['register'] ) {
		extract( $_GET );
		if ( $register == 'success' ) {
			$p3['non_design']['payer_email'] = $payer_email;
			$p3['non_design']['txn_id'] = $txn_id;
			$p3['non_design']['purch_time'] = $purch_time;
			p3_store_options();
			p3_p2_welcome();
			return;
		} else if ( $register == 'failure' ) {
			$error_class = ' reg-problem registration-failed';
			$email_val   = $payer_email;
			$txn_id_val  = $txn_id;
		} else if ( $register == 'error' ) {
			$error_class = ' reg-problem registration-error';
			$email_val   = $payer_email;
			$txn_id_val  = $txn_id;
		} else {
			p3_p2_welcome();
			return;
		}
		
	// lazy SOB
	} else if ( $_GET['do-it-later'] ) {
		p3_p2_welcome();
		return;
	}
	
	// record activation date
	$p3['non_design']['activation_time'] = time();
	p3_store_options();
	
	$blog_url = P3_URL;
	$pp_marketing_includes = PP_MARKETING_INCLUDES;
	$activation_success_msg = p3_get_updated_msg( 'ProPhoto3 theme activated.' );
	$POST_link = admin_url( 'themes.php?activated=true&p3activate=inprocess&step=register');
	$mailto_href = '?subject=Registration%20Problem%20-%20ID%20#' . time() . '&body=Name:%0A%0APurchase%20Date:%0A%0APayPal%20Email%20(if%20applicable):%0A%0AZip%20Code:%0A%0AAny%20other%20info:';
	echo <<<HTML
	<div class="wrap p3wrap{$error_class}">
		<h2>Welcome to ProPhoto3!</h2>
		$activation_success_msg
		<h3>Register your copy of ProPhoto:</h3>
		<p class="intro">Before you do anything else, please take 30 seconds and enter the email address and transaction ID associated with your purchase of ProPhoto3.  You don't have to do this right now to use the theme, but <strong>you will have to enter it before you can receive any <span>free updates</span></strong> that are released.</p>
		
		<!-- errors -->
		<p class="not-found">Sorry, but we were <strong>unable to find that combination of Payer Email and Transaction ID in our records.</strong></p>
		
		<p class="reg-error"><strong>How embarrasing!</strong> We encountered a problem trying to check your information. It was our fault, and not yours.</p>
		
		<p class="reg-issue">What now? First, try <span>double-checking your info and </span>re-submitting.  If you get this message again, send us an email at <a href="mailto:install@netrivet.com{$mailto_href}">install@netrivet.com</a> and <strong>provide as much info as you can about your purchase</strong>: your name, purchase email, purchase date.  Then click "Do it later" and continue.  We'll try to dig up the record of your purchase and send you back an email with instructions on how you can re-enter this information.</p>
		<!-- / errors  -->
		
		<h4>How do I find these?</h4>
		<p>The moment your purchase was completed, <strong>we sent you an email with both of these pieces of info</strong>.  Just find that email and copy/paste in the two items below. Make sure you're checking the email address you used when purchasing (if you have a paypal account, this would be your PayPal account email). </p>
		<p><strong>Still no joy?</strong> If your email program/site has a search feature, search for the word <code>pp3purchase</code>.</p>
		<form action="{$pp_marketing_includes}p3_registration.php" method="post" id="register-form">
			<input type="hidden" name="return_url" value="$POST_link" id="return_url">
			<input type="hidden" name="blog_url" value="$blog_url" id="blog_url">
			<p id="payer_email_parent">
				<label for="payer_email">Payer Email:</label>
				<input type="text" name="payer_email" value="$email_val" id="payer_email" size="31">
				<span>Please enter a valid email</span>
			</p>
			<p id="txn_id_parent">
				<label for="txn_id">Transaction ID:</label>
				<input type="text" name="txn_id" value="$txn_id_val" id="txn_id" size="31">
				<span>Must be 17 letters/numbers, like: <code>5PH528963D057T007</code></span>	
			</p>
			<p><input type="checkbox" name="blue_host_rebate" value="true" id="blue_host_rebate">&nbsp; I signed up with Bluehost.com and would like to receive my $30 rebate. <a id="bluehost-explain" title="what does this mean?" style="font-weight:700;text-decoration:none">?</a></p>
			<p id="bluehost-more-info" style="display:none;font-style:italic;">ProPhoto offers a $30 rebate for anyone who signs up for a new hosting account with our hosting partner, <a href="http://www.bluehost.com/track/netrivet/in-theme-activation/">Bluehost.com</a>. Full information about the offer <a href="http://www.prophotoblogs.com/support/about/bluehost-rebate/">is here</a>.</p>
			<p><input id="register-submit" type="submit" value="Submit"><input id="do-it-later" type="submit" value="Do it later"></p>
		</form>
	</div>
HTML;
}


/* welcome info for those upgrading from P2 */
function p3_p2_welcome() {
	require_once( 'designs.php' );
	if ( !$p2 = p3_p2_db_data() ) return p3_pages_intro();
	$static_resource_url = STATIC_RESOURCE_URL;
	$nexturl = admin_url( 'themes.php?activated=true&p3activate=inprocess&step=intro');
	if ( $_GET['register'] == 'success' ) $register_success = p3_get_updated_msg( 'Registration successful!' );
	
	echo <<<HTML
	<div class="wrap p3wrap p2-welcome">
		<div class="icon32" id="icon-tools"><br/></div>
		<h2>Migrating from P2 to P3</h2>
		<div class="welcome-chunk welcome-chunk-active">
			$register_success
			<p class="intro">ProPhoto3 has noticed that you are also a <strong>ProPhoto <span>version 2</span> user</strong>. Thanks for being a repeat customer! We've tried to make the transition from P2 to P3 as simple as we possibly could.</p>
			<h3>Importing your ProPhoto version 2 layouts</h3>
			<p class="has-btn">ProPhoto3 makes it <strong>super easy to import your version 2 design layouts</strong>. All you have to do is, when you first go to the "P3 Designs" page, look for the <a class="button-primary">Import P2 Layouts</a> button, as shown in the below picture.</p>
			<img src="{$static_resource_url}img/show-p2-import-btn.jpg" style="width:499px" />
		</div>
		<div class="welcome-chunk">
			<h3>Activating a P2 Layout</h3>
			<p>After importing your P2 layout/s, they will show up in your "Inactive Designs" section, where you can <strong>click to activate them</strong>, making them your current active design.</p>
			<img src="{$static_resource_url}img/p2-imported-layouts.jpg" style="width:499px" />
		</div>
		<div class="btn-holder"><a class="next-chunk button-secondary" nexturl="$nexturl">Continue &raquo;</a></div>
	</div>
HTML;
}


/* show introduction to ProPhoto pages */
function p3_pages_intro() {
	$static_resource_url = STATIC_RESOURCE_URL;
	$designs_page    = p3_get_admin_url();
	$customize_page  = p3_get_admin_url( 'background' );
	if ( $_GET['register'] == 'success' ) $register_success = p3_get_updated_msg( 'Registration successful!' );
	
	echo <<<HTML
	<div class="wrap p3wrap">
		<div class="icon32" id="icon-tools"><br/></div>
		<h2>Introduction to ProPhoto3 (P3)</h2>
		<div class="welcome-chunk welcome-chunk-active">
			$register_success
			<h3>Customizing ProPhoto</h3>
			<p>You can customize ProPhoto in a ton of ways, by visiting the "<strong>P3 Customize</strong>" page. The link to that page is in your WordPress left sidebar, under "Appearance". To make the link throb <strong>so you know where it is</strong> for future reference, <a class="link-highlight" rel="customize">click here</a>.</p>
			<p>All of the customization options are broken into 13 categories, represented by the 13 tabs on the top of the Customization page.  Just choose the area you want to work on, and click the appropriate tab.</p>
			<img src="{$static_resource_url}img/customize-tabs.jpg" />
		</div>
		<div class="welcome-chunk">
			<h3>Getting help with customization options</h3>
			<p>If you <strong>don't understand</strong> what a customization area does, or you're <strong>having a problem</strong> with it, click the <span id="help-icon">&nbsp;&nbsp;&nbsp;&nbsp;</span> help icon in the upper right to get more explanation and a link to a full tutorial.</p>
			<img src="{$static_resource_url}img/inline-help.jpg" />
		</div>
		<div class="welcome-chunk">
			<h3>The "P3 Designs" Page</h3>
			<p>Because ProPhoto is so customizable, we also give you a way to save combinations of customizations. We call these saved groups of customizations <strong>DESIGNS</strong>. Whenever you're customizing your ProPhoto blog, all of your customizations are automatically being saved to whatever design is currently <strong>active</strong>.</p>
			<p>To create new designs, or activate inactive ones, you just go to the <strong>P3 Designs page</strong> by clicking on the "P3 Designs" link in the left sidebar.  To make the link throb <strong>so you know where it is</strong> for future reference, <a class="link-highlight" rel="designs">click here</a>.</p>
			<img src="{$static_resource_url}img/designs-page.jpg" />
		</div>
		<div class="welcome-chunk has-btns">
			<h3>Get Started</h3>
			<p>Thanks for purchasing ProPhoto, you're ready to jump in and get started.  Have fun!</p>
			<div class="btn-holder">
				<a href="$designs_page" class="button-secondary">Go to <strong>P3 Designs</strong> Page &raquo;</a>
			</div>
			<div class="btn-holder">
				<a href="$customize_page" class="button-secondary">Go to <strong>P3 Customize</strong> Page &raquo;</a>
			</div>
		</div>
		<div class="btn-holder"><a class="next-chunk button-secondary" nexturl="$nexturl">Continue &raquo;</a></div>
	</div>
HTML;
}


/* js for the welcome procedure */
function p3_welcome_js() {
	echo <<<HTML
	<script type="text/javascript" charset="utf-8">
	jQuery(document).ready(function(){
		
		jQuery('#bluehost-explain').click(function(){
			jQuery('#bluehost-more-info').show();
			return false;
		});
		
		// welcome chunk-ifier
		jQuery('.next-chunk').click(function(){
			jQuery('.welcome-chunk-active').removeClass('welcome-chunk-active')
				.next().addClass('welcome-chunk-active');
				if (!jQuery('.welcome-chunk-active').next().hasClass('welcome-chunk')) {
					jQuery('.next-chunk').unbind().click(function(){
						window.location.href = jQuery(this).attr('nexturl');
						return false;
					});
				}
				if (jQuery('.welcome-chunk-active').hasClass('has-btns')) {
					jQuery('.next-chunk').hide();
				}
				return false;
		});
		
		
		// throb link to highlight
		jQuery('a.link-highlight').click(function(){
			var linkname = jQuery(this).attr('rel');
			jQuery('li#menu-appearance').addClass('wp-menu-open');
			var link = jQuery('a[href$=p3-'+linkname+']', jQuery('li#menu-appearance'));
			link.css({fontWeight:'700',textDecoration:'underline'});
			var link_throb = setInterval(function(){
				link.animate({fontSize:'1.4em'},300,function(){
					link.animate({fontSize:'0.9em'},300);
				})
			}, 600);
			setTimeout(function(){
				clearInterval(link_throb);
				setTimeout(function(){
					link.css({fontWeight:'400',textDecoration:'none', fontSize:'0.9em'});
				}, 600);
			}, 5000);
		});
		
		// registration stuff
		jQuery('#register-form').submit(function(){
			email_validates = txn_id_validates = false;
			var email  = jQuery('#payer_email').val();
			var txn_id = jQuery('#txn_id').val();
			
			if ( email.match(/^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) ) {
				email_validates = true;
			}
			
			if ( txn_id.match(/([A-Z0-9]){17}/) && txn_id.length == 17 ) {
				txn_id_validates = true;
			}

			if ( !email_validates ) {
				jQuery('#payer_email_parent').addClass('p3error')
			} else {
				jQuery('#payer_email_parent').removeClass('p3error')
			}
			
			if ( !txn_id_validates ) {
				jQuery('#txn_id_parent').addClass('p3error')
			} else {
				jQuery('#txn_id_parent').removeClass('p3error')
			}
			
			if ( email_validates && txn_id_validates) return;
			return false;
		});
		jQuery('#do-it-later').click(function(){
			var next_step_url = jQuery('input[name="return_url"]').val() + '&do-it-later=true';
			if ( jQuery('.p3wrap').hasClass('reg-problem') ) {
				window.location.href = next_step_url;
				return false;
			} else {
				var procrastinate = confirm('Not to be pushy, but, it\'s probably going to be easier to dig up this info now than in a few weeks/months when the first free update is ready. Do you still want to do it later?');
				if ( !procrastinate ) return false;
				window.location.href = next_step_url;
			}
			return false;
		});
	});
	</script>
HTML;
}


/* list of p3 files */
function p3_get_file_list() {
	// build by adding ?files=1 to url
return array('/404.php','/adminpages/colorpicker/farbtastic.css','/adminpages/colorpicker/farbtastic.js','/adminpages/colorpicker/marker.png','/adminpages/colorpicker/mask.png','/adminpages/colorpicker/wheel.png','/adminpages/css/admin.css','/adminpages/css/common.css','/adminpages/css/designs-popup.css','/adminpages/css/designs.css','/adminpages/css/options.css','/adminpages/css/popup-reset.css','/adminpages/css/popup-upload.css','/adminpages/css/popup.css','/adminpages/css/tinymce.css','/adminpages/css/upload.css','/adminpages/css/welcome.css','/adminpages/css/widgets.css','/adminpages/designs.php','/adminpages/js/designs.js','/adminpages/js/fontpreview.js','/adminpages/js/jquery.ui.slider.1.7.2.js','/adminpages/js/jquery.ui.slider.1.8.5.js','/adminpages/js/options.js','/adminpages/js/popup-reset.js','/adminpages/js/popup-upload.js','/adminpages/js/posting.js','/adminpages/js/tinymce.js','/adminpages/js/upload.js','/adminpages/js/widgets.js','/adminpages/js/wp_media_upload.js','/adminpages/options/background.php','/adminpages/options/bio.php','/adminpages/options/comments.php','/adminpages/options/contact.php','/adminpages/options/content.php','/adminpages/options/fonts.php','/adminpages/options/footerz.php','/adminpages/options/galleries.php','/adminpages/options/header.php','/adminpages/options/menu.php','/adminpages/options/settings.php','/adminpages/options/sidebar.php','/adminpages/options/widgets.php','/adminpages/options.php','/adminpages/popup-reset.php','/adminpages/popup.php','/archive.php','/author.php','/category.php','/comments.php','/dynamic/css/bio.css.php','/dynamic/css/boxy.css.php','/dynamic/css/comments.css.php','/dynamic/css/contact.css.php','/dynamic/css/content.css.php','/dynamic/css/footer.css.php','/dynamic/css/general.css.php','/dynamic/css/hardcoded.css','/dynamic/css/header.css.php','/dynamic/css/ie6.css.php','/dynamic/css/minima.css.php','/dynamic/css/nav.css.php','/dynamic/css/postheader.css.php','/dynamic/css/sidebar.css.php','/dynamic/css/tabbed.css.php','/dynamic/css/widgets.css.php','/dynamic/customstyle.css.php','/dynamic/empty.php','/dynamic/fontpreview.css.php','/dynamic/gallery.json.php','/dynamic/ie6.js.php','/dynamic/images.xml.php','/dynamic/js/audioplayer.js','/dynamic/js/p3lightbox.js','/dynamic/js/swfobject.js','/dynamic/themescript.js.php','/flash/audioplayer.swf','/flash/expressInstall.swf','/flash/flashheader.swf','/flash/fonts/Arial.swf','/flash/fonts/BookmanOldStyle.swf','/flash/fonts/CenturyGothic.swf','/flash/fonts/ComicSansMS.swf','/flash/fonts/Courier.swf','/flash/fonts/Garamond.swf','/flash/fonts/Georgia.swf','/flash/fonts/Helvetica.swf','/flash/fonts/LucidaGrande.swf','/flash/fonts/Palatino.swf','/flash/fonts/Tahoma.swf','/flash/fonts/Times.swf','/flash/fonts/TrebuchetMS.swf','/flash/fonts/Verdana.swf','/flash/gallery.swf','/footer.php','/functions/class.borders.php','/functions/class.fonts.php','/functions/class.options.php','/functions/class.upload.php','/functions/comments.php','/functions/css.php','/functions/debug.php','/functions/designs.php','/functions/dev.php','/functions/flashgal.php','/functions/folders.php','/functions/general.php','/functions/images.php','/functions/import_p2.php','/functions/lightboxgal.php','/functions/nav.php','/functions/options.php','/functions/sandbox.php','/functions/sidebar.php','/functions/static.php','/functions/support.php','/functions/template.php','/functions/upload.php','/functions/utility.php','/functions/version.php','/functions/welcome.php','/functions/widgets.php','/functions.php','/header.php','/images/aqua_blog_bg.jpg','/images/aqua_comments_addacomment_image.png','/images/aqua_comments_emailafriend_image.png','/images/aqua_comments_linktothispost_image.png','/images/aqua_content_bg.png','/images/aqua_logo.png','/images/aqua_masthead_image.jpg','/images/blank.gif','/images/borderCAC9C9.gif','/images/brown_blog_bg.jpg','/images/brown_comments_header_bg.jpg','/images/brown_logo.png','/images/brown_post_bg.gif','/images/comment.gif','/images/comments-closed.gif','/images/comments-open.gif','/images/dropshadow_corners.png','/images/dropshadow_sides.png','/images/dropshadow_topbottom.png','/images/dropshadow_wide_corners.png','/images/dropshadow_wide_sides.png','/images/dropshadow_wide_topbottom.png','/images/elegant-bg.jpg','/images/elegant_blog_bg.jpg','/images/elegant_comments_addacomment_icon.gif','/images/elegant_comments_comment_outer_bg.jpg','/images/elegant_comments_emailafriend_icon.gif','/images/elegant_comments_linktothispost_icon.gif','/images/elegant_logo.jpg','/images/elegant_masthead_image.jpg','/images/elegant_post_header_separator.jpg','/images/email.gif','/images/grunge_bio_bg.jpg','/images/grunge_biopic1.jpg','/images/grunge_blog_bg.jpg','/images/grunge_comments_addacomment_image.png','/images/grunge_comments_emailafriend_image.png','/images/grunge_comments_header_bg.png','/images/grunge_comments_linktothispost_image.png','/images/grunge_logo.jpg','/images/grunge_post_bg.jpg','/images/lightbox-blank.gif','/images/lightbox-btn-close.gif','/images/lightbox-btn-next.gif','/images/lightbox-btn-prev.gif','/images/lightbox-ico-loading.gif','/images/link.gif','/images/logo.jpg','/images/minima-comments-show-hide.png','/images/minimalist_logo.gif','/images/nodefaultimage.gif','/images/post-interaction-button-bg.jpg','/images/prophoto1_biopic1.jpg','/images/prophoto1_logo.jpg','/images/prophoto1_masthead_image1.jpg','/images/prophoto2_bio_bg.jpg','/images/prophoto2_bio_inner_bg.jpg','/images/prophoto2_bio_inner_bg_alt.jpg','/images/prophoto2_biopic1.jpg','/images/prophoto2_logo.jpg','/images/prophoto2_masthead_image1.jpg','/images/rss-icon.png','/images/tab1-left.jpg','/images/tab1-right.jpg','/images/tab2-left.jpg','/images/tab2-right.jpg','/images/watermark.png','/includes/bio.php','/includes/constants.php','/includes/contact-form-process.php','/includes/contact-form.php','/includes/settings/images.php','/includes/settings/interface.php','/includes/settings/misc.php','/includes/settings/notices.php','/includes/settings/options.php','/includes/settings/p2names.php','/includes/starters/aqua.php','/includes/starters/brown.php','/includes/starters/elegant.php','/includes/starters/grunge.php','/includes/starters/minimalist.php','/includes/starters/prophoto1.php','/includes/starters/prophoto2.php','/index.php','/license.txt','/page.php','/screenshot.png','/search.php','/single.php','/style.css','/svn.php','/tag.php','/widgets/class.p3CustomIcon.php','/widgets/class.p3FacebookLikebox.php','/widgets/class.p3SocialMediaIcons.php','/widgets/class.p3SubscribeByEmail.php','/widgets/class.p3Text.php','/widgets/class.p3TwitterHTML.php','/widgets/class.p3TwitterSlider.php','/widgets/class.p3TwitterWidget.php',);
}
?>