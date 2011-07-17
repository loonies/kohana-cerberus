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
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Cerberus_Core {

	/**
	 * @var  Cerberus_Session
	 */
	protected $_session = NULL;

	/**
	 * Creates a new Cerberus instance
	 *
	 * @param   Cerberus_Session
	 * @return  void
	 */
	public function __construct(Cerberus_Session $session)
	{
		$this->_session = $session;
	}

	/**
	 * Authenticates against the supplied service
	 *
	 * @throws  Cerberus_Exception
	 * @param   Cerberus_Service
	 * @return  Ceberus_Result
	 */
	public function authenticate(Cerberus_Service $service)
	{
		$result = $service->authenticate();

		$this->_session->clear();

		if ( ! $result->is_valid())
			throw new Cerberus_Exception($result);

		$this->_session->write($result->identity());

		return $result;
	}

	/**
	 * Returns the identity from session
	 *
	 * @return  mixed
	 */
	public function identity()
	{
		return $this->_session->read();
	}

	/**
	 * Checks if session is valid
	 *
	 * @return  bool
	 */
	public function is_valid()
	{
		return ! $this->_session->is_empty();
	}

	/**
	 * Clears session
	 *
	 * @return  void
	 */
	public function clear()
	{
		$this->_session->clear();
	}
}