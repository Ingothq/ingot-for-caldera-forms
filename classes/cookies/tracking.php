<?php
/**
 * @TODO What this does.
 *
 * @package   @TODO
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */

namespace ingot\addon\forms\cf\cookies;

class tracking extends \ingot\testing\cookies\tracking {


	protected  $option_key = '_ingot_cf_tracking';

	/**
	 * @var tracking
	 */
	protected static $instance;

	/**
	 *
	 * @return tracking
	 */
	public static function get_instance(){
		if( null === static::$instance ){
			static::$instance = new self();
		}

		return static::$instance;
	}
	

}
