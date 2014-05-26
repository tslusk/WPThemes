<?php
/* ---------------------------------------------------- */
/* -- class for printing groupings of border options -- */
/* ---------------------------------------------------- */


/* helper function to set up the instantiation of the p3_border_group class */
function p3_border_group( $args ) {
	global $p3_border_args;
	$p3_border_args = $args;
	p3_o( $args['key'] . '_group', 'function|p3_new_border_group', $args['comment'], $args['title'] );
}


/* helper function to instantiate the p3_border_group class from within an option area */
function p3_new_border_group() {
	global $p3_border_args;
	$border_group = new p3_border_group( $p3_border_args );
	return $border_group->markup;
}


/* border group class */
class p3_border_group {
	
	
	/* php 5 constructor */
	function __construct( $args ) {		
		$this->key = $args['key'];
		$this->minwidth = ( isset( $args['minwidth'] ) ) ? (string) $args['minwidth'] : '1';	
		$this->setup_options();
		$this->build_markup();
	}
	
	
	/* prepare markup for individual items */
	function setup_options() {
		// border color
		$color = new p3_option_box( $this->key . '_color', 'color', '', '' );
		$this->color_markup = $color->input_markup;

		// border width
		$width = new p3_option_box( $this->key . '_width', 'slider|' . $this->minwidth . '|40|px width', '', '' );
		$this->width_markup = $width->input_markup;
		
		// border style
		$style = new p3_option_box( $this->key . '_style', BORDER_STYLE_SELECT, '', '' );
		$this->style_markup = $style->input_markup;
	}
	
	
	/* return html markup of border group */
	function build_markup() {
		$classes_array = p3_get_interface_classes( $this->key );
		$classes_array[] = 'border-group';
		$classes = implode( ' ', $classes_array );
		$this->markup = <<<HTML
		<div class="$classes">
			<div class="border-options-top self-clear">
				<div class="border-option">$this->style_markup</div>
				<div class="border-option border-color">$this->color_markup</div>
			</div>
			<div class="border-option">$this->width_markup</div>
		</div>
HTML;
	}
	
	
	/* php 4 constructor */
	function p3_border_group( $args ) {
		$this->__construct( $args );
	}
}

?>