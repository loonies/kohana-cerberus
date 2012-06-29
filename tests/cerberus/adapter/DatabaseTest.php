<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Cerberus_Adapter_Database test
 *
 * @group  cerberus
 * @group  cerberus.adapter
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011-2012, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Adapter_DatabaseTest extends PHPUnit_Framework_TestCase {

	/**
	 * Provider for [test_it_returns_authentication_result]
	 *
	 * @return  array
	 */
	public function provider_it_returns_authentication_result()
	{
		return array(
			array(NULL, NULL, Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND),
			array('SoMeHaSh', FALSE, Cerberus_Result::FAILURE_CREDENTIAL_INVALID),
			array('SoMeHaSh', 'StrangeThing', Cerberus_Result::FAILURE_GENERAL),
			array('SoMeHaSh', TRUE, Cerberus_Result::SUCCESS),
		);
	}

	public function test_it_implements_adapter_interface()
	{
		$query  = $this->getMockForAbstractClass('Cerberus_Adapter_Database_Query');
		$hasher = $this->getMock('Cerberus_Hasher');

		$this->assertInstanceOf('Cerberus_Adapter', new Cerberus_Adapter_Database($query, $hasher));
	}

	public function test_it_has_empty_credentials_initialy()
	{
		$query  = $this->getMockForAbstractClass('Cerberus_Adapter_Database_Query');
		$hasher = $this->getMock('Cerberus_Hasher');

		$adapter = new Cerberus_Adapter_Database($query, $hasher);

		$this->assertAttributeEmpty('identity', $adapter);
		$this->assertAttributeEmpty('password', $adapter);
	}

	/**
	 * @covers  Cerberus_Adapter_Database::credentials
	 * @covers  Cerberus_Adapter_Database::authenticate
	 */
	public function test_it_authenticates_with_provided_credentials()
	{
		$query = $this->getMockForAbstractClass('Cerberus_Adapter_Database_Query');

		$query
			->expects($this->once())
			->method('find')
			->with($this->equalTo('user'))
			->will($this->returnValue('IAmHash'));

		$hasher = $this->getMock('Cerberus_Hasher');

		$hasher
			->expects($this->once())
			->method('check')
			->with($this->equalTo('pass'), $this->equalTo('IAmHash'))
			->will($this->returnValue(TRUE));

		$adapter = new Cerberus_Adapter_Database($query, $hasher);

		$adapter->credentials('user', 'pass');

		$adapter->authenticate();
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 */
	public function test_it_doesnt_hash_password_if_user_not_found()
	{
		$query = $this->getMockForAbstractClass('Cerberus_Adapter_Database_Query');

		$query
			->expects($this->once())
			->method('find')
			->will($this->returnValue(NULL));

		$hasher = $this->getMock('Cerberus_Hasher');

		$hasher
			->expects($this->never())
			->method('check');

		$adapter = new Cerberus_Adapter_Database($query, $hasher);

		$result = $adapter->authenticate();

		$this->assertSame(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, $result->code());
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 *
	 * @dataProvider  provider_it_returns_authentication_result
	 *
	 * @param   mixed   Hashed password from a database
	 * @param   mixed   Result of password verification
	 * @param   int     Expected result code
	 */
	public function test_it_returns_authentication_result($hash, $verification, $code)
	{
		$query = $this->getMockForAbstractClass('Cerberus_Adapter_Database_Query');

		$query
			->expects($this->once())
			->method('find')
			->will($this->returnValue($hash));

		$hasher = $this->getMock('Cerberus_Hasher');

		$hasher
			->expects($this->any())
			->method('check')
			->will($this->returnValue($verification));

		$adapter = new Cerberus_Adapter_Database($query, $hasher);

		$result = $adapter->authenticate();

		$this->assertSame($code, $result->code());
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 */
	public function test_it_gets_data_by_querying_database()
	{
		$query = $this->getMockForAbstractClass('Cerberus_Adapter_Database_Query');

		$query
			->expects($this->once())
			->method('find');

		$hasher = $this->getMock('Cerberus_Hasher');

		$adapter = new Cerberus_Adapter_Database($query, $hasher);

		$adapter->authenticate();
	}
}