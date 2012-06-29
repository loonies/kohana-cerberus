<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Storage interface
 *
 * @package    Cerberus
 * @category   Storage
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    New BSD License
 */
interface Cerberus_Core_Storage {

	/**
	 * Returns TRUE if storage is empty
	 *
	 * @return  bool
	 */
	public function is_empty();

	/**
	 * Returns the content of storage
	 *
	 * @return  mixed
	 */
	public function read();

	/**
	 * Writes content to storage
	 *
	 * @param   mixed
	 * @return  void
	 */
	public function write($content);

	/**
	 * Clears the storage
	 *
	 * @return  void
	 */
	public function clear();
}
