<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Exception test
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
	public function test_constructor_gets_storage()
	{
		$storage = $this->getMock('Cerberus_Storage');

		$cerberus = new Cerberus($storage);

		$this->assertAttributeInstanceOf('Cerberus_Storage', 'storage', $cerberus);
	}

	/**
	 * @covers  Cerberus::authenticate
	 * @expectedException  Cerberus_Exception
	 *
	 * @return  void
	 */
	public function test_authentication_failure_throws_exception()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::FAILURE_GENERAL, 'itsnotme')));

		$storage = $this->getMock('Cerberus_Storage');

		$cerberus = new Cerberus($storage);

		$cerberus->authenticate($adapter);
	}

	/**
	 * @covers  Cerberus::authenticate
	 *
	 * @return  void
	 */
	public function test_successful_authentication_writes_to_storage()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'itsme')));

		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('write');

		$cerberus = new Cerberus($storage);

		$cerberus->authenticate($adapter);
	}

	/**
	 * @covers  Cerberus::authenticate
	 *
	 * @return  void
	 */
	public function test_successful_authentication_returns_result_obj()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'itsme')));

		$storage = $this->getMock('Cerberus_Storage');

		$cerbeus = new Cerberus($storage);

		$this->assertInstanceOf('Cerberus_Result', $cerbeus->authenticate($adapter));
	}

	/**
	 * @covers  Cerberus::is_valid
	 *
	 * @return  void
	 */
	public function test_is_not_valid_when_storage_empty()
	{
		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('is_empty')
			->will($this->returnValue(TRUE));

		$cerbeus = new Cerberus($storage);

		$this->assertFalse($cerbeus->is_valid());
	}

	/**
	 * @covers  Cerberus::is_valid
	 *
	 * @return  void
	 */
	public function test_is_valid_when_storage_not_empty()
	{
		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('is_empty')
			->will($this->returnValue(FALSE));

		$cerbeus = new Cerberus($storage);

		$this->assertTrue($cerbeus->is_valid());
	}

	/**
	 * @covers  Cerberus::clear
	 *
	 * @return  void
	 */
	public function test_clear_will_clear_storage()
	{
		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('clear');

		$cerberus = new Cerberus($storage);

		$cerberus->clear();
	}
}
