<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A port of [Zend Framework](http://framework.zend.com/)
 * authentication component to Kohana
 *
 * @package    Cerberus
 * @category   Session
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Cerberus_Session {

	/**
	 * Returns TRUE if session is empty
	 *
	 * @return  bool
	 */
	public function is_empty();

	/**
	 * Returns the content of session
	 *
	 * @return  mixed
	 */
	public function read();

	/**
	 * Writes content to session
	 *
	 * @param   mixed
	 * @return  void
	 */
	public function write($content);

	/**
	 * Clears the session
	 *
	 * @return  void
	 */
	public function clear();
}
