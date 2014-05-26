<?php
/* ------------------------------------- */
/* -- class for printing upload boxes -- */
/* ------------------------------------- */


/* wrapper function for printing an image upload box */
function p3_image( $img_id, $pretty_name = '', $comment = '' ) {
	$img_box = new p3_upload( $img_id, $pretty_name, $comment );
	echo $img_box->markup;
}


/* wrapper function for printing file upload box */
function p3_file( $file_id, $pretty_name = '', $comment = '' ) {
	$file_box = new p3_file( $file_id, $pretty_name, $comment );
	echo $file_box->markup;
}


/* wrapper function for printing bg image upload & options */
function p3_bg( $img_id, $pretty_name = '', $comment = '', $show_img_options = TRUE ) {
	$bg_box = new p3_bg( $img_id, $pretty_name, $comment, $show_img_options );
	echo $bg_box->markup;
}


/* wrapper function for printing image with optional link URL */
function p3_linked_image( $img_id, $pretty_name = '', $comment = '', $link_comment = '' ) {
	$linked_image = new p3_linked_image( $img_id, $pretty_name, $comment, $link_comment );
	echo $linked_image->markup;
}


/* wrapper function for returning markup for upload area within option area */
function p3_mini_image( $img_id ) {
	$mini_image = new p3_mini_image( $img_id );
	return $mini_image->markup;
}


/* wrapper function for printing image upload area with associated option */
function p3_image_with_option( $img_args, $option_args ) {
	$image_with_option = new p3_image_with_option( $img_args, $option_args );
	echo $image_with_option->markup;
}



/* the main parent class */
class p3_upload {
	
