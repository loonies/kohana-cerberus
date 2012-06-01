<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Cerberus_Adapter_Database test
 *
 * @group  cerberus
 * @group  cerberus.adapter
 *
 * @package    Cerberus
 * @category   Tests
 * @author     Miodrag Tokić <mtokic@gmail.com>
 * @copyright  (c) 2011, Miodrag Tokić
 * @license    New BSD License
 */
class Cerberus_Adapter_DatabaseTest extends PHPUnit_Framework_TestCase {

	/**
	 * Test class signature
	 *
	 * @return  void
	 */
	public function test_Cerberus_Database_implements_Cerberus_Adapter()
	{
		if ( ! class_exists('Database_Query_Builder_Select'))
		{
			$this->markTestSkipped('Database module not enabled');
		}

		$adapter = new Cerberus_Adapter_Database(array('key' => 'foo'));

		$this->assertInstanceOf('Cerberus_Adapter', $adapter);
	}

	/**
	 * Test class signature
	 *
	 * @return  Cerberus_Adapter_Database
	 */
	public function test_identity_and_password_are_NULL_before_set()
	{
		if ( ! class_exists('Database_Query_Builder_Select'))
		{
			$this->markTestSkipped('Database module not enabled');
		}

		$adapter = new Cerberus_Adapter_Database(array('key' => 'foo'));

		$this->assertAttributeSame(NULL, '_identity', $adapter);
		$this->assertAttributeSame(NULL, '_password', $adapter);

		return $adapter;
	}

	/**
	 * @depends  test_identity_and_password_are_NULL_before_set
	 *
	 * @param   Cerberus_Adapter_Database
	 * @return  void
	 */
	public function test_credentials_sets_identity_and_password_and_returns_this(Cerberus_Adapter $adapter)
	{
		$adapter->credentials('itsme', 'GuessMe');

		$this->assertAttributeSame('itsme', '_identity', $adapter);
		$this->assertAttributeSame('GuessMe', '_password', $adapter);
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
	 * @covers  Cerberus_Adapter_Database::__construct
	 * @dataProvider provider_test_constructor_uses_settings_from_config
	 *
	 * @param   array   Passed config
	 * @param   array   Expected config
	 * @return  void
	 */
	public function test_constructor_uses_settings_from_config($config, $expected)
	{
		if ( ! class_exists('Database_Query_Builder_Select'))
		{
			$this->markTestSkipped('Database module not enabled');
		}

		$adapter = new Cerberus_Adapter_Database($config);

		foreach ($expected as $key => $value)
		{
			$this->assertAttributeSame($value, '_'.$key, $adapter);
		}
	}

	/**
	 * @covers  Cerberus_Adapter_Database::__construct
	 * @expectedException  Kohana_Exception
	 *
	 * @return  void
	 */
	public function test_throw_exception_if_secret_key_not_set()
	{
		new Cerberus_Adapter_Database(array());
	}

	/**
	 * @covers  Cerberus_Adapter_Database::__construct
	 *
	 * @return  void
	 */
	public function test_constructor_sets_Database_Query_Builder_Select_if_non_passed()
	{
		if ( ! class_exists('Database_Query_Builder_Select'))
		{
			$this->markTestSkipped('Database module not enabled');
		}

		$adapter = new Cerberus_Adapter_Database(array('key' => 'foo'));

		$this->assertAttributeInstanceOf('Database_Query_Builder_Select', '_query', $adapter);
	}

	/**
	 * @covers  Cerberus_Adapter_Database::__construct
	 *
	 * @return  void
	 */
	public function test_constructor_sets_Database_Query_Builder_Select_if_passed()
	{
		if ( ! class_exists('Database_Query_Builder_Select'))
		{
			$this->markTestSkipped('Database module not enabled');
		}

		$adapter = new Cerberus_Adapter_Database(array('key' => 'foo'), new Database_Query_Builder_Select);

		$this->assertAttributeInstanceOf('Database_Query_Builder_Select', '_query', $adapter);
	}

	/**
	 * @covers  Cerberus_Adapter_Database::query
	 *
	 * @return  array
	 */
	public function test_query()
	{
		$this->markTestIncomplete('@TODO');
	}

	/**
	 * Returns mocked Cerberus_Adapter_Database
	 *
	 * @return  Cerberus_Adapter_Database
	 */
	public function get_adapter_mock()
	{
		return $this->getMockBuilder('Cerberus_Adapter_Database')
			->disableOriginalConstructor()
			->setMethods(array('query', 'hash'))
			->getMock();
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_FAILURE_IDENTITY_NOT_FOUND()
	{
		$adapter = $this->get_adapter_mock();

		$adapter
			->expects($this->once())
			->method('query')
			->will($this->returnValue(FALSE));

		$result = $adapter->authenticate();

		$this->assertSame(Cerberus_Result::FAILURE_IDENTITY_NOT_FOUND, $result->code());
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_FAILURE_CREDENTIAL_INVALID()
	{
		$adapter = $this->get_adapter_mock();

		$adapter
			->expects($this->once())
			->method('query')
			->will($this->returnValue(array('password' => 'guessme')));

		$adapter
			->expects($this->once())
			->method('hash')
			->will($this->returnValue('cantguess'));

		$result = $adapter->authenticate();

		$this->assertSame(Cerberus_Result::FAILURE_CREDENTIAL_INVALID, $result->code());
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_SUCCESS()
	{
		$adapter = $this->get_adapter_mock();

		$adapter
			->expects($this->once())
			->method('query')
			->will($this->returnValue(array('password' => 'guessme')));

		$adapter
			->expects($this->once())
			->method('hash')
			->will($this->returnValue('guessme'));

		$result = $adapter->authenticate();

		$this->assertSame(Cerberus_Result::SUCCESS, $result->code());
	}

	/**
	 * @covers  Cerberus_Adapter_Database::authenticate
	 *
	 * @return  array
	 */
	public function test_authenticate_hashes_password()
	{
		$adapter = $this->get_adapter_mock();

		$adapter
			->expects($this->once())
			->method('query')
			->will($this->returnValue(array('password' => 'foobar')));

		$adapter
			->expects($this->once())
			->method('hash');

		$adapter->authenticate();
	}

	/**
	 * @covers  Cerberus_Adapter_Database::hash
	 *
	 * @return  array
	 */
	public function test_hash_with_specified_algorithm()
	{
		if ( ! class_exists('Database_Query_Builder_Select'))
		{
			$this->markTestSkipped('Database module not enabled');
		}

		$pass = 'guessme';
		$algo = 'md5';
		$key  = 'top-secret';

		$config = array(
			'key'       => $key,
			'algorithm' => $algo,
		);

		$adapter = new Cerberus_Adapter_Database($config);

		$this->assertSame(hash_hmac($algo, $pass, $key), $adapter->hash($pass));
	}
}
