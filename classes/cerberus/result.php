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
class Cerberus_Result {

	// Result codes
	const SUCCESS                    =  1;
	const FAILURE_GENERAL            =  0;
	const FAILURE_IDENTITY_NOT_FOUND = -1;
	const FAILURE_CREDENTIAL_INVALID = -2;

	/**
	 * @var  int  Authentication result code
	 */
	protected $_code;

	/**
	 * @var  mixed  The identity used in the authentication attempt
	 */
    protected $_identity;

	/**
	 * Creates a new Cerberus_Result instance
	 *
	 * @param   int     Authentication result code
	 * @param   mixed   The identity used in the authentication attempt
	 * @return  void
	 */
    public function __construct($code, $identity)
    {
		$this->_code     = $code;
		$this->_identity = $identity;
    }

	/**
	 * Returns whether the result represents a successful authentication attempt
	 *
	 * @return  bool
	 */
	public function is_valid()
	{
		return $this->_code > 0;
	}

	/**
	 * Get the result code for this authentication attempt
	 *
	 * @return  int
	 */
    public function code()
    {
        return $this->_code;
    }

	/**
	 * Returns the identity used in the authentication attempt
	 *
	 * @return  mixed
	 */
    public function identity()
    {
        return $this->_identity;
    }
}