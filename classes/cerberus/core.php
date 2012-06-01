<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A port of [Zend Framework](http://framework.zend.com/)
 * authentication component to Kohana
 *
 * @package    Cerberus
 * @category   Base
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    New BSD License
 */
class Cerberus_Core {

	/**
	 * @var  Cerberus_Storage
	 */
	protected $_storage = NULL;

	/**
	 * Creates a new Cerberus instance
	 *
	 * @param   Cerberus_Storage
	 * @return  void
	 */
	public function __construct(Cerberus_Storage $storage)
	{
		$this->_storage = $storage;
	}

	/**
	 * Authenticates against the supplied adapter
	 *
	 * @throws  Cerberus_Exception
	 * @param   Cerberus_Adapter
	 * @return  Ceberus_Result
	 */
	public function authenticate(Cerberus_Adapter $adapter)
	{
		$result = $adapter->authenticate();

		$this->_storage->clear();

		if ( ! $result->is_valid())
			throw new Cerberus_Exception($result);

		$this->_storage->write($result->identity());

		return $result;
	}

	/**
	 * Returns the identity from storage
	 *
	 * @return  mixed
	 */
	public function identity()
	{
		return $this->_storage->read();
	}

	/**
	 * Checks if storage is valid
	 *
	 * @return  bool
	 */
	public function is_valid()
	{
		return ! $this->_storage->is_empty();
	}

	/**
	 * Clears storage
	 *
	 * @return  void
	 */
	public function clear()
	{
		$this->_storage->clear();
	}
}