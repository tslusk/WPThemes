<?php
/* ------------------- */
/* -- theme notices -- */
/* ------------------- */


/* reusable chunks */
define( 'NOTICE_FILE_PERMISSIONS', "<br /><br />If you're not sure how to change permissions, <a target='_blank' href='" . CHANGE_PERMISSIONS_TUT . "'>see this tutorial</a>." );
define( 'NOTICE_FTP_TUTORIAL', "<br /><br />More information about using FTP can be <a target='_blank' href='" . FTP_TUT . "'>found here</a>." );
define( 'CLICK_RELOAD_PARENT', ' href="" onclick="javascript:var win = window.dialogArguments || opener || parent || top;win.location.href=win.location.href; return false;"' );
define( 'REMOVE_IMPORTING_MSG', '<script type="text/javascript" charset="utf-8">document.getElementById("importing").style.display = "none";</script>' );



/* galleries */
// no post id for flash gallery
$p3_notices['no_postid_for_gallery'] = '<style type="text/css">html{overflow:hidden;}</style><p style="padding:20px">Oops! ProPhoto can\'t tell what post this is, because WordPress has not assigned it an ID yet. Close this window and save your post as a draft, and then come back here. Thanks!</p>';


/* import/export P3 designs */
// no p3 export text files found in imgs dir
$p3_notices['no_available_p3_imports'] = 'Sorry, but ProPhoto couldn\'t find any export files in your <tt>p3/<strong>images</strong></tt> folder.  See <a target="_blank" href="' . IMPORT_P3_DESIGN_TUT . '">this tutorial</a> for information on how to import an exported design from ProPhoto3.';

// design zip created successfully
$p3_notices['zip_design_success'] = "<h3>Export Design</h3><p>A zip file of all of the settings and images associated with the <strong>\"%1\"</strong> design was successfully created. You can download it by <a target='_blank' href='%2'>clicking here</a>.</p>";

// "export everything" zip created successfully
$p3_notices['zip_everything_success'] = "<h3>Export Everything</h3><p>A zip file of all of the settings and images associated with the ProPhoto theme was successfully created. You can download it by <a target='_blank' href='%1'>clicking here</a>.</p>";

// server unable to create the flat txt export file
$p3_notices['cant_create_export_txt_file'] = "<h3>Design Export Problem</h3><p><strong>Oops</strong> -- ProPhoto was not able to export your settings, most likely because of a server permissions problem. <br /><br />To fix it, use your FTP program to change the permissions of the <strong>%1</strong> folder to 777 and then try again. <br /><br />The server path to that folder is <tt>%2</tt>." . NOTICE_FILE_PERMISSIONS . '</p>';

// server created the flat txt export file, but couldn't create the zip
$p3_notices['txt_success_zip_failure'] = "ProPhoto was able to write your design settings to an export file, which you can download by <strong>right-clicking</strong> (or control-clicking) on the link below and choosing 'Save Target As' or 'Save Link As': <br /><br /><a href='%1'>Export file</a><br /><br /><strong>However...</strong> For some reason, however, ProPhoto was <strong>unable to create a zip file containing this design's images</strong>, so should also use an FTP program to download all of the images in the <tt>%2</tt> directory. Or, you could try disabling all of your plugins and then attempting again." . NOTICE_FTP_TUTORIAL;

// export txt file not readable
$p3_notices['cant_read_txt_export_file'] = 'Sorry, but for some reason ProPhoto was not able to read the export file: <tt>%1</tt>. You might try setting the file permissions to 777 or checking to see that you uploaded it correctly.' . NOTICE_FILE_PERMISSIONS;

// import non design warning
$p3_notices['import_non_design_warning'] = 'When importing layouts, you can also choose to import the non-design-related settings that were exported at the same time.  Non-design-related setting are stored options that don\'t affect the blog\'s appearance--things like Google Analytics tracking code, Feedburner URL, and translated phrases. Any non-design-related settings imported if you check this will <strong>immediately become your active settings for those options</strong>.';

// default description for untitled design
$p3_notices['untitled_design_desc'] = 'This design was created automatically for you as your starting design when you activated ProPhoto3.  Any design changes you\'ve made or images you have uploaded already are already saved to this layout.  You can change the title or this description by clicking the "Edit" button below, or start a new design if you choose.';


