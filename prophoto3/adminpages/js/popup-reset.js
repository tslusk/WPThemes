/* Scripts for the Reset Images iframed popup */

// We're sending some information to the opening page (to scripts in upload.js)
function p3_sendtopage(url, shortname, width, height, size) {
	var win = window.opener ? window.opener : window.dialogArguments;
	if (!win) win = top;
	win.p3_update_theme_image(url, shortname, width, height, size, false);
}
