<?php defined('SYSPATH') or die('No direct script access.');
/**
 * A port of [Zend Framework](http://framework.zend.com/)
 * authentication component to Kohana
 *
 * @package    Cerberus
 * @category   Exception
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Exception extends Kohana_Exception {

	/**
	 * @var  Cerberus_Result
	 */
	public $result;

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
}