/* misc */
$p3_notices['recommended_img_max_upload_width'] = 'Based on your current <strong>ProPhoto theme layout choices</strong>, you should upload images no wider than <strong>%1px</strong>.';

$p3_notices['clone_name_required'] = '<p style="color:red">You must enter a clone name.</p><style type="text/css" media="screen">input#design_name {border:red solid 1px;}</style>';

$p3_notices['no_mp3s_for_audio_player'] = '<span style="background:#fff;color:red;">You must have at least one MP3 for the audio player to work.</span>';

$p3_notices['create_dir_error'] = 'Unable to create directory %1. Is its parent directory writable by the server?';

$p3_notices['nested_theme_folder'] = 'Your <strong>prophoto3</strong> theme appears to be installed incorrectly.  Please see <a href="' . NESTED_THEME_FOLDER_TUT . '">this page</a> for info on how to fix this.';

$p3_notices['misnamed_theme_folder'] = 'Your <strong>prophoto3</strong> theme appears to be installed incorrectly.  Please see <a href="' . MISNAMED_THEME_FOLDER_TUT . '">this page</a> for info on how to fix this.';

$p3_notices['cant_create_folder'] = 'ProPhoto is unable to create a folder it needs. See <a target="_blank" href="%1">this page</a> for info on how to fix this.';

$p3_notices['tell_jared_problem'] = '<strong>How embarrassing.</strong> ProPhoto encountered an unexpected problem. Please contact us through our <a href="' . P3_CONTACT_SUPPORT_PAGE . '">support page</a>, and copy this error code into the message body: <code>P3 error in function: %1()';

$p3_notices['static_file_write_error'] = 'ProPhoto had a problem writing your custom files. See <a href="' . P3_FOLDER_WRITABLE_TUT . '">this page</a> for info on how to fix this.';

$p3_notices['dont_edit_theme_files'] = 'You should <strong>never need to edit ProPhoto theme files</strong> using this page. We <strong>do not support</strong> questions or problems that arise from hand-editing theme files.  More info on why you shouldn\'t edit these files and how to accomplish what you want without editing them <a href="' . EDIT_THEME_FILES_TUT . '">can be found here</a>.';

$p3_notices['wpurl_change_warning_js'] = "
<script type=\"text/javascript\" charset=\"utf-8\">jQuery(document).ready(function(){jQuery('input#siteurl, input#home').click(function(){
jQuery('input#siteurl, input#home').css('border', 'solid red 1px');if (!jQuery('#siteurl-change-warn').length) jQuery('input#siteurl').parents('tr').before('<tr id=\"siteurl-change-warn\"><td colspan=\"2\"><p>ProPhoto Users: Changing these values <strong>without also making changes through FTP</strong> will cause your site to become <strong>inaccessible</strong>. Be sure to fully read our <a href=\"" . CHANGE_BLOG_ADDRESS_TUT . "\">tutorial here</a> before making any changes.</p></td></tr>');});});</script>";

$p3_notices['design_upload_import_success'] = REMOVE_IMPORTING_MSG . 'Successfully imported. The imported design is called <strong>%1</strong>, and will be visible in the <em>Inactive designs</em> section after <a' . CLICK_RELOAD_PARENT . '>clicking here</a> to reload the page.';

$p3_notices['invalid_design_zip'] = 'The file <strong><code>%1</code></strong> does not appear to be a valid ProPhoto3 design export zip.  Only .zip files created by the ProPhoto theme will work. Please try again.';

$p3_notices['p2_layouts_imported'] = REMOVE_IMPORTING_MSG . '<p>ProPhoto version 2 import process successful. All imported layouts will be visible in the <em>Inactive designs</em> section and available to be activated after <a' . CLICK_RELOAD_PARENT . '>clicking here</a> to reload the page.</p>';

