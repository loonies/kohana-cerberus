<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus test
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
	 */
	public function test_it_sets_storage()
	{
		$storage = $this->getMock('Cerberus_Storage');

		$cerberus = new Cerberus($storage);

		$this->assertAttributeInstanceOf('Cerberus_Storage', 'storage', $cerberus);
	}

	/**
	 * @covers  Cerberus::authenticate
	 */
	public function test_it_writes_identity_to_storage_if_authentication_passes()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'itsme')));

		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('clear');

		$storage
			->expects($this->once())
			->method('write')
			->with($this->equalTo('itsme'));

		$cerberus = new Cerberus($storage);

		$cerberus->authenticate($adapter);
	}

	/**
	 * @covers  Cerberus::authenticate
	 */
	public function test_it_does_not_writes_identity_to_storage_if_authentication_passes()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::FAILURE_GENERAL, 'itsnotme')));

		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('clear');

		$storage
			->expects($this->never())
			->method('write');

		$cerberus = new Cerberus($storage);

		try
		{
			$cerberus->authenticate($adapter);

			$this->fail('Should throw Cerberus_Exception');
		}
		catch(Cerberus_Exception $e)
		{

		}
	}

	/**
	 * @covers  Cerberus::authenticate
	 */
	public function test_it_returns_authentication_result_if_authentication_passes()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'bar')));

		$storage = $this->getMock('Cerberus_Storage');

		$cerbeus = new Cerberus($storage);

		$this->assertInstanceOf('Cerberus_Result', $cerbeus->authenticate($adapter));
	}

	/**
	 * @covers  Cerberus::authenticate
	 *
	 * @expectedException      Cerberus_Exception
	 * @expectedExceptionCode  -1
	 */
	public function test_it_throws_exception_if_authentication_fails()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, 'itsnotme')));

		$storage = $this->getMock('Cerberus_Storage');

		$cerberus = new Cerberus($storage);

		$cerberus->authenticate($adapter);
	}

	/**
	 * @covers  Cerberus::identity
	 */
	public function test_it_returns_identity_from_successful_authentication_attempt()
	{
		$adapter = $this->getMock('Cerberus_Adapter');

		$adapter
			->expects($this->once())
			->method('authenticate')
			->will($this->returnValue(new Cerberus_Result(Cerberus_Result::SUCCESS, 'foo')));

		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('read')
			->will($this->returnValue('foo'));

		$cerberus = new Cerberus($storage);

		$cerberus->authenticate($adapter);

		$this->assertSame('foo', $cerberus->identity());
	}

	/**
	 * @covers  Cerberus::is_valid
	 */
	public function test_it_is_not_valid_when_storage_empty()
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
	 */
	public function test_it_is_valid_when_storage_not_empty()
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
	 */
	public function test_it_clears_storage()
	{
		$storage = $this->getMock('Cerberus_Storage');

		$storage
			->expects($this->once())
			->method('clear');

		$cerberus = new Cerberus($storage);

		$cerberus->clear();
	}
}