	/* php 5 constructor */
	function __construct( $img_id, $pretty_name = '', $comment = '' ) {
		//initial setup
		$this->img_id      = $img_id;
		$this->pretty_name = ( $pretty_name ) ? $pretty_name : $this->img_id;
		$this->comment     = ( $comment ) ? "<p class='img-comment'>$comment</p>" : '';
		
		// run methods
		$this->setup_data();
		$this->early_hook();
		$this->filetype_properties();
		$this->middle_hook();
		$this->html_chunks();
		$this->late_hook();
		$this->get_option_classes();
		
		// get markup
		$this->get_complete_markup();
	}
	
	
	/* php 4 constructor */
	function p3_upload( $img_id, $pretty_name = '', $comment = '' ) {
		$this->__construct( $img_id, $pretty_name, $comment );
	}
	
	
	/* function "hooks" for child classes to modify properties */
	function early_hook() {}
	function middle_hook() {}
	function late_hook() {}
	
	
	/* setup internal data */
	function setup_data() {
		global $p3, $p3_defaults, $p3_img_size_recommendations;
		
		// image url and path
		$this->imgurl  = p3_imageurl( $this->img_id, FALSE );
		$this->imgpath = p3_imagepath( $this->img_id, FALSE );
		
		// basic facts
		$this->img_is_optional   = ( $p3_defaults['images'][$this->img_id] == 'nodefaultimage.gif' ) ? TRUE : FALSE;
		$this->is_masthead_image = ( strpos( $this->img_id, 'masthead_image' ) !== FALSE ) ? TRUE : FALSE;
		$this->image_exists      = p3_image_exists( $this->img_id );
		$this->file_exists       = ( $this->imgurl == '#') ? FALSE : file_exists( $this->imgpath );
		$this->filename          = basename( $this->imgpath );
		$this->imgname_is_in_db  = ( $p3[$p3['active_design']]['images'][$this->img_id] ) ? TRUE : FALSE;
		$this->max_img_display_width = MAX_UPLOAD_BOX_IMG_WIDTH;	

		// CSS switch to show/hide the reset image button
		$this->reset_btn_display = ( $this->image_exists ) ? '' : 'display:none;';		

		// Image dimensions
		$file_infos = p3_image_infos( $this->imgpath );
		if ( $file_infos ) {
			$this->img_width  = $file_infos[0];
			$this->img_height = $file_infos[1];
		} else {
			$this->img_width  = 'unknown';
			$this->img_height = 'unknown';
		}
		
		// File size
		$this->filesize = @filesize( $this->imgpath ) / 1024;
		if ( $this->filesize && $this->filesize < 1 ) $this->filesize = 1;
		$this->filesize = number_format( $this->filesize );
		if ( substr( $this->imgpath, -1 ) == '/' ) $this->filesize = 0;

		// hardcoded size recommendations
		if ( $p3_img_size_recommendations[$this->img_id] ) {
			$this->has_reco    = TRUE;
			$this->reco_width  = $p3_img_size_recommendations[$this->img_id]['width'];
			$this->reco_height = $p3_img_size_recommendations[$this->img_id]['height'];
			
		// masthead image: calculate recommended sizes
		} else if ( strpos( $this->img_id, 'masthead_image' ) !== FALSE ) {
			$this->has_reco    = TRUE;
			$this->reco_width  = p3_masthead_image_recommendation( 'width' );
			$this->reco_height = p3_masthead_image_recommendation( 'height' );
		
		// no recommendations
		} else {
			$this->has_reco = $this->reco_width = $this->reco_height = FALSE;
		}
		
		// debug
		$this->debug = ( P3_DEV ) ? "<tt>$this->img_id</tt>" : '';
	}
	
	
	/* properties related to the file as an image */
	function filetype_properties() {
		// button links
		$this->upload_form_url = p3_popup_href( "do=upload&p3_image=$this->img_id&misc=false", '410', '110' );
		$this->reset_form_url  = p3_popup_href( "do=reset&p3_image=$this->img_id&misc=false", '410', '110' );
		
		// reset label text
		$this->reset_link_text = ( $this->img_is_optional ) ? 'Delete Image' : 'Reset Image';
		
		// upload button label
		if ( $this->img_is_optional ) {
			$this->upload_button_label = ( $this->image_exists ) ? 'Replace Image' : 'Upload Image';
		} else {
			$this->upload_button_label = 'Replace Image';
		}
		
		// image size recommendation box
		$this->recommendation_box = ( $this->has_reco ) ? $this->img_recommendation_box() : '';
	}
	
	
	/* assemble chunks of html markup */
	function html_chunks() {
		$this->file_display_markup = $this->get_file_display_markup();
		$this->stats_box = $this->get_stats_box();
		$this->reset_link = "<a href='{$this->reset_form_url}' style='{$this->reset_btn_display}' id='p3_reset_button_{$this->img_id}' class='button-secondary thickbox p3_reset_button'>{$this->reset_link_text}</a>";
	}
	
	
	/* build string of appropriate classes for option area */
	function get_option_classes() {
		// basic classes
		$c   = p3_get_interface_classes( $this->img_id );
		$c[] = 'option';
		$c[] = 'upload-row';
		$c[] = 'self-clear';
		
		// img existence classes
		if ( !$this->image_exists ) {
			$c[] = 'empty';
			$c[] = 'no-img';
		} else {
			$c[] = 'has-img';
		}
		// class for upload area currently holding a very small image
		if ( $this->image_exists && ( ( $this->img_width + $this->img_height ) < 50 ) ) {
			$c[] = 'very-small-image';
		}
		
		// hook for child classes
		foreach ( (array) $this->added_option_classes as $class ) {
			$c[] = $class;
		}
		
		$c = apply_filters( 'p3_upload_option_classes', $c, $this->img_id );
		$this->option_classes = implode( ' ', $c );
	}
	
	
	/* get completely assembled markup */
	function get_complete_markup() {
		$markup = <<<HTML
		<div class="{$this->option_classes}" id="upload-row-{$this->img_id}"$display>
			<div class="upload-row-top self-clear">
				<a id="{$this->img_id}-cfe" title="help" class="click-for-explain"></a>
				<div class="upload-row-label option-label">$this->debug
					<p>$this->pretty_name</p>
				</div>
				<div id="explain-{$this->img_id}" class="extra-explain" style="display:none;"></div>
			</div>
			
			$this->above_options
			
			<div class="upload-row-body self-clear">
				$this->left_options
			
				<div class="image-info">
					<div class="self-clear">
						<a href='$this->upload_form_url' class='button-secondary thickbox p3_upload_button'>$this->upload_button_label</a> 			
						$this->reset_link
					</div>
					$this->stats_box
					$this->recommendation_box
					$this->left_below_options		
				</div>

				<div class="image-holder">
					$this->comment
					$this->file_display_markup
				</div>

				$this->below_options
			</div>
		</div>
HTML;
		$markup = apply_filters( "p3_upload_markup_{$this->img_id}", $markup );
		$this->markup = apply_filters( 'p3_upload_markup', $markup, $this->img_id );
	}
	
	
	/* subroutine to return markup for current image stats box */
	function get_stats_box() {
		// show the stats only if image exists. div is loaded but hidden for JS to show after upload
		$display = ( $this->image_exists ) ? '' : ' style="display:none;"';

		// add class for images with no default
		if ( $this->img_is_optional ) $no_default_class = ' nodefault';

		return <<<HTML
			<ul class='p3_image_infos self-clear{$no_default_class}' id='p3_imginfos_{$this->img_id}'$display> 
				<li class="title"><strong>Current Image Stats:</strong><br />
				<li><strong>Width:</strong> <span class='p3_imginfos_width'>$this->img_width</span> pixels</li>
				<li><strong>Height:</strong> <span class='p3_imginfos_height'>$this->img_height</span> pixels</li>
				<li class="size"><strong>Size:</strong> <span class='p3_imginfos_size'>$this->filesize</span> kb</li>
				$this->not_fullsize_msg
			</ul>
HTML;
	}

	
	/* subroutine to return markup for image recommendation box */
	function img_recommendation_box() {
		// image conforms to recommended dimensions
		if ( $this->follows_recommendations() )  {
			$displaygood = '';
			$displaybad = 'none';
		
		// image does not conform
		} else {
			$displaygood = 'none';
			$displaybad = '';
		}
		
		// image does not exist
		if ( !$this->image_exists ) $displaybad = 'none';

		// explanation of recommendation according for header image
		if ( $this->is_masthead_image ) {
			
			$reco_explain = 'Based on your header layout choice';
			
			// if header layout has logo constraining header height
			if ( p3_logo_masthead_sameline() ) {
				$reco_explain .= ' and current logo dimensions';
			}
			$reco_explain .= ', this image should be <strong>exactly</strong>';
		
		// non header image
		} else {
			$reco_explain = 'This image should be <strong>exactly</strong>';
		}
		
		// size dimensions
		if ( $this->reco_width  && !$this->reco_height ) $reco_explain .= ' this width';
		if ( $this->reco_height && !$this->reco_width  ) $reco_explain .= ' this height';
		if ( $this->reco_width  &&  $this->reco_height ) $reco_explain .= ' these dimensions';

		// the recommendations in pixels
		if ( $this->reco_width ) {
			$recos_display .= "<li><strong>Width:</strong> <span class='p3_recommended_width'>$this->reco_width</span> pixels</li>\n";
		}
		if ( $this->reco_height ) {
			$recos_display .= "<li><strong>Height:</strong> <span class='p3_recommended_height'>$this->reco_height</span> pixels</li>";
		}

		return <<<HTML
		<ul class="p3_image_infos p3_image_infos_recommend" id="p3_recommendation_{$this->img_id}">
			<li class="title"><strong>Recommended Image Size:</strong></li>
			<li>$reco_explain</li>
			$recos_display
			<li class="p3_recommended p3_recommended_actual:{$this->img_width}:{$this->img_height}">
				<span class='p3_fh_dimensions_ok' style='display:$displaygood'>
					Image size correct.
				</span>
				<span class='p3_fh_dimensions_notok' style='display:$displaybad'>
					Current image does not meet recommendations.
				</span>
			</li>
		</ul> 
HTML;
	}	
	
	
	/* boolean returns if an image complies to recommendations */
	function follows_recommendations() {
		// get actual file dimensions
		$actual_width = p3_imagewidth( $this->img_id, NO_ECHO );
		$actual_height = p3_imageheight( $this->img_id, NO_ECHO );

		// start by assuming width and height are incorrect
		$width_follows = $height_follows = FALSE;

		// test the width, true if correct or no recommendation
		if ( $this->reco_width ) {
			if ( $actual_width == $this->reco_width ) $width_follows = TRUE;
		} else {
			$width_follows = TRUE;
		}

		// test the height, true if correct or no recommendation
		if ( $this->reco_height ) {
			if ( $actual_height == $this->reco_height ) $height_follows = TRUE;
		} else {
			$height_follows = TRUE;
		}

		if ( $width_follows AND $height_follows ) return TRUE;
		else return FALSE;
	}
	
	
	/* subroutine to return markup for the upload box image/file display section */
	function get_file_display_markup() {
		
		// Width attribute will be set to be 400 or nothing, advise if image is shrunk
		if ( $this->img_width > $this->max_img_display_width ) {
			$maxwidth = ' width="' . $this->max_img_display_width . '"';
			$this->not_fullsize_msg  = "<li class='p3_widthmsg'>Not shown <a href=\"$this->imgurl\">fullsize</a></li>";
		}

		// return markup for image
		return $markup = 
			"<img title='$this->img_id' id='p3_image_$this->img_id' class='p3_upload_image' src='{$this->imgurl}?{$this->img_width}'$maxwidth /> <span class='highlight-small-img'><strong>&larr</strong> image</span>";
	}
}



