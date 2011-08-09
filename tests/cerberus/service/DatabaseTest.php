<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Test the Cerberus_Service_Database class
 *
 * @group  cerberus
 * @group  cerberus.service
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Service_DatabaseTest extends Unittest_TestCase {

	/**
	 * Test class signature
	 *
	 * @return  void
	 */
	public function test_Cerberus_Database_implements_Cerberus_Service()
	{
		$service = new Cerberus_Service_Database(array('key' => 'foo'));

		$this->assertInstanceOf('Cerberus_Service', $service);
	}

	/**
	 * Test class signature
	 *
	 * @return  Cerberus_Service_Database
	 */
	public function test_identity_and_password_are_NULL_before_set()
	{
		$service = new Cerberus_Service_Database(array('key' => 'foo'));

		$this->assertAttributeSame(NULL, '_identity', $service);
		$this->assertAttributeSame(NULL, '_password', $service);

		return $service;
	}

	/**
	 * @depends  test_identity_and_password_are_NULL_before_set
	 *
	 * @param   Cerberus_Service_Database
	 * @return  void
	 */
	public function test_credentials_sets_identity_and_password_and_returns_this(Cerberus_Service $service)
	{
		$service->credentials('itsme', 'GuessMe');

		$this->assertAttributeSame('itsme', '_identity', $service);
		$this->assertAttributeSame('GuessMe', '_password', $service);
	}

	/**
	 * Data provider for [test_constructor_uses_settings_from_config]
	 *
	 * @return  array
	 */
	public function provider_test_constructor_uses_settings_from_config()
	{
		return array(
			// #0
			array(
				array(
					'key'       => 'asdf',
				),
				array(
					'table'     => 'user',
					'columns'   => array('identity' => 'username', 'password' => 'password'),
					'algorithm' => 'sha256',
					'key'       => 'asdf',
				),
			),
			// #1
			array(
				array(
					'table'     => 'customers',
					'columns'   => array('identity' => 'email', 'password' => 'pincode'),
					'algorithm' => 'md5',
					'key'       => 'qwerty',
				),
				array(
					'table'     => 'customers',
					'columns'   => array('identity' => 'email', 'password' => 'pincode'),
					'algorithm' => 'md5',
					'key'       => 'qwerty',
				),
			),
		);
	}

	/**
	 * @covers  Cerberus_Service_Database::__construct
	 * @dataProvider provider_test_constructor_uses_settings_from_config
	 *
	 * @param   array   Passed config
	 * @param   array   Expected config
	 * @return  void
	 */
	public function test_constructor_uses_settings_from_config($config, $expected)
	{
		$service = new Cerberus_Service_Database($config);

		foreach ($expected as $key => $value)
		{
			$this->assertAttributeSame($value, '_'.$key, $service);
		}
	}

	/**
	 * @covers  Cerberus_Service_Database::__construct
	 * @expectedException  Kohana_Exception
	 *
	 * @return  void
	 */
	public function test_throw_exception_if_secret_key_not_set()
	{
		new Cerberus_Service_Database(array());
	}

	/**
	 * @covers  Cerberus_Service_Database::__construct
	 *
	 * @return  void
	 */
	public function test_constructor_sets_Database_Query_Builder_Select_if_non_passed()
	{
		$service = new Cerberus_Service_Database(array('key' => 'foo'));

		$this->assertAttributeInstanceOf('Database_Query_Builder_Select', '_query', $service);
	}

	/**
	 * @covers  Cerberus_Service_Database::__construct
	 *
	 * @return  void
	 */
	public function test_constructor_sets_Database_Query_Builder_Select_if_passed()
	{
		$service = new Cerberus_Service_Database(array('key' => 'foo'), new Database_Query_Builder_Select);

		$this->assertAttributeInstanceOf('Database_Query_Builder_Select', '_query', $service);
	}

	/**
	 * @covers  Cerberus_Service_Database::query
	 *
	 * @return  array
	 */
	public function test_query()
	{
		$this->markTestIncomplete('@TODO');
	}

	/**
	 * Returns mocked Cerberus_Service_Database
	 *
	 * @return  Cerberus_Service_Database
	 */
	public function get_service_mock()
	{
		return $this->getMockBuilder('Cerberus_Service_Database')
			->disableOriginalConstructor()
			->setMethods(array('query', 'hash'))
			->getMock();
	}

	/**
	 * @covers  Cerberus_Service_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_FAILURE_IDENTITY_NOT_FOUND()
	{
		$service = $this->get_service_mock();

		$service
			->expects($this->once())
			->method('query')
			->will($this->returnValue(FALSE));

		$result = $service->authenticate();

		$this->assertSame(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, $result->code());
	}

	/**
	 * @covers  Cerberus_Service_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_FAILURE_CREDENTIAL_INVALID()
	{
		$service = $this->get_service_mock();

		$service
			->expects($this->once())
			->method('query')
			->will($this->returnValue(array('password' => 'guessme')));

		$service
			->expects($this->once())
			->method('hash')
			->will($this->returnValue('cantguess'));

		$result = $service->authenticate();

		$this->assertSame(Cerberus_Result::FAILURE_CREDENTIAL_INVALID, $result->code());
	}

	/**
	 * @covers  Cerberus_Service_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_SUCCESS()
	{
		$service = $this->get_service_mock();

		$service
			->expects($this->once())
			->method('query')
			->will($this->returnValue(array('password' => 'guessme')));

		$service
			->expects($this->once())
			->method('hash')
			->will($this->returnValue('guessme'));

		$result = $service->authenticate();

		$this->assertSame(Cerberus_Result::SUCCESS, $result->code());
	}

	/**
	 * @covers  Cerberus_Service_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_hashes_password()
	{
		$service = $this->get_service_mock();

		$service
			->expects($this->once())
			->method('query')
			->will($this->returnValue(array('password' => 'foobar')));

		$service
			->expects($this->once())
			->method('hash');

		$service->authenticate();
	}

	/**
	 * @covers  Cerberus_Service_Database::hash
	 *
	 * @return  array
	 */
	public function test_hash_with_specified_algorithm()
	{
		$pass = 'guessme';
		$algo = 'md5';
		$key  = 'top-secret';

		$config = array(
			'key'       => $key,
			'algorithm' => $algo,
		);

		$service = new Cerberus_Service_Database($config);

		$this->assertSame(hash_hmac($algo, $pass, $key), $service->hash($pass));
	}
}
