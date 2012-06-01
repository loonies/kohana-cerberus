<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test the Cerberus_Exception class
 *
 * @group  cerberus
 * @group  cerberus.base
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Base_CerberusTest extends PHPUnit_Framework_TestCase {

	/**
	 * @covers  Cerberus::__construct
	 *
	 * @return  void
	 */
	public function test_constructor_gets_session()
	{
		$session = $this->getMock('Cerberus_Session');

		$cerberus = new Cerberus($session);

		$this->assertAttributeInstanceOf('Cerberus_Session', '_session', $cerberus);
	}

	/**
	 * @covers  Cerberus::authenticate
	 * @expectedException  Cerberus_Exception
	 *
	 * @return  void
	 */
	public function test_authentication_failure_throws_exception()
	{
		$service = $this->getMock('Cerberus_Service');

		$service
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::FAILURE_GENERAL, 'itsnotme')));

		$session = $this->getMock('Cerberus_Session');

		$cerberus = new Cerberus($session);

		$cerberus->authenticate($service);
	}

	/**
	 * @covers  Cerberus::authenticate
	 *
	 * @return  void
	 */
	public function test_successful_authentication_writes_to_session()
	{
		$service = $this->getMock('Cerberus_Service');

		$service
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'itsme')));

		$session = $this->getMock('Cerberus_Session');

		$session
			->expects($this->once())
			->method('write');

		$cerberus = new Cerberus($session);

		$cerberus->authenticate($service);
	}

	/**
	 * @covers  Cerberus::authenticate
	 *
	 * @return  void
	 */
	public function test_successful_authentication_returns_result_obj()
	{
		$service = $this->getMock('Cerberus_Service');

		$service
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'itsme')));

		$session = $this->getMock('Cerberus_Session');

		$cerbeus = new Cerberus($session);

		$this->assertInstanceOf('Cerberus_Result', $cerbeus->authenticate($service));
	}

	/**
	 * @covers  Cerberus::is_valid
	 *
	 * @return  void
	 */
	public function test_is_not_valid_when_session_empty()
	{
		$session = $this->getMock('Cerberus_Session');

		$session
			->expects($this->once())
			->method('is_empty')
			->will($this->returnValue(TRUE));

		$cerbeus = new Cerberus($session);

		$this->assertFalse($cerbeus->is_valid());
	}

	/**
	 * @covers  Cerberus::is_valid
	 *
	 * @return  void
	 */
	public function test_is_valid_when_session_not_empty()
	{
		$session = $this->getMock('Cerberus_Session');

		$session
			->expects($this->once())
			->method('is_empty')
			->will($this->returnValue(FALSE));

		$cerbeus = new Cerberus($session);

		$this->assertTrue($cerbeus->is_valid());
	}

	/**
	 * @covers  Cerberus::clear
	 *
	 * @return  void
	 */
	public function test_clear_will_clear_session()
	{
		$session = $this->getMock('Cerberus_Session');

		$session
			->expects($this->once())
			->method('clear');

		$cerberus = new Cerberus($session);

		$cerberus->clear();
	}
}