/* child class: p3 file upload */
class p3_file extends p3_upload {
	
	/* php 5 constructor */
	function __construct( $img_id, $pretty_name, $comment ) {
		$this->is_file = TRUE;
		$this->added_option_classes = array( 'file', 'nodefault' );
		parent::__construct( $img_id, $pretty_name, $comment );
	}
	
	
	/* properties specific to misc file uploads */
	function filetype_properties() {
		$this->upload_form_url = p3_popup_href( "do=upload&p3_image=$this->img_id&misc=true", '410', '110' );
		$this->reset_form_url  = p3_popup_href( "do=reset&p3_image=$this->img_id&misc=true", '410', '110' );
		$this->reset_link_text = 'Delete File';
		$this->upload_button_label = ( $this->file_exists ) ? 'Replace File' : 'Upload File';
		$this->reset_btn_display = ( $this->file_exists ) ? '' : 'display:none;';
	}
	
	
	/* subroutine to return markup for current misc file stats box */
	function get_stats_box() {
		// we have a file
		if ( !$this->filesize ) $display = 'style="display:none"';
		$file_extension = ( $this->filesize ) ? preg_replace( '/.+\./', '', $this->filename ) :'';
		
		return <<<HTML
		<ul class='p3_image_infos' id='p3_imginfos_{$this->img_id}'$display> 
			<li><strong>Current File Stats:</strong><br />
			<li><strong>File type</strong>: <span class="p3_imginfos_ext">$file_extension</span></li>
			<li><strong>Size:</strong> <span class='p3_imginfos_size'>$this->filesize</span> kb</li>
		</ul>
HTML;
	}
	
	
	/* display area for file */
	function get_file_display_markup() {
		// name holds actual file name or is dirname if system can't find file
		if ( !$this->filename || strpos( $this->filename, '.' ) === FALSE ) return;

		// get file type from extention of mis file
		$parts = explode( '.', basename( $this->filename ) );
		$extension = end( $parts );
		$filetype = p3_get_filetype( $extension );

		// display markup for misc file
		$file_display = ( 'favicon icon' == $filetype ) 
			? '<img src="' . p3_imageurl( $this->img_id, FALSE ) . '">' 
			: "<a href='$this->imgurl'>$this->filename</a>";

		return $markup = 
			"<div class='p3_upload_misc p3_upload_misc_$filetype'>
				<p>$file_display</p>
			</div>";
	}

	
	/* php 4 constructor */
	function p3_file( $img_id, $pretty_name, $comment ) {
		$this->__construct( $img_id, $pretty_name, $comment );
	}
}



