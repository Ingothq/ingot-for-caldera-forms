<?php
/**
 * Setup cookie tracking for CF Forms
 *
 * @package   ingot_cf
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */

namespace ingot\addon\forms\cf\cookies;


use ingot\testing\bandit\content;
use ingot\testing\crud\group;

class init {
	/**
	 * Groups being tracked in cookies
	 *
	 * group_id => variant_id
	 *
	 * @since 0.0.2
	 *
	 * @var array
	 */
	protected static $tests;


	/**
	 * Get a variant from tests setup by this class
	 *
	 * @since 0.0.2
	 *
	 * @param int $group_id Group ID
	 *
	 * @return null|int
	 */
	public static function get_variant( $group_id ){
		if( ! empty( self::$tests ) && array_key_exists( $group_id, self::$tests  ) ) {
			return self::$tests[ $group_id ];
		}
	}

	/**
	 * Check cookies, setting if needed
	 *
	 * @since 0.0.2
	 */
	public static function check(){
		self::$tests = cookie::get_all_cookies();
		$groups = tracking::get_instance()->get_tracked();
		if( ! empty( $groups ) ){
			foreach ( $groups as $i => $group_id ){
				$group = group::read( $group_id );
				if( group::valid( $group ) ){
					self::check_cookie( $group_id, $i );
				}
			}
		}
	}

	/**
	 * Check one cookie's contents
	 *
	 * @since 0.0.2
	 *
	 * @param $group_id
	 * @param $i
	 */
	public static function check_cookie( $group_id, $i ){
		if( empty( self::$tests  ) || ! in_array( $group_id, self::$tests  ) ){
			$variant_id = self::choose( $group_id );
			cookie::set_cookie( $group_id, $variant_id );
			self::$tests[ $group_id ] = $variant_id;

		}else{
			$variant_id = cookie::get_cookie( $group_id );
			self::$tests[ $group_id ] = $variant_id;
		}

		unset( self::$tests[ $i ] );
	}

	/**
	 * Choose a variant
	 *
	 * @since 0.0.2
	 *
	 * @param $group_id
	 *
	 * @return mixed
	 */
	protected static function choose( $group_id ){
		$bandit = new content( $group_id );
		$variant = $bandit->choose();
		if( is_object( $variant ) && ! is_wp_error( $variant ) ){
			return $variant->getValue();
		}
	}



}
