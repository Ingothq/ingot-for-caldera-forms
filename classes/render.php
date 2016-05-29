<?php
/**
 * Output forms
 *
 * @package   ingot_cf
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */


namespace ingot\addon\forms\cf;

class render extends \ingot\ui\render\click_tests\click {

	/**
	 * Make HTML for to output and set in the html property of this class
	 *
	 * @since 0.0.2
	 *
	 * @access protected
	 */
	protected function make_html() {

		$form_id = $this->get_variant_content();
		$form = \Caldera_Forms_Forms::get_form( $form_id );


		add_filter( 'caldera_forms_render_form_attributes', function( $atts ){
			$atts[ 'data-ingot-group-id' ] = $this->get_group()[ 'ID' ];
			$atts[ 'data-ingot-variant-id' ] =  $this->get_variant()[ 'ID' ];
			return $atts;
		});

		$form_html =  \Caldera_Forms::render_form( $form  );
		$this->html = $form_html;


	}


}