$p3_notices['wp_version_not_supported'] = '<div style="text-align:center;width:500px;margin:50px auto;">
<h1>WP Version Not Supported</h1>
<p>The ProPhoto3 theme requires at least WordPress version <strong>2.9</strong> or newer. Your blog is running version <span style="color:red;font-family: Courier, monospace;">%1</span>.</p><p>Click, or copy this link and paste it into your browser: <br /><a href="' . SUPPORTED_VERSION_LINK . '">' . SUPPORTED_VERSION_LINK . '</a> <br />to get lots of good information on upgrading your version of WordPress, then activate another theme until you\'ve upgraded.</p><p>Remember to <a href="' . BLOG_BACKUP_LINK . '">backup your blog</a> <em>AND</em> database before you upgrade!</p>
</div>';

$p3_notices['db_backup_plugin_missing'] = '<strong>ProPhoto</strong> has detected that you are not running our recommended<strong> database backup plugin</strong>.  You alone are responsible for <strong>always having a recent backup of your database</strong> in case something goes wrong.  The plugin we recommend makes this extremely simple and automated.  <a href="' . DB_BACKUP_NAG_TUT . '">Click here</a> for instructions on how to install the plugin and remove this notice.';

$p3_notices['flash_gal_needs_pathfixer'] = '<div style="color:red;background:white;padding:3px 6px;">ProPhoto has encoutered a problem trying to display your flash gallery.  Usually this problem occurs when you have changed your blog address without turning on the <strong>Blog path fixer</strong> and entering the old address of your blog.  You can find that option on the bottom of <a href="%1" style="color:blue;text-decoration:underline">this page</a>. Don\'t worry, <strong>only you can see this message</strong> because you are logged in as an administrator.</div>';

$p3_notices['requires_feedburner_feed'] = 'In order to allow your blog reader to subscribe to your posts by email, you must burn your blog\'s feed to a <strong>Feedburner Feed</strong>.  You can do this by first setting up a Feedburner feed (<a href="' . BURN_FEEDBURNER_FEED_TUT . '">tutorial</a>), and then going <a href="%1">here</a> to enter in your Feedburner URL.  Then come back here, and you will be able to use the Feedburner Subscribe-by-email functionality.';

$p3_notices['requires_email_subscription_enabled'] = '<strong>NOTE:</strong> in order for this to work, you have to have <strong>email subscriptions enabled</strong> in your Feedburner feed account. Tutorial <a href="' . FEEDBURNER_SUBSCRIBE_TUT . '" target="_blank">here</a>.';

$p3_notices['deactivate_p3_before_upgrade'] = '<p style="font-size:18px; text-align:center;margin-bottom:100px;width:660px;margin:0 auto 100px auto;"><span style="color:red;font-weight:bold">IMPORTANT:</span> You must <strong>de-activate ProPhoto3 before upgrading WordPress</strong>.  To do so, go to <em>"Appearance" => "Themes"</em> in the left sidebar, and click one of the "Activate" buttons below an available theme.<br /><br /><span style="font-size:15px;">After successfully upgrading WordPress, you may re-activate ProPhoto3.</span></p>';

$p3_notices['register_globals_on'] = '<strong>IMPORTANT:</strong> Your webhost has a very is configured in a <strong>very insecure way</strong>.  This configuration can also cause a problem where whenever you try to save changes in the "P3 Customize" page, everything <strong>goes blank</strong> instead of correctly saving. To find out how to fix this, see <a href="' . REGISTER_GLOBALS_TUT . '">this tutorial</a>.';

$p3_notices['safe_mode_subfolder_problem'] = 'ProPhoto has encountered a problem trying to create some folders it needs due to a setting on your web server. Steps you can take to resolve this issue can be <a href="' . SAFE_MODE_FIX_TUT . '">found here</a>.';

$p3_notices['akismet_notice'] = 'We highly recommend activating the plugin "Akismet" to catch <strong>comment spam</strong>. It\'s super easy to do, our tutorial <a href=' . COMMENT_SPAM_TUT . '>is here</a>.';

