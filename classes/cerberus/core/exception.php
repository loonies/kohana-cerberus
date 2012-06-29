<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Authentication exception
 *
 * @package    Cerberus
 * @category   Exception
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Core_Exception extends Kohana_Exception {

	/**
	 * @var  Cerberus_Result
	 */
	protected $result;

	/**
	 * Creates a new Cerberus_Exception instance
	 *
	 * @param   Cerberus_Result
	 * @param   string
	 * @param   array
	 * @param   int
	 * @return  void
	 */
	public function __construct(Cerberus_Result $result, $message = 'Authentication failed', array $variables = NULL, $code = Cerberus_Result::FAILURE_GENERAL)
	{
		$this->result = $result;

		parent::__construct($message, $variables, $result->code());
	}

	/**
	 * Returns a result from the authentication attempt
	 *
	 * @return  Cerberus_Result
	 */
	public function result()
	{
		return $this->result;
	}
}