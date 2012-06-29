<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Exception test
 *
 * @group  cerberus
 * @group  cerberus.exception
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2012, Miodrag Tokić
 */
class Cerberus_ExceptionTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers  Cerberus_Exception::__construct
	 * @covers  Cerberus_Exception::result
	 */
	public function test_it_returns_authentication_result()
	{
		$result = new Cerberus_Result(Cerberus_Result::FAILURE_CREDENTIAL_INVALID, 'foo');

		$e = new Cerberus_Exception($result);

		$this->assertSame($result, $e->result());
	}
}
