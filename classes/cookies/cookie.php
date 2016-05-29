<?php
/**
 * Add cookie option tracking for this type of test
 *
 * @package   ingot_cf
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */
namespace ingot\addon\forms\cf\cookies;


class cookie extends \ingot\testing\cookies\cookie {

	/**
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected static $type = 'form_cf';

}
