<?php
$twitter_slider_widget_css = ( p3_is_active_widget( 'p3-twitter-slider' ) ) ? P3_Twitter_Slider::css() : '';
echo <<<CSS
p.icon-note {
	margin:0 !important;
}
.widget_calendar th {
	font-weight:bold;
}
.widget_calendar td {
	padding:0 2px;
}
li.widget li {
	margin-left:1.2em;
	line-height:1.1em;
	margin-bottom:0.7em;
	list-style-type:disc;
	list-style-position:outside !important;
}
li.widget .p3-html-twitter-widget li {
	margin-left:0;
	list-style-type:none;
}
li.widget #searchsubmit {
	margin-top:0.3em;
}
$twitter_slider_widget_css
h3.widgettitle {
	line-height:1em;
	margin-bottom:0.35em;
}
.twitter-interactive-badge {
	height:350px;
}
.js-info {
	display:none;
}
.twitter-follow-link {
	margin-top:4px;
}
.twitter-follow-link a {
	font-size:10px;
	text-decoration:none;
	line-height:1em;
}
.p3-twitter-html p {
	font-size:.8em;
	text-align:right;
	font-style:italic;
}
.p3-twitter-html p a {
	font-style:italic;
}
.p3-twitter-html li {
	font-size:.9em;
	line-height:1.2em;
	margin-bottom:.75em;
	margin-left:0 !important;
}
.twitter-interactive-badge-wrap {
	width:290px;
	height:350px;
}
.twitter-simple-badge-wrap {
	width:176px;
	min-height:176px;
}
.twitter-simple-badge-wrap a {
	font-size:10px;
	text-align:center;
	display:block;
	line-height:1em;
	margin-top:3px;
}
#outer-wrap-centered .widget_p3-twitter-com-widget a img {
	height:15px !important;
}
.widget_p3-facebook-likebox iframe {
	background:#fff;
}
CSS;
?>
