<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Password hash checking interface
 *
 * @package    Cerberus
 * @category   Hasher
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
interface Cerberus_Core_Hasher {

	/**
	 * Check if password matches a hash
	 *
	 * @param   string  Password to check
	 * @param   string  Hash to check against
	 * @return  bool
	 */
	public function check($password, $hash);
}