<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Authentication adapter interface
 *
 * @package    Cerberus
 * @category   Adapter
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    New BSD License
 */
interface Cerberus_Core_Adapter {

	/**
	 * Performs an authentication attempt
	 *
	 * @return  Cerberus_Result
	 */
	public function authenticate();
}