<?php

use ingot\addon\forms\cf\cookies\init;
use ingot\addon\forms\cf\cookies\tracking;

use ingot\testing\utility\group;

/**
 * When form submit starts register conversion
 *
 * @since 0.0.2
 */
add_action( 'caldera_forms_submit_start', function( $form ){

	if( isset( $_POST[ 'ingotVariantId' ], $_POST[ 'ingotGroupId' ] ) ){
		ingot_register_conversion( absint( $_POST[ 'ingotVariantId' ]  ) );
	}

});




/**
 * Load admin
 *
 * @since 0.0.2
 */
add_action( 'admin_init', function(  ) {
	include_once  __DIR__ . '/classes/admin.php';
	new ingot\addon\forms\cf\admin( plugin_dir_url( __FILE__ ) . 'assets/admin.js'  );
});

/**
 * Allow our click type
 *
 * @since 0.0.2
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
 * @since 0.0.2
 */
add_filter( 'ingot_click_type_ui_urls', function( $urls ){
	$urls[ 'form-cf' ] = plugin_dir_url( __FILE__ ) . '/assets/caldera-forms.html';
	return $urls;
});

/**
 * Add translation strings to UI
 *
 * @since 0.0.2
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
 * @since 0.0.2
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
 * @since 0.0.2
 *
 * @param array $group Group config
 *
 * @return string
 */
function ingot_cf_cb( $group ){
	include_once __DIR__ . '/classes/render.php';

	$variant_id = init::get_variant( $group[ 'ID' ] );
	$ui = new ingot\addon\forms\cf\render( $group, $variant_id );
	return $ui->get_html();
}

/**
 * On presave set cookie tracking
 *
 * @since 0.0.2
 */
add_filter( 'ingot_crud_update', function( $data, $id, $what ){
	if( 'group' == $what ){
		$sub_type = group::sub_type( $data );
		if( 'form-cf' == $sub_type ){
			if( isset( $data[ 'meta' ][ 'cookie'  ]) ){

				if(  true == $data[ 'meta' ][ 'cookie'  ] && ! tracking::get_instance()->is_tracking( $id ) ) {
					tracking::get_instance()->add_to_tracking( $id );
					tracking::get_instance()->save();
				}elseif(  false == $data[ 'meta' ][ 'cookie'  ] && tracking::get_instance()->is_tracking( $id ) ) {
					tracking::get_instance()->remove_from_tracking( $id );
					tracking::get_instance()->save();
				}
			}
		}
	}

	return $data;
}, 10, 3);


/**
 * Setup cookies
 *
 * @since 0.0.2
 */
add_filter( 'ingot_loaded', function(){
	/** @TODO AUTOLOADER! */
	include_once __DIR__ . '/classes/cookies/cookie.php';
	include_once __DIR__ . '/classes/cookies/init.php';
	include_once __DIR__ . '/classes/cookies/tracking.php';
	
	init::check();

});

/**
 * Add CF licensing
 *
 * @since 1.0.0
 */
add_action( 'init', function(){

	$plugin = array(
		'name'		=>	'Ingot For Caldera Forms',
		'slug'		=>	'ingot-for-caldera-forms',
		'url'		=>	'https://calderawp.com/',
		'version'	=>	INGOT_CF_VER,
		'key_store'	=>  'ingot_cf_license',
		'file'		=>  INGOT_CF_CORE
	);

	new \calderawp\licensing_helper\licensing( $plugin );

});
