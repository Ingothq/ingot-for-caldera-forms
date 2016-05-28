<?php

/**
 * When form submit starts register conversion
 *
 * @since 0.2.0
 */
add_action( 'caldera_forms_submit_start', function( $form ){

	if( isset( $_POST[ 'ingotVariantId' ], $_POST[ 'ingotGroupId' ] ) ){
		ingot_register_conversion( absint( $_POST[ 'ingotVariantId' ]  ) );
	}

});

/**
 * Load admin
 *
 * @since 0.2.0
 */
add_action( 'admin_init', function(  ) {
	include_once  __DIR__ . '/classes/admin.php';
	new ingot\addon\forms\cf\admin( plugin_dir_url( __FILE__ ) . 'assets/admin.js'  );
});

/**
 * Allow our click type
 *
 * @since 0.2.0
 */
add_filter( 'ingot_allowed_click_types', function(  $types ){
	$types[ 'form-cf'    ]     = [
		'name'        => __( 'Caldera Forms', 'ingot' ),
		'description' => __( 'Find which Caldera Forms converts the best', 'ingot' ),
	];

	return $types;
});

/**
 * Add our template
 *
 * @since 0.2.0
 */
add_filter( 'ingot_click_type_ui_urls', function( $urls ){
	$urls[ 'form-cf' ] = plugin_dir_url( __FILE__ ) . '/assets/caldera-forms.html';
	return $urls;
});

/**
 * Add translation strings to UI
 *
 * @since 0.2.0
 */
add_filter( 'ingot_ui_translation_strings', function( $strings ){
	$strings[ 'forms' ][ 'cf' ] = [
		'form' => esc_html__( 'Form', 'ingot-caldera-forms' ),
		'add_form' => esc_html__( 'Add Form', 'ingot-caldera-forms' ),
		'cookie_mode_label' => esc_html__(  'Cookie Tracking', 'ingot-caldera-forms' ),
		'cookie_mode_desc' => esc_html__(  'If checked, variants will be chosen by visitor, if not they will be chosen per page load.', 'ingot-caldera-forms' ),
	];

	return $strings;

});

/**
 * Add our callback function for rendering the form in front-end
 *
 * @since 0.2.0
 */
add_filter( 'ingot_click_test_custom_render_callback', function( $cb, $type ){
	if( 'form-cf' == $type ){
		$cb = 'ingot_cf_cb';

	}
	return $cb;
}, 10, 2);


/**
 * Callback to render the form
 *
 * @since 0.2.0
 *
 * @param array $group Group config
 *
 * @return string
 */
function ingot_cf_cb( $group ){
	include_once __DIR__ . '/classes/render.php';
	$ui = new ingot\addon\forms\cf\render( $group );
	return $ui->get_html();
}