/* child class for background images with associated options built-in */
class p3_bg extends p3_upload {
	
	/* php 5 constructor */
	function __construct( $img_id, $pretty_name, $comment, $show_img_options ) {
		$this->show_img_options = $show_img_options;
		$this->bg_options( $img_id );
		parent::__construct( $img_id, $pretty_name, $comment );
	}

	
	/* override parent properties at various points */
	function early_hook() {
		$this->max_img_display_width = 394;
	}
	function middle_hook() {
		$this->upload_button_label = str_replace( 'Image', 'Background Image', $this->upload_button_label );
		$this->reset_link_text     = str_replace( 'Image', 'Background Image', $this->reset_link_text );
	}
	function late_hook() {
		$c[] = 'bg-option-group';
		// special classes for display of IE6-fallback color
		if ( $this->image_exists AND stripos( $this->imgpath, '.png' ) !== FALSE ) {
			$c[] = 'is-png';
		}
		if ( !p3_optional_color_bound( $this->img_id . '_color' ) ) {
			$c[] = 'bg-color-not-set';
		}
		$this->added_option_classes = $c;
	}
	
	
	/* add options for bg image */
	function bg_options( $img_id ) {
		$bg_color = new p3_option_box( $img_id . '_color', 'color|optional', 'background color', '' );
		$this->left_options = $bg_color->option_markup;
			
		// open bg-image area wrapping div
		$bg_bound_class = ( !p3_optional_color_bound( $img_id . '_color' ) ) ? ' bg-color-not-set' : '';
		$options_display = ( p3_image_exists( $img_id ) ) ? '' : ' style="display:none;"';
		$open_bg_options =  '<div id="bg-img-options-' . $img_id . '" class="bg-img-options self-clear' . $bg_bound_class . '"' . $options_display . '>';
		$options .= $open_bg_options;
		
		// bg repeat
		$repeat = new p3_option_box( 
			$img_id . '_img_repeat', 'select|repeat|tile|repeat-y|tile only vertically|repeat-x|tile only horizontally|no-repeat|do not tile', 'background image tiling', '' );
		$options .= $repeat->option_markup;
		
		// bg position
		$position = new p3_option_box( $img_id . '_img_position', 'select|top left|top left|top center|top center|top right|top right|center left|center left|center center|center center|center right|center right|bottom left|bottom left|bottom center|bottom center|bottom right|bottom right', 'background image starting position', '' );
		$options .= $position->option_markup;
		
		// bg attachment
		if ( $img_id == 'blog_bg' || $img_id == 'comments_comment_outer_bg' ) {
			$attachment = new p3_option_box( $img_id . '_img_attachment', 'radio|scroll|scroll with page|fixed|stay fixed in place', 'background image scrolling behavior', '' );
			$options .= $attachment->option_markup;
		}
		
		// ie6-only fallback color
		$ie6_color = new p3_option_box( $img_id . '_ie6_color', 'color|optional', 'fallback background color for just Internet Explorer 6 - set if using a semi transparent .png file &amp; no background color', '' );
		$ie6_option = "<div class='ie6-color'>{$ie6_color->option_markup}</div>";
		$options .= $ie6_option;
		
		// close wrapping div and store to class property
		$options .= '</div>';
		if ( !$this->show_img_options ) $options = $open_bg_options . $ie6_option . '</div>';
		$this->above_options = $options;
	}
	
	
	/* php 4 constructor */
	function p3_bg( $img_id, $pretty_name, $comment, $show_img_options ) {
		$this->__construct( $img_id, $pretty_name, $comment, $show_img_options );
	}
}