$p3_notices['old_wp_version_hack_warning'] = 'Hello, this is a notice from your ProPhoto blog that the <strong>version of WordPress</strong> you are currently running is <strong>dangerously out-of-date</strong>.<br /><br />The reason this is important is because there are serious security problems with old versions of WordPress that <strong>hackers can use to insert malicious code into your blog</strong>. The only way to ensure that you won\'t get hacked is to always keep your WordPress blog up to date with the latest version of WordPress.<br /><br />Upgrading is <strong>free and easy</strong>.  For most people it takes less than 30 seconds and just a few clicks of a mouse.  We have an easy-to-follow tutorial here:<br /><br />' . UPGRADING_WP_TUT . '<br /><br />If you do not upgrade soon, you have a very high chance of having your blog hacked.  A hacked blog can get you <strong>banned from Google, can bring down your website, and can cause you to lose your posts and comments.</strong> Unhacking a blog is time-consuming, frustrating, and often expensive.<br /><br />We strongly recommend that you take a few moments right now to <strong>upgrade your WordPress version.</strong>';

$p3_notices['ftp_info_advise_core'] = '<p id="ftp-advise">The information being requested here is your <strong>web-host account FTP login</strong> info.  If you do not know what it is, contact your web-host tech support.  <strong>Do not contact ProPhoto</strong>, as we do not know your FTP info. If you <strong>can not get this to work</strong>, you will have to upgrade WordPress manually.  Follow our <a href="' . UPGRADING_WP_TUT . '">tutorial here</a> to do so.</p>';

$p3_notices['ftp_info_advise_plugin'] = '<p id="ftp-advise">The information being requested here is your <strong>web-host account FTP login</strong> info.  If you do not know what it is, contact your web-host tech support.  <strong>Do not contact ProPhoto</strong>, as we do not know your FTP info. If you <strong>can not get this to work</strong>, you will have to upgrade or install the plugin manually.  Follow our <a href="' . MANUAL_PLUGIN_INSTALL_TUT . '">tutorial here</a> to do so.</p>';

$p3_notices['no_theme_options_found'] = 'ProPhoto is having a problem reading your stored options. First, <strong>try refreshing this page</strong>, the problem may resolve on it\'s own.<br /><br />If it does not, and <strong>you have not made any customizations yet</strong>, you may force-reset ProPhoto by <a href="' . admin_url( 'themes.php?page=p3-designs&show_reset=true' ) . '">clicking here</a> and then clicking the "Reset Everything" button that appears under "Upload Design Zip".%1';

$p3_notices['restore_from_backups'] =  '<br /><br />If you <strong>have made customization changes</strong>, you may <a href="' . admin_url( 'themes.php?page=p3-designs&restore_from_backups=1&restore_most_recent=1' ) . '">restore a backup of your most recent saved changes</a>, or <a href="' .  admin_url( 'themes.php?page=p3-designs&restore_from_backups=1' ) . '">view all available backups</a> and choose which to restore.';

$p3_notices['no_theme_options_found_comment'] = "\n\n\n\n<!--\n\nP3 THEME ERROR !!!!!!! \nNO THEME OPTIONS FOUND \nSEE p3_read_options()\n\n-->\n\n\n\n\n";

$p3_notices['backup_restored'] = '<strong>Backup successfully restored</strong>.  <a target="_blank" href="' . P3_URL .'">View your blog</a> to see if things look correct.  Or, you can try restoring a different backup.';

$p3_notices['wp_menus_not_used'] = '<strong>ProPhoto does not use this menu page</strong> to create your blog\'s main nav menu, or for any other purpose. Instead, you can edit the appearance and content of your menu on <a href="%1">this page</a>.';

$p3_notices['wpmu_warning'] = 'ProPhoto <strong>does not work in multi-user mode</strong>.  You must use it in standard, single-user mode.';

$p3_notices['no_logging_before_pp31'] = '<p>Any contact form submissions made before using ProPhoto 3.1 were not logged.</p>';

$p3_notices['no_contact_log_entries'] = '<p>There is no record of any submitted contact form requests. Any contact form submissions made before using ProPhoto 3.1 were not logged.</p>';

$p3_notices['non_utf8_encoding'] = 'Your blog\'s character encoding must be set to <code>UTF-8</code> in the <a href="%1">Reading Settings area</a>.';

$p3_notices['link_removal_txn_id_not_found'] = 'The <strong>Transaction ID</strong> you entered to verify your purchase of a link-removal license <strong>could not be verified</strong>. Please double-check the ID and re-enter.';
?>