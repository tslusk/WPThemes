<?php
/* ------------------------------------------------------- */
/* -- class for printing groupings of font/link options -- */
/* ------------------------------------------------------- */



/* helper function to set up the instantiation of the p3_font_group class */
function p3_font_group( $args ) {
	global $p3_font_args;
	$p3_font_args = $args;
	p3_o( $args['key'] . '_font_group', 'function|p3_new_font_group', $args['comment'], $args['title'] );
}


/* helper function to instantiate the p3_font_group class from within an option area */
function p3_new_font_group() {
	global $p3_font_args;
	$font_group = new p3_font_group( $p3_font_args );
	return $font_group->markup();
}


/* font group class */
class p3_font_group{
	
	/* php 5 constructor */
	function __construct( $args ) {
		$this->args = $args;
		$this->key = $args['key'];
		$key = $this->key;
		$this->include_options = $this->included_options();
		$this->get_inheritance();
		$this->preview_area();
		
		// stored values
		$this->transform_val     = p3_get_option( $key . '_text_transform' );
		$this->style_val         = p3_get_option( $key . '_font_style' );
		$this->weight_val        = p3_get_option( $key . '_font_weight' );
		$this->lineheight_val    = p3_get_option( $key . '_line_height' );
		$this->letterspacing_val = p3_get_option( $key . '_letterspacing' );
		
		// css display vals
		$this->css_transform_val  =  str_replace( '.', '', $this->transform_val );
		$this->css_style_val      =  str_replace( '.', '', $this->style_val );
		$this->css_weight_val     =  str_replace( '.', '', $this->weight_val );
	    $this->css_lineheight_val =  str_replace( '.', '', $this->lineheight_val );
	
		// font size markup
		if ( $this->include_options['size'] ) {
			$size = new p3_option_box( $key . '_font_size', 'text|2', '', '' );
			$this->size_markup = '<div class="inline-option inline-text-option">' . $size->input_markup . '<span>px &nbsp;</span></div>';
		}
		
		// font family markup
		if ( $this->include_options['family'] ) {
			$family = new p3_option_box( $key . '_font_family', FONT_FAMILY_SELECT, '', '' );
			$this->family_markup = $family->input_markup;
		}
		
		// non-link font color
		if ( $this->include_options['nonlink_color'] ) {
			$optional = ( $this->can_inherit( 'nonlink_color' ) ) ? '|optional' : '';
			$nonlink_key = str_replace( '_link', '', $key );
			$nonlink_color = new p3_option_box( $nonlink_key . '_font_color', 'color' . $optional, '', '' );
			$this->nonlink_color_markup = '<div class="inline-option inline-color-option">' . $nonlink_color->input_markup . '</div>';
			$this->did_nonlink_fontcolor = TRUE;
		}
		
		// font color markup
		if ( $this->include_options['color'] && !$this->did_nonlink_fontcolor ) {
			$optional = ( $this->can_inherit( 'color' ) ) ? '|optional' : '';
			$color = new p3_option_box( $key . '_font_color', 'color' . $optional, '', '' );
			$this->color_markup = '<div class="inline-option inline-color-option">' . $color->input_markup . '</div>';
		}
		
		// font weight markup
		if ( $this->include_options['weight'] ) {
			$optional = ( $this->can_inherit( 'weight' ) ) ? '|' : '';
			$weight = new p3_option_box( $key . '_font_weight', FONT_WEIGHT_SELECT, '', '' );
			$this->weight_markup = '<a class="font-button font-button-weight font-button-weight-val-' . $this->css_weight_val . '" type="weight" val="' . $this->weight_val . '" options="700|400' . $optional . '"></a><div class="hidden-input font-group-hidden-input-weight">' . $weight->input_markup . '</div>';
		}
		
		// font style markup
		if ( $this->include_options['style'] ) {
			$optional = ( $this->can_inherit( 'style' ) ) ? '|' : '';
			$style = new p3_option_box( $key . '_font_style', FONT_STYLE_SELECT, '', '' );
			$this->style_markup = '<a class="font-button font-button-style font-button-style-val-' . $this->css_style_val . '" type="style" val="' . $this->style_val . '" options="italic|normal' . $optional . '"></a><div class="hidden-input font-group-hidden-input-style">' . $style->input_markup . '</div>';
		}
		
		// text transform markup
		if ( $this->include_options['transform'] ) {
			$optional = ( $this->can_inherit( 'transform' ) ) ? '|' : '';
			$transform = new p3_option_box( $key . '_text_transform', FONT_TRANSFORM_SELECT, '', '' );
			$this->transform_markup = '<a class="font-button font-button-transform font-button-transform-val-' . $this->css_transform_val . '" type="transform" val="' . $this->transform_val . '" options="uppercase|lowercase|none' . $optional . '"></a><div class="hidden-input font-group-hidden-input-transform">' . $transform->input_markup . '</div>';
		}
		
		// lineheight markup
		if ( $this->include_options['lineheight'] ) {
			$optional = ( $this->can_inherit( 'lineheight' ) ) ? '|' : '';
			$line_height = new p3_option_box( $key . '_line_height', FONT_LINEHEIGHT_SELECT, '', '' );
			$this->lineheight_markup = '<a class="font-button font-button-lineheight font-button-lineheight-val-' . $this->css_lineheight_val . '" type="lineheight" val="' . $this->lineheight_val . '" options="1|1.25|1.5|1.75|2' . $optional . '"></a><div class="hidden-input font-group-hidden-input-lineheight">' . $line_height->input_markup . '</div>';
		}
		
		// letterspacing markup
		if ( $this->include_options['letterspacing'] ) {
			$letterspacing = new p3_option_box( $key . '_letterspacing', 'select|normal|normal|1px|1px|2px|2px|-2px|-2px|-1px|-1px', '', '' );
			$this->letterspacing_markup = '<a class="font-button font-button-letterspacing font-button-letterspacing-val-' . $this->letterspacing_val . '" type="letterspacing" val="' . $this->letterspacing_val . '" options="normal|1px|2px|-2px|-1px"></a><div class="hidden-input font-group-hidden-input-letterspacing">' . $letterspacing->input_markup . '</div>';
		}
		
		// margin bottom markup
		if ( $this->include_options['margin_bottom'] ) {
			$margin_bottom = new p3_option_box( $key . '_margin_bottom', 'text|2', '', '' );
			$this->margin_bottom_markup = '<div class="inline-option inline-text-option">' . $margin_bottom->input_markup . '<span>px below ' . $this->args['margin_bottom_comment'] . '&nbsp;</span></div>';
		}
		
		// link markup
		if ( $this->is_link() ) $this->setup_link_markup();
	}
	
	
	/* build markup for link groupings */
	function setup_link_markup() {		
		// stored values
		$this->decoration_val  = p3_get_option( $this->key . '_decoration' );
		$this->hover_decoration_val  = p3_get_option( $this->key . '_hover_decoration' );

		// css display vals
		$this->css_decoration_val  =  str_replace( '.', '', $this->decoration_val );
		$this->css_hover_decoration_val  =  str_replace( '.', '', $this->hover_decoration_val );
		
		// normal link decoration
		if ( $this->include_options['decoration'] ) {
			$decoration = new p3_option_box( $this->key . '_decoration', LINK_DECORATION_SELECT, '', '' );
			$this->decoration_markup = '<a class="font-button font-button-decoration font-button-decoration-val-' . $this->css_decoration_val . '" type="decoration" val="' . $this->decoration_val . '" options="underline|none|overline|line-through|"></a><div class="hidden-input font-group-hidden-input-decoration">' . $decoration->input_markup . '</div>';
		}
		
		// hover link decoration
		if ( $this->include_options['hover_decoration'] ) {
			$hover_decoration = new p3_option_box( $this->key . '_hover_decoration', LINK_DECORATION_SELECT, '', '' );
			$this->hover_decoration_markup = '<a class="font-button font-button-decoration font-button-hover_decoration-val-' . $this->css_hover_decoration_val . '" type="hover_decoration" val="' . $this->hover_decoration_val . '" options="underline|none|overline|line-through|"></a><div class="hidden-input font-group-hidden-input-hover_decoration">' . $hover_decoration->input_markup . '</div>';
		}
	
		// hover color
		if ( $this->include_options['hover_color'] ) {
			$hover_color = new p3_option_box( $this->key . '_hover_font_color', 'color|optional', '', '' );
			$this->hover_color_markup = '<div class="inline-option inline-color-option">' . $hover_color->input_markup . '</div>';
		}
		
		// visited color
		if ( $this->include_options['visited_color'] ) {
			$visited_color = new p3_option_box( $this->key . '_visited_font_color', 'color|optional', '', '' );
			$this->visited_color_markup = '<div class="inline-option inline-color-option">' . $visited_color->input_markup . '</div>';
		}
		
		// link-color (only shown when non-link color included)
		if ( $this->include_options['nonlink_color'] ) {
			$link_color = new p3_option_box( $this->key . '_font_color', 'color|optional', '', '' );
			$this->link_color_markup = '<span class="font-group-subsection-label font-group-subsection-label-link">Link color:</span><div class="inline-option inline-color-option nonlink-section">' . $link_color->input_markup . '</div>';
		}
		
		// hover state options
		if ( $this->hover_decoration_markup OR $this->hover_color_markup ) $this->hover_state = <<< HTML
		<span class="font-group-subsection-label">Hover state:</span>
		$this->hover_decoration_markup
		$this->hover_color_markup
HTML;
		
		// visited state options
		if ( $this->visited_color_markup )$this->visited_state = <<< HTML
		<span class="font-group-subsection-label font-group-subsection-label-visited">Visited state:</span>
		$this->visited_color_markup
HTML;
		
		// link extra markup
		if ( $this->hover_state OR $this->visited_state or $this->link_color_markup )
			$this->link_extras = <<< HTML
		<div class="p3-font-group-subgroup p3-font-group-subgroup-links">
			$this->link_color_markup
			$this->hover_state
			$this->visited_state
		</div>
HTML;
	}
	
	
	/* boolean test: is this a link function */
	function is_link() {
		if ( strpos( $this->key, '_link' ) !== FALSE ) return TRUE;
		return FALSE;
	}
	
	
	/* return full markup for option section */
	function markup() {
		return <<< HTML
		<div id="p3-font-group-{$this->key}" class="p3-font-group self-clear">
			<div class="p3-font-group-subgroup p3-font-group-subgroup-normal">
				$this->size_markup
				$this->family_markup
				$this->weight_markup
				$this->style_markup
				$this->transform_markup
				$this->decoration_markup
				$this->lineheight_markup
				$this->letterspacing_markup
				$this->color_markup
				$this->nonlink_color_markup
				$this->margin_bottom_markup
			</div>
			$this->link_extras
		</div>
		$this->preview
HTML;
	}
	
	
	/* return array of which options to include */
	function included_options() {
		// set up defaults
		$default_options = array(
			'size'             => TRUE,
			'family'           => TRUE,
			'color'            => TRUE,
			'weight'           => TRUE,
			'style'            => TRUE,
			'transform'        => TRUE,
			'decoration'       => TRUE,
			'hover_decoration' => TRUE,
			'hover_color'      => TRUE,
			'visited_color'    => TRUE,
		);
		

		// start by setting custom options to default
		$included_options = $default_options;
		
		// remove specified options from default
		$removed_options = $this->args['not'];
		if ( is_array( $removed_options ) ) {
			foreach ( $removed_options as $removed_option ) {
				$included_options[$removed_option] = FALSE;
			}
		}
		
		// add extra options
		$added_options = $this->args['add'];
		if ( is_array( $added_options ) ) {
			foreach ( $added_options as $added_option ) {
				$included_options[$added_option] = TRUE;
			}
		}

		return $included_options;
	}
	
	
	/* boolean test, does this option inherit */
	function can_inherit( $option ) {
		if ( $this->inheriting == 'all' ) return TRUE;
		if ( $this->inheriting[$option] ) return TRUE;
		return FALSE;
	}
	
	
	/* setup inheritance info */
	function get_inheritance() {
		// everything inherits
		if ( $this->args['inherit'] == 'all' ) return $this->inheriting = 'all';
		
		// nothing inherits
		if ( !$this->args['inherit'] ) return $this->inheriting = FALSE;
		
		// individual items are specified to inherit
		if ( is_array( $this->args['inherit'] ) ) {
			foreach ( $this->args['inherit'] as $inheriting_option ) {
				$this->inheriting[$inheriting_option] = TRUE;
			}
			return $this->inheriting;		
		}
		
		// catch errors, do not inherit
		$this->inheriting = FALSE;
	}
	
	
	/* fetch font live preview text */
	function preview_area() {
		global $p3_font_preview_text;
		if ( $this->args['preview'] ) {
			$text = $this->args['preview'];
		} else if ( $p3_font_preview_text[$this->key] ) {
			$text = $p3_font_preview_text[$this->key];
		} else if ( $this->key == 'nav_link' ) {
			return; // skip menu, too complicated
		} else {
			$text = $p3_font_preview_text['default'];
		}
		$this->preview = "<div id='{$this->key}-font-preview' class='font-preview self-clear'>$text</div>";
	}	


	/* php 4 constructor */
	function p3_font_group( $args ) {
		$this->__construct( $args );
	}
} 
?>