/* child class for an image with an optional link URL */
class p3_linked_image extends p3_upload {
	
	/* php 5 constructor */
	function __construct( $img_id, $pretty_name, $comment, $link_comment ) {
		$this->link_comment = ( $link_comment ) ? $link_comment : 'optional URL link for this image';
		$this->link_input( $img_id );
		parent::__construct( $img_id, $pretty_name, $comment );
	}
	
	
	/* add markup for link input associated with uploaded image */
	function link_input( $img_id ) {
		$linkurl = new p3_option_box( $img_id . '_linkurl', 'text|25', $this->link_comment, '' );
		$this->left_below_options = "<div class='below-options only-if-img self-clear'>$linkurl->option_markup</div>";
	}
	
	
	/* php 4 constructor */
	function p3_linked_image( $img_id, $pretty_name, $comment, $link_comment ) {
		$this->__construct( $img_id, $pretty_name, $comment, $link_comment );
	}
}



/* child class for an image with an optional link URL */
class p3_audio_upload extends p3_file {
	
	/* php 5 constructor */
	function __construct( $file_id, $pretty_name, $comment ) {
		$this->audio_filename_input( $file_id );
		parent::__construct( $file_id, $pretty_name, $comment );
	}
	
	
	/* add markup for link input associated with uploaded image */
	function audio_filename_input( $file_id ) {
		$filename = new p3_option_box( $file_id . '_filename', 'text|24', 'song name', '' );
		$this->left_below_options = "<div class='below-options only-if-file self-clear'>$filename->option_markup</div>";
	}
	
	/* add class */
	function late_hook() {
		if ( $this->file_exists ) $this->added_option_classes[] = 'has-file';
		else $this->added_option_classes[] = 'no-file';
	}
	
	
	/* php 4 constructor */
	function p3_linked_image( $file_id, $pretty_name, $comment ) {
		$this->__construct( $file_id, $pretty_name, $comment );
	}
}



/* child class for upload areas within multiple option areas */
class p3_mini_image extends p3_upload {
	
	/* php 5 constructor */
	function __construct( $img_id ) {
		parent::__construct( $img_id, '', '' );
	}
	
	/* custom, stripped-down markup */
	function get_complete_markup() {
		if ( $this->img_is_optional ) $class = 'nodefault';
		$this->markup = <<<HTML
		<div id="upload-row-{$this->img_id}" class="mini-img-wrap">
			<span id="p3_imginfos_{$this->img_id}" style="display:none" class="$class"></span><!-- for js  -->		
			<div class="image-holder">
				$this->file_display_markup
			</div>
			<div class="image-info">
				<div class="mini-img-btn-wrap self-clear">
					<a href='$this->upload_form_url' class='button-secondary thickbox p3_upload_button'>$this->upload_button_label</a> 			
					$this->reset_link
				</div>		
			</div>
		</div>
HTML;
	}
	
	/* php 4 constructor */
	function p3_mini_image( $img_id ) {
		$this->__construct( $img_id );
	}
}


/* child class for upload areas with one associated option */
class p3_image_with_option extends p3_upload {
	
	/* php 5 constructor */
	function __construct( $img_args, $option_args ) {
		$this->add_option( $option_args );
		list( $img_id, $pretty_name, $comment ) = $img_args;
		parent::__construct( $img_id, $pretty_name, $comment );
	}
	
	
	/* php 4 constructor */
	function p3_image_with_option( $img_args, $option_args ) {
		$this->__construct( $img_args, $option_args );
	}
	
	/* setup added option */
	function add_option( $option_args ) {
		list( $id, $params, $comment ) = $option_args;
		$added_option = new p3_option_box( $id, $params, $comment, '' );
		$this->left_below_options = "<div class='below-options only-if-img self-clear'>$added_option->option_markup</div>";
	}
	
}


/* child class for background images with associated options built-in */
class p3_nav_icon_link extends p3_upload {
	
	/* php 5 constructor */
	function __construct( $icon_id ) {
		$this->link_num = preg_replace( '/[^0-9]/', '', $icon_id );
		$this->link_options( $this->link_num );
		parent::__construct( $icon_id, 'Custom link #' . $this->link_num, 'Optional uploaded icon to go with this link. <strong>Note</strong>: icon will be constrained to the height of the menu text, which is currently <strong>' . p3_get_option( 'nav_link_font_size' ) . 'px</strong>.' );
	}

	
	/* override parent properties at various points */
	function middle_hook() {
		$this->upload_button_label = str_replace( 'Image', 'Icon', $this->upload_button_label );
		$this->reset_link_text     = str_replace( 'Image', 'Icon', $this->reset_link_text );
	}
	function late_hook() {
		$c[] = 'nav-icon-link';
		if ( !p3_test( 'nav_customlink' . $this->link_num . '_url' ) ) $c[] = 'nav-icon-link-empty';
		$this->added_option_classes = $c;
	}
	
	
	/* add options for bg image */
	function link_options( $num ) {
		$text   = new p3_option_box( 'nav_customlink' . $num . '_title', 'text|28', 'link text for custom link #' . $num, '' );
		$url    = new p3_option_box( 'nav_customlink' . $num . '_url', 'text|28', 'web address for custom link #' . $num, '' );
		$target = new p3_option_box( 'nav_customlink' . $num . '_target', 'radio|_self|same window|_blank|new window', 'link, when clicked, opens in same browser window or new window/tab', '' );
		$this->above_options = '<div class="nav-link self-clear">' . $text->option_markup . $url->option_markup . $target->option_markup . '</div>';
	}
	
	
	/* php 4 constructor */
	function p3_nav_icon_link( $icon_id ) {
		$this->__construct( $icon_id );
	}
}
?>